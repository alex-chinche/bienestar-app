<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restricts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('application_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('application_id')
                ->references('id')->on('applications');
            $table->time('max-possible-hour');
            $table->time('min-possible-hour');
            $table->time('max-time-used');
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
        Schema::dropIfExists('restricts');
    }
}
