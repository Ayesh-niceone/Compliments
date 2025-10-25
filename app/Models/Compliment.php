<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Compliment extends Model
{
    use HasFactory;


    protected $fillable = [
        'customer_name',
        'phone',
        'plate_number',
        'created_at',
        'closed_at',
        'department_id',
        'care_user_id',
        'comment',
        'care_comment',
        'status_id',
        'target_type',
        'completion_type_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function completion_type()
    {
        return $this->belongsTo(CompletionType::class, 'completion_type_id');
    }

    public function careUser()
    {
        return $this->belongsTo(User::class, 'care_user_id');
    }

}
