(function () {

    angular
        .module('larakit')
        .component('adminlteGalleryStep1', {
            templateUrl: '/packages/larakit/lkng-gallery/components/adminlte-gallery-step1/component.html',
            bindings: {
                model: '=',
                load: '&',
                class: '=?'
            },
            controller: Controller
        });

    Controller.$inject = ['$uibModal'];

    function Controller($uibModal) {
        var $ctrl = this;
        $ctrl.gotoStep2 = function () {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                component: 'adminlteGalleryStep2',
                backdrop: 'static',
                size: 'full',
                keyboard: false,
                resolve: {
                    model: function () {
                        return $ctrl.model;
                    }
                }
            });
            modalInstance.result.then(function (o) {
                if ($ctrl.load) {
                    $ctrl.load()();
                }
            }, function () {
                if ($ctrl.load) {
                    $ctrl.load()();
                }
            });

        };
    }
})();