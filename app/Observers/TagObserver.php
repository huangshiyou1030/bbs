<?php
namespace App\Observers;

use App\Models\Tag;
use Cache;
class TagObserver{
    //在保存时清空 cache_key 对应的缓存
    public function saved(Tag $tag){
        Cache::forget($tag->cache_key);
    }
    public function deleted(Tag $tag)
    {
        Cache::forget($tag->cache_key);
    }
}