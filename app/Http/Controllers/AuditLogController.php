<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                ->orWhere('aksi', 'like', "%{$request->search}%")
                ->orWhere('module', 'like', "%{$request->search}%");
        }

        if ($request->module) {
            $query->where('module', $request->module);
        }

        if ($request->aksi) {
            $query->where('aksi', $request->aksi);
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('audit-logs.index', compact('logs'));
    }
}
