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
        'close_date',
        'worker_id',
        'images',
        'video',       // new field
        'audio',       // new field
        'missed_pay',  // new field
        'paid',        // new field
    ];

    protected $casts = [
        'images' => 'array',
        'video' => 'array',      // if storing multiple video URLs or files
        'audio' => 'array',      // if storing multiple audio files
       
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

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
