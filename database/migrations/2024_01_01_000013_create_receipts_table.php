<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('receipt_no', 50)->unique();
            $table->enum('bill_type', ['admission', 'monthly', 'registration', 'bus', 'bookset', 'other']);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->decimal('advance_amount', 10, 2)->default(0);
            $table->decimal('old_due_paid', 10, 2)->default(0);
            $table->enum('payment_mode', ['cash', 'online'])->default('cash');
            $table->date('billing_date');
            $table->text('description')->nullable();
            $table->enum('status', ['paid', 'due', 'cancelled'])->default('paid');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
