<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('crm_tasks');
echo head_page($title, false);

/*echo head_page('Tasks', false);*/
$this->load->helper('crm_helper');
$supplier_arr = all_supplier_drop(false);
$date_format_policy = date_format_policy();
$category_arr_filter = load_all_categories(false);
$status_arr_filter = all_task_status(false);
$assignees_arr_filter = load_all_employees_taskFilter(false);
$isgroupadmin = crm_isGroupAdmin();
$admin = crm_isSuperAdmin();
$cuurentuser = current_userID();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<style>
    .task-cat-upcoming {
        border-bottom: solid 1px #f76f01;
    }

    .task-cat-upcoming-label {
        display: inline;
        float: left;
        color: #f76f01;
        font-weight: bold;
        margin-top: 5px;
        font-size: 15px;
    }

    .taskcount {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #eee;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 1px 5px 0 6px;
        line-height: 14px;
        margin-left: 8px;
        margin-top: 9px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .custome {
        width: 60%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
    }

    .customestyle {
        width: 60%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
        margin-left: -46%
    }

    .customestyle2 {
        width: 80%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
        margin-left: -94%
    }

    .customestyle3 {
        width: 80%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
        margin-left: -94%
    }

    #search_cancel img {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;

        padding: 4px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .textClose {
        text-decoration: line-through;
        font-weight: 500;
        text-decoration-color: #3c8dbc;
    }

</style>
<div id="filter-panel" class="collapse filter-panel">
</div>
<div class="row">
    <div class="col-md-5">
        &nbsp;
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right"
                onclick="fetchPage('system/crm/create_new_task',null,'<?php echo $this->lang->line('crm_add_new_task');?>','CRM','CRM');"><i
                class="fa fa-plus"></i> <?php echo $this->lang->line('crm_create_task');?>
        </button><!--Add New Task--><!--Create Task-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box-body no-padding">
            <div class="row" style="margin-top: 2%;">
                <div class="col-sm-4" style="margin-left: 2%;">
                    <div class="col-sm-2">
                        <div class="mailbox-controls">
                            <div class="skin skin-square">
                                <div class="skin-section extraColumns"><input id="isAttended" type="checkbox"
                                                                              data-caption="" class="columnSelected"
                                                                              name="isActive" value="1"><label
                                        for="checkbox">&nbsp;</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="box-tools">
                            <div class="has-feedback">
                                <input name="searchTask" type="text" class="form-control input-sm"
                                       placeholder="<?php echo $this->lang->line('crm_search_task');?>"
                                       id="searchTask" onkeypress="startMasterSearch()"><!--Search Task-->
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="col-sm-3">
                        <?php echo form_dropdown('Category', $category_arr_filter, '', 'class="form-control" id="filter_categoryID"  onchange="startMasterSearch()"'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo form_dropdown('statusID', $status_arr_filter, '', 'class="form-control" id="filter_statusID"  onchange="startMasterSearch()"'); ?>
                    </div>
                    <div class="col-sm-3">
                        <?php echo form_dropdown('Priority', array('' =>  $this->lang->line('crm_priority')/*'Priority'*/, '1' => $this->lang->line('crm_low')/*'Low'*/, '2' => $this->lang->line('crm_medium')/*'Medium'*/, '3' => $this->lang->line('crm_high')/*'High'*/), '', 'class="form-control" id="filter_Priority" onchange="startMasterSearch()"'); ?>
                    </div>
                    <div class="col-sm-3">
                        <?php echo form_dropdown('assigneesID', $assignees_arr_filter, '', 'class="form-control" id="filter_assigneesID"  onchange="startMasterSearch()"'); ?>
                    </div>
                    <div class="col-sm-1 hide" id="search_cancel">
                    <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clearSearchFilter()"><img
                                src="<?php echo base_url("images/crm/cancel-search.gif") ?>"></a></span>
                    </div>
                </div>
            </div>
            <div id="taskMaster_view"></div>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/crm/task_management', '', 'Tasks');
        });

        Inputmask().mask(document.querySelectorAll("input"));

        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

        getTaskManagement_tableView();

    });

    $('#searchTask').bind('input', function(){
        startMasterSearch();
    });

    function getTaskManagement_tableView() {
        var searchTask = $('#searchTask').val();
        var category = $('#filter_categoryID').val();
        var status = $('#filter_statusID').val();
        var priority = $('#filter_Priority').val();
        var assignees = $('#filter_assigneesID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'searchTask': searchTask, status: status, priority: priority, assignees: assignees, category:category},
            url: "<?php echo site_url('crm/load_taskManagement_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#taskMaster_view').html(data);
                $(".taskprojecteditview").hide();
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function delete_task(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*/!*Are you sure?*!/*/
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
                    type: 'post',
                    dataType: 'json',
                    data: {'taskID': id},
                    url: "<?php echo site_url('Crm/delete_task'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0],data[1]);
                         if(data[0] == 's')
                        {
                            getTaskManagement_tableView();
                        }

                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getTaskManagement_tableView();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('#searchTask').val('');
        $('#filter_statusID').val('');
        $('#filter_Priority').val('');
        $('#filter_assigneesID').val('');
        $('#filter_categoryID').val('');
        getTaskManagement_tableView();
    }
    function edit_task(taskid,createdUserIDtask,assignuser)
    {
        if((createdUserIDtask == '<?php echo $cuurentuser ?>') || ('<?php echo $admin['isSuperAdmin']?>' == 1) || ('<?php echo $isgroupadmin['adminYN']?>' == 1) || (assignuser == 1))
        {
            fetchPage('system/crm/create_new_task',taskid,'Edit Task','CRM','CRM')
        }else
        {
           myAlert('w','You do not have the permission to edit');
        }


    }

</script>