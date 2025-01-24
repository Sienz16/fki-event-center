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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id('volunteer_id'); // Primary key
            
            // Foreign key referencing 'events' table
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            
            // Foreign key referencing 'event_organizers' table
            $table->unsignedBigInteger('organizer_id');
            $table->foreign('organizer_id')->references('organizer_id')->on('event_organizers')->onDelete('cascade');
        
            $table->integer('volunteer_capacity')->default(1); // Maximum capacity for the event
            $table->timestamps(); // Timestamps for tracking when created/updated
            $table->text('notes')->nullable(); // Additional notes related to the volunteer opportunity
        });     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
