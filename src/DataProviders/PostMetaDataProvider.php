<?php

namespace LaPress\Models\DataProviders;

use LaPress\Models\AbstractPost;
use SEO;
/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostMetaDataProvider
{
    /**
     * @param AbstractPost $post
     */
    public static function provide(AbstractPost $post)
    {
        SEO::setTitle($post->post_title);
        SEO::setDescription($post->post_excerpt);

        if ($post->thumbnail) {
            SEO::opengraph()->addImage(url($post->thumbnail->size->cover));
        }
    }
}