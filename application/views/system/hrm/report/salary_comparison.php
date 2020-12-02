<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('hrms_reports_salary_comparison');
echo head_page($title, false);


$formula_arr = get_salaryComparison();

if (!empty($formula_arr)) {
    ?>
    <style>
        tr.highlight td {
            background-color: #B0BED9 !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/tabs.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">


    <?php echo form_open('', ' role="form" id="reportCreate_form" class="form-horizontal" '); ?>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo $this->lang->line('common_filters'); ?><!--Filter--></legend>
        <div class="col-md-12">
            <label for="currentMonth" class="col-md-2 control-label">
                <?php echo $this->lang->line('hrms_reports_first_month2'); ?><!--First Month--></label>
            <div class="col-md-2">
                <div class="form-group">
                    <?php echo form_dropdown('firstMonth', payrollMonth_dropDown(), '', 'class="form-control select2" id="currentMonth" required'); ?>
                </div>
            </div>
            <label for="lastMonth" class="col-md-2 control-label">
                <?php echo $this->lang->line('hrms_reports_scond_month2'); ?><!--Second Month--></label>
            <div class="col-md-2">
                <div class="form-group">
                    <?php echo form_dropdown('secondMonth', payrollMonth_dropDown(), '', 'class="form-control select2" id="lastMonth" required'); ?>
                </div>
            </div>
            <label for="inputData" class="col-md-1 control-label"></label>
            <div class="col-md-3" id="form-btn-group">
                <button type="button" class="btn btn-primary btn-xs generateBtn"
                        onclick="isGenerateORPrint('Generate')">
                    <?php echo $this->lang->line('common_generate'); ?><!--Generate--></button>
                <?php echo export_buttons('salaryComparisonTB', 'Employee Pay Scale Report', True, True); ?>
                <!--<button type="button" class="btn btn-primary btn-xs generateBtn" onclick="isGenerateORPrint('Print')">Print</button>-->
                <?php /*echo export_buttons('salaryComparisonReport', 'ETF Return', true, false, 'btn-xs '); */ ?>
                <input type="hidden" id="eventType" value="">
            </div>
        </div>
    </fieldset>
    <?php echo form_close(); ?>

    <div id="salaryComparisonReport" class="cols-sm-12" style="display: none; margin-top: 2%"></div>


    <?php
} else {
    ?>
    <div class="alert alert-warning">
        <strong><?php echo $this->lang->line('hrms_reports_warning'); ?><!--Warning-->!</strong>
        </br>
        <?php echo $this->lang->line('hrms_reports_payroll_employee_report_report_configuration_is_not_done'); ?><!--Report configuration is not done.-->
    </div>

    <?php
}
?>


<?php echo footer_page('Right foot', 'Left foot', false); ?>

    <script>

        var reportCreateForm = $('#reportCreate_form');

        $('.filterDate').datepicker({
            format: 'yyyy-mm',
            viewMode: "months",
            minViewMode: "months"
        }).on('changeDate', function (ev) {
            reportCreateForm.bootstrapValidator('revalidateField', $(this).attr('id'));
            $(this).datepicker('hide');
        });


        $(document).ready(function (e) {
            /*align excel button*/
            var btnExcel = $('#btn-excel');
            var divContent = btnExcel.closest('div').html();
            btnExcel.closest('div').remove();
            $('#form-btn-group').append(divContent);

            Inputmask().mask(document.querySelectorAll("input"));

            $('.headerclose').click(function () {
                fetchPage('system/hrm/report/salary_comparison', '', 'Salary Comparison');
            });

            reportCreateForm.bootstrapValidator({
                live: 'enabled',
                message: 'This value is not valid.',
                excluded: [':disabled'],
                fields: {
                    firstMonth: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_first_month_is_required');?>.'}}}, /*First month is required*/
                    secondMonth: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_second_month_is_required');?>.'}}}/*Second month is required*/
                },
            }).on('success.form.bv', function (e) {
                $('.generateBtn').prop('disabled', false);
                e.preventDefault();

                var eventType = $('#eventType').val();

                if (eventType == 'Print') {
                    PrintData();
                }
                else if (eventType == 'Excel') {
                    loadToExcel();
                }
                else {
                    generateData();
                }

            });

        });

        function isGenerateORPrint(eventType) {
            $('#eventType').val(eventType);
            reportCreateForm.submit();
        }

        function generateReportPdf() {
            $('#eventType').val('Print');
            reportCreateForm.submit();
        }

        function generateData() {
            var postData = reportCreateForm.serializeArray();
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo site_url('Report/salaryComparison_reportGenerate/view/') ?>',
                data: postData,
                cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#salaryComparisonReport').html(data).css('display', 'block');

                    $('#salaryComparisonTB').tableHeadFixer({
                        head: true,
                        foot: true,
                        left: 0,
                        right: 0,
                        'z-index': 0
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function PrintData() {
            var form = document.getElementById('reportCreate_form');
            form.target = '_blank';
            form.method = 'post';
            form.action = '<?php echo site_url('Report/salaryComparison_reportGenerate/print/Salary-Comparison'); ?>';
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