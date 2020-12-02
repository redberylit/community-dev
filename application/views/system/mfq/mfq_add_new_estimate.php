<?php
$date_format_policy = date_format_policy();
$from = convert_date_format($this->common_data['company_data']['FYPeriodDateFrom']);
$currency_arr = all_currency_new_drop();
$current_date = current_format_date();
$standard = getStandardDetail();
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
    <a class="btn btn-primary" href="#header" data-toggle="tab">Estimate Header</a>
    <a class="btn btn-default btn-wizard" href="#detail" data-toggle="tab">
        Estimate Detail</a>
    <a class="btn btn-default btn-wizard" href="#print" onclick="estimate_print();" data-toggle="tab">
        Confirmation</a>
</div>
<hr>
<div class="tab-content">
    <div class="tab-pane active" id="header">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-md-12 animated zoomIn">
                            <form class="frm_estimate" method="post">
                                <input type="hidden" id="estimateMasterID" name="estimateMasterID"
                                       value="<?php echo $page_id ?>">
                                <header class="head-title">
                                    <h2>Estimate Information </h2>
                                </header>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Customer </label>
                                            </div>

                                            <div class="form-group col-sm-4">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('mfqCustomerAutoID', all_mfq_customer_drop(), '', 'class="form-control select2" id="est-mfqCustomerAutoID"');
                                                    ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Estimate Date </label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <div class='input-group date filterDate' id="">
                                                        <input type='text' class="form-control"
                                                               name="documentDate"
                                                               id="est_documentDate"
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
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Delivery Date </label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                <div class='input-group date filterDate' id="">
                                                    <input type='text' class="form-control"
                                                           name="deliveryDate"
                                                           id="est-deliveryDate"
                                                           value="<?php echo $current_date; ?>"
                                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"/>
                                                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Currency </label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <div class="input-req" title="Required Field">
                                                    <?php echo form_dropdown('currencyID', $currency_arr, $this->common_data['company_data']['company_default_currencyID'], 'class="form-control select2" id="est-currencyID" onchange="currency_validation(this.value,\'BOM\')" required disabled'); ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2">
                                                <label class="title">Approval Status </label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <div class="input-req" title="Required Field">
                                                    <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                    <?php echo form_dropdown('submissionStatus', all_mfq_status(2), 7, 'class="form-control" id="est-submissionStatus"'); ?>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Warranty </label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <?php $key = array_search(2, array_column($standard, 'typeID'));
                                                ?>
                                                <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                                <?php echo form_dropdown('warranty', all_mfq_month_drop(), $key != "" ? $standard[$key]["Description"] : "", 'class="form-control" id="est-warranty"'); ?>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Description </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <div class="input-req" title="Required Field">
                                                            <textarea class="form-control" id="est-description"
                                                                      name="description" rows="2"></textarea>
                                                    <span class="input-req-inner"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Scope of Work </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <textarea class="form-control" id="est-scopeOfWork" name="scopeOfWork"
                                                          rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Technical Detail </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <textarea class="form-control" id="est-technicalDetail"
                                                          name="technicalDetail" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title"> Exclusions </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                    <textarea class="form-control" id="est-exclusions"
                                                              name="exclusions" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Payment Terms </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <textarea class="form-control" id="est-paymentTerms" name="paymentTerms"
                                                          rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title">Terms & condition </label>
                                            </div>
                                            <?php $key = array_search(1, array_column($standard, 'typeID'));
                                            ?>
                                            <div class="form-group col-sm-10">
                                                <textarea class="form-control richtext" id="est-termsAndCondition"
                                                          name="termsAndCondition"
                                                          rows="2"><?php echo (string)$key != "" ? $standard[$key]["Description"] : ""; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title"> Delivery Terms </label>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                    <textarea class="form-control" id="est-deliveryTerms"
                                                              name="deliveryTerms" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="form-group col-sm-2 md-offset-2">
                                                <label class="title"> Validity </label>
                                            </div>
                                            <?php $key = array_search(3, array_column($standard, 'typeID')); ?>
                                            <div class="form-group col-sm-10">
                                                    <textarea class="form-control richtext" id="est-validity"
                                                              name="validity"
                                                              rows="2"><?php echo $key != "" ? $standard[$key]["Description"] : ""; ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-12 ">
                                        <div class="pull-right">
                                            <button class="btn btn-primary" onclick="saveEstimate()"
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
    <div class="tab-pane" id="detail">
        <br>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <div class="row">
                    <div class="col-md-12">
                        <header class="head-title">
                            <h2>Item Detail </h2>
                        </header>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-condesed mfqTable">
                            <thead>
                            <tr>
                                <th colspan="5"> Item Detail</th>
                                <th colspan="6">Cost Detail <span
                                            class="currency">(<?php echo $this->common_data['company_data']['company_default_currency']; ?>
                                        )</span></th>
                                <th style="width: 5%">
                                    <button type="button" onclick="estimate_detail_modal()"
                                            class="btn btn-primary btn-sm"><i
                                                class="fa fa-plus"></i> Add Item
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th style="min-width: 5%">#</th>
                                <th style="min-width: 10%">Code</th>
                                <th style="min-width: 25%" class="text-left">Description</th>
                                <th style="min-width: 5%">UOM</th>
                                <th style="min-width: 5%">Qty</th>
                                <th style="min-width: 10%">Unit Cost</th>
                                <th style="min-width: 12%">Total Cost</th>
                                <th style="width: 10%">Margin(%)</th>
                                <th style="width: 12%">Selling Price</th>
                                <th style="width: 10%">Discount(%)</th>
                                <th style="width: 10%">Discounted Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="est-table_body">
                            <tr class="danger">
                                <td colspan="10" class="text-center"><b>No Records Found</b></td>
                            </tr>
                            </tbody>
                            <tfoot id="table_tfoot">
                            <tr>
                                <td colspan="4">
                                    <div class="text-right">Total</div>
                                </td>
                                <td>
                                    <div id="est-tot_qty"
                                         style="" class="text-right">0.00
                                    </div>
                                </td>
                                <td>
                                    <div id="est-tot_unitCost"
                                         style="" class="text-right">0.00
                                    </div>
                                </td>
                                <td>
                                    <div id="est-tot_totCost"
                                         style="" class="text-right">0.00
                                    </div>
                                </td>
                                <td colspan="2"></td>
                                <td>
                                    <div>&nbsp;
                                    </div>
                                    <div style="text-align: right">
                                        Margin(%)
                                    </div>
                                    <div>&nbsp;</div>
                                    <div>&nbsp;</div>
                                    <div style="text-align: right">
                                        Discount(%)
                                    </div>
                                </td>
                                <td>
                                    <div id="est-tot_sellingPrice"
                                         style="text-align: right">0.00
                                    </div>
                                    <div>
                                        <input type="text" name="marginTot" placeholder="0" id="est-marginPerTot"
                                               class="number" value="0"
                                               onkeypress="return validateFloatKeyPress(this,event)"
                                               onfocus="this.select();" onkeyup="calculateItemTotal()"
                                               onchange="save_estimate_detail_margin_total()" style="width: 100%">
                                    </div>
                                    <div id="est-tot_masterSellingPrice"
                                         style="text-align: right">0.00
                                    </div>
                                    <div>
                                        <input type="text" name="discountTot" placeholder="0" id="est-discountPerTot"
                                               class="number" value="0"
                                               onkeypress="return validateFloatKeyPress(this,event)"
                                               onfocus="this.select();" onkeyup="calculateItemTotal()"
                                               onchange="save_estimate_detail_discount_total()" style="width: 100%">
                                    </div>
                                    <div id="est-tot_masterDiscountPrice"
                                         style="text-align: right">0.00
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
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
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-12 ">
                        <div class="pull-right">
                            <button class="btn btn-success" onclick="confirmEstimate()"
                                    type="button"
                                    id="">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="estimate_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-width="95%" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width: 85%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Customer Inquiry</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="box box-widget widget-user-2">
                                <div class="widget-user-header bg-yellow">
                                    <h5>Customer Inquiry</h5>
                                </div>
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked" id="ciCode">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <form id="frm_customerInquiry" class="fromCustomerInquiry" method="post">
                                <input type="hidden" id="customerID" name="customerID" value="">
                                <table class="table table-striped table-condesed mfqTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th class="text-left">Description</th>
                                        <th>UOM</th>
                                        <th>Total Qty</th>
                                        <th>Balance Qty</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_body_ci_detail">

                                    </tbody>
                                </table>
                            </form>

                            <!--if there is no item in customer inquiry display this form to add item to perticular customer inquiry-->
                            <!--initially this will be hidden-->
                            <form id="frm_customerInquiryDirect" class="directCustomerInquiry" method="post">
                                <input type="hidden" id="direct_mfqCustomerAutoID" name="mfqCustomerAutoID" value="">
                                <input type="hidden" id="" name="estimateMasterID" value="<?php echo $page_id ?>">
                                <table class="table table-striped table-condesed mfqTable"
                                       id="tbl_customerInquiryDirect">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>UOM</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th style="min-width: 5%">
                                            <div class=" pull-right">
                                                            <span class="button-wrap-box">
                                                                <button type="button" data-text="Add" id="btnAdd"
                                                                        onclick="add_more_finish_goods()"
                                                                        class="button button-square button-tiny button-royal button-raised">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                            </span>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_body_ci_direct_detail">

                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnci" onclick="save_customer_inquiry_items()">Save
                        changes
                    </button>
                    <button type="button" class="btn btn-primary btncid" onclick="save_customer_inquiry_direct_items()">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bom_detail_modal" role="dialog" aria-labelledby="myModalLabel"
         data-width="95%" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width: 95%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="bomHeader"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="bomContent"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo footer_page('Right foot', 'Left foot', false); ?>
    <script src="<?php echo base_url('plugins/tinymce/tinymce.min.js'); ?>"></script>
    <script>
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
        var search_id = 1;
        var estimateMasterID = "";
        var currency_decimal = 3;
        $(document).ready(function () {
            $('.select2').select2();
            $('.filterDate').datetimepicker({
                useCurrent: false,
                format: date_format_policy
            });
            $('.headerclose').click(function () {
                fetchPage('system/mfq/mfq_estimate', '', 'Estimate');
            });
            Inputmask().mask(document.querySelectorAll("input"));
            <?php
            if ($page_id) {
            ?>
            estimateMasterID = '<?php echo $page_id  ?>';
            load_estimate_detail('<?php echo $page_id  ?>');
            $('[href=#detail]').tab('show');
            <?php
            }else{
            ?>
            $('.btn-wizard').addClass('disabled');
            <?php
            }
            ?>
            loadEstimate();
            $(document).on('click', '.remove-tr', function () {
                $(this).closest('tr').remove();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('a[data-toggle="tab"]').removeClass('btn-primary');
                $('a[data-toggle="tab"]').addClass('btn-default');
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
            });

            $('.directCustomerInquiry').hide();
            $('.btncid').hide();

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
            });
        });

        function loadEstimate() {
            if (estimateMasterID > 0) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url("MFQ_Estimate/load_mfq_estimate"); ?>',
                    dataType: 'json',
                    data: {estimateMasterID: estimateMasterID},
                    async: false,
                    success: function (data) {
                        $("#est-mfqCustomerAutoID").val(data['mfqCustomerAutoID']).change();
                        $("#direct_mfqCustomerAutoID").val(data['mfqCustomerAutoID']);
                        $("#est-documentDate").val(data['documentDate']).change();
                        $("#est-deliveryDate").val(data['deliveryDate']).change();
                        $("#est-description").val(data['description']);
                        $("#est-scopeOfWork").val(data['scopeOfWork']);
                        $("#est-technicalDetail").val(data['technicalDetail']);
                        $("#est-marginPerTot").val(data['totMargin']);
                        $("#est-discountPerTot").val(data['totDiscount']);
                        $("#est-submissionStatus").val(data['submissionStatus']);
                        $("#est-paymentTerms").val(data['paymentTerms']);
                        //$("#est-termsAndCondition").val(data['termsAndCondition']);
                        $("#est-deliveryTerms").val(data['deliveryTerms']);
                        //$("#est-validity").val(data['validity']);
                        $("#est-warranty").val(data['warranty']);
                        $("#est-exclusions").val(data['exclusions']);
                        $("#customerID").val(data['mfqCustomerAutoID']);
                        setTimeout(function () {
                            tinyMCE.get("est-termsAndCondition").setContent(data['termsAndCondition']);
                            tinyMCE.get("est-validity").setContent(data['validity']);
                        }, 1000);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        myAlert('e', xhr.responseText);
                    }
                });
            }
        }

        function saveEstimate() {
            tinymce.triggerSave();
            var data = $(".frm_estimate").serializeArray();
            $.ajax({
                url: "<?php echo site_url('MFQ_Estimate/save_Estimate'); ?>",
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
                        estimateMasterID = data[2];
                        $("#estimateMasterID").val(data[2]);
                        $("#customerID").val($('#est-mfqCustomerAutoID').val());
                        $('.btn-wizard').removeClass('disabled');
                        $('[href=#detail]').tab('show');
                        load_estimate_detail(data[2]);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    stopLoad();
                    myAlert('e', xhr.responseText);
                }
            });
        }

        function confirmEstimate() {
            //if($("#est-submissionStatus").val() == 4) {
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
                        url: "<?php echo site_url('MFQ_Estimate/confirm_Estimate'); ?>",
                        type: 'post',
                        data: {estimateMasterID: estimateMasterID},
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
            /*}else{
                myAlert('w','You can confirm if submission status is approved')
            }*/
        }

        function delete_estimateDetail(estimateDetailID) {
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
                        url: "<?php echo site_url('MFQ_Estimate/delete_estimateDetail'); ?>",
                        type: 'post',
                        data: {estimateDetailID: estimateDetailID, estimateMasterID: estimateMasterID},
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
                                //$("#rowET_" + estimateDetailID).remove();
                                load_estimate_detail(estimateMasterID)
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

        function estimate_print() {
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
                    $("#review").html(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function estimate_detail_modal() {
            if (estimateMasterID > 0) {
                $('.directCustomerInquiry').hide();
                $('.fromCustomerInquiry').show();
                load_customer_inquiry();
                $("#estimate_detail_modal").modal({backdrop: "static"});

            }
        }

        function load_customer_inquiry() {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {mfqCustomerAutoID: $('#customerID').val()},
                url: "<?php echo site_url('MFQ_Estimate/fetch_customer_inquiry'); ?>",
                success: function (data) {
                    $('#ciCode').empty();
                    $('#table_body_ci_detail').html('<tr class="danger"><td colspan="9" class="text-center"><b>No Records Found</b></td></tr>');
                    var mySelect = $('#ciCode');
                    if (!jQuery.isEmptyObject(data)) {
                        $.each(data, function (key, value) {
                            mySelect.append('<li><a onclick="fetch_customer_inquiry_detail(' + value['ciMasterID'] + ')">' + value['ciCode'] + ' <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span></a></li>');
                        });
                    } else {
                        mySelect.append('<li><a>No Records found</a></li>');
                    }
                }, error: function () {
                    swal("Cancelled", "Your " + value + " file is safe :)", "error");
                }
            });
        }

        function fetch_customer_inquiry_detail(ciMasterID) {
            if (ciMasterID) {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'ciMasterID': ciMasterID},
                    url: "<?php echo site_url('MFQ_Estimate/load_mfq_customerInquiryDetail'); ?>",
                    beforeSend: function () {
                        startLoad();
                        $('.btnci').show();
                    },
                    success: function (data) {
                        $('#table_body_ci_detail').empty();
                        x = 1;
                        if (jQuery.isEmptyObject(data)) {
                            $('#table_body_ci_detail').append('<tr class="danger"><td colspan="9" class="text-center"><b>No Records Found</b></td></tr>');
                            $('.directCustomerInquiry').show();
                            $('.fromCustomerInquiry').hide();
                            $('.btncid').show();
                            $('.btnci').hide();
                            init_customerInquiryDetailForm(ciMasterID);
                        } else {
                            $('.fromCustomerInquiry').show();
                            $('.directCustomerInquiry').hide();
                            $('.btncid').hide();
                            $('.btnci').show();
                            $.each(data, function (key, value) {
                                var itemSystemCode;
                                if (!value['itemSystemCode']) {
                                    itemSystemCode = '<input type="text" onkeyup="clearitemAutoID(event,this)" class="form-control ci_search" name="search[]" placeholder="Item ID, Item Description..." id="ci_search_' + x + '">';
                                } else {
                                    itemSystemCode = value['itemSystemCode'];
                                }
                                $('#table_body_ci_detail').append('<tr><td>' + x + '</td><td>' + itemSystemCode + '</td><td>' + value['itemDescription'] + '</td><td >' + value['UnitDes'] + '</td><td>' + value['expectedQty'] + '</td><td>' + value['balanceQty'] + '</td><td><input type="text" class="number" size="10" id="" name="expectedQty[]" value="' + value['balanceQty'] + '"></td><td><input type="text" class="number estimatedCost" size="10" id="" name="estimatedCost[]" value="' + value["cost"] + '"></td><td><input type="checkbox" name="checked[]" value="1"><input type="hidden" class="mfqItemID" name="mfqItemID[]" value="' + value["mfqItemID"] + '"><input type="hidden" name="ciMasterID[]" value="' + value["ciMasterID"] + '"><input type="hidden" name="ciDetailID[]" value="' + value["ciDetailID"] + '"><input type="hidden" name="bomMasterID[]" class="bomMasterID" value="' + value["bomMasterID"] + '"></td></tr>');
                                if (value['itemSystemCode'] === null) {
                                    initializeCustomerInquiryDetailTypeahead2(x);
                                }
                                x++;
                            });
                        }
                        number_validation();
                        stopLoad();
                    }, error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        stopLoad();
                    }
                });
            }
        }

        function save_customer_inquiry_items() {
            //var data = $("#frm_customerInquiry").serializeArray();
            var values = [];
            var count = $("input[name='checked[]']:checked").length;
            if (count) {
                $.each($("input[name='checked[]']:checked"), function () {
                    var data = $(this).parents('tr:eq(0)');
                    var expectedQty = $(data).find("td:eq(6) input[name='expectedQty[]']").val();
                    var estimatedCost = $(data).find("td:eq(7) input[name='estimatedCost[]']").val();
                    var mfqItemID = $(data).find("td:eq(8) input[name='mfqItemID[]']").val();
                    var ciMasterID = $(data).find("td:eq(8) input[name='ciMasterID[]']").val();
                    var ciDetailID = $(data).find("td:eq(8) input[name='ciDetailID[]']").val();
                    var bomMasterID = $(data).find("td:eq(8) input[name='bomMasterID[]']").val();
                    values.push({name: 'expectedQty[]', value: expectedQty}, {
                            name: 'estimatedCost[]',
                            value: estimatedCost
                        },
                        {name: 'mfqItemID[]', value: mfqItemID}, {
                            name: 'ciMasterID[]',
                            value: ciMasterID
                        }, {name: 'ciDetailID[]', value: ciDetailID}, {name: 'bomMasterID[]', value: bomMasterID});
                });
                values.push({name: 'mfqCustomerAutoID', value: $("#customerID").val()});
                values.push({name: 'estimateMasterID', value: estimateMasterID});

                $.ajax({
                    url: "<?php echo site_url('MFQ_Estimate/save_EstimateDetail'); ?>",
                    type: 'post',
                    data: values,
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            $("#estimate_detail_modal").modal('hide');
                            load_estimate_detail(estimateMasterID);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        stopLoad();
                        myAlert('e', xhr.responseText);
                    }
                });
            } else {
                myAlert('w', 'Please select an item');
            }
        }


        function save_customer_inquiry_direct_items() {
            var data = $("#frm_customerInquiryDirect").serializeArray();
            $.ajax({
                url: "<?php echo site_url('MFQ_Estimate/save_EstimateDetail'); ?>",
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
                        $("#estimate_detail_modal").modal('hide');
                        load_estimate_detail(estimateMasterID);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    stopLoad();
                    myAlert('e', xhr.responseText);
                }
            });
        }

        function load_estimate_detail(estimateMasterID) {
            if (estimateMasterID) {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'estimateMasterID': estimateMasterID},
                    url: "<?php echo site_url('MFQ_Estimate/load_mfq_estimate_detail'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        $('#est-table_body').empty();
                        x = 1;
                        if ($.isEmptyObject(data)) {
                            $('#est-table_body').append('<tr class="danger"><td colspan="12" class="text-center"><b>No Records Found</b></td></tr>');
                            $("#est-tot_qty").text("0.00");
                            $("#est-tot_unitCost").text("0.00");
                            $("#est-tot_totCost").text("0.00");
                            $("#est-tot_sellingPrice").text("0.00");
                            $("#est-tot_masterSellingPrice").text("0.00");
                            $("#est-tot_masterDiscountPrice").text("0.00");
                        } else {
                            if (data) {
                                $.each(data, function (key, value) {
                                    var bomMasterID = value['bomMasterID'] ? 'Edit Bill of Material' : 'Add Bill of Material';
                                    $('#est-table_body').append('<tr id="rowET_' + value['estimateDetailID'] + '"><td>' + x + '</td><td>' + value['itemSystemCode'] + '</td><td>' + value['itemDescription'] + '</td><td>' + value['UnitDes'] + '</td><td style="text-align: right">' + value['expectedQty'] + '</td><td style="text-align: right">' + commaSeparateNumber(value['estimatedCost'], value['companyLocalCurrencyDecimalPlaces']) + '</td><td style="text-align: right">' + commaSeparateNumber(value['totalCost'], value['companyLocalCurrencyDecimalPlaces']) + '</td><td><input type="text" name="margin[]" placeholder="0" class="number marginPer" value="' + value['margin'] + '" onkeypress="return validateFloatKeyPress(this,event,5)" onkeyup="cal_item_line_total(this)" onfocus="this.select();" onchange="save_estimate_detail_margin(this,' + value['estimateDetailID'] + ')"> </td><td style="text-align: right"><input type="text" name="sellingPrice[]" placeholder="0" class="number sellingPrice" value="' + value['sellingPrice'] + '" onkeypress="return validateFloatKeyPress(this,event)" onkeyup="cal_item_line_total_selling_price(this)" onfocus="this.select();" onchange="save_estimate_detail_selling_price(this,' + value['estimateDetailID'] + ')"></td><td><input type="text" name="discount[]" placeholder="0" class="number discountPer" value="' + value['discount'] + '" onkeypress="return validateFloatKeyPress(this,event)" onkeyup="cal_item_line_total(this)" onfocus="this.select();" onchange="save_estimate_detail_discount(this,' + value['estimateDetailID'] + ')"> </td><td style="text-align: right"><span class="totDiscountPrice">' + commaSeparateNumber(value['discountedPrice'], value['companyLocalCurrencyDecimalPlaces']) + '</span><input type="hidden" name="discountedPrice" placeholder="0" class="discountedPrice" value="' + value['discountedPrice'] + '"> </td><td><a onclick="delete_estimateDetail(' + value['estimateDetailID'] + ')" title="Delete" rel="tooltip"><span style="color:red;" class="glyphicon glyphicon-trash"></span></a>&nbsp; | &nbsp;<a onclick="createBOM(\'system/mfq/mfq_add_new_bill_of_material\',' + value['bomMasterID'] + ',\'' + bomMasterID + '\',\'EST\',' + value["mfqItemID"] + ',' + value["estimateDetailID"] + ')" title="BOM" rel="tooltip"><i class="fa fa-file-text" aria-hidden="true"></i></a></td></tr>');
                                    x++;
                                });
                                calculateItemTotal();
                            } else {
                                $("#est-tot_sellingPrice").text('0.00');
                            }
                        }
                        $("[rel=tooltip]").tooltip();
                        stopLoad();
                    }, error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        stopLoad();
                    }
                });
            }
        }

        function cal_item_line_total(element, estimatedDetailID) {
            var tot_totalCost_value = getNumberAndValidate($(element).closest('tr').find('td:eq(6)').text());
            var margin = getNumberAndValidate($(element).closest('tr').find('.marginPer').val());
            $(element).closest('tr').find('.sellingPrice').val((((tot_totalCost_value * margin) / 100) + tot_totalCost_value).toFixed(2));

            var tot_totalCost_value2 = $(element).closest('tr').find('.sellingPrice').val();
            var discount = getNumberAndValidate($(element).closest('tr').find('.discountPer').val());
            $(element).closest('tr').find('.totDiscountPrice').text(commaSeparateNumber((tot_totalCost_value2 - ((tot_totalCost_value2 * discount) / 100)).toFixed(2)));
            $(element).closest('tr').find('.discountedPrice').val((tot_totalCost_value2 - ((tot_totalCost_value2 * discount) / 100)).toFixed(2));

            calculateItemTotal();
        }

        function cal_item_line_total_selling_price(element, estimatedDetailID) {
            var tot_totalCost_value = getNumberAndValidate($(element).closest('tr').find('td:eq(6)').text());
            var margin = (($(element).val() - tot_totalCost_value)/tot_totalCost_value) * 100;
            $(element).closest('tr').find('.marginPer').val(margin.toFixed(5));

            var tot_totalCost_value2 = $(element).val();
            var discount = getNumberAndValidate($(element).closest('tr').find('.discountPer').val());
            $(element).closest('tr').find('.totDiscountPrice').text(commaSeparateNumber((tot_totalCost_value2 - ((tot_totalCost_value2 * discount) / 100)).toFixed(2)));
            $(element).closest('tr').find('.discountedPrice').val((tot_totalCost_value2 - ((tot_totalCost_value2 * discount) / 100)).toFixed(2));

            calculateItemTotal();
        }


        function save_estimate_detail_margin(element, estimateDetailID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    estimateDetailID: estimateDetailID,
                    sellingPrice: $(element).closest('tr').find('.sellingPrice').val(),
                    discountedPrice: $(element).closest('tr').find('.discountedPrice').val(),
                    margin: $(element).val()
                },
                url: "<?php echo site_url('MFQ_Estimate/save_estimate_detail_margin'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    save_estimate_detail_margin_total();
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }

        function save_estimate_detail_discount(element, estimateDetailID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    estimateDetailID: estimateDetailID,
                    discountedPrice: $(element).closest('tr').find('.discountedPrice').val(),
                    discount: $(element).val()
                },
                url: "<?php echo site_url('MFQ_Estimate/save_estimate_detail_discount'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    save_estimate_detail_margin_total();
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }

        function save_estimate_detail_selling_price(element, estimateDetailID) {
            var tot_totalCost_value = getNumberAndValidate($(element).closest('tr').find('td:eq(6)').text());
            var margin = (($(element).val() - tot_totalCost_value)/tot_totalCost_value) * 100;
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    estimateDetailID: estimateDetailID,
                    sellingPrice: $(element).val(),
                    margin:margin
                },
                url: "<?php echo site_url('MFQ_Estimate/save_estimate_detail_selling_price'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    save_estimate_detail_margin_total();
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }


        function save_estimate_detail_margin_total() {
            var totalSellingPrice = $('#est-tot_masterSellingPrice').text();
            var totalDiscountPrice = $('#est-tot_masterDiscountPrice').text();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    totalMargin: $("#est-marginPerTot").val(),
                    estimateMasterID: estimateMasterID,
                    totalSellingPrice: getNumberAndValidate(totalSellingPrice).toFixed(2),
                    totDiscountPrice: getNumberAndValidate(totalDiscountPrice).toFixed(2)
                },
                url: "<?php echo site_url('MFQ_Estimate/save_estimate_detail_margin_total'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }


        function save_estimate_detail_discount_total() {
            var totalDiscountPrice = $('#est-tot_masterDiscountPrice').text();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    totDiscount: $("#est-discountPerTot").val(),
                    estimateMasterID: estimateMasterID,
                    totDiscountPrice: getNumberAndValidate(totalDiscountPrice).toFixed(2)
                },
                url: "<?php echo site_url('MFQ_Estimate/save_estimate_detail_discount_total'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }


        function calculateItemTotal() {
            var tot_qty = 0;
            var tot_unitCost = 0;
            var tot_totalCost = 0;
            var tot_sellingPrice = 0;
            var tot_discountPrice = 0;
            $('#est-table_body tr').each(function () {
                var tot_qty_value = getNumberAndValidate($('td', this).eq(4).text());
                tot_qty += tot_qty_value;

                var tot_unitCost_value = getNumberAndValidate($('td', this).eq(5).text());
                tot_unitCost += tot_unitCost_value;

                var tot_totalCost_value = getNumberAndValidate($('td', this).eq(6).text());
                tot_totalCost += tot_totalCost_value;

                var tot_sellingPrice_value = getNumberAndValidate($('td', this).eq(8).find('.sellingPrice').val());
                tot_sellingPrice += tot_sellingPrice_value;

                var tot_discountPrice_value = parseFloat($('td', this).eq(10).find('.discountedPrice').val());
                tot_discountPrice += tot_discountPrice_value;
            });

            $("#est-tot_qty").text(tot_qty);
            $("#est-tot_unitCost").text(commaSeparateNumber(tot_unitCost, 2));
            $("#est-tot_totCost").text(commaSeparateNumber(tot_totalCost, 2));
            $("#est-tot_sellingPrice").text(commaSeparateNumber(tot_discountPrice, 2));
            $("#est-tot_masterSellingPrice").text(commaSeparateNumber((((tot_discountPrice * $("#est-marginPerTot").val()) / 100) + tot_discountPrice), 2));

            var marginDiscountPrice = getNumberAndValidate($('#est-tot_masterSellingPrice').text());
            $("#est-tot_masterDiscountPrice").text(commaSeparateNumber((marginDiscountPrice - ((marginDiscountPrice * $("#est-discountPerTot").val()) / 100)), 2));
        }

        function getNumberAndValidate(thisVal, dPlace=2) {
            thisVal = $.trim(thisVal);
            thisVal = removeCommaSeparateNumber(thisVal);
            thisVal = thisVal.toFixed(dPlace);
            if ($.isNumeric(thisVal)) {
                return parseFloat(thisVal);
            }
            else {
                return parseFloat(0);
            }
        }

        function validateFloatKeyPress(el, evt,currency_decimal=3) {
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

        function getSelectionStart(o) {
            if (o.createTextRange) {
                var r = document.selection.createRange().duplicate()
                r.moveEnd('character', o.value.length)
                if (r.text == '') return o.value.length
                return o.value.lastIndexOf(r.text)
            } else return o.selectionStart
        }

        function initializeCustomerInquiryDetailTypeahead(id) {
            $('#ci_search_' + id).autocomplete({
                serviceUrl: '<?php echo site_url();?>/MFQ_CustomerInquiry/fetch_finish_goods/',
                onSelect: function (suggestion) {
                    setTimeout(function () {
                        $('#ci_search_' + id).closest('tr').find('.mfqItemID').val(suggestion.mfqItemID);
                        $('#ci_search_' + id).closest('tr').find('.uom').val(suggestion.uom);
                        $('#ci_search_' + id).closest('tr').find('.estimatedCost').val(suggestion.cost);
                        $('#ci_search_' + id).closest('tr').find('.bomMasterID').val(suggestion.bomMasterID);
                    }, 200);
                },
            });
            $(".tt-dropdown-menu").css("top", "");
        }

        function initializeCustomerInquiryDetailTypeahead2(id) {
            $('#ci_search_' + id).autocomplete({
                serviceUrl: '<?php echo site_url();?>/MFQ_CustomerInquiry/fetch_finish_goods/',
                onSelect: function (suggestion) {
                    setTimeout(function () {
                        $('#ci_search_' + id).closest('tr').find('.mfqItemID').val(suggestion.mfqItemID);
                        $('#ci_search_' + id).closest('tr').find('.estimatedCost').val(suggestion.cost);
                        $('#ci_search_' + id).closest('tr').find('.bomMasterID').val(suggestion.bomMasterID);
                    }, 200);
                },
            });
            $(".tt-dropdown-menu").css("top", "");
        }

        function init_customerInquiryDetailForm(ciMasterID) {
            $('#table_body_ci_direct_detail').html('');
            $('#table_body_ci_direct_detail').append('<tr> <td> <input type="text" onkeyup="clearitemAutoID(event,this)" class="form-control ci_search" name="search[]" placeholder="Item ID, Item Description..." id="ci_search_1"> <input type="hidden" class="form-control mfqItemID" name="mfqItemID[]"> <input type="hidden" class="form-control ciMasterID" name="ciMasterID[]" value="' + ciMasterID + '"> <input type="hidden" name="bomMasterID[]" value="" class="bomMasterID"> <input type="hidden" name="ciDetailID[]" value="" class="ciDetailID"> </td> <td><input type="text" name="uom[]" id="uom" class="form-control uom" readonly> </td> <td><input type="text" name="expectedQty[]" id="expectedQty" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number expectedQty" onfocus="this.select();"> </td> <td><input type="text" name="estimatedCost[]" id="estimatedCost" onkeypress="return validateFloatKeyPress(this,event)" class="form-control number estimatedCost"> </td>   <td class="remove-td" style="vertical-align: middle;text-align: center"></td> </tr>');
            number_validation();
            setTimeout(function () {
                initializeCustomerInquiryDetailTypeahead(1);
            }, 500);
        }

        function add_more_finish_goods() {
            search_id += 1;
            //$('select.select2').select2('destroy');
            var appendData = $('#tbl_customerInquiryDirect tbody tr:first').clone();
            appendData.find('.ci_search').attr('id', 'ci_search_' + search_id);
            appendData.find('.ci_search').attr('onkeyup', 'clearitemAutoID(event,this)');
            appendData.find('input').val('');
            appendData.find('textarea').val('');
            appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
            $('#table_body_ci_direct_detail').append(appendData);
            var lenght = $('#tbl_customerInquiryDirect tbody tr').length - 1;

            number_validation();
            initializeCustomerInquiryDetailTypeahead(search_id);
        }

        function clearitemAutoID(e, ths) {
            var keyCode = e.keyCode || e.which;
            if (keyCode == 9) {
                //e.preventDefault();
            } else {
                $(ths).closest('tr').find('.itemAutoID').val('');
            }
        }

        function createBOM(page_url, page_id, page_name, policy_id, data_arr, master_page_url=null) {
            var postData = {mfqItemID: data_arr, estimateDetailID: master_page_url};
            $.ajax({
                async: true,
                type: 'POST',
                url: '<?php echo site_url("dashboard/fetchPage"); ?>',
                dataType: 'html',
                data: {
                    'page_id': page_id,
                    'page_url': page_url,
                    'page_name': page_name,
                    'policy_id': policy_id,
                    'data_arr': postData,
                    'master_page_url': master_page_url
                },
                beforeSend: function () {
                    startLoad();
                },
                success: function (page_html) {
                    stopLoad();
                    $('#bom_detail_modal').modal();
                    $('#bomHeader').html(page_name);
                    $('#bomContent').html(page_html);
                    load_estimate_detail(estimateMasterID)
                    $("html, body").animate({scrollTop: "0px"}, 10);
                },
                error: function (jqXHR, status, errorThrown) {
                    stopLoad();
                    $("html, body").animate({scrollTop: "0px"}, 10);
                    $('#bomContent').html(jqXHR.responseText + '<br/>Error Message: ' + errorThrown);
                }
            });

        }

    </script>
