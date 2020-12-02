<?php

echo head_page('IOU Voucher', true);
$this->load->helper('iou_helper');
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$employeedrop = fetch_users_iou();
$segment_arr = segment_drop();
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<style>
    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .label {
        display: inline;
        padding: .2em .8em .3em;
    }

    .actionicon {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #89e68d;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 2px 5px 2px 5px;
        line-height: 14px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .headrowtitle {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0 25px;
        font-weight: bold;
        text-align: left;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
        background-color: white;
        border-top: 1px solid #ffffff;
    }

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
        padding: 1px 3px 0 3px;
        line-height: 14px;
        margin-left: 8px;
        margin-top: 9px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .numberColoring {
        font-size: 13px;
        font-weight: 600;
        color: saddlebrown;
    }
</style>

<div id="filter-panel" class="collapse filter-panel">
    <div class="row">
        <div class="form-group col-sm-3">
            <label for="supplierPrimaryCode">Date From</label>
            <div class="input-group datepic">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" name="voucherDatefrom"
                       data-inputmask="'alias': '<?php echo $date_format_policy ?>'" id="voucherDatefrom"
                       class="form-control" value="">
            </div>
        </div>
        <div class="form-group col-sm-3">
            <label for="supplierPrimaryCode">&nbsp&nbspTo&nbsp&nbsp</label>
            <div class="input-group datepic">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" name="voucherDateto"
                       data-inputmask="'alias': '<?php echo $date_format_policy ?>'" id="voucherDateto"
                       class="form-control" value="">
            </div>
        </div>


        <div class="col-sm-3" style="margin-top: 26px;">
            <?php echo form_dropdown('employeesearch', $employeedrop, '', 'class="form-control select2" onchange="startMasterSearch()" id="employeesearch"'); ?>
        </div>
        <br>

    </div>
    <br>
</div>
<div class="row">
    <div class="col-md-5">
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right"
                onclick="fetchPage('system/iou/create_iou_voucher',null,'Add New IOU Voucher','IOU');"><i
                    class="fa fa-plus"></i> Create IOU Voucher
        </button>
    </div>
</div>
<div class="row" style="margin-top: 2%;">
    <div class="col-sm-4" style="margin-left: 2%;">

        <div class="col-sm-12">
            <div class="box-tools">
                <div class="has-feedback">
                    <input name="searchTask" type="text" class="form-control input-sm"
                           placeholder="Enter Your Text Here"
                           id="searchTask" onkeypress="startMasterSearch()">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-1">
        <div class="col-sm-1 hide" id="search_cancel">
                    <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clearSearchFilter()"><img
                                    src="<?php echo base_url("images/crm/cancel-search.gif") ?>"></a></span>
        </div>
    </div>
    <div class="col-md-2">
        <?php echo form_dropdown('iouvoucherstatus', array('' => 'Status', '1' => 'Draft', '2' => 'Confirmed', '3' => 'Approved'), '', 'class="form-control" onchange="startMasterSearch()" id="iouvoucherstatus"'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive mailbox-messages" id="iovoucehrmasterview">
            <!-- /.table -->
        </div>

    </div>
</div>


<div class="modal fade bs-example-modal-sm" role="dialog" aria-labelledby="myLargeModalLabel"
     id="iou_voucher_model_close">
    <div class="modal-dialog modal-lg" style="width: 55%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true" onclick="getiouvouchertable()">&times;</span></button>
                <h4 class="modal-title" id="PageViewTitle"></h4>
            </div>
            <?php echo form_open('', 'role="form" id="iou_voucher_master_form"'); ?>
            <input type="hidden" id="voucherid" name="voucherid">
            <input type="hidden" id="balanceamt" name="balanceamt">
            <div class="modal-body">

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">Voucher Date</label>
                    </div>
                    <div class="form-group col-sm-5">
                         <span class="input-req" title="Required Field">
                        <div class="input-group PVchequeDatepick">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="RVdate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="RVdate"
                                   class="form-control">
                        </div>
                        <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-3">
                        <label class="title">Bank or Cash</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <span class="input-req" title="Required Field">
                            <?php echo form_dropdown('RVbankCode', company_bank_account_drop(), '', 'class="form-control select2" id="RVbankCode" onchange="set_payment_method()" required'); ?>
                            <span class="input-req-inner"></span>
                    </div>
                </div>
                <div class="row paymentmoad" style="margin-top: 10px;">

                    <div class="form-group col-sm-3">
                        <label class="title">Cheque Number</label>
                    </div>
                    <div class="form-group col-sm-5">
                <span class="input-req" title="Required Field">
                  <input type="text" name="RVchequeNo" id="RVchequeNo" class="form-control">
                    <span class="input-req-inner"></span>
                </span>
                    </div>
                </div>

                <div class="row paymentmoad" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">Cheque Date</label>
                    </div>
                    <div class="form-group col-sm-5">
                         <span class="input-req" title="Required Field">
                        <div class="input-group PVchequeDatepick">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="RVchequeDate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="RVchequeDate"
                                   class="form-control">
                        </div>
                        <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="savevoucher()" class="btn btn-sm btn-primary"><span
                            class="glyphicon glyphicon-floppy-disk"
                            aria-hidden="true"></span> Save
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<div class="modal fade bs-example-modal-sm" role="dialog" aria-labelledby="myLargeModalLabel"
     id="iou_voucher_model_close_paymentvoucher">
    <div class="modal-dialog modal-lg" style="width: 55%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true" onclick=" getiouvouchertable()">&times;</span></button>
                <h4 class="modal-title" id="PageViewTitlepayment"></h4>
            </div>
            <?php echo form_open('', 'role="form" id="iou_voucher_master_form_paymentvoucher"'); ?>
            <input type="hidden" id="voucheridpayment" name="voucheridpayment">
            <input type="hidden" id="balanceamtpaymentvoucher" name="balanceamtpaymentvoucher">
            <div class="modal-body">

                <div class="row" style="margin-top: 10px;">

                    <div class="form-group col-sm-3">
                        <label class="title">Payment Voucher Date</label>
                    </div>
                    <div class="form-group col-sm-5">
                         <span class="input-req" title="Required Field">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="PVdate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="PVdate" class="form-control">
                        </div>
                        <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-sm-3">
                        <label class="title">Bank or Cash</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <span class="input-req" title="Required Field">
                            <?php echo form_dropdown('PVbankCode', company_bank_account_drop(), '', 'class="form-control select2" id="PVbankCode" onchange="fetch_cheque_number(this.value)"'); ?>
                            <span class="input-req-inner"></span>
                    </div>
                </div>
                <div class="row paymentType hide" style="margin-top: 10px;">

                    <div class="form-group col-sm-3">
                        <label class="title">Payment Type</label>
                    </div>
                    <div class="form-group col-sm-5">
                <span class="input-req" title="Required Field">
                 <?php echo form_dropdown('paymentType', array('' => $this->lang->line('common_select_type')/*'Select Type'*/, '1' => 'Cheque ', '2' => 'Bank Transfer'), ' ', 'class="form-control select2" id="paymentType" onchange="show_payment_method(this.value)"'); ?>
                    <span class="input-req-inner"></span>
                </span>
                    </div>
                </div>

                <div class="row paymentmoad" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">Payee Only</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <div class="skin skin-square">
                            <div class="skin-section extraColumns"><input id="accountPayeeOnly" type="checkbox"
                                                                          data-caption="" class="columnSelected"
                                                                          name="accountPayeeOnly" value="1"><label
                                        for="checkbox">&nbsp;</label></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="hide" id="employeerdirect">
                        <div class="form-group col-sm-3">
                            <label class="title">Bank Transfer Details</label>
                        </div>
                        <div class="form-group col-sm-5">
                        <textarea class="form-control" rows="3" name="bankTransferDetails"
                                  id="bankTransferDetails"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row paymentmoad" style="margin-top: 10px;">

                    <div class="form-group col-sm-3">
                        <label class="title">Cheque Number</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <span class="input-req" title="Required Field">
                                <input type="text" name="PVchequeNo" id="PVchequeNo" class="form-control">
                            <span class="input-req-inner"></span></span>

                    </div>
                </div>
                <div class="row paymentmoad" style="margin-top: 10px;">

                    <div class="form-group col-sm-3">
                        <label class="title">Cheque Date</label>
                    </div>
                    <div class="form-group col-sm-5">
                         <span class="input-req" title="Required Field">
                        <div class="input-group PVchequeDatepick">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="PVchequeDate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="PVchequeDate" class="form-control">
                        </div>
                        <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="savevoucherpayment()" class="btn btn-sm btn-primary"><span
                            class="glyphicon glyphicon-floppy-disk"
                            aria-hidden="true"></span> Save
                </button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="ap_closed_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ap_closed_user_label">Closed IOU Vouchers</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="voucheridclosediou" id="voucheridclosediou">
                <input type="hidden" name="typevoucher" id="typevoucher">
                <dl class="dl-horizontal">
                    <dt>Document code :</dt>
                    <dd id="closed_document_code">...</dd>
                    <dt>Document Date :</dt>
                    <dd id="closed_document_date">...</dd>
                    <dt>Closed Date :</dt>
                    <dd id="closed_date">...</dd>
                    <dt>Closed By :</dt>
                    <dd id="closed_by">...</dd>
                    <dt>Voucher Type :</dt>
                    <dd id="vouchertype">...</a></dd>
                    <dt>Voucher Amount :</dt>
                    <dd id="voucheramountdirect">
                        <a href="#" onclick="viewvoucher()"><label id="voucheramount"></label> </a>

                    </dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="voucher_closed_drilldown" tabindex="2" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" style="width: 80%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Closed Vouchers<span class="myModalLabel"></span>
                </h4>
            </div>
            <div class="modal-body">
                <div id="voucherdrilldown"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script type="text/javascript">
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
    var voucheridiou;
    var banktransferdet;
    $(document).ready(function () {
        $(".paymentmoad").hide();
        $('.select2').select2();
        $('.headerclose').click(function () {
            fetchPage('system/iou/iou_voucher', '', 'IOU Voucher');
        });
        getiouvouchertable();
        Inputmask().mask(document.querySelectorAll("input"));

        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.PVchequeDatepick').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {

        });


    });

    $('#searchTask').bind('input', function () {
        startMasterSearch();
    });


    function getiouvouchertable(filtervalue) {
        var searchTask = $('#searchTask').val();
        var staus = $('#iouvoucherstatus').val();
        var datefrom = $('#voucherDatefrom').val();
        var dateto = $('#voucherDateto').val();
        var employee = $('#employeesearch').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                'q': searchTask,
                'filtervalue': filtervalue,
                'staus': staus,
                'datefrom': datefrom,
                'dateto': dateto,
                'employee': employee
            },
            url: "<?php echo site_url('iou/load_iou_voucher_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#iovoucehrmasterview').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function delete_iou_voucher(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_delete');?>", /*You want to delete this record!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_delete');?>", /*Delete*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'voucherAutoID': id},
                    url: "<?php echo site_url('iou/delete_iou_voucher_delete'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        refreshNotifications(true);
                        stopLoad();
                        getiouvouchertable();
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getiouvouchertable();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('.donorsorting').removeClass('selected');
        $('#searchTask').val('');
        $('#voucherDatefrom').val('');
        $('#voucherDateto').val('');
        $('#employeesearch').val(null).trigger("change");
        $('#iouvoucherstatus').val('');
        $('#sorting_1').addClass('selected');
        getiouvouchertable();
    }


    function reopen_iou_voucher(id) {
        swal({
                title: "Are you sure?",
                text: "You want to reopen IOU Voucher!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'voucherAutoID': id},
                    url: "<?php echo site_url('iou/reopen_iou_voucher'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            getiouvouchertable();
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function generatevoucher(voucherid, expamt, vouchername, type) {
        swal({
                title: "Are you sure?",
                text: "You want to Generate A " + vouchername,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'voucherAutoID': voucherid},
                    url: "<?php echo site_url('iou/generatevoucher'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        if (data['error'] == 0) {
                            myAlert('e', data['message']);
                        } else if (data['error'] == 1) {
                            iouvouchergeneratemodel(voucherid, vouchername, type, expamt)

                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function referback_iouvoucher(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_refer_back');?>", /*You want to refer back!*/
                type: "warning", /*warning*/
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_yes');?>", /*Yes*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'returnAutoID': id},
                    url: "<?php echo site_url('iou/iou_referback'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            getiouvouchertable();
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    $('.datepic').datetimepicker({
        useCurrent: false,
        format: date_format_policy,
    }).on('dp.change', function (e) {
        startMasterSearch();
    });

    function iouvouchergeneratemodel(voucherid, vouchername, type, expamt) {
        if (type == 1) {
            $('#voucherid').val(voucherid);
            $('#balanceamt').val(expamt);
            $('#PageViewTitle').text('Generate ' + vouchername);
            $('#iou_voucher_master_form')[0].reset();
            $('#RVbankCode').val('');
            $('#iou_voucher_model_close').modal('show');
        }
        else if (type == 2) {
            $('#PVbankCode').val(null).trigger('change');
            $('#paymentType').val(null).trigger('change');
            $('#voucheridpayment').val(voucherid);
            $('#balanceamtpaymentvoucher').val(expamt);
            $('#PageViewTitlepayment').text('Generate ' + vouchername);
            $('#iou_voucher_master_form_paymentvoucher')[0].reset();
            $('#iou_voucher_model_close_paymentvoucher').modal('show');
        }

    }

    function set_payment_method() {
        val = $('#RVbankCode option:selected').text();
        res = val.split(" | ")
        if (res[5] == 'Cash') {
            $(".paymentmoad").hide();
        } else {
            $(".paymentmoad").show();
        }
    }

    function savevoucher() {
        var data = $('#iou_voucher_master_form').serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('iou/save_iou_voucher_receipt_voucher'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1], data[2]);
                if (data[0] == 's') {
                    getiouvouchertable();
                    $('#iou_voucher_model_close').modal('hide');
                }
            },
            error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

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
                        $("#PVchequeNo").val((parseFloat(data['bankCheckNumber']) + 1));

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
            $('#bankTransferDetails ~ iframe').contents().find('.wysihtml5-editor').html(invoiceNote);
        } else {
            $('#employeerdirect').addClass('hide');
            $('.banktrans').addClass('hide');
            $(".paymentmoad").hide();
        }


    }

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

    function savevoucherpayment() {
        var data = $('#iou_voucher_master_form_paymentvoucher').serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('iou/save_iou_voucher_payment_voucher'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1], data[2]);
                if (data[0] == 's') {
                    getiouvouchertable();
                    $('#iou_voucher_model_close_paymentvoucher').modal('hide');
                }
            },
            error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function closeiouvoucher(voucherid) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                text: "You want to Close this Voucher!", /*You want to refer back!*/
                type: "warning", /*warning*/
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_yes');?>", /*Yes*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'voucherid': voucherid},
                    url: "<?php echo site_url('iou/close_iou_voucher'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1], data[2]);
                        if (data[0] == 's') {
                            getiouvouchertable();
                            $('#iou_voucher_model_close').modal('hide');
                        }
                    },
                    error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        stopLoad();
                        refreshNotifications(true);
                    }
                });
            });
    }

    function closedvoucherdetails(voucherid) {

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'voucherid': voucherid},
            url: "<?php echo site_url('iou/fetch_iou_closed_details'); ?>",
            success: function (data) {
                if (data) {
                    $('#closed_document_code').html(data['iouCode']);
                    $('#voucheridclosediou').val(data['balanceVoucherAutoID']);
                    $('#typevoucher').val(data['balanceVoucherType']);
                    $('#closed_document_date').html(data['voucherDate']);
                    $('#closed_date').html(data['closedDate']);
                    $('#closed_by').html(data['employeenameclosed']);
                    $('#vouchertype').html(data['Vouchertype']);

                    $('#voucheramount').html(parseFloat(data['balanceVoucherAmount']).formatMoney(data['transactionCurrencyDecimalPlaces'], '.', ','));

                    $('#ap_closed_user').modal('show');
                }
                ;
            }
        });

    }

    function viewvoucher() {
        var voucherid = $('#voucheridclosediou').val();
        var type = $('#typevoucher').val();
        if (voucherid) {
            $.ajax({
                async: true,
                type: 'POST',
                dataType: 'html',
                data: {'VoucherAutoId': voucherid, 'html': 'html', 'type': type},
                url: '<?php echo site_url('iou/load_pv_conformation'); ?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $("#voucherdrilldown").html(data);
                    $('#voucher_closed_drilldown').modal("show");
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }


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