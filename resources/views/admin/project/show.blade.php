<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Detail</h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ url('/admin/projects') }}" class="btn btn-sm btn-default" title="List">
                            <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                        </a>
                    </div>

                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;PO# Factory</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="">

                <div class="box-body">
                    <div class="col-md-12">
                        <p><b>Clients：</b>{{ $project->client->name }}</p>
                        <p><b>Project name：</b>{{ $project->name }}</p>
                        <p><b>P.O#：</b>{{ $project->no }}</p>
                        <p><b>Client delivery time：</b>{{ $project->client_delivery_time->toFormattedDateString() }}</p>
                        <p><b>PO Date：</b>{{ $project->po_date->toFormattedDateString() }}</p>
                    </div>

                    @foreach($project->poFactory as $item)
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <b>PO# Factory：</b>{{ $item->no }}
                                    <button class="btn btn-success btn-xs pull-right"
                                            onclick="factory('{{ $item->id }}', '{{ $item->no }}')">
                                        <i class="fa fa-plus"></i><span class="hidden-xs"></span>
                                    </button>
                                    @if(!!!count($item->batches))
                                        <button class="btn btn-default btn-xs pull-right" style="margin-right: 4px"
                                                onclick="deleteFactory('{{ $item->id }}')">
                                            <i class="fa fa-minus"></i><span class="hidden-xs"></span>
                                        </button>
                                    @else
                                        <button class="btn btn-default btn-xs pull-right" style="margin-right: 4px" title="history"
                                                onclick="deletedBatch('{{ $item->id }}', '{{ $item->no }}')">
                                            <i class="fa fa-history"></i><span class="hidden-xs"></span>
                                        </button>
                                    @endif
                                </div>
                                <div style="overflow: auto; width: 100%;">

                                    <table class="table table-bordered text-center"
                                           style="margin-bottom: 100px; {{ count($item->batches->where('deleted_at', null)) == 0 ? 'border-bottom:0' : '' }}">
                                        <thead>
                                        <tr>
                                            <th>Updated at</th>
                                            <th>Project name</th>
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
                                        @if(count($item->batches->where('deleted_at', null)))
                                            @foreach($item->batches->where('deleted_at', null) as $batch)
                                                <tr class="data_batch_row_{{ $batch->id }}">
                                                    <td style="vertical-align: middle">{{ $batch->updated_at->diffForHumans() }}</td>
                                                    <td style="vertical-align: middle">{{ $batch->name }}</td>
                                                    <td style="vertical-align: middle">
                                                        <span style="display: block;padding: 5px" class="label
                                                        @if($batch->status == 0)
                                                            label-info
                                                        @elseif($batch->status == 1)
                                                            label-warning
                                                        @elseif($batch->status == 2)
                                                            label-success
                                                        @endif
                                                            ">{{ \App\Enums\BatchStatus::getDescription($batch->status) }}</span>
                                                    </td>
                                                    <td style="vertical-align: middle">
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
                                                            <p>{{ $batch->estimated_production_completion ? $batch->estimated_production_completion->toFormattedDateString() : '-' }}</p>
                                                            <p>{{ $batch->etd_port ? $batch->etd_port->toFormattedDateString() : '-' }}</p>
                                                            <p>{{ $batch->eta_port ? $batch->eta_port->toFormattedDateString() : '-' }}</p>
                                                            <p>{{ $batch->eta_job_site ? $batch->eta_job_site->toFormattedDateString() : '-' }}</p>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
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
                                                            <p>{{ $batch->actual_production_completion ? $batch->actual_production_completion->toFormattedDateString() : '-' }}</p>
                                                            <p>{{ $batch->atd_port ? $batch->atd_port->toFormattedDateString() : '-' }}</p>
                                                            <p>{{ $batch->ata_port ? $batch->ata_port->toFormattedDateString() : '-' }}</p>
                                                            <p>{{ $batch->ata_job_site ? $batch->ata_job_site->toFormattedDateString() : '-' }}</p>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">{{ $batch->carrier }}</td>
                                                    <td style="vertical-align: middle">{{ $batch->b_l }}</td>
                                                    <td style="vertical-align: middle">{{ $batch->vessel }}</td>
                                                    <td style="vertical-align: middle">{{ $batch->container_no }}</td>
                                                    <td style="vertical-align: middle">{{ $batch->remarks }}</td>
                                                    <td style="vertical-align: middle">{{ $batch->shipping_method }}</td>
                                                    <td style="vertical-align: middle">
                                                        <div class="grid-dropdown-actions dropdown">
                                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle"
                                                               data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v"></i>
                                                            </a>
                                                            <ul class="dropdown-menu"
                                                                style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                                                <li>
                                                                    <a href="javascript:void(0);" class="batch-edit"
                                                                       data-edit="{{ $batch->id }}">Edit</a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0);" class="batch-delete"
                                                                       force-delete="false"
                                                                       data-delete="{{ $batch->id }}">Delete</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="18"
                                                    style="padding: 100px 50px 0px 50px;text-align: center;color: #999999;border-bottom: 0">
                                                    <svg t="1562312016538" class="icon" viewBox="0 0 1024 1024"
                                                         version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2076"
                                                         width="80" height="80" style="fill: #e9e9e9;">
                                                        <path
                                                            d="M512.8 198.5c12.2 0 22-9.8 22-22v-90c0-12.2-9.8-22-22-22s-22 9.8-22 22v90c0 12.2 9.9 22 22 22zM307 247.8c8.6 8.6 22.5 8.6 31.1 0 8.6-8.6 8.6-22.5 0-31.1L274.5 153c-8.6-8.6-22.5-8.6-31.1 0-8.6 8.6-8.6 22.5 0 31.1l63.6 63.7zM683.9 247.8c8.6 8.6 22.5 8.6 31.1 0l63.6-63.6c8.6-8.6 8.6-22.5 0-31.1-8.6-8.6-22.5-8.6-31.1 0l-63.6 63.6c-8.6 8.6-8.6 22.5 0 31.1zM927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z"
                                                            p-id="2077"></path>
                                                    </svg>
                                                </td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- /.box-body -->

            </div>
        </div>
    </div>

    <div class="col-md-12">
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add PO# Factory</h4>
            </div>
            <form>
                <div class="modal-body">
                    <textarea rows="5" placeholder="PO# Factory" class="form-control remark" id="po"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="submit" type="button" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Factory Modal -->
