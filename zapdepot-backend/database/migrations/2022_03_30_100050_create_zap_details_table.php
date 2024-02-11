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
        Schema::create('zap_details', function (Blueprint $table) {
            $table->id();
            $table->integer('zap_id');
            $table->string('event_type');
            $table->string('interation_type');
            $table->string('action_type')->nullable();
            $table->text('tag_id')->nullable();
            $table->text('list_id')->nullable();
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
        Schema::dropIfExists('zap_details');
    }
};
