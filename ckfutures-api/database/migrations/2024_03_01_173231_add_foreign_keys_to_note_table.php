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
        Schema::table('note', function (Blueprint $table) {
            $table->foreign(['id_tree'], 'note_ibfk_1')->references(['id'])->on('tree')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['id_user'], 'note_ibfk_2')->references(['id'])->on('user')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note', function (Blueprint $table) {
            $table->dropForeign('note_ibfk_1');
            $table->dropForeign('note_ibfk_2');
        });
    }
};
