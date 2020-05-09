<?php

namespace App\Admin\Controllers;

use App\Client;
use App\Contact;
use App\Project;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
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
        $grid->model()->orderByDesc('id');
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            if ($actions->row->poClients()->count()) {
                // 去掉删除
                $actions->disableDelete();
            } //存在 Factory 不允许删除
        });

        $grid->column('name', __('Project name'))->display(function ($name) {
            $url = url("/admin/projects/{$this->id}");

            return "<a href='{$url}'>{$name}</a>";
        });
        $grid->column('client.name', __('Client'));

        $grid->column('address', __('Address'));

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

        $project = Project::with(['client', 'poClients' => function ($query) {
            $query->with(['poFactories' => function ($query) {
                $query->with(['factory', 'batches' => function ($query) {
                    $query->orderBy('sequence', 'ASC');
                }]);
            }]);
        }])->findOrFail($id);

        return view('admin.project.detail', compact('project'));
    }

    /**
     * Make a form builder.
     * @param null $id
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Project());

        $clients = Client::all();
        $clients = $clients->map(function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->number . ' : ' . $item->name,
            ];
        });

        $clients = $clients->pluck('name', 'id');

        $form->select('client_id', 'Clients')->options($clients)->load('contacts', '/admin/api/contact')->required();

        $form->text('name', __('Project Name'))->required();
        $form->text('address', __('Address'));

        $multipleSelect = $form->multipleSelect('contacts');

        $form->isEditing() ? $multipleSelect->options(function () {
            return Contact::select('id', 'name')->where('client_id', $this->client_id)->get()->pluck('name', 'id');
        }) : '';

        $form->hidden('number', __('Number'));


        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        if ($form->isCreating()) {
            $form->saving(function (Form $form) {
                $carbon = Carbon::now();
                $project = Project::orderBy('id', 'desc')->whereBetween('created_at', [$carbon->startOfYear()->toDateTimeString(), $carbon->endOfYear()->toDateTimeString()])->first();
                $number = $project ? intval($project->number) + 1 : substr($carbon->year, -2) . '001';
                $form->number = $number;
            });
        }

        return $form;
    }
}
