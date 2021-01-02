<?php
$this->load->helper('report');
echo head_page('Sales Person Performance', false);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$cdate=current_date(FALSE);
$startdate =date('Y-01-01', strtotime($cdate));
$start_date = convert_date_format($startdate);
$salesperso_arr = all_sales_person_drop(false);
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
        <?php echo form_open('login/loginSubmit', ' name="frm_sales_person_rpt" id="frm_sales_person_rpt" class="form-group" role="form"'); ?>
            <div class="col-md-12">
                <div class="form-group col-sm-2">
                    <label for="">Currency</label>
                    <select name="currency" class="form-control " id="currency" onchange="get_sales_person_performance_report()" tabindex="-1" aria-hidden="true" data-bv-field="currency">
                      <!--  <option value="1">Transaction Currency</option>-->
                        <option value="2">Local Currency</option>
                        <option value="3" selected>Reporting Currency</option>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Date From</label>
                    <div class="input-group datepic">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="datefrom"
                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                               value="<?php echo $start_date; ?>" id="datefrom" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Date To</label>
                    <div class="input-group datepicto">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="dateto"
                               data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                               value="<?php echo $current_date; ?>" id="dateto" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="">Sales Person</label>
                        <?php echo form_dropdown('salesperson[]', $salesperso_arr, '', 'multiple  class="form-control select2" id="salesperson" required'); ?>
                </div>
                <div class="form-group col-sm-1">
                    <label for=""></label>
                    <button style="margin-top: 27px" type="button" onclick="get_sales_person_performance_report()"
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
<div class="modal fade" id="sales_person_com_dd" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 95%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Sales Person Performance Drill Down</h4>
            </div>
            <div class="modal-body" id="salesperson_detail">

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

    $(document).ready(function (e) {
        $('#salesperson').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            selectAllValue: 'select-all-value',
            //enableFiltering: true
            buttonWidth: 150,
            maxHeight: 200,
            numberDisplayed: 1
        });
        $("#salesperson").multiselect2('selectAll', false);
        $("#salesperson").multiselect2('updateButtonText');

        $('.headerclose').click(function () {
            fetchPage('system/sales/erp_sales_person_report', '', 'Sales Person')
        });

        $('.modal').on('hidden.bs.modal', function (e) {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });

        var typeArr = $('#parentCompanyID option:selected').val();
        typeArr  = typeArr.split('-');
        type = typeArr[1];


        get_sales_person_performance_report();

    });

    function get_sales_person_performance_report() {
        var data = $("#frm_sales_person_rpt").serialize();
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('Sales/get_sales_person_performance_report'); ?>',
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
        var form = document.getElementById('frm_sales_person_rpt');
        form.target = '_blank';
        form.action = '<?php echo site_url('Sales/get_sales_person_performance_report_pdf'); ?>';
        form.submit();
    }

    function opensalespersondd(salesPersonID){
        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();
        var currency = $('#currency').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Sales/get_sales_preformance_dd'); ?>",
            data: {'salesPersonID': salesPersonID,'datefrom':datefrom,'dateto':dateto,'currency':currency},
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#salesperson_detail").html(data);
                $('#sales_person_com_dd').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }
</script>
