<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('config', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('config_approval_setup');
echo head_page($title, true);

/*echo head_page('Approval Setup',true);*/
$employee_arr       = array(''=>'Select Employee');//all_employee_drop();
$group_arr          = all_group_drop();
$document_code_arr  = all_document_code_drop();
$employee_filter_arr = all_employees_drop(false);
$documents_drop_arr = all_document_code_drop(false);
$DocAotoAppYN = getPolicyValues('DAA', 'All');
?>
<div id="filter-panel" class="collapse filter-panel">
    <div class="row">
        <div class="form-group col-sm-4">
            <label for="supplierPrimaryCode"> <?php echo $this->lang->line('config_document_id');?><!--Document ID--></label><br>
            <?php echo form_dropdown('documentID[]', $documents_drop_arr, '', 'class="form-control" id="documentID_filter" onchange="approvaluser_table()" multiple="multiple"'); ?>
        </div>
        <div class="form-group col-sm-4">
            <label for="supplierPrimaryCode"> <?php echo $this->lang->line('common_employee_name');?><!--Employee Name--></label><br>
            <?php echo form_dropdown('employeeID[]', $employee_filter_arr, '', 'class="form-control" id="employeeID_filter" onchange="approvaluser_table()" multiple="multiple"'); ?>
        </div>
        <div class="form-group col-sm-4">
            <button type="button" class="btn btn-primary pull-right"
                    onclick="clear_all_filters()"><i class="fa fa-paint-brush"></i> <?php echo $this->lang->line('common_clear');?><!--Clear-->
            </button>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-4">
        <table class="<?php echo table_class(); ?>">
                <tr>
                    <td><span class="label label-success">&nbsp;</span> <?php echo $this->lang->line('common_active');?><!--Active--> </td>
                    <td><span class="label label-danger">&nbsp;</span>   <?php echo $this->lang->line('config_not_active');?><!--Not Active--> </td>
                </tr>
            </table>
    </div>
    <div class="col-md-5 text-center">
        &nbsp; 
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right" onclick="approvel_user_model()"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('config_create_approval_user');?><!--Create Approval User--></button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="approvaluser_table" class="<?php echo table_class(); ?>">
        <thead>
            <tr>
                <th style="min-width: 10px">#</th>
                <th style="width: 120px"><?php echo $this->lang->line('config_document_id');?><!--Document ID--></th>
                <th style=""><?php echo $this->lang->line('common_document');?><!--Document--></th>
                <th style="min-width: 20%"><?php echo $this->lang->line('common_employee_name');?><!--Employee Name--></th>
                <th style="min-width: 20%"><?php echo $this->lang->line('config_level_no');?><!--Level No--></th>
                <th style="min-width: 10%"><?php echo $this->lang->line('common_action');?><!--Action--></th>
            </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>
<div aria-hidden="true" role="dialog" id="approvel_user_model" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title"><?php echo $this->lang->line('config_add_new_user_approval');?><!--Add New User Approval--></h5>
            </div>
            <form role="form" id="approvel_user_form" class="form-horizontal">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" id="approvalUserID" name="approvalUserID">
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo $this->lang->line('common_document');?><!--Document--></label>
                            <div class="col-sm-5">
                                <?php echo form_dropdown('documentid', $document_code_arr, '','class="form-control select2" id="documentid" required'); ?>
                                <!-- <input type="text" class="form-control form1" id="" name="documentid"> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo $this->lang->line('config_level_no');?><!--Level No--></label>
                            <div class="col-sm-5">
                                <?php echo form_dropdown('levelno',array('' => 'Select Level'), '','class="form-control select2" id="levelno" required'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo $this->lang->line('common_group');?><!--Group--></label>
                            <div class="col-sm-5">
                                <?php echo form_dropdown('userGroupID', $group_arr, '','class="form-control select2" id="userGroupID"  onchange="fetch_emploee_using_group(this.value)"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo $this->lang->line('common_employee');?><!--Employee--></label>
                            <div class="col-sm-5">
                                <?php echo form_dropdown('employeeid', $employee_arr, '','class="form-control select2" id="employeeid"'); ?>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('config_save_approva_user');?><!--Save Approval User--> </button>
                    <button data-dismiss="modal" class="btn btn-default" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.headerclose').click(function(){
            fetchPage('system/srp_approvalusers_view','Test','Approval User');
        });

        $('.select2').select2();

        $('#employeeID_filter').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });

        $('#documentID_filter').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });

        approvaluser_table();
        $('#approvel_user_form').bootstrapValidator({
            live: 'enabled',
            message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
            excluded: [':disabled'],
            fields: {
                //levelno: {validators: {notEmpty: {message: '<?php //echo $this->lang->line('config_level_no_is_required');?>.'}}},/*Level No is required*/
                documentid: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_document_is_required');?>.'}}},/*Document is required*/
                //employeeid: {validators: {notEmpty: {message: '<?php // echo $this->lang->line('common_employee_is_required');?>.'}}},/*Employee is required*/
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name' : 'document', 'value' : $('#documentid option:selected').text()});
            data.push({'name' : 'employee', 'value' : $('#employeeid option:selected').text()});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('Approvel_user/save_approveluser'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    refreshNotifications(true);
                    stopLoad();
                    if(data==true){
                        $("#approvel_user_model").modal("hide");
                        approvaluser_table();
                    }
                }, 
                error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });
        $("#documentid").change(function (){
            fetch_approval_level($(this).val());
        });
    });

    function approvaluser_table() {
        var Otable = $('#approvaluser_table').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Approvel_user/load_approvel_user'); ?>",
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
                {"mData": "approvalUserID"},
                {"mData": "documentID"},
                {"mData": "document"},
                {"mData": "employeeName"},
                {"mData": "levelNo"},
                {"mData": "action"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "documentID", "value": $("#documentID_filter").val()});
                aoData.push({"name": "employeeID", "value": $("#employeeID_filter").val()});
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function approvel_user_model(){
        $('#approvel_user_form')[0].reset();
        $("#documentid").val(null).trigger("change");
        $("#levelno").val(null).trigger("change");
        $("#userGroupID").val(null).trigger("change");
        $('#approvel_user_form').bootstrapValidator('resetForm', true);
        $("#approvel_user_model").modal({backdrop: "static"});
        $('#approvalUserID').val('');
    }

    function openapprovelusermodel(id){
        approvel_user_model();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'id':id},
            url: "<?php echo site_url('Approvel_user/edit_approveluser'); ?>",
            success: function (data) {
                $('#approvalUserID').val(id);
                $('#documentid').val(data['documentID']).change();
                $('#userGroupID').val(data['groupID']).change();
                setTimeout(function(){ $('#levelno').val(data['levelNo']).change(); $('#employeeid').val(data['employeeID']).change();}, 500);

            }, 
            error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/

            }
        });
    }

    function deleteapproveluser(id){
        swal({   title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('config_you_want_to_delete_this_file');?>",/*You want to delete this file!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                closeOnConfirm: true },

            function(){
                $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {id:id},
                        url: "<?php echo site_url('Approvel_user/delete_approveluser'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            refreshNotifications(true);
                            if(data){
                                fetchPage('system/srp_approvalusers_view','Test','Approval User');
                            }
                        }, error: function () {
                        alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                        stopLoad();
                        refreshNotifications(true);
                    }
                });
            });
    }

    function fetch_emploee_using_group(id){
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'id':id},
            url: "<?php echo site_url('Approvel_user/fetch_emploee_using_group'); ?>",
            beforeSend: function () {
                //startLoad();
            },
            success: function (data) {
                $('#employeeid').empty();
                var mySelect = $('#employeeid');
                mySelect.append($('<option></option>').val('').html('Select Employee'));
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['EIdNo']).html(text['ECode'] + ' | ' + text['Ename1']+ ' | ' + text['Ename2']));
                    });
                }            
            }, 
            error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
            }
        });
    }

    function fetch_approval_level(documentID){
        $.ajax({
            async: true,
            type: 'POST',
            dataType: 'json',
            data: {'documentID':documentID},
            url: "<?php echo site_url('Approvel_user/fetch_approval_level'); ?>",
            beforeSend: function () {
                //startLoad();
            },
            success: function (data) {
                $('#levelno').empty();
                var mySelect = $('#levelno');
                mySelect.append($('<option></option>').val('').html('Select Level'));
                if (!jQuery.isEmptyObject(data)) {
                    console.log(data.approvalLevel);
                    for (i = 1; i <= data.approvalLevel; i++) {
                        mySelect.append($('<option></option>').val(i).html("Level - "+i));
                    }
                    <?php if($DocAotoAppYN==1){
                    ?>
                    mySelect.append($('<option></option>').val(0).html('No Approval'));
                    <?php } ?>

                }
            },
            error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
            }
        });
    }

    function clear_all_filters(){
        $('#documentID_filter').multiselect2('deselectAll', false);
        $('#documentID_filter').multiselect2('updateButtonText');

        $('#employeeID_filter').multiselect2('deselectAll', false);
        $('#employeeID_filter').multiselect2('updateButtonText');

        approvaluser_table();
    }
</script>