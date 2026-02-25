<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'school_admin', 'accountant', 'receptionist'])->default('school_admin')->after('name');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null')->after('role');
            $table->timestamp('last_login_at')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'school_id', 'last_login_at']);
        });
    }
};
