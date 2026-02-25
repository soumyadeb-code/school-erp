<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->integer('month');
            $table->decimal('tuition_fee', 10, 2)->default(0);
            $table->decimal('bus_fee', 10, 2)->default(0);
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('receipt_no', 50)->nullable();
            $table->foreignId('receipt_id')->nullable()->constrained('receipts')->onDelete('set null');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['paid', 'due'])->default('due');
            $table->timestamps();
            $table->unique(['student_id', 'academic_year_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_payments');
    }
};
