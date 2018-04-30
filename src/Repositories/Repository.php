<?php

namespace LaPress\Models\Repositories;

use Illuminate\Database\Eloquent\Model;
use LaPress\Support\Cache\RepositoryCacheNameGenerator;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Repository
{
    const CACHE_TTL = '86400';
    /**
     * @var Model
     */
    private $model;

    private $criteria = [];

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function latest()
    {
        $this->criteria['latest'] = ['post_date'];
        $this->criteria['published'] = [];

        return $this;
    }

    public function find($id)
    {
        return app('cache')->remember($this->getCacheKey([$id]), static::CACHE_TTL, function () use ($id) {
            return $this->model->find($id);
        });
    }

    public function paginate($take = 10)
    {
        $options = [
            'page' => [app('request')->get('page') ?: 1],
        ];

        return app('cache')->remember($this->getCacheKey($options), static::CACHE_TTL, function () use ($take) {
            foreach ($this->criteria as $name => $args) {
                $this->model = call_user_func_array([$this->model, $name], $args);
            }

            return $this->model->paginate($take);
        });

    }

    public function first()
    {
        return app('cache')->remember($this->getCacheKey(), static::CACHE_TTL, function () {
            foreach ($this->criteria as $name => $args) {
                $this->model = call_user_func_array([$this->model, $name], $args);
            }

            return $this->model->first();
        });
    }

    public function get()
    {
        return app('cache')->remember($this->getCacheKey(), static::CACHE_TTL, function () {
            foreach ($this->criteria as $name => $args) {
                $this->model = call_user_func_array([$this->model, $name], $args);
            }

            return $this->model->get();
        });
    }

    public function __call($name, $arguments)
    {
        $this->criteria[$name] = $arguments;

        return $this;
    }

    public function getCacheKey(array $options = [])
    {
        return (new RepositoryCacheNameGenerator($this->getBaseCacheKey(), array_merge($this->criteria, $options)))->get();
    }

    private function getBaseCacheKey()
    {
        return class_basename($this->model);
    }
}