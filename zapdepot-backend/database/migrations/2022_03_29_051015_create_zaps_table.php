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
        Schema::create('zaps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sender_name')->nullable();
            $table->string('receiver_name')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->integer('sender_id')->nullable();
            $table->integer('status')->nullable();
            $table->integer('user_id');
            $table->tinyInteger('data_transfer_status')->default(0)->comment("1:Yes,0:No");
            $table->string('get_val',50)->nullable();
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
        Schema::dropIfExists('zaps');
    }
};
