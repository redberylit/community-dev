<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('finance', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('finance_ms_ca_chart_of_accounts');
echo head_page($title, false);


/*echo head_page('Chart of Accounts', false);*/
$master_acc_arr = master_coa_account();
$currency_arr = all_currency_drop();
$master_arr = array('' => 'Select Type', '1' => 'Master Account', '0' => 'Ledger Account');
$controll_arr = array('' => 'Select Type', '1' => 'Controll Account', '0' => 'Ledger Account');
?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
<style>
    /**
     * Framework starts from here ...
     * ------------------------------
     */
    .tree,
    .tree ul {
        margin: 0 0 0 1em; /* indentation */
        padding: 0;
        list-style: none;
        color: #cbd6cc;
        position: relative;
    }

    .tree ul {
        margin-left: .5em
    }

    /* (indentation/2) */

    .tree:before,
    .tree ul:before {
        content: "";
        display: block;
        width: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        border-left: 1px solid;
    }

    .tree li {
        margin: 0;
        padding: 0 1.5em; /* indentation + .5em */
        line-height: 2em; /* default list item's `line-height` */
        font-weight: bold;
        position: relative;
        font-size: 11px
    }

    .tree li:before {
        content: "";
        display: block;
        width: 10px; /* same with indentation */
        height: 0;
        border-top: 1px solid;
        margin-top: -1px; /* border top width */
        position: absolute;
        top: 1em; /* (line-height/2) */
        left: 0;
    }

    .tree li:last-child:before {
        background: white; /* same with body background */
        height: auto;
        top: 1em; /* (line-height/2) */
        bottom: 0;
    }

    .header {
        color: #000080;
        font-weight: bolder;
        font-size: 13px;
        background-color: #E8F1F4;
    }

    .subheader {
        color: black;
        font-weight: bolder;
        font-size: 11px;
        background-color: #fbfbfb;
    }

    .subdetails {
        /* color: #4e4e4e;*/

        font-size: 11px;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 4px;
    }

    .highlight {
        background-color: #FFF59D;
        /* color:#555;*/
    }

</style>

<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-2">
        <table class="<?php echo table_class(); ?>">
            <tr>
                <td>
                    <span class="glyphicon glyphicon-stop" style="color:green; font-size:15px;"></span> <?php echo $this->lang->line('common_active');?><!--Active-->
                </td>
                <td>
                    <span class="glyphicon glyphicon-stop" style="color:red; font-size:15px;"></span> <?php echo $this->lang->line('finance_common_inactive');?><!--Inactive-->
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-3">
        <span style=""><?php echo $this->lang->line('finance_ms_ca_account_category');?><!--Account Category--></span>

        <?php echo form_dropdown('accountType', all_account_category_drop(false), '', 'class="form-control" multiple onchange="load_page()" id="accountType"'); ?>

    </div>
    <div class="col-md-4">
        <span style=""><?php echo $this->lang->line('finance_common_find_gl');?><!--Find GL--> &nbsp;</span>
        <input name="query" id="query" class="" type="text" size="30" maxlength="30"
               onkeyup="highlightSearch(this.value)">
    </div>

    <div class="col-md-1 text-right pull-right">
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" onclick="load_gl_model()"><i
                class="fa fa-plus"></i> <?php echo $this->lang->line('common_create_new');?><!--Create New-->
        </button>
    </div>
</div>
<hr>
<div id="load_chartofAccount">
    <?php
    $CI =& get_instance();
    $companyID = $CI->common_data['company_data']['company_id'];
    $header = $CI->db->query("select Type,accountCategoryTypeID,CategoryTypeDescription from srp_erp_accountcategorytypes order by sortOrder asc")->result_array();
    // $details = $CI->db->query("SELECT * from srp_erp_chartofaccounts where companyID=11")->result_array();
    $details = $CI->db->query("SELECT 
    srp_erp_chartofaccounts.*,companyReportingAmount,companyReportingCurrencyDecimalPlaces
FROM
    srp_erp_chartofaccounts
        LEFT JOIN
    (SELECT 
        SUM(companyReportingAmount) AS companyReportingAmount,GLAutoID,companyReportingCurrencyDecimalPlaces
    FROM
        srp_erp_generalledger WHERE companyID=11 GROUP BY srp_erp_generalledger.GLAutoID) gl ON (gl.GLAutoID =srp_erp_chartofaccounts.GLAutoID)
WHERE
    srp_erp_chartofaccounts.companyID = 11
GROUP BY srp_erp_chartofaccounts.GLAutoID")->result_array();
    $html = "";
    if ($header) {
        $html .= "<ul class='tree'>";
        foreach ($header as $value) {
            $html .= "<li>";
            $html .= "<span class='header'>" . $value['CategoryTypeDescription'] . " (" . $value['Type'] . ")</span>";
            if ($details) {
                foreach ($details as $account) {
                    if ($account['masterAccountYN'] == 1 && $account['accountCategoryTypeID'] == $value['accountCategoryTypeID']) {
                        $html .= "<ul>";
                        $html .= "<li>";
                        $html .= "<span class='subheader'>" . $account['GLDescription'] . "  | System Code : " . $account['systemAccountCode'] . "  | Secondary Code :" . $account['GLSecondaryCode'] . "</span>";

                        if ($details) {
                            foreach ($details as $subAccount) {
                                if ($subAccount['masterAutoID'] == $account['GLAutoID']) {
                                    $html .= "<ul>";
                                    $html .= "<li>";

                                    $html .= "<span class='subdetails'>" . $subAccount['GLDescription'] . "  | System Code : " . $subAccount['systemAccountCode'] . "  | Secondary Code :" . $subAccount['GLSecondaryCode'] . "</span>";
                                    $html .= "</li>";

                                    $html .= "</ul>";
                                }
                            }
                        }
                        $html .= "</li>";

                        $html .= "</ul>";
                    }
                }
            }
            $html .= "</li>";
        }
        $html .= "</ul>";

        //$html;
    }
    ?>


</div>
<br>
<br>

<div class="modal fade" id="GL_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false"
     data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('finance_ms_ca_chart_of_accounts');?><!--Chart of Accounts--></h4>
            </div>
            <form class="form-horizontal" id="chart_of_accont_form">
                <input type="hidden" id="controlAccountUpdate" name="controlAccountUpdate" value="0">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="masterCategory" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_account_type');?><!--Account Type-->  <?php required_mark(); ?></label>

                                <div class="col-sm-8">
                                    <?php echo form_dropdown('accountCategoryTypeID', all_account_category_drop(), '', 'class="form-control" onchange="fetch_master_Account(this.value)" id="accountCategoryTypeID"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="masterAccountYN1" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_ms_ca_master_account');?><!--Master Account-->  <?php required_mark(); ?></label>

                                <div class="col-sm-3">
                                    <?php echo form_dropdown('masterAccountYN', array('' => $this->lang->line('finance_common_select_status')/*'Select Status'*/, '1' => $this->lang->line('common_yes')/*'Yes'*/, '0' => $this->lang->line('common_no')/*'No'*/), '0', 'class="form-control " id="masterAccountYN" onchange="set_master_detail(this.value)"'); ?>
                                    <?php echo form_dropdown('isBank', array('' => $this->lang->line('finance_common_select_status')/*'Select Status'*/, '1' => $this->lang->line('common_yes')/*'Yes'*/, '0' => $this->lang->line('common_no')/*'No'*/), '0', 'class="form-control control_account" id="isBank" style="display: none;" required'); ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php echo form_dropdown('masterAccount', array('' => $this->lang->line('finance_ms_ca_master_account')/*'Master Account'*/), '', 'class="form-control set_master" id="masterAccount"'); ?>
                                </div>
                            </div>
                            <div class="form-group isCash" style="display: none;">
                                <label for="bankCheckNumber" class="col-sm-4 control-label"><?php echo $this->lang->line('common_type');?><!--Type--></label>

                                <div class="col-sm-6">
                                    <?php echo form_dropdown('isCash', array('' => $this->lang->line('common_select_type')/*'Select Type'*/, 1 => $this->lang->line('finance_ms_ca_cash_account')/*'Cash Account'*/, 0 => $this->lang->line('finance_ms_ca_bank_account')/*'Bank Account'*/), 0, 'class="form-control" onchange="is_cash(this.value)" id="isCash"'); ?>
                                </div>
                            </div>
                            <div class="form-group set_bank set_controll">
                                <label for="bankName" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_bank_name');?><!--Bank Name-->  <?php required_mark(); ?></label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="bankName" name="bankName">
                                </div>
                            </div>
                            <div class="form-group set_bank">
                                <label for="bankAccountNumber" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_b_account_number');?><!--B/Account Number--> <?php required_mark();?></label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control number" id="bankAccountNumber"
                                           name="bankAccountNumber">
                                </div>
                            </div>
                            <div class="form-group set_bank set_controll">
                                <label for="bankCheckNumber" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_ms_ca_check_number');?><!--Check Number-->  <?php required_mark(); ?></label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control number" id="bankCheckNumber"
                                           name="bankCheckNumber">
                                </div>
                            </div>
                            <div class="form-group activeSub">
                                <label for="bankCheckNumber" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_is_active_is');?><!--isActive--></label>

                                <div class="col-sm-6">
                                    <div class="skin skin-square">
                                        <div class="skin-section" id="extraColumns">
                                            <input id="checkbox_isActive" type="checkbox"
                                                   data-caption="" class="columnSelected" name="isActive" value="1"
                                                   checked>
                                            <label for="checkbox">
                                                &nbsp;
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GLSecondaryCode" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_secondary_code');?><!--Secondary Code-->  <?php required_mark(); ?></label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="GLSecondaryCode" name="GLSecondaryCode">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="GLDescription" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_account_name');?><!--Account Name-->  <?php required_mark(); ?></label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="GLDescription" id="GLDescription">
                                </div>
                            </div>
                            <div class="form-group set_bank set_controll">
                                <label for="bank_branch" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_common_bank_branch');?><!--Bank Branch-->  <?php required_mark(); ?></label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="bank_branch" name="bank_branch">
                                </div>
                            </div>
                            <div class="form-group set_bank set_controll">
                                <label for="bank_swift_code" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_ms_ca_bank_swift_code');?><!--Bank Swift Code-->  <?php required_mark(); ?></label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="bank_swift_code" name="bank_swift_code">
                                </div>
                            </div>
                            <div class="form-group set_bank set_controll">
                                <label for="bank_branch" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_ms_ca_bank_currency');?><!--Bank Currency-->  <?php required_mark(); ?></label>

                                <div class="col-sm-7">
                                    <?php echo form_dropdown('bankCurrencyCode', $currency_arr, '', 'class="form-control select2" id="bankCurrencyCode" '); ?>
                                </div>
                            </div>
                            <div class="form-group set_bank">
                                <label for="bankCheckNumber" class="col-sm-4 control-label"><?php echo $this->lang->line('finance_ms_ca_bank_iscard');?><!--isCard--></label>

                                <div class="col-sm-6">
                                    <?php echo form_dropdown('isCard', array('' => $this->lang->line('finance_common_select_status')/*'Select Status'*/, '1' => $this->lang->line('common_yes')/*'Yes'*/, '0' => $this->lang->line('common_no')/*'No'*/), '0', 'class="form-control control_account" id="isCard"'); ?>
                                </div>
                            </div>
                            <!--<div class="form-group">
                                <label for="" class="col-sm-4 control-label">Signature level</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control number"  id="authourizedSignatureLevel" name="authourizedSignatureLevel">
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                    <button type="Submit" class="btn btn-primary" id="chartofaccountbtn"><?php echo $this->lang->line('common_save_change');?><!--Save changes--></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script>
    $(document).ready(function () {
        $('#extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });
    });
    $('.headerclose').click(function () {
        fetchPage('system/chart_of_accounts/erp_chart_of_accounts_manage', '', 'Chart of Accounts');
    });
    $('#accountType').multiselect2({
        includeSelectAllOption: true,
        enableFiltering: true,
        onChange: function (element, checked) {
        }
    });
    $("#accountType").multiselect2('selectAll', false);
    $("#accountType").multiselect2('updateButtonText');

    load_master_ofAccount();

    function load_page() {
        load_master_ofAccount();
    }
    function load_master_ofAccount() {
        $.ajax({
            type: 'post',
            dataType: 'html',
            data: {accountTYpe: $('#accountType').val()},
            url: "<?php echo site_url('Chart_of_acconts/load_master_ofAccount'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#load_chartofAccount').html(data);
                $("[rel=tooltip]").tooltip();

            },
            error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
            }
        });
    }

    $('#chart_of_accont_form').bootstrapValidator({
        live: 'enabled',
        message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
        excluded: [':disabled'],
        fields: {
           /* accountCategoryTypeID: {validators: {notEmpty: {message: 'Account Type is required.'}}},
            GLSecondaryCode: {validators: {notEmpty: {message: 'Account Code is required.'}}},
            GLDescription: {validators: {notEmpty: {message: 'Account Description is required.'}}},
            masterAccountYN: {validators: {notEmpty: {message: 'Is Master Account is required.'}}},*/
        },
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        var data = $form.serializeArray();
        data.push({'name': 'GLAutoID', 'value': GLAutoID});
        data.push({'name': 'masterAccount_dec', 'value': $('#masterAccount option:selected').text()});
        data.push({'name': 'account_type', 'value': $('#accountCategoryTypeID option:selected').text()});
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('Chart_of_acconts/save_chart_of_accont'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data_arr) {
                stopLoad();
                refreshNotifications(true);
                $('#chartofaccountbtn').attr('disabled',false);
                if (data_arr) {
                    $("#GL_modal").modal("hide");
                    $('#chart_of_accont_form')[0].reset();
                    /*             $('#chart_of_accont_form').bootstrapValidator('resetForm', true);*/
                    load_master_ofAccount();
                }
            },
            error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    });

    function load_gl_model() {
        GLAutoID = null;
        $("#GL_modal").modal({backdrop: "static"});
        $('#chart_of_accont_form')[0].reset();
        $('.set_bank').addClass('hidden');
        $('#isCash').val('0');
        $('.isCash').hide();
        $('#accountCategoryTypeID ').prop('disabled', false);
        $('#masterAccountYN ').prop('disabled', false);
        $('#masterAccount ').prop('disabled', false);
        $('#checkbox_isActive').iCheck('enable');
        $('#controlAccountUpdate').val(0);
    }

    function set_master_detail(val) {
        var account_id = $('#accountCategoryTypeID option:selected').val();

        val = $('#masterAccountYN option:selected').val();
        $('#masterAccountYN').val(val.toString());
        $('#isBank').val('0');
        $('.set_bank').addClass('hidden');
        $('#isCash').val('0');
        $('.isCash').hide();
        if (val == 1) {
            $('.set_master').hide();
            $('.is_master').show();
            $('#masterAccount').val('');
            $('.activeSub').hide();
        } else {
            $('.set_master').show();
            $('.is_master').hide();
            $('.activeSub').show();
            if (account_id == 1 && val == 0) {
                $('#isBank').val('1');
                $('.set_bank').removeClass('hidden');
                $('#isCash').val('0');
                $('.isCash').show();
            }
        }
    }

    function is_cash(val) {
        if (val == 1) {
            $('.set_bank').addClass('hidden');
        }else{
            $('.set_bank').removeClass('hidden');
        }
    }

    function fetch_master_Account(val, select_value) {
        string = $('#accountCategoryTypeID option:selected').text();
        accountCategoryTypeID = $('#accountCategoryTypeID option:selected').val();
        if (string) {
            sub_cat = string.split('|');
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'subCategory': sub_cat[1], 'accountCategoryTypeID': accountCategoryTypeID, GLAutoID: GLAutoID},
                url: "<?php echo site_url('Chart_of_acconts/fetch_master_account'); ?>",
                success: function (data) {
                    $('#masterAccount').empty();
                    var mySelect = $('#masterAccount');
                    mySelect.append($('<option></option>').val('').html('Select Master Account'));
                    if (!jQuery.isEmptyObject(data)) {
                        $.each(data, function (val, text) {
                            mySelect.append($('<option></option>').val(text['GLAutoID']).html(text['systemAccountCode'] + ' | ' + text['GLSecondaryCode'] + ' | ' + text['GLDescription']));
                        });
                        if (select_value) {
                            $("#masterAccount").val(select_value);
                        }
                    }

                    /*If Bank*/
                    set_master_detail()
                }, error: function () {
                    swal("Cancelled", "Your " + value + " file is safe :)", "error");
                }
            });
        }
    }

    function edit_chart_of_accont(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('finance_common_you_want_to_edit_this_file');?>",/*You want to edit this file !*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#f8bb86",
                confirmButtonText: "<?php echo $this->lang->line('common_edit');?>",/*Edit*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'GLAutoID': id},
                    url: "<?php echo site_url('Chart_of_acconts/load_chart_of_accont_header'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        $("#GL_modal").modal({backdrop: "static"});
                        GLAutoID = data['GLAutoID'];
                        $('#GLSecondaryCode').val(data['GLSecondaryCode']);
                        $('#GLDescription').val(data['GLDescription']);
                        $('#masterAccount').val(data['masterAccount']);
                        $('#masterCategory').val(data['masterCategory']);
                        $('#controllAccountYN').val(data['controllAccountYN']);
                        $('#accountCategoryTypeID').val(data['accountCategoryTypeID']);
                        fetch_master_Account(data['subCategory'], data['masterAutoID']);
                        $('#subCategory').val(data['subCategory']);
                        $('#isBank').val(data['isBank']);
                        set_master_detail(data['masterAccountYN']);
                        $('#bankName').val(data['bankName']);
                        $('#bankAccountNumber').val(data['bankAccountNumber']);
                        $('#bankCheckNumber').val(data['bankCheckNumber']);
                        $('#masterAccountYN').val(data['masterAccountYN']);
                        $('#bank_swift_code').val(data['bankSwiftCode']);
                        $('#bank_branch').val(data['bankBranch']);
                        $('#bankCurrencyCode').val(data['bankCurrencyCode']);
                        $('#isCard').val(data['isCard']);
                        if (data['isBank'] == 1) {
                            is_cash(data['isCash']);
                            setTimeout(function () {
                                $('#isCash').val(data['isCash']).change();
                            }, 2500);
                        }
                        if(data['isActive'] == 1){
                            $('#checkbox_isActive').iCheck('check');
                        }else{
                            $('#checkbox_isActive').iCheck('uncheck');
                        }

                        if(data['controllAccountYN']==1){
                            $('#accountCategoryTypeID ').prop('disabled', true);
                            $('#masterAccountYN ').prop('disabled', true);
                            $('#masterAccount ').prop('disabled', true);
                            $('#checkbox_isActive').iCheck('disable');
                            $('#controlAccountUpdate').val(1);

                        }
                        else{
                            $('#accountCategoryTypeID ').prop('disabled', false);
                            $('#masterAccountYN ').prop('disabled', false);
                            $('#masterAccount ').prop('disabled', false);
                            $('#checkbox_isActive').iCheck('enable');
                            $('#controlAccountUpdate').val(0);
                        }

                        stopLoad();
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }
</script>