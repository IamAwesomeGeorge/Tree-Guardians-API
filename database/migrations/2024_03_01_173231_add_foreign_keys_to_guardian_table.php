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
        Schema::table('guardian', function (Blueprint $table) {
            $table->foreign(['id_user'], 'guardian_ibfk_1')->references(['id'])->on('user')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['id_tree'], 'guardian_ibfk_2')->references(['id'])->on('tree')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guardian', function (Blueprint $table) {
            $table->dropForeign('guardian_ibfk_1');
            $table->dropForeign('guardian_ibfk_2');
        });
    }
};
