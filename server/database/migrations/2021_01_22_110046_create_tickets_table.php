<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("person_id")->unsigned()->nullable();
            $table->bigInteger("queue_id")->unsigned();
            $table->bigInteger("priority_id")->unsigned()->default(3);
            $table->string("ticket_code");
            $table->bigInteger("period_id")->unsigned();
            $table->bigInteger("status_id")->unsigned();
            $table->dateTime("waiting_call")->nullable(); // The person will be called in this after postponed
            $table->string("details")->nullable();

            $table->foreign("person_id")->references("id")->on("people");
            $table->foreign("queue_id")->references("id")->on("queues");
            $table->foreign("priority_id")->references("id")->on("priorities");
            $table->foreign("period_id")->references("id")->on("periods");
            $table->foreign("status_id")->references("id")->on("statuses");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
