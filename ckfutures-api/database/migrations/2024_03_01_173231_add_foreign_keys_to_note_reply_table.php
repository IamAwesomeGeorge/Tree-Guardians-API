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
        Schema::table('note_reply', function (Blueprint $table) {
            $table->foreign(['id_note'], 'note_reply_ibfk_1')->references(['id'])->on('note')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['id_user'], 'note_reply_ibfk_2')->references(['id'])->on('user')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_reply', function (Blueprint $table) {
            $table->dropForeign('note_reply_ibfk_1');
            $table->dropForeign('note_reply_ibfk_2');
        });
    }
};
