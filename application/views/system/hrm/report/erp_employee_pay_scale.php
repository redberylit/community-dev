<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('hrms_reports_employee_pay_scale_report');
echo head_page($title, false);


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
        <legend class="scheduler-border"><?php echo $this->lang->line('common_filters');?><!--Filter--></legend>
    <?php echo form_open('login/loginSubmit', ' name="frm_rpt_payScale" id="frm_rpt_payScale" class="form-horizontal" role="form"'); ?>
        <div class="col-md-12">
   <!--     <div class="form-group">
            <label class="col-md-1 control-label" style="    width: 100px;">As Of </label>
            <div class="col-md-2 ">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    <input onchange="get_payScale()" type="text" name="asofDate" id="asofDate"
                           value="<?php /*echo date('Y-m-d') */?>"
                           class="form-control filterDate" readonly>
                </div>
            </div>
        </div>-->
            <label for="inputCodforn" class="col-md-1 control-label"><?php echo $this->lang->line('hrms_reports_as_of');?><!--As Of--></label>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    <input onchange="" type="text" name="asofDate" id="asofDate"
                           value="<?php echo date('Y-m-d') ?>"
                           class="form-control filterDate" readonly>
                </div>
            </div>
        <label for="inputData" class="col-md-1 control-label"><?php echo $this->lang->line('common_segment');?><!--Segment-->:</label>
        <div class="col-md-2">
            <?php echo form_dropdown('segmentID[]', fetch_segment(true, false), '', 'multiple  class="form-control select2" id="segmentID" required'); ?>
        </div>

            <button style="margin-top: 5px" type="button" onclick="get_payScale()" class="btn btn-primary btn-xs"><?php echo $this->lang->line('common_search');?><!--Search--></button>



        </div>
    <?php echo form_close(); ?>
    </fieldset>

</div>
<hr style="margin: 0px;">


<div id="div_payscale">


</div>


<?php echo footer_page('Right foot', 'Left foot', false); ?>

<script>
    $('#segmentID').multiselect2({
        includeSelectAllOption: true,
        selectAllValue: 'select-all-value',
        //enableFiltering: true
        buttonWidth:150,
        maxHeight: 200,
        numberDisplayed: 1
    });
    $("#segmentID").multiselect2('selectAll', false);
    $("#segmentID").multiselect2('updateButtonText');
    $('.headerclose').click(function () {

        fetchPage('system/hrm/report/erp_employee_pay_scale','','Employee Pay Scale')
    });
    $(document).ready(function (e) {
      get_payScale();
        $('.filterDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });

    function get_payScale() {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Template_paysheet/get_payScale_report') ?>",
            data: $("#frm_rpt_payScale").serialize(),
            dataType: "html",
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#div_payscale").html(data);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
            }
        });
    }


</script>
