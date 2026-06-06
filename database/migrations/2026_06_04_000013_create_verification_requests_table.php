<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('target_type', 50);
            $table->unsignedBigInteger('target_id');
            $table->text('relationship_desc');
            $table->text('verification_documents')->nullable();
            $table->string('status', 50)->default('Pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_requests');
    }
}
