<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;
use App\Models\Label;
use App\Models\Organizer;
use App\Models\User;

class SelectController extends Controller
{
    public function bands(Request $request)
    {
        $search = $request->q;
        $page = $request->page ?? 1;

        $query = Band::select('id', 'name as text')
            ->where('name', 'LIKE', "%$search%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $result = $query->paginate(10);

        return response()->json([
            'results' => $result->items(),
            'pagination' => ['more' => $result->hasMorePages()]
        ]);
    }

    public function labels(Request $request)
    {
        $search = $request->q;
        $query = Label::select('id', 'name as text')
            ->where('name', 'LIKE', "%$search%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $result = $query->paginate(10);

        return response()->json([
            'results' => $result->items(),
            'pagination' => ['more' => $result->hasMorePages()]
        ]);
    }

    public function organizers(Request $request)
    {
        $search = $request->q;
        $query = Organizer::select('id', 'name as text')
            ->where('name', 'LIKE', "%$search%");

        if (!auth()->user()->hasRole('SUPERADMIN') && !auth()->user()->hasRole('ADMIN')) {
            $query->where('created_by', auth()->id());
        }

        $result = $query->paginate(10);

        return response()->json([
            'results' => $result->items(),
            'pagination' => ['more' => $result->hasMorePages()]
        ]);
    }

    public function roles(Request $request)
    {
        $search = $request->q;
        $query = \Spatie\Permission\Models\Role::select('id', 'name as text')
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

        return response()->json([
            'results' => $query->items(),
            'pagination' => ['more' => $query->hasMorePages()]
        ]);
    }
}
