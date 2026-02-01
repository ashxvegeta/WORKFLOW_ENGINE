<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowTrigger extends Model
{
    //
    protected $fillable = [
        'workflow_id',
        'event_name',
    ];

    /* ==========================
     | Relationships
     |========================== 
     */

    //  Trigger → belongsTo → Workflow
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
