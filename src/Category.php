<?php

namespace LaPress\Models;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Category extends Taxonomy
{
    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->term->name;
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->term->url;
    }

    /**
     * @param string $name
     * @return Category|null
     */
    public static function getByName(string $name)
    {
        return static::whereHas('term', function ($query) use($name){
            $query->whereSlug($name);
        })->first();
    }
}