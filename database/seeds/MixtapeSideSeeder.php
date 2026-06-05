<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Band;
use App\Models\BandMember;
use App\Models\Label;
use App\Models\Release;
use App\Models\Track;
use App\Models\TrackContributor;
use App\Models\Organizer;
use App\Models\Gig;
use Illuminate\Support\Str;

class MixtapeSideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Fetch users created by UserSeeder
        $userSuper = User::where('username', 'superadmin')->first();
        $userCurator = User::where('username', 'curator')->first();
        $userOwner = User::where('username', 'band_owner')->first();
        $userContributor = User::where('username', 'contributor')->first();

        $allUsers = array_filter([$userSuper, $userCurator, $userOwner, $userContributor]);

        // 2. Seed Profiles for all users
        foreach ($allUsers as $user) {
            if ($user && !$user->profile) {
                Profile::create([
                    'user_id' => $user->id,
                    'display_name' => $user->name,
                    'location' => 'Indonesia',
                    'bio' => 'Archiving the noise since day one.'
                ]);
            }
        }

        // Fallback user ID for foreign keys
        $defaultOwnerId = $userOwner->id ?? 1;
        $defaultCuratorId = $userCurator->id ?? 1;
        $defaultSuperId = $userSuper->id ?? 1;

        // 3. Seed Labels
        $labels = [
            ['slug' => 'grimloc-records', 'name' => 'Grimloc Records', 'city' => 'Bandung', 'formed_year' => 2010, 'status' => 'Active', 'description' => 'Independent hip-hop and hardcore label based in Bandung.'],
            ['slug' => 'demajors', 'name' => 'Demajors', 'city' => 'Jakarta', 'formed_year' => 2000, 'status' => 'Active', 'description' => 'One of the biggest independent record labels in Indonesia.'],
            ['slug' => 'disaster-records', 'name' => 'Disaster Records', 'city' => 'Bandung', 'formed_year' => 2012, 'status' => 'Active', 'description' => 'Record label focusing on metal, hardcore, and punk.'],
            ['slug' => 'blackandje-records', 'name' => 'Blackandje Records', 'city' => 'Jakarta', 'formed_year' => 2007, 'status' => 'Active', 'description' => 'Indonesian metal & extreme music label.'],
            ['slug' => 'majemuk-records', 'name' => 'Majemuk Records', 'city' => 'Malang', 'formed_year' => 2015, 'status' => 'Active', 'description' => 'Independent label documenting the East Java underground scene.']
        ];

        foreach ($labels as $lbl) {
            Label::firstOrCreate(
                ['slug' => $lbl['slug']],
                array_merge($lbl, ['owner_id' => $defaultSuperId])
            );
        }

        // 4. Seed Bands
        $bands = [
            ['slug' => 'burgerkill', 'name' => 'Burgerkill', 'city' => 'Bandung', 'formed_year' => 1995, 'genre' => ['Metalcore', 'Death Metal'], 'biography' => 'Pioneers of the Indonesian metal scene.'],
            ['slug' => 'seringai', 'name' => 'Seringai', 'city' => 'Jakarta', 'formed_year' => 2002, 'genre' => ['High Octane Rock', 'Stoner Metal'], 'biography' => 'High octane rock heavily influenced by Black Sabbath and Motorhead.'],
            ['slug' => 'deadsquad', 'name' => 'DeadSquad', 'city' => 'Jakarta', 'formed_year' => 2006, 'genre' => ['Technical Death Metal'], 'biography' => 'Renowned for their extreme technicality and aggressive sound.'],
            ['slug' => 'fraud', 'name' => 'Fraud', 'city' => 'Surabaya', 'formed_year' => 2010, 'genre' => ['Beatdown Hardcore'], 'biography' => 'Heavy beatdown hardcore unit from the heat of Surabaya.'],
            ['slug' => 'extreme-decay', 'name' => 'Extreme Decay', 'city' => 'Malang', 'formed_year' => 1998, 'genre' => ['Grindcore'], 'biography' => 'Legendary grindcore machine from Malang, East Java.'],
            ['slug' => 'navicula', 'name' => 'Navicula', 'city' => 'Denpasar', 'formed_year' => 1996, 'genre' => ['Grunge', 'Psychedelic Rock'], 'biography' => 'The green grunge gentlemen of Bali.'],
            ['slug' => 'the-panturas', 'name' => 'The Panturas', 'city' => 'Jatinangor', 'formed_year' => 2015, 'genre' => ['Surf Rock'], 'biography' => 'Contemporary surf rock bringing coastal vibes to the mountains.'],
            ['slug' => 'jasad', 'name' => 'Jasad', 'city' => 'Bandung', 'formed_year' => 1990, 'genre' => ['Brutal Death Metal'], 'biography' => 'Sundanese brutal death metal veterans.']
        ];

        $bandModels = [];
        foreach ($bands as $bnd) {
            $bandModels[$bnd['slug']] = Band::firstOrCreate(
                ['slug' => $bnd['slug']],
                array_merge($bnd, ['country' => 'Indonesia', 'status' => 'Active', 'owner_id' => $defaultOwnerId])
            );
        }

        // 4.5. Seed Band Members
        $bandMembers = [
            // Burgerkill
            ['band_slug' => 'burgerkill', 'name' => 'Aries Tanto (Eben)', 'role' => 'Guitars', 'join_year' => 1995, 'leave_year' => 2021, 'is_current' => false],
            ['band_slug' => 'burgerkill', 'name' => 'Agung Hellfrog', 'role' => 'Guitars', 'join_year' => 2003, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'burgerkill', 'name' => 'Ramdan Agustiana', 'role' => 'Bass', 'join_year' => 2007, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'burgerkill', 'name' => 'Putra Pra Ramadhan', 'role' => 'Drums', 'join_year' => 2016, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'burgerkill', 'name' => 'Ronald Alexander', 'role' => 'Vocals', 'join_year' => 2021, 'leave_year' => null, 'is_current' => true],

            // Seringai
            ['band_slug' => 'seringai', 'name' => 'Arian13', 'role' => 'Vocals', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'seringai', 'name' => 'Ricky Siahaan', 'role' => 'Guitars', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'seringai', 'name' => 'Sammy Bramantyo', 'role' => 'Bass', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'seringai', 'name' => 'Edy Khemod', 'role' => 'Drums', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],

            // DeadSquad
            ['band_slug' => 'deadsquad', 'name' => 'Stevi Item', 'role' => 'Guitars', 'join_year' => 2006, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'deadsquad', 'name' => 'Karis', 'role' => 'Guitars', 'join_year' => 2015, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'deadsquad', 'name' => 'Vicky Mono', 'role' => 'Vocals', 'join_year' => 2022, 'leave_year' => null, 'is_current' => true],

            // The Panturas
            ['band_slug' => 'the-panturas', 'name' => 'Abyan Nabilio', 'role' => 'Vocals, Guitars', 'join_year' => 2015, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-panturas', 'name' => 'Rizal Tofik', 'role' => 'Guitars', 'join_year' => 2015, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-panturas', 'name' => 'Bagus Patria', 'role' => 'Bass', 'join_year' => 2015, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-panturas', 'name' => 'Surya Fikri', 'role' => 'Drums', 'join_year' => 2015, 'leave_year' => null, 'is_current' => true],

            // Jasad
            ['band_slug' => 'jasad', 'name' => 'Man Jasad', 'role' => 'Vocals', 'join_year' => 2000, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'jasad', 'name' => 'Ferly', 'role' => 'Guitars', 'join_year' => 2001, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'jasad', 'name' => 'Yuli', 'role' => 'Bass', 'join_year' => 1990, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'jasad', 'name' => 'Oki', 'role' => 'Drums', 'join_year' => 2016, 'leave_year' => null, 'is_current' => true],
        ];

        foreach ($bandMembers as $member) {
            if (isset($bandModels[$member['band_slug']])) {
                BandMember::firstOrCreate(
                    [
                        'band_id' => $bandModels[$member['band_slug']]->id,
                        'name' => $member['name']
                    ],
                    [
                        'role_instrument' => $member['role'],
                        'join_year' => $member['join_year'],
                        'leave_year' => $member['leave_year'],
                        'is_current' => $member['is_current'],
                        'linked_user_id' => null // Bisa di-mapping jika ada user yg terhubung
                    ]
                );
            }
        }

        // 5. Seed Releases
        $releases = [
            ['slug' => 'beyond-the-self', 'band_slug' => 'burgerkill', 'title' => 'Beyond the Self', 'year' => 2006, 'type' => 'Full-length', 'track_count' => 10],
            ['slug' => 'venomous', 'band_slug' => 'burgerkill', 'title' => 'Venomous', 'year' => 2011, 'type' => 'Full-length', 'track_count' => 9],
            ['slug' => 'serigala-militia', 'band_slug' => 'seringai', 'title' => 'Serigala Militia', 'year' => 2007, 'type' => 'Full-length', 'track_count' => 11],
            ['slug' => 'horror-vision', 'band_slug' => 'deadsquad', 'title' => 'Horror Vision', 'year' => 2009, 'type' => 'Full-length', 'track_count' => 8],
            ['slug' => 'sanctuary', 'band_slug' => 'fraud', 'title' => 'Sanctuary', 'year' => 2020, 'type' => 'Full-length', 'track_count' => 10],
            ['slug' => 'downfall-of-a-god-complex', 'band_slug' => 'extreme-decay', 'title' => 'Downfall Of A God Complex', 'year' => 2021, 'type' => 'Full-length', 'track_count' => 14],
            ['slug' => 'bumi-dan-surganya', 'band_slug' => 'navicula', 'title' => 'Bumi dan Surganya', 'year' => 2018, 'type' => 'Full-length', 'track_count' => 11],
            ['slug' => 'ombak-banyu-asmara', 'band_slug' => 'the-panturas', 'title' => 'Ombak Banyu Asmara', 'year' => 2021, 'type' => 'Full-length', 'track_count' => 10],
        ];

        foreach ($releases as $rel) {
            if (isset($bandModels[$rel['band_slug']])) {
                Release::updateOrCreate(
                    ['slug' => $rel['slug']],
                    [
                        'band_id' => $bandModels[$rel['band_slug']]->id,
                        'title' => $rel['title'],
                        'release_type' => $rel['type'],
                        'original_release_year' => $rel['year'],
                        'track_count' => $rel['track_count']
                    ]
                );
            }
        }

        // 6. Seed Organizers
        $organizers = [
            ['slug' => 'atap-promotions', 'name' => 'Atap Promotions', 'city' => 'Bandung', 'description' => 'Boutique music promoter and mastermind behind Bandung Berisik.'],
            ['slug' => 'ravel-entertainment', 'name' => 'Ravel Entertainment', 'city' => 'Jakarta', 'description' => 'The engine behind Hammersonic Festival.'],
            ['slug' => 'demajors-festival', 'name' => 'Demajors & Dyandra', 'city' => 'Jakarta', 'description' => 'Promoters for Synchronize Fest.'],
            ['slug' => 'kebun-raya-id', 'name' => 'Kebun Raya ID', 'city' => 'Bogor', 'description' => 'Organizers focusing on intimate outdoor nature concerts.']
        ];

        $orgModels = [];
        foreach ($organizers as $org) {
            $orgModels[$org['slug']] = Organizer::firstOrCreate(
                ['slug' => $org['slug']],
                $org
            );
        }

        // 7. Seed Gigs
        $gigs = [
            ['slug' => 'bandung-berisik-2023', 'title' => 'Bandung Berisik 2023', 'date' => '2023-12-30', 'venue' => 'GOR Saparua', 'address' => 'Jl. Ambon No.9', 'city' => 'Bandung', 'price' => 100000, 'org_slug' => 'atap-promotions'],
            ['slug' => 'hammersonic-2023', 'title' => 'Hammersonic Festival 2023', 'date' => '2023-03-18', 'venue' => 'Carnaval Ancol', 'address' => 'Taman Impian Jaya Ancol', 'city' => 'Jakarta', 'price' => 2500000, 'org_slug' => 'ravel-entertainment'],
            ['slug' => 'synchronize-fest-2023', 'title' => 'Synchronize Fest 2023', 'date' => '2023-09-01', 'venue' => 'Gambir Expo', 'address' => 'JIExpo Kemayoran', 'city' => 'Jakarta', 'price' => 650000, 'org_slug' => 'demajors-festival'],
            ['slug' => 'sunset-di-kebun-purwodadi', 'title' => 'Sunset di Kebun', 'date' => '2024-03-02', 'venue' => 'Kebun Raya Purwodadi', 'address' => 'Jl. Raya Surabaya - Malang', 'city' => 'Pasuruan', 'price' => 150000, 'org_slug' => 'kebun-raya-id']
        ];

        foreach ($gigs as $gig) {
            if (isset($orgModels[$gig['org_slug']])) {
                Gig::firstOrCreate(
                    ['slug' => $gig['slug']],
                    [
                        'title' => $gig['title'],
                        'date' => $gig['date'],
                        'venue_name' => $gig['venue'],
                        'venue_address' => $gig['address'],
                        'city' => $gig['city'],
                        'ticket_price' => $gig['price'],
                        'organizer_id' => $orgModels[$gig['org_slug']]->id,
                        'created_by' => $defaultCuratorId
                    ]
                );
            }
        }
    }
}
