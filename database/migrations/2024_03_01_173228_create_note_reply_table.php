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
        Schema::create('note_reply', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_note')->index('id_note');
            $table->integer('id_user')->nullable()->index('id_user');
            $table->dateTime('date')->nullable();
            $table->string('text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_reply');
    }
};