<div class="modal fade" id="myFactoryModal" tabindex="-1" role="dialog" aria-labelledby="myFactoryModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">PO# Factory：<span id="factory_no"></span></h4>
            </div>
            <form class="form-horizontal" id="factory-form">
                <input id="po_factory_id" name="po_factory_id" class="hidden" value="">
                <div class="modal-body" style="display: inline-block">
                    <div class="fields-group">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group  ">
                                                <label class="col-sm-4 asterisk control-label">Project name</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="name" value=""
                                                               class="form-control" placeholder="Input Project name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">Carrier</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="carrier" value=""
                                                               class="form-control" placeholder="Input Carrier">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">B/L</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="b_l" value=""
                                                               class="form-control" placeholder="Input B/L">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                          data-original-title="Shipping method">S-method</span>
                                                </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="shipping_method">
                                                        <option style='display: none'></option>
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

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">Vessel</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="vessel" value=""
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
                                                        <input type="text" name="container_no" value=""
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
                                                        <input type="text" name="remarks" value=""
                                                               class="form-control" placeholder="Input Remarks">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="col-md-6">
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
                                                                   class="form-control estimated-datetime-picker">
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
                                                                   class="form-control estimated-datetime-picker" readonly="readonly">
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
                                                                   class="form-control estimated-datetime-picker" readonly="readonly">
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
                                                                   class="form-control estimated-datetime-picker" readonly="readonly">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                                                   class="form-control actual-datetime-picker">
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
                                                                   class="form-control actual-datetime-picker" readonly="readonly">
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
                                                                   class="form-control actual-datetime-picker" readonly="readonly">
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
                                                                   class="form-control actual-datetime-picker" readonly="readonly">
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
                    <button id="factory-submit" type="button" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Batch -->
