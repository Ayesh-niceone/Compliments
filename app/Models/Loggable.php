<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Loggable extends Model
{
    use LogsActivity;

    // Log every attribute in the model
    protected static $logAttributes = ['*'];

    // Log only the changed fields
    protected static $logOnlyDirty = true;

    // Log created, updated, deleted events
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    // Custom log name per model (optional)
    protected static $logName = 'system';

    // Don't log empty changes
    protected static $submitEmptyLogs = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                 // Logs ALL fields
            ->logOnlyDirty()           // Log only changed values
            ->dontSubmitEmptyLogs();   // Skip empty logs
    }
}
