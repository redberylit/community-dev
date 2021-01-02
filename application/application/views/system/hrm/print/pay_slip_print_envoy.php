<div style="height: 0.7cm;"> &nbsp; </div>
<?php
$date = $masterData['payrollYear'] . "-" . $masterData['payrollMonth'] . "-01";
$pay_slip_month = date('F Y', strtotime($date));
?>
<table class="envoy-payslip-tbl" style="width: 90%; font-size: 12px; font-weight: bolder;" border="1">
    <tbody>
    <tr>
        <th style="text-align: right; border:1px solid;border-bottom: none;" colspan="2" class="dot_matrix_font">
            PAY SLIP
        </th>
    </tr>
    <tr>
        <th style="text-align: right; border-left:1px solid; border-right: 1px solid; border-top: none; height: 50px" colspan="2" valign="bottom" class="dot_matrix_font">
            Month : <?php echo $pay_slip_month; ?>
        </th>
    </tr>
    <tr>
        <th style="border-right: none" class="dot_matrix_font">
            Employee Name : <?php echo $details['headerDet']['empName']; ?>
        </th>
        <th style="text-align: right; border-left: none" class="dot_matrix_font">
            Employee No : <?php echo $details['headerDet']['secondaryCode']; ?>
        </th>
    </tr>
    </tbody>
</table>