<div class="modal fade" id="myFactoryEditModal" tabindex="-1" role="dialog" aria-labelledby="myFactoryEditModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">PO# Factory：<span id="edit_factory_no"></span></h4>
            </div>
            <form class="form-horizontal" id="factory-edit-form">
                <div class="modal-body" style="display: inline-block">
                    <div class="fields-group">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group  ">
                                                <label class="col-sm-4 asterisk control-label">Project name</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="name" value=""
                                                               class="form-control" placeholder="Input Project name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">Carrier</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="carrier" value=""
                                                               class="form-control" placeholder="Input Carrier">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">B/L</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="b_l" value=""
                                                               class="form-control" placeholder="Input B/L">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                          data-original-title="Shipping method">S-method</span>
                                                </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="shipping_method">
                                                        <option style='display: none'></option>
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

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group  ">
                                                <label class="col-sm-4 control-label">Vessel</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-pencil fa-fw"></i></span>
                                                        <input type="text" name="vessel" value=""
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
                                                        <input type="text" name="container_no" value=""
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
                                                        <input type="text" name="remarks" value=""
                                                               class="form-control" placeholder="Input Remarks">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div
                                                style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px">
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
                                                                   class="form-control estimated-datetime-picker">
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
                                                                   class="form-control estimated-datetime-picker" readonly="readonly">
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
                                                                   class="form-control estimated-datetime-picker" readonly="readonly">
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
                                                                   class="form-control estimated-datetime-picker" readonly="readonly">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                                                   class="form-control actual-datetime-picker">
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
                                                                   class="form-control actual-datetime-picker" readonly="readonly">
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
                                                                   class="form-control actual-datetime-picker" readonly="readonly">
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
                                                                   class="form-control actual-datetime-picker" readonly="readonly">
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
                    <button id="edit-batch-submit" type="button" class="btn btn-primary" data-batch-id="">Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- DeletedBatch -->
<div class="modal fade" id="myDeletedBatchModal" tabindex="-1" role="dialog" aria-labelledby="myDeletedBatchModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Deleted PO# Factory：<span id="deleted_factory_no"></span></h4>
            </div>
            <form>
                <div class="modal-body">
                    <div style="overflow: auto; width: 100%;">
                        <table class="table" style="margin-bottom: 100px">
                            <thead>
                            <tr>
                                <th>Deleted at</th>
                                <th>Project name</th>
                                <th>B/L</th>
                                <th>Vessel</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="deleted-html-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<progress-line></progress-line>



