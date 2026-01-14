<?php

namespace IntentDoc\Laravel\Formatters;

interface FormatterInterface
{
    public function format(array $intents): string;
}
