<?php

use App\Models\sheetEntryCount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetEntryCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheet_entry_counts', function (Blueprint $table) {
            $table->id();
            $table->integer("zap_id")->nullable(); 
            $table->string("sender_tag_list_id")->nullable(); 
            $table->string("receiver_tag_list_id")->nullable(); 
            $table->string("code")->nullable(); 
            $table->timestamps();
        });
        // sheetEntryCount::create([
        //     "code" => 0
        // ]);

    }
       
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheet_entry_counts');
    }
}
