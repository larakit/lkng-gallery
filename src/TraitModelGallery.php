<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 14.06.17
 * Time: 14:10
 */

namespace Larakit;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Larakit\LkNg\LkNgGallery;

trait TraitModelGallery {
    
    //    function galleriesConfig() {
    //        return [
    //            'thumb' => CinemaHolidayGallery::class,
    //        ];
    //    }
    
    static function getGalleryKey() {
        $r = new \ReflectionClass(static::class);
        
        return Str::snake($r->getShortName(), '-');
    }
    
    protected $is_gallery_hashed = false;
    
    function galleryHashed() {
        $this->is_gallery_hashed = true;
    }
    
    public function galleries() {
        return $this->morphMany(ModelLkNgGallery::class, 'galleriable')
                    ->orderBy('priority', 'desc')
            ;
    }
    
    function getGalleryBlocksAttribute() {
        //        $ret = $this->galleriesConfig();
        $ret = LkNgGallery::modelConfig(self::class);
        foreach($ret as $block_name => $thumb_data) {
            $thumb                     = Arr::get($thumb_data, 'thumb');
            $ret[$block_name]['items'] = [];
            $ret[$block_name]['grid']  = LkNgGallery::modelGrid(self::class);;
            
            $ret[$block_name]['label']      = $thumb::getName();
            $ret[$block_name]['url_upload'] = route(
                'gallery-upload', [
                                    'model' => LkNgGallery::getKey(static::class),
                                    'id'    => $this->id,
                                    'block' => $block_name,
                                ]
            );
        }
        foreach($this->galleries as $el) {
            $ret[$el->block]['items'][] = $el->toArray();
        }
        return $ret;
    }
    
    function galleryClear($type) {
        $class = Arr::get(static::thumbsConfig(), $type);
        if(class_exists($class)) {
            $gallery = new $class($this->id);
            /** @var \Larakit\Thumb\Thumb $gallery */
            $gallery->delete();
            
            return true;
        }
        
        return false;
    }
    
    function galleryUpload($type) {
        $class = Arr::get(static::gallerysConfig(), $type);
        if(class_exists($class)) {
            $gallery = new $class($this->id);
            
            return $gallery->processing(\Request::file('file'));
        }
        
        return false;
        
    }
}