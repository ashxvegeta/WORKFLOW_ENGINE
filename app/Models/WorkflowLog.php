<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowLog extends Model
{
    protected $fillable = [
        'workflow_run_id',
        'status',
        'message',
    ];

    public function run(): BelongsTo
    {
        return $this->belongsTo(WorkflowRun::class, 'workflow_run_id');
    }
}
