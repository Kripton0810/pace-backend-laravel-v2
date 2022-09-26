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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('course_duration')->nullable(); // in months
            $table->timestamp('start_date_time')->nullable();
            $table->double('price')->nullable();
            $table->double('discount')->nullable();
            $table->double('net_price')->nullable();
            $table->boolean('is_online')->nullable();
            $table->string('venue')->nullable();
            $table->integer('student_enrolled')->nullable();
            $table->integer('likes')->nullable();
            $table->double('rating')->nullable();
            $table->boolean('is_enrollment_active')->nullable();
            $table->boolean('is_recording_available')->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('google_classroom_url')->nullable();
            $table->string('whatsapp_group')->nullable();
            $table->string('telegram_group')->nullable();
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->timestamps();
            $table->foreign('course_category_id')->references('id')->on('courses_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
