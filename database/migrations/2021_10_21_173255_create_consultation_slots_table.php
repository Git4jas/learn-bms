<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_slots', function (Blueprint $table) {
            $table->bigIncrements('consultation_slot_id');
            $table->unsignedBigInteger('booking_id')->index();
            $table->dateTime('start_time', 0);
            $table->dateTime('end_time', 0);
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
        Schema::dropIfExists('consultation_slots');
    }
}
