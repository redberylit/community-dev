<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('inventory', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('erp_grv_add_on_category_add_on_master');
echo head_page($title, false);


/*echo head_page('Add On Master', false); */?>

<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <!-- <div class="col-md-5">
        <table class="<?php //echo table_class(); ?>">
                <tr>
                    <td><span class="label label-success">&nbsp;</span> Confirmed /
                        Approved
                    </td>
                    <td><span class="label label-danger">&nbsp;</span> Not Confirmed
                        / Not Approved
                    </td>
                    <td><span class="label label-warning">&nbsp;</span> Refer-back
                    </td>
                </tr>
            </table>
    </div> -->
    <div class="col-md-9 text-center">
        &nbsp; 
    </div>
    <div class="col-md-3 text-right">
        <button type="button"   class="btn btn-primary pull-right" onclick="openAddOnModal()" ><i class="fa fa-plus"></i> <?php echo $this->lang->line('common_create_new');?> <!--Create New--> </button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="addonmaster_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="min-width: 85%"><?php echo $this->lang->line('common_description');?><!--Description--></th>
            <th style="min-width: 10%"><?php echo $this->lang->line('common_action');?><!--Action--></th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>

<div aria-hidden="true" role="dialog" tabindex="-1" id="addonmaster_model" class="modal fade" style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="addOnHeader"></h4>
            </div>
            <form role="form" id="addonmaster_form" class="form-horizontal">
                <input type="hidden" class="form-control" id="addonmasteredit" name="addonmasteredit">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo $this->lang->line('common_description');?><!--Description--> <?php required_mark(); ?></label>
                            <div class="col-sm-6">
                                <input type="text" name="description" id="description" class="form-control" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><?php echo $this->lang->line('common_save');?><!--Save--> <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> </button>
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $('.headerclose').click(function(){
            fetchPage('system/grv/erp_srp_addon_management','','GRV Addon Category');
        });
        addon_table();
        $('#addonmaster_form').bootstrapValidator({
            live: 'enabled',
            message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
            excluded: [':disabled'],
            fields: {
                description: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_description_is_required');?>.'}}},/*Description is required*/
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('Grv/save_addonmaster'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        HoldOn.close();
                        refreshNotifications(true);
                        if(data){
                            $("#addonmaster_model").modal("hide");
                            addon_table();
                            //fetchPage('system/srp_mu_suppliermaster_view','Test','Supplier Master');
                        }
                    }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    HoldOn.close();
                    refreshNotifications(true);
                }
            });
        });
    });


    function addon_table() {
        var Otable = $('#addonmaster_table').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "<?php echo site_url('Grv/fetch_addon_data'); ?>",
            "aaSorting": [[0, 'desc']],
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "category_id"},
                {"mData": "description"},
                {"mData": "action"},
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false
            }],
            "fnServerData": function (sSource, aoData, fnCallback) {
                $.ajax
                ({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function openAddOnModal(){
        $('#addonmaster_form')[0].reset();
        $('#addonmasteredit').val('');
        $('#addOnHeader').html('<?php echo $this->lang->line('erp_grv_add_on_category_add_new_addon');?>');/*Add New Add On*/
        $('#addonmaster_form').bootstrapValidator('resetForm', true);
        $("#addonmaster_model").modal({backdrop: "static"});

    }


    $("#addonmaster_model").on("hidden.bs.modal", function () {
        addon_table();
    });



    function openaddoneditmodel(id){
        $("#addonmaster_model").modal("show");
        $('#addonmasteredit').val(id);
        $('#addOnHeader').html('<?php echo $this->lang->line('erp_grv_add_on_category_edit_addon');?>');/*Edit Add On*/
        $.ajax(
            {
                async: false,
                type: 'post',
                dataType: 'json',
                data: {id:id},
                url: "<?php echo site_url('Grv/edit_addonmaster'); ?>",
                beforeSend: function () {

                },
                success: function (data) {
                    $('#description').val(data['description']);
                }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/

            }
            });
    }


    function deleteaddonmaster(id){

        swal({   title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('erp_grv_add_on_category_you_want_to_delete_this_file');?>",/*You want to delete this file!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>",
                closeOnConfirm: true },
            function(){
                $.ajax(
                    {
                        async: false,
                        type: 'post',
                        dataType: 'json',
                        data: {id:id},
                        url: "<?php echo site_url('Grv/delete_addonmaster'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            refreshNotifications(true);
                            if(data){
                                fetchPage('system/srp_addonmaster_view','Test','Add On Category');
                            }
                        }, error: function () {
                        alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                        stopLoad();
                        refreshNotifications(true);

                    }
                    });
                swal("Deleted!", "record has been deleted.", "success");
            });
    }



</script>