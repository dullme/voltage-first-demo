<template>
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
                            <input type="text"  id="epc-pickup"
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
                            <input type="text" id="etd-port-pickup" placeholder="ETD Port"
                                   class="form-control estimated-datetime-picker">
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
</template>

<script>
    require('../../../public/vendor/date-js/date-zh-CN')

    export default {
        data() {
            return {
                'estimated_production_completion' : '',
                'etd_port' : '',
                'eta_port' : '',
                'eta_job_site' : '',
            }
        },

        mounted() {
            $('.estimated-datetime-picker').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true,
            });

            $('.actual-datetime-picker').datetimepicker({
                'format': 'YYYY-MM-DD',
                'allowInputToggle': true,
                'maxDate': Date.today()
            });
        },

        methods:{
            start(){
                $('#epc-pickup').on('dp.change', (e) => {
                    this.estimated_production_completion = e.currentTarget.value;
                    $('#etd-port-pickup').data("DateTimePicker").maxDate(e.currentTarget.value);
                });
            }
        }
    }
</script>
