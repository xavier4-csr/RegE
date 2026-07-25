<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'reference', 'mpesa_receipt',
        'mpesa_checkout_id', 'amount', 'currency', 'type',
        'status', 'phone_number', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount'   => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Payment $p) {
            $p->reference = $p->reference ?? 'REGE-' . strtoupper(Str::random(10));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isPending():   bool { return $this->status === 'pending'; }
    public function isFailed():    bool { return $this->status === 'failed'; }
}