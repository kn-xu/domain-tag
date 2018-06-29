<?php

namespace App\Http\Controllers;

use App\Models\Domains;
use App\Libraries\RedisTotalDomainsClient;
use App\Libraries\RedisServerSidePagingDomainsClient;
use App\Libraries\RedisDomainDescriptionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use DB;

/**
 *
 * Class DomainsController
 * @package App\Http\Controllers
 */
class DomainsController extends Controller
{
    /**
     * Gets a list of domains with regards to certain parameters
     *
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        // Get all params first
        $params = $request->all();

        // Check if use redis parameter is set to use redis or only postgres
        if (config('app.use_redis')) {
            // Instantiate the client for this specific query based on server side paging
            $pagedDomainsRedisClient = new RedisServerSidePagingDomainsClient($params);

            // If no cache exist, get data from postgres and save it to redis
            if (is_null($pagedDomainsRedisClient->get())) {
                $domains = $this->getDomainsFromPostgresWithParams($params);
                $pagedDomainsRedisClient->set($domains);
            } else {
                // Else just get from redis
                // IMPORTANT: Decode the stored value in redis because it comes back as STRING
                $domains = $pagedDomainsRedisClient->get();
                $domains = json_decode($domains);
            }
        } else {
            // If config for redis is turned off, just use postgres
            $domains = $this->getDomainsFromPostgresWithParams($params);
        }

        return response()->json($domains);
    }

    /**
     * Returns total number of domains
     *
     * @param Request $request
     * @return mixed
     */
    public function total(Request $request)
    {
        // Check for use redis parameter
        if (config('app.use_redis')) {
            // Get client for total domains cache
            $totalDomainsRedisClient = new RedisTotalDomainsClient();

            // If cache doesn't exist, query from postgres and save into redis
            if (is_null($totalDomainsRedisClient->get())) {
                $totalDomains = Domains::count();
                $totalDomainsRedisClient->set((int) $totalDomains);
            } else {
                // Else just get from redis
                $totalDomains = $totalDomainsRedisClient->get();
            }
        } else {
            // If no parameter or set to false, get it from postgres
            $totalDomains = Domains::count();
        }

        return response()->json((int) $totalDomains);
    }

    /**
     * Get description field of domain
     *
     * @param $domainId
     * @param Request $request
     * @return mixed
     */
    public function description($domainId, Request $request)
    {
        // Check if config is turned on for redis
        if (config('app.use_redis')) {
            // Get domain description cache
            $domainDescriptionRedisClient = new RedisDomainDescriptionClient($domainId);

            // Check if this domain (by id) has a cache of the description
            if (is_null($domainDescriptionRedisClient->get())) {
                // If not, get data from postgres and save it to redis
                $descriptionObject = $this->getDomainDescriptionFromPostgresById((int) $domainId);

                if (is_null($descriptionObject)) {
                    return response('No Domain Found', 401);
                }

                $description = $descriptionObject->description;
                $domainDescriptionRedisClient->set($description);
            } else {
                // Else get it from postgres
                $description = $domainDescriptionRedisClient->get();
            }
        } else {
            // If config is turned off, just get data from postgres
            $descriptionObject = $this->getDomainDescriptionFromPostgresById((int) $domainId);

            // If that domain doesn't exist, return error
            if (is_null($descriptionObject)) {
                return response('No Domain Found', 401);
            }

            $description = $descriptionObject->description;
        }

        return response()->json($description);
    }

    /**
     * Post endpoint for creating a domain in the domains table
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        // Get request json
        $domainJson = $request->get('params');

        // Wrap create in a try catch block
        try {
            $newDomain = new Domains();
            $newDomain->domain = $this->getDomainHostFromDomain($domainJson['domain']);
            $newDomain->description = $domainJson['description'];
            $newDomain->flag_status = 0;
            $newDomain->save();

            // Delete all caches because all caches have inaccurate data, we can create one ones
            // When we query for them again
            Redis::flushAll();
        } catch (\Exception $e) {
            return response('Error Saving Domain', 500);
        }

        return response()->json('Success');
    }

    /**
     * Validation endpoint to check if Domain is unique
     *
     * @param Request $request
     * @return mixed
     */
    public function validateDomain(Request $request)
    {
        // Get request parameters
        $params = $request->get('params');
        $domainName = $params['domain'];

        // Parse the string domain into parts, looking for just the host
        $domainHost = $this->getDomainHostFromDomain($domainName);

        // In case domain string is not valid, return error with message
        if (!$domainHost) {
            return response('This is not a valid domain.', 401);
        }

        // Check DB if domain already exists
        $domains = Domains::where('domain', $domainHost)->get();

        // Instantiate variable describing if domain is good to create/unique
        $isValid = count($domains) < 1;

        // Return response
        return response()->json($isValid);
    }

    /**
     * @param $params
     * @return mixed
     */
    private function getDomainsFromPostgresWithParams($params)
    {
        return DB::table('domains')
            ->select('id', 'domain', 'flag_status', 'created_at', 'updated_at')
            ->orderBy((string) $params['sort'], (string) $params['sortType'])
            ->offset((int) $params['offset'])
            ->limit((int) $params['limit'])
            ->get();
    }

    /**
     * @param $domainId
     * @return mixed
     */
    private function getDomainDescriptionFromPostgresById($domainId)
    {
        return DB::table('domains')
            ->select('description')
            ->where('id', (int) $domainId)
            ->first();
    }

    /**
     * @param $domainString
     * @return mixed
     */
    private function getDomainHostFromDomain($domainString)
    {
        // Use PHP method parse_url to separate URL into pieces
        $parsedDomainString = parse_url($domainString);

        // If string of domain is invalid domain, return false;
        if (!array_key_exists('host', $parsedDomainString)) {
            return false;
        }

        // Return just the host because that's all we care about
        return $parsedDomainString['host'];
    }
}
