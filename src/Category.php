<?php

namespace LaPress\Models;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Category extends Taxonomy
{
    /**
     * @return string|null
     */
    public function getNameAttribute()
    {
        return $this->term->name;
    }

    /**
     * @return string|null
     */
    public function getUrlAttribute()
    {
        return $this->term->url;
    }

    /**
     * @param string $name
     * @return Category
     */
    public static function getByName(string $name)
    {
        return Category::whereHas('term', function ($query) use($name){
            $query->whereSlug($name);
        })->first();
    }
}
