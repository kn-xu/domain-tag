<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Redis;

/**
 * Class RedisTotalDomainsClient
 * @package App\Libraries
 */
class RedisTotalDomainsClient extends RedisClient
{
    const PREFIX = 'Domains_Total_';

    /**
     * RedisTotalDomainsClient constructor.
     */
    public function __construct()
    {
        parent::__construct($this::PREFIX, 'All');
    }
}
