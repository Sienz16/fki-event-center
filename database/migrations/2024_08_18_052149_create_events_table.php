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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->unsignedBigInteger('organizer_id'); // Use organizer_id for foreign key
            $table->foreign('organizer_id')->references('organizer_id')->on('event_organizers')->onDelete('cascade'); 
            $table->string('event_name');

            // Add new fields for both single-day and multi-day events
            $table->date('event_date')->nullable();          // For single-day events
            $table->date('event_start_date')->nullable();    // For multi-day events (start)
            $table->date('event_end_date')->nullable();      // For multi-day events (end)

            // Add start and end times
            $table->time('event_start_time')->nullable();    // Event start time
            $table->time('event_end_time')->nullable();      // Event end time

            // Add new columns for event type and online platform
            $table->enum('event_type', ['physical', 'online'])->default('physical')->after('event_end_time');
            $table->string('online_platform')->nullable()->after('event_type');

            // Add foreign key to the venues table
            $table->unsignedBigInteger('venue_id')->nullable(); // Link to the venues table
            $table->foreign('venue_id')->references('venue_id')->on('venues')->onDelete('cascade');
            
            $table->string('event_img')->nullable();
            $table->text('event_desc');
            $table->enum('event_status', ['active', 'suspend', 'pending'])->default('active'); // Set default value to 'active'
            $table->string('event_code', 10)->nullable();
            $table->string('cert_template')->nullable(); // Field to store the certificate template path
            $table->enum('cert_orientation', ['portrait', 'landscape'])->default('portrait'); // Field to store the certificate orientation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
