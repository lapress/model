<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Option extends Model
{
    /**
     * @var string
     */
    protected $table = 'options';

    /**
     * @var string
     */
    protected $primaryKey = 'option_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'option_name',
        'option_value',
    ];

    /**
     * @param $value
     */
    public function setOptionValueAttribute($value)
    {
        $this->attributes['option_value'] = is_array($value) ? serialize($value) : $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getOptionValueAttribute($value)
    {
        return @unserialize($value) ?: $value;
    }

    /**
     * @return mixed
     */
    public function getValueAttribute()
    {
        return $this->option_value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        return optional(static::where('option_name', $key)->first())
            ->value;
    }

    /**
     * @param string $key
     * @return \Illuminate\Support\Collection
     */
    public static function collect($key)
    {
        $collection = collect(static::get($key));

        return $collection->map(function ($item) {
            if (is_array($item)) {
                return collect($item);
            }

            return $item;
        });
    }


}