<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\WorkflowTrigger;
use App\Models\WorkflowRun;
use App\Models\WorkflowLog;
use App\Events\OrderCreated;

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
        WorkflowLog::create([
            'workflow_run_id' => $run->id,
            'message' => "Workflow started by trigger: {$trigger->id}",
            'created_at' => now(),
        ]);

    }
}
