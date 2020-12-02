<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('sales_marketing_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

if ($details) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('salesOrderReport', 'Sales Person Summary', false, false);
            } ?>
        </div>
    </div>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="salesOrderReport">
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong>Sales Person Performance</strong></div>
            <br>
            <div style="">
                <table id="tbl_rpt_salesreturn" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>
                        <th rowspan="2">Customer</th>
                        <th rowspan="2">Currency</th>
                        <th colspan="3">Contract/Sales Order</th>
                        <th colspan="3">Invoiced</th>
                        <th rowspan="2">Balance</th>
                    <tr>
                        <th>Doc Num</th>
                        <th style="width: 9%">Date</th>
                        <th>Value</th>
                        <th>Doc Num</th>
                        <th style="width: 9%">Date</th>
                        <th>Value</th>
                    </tr>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($details) {
                        $contractnametra = [];

                        if($currency==1){
                            $details = array_group_by($details, 'contractAutoID');
                        }elseif($currency==2){
                            $details = array_group_by($details, 'contractAutoID');
                        }else{
                            $details = array_group_by($details, 'contractAutoID');
                        }


                        foreach ($details as $value) {
                            $salesOrder = 0;
                            $invoice = 0;
                            $receipt = 0;
                            $returnamt = 0;
                            $creditamount = 0;
                            $receiptamount = 0;
                            $credittot = 0;
                            $creditnettot = 0;
                            $bal = 0;
                            $balttot = 0;
                            $decimalPlace = 2;
                            $amountcontract = 0;
                            $amountinvoiced = 0;
                            $decimalPlacescontract = 2;
                            $decimalPlacescinvoiced  = 2;
                            $blance_value_total = 0;
                            $contract_value_total = 0;
                            $dataarr = array();
                            foreach ($value as $val) {
                                $companyid = current_companyID();
                                $date_format_policy = date_format_policy();
                                $datefromc = $datefrom;
                                $datetoc = $dateto;
                                $datefromconvert = input_format_date($datefromc, $date_format_policy);
                                $datetoconvert = input_format_date($datetoc, $date_format_policy);
                                $date = "";
                                $datecontract = "";
                                if (!empty($datefromc) && !empty($datetoc)) {
                                    $date .= " AND ( invoiceDate >= '" . $datefromconvert . " 00:00:00' AND invoiceDate <= '" . $datetoconvert . " 00:00:00')";
                                }
                                if (!empty($datefromc) && !empty($datetoc)) {
                                    $datecontract .= " AND ( contractDate >= '" . $datefromconvert . " 00:00:00' AND contractDate <= '" . $datetoconvert . " 00:00:00')";
                                }



                                if($currency==1){
                                    $amountcontract = $val['contractmastertransactionamount'];
                                    $amountinvoiced =$val['invoicetransactionamount'];
                                    $decimalPlacescontract =$val['contracttransactionCurrencyDecimalPlaces'];
                                    $decimalPlacescinvoiced =$val['invoicetransactionCurrencyDecimalPlaces'];
                                    $curr= ($val['transactionCurrency']);
                                }elseif($currency==2){
                                    $amountcontract =$val['contractmasterlocalexchange'];
                                    $amountinvoiced =$val['invoicelocalmamount'];
                                    $decimalPlacescontract  =$val['contractLocalCurrencyDecimalPlaces'];
                                    $decimalPlacescinvoiced  =$val['invoicecompanyLocalCurrencyDecimalPlaces'];
                                    $curr= ($val['companyLocalCurrency']);
                                }else{
                                    $amountcontract =$val['contractmasterreportingexchange'];
                                    $amountinvoiced =$val['invoicereportingamount'];
                                    $decimalPlacescontract =$val['contractReportingCurrencyDecimalPlaces'];
                                    $decimalPlacescinvoiced =$val['invoicecompanyReportingCurrencyDecimalPlaces'];
                                    $curr= ($val['companyReportingCurrency']);
                                }

                                ?>

                                <?php if(in_array($val['contractAutoID'],$dataarr)) {?>
                                    <tr>
                                        <td width="200px"> </td>
                                        <td width="200px"> </td>
                                        <td width="200px"> </td>
                                        <td> </td>
                                        <td width="200px" style="text-align: right"> </td>


                                        <td><a href="#" class="drill-down-cursor"
                                               onclick="documentPageView_modal('<?php echo $val["invoicedocid"] ?>',<?php echo $val["invoiceAutoID"] ?>)"><?php echo $val["docsyscodeinvoice"] ?></a> </td>
                                        <td width="200px"><?php echo $val['docdateinvoice'] ?></td>
                                        <td width="200px" style="text-align: right"><?php echo number_format($amountinvoiced,$decimalPlacescinvoiced) ?></td>
                                        <td width="200px" style="text-align: right"> </td>

                                    </tr>
                             <?php  }else {
                                    array_push($dataarr,$val['contractAutoID']); ?>
                                    <tr>
                                        <td width="200px"><?php echo $val["customerName"] ?></td>
                                        <td width="200px"><?php echo $curr?> </td>
                                        <td><a href="#" class="drill-down-cursor"
                                               onclick="documentPageView_modal('<?php echo $val["contractdocid"] ?>',<?php echo $val["contractAutoID"] ?>)"><?php echo $val["docsyscodecontract"] ?></a> </td>
                                        <td width="200px"><?php echo $val["docdatecontract"] ?></td>

                                        <td width="200px" style="text-align: right"><?php echo number_format($amountcontract,$decimalPlacescontract) ?></td>

                                        <td><a href="#" class="drill-down-cursor"
                                               onclick="documentPageView_modal('<?php echo $val["invoicedocid"] ?>',<?php echo $val["invoiceAutoID"] ?>)"><?php echo $val["docsyscodeinvoice"] ?></a> </td>
                                        <td width="200px"><?php echo $val['docdateinvoice'] ?></td>
                                        <td width="200px" style="text-align: right"><?php echo number_format($amountinvoiced,$decimalPlacescinvoiced) ?></td>
                                        <td width="200px" style="text-align: right"> </td>

                                    </tr>

                                <?php
                                    $contract_value_total += $amountcontract;
                                }?>


                                <?php

                                $blance_value_total += $amountinvoiced;



                            }
                            ?>
                            <tr>
                                <td colspan="4"><b>Total</b></td>

                                <td class="text-right reporttotal"><?php echo number_format($contract_value_total, $decimalPlacescinvoiced) ?></td>
                                <td colspan="2"> </td>
                                <td class="text-right reporttotal"><?php echo number_format($blance_value_total, $decimalPlacescinvoiced) ?></td>
                                <td class="text-right reporttotal"><?php echo number_format(($contract_value_total - $blance_value_total), $decimalPlacescinvoiced) ?></td>

                            </tr>

                            <?php
                        }
                    } ?>
                    <tr>
                        <td>&nbsp;

                        </td>


                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
<?php } else {
    ?>
    <br>
    <div class="row">
        <div class="col-md-12 xxcol-md-offset-2">
            <div class="alert alert-warning" role="alert">
                No Records found
            </div>
        </div>
    </div>

    <?php
} ?>
<script>
    $('#tbl_rpt_salesreturn tr').mouseover(function (e) {
        $('#tbl_rpt_salesreturn tr').removeClass('highlighted');
        $(this).addClass('highlighted');
    });

    $('#tbl_rpt_salesreturn').tableHeadFixer({
        head: true,
        foot: true,
        left: 0,
        right: 0,
        'z-index': 10
    });

</script>