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
        Schema::create('tbexhibitions', function (Blueprint $table) {
            $table->string('idexhibitions', 20)->primary();
            $table->string("name");
            $table->string("tanggal");
            $table->string("keterangan");
            $table->string('path');
            $table->string('all_banner')->nullable();
            $table->string('web_own')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('is_show', ['1', '0'])->default('0');
            $table->string('event_name')->nullable();
            $table->enum('type', ['REG', 'VIP'])->default('REG');
            $table->longText('opening_hours')->nullable();
            $table->timestamps();
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
