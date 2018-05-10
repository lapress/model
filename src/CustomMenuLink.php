<?php

namespace App\Models;

use LaPress\Models\AbstractPost;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class CustomMenuLink extends AbstractPost
{
    protected $postType = 'nav_menu_item';
    protected $with = ['meta'];


    public function getUrlAttribute(): ?string
    {
        return $this->meta->_menu_item_url;
    }
}