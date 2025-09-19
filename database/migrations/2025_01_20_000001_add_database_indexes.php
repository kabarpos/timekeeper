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
            // Index untuk query is_active yang sering digunakan
            $table->index('is_active');
            
            // Index untuk query type
            $table->index('type');
            
            // Composite index untuk query is_active + created_at (untuk recent active messages)
            $table->index(['is_active', 'created_at']);
            
            // Index untuk created_at (untuk ordering)
            $table->index('created_at');
        });

        Schema::table('timers', function (Blueprint $table) {
            // Index untuk query status yang sering digunakan
            $table->index('status');
            
            // Index untuk started_at dan ended_at
            $table->index('started_at');
            $table->index('ended_at');
            
            // Composite index untuk status + created_at
            $table->index(['status', 'created_at']);
        });

        Schema::table('settings', function (Blueprint $table) {
            // Index untuk display_mode yang sering diquery
            $table->index('display_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['type']);
            $table->dropIndex(['is_active', 'created_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('timers', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['started_at']);
            $table->dropIndex(['ended_at']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex(['display_mode']);
        });
    }
};