<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('config', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$tittle = 'Template Setup';
echo head_page($tittle, false);

$masterID = trim($this->input->post('page_id'));
$description = trim($this->input->post('data_arr'));

$type = [
    '' => 'Select a type',
    '2' => 'Header',
    '3' => 'Group Total',
];

$detData = get_fm_templateDetails($masterID);

//echo '<pre>'; print_r($detData); echo '</pre>';
?>

<style>
    legend{ font-size: 16px !important; }

    .mini-header{
        background: #596b8e;
        color: #f5f5f5;
        font-weight: bolder;
        font-size: 13px;
    }

    .sub1{
        background: #E8F1F4;
        color: #000080;
        font-weight: bolder;
        font-size: 13px;
    }

    .sub2{
        background: #ffffff;
        color: #000;
        font-weight: bolder;
        font-size: 13px;
    }

    .sortTxt{
        width: 50px;
        color: #000000;
    }
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/tabs.css'); ?>">
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row" style="margin-top: 15px"> </div>
<div class="masterContainer">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-condensed" style="background-color: #EAF2FA;">
                <tr>
                    <td width="85px"><?php echo $this->lang->line('common_description');?> : <!--Description--></td>
                    <td class="bgWhite" colspan="2">
                        <a href="#" data-type="text" data-placement="bottom" data-title="Edit Description"
                           data-pk="<?php echo $description?>" id="description_xEditable" data-value="<?php echo $description; ?>">
                            <?php echo $description?>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<br>

<div class="row" style="margin-top: 15px">
    <div class="col-md-12">
        <fieldset class="scheduler-border" style="">
            <legend class="scheduler-border">Details</legend>
            <div class="row" style="margin-bottom: 4px;">&nbsp;</div>

            <div class="row">
                <div class="col-sm-12" style="margin-bottom: 10px">
                    <button class="btn btn-primary pull-right" type="button" id=" " onclick="new_header_or_group()">
                        New Header/ Group Total
                    </button>
                    <button class="btn btn-primary pull-right" type="button" id=" " onclick="update_sortOrder()" style="margin-right: 10px;">
                        Update Sort Order
                    </button>
                </div>
                <?php echo form_open('', 'role="form" id="sortOrderUpdate_frm" autocomplete="off"'); ?>
                <div class="table-responsive">
                    <table id="detailMaster_table" class="<?php echo table_class(); ?>">
                        <thead>
                        <tr>
                            <th style="min-width: 3%">#</th>
                            <th style="min-width: 80%"><?php echo $this->lang->line('common_description');?><!--Description--></th>
                            <th style="min-width: 10%"><?php echo $this->lang->line('common_type');?></th>
                            <th style="min-width: 10%"><?php echo $this->lang->line('common_sort_order');?></th>
                            <th style="min-width: 2%"><?php echo $this->lang->line('common_action');?><!--Action--></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php echo  $detData;?>
                        </tbody>
                    </table>
                </div>
                <?php echo form_close(); ?>
            </div>
        </fieldset>
    </div>
</div>

<div class="modal fade" id="templateConfig_modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" id="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Item <span id=""></span></h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <?php echo form_open('', 'role="form" id="frm_add_new_item" autocomplete="off"'); ?>
                <input type="hidden" name="masterID" value="<?php echo $masterID; ?>">
                <input type="hidden" name="subMaster" id="subMaster" value="null">
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label><?php echo $this->lang->line('common_type');?></label>
                        <select name="itemType" id="itemType" class="form-control" onchange="displayAccountType()"> </select>
                    </div>
                    <div class="form-group col-sm-3 accountType-container">
                        <label>Account Type</label>
                        <select name="accountType" id="accountType" class="form-control">
                            <option value="I">Income</option>
                            <option value="E">Expense</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label><?php echo $this->lang->line('common_description');?><!--Description--></label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>
                    <div class="form-group col-sm-3">
                        <label><?php echo $this->lang->line('common_sort_order');?><!--Sort Order--></label>
                        <input type="number" name="sortOrder" id="sortOrder" class="form-control">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="" onclick="save_templateDetail()">
                    <?php echo $this->lang->line('common_save');?>
                </button>
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="glConfig_modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="">
            <?php echo form_open('', 'role="form" id="glConfig_from" autocomplete="off"'); ?>
            <input type="hidden" name="masterID" value="<?php echo $masterID; ?>">
            <input type="hidden" name="detID" id="detID">
            <input type="hidden" name="linkType" id="linkType" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <span id="gl-link-title"></span> </h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <div class="row">
                    <div class="col-sm-12" id="gl-config-container"> </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" onclick="save_reportTemplateLink()">
                    <?php echo $this->lang->line('common_save');?><!--Save-->
                </button>
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="editTitle_modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo form_open('', 'role="form" id="editTitle_form" autocomplete="off"'); ?>
            <input type="hidden" name="title_id" id="title_id">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Title Edit </h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <div class="row">
                    <div class="col-md-12" style="height: 45px;">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="title_str"> <?php echo $this->lang->line('common_title');?> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title_str" name="title_str">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" onclick="update_title()">
                    <?php echo $this->lang->line('common_save');?><!--Save-->
                </button>
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var masterID = '<?php echo $masterID; ?>';
    var description = <?php echo json_encode($description); ?>;

    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/ReportTemplate/erp_report_template_master', 'Test', 'Report Template');
        });
    });

    $('#description_xEditable').editable({
        url: '<?php echo site_url('ReportTemplate/update_templateDescription?masterID='.$masterID) ?>',
        send: 'always',
        ajaxOptions: {
            type: 'post',
            dataType: 'json',
            success: function (data) {
                myAlert(data[0], data[1]);
                if( data[0] == 's'){
                    var description_xEditable = $('#description_xEditable');
                    setTimeout(function (){
                        description_xEditable.attr('data-pk', description_xEditable.html());
                        description = $.trim(description_xEditable.html());
                    },400);

                }else{
                    var oldVal = $('#description_xEditable').data('pk');
                    setTimeout(function (){
                        $('#description_xEditable').editable('setValue', oldVal );
                    },300);
                }
            },
            error: function (xhr) {
                myAlert('e', xhr.responseText);
            }
        }
    });

    function delete_template_data(linkID, d_type){
        swal({
            title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
            text: "<?php echo $this->lang->line('common_you_want_to_delete');?>", /*You want to delete this record!*/
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?php echo $this->lang->line('common_delete');?>" /*Delete*/,
            cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>" /*cancel */
        },
        function () {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'linkID': linkID, 'd_type': d_type},
                url: "<?php echo site_url('ReportTemplate/delete_template_data'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        refresh_page();
                    }
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', errorThrown);
                }
            });
        });
    }

    function new_header_or_group(subID=null){
        $('#frm_add_new_item')[0].reset();
        $('.accountType-container').show();
        var dropData = '<option value="2">Header</option>';
        dropData += '<option value="3">Group Total</option>';

        if(subID != null){
            $('.accountType-container').hide();
            dropData = '<option value="1">Sub Category</option>';
            dropData += '<option value="3">Group Total</option>';
        }

        $('#subMaster').val(subID);

        $('#itemType').empty().append(dropData);
        $('#templateConfig_modal').modal('show');
    }

    function displayAccountType(){
        $('.accountType-container').hide();

        if($('#itemType').val() == 2){
            $('.accountType-container').show();
        }
    }

    function edit_title(id, dis){
        $('#title_id').val(id);
        $('#title_str').val(dis);
        $('#editTitle_modal').modal('show');
    }

    function sub_item_config(id, dis, reqType){
        $('#detID').val(id);
        $('#linkType').val(reqType);
        dis += (reqType == 'G')? ' - link sub category': ' - link GL';
        $('#gl-link-title').html(dis);
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {companyReportTemplateID: masterID, reqType: reqType, 'id': id},
            url: "<?php echo site_url('ReportTemplate/load_gl_data'); ?>",
            beforeSend: function () {
                startLoad();
                $('#gl-config-container').html('');
            },
            success: function (data) {
                stopLoad();
                $('#glConfig_modal').modal('show');
                $('#gl-config-container').html(data);
                setTimeout(function(){

                }, 400);
            }, error: function () {
                stopLoad();
                myAlert('e', 'Error in call back');
            }
        });
    }

    function update_title(){
        var data = $("#editTitle_form").serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('ReportTemplate/update_title'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $('#editTitle_modal').modal('hide');

                    refresh_page();
                }
            }, error: function () {
                myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
            }
        });
    }

    function save_reportTemplateLink(){
        var data = $("#glConfig_from").serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('ReportTemplate/save_reportTemplateLink'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $('#glConfig_modal').modal('hide');

                    refresh_page();
                }
            }, error: function () {
                myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
            }
        });
    }

    function save_templateDetail(){
        var data = $("#frm_add_new_item").serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('ReportTemplate/save_reportTemplateDetail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $('#templateConfig_modal').modal('hide');
                    refresh_page();
                }
            }, error: function () {
                stopLoad();
                myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>');
            }
        });
    }

    function get_configData(id, itemType){
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'id': id, 'itemType':itemType},
            url: "<?php echo site_url('ReportTemplate/load_templateConfig'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#templateConfig_modal').modal('show');
                $('#templateConfig-container').html(data);

            }, error: function () {
                stopLoad();
                myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>');
            }
        });
    }

    function update_sortOrder(){
        var data = $("#sortOrderUpdate_frm").serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('ReportTemplate/update_sortOrder'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    refresh_page();
                }
            }, error: function () {
                stopLoad();
                myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>');
            }
        });
    }

    function refresh_page(){
        setTimeout(function(){
            setupTemplate( masterID, description );
        }, 400);
    }

</script>



<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 7/30/2018
 * Time: 9:49 AM
 */