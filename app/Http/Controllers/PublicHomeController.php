<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Release;
use App\Models\Gig;
use App\Models\UserLog;
use App\Models\Band;
use App\Models\Label;

use Illuminate\Routing\Controller;

class PublicHomeController extends Controller
{
    public function index(Request $request)
    {
        $newReleases = Release::with('band')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $upcomingGigs = Gig::with(['organizer', 'bands'])
            ->where('date', '>=', date('Y-m-d'))
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();

        $popularLabels = Label::take(4)->get();

        $data = [
            'newReleases' => $newReleases,
            'upcomingGigs' => $upcomingGigs,
            'popularLabels' => $popularLabels,
            'bandCount' => Band::count(),
            'releaseCount' => Release::count(),
            'labelCount' => Label::count(),
            'title' => 'Home',
            'subtitle' => 'Platform Ensiklopedia & Arsip Musik Lokal'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($data));
        }

        return view('public.home', $data);
    }
}
