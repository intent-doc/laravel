<?php

namespace IntentDoc\Laravel\Tests\Unit;

use IntentDoc\Laravel\Intent;
use IntentDoc\Laravel\IntentRegistry;
use PHPUnit\Framework\TestCase;

class IntentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IntentRegistry::clear();
    }

    public function test_can_create_intent_with_name(): void
    {
        $intent = Intent::make('Test Intent');

        $this->assertInstanceOf(Intent::class, $intent);
        $this->assertEquals('Test Intent', $intent->name);
    }

    public function test_can_set_description(): void
    {
        $intent = Intent::make('Test Intent')
            ->description('This is a test description');

        $this->assertEquals('This is a test description', $intent->description);
    }

    public function test_can_set_rules(): void
    {
        $rules = ['Rule 1', 'Rule 2', 'Rule 3'];
        $intent = Intent::make('Test Intent')->rules($rules);

        $this->assertEquals($rules, $intent->rules);
    }

    public function test_can_set_method(): void
    {
        $intent = Intent::make('Test Intent')->method('POST');

        $this->assertEquals('POST', $intent->method);
    }

    public function test_can_set_endpoint(): void
    {
        $intent = Intent::make('Test Intent')->endpoint('/api/test');

        $this->assertEquals('/api/test', $intent->endpoint);
    }

    public function test_can_set_request_example(): void
    {
        $request = ['name' => 'John', 'email' => 'john@example.com'];
        $intent = Intent::make('Test Intent')->request($request);

        $this->assertEquals($request, $intent->request);
    }

    public function test_can_set_response_example(): void
    {
        $response = ['status' => 'success', 'data' => ['id' => 1]];
        $intent = Intent::make('Test Intent')->response($response);

        $this->assertEquals($response, $intent->response);
    }

    public function test_can_chain_methods(): void
    {
        $intent = Intent::make('Test Intent')
            ->description('Test description')
            ->rules(['Rule 1'])
            ->method('POST')
            ->endpoint('/api/test')
            ->request(['input' => 'value'])
            ->response(['output' => 'result']);

        $this->assertEquals('Test Intent', $intent->name);
        $this->assertEquals('Test description', $intent->description);
        $this->assertEquals(['Rule 1'], $intent->rules);
        $this->assertEquals('POST', $intent->method);
        $this->assertEquals('/api/test', $intent->endpoint);
        $this->assertEquals(['input' => 'value'], $intent->request);
        $this->assertEquals(['output' => 'result'], $intent->response);
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $intent = Intent::make('Test Intent')
            ->description('Test description')
            ->method('GET')
            ->endpoint('/test');

        $array = $intent->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('method', $array);
        $this->assertArrayHasKey('endpoint', $array);
        $this->assertArrayHasKey('rules', $array);
        $this->assertArrayHasKey('request', $array);
        $this->assertArrayHasKey('response', $array);
    }

    public function test_can_register_intent(): void
    {
        $intent = Intent::make('Test Intent');
        $intent->register();

        $registered = IntentRegistry::all();

        $this->assertCount(1, $registered);
        $this->assertEquals('Test Intent', $registered[0]['name']);
    }
}
