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
        Schema::create('maintenance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->default('Site Under Maintenance');
            $table->string('school_title')->default('School Business ERP');
            $table->string('maintenance_message')->default('We\'re currently performing scheduled maintenance to improve our services.');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_settings');
    }
};
