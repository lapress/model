<?php

namespace LaPress\Models;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Category extends Taxonomy
{
    public function getNameAttribute()
    {
        return $this->term->name;
    }

    public function getUrlAttribute()
    {
        return $this->term->url;
    }

    public static function getByName(string $name)
    {
        return Category::whereHas('term', function ($query) use($name){
            $query->whereSlug($name);
        })->first();
    }
}