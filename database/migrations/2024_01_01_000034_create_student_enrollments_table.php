<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->integer('roll')->nullable();
            $table->string('section', 20)->nullable();
            $table->enum('status', ['admitted', 'enrolled', 'promoted', 'completed', 'tc_issued'])->default('enrolled');
            $table->foreignId('promoted_to_enrollment_id')->nullable()->constrained('student_enrollments')->onDelete('set null');
            $table->timestamps();
            
            // Unique constraint: one enrollment per student per academic year
            $table->unique(['student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
