<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'company_name', 'slug', 'owner_name',
        'business_type', 'street_address', 'city', 'county', 'postal_code',
        'latitude', 'longitude', 'website', 'phone', 'email',
        'company_description', 'logo_url', 'gallery_images',
        'registration_number', 'registration_date', 'annual_return_due',
        'status', 'is_featured', 'is_verified', 'profile_views',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images'    => 'array',
            'registration_date' => 'date',
            'annual_return_due' => 'date',
            'is_featured'       => 'boolean',
            'is_verified'       => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Company $company) {
            $company->slug = Str::slug($company->company_name);
            if ($company->registration_date && !$company->annual_return_due) {
                $company->annual_return_due = $company->registration_date->addYear();
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function complianceProgress()
    {
        return $this->hasMany(ComplianceProgress::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function aiInteractions()
    {
        return $this->hasMany(AiInteraction::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCounty($query, string $county)
    {
        return $query->where('county', $county);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('company_name', 'like', "%{$term}%")
              ->orWhere('company_description', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%")
              ->orWhere('county', 'like', "%{$term}%");
        });
    }

    // ── Computed attributes ────────────────────────────────────────────────

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getCompliancePercentAttribute(): int
    {
        $total = $this->complianceProgress()->count();
        if ($total === 0) return 0;
        $done = $this->complianceProgress()->where('status', 'completed')->count();
        return (int) round(($done / $total) * 100);
    }

    public function getLogoAttribute(): string
    {
        return $this->logo_url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->company_name) . '&background=007bff&color=fff&size=128';
    }

    public function incrementViews(): void
    {
        static::withoutEvents(fn () => $this->increment('profile_views'));
    }
}