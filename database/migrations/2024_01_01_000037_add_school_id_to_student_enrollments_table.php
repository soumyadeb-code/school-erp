<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade')->after('id');
            
            // Add index for school_id + student_id + academic_year_id combination
            $table->index(['school_id', 'student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropIndex(['school_id', 'student_id', 'academic_year_id']);
            $table->dropColumn('school_id');
        });
    }
};
