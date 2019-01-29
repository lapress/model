<?php

namespace LaPress\Models\UrlGenerators;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class UrlGeneratorResolver
{
    /**
     * @param string $key
     * @return string
     */
    public function resolve(string $key)
    {
        $type = ucfirst($key);

        if (class_exists('App\\Http\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'App\\Http\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        if (class_exists('LaPress\\Models\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'LaPress\\Models\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        return PostUrlGenerator::class;
    }
}
