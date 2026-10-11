<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['company_id', 'account_id']);
        });

        Schema::create('cash_flow_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->enum('activity', ['operating', 'investing', 'financing']);
            $table->string('cash_flow_category', 100);
            $table->enum('method', ['direct', 'indirect'])->default('direct');
            $table->boolean('is_inflow')->default(true);
            $table->boolean('is_non_cash')->default(false);
            $table->timestamps();
            $table->unique(['company_id', 'account_id', 'method'], 'cf_mapping_unique');
        });

        Schema::create('cash_flow_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('periode_start');
            $table->date('periode_end');
            $table->enum('method', ['direct', 'indirect']);
            $table->json('operating_activities');
            $table->json('investing_activities');
            $table->json('financing_activities');
            $table->decimal('net_operating', 15, 2)->default(0);
            $table->decimal('net_investing', 15, 2)->default(0);
            $table->decimal('net_financing', 15, 2)->default(0);
            $table->decimal('net_increase', 15, 2)->default(0);
            $table->decimal('beginning_cash', 15, 2)->default(0);
            $table->decimal('ending_cash', 15, 2)->default(0);
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('generated_at');
            $table->timestamps();
            $table->unique(['company_id', 'periode_start', 'periode_end', 'method'], 'cf_report_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flow_reports');
        Schema::dropIfExists('cash_flow_mappings');
        Schema::dropIfExists('cash_accounts');
    }
};
