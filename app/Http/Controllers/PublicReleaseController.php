<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;
use App\Models\Release;
use App\Models\Track;

use Illuminate\Routing\Controller;

class PublicReleaseController extends Controller
{
    public function index(Request $request)
    {
        $releases = Release::with('band')
            ->orderBy('original_release_year', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'releases' => $releases,
            'title' => 'All Releases',
            'subtitle' => 'Daftar semua rilisan musik'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($data));
        }

        return view('public.release.index', $data);
    }

    public function show(Request $request, $band_slug, $release_slug)
    {
        $band = Band::where('slug', $band_slug)->firstOrFail();
        
        $release = Release::where('band_id', $band->id)
            ->where('slug', $release_slug)
            ->with(['tracks.contributors', 'labels'])
            ->firstOrFail();

        $data = [
            'band' => $band,
            'release' => $release,
            'title' => $release->title . ' - ' . $band->name,
            'subtitle' => 'Detail Rilisan Musik'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($data));
        }

        return view('public.release.show', $data);
    }

    public function trackDetail($id)
    {
        $track = Track::with('contributors')->findOrFail($id);

        $view = view('public.release.partial_track_detail', compact('track'))->render();

        return response()->json([
            'status' => true,
            'view' => $view
        ]);
    }
}
