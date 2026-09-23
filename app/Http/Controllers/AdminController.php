<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ComplianceStep;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────────────

    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers'       => User::count(),
            'totalCompanies'   => Company::count(),
            'totalPayments'    => Payment::where('status', 'completed')->count(),
            'totalRevenue'     => Payment::where('status', 'completed')->sum('amount'),
            'recentUsers'      => User::latest()->limit(5)->get(),
            'recentCompanies'  => Company::with('user')->latest()->limit(5)->get(),
            'recentPayments'   => Payment::with(['user', 'company'])->where('status', 'completed')->latest()->limit(5)->get(),
        ]);
    }

    // ── Users ─────────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::withCount('companies');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', "User {$user->full_name} " . ($user->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function makeAdmin(User $user)
    {
        $user->update(['role' => $user->role === 'admin' ? 'owner' : 'admin']);
        return back()->with('success', "Role updated for {$user->full_name}.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    // ── Companies ─────────────────────────────────────────────────────────

    public function companies(Request $request)
    {
        $query = Company::with(['user', 'category'])->withCount('reviews');

        if ($search = $request->input('q')) {
            $query->where('company_name', 'like', "%{$search}%")
                  ->orWhere('county', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $companies = $query->latest()->paginate(20)->withQueryString();
        return view('admin.companies', compact('companies'));
    }

    public function toggleCompanyStatus(Company $company)
    {
        $company->update([
            'status' => $company->status === 'active' ? 'suspended' : 'active'
        ]);
        return back()->with('success', "Company {$company->company_name} " . $company->status . '.');
    }

    public function toggleFeatured(Company $company)
    {
        $company->update(['is_featured' => !$company->is_featured]);
        return back()->with('success', "Featured status updated for {$company->company_name}.");
    }

    public function toggleVerified(Company $company)
    {
        $company->update(['is_verified' => !$company->is_verified]);
        return back()->with('success', "Verified status updated for {$company->company_name}.");
    }

    public function deleteCompany(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Company deleted.');
    }

    // ── Payments ──────────────────────────────────────────────────────────

    public function payments(Request $request)
    {
        $query = Payment::with(['user', 'company']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $payments = $query->latest()->paginate(20)->withQueryString();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        return view('admin.payments', compact('payments', 'totalRevenue'));
    }

    // ── Compliance Steps ──────────────────────────────────────────────────

    public function complianceSteps()
    {
        $steps = ComplianceStep::orderBy('sort_order')->get();
        return view('admin.compliance-steps', compact('steps'));
    }

    public function createComplianceStep(Request $request)
    {
        $data = $request->validate([
            'title'                     => ['required', 'string', 'max:255'],
            'description'               => ['required', 'string'],
            'authority'                 => ['required', 'string', 'max:255'],
            'portal_url'                => ['nullable', 'url'],
            'days_after_registration'   => ['required', 'integer', 'min:0'],
            'is_mandatory'              => ['boolean'],
            'sort_order'                => ['integer'],
        ]);

        $data['is_mandatory'] = $request->boolean('is_mandatory');
        ComplianceStep::create($data);
        return back()->with('success', 'Compliance step created.');
    }

    public function deleteComplianceStep(ComplianceStep $step)
    {
        $step->delete();
        return back()->with('success', 'Compliance step deleted.');
    }
}
