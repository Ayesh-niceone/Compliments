<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'job_title', 'phone', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

