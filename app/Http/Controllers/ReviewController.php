<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Notification;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function store(Request $request, Company $company)
    {
        if ($company->user_id === Auth::id()) {
            return back()->withErrors(['review' => "You can't review your own company."]);
        }

        $existing = Review::where('company_id', $company->id)
                          ->where('user_id', Auth::id())->first();

        if ($existing) {
            return back()->withErrors(['review' => "You've already reviewed this company."]);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title'  => ['nullable', 'string', 'max:120'],
            'body'   => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'company_id' => $company->id,
            'user_id'    => Auth::id(),
            'rating'     => $data['rating'],
            'title'      => $data['title'] ?? null,
            'body'       => $data['body'] ?? null,
        ]);

        Notification::create([
            'user_id'    => $company->user_id,
            'title'      => '⭐ New review on ' . $company->company_name,
            'body'       => Auth::user()->full_name . ' left a ' . $data['rating'] . '-star review.',
            'type'       => 'review_received',
            'action_url' => route('companies.show', $company),
        ]);

        return back()->with('success', 'Review submitted. Thank you!');
    }
}