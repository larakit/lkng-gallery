<?php
/**
 * Created by PhpStorm.
 * User: aberdnikov
 * Date: 29.08.2017
 * Time: 16:42
 */

namespace Larakit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Larakit\LkNg\LkNgGallery;
use Larakit\Thumb\TraitModelThumb;

class ModelLkNgGallery extends Model {
    
    use TraitModelThumb;
    protected $connection = 'mysql';
    
    protected $table = 'galleries';
    
    protected $fillable = [
        'galleriable_id',
        'galleriable_type',
        'block',
        'name',
        'priority',
        'desc',
    ];
    
    protected $appends = [
        'thumbs',
        'hash',
    ];
    
    function getHashAttribute() {
        return hashids_encode($this->id);
    }
    
    function thumbsConfig() {
        $ret = LkNgGallery::modelConfig($this->galleriable_type);
        $ret = Arr::only($ret, $this->block);
        return $ret;
    }
    
    public function galleriable() {
        return $this->morphTo();
    }
    
}

ModelLkNgGallery::saving(
    function ($model) {
        $model->priority = (int) $model->priority;
    }
);