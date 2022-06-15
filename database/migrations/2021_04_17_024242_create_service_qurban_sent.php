<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceQurbanSent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_qurban_sent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_peserta')->unsigned();
            $table->foreign('id_peserta')->references('id')->on('peserta')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('year_hijriah', 4)->nullable();
            $table->float('amount_sent')->unsigned()->nullable()->default(0);
            $table->string('amount_type', 255)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_qurban_sent')->nullable()->default(false);
            $table->bigInteger('id_user_qurban_sent')->unsigned()->nullable();
            $table->foreign('id_user_qurban_sent')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user_amount_sent_updated')->unsigned()->nullable();
            $table->foreign('id_user_amount_sent_updated')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('date_qurban_sent')->nullable();   
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
        Schema::dropIfExists('service_qurban_sent');
    }
}
