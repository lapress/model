<?php

namespace LaPress\Models;

use Laravel\Scout\Searchable;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Post extends AbstractPost
{
    use Searchable;

    /**
     * @var bool
     */
    public $asYouType = true;

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        if ($this->isDraft()) {
            return [];
        }

        return $this->getSearchableArray();
    }

    /**
     * @return array
     */
    public function getSearchableArray(): array
    {
        return [
            'ID'         => $this->ID,
            'post_title' => $this->post_title,
            'body'       => $this->post_content,
            'categories' => $this->categories->pluck('name')->implode(', '),
            'post_date'  => $this->date,
        ];
    }

    /**
     * @return string
     */
    public function searchableAs(): string
    {
        return str_plural(strtolower(class_basename(get_class())));
    }
}
