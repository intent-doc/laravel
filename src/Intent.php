<?php

namespace IntentDoc\Laravel;

class Intent
{
    public function __construct(
        public string $name,
        public string $description = '',
        public array $rules = [],
        public array $request = [],
        public array $response = [],
        public ?string $method = null,
        public ?string $endpoint = null,
    ) {}

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function rules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function endpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function request(array $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function response(array $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function register(): void
    {
        IntentRegistry::register($this);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'method' => $this->method,
            'endpoint' => $this->endpoint,
            'rules' => $this->rules,
            'request' => $this->request,
            'response' => $this->response,
        ];
    }
}
