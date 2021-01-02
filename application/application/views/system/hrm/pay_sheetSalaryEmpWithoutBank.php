

<!--Translation added by Naseek-->


<?php
$companyBanks = company_bank_account_drop();
$isNonPayroll = $this->input->post('isNonPayroll');
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_payroll', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$this->lang->load('calendar', $primaryLanguage);

$no_data = $this->lang->line('hrms_payroll_no_data_available_in_table');
?>
<style type="text/css">
    .appendTR{
        background: #e3ebf1;
        height: 50px;
    }
    .payTDInputs{
        padding: 2px 4px;
        height: 25px;
        font-size: 12px;
    }
</style>
<div class="nav-tabs-custom">
    <ul class="nav nav2 nav-tabs" style="border-top: 1px solid #f4f4f4;">
        <li class="active"><a href="#empNoBank_pendingTab" id="" class="" data-toggle="tab" aria-expanded="true" data-value="0"><?php echo $this->lang->line('common_pending');?><!--Pending--></a></li>
        <li class=""><a href="#empNoBank_processedTab" id="" class=""  data-toggle="tab" aria-expanded="false" data-value="0"><?php echo $this->lang->line('common_processed');?><!--Processed--></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="empNoBank_pendingTab" > <!-- /.tab-pane -->
            <div style="margin-top: 2%">&nbsp;</div>
            <?php echo form_dropdown('getClone_selectBox', $companyBanks, '', 'id="getClone_selectBox" class="form-control" style="display:none"'); ?>
            <table class="<?php echo table_class(); ?>"  id="empNoBankTB">
                <thead>
                <tr>
                    <th style="width: auto"> # </th>
                    <th style="width: auto"> <?php echo $this->lang->line('hrms_payroll_emp_id');?><!--EMP ID--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('common_name');?><!--Name--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('common_currency');?><!--Currency--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('common_amount');?><!--Amount--> </th>
                    <th style="width: 10%"> <?php echo $this->lang->line('common_action');?><!--Action--> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $j = 0;  $n = 0; $totCurrency = 0;
                $lastCurrency = null;
                $carcy = $this->lang->line('common_currency');
                if( !empty($empWithoutBank)){
                    foreach($empWithoutBank as $empBnk){
                        $trCurrency = $empBnk['transactionCurrency'];
                        $btn = '';
                        $pay = $this->lang->line('common_pay');
                        if( $empBnk['isPaid'] != 1 ) {
                            $btn = '<button type="button" class="btn btn-primary btn-xs empNotBankPay_Btn" id="btnID_' . $j . '" style="font-size:10px"';
                            $btn .= 'onclick="payEmpSalary(this, ' . $j . ', ' . $empBnk['empID'] . ')">'.$pay.'</button>';
                        }

                        if($lastCurrency != $trCurrency){

                            echo '<tr > <th colspan="6" style="font-size: 12px">  '.  $carcy.' : '.$trCurrency.'</th></tr>' ;
                            $lastCurrency = $trCurrency;
                            $n = 0; $totCurrency = 0;
                        }

                        echo
                        '<tr id="TR_'.$j.'">
                            <td>' . ($j+1) . '</td>
                            <td>' . $empBnk['ECode'] . '</td>
                            <td>' . $empBnk['empName'] . '</td>
                            <td align="center">' . $trCurrency . '</td>
                            <td align="right">' . number_format($empBnk['transactionAmount'], $empBnk['dPlace']) . '</td>
                            <td><div align="center">'.$btn.'</div></td>
                        </tr>';
                        $j++;
                        $n++;
                        $totCurrency += number_format($empBnk['transactionAmount'], $empBnk['dPlace'], '.', '');

                        $totl = $this->lang->line('common_total');

                        if( array_key_exists($j, $empWithoutBank) ) {
                            if( $empWithoutBank[$j]['transactionCurrency'] != $lastCurrency && $n > 1) {
                                echo '<tr> <th colspan="4" style="font-size: 12px !important;"><div align="right"> '.$totl.'<!--Total--></div></th>
                                     <th style="font-size: 12px !important;"><div align="right">' . number_format($totCurrency, $empBnk['dPlace']) . '</div></th>
                                     <th>&nbsp;</th>
                                 </tr>';

                            }
                        }
                        else{

                            if( $n > 1 ){
                                echo '<tr> <th colspan="4" style="font-size: 12px !important;"><div align="right">'.$totl.'<!--Total--></div></th>
                                    <th style="font-size: 12px !important;"><div align="right">'.number_format($totCurrency, $empBnk['dPlace']).'</div></th>
                                    <th>&nbsp;</th>
                                  </tr>';
                            }
                        }

                    }
                }
                else{
                    echo '<tr> <td colspan="6">'.$no_data.'<!--No data available in table--></td> </tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane"  id="empNoBank_processedTab" > <!-- /.tab-pane -->
            <div class="pull-right" style="margin: 1%; margin-right: 0px;">
                <a href="<?php echo site_url('Template_paysheet/print_empNonBankPay').'/'.$payrollID.'/'.$isNonPayroll; ?>" target="_blank">
                    <button class="btn btn-primary btn-sm" style="/*font-size: 12px*/"><?php echo $this->lang->line('common_print');?><!--Print--></button>
                </a>
            </div>
            <table class="<?php echo table_class(); ?>"  id="empNoBankTB">
                <thead>
                <tr>
                    <th style="width: auto"> # </th>
                    <th style="width: auto"> <?php echo $this->lang->line('hrms_payroll_emp_id');?><!--EMP ID--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('common_name');?><!--Name--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('hrms_payroll_paid_by');?><!--Paid By--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('hrms_payroll_bank_name');?><!--Bank Name--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('hrms_payroll_cheque_no');?><!--Cheque No--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('common_currency');?><!--Currency--> </th>
                    <th style="width: auto"> <?php echo $this->lang->line('common_amount');?><!--Amount--> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $j = 0;  $n = 0; $totCurrency = 0;
                $lastCurrency = null;

                if( !empty($empWithoutBank_paid)){
                    foreach($empWithoutBank_paid as $empBnk){
                        $trCurrency = $empBnk['transactionCurrency'];
                        $paidBy = ( $empBnk['payByBankID'] == null ) ? 'Cash' : 'Cheque';
                        $bankName = ( $empBnk['payByBankID'] != null ) ? $empBnk['bankName'] : '-';
                        $chequeNo = ( $empBnk['payByBankID'] != null ) ? $empBnk['chequeNo'] : '-';

                        if($lastCurrency != $trCurrency){
                            echo '<tr > <th colspan="8" style="font-size: 12px">'.$carcy.' : '.$trCurrency.'</th></tr>' ;
                            $lastCurrency = $trCurrency;
                            $n = 0; $totCurrency = 0;
                        }

                        echo
                            '<tr id="TR_'.$j.'">
                            <td>' . ($j+1) . '</td>
                            <td>' . $empBnk['ECode'] . '</td>
                            <td>' . $empBnk['empName'] . '</td>
                            <td align="center">' . $paidBy . '</td>
                            <td>' . $bankName . '</td>
                            <td>' . $chequeNo . '</td>
                            <td align="center">' . $trCurrency . '</td>
                            <td align="right">' . number_format($empBnk['transactionAmount'], $empBnk['dPlace']) . '</td>
                        </tr>';
                        $j++;
                        $n++;
                        $totCurrency += number_format($empBnk['transactionAmount'], $empBnk['dPlace'], '.', '');

                        if( array_key_exists($j, $empWithoutBank_paid) ) {
                            if( $empWithoutBank_paid[$j]['transactionCurrency'] != $lastCurrency && $n > 1) {
                                echo '<tr> <th colspan="7" style="font-size: 12px !important;"><div align="right">'.$totl.'<!--Total--></div></th>
                                     <th style="font-size: 12px !important;"><div align="right">' . number_format($totCurrency, $empBnk['dPlace']) . '</div></th>
                                 </tr>';

                            }
                        }
                        else{
                            if( $n > 1 ){
                                echo '<tr> <th colspan="7" style="font-size: 12px !important;"><div align="right">'.$totl.'<!--Total--></div></th>
                                        <th style="font-size: 12px !important;"><div align="right">'.number_format($totCurrency, $empBnk['dPlace']).'</div></th>
                                      </tr>';
                            }
                        }

                    }
                }
                else{
                    echo '<tr> <td colspan="8">'.$no_data.'<!--No data available in table--></td> </tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(ev){
            $(this).datepicker('hide');
        });


        var isPending = "<?php echo $isPending; ?>";
        if( isPending == 'N'){
            $('.nav2 li:eq(1) a').tab('show');
        }

    });

    function payEmpSalary(obj, row, empID){
        $('.empNotBankPay_Btn:not(#'+obj.id+')').attr('data-value' , 0);

        var appendTR = $('.appendTR');
        if( obj.getAttribute("data-value") != 1 ) {
            obj.setAttribute("data-value", 1);
            var tr = $('#TR_' + row);
            appendTR.addClass('removeTR');
            appendTR.fadeOut('slow');


            var formData = '<div class="row divScroll" style="display: none;padding : 1% 4%">';/*padding*/
            formData += '<form id="frm_empNonBankPay">';
            formData += '<div class="col-md-2">';
            formData += '<div class="form-group cols-sm-3">';
            formData += '<label for="payType" class="cols-sm-3">  <?php echo $this->lang->line('hrms_payroll_pay_type');?>   </label>';<!--Pay Type-->
            formData += '<select name="payType" class="form-control payTDInputs cols-sm-3" id="payType" onchange="isCheque(this)" required="">';
            formData += '<option value="By Cash"><?php echo $this->lang->line('common_by_cash');?> </option>';<!--By Cash-->
            formData += '<option value="By Cheque"><?php echo $this->lang->line('hrms_payroll_pay_cheque');?> </option>';<!--By Cheque-->
            formData += '</select> </div></div>';

            formData += '<div class="col-md-3">';
            formData += '<div class="form-group cols-sm-3">';
            formData += '<label for="payType" class="cols-sm-3"><?php echo $this->lang->line('common_bank');?></label>';<!--Bank-->
            formData += '<div class="bankTD"> </div></div></div>';

            formData += '<div class="col-md-3">';
            formData += '<div class="form-group cols-sm-3">';
            formData += '<label for="payType" class="cols-sm-3"><?php echo $this->lang->line('hrms_payroll_cheque_no');?></label>';<!--Cheque No-->
            formData += '<input type="text" name="chequeNo" class="form-control payTDInputs cols-sm-3 chequeInput" id="chequeNo" style="padding-left: 10px" disabled>';
            formData += '</div></div>';

            formData += '<div class="col-md-2">';
            formData += '<div class="form-group cols-sm-3">';
            formData += '<label for="processingDate" class="cols-sm-3"><?php echo $this->lang->line('hrms_payroll_processing_date');?></label>';<!--Processing date-->
            formData += '<div class="input-group cols-sm-3">';
            formData += '<div class="input-group-addon" style="padding: 3px 7px;"><i class="fa fa-calendar"  style="font-size: 10px"></i></div>';
            formData += '<input type="text" name="paymentDate" value="<?php echo date('Y-m-d')?>" id="paymentDate" class="form-control datepicker payTDInputs cols-sm-3">';
            formData += '</div></div></div>';

            formData += '<div class="col-md-2">';
            formData += '<div class="form-group cols-sm-3">';
            formData += '<label for="payType" class="cols-sm-3">&nbsp;</label> <br>';
            formData += '<input type="button" class="btn btn-primary btn-xs cols-sm-2" value="<?php echo $this->lang->line('common_save');?>" style="min-width: 60px" onclick="save_empNonBankPay()">';
            formData += '<input type="hidden" name="hidden_empID" value="' + empID + '">';
            formData += '<input type="hidden" name="hidden_payrollID" value="<?php echo $payrollID ?>">';
            formData += '</div>';
            formData += '</div>';

            formData += '</form >';
            formData += '</div>';

            /*var formData = '<form >';
             formData += '<table style="border: 0px solid; margin-top: 3px">';
             formData += '<tr>';
             formData += '<td style="border: 0px solid"><label for="payType" class="" >Pay Type</label></td>';
             formData += '<td style="border: 0px solid">';
             formData += '<select name="payType" class="form-control payType payTDInputs" id="payType" style="" onchange="" required="">';
             formData += '<option value="By Cash">By Cash</option>';
             formData += '<option value="By Cheque">By Cheque</option>';
             formData += '</td>';
             formData += '<td style="border: 0px solid"><label for="processingDate" class="cols-sm-2">Processing date</label></td>';
             formData += '<td style="border: 0px solid">';
             formData += '<td style="border: 0px solid" class="bankTD"></td>';
             formData += '<div class="input-group">';
             formData += '<div class="input-group-addon" style="padding: 3px 7px;"><i class="fa fa-calendar" style="font-size: 10px;"></i></div>';
             formData += '<input type="text" name="paymentDate" value="" id="paymentDate" class="form-control datepicker payTDInputs" style="position: initial;">';
             formData += '</div>';
             formData += '</td>';
             formData += '<td align="center" style="border: 0px solid">';
             formData += '<button class="btn btn-primary btn-xs" style="font-size: 10px;min-width: 60px"> Save </button>';
             formData += '</td>';
             formData += '</tr>';
             formData += '<table>';
             formData += '</form >';*/

            tr.after('<tr class="appendTR" style="background: #e3ebf1"><td colspan="6">' + formData + '</td></tr>');
            $('#getClone_selectBox').clone().attr({
                'name': 'empPayBank',
                'class': 'empPayBank form-control payTDInputs cols-sm-3 chequeInput',
                'id': 'empPayBank',
                'style': 'width: 100%; display:block',
                'disabled': 'disabled'
            }).appendTo('.bankTD');


            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
            $('.divScroll').slideDown('slow');
            setTimeout(function () {
                $('.removeTR').remove();
            }, 600);
        }
        else{
            appendTR.hide().fadeIn();
        }
        return false;
    }

    function save_empNonBankPay(){
        var formData = $('#frm_empNonBankPay').serializeArray();
        formData.push({'name':'isNonPayroll', 'value':'<?php echo $isNonPayroll;?>'});

        $.ajax({
            async: false,
            type: 'post',
            dataType: 'json',
            data: formData,
            url: "<?php echo site_url('Template_paysheet/save_empNonBankPay'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if( data[0] == 's'){
                    $('#emp_withoutBank').click();
                }
            },
            error : function() {
                stopLoad();
                myAlert('e','An Error Occurred! Please Try Again.');
                return 'e';
            }
        });
    }

    function isCheque(obj){
        if(obj.value == 'By Cheque'){
            $('.chequeInput').removeAttr('disabled');
        }
        else{
            $('.chequeInput').attr('disabled', 'disabled');
        }
    }
</script>

<?php
/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 2016-08-16
 * Time: 1:56 PM
 */