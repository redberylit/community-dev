<?php echo head_page($_POST["page_name"], false);
$this->load->helper('buyback_helper');

$yearfilter = load_yearfilter_dashboard();

?>
            <?php echo form_open('login/loginSubmit', ' name="Production_Report" id="Production_Report" class="form-horizontal" role="form"'); ?>
            <?php echo form_close(); ?>

    <div id="div_production_report">
    </div>


<?php echo footer_page('Right foot', 'Left foot', false); ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.headerclose').click(function () {
                fetchPage('system/buyback/report/production_report_output','', '<?php echo $_POST["page_name"] ?>')
            });
            $('.select2').select2();
            get_Production_Report();
        });

        function get_Production_Report() {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('buyback/get_Production_Report') ?>",
              //  data: $("#frm_rpt_leave_history").serialize(),
                dataType: "html",
                cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $("#div_production_report").html(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<strong> Error </strong><br>Status: ' + textStatus + '<br>Message: ' + errorThrown);
                }
            });
        }

        function generateReportPdf() {
            var form = document.getElementById('Production_Report');
            form.target = '_blank';
            form.action = '<?php echo site_url('buyback/get_Production_Report_pdf'); ?>';
            form.submit();
        }

    </script>

<?php
/**
 * Created by PhpStorm.
 * User: Safeena
 * Date: 10/16/2018
 * Time: 11:35 AM
 */