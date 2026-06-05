<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizersAndGigsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Organizers Table (Collectives, Venues, Promoters)
        Schema::create('organizers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->string('city', 100);
            $table->text('description')->nullable();
            $table->string('contact_info')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
        });

        // Gigs Table
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('poster_url')->nullable();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->string('venue_name');
            $table->text('venue_address');
            $table->string('city', 100);
            $table->decimal('ticket_price', 12, 2)->default(0.00);
            $table->string('ticket_info')->nullable(); // Free, BYOB, Presale 50k
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
            $table->index('date');
        });

        // Gig Bands (Bridge table)
        Schema::create('gig_bands', function (Blueprint $table) {
            $table->unsignedBigInteger('gig_id');
            $table->unsignedBigInteger('band_id');
            $table->integer('performance_order')->nullable();

            $table->primary(['gig_id', 'band_id']);
            $table->foreign('gig_id')->references('id')->on('gigs')->onDelete('cascade');
            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
        });

        // Gig Labels (Bridge table for Sponsors, Tenants, Media Partners)
        Schema::create('gig_labels', function (Blueprint $table) {
            $table->unsignedBigInteger('gig_id');
            $table->unsignedBigInteger('label_id');
            $table->string('partnership_role', 100)->default('Sponsor'); // Sponsor, Media Partner, Booth/Tenant

            $table->primary(['gig_id', 'label_id']);
            $table->foreign('gig_id')->references('id')->on('gigs')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gig_labels');
        Schema::dropIfExists('gig_bands');
        Schema::dropIfExists('gigs');
        Schema::dropIfExists('organizers');
    }
}
