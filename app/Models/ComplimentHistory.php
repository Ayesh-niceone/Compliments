<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComplimentHistory extends Model
{
    use HasFactory;


    protected $fillable = ['compliment_id', 'user_id', 'action', 'note'];


    public function compliment()
    {
        return $this->belongsTo(Compliment::class);
    }


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
