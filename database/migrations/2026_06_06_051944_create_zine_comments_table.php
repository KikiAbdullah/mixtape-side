<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZineCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('zine_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zine_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->timestamps();

            $table->foreign('zine_id')->references('id')->on('zines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('zine_comments');
    }
}
