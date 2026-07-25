<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ComplianceProgress;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct() { $this->middleware(['auth', 'verified']); }

    public function index()
    {
        $user = Auth::user();
        $companies = $user->companies()->with('category')->withCount('reviews')->orderByDesc('created_at')->get();
        $pendingCompliance = ComplianceProgress::whereIn('company_id', $companies->pluck('id'))
            ->where('status', 'pending')
            ->where('due_date', '<=', now()->addDays(30))
            ->with(['complianceStep', 'company'])
            ->orderBy('due_date')
            ->limit(5)->get();
        $recentPayments = $user->payments()->where('status','completed')->orderByDesc('created_at')->limit(5)->get();
        $unreadCount = $user->unreadNotifications()->count();
        return view('dashboard.index', compact('companies', 'pendingCompliance', 'recentPayments', 'unreadCount'));
    }
}