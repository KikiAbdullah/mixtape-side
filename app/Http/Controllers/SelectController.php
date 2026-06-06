<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;
use App\Models\Label;
use App\Models\Organizer;
use App\Models\Release;
use App\Models\User;
use DB;

class SelectController extends Controller
{
    public function bands(Request $request)
    {
        DB::connection()->disableQueryLog();
        $query = Band::where("name", 'LIKE', "%" . ($request->q ?? '') . "%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $jml = $query->count();
        $pagination = $this->selectPaginationAttr($request, $jml);

        $data = $query->offset($pagination['offset'])
            ->limit($pagination['paginate'])
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'text'  => $item->name,
                ];
            });

        return [
            "results"       => $data,
            "pagination"    => [
                "more"  => $pagination['more'],
            ]
        ];
    }

    public function labels(Request $request)
    {
        DB::connection()->disableQueryLog();
        $query = Label::where("name", 'LIKE', "%" . ($request->q ?? '') . "%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $jml = $query->count();
        $pagination = $this->selectPaginationAttr($request, $jml);

        $data = $query->offset($pagination['offset'])
            ->limit($pagination['paginate'])
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'text'  => $item->name,
                ];
            });

        return [
            "results"       => $data,
            "pagination"    => [
                "more"  => $pagination['more'],
            ]
        ];
    }

    public function organizers(Request $request)
    {
        DB::connection()->disableQueryLog();
        $query = Organizer::where("name", 'LIKE', "%" . ($request->q ?? '') . "%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $jml = $query->count();
        $pagination = $this->selectPaginationAttr($request, $jml);

        $data = $query->offset($pagination['offset'])
            ->limit($pagination['paginate'])
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'text'  => $item->name,
                ];
            });

        return [
            "results"       => $data,
            "pagination"    => [
                "more"  => $pagination['more'],
            ]
        ];
    }

    public function releases(Request $request)
    {
        DB::connection()->disableQueryLog();
        $query = Release::where("title", 'LIKE', "%" . ($request->q ?? '') . "%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $jml = $query->count();
        $pagination = $this->selectPaginationAttr($request, $jml);

        $data = $query->offset($pagination['offset'])
            ->limit($pagination['paginate'])
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'text'  => $item->title,
                ];
            });

        return [
            "results"       => $data,
            "pagination"    => [
                "more"  => $pagination['more'],
            ]
        ];
    }

    public function roles(Request $request)
    {
        DB::connection()->disableQueryLog();
        $query = \Spatie\Permission\Models\Role::where("name", 'LIKE', "%" . ($request->q ?? '') . "%");

        $jml = $query->count();
        $pagination = $this->selectPaginationAttr($request, $jml);

        $data = $query->offset($pagination['offset'])
            ->limit($pagination['paginate'])
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'text'  => $item->name,
                ];
            });

        return [
            "results"       => $data,
            "pagination"    => [
                "more"  => $pagination['more'],
            ]
        ];
    }

    public function selectPaginationAttr($request, $jumlah_data, $paginate = 20)
    {
        $page       = $request->page ?? 1;
        $page--;
        $offset     = $page * $paginate;
        $more       = false;

        if ($jumlah_data > ($paginate + $offset)) {
            $more = true;
        }

        return [
            'offset'                => $offset,
            'paginate'              => $paginate,
            'more'                  => $more,
        ];
    }
}
