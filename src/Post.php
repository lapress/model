<?php

namespace LaPress\Models;

use Ably\Sitemap\HasSitemap;
use Laravel\Scout\Searchable;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Post extends AbstractPost
{
    use HasSitemap;
    
    /**
     * @var bool
     */
    public $asYouType = true;

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        if (!$this->isPublished()) {
            return [];
        }

        return $this->getSearchableArray();
    }

    /**
     * @return string
     */
    public function searchableAs()
    {
        return str_plural(strtolower(class_basename(get_class())));
    }

    /**
     * @return Category|null
     */
    public function getCategoryAttribute()
    {
        return $this->categories()->first();
    }

    /**
     * @return $this
     */
    public function categories()
    {
        return $this->getTaxonomyRelationship(
            $this->getLocalizedModel('Category')
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->getTaxonomyRelationship(
            $this->getLocalizedModel('PostTag')
        );
    }

    /**
     * @return array
     */
    public function getSearchableArray(): array
    {
        return [
            'ID'           => $this->ID,
            'post_title'   => $this->post_title,
            'post_excerpt' => $this->post_excerpt,
            'body'         => $this->post_content,
            'categories'   => $this->categories->pluck('name')->implode(', '),
            'tags'         => $this->tags->pluck('name')->implode(', '),
            'post_date'    => $this->date,
        ];
    }
}