<table class="envoy-payslip-tbl" style="width:90%; height: 80% !important;">
    <tr>
        <td style="border-left:1px solid; border-right: 1px solid; height: 30px; width:50%">&nbsp;</td>
        <td style="border-left:1px solid; border-right: 1px solid; width:50%">&nbsp;</td>
    </tr>
    <tr>
        <td style="width:50%; vertical-align: top; padding-right:1px; border: 1px solid; border-top: none;">
            <table class="" style="width: 100%;  height: 80%">
                <tbody>
                <?php
                $addTot = 0;
                $dedTot = 0;
                $dedCount = 0;
                $default_dPlace = $details['headerDet']['dPlace'];

                //Fixed salary Additions
                foreach ($details['salaryDec_A'] as $salDec) {
                    $amount = number_format($salDec['transactionAmount'], $default_dPlace);

                    $otHours = '';
                    if($salDec['fromTB'] == 'OT' && !empty($details['OT_data'])){
                        foreach ($details['OT_data'] as $otDec) {
                            if($salDec['salCatID']==$otDec['salCatID']){
                                //$otHours = $details['OT_data'];
                                $otHours = (strlen($salDec['salaryDescription']) > 10)? ' <br/> &nbsp; ('.$otDec['otHour'].')': ' &nbsp; ('.$otDec['otHour'].')';

                                echo '<tr>
                            <th class="dot_matrix_font">' . $salDec['salaryDescription'] . ' '.$otHours.'</th>
                            <th class="paySheetDet_TD dot_matrix_font" align="right">' . $amount . '</th>
                          </tr>';
                            }
                        }
                    }else{
                        echo '<tr>
                            <th class="dot_matrix_font">' . $salDec['salaryDescription'] . ' '.$otHours.'</th>
                            <th class="paySheetDet_TD dot_matrix_font" align="right">' . $amount . '</th>
                          </tr>';
                    }



                    $addTot += number_format($salDec['transactionAmount'], $default_dPlace, '.', ''); //$salDec['dPlace'],
                }

                //Monthly Additions
                if (!empty($details['monthAdd'])) {
                    foreach ($details['monthAdd'] as $monthAdd) {
                        echo '<tr>
                                <th class="dot_matrix_font">' . $monthAdd['description'] . '</th>
                                <th class="paySheetDet_TD dot_matrix_font" align="right"> ' . number_format($monthAdd['transactionAmount'], $default_dPlace) . ' </th>
                              </tr>';

                        $addTot += number_format($monthAdd['transactionAmount'], $default_dPlace, '.', ''); //$monthAdd['dPlace']
                    }
                }

                $otherData = $details['employerContributions'];

                echo '<tr>
                        <th><div style="font-size: 12px"> &nbsp;</div></th>
                        <th align="right" class="pull-right sub_total"><div style="font-size: 12px;" class="dot_matrix_font">' . number_format($addTot, $default_dPlace) . '</div></th>
                      </tr>
                      <tr><th style="height: 25px" colspan="2">&nbsp;</th></tr>
                      <tr><th class="dot_matrix_font">EPF - Employer</th><th class="paySheetDet_TD dot_matrix_font" align="right">'.number_format(abs($otherData[6]), $default_dPlace, '.', '').'</th></tr>
                      <tr><th class="dot_matrix_font">ETF - Employer</th><th class="paySheetDet_TD dot_matrix_font" align="right">'.number_format(abs($otherData[18]), $default_dPlace, '.', '').'</th></tr>
                      <tr><th class="dot_matrix_font">EPF - Total</th><th class="paySheetDet_TD dot_matrix_font" align="right">'.number_format(abs($otherData[6]+$otherData[7]), $default_dPlace, '.', '').'</th></tr>';
                ?>
                </tbody>
            </table>
        </td>
        <td style="width:50%; vertical-align: top; padding-right:5px; border: 1px solid; border-top: none;">
            <table class="" style="width: 100%; height: 80%">
                <tbody>
                <tr> <th colspan="2"><span style="font-size: 12px" class="dot_matrix_font">Deduction as Direct</span></th> </tr>
                <?php

                $dedTot = 0;
                $dedCount = 0;
                $default_dPlace = $details['headerDet']['dPlace'];

                //SSO Payee
                if (!empty($details['sso_payee'])) {
                    foreach ($details['sso_payee'] as $sso_payee) {
                        echo '<tr>
                                <th class="dot_matrix_font">' . $sso_payee['description'] . '</th>
                                <th class="paySheetDet_TD dot_matrix_font" align="right"> ' . number_format($sso_payee['transactionAmount'], $default_dPlace) . ' </th>
                              </tr>';

                        $dedTot += number_format($sso_payee['transactionAmount'], $default_dPlace, '.', ''); //$sso_payee['dPlace'],
                        $dedCount++;
                    }
                }

                //Fixed Salary Deduction
                if (!empty($details['salaryDec_D'])) {
                    foreach ($details['salaryDec_D'] as $salDec) {
                        $amount = number_format($salDec['transactionAmount'], $default_dPlace); //$salDec['dPlace']
                        echo '<tr>
                                <th class="dot_matrix_font">' . $salDec['salaryDescription'] . '</th>
                                <th class="paySheetDet_TD dot_matrix_font" align="right">' . $amount . '</th>
                              </tr>';

                        $dedTot += number_format($salDec['transactionAmount'], $default_dPlace, '.', '');
                        $dedCount++;
                    }
                }

                //Monthly Deduction
                if (!empty($details['monthDec'])) {
                    foreach ($details['monthDec'] as $monthDed) {
                        echo '<tr>
                                <th class="dot_matrix_font">' . $monthDed['description'] . '</th>
                                <th class="paySheetDet_TD dot_matrix_font" align="right"> ' . number_format($monthDed['transactionAmount'], $default_dPlace) . ' </th>
                              </tr>';

                        $dedTot += number_format($monthDed['transactionAmount'], $default_dPlace, '.', ''); //$monthDed['dPlace'],
                        $dedCount++;
                    }
                }

                //Loan Deduction
                if (!empty($details['loanDed'])) {
                    foreach ($details['loanDed'] as $loanDed) {
                        echo '<tr>
                                <th class="dot_matrix_font">
                                    ' . $loanDed['loanDescription'] . ' [ ' . $loanDed['loanCode'] . ' | Installment No : ' .$loanDed['installmentNo'] . ' ]
                                    </th>
                                <th class="paySheetDet_TD dot_matrix_font" align="right"> ' . number_format($loanDed['transactionAmount'], $default_dPlace) . ' </th>
                              </tr>';

                        $dedTot += number_format($loanDed['transactionAmount'], $default_dPlace, '.', ''); //$default_dPlace
                        $dedCount++;
                    }
                }


                if ($dedCount == 0) {
                    echo '<tr> <td>-</td> <td align="right">-</td> </tr>';
                }

                echo '<tr>
                        <th><div style="font-size: 12px" class="dot_matrix_font"> Total Deductions </div></th>
                        <th align="right" class="pull-right sub_total dot_matrix_font"><div style="font-size: 12px;">' . number_format($dedTot, $default_dPlace) . '</div></th>
                      </tr>';

                echo '<tr>
                        <th style="height: 60px"><div style="font-size: 12px" class="dot_matrix_font"> Net Remuneration </div></th>
                        <th style="height: 60px" class=""><div style="font-size: 12px; text-align=right" class="dot_matrix_font">' . number_format($addTot + $dedTot, $default_dPlace) . '</div></th>
                      </tr>';
                ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<script>
    $('.review').removeClass('hidden');
    a_link = "<?php echo site_url('Template_paysheet/get_paySlip_reports_pdf'); ?>/<?php echo $payrollMasterID ?>/<?php echo $empID ?>/<?php echo $isNonPayroll ?>";
    $("#a_link").attr("href", a_link);
</script>

<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 11/24/2017
 * Time: 11:53 AM
 */