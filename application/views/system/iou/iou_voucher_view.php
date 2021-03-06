<?php echo head_page($_POST['page_name'], false);
$this->load->helper('buyback_helper');
$this->load->helper('iou_helper');
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$segment_arr = fetch_segment();
$farms_arr = load_all_farms();
$currency_arr = all_currency_new_drop();//array('' => 'Select Currency');
$location_arr = all_delivery_location_drop();
$location_arr_default = default_delivery_location_drop();
$financeyear_arr = all_financeyear_drop(true);
$uom_arr = array('' => 'Select UOM');
$batch_arr = array('' => 'Select Batch');
$gl_code_arr = company_PL_account_drop();
$segment_arr = fetch_segment();

$employeedrop = fetch_users_iou();

?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/crm_style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>"/>
<link rel="stylesheet"
      href="<?php echo base_url('plugins/bootstrap-slider-master/dist/css/bootstrap-slider.min.css'); ?>"/>
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
</style>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - IOU Voucher Header</a>
    <a class="btn btn-default btn-wizard" href="#step2" data-toggle="tab" onclick="load_voucher_expences();">Step 2 -
        IOU Voucher Expenses</a>

</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="iovoucher_header_form"'); ?>
        <input type="hidden" name="voucherautoid" id="voucherautoid_edit">

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>IOU VOUCHER HEADER</h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Voucher Date</label>
                    </div>
                    <div class="form-group col-sm-4">
                       <span class="input-req" title="Required Field">
                            <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="voucherdate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="voucherdate" class="form-control" readonly>
                        </div>
                       <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="title">Employee</label>
                    </div>
                    <div class="form-group col-sm-4">
                      <span class="input-req" title="Required Field">
                      <?php echo form_dropdown('employeeid', $employeedrop, '', 'class="form-control select2" id="employeeid" disabled'); ?>
                          <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>


                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Currency</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                        <?php echo form_dropdown('transactionCurrencyID', $currency_arr, $this->common_data['company_data']['company_default_currencyID'], 'class="form-control select2" id="transactionCurrencyID"  required disabled'); ?>
                            <span class="input-req-inner"></span>
                    </div>

                    <div id="div_ClassBank">
                        <div class="form-group col-sm-2">
                            <label class="title">Bank or Cash</label>
                        </div>
                        <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                                <?php echo form_dropdown('PVbankCode', company_bank_account_drop(), '', 'class="form-control select2" id="PVbankCode" onchange="fetch_cheque_number(this.value)" readonly disabled'); ?>
                            <span class="input-req-inner"></span></span>
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Financial Year</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                <?php echo form_dropdown('financeyear', $financeyear_arr, $this->common_data['company_data']['companyFinanceYearID'], 'class="form-control" id="financeyear" required onchange="fetch_finance_year_period(this.value)" disabled'); ?>
                            <span class="input-req-inner"></span>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="title">Financial Period</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                <?php echo form_dropdown('financeyear_period', array('' => 'Financial Period'), '', 'class="form-control" id="financeyear_period" required disabled'); ?>
                            <span class="input-req-inner"></span>
                    </div>
                </div>

                <div class="row paymentType hide" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Payment Type</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                               <?php echo form_dropdown('paymentType', array('' => $this->lang->line('common_select_type')/*'Select Type'*/, '1' => 'Cheque ', '2' => 'Bank Transfer'), ' ', 'class="form-control select2" id="paymentType" onchange="show_payment_method(this.value)" disabled'); ?>
                            <span class="input-req-inner"></span></span>
                    </div>
                    <div class="paymentmoad">
                        <div class="form-group col-sm-2">
                            <label class="title">Payee Only</label>
                        </div>
                        <div class="form-group col-sm-4">
                            <div class="skin skin-square">
                                <div class="skin-section extraColumns"><input id="accountPayeeOnly" type="checkbox"
                                                                              data-caption="" class="columnSelected"
                                                                              name="accountPayeeOnly" value="1" disabled><label
                                            for="checkbox">&nbsp;</label></div>
                            </div>


                        </div>
                    </div>


                    <div class="hide" id="employeerdirect">
                        <div class="form-group col-sm-2">
                            <label class="title">Bank Transfer Details</label>
                        </div>
                        <div class="form-group col-sm-4">
                            <textarea class="form-control" rows="3" name="bankTransferDetails"
                                      id="bankTransferDetails" disabled></textarea>
                        </div>
                    </div>

                </div>

                <div class="row paymentmoad" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Cheque Number</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                                <input type="text" name="PVchequeNo" id="PVchequeNo" class="form-control" disabled>
                            <span class="input-req-inner"></span></span>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Cheque Date</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="PVchequeDate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="PVchequeDate" class="form-control" disabled>
                        </div>
                        <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Segment</label>
                    </div>

                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                        <?php echo form_dropdown('segment', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="segment" required disabled'); ?>
                            <span class="input-req-inner"></span></span>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="title">Reference No</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" name="referenceno" id="referenceno" class="form-control" disabled>
                    </div>

                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Narration</label>
                    </div>
                    <div class="form-group col-sm-4">
                         <span class="input-req" title="Required Field">
                        <textarea class="form-control" rows="3" id="narration" name="narration" disabled></textarea>
                         <span class="input-req-inner"></span></span>
                    </div>
                </div>
            </div>
        </div>
        </form>

        <div class="row addTableView">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>IOU VOUCHER DETAILS</h2>
                </header>
               <br>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-11">
                        <div id="iou_voucher_Detial_item"></div>
                    </div>
                    <div class="col-sm-1">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>


        <br>
    </div>

    <div id="step2" class="tab-pane">
        <div id="confirm_body"></div>
    </div>

</div>

<div aria-hidden="true" role="dialog" id="iou_voucher_detail_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add IOU Voucher Detail</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="iou_voucher_detail_add_form" class="form-horizontal">
                    <table class="table table-bordered table-condensed no-color" id="iou_voucher_detail_add_table">
                        <thead>
                        <tr>
                            <th>Description <?php required_mark(); ?></th>
                            <th>Amount <span class="currency"></span> <?php required_mark(); ?></th>
                            <th style="width: 40px;">
                                <button type="button" class="btn btn-primary btn-xs" onclick="add_more_vouchers()">
                                    <i class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control" rows="1" id="description"
                                       name="description[]">
                            </td>
                            <td>
                                <input type="text" name="amount[]" id="amount" class="form-control number"
                                       onkeypress="return validateFloatKeyPress(this,event)">
                            </td>

                            <td class="remove-td" style="vertical-align: middle;text-align: center"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="save_Voucher_details()">Save
                    changes
                </button>
            </div>

        </div>
    </div>
</div>

<div aria-hidden="true" role="dialog" tabindex="-1" id="iou_voucher_detail_edit_modal" data-backdrop="static"
     data-keyboard="false" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 50%">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit IOU Voucher Detail</h4>
            </div>
            <form role="form" id="iou_voucher_detail_edit_form" class="form-horizontal">
                <input type="hidden" id="iouvoucherdetails_edit" name="iouvoucherdetails_edit">

                <div class="modal-body">
                    <table class="table table-bordered table-condensed" id="">
                        <thead>
                        <tr>
                            <th>Description <?php required_mark(); ?></th>
                            <th>Amount <span class="currency"></span> <?php required_mark(); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control" id="description_edit" name="description_edit">
                            </td>
                            <td>
                                <input type="text" id="amount_edit" name="amount_edit" class="form-control number"
                                       onkeypress="return validateFloatKeyPress(this,event)">
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="update_voucher_details()">Update changes
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="voucher_expences_drilldown" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" style="width: 80%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">View Expences Detail<span class="myModalLabel"></span>
                </h4>
            </div>
            <div class="modal-body">
                <div id="voucherexpences"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script>
    var IOUmasterid;
    var currency_decimal;
    $(document).ready(function () {
        number_validation();
        $('.select2').select2();
        $(".paymentmoad").hide();
        $('.headerclose').click(function () {
            fetchPage('system/iou/iou_voucher', '', 'IOU Voucher')
        });

        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {

        });
        Inputmask().mask(document.querySelectorAll("input"));

        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });
    });
    p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;

    if (p_id) {
        IOUmasterid = p_id;
        load_voucherHeader();
        $('.btn-wizard').removeClass('disabled');
    }
    else {
        $('.btn-wizard').addClass('disabled');
        $('.addTableView').addClass('hide');
        $('#bankTransferDetails').wysihtml5({
            toolbar: {
                "font-styles": false,
                "emphasis": false,
                "lists": false,
                "html": false,
                "link": false,
                "image": false,
                "color": false,
                "blockquote": false
            }
        });
    }


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

    FinanceYearID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinanceYearID'])); ?>;
    DateFrom = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateFrom'])); ?>;
    DateTo = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateTo'])); ?>;
    periodID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinancePeriodID'])); ?>;
    fetch_finance_year_period(FinanceYearID, periodID);
    IOUmasterid = null;

    $('#iovoucher_header_form').bootstrapValidator({
        live: 'enabled',
        message: 'This value is not valid.',
        excluded: [':disabled'],
        fields: {
            voucherdate: {validators: {notEmpty: {message: 'Voucher Date is required.'}}},
            employeeid: {validators: {notEmpty: {message: 'Employee is required.'}}},
            transactionCurrencyID: {validators: {notEmpty: {message: 'Currency is required.'}}},
            PVbankCode: {validators: {notEmpty: {message: 'Bank or Cash is required.'}}},
            financeyear: {validators: {notEmpty: {message: 'Financial Year is required.'}}},
            financeyear_period: {validators: {notEmpty: {message: 'Financial Period is required.'}}},
            segment: {validators: {notEmpty: {message: 'Segment is required.'}}},
            narration: {validators: {notEmpty: {message: ' Narrration is required.'}}}
        },
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        $("#PVtype").prop("disabled", false);
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        var data = $form.serializeArray();
        data.push({'name': 'empname', 'value': $('#employeeid option:selected').text()});
        data.push({'name': 'companyFinanceYear', 'value': $('#financeyear option:selected').text()});
        data.push({'name': 'currency_code', 'value': $('#transactionCurrencyID option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('iou/save_iou_voucher_header'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1], data[2]);
                if (data[0] == 's') {
                    IOUmasterid = data[2];
                    $('#voucherautoid_edit').val(IOUmasterid);
                    //$('[href=#step2]').tab('show');
                    get_iou_voucher_detail_view(IOUmasterid);
                    $('#save_btn').html('Update');
                    $('.addTableView').removeClass('hide');
                    $('.btn-wizard').removeClass('disabled');
                    $('#save_btn').prop('disabled', false);
                } else {
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

    $(document).on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });

    function fetch_cheque_number(GLAutoID) {
        if (!jQuery.isEmptyObject(GLAutoID)) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'GLAutoID': GLAutoID},
                url: "<?php echo site_url('Chart_of_acconts/fetch_cheque_number'); ?>",
                success: function (data) {
                    if (data) {
                        if (p_id) {
                            $("#PVchequeNo").val((parseFloat(data['bankCheckNumber'])));
                        } else {
                            $("#PVchequeNo").val((parseFloat(data['bankCheckNumber']) + 1));
                        }

                        /*if($('#vouchertype').val()=='Supplier'){*/
                        if (data['isCash'] == 1) {
                            $(".paymentmoad").hide();
                            $('.paymentType').addClass('hide');
                            $('.banktrans').addClass('hide');
                        } else {
                            $('.paymentType').removeClass('hide');
                            show_payment_method();
                            //$(".paymentmoad").show();
                        }
                        /*}else{
                            if (data['isCash'] == 1) {
                                $(".paymentmoad").hide();
                            } else {
                                $(".paymentmoad").show();
                            }
                        }*/

                    }
                    ;
                }
            });
        } else {
            $('.paymentType').addClass('hide');
            $('.banktrans').addClass('hide');
        }

    }

    function fetch_finance_year_period(companyFinanceYearID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'companyFinanceYearID': companyFinanceYearID},
            url: "<?php echo site_url('Dashboard/fetch_finance_year_period'); ?>",
            success: function (data) {
                $('#financeyear_period').empty();
                var mySelect = $('#financeyear_period');
                mySelect.append($('<option></option>').val('').html('Select  Financial Period'));
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['companyFinancePeriodID']).html(text['dateFrom'] + ' - ' + text['dateTo']));
                    });
                    if (select_value) {
                        $("#financeyear_period").val(select_value);
                    }
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }

    function get_iou_voucher_detail_view(IOUmasterid) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'IOUmasterid': IOUmasterid},
            url: "<?php echo site_url('iou/load_iou_voucher_detail_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#iou_voucher_Detial_item').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function iou_voucher_model() {
        if (IOUmasterid) {
            var transactioncurrency = $('#transactionCurrencyID').val();
            $('#iou_voucher_detail_add_form')[0].reset();
            $('#iou_voucher_detail_add_table tbody tr').not(':first').remove();
            $("#iou_voucher_detail_add_modal").modal({backdrop: "static"});
            get_currency_decimal_places(IOUmasterid);
        }
    }

    function add_more_vouchers() {
        $('select.select2').select2('destroy');
        var appendData = $('#iou_voucher_detail_add_table tbody tr:first').clone();
        appendData.find('input').val('');
        appendData.find('textarea').val('');
        appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
        $('#iou_voucher_detail_add_table').append(appendData);
        var lenght = $('#iou_voucher_detail_add_table tbody tr').length - 1;
        $(".select2").select2();
        number_validation();
    }

    function save_Voucher_details() {
        var $form = $('#iou_voucher_detail_add_form');
        var data = $form.serializeArray();
        data.push({'name': 'IOUmasterid', 'value': IOUmasterid});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('iou/save_iou_voucher_details'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {

                    $('#iou_voucher_detail_add_form')[0].reset();
                    get_iou_voucher_detail_view(IOUmasterid);
                    $('#iou_voucher_detail_add_modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function delete_iou_voucher(voucherDetailID) {
        swal({
                title: "Are you sure?",
                text: "You want to delete this record!",
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
                    data: {'voucherDetailID': voucherDetailID},
                    url: "<?php echo site_url('iou/delete_iouVoucher_detail'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert('s', 'IOU Voucher Detail Deleted Successfully');
                        get_iou_voucher_detail_view(IOUmasterid);
                        refreshNotifications(true);
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function load_voucherHeader() {
        if (IOUmasterid) {

            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'IOUmasterid': IOUmasterid},
                url: "<?php echo site_url('iou/load_voucherHeader'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        IOUmasterid = data['voucherAutoID'];
                        $('#voucherautoid_edit').val(IOUmasterid);
                        $('#voucherdate').val(data['voucherDate']);
                        $('#employeeid').val(data['empID'] + '|' + data['userType']).change();
                        $('#transactionCurrencyID').val(data['transactionCurrencyID']).change();
                        $('#PVbankCode').val(data['bankGLAutoID']).change();
                        $('#financeyear').val(data['companyFinanceYearID']);
                        fetch_finance_year_period(data['companyFinanceYearID'], data['companyFinancePeriodID']);
                        $('#narration').val(data['narration']);
                        setTimeout(function () {
                            $('#PVchequeNo').val(data['chequeNo']);
                        }, 2000);


                        $('#PVchequeDate').val(data['chequeDate']);
                        $('#segment').val(data['segmentID'] + '|' + data['segmentCode']).change();
                        $('#referenceno').val(data['referenceNumber']);
                        $('#paymentType').val(data['paymentType']).change();
                        if (data['accountPayeeOnly'] == 1) {
                            $('#accountPayeeOnly').iCheck('check');
                        }
                        if (data['modeOfPayment'] == 0) {
                            $(".paymentmoad").show();
                        }
                        if (data['paymentType'] == 1) {
                            $(".banktrans").addClass('hide');
                        } else {
                            $('#bankTransferDetails').wysihtml5({
                                toolbar: {
                                    "font-styles": false,
                                    "emphasis": false,
                                    "lists": false,
                                    "html": false,
                                    "link": false,
                                    "image": false,
                                    "color": false,
                                    "blockquote": false
                                }
                            });

                            $("#bankTransferDetails").val(data['bankTransferDetails']);
                            $("#employeerdirect").removeClass('hide');
                            $(".banktrans").addClass('show');

                        }
                        get_iou_voucher_detail_view(data['voucherAutoID']);
                        load_voucher_expences();
                        $('[href=#step2]').tab('show');
                        $('a[data-toggle="tab"]').removeClass('btn-primary');
                        $('a[data-toggle="tab"]').addClass('btn-default');
                        $('[href=#step2]').removeClass('btn-default');
                        $('[href=#step2]').addClass('btn-primary');
                        $('#save_btn').html('Update');

                        
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

    function load_voucher_expences() {
        if (IOUmasterid) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'voucherAutoID': IOUmasterid, 'html': true},
                url: "<?php echo site_url('iou/load_iou_voucher_expences'); ?>",
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
        if (IOUmasterid) {
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
                        data: {'IOUmasterid': IOUmasterid},
                        url: "<?php echo site_url('iou/iouvoucher_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            refreshNotifications(true);
                            if (data['error'] == 1) {
                                myAlert('e', data['message']);
                            } else if (data['error'] == 2) {
                                myAlert('w', data['message']);
                            }
                            else if (data['error'] == 0) {
                                myAlert('s', data['message']);
                                fetchPage('system/iou/iou_voucher', IOUmasterid, 'IOU Voucher');
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function save_draft() {
        if (IOUmasterid) {
            swal({
                    title: "Are you sure?",
                    text: "You want to save this document!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Save as Draft"
                },
                function () {
                    fetchPage('system/iou/iou_voucher', IOUmasterid, 'IOU Voucher');
                });
        }
    }

    function edit_paymentVoucher_advance(voucherDetailID) {
        if (voucherDetailID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to edit this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Edit"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'voucherDetailID': voucherDetailID},
                        url: "<?php echo site_url('iou/fetch_iou_voucher_details'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {

                            $('#description_edit').val(data['description']);
                            $('#amount_edit').val(data['transactionAmount']);
                            $('#iouvoucherdetails_edit').val(data['voucherDetailID']);

                            $("#iou_voucher_detail_edit_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }

    function update_voucher_details() {
        var data = $('#iou_voucher_detail_edit_form').serialize();

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('iou/update_iou_voucher_details'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    $('#iou_voucher_detail_edit_form')[0].reset();
                    get_iou_voucher_detail_view(IOUmasterid);
                    $('#iou_voucher_detail_edit_modal').modal('hide');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function show_payment_method() {
        if ($("#paymentType").val() == 1) {
            $(".paymentmoad").show();
            $('.banktrans').addClass('hide');
            $('#employeerdirect').addClass('hide');
        } else if ($("#paymentType").val() == 2) {
            $('#supplierBankMasterID').addClass('hide');
            $('#employeerdirect').removeClass('hide');
            $(".paymentmoad").hide();
            var invoiceNote = '<p><p>Beneficiary Name : </p><p>Bank Name : </p><p>Beneficiary Bank Address : </p><p>Bank Account : </p><p>Beneficiary Swift Code : </p><p>Beneficiary ABA/Routing :</p><p>Reference : </p><br></p>';
            if (p_id) {

            } else {
                $('#bankTransferDetails ~ iframe').contents().find('.wysihtml5-editor').html(invoiceNote);
            }
        } else {
            $('#employeerdirect').addClass('hide');
            $('.banktrans').addClass('hide');
            $(".paymentmoad").hide();
        }


    }

    function validateFloatKeyPress(el, evt) {
        //alert(currency_decimal);
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot
        if (number.length > 1 && charCode == 46) {
            return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if (caratPos > dotPos && dotPos > -(currency_decimal - 1) && (number[1].length > (currency_decimal - 1))) {
            return false;
        }
        return true;
    }

    function get_currency_decimal_places(IOUmasterid) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {IOUmasterid: IOUmasterid},
            url: "<?php echo site_url('iou/get_currency_decimal_places'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                currency_decimal = data['DecimalPlaces'];
            },
            error: function () {
                myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
            }
        });
    }

    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate();
            r.moveEnd('character', o.value.length);
            if (r.text == '') return o.value.length;
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }
    function viewiouvoucherexpencedetails(bookingMasterID) {
        $.ajax({
            async: true,
            type: 'POST',
            dataType: 'html',
            data: {'IOUbookingmasterid': bookingMasterID,'html': true},
            url: '<?php echo site_url('iou/load_iou_voucher_booking_confirmation'); ?>',
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#voucherexpences").html(data);
                $('#voucher_expences_drilldown').modal("show");
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
            }
        });
    }

</script>
