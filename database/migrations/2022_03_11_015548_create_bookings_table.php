<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id');
            $table->string('email')->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("birth_date")->nullable();
            $table->string('phone')->nullable();
            $table->string("message")->nullable();
            $table->string("started_at");
            $table->date("date");
            $table->time("time");
            $table->integer("duration");
            $table->char("is_delete")->default("N");
            $table->string("type")->default("green");
            $table->integer("service_id")->nullable();
            $table->integer("pesubox_id")->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
