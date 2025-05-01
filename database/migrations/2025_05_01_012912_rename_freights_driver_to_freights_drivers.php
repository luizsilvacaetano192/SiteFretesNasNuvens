<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('freights_driver', 'freights_drivers');
    }

    public function down(): void
    {
        Schema::rename('freights_drivers', 'freights_driver');
    }
};

