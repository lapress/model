<?php

namespace LaPress\Models\DataProviders;

use LaPress\Models\AbstractPost;
use LaPress\Models\Page;
use SEO;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostListMetaData
{
    /**
     * @param string    $type
     * @param Page|null $page
     */
    public static function provide(string $type, $page = null)
    {
        if ($page instanceof AbstractPost) {
            return PostMetaData::provide($page);
        }

        SEO::setTitle(ucfirst($type).' list');
        SEO::setDescription('');
    }
}
