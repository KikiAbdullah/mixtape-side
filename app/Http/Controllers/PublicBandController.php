<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;
use App\Models\Gig;

use Illuminate\Routing\Controller;

class PublicBandController extends Controller
{
    public function index(Request $request)
    {
        $query = Band::query();

        // Filter by Genre
        if ($request->has('genre') && !empty($request->genre)) {
            $genre = $request->genre;
            $query->where(function($q) use ($genre) {
                if (is_array($genre)) {
                    foreach ($genre as $g) {
                        $q->orWhere('genre', 'LIKE', '%"' . $g . '"%')
                          ->orWhere('genre', 'LIKE', '%' . $g . '%');
                    }
                } else {
                    $q->where('genre', 'LIKE', '%"' . $genre . '"%')
                      ->where('genre', 'LIKE', '%' . $genre . '%');
                }
            });
        }

        // Filter by City
        if ($request->has('city') && !empty($request->city)) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        // Filter by Status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by formed year range
        if ($request->has('formed_from') && !empty($request->formed_from)) {
            $query->where('formed_year', '>=', $request->formed_from);
        }
        if ($request->has('formed_to') && !empty($request->formed_to)) {
            $query->where('formed_year', '<=', $request->formed_to);
        }

        $bands = $query->orderBy('name', 'asc')->paginate(12);

        $data = [
            'bands' => $bands,
            'title' => 'Bands Directory',
            'subtitle' => 'Jelajahi Band Lokal'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($bands));
        }

        return view('public.band.index', $data);
    }

    public function show(Request $request, $slug)
    {
        $band = Band::where('slug', $slug)->firstOrFail();

        // Get members
        $currentMembers = $band->members()->where('is_current', true)->get();
        $pastMembers = $band->members()->where('is_current', false)->get();

        // Get releases grouped by type
        $releases = $band->releases()->orderBy('original_release_year', 'desc')->get();
        $groupedReleases = $releases->groupBy('release_type');

        // Get gigs history (where they performed)
        $gigs = $band->gigs()->orderBy('date', 'desc')->get();
        $upcomingGigs = $gigs->where('date', '>=', date('Y-m-d'));
        $pastGigs = $gigs->where('date', '<', date('Y-m-d'));

        $data = [
            'band' => $band,
            'currentMembers' => $currentMembers,
            'pastMembers' => $pastMembers,
            'groupedReleases' => $groupedReleases,
            'upcomingGigs' => $upcomingGigs,
            'pastGigs' => $pastGigs,
            'title' => $band->name,
            'subtitle' => 'Profil Band Ensiklopedia'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($data));
        }

        return view('public.band.show', $data);
    }
}
