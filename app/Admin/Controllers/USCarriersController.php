<?php

namespace App\Admin\Controllers;

use App\USCarriers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class USCarriersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\USCarriers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new USCarriers());

        $grid->column('name', __('Name'));
        $grid->column('address', __('Address'));
        $grid->column('agent_name', __('Agent name'));
        $grid->column('position', __('Position'));
        $grid->column('tel', __('Tel'));
        $grid->column('email', __('Email'));

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
        $show = new Show(USCarriers::findOrFail($id));

        $show->field('name', __('Name'));
        $show->field('address', __('Address'));
        $show->field('agent_name', __('Agent name'));
        $show->field('position', __('Position'));
        $show->field('tel', __('Tel'));
        $show->field('email', __('Email'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new USCarriers());

        $form->text('name', __('Name'))->required();
        $form->text('address', __('Address'));
        $form->text('agent_name', __('Agent name'));
        $form->text('position', __('Position'));
        $form->text('tel', __('Tel'));
        $form->email('email', __('Email'));

        return $form;
    }
}
