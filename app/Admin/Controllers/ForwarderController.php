<?php

namespace App\Admin\Controllers;

use App\Forwarder;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        $grid->column('name', __('Name'));
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
        $show = new Show(Forwarder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('address', __('Address'));
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
        $form->text('address', __('Address'));

        return $form;
    }
}
