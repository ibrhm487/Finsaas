<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Jurnal {{ $journal->nomor_jurnal }}</title>
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
    th { background: #0F172A; color: #fff; }
    .text-end { text-align: right; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h2 { margin: 0; }
</style>
</head>
<body>
<div class="header">
    <h2>{{ $journal->company?->nama ?? 'Perusahaan' }}</h2>
    <p>JURNAL UMUM</p>
</div>
<table>
    <tr><td><strong>Nomor Jurnal:</strong> {{ $journal->nomor_jurnal }}</td><td><strong>Tanggal:</strong> {{ $journal->tanggal->format('d/m/Y') }}</td></tr>
    <tr><td colspan="2"><strong>Keterangan:</strong> {{ $journal->keterangan ?? '-' }}</td></tr>
</table>
<table>
    <thead><tr><th>Kode</th><th>Akun</th><th class="text-end">Debit</th><th class="text-end">Kredit</th></tr></thead>
    <tbody>
        @foreach($journal->details as $d)
        <tr>
            <td>{{ $d->akun?->kode_akun }}</td>
            <td>{{ $d->akun?->nama_akun }}</td>
            <td class="text-end">{{ number_format($d->debit, 2) }}</td>
            <td class="text-end">{{ number_format($d->kredit, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight:bold;"><td colspan="2" class="text-end">Total</td>
            <td class="text-end">{{ number_format($journal->details->sum('debit'), 2) }}</td>
            <td class="text-end">{{ number_format($journal->details->sum('kredit'), 2) }}</td>
        </tr>
    </tfoot>
</table>
</body>
</html>
