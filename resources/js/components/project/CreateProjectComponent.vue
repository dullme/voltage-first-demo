<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title">Create</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="/admin/projects" class="btn btn-sm btn-default" title="List">
                                <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                            </a>
                        </div>

                    </div>
                </div>

                <form class="form-horizontal">

                    <div class="box-body">

                        <div class="fields-group">

                            <div class="col-md-12">
                                <div class="form-group  ">
                                    <label class="col-sm-2 asterisk control-label">Clients</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="client_id" name="client_id" v-model="project_form.client_id">
                                            <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group  ">
                                    <label class="col-sm-2 asterisk control-label">Contact</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="contact_id" name="contacts[]" multiple="multiple">
                                            <option v-for="contact in contacts" :value="contact.id">{{ contact.name }}</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group  ">
                                    <label class="col-sm-2 asterisk control-label">Name</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input required="1" type="text" v-model="project_form.name" id="name" name="name" value="" class="form-control" placeholder="Input Name">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  ">
                                    <label class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input required="1" type="text" v-model="project_form.address" id="address" name="address" value="" class="form-control" placeholder="Input Address">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div style="clear: both"></div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <button v-on:click="saveProject" type="button" class="btn btn-primary"
                                        :disabled="loading.project">Submit <i v-if="loading.project"
                                                                              class="fa fa-spinner fa-spin"></i></button>
                            </div>
                        </div>
                    </div>


                    <!-- /.box-footer -->
                </form>

            </div>
        </div>
    </div>
</template>

<script src="./resources/js/helps.js"></script>
<script>
    export default {
        data() {
            return {
                clients:[],
                contacts:[],
                loading:{
                    project:false
                },
                project_form:{
                    client_id:'',
                    contacts:[],
                    name:'',
                    address:'',
                }
            }
        },
        mounted(){
            $('#client_id').select2({
                placeholder : 'Please choose',
                allowClear: true, //选中项可清空
            }).on('change', (e) => {
                this.project_form[e.target.name] = e.currentTarget.value
                axios.get('/admin/contact-list/'+e.currentTarget.value).then(response => {
                    this.contacts = response.data.data
                })
                $('#contact_id').val(null).trigger('change');
            })

            $('#contact_id').select2({
                placeholder : 'Please choose',
                allowClear: true, //选中项可清空
            }).on('change', (e) => {
                this.project_form.contacts = $('#contact_id').val()
            }).on('select2:opening select2:closing', function( event ) {
                var $searchfield = $(this).parent().find('.select2-search__field');
                $searchfield.prop('disabled', true);
            });

        },
        created() {
            axios.get('/admin/client-list').then(response => {
                this.clients = response.data.data
            })
        },
        methods: {
            saveProject(){
                this.loading.project = true
                axios({
                    method: 'post',
                    url: '/admin/projects',
                    data: this.project_form
                }).then(response => {
                    if (response.data.status) {
                        swal(
                            "SUCCESS",
                            response.data.message,
                            'success'
                        ).then(function () {
                            location.href='/admin/projects'
                        })
                    }
                    // this.loading.project = false
                }).catch(error => {
                    this.loading.project = false
                    toastr.error(error.response.data.message)
                });
            },
        },
    }
</script>

<style>
    .mb15{
        margin-bottom: 15px;
    }
    .popover{
        max-width:unset !important;
        width: 100%;
    }
    .form-control[readonly]{
        background-color: #fbfbfb;
    }
    .input-group span.asterisk:before {
        content: "* ";
        color: red;
    }
    .select2{
        width:100% !important;
    }
    .checked{
        color: #dd4b39 !important;
        border-color: #dd4b39 !important;
    }
    .border-red{
        border-color: #dd4b39 !important;
    }
    .remark-list {
        margin-bottom: 10px;
    }
</style>
