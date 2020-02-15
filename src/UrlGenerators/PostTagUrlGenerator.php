<?php

namespace LaPress\Models\UrlGenerators;

use LaPress\Models\Term;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostTagUrlGenerator implements UrlGenerator
{
    const PATTERN = '/tag/%s';

    /**
     * @var Term
     */
    private $term;

    /**
     * @param Term $term
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    /**
     * @return string
     */
    public function get(): ?string
    {
        return sprintf(static::PATTERN, $this->term->slug);
    }
}
