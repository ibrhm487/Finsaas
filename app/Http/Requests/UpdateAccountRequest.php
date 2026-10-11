<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'kode_akun' => 'required|string|max:20|unique:accounts,kode_akun,' . $this->account->id . ',id,company_id,' . auth()->user()->company_id,
            'nama_akun' => 'required|string|max:255',
            'kategori' => 'required|in:Aset,Kewajiban,Modal,Pendapatan,Beban',
            'saldo_normal' => 'required|in:Debit,Kredit',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_akun.unique' => 'Kode akun sudah digunakan.',
        ];
    }
}
