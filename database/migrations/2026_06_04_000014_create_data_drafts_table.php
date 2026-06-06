<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataDraftsTable extends Migration
{
    public function up()
    {
        Schema::create('data_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('target_table', 50);
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('proposed_data');
            $table->string('status', 50)->default('Pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_drafts');
    }
}
