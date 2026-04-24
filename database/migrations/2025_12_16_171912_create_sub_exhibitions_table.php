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
        Schema::create('tbsubexhibitions', function (Blueprint $table) {
            $table->string('idsubexhibitions', 50)->primary();
            $table->string('idexhibitions', 20);
            $table->foreign('idexhibitions')->references('idexhibitions')->on('tbexhibitions');
            $table->string('nama');
            $table->string('path');
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
