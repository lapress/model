<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Category extends Taxonomy
{
    const TAXONOMY_KEY = 'category';
    /**
     * @var array
     */
    protected $guarded = [];
    
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(static::TAXONOMY_KEY, function (Builder $builder) {
            $builder->whereTaxonomy(static::TAXONOMY_KEY);
        });
    }

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
        return static::whereHas('term', function ($query) use ($name) {
            $query->whereSlug($name);
        })->first();
    }

    /**
     * @param string $name
     * @param null $slug
     * @return Category
     */
    public static function add(string $name, $slug = null)
    {
        $term = Term::create([
            'name' => $name,
            'slug' => $slug ?: str_slug($name), 
        ]);
        
        return static::create([
            'description' => '' ,
            'taxonomy' => static::TAXONOMY_KEY,
            'term_id' => $term->term_id;
        ]);
    }
}
