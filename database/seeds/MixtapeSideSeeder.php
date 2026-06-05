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
use App\Models\VerificationRequest;
use App\Models\DataDraft;
use App\Models\UserLog;
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
        // 1. Seed Demo Users
        $usersData = [
            [
                'username' => 'bandowner',
                'name' => 'Budi Punker',
                'email' => 'budi@punkband.com',
                'password' => 'password123',
                'nowa' => '081234567893'
            ],
            [
                'username' => 'labelowner',
                'name' => 'Adi Recordist',
                'email' => 'adi@rockrecords.com',
                'password' => 'password123',
                'nowa' => '081234567894'
            ],
            [
                'username' => 'kolektifowner',
                'name' => 'Rian Collective',
                'email' => 'rian@diycollective.com',
                'password' => 'password123',
                'nowa' => '081234567895'
            ],
            [
                'username' => 'regularuser',
                'name' => 'Joni Penikmat',
                'email' => 'joni@example.com',
                'password' => 'password123',
                'nowa' => '081234567896'
            ]
        ];

        $users = [];
        foreach ($usersData as $uData) {
            $user = User::where('username', $uData['username'])->first();
            if (!$user) {
                $user = User::create($uData);
                $user->markEmailAsVerified();
            }
            $users[$uData['username']] = $user;
        }

        // Add pre-existing users from UserSeeder
        $users['superadmin'] = User::where('username', 'superadmin')->first();
        $users['admin'] = User::where('username', 'admin')->first();
        $users['staff'] = User::where('username', 'staff')->first();

        // Assign Roles
        $users['bandowner']->syncRoles(['VERIFIED_ENTITY']);
        $users['labelowner']->syncRoles(['VERIFIED_ENTITY']);
        $users['kolektifowner']->syncRoles(['VERIFIED_ENTITY']);
        $users['regularuser']->syncRoles(['REGISTERED_USER']);

        // 2. Seed Profiles
        foreach ($users as $username => $user) {
            if (!$user->profile) {
                Profile::create([
                    'user_id' => $user->id,
                    'display_name' => $user->name,
                    'location' => 'Bandung',
                    'bio' => 'Halo! Saya adalah ' . $user->name . ' di platform MixtapeSide.'
                ]);
            }
        }

        // 3. Seed Labels
        $labelA = Label::firstOrCreate(
            ['slug' => 'rock-records'],
            [
                'name' => 'Rock Records',
                'logo_url' => null,
                'city' => 'Bandung',
                'formed_year' => 2010,
                'status' => 'Active',
                'website_url' => 'https://rockrecords.com',
                'contact_email' => 'contact@rockrecords.com',
                'description' => 'Record label independen asal Bandung yang fokus merilis musik punk rock dan garage rock sejak tahun 2010.',
                'owner_id' => $users['labelowner']->id
            ]
        );

        $labelB = Label::firstOrCreate(
            ['slug' => 'noise-alliance'],
            [
                'name' => 'Noise Alliance',
                'logo_url' => null,
                'city' => 'Jakarta',
                'formed_year' => 2018,
                'status' => 'Active',
                'website_url' => 'https://noisealliance.id',
                'contact_email' => 'info@noisealliance.id',
                'description' => 'Kolektif dan record label yang merilis musik experimental, noise, dan post-punk di Jakarta.',
                'owner_id' => null // unclaimed label
            ]
        );

        // 4. Seed Bands
        $bandA = Band::firstOrCreate(
            ['slug' => 'the-punk-band'],
            [
                'name' => 'The Punk Band',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'formed_year' => 2015,
                'disbanded_year' => null,
                'status' => 'Active',
                'genre' => ['Punk', 'Rock', 'Garage'],
                'biography' => 'The Punk Band dibentuk pada tahun 2015 di Bandung. Terpengaruh oleh gelombang 77 punk rock Inggris dengan lirik protes sosial lokal.',
                'alternative_names' => ['TPB', 'Punk Bandung'],
                'social_links' => [
                    'instagram' => 'https://instagram.com/thepunkband',
                    'spotify' => 'https://spotify.com/artist/thepunkband',
                    'bandcamp' => 'https://thepunkband.bandcamp.com'
                ],
                'owner_id' => $users['bandowner']->id,
                'created_by' => $users['bandowner']->id
            ]
        );

        $bandB = Band::firstOrCreate(
            ['slug' => 'scream-and-shout'],
            [
                'name' => 'Scream & Shout',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'formed_year' => 2018,
                'disbanded_year' => 2024,
                'status' => 'Split-up',
                'genre' => ['Post-Punk', 'New Wave'],
                'biography' => 'Duo post-punk asal Jakarta Timur yang aktif meramaikan gigs underground ibu kota sebelum akhirnya memutuskan bubar pada 2024.',
                'alternative_names' => ['Scream Shout', 'SNS'],
                'social_links' => [
                    'instagram' => 'https://instagram.com/screamshout',
                    'bandcamp' => 'https://screamshout.bandcamp.com'
                ],
                'owner_id' => null,
                'created_by' => $users['regularuser']->id
            ]
        );

        // 5. Seed Band Members
        $membersA = [
            [
                'name' => 'Budi Punker',
                'linked_user_id' => $users['bandowner']->id,
                'role_instrument' => 'Vocal & Guitar',
                'join_year' => 2015,
                'leave_year' => null,
                'is_current' => true
            ],
            [
                'name' => 'Jono Bassist',
                'linked_user_id' => null,
                'role_instrument' => 'Bass',
                'join_year' => 2015,
                'leave_year' => null,
                'is_current' => true
            ],
            [
                'name' => 'Ali Drummer',
                'linked_user_id' => null,
                'role_instrument' => 'Drums',
                'join_year' => 2018,
                'leave_year' => null,
                'is_current' => true
            ],
            [
                'name' => 'Tono Mantan',
                'linked_user_id' => null,
                'role_instrument' => 'Drums',
                'join_year' => 2015,
                'leave_year' => 2018,
                'is_current' => false
            ]
        ];

        foreach ($membersA as $m) {
            BandMember::firstOrCreate(
                ['band_id' => $bandA->id, 'name' => $m['name']],
                $m
            );
        }

        $membersB = [
            [
                'name' => 'Riko',
                'linked_user_id' => null,
                'role_instrument' => 'Vocals & Synth',
                'join_year' => 2018,
                'leave_year' => 2024,
                'is_current' => false
            ],
            [
                'name' => 'Dani',
                'linked_user_id' => null,
                'role_instrument' => 'Guitar',
                'join_year' => 2018,
                'leave_year' => 2024,
                'is_current' => false
            ]
        ];

        foreach ($membersB as $m) {
            BandMember::firstOrCreate(
                ['band_id' => $bandB->id, 'name' => $m['name']],
                $m
            );
        }

        // 6. Seed Releases
        $releaseA1 = Release::firstOrCreate(
            ['slug' => 'first-demo'],
            [
                'band_id' => $bandA->id,
                'title' => 'First Demo',
                'release_type' => 'Demo',
                'cover_url' => null,
                'original_release_year' => 2016,
                'description' => 'Rekaman demo pertama kami yang direkam secara live di studio latihan Bandung. Berisi 3 lagu mentah sarat protes sosial.',
                'track_count' => 3
            ]
        );

        // Connect ReleaseA1 to LabelA
        if ($releaseA1->labels()->where('labels.id', $labelA->id)->count() == 0) {
            $releaseA1->labels()->attach($labelA->id, [
                'catalog_number' => 'RR-001',
                'press_year' => 2016,
                'format' => 'Kaset',
                'press_type' => 'Original Press',
                'notes' => 'Dirilis terbatas sebanyak 100 keping kaset pita berwarna merah.'
            ]);
        }

        $releaseA2 = Release::firstOrCreate(
            ['slug' => 'anarchy-ep'],
            [
                'band_id' => $bandA->id,
                'title' => 'Anarchy EP',
                'release_type' => 'EP',
                'cover_url' => null,
                'original_release_year' => 2019,
                'description' => 'Mini album / EP yang dirilis secara bersama (co-release) oleh Rock Records Bandung dan Noise Alliance Jakarta.',
                'track_count' => 2
            ]
        );

        // Connect ReleaseA2 to LabelA & LabelB
        if ($releaseA2->labels()->where('labels.id', $labelA->id)->count() == 0) {
            $releaseA2->labels()->attach($labelA->id, [
                'catalog_number' => 'RR-009',
                'press_year' => 2019,
                'format' => 'CD',
                'press_type' => 'Original Press',
                'notes' => 'CD Jewel case.'
            ]);
        }
        if ($releaseA2->labels()->where('labels.id', $labelB->id)->count() == 0) {
            $releaseA2->labels()->attach($labelB->id, [
                'catalog_number' => 'NA-003',
                'press_year' => 2019,
                'format' => 'CD',
                'press_type' => 'Original Press',
                'notes' => 'CD Digipack.'
            ]);
        }

        $releaseB1 = Release::firstOrCreate(
            ['slug' => 'goodbye-world'],
            [
                'band_id' => $bandB->id,
                'title' => 'Goodbye World',
                'release_type' => 'Album',
                'cover_url' => null,
                'original_release_year' => 2021,
                'description' => 'Full-length album perdana sekaligus penutup dari Scream & Shout sebelum menyatakan bubar.',
                'track_count' => 2
            ]
        );

        if ($releaseB1->labels()->where('labels.id', $labelB->id)->count() == 0) {
            $releaseB1->labels()->attach($labelB->id, [
                'catalog_number' => 'NA-012',
                'press_year' => 2021,
                'format' => 'Vinyl',
                'press_type' => 'Original Press',
                'notes' => '12" Black Vinyl. Terbatas 300 keping.'
            ]);
        }

        // 7. Seed Tracks & Track Contributors
        // Release A1 Tracks
        $tracksA1 = [
            [
                'track_number' => 1,
                'title' => 'Anarki Kota',
                'duration' => 180,
                'lyrics' => "Pagi datang dengan kabut asap\nDi sudut kota yang penuh senyap\nMereka berteriak minta keadilan\nTapi dijawab dengan tembakan...",
                'lyrics_translation' => "Morning comes with foggy smoke\nIn a silent corner of the city\nThey shout out for justice\nBut get answered with gunshots..."
            ],
            [
                'track_number' => 2,
                'title' => 'Sistem Hancur',
                'duration' => 150,
                'lyrics' => "Sistem yang bobrok harus diubah\nJangan biarkan mereka menjajah\nBangkitlah kawan, suara kita adalah senjata...",
                'lyrics_translation' => "The rotten system must be changed\nDo not let them colonize us\nRise up friends, our voice is the weapon..."
            ],
            [
                'track_number' => 3,
                'title' => 'Lagu Protes',
                'duration' => 210,
                'lyrics' => "Ini adalah lagu protes kami\nBagi penguasa yang menutup telinga...",
                'lyrics_translation' => null
            ]
        ];

        foreach ($tracksA1 as $t) {
            $track = Track::firstOrCreate(
                ['release_id' => $releaseA1->id, 'track_number' => $t['track_number']],
                $t
            );

            // Add Contributor
            TrackContributor::firstOrCreate(
                ['track_id' => $track->id, 'name' => 'Budi Punker', 'role' => 'Songwriter'],
                ['notes' => 'Menulis lirik dan melodi gitar utama.']
            );
        }

        // Release A2 Tracks
        $tracksA2 = [
            [
                'track_number' => 1,
                'title' => 'Bising Bandung',
                'duration' => 200,
                'lyrics' => "Deru motor di jalanan Dago\nBising bandung merasuk dada...",
                'lyrics_translation' => null
            ],
            [
                'track_number' => 2,
                'title' => 'Lawan!',
                'duration' => 140,
                'lyrics' => "Lawan tirani, lawan ketakutan!\nHari ini kita merdeka!",
                'lyrics_translation' => null
            ]
        ];

        foreach ($tracksA2 as $t) {
            $track = Track::firstOrCreate(
                ['release_id' => $releaseA2->id, 'track_number' => $t['track_number']],
                $t
            );

            TrackContributor::firstOrCreate(
                ['track_id' => $track->id, 'name' => 'Jono Bassist', 'role' => 'Composer'],
                ['notes' => 'Menyusun progresi bassline.']
            );
        }

        // Release B1 Tracks
        $tracksB1 = [
            [
                'track_number' => 1,
                'title' => 'Echoes of Jakarta',
                'duration' => 240,
                'lyrics' => "Walk down the alley in the dark Jakarta night\nLooking for the neon light...\nEchoes in my head, echoes of the past...",
                'lyrics_translation' => null
            ],
            [
                'track_number' => 2,
                'title' => 'Goodbye World (Outro)',
                'duration' => 300,
                'lyrics' => "This is the end of our journey.\nThank you and goodbye.",
                'lyrics_translation' => null
            ]
        ];

        foreach ($tracksB1 as $t) {
            $track = Track::firstOrCreate(
                ['release_id' => $releaseB1->id, 'track_number' => $t['track_number']],
                $t
            );

            TrackContributor::firstOrCreate(
                ['track_id' => $track->id, 'name' => 'Riko', 'role' => 'Songwriter'],
                ['notes' => 'Menulis synthesizer track.']
            );
            TrackContributor::firstOrCreate(
                ['track_id' => $track->id, 'name' => 'Dani', 'role' => 'Musician'],
                ['notes' => 'Gitar elektrik.']
            );
        }

        // 8. Seed Organizers
        $organizerA = Organizer::firstOrCreate(
            ['slug' => 'diy-collective'],
            [
                'name' => 'DIY Collective',
                'logo_url' => null,
                'city' => 'Bandung',
                'description' => 'Kolektif gigs independen D.I.Y di Bandung yang aktif menyelenggarakan konser kecil di halaman rumah dan studio musik lokal.',
                'contact_info' => '@diycollective_bdg',
                'owner_id' => $users['kolektifowner']->id
            ]
        );

        $organizerB = Organizer::firstOrCreate(
            ['slug' => 'jakarta-noise-consortium'],
            [
                'name' => 'Jakarta Noise Consortium',
                'logo_url' => null,
                'city' => 'Jakarta',
                'description' => 'Asosiasi kolektif musik kebisingan dan post-punk ibu kota.',
                'contact_info' => 'contact@jktnoise.org',
                'owner_id' => null
            ]
        );

        // 9. Seed Gigs
        $gigA = Gig::firstOrCreate(
            ['slug' => 'bandung-noise-fest'],
            [
                'title' => 'Bandung Noise Fest',
                'poster_url' => null,
                'date' => date('Y-m-d', strtotime('+14 days')),
                'start_time' => '17:00:00',
                'venue_name' => 'Noise House Garage',
                'venue_address' => 'Jl. Ir. H. Juanda No. 120, Dago',
                'city' => 'Bandung',
                'ticket_price' => 50000.00,
                'ticket_info' => 'HTM: 50K (Sudah termasuk sticker pack)',
                'organizer_id' => $organizerA->id,
                'created_by' => $users['kolektifowner']->id
            ]
        );

        // Connect GigA to BandA & LabelA
        if ($gigA->bands()->where('bands.id', $bandA->id)->count() == 0) {
            $gigA->bands()->attach($bandA->id, ['performance_order' => 1]);
        }
        if ($gigA->labels()->where('labels.id', $labelA->id)->count() == 0) {
            $gigA->labels()->attach($labelA->id, ['partnership_role' => 'Media Partner']);
        }

        $gigB = Gig::firstOrCreate(
            ['slug' => 'jakarta-underground-showcase'],
            [
                'title' => 'Jakarta Underground Showcase',
                'poster_url' => null,
                'date' => date('Y-m-d', strtotime('-30 days')), // past event
                'start_time' => '19:00:00',
                'venue_name' => 'Studio Kebisingan Mandiri',
                'venue_address' => 'Samping Stasiun Tebet, Jakarta Selatan',
                'city' => 'Jakarta',
                'ticket_price' => 0.00,
                'ticket_info' => 'Free / Bring Your Own Beverage (BYOB)',
                'organizer_id' => $organizerB->id,
                'created_by' => $users['superadmin']->id
            ]
        );

        // Connect GigB to BandB & LabelB
        if ($gigB->bands()->where('bands.id', $bandB->id)->count() == 0) {
            $gigB->bands()->attach($bandB->id, ['performance_order' => 1]);
        }
        if ($gigB->labels()->where('labels.id', $labelB->id)->count() == 0) {
            $gigB->labels()->attach($labelB->id, ['partnership_role' => 'Sponsor']);
        }

        // 10. Seed Verification Requests (Claims profile)
        VerificationRequest::create([
            'user_id' => $users['regularuser']->id,
            'target_type' => 'label',
            'target_id' => $labelB->id,
            'relationship_desc' => 'Saya adalah salah satu co-founder dari label Noise Alliance Jakarta.',
            'verification_documents' => ['https://mixtapeside.com/proof/doc1.jpg'],
            'status' => 'Pending',
            'admin_notes' => null
        ]);

        VerificationRequest::create([
            'user_id' => $users['kolektifowner']->id,
            'target_type' => 'organizer',
            'target_id' => $organizerA->id,
            'relationship_desc' => 'Saya adalah PJ / Ketua kolektif DIY Collective.',
            'verification_documents' => ['https://mixtapeside.com/proof/doc2.jpg'],
            'status' => 'Approved',
            'admin_notes' => 'Klaim disetujui, bukti kuat.'
        ]);

        // 11. Seed Data Drafts (Crowdsourced contribution)
        DataDraft::create([
            'user_id' => $users['regularuser']->id,
            'target_table' => 'bands',
            'target_id' => $bandB->id,
            'proposed_data' => [
                'biography' => 'Duo post-punk asal Jakarta Timur yang aktif meramaikan gigs underground ibu kota sebelum akhirnya memutuskan bubar pada 2024. Telah meluncurkan 1 album studio.',
                'city' => 'Jakarta Timur'
            ],
            'status' => 'Pending',
            'admin_notes' => null
        ]);

        DataDraft::create([
            'user_id' => $users['regularuser']->id,
            'target_table' => 'releases',
            'target_id' => $releaseB1->id,
            'proposed_data' => [
                'description' => 'Full-length album perdana sekaligus penutup dari Scream & Shout sebelum menyatakan bubar pada pertengahan 2024. Sangat direkomendasikan untuk fans Joy Division.',
            ],
            'status' => 'Applied',
            'admin_notes' => 'Perubahan deskripsi informatif. Disetujui.'
        ]);

        // 12. Write Initial User Logs / Archive Activity
        UserLog::create([
            'user_id' => $users['superadmin']->id,
            'action' => 'Penambahan',
            'message' => '<p>User <b>Super Administrator</b> melakukan Penambahan data di menu <b>Band</b> dan id/nomor <b>' . $bandA->name . '</b></p>'
        ]);

        UserLog::create([
            'user_id' => $users['labelowner']->id,
            'action' => 'Penambahan',
            'message' => '<p>User <b>Adi Recordist</b> melakukan Penambahan data di menu <b>Label</b> dan id/nomor <b>' . $labelA->name . '</b></p>'
        ]);

        UserLog::create([
            'user_id' => $users['bandowner']->id,
            'action' => 'Perubahan',
            'message' => '<p>User <b>Budi Punker</b> melakukan Perubahan data di menu <b>Lagu</b> dan id/nomor <b>' . $tracksA1[0]['title'] . '</b></p>'
        ]);

        UserLog::create([
            'user_id' => $users['kolektifowner']->id,
            'action' => 'Penambahan',
            'message' => '<p>User <b>Rian Collective</b> melakukan Penambahan data di menu <b>Gigs</b> dan id/nomor <b>' . $gigA->title . '</b></p>'
        ]);
    }
}
