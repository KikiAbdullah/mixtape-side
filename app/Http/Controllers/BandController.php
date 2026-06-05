<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\BandRequest;
use DB;
use App\Helpers\LogHelper;

class BandController extends Controller
{
    public function __construct(Band $model)
    {
        $this->title            = 'Band';
        $this->subtitle         = 'Bands Management';
        $this->folder           = 'management';
        $this->relation         = [];
        $this->model            = $model;
        $this->withTrashed      = false;
    }

    public function getRequest()
    {
        $data = request()->all();
        
        // Handle Multiple Values (Comma Separated Strings to Array)
        if (isset($data['alternative_names'])) {
            $data['alternative_names'] = array_map('trim', explode(',', $data['alternative_names']));
        }
        if (isset($data['genre'])) {
            $data['genre'] = array_map('trim', explode(',', $data['genre']));
        }

        return $data;
    }

    public function customStore($data, $model)
    {
        $this->handleUploads($model);
    }

    public function customUpdate($data, $model)
    {
        $this->handleUploads($model);
    }

    private function handleUploads($model)
    {
        if (request()->hasFile('logo_file')) {
            $model->logo_url = 'storage/bands/logos/' . $this->saveFoto(request()->file('logo_file'), 'bands/logos');
        }
        if (request()->hasFile('photo_file')) {
            $model->photo_url = 'storage/bands/photos/' . $this->saveFoto(request()->file('photo_file'), 'bands/photos');
        }
        $model->save();
    }

    public function ajaxData()
    {
        $query = $this->model->query();

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        return DataTables::of($query)
            ->addColumn('logo_display', function ($data) {
                if ($data->logo_url) {
                    return '<img src="' . asset($data->logo_url) . '" height="30" class="rounded">';
                }
                return '<i class="ri-image-line ri-24px text-muted"></i>';
            })
            ->addColumn('status_badge', function ($data) {
                $class = $data->status == 'Active' ? 'success' : ($data->status == 'On Hold' ? 'warning' : 'danger');
                return '<span class="badge bg-label-' . $class . '">' . $data->status . '</span>';
            })
            ->rawColumns(['logo_display', 'status_badge'])
            ->make(true);
    }
}
