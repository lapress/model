<?php

namespace LaPress\Models\Collections;

use Illuminate\Support\Collection;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ArrayCollection extends Collection
{
    /**
     * @param string $key
     * @return ArrayCollection|mixed
     */
    public function __get($key)
    {
        $result = json_decode($this->getFlattened()->get($key), true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($result)) {
            return collect($result);
        }
        

        return $this->getFlattened()->get($key);
    }

    /**
     * @param $values
     * @return static
     */
    public function getCollected($values)
    {
        return is_array($values) ? new static($values) : $values;
    }
}
