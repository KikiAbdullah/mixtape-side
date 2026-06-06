<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('release_id');
            $table->integer('track_number');
            $table->string('title');
            $table->string('duration', 20)->nullable();
            $table->text('lyrics')->nullable();
            $table->text('lyrics_translation')->nullable();
            $table->timestamps();

            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->unique(['release_id', 'track_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
