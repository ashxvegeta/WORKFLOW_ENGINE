<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowAction extends Model
{
    protected $fillable = [
        'workflow_id',
        'action_type',
        'payload',
        'delay_seconds',
        'sequence',
    ];

    protected $casts = [
        'payload' => 'array',
        'delay_seconds' => 'integer',
        'sequence' => 'integer',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
