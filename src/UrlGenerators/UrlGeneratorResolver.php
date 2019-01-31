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
     * @param null   $default
     * @return string
     */
    public function resolve(string $key, $default = null)
    {
        $type = ucfirst(camel_case($key));

        if (class_exists('App\\Http\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'App\\Http\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        if (class_exists('LaPress\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'LaPress\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        return $default ?: PostUrlGenerator::class;
    }
}
