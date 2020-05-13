<?php

namespace App\Admin\Controllers;

use DB;
use App\Client;
use App\Contact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Contact';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Contact());
        $grid->model()->orderByDesc('id');
        $grid->disableExport();
//        $grid->disableFilter();
        $grid->disableRowSelector();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->equal('client_id', 'Client')->select('/admin/client-list');
        });

//        $grid->column('id', __('Id'));
        $grid->client()->name(__('Client name'));
        $grid->column('name', __('Name'));
        $grid->column('tel', __('Tel'));
        $grid->column('email', __('E-mail'));
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
        $show = new Show(Contact::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('client_id', __('Client id'));
        $show->field('name', __('Name'));
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
        $form = new Form(new Contact());

        $clients = Client::pluck('name', 'id');
        $form->select('client_id', 'Clients')->options($clients)->required();
        $form->text('name', __('Name'))->required();
        $form->text('tel', __('Tel'));
        $form->email('email', __('E-mail'));

        $form->saving(function (Form $form){

            $contact = Contact::where('client_id', $form->client_id)->where('name', $form->name);
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

    public function contact(Request $request)
    {
        $clientId = $request->get('q');

        return Contact::where('client_id', $clientId)->get(['id', DB::raw('name as text')]);
    }
}
