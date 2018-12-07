<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->enum('tempUnit', ['c', 'f'])->default('c');
            $table->enum('windSpeedUnit', ['mph', 'kph', 'm/s'])->default('kph');
            $table->integer('minTemp')->default(-10);
            $table->integer('maxTemp')->default(30);
            $table->string('timezone')->default('Europe/London');
            $table->json('disasterCategories')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
