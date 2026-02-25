<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->decimal('tuition_fee', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['school_id', 'academic_year_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_fees');
    }
};
