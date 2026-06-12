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
        // ====================================================================
        // 1. FETCH USERS & SEED PROFILES
        // ====================================================================
        $userSuper = User::where('username', 'superadmin')->first();
        $userCurator = User::where('username', 'curator')->first();
        $userOwner = User::where('username', 'band_owner')->first();
        $userContributor = User::where('username', 'contributor')->first();

        $allUsers = array_filter([$userSuper, $userCurator, $userOwner, $userContributor]);

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

        $defaultOwnerId = $userOwner->id ?? 1;
        $defaultCuratorId = $userCurator->id ?? 1;
        $defaultSuperId = $userSuper->id ?? 1;

        // ====================================================================
        // 2. SEED RECORD LABELS
        // ====================================================================
        $labels = [
            // Skena Metal / Hardcore
            ['slug' => 'grimloc-records', 'name' => 'Grimloc Records', 'city' => 'Bandung', 'formed_year' => 2010, 'status' => 'Active', 'description' => 'Independent hip-hop and hardcore label based in Bandung.'],
            ['slug' => 'disaster-records', 'name' => 'Disaster Records', 'city' => 'Bandung', 'formed_year' => 2012, 'status' => 'Active', 'description' => 'Record label focusing on metal, hardcore, and punk.'],
            ['slug' => 'blackandje-records', 'name' => 'Blackandje Records', 'city' => 'Jakarta', 'formed_year' => 2007, 'status' => 'Active', 'description' => 'Indonesian metal & extreme music label.'],
            ['slug' => 'majemuk-records', 'name' => 'Majemuk Records', 'city' => 'Malang', 'formed_year' => 2015, 'status' => 'Active', 'description' => 'Independent label documenting the East Java underground scene.'],

            // Skena Indie / Pop / Alternative
            ['slug' => 'demajors', 'name' => 'Demajors', 'city' => 'Jakarta', 'formed_year' => 2000, 'status' => 'Active', 'description' => 'One of the biggest independent record labels in Indonesia.'],
            ['slug' => 'ffwd-records', 'name' => 'Fast Forward (FFWD) Records', 'city' => 'Bandung', 'formed_year' => 1999, 'status' => 'Active', 'description' => 'Pioneer of the Indonesian indie pop scene, home to Mocca and The S.I.G.I.T.'],
            ['slug' => 'sun-eater', 'name' => 'Sun Eater', 'city' => 'Jakarta', 'formed_year' => 2019, 'status' => 'Active', 'description' => 'Contemporary music company and label shaping the modern pop/indie sound.'],
            ['slug' => 'aksara-records', 'name' => 'Aksara Records', 'city' => 'Jakarta', 'formed_year' => 2004, 'status' => 'Defunct', 'description' => 'Legendary independent label that defined the 2000s Jakarta indie scene.'],
            ['slug' => 'srm-bands', 'name' => 'SRM Bookings & Services', 'city' => 'Jakarta', 'formed_year' => 2010, 'status' => 'Active', 'description' => 'Management and label for massive independent acts.'],
            ['slug' => 'orange-cliff', 'name' => 'Orange Cliff Records', 'city' => 'Bandung', 'formed_year' => 2012, 'status' => 'Active', 'description' => 'Purveyors of fine fuzz, psychedelic, and lo-fi sounds.']
        ];

        foreach ($labels as $lbl) {
            Label::firstOrCreate(
                ['slug' => $lbl['slug']],
                array_merge($lbl, ['owner_id' => $defaultSuperId])
            );
        }

        // ====================================================================
        // 3. SEED BANDS / ARTISTS
        // ====================================================================
        $bands = [
            // Metal / Hardcore / Rock
            ['slug' => 'burgerkill', 'name' => 'Burgerkill', 'city' => 'Bandung', 'formed_year' => 1995, 'genre' => ['Metalcore', 'Death Metal'], 'biography' => 'Pioneers of the Indonesian metal scene.'],
            ['slug' => 'seringai', 'name' => 'Seringai', 'city' => 'Jakarta', 'formed_year' => 2002, 'genre' => ['High Octane Rock', 'Stoner Metal'], 'biography' => 'High octane rock heavily influenced by Black Sabbath and Motorhead.'],
            ['slug' => 'deadsquad', 'name' => 'DeadSquad', 'city' => 'Jakarta', 'formed_year' => 2006, 'genre' => ['Technical Death Metal'], 'biography' => 'Renowned for their extreme technicality and aggressive sound.'],
            ['slug' => 'fraud', 'name' => 'Fraud', 'city' => 'Surabaya', 'formed_year' => 2010, 'genre' => ['Beatdown Hardcore'], 'biography' => 'Heavy beatdown hardcore unit from the heat of Surabaya.'],
            ['slug' => 'extreme-decay', 'name' => 'Extreme Decay', 'city' => 'Malang', 'formed_year' => 1998, 'genre' => ['Grindcore'], 'biography' => 'Legendary grindcore machine from Malang, East Java.'],
            ['slug' => 'jasad', 'name' => 'Jasad', 'city' => 'Bandung', 'formed_year' => 1990, 'genre' => ['Brutal Death Metal'], 'biography' => 'Sundanese brutal death metal veterans.'],
            ['slug' => 'the-sigit', 'name' => 'The S.I.G.I.T', 'city' => 'Bandung', 'formed_year' => 1997, 'genre' => ['Hard Rock', 'Garage Rock'], 'biography' => 'The Super Insurgent Group of Intemperance Talent. Indonesian rock powerhouse.'],

            // Indie / Alternative / Pop
            ['slug' => 'sheila-on-7', 'name' => 'Sheila On 7', 'city' => 'Yogyakarta', 'formed_year' => 1996, 'genre' => ['Pop', 'Alternative Rock'], 'biography' => 'One of the most successful and beloved Indonesian pop-rock bands of all time.'],
            ['slug' => 'efek-rumah-kaca', 'name' => 'Efek Rumah Kaca', 'city' => 'Jakarta', 'formed_year' => 2001, 'genre' => ['Indie Pop', 'Alternative Rock'], 'biography' => 'Critically acclaimed indie band known for their poetic and socially conscious lyrics.'],
            ['slug' => 'the-adams', 'name' => 'The Adams', 'city' => 'Jakarta', 'formed_year' => 2001, 'genre' => ['Power Pop', 'Indie Rock'], 'biography' => 'Masters of harmonious vocal layers and energetic power pop anthems.'],
            ['slug' => 'mocca', 'name' => 'Mocca', 'city' => 'Bandung', 'formed_year' => 1999, 'genre' => ['Indie Pop', 'Swing', 'Twee Pop'], 'biography' => 'Sweet, storytelling indie-pop with strong jazz and swing influences.'],
            ['slug' => 'white-shoes', 'name' => 'White Shoes & The Couples Company', 'city' => 'Jakarta', 'formed_year' => 2002, 'genre' => ['Indie Pop', 'Retro Pop', 'Funk'], 'biography' => 'Charming retro-pop ensemble bringing the sound of 1970s Indonesian cinema back to life.'],
            ['slug' => 'the-panturas', 'name' => 'The Panturas', 'city' => 'Jatinangor', 'formed_year' => 2015, 'genre' => ['Surf Rock'], 'biography' => 'Contemporary surf rock bringing coastal vibes to the mountains.'],
            ['slug' => 'navicula', 'name' => 'Navicula', 'city' => 'Denpasar', 'formed_year' => 1996, 'genre' => ['Grunge', 'Psychedelic Rock'], 'biography' => 'The green grunge gentlemen of Bali, strongly advocating for environmental and social issues.'],
            ['slug' => 'hindia', 'name' => 'Hindia', 'city' => 'Jakarta', 'formed_year' => 2018, 'genre' => ['Alternative Pop', 'Indie Pop'], 'biography' => 'Solo moniker of Baskara Putra, capturing the anxieties of the modern Indonesian youth.']
        ];

        $bandModels = [];
        foreach ($bands as $bnd) {
            $bandModels[$bnd['slug']] = Band::firstOrCreate(
                ['slug' => $bnd['slug']],
                array_merge($bnd, ['country' => 'Indonesia', 'status' => 'Active', 'owner_id' => $defaultOwnerId])
            );
        }

        // ====================================================================
        // 4. SEED BAND MEMBERS
        // ====================================================================
        $bandMembers = [
            // Burgerkill
            ['band_slug' => 'burgerkill', 'name' => 'Aries Tanto (Eben)', 'role' => 'Guitars', 'join_year' => 1995, 'leave_year' => 2021, 'is_current' => false],
            ['band_slug' => 'burgerkill', 'name' => 'Ivan Scumbag', 'role' => 'Vocals', 'join_year' => 1995, 'leave_year' => 2006, 'is_current' => false],
            ['band_slug' => 'burgerkill', 'name' => 'Agung Hellfrog', 'role' => 'Guitars', 'join_year' => 2003, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'burgerkill', 'name' => 'Ramdan Agustiana', 'role' => 'Bass', 'join_year' => 2007, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'burgerkill', 'name' => 'Putra Pra Ramadhan', 'role' => 'Drums', 'join_year' => 2016, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'burgerkill', 'name' => 'Ronald Alexander', 'role' => 'Vocals', 'join_year' => 2021, 'leave_year' => null, 'is_current' => true],

            // Seringai
            ['band_slug' => 'seringai', 'name' => 'Arian13', 'role' => 'Vocals', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'seringai', 'name' => 'Ricky Siahaan', 'role' => 'Guitars', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'seringai', 'name' => 'Sammy Bramantyo', 'role' => 'Bass', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'seringai', 'name' => 'Edy Khemod', 'role' => 'Drums', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],

            // The S.I.G.I.T
            ['band_slug' => 'the-sigit', 'name' => 'Rekti Yoewono', 'role' => 'Vocals, Guitars', 'join_year' => 1997, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-sigit', 'name' => 'Farri Icksan Wibisana', 'role' => 'Guitars, Synthesizer', 'join_year' => 1997, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-sigit', 'name' => 'Aditya Bagja Mulyana', 'role' => 'Bass', 'join_year' => 1997, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-sigit', 'name' => 'Donar Armando Ekana (Acil)', 'role' => 'Drums', 'join_year' => 1997, 'leave_year' => null, 'is_current' => true],

            // Sheila On 7
            ['band_slug' => 'sheila-on-7', 'name' => 'Akhdiyat Duta Modjo', 'role' => 'Vocals', 'join_year' => 1996, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'sheila-on-7', 'name' => 'Eross Candra', 'role' => 'Guitars', 'join_year' => 1996, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'sheila-on-7', 'name' => 'Adam Muhammad Subarkah', 'role' => 'Bass', 'join_year' => 1996, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'sheila-on-7', 'name' => 'Brian Kresna Putro', 'role' => 'Drums', 'join_year' => 2004, 'leave_year' => 2022, 'is_current' => false],
            ['band_slug' => 'sheila-on-7', 'name' => 'Anton Widiastanto', 'role' => 'Drums', 'join_year' => 1996, 'leave_year' => 2004, 'is_current' => false],

            // Efek Rumah Kaca
            ['band_slug' => 'efek-rumah-kaca', 'name' => 'Cholil Mahmud', 'role' => 'Vocals, Guitars', 'join_year' => 2001, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'efek-rumah-kaca', 'name' => 'Poppie Airil', 'role' => 'Bass', 'join_year' => 2015, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'efek-rumah-kaca', 'name' => 'Akbar Bagus Sudibyo', 'role' => 'Drums', 'join_year' => 2001, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'efek-rumah-kaca', 'name' => 'Adrian Yunan Faisal', 'role' => 'Bass, Vocals', 'join_year' => 2001, 'leave_year' => 2017, 'is_current' => false],

            // The Adams
            ['band_slug' => 'the-adams', 'name' => 'Ario Hendarwan', 'role' => 'Vocals, Guitars', 'join_year' => 2001, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-adams', 'name' => 'Saleh Husein', 'role' => 'Vocals, Guitars', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-adams', 'name' => 'Gigih Suryo Prayogo', 'role' => 'Drums, Vocals', 'join_year' => 2005, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'the-adams', 'name' => 'Pandu Fuzztoni', 'role' => 'Bass, Vocals', 'join_year' => 2014, 'leave_year' => null, 'is_current' => true],

            // Mocca
            ['band_slug' => 'mocca', 'name' => 'Arina Ephipania', 'role' => 'Vocals, Flute', 'join_year' => 1999, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'mocca', 'name' => 'Riko Prayitno', 'role' => 'Guitars', 'join_year' => 1999, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'mocca', 'name' => 'Toma Pratama', 'role' => 'Bass', 'join_year' => 1999, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'mocca', 'name' => 'Indra Massad', 'role' => 'Drums', 'join_year' => 1999, 'leave_year' => null, 'is_current' => true],

            // White Shoes & The Couples Company
            ['band_slug' => 'white-shoes', 'name' => 'Aprilia Apsari', 'role' => 'Vocals', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'white-shoes', 'name' => 'Yusmario Farabi', 'role' => 'Acoustic Guitar', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'white-shoes', 'name' => 'Saleh Husein', 'role' => 'Electric Guitar', 'join_year' => 2002, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'white-shoes', 'name' => 'Ricky Virgana', 'role' => 'Bass, Cello', 'join_year' => 2004, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'white-shoes', 'name' => 'Aprimela Prapanca', 'role' => 'Keyboards, Viola', 'join_year' => 2004, 'leave_year' => null, 'is_current' => true],
            ['band_slug' => 'white-shoes', 'name' => 'John Navid', 'role' => 'Drums, Vibes', 'join_year' => 2004, 'leave_year' => null, 'is_current' => true],

            // Hindia
            ['band_slug' => 'hindia', 'name' => 'Baskara Putra', 'role' => 'Vocals, Synths', 'join_year' => 2018, 'leave_year' => null, 'is_current' => true],
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
                        'linked_user_id' => null
                    ]
                );
            }
        }

        // ====================================================================
        // 5. SEED RELEASES (ALBUMS & EPS)
        // ====================================================================
        $releases = [
            // Metal & Hardcore
            ['slug' => 'dua-sisi', 'band_slug' => 'burgerkill', 'title' => 'Dua Sisi', 'year' => 2000, 'type' => 'Full-length', 'track_count' => 10],
            ['slug' => 'berkarat', 'band_slug' => 'burgerkill', 'title' => 'Berkarat', 'year' => 2003, 'type' => 'Full-length', 'track_count' => 10],
            ['slug' => 'beyond-the-self', 'band_slug' => 'burgerkill', 'title' => 'Beyond the Self', 'year' => 2006, 'type' => 'Full-length', 'track_count' => 10],
            ['slug' => 'venomous', 'band_slug' => 'burgerkill', 'title' => 'Venomous', 'year' => 2011, 'type' => 'Full-length', 'track_count' => 9],
            ['slug' => 'high-octane-rock', 'band_slug' => 'seringai', 'title' => 'High Octane Rock', 'year' => 2004, 'type' => 'EP', 'track_count' => 6],
            ['slug' => 'serigala-militia', 'band_slug' => 'seringai', 'title' => 'Serigala Militia', 'year' => 2007, 'type' => 'Full-length', 'track_count' => 11],
            ['slug' => 'taring', 'band_slug' => 'seringai', 'title' => 'Taring', 'year' => 2012, 'type' => 'Full-length', 'track_count' => 12],
            ['slug' => 'horror-vision', 'band_slug' => 'deadsquad', 'title' => 'Horror Vision', 'year' => 2009, 'type' => 'Full-length', 'track_count' => 8],
            ['slug' => 'profanatik', 'band_slug' => 'deadsquad', 'title' => 'Profanatik', 'year' => 2013, 'type' => 'Full-length', 'track_count' => 8],

            // The S.I.G.I.T
            ['slug' => 'visible-idea-of-perfection', 'band_slug' => 'the-sigit', 'title' => 'Visible Idea of Perfection', 'year' => 2006, 'type' => 'Full-length', 'track_count' => 13],
            ['slug' => 'detourn', 'band_slug' => 'the-sigit', 'title' => 'Detourn', 'year' => 2013, 'type' => 'Full-length', 'track_count' => 11],

            // Sheila On 7
            ['slug' => 'sheila-on-7-album', 'band_slug' => 'sheila-on-7', 'title' => 'Sheila On 7', 'year' => 1999, 'type' => 'Full-length', 'track_count' => 10],
            ['slug' => 'kisah-klasik-untuk-masa-depan', 'band_slug' => 'sheila-on-7', 'title' => 'Kisah Klasik Untuk Masa Depan', 'year' => 2000, 'type' => 'Full-length', 'track_count' => 12],
            ['slug' => '07-des', 'band_slug' => 'sheila-on-7', 'title' => '07 Des', 'year' => 2002, 'type' => 'Full-length', 'track_count' => 14],
            ['slug' => 'pejantan-tangguh', 'band_slug' => 'sheila-on-7', 'title' => 'Pejantan Tangguh', 'year' => 2004, 'type' => 'Full-length', 'track_count' => 12],

            // Efek Rumah Kaca
            ['slug' => 'efek-rumah-kaca-album', 'band_slug' => 'efek-rumah-kaca', 'title' => 'Efek Rumah Kaca', 'year' => 2007, 'type' => 'Full-length', 'track_count' => 12],
            ['slug' => 'kamar-gelap', 'band_slug' => 'efek-rumah-kaca', 'title' => 'Kamar Gelap', 'year' => 2008, 'type' => 'Full-length', 'track_count' => 12],
            ['slug' => 'sinestesia', 'band_slug' => 'efek-rumah-kaca', 'title' => 'Sinestesia', 'year' => 2015, 'type' => 'Full-length', 'track_count' => 6],

            // The Adams
            ['slug' => 'the-adams-album', 'band_slug' => 'the-adams', 'title' => 'The Adams', 'year' => 2005, 'type' => 'Full-length', 'track_count' => 12],
            ['slug' => 'pelantar', 'band_slug' => 'the-adams', 'title' => 'Pelantar', 'year' => 2006, 'type' => 'Full-length', 'track_count' => 12],
            ['slug' => 'agterplaas', 'band_slug' => 'the-adams', 'title' => 'Agterplaas', 'year' => 2019, 'type' => 'Full-length', 'track_count' => 11],

            // Mocca
            ['slug' => 'my-diary', 'band_slug' => 'mocca', 'title' => 'My Diary', 'year' => 2002, 'type' => 'Full-length', 'track_count' => 13],
            ['slug' => 'friends', 'band_slug' => 'mocca', 'title' => 'Friends', 'year' => 2004, 'type' => 'Full-length', 'track_count' => 13],

            // White Shoes
            ['slug' => 'wsatcc-album', 'band_slug' => 'white-shoes', 'title' => 'White Shoes & The Couples Company', 'year' => 2005, 'type' => 'Full-length', 'track_count' => 11],
            ['slug' => 'album-vakansi', 'band_slug' => 'white-shoes', 'title' => 'Vakansi', 'year' => 2010, 'type' => 'Full-length', 'track_count' => 13],

            // Hindia
            ['slug' => 'menari-dengan-bayangan', 'band_slug' => 'hindia', 'title' => 'Menari Dengan Bayangan', 'year' => 2019, 'type' => 'Full-length', 'track_count' => 15],
            ['slug' => 'lagipula-hidup-akan-berakhir', 'band_slug' => 'hindia', 'title' => 'Lagipula Hidup Akan Berakhir', 'year' => 2023, 'type' => 'Full-length', 'track_count' => 28],

            // The Panturas
            ['slug' => 'mabuk-laut', 'band_slug' => 'the-panturas', 'title' => 'Mabuk Laut', 'year' => 2018, 'type' => 'Full-length', 'track_count' => 7],
            ['slug' => 'ombak-banyu-asmara', 'band_slug' => 'the-panturas', 'title' => 'Ombak Banyu Asmara', 'year' => 2021, 'type' => 'Full-length', 'track_count' => 10],
        ];

        foreach ($releases as $rel) {
            if (isset($bandModels[$rel['band_slug']])) {
                $releaseModel = Release::updateOrCreate(
                    ['slug' => $rel['slug']],
                    [
                        'band_id' => $bandModels[$rel['band_slug']]->id,
                        'title' => $rel['title'],
                        'release_type' => $rel['type'],
                        'original_release_year' => $rel['year'],
                        'track_count' => $rel['track_count']
                    ]
                );

                // Seed tracks for this release
                for ($i = 1; $i <= $rel['track_count']; $i++) {
                    Track::firstOrCreate(
                        [
                            'release_id' => $releaseModel->id,
                            'track_number' => $i
                        ],
                        [
                            'title' => 'Track ' . $i . ' of ' . $rel['title'],
                            'duration' => rand(180, 300), // Random duration between 3-5 minutes
                            'lyrics' => 'Sample lyrics for ' . $rel['title'] . ' track ' . $i
                        ]
                    );
                }
            }
        }

        // ====================================================================
        // 6. SEED ORGANIZERS & PROMOTERS
        // ====================================================================
        $organizers = [
            ['slug' => 'atap-promotions', 'name' => 'Atap Promotions', 'city' => 'Bandung', 'description' => 'Boutique music promoter and mastermind behind Bandung Berisik.'],
            ['slug' => 'ravel-entertainment', 'name' => 'Ravel Entertainment', 'city' => 'Jakarta', 'description' => 'The heavy music engine behind Hammersonic Festival.'],
            ['slug' => 'demajors-festival', 'name' => 'Demajors & Dyandra', 'city' => 'Jakarta', 'description' => 'Promoters for the massive national music celebration, Synchronize Fest.'],
            ['slug' => 'ismaya-live', 'name' => 'Ismaya Live', 'city' => 'Jakarta', 'description' => 'Lifestyle and entertainment giants behind We The Fest and Djakarta Warehouse Project (DWP).'],
            ['slug' => 'boss-creator', 'name' => 'Boss Creator', 'city' => 'Jakarta', 'description' => 'Creative organizers behind Pestapora.'],
            ['slug' => 'java-festival-production', 'name' => 'Java Festival Production', 'city' => 'Jakarta', 'description' => 'Pioneers of international-scale festivals like Jakarta International BNI Java Jazz Festival.'],
            ['slug' => 'rajawali-indonesia', 'name' => 'Rajawali Indonesia', 'city' => 'Yogyakarta', 'description' => 'Promoters responsible for Prambanan Jazz and JogjaROCKarta.']
        ];

        $orgModels = [];
        foreach ($organizers as $org) {
            $orgModels[$org['slug']] = Organizer::firstOrCreate(
                ['slug' => $org['slug']],
                $org
            );
        }

        // ====================================================================
        // 7. SEED GIGS / FESTIVALS
        // ====================================================================
        $gigs = [
            // Rock / Metal / Alternative
            ['slug' => 'bandung-berisik-2023', 'title' => 'Bandung Berisik 2023', 'date' => '2023-12-30', 'venue' => 'GOR Saparua', 'address' => 'Jl. Ambon No.9', 'city' => 'Bandung', 'price' => 100000, 'org_slug' => 'atap-promotions'],
            ['slug' => 'hammersonic-2023', 'title' => 'Hammersonic Festival 2023', 'date' => '2023-03-18', 'venue' => 'Carnaval Ancol', 'address' => 'Taman Impian Jaya Ancol', 'city' => 'Jakarta', 'price' => 2500000, 'org_slug' => 'ravel-entertainment'],
            ['slug' => 'jogjarockarta-2023', 'title' => 'JogjaROCKarta 2023', 'date' => '2023-09-30', 'venue' => 'Stadion Kridosono', 'address' => 'Jl. Yos Sudarso No.9', 'city' => 'Yogyakarta', 'price' => 500000, 'org_slug' => 'rajawali-indonesia'],

            // Pop / Indie / Multi-genre Festivals
            ['slug' => 'synchronize-fest-2023', 'title' => 'Synchronize Fest 2023', 'date' => '2023-09-01', 'venue' => 'Gambir Expo', 'address' => 'JIExpo Kemayoran', 'city' => 'Jakarta', 'price' => 650000, 'org_slug' => 'demajors-festival'],
            ['slug' => 'pestapora-2023', 'title' => 'Pestapora 2023', 'date' => '2023-09-22', 'venue' => 'Gambir Expo', 'address' => 'JIExpo Kemayoran', 'city' => 'Jakarta', 'price' => 700000, 'org_slug' => 'boss-creator'],
            ['slug' => 'we-the-fest-2023', 'title' => 'We The Fest (WTF) 2023', 'date' => '2023-07-21', 'venue' => 'GBK Sports Complex', 'address' => 'Senayan', 'city' => 'Jakarta', 'price' => 1500000, 'org_slug' => 'ismaya-live'],

            // Jazz / Special
            ['slug' => 'java-jazz-2024', 'title' => 'BNI Java Jazz Festival 2024', 'date' => '2024-05-24', 'venue' => 'JIExpo Kemayoran', 'address' => 'Jl. Benyamin Sueb', 'city' => 'Jakarta', 'price' => 950000, 'org_slug' => 'java-festival-production'],
            ['slug' => 'prambanan-jazz-2023', 'title' => 'Prambanan Jazz Festival 2023', 'date' => '2023-07-07', 'venue' => 'Candi Prambanan', 'address' => 'Jl. Raya Solo - Yogyakarta', 'city' => 'Sleman', 'price' => 850000, 'org_slug' => 'rajawali-indonesia'],
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
