<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('akun_id')->constrained('accounts')->restrictOnDelete();
            $table->date('tanggal');
            $table->string('nomor_jurnal');
            $table->text('keterangan')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->timestamps();
            $table->index(['company_id', 'akun_id']);
            $table->index(['company_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_ledgers');
    }
};
