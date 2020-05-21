<?php

namespace App\Admin\Controllers;

use App\PoClient;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoClientController extends ResponseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\PoClient';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PoClient());

        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreateButton();

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('no', 'PO# Client');
            $filter->like('voltage_no', 'PO# Voltage');
        });

//        $grid->column('id', __('Id'));
        $grid->project()->name(__('Project name'));
        $grid->column('no', __('PO# Client'))->display(function ($no){
            $url = url('/admin/projects/'.$this->project->id.'/?po-client='.$this->id);
            return "<a href='{$url}'>$no</a>";
        });

//        $grid->column('no', __('No'));
        $grid->column('voltage_no', __('PO# Voltage'));
        $grid->column('client_delivery_time', __('Client delivery time'));
        $grid->column('po_date', __('Po date'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(PoClient::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('project_id', __('Project id'));
        $show->field('no', __('No'));
        $show->field('voltage_no', __('Voltage no'));
        $show->field('client_delivery_time', __('Client delivery time'));
        $show->field('po_date', __('Po date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PoClient());

        $form->number('project_id', __('Project id'));
        $form->text('no', __('No'));
        $form->text('voltage_no', __('Voltage no'));
        $form->datetime('client_delivery_time', __('Client delivery time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('po_date', __('Po date'))->default(date('Y-m-d H:i:s'));

        return $form;
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id'           => 'required',
            'no'                   => 'required',
            'voltage_no'           => 'required',
            'client_delivery_time' => 'nullable|date',
            'po_date'              => 'nullable|date',
        ], [
            'no.required' => 'The PO# Client field is required.'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $po_client = PoClient::create($validator->validated());

        $data = PoClient::with(['poFactories' => function ($query) {
            $query->with(['batches' => function ($query) {
                $query->orderBy('sequence', 'ASC');
            }]);
        }])->find($po_client->id);

        return $this->responseSuccess($data, 'Created');
    }

    public function getPoClient($id)
    {
        $po_client = PoClient::findOrFail($id);

        return $this->responseSuccess($po_client);
    }

    public function save($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no'                   => 'required',
            'voltage_no'           => 'required',
            'client_delivery_time' => 'nullable|date',
            'po_date'              => 'nullable|date',
        ], [
            'no.required' => 'The PO# Client field is required.'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        PoClient::where('id', $id)->update($validator->validated());

        return $this->responseSuccess(true, 'Updated');
    }

    public function delete($id)
    {
        $po_client = PoClient::with('poFactories')->findOrFail($id);

        if (count($po_client->poFactories)) {
            return $this->setStatusCode(422)->responseError('Failed to delete');
        }

        $po_client->delete();

        return $this->responseSuccess(true, 'Deleted');
    }
}
