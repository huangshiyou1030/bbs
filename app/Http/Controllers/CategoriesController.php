<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;
use App\Models\Link;
class CategoriesController extends Controller
{
    //
    public function show(Category $category, Request $request, Topic $topic,User $user,Link $link){
        //读取分类ID 关联的话题，并按每20条分页
        $topics = $topic->withOrder($request->order)
            ->where('category_id',$category->id)
            ->paginate(20);
        // 活跃用户列表
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();
        $categories = $category->all();
        //传参到模板中
        return view('topics.index',compact('topics','category','active_users','links','categories'));
    }
}
