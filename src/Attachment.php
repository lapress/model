<?php

namespace LaPress\Models;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Attachment extends AbstractPost
{
    /**
     * @var string
     */
    protected $postType = 'attachment';

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->meta()->whereMetaKey('_wp_attachment_metadata')->first()->meta_value;
    }

    /**
     * @return ImageSize
     */
    public function getSizeAttribute()
    {
        return new ImageSize($this->image());
    }

    /**
     * @return null|string
     */
    public function getUrlAttribute(): ?string
    {
        return $this->guid;
    }
}