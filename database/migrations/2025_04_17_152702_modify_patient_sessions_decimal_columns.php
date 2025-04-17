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
        // For MySQL/MariaDB: Change column types to decimal with 2 decimal places
        // but enough total digits to handle large numbers
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE patient_sessions MODIFY weight DECIMAL(15,2) NULL');
            DB::statement('ALTER TABLE patient_sessions MODIFY fats_rate DECIMAL(15,2) NULL');
            DB::statement('ALTER TABLE patient_sessions MODIFY burn_rate DECIMAL(15,2) NULL');
        } 
        // For PostgreSQL
        else if (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE patient_sessions ALTER COLUMN weight TYPE DECIMAL(15,2)');
            DB::statement('ALTER TABLE patient_sessions ALTER COLUMN fats_rate TYPE DECIMAL(15,2)');
            DB::statement('ALTER TABLE patient_sessions ALTER COLUMN burn_rate TYPE DECIMAL(15,2)');
        }
        // For other database systems
        else {
            Schema::table('patient_sessions', function (Blueprint $table) {
                $table->decimal('weight', 15, 2)->nullable()->change();
                $table->decimal('fats_rate', 15, 2)->nullable()->change();
                $table->decimal('burn_rate', 15, 2)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If you need to revert to previous format (double with many decimal places)
        Schema::table('patient_sessions', function (Blueprint $table) {
            $table->double('weight', 15, 10)->nullable()->change();
            $table->double('fats_rate', 15, 10)->nullable()->change();
            $table->double('burn_rate', 15, 10)->nullable()->change();
        });
    }
};