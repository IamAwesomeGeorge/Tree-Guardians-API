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
        Schema::table('tree', function (Blueprint $table) {
            $table->foreign(['id_user'], 'tree_ibfk_1')->references(['id'])->on('user')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['species'], 'tree_ibfk_2')->references(['species'])->on('species')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['health_status'], 'tree_ibfk_3')->references(['health_status'])->on('health_status')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tree', function (Blueprint $table) {
            $table->dropForeign('tree_ibfk_1');
            $table->dropForeign('tree_ibfk_2');
            $table->dropForeign('tree_ibfk_3');
        });
    }
};
