<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LabelController extends Controller
{
    public function __construct(Label $model)
    {
        $this->title            = 'Label';
        $this->subtitle         = 'Labels Management';
        $this->folder           = 'management';
        $this->relation         = ['releases.band', 'gigs'];
        $this->model            = $model;
        $this->withTrashed      = false;
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
        if (request()->hasFile('logo_file')) {
            $model->logo_url = 'storage/labels/logos/' . $this->saveFoto(request()->file('logo_file'), 'labels/logos');
        }
        if (request()->hasFile('banner_file')) {
            $model->banner_url = 'storage/labels/banners/' . $this->saveFoto(request()->file('banner_file'), 'labels/banners');
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
                return '<i class="ri-building-line ri-24px text-muted"></i>';
            })
            ->rawColumns(['logo_display'])
            ->make(true);
    }
}
