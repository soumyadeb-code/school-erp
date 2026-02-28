<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the table and recreate with correct schema
        Schema::dropIfExists('bill_layouts');
        
        Schema::create('bill_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('bill_type'); // admission, registration, monthly
            $table->string('variable_name'); // receipt_no, student_name, etc.
            $table->integer('x_position')->default(0);
            $table->integer('y_position')->default(0);
            $table->integer('font_size')->default(12);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique constraint including variable_name
            $table->unique(['school_id', 'bill_type', 'variable_name'], 'bill_layout_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_layouts');
    }
};
