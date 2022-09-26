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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('alternate_phone');
            $table->string('email')->unique();
            $table->boolean('is_aadharcard_verified')->default(false);
            $table->boolean('is_pan_verified')->default(false);
            $table->string('aadhar_card_number')->unique()->nullable();
            $table->string('pan_card_number')->unique()->nullable();
            $table->string('aadhar_card_url')->nullable();
            $table->string('pan_card_url')->nullable();
            $table->string('line_one_address')->nullable();
            $table->string('line_two_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('is_active')->nullable();
            $table->string('github_link')->nullable();
            $table->string('linkedin')->nullable();
            $table->double('annual_pay')->nullable();
            $table->boolean('is_rejoined')->default(false)->nullable();
            $table->date('date_of_join');



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
        Schema::dropIfExists('instructors');
    }
};
