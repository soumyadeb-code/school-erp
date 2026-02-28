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
        // Drop the incorrect unique constraint
        DB::statement('ALTER TABLE bill_layouts DROP INDEX bill_layouts_school_id_bill_type_unique');
        
        // Add the correct unique constraint with variable_name
        DB::statement('ALTER TABLE bill_layouts ADD UNIQUE INDEX bill_layout_unique (school_id, bill_type, variable_name)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE bill_layouts DROP INDEX bill_layout_unique');
        DB::statement('ALTER TABLE bill_layouts ADD UNIQUE INDEX bill_layouts_school_id_bill_type_unique (school_id, bill_type)');
    }
};
