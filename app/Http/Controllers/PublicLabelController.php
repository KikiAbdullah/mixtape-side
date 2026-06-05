<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Label;
use App\Models\Band;
use App\Models\Release;

use Illuminate\Routing\Controller;

class PublicLabelController extends Controller
{
    public function show(Request $request, $slug)
    {
        $label = Label::where('slug', $slug)->firstOrFail();

        // Roster bands (bands with releases under this label)
        $roster = Band::whereHas('releases.labels', function ($q) use ($label) {
            $q->where('labels.id', $label->id);
        })->get();

        // Catalog of releases (releases under this label)
        $catalog = Release::whereHas('labels', function ($q) use ($label) {
            $q->where('labels.id', $label->id);
        })->with('band')->orderBy('original_release_year', 'desc')->get();

        $data = [
            'label' => $label,
            'roster' => $roster,
            'catalog' => $catalog,
            'title' => $label->name,
            'subtitle' => 'Profil Record Label'
        ];

        if ($request->wantsJson()) {
            return response()->json(responseSuccess($data));
        }

        return view('public.label.show', $data);
    }
}
