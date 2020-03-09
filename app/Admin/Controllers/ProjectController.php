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
            if($actions->row->poFactory()->count()){
                // 去掉删除
                $actions->disableDelete();
            } //存在 Factory 不允许删除
        });

        $grid->column('client.name', __('Clients'));
        $grid->column('name', __('Project name'))->display(function ($name){
            $url = url("/admin/projects/{$this->id}");

            return "<a href='{$url}'>{$name}</a>";
        });
        $grid->column('no', __('No'));
        $grid->column('client_delivery_time', __('Client delivery time'))->display(function (){
            return $this->client_delivery_time ? $this->client_delivery_time->toFormattedDateString() : '-';
        });
        $grid->column('po_date', __('Po date'))->display(function (){
            return $this->po_date ? $this->po_date->toFormattedDateString() : '-';
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
//        Admin::script(<<<EOF
//        const app = new Vue({
//        el: '#app'
//    });
//EOF
//        );

        $project = Project::with(['poFactory' => function($query){
            $query->with(['batches' => function($query){
                $query->withTrashed();
            }]);
        }])->findOrFail($id);

        return view('admin.project.show', compact('project'));
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
        $form->text('no', __('No'));
        $form->date('client_delivery_time', __('Client delivery time'));
        $form->date('po_date', __('Po date'));

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }
}
