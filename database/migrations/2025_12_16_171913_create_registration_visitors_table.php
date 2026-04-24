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
        Schema::create('tb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('Exhibition');
            $table->string('NameTitle');
            $table->string('Name');
            $table->longText('Company');
            $table->string('JobTitle');
            $table->longText('Address');
            $table->string('State')->nullable();
            $table->string('Country')->nullable();
            $table->string('Telephone')->nullable();
            $table->string('MobilePhone');
            $table->string('Fax')->nullable();
            $table->string('Email')->nullable();
            $table->string('BusinessType');
            $table->integer('JobFunction')->nullable();
            $table->longText('JobFunctionOther')->nullable();
            $table->integer('VisitPurpose')->nullable();
            $table->longText('VisitPurposeOther')->nullable();
            $table->integer('PurchasingRole')->nullable();
            $table->longText('PurchasingRoleOther')->nullable();
            $table->integer('EventFind')->nullable();
            $table->longText('EventFindOther')->nullable();
            $table->boolean('IsReceivedInvitationNext')->nullable();
            $table->boolean('IsReceivedInvitationNextAddressSame')->nullable();
            $table->longText('ReceivedInvitationNextAddress')->nullable();
            $table->string('Barcode');
            $table->boolean('IsPrinted')->default(0);
            $table->dateTime('RegisterDate')->nullable();
            $table->dateTime('LastCheckinTime')->nullable();
            $table->string('LastCheckinLocation')->nullable();
            $table->string('SubExhibition');
            $table->string('SubExhibitionName');
            $table->string('FirstName')->nullable();
            $table->string('LastName')->nullable();
            $table->string('Companytype')->nullable();
            $table->string('JobLevel')->nullable();
            $table->string('Departement')->nullable();
            $table->string('Website')->nullable();
            $table->string('HowKnow')->nullable();
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
