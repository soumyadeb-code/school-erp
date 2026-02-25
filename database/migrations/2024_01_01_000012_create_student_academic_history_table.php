<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_academic_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->integer('roll')->nullable();
            $table->string('section', 20)->nullable();
            $table->enum('registration_status', ['registered', 'unregistered'])->default('unregistered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_academic_history');
    }
};
