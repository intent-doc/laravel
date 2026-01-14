<?php

namespace IntentDoc\Laravel\Tests\Feature;

use IntentDoc\Laravel\IntentRegistry;
use IntentDoc\Laravel\Tests\TestCase;

class RouteIntentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IntentRegistry::clear();
    }

    public function test_intent_macro_is_available_on_routes(): void
    {
        $this->app['router']->get('/test', function () {
            return 'test';
        })->intent('Test Intent');

        $intents = IntentRegistry::all();

        $this->assertCount(1, $intents);
        $this->assertEquals('Test Intent', $intents[0]['name']);
    }

    public function test_can_chain_description_after_intent(): void
    {
        $this->app['router']->get('/test', function () {
            return 'test';
        })->intent('Test Intent')
          ->description('This is a test description');

        $intents = IntentRegistry::all();

        $this->assertCount(1, $intents);
        $this->assertEquals('Test Intent', $intents[0]['name']);
        $this->assertEquals('This is a test description', $intents[0]['description']);
    }

    public function test_can_chain_rules_after_intent(): void
    {
        $this->app['router']->get('/test', function () {
            return 'test';
        })->intent('Test Intent')
          ->rules(['Rule 1', 'Rule 2']);

        $intents = IntentRegistry::all();

        $this->assertCount(1, $intents);
        $this->assertEquals(['Rule 1', 'Rule 2'], $intents[0]['rules']);
    }

    public function test_can_chain_request_and_response(): void
    {
        $this->app['router']->post('/test', function () {
            return 'test';
        })->intent('Test Intent')
          ->request(['name' => 'John'])
          ->response(['status' => 'success']);

        $intents = IntentRegistry::all();

        $this->assertCount(1, $intents);
        $this->assertEquals(['name' => 'John'], $intents[0]['request']);
        $this->assertEquals(['status' => 'success'], $intents[0]['response']);
    }

    public function test_can_chain_route_methods_after_intent_methods(): void
    {
        $route = $this->app['router']->get('/test', function () {
            return 'test';
        })->intent('Test Intent')
          ->description('Test description')
          ->middleware('web')
          ->name('test.route');

        // Verify the route has the middleware and name
        $this->assertTrue(in_array('web', $route->middleware()));
        $this->assertEquals('test.route', $route->getName());

        // Verify the intent was registered
        $intents = IntentRegistry::all();
        $this->assertCount(1, $intents);
        $this->assertEquals('Test Intent', $intents[0]['name']);
    }

    public function test_intent_captures_http_method_and_endpoint(): void
    {
        $this->app['router']->post('/api/users/{id}', function () {
            return 'test';
        })->intent('Update User');

        $intents = IntentRegistry::all();

        $this->assertCount(1, $intents);
        $this->assertEquals('POST', $intents[0]['method']);
        $this->assertEquals('/api/users/{id}', $intents[0]['endpoint']);
    }

    public function test_multiple_routes_with_intents(): void
    {
        $this->app['router']->get('/users', function () {
            return 'users';
        })->intent('List Users');

        $this->app['router']->post('/users', function () {
            return 'create';
        })->intent('Create User');

        $this->app['router']->get('/users/{id}', function () {
            return 'show';
        })->intent('Show User');

        $intents = IntentRegistry::all();

        $this->assertCount(3, $intents);
        $this->assertEquals('List Users', $intents[0]['name']);
        $this->assertEquals('Create User', $intents[1]['name']);
        $this->assertEquals('Show User', $intents[2]['name']);
    }
}
