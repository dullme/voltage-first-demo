<?php

namespace App\Admin\Controllers;

use App\Client;
use App\Project;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProjectController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Project';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project());
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            if($actions->row->poClients()->count()){
                // 去掉删除
                $actions->disableDelete();
            } //存在 Factory 不允许删除
        });

        $grid->column('client.name', __('Clients'));
        $grid->column('name', __('Project name'))->display(function ($name){
            $url = url("/admin/projects/{$this->id}");

            return "<a href='{$url}'>{$name}</a>";
        });

        $grid->column('created_at', __('Created at'));

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
        Admin::script(<<<EOF
        const app = new Vue({
        el: '#app'
    });
EOF
        );

        $project = Project::with([ 'client', 'poClients' => function($query){
            $query->with(['poFactories' => function($query){
                $query->with(['factory', 'batches' => function($query){
                    $query->orderBy('sequence', 'ASC');
                }]);
            }]);
        }])->findOrFail($id);

        return view('admin.project.detail', compact('project'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Project());

        $clients = Client::pluck('name', 'id');
        $form->select('client_id', 'Clients')->options($clients)->required();
        $form->text('name', __('Name'))->required();

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }
}
