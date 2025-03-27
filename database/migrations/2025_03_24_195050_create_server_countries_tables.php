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

        Schema::create('v2_server_countries', function (Blueprint $table) {
            $table->string('id')->primary(); // varchar NOT NULL
            $table->integer('numeric'); // integer NOT NULL
            $table->string('alpha3'); // varchar NOT NULL
            $table->string('native_name')->nullable(); // varchar
            $table->string('capital')->nullable(); // varchar
            $table->string('top_level_domain')->nullable(); // varchar
            $table->string('calling_code')->nullable(); // varchar
            $table->string('region')->nullable(); // varchar
            $table->string('subregion')->nullable(); // varchar
            $table->float('lat')->nullable(); // float
            $table->float('lon')->nullable(); // float
            $table->string('demonym')->nullable(); // varchar
            $table->integer('area')->nullable(); // integer
            $table->integer('population')->nullable(); // integer
            $table->string('emoji_flag')->nullable(); // varchar
            $table->boolean('is_independent')->default(0); // tinyint(1)
            $table->boolean('is_un_member')->default(0); // tinyint(1)
            $table->boolean('is_eu_member')->default(0); // tinyint(1)
            $table->string('ioc')->nullable(); // varchar
            $table->string('fifa')->nullable(); // varchar
            $table->boolean('show')->default(0); // tinyint(1)

            $table->index('alpha3', 'countries_alpha3_index');
            $table->index('fifa', 'countries_fifa_index');
            $table->index('ioc', 'countries_ioc_index');
            $table->index('numeric', 'countries_numeric_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // // Drop new table
        // Schema::dropIfExists('v2_server_countries');

        //
        Schema::dropIfExists('v2_server_countries');
    }
};
