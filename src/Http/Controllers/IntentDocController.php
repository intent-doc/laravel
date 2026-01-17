<?php

namespace IntentDoc\Laravel\Http\Controllers;

use Illuminate\Routing\Controller;
use IntentDoc\Laravel\IntentRegistry;
use IntentDoc\Laravel\Formatters\JsonFormatter;

class IntentDocController extends Controller
{
    public function index()
    {
        return view('intent-doc::viewer');
    }

    public function api()
    {
        // Load all route files to ensure all intents are registered
        $this->loadAllRouteFiles();

        $intents = IntentRegistry::all();

        $formatter = new JsonFormatter();
        $json = $formatter->format($intents);

        return response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Load all route files from the routes directory.
     */
    protected function loadAllRouteFiles(): void
    {
        $routesPath = base_path('routes');

        if (!is_dir($routesPath)) {
            return;
        }

        $routeFiles = glob($routesPath . '/*.php');

        foreach ($routeFiles as $routeFile) {
            try {
                require_once $routeFile;
            } catch (\Throwable) {
                // Silently ignore route loading errors in API context
            }
        }

        // Also trigger Laravel's route collection
        app('router')->getRoutes();
    }
}
