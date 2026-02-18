<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
class ConditionEvaluator
{
    

   public static function evaluate($conditions, array $data): bool
   {
   
    Log::info('Evaluating conditions', [
        'conditions' => $conditions->toArray(),
        'data' => $data,
    ]);

      foreach ($conditions as $condition) {
         //  Read value from event data
         $fieldvalue = $data[$condition->field] ?? null;
           Log::info('Checking condition', [
            'field' => $condition->field,
            'operator' => $condition->operator,
            'expected' => $condition->value,
            'actual' => $fieldvalue,
        ]);
         // Read expected value from DB
         $expectedvalue = $condition->value;

         if (!self::compare($fieldvalue, $condition->operator, $expectedvalue)) {
             Log::warning('Condition FAILED');
               return false;
         }
      }

      Log::info('All conditions passed');
      return true;
   }


   private static function compare($fieldvalue, $operator, $expectedvalue): bool
   {
     
      $operator = trim($operator); // 🔥 IMPORTANT LINE
      return match($operator){
         '==' => $fieldvalue == $expectedvalue,
         '!=' => $fieldvalue != $expectedvalue,
         '>'  => $fieldvalue > $expectedvalue,
         '<'  => $fieldvalue < $expectedvalue,
         '>=' => $fieldvalue >= $expectedvalue,
         '<=' => $fieldvalue <= $expectedvalue,
         default => false,
      };
   }
}