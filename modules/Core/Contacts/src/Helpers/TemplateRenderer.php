<?php

namespace Modules\Core\Contacts\Helpers;

/**
 * This is base module class that could be reused in other modules.
 */
class TemplateRenderer
{
    /**
     * Render a template with placeholders replaced by data.
     *
     * @param string $template
     * @param array $data
     * @return string
     */
    public static function render(string $template, array $data): string
    {
        return preg_replace_callback('/\{([\w\.]+)\}/', function ($matches) use ($data) {
            $key = $matches[1];
            return self::getNestedValue($data, $key) ?? $matches[0];
        }, $template);
    }

    /**
     * Get a nested value from an array using dot notation.
     *
     * @param array $data
     * @param string $key
     * @return mixed
     */
    private static function getNestedValue(array $data, string $key): mixed
    {
        $keys = explode('.', $key);
        foreach ($keys as $k) {
            if (!isset($data[$k])) {
                return null;
            }
            $data = $data[$k];
        }
        return $data;
    }
}
