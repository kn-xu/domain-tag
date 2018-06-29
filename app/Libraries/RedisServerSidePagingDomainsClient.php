<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Redis;


class RedisServerSidePagingDomainsClient extends RedisClient
{
    const PREFIX = 'Domains_';

    /**
     * RedisServerSidePagingDomainsClient constructor.
     * @param $params
     */
    public function __construct($params)
    {
        parent::__construct($this->hashKey($params), '');
    }

    /**
     * @param $params
     * @return string
     */
    private function hashKey($params)
    {
        return $this::PREFIX . 'Offset_' .
            $params['offset'] . '_Limit_' .
            $params['limit'] . '_Sort_' .
            $params['sort'] . '_Sorttype_' .
            $params['sortType'];
    }
}
