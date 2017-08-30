<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 22.05.17
 * Time: 13:27
 */

namespace Larakit\LkNg;

use Illuminate\Support\Arr;

class LkNgGallery {
    
    protected static $models = [];
    protected static $config = [];
    protected static $grids  = [];
    
    static function modelRegister($model_class, $config, $col_lg = 2) {
        self::$models[$model_class::getGalleryKey()] = $model_class;
        self::$config[$model_class]                  = $config;
        self::$grids[$model_class]                   = $col_lg;
    }
    
    static function models() {
        return self::$models;
    }
    
    static function modelClass() {
        $key = \Request::route('model');
        
        return Arr::get(self::$models, $key);
    }
    
    static function modelConfig($model_class) {
        $ret = [];
        foreach(Arr::get(self::$config, $model_class) as $k => $v) {
            $ret[$k]['thumb'] = $v;
        }
        return $ret;
    }
    
    static function modelGrid($model_class) {
        return Arr::get(self::$grids, $model_class);
    }
    
    static function getKey($model_class) {
        return array_search($model_class, self::$models);
    }
    
    static function model() {
        $model_class = self::modelClass();
        
        if(!class_exists($model_class)) {
            return null;
        }
        $id = (int) \Request::route('id');
        if(!is_callable($model_class . '::getGalleryKey')) {
            return null;
        }
        
        return $model_class::find($id);
    }
}