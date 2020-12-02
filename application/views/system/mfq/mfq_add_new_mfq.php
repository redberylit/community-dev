<?php
$date_format_policy = date_format_policy();
$from = convert_date_format($this->common_data['company_data']['FYPeriodDateFrom']);
$currency_arr = all_currency_new_drop();
$current_date = current_format_date();
$segment = fetch_mfq_segment(true);
$page_id = isset($page_id) && $page_id ? $page_id : 0;

?>
<?php echo head_page($_POST["page_name"], false); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/tabs.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/typehead.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
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
    <a class="btn btn-primary" href="#rfq" data-toggle="tab">Customer Inquiry</a>
    <a class="btn btn-default btn-wizard" href="#attachment" data-toggle="tab">
        Attachment</a>
    <a class="btn btn-default btn-wizard" href="#print" onclick="customer_inquiry_print();" data-toggle="tab">
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
                            <form id="frm_customerInquiry" class="frm_customerInquiry" method="post">
                                <input type="hidden" id="ciMasterID" name="ciMasterID"
                                       value="<?php echo $page_id ?>">
                                <header class="head-title">
                                    <h2>Customer Inquiry Information </h2>
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
                                                <label class="title">Manufacturing Type </label>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('manufacturingType', ['' => 'Select','1' => 'Third Party','2' => 'In House'], '', 'class="form-control select2" id="manufacturingType"');
                                                    ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Inquiry Date </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <div class='input-group date filterDate' id="">
                                                        <input type='text' class="form-control"
                                                               name="documentDate"
                                                               id="documentDate"
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
                                                <label class="title">Actual Submission Date </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <div class='input-group date filterDate' id="">
                                                        <input type='text' class="form-control"
                                                               name="deliveryDate"
                                                               id="deliveryDate"
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
                                                <label class="title">Planned Submission Date </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <div class='input-group date filterDate' id="">
                                                        <input type='text' class="form-control"
                                                               name="dueDate"
                                                               id="dueDate"
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
                                                <label class="title">Status </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <?php echo form_dropdown('statusID', all_mfq_status(1), 1, 'class="form-control" id="statusID"'); ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!--<div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4 md-offset-2">
                                                <label class="title">Payment Terms </label>
                                            </div>
                                            <div class="form-group col-sm-8">
                                                <div class="input-req" title="Required Field">
                                                            <textarea class="form-control" id="paymentTerm"
                                                                      name="paymentTerm" rows="2"></textarea>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>-->
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Client Reference No </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <input type="text" class="form-control" id="referenceNo"
                                                           name="referenceNo">
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Currency </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('currencyID', $currency_arr, $this->common_data['company_data']['company_default_currencyID'], 'class="form-control select2" id="currencyID" onchange="currency_validation(this.value,\'BOM\')" required disabled'); ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Description </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                            <textarea class="form-control" id="description"
                                                                      name="description" rows="3"></textarea>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-4">
                                                <label class="title">Inquiry Type </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('type', array('' => 'Select', '1' => 'Tender', '2' => 'RFQ', '3' => 'SPC'), '2', 'class="form-control" id="type"'); ?>
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
                                                    <table id="mfq_customer_inquiry"
                                                           class="table table-condensed">
                                                        <thead>
                                                        <tr>
                                                            <th style="min-width: 12%">Item</th>
                                                            <th style="min-width: 12%">Expected Qty</th>
                                                            <th style="min-width: 12%">UOM</th>
                                                            <th style="min-width: 12%">Department</th>
                                                            <th style="min-width: 12%">Delivery Date</th>
                                                            <th style="min-width: 12%">Remarks</th>
                                                            <th style="min-width: 12%">Delivery Terms</th>
                                                            <th style="min-width: 5%">
                                                                <div class=" pull-right">
                                                            <span class="button-wrap-box">
                                                                <button type="button" data-text="Add" id="btnAdd"
                                                                        onclick="add_more_material()"
                                                                        class="button button-square button-tiny button-royal button-raised">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                            </span>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="customer_inquiry_body">

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
                                            <button class="btn btn-primary" onclick="saveCustomerInquiry(1)"
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
    <div class="tab-pane" id="attachment">
        <br>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>Attachment</h2>
                </header>
                <div class="row">
                    <?php echo form_open_multipart('', 'id="attachment_uplode_form" class="form-inline"'); ?>
                    <input type="hidden" name="documentSystemCode" id="documentSystemCode"
                           value="<?php echo $page_id ?>">
                    <input type="hidden" name="documentID" id="documentID" value="CI">
                    <input type="hidden" name="document_name" id="document_name" value="Customer Inquiry">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control"
                                       name="attachmentDescription" placeholder="Description..."
                                       style="width: 240%;">
                            </div>
                        </div>
                        <div class="col-sm-8" style="margin-top: -8px;">
                            <div class="form-group">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput"
                                     style="margin-top: 8px;">
                                    <div class="form-control" data-trigger="fileinput"><i
                                                class="glyphicon glyphicon-file color fileinput-exists"></i> <span
                                                class="fileinput-filename"></span></div>
                                    <span class="input-group-addon btn btn-default btn-file"><span
                                                class="fileinput-new"><span class="glyphicon glyphicon-plus"
                                                                            aria-hidden="true"></span></span><span
                                                class="fileinput-exists"><span class="glyphicon glyphicon-repeat"
                                                                               aria-hidden="true"></span></span><input
                                                type="file" name="document_file" id="document_file"></span>
                                    <a class="input-group-addon btn btn-default fileinput-exists" id="remove_id"
                                       data-dismiss="fileinput"><span class="glyphicon glyphicon-remove"
                                                                      aria-hidden="true"></span></a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-default"
                                    onclick="document_uplode()"><span
                                        class="glyphicon glyphicon-floppy-open color" aria-hidden="true"></span>
                            </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" id="show_all_attachments">
                        <header class="infoarea">
                            <div class="search-no-results">NO ATTACHMENT FOUND
                            </div>
                        </header>
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
                    <button class="btn btn-success" onclick="confirmCustomerInquiry()"
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
        var ciMasterID="";
        $(document).ready(function () {
            $('.select2').select2();
            $('.filterDate').datetimepicker({
                useCurrent: false,
                format: date_format_policy
            });
            //$('#confirmBtn').hide();
            $('.headerclose').click(function () {
                fetchPage('system/mfq/mfq_rfq', '', 'Customer Inquiry');
            });
            Inputmask().mask(document.querySelectorAll("input"));
            <?php
            if ($page_id) {
            ?>
            ciMasterID=parseInt("<?php echo $page_id  ?>");
            loadCustomerInquiry();
            load_customer_inquiry_detail('<?php echo $page_id  ?>');
            load_attachments('CI',<?php echo $page_id  ?>);
            <?php
            }else{
            ?>
            $('.btn-wizard').addClass('disabled');
            init_customerInquiryDetailForm();
            <?php
            }
            ?>
            initializeCustomerInquiryDetailTypeahead(1);
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

            /*$('#statusID').change(function () {
                if ($(this).val() == 2) {
                    $('#confirmBtn').show();
                } else {
                    $('#confirmBtn').hide();
                }

            })*/
        });

        function add_more_material() {
            search_id += 1;
            //$('select.select2').select2('destroy');
            var appendData = $('#mfq_customer_inquiry tbody tr:first').clone();
            appendData.find('.f_search').attr('id', 'f_search_' + search_id);
            appendData.find('.f_search').attr('onkeyup', 'clearitemAutoID(event,this)');
            appendData.find('input').val('');
            appendData.find('textarea').val('');
            appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr2" style="color:rgb(209, 91, 71);"></span>');
            $('#mfq_customer_inquiry').append(appendData);
            var lenght = $('#mfq_customer_inquiry tbody tr').length - 1;

            number_validation();
            initializeCustomerInquiryDetailTypeahead(search_id);
            $('.filterDate').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            });
            Inputmask().mask(document.querySelectorAll("input"));
            //initializeCustomerInquiryDetailTypeahead(1);
        }

        function load_customer_inquiry_detail(ciMasterID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {ciMasterID: ciMasterID},
                url: "<?php echo site_url('MFQ_CustomerInquiry/load_mfq_customerInquiryDetail'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#customer_inquiry_body').html('');
                    var i = 0;
                    if (!$.isEmptyObject(data)) {
                        $.each(data, function (k, v) {
                            var segment = '<?php
                                echo str_replace(array("\n", '<select'), array('', '<select id="ci_\'+search_id+\'"'), form_dropdown('segmentID[]', $segment, 'Each', 'class="form-control segmentID"  required'))
                                ?>';
                            $('#customer_inquiry_body').append('<tr id="rowMC_' + v.ciDetailID + '"> <td> <input type="text" onkeyup="clearitemAutoID(event,this)" class="form-control f_search" name="search[]" placeholder="Item ID, Item Description..." value="' + v.itemDescription + '" id="f_search_' + search_id + '"> <input type="hidden" class="form-control mfqItemID" name="mfqItemID[]" value="' + v.mfqItemID + '"> <input type="hidden" class="form-control ciDetailID" name="ciDetailID[]" value="' + v.ciDetailID + '"> </td> <td><input type="text" name="expectedQty[]" id="expectedQty" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number expectedQty" onfocus="this.select();" value="' + v.expectedQty + '"> </td> <td><input type="text" name="uom[]" id="uom" class="form-control uom" value="' + v.UnitDes + '" readonly> </td> <td>' + segment + '</td> <td><div class="input-group date filterDate" id=""> <input type="text" class="form-control" name="expectedDeliveryDate[]" id="expectedDeliveryDate" value="' + v.expectedDeliveryDate + '" data-inputmask="\'alias\': \'<?php echo $date_format_policy ?>\'"> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div> </td> <td><textarea name="remarks[]" id="remarks" class="form-control" rows="1">' + v.remarks + '</textarea> </td> <td><textarea name="deliveryTerms[]" id="deliveryTerms" class="form-control" rows="1">' + v.deliveryTerms + '</textarea> </td>  <td class="remove-td" style="vertical-align: middle;text-align: center"><span onclick="delete_customerInquiryDetail(' + v.ciDetailID + ',' + v.ciMasterID + ')" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></td> </tr>');
                            initializeCustomerInquiryDetailTypeahead(search_id);
                            $('.filterDate').datetimepicker({
                                useCurrent: false,
                                format: date_format_policy,
                            });
                            Inputmask().mask(document.querySelectorAll("input"));
                            $('#ci_' + search_id).val(v.segmentID);
                            search_id++;
                            i++;
                        });
                    } else {
                        init_customerInquiryDetailForm();
                    }
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function initializeCustomerInquiryDetailTypeahead(id) {
            $('#f_search_' + id).autocomplete({
                serviceUrl: '<?php echo site_url();?>/MFQ_Job_Card/fetch_finish_goods/',
                onSelect: function (suggestion) {
                    setTimeout(function () {
                        $('#f_search_' + id).closest('tr').find('.mfqItemID').val(suggestion.mfqItemID);
                        $('#f_search_' + id).closest('tr').find('.uom').val(suggestion.uom);
                    }, 200);
                    //fetch_related_uom_id(suggestion.defaultUnitOfMeasureID, suggestion.defaultUnitOfMeasureID, this);
                },
                /*showNoSuggestionNotice: true,
                 noSuggestionNotice:'No record found',*/
            });
            $(".tt-dropdown-menu").css("top", "");
        }

        function init_customerInquiryDetailForm() {
            var segment = '<?php
                echo str_replace(array("\n", '<select'), array('', '<select id="ci_1"'), form_dropdown('segmentID[]', $segment, 'Each', 'class="form-control segmentID"  required'))
                ?>';
            $('#customer_inquiry_body').html('');
            $('#customer_inquiry_body').append('<tr> <td> <input type="text" onkeyup="clearitemAutoID(event,this)" class="form-control f_search" name="search[]" placeholder="Item ID, Item Description..." id="f_search_1"> <input type="hidden" class="form-control mfqItemID" name="mfqItemID[]"> <input type="hidden" class="form-control ciDetailID" name="ciDetailID[]"> </td> <td><input type="text" name="expectedQty[]" id="expectedQty" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number expectedQty" onfocus="this.select();"> </td> <td><input type="text" name="uom[]" id="uom" class="form-control uom" readonly> </td><td>' + segment + '</td> <td><div class="input-group date filterDate" id=""> <input type="text" class="form-control" name="expectedDeliveryDate[]" id="expectedDeliveryDate" value="" data-inputmask="\'alias\': \'<?php echo $date_format_policy ?>\'"> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div> </td> <td><textarea name="remarks[]" id="remarks" class="form-control" rows="1"></textarea> </td> <td><textarea name="deliveryTerms[]" id="deliveryTerms" class="form-control" rows="1"></textarea> </td> <td class="remove-td" style="vertical-align: middle;text-align: center"></td> </tr>');
            number_validation();
            $('.filterDate').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
                sideBySide: true,
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'top'
                }
            });
            Inputmask().mask(document.querySelectorAll("input"));
            setTimeout(function () {
                initializeCustomerInquiryDetailTypeahead(1);
            }, 500);
        }

        function loadCustomerInquiry() {
            if (ciMasterID > 0) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url("MFQ_CustomerInquiry/load_mfq_customerInquiry"); ?>',
                    dataType: 'json',
                    data: {ciMasterID: ciMasterID},
                    async: false,
                    success: function (data) {
                        $("#mfqCustomerAutoID").val(data['mfqCustomerAutoID']).change();
                        $("#documentDate").val(data['documentDate']).change();
                        $("#deliveredDate").val(data['deliveredDate']).change();
                        $("#dueDate").val(data['dueDate']).change();
                        $("#description").val(data['description']);
                        $("#referenceNo").val(data['referenceNo']);
                        $("#statusID").val(data['statusID']);
                        $("#type").val(data['type']);
                        /*if (data['statusID'] == 2) {
                            $('#confirmBtn').show();
                        } else {
                            $('#confirmBtn').hide();
                        }*/
                        //$("#paymentTerm").val(data['paymentTerm']);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        myAlert('e', xhr.responseText);
                    }
                });
            }
        }

        function saveCustomerInquiry(type) {
            var data = $(".frm_customerInquiry").serializeArray();
            data.push({'name': 'status', 'value': type});
            $.ajax({
                url: "<?php echo site_url('MFQ_CustomerInquiry/save_CustomerInquiry'); ?>",
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
                            $("#ciMasterID").val(data[2]);
                            ciMasterID=data[2];
                            $("#documentSystemCode").val(data[2]);
                            $('.btn-wizard').removeClass('disabled');
                            load_customer_inquiry_detail(data[2]);
                        }

                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    stopLoad();
                    myAlert('e', xhr.responseText);
                }
            });
        }


        function confirmCustomerInquiry() {
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
                        url: "<?php echo site_url('MFQ_CustomerInquiry/customer_inquiry_confirmation'); ?>",
                        type: 'post',
                        data: {ciMasterID: ciMasterID},
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

        function delete_customerInquiryDetail(ciDetailID, masterID) {
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
                        url: "<?php echo site_url('MFQ_CustomerInquiry/delete_customerInquiryDetail'); ?>",
                        type: 'post',
                        data: {ciDetailID: ciDetailID, masterID: masterID},
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
                                    init_customerInquiryDetailForm();
                                }
                                $("#rowMC_" + ciDetailID).remove();
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

        function load_attachments(documentID, ciMasterID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {documentID: documentID, documentSystemCode: ciMasterID},
                url: "<?php echo site_url('MFQ_CustomerInquiry/load_attachments'); ?>",
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

        function customer_inquiry_print() {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {
                    ciMasterID: $('#ciMasterID').val(),
                },
                url: "<?php echo site_url('MFQ_CustomerInquiry/fetch_customer_inquiry_print'); ?>",
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

        function clearitemAutoID(e, ths) {
            var keyCode = e.keyCode || e.which;
            if (keyCode == 9) {
                //e.preventDefault();
            } else {
                $(ths).closest('tr').find('.mfqItemID').val('');
                $(ths).closest('tr').find('.uom').val('');
            }
        }

    </script>
