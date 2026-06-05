<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesAndTracksTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Releases Table
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('band_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('release_type', 50); // Album, EP, Single, Demo, Split, Compilation
            $table->string('cover_url')->nullable();
            $table->integer('original_release_year');
            $table->text('description')->nullable();
            $table->integer('track_count')->default(0);
            $table->timestamps();

            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
            $table->index('slug');
        });

        // Release Labels Table (Bridge for multi-label and reissue)
        Schema::create('release_labels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('release_id');
            $table->unsignedBigInteger('label_id');
            $table->string('catalog_number', 100)->nullable();
            $table->integer('press_year');
            $table->string('format', 50); // Kaset, CD, Vinyl, Digital, Boxset
            $table->string('press_type', 50)->default('Original Press'); // Original Press, Re-issue, Repress
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });

        // Tracks Table
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('release_id');
            $table->integer('track_number');
            $table->string('title');
            $table->integer('duration')->nullable(); // duration in seconds
            $table->text('lyrics')->nullable();
            $table->text('lyrics_translation')->nullable();
            $table->timestamps();

            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->unique(['release_id', 'track_number']);
        });

        // Track Contributors Table (Granular credits per track)
        Schema::create('track_contributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('track_id');
            $table->string('name');
            $table->string('role', 100); // Songwriter, Composer, Guest Vocalist, Audio Engineer
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_contributors');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('release_labels');
        Schema::dropIfExists('releases');
    }
}
