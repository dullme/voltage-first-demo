<?php

namespace App\Admin\Controllers;

use App\Batch;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BatchController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Batch';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batch());

        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreateButton();

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('containers.no','No');
            $filter->like('b_l','B/L');
        });

        $grid->column('name', __('Name'))->display(function ($name){
            return $name ? getSequence($this->sequence) .' - '.$name : getSequence($this->sequence);
        });

        $grid->containers(__('Containers'))->display(function ($containers){
            $res = '';
            foreach ($containers as $container){
                $url = url('admin/batch/show/'.$container['batch_id'].'?container='.$container['id']);
                $res .= "<a style='display: block' href='{$url}'>{$container['no']}</a>";
            }
            return $res;
        });

        $grid->column('sequence', __('Sequence'));
        $grid->column('carrier', __('Carrier'));
        $grid->column('ocean_forwarder', __('Ocean forwarder'));
        $grid->column('inland_forwarder', __('Inland forwarder'));
        $grid->column('china_inland_forwarder', __('China inland forwarder'));
        $grid->column('b_l', __('B/L'));
        $grid->column('vessel', __('Vessel'));
        $grid->column('remarks', __('Remarks'));

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
        $show = new Show(Batch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('po_factory_id', __('Po factory id'));
        $show->field('name', __('Name'));
        $show->field('sequence', __('Sequence'));
        $show->field('status', __('Status'));
        $show->field('estimated_production_completion', __('Estimated production completion'));
        $show->field('etd_port', __('Etd port'));
        $show->field('eta_port', __('Eta port'));
        $show->field('eta_job_site', __('Eta job site'));
        $show->field('actual_production_completion', __('Actual production completion'));
        $show->field('atd_port', __('Atd port'));
        $show->field('ata_port', __('Ata port'));
        $show->field('ata_job_site', __('Ata job site'));
        $show->field('carrier', __('Carrier'));
        $show->field('ocean_forwarder', __('Ocean forwarder'));
        $show->field('inland_forwarder', __('Inland forwarder'));
        $show->field('china_inland_forwarder', __('China inland forwarder'));
        $show->field('b_l', __('B l'));
        $show->field('vessel', __('Vessel'));
        $show->field('remarks', __('Remarks'));
        $show->field('shipping_method', __('Shipping method'));
        $show->field('rmb', __('Rmb'));
        $show->field('foreign_currency', __('Foreign currency'));
        $show->field('foreign_currency_type', __('Foreign currency type'));
        $show->field('port_of_departure', __('Port of departure'));
        $show->field('destination_port', __('Destination port'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('epc_history', __('Epc history'));
        $show->field('etd_port_history', __('Etd port history'));
        $show->field('eta_port_history', __('Eta port history'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Batch());

        $form->number('po_factory_id', __('Po factory id'));
        $form->text('name', __('Name'));
        $form->number('sequence', __('Sequence'));
        $form->number('status', __('Status'));
        $form->datetime('estimated_production_completion', __('Estimated production completion'))->default(date('Y-m-d H:i:s'));
        $form->datetime('etd_port', __('Etd port'))->default(date('Y-m-d H:i:s'));
        $form->datetime('eta_port', __('Eta port'))->default(date('Y-m-d H:i:s'));
        $form->datetime('eta_job_site', __('Eta job site'))->default(date('Y-m-d H:i:s'));
        $form->datetime('actual_production_completion', __('Actual production completion'))->default(date('Y-m-d H:i:s'));
        $form->datetime('atd_port', __('Atd port'))->default(date('Y-m-d H:i:s'));
        $form->datetime('ata_port', __('Ata port'))->default(date('Y-m-d H:i:s'));
        $form->datetime('ata_job_site', __('Ata job site'))->default(date('Y-m-d H:i:s'));
        $form->text('carrier', __('Carrier'));
        $form->number('ocean_forwarder', __('Ocean forwarder'));
        $form->number('inland_forwarder', __('Inland forwarder'));
        $form->number('china_inland_forwarder', __('China inland forwarder'));
        $form->text('b_l', __('B l'));
        $form->text('vessel', __('Vessel'));
        $form->text('remarks', __('Remarks'));
        $form->text('shipping_method', __('Shipping method'));
        $form->decimal('rmb', __('Rmb'));
        $form->decimal('foreign_currency', __('Foreign currency'));
        $form->number('foreign_currency_type', __('Foreign currency type'));
        $form->text('port_of_departure', __('Port of departure'));
        $form->text('destination_port', __('Destination port'));
        $form->textarea('epc_history', __('Epc history'));
        $form->textarea('etd_port_history', __('Etd port history'));
        $form->textarea('eta_port_history', __('Eta port history'));

        return $form;
    }
}
