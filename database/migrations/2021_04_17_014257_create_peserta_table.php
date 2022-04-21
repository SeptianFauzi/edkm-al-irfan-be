<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable();
            $table->boolean('is_person')->nullable()->default(false);
            $table->boolean('service_money')->nullable()->default(false);
            $table->boolean('service_zakat_sent')->nullable()->default(false);
            $table->boolean('service_zakat_received')->nullable()->default(false);
            $table->boolean('service_qurban_sent')->nullable()->default(false);
            $table->boolean('service_qurban_received')->nullable()->default(false);
            $table->text('url_qrcode')->nullable();
            $table->text('notes')->nullable();
            $table->string('phone', 15)->nullable();
            $table->boolean('status')->nullable()->default(true);
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
        Schema::dropIfExists('peserta');
    }
}
