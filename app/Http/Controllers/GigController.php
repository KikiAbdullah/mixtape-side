<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GigController extends Controller
{
    public function __construct(Gig $model)
    {
        $this->title            = 'Gig';
        $this->subtitle         = 'Gigs Management';
        $this->folder           = 'management';
        $this->relation         = ['organizer'];
        $this->model            = $model;
        $this->withTrashed      = false;
    }

    public function formData()
    {
        return [
            'list_organizer' => Organizer::orderBy('name')->pluck('name', 'id')
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
        if (request()->hasFile('poster_file')) {
            $model->poster_url = 'storage/gigs/posters/' . $this->saveFoto(request()->file('poster_file'), 'gigs/posters');
        }
        $model->save();
    }

    public function ajaxData()
    {
        $query = $this->model->with(['organizer']);

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        return DataTables::of($query)
            ->addColumn('poster_display', function ($data) {
                if ($data->poster_url) {
                    return '<img src="' . asset($data->poster_url) . '" height="30" class="rounded">';
                }
                return '<i class="ri-calendar-event-line ri-24px text-muted"></i>';
            })
            ->addColumn('organizer_name', function ($data) {
                return $data->organizer->name ?? 'Underground';
            })
            ->rawColumns(['poster_display'])
            ->make(true);
    }
}
