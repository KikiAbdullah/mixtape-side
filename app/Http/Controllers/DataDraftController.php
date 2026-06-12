<?php

namespace App\Http\Controllers;

use App\Models\DataDraft;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class DataDraftController extends Controller
{
    public function __construct(DataDraft $model)
    {
        $this->title    = 'Data Draft';
        $this->subtitle = 'Moderation Queue';
        $this->folder   = 'management';
        $this->model    = $model;
    }

    public function index()
    {
        $data = [
            'title'    => $this->title,
            'subtitle' => $this->subtitle,
            'url'      => [
                'get_data' => route('management.data-draft.get-data'),
            ]
        ];
        return view('management.data-draft.index')->with($data);
    }

    public function ajaxData()
    {
        $query = DataDraft::with('user')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addColumn('user_name', function ($data) {
                return $data->user->name ?? 'System';
            })
            ->addColumn('action_buttons', function ($data) {
                if ($data->status == 'Pending') {
                    return '<button class="btn btn-sm btn-success btnApprove" data-id="'.$data->id.'">Approve</button>
                            <button class="btn btn-sm btn-danger btnReject" data-id="'.$data->id.'">Reject</button>';
                }
                return '<span class="badge bg-label-secondary">'.$data->status.'</span>';
            })
            ->rawColumns(['action_buttons'])
            ->make(true);
    }

    public function approve($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                // 1. Approval Lock to prevent race conditions
                $draft = DataDraft::lockForUpdate()->findOrFail($id);

                if ($draft->status !== 'Pending' && $draft->status !== 'Under Review') {
                    return response()->json(['status' => false, 'msg' => 'Draft has already been processed.']);
                }

                // 2. Conflict Detection (Optimistic Locking)
                if ($draft->target_id) {
                    $liveModelClass = $this->getModelClass($draft->target_table);
                    $liveModel = $liveModelClass::find($draft->target_id);

                    if (!$liveModel) {
                        $draft->update(['status' => 'Expired', 'admin_notes' => 'Source data no longer exists.']);
                        return response()->json(['status' => false, 'msg' => 'Source data no longer exists. Draft marked as Expired.']);
                    }

                    // Compare current live data with snapshot at draft creation
                    $currentSnapshot = $liveModel->toArray();
                    // We only compare keys that were present in the original snapshot to ignore timestamps etc.
                    $keysToCompare = array_keys($draft->original_snapshot ?? []);
                    $filteredCurrent = array_intersect_key($currentSnapshot, array_flip($keysToCompare));

                    if ($draft->original_snapshot && $filteredCurrent != $draft->original_snapshot) {
                        return response()->json([
                            'status' => false, 
                            'msg' => 'Conflict detected: Live data has changed since this draft was created.',
                            'conflict' => true
                        ]);
                    }

                    // 3. Asset Lifecycle: Move files from drafts to production
                    $finalData = $this->handleAssetMovement($draft->proposed_data, $draft->target_table);

                    // 4. Apply Update
                    $liveModel->update($finalData);
                } else {
                    // Create New
                    $liveModelClass = $this->getModelClass($draft->target_table);
                    $finalData = $this->handleAssetMovement($draft->proposed_data, $draft->target_table);
                    $liveModelClass::create($finalData);
                }

                $draft->update([
                    'status' => 'Applied',
                    'reviewed_by' => auth()->id(),
                    'updated_at' => now()
                ]);

                // 5. Audit Trail
                UserLog::create([
                    'actor_id' => auth()->id(),
                    'action' => 'APPROVE_DRAFT',
                    'entity_type' => $draft->target_table,
                    'entity_id' => $draft->target_id,
                    'new_values' => $draft->proposed_data,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                return response()->json(['status' => true, 'msg' => 'Draft approved and applied successfully.']);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    private function getModelClass($table)
    {
        $map = [
            'bands' => \App\Models\Band::class,
            'releases' => \App\Models\Release::class,
            'labels' => \App\Models\Label::class,
            'gigs' => \App\Models\Gig::class,
            'organizers' => \App\Models\Organizer::class,
            'zines' => \App\Models\Zine::class,
        ];
        return $map[$table] ?? null;
    }

    private function handleAssetMovement($data, $table)
    {
        foreach ($data as $key => $value) {
            if (is_string($value) && strpos($value, 'drafts/') !== false) {
                $newName = str_replace('drafts/', "$table/", $value);
                if (\Storage::disk('public')->exists($value)) {
                    \Storage::disk('public')->move($value, $newName);
                    $data[$key] = $newName;
                }
            }
        }
        return $data;
    }

    public function reject(Request $request, $id)
    {
        $draft = DataDraft::findOrFail($id);
        $draft->update([
            'status' => 'Rejected',
            'admin_notes' => $request->notes
        ]);

        return response()->json(['status' => true, 'msg' => 'Draft rejected.']);
    }
}
