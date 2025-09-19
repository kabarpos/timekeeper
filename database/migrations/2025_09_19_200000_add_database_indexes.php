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
            if (!$this->hasIndex('messages', 'messages_is_active_index')) {
                $table->index('is_active');
            }
            
            // Composite index untuk query is_active + created_at (untuk recent active messages)
            if (!$this->hasIndex('messages', 'messages_is_active_created_at_index')) {
                $table->index(['is_active', 'created_at']);
            }
            
            // Index untuk created_at (untuk ordering)
            if (!$this->hasIndex('messages', 'messages_created_at_index')) {
                $table->index('created_at');
            }
        });

        Schema::table('timers', function (Blueprint $table) {
            // Index untuk query status yang sering digunakan
            if (!$this->hasIndex('timers', 'timers_status_index')) {
                $table->index('status');
            }
            
            // Index untuk started_at dan ended_at
            if (!$this->hasIndex('timers', 'timers_started_at_index')) {
                $table->index('started_at');
            }
            if (!$this->hasIndex('timers', 'timers_ended_at_index')) {
                $table->index('ended_at');
            }
            
            // Composite index untuk status + created_at
            if (!$this->hasIndex('timers', 'timers_status_created_at_index')) {
                $table->index(['status', 'created_at']);
            }
        });

        Schema::table('settings', function (Blueprint $table) {
            // Index untuk display_mode yang sering diquery
            if (!$this->hasIndex('settings', 'settings_display_mode_index')) {
                $table->index('display_mode');
            }
        });
    }

    /**
     * Helper method to check if index exists
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $databaseName = $connection->getDatabaseName();
            
            // For MySQL
            if ($connection->getDriverName() === 'mysql') {
                $result = $connection->select("
                    SELECT COUNT(*) as count 
                    FROM information_schema.statistics 
                    WHERE table_schema = ? AND table_name = ? AND index_name = ?
                ", [$databaseName, $table, $indexName]);
                
                return $result[0]->count > 0;
            }
            
            // For SQLite - check if index exists by trying to create it
            if ($connection->getDriverName() === 'sqlite') {
                $result = $connection->select("
                    SELECT name FROM sqlite_master 
                    WHERE type='index' AND name = ?
                ", [$indexName]);
                
                return count($result) > 0;
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop indexes safely - use try-catch for each
            try {
                $table->dropIndex('messages_is_active_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('messages_is_active_created_at_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('messages_created_at_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
        });

        Schema::table('timers', function (Blueprint $table) {
            try {
                $table->dropIndex('timers_status_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('timers_started_at_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('timers_ended_at_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('timers_status_created_at_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
        });

        Schema::table('settings', function (Blueprint $table) {
            try {
                $table->dropIndex('settings_display_mode_index');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
        });
    }
};