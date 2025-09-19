<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to drop and recreate the constraint
        // First, drop the existing check constraint to allow data updates
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        
        // Now update any existing 'provider' role to 'individual_provider'
        DB::statement("UPDATE users SET role = 'individual_provider' WHERE role = 'provider'");
        
        // Add the new check constraint with all 5 roles
        DB::statement("
            ALTER TABLE users 
            ADD CONSTRAINT users_role_check 
            CHECK (role IN (
                'customer', 
                'individual_provider', 
                'company_provider', 
                'admin', 
                'editor'
            ))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert all new roles back to old system
        DB::statement("UPDATE users SET role = 'provider' WHERE role IN ('individual_provider', 'company_provider')");
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'editor'");
        
        // Drop the new constraint
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        
        // Add back the original constraint
        DB::statement("
            ALTER TABLE users 
            ADD CONSTRAINT users_role_check 
            CHECK (role IN ('customer', 'provider', 'admin'))
        ");
    }
};