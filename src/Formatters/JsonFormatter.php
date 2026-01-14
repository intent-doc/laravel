<?php

namespace IntentDoc\Laravel\Formatters;

class JsonFormatter implements FormatterInterface
{
    public function format(array $intents): string
    {
        return json_encode([
            'version' => '1.0',
            'generated_at' => now()->toIso8601String(),
            'endpoints' => $intents,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
