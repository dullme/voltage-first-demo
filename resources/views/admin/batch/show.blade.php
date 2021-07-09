<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Detail <span class="label label-info" style="margin-left: 10px">{{ optional($batch->poFactory->poClient->project->author)->name }}</span><span style="margin-left: 10px"></span></h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ url('/admin/projects/'.$batch->poFactory->poClient->project->id) }}" class="btn btn-sm btn-default" title="List">
                            <i class="fa fa-mail-reply"></i><span class="hidden-xs"> Back</span>
                        </a>
                    </div>
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a class="btn btn-sm btn-info" href="{{ url('/admin/batches/'.$batch->id.'/edit') }}">
                            <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Upload File</span>
                        </a>
                    </div>
                    @if(!$batch->ata_port)
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Container</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="">

                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <p>Sequence：{{ getSequence($batch->sequence) }}</p>
                                <p>Carrier：{{ $batch->carrier }}</p>
                                <p>Ocean Forwarder：{{ $batch->oceanForwarder? optional(optional($batch->oceanForwarder)->forwarder)->name : '' }} : {{ $batch->oceanForwarder ? optional($batch->oceanForwarder)->name : '' }}</p>
                                <p>B/L：{{ $batch->b_l }}</p>
                                <p>RMB：{{ $batch->rmb }}</p>
                                <p>Port Of Departure：{{ $batch->port_of_departure }}</p>
                                <p>S-method：{{ $batch->shipping_method }}</p>

                                <p>AN：
                                    @if($batch->shipment_file)
                                    <a href="{{ url('/admin/download?file='.$batch->shipment_file) }}" target="_blank"><i class="fa fa-download"></i></a>
                                    @endif
                                </p>

                                <p>Freight：
                                    @if($batch->freight_file)
                                    <a href="{{ url('/admin/download?file='.$batch->freight_file) }}" target="_blank"><i class="fa fa-download"></i></a>
                                    @endif
                                </p>


                                <p>Tariff：
                                    @if($batch->tariff_file)
                                    <a href="{{ url('/admin/download?file='.$batch->tariff_file) }}" target="_blank"><i class="fa fa-download"></i></a>
                                    @endif
                                </p>

                            </div>

                            <div class="col-lg-6">
                                <p>Shipment #：{{ $batch->name }}</p>
                                <p>Vessel：{{ $batch->vessel }}</p>
                                <p>Inland Forwarder：{{ $batch->inlandForwarder ? optional(optional($batch->inlandForwarder)->forwarder)->name : '' }} : {{ $batch->inlandForwarder ? optional($batch->inlandForwarder)->name : '' }}</p>
                                <p>China Inland Forwarder：{{ $batch->chinaInlandForwarder ? optional(optional($batch->chinaInlandForwarder)->forwarder)->name : '' }} : {{ $batch->chinaInlandForwarder ? optional($batch->chinaInlandForwarder)->name : '' }}</p>
                                <p>Foreign Currency：{{ $batch->foreign_currency }}/{{ getForeignCurrencyType($batch->foreign_currency_type) }}</p>
                                <p>Destination Port：{{ $batch->destination_port }}</p>
                                <p>Remarks：{{ $batch->remarks }}</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div style="background-color: rgb(238, 238, 238);    padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                <p class="{{ $batch->epc_color ? $batch->epc_color == 1 ? 'll-warning' : 'll-danger' : '' }}">EPC：{{ optional($batch->estimated_production_completion)->toDatestring() }}</p>
                                <p class="{{ $batch->etd_color ? $batch->etd_color == 1 ? 'll-warning' : 'll-danger' : '' }}">ETD Port：{{ optional($batch->etd_port)->toDatestring() }}</p>
                                <p class="{{ $batch->eta_color ? $batch->eta_color == 1 ? 'll-warning' : 'll-danger' : '' }}">ETA Port：{{ optional($batch->eta_port)->toDatestring() }}</p>
                                <p class="{{ $batch->eta_job_site_color ? $batch->eta_job_site_color == 1 ? 'll-warning' : 'll-danger' : '' }}">ETA Job Site：{{ optional($batch->eta_job_site)->toDatestring() }}</p>
                                @if($batch->estimated_production_completion && $batch->eta_job_site)
                                    <p>Time consuming：
                                        <span class="label label-default">
                                                {{ \Carbon\Carbon::parse($batch->estimated_production_completion)->diffInDays($batch->eta_job_site) }} days
                                            </span>
                                    </p>
                                @endif
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <div style="background-color: rgb(238, 238, 238);    padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                <p>APC：{{ optional($batch->actual_production_completion)->toDatestring() }}</p>
                                <p>ATD Port：{{ optional($batch->atd_port)->toDatestring() }}</p>
                                <p>ATA Port：{{ optional($batch->ata_port)->toDatestring() }}</p>
                                <p>ATA Job Site：{{ optional($batch->ata_job_site)->toDatestring() }}</p>
                                @if($batch->actual_production_completion && $batch->ata_job_site)
                                    <p>Time consuming：
                                        <span class="label label-default">
                                                {{ \Carbon\Carbon::parse($batch->actual_production_completion)->diffInDays($batch->ata_job_site) }} days
                                            </span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($batch->containers->count())
                            <div class="col-sm-12" style="margin-top: 20px">
                                <div class="panel panel-info">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Container</div>
    {{--                                <div class="panel-body">--}}
    {{--                                    <p>...</p>--}}
    {{--                                </div>--}}

                                    <!-- Table -->
                                    <table class="table table-hover text-center">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>Type</th>
                                            <th style="min-width: 200px">ETA Job Site</th>
                                            <th style="min-width: 200px">ATA Job Site</th>
                                            <th>Link</th>
                                            <th>Amount</th>
                                            <th>US Carriers</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($batch->containers as $container)
                                            <tr class="data_container_row_{{ $container->id }}">
                                                <td>
                                                    @if(request()->get('container') && request()->get('container') == $container->id)
                                                        <span style="color: #dd4b39;font-weight: bold;font-size: 16px">{{ $container->no }}</span>
                                                    @else
                                                        @if($container->type)
                                                            {{ $container->no }}
                                                        @else
                                                            <span style="color: #e8e8e8;font-weight: bold;font-size: 16px">{{ $container->no }}</span>
                                                        @endif

                                                    @endif
                                                </td>
                                                <td>{{ $container->type }}</td>
                                                <td style="vertical-align: middle">

                                                    <p class="{{ $container->eta_job_site_color ? $batch->eta_job_site_color == 1 ? 'll-warning' : 'll-danger' : '' }}">
                                                        <span class="popover-t" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="ETA Job Site" data-content="{{ popover($container->eta_job_site_history) }}">{{ $container->eta_job_site ? $container->eta_job_site : '-' }}</span>
                                                    </p>
{{--                                                    <div style="width:50%;text-align: right;float: left">--}}
{{--                                                        <p><i data-toggle="tooltip"--}}
{{--                                                              data-placement="top" title=""--}}
{{--                                                              data-original-title="Estimated production completion">EPC：</i>--}}
{{--                                                        </p>--}}
{{--                                                        <p><i>ETD Port：</i></p>--}}
{{--                                                        <p><i>ETA Port：</i></p>--}}
{{--                                                        <p><b><i>ETA Job Site：</i></b></p>--}}
{{--                                                    </div>--}}
{{--                                                    <div style="width:50%;text-align: left;float: right">--}}
{{--                                                        <p>{{ $batch->estimated_production_completion ? $batch->estimated_production_completion : '-' }}</p>--}}
{{--                                                        <p>{{ $batch->etd_port ? $batch->etd_port : '-' }}</p>--}}
{{--                                                        <p>{{ $batch->eta_port ? $batch->eta_port : '-' }}</p>--}}
{{--                                                        <p><b>{{ $container->eta_job_site ? $container->eta_job_site : '-' }}</b></p>--}}
{{--                                                    </div>--}}
                                                </td>
                                                <td style="vertical-align: middle">
                                                    <p><b>{{ $container->ata_job_site ? $container->ata_job_site : '-' }}</b></p>
{{--                                                    <div style="width:50%;text-align: right;float: left">--}}
{{--                                                        <p><i data-toggle="tooltip"--}}
{{--                                                              data-placement="top" title=""--}}
{{--                                                              data-original-title="Actual production completion">APC：</i>--}}
{{--                                                        </p>--}}
{{--                                                        <p><i>ATD Port：</i></p>--}}
{{--                                                        <p><i>ATA Port：</i></p>--}}
{{--                                                        <p><b><i>ATA Job Site：</i></b></p>--}}
{{--                                                    </div>--}}
{{--                                                    <div style="width:50%;text-align: left;float: right">--}}
{{--                                                        <p>{{ $batch->actual_production_completion ? $batch->actual_production_completion : '-' }}</p>--}}
{{--                                                        <p>{{ $batch->atd_port ? $batch->atd_port : '-' }}</p>--}}
{{--                                                        <p>{{ $batch->ata_port ? $batch->ata_port : '-' }}</p>--}}
{{--                                                        <p><b>{{ $container->ata_job_site ? $container->ata_job_site : '-' }}</b></p>--}}
{{--                                                    </div>--}}
                                                </td>
                                                <td>
                                                    @if($container->containers->count())
                                                        @foreach($container->containers as $linkContainer)
                                                        @if(!is_null($linkContainer->batch))
                                                                <p><a href="{{ url('/admin/batch/show/'.$linkContainer->batch->id.'?container='.$linkContainer->id) }}">{{ $linkContainer->batch->project->name }} - {{ getSequence($linkContainer->batch->sequence) }} {{ $linkContainer->batch->name ? ' : ' . $linkContainer->batch->name : '' }} - {{ $linkContainer->no }}</a></p>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>{{ $container->amount }}</td>
                                                <td>{{ optional($container->uSCarriers)->name }}</td>
                                                <td>{{ $container->remarks }}</td>
                                                <td>
                                                    <div class="grid-dropdown-actions dropdown">
                                                        <a href="#" style="padding: 0 10px;" class="dropdown-toggle"
                                                           data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </a>
                                                        <ul class="dropdown-menu"
                                                            style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                                            <li>
                                                                <a href="javascript:void(0);" class="container-edit"
                                                                   data-edit="{{ $container->id }}">Edit</a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="container-delete"
                                                                   force-delete="false"
                                                                   data-delete="{{ $container->id }}">Delete</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /.box-body -->

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Container</h4>
            </div>
            <form class="form-horizontal">
                <div class="modal-body">
                    <div class="fields-group">
                        <div class="col-sm-12">
                            <div class="form-group ">
                                <label class="asterisk">No.</label>
                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" class="form-control" placeholder="NO." id="no">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="">Type</label>
                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                    <select class="form-control" id="type">
                                        <option value="">Please choose</option>
                                        <option value="20GP">20GP</option>
                                        <option value="40GP">40GP</option>
                                        <option value="40HQ">40HQ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label class="">US Carrier</label>
                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                    <select class="form-control" id="u_s_carriers_id">
                                        <option value="">Please choose</option>
                                        @foreach($us_carriers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label class="">Amount</label>
                                <input class="form-control" id="amount"/>
                            </div>

                            <div class="form-group ">
                                <label class="">Remarks</label>
                                <textarea rows="5" class="form-control" id="remarks"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 estimated">
                                <div style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                    <div class="form-group  ">
                                        <label class="col-sm-4 control-label">
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Estimated production completion">EPC</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->estimated_production_completion }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  ">
                                        <label class="col-sm-4 control-label">ETD Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->etd_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ETA Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->eta_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  " style="margin-bottom: unset">
                                        <label class="col-sm-4 control-label">ETA Job Site</label>
                                        <div class="col-sm-7">
                                            <div class="input-group {{ $batch->eta_port?'':'readonly' }}">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control datetime-picker" id="eta_job_site" type="text" {{ $batch->eta_port?'':'readonly="true"' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="clear: both"></div>
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
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->actual_production_completion }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  ">
                                        <label class="col-sm-4 control-label">ATD Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->atd_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ATA Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->ata_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  " style="margin-bottom: unset">
                                        <label class="col-sm-4 control-label">ATA Job Site</label>
                                        <div class="col-sm-7">
                                            <div class="input-group {{ $batch->ata_port?'':'readonly' }}">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control datetime-picker" id="ata_job_site" type="text" {{ $batch->ata_port?'':'readonly="true"' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="container-submit" type="button" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>





