<?php echo head_page('JOB', false);
$date_format_policy = date_format_policy();
$current_date = current_format_date();?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-5">
        <table class="<?php echo table_class() ?>">
            <tr>
                <td><span class="label label-success">&nbsp;</span>
                    Approved
                </td>
                <td><span class="label label-danger">&nbsp;</span> Not Approved
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-center">
        <?php echo form_dropdown('approvedYN', array('0' => 'Pending', '1' => 'Approved'), '', 'class="form-control" id="approvedYN" required onchange="job_table()"'); ?>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="job_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="min-width: 10%">CODE</th>
            <th style="min-width: 20%">DETAILS</th>
            <th style="min-width: 5%">LEVEL</th>
            <th style="min-width: 5%">STATUS</th>
            <th style="min-width: 10%">ACTION</th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<div class="modal fade" id="jv_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Job Approval</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-1">
                    <!-- Nav tabs -->
                    <ul class="zx-nav zx-nav-tabs zx-tabs-left zx-vertical-text">
                        <li id="po_attachement_approval_Tabview_v" class="active"><a href="#Tab-home-v"
                                                                                     data-toggle="tab"
                                                                                     onclick="tabView()">View</a></li>
                        <li id="po_attachement_approval_Tabview_a"><a href="#Tab-profile-v" data-toggle="tab"
                                                                      onclick="tabAttachement()">Attachment</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-11">
                    <div class="zx-tab-content">
                        <div class="zx-tab-pane active" id="Tab-home-v">
                            <div id="confirm_body"></div>
                            <hr>
                            <form class="form-horizontal" id="job_approval_form">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Status</label>

                                    <div class="col-sm-4">
                                        <?php echo form_dropdown('po_status', array('' => 'Please Select', '1' => 'Approved', '2' => 'Referred-back'), '', 'class="form-control" id="po_status" required'); ?>
                                        <input type="hidden" name="Level" id="Level">
                                        <input type="hidden" name="documentApprovedID" id="documentApprovedID">
                                        <input type="hidden" name="workProcessID" id="workProcessID2">
                                        <input type="hidden" name="jobcardID" id="jobcardID">
                                        <input type="hidden" name="maxLevel" id="maxLevel" value="0">
                                    </div>
                                </div>
                                <div class="form-group" id="financeDate">
                                    <label for="inputPassword3" class="col-sm-2 control-label">Finance Date</label>

                                    <div class="col-sm-8">
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                <div class='input-group date filterDate' id="">
                                                    <input type='text' class="form-control"
                                                           name="postingFinanceDate"
                                                           id="postingFinanceDate"
                                                           value="<?php echo $current_date; ?>"
                                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"/>
                                                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">Comments</label>

                                    <div class="col-sm-8">
                                        <textarea class="form-control" rows="3" name="comments"
                                                  id="comments"></textarea>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane hide" id="Tab-profile-v">
                            <div class="table-responsive">
                                <span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span>
                                &nbsp <strong>Job Attachments</strong>
                                <br><br>
                                <table class="table table-striped table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File Name</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="po_attachment_body" class="no-padding">
                                    <tr class="danger">
                                        <td colspan="5" class="text-center">No Attachment Found</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">&nbsp;
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
    $(document).ready(function () {
        $('.filterDate').datetimepicker({
            useCurrent: false,
            format: date_format_policy
        });
        $('#financeDate').hide();
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_job_approval', '', 'Job');
        });
        job_table();

        $('#job_approval_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                status: {validators: {notEmpty: {message: 'Status is required.'}}},
                Level: {validators: {notEmpty: {message: 'Level Order Status is required.'}}},
                documentApprovedID: {validators: {notEmpty: {message: 'Document Approved ID is required.'}}}
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
                url: "<?php echo site_url('MFQ_Job/save_job_approval'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        $("#jv_modal").modal('hide');
                        job_table();
                        $form.bootstrapValidator('disableSubmitButtons', false);
                    }
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });
    });

    function job_table() {
        var Otable = $('#job_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('MFQ_Job/fetch_job_approval'); ?>",
            "aaSorting": [[1, 'desc']],
            "fnInitComplete": function () {

            },
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
                {"mData": "workProcessID"},
                {"mData": "documentCode"},
                {"mData": "detail"},
                {"mData": "level"},
                {"mData": "approved"},
                {"mData": "edit"}
            ],
            //"columnDefs": [{"targets": [2], "orderable": false}],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "approvedYN", "value": $("#approvedYN :checked").val()});
                //aoData.push({ "name": "subcategory","value": $("#subcategory").val()});
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

    function fetch_approval(workProcessID, documentApprovedID, Level, jobID,finalApproval,financeDate) {
        if (workProcessID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'workProcessID': workProcessID, jobCardID: jobID, 'html': true},
                url: "<?php echo site_url('MFQ_Job/fetch_job_approval_print'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $("#jv_modal").modal({backdrop: "static"});
                    $('#confirm_body').html(data);
                    $('#workProcessID2').val(workProcessID);
                    $('#jobcardID').val(jobID);
                    $('#documentApprovedID').val(documentApprovedID);
                    $('#Level').val(Level);
                    $('#comments').val('');
                    if(Level == finalApproval){
                        $('#maxLevel').val(1);
                        $("#financeDate").val(financeDate).change();
                        $('#financeDate').show();
                    }else{
                        $('#maxLevel').val(0);
                        $('#financeDate').show();
                    }
                    //job_attachment_view_modal('JOB', workProcessID);
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    stopLoad();
                    alert('An Error Occurred! Please Try Again.');
                    refreshNotifications(true);
                }
            });
        }
    }

    function job_attachment_view_modal(documentID, documentSystemCode) {
        $("#Tab-profile-v").removeClass("active");
        $("#Tab-home-v").addClass("active");
        $("#po_attachement_approval_Tabview_a").removeClass("active");
        $("#po_attachement_approval_Tabview_v").addClass("active");
        if (documentSystemCode) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("Attachment/fetch_attachments"); ?>',
                dataType: 'json',
                data: {'documentID': documentID, 'documentSystemCode': documentSystemCode,'confirmedYN': 0},
                success: function (data) {
                    $('#po_attachment_body').empty();
                    $('#po_attachment_body').append('' +data+ '');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#ajax_nav_container').html(xhr.responseText);
                }
            });
        }
    }

    function tabAttachement() {
        $("#Tab-profile-v").removeClass("hide");
    }

    function tabView() {
        $("#Tab-profile-v").addClass("hide");
    }


</script>