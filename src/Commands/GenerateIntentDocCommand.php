<?php

namespace IntentDoc\Laravel\Commands;

use Illuminate\Console\Command;
use IntentDoc\Laravel\IntentRegistry;
use IntentDoc\Laravel\Formatters\JsonFormatter;
use IntentDoc\Laravel\Formatters\MarkdownFormatter;
use IntentDoc\Laravel\Formatters\HtmlFormatter;

class GenerateIntentDocCommand extends Command
{
    protected $signature = 'intent-doc:generate
                            {--format=json : Output format (json, markdown, html)}
                            {--output= : Output file path}';

    protected $description = 'Generate API documentation from intent definitions';

    public function handle(): int
    {
        $this->info('Loading routes to collect intent documentation...');

        // Force load all routes to trigger intent registration
        app('router')->getRoutes();

        $intents = IntentRegistry::all();

        if (empty($intents)) {
            $this->warn('No intent documentation found. Make sure your routes use ->intent()');
            return self::FAILURE;
        }

        $this->info('Found ' . count($intents) . ' documented endpoints');

        $format = $this->option('format');
        $output = $this->option('output');

        // If no output specified, generate the intent-doc folder structure
        if (!$output) {
            return $this->generateIntentDocFolder($intents);
        }

        // Legacy behavior: generate single file with specified format
        $formatter = match ($format) {
            'markdown' => new MarkdownFormatter(),
            'html' => new HtmlFormatter(),
            default => new JsonFormatter(),
        };

        $content = $formatter->format($intents);

        file_put_contents($output, $content);
        $this->info("Documentation generated: {$output}");

        return self::SUCCESS;
    }

    protected function generateIntentDocFolder(array $intents): int
    {
        $docPath = base_path('intent-doc');

        // Create intent-doc directory if it doesn't exist
        if (!is_dir($docPath)) {
            mkdir($docPath, 0755, true);
            $this->info('Created intent-doc/ directory');
        }

        // Generate api-doc.json
        $jsonFormatter = new JsonFormatter();
        $jsonContent = $jsonFormatter->format($intents);
        $jsonPath = $docPath . '/api-doc.json';
        file_put_contents($jsonPath, $jsonContent);
        $this->info('Generated: intent-doc/api-doc.json');

        // Generate index.html with embedded JSON (no CORS issues)
        $htmlContent = $this->getIndexHtmlTemplate($jsonContent);
        $htmlPath = $docPath . '/index.html';
        file_put_contents($htmlPath, $htmlContent);
        $this->info('Generated: intent-doc/index.html');

        $this->newLine();
        $this->info('Documentation generated successfully!');
        $this->line('  Open intent-doc/index.html in your browser to view the documentation.');

        return self::SUCCESS;
    }

