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
        Schema::create('community_forum', function (Blueprint $table) {
            $table->id('com_id');
            $table->foreignId('organizer_id')->constrained('event_organizers', 'organizer_id')->onDelete('cascade');
            $table->string('img');
            $table->text('desc')->nullable();
            $table->integer('views')->default(0);  // New column
            $table->integer('comments_count')->default(0);  // New column
            $table->integer('likes')->default(0);  // New column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_forum');
    }
};
