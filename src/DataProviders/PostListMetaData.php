<?php

namespace LaPress\Models\DataProviders;

use Illuminate\Support\Facades\Lang;
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

        $locale = \App::getLocale();
        $title = ucfirst($type).' list';

        if (Lang::has("pages.{$type}.title", $locale)) {
            $title = trans("pages.{$type}.title");
        }

        SEO::setTitle($title);
        SEO::setDescription('');
    }
}
