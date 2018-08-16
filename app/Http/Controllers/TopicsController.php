<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;
use App\Models\TopicTag;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic, User $user, Link $link)
    {
        $links = $link->getAllCached();
        $topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        return view('topics.index', compact('topics', 'active_users', 'links'));
    }

    public function show(Topic $topic, Request $request)
    {
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    public function create(Topic $topic)
    {
        $topic->tag_ids = [];
        return view('topics.create_and_edit', compact('topic'));
    }

    public function store(TopicRequest $request, Topic $topic, TopicTag $topicTag)
    {
        $topic->fill($request->all());
        $tags = Tag::whereIn('id', $request->tag_ids)->get()->toArray();
        $topicTag->addTagIds($topic->id,$request->tag_ids);
        $topic->user_id = Auth::id();
        $topic->save();
        return redirect()->to($topic->link())->with('success', '成功创建主题！');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->tag_ids = TopicTag::where('topic_id', $topic->id)->pluck('tag_id')->toArray();
        return view('topics.create_and_edit', compact('topic'));
    }

    public function update(TopicRequest $request, Topic $topic, TopicTag $topicTag)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        // 先彻底删除此文章下的所有标签
        $articleTagMap = [
            'topic_id' => $topic->id
        ];
        //先删除该文章下的所有标签
        \DB::table('topic_tags')->where('topic_id', $topic->id)->delete();
        $topicTag->addTagIds($topic->id,$request->tag_ids);
        return redirect()->to($topic->link())->with('message', '更新成功');
    }

    public function destroy(Topic $topic)
    {

        $this->authorize('destroy', $topic);
        $topic->delete();
        return redirect()->route('topics.index')->with('message', '删除成功.');
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        //初始化返回数据，默认是失败的
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];
        //判断是否有上传文件，并赋值级$file
        if ($file = $request->upload_file) {
            //保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            //图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功';
                $data['success'] = true;
            }
        }
        return $data;
    }

}