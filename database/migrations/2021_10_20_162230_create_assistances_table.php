<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->bigIncrements('assistance_id');
            $table->string('label');
            $table->unsignedBigInteger('assistance_category_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('description');
            $table->string('image')->nullable();
            $table->unsignedDecimal('cost_per_session', 10, 2);
            $table->nullableTimestamps(0);
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
        Schema::dropIfExists('assistances');
    }
}
