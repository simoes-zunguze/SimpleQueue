<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->timestamps();
        });

        Schema::create('services_queue', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("service_id")->unsigned();
            $table->bigInteger("queue_id")->unsigned();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('queue_id')->references('id')->on('queues');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_queue');
        Schema::dropIfExists('services');
    }
}
