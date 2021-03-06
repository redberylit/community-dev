<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_payable', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
echo head_page($_POST['page_name'], false);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$currency_arr = all_currency_new_drop();
$supplier_arr = all_supplier_drop();
$financeyear_arr = all_financeyear_drop(true);
$segment_arr = fetch_segment();
$projectExist = project_is_exist();
$financeyearperiodYN = getPolicyValues('FPC', 'All');
?>
<style>
    .boldtab{
        font-weight: bold;
        border-left-color: #ead8d8 !important;
    }
</style>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab"> <?php echo $this->lang->line('accounts_payable_step_one');?><!--Step 1--> - <?php echo $this->lang->line('accounts_payable_dn_header');?><!--DN Header--></a>
    <a class="btn btn-default btn-wizard" href="#step2" onclick="fetch_dn_details()" data-toggle="tab"> <?php echo $this->lang->line('accounts_payable_step_two');?><!--Step 2--> - <?php echo $this->lang->line('accounts_payable_dn_detail');?> <!--DN Detail--></a>
    <a class="btn btn-default btn-wizard" href="#step3" onclick="load_conformation();" data-toggle="tab"> <?php echo $this->lang->line('accounts_payable_step_three');?><!--Step 3--> - <?php echo $this->lang->line('accounts_payable_dn_confiramation');?><!--DN Confirmation--></a>
