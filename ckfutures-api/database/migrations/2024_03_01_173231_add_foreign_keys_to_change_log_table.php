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
        Schema::table('change_log', function (Blueprint $table) {
            $table->foreign(['id_user'], 'change_log_ibfk_1')->references(['id'])->on('user')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['approved_by'], 'change_log_ibfk_2')->references(['id'])->on('user')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('change_log', function (Blueprint $table) {
            $table->dropForeign('change_log_ibfk_1');
            $table->dropForeign('change_log_ibfk_2');
        });
    }
};
