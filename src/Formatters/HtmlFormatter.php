<?php

namespace IntentDoc\Laravel\Formatters;

class HtmlFormatter implements FormatterInterface
{
    public function format(array $intents): string
    {
        $html = $this->getHeader();

        foreach ($intents as $intent) {
            $html .= $this->formatIntent($intent);
        }

        $html .= $this->getFooter();

        return $html;
    }

    protected function formatIntent(array $intent): string
    {
        $html = '<div class="endpoint">';
        $html .= '<h2>' . htmlspecialchars($intent['name']) . '</h2>';

        if (!empty($intent['description'])) {
            $html .= '<p class="description">' . htmlspecialchars($intent['description']) . '</p>';
        }

        $method = strtoupper($intent['method'] ?? 'GET');
        $methodClass = strtolower($method);
        $html .= '<div class="endpoint-info">';
        $html .= '<span class="method method-' . $methodClass . '">' . $method . '</span>';
        $html .= '<code class="path">' . htmlspecialchars($intent['endpoint']) . '</code>';
        $html .= '</div>';

        if (!empty($intent['rules'])) {
            $html .= '<div class="rules">';
            $html .= '<h3>Rules</h3>';
            $html .= '<ul>';
            foreach ($intent['rules'] as $rule) {
                $html .= '<li>' . htmlspecialchars($rule) . '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
        }

        if (!empty($intent['request'])) {
            $html .= '<div class="request">';
            $html .= '<h3>Request</h3>';
            $html .= '<pre><code>' . htmlspecialchars(json_encode($intent['request'], JSON_PRETTY_PRINT)) . '</code></pre>';
            $html .= '</div>';
        }

        if (!empty($intent['response'])) {
            $html .= '<div class="response">';
            $html .= '<h3>Response</h3>';
            $html .= '<pre><code>' . htmlspecialchars(json_encode($intent['response'], JSON_PRETTY_PRINT)) . '</code></pre>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    protected function getHeader(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - IntentDoc</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        h1 { font-size: 2.5rem; margin-bottom: 0.5rem; color: #2c3e50; }
        .generated { color: #7f8c8d; font-size: 0.9rem; margin-bottom: 2rem; }
        .endpoint { background: white; border-radius: 8px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .endpoint h2 { font-size: 1.8rem; margin-bottom: 1rem; color: #2c3e50; }
        .description { color: #555; margin-bottom: 1rem; font-size: 1.1rem; }
        .endpoint-info { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 4px; }
        .method { padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: bold; font-size: 0.85rem; text-transform: uppercase; }
        .method-get { background: #61affe; color: white; }
        .method-post { background: #49cc90; color: white; }
        .method-put { background: #fca130; color: white; }
        .method-patch { background: #50e3c2; color: white; }
        .method-delete { background: #f93e3e; color: white; }
        .path { font-family: "Courier New", monospace; font-size: 1rem; }
        .rules, .request, .response { margin-top: 1.5rem; }
        h3 { font-size: 1.2rem; margin-bottom: 0.75rem; color: #34495e; }
        ul { list-style: none; padding-left: 0; }
        li { padding: 0.5rem 0; padding-left: 1.5rem; position: relative; }
        li:before { content: "•"; position: absolute; left: 0; color: #3498db; font-weight: bold; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto; border-left: 3px solid #3498db; }
        code { font-family: "Courier New", monospace; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>API Documentation</h1>
        <p class="generated">Generated at: {$this->getTimestamp()}</p>
HTML;
    }

    protected function getFooter(): string
    {
        return <<<HTML
    </div>
</body>
</html>
HTML;
    }

    protected function getTimestamp(): string
    {
        return now()->toDateTimeString();
    }
}
