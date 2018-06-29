(function () {
    angular
        .module('domain.directives', [])
        .component('squareSpinner', {
            bindings: {
                show: "="
            },
            template: "" +
            "<div ng-if='$ctrl.show' class=\"sk-folding-cube\">" +
            "  <div class=\"sk-cube1 sk-cube\"></div>" +
            "  <div class=\"sk-cube2 sk-cube\"></div>" +
            "  <div class=\"sk-cube4 sk-cube\"></div>" +
            "  <div class=\"sk-cube3 sk-cube\"></div>" +
            "</div>"
        });
})();