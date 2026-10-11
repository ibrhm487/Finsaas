<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_jurnal')->index();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['company_id', 'nomor_jurnal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_headers');
    }
};
