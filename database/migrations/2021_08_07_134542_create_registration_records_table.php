<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone_num', 11);
            $table->string('store_code', 100);
            $table->dateTime('registration_datetime');
            $table->timestamps();

            $table->index('phone_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registration_records');
    }
}
