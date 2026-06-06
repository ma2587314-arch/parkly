<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'actual_start_time')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dateTime('actual_start_time')->nullable()->after('end_time');
            });
        }

        // Extend the status enum to include checked_in and completed
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending','confirmed','checked_in','completed','cancelled') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
        }

        if (Schema::hasColumn('bookings', 'actual_start_time')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('actual_start_time');
            });
        }
    }
};
