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
        Schema::create('sub_exhibitions', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('exhibitions_id');
            $table->foreign('exhibitions_id')->references('id')->on('exhibitions');
            $table->string('nama');
            $table->string('file_banner', 64);
            $table->foreign('file_banner')->references('id')->on('files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_exhibitions');
    }
};
