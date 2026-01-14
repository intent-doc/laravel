<?php

namespace IntentDoc\Laravel;

use Illuminate\Routing\Route;
use IntentDoc\Laravel\Intent;

class RouteIntentBuilder
{
    protected Route $route;
    protected Intent $intent;

    public function __construct(Route $route, string $name)
    {
        $this->route = $route;

        $this->intent = Intent::make($name)
            ->method($route->methods()[0] ?? null)
            ->endpoint('/' . ltrim($route->uri(), '/'));
    }

    public function description(string $description): self
    {
        $this->intent->description($description);
        return $this;
    }

    public function rules(array $rules): self
    {
        $this->intent->rules($rules);
        return $this;
    }

    public function request(array $request): self
    {
        $this->intent->request($request);
        return $this;
    }

    public function response(array $response): self
    {
        $this->intent->response($response);
        return $this;
    }

    /**
     * Proxy method calls to the underlying Route object.
     * This allows chaining of Route methods after intent methods.
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->route->{$method}(...$parameters);
    }

    public function __destruct()
    {
        $this->intent->register();
    }
}
