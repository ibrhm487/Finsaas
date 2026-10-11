<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Neraca Saldo</title>
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
<div class="header"><h2>NERACA SALDO</h2></div>
<table>
    <thead><tr><th>Kode</th><th>Nama Akun</th><th class="text-end">Debit</th><th class="text-end">Kredit</th></tr></thead>
    <tbody>
        @forelse($balances as $b)
        <tr>
            <td>{{ $b->akun?->kode_akun }}</td>
            <td>{{ $b->akun?->nama_akun }}</td>
            <td class="text-end">{{ $b->debit > 0 ? number_format($b->debit, 2) : '-' }}</td>
            <td class="text-end">{{ $b->kredit > 0 ? number_format($b->kredit, 2) : '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;">Tidak ada data</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="font-weight:bold;"><td colspan="2" class="text-end">Total</td>
            <td class="text-end">{{ number_format($totalDebit, 2) }}</td>
            <td class="text-end">{{ number_format($totalKredit, 2) }}</td>
        </tr>
    </tfoot>
</table>
</body>
</html>
