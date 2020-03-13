<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ project_name }} - {{ client.name }}</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="/admin/projects" class="btn btn-sm btn-default" title="List">
                                <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                            </a>
                        </div>

                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#poClient">
                                <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;PO# Client</span>
                            </button>
                        </div>

                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="">

                    <div class="box-body" style="padding-top: 25px">
                        <div class="">
                            <div class="col-md-12">
                                <div class="panel panel-info" v-for="po_client in po_clients" style="margin-bottom: 60px">
                                    <div class="panel-heading" style="border-bottom: 0">
                                        <div style="margin-bottom: 10px">
                                            <span>PO# Client：{{ po_client.no }} {{ po_client.client_delivery_time }} - {{ po_client.po_date }}</span>
                                            <span style="float: right" class="btn btn-default btn-xs"
                                                  v-on:click="editPoClient(po_client.id)"><i
                                                class="fa fa-pencil" style="padding-right: 2px"></i> Edit PO# Client </span>
                                            <span style="float: right;margin-right: 7px" class="btn btn-danger btn-xs" v-if="!po_client.po_factories.length"
                                                  v-on:click="deletePoClient(po_client.id)"><i
                                                class="fa fa-minus" style="padding-right: 2px"></i> Delete PO# Client </span>
                                            <span style="float: right;margin-right: 7px" class="btn btn-success btn-xs"
                                                  v-on:click="showAddPoFactory(po_client.id, po_client.no)"><i
                                                class="fa fa-plus" style="padding-right: 2px"></i> Add PO# Factory </span>
                                        </div>

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist" style="background-color: #d9edf7">
                                            <li role="presentation" :class="index == 0 ? 'active' : ''" v-for="(po_factory,index) in po_client.po_factories">
                                                <a :href="'#' + po_factory.id + po_factory.no" aria-controls="home" role="tab" data-toggle="tab">
                                                    {{ po_factory.no }}<span class="badge" v-if="po_factory.batches.length">{{ po_factory.batches.length }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane" :class="index  == 0 ? 'active' : ''" :id="po_factory.id + po_factory.no" v-for="(po_factory,index) in po_client.po_factories">
                                            <div class="panel-body">
                                                <pre style="background-color: unset;border: unset;padding: 0">{{ po_factory.remarks }}</pre>

                                                <button class="btn btn-success btn-xs" style="margin-right: 5px" v-on:click="showAddShipment(po_factory.id, po_factory.no)">
                                                    <i class="fa fa-plus" style="padding-right: 2px"></i> Add Shipment #
                                                </button>

                                                <button class="btn btn-danger btn-xs" style="margin-right: 5px" v-if="!po_factory.batches.length" v-on:click="deletePoFactory(po_factory.id)">
                                                    <i class="fa fa-minus" style="padding-right: 2px"></i> Delete PO# Factory
                                                </button>

                                                <button class="btn btn-default btn-xs" style="margin-right: 5px" v-on:click="editPoFactory(po_factory.id)">
                                                    <i class="fa fa-pencil" style="padding-right: 2px"></i> Edit PO# Factory
                                                </button>

                                                <button class="btn btn-default btn-xs" style="margin-right: 5px" v-on:click="deletedShipment(po_factory.id, po_factory.no)">
                                                    <i class="fa fa-history" style="padding-right: 2px"></i> Factory Shipment #
                                                </button>
                                            </div>

                                            <div style="overflow: auto; width: 100%;">
                                                <table class="table text-center">
                                                    <thead>
                                                    <tr>
                                                        <th>Updated at</th>
                                                        <th>Shipment #</th>
                                                        <th style="min-width: 100px">PO Status</th>
                                                        <th style="min-width: 200px">Estimated</th>
                                                        <th style="min-width: 200px">Actual</th>
                                                        <th>Carrier</th>
                                                        <th>B/L</th>
                                                        <th>Vessel</th>
                                                        <th>Container No.</th>
                                                        <th>Remarks</th>
                                                        <th><span data-toggle="tooltip" data-placement="top"
                                                                  data-original-title="Shipping method">S-method</span></th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-if="po_factory.batches.length" v-for="batch in po_factory.batches">
                                                        <td>{{ batch.updated_at }}</td>
                                                        <td>{{ batch.name }}<b><i v-if="batch.sequence"> - {{ getSequence(batch.sequence) }}</i></b></td>
                                                        <td>
                                                            <span style="display: block;padding: 5px" class="label label-info" v-if="batch.status == 0">InProduction</span>
                                                            <span style="display: block;padding: 5px" class="label label-warning" v-else-if="batch.status == 1">Shipping</span>
                                                            <span style="display: block;padding: 5px" class="label label-success" v-else-if="batch.status == 2">Finished</span>
                                                        </td>
                                                        <td>
                                                            <div style="width:50%;text-align: right;float: left">
                                                                <p><i data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="Estimated production completion">EPC：</i>
                                                                </p>
                                                                <p><i>ETD Port：</i></p>
                                                                <p><i>ETA Port：</i></p>
                                                                <p><i>ETA Job Site：</i></p>
                                                            </div>
                                                            <div style="width:50%;text-align: left;float: right">
                                                                <p>{{ batch.estimated_production_completion ? batch.estimated_production_completion.substr(0,10) : '-' }}</p>
                                                                <p>{{ batch.etd_port ? batch.etd_port.substr(0,10) : '-' }}</p>
                                                                <p>{{ batch.eta_port ? batch.eta_port.substr(0,10) : '-' }}</p>
                                                                <p>{{ batch.eta_job_site ? batch.eta_job_site.substr(0,10) : '-' }}</p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="width:50%;text-align: right;float: left">
                                                                <p><i data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="Actual production completion">APC：</i>
                                                                </p>
                                                                <p><i>ATD Port：</i></p>
                                                                <p><i>ATA Port：</i></p>
                                                                <p><i>ATA Job Site：</i></p>
                                                            </div>
                                                            <div style="width:50%;text-align: left;float: right">
                                                                <p>{{ batch.actual_production_completion ? batch.actual_production_completion.substr(0,10) : '-' }}</p>
                                                                <p>{{ batch.atd_port ? batch.atd_port.substr(0, 10) : '-' }}</p>
                                                                <p>{{ batch.ata_port ? batch.ata_port.substr(0,10) : '-' }}</p>
                                                                <p>{{ batch.ata_job_site ? batch.ata_job_site.substr(0,10) : '-' }}</p>
                                                            </div>
                                                        </td>
                                                        <td>{{ batch.carrier }}</td>
                                                        <td>{{ batch.b_l }}</td>
                                                        <td>{{ batch.vessel }}</td>
                                                        <td>{{ batch.container_no }}</td>
                                                        <td>{{ batch.remarks }}</td>
                                                        <td>{{ batch.shipping_method }}</td>
                                                        <td style="vertical-align: middle">
                                                            <div class="grid-dropdown-actions dropdown">
                                                                <a href="#" style="padding: 0 10px;" class="dropdown-toggle"
                                                                   data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </a>
                                                                <ul class="dropdown-menu"
                                                                    style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                                                    <li>
                                                                        <a href="javascript:void(0);" v-on:click="editShipment(batch.id)">Edit</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0);" v-on:click="deleteShipment(batch.id, false)">Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr v-if="po_factory.batches.length == 0">
                                                        <td colspan="18"
                                                            style="padding: 100px 50px;text-align: center;color: #999999;border-bottom: 0">
                                                            <svg t="1562312016538" class="icon" viewBox="0 0 1024 1024"
                                                                 version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2076"
                                                                 width="80" height="80" style="fill: #e9e9e9;">
                                                                <path
                                                                    d="M512.8 198.5c12.2 0 22-9.8 22-22v-90c0-12.2-9.8-22-22-22s-22 9.8-22 22v90c0 12.2 9.9 22 22 22zM307 247.8c8.6 8.6 22.5 8.6 31.1 0 8.6-8.6 8.6-22.5 0-31.1L274.5 153c-8.6-8.6-22.5-8.6-31.1 0-8.6 8.6-8.6 22.5 0 31.1l63.6 63.7zM683.9 247.8c8.6 8.6 22.5 8.6 31.1 0l63.6-63.6c8.6-8.6 8.6-22.5 0-31.1-8.6-8.6-22.5-8.6-31.1 0l-63.6 63.6c-8.6 8.6-8.6 22.5 0 31.1zM927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z"
                                                                    p-id="2077"></path>
                                                            </svg>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>


        <!-- Add PO# Client -->
        <div class="modal fade in" id="poClient">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Add PO# Client</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="fields-group">
                                <div class="col-md-12">

                                    <div class="form-group ">
                                        <label class="col-sm-4 asterisk control-label">PO# Client</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                    class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" class="form-control" v-model="po_client_form.no">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-4 control-label">Client delivery time</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                    class="fa fa-calendar fa-fw"></i></span>
                                                <input type="text" name="client_delivery_time"
                                                       class="form-control datetime-picker">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-4 control-label">Po date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                    class="fa fa-calendar fa-fw"></i></span>
                                                <input type="text" name="po_date" class="form-control datetime-picker">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="clear: both">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button v-on:click="addPoClient" type="button" class="btn btn-primary">Submit</button>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- Edit PO# Client -->
        <div class="modal fade in" id="editPoClient">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Edit PO# Client：<span class="po_client_no"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="fields-group">
                                <div class="col-md-12">

                                    <div class="form-group ">
                                        <label class="col-sm-4 asterisk control-label">PO# Client</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                    class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" class="form-control" v-model="po_client_edit_form.no">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-4 control-label">Client delivery time</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                    class="fa fa-calendar fa-fw"></i></span>
                                                <input type="text" name="client_delivery_time" v-model="po_client_edit_form.client_delivery_time"
                                                       class="form-control datetime-picker">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-4 control-label">Po date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                    class="fa fa-calendar fa-fw"></i></span>
                                                <input type="text" name="po_date" v-model="po_client_edit_form.po_date"
                                                       class="form-control datetime-picker">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="clear: both">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button v-on:click="savePoClient" type="button" class="btn btn-primary">Submit</button>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- Add PO# Factory -->
        <div class="modal fade in" id="addPoFactory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Add PO# Factory - <span class="po_client_no"></span>
                        </h4>
                    </div>
                    <form class="form-horizontal">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>PO# Factory</label>
                                    <input type="email" class="form-control" placeholder="PO# Factory" v-model="po_factory_form.no">
                                </div>
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea rows="5" placeholder="Remarks" class="form-control remark" v-model="po_factory_form.remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="clear: both">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button v-on:click="addPoFactory" type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Edit PO# Factory -->
        <div class="modal fade in" id="editPoFactory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" >Edit PO# Factory - <span class="po_client_no"></span>
                        </h4>
                    </div>
                    <form class="form-horizontal">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>PO# Factory</label>
                                    <input type="email" class="form-control" placeholder="PO# Factory" v-model="po_factory_edit_form.no">
                                </div>
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea rows="5" placeholder="Remarks" class="form-control remark" v-model="po_factory_edit_form.remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="clear: both">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button v-on:click="savePoFactory" type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- Add Shipment -->
        <div class="modal fade in" id="addShipment">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">PO# Factory：<span class="po_factory_no"></span></h4>
                    </div>
                    <form class="form-horizontal">
                        <div class="modal-body">
                            <div class="fields-group">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 asterisk control-label">Shipment #</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.name" class="form-control" placeholder="Input Shipment #">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Carrier</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.carrier" class="form-control" placeholder="Input Carrier">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">B/L</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.b_l" class="form-control" placeholder="Input B/L">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">
                                                        <span data-toggle="tooltip" data-placement="top"
                                                          data-original-title="Shipping method">S-method</span>
                                                        </label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" name="shipping_method" v-model="shipment_form.shipping_method">
                                                                <option value="Customize">Customize</option>
                                                                <option value="Regular Ocean Shipping">Regular Ocean Shipping
                                                                </option>
                                                                <option value="Fast Ocean Shipping">Fast Ocean Shipping</option>
                                                                <option value="Expedited Ocean+Rail+Truck">Expedited
                                                                    Ocean+Rail+Truck
                                                                </option>
                                                                <option value="Ocean+Flatbed">Ocean+Flatbed</option>
                                                                <option value="Air Freight">Air Freight</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" v-if="shipment_form.shipping_method == 'Customize'">
                                                        <label class="col-sm-4 control-label"></label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.customize_shipping_method"
                                                                       class="form-control" placeholder="Input Remarks">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Sequence</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" v-model="shipment_form.sequence">
                                                                <option value="">Please choose</option>
                                                                <option value="1">1st</option>
                                                                <option value="2">2nd</option>
                                                                <option value="3">3rd</option>
                                                                <option v-for="i in 17" :value="i+3">{{ i+3 }}th</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Vessel</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.vessel"
                                                                       class="form-control" placeholder="Input Vessel">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Container No.</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.container_no"
                                                                       class="form-control" placeholder="Input Container No.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Remarks</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_form.remarks"
                                                                       class="form-control" placeholder="Input Remarks">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="col-md-6 estimated">
                                                    <div
                                                        style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              data-original-title="Estimated production completion">EPC</span>
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="estimated_production_completion"
                                                                           placeholder="Estimated Production Completion"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.estimated_production_completion"
                                                                           :readonly="shipment_form.etd_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ETD Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="etd_port" placeholder="ETD Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.etd_port"
                                                                           :readonly="!!!shipment_form.estimated_production_completion || shipment_form.eta_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ETA Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="eta_port" placeholder="ETA Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.eta_port"
                                                                           :readonly="!!!shipment_form.etd_port || shipment_form.eta_job_site ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="margin-bottom: 0">
                                                            <label class="col-sm-4 control-label">ETA Job Site</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="eta_job_site"
                                                                           placeholder="ETA Job Site"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.eta_job_site"
                                                                           :readonly="!!!shipment_form.eta_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 actual">
                                                    <div
                                                        style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px">
                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              data-original-title="Actual production completion">APC</span>
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="actual_production_completion"
                                                                           placeholder="Actual Production Completion"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.actual_production_completion"
                                                                           :readonly="shipment_form.atd_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ATD Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="atd_port" placeholder="ATD Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.atd_port"
                                                                           :readonly="!!!shipment_form.actual_production_completion || shipment_form.ata_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ATA Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="ata_port" placeholder="ATA Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.ata_port"
                                                                           :readonly="!!!shipment_form.atd_port || shipment_form.ata_job_site ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="margin-bottom: 0">
                                                            <label class="col-sm-4 control-label">ATA Job Site</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="ata_job_site"
                                                                           placeholder="ATA Job Site"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_form.ata_job_site"
                                                                           :readonly="!!!shipment_form.ata_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button v-on:click="addShipment" type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Edit Shipment -->
        <div class="modal fade in" id="editShipment">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">PO# Factory：<span class="po_factory_no"></span></h4>
                    </div>
                    <form class="form-horizontal">
                        <div class="modal-body">
                            <div class="fields-group">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6 estimated">
                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 asterisk control-label">Shipment #</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.name" class="form-control" placeholder="Input Shipment #">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Carrier</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.carrier" class="form-control" placeholder="Input Carrier">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">B/L</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.b_l" class="form-control" placeholder="Input B/L">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              data-original-title="Shipping method">S-method</span>
                                                        </label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" name="shipping_method" v-model="shipment_edit_form.shipping_method">
                                                                <option value="Customize">Customize</option>
                                                                <option value="Regular Ocean Shipping">Regular Ocean Shipping
                                                                </option>
                                                                <option value="Fast Ocean Shipping">Fast Ocean Shipping</option>
                                                                <option value="Expedited Ocean+Rail+Truck">Expedited
                                                                    Ocean+Rail+Truck
                                                                </option>
                                                                <option value="Ocean+Flatbed">Ocean+Flatbed</option>
                                                                <option value="Air Freight">Air Freight</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" v-if="shipment_edit_form.shipping_method == 'Customize'">
                                                        <label class="col-sm-4 control-label"></label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.customize_shipping_method"
                                                                       class="form-control" placeholder="Input Remarks">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Sequence</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" v-model="shipment_edit_form.sequence">
                                                                <option value="">Please choose</option>
                                                                <option value="1">1st</option>
                                                                <option value="2">2nd</option>
                                                                <option value="3">3rd</option>
                                                                <option v-for="i in 17" :value="i+3">{{ i+3 }}th</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Vessel</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.vessel"
                                                                       class="form-control" placeholder="Input Vessel">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Container No.</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.container_no"
                                                                       class="form-control" placeholder="Input Container No.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group  ">
                                                        <label class="col-sm-4 control-label">Remarks</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                                <input type="text" v-model="shipment_edit_form.remarks"
                                                                       class="form-control" placeholder="Input Remarks">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="col-md-6 estimated">
                                                    <div
                                                        style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              data-original-title="Estimated production completion">EPC</span>
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="estimated_production_completion"
                                                                           placeholder="Estimated Production Completion"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.estimated_production_completion"
                                                                           :readonly="shipment_edit_form.etd_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ETD Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="etd_port" placeholder="ETD Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.etd_port"
                                                                           :readonly="!!!shipment_edit_form.estimated_production_completion || shipment_edit_form.eta_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ETA Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="eta_port" placeholder="ETA Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.eta_port"
                                                                           :readonly="!!!shipment_edit_form.etd_port || shipment_edit_form.eta_job_site ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="margin-bottom: 0">
                                                            <label class="col-sm-4 control-label">ETA Job Site</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="eta_job_site"
                                                                           placeholder="ETA Job Site"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.eta_job_site"
                                                                           :readonly="!!!shipment_edit_form.eta_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 actual">
                                                    <div
                                                        style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px">
                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              data-original-title="Actual production completion">APC</span>
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="actual_production_completion"
                                                                           placeholder="Actual Production Completion"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.actual_production_completion"
                                                                           :readonly="shipment_edit_form.atd_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ATD Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="atd_port" placeholder="ATD Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.atd_port"
                                                                           :readonly="!!!shipment_edit_form.actual_production_completion || shipment_edit_form.ata_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">
                                                            <label class="col-sm-4 control-label">ATA Port</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="ata_port" placeholder="ATA Port"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.ata_port"
                                                                           :readonly="!!!shipment_edit_form.atd_port || shipment_edit_form.ata_job_site ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="margin-bottom: 0">
                                                            <label class="col-sm-4 control-label">ATA Job Site</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                                    <input type="text" name="ata_job_site"
                                                                           placeholder="ATA Job Site"
                                                                           class="form-control datetime-picker"
                                                                           v-model="shipment_edit_form.ata_job_site"
                                                                           :readonly="!!!shipment_edit_form.ata_port ? true : false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button v-on:click="saveShipment" type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Deleted Shipment -->
        <div class="modal fade in" id="deletedShipment">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Deleted PO# Factory：<span id="deleted_factory_no"></span></h4>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div style="overflow: auto; width: 100%;min-height: 300px">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Deleted at</th>
                                        <th>Shipment #</th>
                                        <th>PO Status</th>
                                        <th>B/L</th>
                                        <th>Vessel</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="deleted_shipments.length" v-for="deleted_shipment in deleted_shipments">
                                            <td>{{ deleted_shipment.deleted_at}}</td>
                                            <td>{{ deleted_shipment.name }}<b><i v-if="deleted_shipment.sequence"> - {{ getSequence(deleted_shipment.sequence) }}</i></b></td>
                                            <td>
                                                <span style="display: block;padding: 5px" class="label label-info" v-if="deleted_shipment.status == 0">InProduction</span>
                                                <span style="display: block;padding: 5px" class="label label-warning" v-else-if="deleted_shipment.status == 1">Shipping</span>
                                                <span style="display: block;padding: 5px" class="label label-success" v-else-if="deleted_shipment.status == 2">Finished</span>
                                            </td>
                                            <td>{{ deleted_shipment.b_l }}</td>
                                            <td>{{ deleted_shipment.vessel }}</td>
                                            <td>
                                                <a href="javascript:void(0);" title="restore" class="batch-restore btn btn-default btn-xs" v-on:click="restoreShipment(deleted_shipment.id)"><i class="fa fa-undo"></i></a>
                                                <a href="javascript:void(0);" title="force delete" class="batch-delete btn btn-default btn-xs" v-on:click="deleteShipment(deleted_shipment.id, true)"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>

                                        <tr v-if="!deleted_shipments.length">
                                            <td colspan="18"
                                                style="padding: 100px 50px;text-align: center;color: #999999;border-bottom: 0">
                                                <svg t="1562312016538" class="icon" viewBox="0 0 1024 1024"
                                                     version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2076"
                                                     width="80" height="80" style="fill: #e9e9e9;">
                                                    <path
                                                        d="M512.8 198.5c12.2 0 22-9.8 22-22v-90c0-12.2-9.8-22-22-22s-22 9.8-22 22v90c0 12.2 9.9 22 22 22zM307 247.8c8.6 8.6 22.5 8.6 31.1 0 8.6-8.6 8.6-22.5 0-31.1L274.5 153c-8.6-8.6-22.5-8.6-31.1 0-8.6 8.6-8.6 22.5 0 31.1l63.6 63.7zM683.9 247.8c8.6 8.6 22.5 8.6 31.1 0l63.6-63.6c8.6-8.6 8.6-22.5 0-31.1-8.6-8.6-22.5-8.6-31.1 0l-63.6 63.6c-8.6 8.6-8.6 22.5 0 31.1zM927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z"
                                                        p-id="2077"></path>
                                                </svg>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
