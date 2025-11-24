<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompletionType extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'type'];


    public function compliments()
    {
        return $this->hasMany(\App\Models\Compliment::class);
    }
}
