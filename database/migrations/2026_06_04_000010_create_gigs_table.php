<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigsTable extends Migration
{
    public function up()
    {
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('poster_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->string('venue_name');
            $table->text('venue_address');
            $table->string('city', 100);
            $table->decimal('ticket_price', 12, 2)->default(0.00);
            $table->string('ticket_info')->nullable();
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('slug');
            $table->index('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gigs');
    }
}
