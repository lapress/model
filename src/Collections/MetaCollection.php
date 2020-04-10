<?php

namespace LaPress\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class MetaCollection extends Collection
{
    /**
     * @param string $key
     * @return mixed
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
     * @return array
     */
    public function toArray()
    {
        return $this->getFlattened()->items;
    }

    /**
     * @return static
     */
    public function getFlattened()
    {
        return $this->mapWithKeys(function ($item) {
            return $item->toArray();
        });
    }
}
