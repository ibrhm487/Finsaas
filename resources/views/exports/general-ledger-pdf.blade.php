<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Buku Besar</title>
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
    th { background: #0F172A; color: #fff; }
    .text-end { text-align: right; }
    .header { text-align: center; margin-bottom: 20px; }
</style>
</head>
<body>
<div class="header">
    <h2>BUKU BESAR</h2>
    @if($selectedAkun)
    <p>Akun: {{ $selectedAkun->kode_akun }} - {{ $selectedAkun->nama_akun }}</p>
    @endif
    <p>Periode: {{ $request->from ?? 'Awal' }} s/d {{ $request->to ?? 'Akhir' }}</p>
</div>
<table>
    <thead><tr><th>Tanggal</th><th>No. Jurnal</th><th>Keterangan</th><th class="text-end">Debit</th><th class="text-end">Kredit</th><th class="text-end">Saldo</th></tr></thead>
    <tbody>
        @forelse($ledgers as $l)
        <tr>
            <td>{{ $l->tanggal->format('d/m/Y') }}</td>
            <td>{{ $l->nomor_jurnal }}</td>
            <td>{{ $l->keterangan ?? '-' }}</td>
            <td class="text-end">{{ $l->debit > 0 ? number_format($l->debit, 2) : '-' }}</td>
            <td class="text-end">{{ $l->kredit > 0 ? number_format($l->kredit, 2) : '-' }}</td>
            <td class="text-end">{{ number_format($l->saldo, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>
</body>
</html>
