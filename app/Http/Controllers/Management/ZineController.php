<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Zine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use DB;

class ZineController extends Controller
{
    public function __construct(Zine $model)
    {
        $this->title            = 'Zine';
        $this->subtitle         = 'Zine Management';
        $this->folder           = 'management';
        $this->model            = $model;
    }

    public function index(Request $request)
    {
        $data['title'] = $this->title;
        $data['subtitle'] = $this->subtitle;
        $data['url'] = [
            'edit' => 'management.zine.edit',
            'destroy' => 'management.zine.destroy',
            'store' => route('management.zine.store')
        ];
        return view($this->folder . '.zine.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:Draft,Published'
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['slug'] = Str::slug($request->title) . '-' . time();
            $data['author_id'] = auth()->id();
            
            if ($request->status == 'Published') {
                $data['published_at'] = now();
            }

            $zine = Zine::create($data);

            if ($request->hasFile('thumbnail_file')) {
                $zine->thumbnail_url = 'storage/zines/thumbnails/' . $this->saveFoto($request->file('thumbnail_file'), 'zines/thumbnails');
            }
            if ($request->hasFile('banner_file')) {
                $zine->banner_url = 'storage/zines/banners/' . $this->saveFoto($request->file('banner_file'), 'zines/banners');
            }
            $zine->save();

            // Tags
            if ($request->has('band_ids')) $zine->bands()->sync($request->band_ids);
            if ($request->has('release_ids')) $zine->releases()->sync($request->release_ids);
            if ($request->has('label_ids')) $zine->labels()->sync($request->label_ids);
            if ($request->has('organizer_ids')) $zine->organizers()->sync($request->organizer_ids);

            DB::commit();
            return response()->json(['status' => true, 'msg' => 'Article created successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, $id)
    {
        $data['item'] = Zine::with(['bands', 'releases', 'labels', 'organizers'])->findOrFail($id);
        $data['url'] = ['update' => 'management.zine.update'];
        $data['id'] = $id;
        
        $view = view($this->folder . '.zine.edit', $data)->render();
        return response()->json(['status' => true, 'view' => $view]);
    }

    public function update(Request $request, $id)
    {
        $zine = Zine::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:Draft,Published'
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            
            if ($zine->status == 'Draft' && $request->status == 'Published') {
                $data['published_at'] = now();
            }

            $zine->update($data);

            if ($request->hasFile('thumbnail_file')) {
                $zine->thumbnail_url = 'storage/zines/thumbnails/' . $this->saveFoto($request->file('thumbnail_file'), 'zines/thumbnails');
            }
            if ($request->hasFile('banner_file')) {
                $zine->banner_url = 'storage/zines/banners/' . $this->saveFoto($request->file('banner_file'), 'zines/banners');
            }
            $zine->save();

            // Tags
            $zine->bands()->sync($request->band_ids ?? []);
            $zine->releases()->sync($request->release_ids ?? []);
            $zine->labels()->sync($request->label_ids ?? []);
            $zine->organizers()->sync($request->organizer_ids ?? []);

            DB::commit();
            return response()->json(['status' => true, 'msg' => 'Article updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Zine::findOrFail($id)->delete();
        return response()->json(['status' => true, 'msg' => 'Article deleted successfully']);
    }

    public function ajaxData()
    {
        $query = Zine::query();
        return DataTables::of($query)
            ->addColumn('thumbnail_display', function ($data) {
                if ($data->thumbnail_url) {
                    return '<img src="' . asset($data->thumbnail_url) . '" height="30" class="rounded">';
                }
                return '<i class="ri-image-line ri-24px text-muted"></i>';
            })
            ->addColumn('status_badge', function ($data) {
                $class = $data->status == 'Published' ? 'success' : 'warning';
                return '<span class="badge bg-label-' . $class . '">' . $data->status . '</span>';
            })
            ->rawColumns(['thumbnail_display', 'status_badge'])
            ->make(true);
    }
}
