<?php

namespace LaPress\Models;

use Illuminate\Support\Collection;
/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ImageSize
{
    const PATH = '/wp-content/uploads/%s/';
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var Collection
     */
    protected $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = collect($data);
        $this->extractBasePath();
    }

    public function extractBasePath()
    {
        $this->basePath = sprintf(static::PATH, dirname($this->data->get('file')));
    }

    /**
     * @param $size
     * @return string
     */
    public function get($size)
    {
        if ($size == 'full') {
            return '/wp-content/uploads/'.$this->data->get('file');
        }
        $sizeArray = $this->data->get('sizes')[$size];

        return $this->basePath.$sizeArray['file'];
    }

    /**
     * @param string $name
     * @return null|string
     */
    function __get($name)
    {
        if ($this->hasSize($name) || $name == 'full') {
            return $this->get($name);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    private function hasSize($name): bool
    {
        return !empty($this->data->get('sizes')[$name]);
    }
}