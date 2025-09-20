<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix model_has_roles table to support UUID model_id
        
        // First, clear existing data since we can't cast bigint to uuid directly
        DB::table('model_has_roles')->truncate();
        
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Drop the composite primary key first
            $table->dropPrimary('model_has_roles_pkey');
            
            // Drop the existing model_id column and recreate it as UUID
            $table->dropColumn('model_id');
        });
        
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Add model_id as UUID column 
            $table->uuid('model_id');
            
            // Recreate the composite primary key
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Fix model_has_permissions table to support UUID model_id
        
        // First, clear existing data since we can't cast bigint to uuid directly
        DB::table('model_has_permissions')->truncate();
        
        Schema::table('model_has_permissions', function (Blueprint $table) {
            // Drop the composite primary key first
            $table->dropPrimary('model_has_permissions_pkey');
            
            // Drop the existing model_id column and recreate it as UUID
            $table->dropColumn('model_id');
        });
        
        Schema::table('model_has_permissions', function (Blueprint $table) {
            // Add model_id as UUID column 
            $table->uuid('model_id');
            
            // Recreate the composite primary key
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert model_has_roles table back to bigint model_id
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Drop the composite primary key first
            $table->dropPrimary('model_has_roles_pkey');
            
            // Change model_id column back to unsignedBigInteger
            $table->unsignedBigInteger('model_id')->change();
            
            // Recreate the composite primary key
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Revert model_has_permissions table back to bigint model_id
        Schema::table('model_has_permissions', function (Blueprint $table) {
            // Drop the composite primary key first
            $table->dropPrimary('model_has_permissions_pkey');
            
            // Change model_id column back to unsignedBigInteger
            $table->unsignedBigInteger('model_id')->change();
            
            // Recreate the composite primary key
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });
    }
};
