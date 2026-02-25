<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->integer('year');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
            $table->unique(['school_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
