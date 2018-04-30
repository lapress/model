<?php

namespace LaPress\Models\UrlGenerators;

use LaPress\Models\AbstractPost;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
abstract class AbstractPostUrlGenerator implements UrlGenerator
{
    const PATTERN = '/{slug}';
    /**
     * @var Post
     */
    protected $post;

    /**
     * @param AbstractPost $post
     */
    public function __construct(AbstractPost $post)
    {
        $this->post = $post;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return str_replace(
            ['{id}', '{slug}'],
            [$this->post->ID, $this->post->post_name],
            $this->getPattern()
        );
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        $array = config('wordpress.posts.routes.'.$this->post->post_type);
        if (!$array || !array_key_exists('route', $array)) {
            return static::PATTERN;
        }

        return $array['route'];
    }
}