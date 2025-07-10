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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('clientName');
            $table->string('storyTitle');
            $table->text('storyInfo')->nullable();
            $table->string('storyGenre');
            $table->string('wordCount');
            $table->string('submissionStrategy')->nullable();
            $table->string('strategyNotes')->nullable();
            $table->boolean('finalVersionSaved')->nullable();
            $table->boolean('wordCountChecked')->default(0);
            $table->string('doNotSubmitList')->nullable();
            $table->string('correspondence')->nullable();
            $table->text('notesFromClient');
            $table->text('storyBlurb')->nullable();
            $table->text('coverLetter')->nullable();
            $table->text('files')->nullable();
            $table->string('submissionPackageId')->nullable();
            $table->string('fictionPackageId')->nullable();
            $table->string('editingPackageId')->nullable();
            $table->text('customizeService')->nullable();
            $table->string('extraServicesId')->nullable();
            $table->boolean('status')->default('1');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('stories');
    }
};
