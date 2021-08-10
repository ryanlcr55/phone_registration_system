<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('store_name', 100);
            $table->string('store_code', 100)->unique()->nullable();
            $table->float('lat', '9', '7');
            $table->float('lon', '10', '7');
            $table->timestamps();

            $table->index('store_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_codes');
    }
}
