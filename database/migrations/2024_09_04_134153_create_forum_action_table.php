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
        Schema::create('forum_action', function (Blueprint $table) {
            $table->id('act_id');  // Primary key for each action
        
            // Foreign key to students table
            $table->unsignedBigInteger('stud_id');  
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
        
            // Foreign key to community forum table
            $table->unsignedBigInteger('com_id');
            $table->foreign('com_id')->references('com_id')->on('community_forum')->onDelete('cascade');
        
            // Action columns
            $table->enum('action_type', ['like', 'view']);  // Defines the action type: like, view
            $table->timestamps();  // Track when the action happened (created_at, updated_at)
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_action');
    }
};