<div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="myEditModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Container：<span id="container_no"></span></h4>
            </div>
            <form class="form-horizontal">
                <div class="modal-body">
                    <div class="fields-group">
                        <div class="col-sm-12">
                            <div class="form-group ">
                                <label class="asterisk">No.</label>
                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" class="form-control" placeholder="NO." id="edit_no">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="">Type</label>
                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                    <select class="form-control" id="edit_type">
                                        <option value="">Please choose</option>
                                        <option value="20GP">20GP</option>
                                        <option value="40GP">40GP</option>
                                        <option value="40HQ">40HQ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label class="">US Carrier</label>
                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                    <select class="form-control" id="edit_u_s_carriers_id">
                                        <option value="">Please choose</option>
                                        @foreach($us_carriers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label class="">Amount</label>
                                <input class="form-control" id="edit_amount"/>
                            </div>

                            <div class="form-group ">
                                <label class="">Remarks</label>
                                <textarea rows="5" class="form-control" id="edit_remarks"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 estimated">
                                <div style="background-color: #EEEEEE;padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                    <div class="form-group  ">
                                        <label class="col-sm-4 control-label">
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Estimated production completion">EPC</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->estimated_production_completion }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  ">
                                        <label class="col-sm-4 control-label">ETD Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->etd_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ETA Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->eta_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  " style="margin-bottom: unset">
                                        <label class="col-sm-4 control-label">ETA Job Site</label>
                                        <div class="col-sm-7">
                                            <div class="input-group {{ $batch->eta_port?'':'readonly' }}">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control datetime-picker" id="edit_eta_job_site" type="text" {{ $batch->eta_port?'':'readonly="true"' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="clear: both"></div>
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
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->actual_production_completion }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  ">
                                        <label class="col-sm-4 control-label">ATD Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->atd_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ATA Port</label>
                                        <div class="col-sm-7">
                                            <div class="input-group readonly">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" type="text" value="{{ $batch->ata_port }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group  " style="margin-bottom: unset">
                                        <label class="col-sm-4 control-label">ATA Job Site</label>
                                        <div class="col-sm-7">
                                            <div class="input-group {{ $batch->ata_port?'':'readonly' }}">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control datetime-picker" id="edit_ata_job_site" type="text" {{ $batch->ata_port?'':'readonly="true"' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="edit-container-submit" type="button" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control[readonly] {
        background-color: #eeeeee;
    }

    .readonly .input-group-addon {
        background-color: #eee;
    }

    td{
        vertical-align: middle !important;
    }

    .ll-warning{
        background-color: #f39c12 !important;
    }

    .ll-danger{
        background-color: #dd4b39 !important;
    }
