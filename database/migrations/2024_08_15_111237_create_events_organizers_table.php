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
        Schema::create('event_organizers', function (Blueprint $table) {
            $table->id('organizer_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to 'users' table
            $table->string('org_name');
            $table->integer('org_age');
            $table->string('org_course');
            $table->string('org_position');
            $table->string('org_phoneNo');
            $table->text('org_detail')->nullable();
            $table->string('org_img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events_organizers');
    }
};
