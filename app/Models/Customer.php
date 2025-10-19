<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'phone', 'email'];


    public function complimentsCreated()
    {
        return $this->morphMany(\App\Models\Compliment::class, 'created_by');
    }


    public function complimentsTargeted()
    {
        return $this->morphMany(\App\Models\Compliment::class, 'target');
    }
}
