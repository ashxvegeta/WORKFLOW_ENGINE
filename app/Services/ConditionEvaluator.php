<?php

namespace App\Services;

class ConditionEvaluator
{
    

   public static function evaluate($conditions, array $data): bool
   {
      foreach ($conditions as $condition) {
         //  Read value from event data
         $fieldvalue = $data[$condition->field] ?? null;
         // Read expected value from DB
         $expectedvalue = $condition->value;

         if (!self::compare($fieldvalue, $condition->operator, $expectedvalue)) {
               return false;
         }
      }

      return true;
   }


   private static function compare($fieldvalue, $operator, $expectedvalue): bool
   {
     
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