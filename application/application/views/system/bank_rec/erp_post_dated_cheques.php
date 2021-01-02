<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('treasury', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('treasury_tr_lm_post_dated_cheques');
echo head_page($title, false);
/*echo head_page('Post Dated Cheques',false); */?>

<div id="filter-panel" class="collapse filter-panel">
</div>
<div class="m-b-md" id="wizardControl">
    <a id="tab1" class="btn btn-default" href="#step1" onclick="get_post_dated_cheques();" data-toggle="tab"><?php echo $this->lang->line('treasury_tr_lm_received_post_dated_cheques');?><!--Received Post Dated Cheques--></a>
    <a id="tab2" class="btn btn-default btn-wizard" href="#step2" onclick="get_post_dated_chequespayment();"  data-toggle="tab"><?php echo $this->lang->line('treasury_tr_lm_issued_post_dated_cheques');?><!--Issued Post Dated Cheques--></a>

</div><hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <div id="load_generated_tables"></div>
    </div>
    <div id="step2" class="tab-pane active">
        <div id="load_generated_tables2"></div>
    </div>

    </div>


<?php echo footer_page('Right foot','Left foot',false); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.headerclose').click(function(){
            fetchPage('system/bank_rec/erp_post_dated_cheques','','Postdated cheque');
        });
        get_post_dated_cheques();


    });

    function get_post_dated_cheques() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            url: "<?php echo site_url('Bank_rec/get_post_dated_cheques'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#load_generated_tables').html(data);
                stopLoad();
                refreshNotifications(true);
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });

    }
    function get_post_dated_chequespayment() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            url: "<?php echo site_url('Bank_rec/get_post_dated_cheques_payment'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#load_generated_tables2').html(data);
                stopLoad();
                refreshNotifications(true);
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });

    }


</script>