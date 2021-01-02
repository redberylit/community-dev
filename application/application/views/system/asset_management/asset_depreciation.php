<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('assetmanagementnew', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);


//echo head_page('Asset Depreciation', false);
$financeyear_arr = all_financeyear_drop();
?>
<!--<div class="pull-right">
    <button class="btn btn-xs btn-danger" onclick=" toggleDivs('#assetDepDivHistory', 'Asset Depreciation History')"><i
            class="fa fa-remove"></i></button>
</div>-->
<div class="tab-content">
    <?php echo form_open('', 'role="form" id="asset_dep_form"'); ?>
    <div class="row">
        <div class="form-group col-sm-4">
            <label for="financeyear"><?php echo $this->lang->line('assetmanagement_financial_year');?><!--Financial Year--> <?php required_mark(); ?></label>
            <?php echo form_dropdown('financeyear', $financeyear_arr, $this->common_data['company_data']['companyFinanceYearID'], 'class="form-control" id="financeyear" required onchange="fetch_finance_year_period(this.value)"'); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <label for="financeyear_period"><?php echo $this->lang->line('assetmanagement_financial_period');?><!--Financial Period--> <?php required_mark(); ?></label>
            <?php echo form_dropdown('financeyear_period', array('' => 'Financial Period'), '', 'class="form-control" id="financeyear_period" required'); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <!--<a class="btn btn-flat btn-primary" type="button"
               onclick="fetchPage('system/asset_management/asset_depreciation_master', '', 'Asset Depreciation History','','')">
                View All
            </a>-->
            <button id="" type="submit" class="btn btn-flat btn-primary pull-right"><?php echo $this->lang->line('common_generate');?><!--Generate--></button>
        </div>
    </div>
    </form>
</div>

<script>
    $('#asset_dep_form').bootstrapValidator({
        live: 'enabled',
        message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
        excluded: [':disabled'],
        fields: {
            financeyear: {validators: {notEmpty: {message: '<?php echo $this->lang->line('assetmanagement_financial_year_is_required');?>.'}}},/*Financial Year is required*/
            financeyear_period: {validators: {notEmpty: {message: '<?php echo $this->lang->line('assetmanagement_financial_period_is_required');?>.'}}}/*Financial Period is required*/
        },
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        var data = $form.serializeArray();
        bootbox.confirm('Are you sure ? You want to generate Asset Depreciation for selected Finance Period.', function (confirmed) {
            if (confirmed) {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('AssetManagement/assetDepGenerate'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        refreshNotifications(true);
                        if (data['status'] == true) {
                            getAssetDepDetail(data['last_id'], 0)
                        }
                        stopLoad();
                    }, error: function () {
                        alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                        stopLoad();
                        refreshNotifications(true);
                    }
                });
            }
        })
    });

    function fetch_finance_year_period(companyFinanceYearID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'companyFinanceYearID': companyFinanceYearID},
            url: "<?php echo site_url('Dashboard/fetch_finance_year_period'); ?>",
            success: function (data) {
                $('#financeyear_period').empty();
                var mySelect = $('#financeyear_period');
                mySelect.append($('<option></option>').val('').html('Select  Financial Period'));
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['dateFrom'] + '|' + text['dateTo']).html(text['dateFrom'] + ' - ' + text['dateTo']));
                    });
                    if (select_value) {
                        $("#financeyear_period").val(select_value);
                    }
                    ;
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }

    FinanceYearID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinanceYearID'])); ?>;
    DateFrom = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateFrom'])); ?>;
    DateTo = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateTo'])); ?>;
    fetch_finance_year_period(FinanceYearID, DateFrom + '|' + DateTo);
</script>