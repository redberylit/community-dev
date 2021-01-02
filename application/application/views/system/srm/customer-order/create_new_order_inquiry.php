<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('srm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);


echo head_page($_POST['page_name'], false);
$this->load->helper('srm_helper');
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$customerArr = all_srm_customers();
$currency_arr = all_currency_new_drop();
//$countries_arr = load_all_countrys();
//$groupmaster_arr = all_crm_groupMaster();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/css/autocomplete-suggestions.css'); ?>"/>
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #060606
    }

    .contact-box .align-left {
        float: left;
        margin: 0 7px 0 0;
        padding: 2px;
        border: 1px solid #ccc;
    }

    img {
        vertical-align: middle;
        border: 0;
        -ms-interpolation-mode: bicubic;
    }

    .posts-holder {
        padding: 0 0 10px 4px;
        margin-right: 10px;
    }

    #toolbar, .past-info .toolbar {
        background: #f8f8f8;
        font-size: 13px;
        font-weight: bold;
        color: #000;
        border-radius: 3px 3px 0 0;
        -webkit-border-radius: 3px 3px 0 0;
        border: #dcdcdc solid 1px;
        padding: 5px 15px 12px 10px;
        height: 20px;
    }

    .past-info {
        background: #fff;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        padding: 0 0 8px 10px;
        margin-left: 2px;
    }

    .title {
        float: left;
        width: 170px;
        text-align: right;
        font-size: 13px;
        color: #7b7676;
        padding: 4px 10px 0 0;
    }

    .search_cancel {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;
        vertical-align: middle;
        padding: 3px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
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

    .btn-group {
        width: 100%;
    }

</style>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab"><?php echo $this->lang->line('srm_step_one');?><!--Step 1--> - <?php echo $this->lang->line('srm_step_inquiry_header');?><!--Inquiry Header--></a>
    <a class="btn btn-default btn-wizard" href="#step2" onclick="getCustomerInquiryItem_tableView()" data-toggle="tab"><?php echo $this->lang->line('srm_step_two');?><!--Step 2--> - <?php echo $this->lang->line('srm_step_order_details');?><!--Order Details--></a>
    <a class="btn btn-default btn-wizard generate-rfq" href="#step3" onclick="generate_rfq()" data-toggle="tab"><?php echo $this->lang->line('srm_step_three');?><!--Step 3--> - <?php echo $this->lang->line('srm_generated_rfq');?><!--Generated RFQ--></a>
</div>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="customer_order_inquiry_form"'); ?>
        <input type="hidden" name="inquiryID" id="inquiryID_master">
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2><?php echo $this->lang->line('srm_step_order_detail_cap');?><!--ORDER DETAILS--></h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('common_customer_name');?><!--Customer Name--></label>
                    </div>
                    <div class="form-group col-sm-4">
                            <span class="input-req"
                                  title="Required Field">
                                        <?php echo form_dropdown('customerID', $customerArr, '', 'class="form-control select2" id="customerID" ');
                                        ?>
                                <span class="input-req-inner"></span></span>

                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('common_currency');?><!--Currency--></label>
                    </div>
                    <div class="form-group col-sm-3">
                <span class="input-req" title="Required Field">
                <?php echo form_dropdown('transactionCurrencyID', $currency_arr, '', 'class="form-control select2" id="transactionCurrencyID" onchange="load_customer_orderID()" required'); ?>
                    <span class="input-req-inner"></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('srm_order_id');?><!--Order ID--></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div id="div_orderID">
                            <select name="customer_orderID[]" id="customer_orderID"
                                    class="form-control select2" multiple="">
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('common_document_date');?><!--Document Date--></label>
                    </div>
                    <div class="form-group col-sm-3">
                <span class="input-req" title="Required Field">
                <div class="input-group datepic">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="documentDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                           value="<?php echo $current_date; ?>" id="documentDate" class="form-control" required>
                </div>
                <span class="input-req-inner" style="z-index: 100"></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('common_narration');?><!--Narration--></label>
                    </div>
                    <div class="form-group col-sm-4">
                            <textarea class="form-control" rows="3"
                                      name="narration" id="narration"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-11">
                <div class="text-right m-t-xs">
                    <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('common_save_and_next');?><!--Save Next--></button>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div id="step2" class="tab-pane">
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2><?php echo $this->lang->line('srm_order_item_details');?><!--ORDERED ITEM DETAILS--></h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-7">
                        <div id="orderBase_item"></div>
                    </div>
                    <div class="col-sm-5">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-sm-7">
                <div class="text-right m-t-xs">
                    <button class="btn btn-primary" onclick="generate_order_itemView()"><?php echo $this->lang->line('common_generate');?><!--Generate--></button>
                </div>
            </div>
            <div class="col-sm-5">
                &nbsp;
            </div>
        </div>
        <div class="row hide" id="supplier_detail_div">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2><?php echo $this->lang->line('srm_order_item_supplier_details');?><!--ITEM SUPPLIER DETAILS--></h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-11">
                        <div id="itemBase_suppliers"></div>
                    </div>
                    <div class="col-sm-1">
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-2">
                    <label class="title"><?php echo $this->lang->line('srm_delivery_terms');?><!--Delivery Terms--></label>
                </div>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="3" name="deliveryTerms" id="deliveryTerms"></textarea>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-12">
                    <div
                        style="font-size: 13px;font-weight: 700;color: #ff4d4d;padding: 4px 10px 0 0;margin-left: 5%;">
                        <?php echo $this->lang->line('srm_rfq_will_be_generate');?> <!-- RFQ WILL BE GENERATE TO ONLY SELECTED SUPPLIERS-->
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-11">
                    <div class="text-right m-t-xs">
                        <div class="text-right m-t-xs">
                            <button class="btn btn-primary " onclick="draft_order_inquiry()"><?php echo $this->lang->line('common_save_as_draft');?><!--Save as Draft--></button>
                            <button class="btn btn-success submitWizard" onclick="confirm_order_inquiry()"><?php echo $this->lang->line('common_confirm');?><!--Confirm-->
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="step3" class="tab-pane">
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2><?php echo $this->lang->line('srm_suppier_rfq');?><!--SUPPLIER RFQ--></h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-8">
                        <div id="generated_supplier_rfq_view"></div>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                </div>
            </div>
            <br>

            <div class="row hide" style="margin-top: 10px;">
                <div class="col-sm-8">
                    <div class="text-right m-t-xs">
                        <button class="btn btn-primary" onclick="generate_rfq()" style="margin-right: 2%;"><?php echo $this->lang->line('srm_send_all_rfq');?><!--Send All RFQ-->
                        </button>
                    </div>
                </div>
                <div class="col-sm-4">
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="srm_rfq_modelView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="documentPageViewTitle"><?php echo $this->lang->line('srm_request_for_quotation');?><!--Request For Quotation--></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="srm_rfqPrint_Content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="srm_rfq_modelView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="documentPageViewTitle"><?php echo $this->lang->line('srm_request_for_quotation');?><!--Request For Quotation--></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="srm_rfqPrint_Content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="assignSupplier_item_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:50%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="documentPageViewTitle"><?php echo $this->lang->line('srm_request_for_quotation');?><!--Request For Quotation--></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="assignedSupplier_itemID"></div>
                        <div id="assignSupplier_item_Content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12 pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                    <button class="btn btn-primary" onclick="assign_supplier()"><?php echo $this->lang->line('srm_assign');?><!--Assign--></button>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js'); ?>"></script>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var ordArr;
    var search_id = 1;
    var selectedItemsSync = [];
    var selectedSupplierSync = [];
    var assignSupplierItemSync = [];
    var inquiryID = '';
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/srm/srm_order_inquiry', '', 'Order Inquiry')
        });

        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {
            //$('#purchase_order_form').bootstrapValidator('revalidateField', 'expectedDeliveryDate');
        });

        $('.select2').select2();

        $('#customer_orderID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '100%',
            maxHeight: '30px'
        });

        Inputmask().mask(document.querySelectorAll("input"));

        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            inquiryID = p_id;
            load_customerInquiry_header();
            $('.btn-wizard').removeClass('disabled');
            $('.generate-rfq').addClass('disabled');
        } else {
            $('.btn-wizard').addClass('disabled');
            //save_customer_order();
        }

        $('#customer_order_inquiry_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                customerID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('srm_customer_name_is_required');?>.'}}},/*Customer Name is required*/
                customer_orderID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('srm_order_id_is_required');?>.'}}},/*Order ID is required*/
                transactionCurrencyID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_currency_is_required');?>.'}}},/*Currency is required*/
                documentDate: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_document_date_is_required');?>.'}}}/*Document Date is required*/
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
                url: "<?php echo site_url('srm_master/save_order_inquiry'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        $('#inquiryID_master').val(data[2]);
                        getCustomerInquiryItem_tableView();
                        $('.btn-wizard').removeClass('disabled');
                        $('[href=#step2]').tab('show');
                    } else {
                        $('.btn-primary').prop('disabled', false);
                    }
                },
                error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
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

    function getCustomerInquiryItem_tableView() {
        var orderID = $('#customer_orderID').val();
        var inquiry_ID = $('#inquiryID_master').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {orderID: orderID, inquiry_ID: inquiry_ID},
            url: "<?php echo site_url('srm_master/load_customer_inquiry_detail_items_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#orderBase_item').html(data);
                stopLoad();
                if (inquiryID != '') {
                    load_customerInquiry_header();
                    setTimeout(function () {
                        generate_order_itemView();
                    }, 500);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }


    function save_customer_order() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {},
            url: "<?php echo site_url('srm_master/save_customer_ordermaster_add'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1], data[2]);
                if (data[0] == 's') {
                    $('#customerOrderID_edit').val(data[2]);
                    $('#customerOrderID_orderDetail').val(data[2]);
                    load_customer_order_autoGeneratedID(data[2]);
                    getCustomerOrderItem_tableView(data[2]);
                }
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function saveCustomerOrderDetails() {
        var customerOrderID = $('#customerOrderID_orderDetail').val();
        var data = $('#customer_order_detail_form').serializeArray();
        $('select[name="UnitOfMeasureID[]"] option:selected').each(function () {
            data.push({'name': 'uom[]', 'value': $(this).text()})
        });
        $.ajax(
            {
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('srm_master/save_customer_order_detail'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        getCustomerOrderItem_tableView(customerOrderID);
                        $('#customer_order_detail_modal').modal('hide');
                    }
                }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
            });

    }

    function load_customerInquiry_header() {
        if (inquiryID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'inquiryID': inquiryID},
                url: "<?php echo site_url('srm_master/load_customerInquiry_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data['header'])) {
                        $('#inquiryID_master').val(inquiryID);
                        $('#customerID').val(data['header']['customerID']).change();
                        $('#transactionCurrencyID').val(data['header']['transactionCurrencyID']).change();
                        $('#documentDate').val(data['header']['documentDate']);
                        $('#narration').val(data['header']['narration']);
                        $('#deliveryTerms').val(data['header']['deliveryTerms']);
                        if (data['header']['confirmedYN'] == 1) {
                            $('.btn-wizard').addClass('disabled');
                            $('.generate-rfq').removeClass('disabled');
                            $('[href=#step3]').tab('show');
                        }
                        //getCustomerOrderItem_tableView(customerOrderID);
                    }
                    if (!jQuery.isEmptyObject(data['orders'])) {
                        var selectedItems = [];
                        $.each(data['orders'], function (key, value) {
                            selectedItems.push(value.customerOrderID);
                        });
                        console.log(selectedItems);
                        setTimeout(function () {
                            $('#customer_orderID').val(selectedItems).multiselect2("refresh");
                        }, 500);
                    }

                    if (!jQuery.isEmptyObject(data['orderItem'])) {
                        $.each(data['orderItem'], function (key, value) {
                            $('#isAttended_' + value.itemAutoID).iCheck('check');
                        });

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

    function confirmation() {
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
                var data = $('#customer_order_form').serializeArray();
                data.push({'name': 'confirmedYN', 'value': 1});
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('srm_master/save_customer_order_header'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            fetchPage('system/srm/srm_customer_order', '', 'Customer Order');
                        } else {

                        }
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function save_draft() {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('srm_you_want_to_save_this_customer_order');?>",/*You want to save this Customer Order!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_save_as_draft');?>",/*Save as Draft*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                var data = $('#customer_order_form').serializeArray();
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('srm_master/save_customer_order_header'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            fetchPage('system/srm/srm_customer_order', '', 'Customer Order');
                        } else {

                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });

    }
    // added for order form


    function load_customerOrder_BaseItem(select_val) {
        $('#order_itemID').val("");
        $('#order_itemID option').remove();
        var customerID = $('#customerID').val();
        var customerOrderID = $('#customer_orderID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("srm_master/load_customerOrder_BaseItem"); ?>',
            dataType: 'json',
            data: {'customerOrderID': customerOrderID},
            async: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (!jQuery.isEmptyObject(data)) {
                    $('#order_itemID').empty();
                    var mySelect = $('#order_itemID');
                    mySelect.append($('<option></option>').val('').html('Select Option'));
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['itemAutoID']).html(text['itemDescription']));
                    });
                    if (select_val) {
                        $("#order_itemID").val(select_val);
                    }
                    load_OrderID_BaseCurrency();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }


    function load_OrderID_BaseCurrency() {
        var customerOrderID = $('#customer_orderID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("srm_master/load_OrderID_BaseCurrency"); ?>',
            dataType: 'json',
            data: {'customerOrderID': customerOrderID},
            async: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (!jQuery.isEmptyObject(data)) {
                    $('#transactionCurrencyID').val(data['transactionCurrencyID']).change();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }

    function orderItem_selected_check(item) {
        console.log(item);
        var value = $(item).val();
        if ($(item).is(':checked')) {
            var inArray = $.inArray(value, selectedItemsSync);
            if (inArray == -1) {
                selectedItemsSync.push(value);
            }
        }
        else {
            var i = selectedItemsSync.indexOf(value);
            if (i != -1) {
                selectedItemsSync.splice(i, 1);
            }
        }
    }

    function generate_order_itemView() {
        var orderID = $('#customer_orderID').val();
        var inquiryID = $('#inquiryID_master').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'selectedItemsSync': selectedItemsSync, orderID: orderID, inquiryID: inquiryID},
            url: "<?php echo site_url('srm_master/save_order_inquiry_itemDetail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data) {
                    generate_supplierView();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function generate_supplierView() {
        var inquiryID = $('#inquiryID_master').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {inquiryID: inquiryID},
            url: "<?php echo site_url('srm_master/load_customer_inquiry_detail_sellars_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#supplier_detail_div').removeClass('hide');
                $('#itemBase_suppliers').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function supplier_selected_check(supplier) {
        console.log(supplier);
        var value = $(supplier).val();
        if ($(supplier).is(':checked')) {
            var inArray = $.inArray(value, selectedSupplierSync);
            if (inArray == -1) {
                selectedSupplierSync.push(value);
            }
        }
        else {
            var i = selectedSupplierSync.indexOf(value);
            if (i != -1) {
                selectedSupplierSync.splice(i, 1);
            }
        }
    }


    function generated_supplier_RFQ_View() {
        var inquiryID = $('#inquiryID_master').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {inquiryID: inquiryID},
            url: "<?php echo site_url('srm_master/load_orderbase_generated_rfq_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#generated_supplier_rfq_div').removeClass('hide');
                $('#generated_supplier_rfq_view').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function view_rfq_printModel(inquiryMasterID, supplierID) {
        var html = 'html';
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {inquiryMasterID: inquiryMasterID, supplierID: supplierID, html: html},
            url: "<?php echo site_url('srm_master/supplier_rfq_print_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                //$('#documentPageViewTitle').html(title);
                $('#srm_rfqPrint_Content').html(data);
                $("#srm_rfq_modelView").modal({backdrop: "static"});
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
            }
        });
    }

    function load_customer_orderID() {
        var customerID = $('#customerID').val();
        var currency = $('#transactionCurrencyID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("srm_master/load_customerbase_ordersID"); ?>',
            dataType: 'html',
            data: {customerID: customerID, currency: currency},
            async: true,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#div_orderID').html(data);
                $('#customer_orderID').multiselect2({
                    enableCaseInsensitiveFiltering: true,
                    includeSelectAllOption: true,
                    numberDisplayed: 1,
                    buttonWidth: '100%',
                    maxHeight: '30px'
                });
                stopLoad();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                stopLoad();
            }
        });
    }

    function clearCurrency() {
        $("#transactionCurrencyID").val(null).trigger("change");
    }

    function view_supplierAssignModel(itemAutoID) {
        $('#assignedSupplier_itemID').val('');
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {itemAutoID: itemAutoID},
            url: "<?php echo site_url('srm_master/assignItem_supplier_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                //$('#documentPageViewTitle').html(title);
                $('#assignedSupplier_itemID').val(itemAutoID);
                $('#assignSupplier_item_Content').html(data);
                $("#assignSupplier_item_model").modal({backdrop: "static"});
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function assign_supplier_selected_check(supplier) {
        console.log(supplier);
        var value = $(supplier).val();
        if ($(supplier).is(':checked')) {
            var inArray = $.inArray(value, assignSupplierItemSync);
            if (inArray == -1) {
                assignSupplierItemSync.push(value);
            }
        }
        else {
            var i = assignSupplierItemSync.indexOf(value);
            if (i != -1) {
                assignSupplierItemSync.splice(i, 1);
            }
        }
    }

    function assign_supplier() {
        var itemAutoID = $('#assignedSupplier_itemID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                'assignSupplierItemSync': assignSupplierItemSync,
                itemAutoID: itemAutoID,
            },
            url: "<?php echo site_url('srm_master/assignItems_supplier_orderInquiry'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    generate_order_itemView();
                    $("#assignSupplier_item_model").modal('hide');
                } else {

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function confirm_order_inquiry() {
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
                var orderID = $('#customer_orderID').val();
                var inquiryID = $('#inquiryID_master').val();
                var deliveryTerms = $('#deliveryTerms').val();
                var confirmed = 1;
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'selectedSupplierSync': selectedSupplierSync,
                        inquiryID: inquiryID,
                        deliveryTerms: deliveryTerms,
                        confirmed: confirmed,
                        orderID: orderID
                    },
                    url: "<?php echo site_url('srm_master/order_inquiry_generate_supplier_rfq'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            $('#generated_supplier_rfq_div').removeClass('hide');
                            generated_supplier_RFQ_View();
                            $('.generate-rfq').removeClass('disabled');
                            $('[href=#step3]').tab('show');
                            $(document).scrollTop(0);
                        } else {

                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Something Went Wrong ! :)", "error");
                    }
                });
            });
    }

    function draft_order_inquiry() {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_save_this_document');?>",/*You want to Save this document!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_save_as_draft');?>",/*Save as Draft*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                var inquiryID = $('#inquiryID_master').val();
                var deliveryTerms = $('#deliveryTerms').val();
                var confirmed = 0;
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'selectedSupplierSync': selectedSupplierSync,
                        inquiryID: inquiryID,
                        deliveryTerms: deliveryTerms,
                        confirmed: confirmed
                    },
                    url: "<?php echo site_url('srm_master/order_inquiry_generate_supplier_rfq'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            fetchPage('system/srm/srm_order_inquiry', '', 'Order Inquiry');

                        } else {

                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function send_rfq_supplier(inquiryMasterID, supplierID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'inquiryMasterID': inquiryMasterID, supplierID: supplierID},
            url: "<?php echo site_url('srm_master/send_rfq_email_suppliers'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    generated_supplier_RFQ_View();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

</script>
