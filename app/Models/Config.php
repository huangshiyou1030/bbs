<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    //
    //
    protected $fillable = ['name','value'];

    /**
     * 存储
     *
     * @param $data
     * @param $section
     * @param string $module
     * @param string $owner
     * @return bool
     */
    public function store($data){
        foreach($data as $key => $value){
            empty($value) && $value = '';
            static::updateOrCreate(['name'=>$key], ['value'=> is_string($value) ? $value : json_encode($value)]);
        }
    }
    public static function loadConfig()
    {
        foreach (Config::all(['name', 'value']) as $config) {
            config([$config['name'] => $config['value']]);
        }
    }
}
