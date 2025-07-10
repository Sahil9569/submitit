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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('doNotSubmitList')->nullable();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('penName')->nullable();
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('zipCode');
            $table->string('phone');
            $table->string('email');
            $table->string('hearAboutUs')->nullable();
            $table->text('notes')->nullable();
            $table->text('bio')->nullable();
            $table->string('superPackage')->nullable();
            $table->string('referral')->nullable();
            $table->string('sharedEmail')->nullable();
            $table->string('stripe_id')->nullable();
            $table->string('password');
            $table->string('status')->default('1');
            $table->string('roleAs')->default('normal');
            $table->string('userType')->default('client');
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
        Schema::dropIfExists('users');
    }
};
