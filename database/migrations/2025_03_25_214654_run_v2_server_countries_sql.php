<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sqlFilePath = database_path('sql/v2_server_countries.sql'); // Path to your SQL file
        
        if (File::exists($sqlFilePath)) {
            $sql = File::get($sqlFilePath);
            DB::unprepared($sql); // Run the SQL file
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new table
        Schema::dropIfExists('v2_server_countries');
    }
};
