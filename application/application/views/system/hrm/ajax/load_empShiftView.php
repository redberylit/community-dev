<?php
$floors_arr = floors_drop();
$leaveGroup_arr = leaveGroup_drop();
$overTimeGroup = over_time_group();
$shift_arr = fetch_shifts();

$date_format_policy = date_format_policy();
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('employee_master', $primaryLanguage);

?>
<style>
.alert-warning1{
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #faebcc;
    margin: 0px 0px 10px !important;

}

.action-div:hover{
    cursor: pointer !important;
}
</style>

    <div class="row">
        <?php if($attendanceData['leaveAdjustmentStatus'] == 0 ){ ?>
        <div class="col-md-12">
            <div class="alert alert-warning1">
                <strong>Warning!</strong> Leave group has been changed for this employee. Please do the leave adjustment to get the accurate leave entitlement for the employee.
            </div>
        </div>
        <?php  } ?>

        <div class="col-md-6">
            <fieldset>
                <legend><?php echo $this->lang->line('emp_attendance_details');?><!--Attendance Details--></legend>

                <?php echo form_open('', 'role="form" id="attendanceData_form"  autocomplete="off"'); ?>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="floorID"><?php echo $this->lang->line('emp_attendance_floor');?><!--Floor--></label>
                    <?php echo form_dropdown('floorID', $floors_arr, $attendanceData['floorID'], 'class="form-control select2" id="floorID" '); ?>
                </div>

                <div class="form-group col-sm-6 col-xs-6">
                    <label for="empMachineID"><?php echo $this->lang->line('emp_attendance_machine_id');?><!--Machine ID--></label>
                    <input type="text" class="form-control number" id="empMachineID" name="empMachineID" value="<?php echo $attendanceData['empMachineID']; ?>">
                </div>

                <div class="form-group col-sm-6 col-xs-6">
                    <label for="leaveGroupID"><?php echo $this->lang->line('emp_attendance_leave_group');?><!--Leave Group--> <?php required_mark(); ?></label>
                    <div class="input-group">
                        <div class="input-group-addon" onclick="loadLeaveHistory()" title="<?php echo $this->lang->line('hrms_leave_group_change_history'); ?>"><i class="fa fa-info-circle"></i></div>
                        <?php echo form_dropdown('leaveGroupID', $leaveGroup_arr, $attendanceData['leaveGroupID'], 'class="form-control select2" id="leaveGroupID" '); ?>
                    </div>
                </div>

                <div class="form-group col-sm-6 col-xs-6">
                    <label for="overTimeGroup"><?php echo $this->lang->line('emp_attendance_over_time_group');?><!--Over Time Group--></label>
                    <?php echo form_dropdown('overTimeGroup', $overTimeGroup, $attendanceData['overTimeGroup'], 'class="form-control select2" id="overTimeGroup" '); ?>
                </div>

                <div class="form-group col-sm-4 col-xs-6">
                    <label for="isCheckIn">&nbsp;</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="isCheckIn" id="isCheckIn" value="1" <?php echo ($attendanceData['isCheckin'] == 1)? 'checked': ''; ?>>
                        </span>
                        <input type="text" class="form-control" disabled="" value="<?php echo $this->lang->line('emp_attendance_check_in');?>" >
                    </div>
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="row" style="margin-right: 15px; margin-left: 15px;">
                    <hr style="margin: 10px 0px 10px;">
                    <button type="button" class="btn btn-primary btn-sm pull-right" onclick="save_attendanceData()"> <?php echo $this->lang->line('emp_save');?><!--Save--> </button>
                    <input type="hidden" name="isLeaveGroupChangeConfirmed" id="isLeaveGroupChangeConfirmed" value="0">
                </div>
                <?php echo form_close(); ?>
            </fieldset>
        </div>

        <div class="col-md-6">
            <fieldset>
                <legend><?php echo $this->lang->line('emp_attendance_shift_details');?><!--Shift Details--></legend>
                <div class="row" style="margin-bottom: 2%">
                    <div class="col-sm-12">
                        <div class="col-sm-6 col-xs-6">
                            <table class="table table-bordered table-striped table-condensed ">
                                <tbody>
                                <tr>
                                    <td><span class="label label-success" style="padding: 0px 5px ;font-size: 100%;">&nbsp;</span>
                                        <?php echo $this->lang->line('emp_is_active');?>
                                    </td>
                                    <td><span class="label label-danger" style="padding: 0px 5px ;font-size: 100%;">&nbsp;</span> <?php echo $this->lang->line('emp_in_active');?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-6 col-xs-6 pull-right">
                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="openShift_modal()"><i
                                    class="fa fa-plus-square"></i>&nbsp; <?php echo $this->lang->line('emp_add');?><!--Add-->
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="margin-top: 1%;">
                    <table id="empShiftsTB" class="<?php echo table_class(); ?>">
                        <thead>
                        <tr>
                            <th style="min-width: 5%">#</th>
                            <th style="width: auto"><?php echo $this->lang->line('emp_attendance_shift_description');?><!--Shift Description--></th>
                            <th style="width: auto"><?php echo $this->lang->line('emp_attendance_start_date');?><!--Start Date--></th>
                            <th style="width: auto"><?php echo $this->lang->line('emp_attendance_end_date');?><!--End Date--></th>
                            <!--<th style="width: auto">Status</th>-->
                            <th style="width: 35px"></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>

    <div style="margin-top: 2%">&nbsp;</div>

    <div class="modal fade" id="leaveHistory_modal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title"><?php echo $this->lang->line('hrms_leave_group_change_history'); ?></h3>
                </div>
                <div role="form" id="" class="form-horizontal" autocomplete="off">
                    <div class="modal-body">
                        <table class="<?php echo table_class() ?>" id="leave-group-change-history-tb">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>#</th>
                                <th><?php echo $this->lang->line('emp_attendance_leave_group'); ?></th>
                                <th><?php echo $this->lang->line('emp_changed_date'); ?></th>
                                <th><?php echo $this->lang->line('emp_leave_adjustment_status'); ?></th>
                                <th> </th>
                            </tr>
                            </thead>

                            <tbody> </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="leaveAccrual_modal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close hideOnLoad" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="title-leaveAccrual_modal"></h3>
                </div>
                <form id="leaveAccrual_form" class="form-horizontal" autocomplete="off">
                    <div class="modal-body" id="leaveAccrual_data">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm" onclick="leave_accrual_process()" id="leave_accrual_process_btn"><?php echo $this->lang->line('emp_save');?><!--Save--></button>
                        <button data-dismiss="modal" class="btn btn-default btn-sm hideOnLoad" type="button"><?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="empShift_modal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title shiftModal-title" id="myModalLabel"></h4>
                </div>

                <form role="form" id="empShift_form" class="form-horizontal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"
                                           for="shiftID"><?php echo $this->lang->line('emp_attendance_shift');?><!--Shift--> <?php required_mark(); ?></label>
                                    <div class="col-sm-6">
                                        <select name="shiftID" id="shiftID" class="form-control select2">
                                            <option></option>
                                            <?php
                                            foreach ($shift_arr as $key => $row) {
                                                echo '<option value="' . $row['shiftID'] . '" > ' . $row['Description'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="startDate"><?php echo $this->lang->line('emp_attendance_start_date');?><!--Start
                                        Date--> <?php required_mark(); ?></label>
                                    <div class="col-sm-6">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="startDate" value="" id="startDate" class="form-control dateFields"
                                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="endDate"><?php echo $this->lang->line('emp_attendance_end_date');?><!--End
                                        Date--> <?php required_mark(); ?></label>
                                    <div class="col-sm-6">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="endDate" value="" id="endDate" class="form-control dateFields"
                                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="editID" name="editID" value="0">
                        <button type="submit" class="btn btn-primary btn-sm actionBtn" id="save-btn"><?php echo $this->lang->line('emp_save');?><!--Save--></button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang->line('emp_Close');?><!--Close--></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $('.select2').select2();
        var empShift_form = $('#empShift_form');
        var empShift_modal = $('#empShift_modal');

        Inputmask().mask(document.querySelectorAll("input"));
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';


        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy
        });

        $(document).ready(function () {
            /*$('.dateFields').datepicker({
                format: 'yyyy-mm-dd'
            }).on('changeDate', function (ev) {
                var thisDate = $(this).attr('id');
                empShift_form.bootstrapValidator('revalidateField', thisDate);
                $(this).datepicker('hide');
            });*/

            $('.select2').select2();

            load_empShifts();

            empShift_form.bootstrapValidator({
                live: 'enabled',
                message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
                excluded: [':disabled'],
                fields: {
                    shiftID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('emp_shift_is_required');?>.'}}}/*Shift is required*/
                    //startDate: {validators: {notEmpty: {message: 'Start date is required.'}}},
                    //endDate: {validators: {notEmpty: {message: 'End date is required.'}}}
                }
            }).on('success.form.bv', function (e) {
                    $('.submitBtn').prop('disabled', false);
                    e.preventDefault();
                    var $form = $(e.target);
                    var postData = $form.serializeArray();
                    var urlReq = $form.attr('action');

                    var empID = $('#updateID').val();
                    postData.push({'name': 'empID', 'value': empID});

                    $.ajax({
                        type: 'post',
                        url: urlReq,
                        data: postData,
                        dataType: 'json',
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert(data[0], data[1]);
                            $('#updateBtn').attr('disabled',true);
                            if (data[0] == 's') {
                                empShift_modal.modal('hide');
                                var editID = $('#editID').val();
                                var masterID = ($.isNumeric(editID)) ? editID : data[2];
                                load_empShifts(masterID);
                            }
                        },
                        error: function () {
                            stopLoad();
                            myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                        }
                    });

                });
        });

        function load_empShifts(selectedRowID=null) {

           var soc = $('#empShiftsTB').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "StateSave": true,
                "sAjaxSource": "<?php echo site_url('Employee/fetch_empShifts'); ?>",
                "aaSorting": [[1, 'asc']],
                "fnInitComplete": function () {

                },
                "fnDrawCallback": function (oSettings) {

                    var tmp_i = oSettings._iDisplayStart;
                    var iLen = oSettings.aiDisplay.length;

                    var x = 0;
                    for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);

                        if (parseInt(oSettings.aoData[x]._aData['autoID']) == selectedRowID) {
                            var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                            $(thisRow).addClass('dataTable_selectedTr');
                        }

                        x++;
                    }
                    if(fromHiarachy==1){
                        soc.column( 4 ).visible( false );
                    }
                },
                "aoColumns": [
                    {"mData": "autoID"},
                    {"mData": "Description"},
                    {"mData": "startDate"},
                    {"mData": "endDate"},
//                    {"mData": "status"},
                    {"mData": "edit"}
                ],
                "fnServerData": function (sSource, aoData, fnCallback) {
                    var empID = $('#updateID').val();
                    aoData.push({'name': 'empID', 'value': empID});
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

        function openShift_modal() {

            empShift_form.attr('action', '<?php echo site_url('Employee/save_empShift'); ?>');
            empShift_form[0].reset();
            empShift_form.bootstrapValidator('resetForm', true);
            $('.shiftModal-title').text('<?php echo $this->lang->line('emp_attendance_shift');?>');

            empShift_modal.modal({backdrop: "static"});
        }

        function editEmp_shift(det) {
            var table = $('#empShiftsTB').DataTable();
            var thisRow = $(det);
            var details = table.row(thisRow.parents('tr')).data();

            empShift_form.attr('action', '<?php echo site_url('Employee/update_empShift'); ?>');
            empShift_form[0].reset();
            empShift_form.bootstrapValidator('resetForm', true);
            $('.shiftModal-title').text('<?php echo $this->lang->line('emp_attendance_shift');?>');

            $('#editID').val(details.autoID);
            $('#startDate').val(details.startDate);
            $('#endDate').val(details.endDate);
            $('#shiftID').val(details.shiftID);
            $('#shiftID').change();

            empShift_modal.modal({backdrop: "static"});
        }

        function deleteEmp_shift(det) {
            var table = $('#empShiftsTB').DataTable();
            var thisRow = $(det);
            var details = table.row(thisRow.parents('tr')).data();

            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        url: "<?php echo site_url('Employee/delete_empShift'); ?>",
                        type: 'post',
                        dataType: 'json',
                        data: {'hidden-id': details.autoID},
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert(data[0], data[1]);
                            if (data[0] == 's') {
                                load_empShifts()
                            }
                        }, error: function () {
                            stopLoad();
                            myAlert('e', 'error');
                        }
                    });
                }
            );

        }

        function save_attendanceData(){
            var formData = $('#attendanceData_form').serializeArray();
            $('#isLeaveGroupChangeConfirmed').val(0);
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: formData,
                url: '<?php echo site_url('/Employee/save_attendanceData/?empID='.$empID);?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();

                    if (data[0] == 'm') {
                        leaveGroupChangeConformation();
                    }else{
                        myAlert(data[0], data[1]);

                        if(data['isLeaveGroupChangeConfirmed'] == 1){
                            newLeaveAdjustment( data['changeHistoryID'], 1 );
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function leaveGroupChangeConformation(){
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_change_leave_group');?>",/*You want to delete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_change');?>",/*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $('#isLeaveGroupChangeConfirmed').val(1);
                    save_attendanceData();
                }
            );
        }
        
        function newLeaveAdjustment(id, isFromLoad=0) {
            $('#title-leaveAccrual_modal').text('<?php echo $this->lang->line('emp_master_new_leave_adjustment'); ?>');
            $('#leave_accrual_process_btn').css('display', 'none');

            $('.hideOnLoad').show();
            if(isFromLoad == 1){ /* If first load after group change close buttons will be hide */
                $('.hideOnLoad').hide();
            }

            $.ajax({
                async: true,
                url: "<?php echo site_url('Employee/create_leaveAdjustment_in_leave_group_change_view'); ?>",
                type: 'post',
                dataType: 'json',
                data: {id:id},
                beforeSend: function () {
                    startLoad();
                    $('#leaveAccrual_data').html('');
                },
                success: function (data) {
                    stopLoad();

                    if (data[0] == 's') {
                        $('#leaveHistory_modal').modal('hide');
                        $('#leave_accrual_process_btn').css('display', 'initial');

                        $('#leaveAccrual_modal').modal('show');
                        $('#leaveAccrual_data').html(data['view']);
                    }
                    else if (data[0] == 'e') {
                        myAlert(data[0], data[1]);
                    }
                    else{
                        myAlert('e', 'error');
                    }

                }, error: function () {
                    stopLoad();
                    myAlert('e', 'error');
                }
            });
        }

        function getLeaveAdjustment(id){
            $('#title-leaveAccrual_modal').text('<?php echo $this->lang->line('emp_master_leave_adjustment'); ?>');
            $('#leave_accrual_process_btn').css('display', 'none');

            $.ajax({
                async: true,
                url: "<?php echo site_url('Employee/view_leaveAdjustment_in_leave_group_change'); ?>",
                type: 'post',
                dataType: 'html',
                data: {id:id},
                beforeSend: function () {
                    startLoad();
                    $('#leaveAccrual_data').html('');
                },
                success: function (data) {
                    stopLoad();

                    $('#leaveAccrual_modal').modal('show');
                    $('#leaveAccrual_data').html(data);

                }, error: function () {
                    stopLoad();
                    myAlert('e', 'error');
                }
            });
        }

        function leave_accrual_process(){
            var postData = $('#leaveAccrual_form').serializeArray();
            $.ajax({
                async: true,
                url: "<?php echo site_url('Employee/leaveAdjustment_in_leave_group_change'); ?>",
                type: 'post',
                dataType: 'json',
                data: postData,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();

                    if (data[0] == 's') {
                        myAlert(data[0], data[1]);
                        $('#leaveAccrual_modal').modal('hide');
                        setTimeout(function(){
                            fetch_attendance();
                        }, 300);

                    }
                    else if (data[0] == 'e') {
                        myAlert(data[0], data[1]);
                    }
                    else{
                        myAlert('e', 'error');
                    }

                }, error: function () {
                    stopLoad();
                    myAlert('e', 'error');
                }
            });
        }

        function skipLeaveAdjustment(adjID){

            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",
                    text: "<?php echo $this->lang->line('leave_adjustment_skip_you_want_to_skip_adjustment');?>",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",
                    cancelButtonText: "<?php echo $this->lang->line('common_no');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        url: "<?php echo site_url('Employee/skipLeaveAdjustment'); ?>",
                        type: 'post',
                        dataType: 'json',
                        data: {adjID: adjID},
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert(data[0], data[1]);
                            if (data[0] == 's') {
                                $('#leaveHistory_modal').modal('hide');
                                setTimeout(function(){
                                    fetch_attendance();
                                }, 300);
                            }
                        }, error: function () {
                            stopLoad();
                            myAlert('e', 'error');
                        }
                    });
                }
            );
        }

        if(fromHiarachy == 1){
            $('.btn ').addClass('hidden');
            $('.navdisabl ').removeClass('hidden');
            $('.form-control:not([type="search"], #parentCompanyID)').attr('disabled', true);
        }

        function loadLeaveHistory(){
            $('#leaveHistory_modal').modal('show');

            var leave_group_change_history_tb = $('#leave-group-change-history-tb').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "StateSave": true,
                "sAjaxSource": "<?php echo site_url('Employee/leave_group_change_history'); ?>",
                "aaSorting": [[0, 'desc']],
                "columnDefs": [ { "targets": [0], "visible": false }],
                "fnInitComplete": function () {

                },
                "fnDrawCallback": function (oSettings) {

                    var tmp_i = oSettings._iDisplayStart;
                    var iLen = oSettings.aiDisplay.length;

                    var x = 0;
                    for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                        x++;
                    }
                    if(fromHiarachy==1){
                        leave_group_change_history_tb.column( 4 ).visible( false );
                    }
                },
                "aoColumns": [
                    {"mData": "id"},
                    {"mData": "id"},
                    {"mData": "description"},
                    {"mData": "crDate"},
                    {"mData": "adjStatus"},
                    {"mData": "action"}
                ],
                "fnServerData": function (sSource, aoData, fnCallback) {
                    var empID = $('#updateID').val();
                    aoData.push({'name': 'empID', 'value': empID});
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

        $(".modal").on("hidden.bs.modal", function () {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
            setTimeout(function(){
                $('body').css('padding-right', '0px');
            }, 200);
        });

        $('.table-row-select tbody').on('click', 'tr', function () {
            $('.table-row-select tr').removeClass('dataTable_selectedTr');
            $(this).toggleClass('dataTable_selectedTr');
        });

        $('.number').keypress(function (event) {

            if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        });
    </script>


<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 1/9/2017
 * Time: 2:48 PM
 */