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
        Schema::create('ecertificates', function (Blueprint $table) {
            $table->id('ecert_id');  // Primary key
            $table->unsignedBigInteger('stud_id');  // Foreign key to students table
            $table->unsignedBigInteger('event_id');  // Foreign key to events table
            $table->string('ecert_file');  // Path to the e-cert PDF file
            $table->string('unique_code')->unique();  // Unique code to validate the authenticity of the certificate
            $table->timestamp('ecert_datetime');  // Timestamp when the certificate was generated
            $table->enum('template_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecertificates');
    }
};
