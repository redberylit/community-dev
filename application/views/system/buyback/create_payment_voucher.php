<?php echo head_page($_POST['page_name'], false);
$this->load->helper('buyback_helper');
$date_format_policy = date_format_policy();
$current_date = current_format_date();

$farms_arr = load_all_farms();
$currency_arr = all_currency_new_drop();//array('' => 'Select Currency');
$location_arr = all_delivery_location_drop();
$location_arr_default = default_delivery_location_drop();
$financeyear_arr = all_financeyear_drop(true);
$uom_arr = array('' => 'Select UOM');
$batch_arr = array('' => 'Select Batch');

$gl_code_arr = company_PL_account_drop();
$segment_arr = fetch_segment();
$curency = "LKR";
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
    <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - Voucher Header</a>
    <a class="btn btn-default btn-wizard" href="#step2" data-toggle="tab">Step 2 -
        Voucher Detail</a>
    <a class="btn btn-default btn-wizard" href="#step3" onclick="load_confirmation();" data-toggle="tab">Step 3 -
        Voucher Confirmation</a>
</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="paymentvoucher_header_form"'); ?>
        <input type="hidden" name="pvMasterAutoID" id="pvMasterAutoID_edit">

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>VOUCHER HEADER</h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Voucher Type</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                    <?php echo form_dropdown('PVtype', array('' => 'Select Type', '1' => 'Payment Voucher', '2' => 'Receipt Voucher', '3' => 'Settlement'), '', 'class="form-control" id="PVtype"'); ?>
                            <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Document Date</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="documentDate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="documentDate" class="form-control" required>
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
                        <?php echo form_dropdown('segment', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="segment"'); ?>
                            <span class="input-req-inner"></span></span>

                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Reference No</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" class="form-control " id="referenceno" name="referenceno">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Farm</label>
                    </div>
                    <div class="form-group col-sm-4">
                            <span class="input-req" title="Required Field">
                <?php echo form_dropdown('farmID', $farms_arr, '', 'class="form-control select2" id="farmID" onchange="farmMasterChange(this.value)" required'); ?>
                                <span class="input-req-inner"></span></span>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Currency</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                        <?php echo form_dropdown('transactionCurrencyID', $currency_arr, '', 'class="form-control select2" id="transactionCurrencyID"  required'); ?>
                            <span class="input-req-inner"></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Financial Year</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                <?php echo form_dropdown('financeyear', $financeyear_arr, $this->common_data['company_data']['companyFinanceYearID'], 'class="form-control" id="financeyear" required onchange="fetch_finance_year_period(this.value)"'); ?>
                            <span class="input-req-inner"></span>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="title">Financial Period</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                <?php echo form_dropdown('financeyear_period', array('' => 'Financial Period'), '', 'class="form-control" id="financeyear_period" required'); ?>
                            <span class="input-req-inner"></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div id="div_ClassBank">
                        <div class="form-group col-sm-2">
                            <label class="title">Bank or Cash</label>
                        </div>
                        <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                                <?php echo form_dropdown('PVbankCode', company_bank_account_drop(), '', 'class="form-control select2" id="PVbankCode" onchange="fetch_cheque_number(this.value)"'); ?>
                            <span class="input-req-inner"></span></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2 settleMentDiv">
                            <label class="title">Batch</label>
                        </div>
                        <div class="form-group col-sm-4 settleMentDiv">
                            <div class="div_loadBatch_settlementClosed">
                                <?php echo form_dropdown('batchMasterID', $batch_arr, 'Each', 'class="form-control select2" '); ?>
                            </div>
                        </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Memo</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <textarea class="form-control" rows="3" id="narration" name="narration"></textarea>
                    </div>
                </div>
                <div class="row paymentmoad" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Cheque Number</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                                <input type="text" name="PVchequeNo" id="PVchequeNo" class="form-control">
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
                                   value="<?php echo $current_date; ?>" id="PVchequeDate" class="form-control">
                        </div>
                        <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-12">
                        <button class="btn btn-primary pull-right" type="submit">Save & Next</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <br>
    </div>
    <div id="step2" class="tab-pane">
        <div id="tab2_type_paymentVoucher">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="active"><a data-toggle="tab" href="#tab_sub_1" aria-expanded="false">Expenses</a></li>
                    <li class=""><a data-toggle="tab" href="#tab_sub_2" aria-expanded="false">Advance</a></li>
                    <li class=""><a data-toggle="tab" href="#tab_sub_3" aria-expanded="false">Batch</a></li>
                    <li class=""><a data-toggle="tab" href="#tab_sub_4" aria-expanded="false">Loan</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab_sub_1" class="tab-pane active">
                        <div class="row addTableView">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>EXPENSE</h2>
                                </header>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary pull-right"
                                                onclick="paymentVoucher_expense_modal()">
                                            <i class="fa fa-plus"></i> Add Expense
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-11">
                                        <div id="paymentVoucher_expense"></div>
                                    </div>
                                    <div class="col-sm-1">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab_sub_2" class="tab-pane">
                        <div class="row addTableView">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>ADVANCE</h2>
                                </header>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary pull-right"
                                                onclick="paymentVoucher_advance_modal()">
                                            <i class="fa fa-plus"></i> Add Advance
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-11">
                                        <div id="paymentVoucher_advance"></div>
                                    </div>
                                    <div class="col-sm-1">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab_sub_3" class="tab-pane">
                        <div class="row addTableView">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>BATCH</h2>
                                </header>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary pull-right"
                                                onclick="paymentVoucher_batch_modal()">
                                            <i class="fa fa-plus"></i> Add Batch
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-11">
                                        <div id="paymentVoucher_batch"></div>
                                    </div>
                                    <div class="col-sm-1">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab_sub_4" class="tab-pane">
                        <div class="row addTableView">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>Loan</h2>
                                </header>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary pull-right"
                                                onclick="paymentVoucher_loan_modal()">
                                            <i class="fa fa-plus"></i> Add Loan
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-11">
                                        <div id="paymentVoucher_loan"></div>
                                    </div>
                                    <div class="col-sm-1">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab2_type_receiptVoucher">
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>DEPOSIT</h2>
                    </header>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary pull-right"
                                    onclick="receiptVoucher_income_single_modal()">
                                <i class="fa fa-plus"></i> Add Deposit
                            </button>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-11">
                            <div id="receiptVoucher_income"></div>
                        </div>
                        <div class="col-sm-1">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab2_type_settlementVoucher">
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>BATCH SETTLEMENT</h2>
                    </header>
                    <?php echo form_open('', 'role="form" id="batchSettlement_form"'); ?>
                    <input type="hidden" id="settlement_pvMasterAutoID" name="pvMasterAutoID">
                    <input type="hidden" id="settlement_BatchID" name="BatchID">

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <div id="settlementVoucher_all"></div>
                        </div>
                    </div>
                    </form>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <button class="btn btn-primary pull-right" type="button"
                                    onclick="Save_BatchSettlement_Amount()">Save
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="step3" class="tab-pane">
        <div id="confirm_body"></div>
        <hr>
        <div id="conform_body_attachement">
            <h4 class="modal-title" id="purchaseOrder_attachment_label">Modal title</h4>
            <br>

            <div class="table-responsive" style="width: 60%">
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
                    <tbody id="purchaseOrder_attachment" class="no-padding">
                    <tr class="danger">
                        <td colspan="5" class="text-center">No Attachment Found</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev">Previous</button>
            <button class="btn btn-primary " onclick="save_draft()">Save as Draft</button>
            <button class="btn btn-success submitWizard" onclick="confirmation()">Confirm</button>
        </div>
    </div>
</div>

<div aria-hidden="true" role="dialog" id="pv_expense_detail_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Expenses Detail</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="pv_expense_detail_add_form" class="form-horizontal">
                    <table class="table table-bordered table-condensed no-color" id="pv_expense_detail_add_table">
                        <thead>
                        <tr>
                            <th style="width: 350px;">Batch <?php required_mark(); ?></th>
                            <th style="width: 350px;">GL Code <?php required_mark(); ?></th>
                            <th>Segment</th>
                            <th style="width: 150px;">Amount <span
                                    class="currency"> (<?php echo $curency; ?>)</span> <?php required_mark(); ?></th>
                            <th style="width: 200px;">Description <?php required_mark(); ?></th>
                            <th style="width: 40px;">
                                <button type="button" class="btn btn-primary btn-xs" onclick="add_more_expense()"><i
                                        class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="div_loadBatch">
                                    <?php echo form_dropdown('batchMasterID', $batch_arr, 'Each', 'class="form-control select2" '); ?>
                                </div>
                            </td>
                            <td>
                                <?php echo form_dropdown('gl_code[]', $gl_code_arr, '', 'class="form-control select2"  required'); ?>
                            </td>
                            <td>
                                <?php echo form_dropdown('segment_gl[]', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2 segment_glAdd" '); ?>

                            </td>
                            <td>
                                <input type="text" name="amount[]" id="amount" class="form-control number">
                            </td>
                            <td>
                                <textarea class="form-control" rows="1" id="description"
                                          name="description[]"></textarea>
                            </td>
                            <td class="remove-td" style="vertical-align: middle;text-align: center"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="savePaymentVoucher_Expenses()">Save
                    changes
                </button>
            </div>

        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="pv_expense_detail_edit_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Expenses Detail</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="pv_expense_detail_edit_form" class="form-horizontal">
                    <input type="hidden" id="pvDetailID_edit_expense" name="pvDetailID">
                    <table class="table table-bordered table-condensed no-color"
                           id="edit_payment_voucher_table">
                        <thead>
                        <tr>
                            <th style="width: 350px;">Batch <?php required_mark(); ?></th>
                            <th style="width: 350px;">GL Code <?php required_mark(); ?></th>
                            <th>Segment</th>
                            <th style="width: 150px;">Amount <span
                                    class="currency"> (<?php echo $curency; ?>
                                    )</span> <?php required_mark(); ?></th>
                            <th style="width: 200px;">Description <?php required_mark(); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="div_loadBatch_single">
                                    <?php echo form_dropdown('batchMasterID', $batch_arr, 'Each', 'class="form-control select2" '); ?>
                                </div>
                            </td>
                            <td>
                                <?php echo form_dropdown('gl_code', $gl_code_arr, '', 'class="form-control select2" id="edit_gl_code" required '); ?>
                            </td>
                            <td>
                                <?php echo form_dropdown('segment_gl', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="edit_segment_gl" '); ?>

                            </td>
                            <td>
                                <input type="text" name="amount" id="edit_amount" class="form-control number">
                            </td>
                            <td>
                                        <textarea class="form-control" rows="1" id="edit_description"
                                                  name="description"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="Update_PaymentVoucher_Expenses()">Save
                    changes
                </button>
            </div>

        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="pv_advance_detail_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment Voucher Advance Detail</h4>
            </div>
            <form role="form" id="pv_advance_detail_add_form" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Batch</label>

                        <div class="col-sm-6">
                            <div class="div_loadBatch_single">
                                <?php echo form_dropdown('batchMasterID', $batch_arr, 'Each', 'class="form-control select2" '); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Amount </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="amount" id="advance_amount" placeholder="0.00"
                                       class="form-control number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Description </label>

                        <div class="col-sm-6">
                                    <textarea class="form-control" rows="2" id="advance_description"
                                              name="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="button" onclick="Update_PaymentVoucher_Advance()">Save
                        changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div aria-hidden="true" role="dialog" id="pv_batch_detail_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment Voucher Batch Detail</h4>
            </div>
            <form role="form" id="pv_batch_detail_add_form" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Batch</label>

                        <div class="col-sm-6">
                            <div class="div_loadBatch_allClosed_all">
                                <?php echo form_dropdown('batchMasterID', $batch_arr, 'Each', 'class="form-control select2" '); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Balance Amount </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="balamount" id="balamount" placeholder="0.00"
                                       class="form-control number" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Amount </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="amount" id="advance_amount_batch" placeholder="0.00"
                                       class="form-control number" onchange="validateBlanceAmount(this)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Description </label>

                        <div class="col-sm-6">
                                    <textarea class="form-control" rows="2" id="advance_description_batch"
                                              name="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="button" onclick="Update_PaymentVoucher_update()">Save
                        changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div aria-hidden="true" role="dialog" id="rv_income_detail_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Income Detail</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="rv_income_detail_add_form" class="form-horizontal">
                    <table class="table table-bordered table-condensed no-color" id="rv_income_detail_add_table">
                        <thead>
                        <tr>
                            <th style="width: 350px;">GL Code <?php required_mark(); ?></th>
                            <th>Segment</th>
                            <th style="width: 150px;">Amount <span
                                    class="currency"> (<?php echo $curency; ?>)</span> <?php required_mark(); ?></th>
                            <th style="width: 200px;">Description <?php required_mark(); ?></th>
                            <th style="width: 40px;">
                                <button type="button" class="btn btn-primary btn-xs" onclick="add_more_income()"><i
                                        class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <?php echo form_dropdown('gl_code_income[]', $gl_code_arr, '', 'class="form-control select2 gl_code_income"  required'); ?>
                            </td>
                            <td>
                                <?php echo form_dropdown('segment_gl[]', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2 segment_gl_income" '); ?>

                            </td>
                            <td>
                                <input type="text" name="amount[]" id="amount" class="form-control number">
                            </td>
                            <td>
                                <textarea class="form-control" rows="1" id="description"
                                          name="description[]"></textarea>
                            </td>
                            <td class="remove-td" style="vertical-align: middle;text-align: center"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="saveReceiptVoucher_Income()">Save
                    changes
                </button>
            </div>

        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="rv_income_detail_edit_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Expenses Detail</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="rv_income_detail_edit_form" class="form-horizontal">
                    <input type="hidden" id="pvDetailID_edit_income_rv" name="pvDetailID">
                    <table class="table table-bordered table-condensed no-color"
                           id="edit_payment_voucher_table">
                        <thead>
                        <tr>
                            <th style="width: 350px;">GL Code <?php required_mark(); ?></th>
                            <th>Segment</th>
                            <th style="width: 150px;">Amount <span
                                    class="currency"> (<?php echo $curency; ?>
                                    )</span> <?php required_mark(); ?></th>
                            <th style="width: 200px;">Description <?php required_mark(); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <?php echo form_dropdown('gl_code', $gl_code_arr, '', 'class="form-control select2" id="edit_gl_code_rv_income" required '); ?>
                            </td>
                            <td>
                                <?php echo form_dropdown('segment_gl', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="edit_segment_gl_rv_income" '); ?>

                            </td>
                            <td>
                                <input type="text" name="amount" id="edit_amount_rv_income" class="form-control number">
                            </td>
                            <td>
                                <textarea class="form-control" rows="1" id="edit_description_rv_income"
                                          name="description"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="Update_ReceiptVoucher_Income()">Update Changes
                </button>
            </div>

        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="rv_income_detail_single_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Receipt Voucher Deposit - Detail</h4>
            </div>
            <form role="form" id="rv_income_detail_single_add_frm" class="form-horizontal">
                <input type="hidden" name="pvDetailID" id="rv_income_single_edit">

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Pending Deposit Amount </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="balance_amount" id="deposit_balance_amount" placeholder="0.00"
                                       class="form-control number" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Amount </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="amount" id="rv_income_amount" placeholder="0.00"
                                       class="form-control number" onkeyup="validate_rv_depositAmount(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Description </label>
                        <div class="col-sm-6"><textarea class="form-control" rows="2" id="rv_income_description"
                                              name="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="button" onclick="Update_receiptVoucher_single_income()">Save
                        changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="pv_loan_detail_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment Voucher Loan Detail</h4>
            </div>
            <form role="form" id="pv_loan_detail_add_form" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group hidden">
                        <label class="col-sm-4 control-label">Type</label>

                        <div class="col-sm-6">
                            <?php echo form_dropdown('loanType', array('' => 'Select Type', '1' => 'Direct', '2' => 'Batch Matching'), '1', 'class="form-control" onchange="loanBaseChange(this.value)" id="loanType"'); ?>
                        </div>
                    </div>
                    <div class="form-group loan_batch_div hide">
                        <label class="col-sm-4 control-label">Batch</label>

                        <div class="col-sm-6">
                            <div class="div_loadBatch_allClosed">
                                <?php echo form_dropdown('batchMasterID', $batch_arr, 'Each', 'class="form-control select2" '); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group loan_batch_div hide">
                        <label class="col-sm-4 control-label">Wages Payable </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="loan_amount" id="loan_wages_amount" placeholder="0.00"
                                       class="form-control number" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group loan_batch_div hide">
                        <label class="col-sm-4 control-label">Balance </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="balance_amount" id="loan_balance_amount" placeholder="0.00"
                                       class="form-control number" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Amount </label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon"><span
                                        class="currency"> (<?php echo $curency; ?>
                                        )</span>
                                </div>
                                <input type="text" name="amount" id="loan_amount" placeholder="0.00"
                                       class="form-control number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Description </label>

                        <div class="col-sm-6">
                            <textarea class="form-control" rows="2" id="loan_description"
                                      name="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="button" onclick="Save_PaymentVoucher_Loan()">Save
                        Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var documentCurrency;
    var pvMasterAutoID;
    var currency_decimal = 1;
    var batchMasterID;
    var pvDetailID;
    $(document).ready(function () {

        $('.select2').select2();

        $('.headerclose').click(function () {
            fetchPage('system/buyback/payment_voucher_master', '', 'Payment Voucher')
        });

        $(".paymentmoad").hide();

        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {
            //$('#purchase_order_form').bootstrapValidator('revalidateField', 'expectedDeliveryDate');
        });
        Inputmask().mask(document.querySelectorAll("input"));

        documentCurrency = null;
        pvMasterAutoID = null;
        pvDetailID = null;

        FinanceYearID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinanceYearID'])); ?>;
        DateFrom = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateFrom'])); ?>;
        DateTo = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateTo'])); ?>;
        periodID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinancePeriodID'])); ?>;
        fetch_finance_year_period(FinanceYearID, periodID);

        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            pvMasterAutoID = p_id;
            load_paymentVoucher_header();
            $("#a_link").attr("href", "<?php echo site_url('Grv/load_grv_conformation'); ?>/" + pvMasterAutoID);
            $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_entry_grv'); ?>/" + pvMasterAutoID + '/GRV');
            $('.btn-wizard').removeClass('disabled');
        } else {
            $('.btn-wizard').addClass('disabled');
            $('.addTableView').addClass('hide');
        }
        number_validation();
        currency_decimal = 2;

        $('#paymentvoucher_header_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                PVtype: {validators: {notEmpty: {message: 'Voucher Type is required.'}}},
                documentDate: {validators: {notEmpty: {message: 'Document Date is required.'}}},
                farmID: {validators: {notEmpty: {message: 'Farm is required.'}}},
                transactionCurrencyID: {validators: {notEmpty: {message: 'Currency is required.'}}},
                financeyear: {validators: {notEmpty: {message: 'Financial Year is required.'}}},
                financeyear_period: {validators: {notEmpty: {message: 'Financial Period is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            $("#PVtype").prop("disabled", false);
            $("#farmID").prop("disabled", false);
            $("#financeyear").prop("disabled", false);
            $("#financeyear_period").prop("disabled", false);
            $("#documentDate").prop("disabled", false);
            $("#segment").prop("disabled", false);
            $("#transactionCurrencyID").prop("disabled", false);
            $("#PVbankCode").prop("disabled", false);
            $("#PVchequeNo").prop("disabled", false);
            $("#PVchequeDate").prop("disabled", false);
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'delivery_location', 'value': $('#location option:selected').text()});
            data.push({'name': 'companyFinanceYear', 'value': $('#financeyear option:selected').text()});
            data.push({'name': 'currency_code', 'value': $('#transactionCurrencyID option:selected').text()});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('Buyback/save_payment_voucher_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        $('.addTableView').removeClass('hide');
                        pvMasterAutoID = data[2];
                        getPaymentVoucher_Expense_tableView(pvMasterAutoID);
                        getPaymentVouche_Advance_tableView(pvMasterAutoID);
                        getPaymentVoucher_Batch_tableView(pvMasterAutoID);
                        getReceiptVoucher_deposit_tableView(pvMasterAutoID);
                        getPaymentVoucher_loan_tableView(pvMasterAutoID);
                        $('.btn-wizard').removeClass('disabled');
                        $('[href=#step2]').tab('show');
                        getSettlementVoucher_tableView();
                        $('#settlement_pvMasterAutoID').val(pvMasterAutoID);
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

        $('#pv_advance_detail_add_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                //batchMasterID : {validators : {notEmpty:{message:'Batch is required.'}}},
                amount: {validators: {notEmpty: {message: 'Amount is required.'}}},
                description: {validators: {notEmpty: {message: 'Description is required.'}}},
            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
            //data.push({'name': 'payVoucherDetailAutoID', 'value': payVoucherDetailAutoID});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('Buyback/save_paymentVoucher_advance'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        getPaymentVouche_Advance_tableView(pvMasterAutoID);
                        $('#pv_advance_detail_add_modal').modal('hide');

                    } else {
                        $('.btn-primary').prop('disabled', false);
                    }
                }, error: function () {
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


    });

    function farmMasterChange(farmID) {
        fetch_farmer_currencyID(farmID);
        fetch_farmBatch(farmID);
        fetch_farmBatch_array(farmID);
        fetch_farmBatches_closed(farmID)
        fetch_farmBatches_closed_all(farmID)
        fetch_farmBatches_settlement(farmID)
    }

    function getPaymentVoucher_Expense_tableView(pvMasterAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {pvMasterAutoID: pvMasterAutoID},
            url: "<?php echo site_url('Buyback/load_paymentVoucher_expense_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#paymentVoucher_expense').html(data);

                if ($('#expenrecid').html()=='NO RECORDS FOUND.') {

                }else{
                    if($('#PVtype').val()==1){
                        $('#PVtype').prop('disabled',true);
                        $("#farmID").prop("disabled", true);
                        $("#financeyear").prop("disabled", true);
                        $("#financeyear_period").prop("disabled", true);
                        $("#documentDate").prop("disabled", true);
                        $("#segment").prop("disabled", true);
                        $("#transactionCurrencyID").prop("disabled", true);
                        $("#PVbankCode").prop("disabled", true);
                        $("#PVchequeNo").prop("disabled", true);
                        $("#PVchequeDate").prop("disabled", true);
                    }
                }
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function getPaymentVouche_Advance_tableView(pvMasterAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {pvMasterAutoID: pvMasterAutoID},
            url: "<?php echo site_url('Buyback/load_paymentVoucher_advance_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#paymentVoucher_advance').html(data);
                if ($('#advancerecid').html()=='NO RECORDS FOUND.') {

                }else{
                    if($('#PVtype').val()==1){
                        $('#PVtype').prop('disabled',true);
                        $("#farmID").prop("disabled", true);
                        $("#financeyear").prop("disabled", true);
                        $("#financeyear_period").prop("disabled", true);
                        $("#documentDate").prop("disabled", true);
                        $("#segment").prop("disabled", true);
                        $("#transactionCurrencyID").prop("disabled", true);
                        $("#PVbankCode").prop("disabled", true);
                        $("#PVchequeNo").prop("disabled", true);
                        $("#PVchequeDate").prop("disabled", true);
                    }
                }
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }


    function getPaymentVoucher_Batch_tableView(pvMasterAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {pvMasterAutoID: pvMasterAutoID},
            url: "<?php echo site_url('Buyback/load_paymentVoucher_batch_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#paymentVoucher_batch').html(data);
                if ($('#batchrecid').html()=='NO RECORDS FOUND.') {

                }else{
                    if($('#PVtype').val()==1){
                        $('#PVtype').prop('disabled',true);
                        $("#farmID").prop("disabled", true);
                        $("#financeyear").prop("disabled", true);
                        $("#financeyear_period").prop("disabled", true);
                        $("#documentDate").prop("disabled", true);
                        $("#segment").prop("disabled", true);
                        $("#transactionCurrencyID").prop("disabled", true);
                        $("#PVbankCode").prop("disabled", true);
                        $("#PVchequeNo").prop("disabled", true);
                        $("#PVchequeDate").prop("disabled", true);
                    }
                }
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function getReceiptVoucher_deposit_tableView(pvMasterAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {pvMasterAutoID: pvMasterAutoID},
            url: "<?php echo site_url('Buyback/load_receiptVoucher_deposit_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#receiptVoucher_income').html(data);
                if ($('#depositrecid').html()=='NO RECORDS FOUND.') {

                }else{
                    if($('#PVtype').val()==2){
                        $('#PVtype').prop('disabled',true);
                        $("#farmID").prop("disabled", true);
                        $("#financeyear").prop("disabled", true);
                        $("#financeyear_period").prop("disabled", true);
                        $("#documentDate").prop("disabled", true);
                        $("#segment").prop("disabled", true);
                        $("#transactionCurrencyID").prop("disabled", true);
                        $("#PVbankCode").prop("disabled", true);
                        $("#PVchequeNo").prop("disabled", true);
                        $("#PVchequeDate").prop("disabled", true);

                    }
                }
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function getPaymentVoucher_loan_tableView(pvMasterAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {pvMasterAutoID: pvMasterAutoID},
            url: "<?php echo site_url('Buyback/load_paymentVoucher_loan_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#paymentVoucher_loan').html(data);
                if ($('#loanrecid').html()=='NO RECORDS FOUND.') {

                }else{
                    if($('#PVtype').val()==1){
                        $('#PVtype').prop('disabled',true);
                        $("#farmID").prop("disabled", true);
                        $("#financeyear").prop("disabled", true);
                        $("#financeyear_period").prop("disabled", true);
                        $("#documentDate").prop("disabled", true);
                        $("#segment").prop("disabled", true);
                        $("#transactionCurrencyID").prop("disabled", true);
                        $("#PVbankCode").prop("disabled", true);
                        $("#PVchequeNo").prop("disabled", true);
                        $("#PVchequeDate").prop("disabled", true);
                    }
                }
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function fetch_farmer_currencyID(farmID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'farmID': farmID},
            url: "<?php echo site_url('Buyback/fetch_farmer_currencyID'); ?>",
            success: function (data) {
                if (documentCurrency) {
                    $("#transactionCurrencyID").val(documentCurrency).change()
                } else {
                    if (data.farmerCurrencyID) {
                        $("#transactionCurrencyID").val(data.farmerCurrencyID).change();
                    }
                }

            }
        });
    }

    function fetch_farmerMasterDetails(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'farmID': farmID},
            url: "<?php echo site_url('Buyback/fetch_farmerDetails_For_dispatchNote'); ?>",
            success: function (data) {
                if (data) {
                    $("#contactPersonName").val(data.contactPerson);
                    $("#contactPersonNumber").val(data.phoneHome);
                }
            }
        });
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

    function load_paymentVoucher_header() {
        if (pvMasterAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'pvMasterAutoID': pvMasterAutoID},
                url: "<?php echo site_url('Buyback/load_paymentVoucher_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        pvMasterAutoID = data['pvMasterAutoID'];
                        $('#pvMasterAutoID_edit').val(pvMasterAutoID);
                        $('#pvMasterAutoID_edit_itemAdd').val(pvMasterAutoID);
                        $('#pvMasterAutoID_edit_itemEdit').val(pvMasterAutoID);
                        $('.currency').html('( ' + data['transactionCurrency'] + ' )');
                        $('#PVtype').val(data['PVtype']);
                        $('#documentDate').val(data['documentDate']);
                        $('#narration').val(data['PVNarration']);
                        $('#referenceno').val(data['referenceNo']);
                        $('#farmID').val(data['farmID']).change();
                        documentCurrency = data['transactionCurrencyID'];
                        $('#transactionCurrencyID').val(data['transactionCurrencyID']).change();
                        $('#segment').val(data['segmentID'] + '|' + data['segmentCode']).change();
                        $('#financeyear').val(data['companyFinanceYearID']);
                        $('#PVbankCode').val(data['PVbankCode']).change();
                        $('#PVchequeNo').val(data['PVchequeNo']);
                        $('#PVchequeDate').val(data['PVchequeDate']);
                        if (data['modeOfPayment'] == 0) {
                            $(".paymentmoad").show();
                        }
                        getReceiptVoucher_deposit_tableView(data['pvMasterAutoID']);
                        getPaymentVoucher_Expense_tableView(data['pvMasterAutoID']);
                        getPaymentVouche_Advance_tableView(data['pvMasterAutoID']);
                        getPaymentVoucher_Batch_tableView(data['pvMasterAutoID']);
                        getPaymentVoucher_loan_tableView(data['pvMasterAutoID']);
                        if (data['PVtype'] == 1) {
                            $('#tab2_type_receiptVoucher').addClass('hide');
                            $('#tab2_type_settlementVoucher').addClass('hide');
                            $('#tab2_type_paymentVoucher').removeClass('hide');
                            $('#div_ClassBank').removeClass('hide');
                            $('.settleMentDiv').addClass('hide');
                        } else if (data['PVtype'] == 2) {
                            $('#tab2_type_receiptVoucher').removeClass('hide');
                            $('#tab2_type_paymentVoucher').addClass('hide');
                            $('#tab2_type_settlementVoucher').addClass('hide');
                            $('#div_ClassBank').removeClass('hide');
                            $('.settleMentDiv').addClass('hide');
                        } else {
                            $('#tab2_type_receiptVoucher').addClass('hide');
                            $('#tab2_type_paymentVoucher').addClass('hide');
                            $('#tab2_type_settlementVoucher').removeClass('hide');
                            $('#div_ClassBank').addClass('hide');
                            $('.settleMentDiv').removeClass('hide');
                        }
                        fetch_finance_year_period(data['companyFinanceYearID'], data['companyFinancePeriodID']);
                        load_confirmation();
                        $('[href=#step3]').tab('show');
                        $('a[data-toggle="tab"]').removeClass('btn-primary');
                        $('a[data-toggle="tab"]').addClass('btn-default');
                        $('[href=#step3]').removeClass('btn-default');
                        $('[href=#step3]').addClass('btn-primary');
                        setTimeout(function () {
                            $('#settlement_batchMasterID').val(data['BatchID']).change();
                            getSettlementVoucher_tableView();
                        }, 1000);

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

    function load_confirmation() {
        if (pvMasterAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'pvMasterAutoID': pvMasterAutoID, 'html': true},
                url: "<?php echo site_url('Buyback/load_paymentVoucher_confirmation'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#confirm_body').html(data);
                    //$("#a_link").attr("href", "<?php echo site_url('Grv/load_grv_conformation'); ?>/" + pvMasterAutoID);
                    //$("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_entry_grv'); ?>/" + pvMasterAutoID + '/GRV');
                    attachment_modal_paymentVoucher(pvMasterAutoID, "Voucher", "BBPV");
                    refreshNotifications(true);
                }, error: function () {
                    stopLoad();
                    alert('An Error Occurred! Please Try Again.');
                    refreshNotifications(true);
                }
            });
        }
    }

    $(document).on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });

    function paymentVoucher_expense_modal() {
        if (pvMasterAutoID) {
            $('#pv_expense_detail_add_form')[0].reset();
            $('#pv_expense_detail_add_table tbody tr').not(':first').remove();
            $("#pv_expense_detail_add_modal").modal({backdrop: "static"});
        }
    }

    function receiptVoucher_modal() {
        if (pvMasterAutoID) {
            $('#rv_income_detail_add_form')[0].reset();
            $(".gl_code_income").val(null).trigger("change");
            $('#rv_income_detail_add_table tbody tr').not(':first').remove();
            $("#rv_income_detail_add_modal").modal({backdrop: "static"});
        }
    }

    function add_more_expense() {
        $('select.select2').select2('destroy');
        var appendData = $('#pv_expense_detail_add_table tbody tr:first').clone();
        appendData.find('input').val('');
        appendData.find('textarea').val('');
        appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
        $('#pv_expense_detail_add_table').append(appendData);
        var lenght = $('#pv_expense_detail_add_table tbody tr').length - 1;
        $(".select2").select2();
        number_validation();
    }

    function add_more_income() {
        $('select.select2').select2('destroy');
        var appendData = $('#rv_income_detail_add_table tbody tr:first').clone();
        appendData.find('input').val('');
        appendData.find('textarea').val('');
        appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
        $('#rv_income_detail_add_table').append(appendData);
        var lenght = $('#rv_income_detail_add_table tbody tr').length - 1;
        $(".select2").select2();
        number_validation();
    }

    function paymentVoucher_advance_modal() {
        if (pvMasterAutoID) {
            pvDetailID = null;
            $('#pv_advance_detail_add_form')[0].reset();
            $("#pv_advance_detail_add_modal").modal({backdrop: "static"});
        }
    }

    function paymentVoucher_batch_modal() {
        if (pvMasterAutoID) {
            pvDetailID = null;
            $('#pv_batch_detail_add_form')[0].reset();
            $("#pv_batch_detail_add_modal").modal({backdrop: "static"});
        }
    }

    function fetch_farmBatch(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/fetch_farm_BatchesDropdown'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('.div_loadBatch_single').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function fetch_farmBatches_closed(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/fetch_farm_BatchesDropdown_closed'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('.div_loadBatch_allClosed').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function fetch_farmBatches_closed_all(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/fetch_farm_BatchesDropdown_closed_all'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('.div_loadBatch_allClosed_all').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }
    function fetch_farmBatches_settlement(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/fetch_farm_BatchesDropdown_settlement'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('.div_loadBatch_settlementClosed').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function fetch_farmBatch_array(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/fetch_farm_BatchesDropdown_array'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('.div_loadBatch').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function save_dispatchNote_item_form() {
        var data = $("#dispatchNote_add_item_form").serializeArray();
        data.push({'name': 'uom', 'value': $('#UnitOfMeasureID option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('Buyback/save_dispatchNote_item_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('.uom_disabled').prop('disabled', true);
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    pvDetailID = null;
                    $('#dispatchNote_add_item_modal').modal('hide');
                    setTimeout(function () {
                        getDispatchDetailItem_tableView(pvMasterAutoID);
                    }, 300);
                }
            }, error: function () {
                $('.uom_disabled').prop('disabled', true);
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }


    function update_dispatchNote_item_form() {
        var data = $("#dispatchNote_edit_item_form").serializeArray();
        data.push({'name': 'uom', 'value': $('#UnitOfMeasureID option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('Buyback/update_dispatchNote_item_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $("#dispatchNote_edit_item_modal").modal('hide');
                    getDispatchDetailItem_tableView(pvMasterAutoID);
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }


    function clearitemAutoID(e, ths) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9) {
            //e.preventDefault();
        } else {
            $(ths).closest('tr').find('.itemAutoID').val('');
        }
    }

    function clearitemAutoIDEdit(e, ths) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9) {
            //e.preventDefault();
        } else {
            $(ths).closest('tr').find('#itemAutoID_edit').val('');
        }
    }

    function edit_paymentVoucher_expense(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/fetch_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            pvDetailID = data['pvDetailID'];
                            $('#pvDetailID_edit_expense').val(data['pvDetailID']);
                            $('#batchMasterID').val(data['BatchID']).change();
                            $('#edit_gl_code').val(data['GLAutoID']).change();
                            $('#edit_segment_gl').val(data['segmentID'] + '|' + data['segmentCode']).change();
                            $('#edit_amount').val(data['transactionAmount']);
                            $('#edit_description').val(data['comment']);
                            $("#pv_expense_detail_edit_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }

    function edit_receiptVoucher_income(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/fetch_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            pvDetailID = data['pvDetailID'];
                            $('#pvDetailID_edit_income_rv').val(data['pvDetailID']);
                            $('#edit_gl_code_rv_income').val(data['GLAutoID']).change();
                            $('#edit_segment_gl_rv_income').val(data['segmentID'] + '|' + data['segmentCode']).change();
                            $('#edit_amount_rv_income').val(data['transactionAmount']);
                            $('#edit_description_rv_income').val(data['comment']);
                            $("#rv_income_detail_edit_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }

    function edit_paymentVoucher_advance(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/fetch_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            pvDetailID = data['pvDetailID'];
                            $('#pvDetailID_edit_advance').val(data['pvDetailID']);
                            $('#advance_batchMasterID').val(data['BatchID']).change();
                            $('#advance_amount').val(data['transactionAmount']);
                            $('#advance_description').val(data['comment']);
                            $("#pv_advance_detail_add_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }


    function edit_paymentVoucher_loan(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/fetch_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            pvDetailID = data['pvDetailID'];
                            $('#pvDetailID_edit_advance').val(data['pvDetailID']);
                            $('#advance_batchMasterID').val(data['BatchID']).change();
                            $('#loan_amount').val(data['transactionAmount']);
                            $('#loan_description').val(data['comment']);
                            $("#pv_loan_detail_add_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }


    function edit_paymentVoucher_batch(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/fetch_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            pvDetailID = data['pvDetailID'];
                            $('#pvDetailID_edit_advance').val(data['pvDetailID']);
                            $('#advance_batchMasterID').val(data['BatchID']).change();
                            $('#advance_amount_batch').val(data['transactionAmount']);
                            $('#advance_description_batch').val(data['comment']);
                            $("#pv_batch_detail_add_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }

    function delete_paymentVoucher_expense(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/delete_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            $('#PVtype').prop('disabled',true);
                            $("#farmID").prop("disabled", true);
                            $("#financeyear").prop("disabled", true);
                            $("#financeyear_period").prop("disabled", true);
                            $("#documentDate").prop("disabled", true);
                            $("#segment").prop("disabled", true);
                            $("#transactionCurrencyID").prop("disabled", true);
                            $("#PVbankCode").prop("disabled", true);
                            $("#PVchequeNo").prop("disabled", true);
                            $("#PVchequeDate").prop("disabled", true);
                            getPaymentVoucher_Expense_tableView(pvMasterAutoID);
                            myAlert('s', 'Payment Voucher Expense Detail Deleted Successfully');
                            refreshNotifications(true);
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function delete_paymentVoucher_advance(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/delete_paymentVoucher_advance_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            $('#PVtype').prop('disabled',false);
                            $("#farmID").prop("disabled", false);
                            $("#financeyear").prop("disabled", false);
                            $("#financeyear_period").prop("disabled", false);
                            $("#documentDate").prop("disabled", false);
                            $("#segment").prop("disabled", false);
                            $("#transactionCurrencyID").prop("disabled", false);
                            $("#PVbankCode").prop("disabled", false);
                            $("#PVchequeNo").prop("disabled", false);
                            $("#PVchequeDate").prop("disabled", false);
                            getPaymentVoucher_Batch_tableView(pvMasterAutoID);
                            getPaymentVouche_Advance_tableView(pvMasterAutoID);
                            myAlert('s', 'Payment Voucher Advance Detail Deleted Successfully');
                            refreshNotifications(true);
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function attachment_modal_paymentVoucher(documentSystemCode, document_name, documentID) {
        if (documentSystemCode) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("Attachment/fetch_attachments"); ?>',
                dataType: 'json',
                data: {'documentSystemCode': documentSystemCode, 'documentID': documentID,'confirmedYN': 0},
                beforeSend: function () {
                    $('#purchaseOrder_attachment_label').html('<span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span> &nbsp;' + document_name + " Attachments");
                },
                success: function (data) {
                    $('#purchaseOrder_attachment').empty();
                    $('#purchaseOrder_attachment').append('' +data+ '');

                    //$("#attachment_modal").modal({backdrop: "static", keyboard: true});
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#ajax_nav_container').html(xhr.responseText);
                }
            });
        }
    }

    function save_draft() {
        if (pvMasterAutoID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to save this document!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Save as Draft"
                },
                function () {
                    fetchPage('system/buyback/payment_voucher_master', pvMasterAutoID, 'Payment Voucher');
                });
        }
    }

    function confirmation() {
        if (pvMasterAutoID) {
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
                        data: {'pvMasterAutoID': pvMasterAutoID},
                        url: "<?php echo site_url('Buyback/paymentVoucher_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            refreshNotifications(true);
                            if (data['error'] == 1) {
                                myAlert('e', data['message']);
                            } else {
                                myAlert('s', data['message']);
                                fetchPage('system/buyback/payment_voucher_master', pvMasterAutoID, 'Payment Voucher');
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }


    function fetch_cheque_number(GLAutoID) {
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
                    } else {
                        $(".paymentmoad").show();
                    }
                }
            }
        });
    }

    function savePaymentVoucher_Expenses() {
        var $form = $('#pv_expense_detail_add_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        $('select[name="gl_code[]"] option:selected').each(function () {
            data.push({'name': 'gl_code_des[]', 'value': $(this).text()})
        });
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/save_paymentVoucher_expense_multiple'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    payVoucherDetailAutoID = null;
                    $('#pv_expense_detail_add_form')[0].reset();
                    $("#segment_gl").select2("");
                    $("#gl_code").select2("");
                    getPaymentVoucher_Expense_tableView(pvMasterAutoID);
                    $('#pv_expense_detail_add_modal').modal('hide');
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

    function Update_PaymentVoucher_Expenses() {
        var $form = $('#pv_expense_detail_edit_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        data.push({'name': 'gl_code_des', 'value': $('#edit_gl_code option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/update_paymentVoucher_expense_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    pvDetailID = null;
                    $('#pv_expense_detail_edit_form')[0].reset();
                    $("#edit_segment_gl").select2("");
                    $("#edit_gl_code").select2("");
                    getPaymentVoucher_Expense_tableView(pvMasterAutoID);
                    $('#pv_expense_detail_edit_modal').modal('hide');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function Update_PaymentVoucher_Advance() {
        var $form = $('#pv_advance_detail_add_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/save_paymentVoucher_advance'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    pvDetailID = null;
                    $('#pv_advance_detail_add_form')[0].reset();
                    getPaymentVouche_Advance_tableView(pvMasterAutoID);
                    $('#pv_advance_detail_add_modal').modal('hide');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function Update_PaymentVoucher_update() {
        var $form = $('#pv_batch_detail_add_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/save_paymentVoucher_batch'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    pvDetailID = null;
                    $('#pv_batch_detail_add_form')[0].reset();
                    getPaymentVoucher_Batch_tableView(pvMasterAutoID);
                    $('#pv_batch_detail_add_modal').modal('hide');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function saveReceiptVoucher_Income() {
        var $form = $('#rv_income_detail_add_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        $('select[name="gl_code_income[]"] option:selected').each(function () {
            data.push({'name': 'gl_code_des[]', 'value': $(this).text()})
        });
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/save_receiptVoucher_income_multiple'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    payVoucherDetailAutoID = null;
                    $('#rv_income_detail_add_form')[0].reset();
                    $("#segment_gl").select2("");
                    $("#gl_code").select2("");
                    getReceiptVoucher_deposit_tableView(pvMasterAutoID);
                    $('#rv_income_detail_add_modal').modal('hide');
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

    function Update_ReceiptVoucher_Income() {
        var $form = $('#rv_income_detail_edit_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        data.push({'name': 'gl_code_des', 'value': $('#edit_gl_code_rv_income option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/update_receiptVoucher_income_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    pvDetailID = null;
                    $('#rv_income_detail_edit_form')[0].reset();
                    $("#edit_segment_gl").select2("");
                    $("#edit_gl_code").select2("");
                    getReceiptVoucher_deposit_tableView(pvMasterAutoID);
                    $('#rv_income_detail_edit_modal').modal('hide');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function delete_receiptVoucher_income(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/delete_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {

                            stopLoad();
                            $('#PVtype').prop('disabled',false);
                            $("#farmID").prop("disabled", false);
                            $("#financeyear").prop("disabled", false);
                            $("#financeyear_period").prop("disabled", false);
                            $("#documentDate").prop("disabled", false);
                            $("#segment").prop("disabled", false);
                            $("#transactionCurrencyID").prop("disabled", false);
                            $("#PVbankCode").prop("disabled", false);
                            $("#PVchequeNo").prop("disabled", false);
                            $("#PVchequeDate").prop("disabled", false);
                            getReceiptVoucher_deposit_tableView(pvMasterAutoID);
                            getPaymentVoucher_loan_tableView(pvMasterAutoID);
                            myAlert('s', 'Receipt Voucher Income Detail Deleted Successfully');
                            refreshNotifications(true);
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function receiptVoucher_income_single_modal() {
        if (pvMasterAutoID) {
            pvDetailID = null;
            $('.deposit_batch_div').addClass('hide');
            $(".batchMasterClass").val(null).trigger("change");
            $('#rv_income_detail_single_add_frm')[0].reset();
            fetch_receiptVoucher_depositPaidAmount();
            $("#rv_income_detail_single_add_modal").modal({backdrop: "static"});
        }
    }

    function settlementVoucher_single_modal() {
        if (pvMasterAutoID) {
            pvDetailID = null;
            $('.deposit_batch_div').addClass('hide');
            $(".batchMasterClass").val(null).trigger("change");
            $('#rv_income_detail_single_add_frm')[0].reset();
            fetch_receiptVoucher_depositPaidAmount();
            $("#rv_income_detail_single_add_modal").modal({backdrop: "static"});
        }
    }

    function Update_receiptVoucher_single_income() {
        var $form = $('#rv_income_detail_single_add_frm');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/save_receiptVoucher_single_income'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    pvDetailID = null;
                    $('#rv_income_detail_single_add_frm')[0].reset();
                    getReceiptVoucher_deposit_tableView(pvMasterAutoID);
                    $('#rv_income_detail_single_add_modal').modal('hide');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function edit_receiptVoucher_single_income(id) {
        if (pvMasterAutoID) {
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
                        data: {'pvDetailID': id},
                        url: "<?php echo site_url('Buyback/fetch_paymentVoucher_expense_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            pvDetailID = data['pvDetailID'];
                            fetch_receiptVoucher_depositPaidAmount();
                            $('#rv_income_single_edit').val(data['pvDetailID']);
                            $('#rv_income_amount').val(data['transactionAmount']);
                            $('#rv_income_description').val(data['comment']);
                            $("#rv_income_detail_single_add_modal").modal('show');
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }

    function paymentVoucher_loan_modal() {
        if (pvMasterAutoID) {
            pvDetailID = null;
            $('.loan_batch_div').addClass('hide');
            $(".batchMasterClass").val(null).trigger("change");
            $('#pv_loan_detail_add_form')[0].reset();
            fetch_paymentVoucher_loanPendingAmount();
            $("#loanType").prop("disabled", true);
            $("#pv_loan_detail_add_modal").modal({backdrop: "static"});
        }
    }

    function loanBaseChange(type) {
        if (type == 1) {
            $('.loan_batch_div').addClass('hide');
        } else if (type == 2) {
            $('.loan_batch_div').removeClass('hide');
        }
    }

    function depositBaseChange(type) {
        if (type == 1) {
            $('.deposit_batch_div').addClass('hide');
        } else if (type == 2) {
            $('.deposit_batch_div').removeClass('hide');
        }
    }

    function Save_PaymentVoucher_Loan() {
        $("#loanType").prop("disabled", false);
        var $form = $('#pv_loan_detail_add_form');
        var data = $form.serializeArray();
        data.push({'name': 'pvMasterAutoID', 'value': pvMasterAutoID});
        data.push({'name': 'pvDetailID', 'value': pvDetailID});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('buyback/save_paymentVoucher_loan'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    payVoucherDetailAutoID = null;
                    $('#pv_loan_detail_add_form')[0].reset();
                    getPaymentVoucher_loan_tableView(pvMasterAutoID);
                    $('#pv_loan_detail_add_modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    $("#loanType").prop("disabled", true);
                }else{
                    $("#loanType").prop("disabled", true);
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function fetch_paymentVoucher_loanPendingAmount() {
        var farmID = $('#farmID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'farmID': farmID},
            url: "<?php echo site_url('Buyback/load_buyback_farmer_pendingLoanAmount_pv'); ?>",
            success: function (data) {
                if (data) {
                    $("#loan_balance_amount").val(data.amount);
                }
            }
        });
    }

    function fetch_receiptVoucher_depositPaidAmount() {
        var farmID = $('#farmID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'farmID': farmID},
            url: "<?php echo site_url('Buyback/load_buyback_farmer_paidDepositAmount_rv'); ?>",
            success: function (data) {
                if (data) {
                    $("#deposit_balance_amount").val(data['amount']);
                }
            }
        });
    }

    function fetch_batchOutstandingPayable(batchMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'batchMasterID': batchMasterID},
            url: "<?php echo site_url('Buyback/fetch_buyback_batch_outStandingPayableAmount_voucher'); ?>",
            success: function (data) {
                if (data) {
                    $("#deposit_wages_amount").val(data.batchPayableAmount);
                    $("#loan_wages_amount").val(data.batchPayableAmount);
                }
            }
        });
    }

    function fetch_batchOutstandingPayableAll(batchMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'batchMasterID': batchMasterID},
            url: "<?php echo site_url('Buyback/fetch_buyback_batch_outStandingPayableAmount_voucher'); ?>",
            success: function (data) {
                if (data) {
                    $("#deposit_wages_amount").val(data.batchPayableAmount);
                    $("#loan_wages_amount").val(data.batchPayableAmount);
                }
                fetch_balance_amount(batchMasterID)
            }
        });
    }

    function fetch_balance_amount(batchMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'batchMasterID': batchMasterID},
            url: "<?php echo site_url('Buyback/fetch_buyback_batch_balance_amount'); ?>",
            success: function (data) {
                if (data) {
                    $("#balamount").val(data.balanceAmaount);
                }
            }
        });
    }

    $('#PVtype').on('change', function () {
        if (this.value == 1) {
            $('#tab2_type_receiptVoucher').addClass('hide');
            $('#tab2_type_settlementVoucher').addClass('hide');
            $('#tab2_type_paymentVoucher').removeClass('hide');
            $('#div_ClassBank').removeClass('hide');
            $('.settleMentDiv').addClass('hide');
        } else if (this.value == 2) {
            $('#tab2_type_receiptVoucher').removeClass('hide');
            $('#tab2_type_paymentVoucher').addClass('hide');
            $('#tab2_type_settlementVoucher').addClass('hide');
            $('#div_ClassBank').removeClass('hide');
            $('.settleMentDiv').addClass('hide');
        } else if (this.value == 3) {
            $('#tab2_type_receiptVoucher').addClass('hide');
            $('#tab2_type_settlementVoucher').removeClass('hide');
            $('#tab2_type_paymentVoucher').addClass('hide');
            $('#div_ClassBank').addClass('hide');
            $('.settleMentDiv').removeClass('hide');
        }
    });

    function getSettlementVoucher_tableView() {
        var batchMasterID = $('#settlement_batchMasterID').val();
        $('#settlement_BatchID').val(batchMasterID);
        var farmID = $('#farmID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {batchMasterID: batchMasterID, farmID: farmID, pvMasterAutoID: pvMasterAutoID},
            url: "<?php echo site_url('Buyback/load_batch_settlement_detail_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#settlementVoucher_all').html(data);
                $('#settlement_pvMasterAutoID').val(pvMasterAutoID);
                if ($('#loan_deductionAmount').val()>0&& $('#PVtype').val()==3) {
                    $('#PVtype').prop('disabled',true)
                    $("#farmID").prop("disabled", true);
                    $("#financeyear").prop("disabled", true);
                    $("#financeyear_period").prop("disabled", true);
                    $("#documentDate").prop("disabled", true);
                    $("#segment").prop("disabled", true);
                    $("#transactionCurrencyID").prop("disabled", true);
                    $("#PVbankCode").prop("disabled", true);
                    $("#PVchequeNo").prop("disabled", true);
                    $("#PVchequeDate").prop("disabled", true);
                }
                if ($('#deposit_deductionAmount').val()>0&& $('#PVtype').val()==3) {
                    $('#PVtype').prop('disabled',true);
                    $("#farmID").prop("disabled", true);
                    $("#financeyear").prop("disabled", true);
                    $("#financeyear_period").prop("disabled", true);
                    $("#documentDate").prop("disabled", true);
                    $("#segment").prop("disabled", true);
                    $("#transactionCurrencyID").prop("disabled", true);
                    $("#PVbankCode").prop("disabled", true);
                    $("#PVchequeNo").prop("disabled", true);
                    $("#PVchequeDate").prop("disabled", true);
                }
                if ($('#equipment_deductionAmount').val()>0&& $('#PVtype').val()==3) {
                    $('#PVtype').prop('disabled',true);
                    $("#farmID").prop("disabled", true);
                    $("#financeyear").prop("disabled", true);
                    $("#financeyear_period").prop("disabled", true);
                    $("#documentDate").prop("disabled", true);
                    $("#segment").prop("disabled", true);
                    $("#transactionCurrencyID").prop("disabled", true);
                    $("#PVbankCode").prop("disabled", true);
                    $("#PVchequeNo").prop("disabled", true);
                    $("#PVchequeDate").prop("disabled", true);
                }
                if ($('#advance_deductionAmount').val()>0&& $('#PVtype').val()==3) {
                    $('#PVtype').prop('disabled',true);
                    $("#farmID").prop("disabled", true);
                    $("#financeyear").prop("disabled", true);
                    $("#financeyear_period").prop("disabled", true);
                    $("#documentDate").prop("disabled", true);
                    $("#segment").prop("disabled", true);
                    $("#transactionCurrencyID").prop("disabled", true);
                    $("#PVbankCode").prop("disabled", true);
                    $("#PVchequeNo").prop("disabled", true);
                    $("#PVchequeDate").prop("disabled", true);
                }

                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function validate_loanAmount(balanceAmount, deductionAmount, batchPayableAmount) {
        var dueAmount = (balanceAmount - deductionAmount);
        $('#loan_dueAmount').val(dueAmount);
        if (deductionAmount > balanceAmount) {
            myAlert('w', 'Deduction Amount cannot be greater than Balance Amount');
            $('#loan_deductionAmount').val(0);
            $('#loan_dueAmount').val(0);
        }
        validate_netAmount_payable(batchPayableAmount);
    }

    function validate_depositAmount(balanceAmount, deductionAmount, batchPayableAmount) {
        var dueAmount = (balanceAmount - deductionAmount);
        $('#deposit_dueAmount').val(dueAmount);
        if (deductionAmount > balanceAmount) {
            myAlert('w', 'Deduction Amount cannot be greater than Balance Amount');
            $('#deposit_deductionAmount').val(0);
            $('#deposit_dueAmount').val(0);
        }
        validate_netAmount_payable(batchPayableAmount);
    }

    function validate_advanceAmount(balanceAmount, deductionAmount, batchPayableAmount) {
        var dueAmount = (balanceAmount - deductionAmount);
        $('#advance_dueAmount').val(dueAmount);
        if (deductionAmount > balanceAmount) {
            myAlert('w', 'Deduction Amount cannot be greater than Balance Amount');
            $('#advance_deductionAmount').val(0);
            $('#advance_dueAmount').val(0);
        }
        validate_netAmount_payable(batchPayableAmount);
    }

    function validate_netAmount_payable(batchPayableAmount) {
        var loan = $('#loan_deductionAmount').val();
        var deposit = $('#deposit_deductionAmount').val();
        var advance = $('#advance_deductionAmount').val();
        if (loan == '') {
            loan = 0;
        }
        if (deposit == '') {
            deposit = 0;
        }
        if (advance == '') {
            advance = 0;
        }
        var deductionTotal = parseInt(loan) + parseInt(deposit) + parseInt(advance);
        var netAmount = (batchPayableAmount - deductionTotal).toFixed(2);
        $('#netAmount_payable').html(netAmount);
        $('#netAmount_payable').val(netAmount);
        $('#lastPaidAmount').val(netAmount);
    }

    function validate_lastPaidAmount(paidAmount, batchPayableAmount) {
        var netAmount_payable = $('#netAmount_payable').val();
        if (paidAmount > netAmount_payable) {
            myAlert('w', 'Paid Amount cannot be greater than Net Amount');
            $('#lastPaidAmount').val(0);
        }
    }

    function Save_BatchSettlement_Amount() {
        var data = $('#batchSettlement_form').serialize();
        $.ajax({
            async: true,
            type: 'post',
            data: data,
            dataType: 'json',
            url: "<?php echo site_url('Buyback/save_paymentVoucher_batch_settlement'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $('.addTableView').removeClass('hide');
                    $('.btn-wizard').removeClass('disabled');
                    $('[href=#step3]').tab('show');

                    $('#PVtype').prop('disabled',true);
                    $("#farmID").prop("disabled", true);
                    $("#financeyear").prop("disabled", true);
                    $("#financeyear_period").prop("disabled", true);
                    $("#documentDate").prop("disabled", true);
                    $("#segment").prop("disabled", true);
                    $("#transactionCurrencyID").prop("disabled", true);
                    $("#PVbankCode").prop("disabled", true);
                    $("#PVchequeNo").prop("disabled", true);
                    $("#PVchequeDate").prop("disabled", true);

                    load_confirmation();
                } else {
                    $('.btn-primary').removeAttr('disabled');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function validate_rv_depositAmount(paidAmount) {
        var pendingAmount = parseInt($('#deposit_balance_amount').val());
        if (paidAmount > pendingAmount) {
            myAlert('w', 'Amount cannot be greater than Pending Deposit Amount');
            $('#rv_income_amount').val('');
        }
    }

    function validate_lostAmount(batchBalanceAmt,det,batchMasterID,batchPayableAmount){
        if(det==''){
            det=0
        }
        var Amount = parseInt(batchBalanceAmt)+parseInt(det);
        $('#lost_dueAmount_'+batchMasterID).val(Amount);

        var loan = $('#loan_deductionAmount').val();
        var deposit = $('#deposit_deductionAmount').val();
        var advance = $('#advance_deductionAmount').val();
        var lost = 0;

        $(".loosval").each(function( index ) {
            var amnt=$(this).val();
            if(amnt == ''){
                amnt = 0;
            }
            lost=parseInt(lost)+parseInt(amnt);
        });
        if (loan == '') {
            loan = 0;
        }
        if (deposit == '') {
            deposit = 0;
        }
        if (advance == '') {
            advance = 0;
        }
        if (lost == '') {
            lost = 0;
        }
        var deductionTotal = parseInt(loan) + parseInt(deposit) + parseInt(advance)+parseInt(lost);
        var netAmount = (batchPayableAmount - deductionTotal).toFixed(2);
        $('#netAmount_payable').html(netAmount);
        $('#netAmount_payable').val(netAmount);
        $('#lastPaidAmount').val(netAmount);
    }

    function validateBlanceAmount(amt){
        var amnt=$(amt).val();
        var balamount=$('#balamount').val();

        if(parseInt(amnt)>parseInt(balamount)){
            $(amt).val('');
            myAlert('w', 'Amount should be less than balance amount');

        }
    }


</script>
