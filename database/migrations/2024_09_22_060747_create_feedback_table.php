<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedback_id');  // Primary key
            $table->unsignedBigInteger('event_id');  // Foreign key to events
            $table->unsignedBigInteger('stud_id');   // Foreign key to students
            $table->text('feedback')->nullable();    // Optional feedback text
            $table->unsignedTinyInteger('rating');   // Rating from 1 to 5
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
