<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Models\Band;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReleaseController extends Controller
{
    public function __construct(Release $model)
    {
        $this->title            = 'Release';
        $this->subtitle         = 'Releases Management';
        $this->folder           = 'management';
        $this->relation         = ['band'];
        $this->model            = $model;
        $this->withTrashed      = false;
    }

    public function formData()
    {
        return [
            'list_band' => Band::orderBy('name')->pluck('name', 'id')
        ];
    }

    public function getRequest()
    {
        return request()->all();
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
        if (request()->hasFile('cover_file')) {
            $model->cover_url = 'storage/releases/covers/' . $this->saveFoto(request()->file('cover_file'), 'releases/covers');
        }
        $model->save();
    }

    public function ajaxData()
    {
        $query = $this->model->with(['band']);

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        return DataTables::of($query)
            ->addColumn('cover_display', function ($data) {
                if ($data->cover_url) {
                    return '<img src="' . asset($data->cover_url) . '" height="30" class="rounded">';
                }
                return '<i class="ri-disc-line ri-24px text-muted"></i>';
            })
            ->addColumn('band_name', function ($data) {
                return $data->band->name ?? '-';
            })
            ->rawColumns(['cover_display'])
            ->make(true);
    }
}
