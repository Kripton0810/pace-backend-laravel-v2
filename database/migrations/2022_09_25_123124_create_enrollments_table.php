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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('student_id');
            $table->boolean('is_paid');
            $table->boolean('is_emi');
            $table->string('order_id');
            $table->string('payment_id');
            $table->double('amount');
            $table->timestamp('paid_date');
            $table->string('enrollment_id')->unique();
            $table->double('discount')->default(0);
            $table->boolean('is_discount')->default(false);
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('student_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
