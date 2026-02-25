<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->enum('medium', ['english', 'bengali', 'hindi'])->default('english');
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('registration_start_date')->nullable();
            $table->boolean('registration_status')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'academic_year_id', 'medium']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_fees');
    }
};
