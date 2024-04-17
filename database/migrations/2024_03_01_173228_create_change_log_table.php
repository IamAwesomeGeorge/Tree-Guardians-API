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
        Schema::create('change_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_user')->nullable()->index('id_user');
            $table->dateTime('date')->nullable();
            $table->string('old_values', 512)->nullable();
            $table->string('new_values', 512)->nullable();
            $table->string('table_name', 48)->nullable();
            $table->string('operation', 10)->nullable();
            $table->boolean('approved')->nullable();
            $table->integer('approved_by')->nullable()->index('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_log');
    }
};
