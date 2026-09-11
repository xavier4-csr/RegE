<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ComplianceProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceController extends Controller
{
    public function index(Company $company)
    {
        $this->authorizeOwner($company);
        $progress = $company->complianceProgress()
            ->with('complianceStep')->orderBy('due_date')->get()->groupBy('status');
        return view('compliance.index', compact('company', 'progress'));
    }

    public function markComplete(Request $request, ComplianceProgress $progress)
    {
        $this->authorizeOwner($progress->company);
        $progress->markComplete();
        return back()->with('success', '✅ Step marked as complete.');
    }

    public function markSkipped(Request $request, ComplianceProgress $progress)
    {
        $this->authorizeOwner($progress->company);
        $progress->update(['status' => 'skipped']);
        return back()->with('info', 'Step skipped.');
    }

    private function authorizeOwner(Company $company): void
    {
        if ($company->user_id !== Auth::id()) abort(403);
    }
}