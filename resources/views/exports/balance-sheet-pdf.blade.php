<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Neraca</title>
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
<div class="header"><h2>NERACA</h2><p>Per {{ $periode }}</p></div>

<table>
    <tr><th colspan="2">ASET</th></tr>
    @foreach($aset as $a)
    <tr><td>{{ $a->nama_akun }}</td><td class="text-end">{{ number_format($a->total, 2) }}</td></tr>
    @endforeach
    <tr style="font-weight:bold;"><td>TOTAL ASET</td><td class="text-end">{{ number_format($totalAset, 2) }}</td></tr>
</table>

<table>
    <tr><th colspan="2">KEWAJIBAN</th></tr>
    @foreach($kewajiban as $k)
    <tr><td>{{ $k->nama_akun }}</td><td class="text-end">{{ number_format($k->total, 2) }}</td></tr>
    @endforeach
    <tr style="font-weight:bold;"><td>TOTAL KEWAJIBAN</td><td class="text-end">{{ number_format($totalKewajiban, 2) }}</td></tr>
</table>

<table>
    <tr><th colspan="2">MODAL</th></tr>
    <tr><td>Laba Ditahan</td><td class="text-end">{{ number_format($labaDitahan, 2) }}</td></tr>
    <tr style="font-weight:bold;"><td>TOTAL MODAL</td><td class="text-end">{{ number_format($totalModal, 2) }}</td></tr>
</table>

<div style="margin-top:20px;padding:10px;border:2px solid #10B981;text-align:center;">
    <h4>Kewajiban + Modal = Rp {{ number_format($totalKewajiban + $totalModal, 2) }}</h4>
</div>
</body>
</html>
