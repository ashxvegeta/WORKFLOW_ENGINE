<?php


namespace App\Jobs;

use App\Models\WorkflowRun;
use App\Services\ActionExecutor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExecuteWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public WorkflowRun $run;
    public function __construct(WorkflowRun $run)
    {
        $this->run = $run;
    }

   
    public function handle(): void
    {
        ActionExecutor::execute($this->run);
    }
}
