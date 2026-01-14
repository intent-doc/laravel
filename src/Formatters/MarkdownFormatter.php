<?php

namespace IntentDoc\Laravel\Formatters;

class MarkdownFormatter implements FormatterInterface
{
    public function format(array $intents): string
    {
        $markdown = "# API Documentation\n\n";
        $markdown .= "_Generated at: " . now()->toDateTimeString() . "_\n\n";
        $markdown .= "---\n\n";

        foreach ($intents as $intent) {
            $markdown .= "## {$intent['name']}\n\n";

            if (!empty($intent['description'])) {
                $markdown .= "**Description:** {$intent['description']}\n\n";
            }

            $markdown .= "**Endpoint:** `{$intent['method']} {$intent['endpoint']}`\n\n";

            if (!empty($intent['rules'])) {
                $markdown .= "**Rules:**\n";
                foreach ($intent['rules'] as $rule) {
                    $markdown .= "- {$rule}\n";
                }
                $markdown .= "\n";
            }

            if (!empty($intent['request'])) {
                $markdown .= "**Request:**\n```json\n";
                $markdown .= json_encode($intent['request'], JSON_PRETTY_PRINT);
                $markdown .= "\n```\n\n";
            }

            if (!empty($intent['response'])) {
                $markdown .= "**Response:**\n```json\n";
                $markdown .= json_encode($intent['response'], JSON_PRETTY_PRINT);
                $markdown .= "\n```\n\n";
            }

            $markdown .= "---\n\n";
        }

        return $markdown;
    }
}
