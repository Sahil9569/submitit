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
        Schema::create('story_scores', function (Blueprint $table) {
            $table->id();
            $table->string('quality');
            $table->string('stylized');
            $table->string('difficulty');
            $table->string('topical');
            $table->string('experimental');
            $table->string('humor');
            $table->string('strange');
            $table->string('dark');
            $table->string('international');
            $table->string('margins');
            $table->string('historical');
            $table->string('long');
            $table->string('short');
            $table->unsignedBigInteger('story_id');
            $table->foreign('story_id')->references('id')->on('stories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_scores');
    }
};
