<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceProgress extends Model
{
    protected $fillable = [
        'company_id', 'compliance_step_id', 'status',
        'due_date', 'completed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date'     => 'date',
            'completed_at' => 'date',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function complianceStep()
    {
        return $this->belongsTo(ComplianceStep::class);
    }

    public function markComplete(): void
    {
        $this->update(['status' => 'completed', 'completed_at' => now()]);
    }
}