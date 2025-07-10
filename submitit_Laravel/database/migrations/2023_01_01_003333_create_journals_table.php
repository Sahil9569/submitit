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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('jrnlName');
            $table->string('rating');
            $table->string('date');
            $table->boolean('fictionSubmission');
            $table->boolean('nonFictionSubmission');
            $table->boolean('fictionOnly');
            $table->boolean('nonFictionOnly');
            $table->boolean('onlineSubmission');
            $table->boolean('simultaneousSubmission');
            $table->boolean('defunct');
            $table->boolean('notIncluded');
            $table->boolean('possibleThemes');
            $table->string('flash');
            $table->string('yearOfInception');
            $table->string('acceptancePercent');
            $table->string('submissionVolume');
            $table->string('minimumLength');
            $table->string('maximumLength');
            $table->string('permanentThemes');
            $table->string('notesAboutJournal');
            $table->string('files')->nullable();
            $table->string('dummyStory')->nullable();
            $table->string('theme');
            $table->string('submissionPlatform');
            $table->string('submissionGuidelines');
            $table->string('submissionCost');
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
        Schema::dropIfExists('journals');
    }
};
