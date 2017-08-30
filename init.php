<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 26.06.17
 * Time: 14:29
 */
\Larakit\Boot::register_boot(__DIR__ . '/boot');
\Larakit\NgAdminlte\LkNgThumb::modelRegister(\Larakit\ModelLkNgGallery::class);

//##################################################
//      Регистрация компонента страницы
//##################################################
$components_directory = '/packages/larakit/lkng-gallery/components/';
\Larakit\NgAdminlte\LkNgComponent::register('adminlte-gallery-step1', $components_directory);
\Larakit\NgAdminlte\LkNgComponent::register('adminlte-gallery-step2', $components_directory);
\Larakit\NgAdminlte\LkNgComponent::register('adminlte-gallery-step3', $components_directory);


\Larakit\StaticFiles\Manager::package('larakit/lkng-gallery')
                            ->usePackage('larakit/ng-adminlte')
                            ->setSourceDir('public');