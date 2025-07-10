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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('packageName');
            $table->string('stripe_package_id');
            $table->string('transactionId');
            $table->string('amount');
            $table->string('startFrom');
            $table->string('expiredAt');
            $table->string('status');
            $table->foreignId('user_id')->constrained();
            $table->string('subscriptionId');
            $table->unsignedBigInteger('story_id');
            $table->foreign('story_id')->references('id')->on('stories');
            $table->string('type')->nullable();
            $table->boolean('isCanceled')->default(0);
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
        Schema::dropIfExists('invoices');
    }
};
