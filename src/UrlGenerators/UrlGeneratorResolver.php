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
        if (class_exists('App\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'App\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        if (class_exists('LaPress\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'LaPress\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        return 'LaPress\\Models\\UrlGenerators\\PostUrlGenerator';
    }
}