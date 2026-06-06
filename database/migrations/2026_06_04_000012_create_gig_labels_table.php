<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigLabelsTable extends Migration
{
    public function up()
    {
        Schema::create('gig_labels', function (Blueprint $table) {
            $table->unsignedBigInteger('gig_id');
            $table->unsignedBigInteger('label_id');
            $table->string('partnership_role', 100)->default('Sponsor');

            $table->primary(['gig_id', 'label_id']);
            $table->foreign('gig_id')->references('id')->on('gigs')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gig_labels');
    }
}
