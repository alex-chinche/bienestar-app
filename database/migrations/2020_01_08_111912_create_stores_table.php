<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('application_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('application_id')
                ->references('id')->on('applications');
            $table->double('open_latitude');
            $table->double('open_longitude');
            $table->double('close_latitude');
            $table->double('close_longitude');
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
        Schema::dropIfExists('stores');
    }
}
