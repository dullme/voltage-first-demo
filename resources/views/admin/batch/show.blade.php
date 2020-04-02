<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Detail</h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ url('/admin/projects/'.$batch->poFactory->poClient->project->id) }}" class="btn btn-sm btn-default" title="List">
                            <i class="fa fa-mail-reply"></i><span class="hidden-xs"> Back</span>
                        </a>
                    </div>

                    {{--                        <div class="btn-group pull-right" style="margin-right: 5px">--}}
                    {{--                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">--}}
                    {{--                                <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;PO# Factory</span>--}}
                    {{--                            </button>--}}
                    {{--                        </div>--}}

                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="">

                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <p>Sequence：{{ $batch->sequence }}</p>
                            <p>Carrier：{{ $batch->carrier }}</p>
                            <p>B/L：{{ $batch->b_l }}</p>
                            <p>RMB：{{ $batch->rmb }}</p>
                            <p>Port Of Departure：{{ $batch->port_of_departure }}</p>
                            <p>S-method：{{ $batch->shipping_method }}</p>
                        </div>

                        <div class="col-lg-6">
                            <p>Shipment #：{{ $batch->name }}</p>
                            <p>Vessel：{{ $batch->vessel }}</p>
                            <p>Container No.：{{ $batch->container_no }}</p>
                            <p>Foreign Currency：{{ $batch->foreign_currency }}/{{ getForeignCurrencyType($batch->foreign_currency_type) }}</p>
                            <p>Destination Port：{{ $batch->destination_port }}</p>
                            <p>Remarks：{{ $batch->remarks }}</p>
                        </div>
                        <div class="col-lg-6">
                            <div style="background-color: rgb(238, 238, 238);    padding: 25px;border-radius: 4px;margin-bottom: 20px;">
                                <p>EPC：{{ $batch->estimated_production_completion }}</p>
                                <p>ETD Port：{{ $batch->etd_port }}</p>
                                <p>ETA Port：{{ $batch->eta_port }}</p>
                                <p>ETA Job Site：{{ $batch->eta_job_site }}</p>
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
                                <p>EPC：{{ $batch->actual_production_completion }}</p>
                                <p>ETD Port：{{ $batch->atd_port }}</p>
                                <p>ETA Port：{{ $batch->ata_port }}</p>
                                <p>ETA Job Site：{{ $batch->ata_job_site }}</p>
                                @if($batch->actual_production_completion && $batch->ata_job_site)
                                    <p>Time consuming：
                                        <span class="label label-default">
                                                {{ \Carbon\Carbon::parse($batch->actual_production_completion)->diffInDays($batch->ata_job_site) }} days
                                            </span>
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.box-body -->

            </div>
        </div>
    </div>

    <div class="col-md-12">
    </div>
</div>
