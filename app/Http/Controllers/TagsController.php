<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Tag;
use App\Models\User;
use App\Models\Link;
use App\Models\TopicTag;
use Illuminate\Http\Request;


class TagsController extends Controller
{
    //
    public function show(Tag $tag, Request $request, Topic $topic,User $user,Link $link){
        //读取分类ID 关联的话题，并按每20条分页
        $topic_ids = TopicTag::where('tag_id', $tag->id)->pluck('topic_id')->toArray();
        $topics = $topic->withOrder($request->order)
            ->whereIn('id', $topic_ids)
            ->paginate(20);
        // 活跃用户列表
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();
        //传参到模板中
        return view('topics.index',compact('topics','category','active_users','links'));
    }
}
