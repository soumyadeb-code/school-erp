<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('student_id', 20)->unique();
            $table->string('name');
            $table->date('dob');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('section', 20)->nullable();
            $table->integer('roll')->nullable();
            $table->enum('medium', ['english', 'bengali', 'hindi'])->default('english');
            $table->date('admission_date');
            $table->string('gender', 20)->nullable();
            $table->string('aadhaar', 20)->nullable();
            $table->string('government_id', 50)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('social_category', 50)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('father_name', 100)->nullable();
            $table->string('father_education', 100)->nullable();
            $table->string('mother_name', 100)->nullable();
            $table->string('mother_education', 100)->nullable();
            $table->decimal('yearly_income', 12, 2)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('village', 100)->nullable();
            $table->string('post_office', 100)->nullable();
            $table->string('police_station', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('pin', 10)->nullable();
            $table->string('panchayat', 100)->nullable();
            $table->string('icds_name', 100)->nullable();
            $table->string('icds_center_no', 50)->nullable();
            $table->string('icds_code', 50)->nullable();
            $table->foreignId('bus_destination_id')->nullable()->constrained('bus_fees')->onDelete('set null');
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive', 'tc_issued'])->default('active');
            $table->enum('admission_status', ['pending', 'completed'])->default('pending');
            $table->string('academic_year', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
