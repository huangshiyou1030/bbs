<?php

namespace App\Models;

class Tag extends Model
{
    //
    protected $fillable = ['name'];
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
