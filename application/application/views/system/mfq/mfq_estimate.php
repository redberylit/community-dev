<?php echo head_page('ESTIMATE', false);
?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
<style>
    .sidebar-mini {
        padding-right: 0 !important;
    }
</style>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-5">

    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" style="margin-right: 17px;" class="btn btn-primary pull-right"
                onclick="fetchPage('system/mfq/mfq_add_new_estimate',null,'Add Estimate','EST');"><i
                    class="fa fa-plus"></i> New Estimate
        </button>
    </div>
</div>
<div id="">
    <div class="table-responsive" style="margin-top: 10px">
        <table id="estimate_table" class="table table-striped table-condensed" width="100%">
            <thead>
            <tr>
                <th style="min-width: 2%">#</th>
                <th style="min-width: 12%">ESTIMATE CODE</th>
                <th style="min-width: 12%">ESTIMATE DATE</th>
                <th style="min-width: 12%">CUSTOMER</th>
                <th style="min-width: 12%">DESCRIPTION</th>
                <th style="min-width: 12%;text-align: center">APPROVAL STATUS</th>
                <th style="min-width: 12%;text-align: center">ESTIMATE STATUS</th>
                <th style="min-width: 6%">&nbsp;</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="estimate_print_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Estimate</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group col-sm-3 md-offset-2">
                            <label class="title">Revisions : </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <select onchange="changeVersion(this.value)" class="form-control"
                                    id="est-versionLevel"></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="print">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="job_modal" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 35%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="jobHeader">Job</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="jobContent"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="generateJob()">Generate Job</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Email_modal" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 35%">
        <form method="post" id="Send_Email_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <input type="hidden" name="estimateMasterID" id="estimateMasterID" value="">
                    <h4 class="modal-title" id="EmailHeader">Emails</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="emailContent">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="append_data_nw">
                        <div class="row removable-div-nw" id="mr_1" style="margin-top: 10px;">
                            <div class="col-sm-1">
                            </div>
                            <div class="col-sm-8">
                                <input type="email" name="emailNW[]" id="emailNW" class="form-control"
                                       placeholder="@email" style="margin-left: -10px">
                            </div>
                            <div class="col-sm-1 remov-btn">
                                <button type="button" class="btn btn-primary btn-xs pull-right" id="btn_add_emailNW"
                                        onclick="add_more_nw_mail()"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-sm-1 remove-tdnw">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="loadEmailView()">View</button>
                    <button type="button" class="btn btn-primary" onclick="SendEstimateMail()">Send Email</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="additional_order_modal" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <form id="frm_additionalformdetail" class="frm_additionalformdetail" method="post">
            <input type="hidden" name="estimateMasterID" id="estimateMasterID2" value="">
            <input type="hidden" name="mfqCustomerAutoID" id="est-mfqCustomerAutoID2" value="">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="">Additional Order Detail </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">Exclusions </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <input type="text" name="exclusions" id="exclusions"
                                                       class="form-control">
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">Design Code </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input type="text" name="designCode" id="designCode"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">Design Edition </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input type="text" name="designEditor" id="designEditor"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">QA/QC documentation </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <?php echo form_dropdown('qcqtDocumentation', array('' => 'Select', '1' => 'Yes', '2' => 'No'), '', 'class="form-control" id="qcqtDocumentation"'); ?>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Material Certification </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <?php echo form_dropdown('materialCertificateID[]', fetch_materialCertificate(), '', 'class="form-control" id="materialCertificateID" multiple="multiple"');
                                                ?>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Material Certification Comment </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                    <textarea class="form-control" id="materialCertificationComment"
                                                              name="materialCertificationComment" rows="3"></textarea>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">Department</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                        <span class="input-req" title="Required Field">
                        <?php echo form_dropdown('mfqSegmentID', fetch_mfq_segment(), '', 'class="form-control select2" id="mfqSegmentID"');
                        ?><span class="input-req-inner"></span></span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">PO Number</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input type="text" name="poNumber" id="poNumber"
                                                   class="form-control">

                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Submission of Engineering Drawings </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                <?php echo form_dropdown('engineeringDrawings', array('' => 'Select', '1' => 'Yes', '2' => 'No'), '', 'class="form-control" id="engineeringDrawings"'); ?>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Submission of Engineering Drawings Comment </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                    <textarea class="form-control" id="engineeringDrawingsComment"
                                                              name="engineeringDrawingsComment" rows="3"></textarea>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Submission of ITP </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                <?php echo form_dropdown('submissionOfITP', array('' => 'Select', '1' => 'Yes', '2' => 'No'), '', 'class="form-control" id="submissionOfITP"'); ?>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Submission of ITP Comment </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                    <textarea class="form-control" id="itpComment"
                                                              name="itpComment" rows="3"></textarea>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title">Warehouse</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                         <span class="input-req" title="Required Field">
                        <?php echo form_dropdown('mfqWarehouseAutoID', all_mfq_warehouse_drop(), '', 'class="form-control select2" id="mfqWarehouseAutoID"'); ?>
                             <span class="input-req-inner"></span></span>

                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-4">
                                            <label class="title"> Order Status </label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="input-req" title="Required Field">
                                                <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                <?php echo form_dropdown('orderStatus', array('' => 'Select', '1' => 'Pending', '2' => 'Confirmed & Received'), '', 'class="form-control" id="orderStatus"'); ?>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="form-group col-sm-2 md-offset-2">
                                            <label class="title"> Scope of Work </label>
                                        </div>
                                        <div class="form-group col-sm-9">
                                            <div class="input-req" title="Required Field">
                                                    <textarea class="form-control richtext" id="scopeOfWork"
                                                              name="scopeOfWork" rows="3"></textarea>
                                                <span class="input-req-inner"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="jobOrder_print_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Job Order</h4>
            </div>
            <div class="modal-body">
                <form id="frm_jobOrder_print">
                    <input type="hidden" name="estimateMasterID" id="estimateMasterID_jobOrderEdit">
                    <input type="hidden" name="workProcessID" id="workProcessID_jobOrderEdit">

                    <div class="row">
                        <div class="col-md-6">
                            <div id="">
                                <div class="form-group">
                                    <label for="usergroup" class="col-sm-3 control-label">User Group</label>
                                    <div class="col-sm-9">
                                        <?php echo form_dropdown('usergroup[]', all_mfq_usergroup_drop(false), '', 'class="form-control" id="usergroup" multiple="multiple"'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 10px">
                            <div id="jobOrder_print">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="load_jobOrder_save()">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="quotation_print_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Job Order</h4>
            </div>
            <div class="modal-body">
                <form id="">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="quotation_print">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="job_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Job View</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="job_print">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">&nbsp;
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script src="<?php echo base_url('plugins/tinymce/tinymce.min.js'); ?>"></script>

<script type="text/javascript">
    var oTable;
    var param = [];
    $(document).ready(function () {
        $(".select2").select2();

        $('#materialCertificateID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            allSelectedText: 'All Selected'
        });

        $('#usergroup').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            allSelectedText: 'All Selected'
        });

        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_estimate', 'Test', 'Estimate');
        });
        estimate_table();

        $('.modal').on('hidden.bs.modal', function (e) {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });

        $('.modal').on('shown.bs.modal', function () {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });

        $('#frm_additionalformdetail').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                exclusions: {validators: {notEmpty: {message: 'Exclusions is required.'}}},
                engineeringDrawings: {validators: {notEmpty: {message: 'Submission of Engineering Drawings is required.'}}},
                engineeringDrawingsComment: {validators: {notEmpty: {message: 'Submission of Engineering Drawings comment is required.'}}},
                submissionOfITP: {validators: {notEmpty: {message: 'Submission of ITP  is required.'}}},
                itpComment: {validators: {notEmpty: {message: 'Submission of ITP Comment is required.'}}},
                qcqtDocumentation: {validators: {notEmpty: {message: 'QA/QC documentation is required.'}}},
                materialCertificateID: {validators: {notEmpty: {message: 'Material certification is required.'}}},
                //scopeOfWork: {validators: {notEmpty: {message: 'Scope of work is required.'}}},
                mfqSegmentID: {validators: {notEmpty: {message: 'Segment is required.'}}},
                mfqWarehouseAutoID: {validators: {notEmpty: {message: 'Warehouse is required.'}}},
                orderStatus: {validators: {notEmpty: {message: 'Order status is required.'}}},
                materialCertificationComment: {validators: {notEmpty: {message: 'Material Certification Comment is required.'}}},
                /*poNumber: {validators: {notEmpty: {message: 'PO Number is required.'},callback: {
                            callback: function(value, validator, $field) {
                                if (value === '') {
                                    return true;
                                }
                                // Check po valistion
                                if ($("#orderStatus").val() != 2 && value != '') {
                                    return {
                                        valid: false,
                                        message: 'PO Number is required'
                                    };
                                }
                                return true;
                            }}
                    }
                }*/
            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            tinymce.triggerSave();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            /* param[0]["mfqSegmentID"] = $("#mfqSegmentID").val();
             param[0]["mfqWarehouseAutoID"] = $("#mfqWarehouseAutoID").val();*/
            //data2 = data.concat(param);
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('MFQ_Estimate/save_additional_order_detail'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    if (data[0] == 's') {
                        $('#additional_order_modal').modal('hide');
                        oTable.draw();
                        /*                        setTimeout(function () {
                         fetchPage('system/mfq/mfq_job_create', data[2], 'Add Job', 'EST');
                         }, 500);*/
                        swal("New Job Created!", data[1], "success");
                        load_jobOrder(data[2], data[3]);
                    } else {
                        myAlert(data[0], data[1]);
                        $('.btn-primary').prop('disabled', false);
                    }
                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });

        });

        tinymce.init({
            selector: ".richtext",
            height: 200,
            browser_spellcheck: true,
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
            ],
            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft code",

            menubar: false,
            toolbar_items_size: 'small',

            style_formats: [{
                title: 'Bold text',
                inline: 'b'
            }, {
                title: 'Red text',
                inline: 'span',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Red header',
                block: 'h1',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Example 1',
                inline: 'span',
                classes: 'example1'
            }, {
                title: 'Example 2',
                inline: 'span',
                classes: 'example2'
            }, {
                title: 'Table styles'
            }, {
                title: 'Table row 1',
                selector: 'tr',
                classes: 'tablerow1'
            }],

            templates: [{
                title: 'Test template 1',
                content: 'Test 1'
            }, {
                title: 'Test template 2',
                content: 'Test 2'
            }]
        })

    });

    function estimate_table() {
        oTable = $('#estimate_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            /*"bStateSave": true,*/
            "sAjaxSource": "<?php echo site_url('MFQ_Estimate/fetch_estimate'); ?>",
            "aaSorting": [[0, 'desc']],
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
                {"mData": "estimateMasterID"},
                {"mData": "estimateCode"},
                {"mData": "documentDate"},
                {"mData": "CustomerName"},
                {"mData": "description"},
                {"mData": "submissionStatus"},
                {"mData": "estimateStatus"},
                {"mData": "edit"}
            ],
            "columnDefs": [{"targets": [7], "orderable": false}],
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

    function viewDocument(estimateMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                estimateMasterID: estimateMasterID
            },
            url: "<?php echo site_url('MFQ_Estimate/load_mfq_estimate_version'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                /*$('#est-versionLevel').append($("<option></option>").attr("value", " ").text('Select Version'));*/
                $('#est-versionLevel').empty();
                $.each(data, function (key, value) {
                    $('#est-versionLevel').append($("<option></option>").attr("value", value.estimateMasterID).text('[Revision ' + value.versionLevel + '] ' + value.estimateCode));
                });
                $('#est-versionLevel').val(estimateMasterID).change();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function changeVersion(estimateMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                estimateMasterID: estimateMasterID,
                html: true
            },
            url: "<?php echo site_url('MFQ_Estimate/fetch_estimate_print'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#print").html(data);
                $("#estimate_print_modal").modal();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function load_jobOrder(estimateMasterID, workProcessID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                estimateMasterID: estimateMasterID,
                workProcessID: workProcessID,
                html: true
            },
            url: "<?php echo site_url('MFQ_Estimate/fetch_job_order_view_for_save'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#estimateMasterID_jobOrderEdit").val(estimateMasterID);
                $("#workProcessID_jobOrderEdit").val(workProcessID);
                $("#jobOrder_print").html(data);
                $("#jobOrder_print_modal").modal();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function load_jobOrder_save() {
        var data = $('#frm_jobOrder_print').serialize();
        $.ajax({
            async: true,
            type: 'post',
            data: data,
            dataType: 'json',
            url: "<?php echo site_url('MFQ_Estimate/fetch_job_order_save'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                $('#jobOrder_print_modal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }


    function createJob(estimateMasterID) {
        if (estimateMasterID) {
            $('#estimateMasterID2').val(estimateMasterID);
            $('#frm_additionalformdetail').bootstrapValidator('resetForm', true);
            $('#materialCertificateID').multiselect2("clearSelection");
            getAdditionalOrderDetail(estimateMasterID);
            $('#additional_order_modal').modal('show');

            /*$.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'estimateMasterID': estimateMasterID},
                url: "<?php echo site_url('MFQ_Estimate/load_mfq_estimate_detail'); ?>",
                beforeSend: function () {
                    startLoad();
                    $('#jobContent').html('');
                },
                success: function (data) {
                    stopLoad();
                    $('#job_modal').modal();
                    if (!$.isEmptyObject(data)) {
                        $.each(data, function (key, value) {
                            if (value.itemType == 2 || value.itemType == 3) {
                                $('#jobContent').append('<div class=""><div class="skin skin-square item-iCheck"> <div class="skin-section extraColumns"><input id="linkItem" name="linkItem" type="radio" data-estimatemasterid= "' + value.estimateMasterID + '" data-bommasterid= "' + value.bomMasterID + '" data-estimatedetailid= "' + value.estimateDetailID + '" data-mfqcustomerautoid = "' + value.mfqCustomerAutoID + '" data-description = "' + value.description + '" data-mfqitemid = "' + value.mfqItemID + '" data-unitdes = "' + value.UnitDes + '" data-itemdescription="' + value.itemDescription + '" data-expectedqty = "' + value.expectedQty + '" value="" class="radioChk">&nbsp;&nbsp;&nbsp;&nbsp;<label for="checkbox">' + value.itemDescription + ' (' + value.itemSystemCode + ')</label> </div></div></div><br>');
                            }
                        });
                    }
                    $('.radioChk').iCheck('uncheck');
                    $('.extraColumns input').iCheck({
                        checkboxClass: 'icheckbox_square_relative-purple',
                        radioClass: 'iradio_square_relative-purple',
                        increaseArea: '20%'
                    });
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            })*/
        }
    }

    function createEstimateVersion(estimateMasterID) {
        swal({
                title: "Are you sure?",
                text: "You want to create version of this estimate",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            },
            function () {
                if (estimateMasterID) {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'estimateMasterID': estimateMasterID},
                        url: "<?php echo site_url('MFQ_Estimate/save_estimate_version'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data[0] == 's') {
                                fetchPage('system/mfq/mfq_add_new_estimate', data[2], 'Edit Estimate', 'EST');
                            }

                        }, error: function () {
                            alert('An Error Occurred! Please Try Again.');
                            stopLoad();
                        }
                    })
                }
            });
    }

    /*function generateJob() {
        if ($('input[name=linkItem]:checked').length != 0) {
            param = [];
            var estimateMasterID = $('input[name=linkItem]:checked').data('estimatemasterid');
            var estimateDetailID = $('input[name=linkItem]:checked').data('estimatedetailid');
            var bomMasterID = $('input[name=linkItem]:checked').data('bommasterid');
            var mfqCustomerAutoID = $('input[name=linkItem]:checked').data('mfqcustomerautoid');
            var description = $('input[name=linkItem]:checked').data('description');
            var mfqItemID = $('input[name=linkItem]:checked').data('mfqitemid');
            var unitDes = $('input[name=linkItem]:checked').data('unitdes');
            var itemDescription = $('input[name=linkItem]:checked').data('itemdescription');
            var expectedQty = $('input[name=linkItem]:checked').data('expectedqty');
            $("#estimateMasterID2").val(estimateMasterID);
            param.push(
                {name: 'estimateDetailID', value: estimateDetailID},
                {name: 'bomMasterID', value: bomMasterID},
                {name: 'mfqCustomerAutoID', value: mfqCustomerAutoID},
                {name: 'description', value: description},
                {name: 'mfqItemID', value: mfqItemID},
                {name: 'unitDes', value: unitDes},
                {name: 'type', value: 2},
                {name: 'itemDescription', value: itemDescription},
                {name: 'expectedQty', value: expectedQty});

            $('#job_modal').modal('hide');
            setTimeout(function () {
                $('#frm_additionalformdetail').bootstrapValidator('resetForm', true);
                $('#materialCertificateID').multiselect2("clearSelection");
                getAdditionalOrderDetail(estimateMasterID);
                $('#additional_order_modal').modal('show');
            }, 500);
        } else {
            myAlert('w', 'Please select an item')
        }
    }*/

    function sendemail(estimateMasterID) {
        $('#estimateMasterID').val(estimateMasterID);
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                estimateMasterID: estimateMasterID
            },
            url: "<?php echo site_url('MFQ_Estimate/load_emails'); ?>",
            beforeSend: function () {
                startLoad();
                $('#emailContent').html('');
                $('#emailNW').val('');
            },
            success: function (data) {
                stopLoad();
                $("#Email_modal").modal();
                // $(".append_data_nw").remove();

                console.log(data)
                if (data.length > 0) {
                    //$('#emailContent').append('<form method="post" id="Send_Email_form"><div class=""><div class="skin skin-square item-iCheck"> <div class="skin-section extraColumns"><ul class="list-group"style="margin-bottom: 5px; > <li class="list-group-item">' + value.email + '</li><li class="list-group-item hidden" id="EmailCusmID">' + value.mfqCustomerAutoID + '</li> <input type="checkbox" name="checkmail[]" value="' + value.customerEmailAutoID + '" ' + checked + ' style="margin-left: 15px;"  id="checkmail"></ul></div></div></div></form><br>');
                    var str = '';
                    $.each(data, function (key, value) {

                        var checked = '';
                        if (value.isDefault == 1) {
                            checked = 'checked';
                        }

                        str += '<div class="">';
                        str += '<ul class="list-group"style="margin-bottom: 5px; >';
                        str += '<li class="list-group-item">' + value.email + '' +
                            '<div class="col-md-2"><input type="checkbox" name="checkmailid[]" value="' + value.customerEmailAutoID + '" ' + checked + ' style="margin-left: 15px;" ><div>' +
                            '</li> ';

                        str += '</ul>';
                        str += '</div></div></div>';

                    });

                    str += '</ul></div></div></div></form>';
                    $('#emailContent').append(str);
                    if (data['email'] == '') {
                        var srtnon = '';
                        srtnon += '<div class="text-center alert alert-warning">';
                        srtnon += '<b>No Email Address Found';
                        srtnon += '</b>';
                        srtnon += '</div>';
                        $('#emailContent').append(srtnon);
                    }
                }
                else {
                    var srtnon = '';
                    srtnon += '<div class="text-center alert alert-warning">';
                    srtnon += '<b>No Email Address Found';
                    srtnon += '</b>';
                    srtnon += '</div>';
                    $('#emailContent').append(srtnon);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function SendEstimateMail() {
        /*alert("Hello! I am an alert box!!");*/
        /*var  mfqCustomerAutoID =$('#EmailCusmID').text();*/
        /*var isdefault = $('#checkmail').val();*/
        var form_data = $("#Send_Email_form").serialize();

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: form_data,
            url: "<?php echo site_url('MFQ_Estimate/send_emails'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data[0] == 's') {
                    oTable.draw();
                    $("#Email_modal").modal('hide');
                }
                myAlert(data[0], data[1]);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });

    }

    function add_more_nw_mail() {
        var appendData = $('#mr_1').clone();
        appendData.find('.remove-tdnw').html('<span class="glyphicon glyphicon-trash remove-trnw" onclick="remove_app_div_nw(this)" style="color:rgb(209, 91, 71);"></span>');
        appendData.find('.remov-btn').remove();
        $('.append_data_nw').append(appendData);

    }

    function remove_app_div_nw(obj) {
        $(obj).closest('.removable-div-nw').remove()
    }

    function referbackEstimate(estimateMasterID) {
        swal({
                title: "Are you sure?",
                text: "You want to refer back!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes!"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'estimateMasterID': estimateMasterID},
                    url: "<?php echo site_url('MFQ_Estimate/referback_estimate'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            estimate_table()
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function getAdditionalOrderDetail(estimateMasterID) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("MFQ_Estimate/load_mfq_estimate"); ?>',
            dataType: 'json',
            data: {estimateMasterID: estimateMasterID},
            async: false,
            success: function (data) {
                $("#exclusions").val(data['exclusions']);
                $("#designCode").val(data['designCode']);
                $("#designEditor").val(data['designEditor']);
                $("#engineeringDrawings").val(data['engineeringDrawings']);
                $("#engineeringDrawingsComment").val(data['engineeringDrawingsComment']);
                $("#submissionOfITP").val(data['submissionOfITP']);
                $("#itpComment").val(data['itpComment']);
                $("#qcqtDocumentation").val(data['qcqtDocumentation']);
                //$("#scopeOfWork").val(data['scopeOfWork']);
                $("#mfqSegmentID").val(data['mfqSegmentID']).change();
                $("#mfqWarehouseAutoID").val(data['mfqWarehouseAutoID']).change();
                $("#est-mfqCustomerAutoID2").val(data['mfqCustomerAutoID']);
                $("#orderStatus").val(data['orderStatus']);
                $("#poNumber").val(data['poNumber']);
                $("#materialCertificationComment").val(data['materialCertificationComment']);
                setTimeout(function () {
                    tinyMCE.get("scopeOfWork").setContent(data['scopeOfWork']);
                }, 1000);

                var valArr = data['materialcertificate'];
                i = 0, size = valArr.length;
                for (i; i < size; i++) {
                    $("#materialCertificateID").multiselect2().find(":checkbox[value='" + valArr[i] + "']").prop("checked", true);
                    $("#materialCertificateID option[value='" + valArr[i] + "']").prop("selected", true);
                    $("#materialCertificateID").multiselect2("refresh");
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myAlert('e', xhr.responseText);
            }
        });
    }

    function loadEmailView() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                estimateMasterID: $("#estimateMasterID").val(),
                html: true
            },
            url: "<?php echo site_url('MFQ_Estimate/fetch_quotation_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#quotation_print").html(data);
                $("#quotation_print_modal").modal();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function load_jobOrder_view(estimateMasterID, workProcessID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                estimateMasterID: estimateMasterID,
                workProcessID: workProcessID,
                html: true
            },
            url: "<?php echo site_url('MFQ_Estimate/fetch_job_order_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#job_print").html(data);
                $("#job_view_modal").modal();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }
</script>