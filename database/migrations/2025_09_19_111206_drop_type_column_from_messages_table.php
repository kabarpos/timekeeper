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
        Schema::table('messages', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('messages', 'type')) {
                // No need to drop index since it was never created in the original migration
                // Just drop the column directly
                $table->dropColumn('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('messages', 'type')) {
                $table->enum('type', ['short', 'long'])->default('short')->after('content');
                // No index added to match original migration structure
            }
        });
    }
};
