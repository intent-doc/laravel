<?php

namespace IntentDoc\Laravel\Tests\Unit;

use IntentDoc\Laravel\Intent;
use IntentDoc\Laravel\IntentRegistry;
use PHPUnit\Framework\TestCase;

class IntentRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IntentRegistry::clear();
    }

    public function test_can_register_single_intent(): void
    {
        $intent = Intent::make('Test Intent');
        IntentRegistry::register($intent);

        $all = IntentRegistry::all();

        $this->assertCount(1, $all);
    }

    public function test_can_register_multiple_intents(): void
    {
        $intent1 = Intent::make('Intent 1');
        $intent2 = Intent::make('Intent 2');
        $intent3 = Intent::make('Intent 3');

        IntentRegistry::register($intent1);
        IntentRegistry::register($intent2);
        IntentRegistry::register($intent3);

        $all = IntentRegistry::all();

        $this->assertCount(3, $all);
    }

    public function test_all_returns_array_of_arrays(): void
    {
        $intent = Intent::make('Test Intent')
            ->description('Test description')
            ->method('GET');

        IntentRegistry::register($intent);

        $all = IntentRegistry::all();

        $this->assertIsArray($all);
        $this->assertIsArray($all[0]);
        $this->assertEquals('Test Intent', $all[0]['name']);
        $this->assertEquals('Test description', $all[0]['description']);
        $this->assertEquals('GET', $all[0]['method']);
    }

    public function test_can_clear_registry(): void
    {
        $intent1 = Intent::make('Intent 1');
        $intent2 = Intent::make('Intent 2');

        IntentRegistry::register($intent1);
        IntentRegistry::register($intent2);

        $this->assertCount(2, IntentRegistry::all());

        IntentRegistry::clear();

        $this->assertCount(0, IntentRegistry::all());
    }

    public function test_registry_is_static(): void
    {
        $intent = Intent::make('Test Intent');
        IntentRegistry::register($intent);

        // Access from a different context
        $all = IntentRegistry::all();

        $this->assertCount(1, $all);
    }
}
