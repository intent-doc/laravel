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
        // Force load all routes to trigger intent registration
        app('router')->getRoutes();

        $intents = IntentRegistry::all();

        $formatter = new JsonFormatter();
        $json = $formatter->format($intents);

        return response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
