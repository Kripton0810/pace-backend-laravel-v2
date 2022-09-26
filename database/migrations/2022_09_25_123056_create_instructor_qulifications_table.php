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
        Schema::create('instructor_qulifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('roll_no')->nullable();
            $table->double('marks')->nullable(); //in percentage
            $table->string('document_url')->nullable();
            $table->date('course_started')->nullable();
            $table->date('course_ended')->nullable();
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->foreign('instructor_id')->references('id')->on('instructors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructor_qulifications');
    }
};
