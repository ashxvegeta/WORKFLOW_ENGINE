<?php

namespace App\Services;

class TemplateParser
{
    public static function parse(string $text, array $context): string
    {
        foreach ($context as $key => $value) {

            $text = str_replace(
                "{{".$key."}}",
                $value,
                $text
            );
        }

        return $text;
    }
}