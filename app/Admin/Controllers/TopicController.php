<?php

namespace App\Admin\Controllers;

use App\Models\Topic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TopicController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('帖子列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show(Topic $topics, Content $content)
    {
        return $content
            ->header('帖子详情')
            ->body(view('admin.topics.show', ['topics' => $topics]));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Topic);
        $grid->model()->with(['category','user']);
        $grid->id('Id');
        $grid->title('标题');
        $grid->column('category.name', '类目');
        $grid->column('user.name', '作者');
        $grid->reply_count('回复量');
        $grid->view_count('查看量');
        $grid->created_at('创建时间');
        // 禁用创建按钮，后台不需要创建
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 禁用删除和编辑按钮
            $actions->disableDelete();
            $actions->disableEdit();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Topic::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->body('Body');
        $show->user_id('User id');
        $show->category_id('Category id');
        $show->reply_count('Reply count');
        $show->view_count('View count');
        $show->last_reply_user_id('Last reply user id');
        $show->order('Order');
        $show->excerpt('Excerpt');
        $show->slug('Slug');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->deleted_at('Deleted at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Topic);

        $form->text('title', 'Title');
        $form->textarea('body', 'Body');
        $form->number('user_id', 'User id');
        $form->number('category_id', 'Category id');
        $form->number('reply_count', 'Reply count');
        $form->number('view_count', 'View count');
        $form->number('last_reply_user_id', 'Last reply user id');
        $form->number('order', 'Order');
        $form->textarea('excerpt', 'Excerpt');
        $form->text('slug', 'Slug');

        return $form;
    }
}
