<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('discount_type')->default('monthly');
            $table->decimal('same_month_discount', 10, 2)->default(0);
            $table->decimal('next_month_discount', 10, 2)->default(0);
            $table->integer('valid_till_day')->default(7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_rules');
    }
};
