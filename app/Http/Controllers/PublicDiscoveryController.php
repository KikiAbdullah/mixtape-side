<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;
use App\Models\Release;
use App\Models\Label;
use App\Models\Gig;

use Illuminate\Routing\Controller;

class PublicDiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        
        $bands = collect();
        $releases = collect();
        $labels = collect();
        $gigs = collect();

        if (!empty($q)) {
            $bands = Band::where('name', 'LIKE', "%{$q}%")
                ->orWhere('city', 'LIKE', "%{$q}%")
                ->orWhere('biography', 'LIKE', "%{$q}%")
                ->take(10)->get();

            $releases = Release::where('title', 'LIKE', "%{$q}%")
                ->orWhere('description', 'LIKE', "%{$q}%")
                ->with('band')
                ->take(10)->get();

            $labels = Label::where('name', 'LIKE', "%{$q}%")
                ->orWhere('city', 'LIKE', "%{$q}%")
                ->orWhere('description', 'LIKE', "%{$q}%")
                ->take(10)->get();

            $gigs = Gig::where('title', 'LIKE', "%{$q}%")
                ->orWhere('venue_name', 'LIKE', "%{$q}%")
                ->orWhere('city', 'LIKE', "%{$q}%")
                ->take(10)->get();
        }

        $data = [
            'q' => $q,
            'bands' => $bands,
            'releases' => $releases,
            'labels' => $labels,
            'gigs' => $gigs,
            'title' => 'Pencarian Global',
            'subtitle' => 'Hasil pencarian untuk "' . $q . '"'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess([
                'bands' => $bands,
                'releases' => $releases,
                'labels' => $labels,
                'gigs' => $gigs
            ]));
        }

        return view('public.discovery', $data);
    }
}
