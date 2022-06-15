<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_money', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_peserta')->unsigned();
            $table->foreign('id_peserta')->references('id')->on('peserta')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('year_hijriah', 4)->nullable();
            $table->integer('amount')->unsigned()->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_money_received')->nullable()->default(false);
            $table->boolean('is_money_box_sent')->nullable()->default(false);
            $table->dateTime('date_money_received')->nullable();
            $table->dateTime('date_money_box_sent')->nullable();
            $table->bigInteger('id_user_money_box_sent')->unsigned()->nullable();
            $table->foreign('id_user_money_box_sent')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user_money_received')->unsigned()->nullable();
            $table->foreign('id_user_money_received')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user_amount_updated')->unsigned()->nullable();
            $table->foreign('id_user_amount_updated')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_money');
    }
}
