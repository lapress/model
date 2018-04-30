<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Model;
use LaPress\Models\UrlGenerators\UrlGeneratorResolver;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Term extends Model
{
    /**
     * @var string
     */
    protected $table = 'terms';

    /**
     * @var string
     */
    protected $primaryKey = 'term_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Taxonomy
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function taxonomy()
    {
        return $this->hasOne(Taxonomy::class, 'term_id');
    }

    public function getAnchorAttribute()
    {
        return $this->name;
    }

    public function getUrlAttribute()
    {
        $class = (new UrlGeneratorResolver())->resolve($this->taxonomy->taxonomy);

        return (new $class($this))->get();
    }

    public function getUrlGenerator()
    {
        $type = ucfirst($this->taxonomy->taxonomy);
        dump($type);die();

        if (class_exists('App\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'App\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        if (class_exists('LaPress\\UrlGenerators\\'.$type.'UrlGenerator')) {
            return 'LaPress\\UrlGenerators\\'.$type.'UrlGenerator';
        }

        return 'LaPress\\Models\\UrlGenerators\\CategoryUrlGenerator';
    }
}