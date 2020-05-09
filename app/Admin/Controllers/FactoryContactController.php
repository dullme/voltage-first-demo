<?php

namespace App\Admin\Controllers;

use App\Factory;
use App\FactoryContact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FactoryContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\FactoryContact';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FactoryContact());
        $grid->model()->orderByDesc('id');
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->equal('factory_id', 'Factory')->select('/admin/factory-list');
        });

//        $grid->column('id', __('Id'));
        $grid->factory()->name(__('Factory name'));
        $grid->column('name', __('Name'));
        $grid->column('position', __('Position'));
        $grid->column('tel', __('Tel'));
        $grid->column('email', __('Email'));
        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(FactoryContact::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('factory_id', __('Factory id'));
        $show->field('name', __('Name'));
        $show->field('tel', __('Tel'));
        $show->field('email', __('Email'));
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
        $form = new Form(new FactoryContact());

        $factories = Factory::pluck('name', 'id');
        $form->select('factory_id', 'Clients')->options($factories)->required();
        $form->text('name', __('Name'))->required();
        $form->text('position', __('Position'));
        $form->text('tel', __('Tel'));
        $form->email('email', __('E-mail'));

        return $form;
    }
}
