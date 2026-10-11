<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AuditLog;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('company_id', auth()->user()->company_id);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('kode_akun', 'like', "%{$request->search}%")
                  ->orWhere('nama_akun', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $accounts = $query->orderBy('kode_akun')->paginate(20)->withQueryString();

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(StoreAccountRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = auth()->user()->company_id;
        $account = Account::create($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Create',
            'module' => 'Chart of Accounts',
            'ip_address' => $request->ip(),
            'data' => json_encode(['id' => $account->id, 'kode_akun' => $account->kode_akun]),
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function show(Account $account)
    {
        $this->authorizeAccess($account);
        return redirect()->route('accounts.index');
    }

    public function edit(Account $account)
    {
        $this->authorizeAccess($account);
        return view('accounts.edit', compact('account'));
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorizeAccess($account);
        $account->update($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update',
            'module' => 'Chart of Accounts',
            'ip_address' => $request->ip(),
            'data' => json_encode(['id' => $account->id, 'kode_akun' => $account->kode_akun]),
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $this->authorizeAccess($account);
        $account->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Delete',
            'module' => 'Chart of Accounts',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }

    private function authorizeAccess($account)
    {
        if ($account->company_id !== auth()->user()->company_id) {
            abort(403);
        }
    }
}