<script>
    $(function () {
        $('.estimated-datetime-picker').datetimepicker({
            'format': 'YYYY-MM-DD',
            'allowInputToggle': true,
        });

        $('.actual-datetime-picker').datetimepicker({
            'format': 'YYYY-MM-DD',
            'allowInputToggle': true,
            'maxDate': Date.today()
        });

        //1
        $("input[name='estimated_production_completion']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='etd_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                $("input[name='etd_port']").val('')
                $("input[name='etd_port']").attr("readonly", false);
                $("input[name='eta_port']").attr("readonly", true);
                $("input[name='eta_job_site']").attr("readonly", true);
            }else{
                $("input[name='etd_port']").val('')
                $("input[name='etd_port']").attr("readonly", true);
            }
        });


        //a1
        $("input[name='actual_production_completion']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='atd_port']").data("DateTimePicker").minDate(e.currentTarget.value)
                $("input[name='atd_port']").val('')
                $("input[name='atd_port']").attr("readonly", false);
                $("input[name='ata_port']").attr("readonly", true);
                $("input[name='ata_job_site']").attr("readonly", true);
            }else{
                $("input[name='atd_port']").val('')
                $("input[name='atd_port']").attr("readonly", true);
            }
        });

        //2
        $("input[name='etd_port']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='estimated_production_completion']").attr("readonly", true);
                $("input[name='eta_port']").data("DateTimePicker").minDate(e.currentTarget.value);
                $("input[name='eta_port']").val('')
                $("input[name='eta_port']").attr("readonly", false);
                $("input[name='eta_job_site']").attr("readonly", true);
            }else{
                $("input[name='estimated_production_completion']").attr("readonly", false);
                $("input[name='eta_port']").val('')
                $("input[name='eta_port']").attr("readonly", true);
            }
        });


        //a2
        $("input[name='atd_port']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='actual_production_completion']").attr("readonly", true);
                $("input[name='ata_port']").data("DateTimePicker").minDate(e.currentTarget.value);
                $("input[name='ata_port']").val('')
                $("input[name='ata_port']").attr("readonly", false);
                $("input[name='ata_job_site']").attr("readonly", true);
            }else{
                $("input[name='actual_production_completion']").attr("readonly", false);
                $("input[name='ata_port']").val('')
                $("input[name='ata_port']").attr("readonly", true);
            }
        });

        //3
        $("input[name='eta_port']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='eta_job_site']").data("DateTimePicker").minDate(e.currentTarget.value);
                $("input[name='eta_job_site']").val('')
                $("input[name='eta_job_site']").attr("readonly", false);
                $("input[name='etd_port']").attr("readonly", true);
            }else{
                $("input[name='eta_job_site']").val('')
                $("input[name='etd_port']").attr("readonly", false);
                $("input[name='eta_job_site']").attr("readonly", true);
            }
        });

        //a3
        $("input[name='ata_port']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='ata_job_site']").data("DateTimePicker").minDate(e.currentTarget.value);
                $("input[name='ata_job_site']").val('')
                $("input[name='ata_job_site']").attr("readonly", false);
                $("input[name='atd_port']").attr("readonly", true);
            }else{
                $("input[name='ata_job_site']").val('')
                $("input[name='atd_port']").attr("readonly", false);
                $("input[name='ata_job_site']").attr("readonly", true);
            }
        });

        //4
        $("input[name='eta_job_site']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='eta_port']").attr("readonly", true);
            }else{
                $("input[name='eta_port']").attr("readonly", false);
            }
        });

        //a4
        $("input[name='ata_job_site']").on('dp.change', (e) => {
            if(e.currentTarget.value){
                $("input[name='ata_port']").attr("readonly", true);
            }else{
                $("input[name='ata_port']").attr("readonly", false);
            }
        });
    })

    //添加Factory
    $('#submit').on('click', function () {
        let can_submit = true;
        if (can_submit) {
            can_submit = false;
            axios({
                method: 'post',
                url: '/admin/po-factory',
                data: {
                    'project_id': '{{ $project->id }}',
                    'no': $('#po').val()
                }
            }).then(response => {
                if (response.data.status) {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.reload()
                    });
                } else {
                    can_submit = true;
                }
            }).catch(error => {
                toastr.error(error.response.data.errors.no[0]);
                can_submit = true;
            });
        }

    })


    //添加 Batch
    $('#factory-submit').on('click', function () {
        let data = {};
        let t = $('#factory-form').serializeArray();
        $.each(t, function () {
            data[this.name] = this.value;
        });

        let factory_can_submit = true
        if (factory_can_submit) {
            let factory_can_submit = false
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
                    });
                } else {
                    factory_can_submit = true;
                }
            }).catch(error => {
                toastr.error(error.response.data.message);
                factory_can_submit = true;
            });
        }

    })


    //删除 Batch
    $(document).on('click', '.batch-delete', function () {
        let batch_id = $(this).attr('data-delete')
        let force_delete = $(this).attr('force-delete')
        let title = 'Are you sure to delete this item ?'
        if(force_delete == 'true'){
            title = 'Are you sure to force delete this item ?'
        }

        swal({
            title: title,
            type: force_delete == 'true' ? 'warning' : 'info',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {
                axios({
                    method: 'post',
                    url: '/admin/delete/batch/' + batch_id,
                    data: {
                        force_delete : force_delete
                    }
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        $('.data_batch_row_'+batch_id).remove()
                        // location.reload()
                    });
                })
            }
        })
    })

    //恢复 Batch
    $(document).on('click', '.batch-restore', function () {
        let batch_id = $(this).attr('data-restore')

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
                    url: '/admin/restore/batch/' + batch_id,
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
    })

    $('.batch-edit').on('click', function () {
        let batch_id = $(this).attr('data-edit')
        $("#factory-edit-form input").val('')
        $("#factory-edit-form select").val('')
        axios({
            method: 'get',
            url: '/admin/batch/' + batch_id,
        }).then(response => {
            if (response.data.status) {
                $('#edit_factory_no').html(response.data.data.po_factory.no);
                $("#factory-edit-form input[name='name']").val(response.data.data.name)
                $("#factory-edit-form input[name='carrier']").val(response.data.data.carrier)
                $("#factory-edit-form input[name='b_l']").val(response.data.data.b_l)
                $("#factory-edit-form input[name='vessel']").val(response.data.data.vessel)
                $("#factory-edit-form input[name='container_no']").val(response.data.data.container_no)
                $("#factory-edit-form input[name='remarks']").val(response.data.data.remarks)
                $("#factory-edit-form select[name='shipping_method']").val(response.data.data.shipping_method)

                //epc
                if(response.data.data.estimated_production_completion){
                    $("#factory-edit-form input[name='estimated_production_completion']").val(response.data.data.estimated_production_completion.substr(0, 10))
                }

                //etd port
                if(response.data.data.etd_port){
                    $("#factory-edit-form input[name='etd_port']").val(response.data.data.etd_port.substr(0, 10))
                    $("#factory-edit-form input[name='estimated_production_completion']").attr('readonly', true)
                }else{
                    $("#factory-edit-form input[name='estimated_production_completion']").attr('readonly', false)
                }

                if(response.data.data.estimated_production_completion && !response.data.data.eta_port){
                    $("#factory-edit-form input[name='etd_port']").attr('readonly', false)
                }else{
                    $("#factory-edit-form input[name='etd_port']").attr('readonly', true)
                }

                if(response.data.data.etd_port && !response.data.data.eta_job_site){
                    $("#factory-edit-form input[name='eta_port']").attr('readonly', false)
                }else{
                    $("#factory-edit-form input[name='eta_port']").attr('readonly', true)
                }

                //eta port
                if(response.data.data.eta_port){
                    $("#factory-edit-form input[name='eta_port']").val(response.data.data.eta_port.substr(0, 10))
                    $("#factory-edit-form input[name='eta_job_site']").attr('readonly', false)
                }else{
                    $("#factory-edit-form input[name='eta_job_site']").attr('readonly', true)
                }

                //eta job site
                if(response.data.data.eta_job_site){
                    $("#factory-edit-form input[name='eta_job_site']").val(response.data.data.eta_job_site.substr(0, 10))
                }


                //apc
                if(response.data.data.actual_production_completion){
                    $("#factory-edit-form input[name='actual_production_completion']").val(response.data.data.actual_production_completion.substr(0, 10))
                }

                //atd port
                if(response.data.data.atd_port){
                    $("#factory-edit-form input[name='atd_port']").val(response.data.data.atd_port.substr(0, 10))
                    $("#factory-edit-form input[name='actual_production_completion']").attr('readonly', true)
                }else{
                    $("#factory-edit-form input[name='actual_production_completion']").attr('readonly', false)
                }

                if(response.data.data.actual_production_completion && !response.data.data.ata_port){
                    $("#factory-edit-form input[name='atd_port']").attr('readonly', false)
                }else{
                    $("#factory-edit-form input[name='atd_port']").attr('readonly', true)
                }

                if(response.data.data.atd_port && !response.data.data.ata_job_site){
                    $("#factory-edit-form input[name='ata_port']").attr('readonly', false)
                }else{
                    $("#factory-edit-form input[name='ata_port']").attr('readonly', true)
                }

                //ata port
                if(response.data.data.ata_port){
                    $("#factory-edit-form input[name='ata_port']").val(response.data.data.ata_port.substr(0, 10))
                    $("#factory-edit-form input[name='ata_job_site']").attr('readonly', false)
                }else{
                    $("#factory-edit-form input[name='ata_job_site']").attr('readonly', true)
                }

                //ata job site
                if(response.data.data.ata_job_site){
                    $("#factory-edit-form input[name='ata_job_site']").val(response.data.data.ata_job_site.substr(0, 10))
                }

                $('#edit-batch-submit').attr('data-batch-id', response.data.data.id)
                $('#myFactoryEditModal').modal('show')
            }

        })
    })

    $('#edit-batch-submit').on('click', function () {
        let batch_id = $('#edit-batch-submit').attr('data-batch-id')
        let data = {};
        let t = $('#factory-edit-form').serializeArray();
        $.each(t, function () {
            data[this.name] = this.value;
        });

        let can_edit_submit = true;
        if (can_edit_submit) {
            can_edit_submit = false;
            axios({
                method: 'post',
                url: '/admin/po-factory-batch/edit/' + batch_id,
                data: data
            }).then(response => {
                if (response.data.status) {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.reload()
                    });
                } else {
                    can_edit_submit = true;
                }
            }).catch(error => {
                toastr.error(error.response.data.message);
                can_edit_submit = true;
            });
        }

    })

    function factory(id, no) {
        $('#po_factory_id').val(id);
        $('#factory_no').html(no);
        $('#myFactoryModal').modal('show')
    }

    //删除 Factory
    function deleteFactory(id) {
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
    }

    //已删除的 Batch
    function deletedBatch(id, no) {
        $('#deleted_factory_no').html(no);
        axios({
            method: 'post',
            url: '/admin/deleted/batch/' + id,
        }).then(response => {
            let html = ''
            response.data.data.forEach(function (item) {
                let vessel = item.vessel ?? ""

                html += '<tr class="data_batch_row_' + item.id + '">' +
                    '<th scope="row">' + item.deleted_at + '</th>' +
                    '<td>' + item.name + '</td>' +
                    '<td>' + item.b_l + '</td>' +
                    '<td>' + vessel + '</td>' +
                    '<td><a href="javascript:void(0);" title="restore" class="batch-restore btn btn-default btn-xs" data-restore="' + item.id + '"><i class="fa fa-undo"></i></a> <a href="javascript:void(0);" title="force delete" class="batch-delete btn btn-default btn-xs" force-delete="true" data-delete="' + item.id + '"><i class="fa fa-trash"></i></a></td>' +
                    '</tr>'

                $('#deleted-html-body').html(html)
            })

        })
        $('#myDeletedBatchModal').modal('show')
    }

</script>
