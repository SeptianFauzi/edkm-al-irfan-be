<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceZakatSentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_zakat_sent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_peserta')->unsigned();
            $table->foreign('id_peserta')->references('id')->on('peserta')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('year_hijriah', 4)->nullable();
            $table->float('amount_sent')->unsigned()->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_zakat_sent')->nullable()->default(false);
            $table->bigInteger('id_user_zakat_sent')->unsigned()->nullable();
            $table->foreign('id_user_zakat_sent')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_user_amount_sent_updated')->unsigned()->nullable();
            $table->foreign('id_user_amount_sent_updated')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('date_zakat_sent')->nullable();   
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
        Schema::dropIfExists('service_zakat_sent');
    }
}
