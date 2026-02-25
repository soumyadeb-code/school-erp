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
        Schema::table('bus_fees', function (Blueprint $table) {
            $table->index(['school_id', 'destination'], 'bus_fees_school_destination_idx');
            $table->index('status', 'bus_fees_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bus_fees', function (Blueprint $table) {
            $table->dropIndex('bus_fees_school_destination_idx');
            $table->dropIndex('bus_fees_status_idx');
        });
    }
};
