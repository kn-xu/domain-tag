(function () {
    angular
        .module('domain.list', [
            'domain.factory',
            'domain.directives',
            'angular-table'
        ])
        .component('domainList', {
            controller: DomainListController,
            controllerAs: 'ctrl',
            templateUrl: '/views/angular/list.component.html'
        });

    /**
     *
     * @type {string[]}
     */
    DomainListController.$inject = ['HttpFactory', '$q', '$timeout', 'Notification', '$uibModal'];

    /**
     *
     * @param HttpFactory
     * @param $q
     * @param $timeout
     * @param Notification
     * @param $uibModal
     * @constructor
     */
    function DomainListController(HttpFactory, $q, $timeout, Notification, $uibModal) {
        var ctrl = this;
        ctrl.loading = true;
        ctrl.currentPage = 1;
        ctrl.offset = 0;
        ctrl.limit = 15;
        ctrl.sort = 'created_at';
        ctrl.sortType = 'desc';
        ctrl.domains = [];
        ctrl.tableOptions = {
            itemsPerPage: ctrl.limit,
            fillLastPage: 'yes'
        };

        ctrl.getDescription = getDescription;
        ctrl.setPage = setPage;

        initDependencies();

        /**
         * Init Dependencies on the page
         */
        function initDependencies() {
            var promises = [
                HttpFactory.get('/api/v1/domains', {
                    offset: ctrl.offset,
                    limit: ctrl.limit,
                    sort: ctrl.sort,
                    sortType: ctrl.sortType
                }),
                HttpFactory.get('/api/v1/domains/total')
            ];

            $q.all(promises)
                .then(function (response) {
                    ctrl.domains = response[0].data;
                    ctrl.totalDomains = response[1].data;
                })
                .catch(function (response) {

                })
                .finally(function (response) {
                    ctrl.loading = false;
                })
        }

        /**
         * Server side paging by using offset/limit/sorting
         */
        function setPage() {
            ctrl.loading = true;

            var offset = (ctrl.limit * ctrl.currentPage) - ctrl.limit;

            HttpFactory.get('/api/v1/domains', {
                offset: offset,
                limit: ctrl.limit,
                sort: ctrl.sort,
                sortType: ctrl.sortType
            }).then(function(response) {
                ctrl.domains = response.data;
                ctrl.throttled = true;

                $timeout(function() {
                    ctrl.throttled = false;
                }, 2000)

            }).catch(function (response) {

            }).finally(function (response) {
                ctrl.loading = false;
            })
        }

        /**
         * Async call to get Description of domain
         *
         * @param domainObject
         */
        function getDescription(domainObject) {
            $uibModal.open({
                size: 'lg',
                controllerAs: 'ctrl',
                controller: descriptionController,
                templateUrl: '/views/angular/domain.description.modal.html',
                resolve: {
                    domainObject: function() {
                        return domainObject;
                    }
                }
            });

            /**
             * Modal for domain description
             *
             * @param $uibModalInstance
             * @param $q
             * @param HttpFactory
             * @param domainObject
             */
            function descriptionController($uibModalInstance, $q, HttpFactory, domainObject) {
                var ctrl = this;

                ctrl.loading = true;
                ctrl.domain = domainObject;
                ctrl.closeModal = closeModal;

                initDependencies();

                function initDependencies() {
                    var promises = [
                        HttpFactory.get('/api/v1/domains/' + ctrl.domain.id +  '/description')
                    ];

                    $q.all(promises)
                        .then(function (response) {
                            ctrl.description = response[0].data;
                        })
                        .catch(function (response) {

                        })
                        .finally(function (response) {
                            ctrl.loading = false;
                        })
                }

                /**
                 * Closes the modal
                 */
                function closeModal() {
                    $uibModalInstance.close();
                }
            }
        }
    }
})();
