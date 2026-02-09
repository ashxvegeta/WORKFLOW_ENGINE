<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\WorkflowTrigger;
use App\Models\WorkflowRun;
use App\Models\WorkflowLog;
use App\Events\OrderCreated;
use App\Services\ConditionEvaluator;

class HandleWorkflowTrigger
{
    /**
     * Create the event listener.
     */
  

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $triggers = WorkflowTrigger::where('event_name','order_created')
        ->whereHas('workflow', function ($query) {
            $query->where('is_active', true);
        })->get();

        foreach($triggers  as  $trigger){
               $this->startWorkflow($trigger, $event->data);
        }
    }

    private  function startWorkflow(WorkflowTrigger $trigger, array $data){
        $run =  WorkflowRun::create([
            'workflow_id' => $trigger->workflow_id,
            'status' => 'running',
            'context' => $data,
            'started_at' => now(),
        ]);

         // 👇 NEW: check conditions
        $conditions = $trigger->workflow->conditions;
        
        if(!ConditionEvaluator::evaluate($conditions, $data)){
            $run->update(['status' => 'skipped']);
                WorkflowLog::create([
                    'workflow_run_id' => $run->id,
                    'message' => "Workflow skipped due to unmet conditions",
                    'status' => 'Skipped',
                    'created_at' => now(),
                ]);
            // stop here if conditions are not met
            return;
        }

        WorkflowLog::create([
            'workflow_run_id' => $run->id,
            'message' => "Workflow started by trigger: {$trigger->id}",
             'status' => 'Success',
            'created_at' => now(),
        ]);

    }
}
