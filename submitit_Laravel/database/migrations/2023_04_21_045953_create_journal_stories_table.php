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
        Schema::create('journal_stories', function (Blueprint $table) {
            $table->id();
            $table->string('storyTitle');
            $table->string('year');
            $table->string('isAnthologies')->default(0);
            $table->string('anthologies')->nullable();
            $table->string('dummyStory')->default(0);
            $table->string('quality')->default(1);
            $table->string('stylized')->default(1);
            $table->string('difficulty')->default(1);
            $table->string('topical')->default(1);
            $table->string('experimental')->default(1);
            $table->string('humor')->default(1);
            $table->string('strange')->default(1);
            $table->string('dark')->default(1);
            $table->string('international')->default(1);
            $table->string('margins')->default(1);
            $table->string('historical')->default(1);
            $table->string('long')->default(1);
            $table->string('short')->default(1);
            $table->foreignId('journal_id')->constrained();
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
        Schema::dropIfExists('journal_stories');
    }
};
