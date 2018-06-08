<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('user_id');
            $table->unsignedinteger('channel_id');
            $table->string('title');
            $table->text('body');
            $table->timestamps();
            // $table->unsignedInteger('best_reply_id')->nullable();
            // $table->foreign('best_reply_id')
            //     ->references('id')
            //     ->on('replies')
            //     ->onDelete('set null')
            // ;
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
