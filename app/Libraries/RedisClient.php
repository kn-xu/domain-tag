<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Redis;

/**
 * Class RedisClient
 * @package App\Libraries
 */
class RedisClient
{
    /**
     * @var string
     */
    private $key;

    /**
     * RedisClient constructor.
     * @param $prefix
     * @param $id
     */
    public function __construct($prefix, $id)
    {
        $this->key = $this->hashKey($prefix, $id);
    }

    /**
     * @param $prefix
     * @param $id
     * @return string
     */
    private function hashKey($prefix, $id)
    {
        return $prefix . $id;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return Redis::get($this->key);
    }

    /**
     * @param $value
     */
    public function set($value)
    {
        Redis::set($this->key, $value);
        return;
    }

    public function delete()
    {

    }

    public function update($value)
    {

    }
}
