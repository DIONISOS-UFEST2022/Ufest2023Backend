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
        Schema::create('panitia', function (Blueprint $table) {
            $table->id();
            $table->string("nim", 11)->unique();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("program_studi");
            $table->string("angkatan");
            $table->string("vaccine_certificate")->nullable()->default('none');
            $table->string('division_1');
            $table->string('division_2');
            $table->string("phone_number")->unique();
            $table->string("reason_1");
            $table->string("reason_2");
            $table->string("portofolio")->nullable();
            $table->string("id_line");
            $table->string("instagram_account")->nullable();
            $table->string("city");
            $table->boolean("is_accepted")->comment('0:rejected, 1:accepted')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('panitia');
    }
};
