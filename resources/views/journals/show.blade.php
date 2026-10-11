@php $breadcrumbs = ['Jurnal Umum' => route('journals.index'), 'Detail Jurnal' => '']; @endphp
@extends('layouts.app')
@section('title', 'Detail Jurnal')
@section('content')
<div class="page-header">
    <h4>Detail Jurnal</h4>
    <div>
        <a href="{{ route('exports.journal.pdf', $journal) }}" class="btn btn-danger">PDF</a>
        <a href="{{ route('exports.journal.excel', $journal) }}" class="btn btn-success">Excel</a>
        <a href="{{ route('journals.edit', $journal) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('journals.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><strong>Nomor Jurnal:</strong> {{ $journal->nomor_jurnal }}</div>
                    <div class="col-md-4"><strong>Tanggal:</strong> {{ $journal->tanggal->format('d/m/Y') }}</div>
                    <div class="col-md-4"><strong>User:</strong> {{ $journal->user?->name }}</div>
                    <div class="col-md-12 mt-2"><strong>Keterangan:</strong> {{ $journal->keterangan ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Detail Transaksi</div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr><th>Kode</th><th>Akun</th><th class="text-end">Debit (Rp)</th><th class="text-end">Kredit (Rp)</th></tr>
                    </thead>
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
                        <tr class="fw-bold">
                            <td colspan="2" class="text-end">Total</td>
                            <td class="text-end">{{ number_format($journal->details->sum('debit'), 2) }}</td>
                            <td class="text-end">{{ number_format($journal->details->sum('kredit'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
