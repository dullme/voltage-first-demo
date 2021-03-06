<?php

namespace App\Admin\Controllers;

use App\Carrier;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CarrierController extends ResponseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Carrier';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Carrier());
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
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
        $show = new Show(Carrier::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
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
        $form = new Form(new Carrier());

        $form->text('name', __('Name'))->creationRules(['required', "unique:carriers"])
            ->updateRules(['required', "unique:carriers,name,{{id}}"]);

        return $form;
    }

    public function getCarriers()
    {
        $carriers = Carrier::pluck('name', 'id');

        return $this->responseSuccess($carriers);
    }
}
