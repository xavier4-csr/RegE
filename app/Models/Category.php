<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'icon', 'sort_order', 'company_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'company_id', 'id');
    }
}