    protected function getIndexHtmlTemplate(string $jsonData): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - IntentDoc</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fafafa;
            color: #3b4151;
            line-height: 1.6;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 1.5rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #3b4151;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #61affe, #49cc90);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .version-badge {
            font-size: 0.7rem;
            background: #e8e8e8;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .search-box {
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: #61affe;
        }

        .nav-section {
            margin-bottom: 1rem;
        }

        .nav-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #8a8a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .nav-items {
            padding: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            color: inherit;
        }

        .nav-item:hover {
            background: #e8e8e8;
        }

        /* Main Content */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
        }

        .api-info {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .api-info h1 {
            margin-bottom: 0.5rem;
        }

        .api-info p {
            color: #666;
            margin-bottom: 1rem;
        }

        .endpoint-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .endpoint-header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            border-bottom: 1px solid transparent;
        }

        .endpoint-header:hover {
            background: #f9f9f9;
        }

        .endpoint-card.expanded .endpoint-header {
            border-bottom: 1px solid #e0e0e0;
        }

        /* Method Badges */
        .method {
            padding: 0.3rem 0.6rem;
            border-radius: 3px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            min-width: 60px;
            text-align: center;
        }

        .method-get { background: #61affe; color: #fff; }
        .method-post { background: #49cc90; color: #fff; }
        .method-put { background: #fca130; color: #fff; }
        .method-delete { background: #f93e3e; color: #fff; }
        .method-patch { background: #50e3c2; color: #fff; }

        .method-badge-small {
            padding: 0.15rem 0.4rem;
            border-radius: 3px;
            font-weight: 700;
            font-size: 0.65rem;
            text-transform: uppercase;
        }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            flex: 1;
        }

        .endpoint-summary {
            color: #666;
            font-size: 0.9rem;
        }

        .endpoint-body {
            display: none;
            padding: 1.5rem;
        }

        .endpoint-card.expanded .endpoint-body {
            display: block;
        }

        .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #8a8a8a;
            text-transform: uppercase;
            margin: 1.5rem 0 0.75rem 0;
            letter-spacing: 0.5px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        /* Code Block */
        .code-block {
            background: #292929;
            color: #f1f1f1;
            padding: 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            overflow-x: auto;
        }

        .code-block .key { color: #f8c555; }
        .code-block .string { color: #98c379; }
        .code-block .number { color: #d19a66; }
        .code-block .boolean { color: #56b6c2; }
        .code-block .null { color: #c678dd; }

        /* Rules List */
        .rules-list {
            list-style: none;
            padding: 0;
        }

        .rules-list li {
            padding: 0.6rem 1rem;
            background: #f8f9fa;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            border-left: 3px solid #61affe;
        }

        .arrow {
            transition: transform 0.2s;
        }

        .endpoint-card.expanded .arrow {
            transform: rotate(180deg);
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .generated-info {
            font-size: 0.85rem;
            color: #8a8a8a;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-icon">API</div>
                <span>IntentDoc</span>
                <span class="version-badge" id="sidebar-version">v1.0</span>
            </div>

            <div class="search-box">
                <input type="text" id="search" placeholder="Search endpoints...">
            </div>

            <div class="nav-section">
                <div class="nav-title">Endpoints</div>
                <div class="nav-items" id="nav-items"></div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <div class="api-info">
                <h1>API Documentation</h1>
                <p>Intent-driven documentation for your Laravel API</p>
                <div class="generated-info">
                    <span id="endpoint-count">0 endpoints</span> |
                    Generated: <span id="generated-at">-</span>
                </div>
            </div>

            <div id="endpoints-container"></div>
            <div id="no-results" class="no-results" style="display: none;">
                No endpoints found matching your search.
            </div>
        </main>
    </div>

    <script>
        // Embedded JSON data (no CORS issues)
        const documentation = {$jsonData};

        // Initialize
        displayMetaInfo(documentation);
        displayNavItems(documentation.endpoints);
        displayEndpoints(documentation.endpoints);
        setupSearch();

        function displayMetaInfo(data) {
            document.getElementById('sidebar-version').textContent = 'v' + (data.version || '1.0');
            document.getElementById('endpoint-count').textContent = data.endpoints.length + ' endpoint' + (data.endpoints.length !== 1 ? 's' : '');

            if (data.generated_at) {
                const date = new Date(data.generated_at);
                document.getElementById('generated-at').textContent = date.toLocaleString();
            }
        }

        function displayNavItems(endpoints) {
            const container = document.getElementById('nav-items');
            container.innerHTML = '';

            endpoints.forEach(function(endpoint, index) {
                const method = (endpoint.method || 'GET').toLowerCase();
                const item = document.createElement('a');
                item.className = 'nav-item';
                item.href = '#endpoint-' + index;
                item.innerHTML = '<span class="method-badge-small method-' + method + '">' + (endpoint.method || 'GET').substring(0, 3).toUpperCase() + '</span> ' + (endpoint.name || endpoint.intent || 'Unnamed');
                container.appendChild(item);
            });
        }

        function displayEndpoints(endpoints) {
            const container = document.getElementById('endpoints-container');
            container.innerHTML = '';

            if (endpoints.length === 0) {
                document.getElementById('no-results').style.display = 'block';
                return;
            }

            document.getElementById('no-results').style.display = 'none';

            endpoints.forEach(function(endpoint, index) {
                const endpointEl = createEndpointElement(endpoint, index);
                container.appendChild(endpointEl);
            });
        }

        function createEndpointElement(endpoint, index) {
            const div = document.createElement('div');
            div.className = 'endpoint-card';
            div.id = 'endpoint-' + index;

            const method = (endpoint.method || 'GET').toLowerCase();

            let bodyContent = '';

            // Description
            if (endpoint.description) {
                bodyContent += '<p style="margin-bottom: 1rem;">' + escapeHtml(endpoint.description) + '</p>';
            }

            // Rules
            if (endpoint.rules && endpoint.rules.length > 0) {
                bodyContent += '<h4 class="section-title">Rules & Constraints</h4><ul class="rules-list">';
                endpoint.rules.forEach(function(rule) {
                    bodyContent += '<li>' + escapeHtml(rule) + '</li>';
                });
                bodyContent += '</ul>';
            }

            // Request
            if (endpoint.request) {
                bodyContent += '<h4 class="section-title">Request Example</h4><div class="code-block"><pre>' + syntaxHighlight(endpoint.request) + '</pre></div>';
            }

            // Response
            if (endpoint.response) {
                bodyContent += '<h4 class="section-title">Response Example</h4><div class="code-block"><pre>' + syntaxHighlight(endpoint.response) + '</pre></div>';
            }

            div.innerHTML = '<div class="endpoint-header">' +
                '<span class="method method-' + method + '">' + (endpoint.method || 'GET') + '</span>' +
                '<code class="endpoint-path">' + escapeHtml(endpoint.endpoint || endpoint.uri || '-') + '</code>' +
                '<span class="endpoint-summary">' + escapeHtml(endpoint.name || endpoint.intent || 'Unnamed endpoint') + '</span>' +
                '<span class="arrow">&#9660;</span>' +
                '</div>' +
                '<div class="endpoint-body">' + (bodyContent || '<p>No additional information available.</p>') + '</div>';

            div.querySelector('.endpoint-header').addEventListener('click', function() {
                div.classList.toggle('expanded');
            });

            return div;
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return text;
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function syntaxHighlight(obj) {
            let json = JSON.stringify(obj, null, 2);
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\\\u[a-zA-Z0-9]{4}|\\\\[^u]|[^\\\\"])*"(\\s*:)?|\\b(true|false|null)\\b|-?\\d+(?:\\.\\d*)?(?:[eE][+\\-]?\\d+)?)/g, function (match) {
                let cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                        match = match.slice(0, -1) + '</span>:';
                        return '<span class="' + cls + '">' + match;
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        function setupSearch() {
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();

                if (!query) {
                    displayNavItems(documentation.endpoints);
                    displayEndpoints(documentation.endpoints);
                    return;
                }

                const filtered = documentation.endpoints.filter(function(endpoint) {
                    return (
                        (endpoint.name || '').toLowerCase().indexOf(query) !== -1 ||
                        (endpoint.intent || '').toLowerCase().indexOf(query) !== -1 ||
                        (endpoint.endpoint || '').toLowerCase().indexOf(query) !== -1 ||
                        (endpoint.uri || '').toLowerCase().indexOf(query) !== -1 ||
                        (endpoint.method || '').toLowerCase().indexOf(query) !== -1 ||
                        (endpoint.description || '').toLowerCase().indexOf(query) !== -1
                    );
                });

                displayNavItems(filtered);
                displayEndpoints(filtered);
            });
        }
    </script>
</body>
</html>
HTML;
    }
}
