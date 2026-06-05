<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationRequestsAndDataDraftsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verification Requests (Claim profile verification queue)
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('target_type', 50); // band, label, organizer
            $table->unsignedBigInteger('target_id');
            $table->text('relationship_desc');
            $table->text('verification_documents')->nullable(); // stored as JSON string/comma-separated URLs
            $table->string('status', 50)->default('Pending'); // Pending, Approved, Rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Data Drafts (Crowdsourced data contribution queue)
        Schema::create('data_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('target_table', 50); // bands, releases, tracks, etc.
            $table->unsignedBigInteger('target_id')->nullable(); // null if creating a new entry
            $table->json('proposed_data'); // JSON payload of the changes
            $table->string('status', 50)->default('Pending'); // Pending, Applied, Rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_drafts');
        Schema::dropIfExists('verification_requests');
    }
}
