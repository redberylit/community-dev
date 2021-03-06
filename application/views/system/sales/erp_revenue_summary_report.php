<?php
$primaryLanguage = getPrimaryLanguage();
$this->load->helper('report');
$this->lang->load('sales_marketing_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('sales_markating_sales_order_report');
$date_format_policy = date_format_policy();
$segment_arr = fetch_segment(true,false);
$financeyear_arr = "";
echo head_page('Revenue Summary', false);
$customer="";
$companyFinanceYearID = "";
if($this->session->userdata("companyType") == 1){
    $customer = all_customer_drop(false);
    $financeyear_arr = all_financeyear_drop(true);
    $companyFinanceYearID = $this->common_data['company_data']['companyFinanceYearID'];
}else{
    $customer = all_group_customer_drop(false);
    $financeyear_arr = all_group_financeyear_report_drop(true);
    $segment_arr = fetch_group_segment(true,false);
}
?>
<style>
    .bgc {
        background-color: #e1f1e1;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/tabs.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<div id="filter-panel" class="collapse filter-panel">
</div>
<div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo $this->lang->line('common_filters'); ?><!--Filter--></legend>
        <?php echo form_open('login/loginSubmit', ' name="frm_rpt_customer_invoice" id="frm_rpt_customer_invoice" class="form-group" role="form"'); ?>
            <div class="col-md-12">
                <div class="form-group col-sm-2">
                    <label for="">Currency</label>
                    <select name="currency" class="form-control " id="currency" onchange="get_get_revenue_summery_report()" tabindex="-1" aria-hidden="true" data-bv-field="currency">
                        <!--<option value="1">Transaction Currency</option>-->
                        <option value="2">Local Currency</option>
                        <option value="3" selected>Reporting Currency</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="financeyear">Financial Year </label>
                    <?php echo form_dropdown('financeyear', $financeyear_arr, $companyFinanceYearID, 'class="form-control" id="financeyear" required'); ?>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Customer</label>
                        <?php echo form_dropdown('customerID[]', $customer, '', 'multiple  class="form-control select2" id="customerID" required'); ?>
                </div>
                <div class="form-group col-sm-2">
                    <label for="segment">
                        <?php echo $this->lang->line('common_segment'); ?><!--Segment -->
                    </label>
                    <?php echo form_dropdown('segmentID[]', $segment_arr, '', 'multiple class="form-control select2" id="segmentID" required'); ?>
                </div>

                <div class="form-group col-sm-1">
                    <label for=""></label>
                    <button style="margin-top: 5px" type="button" onclick="get_get_revenue_summery_report()"
                            class="btn btn-primary btn-xs">
                        Generate</button>
                </div>


            </div>
        <?php echo form_close(); ?>
    </fieldset>
</div>
<hr style="margin: 0px;">
<div id="div_customer_invoice">
</div>

<?php echo footer_page('Right foot', 'Left foot', false); ?>

<div class="modal fade" id="returndrilldownModal" tabindex="2" role="dialog" aria-labelledby="myModalLabel" style="z-index: 10000;">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title drilldown-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <table id="tbl_rpt_salesreturn" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>
                        <th>Document Code</th>
                        <th>Document Date</th>
                        <th>Currency</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody id="salesreturn">

                    </tbody>
                    <tfoot id="salesreturnfooter" class="table-borded">

                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    <?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="sumarydrilldownModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 95%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"> Revenue Summary Drill Down</h4>
            </div>
            <div class="modal-body" id="sumarydd">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    <?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var type;
    var url;
    var urlPdf;
    var urlDrill1;
    var urlDrill2;
    var urlDrill3;
    $(document).ready(function (e) {
        $('#customerID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            selectAllValue: 'select-all-value',
            //enableFiltering: true
            buttonWidth: 150,
            maxHeight: 200,
            numberDisplayed: 1
        });
        $("#customerID").multiselect2('selectAll', false);
        $("#customerID").multiselect2('updateButtonText');

        $('#segmentID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            selectAllValue: 'select-all-value',
            //enableFiltering: true
            buttonWidth: 150,
            maxHeight: 200,
            numberDisplayed: 1
        });
        $("#segmentID").multiselect2('selectAll', false);
        $("#segmentID").multiselect2('updateButtonText');
        $('.headerclose').click(function () {
            fetchPage('system/sales/erp_revenue_summary_report', '', 'Sales Order')
        });

        $('.modal').on('hidden.bs.modal', function (e) {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });

        var typeArr = $('#parentCompanyID option:selected').val();
        typeArr  = typeArr.split('-');
        type = typeArr[1];

        if(type == 1){
            url = '<?php echo site_url('sales/get_get_revenue_summery_report'); ?>';
            urlPdf = '<?php echo site_url('sales/get_revenue_summery_report_pdf'); ?>';
            urlDrill1 = '<?php echo site_url('sales/get_revanue_details_drilldown_report'); ?>';
            urlDrill2 = '<?php echo site_url('sales/get_sales_order_return_drilldown_report'); ?>';
            urlDrill3 = '<?php echo site_url('sales/get_sales_order_credit_drilldown_report'); ?>';
        }else{
            url = '<?php echo site_url('sales/get_group_revenue_summery_report'); ?>';
            urlPdf = '<?php echo site_url('sales/get_group_revenue_summery_report_pdf'); ?>';
            urlDrill1 = '<?php echo site_url('sales/get_group_revanue_details_drilldown_report'); ?>';
            urlDrill2 = '<?php echo site_url('sales/get_group_sales_order_return_drilldown_report'); ?>';
            urlDrill3 = '<?php echo site_url('sales/get_group_sales_order_credit_drilldown_report'); ?>';
        }

        get_get_revenue_summery_report();

    });

    function get_get_revenue_summery_report() {
        var data = $("#frm_rpt_customer_invoice").serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#div_customer_invoice").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }

    function generateReportPdf() {
        var form = document.getElementById('frm_rpt_customer_invoice');
        form.target = '_blank';
        form.action = urlPdf;
        form.submit();
    }


    function openresummaryDD(date,currency,customerid){
        var segmentID =$('#segmentID').val();
        $.ajax({
            type: "POST",
            url: urlDrill1,
            data: {'date': date,'currency': currency,'customerID': customerid,'segmentID':segmentID},
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#sumarydd").html(data);
                $('#sumarydrilldownModal').modal('show');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }


    function openreturnDD(invoiceAutoID){
        $.ajax({
            type: "POST",
            url: urlDrill2,
            data: {'invoiceAutoID': invoiceAutoID},
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#salesreturn').empty();
                $('#salesreturnfooter').empty();
                if (jQuery.isEmptyObject(data)) {
                    $('#salesreturn').append('<tr class="danger"><td colspan="4" class="text-center"><b><?php echo $this->lang->line('common_no_records_found'); ?><!--No Records Found--></b></td></tr>');
                } else {
                    tot_amount = 0;
                    var currency;
                    var amount;
                    var decimalPlaces=2;
                    var total=0;
                    $.each(data, function (key, value) {
                        if($('#currency').val()==1){
                            currency=value['transactionCurrency'];
                            amount=value['totalValue']/value['transactionExchangeRate'];
                            decimalPlaces=value['transactionCurrencyDecimalPlaces'];
                        }else if($('#currency').val()==2){
                            currency=value['companyLocalCurrency'];
                            amount=value['totalValue']/value['companyLocalExchangeRate'];
                            decimalPlaces=value['companyLocalCurrencyDecimalPlaces'];
                        }else{
                            currency=value['companyReportingCurrency'];
                            amount=value['totalValue']/value['companyReportingExchangeRate'];
                            decimalPlaces=value['companyReportingCurrencyDecimalPlaces'];
                        }
                        total += amount;
                        $('#salesreturn').append('<tr><td><a href="#" class="" onclick="documentPageView_modal(\'SLR\' , ' + value["salesReturnAutoID"] + ')">' + value["salesReturnCode"] + '</a></td><td>' + value["returnDate"] + '</td><td >' + currency + '</td><td class="text-right">' + parseFloat(amount).formatMoney(+decimalPlaces + ',', '.') + '</td></tr>');
                    });
                    $('#salesreturnfooter').append('<tr><td colspan="3" >&nbsp;</td> <td class="text-right reporttotal" style="font-weight: bold;">' + parseFloat(total).formatMoney(+decimalPlaces + ',', '.') + '</td></tr>');
                }
                $('#returndrilldownModal').modal('show');
                $('.drilldown-title').html("Sales Return Drill Down");

            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }

    function openrecreditDD(invoiceAutoID){
        $.ajax({
            type: "POST",
            url: urlDrill3,
            data: {'invoiceAutoID': invoiceAutoID},
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#salesreturn').empty();
                $('#salesreturnfooter').empty();
                if (jQuery.isEmptyObject(data)) {
                    $('#salesreturn').append('<tr class="danger"><td colspan="4" class="text-center"><b><?php echo $this->lang->line('common_no_records_found'); ?><!--No Records Found--></b></td></tr>');
                } else {
                    tot_amount = 0;
                    var currency;
                    var amount;
                    var decimalPlaces=2;
                    var total=0;
                    $.each(data, function (key, value) {
                        if($('#currency').val()==1){
                            currency=value['transactionCurrency'];
                            amount=value['transactionAmount'];
                            decimalPlaces=value['transactionCurrencyDecimalPlaces'];
                        }else if($('#currency').val()==2){
                            currency=value['companyLocalCurrency'];
                            amount=value['companyLocalAmount'];
                            decimalPlaces=value['companyLocalCurrencyDecimalPlaces'];
                        }else{
                            currency=value['companyReportingCurrency'];
                            amount=value['companyReportingAmount'];
                            decimalPlaces=value['companyReportingCurrencyDecimalPlaces'];
                        }
                        //alert(amount);
                        total += parseFloat(amount);
                        $('#salesreturn').append('<tr><td><a href="#" class="" onclick="documentPageView_modal(\'' + value["docID"] + '\' , ' + value["masterID"] + ')">' + value["documentCode"] + '</a></td><td>' + value["documentDate"] + '</td><td >' + currency + '</td><td class="text-right">' + parseFloat(amount).formatMoney(+decimalPlaces + ',', '.') + '</td></tr>');
                    });
                    $('#salesreturnfooter').append('<tr><td colspan="3" >&nbsp;</td> <td class="text-right reporttotal" style="font-weight: bold;">' + parseFloat(total).formatMoney(+decimalPlaces + ',', '.') + '</td></tr>');
                }
                $('#returndrilldownModal').modal('show');
                $('.drilldown-title').html("Receipt/Credit Note Drill Down");

            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }

</script>
