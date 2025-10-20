<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Compliment extends Model
{
    use HasFactory;


    protected $fillable = [

        'target_type',
        'target_id',
        'department_id',
        'care_user_id',
        'comment',
        'status',
        'name',
        'phone',
        'email'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function careUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'care_user_id');
    }


    public function histories()
    {
        return $this->hasMany(ComplimentHistory::class);
    }
}
