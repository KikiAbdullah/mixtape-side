<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseLabelsTable extends Migration
{
    public function up()
    {
        Schema::create('release_labels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('release_id');
            $table->unsignedBigInteger('label_id');
            $table->string('catalog_number', 100)->nullable();
            $table->integer('press_year');
            $table->string('format', 50);
            $table->string('press_type', 50)->default('Original Press');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('release_labels');
    }
}
