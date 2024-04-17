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
        Schema::create('tree_image', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_tree')->index('id_tree');
            $table->integer('id_user')->nullable()->index('id_user');
            $table->dateTime('upload_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree_image');
    }
};
