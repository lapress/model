<?php

namespace LaPress\Models\UrlGenerators;

use LaPress\Models\Term;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class CategoryUrlGenerator implements UrlGenerator
{
    const PATTERN = '/category/%s';
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