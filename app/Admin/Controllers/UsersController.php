<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UsersController extends Controller
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
            ->header('用户列表')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
        // 创建一个列名为 ID 的列，内容是用户的 id 字段，并且可以在前端页面点击排序
        $grid->id('Id')->sortable();

        $grid->name('用户名');
        $grid->email('邮箱');
        $grid->email_verified('已验证邮箱')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->created_at('注册时间');

        // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户
        $grid->disableCreateButton();

        // $grid->actions(function ($actions) {
        //     // 不在每一行后面展示查看按钮
        //     $actions->disableView();

        //     // 不在每一行后面展示删除按钮
        //     $actions->disableDelete();

        //     // 不在每一行后面展示编辑按钮
        //     $actions->disableEdit();
        // });

        //不需要操作直接禁用
        $grid->disableActions();

        //关掉批量删除操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->email('Email');
        $show->password('Password');
        $show->remember_token('Remember token');
        $show->email_verified('Email verified');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }
}
