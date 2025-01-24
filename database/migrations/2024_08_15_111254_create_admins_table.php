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
        Schema::create('admins', function (Blueprint $table) {
            $table->id('management_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('manage_name');
            $table->string('manage_phoneNo');
            $table->string('manage_email')->unique();
            $table->string('manage_position');
            $table->string('manage_img')->nullable(); // New column for image
            $table->text('manage_detail')->nullable(); // New column for details
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
