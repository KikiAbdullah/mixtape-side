<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBandsAndLabelsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Bands Table
        Schema::create('bands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('alternative_names')->nullable(); // stored as JSON/comma-separated for MySQL compat
            $table->string('logo_url')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('city', 100);
            $table->string('country', 100)->default('Indonesia');
            $table->integer('formed_year');
            $table->integer('disbanded_year')->nullable();
            $table->string('status', 50)->default('Active'); // Active, On Hold, Split-up
            $table->text('genre'); // stored as JSON or comma-separated string for multi-genre
            $table->text('biography')->nullable();
            $table->json('social_links')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
        });

        // Labels Table
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->string('city', 100);
            $table->integer('formed_year');
            $table->integer('defunct_year')->nullable();
            $table->string('status', 50)->default('Active'); // Active, Inactive
            $table->string('contact_email')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
        });

        // Band Members Table
        Schema::create('band_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('band_id');
            $table->string('name');
            $table->unsignedBigInteger('linked_user_id')->nullable();
            $table->string('role_instrument', 100);
            $table->integer('join_year');
            $table->integer('leave_year')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();

            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
            $table->foreign('linked_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('band_members');
        Schema::dropIfExists('labels');
        Schema::dropIfExists('bands');
    }
}
