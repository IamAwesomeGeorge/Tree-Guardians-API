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
        Schema::table('note_image', function (Blueprint $table) {
            $table->foreign(['id_note'], 'note_image_ibfk_1')->references(['id'])->on('note')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_image', function (Blueprint $table) {
            $table->dropForeign('note_image_ibfk_1');
        });
    }
};
