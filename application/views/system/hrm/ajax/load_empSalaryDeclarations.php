<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('employee_master', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>
<style>
    fieldset {
        border: 1px solid silver;
        border-radius: 5px;
        padding: 1%;
        padding-bottom: 15px;
        margin: auto;
    }

    legend {
        width: auto;
        border-bottom: none;
        margin: 0px 10px;
        font-size: 20px;
        font-weight: 500
    }

    .right-align{ text-align: right; }

    .more-info-btn{
        border-radius: 0px;
        font-size: 11px;
        line-height: 1.5;
        padding: 1px 7px;
    }
</style>

<div class="row">
    <?php
    if($access != 1){
        $this->lang->load('hrms_reports', $primaryLanguage);
        echo '<div class="col-sm-12">
                <div class="alert alert-warning">
                    <strong>'.$this->lang->line('hrms_reports_warning').'!</strong></br>
                    '.$this->lang->line('hrms_reports_no_rights').'
                </div>
              </div>';
        die();
    }
    ?>
    <div class="col-sm-6">
        <fieldset>
            <legend><?php echo $this->lang->line('emp_bank_payroll');?><!--Payroll--></legend>
            <div class="box box-solid">
                <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                    <h3 class="box-title"><?php echo $this->lang->line('emp_salary_additions');?><!--Additions--> </h3>
                    <button type="button" class="btn btn-primary btn-xs pull-right navdisabl " onclick="fetchSalaryDeclarationHistory('N')">
                        <i class="fa fa-bars"></i> <?php echo $this->lang->line('emp_salary_detail_salary');?>
                    </button>
                </div>
                <div class="box-body declarationAddition" style="padding: 0px">
                    <table class="table table-bordered" id="add_declarationTB">
                        <thead>
                        <tr>
                            <th> <?php echo $this->lang->line('emp_description');?><!--Description--></th>
                            <th> <?php echo $this->lang->line('common_currency');?><!--Currency--></th>
                            <th> <?php echo $this->lang->line('emp_salary_amount');?><!--Amount--> <span class="pull-right empCurrencyDis"></span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $totAdd = 0;
                        if( !empty($groupBYSalary) ){
                            foreach($groupBYSalary as $keyAdd=>$rowAdd){
                                if($rowAdd->salaryCategoryType == 'A'){
                                    echo '<tr>
                                    <td>'.$rowAdd->salaryDescription.'</td>
                                    <td>'.$rowAdd->transactionCurrency.'</td>
                                    <td align="right">'.number_format( $rowAdd->amount, $dPlaces ).'</td>
                                  </tr>';
                                    $totAdd += round( $rowAdd->amount, $dPlaces);
                                }
                            }
                        }
                        ?>
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="2" align="right"><?php echo $this->lang->line('emp_salary_total');?><!--Total--></td>
                            <td align="right"><?php echo number_format( $totAdd, $dPlaces ) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                    <h3 class="box-title"><?php echo $this->lang->line('emp_salary_deductions');?><!--Deductions--></h3>
                </div>

                <div class="box-body declarationDeduction" style="padding: 0px">
                    <table class="table table-bordered" id="deduct_declarationTB">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('emp_description');?><!-- Description--></th>
                            <th><?php echo $this->lang->line('common_currency');?> <!--Currency--></th>
                            <th><?php echo $this->lang->line('emp_salary_amount');?> <!--Amount--> <span class="pull-right empCurrencyDis"></span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                        $totDeductions = 0;
                        if( !empty($groupBYSalary) ){
                            foreach($groupBYSalary as $keyAdd=>$rowAdd){
                                if($rowAdd->salaryCategoryType == 'D'){
                                    echo '<tr>
                                <td>'.$rowAdd->salaryDescription.'</td>
                                <td>'.$rowAdd->transactionCurrency.'</td>
                                <td align="right">'.number_format( $rowAdd->amount, $dPlaces).'</td>
                              </tr>';

                                    $totDeductions += round( $rowAdd->amount, $dPlaces );
                                }
                            }
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" align="right"><?php echo $this->lang->line('emp_salary_total');?><!--Total--></td>
                            <td align="right"><?php echo number_format( $totDeductions, $dPlaces) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                    <h3 class="box-title"><?php echo $this->lang->line('emp_salary_net_salary');?><!--Net Salary--></h3>
                    <div class="box-tools pull-right">
                        <div class="box-title" id="netSalary" style="font-size: 18px; margin-top: 7px;">
                            <?php echo number_format( ($totAdd+$totDeductions) , $dPlaces) ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-sm-6">
        <fieldset>
            <legend><?php echo $this->lang->line('emp_bank_non_payroll');?><!--Non Payroll--></legend>

            <div class="box box-solid">
                <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                    <h3 class="box-title"><?php echo $this->lang->line('emp_salary_additions');?><!--Additions--> </h3>
                    <button type="button" class="btn btn-primary btn-xs pull-right navdisabl " onclick="fetchSalaryDeclarationHistory('Y')">
                        <i class="fa fa-bars"></i> <?php echo $this->lang->line('emp_salary_detail_salary');?>
                    </button>
                </div>
                <div class="box-body declarationAddition" style="padding: 0px">
                    <table class="table table-bordered" id="add_declarationTB">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('emp_description');?> <!--Description--></th>
                            <th><?php echo $this->lang->line('common_currency');?> <!--Currency--></th>
                            <th><?php echo $this->lang->line('emp_salary_amount');?> <!--Amount--> <span class="pull-right empCurrencyDis"></span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $grandTot = round( ($totAdd+$totDeductions) , $dPlaces);
                        $totAdd = 0;
                        if( !empty($salaryDetNon) ){
                            foreach($salaryDetNon as $keyAdd=>$rowAdd){
                                if($rowAdd->salaryCategoryType == 'A'){
                                    echo '<tr>
                                    <td>'.$rowAdd->salaryDescription.'</td>
                                    <td>'.$rowAdd->transactionCurrency.'</td>
                                    <td align="right">'.number_format( $rowAdd->amount, $dPlaces ).'</td>
                                  </tr>';
                                    $totAdd += round( $rowAdd->amount, $dPlaces);
                                }
                            }
                        }
                        ?>
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="2" align="right"><?php echo $this->lang->line('emp_salary_total');?><!--Total--></td>
                            <td align="right"><?php echo number_format( $totAdd, $dPlaces ) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                    <h3 class="box-title"><?php echo $this->lang->line('emp_salary_deductions');?><!--Deductions--></h3>
                </div>

                <div class="box-body declarationDeduction" style="padding: 0px">
                    <table class="table table-bordered" id="deduct_declarationTB">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('emp_description');?> <!--Description--></th>
                            <th><?php echo $this->lang->line('common_currency');?> <!--Effective Date--></th>
                            <th><?php echo $this->lang->line('emp_salary_amount');?> <!--Amount--> <span class="pull-right empCurrencyDis"></span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                        $totDeductions = 0;
                        if( !empty($salaryDetNon) ){
                            foreach($salaryDetNon as $keyAdd=>$rowAdd){
                                if($rowAdd->salaryCategoryType == 'D'){
                                    echo '<tr>
                                <td>'.$rowAdd->salaryDescription.'</td>
                                <td>'.$rowAdd->transactionCurrency.'</td>
                                <td align="right">'.number_format( $rowAdd->amount, $dPlaces).'</td>
                              </tr>';

                                    $totDeductions += round( $rowAdd->amount, $dPlaces );
                                }
                            }
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" align="right"><?php echo $this->lang->line('emp_salary_total');?><!--Total--></td>
                            <td align="right"><?php echo number_format( $totDeductions, $dPlaces) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                    <h3 class="box-title"><?php echo $this->lang->line('emp_salary_net_salary');?><!--Net Salary--></h3>
                    <div class="box-tools pull-right">
                        <div class="box-title" id="netSalary" style="font-size: 18px; margin-top: 7px;">
                            <?php echo number_format( ($totAdd+$totDeductions) , $dPlaces) ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div style="height: 2%">&nbsp;</div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-solid">
            <div class="box-header with-border" style="border-top: 1px solid #f4f4f4">
                <h3 class="box-title"><?php echo $this->lang->line('emp_salary_grand_total');?> </h3>
                <div class="box-tools pull-right">
                    <div class="box-title" id="netSalary" style="font-size: 18px; margin-top: 7px;">
                        <?php
                        $grandTot += round( ($totAdd+$totDeductions) , $dPlaces);
                        echo number_format( $grandTot, $dPlaces) ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <fieldset>
            <legend><?php echo $this->lang->line('common_variable_pay_declarations');?></legend>

            <table class="<?php echo table_class() ?> drill-table" >
                <thead>
                <tr>
                    <th style="width: 30px"> # </th>
                    <th style="width: 120px"><?php echo $this->lang->line('common_document_code');?></th>
                    <th style=""> <?php echo $this->lang->line('common_category');?> </th>
                    <th style="width: 105px; "> <?php echo $this->lang->line('common_effective_date');?> </th>
                    <th style="width: 110px"> <?php echo $this->lang->line('common_amount');?> </th>
                    <th style=""> <?php echo $this->lang->line('common_narration');?> </th>
                    <th style="width: 40px"> </th>
                </tr>
                </thead>

                <tbody>
                <?php
                if(!empty($vpDeclarations)){
                    $dPlace = $vpDeclarations[0]['trCurrencyDPlaces'];
                    $i = 1;
                    foreach ($vpDeclarations as $key=>$det){
                        echo '<tr>
                                <td class="right-align">'.$i.'</td>                                           
                                <td >'.$det['documentCode'].'</td>
                                <td >'.$det['salaryDescription'].'</td>
                                <td >'.$det['effectiveDate'].'</td>
                                <td class="right-align">'.number_format($det['amount'], $dPlace).'</td>       
                                <td class=""> '.$det['narration'].'</td>  
                                <td class="right-align">                                
                                    <button class="btn btn-default btn-xs more-info-btn" type="button" 
                                        onclick="load_vp_history(\''.$det['salaryCategoryID'].'\')" rel="tooltip" title="History">
                                        <i class="fa fa-info" aria-hidden="true" style="color: #1b1b1b"></i>
                                    </button>
                                </td>                                                                        
                              </tr>';
                        $i++;
                    }
                }
                else{
                    $no_record_found = $this->lang->line('common_no_records_found');
                    echo '<tr><td colspan="7" align="center">'.$no_record_found.'</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>

<div class="modal fade" id="salaryDeclarationHistory" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <?php echo $this->lang->line('emp_salary_salary_declaration_detail');?><!--Salary Declaration Detail-->
                </h3>
            </div>
            <div role="form" id="" class="form-horizontal" autocomplete="off">
                <div class="modal-body">
                    <div style="margin-top: 3%">
                        <table class="<?php echo table_class(); ?>" id="salaryDeclarationHistoryTable">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th><?php echo $this->lang->line('emp_description');?> <!--Description--></th>
                                <th><?php echo $this->lang->line('emp_salary_amount');?> <!--Amount--> </th>
                                <th> <?php echo $this->lang->line('emp_salary_effective_date');?><!--Effective Date--></th>
                                <th> <?php echo $this->lang->line('emp_pay_date');?><!--Pay Date--></th>
                                <th> <?php echo $this->lang->line('common_code');?><!--Code--></th>
                                <th> <?php echo $this->lang->line('emp_salary_comment');?><!--Comment--></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('emp_Close');?><!--Close--></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="vpDeclarationHistory" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <?php echo $this->lang->line('common_variable_pay_declarations_history');?>
                </h3>
            </div>
            <div role="form" id="" class="form-horizontal">
                <div class="modal-body">
                    <div style="margin-top: 3%">
                        <table class="<?php echo table_class(); ?>" id="vpDeclarationHistoryTable">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 120px"><?php echo $this->lang->line('common_document_code');?></th>
                                <th style=""> <?php echo $this->lang->line('common_category');?> </th>
                                <th style="width: 105px; "> <?php echo $this->lang->line('common_effective_date');?> </th>
                                <th style="width: 110px"> <?php echo $this->lang->line('common_amount');?> </th>
                                <th style=""> <?php echo $this->lang->line('common_narration');?> </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('emp_Close');?><!--Close--></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fetchSalaryDeclarationHistory(isNonPayroll) {
        $('#salaryDeclarationHistory').modal({backdrop: 'static'});

        $('#salaryDeclarationHistoryTable').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Employee/fetch_empSalaryDeclaration'); ?>",
            "aaSorting": [[0, 'desc']],
            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                if (oSettings.bSorted || oSettings.bFiltered) {

                    var x = 0;
                    for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                        x++;
                    }
                }
            },
            "aoColumns": [// salaryDescription, amount, effectiveDate, narration
                {"mData": "id"},
                {"mData": "salaryDescription"},
                {"mData": "amountTrAlign"},
                {"mData": "effectiveDateStr"},
                {"mData": "payDateStr"},
                {"mData": "documentSystemCode"},
                {"mData": "narration"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "empId",
                    "value": <?php echo json_encode(trim($this->input->post('empID'))); ?>
                });
                aoData.push({
                    "name": "isNonPayroll",
                    "value": isNonPayroll
                });
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    if(fromHiarachy == 1){
        $('.btn ').addClass('hidden');
        $('.navdisabl ').removeClass('hidden');
    }

    function load_vp_history(catID){
        $('#vpDeclarationHistory').modal({backdrop: 'static'});

        $('#vpDeclarationHistoryTable').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Employee/fetch_empVariablePayDeclaration'); ?>",
            "aaSorting": [[1, 'desc']],
            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                if (oSettings.bSorted || oSettings.bFiltered) {

                    var x = 0;
                    for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                        x++;
                    }
                }
            },
            "aoColumns": [
                {"mData": "id"},
                {"mData": "documentSystemCode"},
                {"mData": "salDec"},
                {"mData": "effectiveDate"},
                {"mData": "amountTrAlign"},
                {"mData": "narration"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({ "name": "empID", "value": <?php echo json_encode(trim($this->input->post('empID'))); ?> });
                aoData.push({ "name": "catID", "value": catID });

                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }
</script>


<?php
/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 2017-03-20
 * Time: 11:50 AM
 */