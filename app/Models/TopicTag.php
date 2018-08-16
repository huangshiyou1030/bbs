<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicTag extends Model
{
    protected $fillable = [
        'topic_id', 'tag_id',
    ];
    /**
     * 为文章批量插入标签
     *
     * @param $article_id
     * @param $tag_ids
     */
    public function addTagIds($topic_id, $tag_ids)
    {
        // 组合批量插入的数据
        $data = [];
        foreach ($tag_ids as $k => $v) {
            $data[] = [
                'topic_id' => $topic_id,
                'tag_id' => $v
            ];
        }
        $this->insert($data);
    }
}
