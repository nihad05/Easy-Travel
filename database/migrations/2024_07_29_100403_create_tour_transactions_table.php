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
        Schema::create('tour_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('price')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            //            $table->foreign('tour_id')->references('id')->on('tours')
            //                ->onDelete('cascade');
            //            $table->foreign('user_id')->references('id')->on('users')
            //                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_transactions');
    }
};
