<?php

use App\Models\Band;
use App\Models\Release;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeatlesReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $band = Band::firstOrCreate(
            ['slug' => 'the-beatles'],
            [
                'name' => 'The Beatles',
                'city' => 'Liverpool',
                'country' => 'United Kingdom',
                'formed_year' => 1960,
                'status' => 'Split-up',
                'genre' => ['Rock', 'Pop', 'Psychedelic Rock'],
                'biography' => 'The Beatles were an English rock band formed in Liverpool in 1960. With a line-up comprising John Lennon, Paul McCartney, George Harrison and Ringo Starr, they are regarded as the most influential band of all time.',
            ]
        );

        Release::updateOrCreate(
            ['slug' => Str::slug('Sgt. Pepper\'s Lonely Hearts Club Band')],
            [
                'band_id' => $band->id,
                'title' => 'Sgt. Pepper\'s Lonely Hearts Club Band',
                'release_type' => 'Full-length',
                'cover_url' => 'https://www.indieground.net/images/blog/2024/indieblog-best-album-covers-60s-01.jpg',
                'original_release_year' => 1967,
                'description' => 'Sgt. Pepper\'s Lonely Hearts Club Band is the eighth studio album by the English rock band the Beatles.',
                'track_count' => 13
            ]
        );
    }
}
