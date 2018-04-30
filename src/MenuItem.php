<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Model;
use LaPress\Models\Traits\HasMeta;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class MenuItem extends AbstractPost
{
    use HasMeta;

    protected $postType = 'nav_menu_item';

    /**
     * @return null|string
     */
    public function getAnchorAttribute(): ?string
    {
        return $this->post_title ?: optional($this->instance())->anchor;
    }

    /**
     * @return null|string
     */
    public function getUrlAttribute(): ?string
    {
        return optional($this->instance())->url;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'anchor' => $this->anchor,
            'url'    => $this->url,
        ];
    }

    /**
     * @return Model|null
     */
    public function instance(): ?Model
    {
        $className = $this->getRelationClassName();

        if (!class_exists($className)) {
            return null;
        }

        return app($className)->find($this->meta->_menu_item_object_id);
    }

    /**
     * @return mixed
     */
    public function getRelationClassName()
    {
        $key = $this->meta->_menu_item_object;

        return config('wordpress.posts.map.'.$key) ?: $key;
    }
}