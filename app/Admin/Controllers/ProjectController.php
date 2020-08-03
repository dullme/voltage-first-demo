<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\BatchReplicate;
use App\AdminUser;
use App\Batch;
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
use Illuminate\Support\Facades\DB;

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
        $grid->model()->with('poClients.poFactories.batches')->orderByDesc('id');
        $grid->disableExport();
//        $grid->disableFilter();
//        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            if ($actions->row->poClients()->count()) {
                // 去掉删除
                $actions->disableDelete();
            } //存在 Factory 不允许删除
        });

        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new BatchReplicate());
        });

        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('name', 'Project name');
            $filter->like('no', 'Project No.');
            $filter->equal('client_id', 'Client')->select('/admin/client-list');
            $filter->equal('author_id', 'Author')->select('/admin/admin-list');
        });

        $grid->column('name', __('Project name'))->display(function ($name) {
            $url = url("/admin/projects/{$this->id}");

            return "<a href='{$url}'>{$name}</a>";
        });

        $grid->column('no', __('Project No.'));

        $grid->column('tip')->display(function () {
            $finished = true;
            $batches = Batch::where('project_id', $this->id)->get();

            foreach ($batches as $batch){
                if($batch->status != 2){
                    $finished = false;
                }

                $epc_color = getWarning($batch->estimated_production_completion, $batch->actual_production_completion);
                $etd_color = getWarning($batch->etd_port, $batch->atd_port);
                $eta_color = getWarning($batch->eta_port, $batch->ata_port);
                $eta_job_site_color = getWarning($batch->eta_job_site, $batch->ata_job_site);
                if($epc_color||$etd_color||$eta_color||$eta_job_site_color){
                    return '<i class="fa fa-exclamation-circle text-danger"></i>';
                }
            }

            if($batches->count() > 0 && $finished){
                return '<i class="fa fa-check-circle text-success"></i>';
            }

            return '';
        });

        $grid->column('client.name', __('Client'));

        $grid->column('address', __('Address'));
        $grid->author()->name('Author');

        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

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

        $project = Project::with(['author:id,name', 'client.contacts', 'poClients' => function ($query) {
            $query->with(['poFactories' => function ($query) {
                $query->with(['poFactoryFactories.factory', 'batches' => function ($query) {
                    $query->orderBy('sequence', 'ASC');
                }]);
            }]);
        }])->findOrFail($id);


        $poClients = $project->poClients->map(function ($item){
            $item->poFactories = $item->poFactories->map(function ($po){
                $po->batches = $po->batches->map(function ($batch){
                    $batch['epc_color'] = getWarning($batch->estimated_production_completion, $batch->actual_production_completion);
                    $batch['etd_color'] = getWarning($batch->etd_port, $batch->atd_port);
                    $batch['eta_color'] = getWarning($batch->eta_port, $batch->ata_port);
                    $batch['eta_job_site_color'] = getWarning($batch->eta_job_site, $batch->ata_job_site);
                    return $batch;
                });
                return $po;
            });
            return $item;
        });
        $project->setAttribute('po_clients', $poClients);

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
        $form->text('no', __('Project No.'));
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
            $form->hidden('author_id', __('Author'));
            $form->saving(function (Form $form) {
                $carbon = Carbon::now();
                $project = Project::orderBy('id', 'desc')->whereBetween('created_at', [$carbon->startOfYear()->toDateTimeString(), $carbon->endOfYear()->toDateTimeString()])->first();
                $number = $project ? intval($project->number) + 1 : substr($carbon->year, -2) . '001';
                $form->number = $number;
                $form->author_id = Auth('admin')->id();
            });
        }else{
            $form->select('author_id', __('Author'))->options(DB::table('admin_users')->pluck('name', 'id'));
            $form->saving(function (Form $form) {
                if ($form->model()->author_id != 0) {
                    $form->author_id = $form->model()->author_id;
                }
            });
        }

        return $form;
    }
}
