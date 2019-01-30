<?php

namespace LaPress\Models;

use LaPress\Models\Traits\HasMeta;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Menu extends Taxonomy
{
    use HasMeta;

    const TAXONOMY_KEY = 'nav_menu';
    const NAV_MENU_LOCATION_KEY = 'nav_menu_locations';

    /**
     * @var array
     */
    protected $with = ['term', 'items'];

    /**
     * @var array
     */
    protected $appends = ['name', 'slug'];

    /**
     * @var array
     */
    protected $hidden = ['term'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(static::TAXONOMY_KEY, function (Builder $builder) {
            $builder->whereTaxonomy(static::TAXONOMY_KEY);
        });
    }

    /**
     * @return string|null
     */
    public function getNameAttribute()
    {
        return optional($this->term)->name;
    }

    /**
     * @return string|null
     */
    public function getSlugAttribute()
    {
        return optional($this->term)->slug;
    }

    /**
     * @param string $key
     * @return Menu|null
     */
    public static function location(string $key)
    {
        $option = Option::collect(config('wordpress.theme.option_key'));
        $menuId = $option->get(static::NAV_MENU_LOCATION_KEY)->get($key);

        return static::where('term_taxonomy_id', $menuId) ?: new static();
    }

    /**
     * Items
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(MenuItem::class, 'term_relationships', 'term_taxonomy_id', 'object_id')
                    ->orderBy('menu_order');
    }
}
