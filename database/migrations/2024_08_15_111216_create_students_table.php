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
        Schema::create('students', function (Blueprint $table) {
            $table->id('stud_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('stud_name');
            $table->integer('stud_age');
            $table->string('stud_course');
            $table->string('stud_phoneNo');
            $table->text('stud_detail')->nullable();
            $table->string('stud_img')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
