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
        Schema::create('exhibitions', function (Blueprint $table) {
            $table->id('id');
            $table->string('code')->unique();
            $table->string('banner_file', 64)->nullable();
            $table->foreign('banner_file')->references('id')->on('files');
            $table->string('all_banner', 64)->nullable();
            $table->foreign('all_banner')->references('id')->on('files');
            $table->string("name");
            $table->string("full_name");
            $table->string("location")->nullable();
            $table->string('date')->nullable();
            $table->string("team")->nullable();
            $table->longText("opening_hours")->nullable();
            $table->string('host')->nullable();
            $table->string('type');
            $table->string('page')->nullable();
            $table->string('path')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitions');
    }
};
