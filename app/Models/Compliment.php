<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Compliment extends Model
{
    use HasFactory;


    protected $fillable = [
        'created_by_type',
        'created_by_id',
        'target_type',
        'target_id',
        'department_id',
        'care_user_id',
        'comment',
        'status'
    ];


    // Polymorphic relations via manual accessor (we store type + id)
    public function createdBy()
    {
        return $this->morphTo(__FUNCTION__, 'created_by_type', 'created_by_id');
    }


    public function target()
    {
        return $this->morphTo(__FUNCTION__, 'target_type', 'target_id');
    }


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