</style>

<script>
    var can_submit = true;
    var can_edit_submit = true;

    $(function () {
        $('.popover-t').popover({
            html: true
        })

        @if($batch->eta_port)
            $('#eta_job_site').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true,
                'minDate' : '{{  $batch->eta_port }}'
            });

            $('#eta_job_site').val('')
        @endif

        @if($batch->ata_port)
            $('#ata_job_site').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true,
                'minDate' : '{{  $batch->ata_port }}',
                'maxDate' : '{{  \Carbon\Carbon::now()->toDateString() }}'
            });
        @endif
    })

    $('#container-submit').on('click', function () {

        if (can_submit) {
            can_submit = false;
            $('#container-submit').prop("disabled", true);
            $('#container-submit').html('Submit <i class="fa fa-spinner fa-spin"></i>');

            axios({
                method: 'post',
                url: '/admin/container/add',
                data: {
                    'batch_id': '{{ $batch->id }}',
                    'no': $('#no').val(),
                    'type': $('#type').val(),
                    'amount': $('#amount').val(),
                    'u_s_carriers_id': $('#u_s_carriers_id').val(),
                    'remarks': $('#remarks').val(),
                    'eta_job_site': $('#eta_job_site').val(),
                    'ata_job_site': $('#ata_job_site').val(),
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
                    $('#container-submit').removeProp("disabled");
                    $('#container-submit').html('Submit');
                    can_submit = true;
                }
            }).catch(error => {
                toastr.error(error.response.data.message);
                $('#container-submit').prop("disabled", false);
                $('#container-submit').html('Submit');
                can_submit = true;
            });
        }

    })



    $(document).on('click', '.container-delete', function () {
        let container_id = $(this).attr('data-delete')

        swal({
            title: 'Are you sure to delete this item ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {
                axios({
                    method: 'post',
                    url: '/admin/delete/container/' + container_id,
                }).then(response => {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        // $('.data_container_row_'+container_id).remove()
                        location.reload()
                    });
                })
            }
        })
    })


    $('.container-edit').on('click', function () {
        let container_id = $(this).attr('data-edit')
        axios({
            method: 'get',
            url: '/admin/container/info/' + container_id,
        }).then(response => {
            if (response.data.status) {
                $('#container_no').html(response.data.data.no)
                $("#edit_no").val(response.data.data.no)
                $("#edit_type").val(response.data.data.type)
                $("#edit_remarks").val(response.data.data.remarks)
                $("#edit_u_s_carriers_id").val(response.data.data.u_s_carriers_id)
                $("#edit_amount").val(response.data.data.amount)

                @if($batch->eta_port)
                $('#edit_eta_job_site').datetimepicker({
                    'format': 'YYYY-MM-DD',
                    'allowInputToggle': true,
                    'minDate' : '{{  $batch->eta_port }}'
                });
                $("#edit_eta_job_site").val(response.data.data.eta_job_site)
                @endif

                @if($batch->ata_port)
                $('#edit_ata_job_site').datetimepicker({
                    'format': 'YYYY-MM-DD',
                    'allowInputToggle': true,
                    'minDate' : '{{  $batch->ata_port }}',
                    'maxDate' : '{{  \Carbon\Carbon::now()->toDateString() }}'
                });
                $("#edit_ata_job_site").val(response.data.data.ata_job_site)
                @endif


                $('#edit-container-submit').attr('data-container-id', response.data.data.id)
                $('#myEditModal').modal('show')
            }
            console.log(response.data)

        })
    })



    $('#edit-container-submit').on('click', function () {
        let container_id = $('#edit-container-submit').attr('data-container-id')

        if (can_edit_submit) {
            can_edit_submit = false;
            $('#edit-container-submit').prop("disabled", true);
            $('#edit-container-submit').html('Update <i class="fa fa-spinner fa-spin"></i>');
            axios({
                method: 'post',
                url: '/admin/container/edit/' + container_id,
                data: {
                    'no': $("#edit_no").val(),
                    'type': $("#edit_type").val(),
                    'amount': $('#edit_amount').val(),
                    'u_s_carriers_id': $('#edit_u_s_carriers_id').val(),
                    'remarks': $("#edit_remarks").val(),
                    'eta_job_site': $("#edit_eta_job_site").val(),
                    'ata_job_site': $("#edit_ata_job_site").val(),
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
                    can_edit_submit = true;
                    $('#edit-container-submit').removeProp("disabled");
                    $('#edit-container-submit').html('Update');
                }
            }).catch(error => {
                toastr.error(error.response.data.message);
                can_edit_submit = true;
                $('#edit-container-submit').prop("disabled", false);
                $('#edit-container-submit').html('Update');
            });
        }

    })
</script>
