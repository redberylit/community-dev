<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('sales_maraketing_masters_customer_master', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('sales_maraketing_masters_add_new_customer');
echo head_page($title, false);

/*echo head_page($_POST['page_name'],false);*/
$country        = load_country_drop();
$gl_code_arr    = supplier_gl_drop();
$currncy_arr    = all_currency_new_drop();
$country_arr    = array('' => 'Select Country');
$taxGroup_arr    = customer_tax_groupMaster();
$customerCategory    = party_category(1);
if (isset($country)) {
    foreach ($country as $row) {
        $country_arr[trim($row['CountryDes'])] = trim($row['CountryDes']);
    }
}
?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('','role="form" id="customermaster_form"'); ?>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('sales_maraketing_masters_customer_secondary_code');?> <?php  required_mark(); ?></label><!--Customer Secondary Code-->
                <input type="text" class="form-control" id="customercode" name="customercode">
            </div>
            <div class="form-group col-sm-4">
                <label for="customerName"><?php echo $this->lang->line('sales_maraketing_masters_customer_name');?> <?php  required_mark(); ?></label><!--Customer Name-->
                <input type="text" class="form-control" id="customerName" name="customerName" required>
            </div>
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('common_category');?> </label><!--Category-->
                <?php  echo form_dropdown('partyCategoryID', $customerCategory, '','class="form-control select2"  id="partyCategoryID"'); ?>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for="receivableAccount"><?php echo $this->lang->line('sales_maraketing_masters_receivable_account');?> <?php  required_mark(); ?></label><!--Receivable Account-->
                <?php  echo form_dropdown('receivableAccount', $gl_code_arr,$this->common_data['controlaccounts']['ARA'],'class="form-control select2" id="receivableAccount" required'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for="customerCurrency"><?php echo $this->lang->line('sales_maraketing_masters_customer_currency');?> <?php  required_mark(); ?></label><!--Customer Currency-->
                <?php  echo form_dropdown('customerCurrency', $currncy_arr, $this->common_data['company_data']['company_default_currency'] ,'class="form-control select2" onchange="changecreditlimitcurr()" id="customerCurrency" required'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('sales_maraketing_masters_customer_country');?> <?php  required_mark(); ?></label><!--Customer Country-->
                <?php  echo form_dropdown('customercountry', $country_arr, $this->common_data['company_data']['company_country'] ,'class="form-control select2"  id="customercountry" required'); ?>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('sales_maraketing_masters_tax_group');?> </label><!--Tax Group-->
                <?php  echo form_dropdown('customertaxgroup', $taxGroup_arr, '','class="form-control select2"  id="customertaxgroup"'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for="">VAT Identification No</label>
                <input type="text" class="form-control" id="vatIdNo" name="vatIdNo">
            </div>
            <div class="form-group col-sm-4">
                <label for="customerTelephone"><?php echo $this->lang->line('common_telephone');?></label><!--Telephone-->
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                    <input type="text" class="form-control" id="customerTelephone" name="customerTelephone">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for="customerEmail"><?php echo $this->lang->line('common_email');?></label><!--Email-->
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                    <input type="text" class="form-control" id="customerEmail" name="customerEmail">
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label for="customerFax"><?php echo $this->lang->line('common_fax');?></label><!--Fax-->
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-fax" aria-hidden="true"></i></div>
                    <input type="text" class="form-control" id="customerFax" name="customerFax" >
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label for="customercustomerCreditPeriod"><?php echo $this->lang->line('sales_maraketing_masters_credit_period');?></label><!--Credit Period-->
                <div class="input-group">
                    <div class="input-group-addon"><?php echo $this->lang->line('common_month');?> </div><!--Month-->
                    <input type="text" class="form-control number" id="customerCreditPeriod" name="customerCreditPeriod">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for="customercustomerCreditLimit"><?php echo $this->lang->line('sales_maraketing_masters_credit_limit');?></label><!--Credit Limit-->
                <div class="input-group">
                    <div class="input-group-addon"><span class="currency">LKR</span></div>
                    <input type="text" class="form-control number" id="customerCreditLimit" name="customerCreditLimit">
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label for="customerUrl">URL</label>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-link" aria-hidden="true"></i></div>
                    <input type="text" class="form-control" id="customerUrl" name="customerUrl">
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label for="customerAddress1"><?php echo $this->lang->line('sales_maraketing_masters_primary_address');?> </label><!--Primary Address-->
                <textarea class="form-control" rows="2" id="customerAddress1" name="customerAddress1"></textarea>
            </div>
            <div class="form-group col-sm-4">
                <label for="customerAddress2"><?php echo $this->lang->line('sales_maraketing_masters_secondary_address');?> </label><!--Secondary Address-->
                <textarea class="form-control" rows="2" id="customerAddress2" name="customerAddress2"></textarea>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for=""><?php echo $this->lang->line('sales_maraketing_masters_is_active');?> </label><!--isActive-->

                    <div class="skin skin-square">
                        <div class="skin-section" id="extraColumns">
                            <input id="checkbox_isActive" type="checkbox"
                                   data-caption="" class="columnSelected" name="isActive" value="1" checked>
                            <label for="checkbox">
                                &nbsp;
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
            <div class="text-right m-t-xs">
                <button class="btn btn-primary" id="customer_btn" type="submit"><?php echo $this->lang->line('sales_maraketing_masters_add_customer');?> </button><!--Add Customer-->
            </div>
        </form>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
var customerAutoID;
    $( document ).ready(function() {
        $('#customer_btn').text('<?php echo $this->lang->line('sales_maraketing_masters_add_customer');?>');/*Add Customer*/
        $('.select2').select2();
        $('.headerclose').click(function(){
            fetchPage('system/customer/erp_customer_master','','Customer Master');
        });
        customerAutoID         = null;
        number_validation();
        p_id         = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            customerAutoID =p_id;
            load_customer_header();
            $('.btn-wizard').removeClass('disabled');
        }else{
            $('.btn-wizard').addClass('disabled');
        }
        $('#customermaster_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                customercode        : {validators: {notEmpty: {message: '<?php echo $this->lang->line('sales_maraketing_masters_customer_code_required');?>.'}}},/*customer Code is required*/
                customerName        : {validators: {notEmpty: {message: '<?php echo $this->lang->line('sales_maraketing_masters_customer_name_required');?>.'}}},/*customer Name is required*/
                customercountry     : {validators: {notEmpty: {message: '<?php echo $this->lang->line('sales_maraketing_masters_customer_country_required');?>.'}}},/*customer Country is required*/
                /*               customerTelephone   : {validators: {notEmpty: {message: 'customer Telephone is required.'}}},
                customerEmail       : {
                    validators: {
                        notEmpty: {
                            message: 'customer Email is required.'
                        },
                        emailAddress: {
                            message: 'The value is not a valid email address'
                        }
                    }
                },
                customerAddress1    : {validators: {notEmpty: {message: 'Address 1 is required.'}}},
                customerAddress2    : {validators: {notEmpty: {message: 'Address 2 is required.'}}},  */
                receivableAccount   : {validators: {notEmpty: {message: '<?php echo $this->lang->line('sales_maraketing_masters_customer_receivabl_account_is_required');?>.'}}},/*Receivabl Account is required*/
/*                customerCreditPeriod: {
                    validators: {
                        notEmpty: {
                            message: 'Credit Period is required'
                        },
                        stringLength: {
                            max: 3,
                            message: 'Character must be below 4 character'
                        }
                    }
                },
                customerCreditLimit : {validators: {notEmpty: {message: 'customer Credit Limit is required.'}}},*/
                customerCurrency    : {validators: {notEmpty: {message: '<?php echo $this->lang->line('sales_maraketing_masters_customer_currency_is_required');?>.'}}},/*customer Currency  is required*/
                customerName        : {validators: {notEmpty: {message: '<?php echo $this->lang->line('sales_maraketing_masters_customer_name_required');?>.'}}}/*customer Name is required*/
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name' : 'customerAutoID', 'value' : customerAutoID });
            data.push({'name' : 'currency_code', 'value' : $('#customerCurrency option:selected').text()});
            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('Customer/save_customer'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        HoldOn.close();
                        refreshNotifications(true);
                        fetchPage('system/customer/erp_customer_master','Test','customer Master');
                    }, 
                    error: function () {
                        alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                        HoldOn.close();
                        refreshNotifications(true);
                    }
                });
        });

        $('#extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });
    });

    function load_customer_header(){
        if (customerAutoID) {
            $.ajax({
                async : true,
                type : 'post',
                dataType : 'json',
                data : {'customerAutoID':customerAutoID},
                url :"<?php echo site_url('Customer/load_customer_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success : function(data){
                    if(!jQuery.isEmptyObject(data)){
                        $('#customer_btn').text('<?php echo $this->lang->line('sales_maraketing_masters_customer_update');?>');/*Update Customer*/
                        customerAutoID = data['customerAutoID'];
                        $("#customerTelephone").val(data['customerTelephone']);
                        $("#customercode").val(data['secondaryCode']);
                        $('#customerName').val(data['customerName']);
                        $('#customerFax').val(data['customerFax']);
                        $('#receivableAccount').val(data['receivableAutoID']).change();
                        //$("#assteGLCode").prop("disabled", true);
                        $('#customerCurrency').val(data['customerCurrencyID']).change();
                        $("#customerCurrency").prop("disabled", true);
                        $('#customercountry').val(data['customerCountry']).change();
                        $('#customerTelephone').val(data['customerTelephone']);
                        $('#customerEmail').val(data['customerEmail']);
                        $('#customerUrl').val(data['customerUrl']);
                        $('#customerCreditPeriod').val(data['customerCreditPeriod']);
                        $('#customerCreditLimit').val(data['customerCreditLimit']);
                        $('#customerAddress1').val(data['customerAddress1']);
                        $('#customerAddress2').val(data['customerAddress2']);
                        $('#partyCategoryID').val(data['partyCategoryID']).change();
                        $('#customertaxgroup').val(data['taxGroupID']).change();
                        $('#vatIdNo').val(data['vatIdNo']);
                        if (data['isActive'] == 1) {
                            $('#checkbox_isActive').iCheck('check');
                        } else {
                            $('#checkbox_isActive').iCheck('uncheck');
                        }
                        //set_currency(data['customerCurrency']);
                        // $('[href=#step2]').tab('show');
                        // $('a[data-toggle="tab"]').removeClass('btn-primary');
                        // $('a[data-toggle="tab"]').addClass('btn-default');
                        // $('[href=#step2]').removeClass('btn-default');
                        // $('[href=#step2]').addClass('btn-primary');
                    }
                    stopLoad();
                    refreshNotifications(true);
                },error : function(){
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });        
        }

        function set_currency(val){
            $('.currency').html(val);
        }    
    }

    function changecreditlimitcurr(){
        var currncy;
        var split;
      currncy=  $('#customerCurrency option:selected').text();
        split= currncy.split("|");
        $('.currency').html(split[0]);
        CurrencyID = $('#customerCurrency').val();
        currency_validation_modal(CurrencyID,'CUS','','CUS');
    }
</script>