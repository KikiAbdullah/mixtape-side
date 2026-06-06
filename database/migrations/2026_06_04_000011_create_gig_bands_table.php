<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigBandsTable extends Migration
{
    public function up()
    {
        Schema::create('gig_bands', function (Blueprint $table) {
            $table->unsignedBigInteger('gig_id');
            $table->unsignedBigInteger('band_id');
            $table->integer('performance_order')->nullable();

            $table->primary(['gig_id', 'band_id']);
            $table->foreign('gig_id')->references('id')->on('gigs')->onDelete('cascade');
            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gig_bands');
    }
}
