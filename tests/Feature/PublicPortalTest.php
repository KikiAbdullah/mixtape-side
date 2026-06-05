<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Band;
use App\Models\Label;
use App\Models\Release;
use App\Models\Track;
use App\Models\Organizer;
use App\Models\Gig;
use App\Models\UserLog;

class PublicPortalTest extends TestCase
{
    use RefreshDatabase;

    protected $band;
    protected $label;
    protected $release;
    protected $gig;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\UserSeeder::class);
        $this->seed(\PermissionSeeder::class);

        $user = User::first();

        // Create mock Label
        $this->label = Label::create([
            'slug' => 'rock-records',
            'name' => 'Rock Records',
            'city' => 'Bandung',
            'formed_year' => 2010,
            'status' => 'Active',
            'website_url' => 'https://rockrecords.com',
            'contact_email' => 'contact@rockrecords.com',
            'description' => 'A legendary local record label.'
        ]);

        // Create mock Band
        $this->band = Band::create([
            'slug' => 'the-punk-band',
            'name' => 'The Punk Band',
            'city' => 'Bandung',
            'formed_year' => 2015,
            'status' => 'Active',
            'genre' => ['Punk', 'Rock'],
            'biography' => 'Formed in Bandung in 2015.',
            'alternative_names' => ['TPB', 'PunkBand'],
            'social_links' => ['instagram' => 'https://instagram.com/tpb']
        ]);

        // Create mock Release
        $this->release = Release::create([
            'band_id' => $this->band->id,
            'slug' => 'first-demo',
            'title' => 'First Demo',
            'release_type' => 'Demo',
            'original_release_year' => 2016,
            'description' => 'Our very first raw demo tape.',
            'track_count' => 1
        ]);

        // Connect Release to Label
        $this->release->labels()->attach($this->label->id, [
            'catalog_number' => 'RR-001',
            'press_year' => 2016,
            'format' => 'Kaset',
            'press_type' => 'Original Press',
            'notes' => 'Limited to 100 copies.'
        ]);

        // Create Track
        $track = Track::create([
            'release_id' => $this->release->id,
            'track_number' => 1,
            'title' => 'Anarchy Song',
            'duration' => 120,
            'lyrics' => 'Anarchy in the city...',
            'lyrics_translation' => 'Kekacauan di kota...'
        ]);

        // Create mock Organizer
        $organizer = Organizer::create([
            'slug' => 'diy-collective',
            'name' => 'DIY Collective',
            'city' => 'Bandung',
            'description' => 'A D.I.Y gig organizer.'
        ]);

        // Create mock Gig
        $this->gig = Gig::create([
            'slug' => 'bandung-noise-fest',
            'title' => 'Bandung Noise Fest',
            'date' => date('Y-m-d', strtotime('+7 days')),
            'start_time' => '18:00:00',
            'venue_name' => 'Noise House',
            'venue_address' => 'Noise Street No. 42',
            'city' => 'Bandung',
            'ticket_price' => 50000.00,
            'ticket_info' => 'Presale 50k',
            'organizer_id' => $organizer->id
        ]);

        // Connect Gig to Band and Label
        $this->gig->bands()->attach($this->band->id, ['performance_order' => 1]);
        $this->gig->labels()->attach($this->label->id, ['partnership_role' => 'Media Partner']);

        // Create mock UserLog
        UserLog::create([
            'user_id' => $user->id,
            'action' => 'Penambahan',
            'message' => $user->name . ' menambahkan band ' . $this->band->name
        ]);
    }

    /** @test */
    public function guests_can_view_homepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
                 ->assertSee('MIXTAPESIDE')
                 ->assertSee('First Demo')
                 ->assertSee('Bandung Noise Fest');
    }

    /** @test */
    public function guests_can_view_bands_directory()
    {
        $response = $this->get('/band');

        $response->assertStatus(200)
                 ->assertSee('Direktori Band')
                 ->assertSee('The Punk Band');
    }

    /** @test */
    public function guests_can_view_band_profile()
    {
        $response = $this->get('/band/the-punk-band');

        $response->assertStatus(200)
                 ->assertSee('The Punk Band')
                 ->assertSee('Formed in Bandung in 2015.')
                 ->assertSee('First Demo');
    }

    /** @test */
    public function guests_can_view_release_details()
    {
        $response = $this->get('/band/the-punk-band/first-demo');

        $response->assertStatus(200)
                 ->assertSee('First Demo')
                 ->assertSee('The Punk Band')
                 ->assertSee('Anarchy Song');
    }

    /** @test */
    public function guests_can_view_label_profile()
    {
        $response = $this->get('/label/rock-records');

        $response->assertStatus(200)
                 ->assertSee('Rock Records')
                 ->assertSee('A legendary local record label.')
                 ->assertSee('First Demo');
    }

    /** @test */
    public function guests_can_search_via_discovery()
    {
        $response = $this->get('/discovery?q=Punk');

        $response->assertStatus(200);
        $response->assertSee('hasil pencarian untuk');
        $response->assertSee('The Punk Band');
    }

    /** @test */
    public function guests_can_view_gigs_index()
    {
        $response = $this->get('/gigs');

        $response->assertStatus(200)
                 ->assertSee('Kalender Gigs Dan Acara')
                 ->assertSee('Bandung Noise Fest');
    }

    /** @test */
    public function guests_can_view_gig_details()
    {
        $response = $this->get('/gigs/bandung-noise-fest');

        $response->assertStatus(200)
                 ->assertSee('Bandung Noise Fest')
                 ->assertSee('Noise House')
                 ->assertSee('The Punk Band')
                 ->assertSee('Media Partner');
    }

    /** @test */
    public function band_directory_json_api()
    {
        $response = $this->json('GET', '/band');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'msg',
                     'data' => [
                         'current_page',
                         'data' => [
                             '*' => [
                                 'id',
                                 'slug',
                                 'name'
                             ]
                         ]
                     ]
                 ]);
    }
}
