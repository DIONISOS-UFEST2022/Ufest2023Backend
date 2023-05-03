<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tim_mobile_legends', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('tokenID');
            $table->string('ketua')->default('none')->nullable();
            $table->string('nama');
            $table->string('jurusan');
            $table->string('angkatan');
            $table->string('userID');
            $table->string('userName');
            $table->string('fotoKtm')->nullable();
            $table->string("buktiWA")->nullable();
            $table->string("phoneNumber")->unique();
            $table->boolean("diterima")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tim_mobile_legends');
    }
};
