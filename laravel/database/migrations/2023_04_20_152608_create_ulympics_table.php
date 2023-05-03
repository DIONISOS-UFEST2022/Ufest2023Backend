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
        Schema::create('ulympics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('namaTim')->unique();
            $table->string('ketua')->default('none');
            $table->smallInteger('jumlahMember');
            $table->string("buktiPembayaran")->nullable();
            $table->string('tokenID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ulympics');
    }
};
