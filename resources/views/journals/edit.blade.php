@php $breadcrumbs = ['Jurnal Umum' => route('journals.index'), 'Edit Jurnal' => '']; @endphp
@extends('layouts.app')
@section('title', 'Edit Jurnal')
@section('content')
<div class="page-header">
    <h4>Edit Jurnal</h4>
</div>

<div class="card">
    <div class="card-body">
        @error('msg')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <form method="POST" action="{{ route('journals.update', $journal) }}" id="journalForm">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Jurnal</label>
                    <input type="text" class="form-control" value="{{ $journal->nomor_jurnal }}" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $journal->tanggal->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $journal->keterangan) }}">
                </div>
            </div>

            <h6 class="fw-bold mb-3">Detail Jurnal</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="journalDetails">
                    <thead>
                        <tr><th style="width:5%;">#</th><th style="width:40%;">Akun</th><th style="width:20%;">Debit (Rp)</th><th style="width:20%;">Kredit (Rp)</th><th style="width:5%;"></th></tr>
                    </thead>
                    <tbody>
                        @foreach($journal->details as $idx => $detail)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <select name="akun_id[]" class="form-select" required>
                                    <option value="">Pilih Akun</option>
                                    @foreach($accounts as $a)
                                    <option value="{{ $a->id }}" {{ $detail->akun_id == $a->id ? 'selected' : '' }}>{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="debit[]" class="form-control debit-input" step="0.01" min="0" value="{{ $detail->debit }}" oninput="updateTotal()"></td>
                            <td><input type="number" name="kredit[]" class="form-control kredit-input" step="0.01" min="0" value="{{ $detail->kredit }}" oninput="updateTotal()"></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); updateTotal();">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2" class="text-end">Total</td>
                            <td id="totalDebit">Rp 0</td>
                            <td id="totalKredit">Rp 0</td>
                            <td></td>
                        </tr>
                        <tr id="balanceRow" style="display:none;">
                            <td colspan="2" class="text-end text-danger">Selisih</td>
                            <td colspan="2" id="balanceDiff" class="text-danger"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button" class="btn btn-sm btn-success mb-3" onclick="addRow()">+ Tambah Baris</button>
            <div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('journals.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let rowCount = {{ $journal->details->count() }};

function addRow() {
    rowCount++;
    const tbody = document.querySelector('#journalDetails tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="text-center">${rowCount}</td>
        <td>
            <select name="akun_id[]" class="form-select" required>
                <option value="">Pilih Akun</option>
                @foreach($accounts as $a)
                <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="debit[]" class="form-control debit-input" step="0.01" min="0" value="0" oninput="updateTotal()"></td>
        <td><input type="number" name="kredit[]" class="form-control kredit-input" step="0.01" min="0" value="0" oninput="updateTotal()"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); updateTotal();">X</button></td>
    `;
    tbody.appendChild(tr);
}

function updateTotal() {
    let totalDebit = 0, totalKredit = 0;
    document.querySelectorAll('.debit-input').forEach(i => totalDebit += parseFloat(i.value) || 0);
    document.querySelectorAll('.kredit-input').forEach(i => totalKredit += parseFloat(i.value) || 0);
    document.getElementById('totalDebit').textContent = 'Rp ' + totalDebit.toLocaleString('id-ID', {minimumFractionDigits: 2});
    document.getElementById('totalKredit').textContent = 'Rp ' + totalKredit.toLocaleString('id-ID', {minimumFractionDigits: 2});
    const diff = Math.abs(totalDebit - totalKredit);
    const row = document.getElementById('balanceRow');
    if (diff > 0.01) {
        row.style.display = '';
        document.getElementById('balanceDiff').textContent = 'Rp ' + diff.toLocaleString('id-ID', {minimumFractionDigits: 2});
    } else {
        row.style.display = 'none';
    }
}

document.getElementById('journalForm').addEventListener('submit', function(e) {
    let totalDebit = 0, totalKredit = 0;
    document.querySelectorAll('.debit-input').forEach(i => totalDebit += parseFloat(i.value) || 0);
    document.querySelectorAll('.kredit-input').forEach(i => totalKredit += parseFloat(i.value) || 0);
    if (Math.abs(totalDebit - totalKredit) > 0.01) {
        e.preventDefault();
        alert('Total Debit harus sama dengan Total Kredit!');
    }
});

updateTotal();
</script>
@endpush
@endsection
