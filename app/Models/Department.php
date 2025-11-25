<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Department extends Loggable
{
    use HasFactory;


    protected $fillable = ['name', 'code'];


    public function compliments()
    {
        return $this->hasMany(\App\Models\Compliment::class);
    }


}
