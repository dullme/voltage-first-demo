<?php

namespace App\Admin\Controllers;

use App\Forwarder;
use App\ForwarderContact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class ForwarderContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\ForwarderContact';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ForwarderContact());
        $grid->model()->orderByDesc('id');
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->equal('forwarder_id', 'Forwarder')->select('/admin/forwarder-list');
        });

//        $grid->column('id', __('Id'));
        $grid->forwarder()->name(__('Forwarder name'));
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
        $show = new Show(ForwarderContact::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('forwarder_id', __('Forwarder id'));
        $show->field('name', __('Name'));
        $show->field('position', __('Position'));
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
        $form = new Form(new ForwarderContact());

        $forwarders = Forwarder::pluck('name', 'id');
        $form->select('forwarder_id', 'Forwarder')->options($forwarders)->required();
        $form->text('name', __('Name'))->required();
        $form->text('position', __('Position'));
        $form->text('tel', __('Tel'));
        $form->email('email', __('Email'));

        $form->saving(function (Form $form){

            $contact = ForwarderContact::where('forwarder_id', $form->forwarder_id)->where('name', $form->name);
            $contact = $form->model()->id ? $contact->where('id', '!=', $form->model()->id)->count() : $contact->count();

            if($contact > 0){
                $error = new MessageBag([
                    'title'   => 'ERROR',
                    'message' => 'The Name has already been taken.',
                ]);

                return back()->with(compact('error'));
            }
        });


        return $form;
    }

    public function getForwarderContacts()
    {
        $data = ForwarderContact::with('forwarder')->orderBy('forwarder_id', 'DESC')->get();

        $data = $data->map(function ($item){
            return [
                'id' => $item['id'],
                'text' => "{$item['forwarder']['name']}：{$item['name']}",
            ];
        });

        return $data;
    }
}
