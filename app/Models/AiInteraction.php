<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiInteraction extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'agent', 'prompt_summary',
        'response_summary', 'model_used', 'input_tokens',
        'output_tokens', 'duration_ms', 'was_successful',
    ];

    protected function casts(): array
    {
        return [
            'was_successful' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getEstimatedCostUsdAttribute(): float
    {
        $inputCost  = ($this->input_tokens  / 1_000_000) * 0.80;
        $outputCost = ($this->output_tokens / 1_000_000) * 4.00;
        return round($inputCost + $outputCost, 6);
    }
}