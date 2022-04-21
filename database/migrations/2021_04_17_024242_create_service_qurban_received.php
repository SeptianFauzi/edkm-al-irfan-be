<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceQurbanReceived extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_qurban_received', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_peserta')->unsigned();
            $table->foreign('id_peserta')->references('id')->on('peserta')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('year_hijriah', 4)->nullable();
            $table->float('amount_received')->unsigned()->nullable()->default(0);
            $table->string('amount_type', 255)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_qurban_received')->nullable()->default(false);
            $table->bigInteger('id_user_qurban_received')->unsigned()->nullable();
            $table->foreign('id_user_qurban_received')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user_amount_received_updated')->unsigned()->nullable();
            $table->foreign('id_user_amount_received_updated')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('date_qurban_received')->nullable();   
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
        Schema::dropIfExists('service_qurban_received');
    }
}
