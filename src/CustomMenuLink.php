<?php

namespace App\Models;


use App\Support\WordPress\PostModel;

class CustomMenuLink extends PostModel
{
    protected $postType = 'nav_menu_item';
    protected $with = ['meta'];


    public function getUrlAttribute(): ?string
    {
        return $this->meta->_menu_item_url;
    }
}