<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    protected $fillable = [
        'internal_id',
        'employee_id',
        'access_granted',
        'attempted_at',
    ];

    protected $casts = [
        'access_granted' => 'boolean',
        'attempted_at' => 'datetime'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
