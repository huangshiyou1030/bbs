<?php

namespace App\Observers;
use App\Notifications\TopicReplied;
use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $topic->increment('reply_count', 1);

        // 通知作者话题被回复了
        $topic->user->notify(new TopicReplied($reply));

    }
    public function creating(Reply $reply)
    {
        //

    }

    public function updating(Reply $reply)
    {
        //
    }
}