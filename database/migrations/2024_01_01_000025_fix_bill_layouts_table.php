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
        // Check if table exists
        if (!Schema::hasTable('bill_layouts')) {
            Schema::create('bill_layouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained()->onDelete('cascade');
                $table->string('bill_type');
                $table->string('variable_name');
                $table->integer('x_position')->default(0);
                $table->integer('y_position')->default(0);
                $table->integer('font_size')->default(12);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->unique(['school_id', 'bill_type', 'variable_name'], 'bill_layout_unique');
            });
        } else {
            // Add missing columns
            Schema::table('bill_layouts', function (Blueprint $table) {
                if (!Schema::hasColumn('bill_layouts', 'school_id')) {
                    $table->foreignId('school_id')->constrained()->onDelete('cascade')->after('id');
                }
                if (!Schema::hasColumn('bill_layouts', 'bill_type')) {
                    $table->string('bill_type')->after('school_id');
                }
                if (!Schema::hasColumn('bill_layouts', 'variable_name')) {
                    $table->string('variable_name')->after('bill_type');
                }
                if (!Schema::hasColumn('bill_layouts', 'x_position')) {
                    $table->integer('x_position')->default(0)->after('variable_name');
                }
                if (!Schema::hasColumn('bill_layouts', 'y_position')) {
                    $table->integer('y_position')->default(0)->after('x_position');
                }
                if (!Schema::hasColumn('bill_layouts', 'font_size')) {
                    $table->integer('font_size')->default(12)->after('y_position');
                }
                if (!Schema::hasColumn('bill_layouts', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('font_size');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_layouts');
    }
};
