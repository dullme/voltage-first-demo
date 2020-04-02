<?php

namespace App\Admin\Controllers;

use App\Port;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PortController extends ResponseController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Port';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Port());
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();

        $grid->column('name', __('Name'));
        $grid->column('type', __('China/Abroad'))->display(function ($type) {
            return $type == 0 ? 'China' : 'Abroad';
        });

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
        $show = new Show(Port::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('type', __('China/Abroad'))->using([0 => 'China', 1 => 'Abroad']);

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Port());
        $form->text('name', __('Name'));
        $form->switch('type', __('China/Abroad'))->states([
            'on'  => ['value' => 1, 'text' => 'Abroad'],
            'off' => ['value' => 0, 'text' => 'China'],
        ]);

        return $form;
    }

    public function getPortList()
    {
        $ports = Port::select('name', 'id', 'type')->get();

        return $this->responseSuccess($ports);
    }
}
