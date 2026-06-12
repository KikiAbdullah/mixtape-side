<?php

use Illuminate\Database\Seeder;
use App\Zine;
use App\ZineComment;
use App\Models\User;
use App\Models\Band;
use App\Models\Release;
use App\Models\Label;
use App\Models\Organizer;
use Illuminate\Support\Str;

class ZinesTableSeeder extends Seeder
{
    public function run()
    {
        $author = User::first() ?? User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);

        $zines = [
            [
                'title' => 'The Rise of Local Post-Punk',
                'content' => 'Exploring the dark melodies and rhythmic drive of the emerging post-punk scene in our city.',
                'status' => 'Published',
                'published_at' => now()->subDays(2),
                'banner_url' => 'https://images.unsplash.com/photo-1514525253361-bee8718a7439?q=80&w=1964&auto=format&fit=crop',
            ],
            [
                'title' => 'Essential DIY Recording Tips',
                'content' => 'How to capture that perfect raw sound in your bedroom without breaking the bank.',
                'status' => 'Published',
                'published_at' => now()->subDays(5),
                'banner_url' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=2070&auto=format&fit=crop',
            ],
            [
                'title' => 'Vinyl Culture in 2024',
                'content' => 'Why physical media still matters in the age of streaming.',
                'status' => 'Published',
                'published_at' => now()->subDays(10),
                'banner_url' => 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?q=80&w=2070&auto=format&fit=crop',
            ],
            [
                'title' => 'Behind the Lens: Gig Photography',
                'content' => 'Capturing the energy of live shows. Experienced photographers share their secrets.',
                'status' => 'Published',
                'published_at' => now()->subDays(15),
                'banner_url' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=2070&auto=format&fit=crop',
            ],
            [
                'title' => 'The Evolution of Electronic Music',
                'content' => 'Tracing the journey from analog synthesizers to modern digital production.',
                'status' => 'Published',
                'published_at' => now()->subDays(20),
                'banner_url' => 'https://images.unsplash.com/photo-1596462758529-67994806a6c2?q=80&w=2070&auto=format&fit=crop',
            ],
        ];

        foreach ($zines as $zineData) {
            $zine = Zine::create(array_merge($zineData, [
                'slug' => Str::slug($zineData['title']),
                'author_id' => $author->id,
            ]));

            // Add comments
            ZineComment::create([
                'zine_id' => $zine->id,
                'user_id' => $author->id,
                'comment' => 'This is a great article!'
            ]);
            ZineComment::create([
                'zine_id' => $zine->id,
                'user_id' => $author->id,
                'comment' => 'Very informative, thanks for sharing.'
            ]);
        }
    }
}
