<style>
    tfoot td{
        background-color: #dedede;
    }
</style>

<?php echo head_page('R Form',false);

$token_details = [
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
];

$isETF_Configured = isReportMasterConfigured('ETF');
$isETF_Head_Configured = isReportMasterConfigured('ETF-H');
$isETF_Employee_Configured = isReportEmployeeConfigured(2);

if( $isETF_Configured == 'Y' && $isETF_Head_Configured == 'Y' && $isETF_Employee_Configured == 'Y') {
    $segment_arr = fetch_segment(true,false);
?>

<form role="form" id="reportCreate_form" class="form-horizontal">
    <input type="hidden" name="<?=$token_details['name'];?>" value="<?=$token_details['hash'];?>" />
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="payrollMonth">Payroll Month</label>
                    <div class="col-sm-2">
                        <?php echo form_dropdown('payrollMonth', payrollMonth_dropDown(), '', 'class="form-control select2" id="payrollMonth" required'); ?>
                        <div class="clearfix visible-xs visible-sm">&nbsp;</div>
                    </div>
                    <label class="col-sm-2 control-label" for="segmentID">Segment</label>
                    <div class="col-sm-3">
                        <?php echo form_dropdown('segment[]', $segment_arr, '', 'class="form-control" id="segmentID" multiple="multiple"'); ?>
                        <div class="clearfix visible-xs visible-sm">&nbsp;</div>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-primary btn-sm generateBtn" onclick="isGenerateORPrint('Generate')">Generate</button>
                        <button type="button" class="btn btn-primary btn-sm generateBtn" onclick="isGenerateORPrint('Print')">Print</button>
                        <input type="hidden"  id="eventType" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<div id="epfReport" class="col-sm-12" style="display: none"></div>


<?php
}
else{
    ?>
    <div class="alert alert-warning">
        <strong>Warning!</strong>
        </br>ETF report configuration is not done.
        </br>Please complete the report configuration and try again.
    </div>

    <?php
}
?>


<?php echo footer_page('Right foot','Left foot',false); ?>

<script>
    var reportCreateForm = $('#reportCreate_form');

    $(document).ready(function (e) {
        $('.select2').select2();

        $('.headerclose').click(function () {
            fetchPage('system/hrm/report/r_form', '', 'C Form');
        });

        $('#segmentID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });

        reportCreateForm.bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                payrollMonth: {validators: {notEmpty: {message: 'Payroll month is required.'}}}
            },
        }).
        on('success.form.bv', function (e) {
            $('.generateBtn').prop('disabled', false);
            e.preventDefault();

            var payrollMonth = $('#payrollMonth').val();
            var eventType = $('#eventType').val();

            if(eventType == 'Print'){
                PrintData(payrollMonth);
            }
            else{
                generateData(payrollMonth);
            }

        });

    });

    function isGenerateORPrint(eventType){
        $('#eventType').val(eventType);
        reportCreateForm.submit();
    }

    function generateData(){
        var postData = reportCreateForm.serializeArray();
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo site_url('Report/rFrom_reportGenerate') ?>/view/',
            data : postData,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#epfReport').html(data).css('display' , 'block');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function PrintData(){
        var form = document.getElementById('reportCreate_form');
        form.target = '_blank';
        form.method = 'post';
        form.post = reportCreateForm.serializeArray();
        form.action = '<?php echo site_url('Report/rFrom_reportGenerate/print/R4-Form'); ?>';
        form.submit();
    }
</script>

<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 4/11/2017
 * Time: 11:18 AM
 */