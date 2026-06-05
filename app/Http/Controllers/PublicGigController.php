<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gig;

use Illuminate\Routing\Controller;

class PublicGigController extends Controller
{
    public function index(Request $request)
    {
        $query = Gig::with(['organizer', 'bands']);

        if ($request->has('city') && !empty($request->city)) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->where('date', '>=', $request->from_date);
        }

        $gigs = $query->orderBy('date', 'desc')->paginate(12);

        $data = [
            'gigs' => $gigs,
            'title' => 'Agenda Acara (Gigs)',
            'subtitle' => 'Arsip & Jadwal Panggung Skena Musik Lokal'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($gigs));
        }

        return view('public.gig.index', $data);
    }

    public function show(Request $request, $slug)
    {
        $gig = Gig::where('slug', $slug)
            ->with(['organizer', 'bands', 'labels'])
            ->firstOrFail();

        $data = [
            'gig' => $gig,
            'title' => $gig->title,
            'subtitle' => 'Detail Informasi Acara'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($data));
        }

        return view('public.gig.show', $data);
    }
}
