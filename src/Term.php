<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Model;
use LaPress\Models\UrlGenerators\CategoryUrlGenerator;
use LaPress\Models\UrlGenerators\UrlGeneratorResolver;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Term extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
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

    /**
     * @return mixed
     */
    public function getAnchorAttribute()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUrlAttribute()
    {
        $class = (new UrlGeneratorResolver())->resolve(
            $this->taxonomy->taxonomy,
            CategoryUrlGenerator::class
        );

        return (new $class($this))->get();
    }
}
