<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\ComplianceProgress;
use App\Models\ComplianceStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['index', 'show']);
    }

    // ── Public: Business Directory ────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Company::query()->active()->with('category');

        // Search
        if ($q = $request->query('q')) {
            $query->search($q);
        }

        // Category filter
        if ($slug = $request->query('category')) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // County filter
        if ($county = $request->query('county')) {
            $query->where('county', $county);
        }

        // Sort
        switch ($request->query('sort')) {
            case 'rating':
                $query->withCount('reviews');
                $query->orderByDesc('reviews_count');
                break;
            case 'views':
                $query->orderByDesc('profile_views');
                break;
            case 'name':
                $query->orderBy('company_name');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $companies  = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('company.index', compact('companies', 'categories'));
    }

    public function show(Company $company)
    {
        $company->load(['category', 'reviews.user']);
        $company->incrementViews();
        return view('company.show', compact('company'));
    }

    // ── Authenticated: Company CRUD ───────────────────────────────────────

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('company.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'        => ['required', 'string', 'max:200', 'unique:companies'],
            'owner_name'          => ['required', 'string', 'max:120'],
            'business_type'       => ['required', 'in:sole_proprietorship,partnership,llc,corporation,ngo,cooperative'],
            'category_id'         => ['nullable', 'exists:categories,id'],
            'street_address'      => ['required', 'string', 'max:200'],
            'city'                => ['nullable', 'string', 'max:100'],
            'county'              => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:20'],
            'phone'               => ['nullable', 'string', 'max:20'],
            'website'             => ['nullable', 'url', 'max:200'],
            'company_description' => ['nullable', 'string', 'max:2000'],
            'logo'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $data['logo_url'] = $request->file('logo')->store('company-logos', 'public');
        }

        $company = Company::create($data);

        // Auto-generate compliance checklist for this company
        $this->generateCompliance($company);

        return redirect()->route('companies.show', $company)
                         ->with('success', '🎉 Your business has been registered! Dashboard updated.');
    }

    public function edit(Company $company)
    {
        $this->authorizeOwner($company);
        $categories = Category::orderBy('name')->get();
        return view('company.edit', compact('company', 'categories'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeOwner($company);

        $data = $request->validate([
            'company_name'        => ['required', 'string', 'max:200', 'unique:companies,company_name,' . $company->id],
            'owner_name'          => ['required', 'string', 'max:120'],
            'business_type'       => ['required', 'in:sole_proprietorship,partnership,llc,corporation,ngo,cooperative'],
            'category_id'         => ['nullable', 'exists:categories,id'],
            'street_address'      => ['required', 'string', 'max:200'],
            'city'                => ['nullable', 'string', 'max:100'],
            'county'              => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:20'],
            'phone'               => ['nullable', 'string', 'max:20'],
            'website'             => ['nullable', 'url', 'max:200'],
            'company_description' => ['nullable', 'string', 'max:2000'],
            'registration_number' => ['nullable', 'string', 'max:60'],
            'registration_date'   => ['nullable', 'date', 'before_or_equal:today'],
            'logo'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo_url && !str_starts_with($company->logo_url, 'http')) {
                Storage::disk('public')->delete($company->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('company-logos', 'public');
        }

        $company->update($data);

        return back()->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $this->authorizeOwner($company);
        $company->delete();
        return redirect()->route('dashboard')->with('success', 'Company deleted.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function authorizeOwner(Company $company): void
    {
        if ($company->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function generateCompliance(Company $company): void
    {
        $steps = ComplianceStep::query()
            ->where(function ($q) use ($company) {
                $q->whereNull('applies_to_business_types')
                  ->orWhereJsonContains('applies_to_business_types', $company->business_type);
            })
            ->orderBy('sort_order')
            ->get();

        foreach ($steps as $step) {
            ComplianceProgress::create([
                'company_id'          => $company->id,
                'compliance_step_id'  => $step->id,
                'status'              => 'pending',
                'due_date'            => now()->addDays($step->days_after_registration),
            ]);
        }
    }
}
