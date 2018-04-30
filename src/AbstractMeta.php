<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Model;
use LaPress\Models\Collections\ArrayCollection;
use LaPress\Models\Collections\MetaCollection;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class AbstractMeta extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'meta_id';

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->meta_key => $this->meta_value
        ];
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getMetaValueAttribute($value)
    {
        $value = @unserialize($value) ?: $value;

        return is_array($value) ? new ArrayCollection($value) : $value;
    }

    /**
     * @param array $models
     * @return MetaCollection
     */
    public function newCollection(array $models = []): MetaCollection
    {
        return new MetaCollection($models);
    }
}