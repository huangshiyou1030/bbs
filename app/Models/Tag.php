<?php

namespace App\Models;
use Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tag extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['name'];
    public $cache_key ='larabbs_tags';
    protected  $cache_expire_in_minutes = 1440;
    public function getAllCached(){
        //尝试从缓存中取出 cache_key 对应的数据 ，如果能取到，便直接返回数据。
        //否则 运行匿名函数中的代码来取出 links 表中所有的数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key,$this->cache_expire_in_minutes,function (){
            return $this->select('id', 'name')->orderBy('created_at')->get();
        });
    }
    /**
     * 关联文章表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function topic()
    {
        return $this->belongsToMany(Topic::class, 'topic_tags');
    }

}
