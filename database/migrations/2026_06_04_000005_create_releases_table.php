<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('band_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('release_type', 50);
            $table->string('cover_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->integer('original_release_year');
            $table->text('description')->nullable();
            $table->integer('track_count')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('releases');
    }
}
