<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceStep extends Model
{
    protected $fillable = [
        'title', 'description', 'authority', 'portal_url',
        'days_after_registration', 'is_mandatory',
        'applies_to_business_types', 'required_documents', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'applies_to_business_types' => 'array',
            'required_documents'        => 'array',
            'is_mandatory'              => 'boolean',
        ];
    }

    public function progress()
    {
        return $this->hasMany(ComplianceProgress::class);
    }

    public function scopeForBusinessType($query, string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->whereNull('applies_to_business_types')
              ->orWhereJsonContains('applies_to_business_types', $type);
        });
    }
}