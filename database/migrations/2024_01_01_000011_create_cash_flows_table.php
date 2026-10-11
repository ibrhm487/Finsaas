<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('akun_id')->constrained('accounts')->restrictOnDelete();
            $table->date('periode');
            $table->enum('kategori', ['Operasi', 'Investasi', 'Pendanaan']);
            $table->decimal('arus_masuk', 15, 2)->default(0);
            $table->decimal('arus_keluar', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2)->default(0);
            $table->timestamps();
            $table->index(['company_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
