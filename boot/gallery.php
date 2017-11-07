<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 19.06.17
 * Time: 11:24
 */

//################################################################################
//      загрузка вложения
//################################################################################
Route::post(
    '!/gallery/{model}/{id}/{block}/upload', function () {
    $model = \Larakit\LkNg\LkNgGallery::model();
    $block = Request::route('block');
    if($model) {
        $o      = \Larakit\ModelLkNgGallery::create(
            [
                'galleriable_id'   => $model->id,
                'galleriable_type' => $model->getMorphClass(),
                'block'            => $block,
                'priority'         => 0,
            ]
        );
        $config = \Larakit\LkNg\LkNgGallery::modelConfig($model->getMorphClass());
        
        $thumb_class = \Illuminate\Support\Arr::get($config, $block . '.thumb');
        if(class_exists($thumb_class)) {
            $thumb = new $thumb_class($o->id);
            if(Request::has('base64')){
                $source = \Request::input('base64');
            } else {
                $source = \Request::file('file');
            }
            if($thumb->processing($source)) {
                return [
                    'result'  => 'success',
                    'url'     => $thumb->getUrl('_'),
                    'message' => 'Иллюстрация галереи успешно загружено',
                    'type'    => $block,
                    'model'   => \Larakit\LkNg\LkNgGallery::model(),
                ];
            }
        }
    }
    return [
        'result'  => 'error',
        'message' => 'Иллюстрация галереи не загружено',
        'type'    => $block,
    ];
}
)
     ->name('gallery-upload')
;

//################################################################################
//      удаление файла
//################################################################################
Route::any(
    '!/gallery/{hash}/delete', function () {
    $id      = (int) \Illuminate\Support\Arr::get(hashids_decode(\Request::route('hash')), 0);
    $gallery = \Larakit\ModelLkNgGallery::find($id);
    
    if($gallery) {
        $model_name = $gallery->galleriable_type;
        $model_id   = $gallery->galleriable_id;
        if($gallery->delete()) {
            return [
                'result'  => 'success',
                'message' => 'Слайд удален',
                'model'   => $model_name::find($model_id)
                                        ->toArray(),
            ];
        }
    }
    
    return [
        'result'  => 'error',
        'message' => 'Слайд не найден',
    ];
}
)
     ->name('gallery-delete')
;

//################################################################################
//      редактирование файла
//################################################################################
Route::any(
    '!/gallery/{hash}/save', function () {
    $id     = (int) \Illuminate\Support\Arr::get(hashids_decode(\Request::route('hash')), 0);
    $data   = \Request::only(
        [
            'name',
            'desc',
            'priority',
        ]
    );
    $model = \Larakit\ModelLkNgGallery::find($id);
    $model->fill($data);
    $model->save();
    $model_name = $model->galleriable_type;
    $model_id   = $model->galleriable_id;
    $message    = 'Данные слайда успешно обновлены!';
    
    return [
        'result'  => 'success',
        'model'   => $model_name::find($model_id)
                                ->toArray(),
        'message' => $message,
    ];
}
)
     ->name('gallery-save')
;


