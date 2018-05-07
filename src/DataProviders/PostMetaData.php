<?php

namespace LaPress\Models\DataProviders;

use LaPress\Models\AbstractPost;
use SEO;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostMetaData
{
    /**
     * @param AbstractPost $post
     */
    public static function provide(AbstractPost $post)
    {
        SEO::setTitle($post->post_title);
        SEO::setDescription($post->post_excerpt);
        SEO::opengraph()
           ->setType('article')
           ->setArticle([
               'published_time' => $post->post_date,
               'modified_time' => $post->post_modified,
               'tag' => $post->categories->pluck('name')
           ]);

        if ($post->thumbnail) {
            $size = $post->thumbnail->size->collect('cover');
            SEO::opengraph()
               ->addImage(url($post->thumbnail->size->cover), [
                   'width'  => $size->get('width'),
                   'height' => $size->get('height'),
               ]);
        }
    }
}