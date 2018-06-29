(function () {
    angular
        .module('domain.factory', [])
        .factory('HttpFactory', HttpFactory);

    /**
     * http request factory
     *
     * @param $http
     * @returns {{get: get}}
     */
    function HttpFactory($http) {
        return {
            get: get,
            post: post
        };

        /**
         * Get request with optional params
         *
         * @param url
         * @param params
         */
        function get(url, params) {
            if (params) {
                return $http.get(url, {params: params});
            } else {
                return $http.get(url);
            }
        }

        /**
         * Post request with optional params
         *
         * @param url
         * @param params
         */
        function post(url, params) {
            return $http.post(url, {params: params});
        }
    }
})();