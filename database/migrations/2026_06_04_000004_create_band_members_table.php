<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBandMembersTable extends Migration
{
    public function up()
    {
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

    public function down()
    {
        Schema::dropIfExists('band_members');
    }
}
