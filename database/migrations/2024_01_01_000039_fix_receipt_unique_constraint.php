<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, we need to handle existing duplicate receipt numbers if any
        // Get all receipts grouped by school_id and receipt_no
        $duplicates = DB::table('receipts')
            ->select('school_id', 'receipt_no', DB::raw('MIN(id) as min_id'))
            ->groupBy('school_id', 'receipt_no')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        // If there are duplicates, we need to fix them
        foreach ($duplicates as $duplicate) {
            $receipts = DB::table('receipts')
                ->where('school_id', $duplicate->school_id)
                ->where('receipt_no', $duplicate->receipt_no)
                ->orderBy('id')
                ->get();

            $counter = 1;
            foreach ($receipts as $receipt) {
                if ($receipt->id !== $duplicate->min_id) {
                    // Update the receipt_no to make it unique within the school
                    DB::table('receipts')
                        ->where('id', $receipt->id)
                        ->update(['receipt_no' => $duplicate->receipt_no . '_' . $counter]);
                    $counter++;
                }
            }
        }

        // Drop the current unique constraint on receipt_no
        // Laravel creates unique constraints with the format: receipts_receipt_no_unique
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropUnique(['receipt_no']);
        });

        // Add composite unique constraint on school_id and receipt_no
        Schema::table('receipts', function (Blueprint $table) {
            $table->unique(['school_id', 'receipt_no'], 'receipts_school_receipt_unique');
        });
    }

    public function down(): void
    {
        // Drop the composite unique constraint
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'receipt_no']);
        });

        // Restore the original unique constraint on receipt_no
        Schema::table('receipts', function (Blueprint $table) {
            $table->string('receipt_no', 50)->unique()->change();
        });
    }
};
