<?php
$grndotalinput = 0;
$grndotalbalance = 0;
$grndotalbatchvalue = 0;
$grndotaldispatchvalue = 0;
$grndotalgrnvalue = 0;
$grndotalbalancevalue = 0;
$grndotalbalancevalue = 0;
$grandtotalreturnval = 0;
if ($details) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('wiprpt', 'Wip Report', True, True);
            } ?>
        </div>
    </div>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="wiprpt">
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong>Work In Progress Report</strong></div>
            <br>
            <div style="">
                <table id="tbl_rpt_salesorder" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>
                        <th>Farm</th>
                        <th>Batch Code</th>
                        <th>Currency</th>
                        <th>Age</th>
                        <th>Mortality</th>
                        <th>Input</th>
                        <th>Balance</th>
                        <th>Batch Value</th>
                        <th>Dispatch Value</th>
                        <th>Return Value</th>
                        <th>GRN Value</th>
                        <th>Balance Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($details) {

                        $details = array_group_by($details, 'farmid');
                        foreach ($details as $value) {
                            $balance = 0;
                            $input = 0;
                            $balance_tot = 0;
                            $batch_value = 0;
                            $dispatch_value = 0;
                            $blance_value = 0;
                            $credit_val = 0;
                            $blance_value_total = 0;
                            $returnvalue = 0;



                            foreach ($value as $val) {
                                $balance = $val['chicksTotal'] - ($val['receivedtotal'] + $val['mortality']);
                                $blance_value = $val['workinprogressamount'] - ($val['creditTotal']) - ($val['returnvalue']);

                                ?>
                                <tr>
                                    <td width="120px"><?php echo $val["farmerName"] ?></td>
                                    <td width="120px"><?php echo $val["batchCode"] ?> </td>
                                    <td><?php echo $val["CurrencyCode"] ?></td>
                                    <td style="text-align: right"><?php echo $val["chicksage"] ?></td>
                                    <td style="text-align: right"><?php echo $val["mortality"] ?></td>
                                    <td width="100px" style="text-align: right"><?php echo $val["chicksTotal"] ?></td>
                                    <td width="100px" style="text-align: right"><?php echo $balance ?></td>
                                    <td width="100px" style="text-align: right">

                                        <a href="#" class="drill-down-cursor"
                                           onclick="open_dis_dd(<?php echo $val["batchMasterID"] ?>)"><?php echo number_format($val["batchvalue"], 2) ?></a>
                                    </td>
                                    <td width="100px"
                                        style="text-align: right"><?php echo number_format($val['workinprogressamount'], 2) ?></td>

                                    <td width="100px"
                                        style="text-align: right"><?php echo number_format($val['returnvalue']) ?></td>

                                    <td width="100px"
                                        style="text-align: right"><?php echo number_format($val['creditTotal'], 2) ?></td>
                                    <td width="100px"
                                        style="text-align: right"><?php echo number_format($blance_value, 2) ?></td>

                                </tr>
                                <?php
                                $input += $val["chicksTotal"];
                                $balance_tot += $balance;
                                $batch_value += $val["batchvalue"];
                                $dispatch_value += $val["workinprogressamount"];
                                $credit_val += $val["creditTotal"];
                                $blance_value_total += $blance_value;
                                $grndotalbatchvalue += $val['batchvalue'];
                                $grndotalinput += $val["chicksTotal"];
                                $grndotalbalance += $balance;
                                $grndotaldispatchvalue += $val['workinprogressamount'];
                                $grndotalgrnvalue += $val['creditTotal'];
                                $grndotalbalancevalue += $blance_value;
                                $returnvalue += $val['returnvalue'];
                                $grandtotalreturnval += $val['returnvalue'];

                            }
                            if($Tot == 'show') { ?>
                                <tr>
                                    <td colspan="5"><b>Total</b></td>

                                    <td class="text-right reporttotal"><?php echo $input ?></td>
                                    <td class="text-right reporttotal"><?php echo number_format($balance_tot, 2) ?></td>
                                    <td class="text-right reporttotal"><?php echo number_format($batch_value, 2) ?></td>
                                    <td class="text-right reporttotal"><?php echo number_format($dispatch_value, 2) ?></td>
                                    <td class="text-right reporttotal"><?php echo number_format($returnvalue, 2) ?></td>
                                    <td class="text-right reporttotal"><?php echo number_format($credit_val, 2) ?></td>
                                    <td class="text-right reporttotal"><?php echo number_format($blance_value_total, 2) ?></td>

                                </tr>

                                <?php
                            }
                        }
                    } ?>
                    <tr>
                        <td>&nbsp;

                        </td>


                    </tr>
                    </tbody>
                    <tr>
                        <td colspan="5"><b>Grand Total</b></td>
                        <td class="text-right reporttotal"><?php echo $grndotalinput ?></td>
                        <td class="text-right reporttotal"><?php echo number_format($grndotalbalance, 2) ?></td>
                        <td class="text-right reporttotal"><?php echo number_format($grndotalbatchvalue, 2) ?></td>
                        <td class="text-right reporttotal"><?php echo number_format($grndotaldispatchvalue, 2) ?></td>
                        <td class="text-right reporttotal"><?php echo number_format($grandtotalreturnval, 2) ?></td>
                        <td class="text-right reporttotal"><?php echo number_format($grndotalgrnvalue, 2) ?></td>
                        <td class="text-right reporttotal"><?php echo number_format($grndotalbalancevalue, 2) ?></td>
                    </tr>
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
    $('#tbl_rpt_salesorder tr').mouseover(function (e) {
        $('#tbl_rpt_salesorder tr').removeClass('highlighted');
        $(this).addClass('highlighted');
    });

    $('#tbl_rpt_salesorder').tableHeadFixer({
        head: true,
        foot: true,
        left: 0,
        right: 0,
        'z-index': 10
    });

</script>