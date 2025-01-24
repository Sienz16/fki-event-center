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
        Schema::create('news', function (Blueprint $table) {
            $table->id('news_id');
            $table->unsignedBigInteger('management_id');
            $table->foreign('management_id')->references('management_id')->on('admins')->onDelete('cascade');
            $table->string('news_title');
            $table->text('news_details');
            $table->datetime('date');
            $table->enum('news_tag', ['Update', 'Maintenance', 'Bugs'])->nullable(); // Enum with fixed values
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
