<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('journal_headers', 'status')) {
            Schema::table('journal_headers', function (Blueprint $table) {
                $table->enum('status', [
                    'draft', 'submitted', 'approved', 'rejected', 'posted'
                ])->default('posted')->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('journal_headers', 'status')) {
            Schema::table('journal_headers', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
