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
        Schema::create('registration_visitors', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('sub_exhibitions_id');
            $table->foreign('sub_exhibitions_id')->references('id')->on('sub_exhibitions');
            $table->string('barcode');
            $table->string('name_title')->nullable();
            $table->string('name')->nullable();
            $table->longText('company')->nullable();
            $table->string('job_title')->nullable();
            $table->longText('address')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile_phone');
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('business_type');
            $table->integer('job_function')->nullable();
            $table->longText('job_function_other')->nullable();
            $table->integer('visit_purpose')->nullable();
            $table->longText('visit_purpose_other')->nullable();
            $table->integer('purchasing_role')->nullable();
            $table->longText('purchasing_role_other')->nullable();
            $table->integer('event_find')->nullable();
            $table->longText('event_find_other')->nullable();
            $table->boolean('is_received_invitation_next')->nullable();
            $table->boolean('is_received_invitation_next_address_same')->nullable();
            $table->longText('received_invitation_next_address')->nullable();
            $table->boolean('is_printed')->default(0);
            $table->dateTime('register_date')->nullable();
            $table->dateTime('last_checkin_time')->nullable();
            $table->string('last_checkin_location')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_type')->nullable();
            $table->string('company_type_other')->nullable();
            $table->longText('line_of_business')->nullable();
            $table->string('city')->nullable();
            $table->enum('is_receive_news_letter', ['1', '0'])->default('0');
            $table->enum('is_agree_policy', ['1', '0'])->default('0');
            $table->string('job_level')->nullable();
            $table->string('job_level_other')->nullable();
            $table->string('departement')->nullable();
            $table->string('departement_other')->nullable();
            $table->string('website')->nullable();
            $table->string('how_know')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_visitors');
    }
};
