<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('income_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('akun_id')->constrained('accounts')->restrictOnDelete();
            $table->date('periode');
            $table->decimal('pendapatan', 15, 2)->default(0);
            $table->decimal('beban', 15, 2)->default(0);
            $table->decimal('laba_bersih', 15, 2)->default(0);
            $table->timestamps();
            $table->index(['company_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('income_statements');
    }
};
