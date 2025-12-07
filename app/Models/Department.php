<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Department extends Loggable
{
    use HasFactory;


    protected $fillable = ['name', 'code'];
    protected $casts = [
        'name' => 'array',
    ];

    public function compliments()
    {
        return $this->hasMany(\App\Models\Compliment::class);
    }

    public function getNameLangAttribute()
    {
        return $this->name[app()->getLocale() === 'ar' ? 'name_ar' : 'name_en'] ?? null;
    }
}