</template>

<script>
    require('../../../public/vendor/date-js/date-zh-CN')

    export default {
        data() {
            return {
                project_id: '',
                client: '',
                po_clients: [],
                deleted_shipments: [],
                project_name: '',
                po_client_form: {
                    no: '',
                    client_delivery_time: '',
                    po_date: '',
                },
                po_client_edit_form: {
                    id: '',
                    no: '',
                    client_delivery_time: '',
                    po_date: '',
                },
                po_factory_form: {
                    po_client_id: '',
                    no: '',
                    remarks:'',
                },
                po_factory_edit_form: {
                    id: '',
                    no: '',
                    remarks:'',
                },
                shipment_form: {
                    po_factory_id: '',
                    name: '',
                    sequence: '',
                    carrier: '',
                    b_l: '',
                    vessel: '',
                    container_no: '',
                    remarks: '',
                    shipping_method: 'Customize',
                    customize_shipping_method: '', //自定义
                    estimated_production_completion: '',
                    etd_port: '',
                    eta_port: '',
                    eta_job_site: '',
                    actual_production_completion: '',
                    atd_port: '',
                    ata_port: '',
                    ata_job_site: '',
                },
                shipment_edit_form: {
                    id: '',
                    name: '',
                    sequence: '',
                    carrier: '',
                    b_l: '',
                    vessel: '',
                    container_no: '',
                    remarks: '',
                    shipping_method: 'Customize',
                    customize_shipping_method: '', //自定义
                    estimated_production_completion: '',
                    etd_port: '',
                    eta_port: '',
                    eta_job_site: '',
                    actual_production_completion: '',
                    atd_port: '',
                    ata_port: '',
                    ata_job_site: '',
                }
            }
        },

        props: [
            'project', //项目
        ],

        created() {
            let project = JSON.parse(this.project);
            this.project_id = project.id
            Vue.set(this.po_client_form, 'project_id', project.id);
            this.project_name = project.name
            this.client = project.client
            this.po_clients = project.po_clients
        },

        mounted() {
            $('#poClient .datetime-picker').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true
            });

            $('#editPoClient .datetime-picker').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true
            });

            $('.estimated .datetime-picker').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true
            });

            $('.actual .datetime-picker').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true,
                'maxDate': Date.today()
            });

            $('#poClient .datetime-picker').on('dp.change', (e) => {
                this.po_client_form[e.target.name] = e.currentTarget.value
            })

            $('#editPoClient .datetime-picker').on('dp.change', (e) => {
                this.po_client_edit_form[e.target.name] = e.currentTarget.value
            })

            $('#addShipment .datetime-picker').on('dp.change', (e) => {
                this.shipment_form[e.target.name] = e.currentTarget.value
            })

            $('#editShipment .datetime-picker').on('dp.change', (e) => {
                this.shipment_edit_form[e.target.name] = e.currentTarget.value
            })

            //addShipment
            $('#addShipment input[name="estimated_production_completion"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#addShipment input[name='etd_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_form.etd_port = '';
                }
            })

            $('#addShipment input[name="etd_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#addShipment input[name='eta_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_form.eta_port = '';
                }
            })

            $('#addShipment input[name="eta_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value){
                    $("#addShipment input[name='eta_job_site']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_form.eta_job_site = '';
                }
            })

            $('#addShipment input[name="actual_production_completion"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#addShipment input[name='atd_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_form.atd_port = '';
                }
            })

            $('#addShipment input[name="atd_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#addShipment input[name='ata_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_form.ata_port = '';
                }
            })

            $('#addShipment input[name="ata_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#addShipment input[name='ata_job_site']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_form.ata_job_site = '';
                }
            })

            //editShipment
            $('#editShipment input[name="estimated_production_completion"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#editShipment input[name='etd_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_edit_form.etd_port = '';
                }
            })

            $('#editShipment input[name="etd_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#editShipment input[name='eta_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_edit_form.eta_port = '';
                }
            })

            $('#editShipment input[name="eta_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#editShipment input[name='eta_job_site']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_edit_form.eta_job_site = '';
                }
            })

            $('#editShipment input[name="actual_production_completion"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#editShipment input[name='atd_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_edit_form.atd_port = '';
                }
            })

            $('#editShipment input[name="atd_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#editShipment input[name='ata_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_edit_form.ata_port = '';
                }
            })

            $('#editShipment input[name="ata_port"]').on('dp.change', (e) => {
                if(e.currentTarget.value) {
                    $("#editShipment input[name='ata_job_site']").data("DateTimePicker").minDate(e.currentTarget.value)
                    this.shipment_edit_form.ata_job_site = '';
                }
            })

        },

        methods: {
            inArray(search,array){
                for(let i in array){
                    if(array[i]==search){
                        return true;
                    }
                }
                return false;
            },

            getSequence(sequence){
                if(sequence){
                    switch (sequence) {
                        case 1 :
                            return sequence + 'st'
                            break
                        case 2 :
                            return sequence + 'nd'
                            break
                        case 3 :
                            return sequence + 'rd'
                            break
                        default :
                            return sequence + 'th'
                    }
                }

                return ''
            },

            addPoClient() {
                axios({
                    method: 'post',
                    url: '/admin/po-client/add/',
                    data: this.po_client_form
                }).then(response => {
                    console.log(response.data)
                    swal(
                        response.data.message,
                        '',
                        'success'
                    ).then(function () {
                        location.reload()
                    })

                }).catch(error => {
                    toastr.error(error.response.data.message);
                });
            },

            deletePoClient(id){
                swal({
                    title: 'Are you sure to delete this item ?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        axios({
                            method: 'post',
                            url: '/admin/po-client/delete/' + id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.message,
                                'success'
                            ).then(function () {
                                location.reload()
                            });
                        }).catch(error => {
                            swal(
                                error.response.data.message,
                                '',
                                'error'
                            )
                        });
                    }
                })
            },

            editPoClient(id){
                axios({
                    method: 'get',
                    url: '/admin/po-client/edit/' + id,
                }).then(response => {
                    this.po_client_edit_form.id = response.data.data.id
                    this.po_client_edit_form.no = response.data.data.no
                    this.po_client_edit_form.client_delivery_time = response.data.data.client_delivery_time
                    this.po_client_edit_form.po_date = response.data.data.po_date
                    $('#editPoClient .po_client_no').html(response.data.data.no)
                    $('#editPoClient').modal('show')
                })
            },

            savePoClient(){
                axios({
                    method: 'post',
                    url: '/admin/po-client/edit/' + this.po_client_edit_form.id,
                    data: this.po_client_edit_form
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.reload()
                    });
                }).catch(error => {
                    toastr.error(error.response.data.message);
                });
            },

            showAddPoFactory(po_client_id, po_client_no) {
                $('#addPoFactory .po_client_no').html(po_client_no)
                this.po_factory_form.no = '';
                this.po_factory_form.po_client_id = po_client_id;
                $('#addPoFactory').modal('show')
            },

            addPoFactory(){
                axios({
                    method: 'post',
                    url: '/admin/po-factory/add/',
                    data: this.po_factory_form
                }).then(response => {
                    console.log(response.data)
                    swal(
                        response.data.message,
                        '',
                        'success'
                    ).then(function () {
                        location.reload()
                    })

                }).catch(error => {
                    toastr.error(error.response.data.message)
                });
            },

            editPoFactory(id){
                axios({
                    method: 'get',
                    url: '/admin/po-factory/edit/' + id,
                }).then(response => {
                    this.po_factory_edit_form = response.data.data
                    $('#editPoFactory').modal('show')
                })
            },

            savePoFactory(){
                axios({
                    method: 'post',
                    url: '/admin/po-factory/edit/' + this.po_factory_edit_form.id,
                    data: this.po_factory_edit_form
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.reload()
                    });
                }).catch(error => {
                    toastr.error(error.response.data.message);
                });
            },

            deletePoFactory(id){
                swal({
                    title: 'Are you sure to delete this item ?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        axios({
                            method: 'post',
                            url: '/admin/delete/factory/' + id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.message,
                                'success'
                            ).then(function () {
                                location.reload()
                            });
                        }).catch(error => {
                            swal(
                                error.response.data.message,
                                '',
                                'error'
                            )
                        });
                    }
                })
            },

            showAddShipment(po_factory_id, po_factory_no){
                $('#addShipment .po_factory_no').html(po_factory_no)
                this.shipment_form.po_factory_id = po_factory_id
                $('#addShipment').modal('show')
            },

            addShipment(){
                let data = this.shipment_form
                if(data.shipping_method == 'Customize'){
                    data.shipping_method = this.shipment_form.customize_shipping_method
                }

                axios({
                    method: 'post',
                    url: '/admin/po-factory-batch',
                    data: data
                }).then(response => {
                    if (response.data.status) {
                        swal(
                            "SUCCESS",
                            response.data.message,
                            'success'
                        ).then(function () {
                            location.reload()
                        })
                    }
                }).catch(error => {
                    toastr.error(error.response.data.message)
                });
            },

            editShipment(id){
                axios({
                    method: 'get',
                    url: '/admin/batch/' + id,
                }).then(response => {
                    this.shipment_edit_form = {
                        id: response.data.data.id,
                        name: response.data.data.name,
                        sequence: response.data.data.sequence,
                        carrier: response.data.data.carrier,
                        b_l: response.data.data.b_l,
                        vessel: response.data.data.vessel,
                        container_no: response.data.data.container_no,
                        remarks: response.data.data.remarks,
                        shipping_method: response.data.data.shipping_method,
                        estimated_production_completion:response.data.data.estimated_production_completion,
                        etd_port: response.data.data.etd_port,
                        eta_port: response.data.data.eta_port,
                        eta_job_site: response.data.data.eta_job_site,
                        actual_production_completion: response.data.data.actual_production_completion,
                        atd_port: response.data.data.atd_port,
                        ata_port: response.data.data.ata_port,
                        ata_job_site: response.data.data.ata_job_site,
                    }

                    if(response.data.data.estimated_production_completion){
                        $("#editShipment input[name='etd_port']").data("DateTimePicker").minDate(response.data.data.estimated_production_completion)
                        this.shipment_edit_form.etd_port = response.data.data.etd_port ? response.data.data.etd_port : '';
                    }

                    if(response.data.data.etd_port){
                        $("#editShipment input[name='eta_port']").data("DateTimePicker").minDate(response.data.data.etd_port)
                        this.shipment_edit_form.eta_port = response.data.data.eta_port ? response.data.data.eta_port : '';
                    }

                    if(response.data.data.eta_port){
                        $("#editShipment input[name='eta_job_site']").data("DateTimePicker").minDate(response.data.data.eta_job_site)
                        this.shipment_edit_form.eta_job_site = response.data.data.eta_job_site ? response.data.data.eta_job_site : '';
                    }

                    if(response.data.data.actual_production_completion){
                        $("#editShipment input[name='atd_port']").data("DateTimePicker").minDate(response.data.data.actual_production_completion)
                        this.shipment_edit_form.atd_port = response.data.data.atd_port ? response.data.data.atd_port : '';
                    }

                    if(response.data.data.atd_port){
                        $("#editShipment input[name='ata_port']").data("DateTimePicker").minDate(response.data.data.atd_port)
                        this.shipment_edit_form.ata_port = response.data.data.ata_port ? response.data.data.ata_port : '';
                    }

                    if(response.data.data.ata_port){
                        $("#editShipment input[name='ata_job_site']").data("DateTimePicker").minDate(response.data.data.ata_job_site)
                        this.shipment_edit_form.ata_job_site = response.data.data.ata_job_site ? response.data.data.ata_job_site : '';
                    }



                    let shipping_method = this.inArray(response.data.data.shipping_method, [
                        'Regular Ocean Shipping',
                        'Fast Ocean Shipping',
                        'Expedited',
                        'Ocean+Rail+Truck',
                        'Ocean+Flatbed',
                        'Air Freight',
                    ])

                    if(shipping_method){
                        this.shipment_edit_form.customize_shipping_method = ''
                    }else{
                        this.shipment_edit_form.shipping_method = 'Customize'
                        this.shipment_edit_form.customize_shipping_method = response.data.data.shipping_method
                    }

                    $('#editShipment .po_factory_no').html(response.data.data.po_factory.no)
                    $('#editShipment').modal('show')
                    console.log(response)
                })
            },

            saveShipment(){
                let data = this.shipment_edit_form
                if(data.shipping_method == 'Customize'){
                    data.shipping_method = this.shipment_edit_form.customize_shipping_method
                }

                axios({
                    method: 'post',
                    url: '/admin/po-factory-batch/edit/' + this.shipment_edit_form.id,
                    data: data
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.reload()
                    });
                }).catch(error => {
                    toastr.error(error.response.data.message);
                });
            },

            deleteShipment(id, force_delete){
                let title = 'Are you sure to delete this item ?'
                if(force_delete){
                    title = 'Are you sure to force delete this item ?'
                }

                swal({
                    title: title,
                    type: force_delete? 'warning' : 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        axios({
                            method: 'post',
                            url: '/admin/delete/batch/' + id,
                            data: {
                                force_delete : force_delete
                            }
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.message,
                                'success'
                            ).then(function () {
                                // $('.data_batch_row_'+batch_id).remove()
                                location.reload()
                            });
                        })
                    }
                })
            },

            deletedShipment(id, no){
                console.log(123)
                $('#deleted_factory_no').html(no);
                axios({
                    method: 'post',
                    url: '/admin/deleted/batch/' + id,
                }).then(response => {
                    this.deleted_shipments =  response.data.data
                    $('#deletedShipment').modal('show')
                })
            },

            restoreShipment(id){
                swal({
                    title: 'Are you sure to restore this item ?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Restore',
                    cancelButtonText: 'Cancel'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        axios({
                            method: 'post',
                            url: '/admin/restore/batch/' + id,
                        }).then(response => {
                            swal(
                                "SUCCESS",
                                response.data.message,
                                'success'
                            ).then(function () {
                                location.reload()
                            });
                        })
                    }
                })
            }

        },
    }
</script>

<style scoped>
    .panel-heading{
        padding-bottom: unset;
    }

    .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover{
        border-color: #bce8f1;
        border-bottom: unset;
        border-bottom-color: unset;
    }

    .nav-tabs{
        border-bottom:unset;
    }

    .nav>li>a{
        padding: 5px 15px;
    }

    .table td {
        vertical-align: middle;
    }

    .panel-info>.panel-heading .badge {
        color: #d9edf7;
        background-color: #31708f;
        margin-top: -3px;
        margin-left: 5px;
    }
</style>
