<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\WorkflowRun;
use App\Models\WorkflowLog;
use App\Mail\WorkflowActionMail;
use Illuminate\Support\Facades\Mail;

class ActionExecutor
{
    public static function execute(WorkflowRun $run): void
    {
        $actions = $run->workflow->actions;
        foreach($actions as $action){
            try {             
                Log::info('Executing action', [
                    'workflow_run_id' => $run->id,
                    'action_id' => $action->id,
                    'type' => $action->action_type,
                ]);
                //// 🔹 Execute action based on type
                match($action->action_type){
                        'log' => self::log($run, $action),
                        'email' => self::email($run, $action),
                        'webhook' => self::webhook($run, $action),
                        default => throw new \Exception("Unknown action type: {$action->action_type}"),
                    };
                // 🔹 Log SUCCESS of action
                WorkflowLog::create([
                    'workflow_run_id' => $run->id,
                    'status' => 'success',
                    'message' => "Action {$action->action_type} executed successfully",
                ]);

            } catch (\Exception $e) {
                // if any action fails, log the error and mark the workflow run as failed
                Log::error('Action execution failed', [
                    'workflow_run_id' => $run->id,
                    'action_id' => $action->id,
                    'error' => $e->getMessage(),
                ]);
                WorkflowLog::create([
                    'workflow_run_id' => $run->id,
                    'status' => 'failed',
                    'message' => "Action {$action->action_type} failed: " . $e->getMessage(),
                ]);
                $run->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                ]);
                // 🔴 STOP further actions
                return;
            }
               
        }
        // ✅ Only reached if ALL actions succeeded
        $run->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

    }

    private static function log(WorkflowRun $run, $action){

        WorkflowLog::create([ 
            'workflow_run_id' => $run->id, 
             'status' => 'info',
            'message' => $action->payload['message'] ?? 'No message provided', 
        ]); 
            
    }


    private static function email(WorkflowRun $run, $action): void
    {
        $payload = $action->payload;

        if (empty($payload['to'])) {
            throw new \Exception('Email action missing "to" address');
        }

        $subject = $payload['subject'] ?? 'Workflow Notification';
        $body    = $payload['body'] ?? 'No content provided';

        Mail::to($payload['to'])
            ->send(new WorkflowActionMail($subject, $body));

        WorkflowLog::create([
            'workflow_run_id' => $run->id,
            'status' => 'info',
            'message' => "Email sent to {$payload['to']}",
        ]);
    }

    private static function webhook(WorkflowRun $run, $action) { 
        
        WorkflowLog::create([ 
            'workflow_run_id' => $run->id, 
            'status' => 'info', 
            'message' => 'Webhook action executed (mock)', 
        ]); 
            
    } 
}