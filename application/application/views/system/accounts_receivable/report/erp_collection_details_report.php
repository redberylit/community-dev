<?php
$date_format_policy = date_format_policy();
$financeyear_arr = all_financeyear_drop(true);
$segment_arr = fetch_segment(true,false);
echo head_page('Collection Details', false);
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
        <legend class="scheduler-border">Filter</legend>
        <?php echo form_open('login/loginSubmit', ' name="frm_rpt_customer_collection_details" id="frm_rpt_customer_collection_details" class="form-group" role="form"'); ?>
            <div class="col-md-12">
                <div class="form-group col-sm-2">
                    <label for="">Currency</label>
                    <select name="currency" class="form-control " id="currency" onchange="get_collection_detail_report()" tabindex="-1" aria-hidden="true" data-bv-field="currency">
                       <option value="1">Transaction Currency</option>
                        <option value="2">Local Currency</option>
                        <option value="3">Reporting Currency</option>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Date From</label>
                    <div class="input-group datepic">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="datefrom"
                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                               value="" id="datefrom" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Date To</label>
                    <div class="input-group datepicto">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="dateto"
                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                               value="" id="dateto" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Customer</label>
                    <?php echo form_dropdown('customerID[]', all_customer_drop(false), '', 'multiple  class="form-control select2" id="customerID" required'); ?>
                </div>

                <div class="form-group col-sm-2">
                    <label for="segment">Segment</label>
                    <?php echo form_dropdown('segment[]', $segment_arr, $this->common_data['company_data']['default_segment'], 'multiple class="form-control select2" id="segment" required'); ?>
                </div>

                <div class="form-group col-sm-1">
                    <label for=""></label>
                    <button style="margin-top: 5px" type="button" onclick="get_collection_detail_report()"
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


<script type="text/javascript">
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

    $('#segment').multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        selectAllValue: 'select-all-value',
        //enableFiltering: true
        buttonWidth: 150,
        maxHeight: 200,
        numberDisplayed: 1
    });
    $("#segment").multiselect2('selectAll', false);
    $("#segment").multiselect2('updateButtonText');
    $('.headerclose').click(function () {
        fetchPage('system/accounts_receivable/report/erp_collection_details_report', '', 'Collection Details')
    });
    $(document).ready(function (e) {
        get_collection_detail_report();

        $('.modal').on('hidden.bs.modal', function (e) {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });
    });

    function get_collection_detail_report() {
        var data = $("#frm_rpt_customer_collection_details").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Report/get_collection_detail_report') ?>",
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
    $(document).ready(function (e) {
        Inputmask().mask(document.querySelectorAll("input"));
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {

        });
        $('.datepicto').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {

        });
        get_collection_detail_report();
    });
    function generateReportPdf() {
        var form = document.getElementById('frm_rpt_customer_collection_details');
        form.target = '_blank';
        form.action = '<?php echo site_url('Report/get_collection_detail_report_pdf'); ?>';
        form.submit();
    }
</script>
