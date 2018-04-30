<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Taxonomy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'term_taxonomy';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'term_taxonomy_id';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['term'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Posts
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        $class = \App\Post::class;

        if (!class_exists($class)) {
            $class = Post::class;
        }

        return $this->belongsToMany($class, 'term_relationships', 'term_taxonomy_id', 'object_id');
    }

    /**
     * term
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    /**
     * Parent
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Taxonomy::class, 'parent');
    }

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        return optional($this->term)->name;
    }

    /**
     * @return mixed
     */
    public function getSlugAttribute()
    {
        return optional($this->term)->slug;
    }

    /**
     * @return mixed
     */
    public function getIdAttribute()
    {
        return $this->term_taxonomy_id;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->term->name,
            'slug'  => $this->term->slug,
            'count' => $this->count,
            'type'  => $this->taxonomy,
        ];
    }
}