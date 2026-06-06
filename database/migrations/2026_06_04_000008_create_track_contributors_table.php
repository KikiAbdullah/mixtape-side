<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackContributorsTable extends Migration
{
    public function up()
    {
        Schema::create('track_contributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('track_id');
            $table->string('name');
            $table->string('role', 100);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('track_contributors');
    }
}