</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="dn_form"'); ?>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for="supplierID"><?php echo $this->lang->line('common_supplier');?><!--Supplier--> <?php required_mark(); ?></label>
                <?php echo form_dropdown('supplier', $supplier_arr, '', 'class="form-control select2" id="supplier" onchange="fetch_supplier_currency_by_id(this.value)" required'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for="transactionCurrencyID"><?php echo $this->lang->line('accounts_payable_supplier_currency');?><!--Supplier Currency--> <?php required_mark(); ?></label>
                <?php echo form_dropdown('transactionCurrencyID', $currency_arr, '', 'class="form-control select2" id="transactionCurrencyID" onchange="currency_validation(this.value,\'DN\')" required'); ?>
            </div>
            <div class="form-group col-sm-4" style="display: none;">
                <label for=""><?php echo $this->lang->line('accounts_payable_exchange_rate');?><!--Exchange Rate--></label>
                <input type="number" step="any" class="form-control " id="exchangerate" name="exchangerate">
            </div>
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('accounts_payable_reference_no');?><!--Reference No--> </label>
                <input type="text" class="form-control " id="referenceno" name="referenceno">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('common_date');?><!--Date--> <?php required_mark(); ?></label>

                <div class="input-group datepic">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="dnDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                           value="<?php echo $current_date; ?>" id="dnDate"
                           class="form-control" required>
                </div>
            </div>
            <?php
            if($financeyearperiodYN==1){
            ?>
            <div class="form-group col-sm-4">
                <label for="financeyear"><?php echo $this->lang->line('accounts_payable_financial_year');?><!--Financial Year--> <?php required_mark(); ?></label>
                <?php echo form_dropdown('financeyear', $financeyear_arr, $this->common_data['company_data']['companyFinanceYearID'], 'class="form-control" id="financeyear" required onchange="fetch_finance_year_period(this.value)"'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for="financeyear"><?php echo $this->lang->line('accounts_payable_financial_period');?><!--Financial Period--> <?php required_mark(); //?></label>
                <?php echo form_dropdown('financeyear_period', array('' => 'Select Financial Period'), '', 'class="form-control" id="financeyear_period" required'); ?>
            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('common_comments');?><!--Comments--> </label>
                <textarea class="form-control" rows="3" id="comments" name="comments"></textarea>
            </div>
        </div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('common_save_and_next');?><!--Save & Next--></button>
        </div>
        </form>
    </div>
    <div id="step2" class="tab-pane">
        <div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs pull-right" role="tablist">
                <li role="presentation">
                    <a href="#glCodeTab" aria-controls="glCodeTab" role="tab" data-toggle="tab" class="boldtab"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--></a>
                </li>
                <li role="presentation" class="active">
                    <a href="#invoiceTab" aria-controls="invoiceTab" role="tab" data-toggle="tab" class="boldtab"><?php echo $this->lang->line('accounts_payable_invoice');?><!--Invoice--> </a>
                </li>
            </ul>

            <!--<div class="col-md-8"><h4>&nbsp;&nbsp;&nbsp;<i class="fa fa-hand-o-right"></i> Add Detail </h4></div>-->
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane " id="glCodeTab">
                    <!-- <div class="row">
                         <div class="col-md-12" style="margin-top: 10px;">
                             <button type="button" onclick="dn_detail_GLCode_modal()" class="btn btn-primary pull-right">
                                 <i class="fa fa-plus"></i> Add
                             </button>
                         </div>
                     </div>
                     <br>-->
                    <!--<h4>GL Details</h4>-->
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 10px;">

                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="<?php echo table_class(); ?>">
                            <thead>
                            <tr>
                                <th colspan="4"><?php echo $this->lang->line('common_gl_details');?><!--GL Details--></th>
                                <th><?php echo $this->lang->line('common_amount');?> <!--Amount--></th>
                                <th>
                                    <button type="button" onclick="dn_detail_GLCode_modal()"
                                            class="btn btn-primary btn-xs pull-right">
                                        <i class="fa fa-plus"></i> <?php echo $this->lang->line('common_add');?><!--Add-->
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th style="min-width: 5%">#</th>
                                <!--<th style="min-width: 15%">Invoice Code</th>-->
                                <th style="min-width: 10%"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--></th>
                                <th style="min-width: 30%"><?php echo $this->lang->line('common_gl_code_description');?><!--GL Code Description--></th>
                                <th style="min-width: 10%"><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
                                <th style="min-width: 15%"><?php echo $this->lang->line('common_transaction');?><!--Transaction--> <span class="trcurrency">()</span></th>
                                <th style="min-width: 5%"><?php echo $this->lang->line('common_action');?> <!--Action--></th>
                            </tr>
                            </thead>
                            <tbody id="table_body_GLCode">
                            <tr class="danger">
                                <td colspan="6" class="text-center"><b><?php echo $this->lang->line('common_no_records_found');?><!--No Records Found--></b></td>
                            </tr>
                            </tbody>
                            <tfoot id="table_tfoot_GLCode">

                            </tfoot>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane active" id="invoiceTab">

                    <div class="row">
                        <div class="col-md-12" style="margin-top: 10px;">

                        </div>
                    </div>
                    <br>

                    <div class="table-responsive">
                        <table id="debit_note_detail_table" class="<?php echo table_class(); ?>">
                            <thead>
                            <tr>
                                <th colspan="5"><?php echo $this->lang->line('accounts_payable_invoice_details');?><!--Invoice Details--></th>
                                <th> <?php echo $this->lang->line('common_amount');?><!--Amount--></th>
                                <th>
                                    <button type="button" onclick="dn_detail_modal()" class="btn btn-primary btn-xs pull-right"><i
                                            class="fa fa-plus"></i> <?php echo $this->lang->line('common_add');?><!--Add-->
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th style="min-width: 5%">#</th>
                                <th style="min-width: 15%"><?php echo $this->lang->line('accounts_payable_invoice_code');?><!--Invoice Code--></th>
                                <th style="min-width: 10%"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--></th>
                                <th style="min-width: 30%"><?php echo $this->lang->line('common_gl_code_description');?><!--GL Code Description--></th>
                                <th style="min-width: 10%"><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
                                <th style="min-width: 15%"><?php echo $this->lang->line('common_transaction');?><!--Transaction--> <span class="trcurrency">()</span></th>
                                <th style="min-width: 5%"><?php echo $this->lang->line('common_action');?><!--Action--></th>
                            </tr>
                            </thead>
                            <tbody id="table_body">
                            <tr class="danger">
                                <td colspan="7" class="text-center"><b><?php echo $this->lang->line('common_no_records_found');?><!--No Records Found--></b></td>
                            </tr>
                            </tbody>
                            <tfoot id="table_tfoot">

                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

        </div>


        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev" onclick=""><?php echo $this->lang->line('common_previous');?><!--Previous--></button>
        </div>
    </div>
    <div id="step3" class="tab-pane">

        <div id="conform_body"></div>
        <hr>
        <div id="conform_body_attachement">
            <h4 class="modal-title" id="debitNote_attachment_label"><?php echo $this->lang->line('accounts_payable_modal_title');?><!--Modal title--></h4>
            <br>

            <div class="table-responsive" style="width: 60%">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo $this->lang->line('common_file_name');?><!--File Name--></th>
                        <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
                        <th><?php echo $this->lang->line('common_type');?><!--Type--></th>
                        <th><?php echo $this->lang->line('common_action');?><!--Action--></th>
                    </tr>
                    </thead>
                    <tbody id="debitNote_attachment" class="no-padding">
                    <tr class="danger">
                        <td colspan="5" class="text-center"><?php echo $this->lang->line('common_no_attachment_found');?><!--No Attachment Found--></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev"><?php echo $this->lang->line('common_previous');?><!--Previous--></button>
            <button class="btn btn-primary " onclick="save_draft()"><?php echo $this->lang->line('common_save_as_draft');?><!--Save as Draft--></button>
            <button class="btn btn-success submitWizard" onclick="confirmation()"><?php echo $this->lang->line('common_confirm');?><!--Confirm--></button>
        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" tabindex="-1" id="dn_detail_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title"><?php echo $this->lang->line('accounts_payable_add_invoice');?><!--Add Invoice--></h5>
            </div>
            <div id="div_invoice"></div>
        </div>
    </div>
</div>

<?php
$gl_code_arr = fetch_all_gl_codes(); ?>


<div aria-hidden="true" role="dialog" id="GLCode_detail_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title"><?php echo $this->lang->line('accounts_payable_add_gl_detail');?><!--Add GL Detail--> </h5>
            </div>
            <div class="modal-body">
                <form role="form" id="multi_detail_form" class="form-horizontal">
                    <table class="table table-bordered table-condensed no-color"
                           id="supplier_invoice_detail_table">
                        <thead>
                        <tr>
                            <th style="width: 350px;"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--> <?php required_mark(); ?></th>
                            <th><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
                            <?php if ($projectExist == 1) { ?>
                                <th><?php echo $this->lang->line('common_project');?><!--Project--> <?php required_mark(); ?></th>
                            <?php } ?>
                            <th style="width: 150px;"><?php echo $this->lang->line('common_amount');?><!--Amount--> <span class="currency"> </span> <?php required_mark(); ?>
                            </th>
                            <th style="width: 200px;"><?php echo $this->lang->line('common_description');?><!--Description--> <?php required_mark(); ?></th>
                            <th style="width: 40px;">
                                <button type="button" class="btn btn-primary btn-xs" onclick="add_more()"><i
                                        class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>

                                <?php echo form_dropdown('gl_code_array[]', $gl_code_arr, '', 'class="form-control select2" id="gl_code_array" required'); ?>
                                <!--<input type="text" name="gl_code[]" id="gl_code"  class="form-control" required>-->
                            </td>
                            <td>
                                <?php echo form_dropdown('segment_gl[]', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="segment_gl" onchange="load_segmentBase_projectID_income(this)"'); ?>
                            </td>
                            <?php if ($projectExist == 1) { ?>
                                <td>
                                    <div class="div_projectID_income">
                                        <select name="projectID"  class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('common_select_project');?><!--Select Project--></option>
                                        </select>
                                    </div>
                                </td>
                            <?php } ?>
                            <td>
                                <input type="text" name="amount[]" id="amount" onfocus="this.select();" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number">
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
                <button data-dismiss="modal" class="btn btn-default" type="button"><!--Close--><?php echo $this->lang->line('common_Close');?></button>
                <button class="btn btn-primary" type="button" onclick="debitNote_Details_GLCode()"><!--Save Changes--><?php echo $this->lang->line('common_save_change');?>
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js'); ?>"></script>
<script type="text/javascript">
    var debitNoteMasterAutoID;
    var debitNoteDetailsID;
    var documentCurrency;
    var projectID;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/accounts_payable/debit_note_management', debitNoteMasterAutoID, 'Debit Note');
        });
        $('.select2').select2();
        debitNoteMasterAutoID = null;
        debitNoteDetailsID = null;
        documentCurrency = null;
        projectID = null;
        number_validation();

        Inputmask().mask(document.querySelectorAll("input"));
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {
            $('#dn_form').bootstrapValidator('revalidateField', 'dnDate');
        });
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('a[data-toggle="tab"]').removeClass('btn-primary');
            $('a[data-toggle="tab"]').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        });

        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            debitNoteMasterAutoID = p_id;
            load_debit_note_header();
            $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_entry_debit_note'); ?>/" + debitNoteMasterAutoID + '/DB');
        } else {
            $('.btn-wizard').addClass('disabled');
        }

        FinanceYearID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinanceYearID'])); ?>;
        DateFrom = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateFrom'])); ?>;
        DateTo = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateTo'])); ?>;
        periodID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinancePeriodID'])); ?>;
        fetch_finance_year_period(FinanceYearID, periodID);
        $('#dn_detail_form').bootstrapValidator({
            live: 'enabled',
            message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
            excluded: [':disabled'],
            fields: {
                gl_code_array: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_gl_code_is_required');?>.'}}},/*GL code is required*/
                amount: {validators: {notEmpty: {message: '<?php echo $this->lang->line('accounts_payable_ammount_is_required');?>.'}}},/*Amount is required*/
                segment_gl: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_segment_is_required');?>.'}}},/*Segment is required*/
                description: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_description_is_required');?>.'}}}/*Description is required*/
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'debitNoteMasterAutoID', 'value': debitNoteMasterAutoID});
            data.push({'name': 'debitNoteDetailsID', 'value': debitNoteDetailsID});
            data.push({'name': 'gl_code_des', 'value': $('#gl_code_array option:selected').text()});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('Payable/save_dn_detail'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $form.bootstrapValidator('resetForm', true);
                    debitNoteDetailsID = null;
                    refreshNotifications(true);
                    stopLoad();
                    $('#dn_detail_modal').modal('hide');
                    if (data['status']) {
                        fetch_dn_details();
                    }
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        $('#dn_form').bootstrapValidator({
            live: 'enabled',
            message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
            excluded: [':disabled'],
            fields: {
                transactionCurrencyID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('accounts_payable_supplier_currency_is_required');?>.'}}},/*Supplier Currency is required*/
                supplier: {validators: {notEmpty: {message: '<?php echo $this->lang->line('accounts_payable_supplier_is_required');?>.'}}},/*Supplier is required*/
                //exchangerate            : {validators : {notEmpty:{message:'Exchange Rate is required.'}}},
                dnDate: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_date_is_required');?>.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            $("#transactionCurrencyID").prop("disabled", false);
            $("#supplier").prop("disabled", false);
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'debitNoteMasterAutoID', 'value': debitNoteMasterAutoID});
            data.push({'name': 'companyFinanceYear', 'value': $('#financeyear option:selected').text()});
            data.push({'name': 'SupplierDetails', 'value': $('#supplier option:selected').text()});
            data.push({'name': 'currency_code', 'value': $('#transactionCurrencyID option:selected').text()});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('Payable/save_debitnote_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data_arr) {
                    refreshNotifications(true);
                    stopLoad();
                    if (data_arr['status']) {
                        debitNoteMasterAutoID = data_arr['last_id'];
                        $('.btn-wizard').removeClass('disabled');
                        $("#a_link").attr("href", "<?php echo site_url('Payable/load_dn_conformation'); ?>/" + debitNoteMasterAutoID);
                        $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_entry_debit_note'); ?>/" + debitNoteMasterAutoID + '/DB');
                        $("#transactionCurrencyID").prop("disabled", true);
                        $("#supplier").prop("disabled", true);
                        $('[href=#step2]').tab('show');
                        fetch_dn_details();
                    }
                },
                error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });
    });

    function add_more() {
        $('select.select2').select2('destroy');
        var appendData = $('#supplier_invoice_detail_table tbody tr:first').clone();

        appendData.find('input').val('');
        appendData.find('textarea').val('');

        appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
        $('#supplier_invoice_detail_table').append(appendData);
        var lenght = $('#supplier_invoice_detail_table tbody tr').length - 1;

        $(".select2").select2();
        number_validation();
    }

    $(document).on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });


    function load_debit_note_header() {
        if (debitNoteMasterAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'debitNoteMasterAutoID': debitNoteMasterAutoID},
                url: "<?php echo site_url('Payable/load_debit_note_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        debitNoteMasterAutoID = data['debitNoteMasterAutoID'];
                        $("#a_link").attr("href", "<?php echo site_url('Payable/load_dn_conformation'); ?>/" + debitNoteMasterAutoID);
                        documentCurrency = data['transactionCurrencyID'];
                        $('#supplier').val(data['supplierID']).change();
                        fetch_finance_year_period(data['companyFinanceYearID'], data['companyFinancePeriodID']);
                        $('#exchangerate').val(data['transactionExchangeRate']);
                        $('#dnDate').val(data['debitNoteDate']);
                        $('#financeyear').val(data['companyFinanceYearID']);
                        $('#comments').val(data['comments']);
                        $('#referenceno').val(data['docRefNo']);
                        fetch_dn_details();
                        $('[href=#step2]').tab('show');
                        $('a[data-toggle="tab"]').removeClass('btn-primary');
                        $('a[data-toggle="tab"]').addClass('btn-default');
                        $('[href=#step2]').removeClass('btn-default');
                        $('[href=#step2]').addClass('btn-primary');
                    }
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }

    function fetch_dn_details() {
        if (debitNoteMasterAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'debitNoteMasterAutoID': debitNoteMasterAutoID},
                url: "<?php echo site_url('Payable/fetch_dn_detail_table'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('.trcurrency').html('( ' + data['currency']['transactionCurrency'] + ' )');
                    $('.locurrency').html('( ' + data['currency']['companyLocalCurrency'] + ' )');
                    $('.sucurrency').html('( ' + data['currency']['supplierCurrency'] + ' )');
                    $('#table_body').empty();
                    $('#table_body_GLCode').empty();
                    $('#table_tfoot').empty();
                    $('#table_tfoot_GLCode').empty();
                    if (jQuery.isEmptyObject(data['detail'])) {
                        $('#table_body').append('<tr class="danger"><td colspan="9" class="text-center"><b><?php echo $this->lang->line('common_no_records_found');?><!--No Records Found--></b></td></tr>');
                        $("#supplier").prop("disabled", false);
                        $("#transactionCurrencyID").prop("disabled", false);
                    } else {
                        $("#supplier").prop("disabled", true);
                        $("#transactionCurrencyID").prop("disabled", true);
                        var trtot = 0;
                        var trtotGLCode = 0;
                        var lotot = 0;
                        var suptot = 0;
                        transaction_currency_decimal = data['currency']['transactionCurrencyDecimalPlaces'];
                        company_currency_decimal = data['currency']['companyLocalCurrencyDecimalPlaces'];
                        supplier_currency_decimal = data['currency']['supplierCurrencyDecimalPlaces'];
                        var x = 1;
                        var i = 1;

                        $.each(data['detail'], function (key, value) {
                            if (value['isFromInvoice'] == 1) {
                                $('#table_body').append('<tr><td>' + x + '</td><td>' + value['bookingInvCode'] + '</td><td>' + value['GLCode'] + '</td><td>' + value['GLDescription'] + '</td><td>' + value['segmentCode'] + '</td><td class="text-right">' + parseFloat(value['transactionAmount']).formatMoney(transaction_currency_decimal, '.', ',') + '</td><td class="text-right"><a onclick="delete_dn_detail(' + value['debitNoteDetailsID'] + ',\'' + value['glCodeDes'] + '\');"><span style="color:rgb(209, 91, 71);" class="glyphicon glyphicon-trash"></span></a></td></tr>');
                                x++;
                                trtot += (parseFloat(value['transactionAmount']));

                            } else if (value['isFromInvoice'] == 0) {
                                $('#table_body_GLCode').append('<tr><td>' + i + '</td><td>' + value['GLCode'] + '</td><td>' + value['GLDescription'] +' : '+value['description']+ '</td><td>' + value['segmentCode'] + '</td><td class="text-right">' + parseFloat(value['transactionAmount']).formatMoney(transaction_currency_decimal, '.', ',') + '</td><td class="text-right"><a onclick="delete_dn_detail(' + value['debitNoteDetailsID'] + ',\'' + value['glCodeDes'] + '\');"><span style="color:rgb(209, 91, 71);" class="glyphicon glyphicon-trash"></span></a></td></tr>');
                                i++;
                                trtotGLCode += (parseFloat(value['transactionAmount']));
                            }

                        });

                        $('#table_tfoot').append('<tr><td colspan="5" style="text-align: right;"><?php echo $this->lang->line('common_total');?><!--Total--> </td><td class="text-right total">' + trtot.formatMoney(transaction_currency_decimal, '.', ',') + '</td></tr>');
                        $('#table_body_GLCode').append('<tr><td colspan="4" style="text-align: right;"><?php echo $this->lang->line('common_total');?><!--Total--> </td><td class="text-right total">' + trtotGLCode.formatMoney(transaction_currency_decimal, '.', ',') + '</td></tr>');

                    }
                    stopLoad();
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                }
            });
        }
        ;
    }

    function fetch_supplier_currency_by_id(supplierAutoID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'supplierAutoID': supplierAutoID},
            url: "<?php echo site_url('Procurement/fetch_supplier_currency_by_id'); ?>",
            success: function (data) {
                if (documentCurrency) {
                    $("#transactionCurrencyID").val(documentCurrency).change()
                } else {
                    if (data.supplierCurrencyID) {
                        $("#transactionCurrencyID").val(data.supplierCurrencyID).change();
                        currency_validation_modal(data.supplierCurrencyID, 'DN', supplierAutoID, 'SUP');
                    }
                }
            }
        });
    }

    function currency_validation(CurrencyID, documentID) {
        if (CurrencyID) {
            partyAutoID = $('#supplier').val();
            currency_validation_modal(CurrencyID, documentID, partyAutoID, 'SUP');
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
                mySelect.append($('<option></option>').val('').html('<?php echo $this->lang->line('accounts_payable_select_finance_period');?>'));/*Select Finance Period*/
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['companyFinancePeriodID']).html(text['dateFrom'] + ' - ' + text['dateTo']));
                    });
                    if (select_value) {
                        $("#financeyear_period").val(select_value);
                    }
                    ;
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }

    function dn_detail_modal() {
        if (debitNoteMasterAutoID) {
            var result = $('#transactionCurrencyID option:selected').text().split('|');
            $('.currency').html('( ' + result[0] + ' )');
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'debitNoteMasterAutoID': debitNoteMasterAutoID},
                url: "<?php echo site_url('Payable/fetch_supplier_invoice'); ?>",
                success: function (data) {
                    $('#div_invoice').html(data);
                    $("#dn_detail_modal").modal({backdrop: "static"});
                }
            });
        }
    }

    function dn_detail_GLCode_modal() {
        if (debitNoteMasterAutoID) {
            $('#multi_detail_form')[0].reset();
            $('#gl_code_array').val('').change();
            $('#supplier_invoice_detail_table tbody tr').not(':first').remove();
            $("#GLCode_detail_modal").modal({backdrop: "static"});
        }
        $("#GLCode_detail_modal").modal;
    }

    function debitNote_Details_GLCode() {
        var $form = $('#multi_detail_form');
        var data = $form.serializeArray();
        data.push({'name': 'debitNoteMasterAutoID', 'value': debitNoteMasterAutoID});
        //data.push({'name': 'InvoiceDetailAutoID', 'value': InvoiceDetailAutoID});

        $('select[name="gl_code_array[]"] option:selected').each(function () {
            console.log($(this).text())
            data.push({'name': 'gl_code_des[]', 'value': $(this).text()})
        });

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('Payable/save_debitNote_detail_GLCode_multiple'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                stopLoad();
                if (data[0] == 's') {
                    InvoiceDetailAutoID = null;
                    $('#multi_detail_form')[0].reset();
                    $("#segment_gl").select2("");
                    $("#gl_code_array").select2("");
                    fetch_dn_details();
                    $('#GLCode_detail_modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }


    function fetch_supplier_currency(supplierAutoID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'supplierAutoID': supplierAutoID},
            url: "<?php echo site_url('Procurement/fetch_supplier_currency'); ?>",
            success: function (data) {
                if (data.supplierCurrencyID) {
                    $("#transactionCurrencyID").val(data.supplierCurrencyID);
                }
                ;
            }
        });
    }

    function edit_dn_header(id) {
        if (debitNoteMasterAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('accounts_payable_you_want_to_edit_this_file');?>",/*You want to edit this file!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_edit');?>",/*Edit*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'debitNoteDetailsID': id},
                        url: "<?php echo site_url('Payable/fetch_dn_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            debitNoteDetailsID = data['debitNoteDetailsID'];

                            $('#gl_code_array').val(data['GLCode']);
                            $('#amount').val(data['transactionAmount']);
                            $('#description').val(data['description']);
                            $('#segment_gl').val(data['segmentID'] + '|' + data['segmentCode']);
                            $("#dn_detail_modal").modal({backdrop: "static"});
                            stopLoad();
                            //refreshNotifications(true);
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
        ;
    }


    function delete_dn_detail(id) {
        if (debitNoteMasterAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('accounts_payable_trans_are_you_want_to_delete');?>",/*You want to delete this file!*/
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
                        data: {'debitNoteDetailsID': id},
                        url: "<?php echo site_url('Payable/delete_dn_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            fetch_dn_details();
                            refreshNotifications(true);
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
        ;
    }

    function load_conformation() {
        if (debitNoteMasterAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'debitNoteMasterAutoID': debitNoteMasterAutoID, 'html': true},
                url: "<?php echo site_url('Payable/load_dn_conformation'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#conform_body').html(data);
                    $("#a_link").attr("href", "<?php echo site_url('Payable/load_dn_conformation'); ?>/" + debitNoteMasterAutoID);
                    $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_entry_debit_note'); ?>/" + debitNoteMasterAutoID + '/DN');
                    attachment_modal_debitNote(debitNoteMasterAutoID, "<?php echo $this->lang->line('accounts_payable_debit_note');?> ", "DN");/*Debit Note*/
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    stopLoad();
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    refreshNotifications(true);
                }
            });
        }
    }


    function confirmation() {
        if (debitNoteMasterAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_confirm_this_document');?>",/*You want to confirm this document!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_confirm');?>",/*Confirm*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'debitNoteMasterAutoID': debitNoteMasterAutoID},
                        url: "<?php echo site_url('Payable/dn_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert(data[0],data[1]);
                            if(data[0]=='s'){
                                fetchPage('system/accounts_payable/debit_note_management', debitNoteMasterAutoID, 'Debit Note');
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
        ;
    }

    function save_draft() {
        if (debitNoteMasterAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",
                    text: "<?php echo $this->lang->line('accounts_payable_you_want_to_save_this_file');?>",/*You want to save this file!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_save_as_draft');?>",/*Save as Draft*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    fetchPage('system/accounts_payable/debit_note_management', debitNoteMasterAutoID, 'Debit Note');
                });
        }
    }


    function attachment_modal_debitNote(documentSystemCode, document_name, documentID) {
        if (documentSystemCode) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("Attachment/fetch_attachments"); ?>',
                dataType: 'json',
                data: {'documentSystemCode': documentSystemCode, 'documentID': documentID,'confirmedYN': 0},
                success: function (data) {
                    $('#debitNote_attachment_label').html('<span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span> &nbsp;' + document_name + "<?php echo $this->lang->line('common_attachments');?>");<!--Attachments-->
                    $('#debitNote_attachment').empty();
                    $('#debitNote_attachment').append('' +data+ '');

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#ajax_nav_container').html(xhr.responseText);
                }
            });
        }
    }

    function delete_debitNote_attachment(debitNoteMasterAutoID, DocumentSystemCode, myFileName) {
        if (debitNoteMasterAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete_this_attachment_file');?>",/*You want to delete this attachment file!*/
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
                        data: {'attachmentID': debitNoteMasterAutoID, 'myFileName': myFileName},
                        url: "<?php echo site_url('Payable/delete_debitNote_attachement'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            attachment_modal_debitNote(DocumentSystemCode, "Debit Note", "DN");
                            stopLoad();
                            if (data == true) {
                                myAlert('s', '<?php echo $this->lang->line('common_deleted_successfully');?>');/*Deleted Successfully*/
                                attachment_modal_debitNote(DocumentSystemCode, "Debit Note", "DN");
                            } else {
                                myAlert('e', '<?php echo $this->lang->line('common_deletion_failed');?>');/*Deletion Failed*/
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
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
        if(number.length>1 && charCode == 46){
            return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if( caratPos > dotPos && dotPos>-(currency_decimal-1) && (number[1].length > (currency_decimal-1))){
            return false;
        }
        return true;
    }

    //thanks: http://javascript.nwbox.com/cursor_position/
    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }

    function load_segmentBase_projectID_income(segment) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Procurement/load_project_segmentBase_multiple"); ?>',
            dataType: 'html',
            data: {segment: segment.value},
            async: true,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $(segment).closest('tr').find('.div_projectID_income').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                stopLoad();
            }
        });
    }

    function load_segmentBase_projectID_itemEdit(segment) {
        var type = 'item';
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Procurement/load_project_segmentBase"); ?>',
            dataType: 'html',
            data: {segment: segment.value,type:type},
            async: true,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#edit_div_projectID_item').html(data);
                $('.select2').select2();
                if (projectID) {
                    $("#projectID_item").val(projectID).change()
                }
                stopLoad();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                stopLoad();
            }
        });
    }
</script>