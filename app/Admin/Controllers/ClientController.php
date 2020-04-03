<?php

namespace App\Admin\Controllers;

use App\Client;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClientController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Client';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Client());
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableRowSelector();

        $grid->column('name', __('Name'));
        $grid->column('number', __('Code'));
        $grid->column('tel', __('Tel'));
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
        $show = new Show(Client::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('tel', __('Tel'));
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
        $form = new Form(new Client());

        $form->text('name', __('Name'))->creationRules(['required', "unique:clients"])
            ->updateRules(['required', "unique:clients,name,{{id}}"]);
        $form->text('tel', __('Tel'));
        $form->text('address', __('Address'));
        $form->hidden('number', __('Number'));

        if($form->isCreating()){
            $form->saving(function (Form $form) {
                $client = Client::orderBy('id', 'desc')->first();
                $number = 0;
                if($client){
                    $number = intval($client->number) == 0 ? 100 : intval($client->number) + 1;
                }
                $form->number = sprintf('%03d', $number);
            });
        }

        return $form;
    }
}
