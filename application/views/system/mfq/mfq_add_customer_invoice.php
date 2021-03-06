<?php
$date_format_policy = date_format_policy();
$from = convert_date_format($this->common_data['company_data']['FYPeriodDateFrom']);
$currency_arr = all_currency_new_drop();
$current_date = current_format_date();
$segment = fetch_mfq_segment(true);
$gl_code = fetch_all_mfq_gl_codes();
$page_id = isset($page_id) && $page_id ? $page_id : 0;

?>
<?php echo head_page($_POST["page_name"], false); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/tabs.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/typehead.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/css/autocomplete-suggestions.css'); ?>"/>
<style>
    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .entity-detail .ralign, .property-table .ralign {
        text-align: right;
        color: gray;
        padding: 3px 10px 4px 0;
        width: 150px;
        max-width: 200px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .title {
        color: #aaa;
        padding: 4px 10px 0 0;
        font-size: 13px;
    }

    .tddata {
        color: #333;
        padding: 4px 10px 0 0;
        font-size: 13px;
    }

    .nav-tabs > li > a {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        position: relative;
        padding: 0 25px;
        float: left;
        display: block;
        /*color: rgb(44, 83, 158);*/
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
    }

    .nav-tabs > li > a:hover {
        background: rgb(230, 231, 234);
        font-size: 12px;
        line-height: 30px;
        height: 30px;
        position: relative;
        padding: 0 25px;
        float: left;
        display: block;
        /*color: rgb(44, 83, 158);*/
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        border-radius: 3px 3px 0 0;
        border-color: transparent;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: #c0392b;
        cursor: default;
        background-color: #fff;
        font-weight: bold;
        border-bottom: 3px solid #f15727;
    }

    .arrow-steps .step.current {
        color: #fff !important;
        background-color: #657e5f !important;
    }

    .table-responsive {
        overflow: visible !important
    }

</style>

<div id="filter-panel" class="collapse filter-panel"></div>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#rfq" data-toggle="tab">Customer Invoice</a>
    <a class="btn btn-default btn-wizard" href="#print" onclick="customer_invoice_print();" data-toggle="tab">
        Confirmation</a>
</div>
<hr>
<div class="tab-content">
    <div class="tab-pane active" id="rfq">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-md-12 animated zoomIn">
                            <form id="frm_customerInvoice" class="frm_customerInvoice" method="post">
                                <input type="hidden" id="invoiceAutoID" name="invoiceAutoID"
                                       value="<?php echo $page_id ?>">
                                <header class="head-title">
                                    <h2>Customer Invoice Information </h2>
                                </header>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-md-offset-0">
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Client </label>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('mfqCustomerAutoID', all_mfq_customer_drop(), '', 'class="form-control select2" id="mfqCustomerAutoID"');
                                                    ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Invoice Date </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <div class='input-group date filterDate' id="">
                                                        <input type='text' class="form-control"
                                                               name="invoiceDate"
                                                               id="invoiceDate"
                                                               value="<?php echo $current_date; ?>"
                                                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                                               readonly>
                                                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                                    </div>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Invoice Due Date </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <div class='input-group date filterDate' id="">
                                                        <input type='text' class="form-control"
                                                               name="invoiceDueDate"
                                                               id="invoiceDueDate"
                                                               value="<?php echo $current_date; ?>"
                                                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"/>
                                                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                                    </div>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Delivery Note </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <?php echo form_dropdown('deliveryNoteID', array("" => "Select"), "", 'class="form-control select2" id="deliveryNoteID"'); ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Currency </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('currencyID', $currency_arr, $this->common_data['company_data']['company_default_currencyID'], 'class="form-control select2" id="currencyID" required'); ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Comment </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                            <textarea class="form-control" id="invoiceNarration"
                                                                      name="invoiceNarration" rows="3"></textarea>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 animated zoomIn">
                                        <header class="head-title">
                                            <h2>Item Detail</h2>
                                        </header>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="job_item_table"
                                                           class="table table-condensed">
                                                        <thead>
                                                        <tr>
                                                            <th style="min-width: 12%">Item Description</th>
                                                            <th style="min-width: 12%">UoM</th>
                                                            <th style="min-width: 12%">Qty</th>
                                                            <th style="min-width: 12%">Unit Rate</th>
                                                            <th style="min-width: 12%">Amount</th>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="job_item_body">
                                                        <tr class="danger">
                                                            <td colspan="5" class="text-center"><b>No Records Found </b>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 animated zoomIn">
                                        <header class="head-title">
                                            <h2>GL Detail</h2>
                                        </header>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="mfq_customer_invoice"
                                                           class="table table-condensed">
                                                        <thead>
                                                        <tr>
                                                            <th style="min-width: 12%">GL Code</th>
                                                            <th style="min-width: 12%">Segment</th>
                                                            <th style="min-width: 12%">Expected Qty</th>
                                                            <th style="min-width: 12%">Unit Rate</th>
                                                            <th style="min-width: 12%">Amount</th>
                                                            <th style="min-width: 5%">
                                                                <div class=" pull-right">
                                                            <span class="button-wrap-box">
                                                                <button type="button" data-text="Add" id="btnAdd"
                                                                        onclick="add_more_row()"
                                                                        class="button button-square button-tiny button-royal button-raised">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                            </span>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="customer_invoice_body">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-12 ">
                                        <div class="pull-right">
                                            <button class="btn btn-primary" onclick="saveCustomerInvoice(1)"
                                                    type="button"
                                                    id="submitBtn">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="print">
        <br>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <div id="review">
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-sm-12 ">
                <div class="pull-right">
                    <button class="btn btn-success" onclick="confirmCustomerInvoice()"
                            type="button"
                            id="confirmBtn">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>


    <?php echo footer_page('Right foot', 'Left foot', false); ?>

    <script>
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
        var search_id = 1;
        var invoiceAutoID = "";
        var deliveryNoteID = "";
        var qty = '0.00';
        var unitPrice = '0.00';
        var currency_decimal = 3;
        $(document).ready(function () {
            $('.select2').select2();
            $('.filterDate').datetimepicker({
                useCurrent: false,
                format: date_format_policy
            });
            $('.headerclose').click(function () {
                fetchPage('system/mfq/mfq_customer_invoice', '', 'Customer Invoice');
            });
            Inputmask().mask(document.querySelectorAll("input"));
            <?php
            if ($page_id) {
            ?>
            invoiceAutoID = parseInt("<?php echo $page_id  ?>");
            loadCustomerInvoice();
            load_customer_invoice_detail('<?php echo $page_id  ?>');

            //load_attachments('CI',<?php echo $page_id  ?>);
            <?php
            }else{
            ?>
            $('.btn-wizard').addClass('disabled');
            init_customerInvoiceDetailForm();
            initializeCustomerInvoiceDetailTypeahead(1);
            <?php
            }
            ?>
            $(document).on('click', '.remove-tr', function () {
                $(this).closest('tr').remove();
            });

            $(document).on('click', '.remove-tr2', function () {
                $(this).closest('tr').remove();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('a[data-toggle="tab"]').removeClass('btn-primary');
                $('a[data-toggle="tab"]').addClass('btn-default');
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
            });

            $("#mfqCustomerAutoID").change(function () {
                get_delivery_note($(this).val())
            });

            $("#deliveryNoteID").change(function () {
                qty = $(this).find(":selected").data("qty");
                unitPrice = $(this).find(":selected").data("unitprice");
                var comment = $(this).find(":selected").data("description") + " " + $(this).find(":selected").data("jobno");
                $("#invoiceNarration").val(comment);

                if ($(this).val() == "") {
                    $('#job_item_body').html('<tr class="danger"><td colspan="5" class="text-center"><b>No Records Found </b></td></tr>');
                } else {
                    $('#job_item_body').html('');
                    $('#job_item_body').append('<tr> <td>' + $(this).find(":selected").data("itemdescription") + '<input type="hidden" class="form-control itemInvoiceDetailsAutoID" name="itemInvoiceDetailsAutoID" value=""><input type="hidden" class="form-control itemAutoID" name="itemAutoID" value="' + $(this).find(":selected").data("itemautoid") + '"></td> <td>' + $(this).find(":selected").data("uom") + '</td> <td><input type="text" name="expectedQty" value="' + qty + '"  onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number requestedQty" onfocus="this.select();" readonly> </td> <td><input type="text" name="unitRate" value="' + unitPrice + '"  onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number amount" onfocus="this.select();"> </td> <td class="text-right" style="vertical-align: middle"> <span class="totalAmount">' + commaSeparateNumber((qty * unitPrice), 2) + '</span></td> </tr>');
                }

            });
        });

        function initializeCustomerInvoiceDetailTypeahead(id) {
            $('#f_search_' + id).autocomplete({
                serviceUrl: '<?php echo site_url();?>/MFQ_CustomerInvoice/fetch_chartofaccount/',
                onSelect: function (suggestion) {
                    setTimeout(function () {
                        $('#f_search_' + id).closest('tr').find('.revenueGLAutoID').val(suggestion.GLAutoID);
                    }, 200);
                    //fetch_related_uom_id(suggestion.defaultUnitOfMeasureID, suggestion.defaultUnitOfMeasureID, this);
                    //getJobQty(this);
                },
                /*showNoSuggestionNotice: true,
                 noSuggestionNotice:'No record found',*/
            });
            $(".tt-dropdown-menu").css("top", "");
        }

        function getJobQty(element) {
            $(element).closest('tr').find('.requestedQty').val(qty);
            $(element).closest('tr').find('.amount').val(unitPrice);
            var total = parseFloat(qty) * parseFloat(unitPrice);
            $(element).closest('tr').find('.totalAmount').text(commaSeparateNumber(parseFloat(total), 2));
        }

        function add_more_row() {
            search_id += 1;
            $('select.select2').select2('destroy');
            var appendData = $('#mfq_customer_invoice tbody tr:first').clone();
            appendData.find('.f_search').attr('id', 'f_search_' + search_id);
            //appendData.find('.f_search').attr('onkeyup', 'clearitemAutoID(event,this)');
            appendData.find('input').val('');
            appendData.find('.requestedQty').val('0.00');
            appendData.find('.amount').val('0.00');
            appendData.find('.totalAmount').text('0.00');
            appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr2" style="color:rgb(209, 91, 71);"></span>');
            $('#mfq_customer_invoice').append(appendData);
            initializeCustomerInvoiceDetailTypeahead(search_id);
            $('.select2').select2();
            number_validation();
        }

        function load_customer_invoice_detail(invoiceAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {invoiceAutoID: invoiceAutoID},
                url: "<?php echo site_url('MFQ_CustomerInvoice/load_mfq_customerInvoiceDetail'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#customer_invoice_body').html('');
                    var i = 0;
                    var isRecordExist = 0;
                    if (!$.isEmptyObject(data)) {
                        $.each(data, function (k, v) {
                            if (v.type == 1) {
                                isRecordExist = 1;
                                var segment = '<?php
                                    echo str_replace(array("\n", '<select'), array('', '<select id="ci_\'+search_id+\'"'), form_dropdown('segmentID[]', $segment, 'Each', 'class="form-control segmentID select2"  required'))
                                    ?>';
                                $('#customer_invoice_body').append('<tr id="rowCI_' + v.invoiceDetailsAutoID + '"> <td><input type="text" class="form-control f_search" name="search[]" placeholder="GLAuto ID, GL Description..." id="f_search_' + search_id + '" value="' + v.GLDescription + '"> <input type="hidden" class="form-control revenueGLAutoID" name="revenueGLAutoID[]" value="' + v.revenueGLAutoID + '"> <input type="hidden" class="form-control invoiceDetailsAutoID" name="invoiceDetailsAutoID[]" value="' + v.invoiceDetailsAutoID + '"> </td> <td>' + segment + '</td> <td><input type="text" name="requestedQty[]" value="' + v.requestedQty + '"  onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number requestedQty" onfocus="this.select();"> </td> <td><input type="text" name="amount[]" value="' + v.unitRate + '"  onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number amount" onfocus="this.select();"> </td> <td class="text-right" style="vertical-align: middle"> <span class="totalAmount">' + commaSeparateNumber((v.requestedQty * v.unitRate), 2) + '</span></td>  <td class="remove-td" style="vertical-align: middle;text-align: center"><span onclick="delete_customerInvoiceDetail(' + v.invoiceDetailsAutoID + ',' + v.invoiceAutoID + ')" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></td> </tr>');
                                if(v.segmentID != '' &&  v.segmentID != 0) {
                                    $('#ci_' + search_id).val(v.segmentID);
                                }else{
                                    $('#ci_' + search_id).val('');
                                }
                                initializeCustomerInvoiceDetailTypeahead(search_id);
                                search_id++;
                                i++;
                            } else {
                                $('#job_item_body').html('');
                                $('#job_item_body').append('<tr> <td>' + v.itemDescription + '<input type="hidden" class="form-control itemInvoiceDetailsAutoID" name="itemInvoiceDetailsAutoID" value="' + v.invoiceDetailsAutoID + '"><input type="hidden" class="form-control itemAutoID" name="itemAutoID" value="' + v.itemAutoID + '"></td> <td>' + v.defaultUnitOfMeasure + '</td> <td><input type="text" name="expectedQty" value="' + v.requestedQty + '"  onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number requestedQty" onfocus="this.select();" readonly> </td> <td><input type="text" name="unitRate" value="' + v.unitRate + '"  onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number amount" onfocus="this.select();"> </td> <td class="text-right" style="vertical-align: middle"> <span class="totalAmount">' + commaSeparateNumber((v.requestedQty * v.unitRate), 2) + '</span></td> </tr>');
                            }
                        });
                    }
                    if(!isRecordExist){
                        init_customerInvoiceDetailForm();
                    }
                    $('.select2').select2();
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function init_customerInvoiceDetailForm() {
            var segment = '<?php
                echo str_replace(array("\n", '<select'), array('', '<select id="ci_1"'), form_dropdown('segmentID[]', $segment, 'Each', 'class="form-control segmentID select2"  required'))
                ?>';
            $('#customer_invoice_body').html('');
            $('#customer_invoice_body').append('<tr>  <td><input type="text" class="form-control f_search" name="search[]" placeholder="GLAuto ID, GL Description..." id="f_search_1"> <input type="hidden" class="form-control revenueGLAutoID" name="revenueGLAutoID[]"><input type="hidden" class="form-control invoiceDetailsAutoID" name="invoiceDetailsAutoID[]"> </td> <td>' + segment + '</td> <td><input type="text" name="requestedQty[]" value="0.00" onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number requestedQty" onfocus="this.select();"> </td> <td><input type="text" name="amount[]" value="0.00" onkeyup="calculateTotal(this)" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number amount" onfocus="this.select();"> </td> <td class="text-right" style="vertical-align: middle"> <span class="totalAmount">0.00</span></td> <td class="remove-td" style="vertical-align: middle;text-align: center"></td> </tr>');
            number_validation();
            $('.select2').select2();
            setTimeout(function () {
                initializeCustomerInvoiceDetailTypeahead(1);
            }, 500);
        }

        function loadCustomerInvoice() {
            if (invoiceAutoID > 0) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url("MFQ_CustomerInvoice/load_mfq_customerInvoice"); ?>',
                    dataType: 'json',
                    data: {invoiceAutoID: invoiceAutoID},
                    async: false,
                    success: function (data) {
                        $("#invoiceDate").val(data['invoiceDate']).change();
                        $("#invoiceDueDate").val(data['invoiceDueDate']).change();
                        $("#currencyID").val(data['transactionCurrencyID']).change();
                        $("#invoiceNarration").val(data['invoiceNarration']);
                        deliveryNoteID = data["deliveryNoteID"];
                        setTimeout(function () {
                            $("#mfqCustomerAutoID").val(data['mfqCustomerAutoID']).change();
                        }, 500);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        myAlert('e', xhr.responseText);
                    }
                });
            }
        }

        function saveCustomerInvoice(type) {
            var data = $(".frm_customerInvoice").serializeArray();
            data.push({'name': 'status', 'value': type});
            $.ajax({
                url: "<?php echo site_url('MFQ_CustomerInvoice/save_customer_invoice'); ?>",
                type: 'post',
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        if (type == 2) {
                            $('.headerclose').trigger('click');
                        } else {
                            $("#invoiceAutoID").val(data[2]);
                            invoiceAutoID = data[2];
                            $("#documentSystemCode").val(data[2]);
                            $('.btn-wizard').removeClass('disabled');
                            load_customer_invoice_detail(data[2]);
                        }

                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    stopLoad();
                    myAlert('e', xhr.responseText);
                }
            });
        }


        function confirmCustomerInvoice() {
            swal({
                    title: "Are you sure?",
                    text: "You want to confirm?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                },
                function () {
                    $.ajax({
                        url: "<?php echo site_url('MFQ_CustomerInvoice/customer_invoice_confirmation'); ?>",
                        type: 'post',
                        data: {invoiceAutoID: invoiceAutoID},
                        dataType: 'json',
                        cache: false,
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert(data[0], data[1]);
                            if (data[0] == 's') {
                                $('.headerclose').trigger('click');
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            stopLoad();
                            myAlert('e', xhr.responseText);
                        }
                    });
                });
        }

        function delete_customerInvoiceDetail(invoiceDetailsID, masterID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to Delete this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "delete",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        url: "<?php echo site_url('MFQ_CustomerInvoice/delete_customerInvoiceDetail'); ?>",
                        type: 'post',
                        data: {invoiceDetailsID: invoiceDetailsID, masterID: masterID},
                        dataType: 'json',
                        cache: false,
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data['error'] == 1) {
                                swal("Error!", data['message'], "error");
                            }
                            else if (data['error'] == 0) {
                                if (data.code == 1) {
                                    init_customerInvoiceDetailForm();
                                }
                                $("#rowCI_" + invoiceDetailsID).remove();
                                swal("Deleted!", data['message'], "success");
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            stopLoad();
                            myAlert('e', xhr.responseText);
                        }
                    });
                });
        }

        function document_uplode() {
            var formData = new FormData($("#attachment_uplode_form")[0]);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?php echo site_url('Attachment/do_upload'); ?>",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data['type'], data['message'], 1000);
                    if (data['status']) {
                        load_attachments('CI', $("#documentSystemCode").val());
                    }
                },
                error: function (data) {
                    stopLoad();
                    swal("Cancelled", "No File Selected :)", "error");
                }
            });
            return false;
        }

        function load_attachments(documentID, invoiceAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {documentID: documentID, documentSystemCode: invoiceAutoID},
                url: "<?php echo site_url('MFQ_CustomerInvoice/load_attachments'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#show_all_attachments').html(data);
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function delete_attachment(id, myFileName) {
            swal({
                    title: "Are you sure?",
                    text: "You want to Delete!",
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
                        data: {attachmentID: id, myFileName: myFileName},
                        url: "<?php echo site_url('Attachment/delete_attachment'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data) {
                                myAlert('s', 'Deleted Successfully');
                                load_attachments('CI', $('#documentSystemCode').val());
                            } else {
                                myAlert('e', 'Deletion Failed');
                            }
                        },
                        error: function () {
                            stopLoad();
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function customer_invoice_print() {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {
                    invoiceAutoID: $('#invoiceAutoID').val()
                },
                url: "<?php echo site_url('MFQ_CustomerInvoice/fetch_customer_invoice_print'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $("#review").html(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function get_delivery_note(mfqCustomerAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    mfqCustomerAutoID: mfqCustomerAutoID,
                    invoiceAutoID: $('#invoiceAutoID').val()

                },
                url: "<?php echo site_url('MFQ_CustomerInvoice/fetch_delivery_note'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#deliveryNoteID').empty();
                    var mySelect = $('#deliveryNoteID');
                    mySelect.append($('<option></option>').val("").html("Select"));
                    if (!$.isEmptyObject(data)) {
                        $.each(data, function (k, text) {
                            mySelect.append($('<option data-itemdescription="' + text['itemSystemCode'] + ' - ' + text['itemDescription'] + '" data-uom="' + text['defaultUnitOfMeasure'] + '"  data-itemAutoID="' + text['mfqItemID'] + '" data-description="' + text['description'] + '" data-jobNo="' + text['documentCode'] + '" data-qty="' + text['qty'] + '" data-unitprice = "' + text['unitPrice'] + '"></option>').val(text['deliverNoteID']).html(text['deliveryNoteCode']));
                        });
                    }
                    if (deliveryNoteID) {
                        mySelect.val(deliveryNoteID);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function calculateTotal(element) {
            var expectedQty = $(element).closest('tr').find('.requestedQty').val();
            var amount = $(element).closest('tr').find('.amount').val();

            var total = parseFloat(expectedQty) * parseFloat(amount);
            $(element).closest('tr').find('.totalAmount').text(commaSeparateNumber(parseFloat(total), 2));

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
            if ((caratPos > dotPos) && (dotPos > -(currency_decimal - 1)) && (number[1].length > (currency_decimal - 1))) {
                return false;
            }
            return true;
        }

        function getSelectionStart(o) {
            if (o.createTextRange) {
                var r = document.selection.createRange().duplicate()
                r.moveEnd('character', o.value.length)
                if (r.text == '') return o.value.length
                return o.value.lastIndexOf(r.text)
            } else return o.selectionStart
        }

    </script>
