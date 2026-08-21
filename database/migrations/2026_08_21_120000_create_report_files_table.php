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
        Schema::create('report_files', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 16)->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->string('report_type', 50)->index();
            $table->string('file_name');
            $table->string('path')->nullable();
            $table->json('selected_fields');
            $table->json('filters');
            $table->unsignedTinyInteger('status')->default(0)->index();
            $table->decimal('execute_time', 10, 2)->nullable();
            $table->text('exception')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_files');
    }
};
