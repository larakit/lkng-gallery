(function () {

    angular
        .module('larakit')
        .component('adminlteGalleryStep2', {
            templateUrl: '/packages/larakit/lkng-gallery/components/adminlte-gallery-step2/component.html',
            bindings: {
                resolve: '<',
                close: '&',
                dismiss: '&'
            },
            controller: Controller
        });

    Controller.$inject = ['$http', '$uibModal'];

    function Controller($http, $uibModal) {
        var $ctrl = this;
        //регистрируем действие ПОСЛЕ успешной загрузки
        $ctrl.onCompleteItem = function (fileItem, response) {
            $ctrl.model = response.model;
        };
        $ctrl.actionRemove = function (file) {
            if(confirm('Вы действительно хотите удалить вложение?')) {
                $http
                    .post('/!/gallery/' + file.hash + '/delete')
                    .then(function (response) {
                        if ('success' == response.data.result) {
                            $ctrl.model = response.data.model;
                        }
                        larakit_toastr(response.data);
                    });
            }
        };
        $ctrl.$onInit = function () {
            $ctrl.model = $ctrl.resolve.model;
        };
        $ctrl.actionEdit = function (file) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                component: 'adminlteGalleryStep3',
                resolve: {
                    model: function () {
                        return file;
                    }
                }
            });
            modalInstance.result.then(function (model) {
                console.log(arguments);
                $ctrl.model = model;
            }, function () {
            });

        };
        $ctrl.cancel = function () {
            $ctrl.dismiss({$value: 'cancel'});
        };

    }
})();