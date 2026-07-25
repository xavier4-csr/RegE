<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'company_id', 'title', 'type',
        'file_path', 'file_name', 'file_size', 'is_generated', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at'   => 'date',
            'is_generated' => 'boolean',
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

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return 'Unknown';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $size = $this->file_size;
        while ($size >= 1024 && $i < 3) {
            $size /= 1024;
            $i++;
        }
        return round($size, 1) . ' ' . $units[$i];
    }
}