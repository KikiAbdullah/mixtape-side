<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelsTable extends Migration
{
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('city', 100);
            $table->integer('formed_year');
            $table->integer('defunct_year')->nullable();
            $table->string('status', 50)->default('Active');
            $table->string('contact_email')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('labels');
    }
}
