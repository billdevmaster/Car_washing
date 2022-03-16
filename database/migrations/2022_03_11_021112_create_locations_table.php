<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->time("Mon_start")->nullable();
            $table->time("Mon_end")->nullable();
            $table->time("Tue_start")->nullable();
            $table->time("Tue_end")->nullable();
            $table->time("Wed_start")->nullable();
            $table->time("Wed_end")->nullable();
            $table->time("Thu_start")->nullable();
            $table->time("Thu_end")->nullable();
            $table->time("Fri_start")->nullable();
            $table->time("Fri_end")->nullable();
            $table->time("Sat_start")->nullable();
            $table->time("Sat_end")->nullable();
            $table->time("Sun_start")->nullable();
            $table->time("Sun_end")->nullable();
            $table->integer("interval")->nullable();
            $table->string("address")->nullable();
            $table->string("street")->nullable();
            $table->string("city")->nullable();
            $table->char("is_delete")->default("N");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
