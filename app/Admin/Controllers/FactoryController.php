<?php

namespace App\Admin\Controllers;

use App\Factory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FactoryController extends ResponseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Factory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Factory());
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('address', __('Address'));
        $grid->column('tel', __('Tel'));
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
        $show = new Show(Factory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('address', __('Address'));
        $show->field('tel', __('Tel'));
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
        $form = new Form(new Factory());

        $form->text('name', __('Name'))->creationRules(['required', "unique:factories"])
            ->updateRules(['required', "unique:factories,name,{{id}}"]);
        $form->text('address', __('Address'));
        $form->text('tel', __('Tel'));

        return $form;
    }

    public function getFactories()
    {
        $factories = Factory::select('name', 'id')->get();

        return $this->responseSuccess($factories);
    }
}
