(function () {
    angular
        .module('domain.new', [
            'domain.factory',
            'ui-notification'
        ])
        .component('newDomain', {
            controller: NewDomainController,
            controllerAs: 'ctrl',
            templateUrl: '/views/angular/new.component.html'
        });

    /**
     * Dependency Injection
     *
     * @type {string[]}
     */
    NewDomainController.$inject = ['HttpFactory', 'Notification', '$state'];

    /**
     * New Domain Controller
     *
     * @param HttpFactory
     * @param Notification
     * @param $state
     * @constructor
     */
    function NewDomainController(HttpFactory, Notification, $state) {
        var ctrl = this;

        ctrl.submit = submit;
        ctrl.validateDomain = validateDomain;

        /**
         * API endpoint to test if domain already exists
         */
        function validateDomain() {
            HttpFactory.post('/api/v1/validate', {domain: ctrl.domain.domain})
                .then(function(response) {
                    ctrl.isValidDomain = response.data;

                    if (ctrl.isValidDomain) {
                        Notification.success({
                            message:'This domain is valid!',
                            title: 'Success'
                        });
                    } else {
                        Notification.error({
                            message: 'This domain already exists in our database.',
                            title: 'Uh Oh'
                        });
                    }
                })
                .catch(function(response) {
                    Notification.error({
                        message: response.data,
                        title: 'Uh Oh'
                    });

                    ctrl.isValidDomain = false;
                })
        }

        /**
         * Creates a new domain with description in the DB
         */
        function submit() {
            if (ctrl.isValidDomain === undefined) {
                Notification.warning({
                    message: 'Please wait while we verify that the domain is valid for you to save.',
                    title: 'Hold On'
                });
                return;
            }

            if (ctrl.isValidDomain !== true) {
                Notification.error({
                    message: 'We can not save this domain. Please verify it was a valid domain and that it doesn\'t already exist in our database.',
                    title: 'Sorry'
                });
                return;
            }

            if (!ctrl.domain.description) {
                Notification.error({
                    message: 'Please enter a description for this domain.',
                    title: 'Sorry'
                });
                return;
            }

            HttpFactory.post('/api/v1/domains', {
                domain: filterXSS(ctrl.domain.domain),
                description: filterXSS(ctrl.domain.description)
            }).then(function(response) {
                Notification.success({
                    message: 'Your domain has been added to our list!',
                    title: 'Congrats!'
                });
            }).catch(function(response) {
                Notification.error({
                    message: 'There is an issue on our end and we are trying to resolve it. Please try again later.',
                    title: 'Sorry'
                });
            }).finally(function(response) {
                $state.go('list');
            })

        }

    }
})();
