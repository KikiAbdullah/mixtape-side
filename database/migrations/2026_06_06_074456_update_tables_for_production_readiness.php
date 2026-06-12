<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateTablesForProductionReadiness extends Migration
{
    public function up()
    {
        // 1. Add SoftDeletes and Status to main entities
        $tables = ['bands', 'releases', 'labels', 'gigs', 'organizers', 'zines'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                    $table->softDeletes();
                }
                if (!Schema::hasColumn($table->getTable(), 'status')) {
                    $table->string('status', 20)->default('Published')->after('id');
                }
            });
        }

        // 2. Update data_drafts table
        Schema::table('data_drafts', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('target_id');
            $table->json('original_snapshot')->nullable()->after('version');
            $table->string('change_summary')->nullable()->after('proposed_data');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });

        // 3. Create Ownership Claims table
        Schema::create('ownership_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('proof_document')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('Pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'entity_type', 'entity_id'], 'user_entity_index');
        });

        // 4. Update user_logs for detailed audit
        Schema::table('user_logs', function (Blueprint $table) {
            $table->string('actor_id_str')->nullable()->after('id'); // temporary for rename
        });
        
        DB::statement('UPDATE user_logs SET actor_id_str = user_id');
        
        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        
        Schema::table('user_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('actor_id')->nullable()->after('id');
        });

        DB::statement('UPDATE user_logs SET actor_id = actor_id_str');

        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropColumn('actor_id_str');
            $table->string('entity_type')->nullable()->after('action');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->json('old_values')->nullable()->after('entity_id');
            $table->json('new_values')->nullable()->after('old_values');
            $table->string('ip', 45)->nullable()->after('new_values');
            $table->text('user_agent')->nullable()->after('ip');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ownership_claims');
        // Rollback for other changes not implemented for brevity in this task, 
        // as we are aiming for forward migration.
    }
}
