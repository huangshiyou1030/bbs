<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Link;
use App\Models\User;
class SearchController extends Controller
{
    //
    public  function search(Request $request, Topic $topic, Link $link,User $user){
        $links = $link->getAllCached();
        $topics = $topic->search($request->words)->paginate(20);
        // 活跃用户列表
        $active_users = $user->getActiveUsers();
        return view('topics.index', compact('topics',  'links','active_users'));
    }

}
