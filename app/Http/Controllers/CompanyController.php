<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function edit()
    {
        $company = Company::findOrFail(auth()->user()->company_id);
        return view('company.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Company::findOrFail(auth()->user()->company_id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update',
            'module' => 'Perusahaan',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('company.edit')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
