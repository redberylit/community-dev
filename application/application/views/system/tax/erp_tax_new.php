<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('tax', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('tax_new_tax');
echo head_page($title, false);

/*echo head_page('New Tax',false);*/
//$current_date        = format_date($this->common_data['current_date']);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$this->load->helpers('expense_claim');
$supplier_arr = all_authority_drop();
$gl_code_arr = authority_gl_drop();
$tax_type_arr        = array('' => $this->lang->line('tax_select_tax_type')/*'Select Tax Type'*/,'1' => $this->lang->line('tax_sales_tax')/*'Sales Tax'*/,'2' =>$this->lang->line('tax_purchase_tax') /*'Purchase Tax'*/);
?>
<div id="filter-panel" class="collapse filter-panel"></div>
<!-- <div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - Tax Header</a>
    <a class="btn btn-default btn-wizard" href="#step2" onclick="fetch_details()" data-toggle="tab">Step 2 - Tax Detail</a>
    <a class="btn btn-default btn-wizard" href="#step3" onclick="fetch_addon_cost()" data-toggle="tab">Step 3 - Tax Confirmation</a>
</div><hr>
<div class="tab-content"> -->
    <div id="step1" class="tab-pane active">
        <?php echo form_open('','role="form" id="tax_form"'); ?>
            <div class="row" >
                <div class="form-group col-sm-4">
                   <label for="taxType"><?php echo $this->lang->line('tax_type');?><!--Tax Type--> <?php  required_mark(); ?></label>
                   <?php echo form_dropdown('taxType', $tax_type_arr, '','class="form-control" id="taxType" required'); ?>
                </div>
                <div class="form-group col-sm-2">
                    <label for=""><?php echo $this->lang->line('tax_reference_no');?><!--Reference No--></label>
                    <input type="text" class="form-control " id="taxReferenceNo" name="taxReferenceNo">
                </div>
                <div class="form-group col-sm-2">
                    <label for="effectiveFrom"><?php echo $this->lang->line('tax_from_date');?><!--From Date--> <?php required_mark();  ?></label>
                    <div class="input-group datepic">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type='text' class="form-control" id="effectiveFrom" name="effectiveFrom" value="<?php echo $current_date; ?>" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" />
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label for="taxDescription"><?php echo $this->lang->line('tax_description');?><!--Tax Description--> <?php required_mark();  ?></label>
                    <input type="text" class="form-control " id="taxDescription" name="taxDescription">
                </div>
            </div>
            <div class="row" >
                <div class="form-group col-sm-2">
                    <label for="taxShortCode"><?php echo $this->lang->line('tax_code');?><!--Tax Code--> <?php  required_mark(); ?></label>
                    <input type="text" class="form-control " id="taxShortCode" name="taxShortCode">
                </div>
                <div class="form-group col-sm-2">
                    <label for=""><?php echo $this->lang->line('tax_percentage');?><!--Tax Percentage--> <?php  required_mark(); ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control " id="taxPercentage" name="taxPercentage">
                        <div class="input-group-addon">%</div>
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label for="supplierID"><?php echo $this->lang->line('tax_paying_authority');?><!--Tax Paying Authority--> <?php required_mark(); ?></label>
                    <?php echo form_dropdown('supplierID', $supplier_arr, '', 'class="form-control select2" id="supplierID" onchange="changesupplierGLAutoID()" required'); ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="liabilityAccount"><?php echo $this->lang->line('tax_liability_account');?><!--Liability Account--> <?php required_mark(); ?></label>
                    <?php echo form_dropdown('supplierGLAutoID', $gl_code_arr, '', 'class="form-control select2" id="supplierGLAutoID" required'); ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="isActive"><?php echo $this->lang->line('tax_status');?><!--Tax Status--> <?php  required_mark(); ?></label>
                    <?php echo form_dropdown('isActive',array('1' => $this->lang->line('common_active')/*'Active'*/,'0' =>$this->lang->line('tax_deactive')/*'Deactive'*/),'1','class="form-control" id="isActive" required'); ?>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Is Claimable</label>
                        <div class="skin skin-square">
                            <div class="skin-section" id="extraColumns">
                                <input id="isClaimable" type="checkbox" data-caption="" class="columnSelected" name="isClaimable" value="1" checked>
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
                <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('common_save');?><!--Save--></button>
            </div>
        </form>
    </div>
    <!-- <div id="step2" class="tab-pane">
        <div class="row">
            <div class="col-md-8"><h4>&nbsp;&nbsp;&nbsp;<i class="fa fa-hand-o-right"></i> Addon Cost </h4><h4></h4></div>
            <div class="col-md-4"><button type="button" onclick="addon_cost_modal()" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Addon Cost</button></div>
        </div><br> 
        <div class="table-responsive">
            <table class="<?php echo table_class(); ?>">
                <thead>
                    <tr>
                        <th style="min-width: 5%">#</th>
                        <th style="min-width: 20%">Addon Catagory</th>
                        <th style="min-width: 15%">Supplier</th>
                        <th style="min-width: 10%">Reference No</th>
                        <th style="min-width: 30%">Description</th>
                        <th style="min-width: 10%">Amount <span class="currency"> ( LK )</span></th>
                        <th style="min-width: 10%">&nbsp;</th>
                    </tr>
                </thead>
                <tbody id="addon_table_body">
                    <tr class="danger">
                        <td class="text-center" colspan="8">No Records Found</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Addons Total <span class="currency"> ( LKR )</span></td>
                        <td id="t_total" class="total text-right">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <hr> 
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev" onclick="">Previous</button>-->
            <!-- <button class="btn btn-primary next" onclick="load_conformation();" >Save & Next</button> -->
        <!-- </div>
    </div>
    <div id="step3" class="tab-pane">
        <div class="row">
            <div class="col-md-12">
                <span class="no-print pull-right">
                <a class="btn btn-default btn-sm" id="de_link" target="_blank" href="<?php //echo site_url('Double_entry/fetch_double_entry_grv/'); ?>"><span class="glyphicon glyphicon-random" aria-hidden="true"></span>  &nbsp;&nbsp;&nbsp;Account Review entries   
                </a>
                </span>
            </div>
        </div><hr>
        <div id="conform_body"></div>
        <hr> 
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev" >Previous</button>
            <button class="btn btn-primary " onclick="save_draft()">Save & Draft</button>
            <button class="btn btn-success submitWizard" onclick="confirmation()">Confirm</button>
        </div>
    </div>
</div> -->
<script type="text/javascript">
var taxMasterAutoID;
$( document ).ready(function() {
    $('.select2').select2();
    $('.headerclose').click(function(){
        fetchPage('system/tax/tax_management','Test','TAX');
    });
    taxMasterAutoID=null;
    /*$('#effectiveFrom').datepicker({
        format: 'yyyy-mm-dd'
    }).on('changeDate', function(ev){
        $('#tax_form').bootstrapValidator('revalidateField', 'effectiveFrom');
        $(this).datepicker('hide');
    });*/

    Inputmask().mask(document.querySelectorAll("input"));
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

    $('.datepic').datetimepicker({
        useCurrent: false,
        format: date_format_policy
    }).on('dp.change', function (ev) {
        $('#tax_form').bootstrapValidator('revalidateField', 'effectiveFrom');
    });

    p_id         = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
    if (p_id) {
        taxMasterAutoID =p_id;
        laad_tax_header();
        //$('.btn-wizard').removeClass('disabled');
    }else{
        //$('.btn-wizard').addClass('disabled');
    }

    $('#tax_form').bootstrapValidator({
        live            : 'enabled',
        message         : '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
        excluded        : [':disabled'],
        fields          : {
            taxType                 : {validators : {notEmpty:{message:'<?php echo $this->lang->line('tax_type_is_required');?>.'}}},/*Tax Type is required*/
            supplierID              : {validators : {notEmpty:{message:'<?php echo $this->lang->line('tax_supplier_is_required');?>.'}}},/*Supplier is required*/
            effectiveFrom           : {validators : {notEmpty:{message:'<?php echo $this->lang->line('tax_effective_date_is_required');?>.'}}},/*Effective Date is required*/
            taxShortCode            : {validators : {notEmpty:{message:'<?php echo $this->lang->line('tax_short_code_is_required');?>.'}}},/*Short Code is required*/
            taxDescription          : {validators : {notEmpty:{message:'<?php echo $this->lang->line('tax_short_tax_description_is_required');?>.'}}},/*Tax Description is required*/
            supplierGLAutoID        : {validators: {notEmpty: {message: '<?php echo $this->lang->line('tax_liability_account_is_required');?>.'}}}/*Liability Account is required*/
        },
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name' : 'taxMasterAutoID', 'value' : taxMasterAutoID });
            $.ajax({
                async : true,
                type : 'post',
                dataType : 'json',
                data : data,
                url :"<?php echo site_url('Tax/save_tax_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success : function(data){ 
                    refreshNotifications(true); 
                    if (data['status']) {
                        $('.btn-wizard').removeClass('disabled');
                        taxMasterAutoID = data['last_id'];
                        fetchPage('system/tax/tax_management','Test','TAX');
                    };
                    stopLoad();
                },error : function(){
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });   
    });
        
    // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    //     $('a[data-toggle="tab"]').removeClass('btn-primary');
    //     $('a[data-toggle="tab"]').addClass('btn-default');
    //     $(this).removeClass('btn-default');
    //     $(this).addClass('btn-primary');
    // });

    // $('.next').click(function(){
    //     var nextId = $(this).parents('.tab-pane').next().attr("id");
    //     $('[href=#'+nextId+']').tab('show');
    // });

    // $('.prev').click(function(){
    //     var prevId = $(this).parents('.tab-pane').prev().attr("id");
    //     $('[href=#'+prevId+']').tab('show');
    // });

    $('#extraColumns input').iCheck({
        checkboxClass: 'icheckbox_square_relative-blue',
        radioClass: 'iradio_square_relative-blue',
        increaseArea: '20%'
    });
});

function laad_tax_header(){
    if (taxMasterAutoID) {
        $.ajax({
            async : true,
            type : 'post',
            dataType : 'json',
            data : {'taxMasterAutoID':taxMasterAutoID},
            url :"<?php echo site_url('Tax/laad_tax_header'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success : function(data){
                if(!jQuery.isEmptyObject(data)){
                    $('#taxType').val(data['taxType']);
                    $("#effectiveFrom").val(data['effectiveFrom']);
                    $("#supplierID").val(data['supplierAutoID']).change();
                    $('#taxReferenceNo').val(data['taxReferenceNo']);
                    $('#taxDescription').val(data['taxDescription']);
                    $('#taxShortCode').val(data['taxShortCode']);
                    $('#taxPercentage').val(data['taxPercentage']);
                    if (data['isClaimable'] == 1) {
                        $('#isClaimable').iCheck('check');
                    } else {
                        $('#isClaimable').iCheck('uncheck');
                    }
                    setTimeout(function(){
                        $("#supplierGLAutoID").val(data['supplierGLAutoID']).change();
                    }, 1000);

                    //$('#taxPercentage').val(data['taxPercentage']);
                    //$('#financeyear').val(data['companyFinanceYearID']);
                    $('#isActive').val(data['isActive']);
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
}

function changesupplierGLAutoID() {
    $supplierID= $('#supplierID').val();
    $.ajax({
        async: true,
        type: 'post',
        dataType: 'json',
        data: {'supplierID': $supplierID},
        url: "<?php echo site_url('Tax/changesupplierGLAutoID'); ?>",
        beforeSend: function () {
            startLoad();
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                $("#supplierGLAutoID").val(data['taxPayableGLAutoID']).change();
            }
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