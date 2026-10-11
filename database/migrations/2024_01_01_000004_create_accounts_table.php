<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('kode_akun')->index();
            $table->string('nama_akun');
            $table->enum('kategori', ['Aset', 'Kewajiban', 'Modal', 'Pendapatan', 'Beban']);
            $table->enum('saldo_normal', ['Debit', 'Kredit']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'kode_akun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
