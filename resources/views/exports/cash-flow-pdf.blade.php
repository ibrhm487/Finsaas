<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Arus Kas</title>
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
<div class="header"><h2>LAPORAN ARUS KAS</h2><p>Periode: {{ $from }} s/d {{ $to }}</p></div>

<table>
    <tr style="font-weight:bold;"><td colspan="2">ARUS KAS DARI AKTIVITAS OPERASI</td></tr>
    <tr><td style="padding-left:20px;">Arus Kas Masuk</td><td class="text-end">{{ number_format($operasi['masuk'], 2) }}</td></tr>
    <tr><td style="padding-left:20px;">Arus Kas Keluar</td><td class="text-end">{{ number_format($operasi['keluar'], 2) }}</td></tr>
    <tr style="font-weight:bold;"><td>Arus Kas Bersih - Operasi</td><td class="text-end">{{ number_format($operasi['bersih'], 2) }}</td></tr>
</table>
<table>
    <tr style="font-weight:bold;"><td colspan="2">ARUS KAS DARI AKTIVITAS INVESTASI</td></tr>
    <tr><td style="padding-left:20px;">Arus Kas Masuk</td><td class="text-end">{{ number_format($investasi['masuk'], 2) }}</td></tr>
    <tr><td style="padding-left:20px;">Arus Kas Keluar</td><td class="text-end">{{ number_format($investasi['keluar'], 2) }}</td></tr>
    <tr style="font-weight:bold;"><td>Arus Kas Bersih - Investasi</td><td class="text-end">{{ number_format($investasi['bersih'], 2) }}</td></tr>
</table>
<table>
    <tr style="font-weight:bold;"><td colspan="2">ARUS KAS DARI AKTIVITAS PENDANAAN</td></tr>
    <tr><td style="padding-left:20px;">Arus Kas Masuk</td><td class="text-end">{{ number_format($pendanaan['masuk'], 2) }}</td></tr>
    <tr><td style="padding-left:20px;">Arus Kas Keluar</td><td class="text-end">{{ number_format($pendanaan['keluar'], 2) }}</td></tr>
    <tr style="font-weight:bold;"><td>Arus Kas Bersih - Pendanaan</td><td class="text-end">{{ number_format($pendanaan['bersih'], 2) }}</td></tr>
</table>

<div style="margin-top:20px;padding:10px;border:2px solid #10B981;text-align:center;">
    <h3>SALDO AKHIR KAS: Rp {{ number_format($saldo_akhir, 2) }}</h3>
</div>
</body>
</html>
