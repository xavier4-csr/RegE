<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'company_id', 'user_id', 'rating', 'title', 'body',
        'is_verified_customer', 'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_verified_customer' => 'boolean',
            'is_approved'          => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStarsAttribute(): array
    {
        return array_map(fn($i) => $i <= $this->rating, range(1, 5));
    }
}