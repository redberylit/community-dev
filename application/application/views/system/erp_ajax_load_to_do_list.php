<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('dashboard', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$priority_arr = all_priority_new_drop();
$current_date = format_date($this->common_data['current_date']);
?>
<div class="nav-tabs-custom">
    <div class="box-tools pull-right">
        <button type="button" onclick="openToDoListModal<?php echo $userDashboardID; ?>()" title="Add List" class="btn btn-box-tool"><i
                class="fa fa-plus-square-o"></i>
        </button>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#todolistview<?php echo $userDashboardID ?>" onclick="load_to_do_list_view<?php echo $userDashboardID; ?>()" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('dashboard_to_do_list');?><!--To Do List--> &nbsp;&nbsp;<span style="font-size: 0.7em;">(<?php echo date('d-m-Y'); ?>)</a>
        </li>
        <li class=""><a href="#todolisthistry<?php echo $userDashboardID ?>" onclick="load_to_do_list_History<?php echo $userDashboardID; ?>()"
                        data-toggle="tab" aria-expanded="false"><?php echo $this->lang->line('dashboard_history');?><!--History--></a>
        </li>

    </ul>
    <div class="tab-content" style="max-height: calc(45vh - 45px);overflow-y: auto;">
        <div class="tab-pane active" id="todolistview<?php echo $userDashboardID ?>">

        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="todolisthistry<?php echo $userDashboardID ?>">


        </div>
        <!-- /.tab-pane -->
    </div>
    <div class="overlay" id="overlay12<?php echo $userDashboardID; ?>"><i class="fa fa-refresh fa-spin"></i></div>
    <!-- /.tab-content -->
</div>


<div class="modal fade" id="toDoListModal<?php echo $userDashboardID; ?>" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 40%">
        <div class="modal-content" id="" style="background-color: #f3f3ec;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('dashboard_to_do');?><!--To Do--> <i class="fa fa-list-alt"></i></span></h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <form name="to_do_list_form" id="to_do_list_form<?php echo $userDashboardID ?>" method="post">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="Link"><?php echo $this->lang->line('common_date');?><!--Date--></label>
                        <input type="text" class="form-control datepicker" value="<?php echo $current_date; ?>"
                               id="startDate" name="startDate"
                               placeholder="Date">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="Link"><?php echo $this->lang->line('common_time');?><!--Time--></label>
                        <input type="text" class="form-control timrpicker" value="10:00 AM" id="startTime"
                               name="startTime"
                               placeholder="Time">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="description"><?php echo $this->lang->line('common_description');?><!--Description--></label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="Priority"><?php echo $this->lang->line('dashboard_priority');?><!--Priority--></label>
                        <?php echo form_dropdown('priority', $priority_arr, '', 'class="form-control select2" id="priority"'); ?>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                <button type="submit" class="btn btn-primary btn-sm" onclick="save_to_do_list()" id="btnSave"><?php echo $this->lang->line('common_save');?><!--Save-->
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    load_to_do_list_view<?php echo $userDashboardID; ?>();

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    }).on('changeDate', function (ev) {
        $('#to_do_list_form<?php echo $userDashboardID; ?>').bootstrapValidator('revalidateField', 'startDate');
        $(this).datepicker('hide');
    });


    $('.timrpicker').timepicker({
        minuteStep: 1,
        defaultTime: '10:00 AM',
        template: 'dropdown',
        appendWidgetTo: 'body',
        showSeconds: false
    });

    function openToDoListModal<?php echo $userDashboardID; ?>() {
        $('#description').val('');
        $('#priority').val('');
        $('#to_do_list_form<?php echo $userDashboardID; ?>').bootstrapValidator('resetForm', true);
        $('#toDoListModal<?php echo $userDashboardID; ?>').modal("show");
    }

    function save_to_do_list() {
        var data = $('#to_do_list_form<?php echo $userDashboardID; ?>').serializeArray();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "<?php echo site_url('Finance_dashboard/save_to_do_list'); ?>",
            data: data,
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $('#toDoListModal<?php echo $userDashboardID; ?>').modal('hide');
                    //myAlert('s', 'Message: ' + data[1]);
                    load_to_do_list_view<?php echo $userDashboardID; ?>();
                } else if (data[0] == 'e') {
                    //myAlert('e', 'Message: ' + data[1]);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', 'Message: ' + "Select Widget");
            }
        });

    }

    function deletetodoList<?php echo $userDashboardID ?>(id,userDashboardID) {
        if (id) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this Record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: "<?php echo site_url('Finance_dashboard/deletetodoList'); ?>",
                        data: {autoId: id},
                        cache: false,
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data[0] == 's') {
                                $('#list_' + id+'_'+userDashboardID).hide();
                                myAlert('s', 'Message: ' + data[1]);
                            } else if (data[0] == 'e') {
                                myAlert('e', 'Message: ' + data[1]);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            stopLoad();
                            myAlert('e', 'Message: ' + "Select Widget");
                        }
                    });
                });
        }
        ;
    }

    function changeDone<?php echo $userDashboardID; ?>(id, userDashboardID ) {
        var checked = '';
        if ($('#donechk_' + id+'_'+userDashboardID).is(':checked')) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('dashboard_you_want_to_complete_this_record');?>",/*You want to complete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",/*YES*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var checked = -1;
                        //$('#list_' + id).addClass("done");
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: "<?php echo site_url('Finance_dashboard/check_to_do_list'); ?>",
                            data: {autoId: id, checked: checked},
                            cache: false,
                            beforeSend: function () {
                                startLoad();
                            },
                            success: function (data) {
                                stopLoad();
                                if (data[0] == 's') {
                                    $('#list_' + id+'_'+userDashboardID).hide();
                                    myAlert('s', 'Message: ' + data[1]);
                                } else if (data[0] == 'e') {
                                    myAlert('e', 'Message: ' + data[1]);
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                stopLoad();
                                myAlert('e', 'Message: ' + "Error");
                            }
                        });
                    } else {
                        $('#donechk_' + id+'_'+userDashboardID).prop('checked', false)
                    }
                });
        } else {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('dashboard_you_want_to_complete_this_record');?>",/*You want to complete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",/*YES*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var checked = 0;
                        //$('#list_' + id).removeClass("done");
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: "<?php echo site_url('Finance_dashboard/check_to_do_list'); ?>",
                            data: {autoId: id, checked: checked},
                            cache: false,
                            beforeSend: function () {
                                startLoad();
                            },
                            success: function (data) {
                                stopLoad();
                                if (data[0] == 's') {
                                    $('#list_' + id+'_'+userDashboardID).hide();
                                    //location.reload();
                                    myAlert('s', 'Message: ' + data[1]);
                                } else if (data[0] == 'e') {
                                    myAlert('e', 'Message: ' + data[1]);
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                stopLoad();
                                myAlert('e', 'Message: ' + "Error");
                            }
                        });
                    } else {
                        $('#donechk_' + id+'_'+userDashboardID).prop('checked', true)
                    }
                });
        }
    }

    function load_to_do_list_History<?php echo $userDashboardID; ?>() {
        var id = 0;
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo site_url('Finance_dashboard/load_to_do_list_History'); ?>",
            data: {autoId: id,userDashboardID:<?php echo $userDashboardID; ?>},
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#todolisthistry<?php echo $userDashboardID ?>").html(data);
            },
            error: function () {
                stopLoad();
                //myAlert('e', 'Message: ' + "Error");
            }
        });
    }

    function load_to_do_list_view<?php echo $userDashboardID; ?>() {
        var id = 0;
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo site_url('Finance_dashboard/load_to_do_list_view'); ?>",
            data: {autoId: id,userDashboardID:<?php echo $userDashboardID; ?>},
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#todolistview<?php echo $userDashboardID ?>").html(data);
            },
            error: function () {
                stopLoad();
                //myAlert('e', 'Message: ' + "Error");
            }
        });
    }


</script>
