<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bands', function (Blueprint $table) {
            $table->string('banner_url')->nullable()->after('logo_url');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->string('banner_url')->nullable()->after('logo_url');
        });

        Schema::table('releases', function (Blueprint $table) {
            $table->string('banner_url')->nullable()->after('cover_url');
        });

        Schema::table('gigs', function (Blueprint $table) {
            $table->string('banner_url')->nullable()->after('poster_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bands', function (Blueprint $table) {
            $table->dropColumn('banner_url');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->dropColumn('banner_url');
        });

        Schema::table('releases', function (Blueprint $table) {
            $table->dropColumn('banner_url');
        });

        Schema::table('gigs', function (Blueprint $table) {
            $table->dropColumn('banner_url');
        });
    }
}
