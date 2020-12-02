<?php
/** Translation added by Shafri */
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('profile', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
//$title = $this->lang->line('profile_my_profile');
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/tabs.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<style>
    .paySheetDet_TD{
        text-align: right;
        padding-right: 4px;
    }

    .envoy-payslip-tbl{
        width: 600px !important;
    }

    .envoy-payslip-tbl th{
        padding-left: 4px;
        font-size: 12px !important;
    }
</style>
<div class="panel panel-default animated zoomIn">
    <div class="panel-heading"><?php echo $this->lang->line('profile_pay_slip'); ?><!--Pay Slip--></div>
    <div class="tab-content">
        <div class="panel-body">
            <form class="form-horizontal" method="post" id="passwordForm">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo $this->lang->line('profile_filter'); ?><!--Filter--></legend>
                    <div class="col-md-12">
                        <label for="confirmPassword" class="col-sm-2 control-label"><?php echo $this->lang->line('profile_payroll_type'); ?><!--Payroll Type--></label>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <select name="isNonPayroll" id="isNonPayroll" class="form-control" onchange="getPayrolls(this)">
                                    <option value="N"><?php echo $this->lang->line('profile_payroll'); ?><!--Payroll--></option>
                                    <option value="Y"><?php echo $this->lang->line('profile_non_payroll'); ?><!--Non payroll--></option>
                                </select>
                            </div>
                        </div>


                        <label for="payrollMonth" class="col-sm-2 control-label"><?php echo $this->lang->line('profile_Month'); ?><!--Month--></label>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?php echo form_dropdown('payrollMonth', payrollMonth_dropDown_with_visible_date(), '', 'class="form-control select2" id="payrollMonth" required'); ?>
                            </div>
                        </div>

                        <div class="col-md-3" id="form-btn-group">
                            <button type="button" class="btn btn-primary btn-xs generateBtn" onclick="load_paySlip()"><?php echo $this->lang->line('profile_load'); ?><!--Load--></button>
                        </div>
                    </div>
                </fieldset>
            </form>
            <br>
            <div class="row">
                <span class="no-print pull-right" style="padding-right: 10px;"> <a class="btn btn-default btn-sm no-print pull-right hidden review" id="a_link" target="_blank" href=""> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> </a> </span>
            </div>
            <br>
            <div class="row" id="div-paysheet-container" style="border:1px solid #f4f4f4;">

            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        $('.select2').select2();
    });

    function load_paySlip() {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('template_paySheet/get_paySlip_profile') ?>",
            data: {
                payrollMonth: $('#payrollMonth').val(),
                empID:<?php echo current_userID() ?>,
                isNonPayroll:$('#isNonPayroll').val(),
                html:1},
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
                //$("#div-paysheet-container").html("<div class='text-center'><i class='fa fa-refresh fa-spin fa-2'></i> Loading</div>");
            },
            success: function (data) {
                stopLoad();
                $("#div-paysheet-container").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //$("#div-paysheet-container").html('');
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }

    function getPayrolls(){
        $("#div-paysheet-container").html('');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('template_paySheet/payroll_dropDown_with_visible_date') ?>",
            data: { isNonPayroll:$('#isNonPayroll').val()},
            dataType: "json",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();

                var appData = '<option value="">Please Select</option>';
                $.each(data, function (val, text) {
                    if(val != '' ){
                        appData += '<option value="' + val + '" >' + text + '</option>';
                    }
                });

                $('#payrollMonth').empty().append(appData).trigger('change');

                $('#select2-payrollMonth-container').fadeOut();
                $('#select2-payrollMonth-container').fadeIn();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }
</script>

