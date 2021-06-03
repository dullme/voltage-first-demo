<?php

namespace App\Admin\Controllers;

use App\Forwarder;
use App\ForwarderContact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ForwarderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Forwarder';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Forwarder());
        $grid->model()->orderByDesc('id');
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('cn_name', __('CN Name'));
        $grid->column('agent_name', __('Agent name'));
        $grid->column('cn_agent_name', __('CN Agent name'));
        $grid->column('cn_address', __('CN Address'));
//        $grid->column('created_at', __('Created at'));

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
        $show = new Show(Forwarder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('cn_name', __('CN Name'));
        $show->field('cn_agent_name', __('CN Agent name'));
        $show->field('cn_address', __('CN Address'));
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
        $form = new Form(new Forwarder());

        $form->text('name', __('Name'))->creationRules(['required', "unique:forwarders"])
            ->updateRules(['required', "unique:forwarders,name,{{id}}"]);
        $form->text('cn_name', __('CN Name'))->creationRules(['required', "unique:forwarders"])
            ->updateRules(['required', "unique:forwarders,cn_name,{{id}}"]);
        $form->text('agent_name', __('Agent name'));
        $form->text('cn_agent_name', __('CN Agent name'));
        $form->text('address', __('Address'));
        $form->text('cn_address', __('CN Address'));

        return $form;
    }

    public function getForwarders()
    {
        if(Admin::user()->language == 'cn'){
            return Forwarder::get(['id', DB::raw('cn_name as text')]);
        }else{
            return Forwarder::get(['id', DB::raw('name as text')]);
        }
    }
}
