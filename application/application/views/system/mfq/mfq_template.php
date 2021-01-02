<?php echo head_page('WORKFLOW PROCESS SETUP', false); ?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-12">
        <div class=" pull-right">
            <button type="button" data-text="Add" id="btnAdd" onclick="fetchPage('system/mfq/mfq_template_create',null,'Add Workflow','MFQ');"
                    class="btn btn-sm btn-primary">
                <i class="fa fa-plus" aria-hidden="true"></i> Add
            </button>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="mfq_template" class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th style="min-width: 5%">&nbsp;</th>
                    <th style="min-width: 12%">DESCRIPTION</th>
                    <th style="min-width: 12%">INDUSTRY</th>
                    <th style="min-width: 5%">&nbsp;</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<?php echo footer_page('Right foot', 'Left foot', false); ?>

<div class="modal" tabindex="-1" role="dialog" aria-labelledby="Work Flow Modal" data-backdrop="static"
     data-keyboard="false"
     id="workflowTemplateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times text-red"></i></span></button>
                <h4 class="modal-title" id="modal_title_category">Work Flow Template </h4>
            </div>
            <form id="frm_mfq_template">
                <div class="modal-body">
                    <input type="hidden" value="0" id="workFlowTemplateID" name="workFlowTemplateID">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="title">Industry</label>
                        </div>
                        <div class="form-group col-sm-6">
                        <span class="input-req"
                              title="Required Field">
                            <?php echo form_dropdown('industryID', get_all_mfq_industry(), '', 'class="form-control" id="industryID"  required'); ?>
                            <span class="input-req-inner"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="title">Description </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <span class="input-req" title="Required Field">
                                <input type="text" name="description" id="description"
                                       class="form-control" required>
                                <span class="input-req-inner"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Save
                    </button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var oTable;
    var oTable2;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_template', 'Test', 'Workflow');
        });
        template();
    });

    function addWorkFlowTemplate() {
        $('#frm_mfq_workflow_template')[0].reset();
        $('#frm_mfq_workflow_template').bootstrapValidator('resetForm', true);
        $('#workFlowTemplateID').val('');
        $('#workflowTemplateModal').modal();
    }

    function template() {
        oTable = $('#mfq_template').DataTable({
            "ordering": false,
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": false,
            "sAjaxSource": "<?php echo site_url('MFQ_Template/fetch_template'); ?>",
            //"aaSorting": [[1, 'desc']],
            language: {
                paginate: {
                    previous: '‹‹',
                    next: '››'
                }
            },
            "fnDrawCallback": function (oSettings) {
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },

            "aoColumns": [
                {"mData": "templateMasterID"},
                {"mData": "templateDescription"},
                {"mData": "industryTypeDescription"},
                {"mData": "edit"}
            ],
            //"columnDefs": [{"targets": [2], "orderable": false}],
            "fnServerData": function (sSource, aoData, fnCallback) {
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

    function edit_work_flow_template(workFlowTemplateID){
        $('#workFlowTemplateID').val(workFlowTemplateID);
        $.ajax({
            type: 'post',
            dataType: 'json',
            data:{workFlowTemplateID:workFlowTemplateID},
            url: "<?php echo site_url('MFQ_Template/edit_work_flow_template'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data) {
                    $('#frm_mfq_workflow_template').bootstrapValidator('resetForm', true);
                    $('#description').val(data['description']);
                    $('#workFlowID').val(data['workFlowID']).change();
                    $('#pageNameLink').val(data['pageNameLink']);
                    $('#workflowTemplateModal').modal('show');
                }
            },
            error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function delete_workflow_master(templateMasterID) {
        if (templateMasterID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to delete this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'templateMasterID': templateMasterID},
                        url: "<?php echo site_url('MFQ_Template/delete_workflow_master'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert(data[0], data[1]);
                            if (data[0] == 's') {
                                oTable.draw();
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }
</script>