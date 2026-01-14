<?php

namespace IntentDoc\Laravel;

class IntentRegistry
{
    protected static array $intents = [];

    public static function register(Intent $intent): void
    {
        self::$intents[] = $intent;
    }

    public static function all(): array
    {
        return array_map(
            fn (Intent $intent) => $intent->toArray(),
            self::$intents
        );
    }

    public static function clear(): void
    {
        self::$intents = [];
    }
}
