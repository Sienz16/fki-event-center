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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->unsignedBigInteger('stud_id'); // Make sure this is unsignedBigInteger
            $table->unsignedBigInteger('event_id'); // Make sure this matches the data type of event_id in events table
            $table->string('status');
            $table->timestamp('register_datetime')->nullable();
            $table->timestamp('attendance_datetime')->nullable();
            $table->timestamps();
        
            // Define foreign keys
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
