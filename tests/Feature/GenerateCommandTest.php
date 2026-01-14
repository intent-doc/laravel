<?php

namespace IntentDoc\Laravel\Tests\Feature;

use IntentDoc\Laravel\Intent;
use IntentDoc\Laravel\IntentRegistry;
use IntentDoc\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\File;

class GenerateCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        IntentRegistry::clear();

        // Clean up any existing intent-doc folder
        if (File::exists(base_path('intent-doc'))) {
            File::deleteDirectory(base_path('intent-doc'));
        }
    }

    protected function tearDown(): void
    {
        // Clean up after tests
        if (File::exists(base_path('intent-doc'))) {
            File::deleteDirectory(base_path('intent-doc'));
        }

        parent::tearDown();
    }

    public function test_command_fails_when_no_intents_found(): void
    {
        $this->artisan('intent-doc:generate')
            ->expectsOutput('Loading routes to collect intent documentation...')
            ->assertExitCode(1);
    }

    public function test_command_generates_intent_doc_folder(): void
    {
        // Register a test intent
        $intent = Intent::make('Test Intent')
            ->description('Test description')
            ->method('GET')
            ->endpoint('/test');

        IntentRegistry::register($intent);

        $this->artisan('intent-doc:generate')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('intent-doc')));
        $this->assertTrue(File::exists(base_path('intent-doc/api-doc.json')));
        $this->assertTrue(File::exists(base_path('intent-doc/index.html')));
    }

    public function test_command_generates_valid_json(): void
    {
        // Register test intents
        $intent = Intent::make('Test Intent')
            ->description('Test description')
            ->method('POST')
            ->endpoint('/api/test')
            ->rules(['Rule 1', 'Rule 2']);

        IntentRegistry::register($intent);

        $this->artisan('intent-doc:generate')
            ->assertExitCode(0);

        $jsonContent = File::get(base_path('intent-doc/api-doc.json'));
        $data = json_decode($jsonContent, true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('version', $data);
        $this->assertArrayHasKey('generated_at', $data);
        $this->assertArrayHasKey('endpoints', $data);
        $this->assertCount(1, $data['endpoints']);
        $this->assertEquals('Test Intent', $data['endpoints'][0]['name']);
    }

    public function test_command_with_custom_output_generates_file(): void
    {
        $intent = Intent::make('Test Intent');
        IntentRegistry::register($intent);

        $outputPath = base_path('custom-output.json');

        $this->artisan('intent-doc:generate', [
            '--output' => $outputPath,
        ])->assertExitCode(0);

        $this->assertTrue(File::exists($outputPath));

        // Clean up
        File::delete($outputPath);
    }
}
