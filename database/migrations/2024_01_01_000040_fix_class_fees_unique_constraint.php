<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, handle any existing duplicate records
        // Get all records grouped by school_id, academic_year_id, class_id
        $duplicates = DB::table('class_fees')
            ->select('school_id', 'academic_year_id', 'class_id', DB::raw('MIN(id) as min_id'))
            ->groupBy('school_id', 'academic_year_id', 'class_id')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        // If there are duplicates, we need to fix them
        // Keep the first record and update others with different mediums
        foreach ($duplicates as $duplicate) {
            $records = DB::table('class_fees')
                ->where('school_id', $duplicate->school_id)
                ->where('academic_year_id', $duplicate->academic_year_id)
                ->where('class_id', $duplicate->class_id)
                ->orderBy('id')
                ->get();

            $mediums = ['Bengali', 'English', 'Hindi'];
            $mediumIndex = 0;
            foreach ($records as $record) {
                if ($record->id !== $duplicate->min_id) {
                    // Assign a medium to make it unique
                    $medium = $mediums[$mediumIndex % count($mediums)];
                    DB::table('class_fees')
                        ->where('id', $record->id)
                        ->update(['medium' => $medium]);
                    $mediumIndex++;
                }
            }
        }

        // Handle records with null medium - assign a default medium
        DB::table('class_fees')
            ->whereNull('medium')
            ->update(['medium' => 'English']);

        // Drop foreign key constraints that reference the unique index
        // The foreign key constraints might be using the unique index
        Schema::table('class_fees', function (Blueprint $table) {
            // First, we need to drop foreign keys that reference this table
            // Then drop the unique constraint
            $table->dropForeign(['school_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['class_id']);
        });

        // Drop the old unique constraint on school_id, academic_year_id, class_id
        Schema::table('class_fees', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'academic_year_id', 'class_id']);
        });

        // Re-add foreign key constraints
        Schema::table('class_fees', function (Blueprint $table) {
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });

        // Add composite unique constraint on school_id, academic_year_id, class_id, and medium
        Schema::table('class_fees', function (Blueprint $table) {
            $table->unique(['school_id', 'academic_year_id', 'class_id', 'medium'], 'class_fees_school_academic_class_medium_unique');
        });
    }

    public function down(): void
    {
        // Drop the composite unique constraint
        Schema::table('class_fees', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'academic_year_id', 'class_id', 'medium']);
        });

        // Restore the original unique constraint on school_id, academic_year_id, class_id
        Schema::table('class_fees', function (Blueprint $table) {
            $table->unique(['school_id', 'academic_year_id', 'class_id']);
        });
    }
};
