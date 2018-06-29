<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Redis;

/**
 * Class RedisDomainDescriptionClient
 * @package App\Libraries
 */
class RedisDomainDescriptionClient extends RedisClient
{
    const PREFIX = 'Domains_Description_';

    /**
     * RedisDomainDescriptionClient constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct($this::PREFIX, $id);
    }
}
