<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Zine;
use App\Models\ZineComment;
use Illuminate\Http\Request;

class ZineController extends Controller
{
    public function index()
    {
        $zines = Zine::with('author')
            ->where('status', 'Published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('public.zine.index', compact('zines'));
    }

    public function show($slug)
    {
        $zine = Zine::with(['author', 'comments.user', 'comments.replies.user', 'bands', 'releases', 'labels', 'organizers'])
            ->where('slug', $slug)
            ->where('status', 'Published')
            ->firstOrFail();

        return view('public.zine.show', compact('zine'));
    }

    public function comment(Request $request, $slug)
    {
        $zine = Zine::where('slug', $slug)->firstOrFail();

        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:zine_comments,id'
        ]);

        ZineComment::create([
            'zine_id' => $zine->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'parent_id' => $request->parent_id
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }
}
