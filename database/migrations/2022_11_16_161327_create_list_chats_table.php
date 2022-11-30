<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('outgoing_id');
            $table->foreign('outgoing_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('incoming_id');
            $table->foreign('incoming_id')->references('id')->on('users')->onDelete('cascade');
            // $table->timestamps('last_sent');
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
        Schema::dropIfExists('list_chats');
    }
}
