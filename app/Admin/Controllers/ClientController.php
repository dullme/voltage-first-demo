<?php

namespace App\Admin\Controllers;

use App\AdminUser;
use App\Client;
use App\Contact;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ClientController extends ResponseController
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

        $grid->actions(function ($actions) {
            if($actions->row->projects()->count()){
                // 去掉删除
                $actions->disableDelete();
            } //存在 Project 不允许删除
        });

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

        $show->panel()->tools(function ($tools) {
                $tools->disableDelete();
        });

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
        $form->number('number', __('Code'))->creationRules(['required', "unique:clients"])
            ->updateRules(['required', "unique:clients,number,{{id}}"]);
        $form->text('tel', __('Tel'));
        $form->text('address', __('Address'));

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

//        if($form->isCreating()){
//            $form->saving(function (Form $form) {
//                $client = Client::orderBy('id', 'desc')->first();
//                $number = 0;
//                if($client){
//                    $number = intval($client->number) == 0 ? 100 : intval($client->number) + 1;
//                }
//                $form->number = sprintf('%03d', $number);
//            });
//        }

        return $form;
    }

    public function getClientList()
    {
        return Client::get(['id', DB::raw('name as text')]);
    }

    public function getAdminList()
    {
        return AdminUser::where('name', '!=', 'admin')->get(['id', DB::raw('name as text')]);
    }

    public function getContactList($id)
    {
        $clients = Contact::select('id', 'name')->where('client_id', $id)->get();

        return $this->responseSuccess($clients);
    }
}
