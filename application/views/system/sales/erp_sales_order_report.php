<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('sales_marketing_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('sales_markating_sales_order_report');
echo head_page($title, false);

$customer="";
if($this->session->userdata("companyType") == 1){
    $customer = all_customer_drop(false);
}else{
    $customer = all_group_customer_drop(false);
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
        <?php echo form_open('login/loginSubmit', ' name="frm_rpt_sales_order" id="frm_rpt_sales_order" class="form-horizontal" role="form"'); ?>
            <div class="col-md-12">
                <label for="inputData" class="col-md-1 control-label">
                    <?php echo $this->lang->line('common_customer'); ?><!--Customer-->:</label>
                <div class="col-md-2">
                    <?php echo form_dropdown('customerID[]', $customer, '', 'multiple  class="form-control select2" id="customerID" required'); ?>
                </div>
                <label for="inputData" class="col-md-1 control-label">
                    <?php echo $this->lang->line('common_search'); ?><!--Search-->:</label>
                <div class="col-md-2">
                    <input type="text" id="search" name="search" class="form-control">
                </div>
                <button style="margin-top: 5px" type="button" onclick="get_sales_order()"
                        class="btn btn-primary btn-xs">
                    <?php echo $this->lang->line('common_search'); ?><!--Search--></button>

            </div>
        <?php echo form_close(); ?>
    </fieldset>
</div>
<hr style="margin: 0px;">
<div id="div_sales_order">
</div>
<div class="modal fade" id="drilldownModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title drilldown-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div id="sales_order_drilldown"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    <?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
            </div>
        </div>
    </div>
</div>

<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script>
    var type;
    var url;
    var urlPdf;
    var urlDrill;
    $(document).ready(function (e) {
        $('#customerID').multiselect2({
            includeSelectAllOption: true,
            selectAllValue: 'select-all-value',
            //enableFiltering: true
            buttonWidth: 150,
            maxHeight: 200,
            numberDisplayed: 1
        });
        $("#customerID").multiselect2('selectAll', false);
        $("#customerID").multiselect2('updateButtonText');
        $('.headerclose').click(function () {
            fetchPage('system/sales/erp_sales_order_report', '', 'Sales Order')
        });

        var typeArr = $('#parentCompanyID option:selected').val();
        typeArr  = typeArr.split('-');
        type = typeArr[1];

        if(type == 1){
            url = '<?php echo site_url('sales/get_sales_order_report'); ?>';
            urlPdf = '<?php echo site_url('sales/get_sales_order_report_pdf'); ?>';
            urlDrill = '<?php echo site_url('sales/get_sales_order_drilldown_report'); ?>';
        }else{
            url = '<?php echo site_url('sales/get_group_sales_order_report'); ?>';
            urlPdf = '<?php echo site_url('sales/get_group_sales_order_report_pdf'); ?>';
            urlDrill = '<?php echo site_url('sales/get_group_sales_order_drilldown_report'); ?>';
        }
        get_sales_order();
    });

    function get_sales_order() {
        $.ajax({
            type: "POST",
            url: url,
            data: $("#frm_rpt_sales_order").serialize(),
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#div_sales_order").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }

    function generateReportPdf() {
        var form = document.getElementById('frm_rpt_sales_order');
        form.target = '_blank';
        form.action = urlPdf;
        form.submit();
    }

    function drilldownSalesOrder(autoID,documentCode,type,title) {
        var form = $("#frm_rpt_sales_order").serializeArray();
        form.push({name:'autoID',value:autoID});
        form.push({name:'type',value:type});
        $.ajax({
            type: "POST",
            url: urlDrill,
            data: form,
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#drilldownModal').modal('show');
                $('.drilldown-title').html(title+" - "+documentCode);
                $("#sales_order_drilldown").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }

</script>
