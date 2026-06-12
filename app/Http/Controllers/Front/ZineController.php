<?php

namespace App\Http\Controllers\Front;

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Zine;
use App\Models\ZineComment;
use Illuminate\Http\Request;

class ZineController extends Controller
{
    public function index(Request $request)
    {
        $zines = Zine::with('author')
            ->where('status', 'Published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Dummy data if empty
        if ($zines->isEmpty()) {
            $dummyAuthor = (object)['name' => 'Mixtape Editor'];
            $zines = collect([
                (object)[
                    'title' => 'The Rise of Local Post-Punk',
                    'slug' => 'rise-of-local-post-punk',
                    'thumbnail_url' => null,
                    'published_at' => now()->subDays(2),
                    'author' => $dummyAuthor,
                    'content' => 'Exploring the dark melodies and rhythmic drive of the emerging post-punk scene in our city. From underground basements to sold-out club shows.'
                ],
                (object)[
                    'title' => 'Essential DIY Recording Tips',
                    'slug' => 'essential-diy-recording-tips',
                    'thumbnail_url' => null,
                    'published_at' => now()->subDays(5),
                    'author' => $dummyAuthor,
                    'content' => 'How to capture that perfect raw sound in your bedroom without breaking the bank. A guide for independent musicians starting their journey.'
                ],
                (object)[
                    'title' => 'Vinyl Culture in 2024',
                    'slug' => 'vinyl-culture-2024',
                    'thumbnail_url' => null,
                    'published_at' => now()->subDays(10),
                    'author' => $dummyAuthor,
                    'content' => 'Why physical media still matters in the age of streaming. We talk to local record store owners about the resurgence of vinyl.'
                ],
                (object)[
                    'title' => 'Behind the Lens: Gig Photography',
                    'slug' => 'behind-the-lens-gig-photography',
                    'thumbnail_url' => null,
                    'published_at' => now()->subDays(15),
                    'author' => $dummyAuthor,
                    'content' => 'Capturing the energy of live shows. Experienced photographers share their secrets on lighting, timing, and getting the perfect shot.'
                ],
            ]);
            // Mock pagination for dummy data
            $zines = new \Illuminate\Pagination\LengthAwarePaginator($zines, $zines->count(), 12);
        }

        return view('public.zine.index', compact('zines'));
    }

    public function show($slug)
    {
        $zine = Zine::with(['author', 'comments.user', 'comments.replies.user', 'bands', 'releases', 'labels', 'organizers'])
            ->where('slug', $slug)
            ->where('status', 'Published')
            ->first();

        if (!$zine) {
            // Check for dummy data
            $dummies = [
                'rise-of-local-post-punk' => [
                    'is_dummy' => true,
                    'slug' => 'rise-of-local-post-punk',
                    'title' => 'The Rise of Local Post-Punk',
                    'author' => (object)['name' => 'Mixtape Editor'],
                    'published_at' => now()->subDays(2),
                    'content' => 'Exploring the dark melodies and rhythmic drive of the emerging post-punk scene in our city.',
                    'banner_url' => 'https://images.unsplash.com/photo-1514525253361-bee8718a7439?q=80&w=1964&auto=format&fit=crop',
                    'thumbnail_url' => null,
                    'comments' => collect([
                        (object)[
                            'user' => (object)['name' => 'PunkRockKid'],
                            'comment' => 'This is exactly what the scene needs right now. Great write up!',
                            'created_at' => now()->subHours(5)
                        ],
                        (object)[
                            'user' => (object)['name' => 'SynthLover'],
                            'comment' => 'Missing some mention of the electronic influence but still a solid read.',
                            'created_at' => now()->subDays(1)
                        ]
                    ]),
                    'bands' => collect([(object)['name' => 'The Void', 'slug' => 'the-void'], (object)['name' => 'Echoes', 'slug' => 'echoes']]),
                    'releases' => collect([]),
                    'labels' => collect([]),
                    'organizers' => collect([]),
                ],
                'essential-diy-recording-tips' => [
                    'is_dummy' => true,
                    'slug' => 'essential-diy-recording-tips',
                    'title' => 'Essential DIY Recording Tips',
                    'author' => (object)['name' => 'Audio Nerd'],
                    'published_at' => now()->subDays(5),
                    'content' => 'How to capture that perfect raw sound in your bedroom without breaking the bank.',
                    'banner_url' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=2070&auto=format&fit=crop',
                    'thumbnail_url' => null,
                    'comments' => collect([]),
                    'bands' => collect([]),
                    'releases' => collect([]),
                    'labels' => collect([]),
                    'organizers' => collect([]),
                ]
            ];

            if (isset($dummies[$slug])) {
                $zine = (object)$dummies[$slug];
            } else {
                abort(404);
            }
        }

        $relatedZines = Zine::where('status', 'Published')
            ->where('id', '!=', $zine->id ?? 0)
            ->limit(3)
            ->get();

        return view('public.zine.show', compact('zine', 'relatedZines'));
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
