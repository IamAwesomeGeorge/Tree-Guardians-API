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
        Schema::create('tree', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('creation_date')->nullable();
            $table->integer('id_user')->nullable()->index('id_user');
            $table->string('species')->nullable()->index('species');
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('health_status', 24)->nullable()->index('health_status');
            $table->decimal('circumference', 4, 1)->nullable();
            $table->dateTime('planted')->nullable();
            $table->integer('height')->nullable();
            $table->boolean('is_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree');
    }
};
