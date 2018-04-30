<?php

namespace LaPress\Models\UrlGenerators;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
interface UrlGenerator
{
    /**
     * @return string
     */
    public function get(): ?string;
}