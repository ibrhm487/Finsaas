<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Laba Rugi</title>
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
    th { background: #0F172A; color: #fff; }
    .text-end { text-align: right; }
    .header { text-align: center; margin-bottom: 20px; }
    .laba { color: #10B981; font-weight: bold; font-size: 14px; }
</style>
</head>
<body>
<div class="header"><h2>LAPORAN LABA RUGI</h2><p>Periode: {{ $from }} s/d {{ $to }}</p></div>

<h4>PENDAPATAN</h4>
<table>
    @foreach($pendapatan as $p)
    <tr><td>{{ $p->nama_akun }}</td><td class="text-end">{{ number_format($p->total, 2) }}</td></tr>
    @endforeach
    <tr style="font-weight:bold;"><td>TOTAL PENDAPATAN</td><td class="text-end">{{ number_format($totalPendapatan, 2) }}</td></tr>
</table>

<h4>BEBAN</h4>
<table>
    @foreach($beban as $b)
    <tr><td>{{ $b->nama_akun }}</td><td class="text-end">{{ number_format($b->total, 2) }}</td></tr>
    @endforeach
    <tr style="font-weight:bold;"><td>TOTAL BEBAN</td><td class="text-end">{{ number_format($totalBeban, 2) }}</td></tr>
</table>

<div style="margin-top:20px;padding:10px;border:2px solid #10B981;text-align:center;">
    <h3 class="laba">LABA BERSIH: Rp {{ number_format($labaBersih, 2) }}</h3>
</div>
</body>
</html>
