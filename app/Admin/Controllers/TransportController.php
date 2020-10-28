<?php

namespace App\Admin\Controllers;

use App\Carrier;
use App\Forwarder;
use App\Port;
use App\Transport;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TransportController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Transport';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transport());

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('carrier', 'Carrier')->select(Carrier::pluck('name', 'id'));
            $ports = Port::orderBy('type','ASC')->pluck('name', 'id');
            $filter->equal('port_of_departure', 'Port of departure')->select($ports);
            $filter->equal('destination_port', 'Destination port')->select($ports);

        });

        $grid->model()->where('end_time', '>=', Carbon::today());

        $grid->column('id', __('Id'));
        $grid->carriers()->name('carrier');
        $grid->agent()->agent_name('Agent');
        $grid->portOfDeparture()->name();
        $grid->destinationPort()->name();
        $grid->column('foreign_currency', __('Foreign currency'));
        $grid->column('shipping_day', __('Transport time'));
        $grid->column('start_time', __('Valid time'))->display(function ($start_time){
            return substr($start_time, 0, 10).' ~ ' . substr($this->end_time, 0, 10);
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
        $show = new Show(Transport::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('carrier', __('Carrier'));
        $show->field('port_of_departure', __('Port of departure'));
        $show->field('destination_port', __('Destination port'));
        $show->field('foreign_currency', __('Foreign currency'));
        $show->field('start_time', __('Start time'));
        $show->field('end_time', __('End time'));
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
        $form = new Form(new Transport());
        $ports = Port::orderBy('type','ASC')->pluck('name', 'id');
        $form->select('carrier', __('Carrier'))->options(Carrier::pluck('name', 'id'))->required();
        $form->select('agent_id', __('Agent'))->options(Forwarder::where('agent_name', '!=', '')->pluck('agent_name', 'id'))->required();
        $form->select('port_of_departure', __('Port of departure'))->options($ports)->required();
        $form->select('destination_port', __('Destination port'))->options($ports)->required();
        $form->number('shipping_day', __('Transport time'))->required()->min(1);
        $form->decimal('foreign_currency', __('Foreign currency'))->required();
        $form->dateRange('start_time', 'end_time', 'Valid time')->required();

        return $form;
    }
}
