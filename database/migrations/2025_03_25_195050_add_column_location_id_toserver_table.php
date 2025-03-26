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
        //
        Schema::table('v2_server', function (Blueprint $table) {
            // $table->integer('commission_status')->nullable()->default(null)->comment('0待确认1发放中2有效3无效')->change();

            // Make location_id nullable initially
            $table->string('country_id')->nullable()->after('some_existing_column'); // specify the position if necessary
            $table->foreign('country_id')->references('id')->on('v2_server_countries')->onDelete('cascade')->comment('country_id');

            // $table->unsignedInteger('location_id');
            // $table->foreign('location_id')->references('id')->on('v2_server_location')->onDelete('cascade')->comment('location id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('v2_server', function (Blueprint $table) {
            
            // Drop the foreign key constraint first
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
            
            // $table->unsignedInteger('location_id');
            // $table->foreign('location_id')->references('id')->on('v2_server_location')->onDelete('cascade')->comment('location id')->change();
        });
    }
};
