<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ==========================
     | Relationships
     |========================== 
     */

    //  Workflow → hasMany → Triggers
    public  function  triggers():HasMany
    {
        return $this->hasMany(WorkflowTrigger::class);
    } 

    //n  Workflow → hasMany → Conditions
    public  function  conditions():HasMany
    {
        return $this->hasMany(WorkflowCondition::class);
    }

    public function  actions():HasMany
    {
        return $this->hasMany(WorkflowAction::class)->orderBy('sequence');
    }

    // Each workflow can execute many times
    public function  runs():HasMany
    {
        return $this->hasMany(WorkflowRun::class);
    }

}
