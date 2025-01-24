<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('venue_book', function (Blueprint $table) {
            $table->id('venue_book_id');
            
            // Foreign key linking to the events table
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            
            // Foreign key linking to the venues table
            $table->unsignedBigInteger('venue_id');
            $table->foreign('venue_id')->references('venue_id')->on('venues')->onDelete('cascade');
            
            // Date and time fields for booking
            $table->date('booking_start_date');
            $table->date('booking_end_date')->nullable();
            $table->time('booking_start_time');
            $table->time('booking_end_time');
    
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('venue_book');
    }
};
