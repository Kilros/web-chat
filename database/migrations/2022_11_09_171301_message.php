<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Message extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('outgoing_msg_id');
            $table->foreign('outgoing_msg_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('incoming_msg_id');
            $table->foreign('incoming_msg_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('style');
            $table->longText('msg');
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
        Schema::dropIfExists('messages');
    }
}
