<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id('venue_id');
            $table->unsignedBigInteger('management_id'); // Declare management_id as unsigned big integer and required
            $table->foreign('management_id')->references('id')->on('users')->onDelete('cascade'); // Set the foreign key with 'onDelete' set to 'cascade'
            $table->string('venue_name');
            $table->string('venue_location');
            $table->enum('venue_status', ['Available', 'Under Maintenance'])->default('Available');
            $table->text('venue_details')->nullable();
            $table->string('venue_image')->nullable();
            $table->timestamps();
        });      
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};

