<?php echo head_page($_POST['page_name'], false);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$data_set = array(0 => array('estimateMasterID' => '', 'estimateDetailID' => '', 'bomMasterID' => '', 'mfqCustomerAutoID' => '', 'description' => '', 'mfqItemID' => '', 'unitDes' => '', 'type' => 1, 'itemDescription' => '', 'expectedQty' => 0, 'mfqSegmentID' => '', 'mfqWarehouseAutoID' => ''));
if ($data_arr) {
    $data_set = $data_arr;
}
?>
<div id="filter-panel" class="collapse filter-panel"></div>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
<!--<script src="<?php /*echo base_url('plugins/html5sortable/jquery.sortable.js'); */ ?>"></script>-->
<!--<link rel="stylesheet"
      href="<?php /*echo base_url('plugins/bootstrap-slider-master/dist/css/bootstrap-slider.min.css'); */ ?>"/>-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/css/autocomplete-suggestions.css'); ?>"/>
<style>

    .title {
        float: left;
        width: 170px;
        text-align: right;
        font-size: 13px;
        color: #7b7676;
        padding: 4px 10px 0 0;
    }

    .slider-selection {
        position: absolute;
        background-image: -webkit-linear-gradient(top, #6090f5 0, #6090f5 100%);
        background-image: -o-linear-gradient(top, #6090f5 0, #6090f5 100%);
        background-image: linear-gradient(to bottom, #6090f5 0, #6090f5 100%);
        background-repeat: repeat-x;
    }

    .affix-content .container .page-header {
        margin-top: 0;
    }

    .affix-sidebar {
        padding-right: 0;
        font-size: small;
        padding-left: 0;
    }

    .affix-row, .affix-container, .affix-content {
        height: 100%;
        overflow: scroll;
        margin-left: 0;
        margin-right: 0;
    }

    .affix-content {
        background-color: white;
    }

    .sidebar-nav .navbar .navbar-collapse {
        padding: 0;
        max-height: none;
    }

    .sidebar-nav .navbar {
        border-radius: 0;
        margin-bottom: 0;
        border: 0;
    }

    .sidebar-nav .navbar ul {
        float: none;
        display: block;
    }

    .sidebar-nav .navbar li {
        float: none;
        display: block;
    }

    .sidebar-nav .navbar li a {
        padding-top: 12px;
        padding-bottom: 12px;
    }

    }

    @media (min-width: 769px) {
        .affix-content .container {
            width: 600px;
        }

        .affix-content .container .page-header {
            margin-top: 0;
        }
    }

    @media (min-width: 992px) {
        .affix-content .container {
            width: 900px;
        }

        .affix-content .container .page-header {
            margin-top: 0;
        }
    }

    @media (min-width: 1220px) {
        .affix-row {
            overflow: hidden;
        }

        .affix-content {
            overflow: auto;
        }

        .affix-content .container {
            width: 1000px;
        }

        .affix-content .container .page-header {
            margin-top: 0;
        }

        .affix-content {
            padding-right: 30px;
            padding-left: 10px;
        }

        .affix-title {
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 10px;
        }

        .navbar-nav {
            margin: 0;
        }

        .navbar-collapse {
            padding: 0;
        }

        .sidebar-nav .navbar li a:hover {
            background-color: #428bca;
            color: white;
        }

        .sidebar-nav .navbar li a > .caret {
            margin-top: 8px;
        }
    }

    .sidebar {
        padding-bottom: 0px;
    }

    div.bhoechie-tab-container {
        background-color: #ffffff;
        padding: 0 !important;
        border-radius: 4px;
        -moz-border-radius: 4px;
        border: 1px solid #ddd;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        -moz-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        background-clip: padding-box;
        opacity: 0.97;
        filter: alpha(opacity=97);
    }

    div.bhoechie-tab-menu {
        padding-right: 0;
        padding-left: 0;
        padding-bottom: 0;
    }

    div.bhoechie-tab-menu div.list-group {
        margin-bottom: 0;
    }

    div.bhoechie-tab-menu div.list-group > a {
        margin-bottom: 0;
    }

    div.bhoechie-tab-menu div.list-group > a .glyphicon,
    div.bhoechie-tab-menu div.list-group > a .fa {
        color: #E78800;
    }

    div.bhoechie-tab-menu div.list-group > a .glyphicon .badge {
        display: inline-block;
        min-width: 10px;
        padding: 6px 9px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        border-radius: 24px;
        color: #555;
        border: 2px solid #555;
        background-color: rgba(119, 119, 119, 0);
    }

    div.bhoechie-tab-menu div.list-group > a:first-child {
        border-top-right-radius: 0;
        -moz-border-top-right-radius: 0;
    }

    div.bhoechie-tab-menu div.list-group > a:last-child {
        border-bottom-right-radius: 0;
        -moz-border-bottom-right-radius: 0;
    }

    div.bhoechie-tab-menu div.list-group > a.active,
    div.bhoechie-tab-menu div.list-group > a.active .glyphicon,
    div.bhoechie-tab-menu div.list-group > a.active .fa {
        background-color: #E78800;
        color: #ffffff;
    }

    div.bhoechie-tab-menu div.list-group > a.active .badge {
        display: inline-block;
        min-width: 10px;
        padding: 6px 9px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        border-radius: 24px;
        color: #ffffff;
        border: 2px solid #ffffff;
        background-color: rgba(119, 119, 119, 0);
    }

    div.bhoechie-tab-menu div.list-group > a.active:after {
        content: '';
        position: absolute;
        left: 100%;
        top: 50%;
        margin-top: -13px;
        border-left: 0;
        border-bottom: 13px solid transparent;
        border-top: 13px solid transparent;
        border-left: 10px solid #E78800;
    }

    div.bhoechie-tab-content {
        background-color: #ffffff;
        /* border: 1px solid #eeeeee; */
        padding-left: 20px;
        padding-top: 10px;
    }

    div.bhoechie-tab div.bhoechie-tab-content:not(.active) {
        display: none;
    }

    .list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
        border: 1px solid #ddd;
    }

    .bhoechie-tab {
        border: solid 2px #E78800;
        margin-left: -2px;
        margin-top: 1px;
        margin-bottom: 1px;
        min-height: 300px;
    }

    .disabledbutton {
        pointer-events: none;
    }

    .table-responsive {
        overflow: visible !important
    }

</style>

<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - Delivery Note Header</a>
    <a class="btn btn-default btn-wizard" href="#step2" onclick="load_dn_confirmation()" data-toggle="tab">Step
        2 - Delivery Note Confirmation</a>
</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="delivery_note_frm"'); ?>
        <input type="hidden" name="deliverNoteID" id="edit_deliverNoteID">

        <div class="row">
            <div class="col-md-6 animated zoomIn">
                <div class="row">
                    <div class="form-group col-sm-4" style="margin-top: 10px;">
                        <label class="title">Customer Name</label>
                    </div>
                    <div class="form-group col-sm-7" style="margin-top: 10px;">
                        <span class="input-req"
                              title="Required Field"><?php echo form_dropdown('mfqCustomerAutoID', all_mfq_customer_drop(), $data_set[0]['mfqCustomerAutoID'], 'class="form-control select2" id="mfqCustomerAutoID"');
                            ?>
                            <span class="input-req-inner"></span></span>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-4">
                        <label class="title">Job</label>
                    </div>
                    <div class="form-group col-sm-7">
                        <div class="input-req" title="Required Field">
                            <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                            <?php echo form_dropdown('jobID', array("" => "Select"), "", 'class="form-control select2" id="jobID"'); ?>
                            <span class="input-req-inner"></span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-4">
                        <label class="title">Driver Name</label>
                    </div>
                    <div class="form-group col-sm-7">
                <span class="input-req" title="Required Field"><input type="text" name="driverName" id="driverName"
                                                                      class="form-control"
                                                                      value=""
                    ><span
                        class="input-req-inner"></span></span>
                    </div>
                </div>



            </div>
            <div class="col-md-6 animated zoomIn">
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-4">
                        <label class="title">Delivery Date</label>
                    </div>
                    <div class="form-group col-sm-7">
                <span class="input-req"
                      title="Required Field"><div class="input-group datepic" id="dateStartDate">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="deliveryDate" id="deliveryDate"
                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                               value="<?php echo $current_date; ?>" class="form-control startDate" required>
                    </div>
                    <span class="input-req-inner"></span></span>
                    </div>
                </div>
<!--                <div class="row">
                    <div class="form-group col-sm-4">
                        <label class="title">Delivery Note Code</label>
                    </div>
                    <div class="form-group col-sm-7">
                <span class="input-req" title="Required Field"><input type="text" name="deliveryNoteCode"
                                                                      id="deliveryNoteCode"
                                                                      class="form-control"
                                                                      value=""
                    ><span
                        class="input-req-inner"></span></span>
                    </div>
                </div>-->
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-4">
                        <label class="title">Vehicle No</label>
                    </div>
                    <div class="form-group col-sm-7">
                <span class="input-req" title="Required Field"><input type="text" name="vehicleNo" id="vehicleNo"
                                                                      class="form-control"
                                                                      value=""
                    ><span
                        class="input-req-inner"></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-4">
                        <label class="title">Mobile No</label>
                    </div>
                    <div class="form-group col-sm-7">
                <span class="input-req" title="Required Field"><input type="text" name="mobileNo" id="mobileNo"
                                                                      class="form-control"
                                                                      value=""
                    ><span
                        class="input-req-inner"></span></span>
                    </div>
                </div>
            </div>
            <div class="row col-md-12" style="margin-top: 10px;">
                <div class="text-right m-t-xs">
                    <button class="btn btn-primary" type="submit" id="saveJob">Save</button>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div id="step2" class="tab-pane">
        <div id="confirm_body"></div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev">Previous</button>
            <button class="btn btn-primary " onclick="save_draft()">Save as Draft</button>
            <button class="btn btn-success submitWizard" onclick="confirmation()">Confirm</button>
        </div>
    </div>
</div>
<script src="<?php echo base_url('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/bootstrap-slider-master/dist/bootstrap-slider.min.js'); ?>"></script>

<script type="text/javascript">
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
    var deliverNoteID;
    var jobID ='';

    $(document).ready(function () {
        $(".select2").select2();
        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {
        });

        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_delivery_note','','Delivery Note');
        });

        Inputmask().mask(document.querySelectorAll("input"));

        deliverNoteID = null;
        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            deliverNoteID = p_id;
            load_delivery_note_header();
        } else {
            $('.btn-wizard').addClass('disabled');
        }

        $('#delivery_note_frm').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                mfqCustomerAutoID: {validators: {notEmpty: {message: 'Customer is required.'}}},
                jobID: {validators: {notEmpty: {message: 'Job is required.'}}},
                deliveryDate: {validators: {notEmpty: {message: 'Delivery Date is required.'}}},
                driverName: {validators: {notEmpty: {message: 'Driver Name is required.'}}},
                mobileNo: {validators: {notEmpty: {message: 'Mobile No is required.'}}},
                vehicleNo: {validators: {notEmpty: {message: 'Vehicle No is required.'}}},
               // deliveryNoteCode: {validators: {notEmpty: {message: 'Delivery Note Code is required.'}}}
            }
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
                url: "<?php echo site_url('MFQ_DeliveryNote/save_delivery_note_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        deliverNoteID = data[2];
                        $('.btn-wizard').removeClass('disabled');
                        $('[href=#step2]').tab('show');
                        load_dn_confirmation();
                    }
                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('a[data-toggle="tab"]').removeClass('btn-primary');
            $('a[data-toggle="tab"]').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        });

        $('.next').click(function () {
            var nextId = $(this).parents('.tab-pane').next().attr("id");
            $('[href=#' + nextId + ']').tab('show');
        });

        $('.prev').click(function () {
            var prevId = $(this).parents('.tab-pane').prev().attr("id");
            $('[href=#' + prevId + ']').tab('show');
        });

        $("#mfqCustomerAutoID").change(function () {
            get_customer_jobs($(this).val())
        });

    });

    function load_delivery_note_header() {
        if (deliverNoteID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {deliverNoteID: deliverNoteID},
                url: "<?php echo site_url('MFQ_DeliveryNote/load_delivery_note_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        deliverNoteID = data['deliverNoteID'];
                        $('#edit_deliverNoteID').val(deliverNoteID);
                        $('#mfqCustomerAutoID').val(data['mfqCustomerAutoID']).change();
                        $('#deliveryDate').val(data['deliveryDate']).change();
                        jobID = data['jobID'];
                        $('#driverName').val(data["driverName"]);
                        $('#mobileNo').val(data["mobileNo"]);
                        $('#deliveryNoteCode').val(data["deliveryNoteCode"]);
                        $('#vehicleNo').val(data["vehicleNo"]);
                        load_dn_confirmation();
                        $('[href=#step2]').tab('show');

                    }
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }


    function load_dn_confirmation() {
        if (deliverNoteID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'deliverNoteID': deliverNoteID, 'html': true},
                url: "<?php echo site_url('MFQ_DeliveryNote/load_deliveryNote_confirmation'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#confirm_body').html(data);
                    refreshNotifications(true);
                }, error: function () {
                    stopLoad();
                    alert('An Error Occurred! Please Try Again.');
                    refreshNotifications(true);
                }
            });
        }
    }

    function confirmation() {
        if (deliverNoteID) {
            swal({
                    title: "Are you sure?",
                    text: "You want confirm this document!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Confirm"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'deliverNoteID': deliverNoteID},
                        url: "<?php echo site_url('MFQ_DeliveryNote/delivery_note_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            myAlert(data[0], data[1]);
                            stopLoad();
                            if (data[0] == 's') {
                                fetchPage('system/mfq/mfq_delivery_note', '', 'Delivery Note');
                            }
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function save_draft() {
        if (deliverNoteID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to save this document!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Save as Draft"
                },
                function () {
                    fetchPage('system/mfq/mfq_delivery_note', deliverNoteID, 'Delivery Note');
                });
        }
    }

    function get_customer_jobs(mfqCustomerAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                mfqCustomerAutoID: mfqCustomerAutoID
            },
            url: "<?php echo site_url('MFQ_DeliveryNote/fetch_customer_jobs'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#jobID').empty();
                var mySelect = $('#jobID');
                mySelect.append($('<option></option>').val("").html("Select"));
                if (!$.isEmptyObject(data)) {
                    $.each(data, function (k, text) {
                        mySelect.append($('<option></option>').val(text['workProcessID']).html(text['documentCode']));
                    });
                }
                if(jobID){
                    mySelect.val(jobID).change();
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }




</script>