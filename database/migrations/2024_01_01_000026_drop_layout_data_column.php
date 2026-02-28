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
        // Drop the layout_data column if it exists (it was probably added incorrectly)
        Schema::table('bill_layouts', function (Blueprint $table) {
            if (Schema::hasColumn('bill_layouts', 'layout_data')) {
                $table->dropColumn('layout_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_layouts', function (Blueprint $table) {
            $table->text('layout_data')->nullable();
        });
    }
};
