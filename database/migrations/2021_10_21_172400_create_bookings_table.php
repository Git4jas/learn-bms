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
            $table->bigIncrements('booking_id');
            $table->unsignedBigInteger('consultant_user_id')->index();
            $table->unsignedBigInteger('customer_user_id')->index();
            $table->unsignedBigInteger('assistance_id')->index();
            $table->unsignedBigInteger('booking_status_id')->index();
            $table->dateTime('session_start_time', 0)->nullable();
            $table->dateTime('session_end_time', 0)->nullable();
            $table->unsignedDecimal('cost_per_session', 10, 2)->nullable()->default(0);
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
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
