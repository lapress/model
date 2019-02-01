<?php

namespace LaPress\Models\UrlGenerators;

use App\Models\User;
/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostAuthorUrlGenerator implements UrlGenerator
{
    const PATTERN = '/author/%s';



    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function get(): ?string
    {
        return sprintf(static::PATTERN, $this->user->user_nicename);
    }
}
