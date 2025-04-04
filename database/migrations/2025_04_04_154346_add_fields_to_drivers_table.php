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
        Schema::table('drivers', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->comment('Driver date of birth');
            $table->string('marital_status', 20)->nullable()->comment('Marital status');
            $table->string('address', 255)->nullable()->comment('Full address');
            $table->string('identity_card', 20)->nullable()->comment('RG number');
            $table->string('driver_license_number', 20)->nullable()->comment('Driver license number');
            $table->string('driver_license_category', 5)->nullable()->comment('Driver license category');
            $table->date('driver_license_expiration')->nullable()->comment('Driver license expiration date');
            $table->string('password')->nullable()->comment('Account password (hashed)');
            $table->boolean('terms_accepted')->default(false)->comment('Whether terms were accepted');
            $table->text('driver_license_front_photo')->nullable()->comment('Base64 encoded front photo of driver license');
            $table->text('driver_license_back_photo')->nullable()->comment('Base64 encoded back photo of driver license');
            $table->text('face_photo')->nullable()->comment('Base64 encoded face photo');
            $table->text('address_proof_photo')->nullable()->comment('Base64 encoded address proof document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'marital_status',
                'address',
                'identity_card',
                'driver_license_number',
                'driver_license_category',
                'driver_license_expiration',
                'password',
                'terms_accepted',
                'driver_license_front_photo',
                'driver_license_back_photo',
                'face_photo',
                'address_proof_photo'
            ]);
        });
    }
};