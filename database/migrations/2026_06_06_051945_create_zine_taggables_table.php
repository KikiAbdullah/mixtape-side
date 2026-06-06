<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZineTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('zine_taggables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zine_id');
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            $table->timestamps();

            $table->foreign('zine_id')->references('id')->on('zines')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('zine_taggables');
    }
}
