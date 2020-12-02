<?php
if (!empty($details)) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('salesOrderReport', 'Collection Summary', True, True);
            } ?>
        </div>
    </div>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="salesOrderReport">
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong>Collection Detail</strong></div>
            <div style="">
                <table id="tbl_rpt_salesorder" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>
                        <th>Customer Name</th>
                        <th>RV Code</th>
                        <th>Document Date</th>
                        <th>Bank</th>
                        <th>Account</th>
                        <th>Segment</th>
                        <th>Currency</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if ($details) {
                        if ($currency == 1) {
                            $details = array_group_by($details, 'transactionCurrency');
                        } elseif ($currency == 2) {
                            $details = array_group_by($details, 'companyLocalCurrency');
                        } else {
                            $details = array_group_by($details, 'companyReportingCurrency');
                        }
                        foreach ($details as $value) {
                            $total = 0;
                            $decimalPlace = 2;
                            $currencys;
                            foreach ($value as $val) {
                                if ($currency == 1) {
                                    $decimalPlace = $val["transactionCurrencyDecimalPlaces"];
                                    $currencys = $val["transactionCurrency"];

                                } elseif ($currency == 2) {
                                    $decimalPlace = $val["companyLocalCurrencyDecimalPlaces"];
                                    $currencys = $val["companyLocalCurrency"];
                                } else {
                                    $decimalPlace = $val["companyReportingCurrencyDecimalPlaces"];
                                    $currencys = $val["companyReportingCurrency"];
                                }

                                ?>
                                <tr>
                                <td><?php echo $val["customerName"] ?></td>
                                <?php
                                if ($type == 'html') {
                                    ?>
                                    <td><a href="#" class="drill-down-cursor"
                                           onclick="documentPageView_modal('<?php echo $val["documentID"] ?>',<?php echo $val["receiptVoucherAutoId"] ?>)"><?php echo $val["RVcode"] ?></a>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td><?php echo $val["RVcode"] ?></td>
                                    <?php
                                }
                                ?>
                                <td><?php echo $val["RVdate"] ?></td>
                                <td><?php echo $val["bankName"] ?></td>
                                <td><?php echo $val["bankAccountNumber"] ?></td>
                                <td><?php echo $val["segmentCode"] ?></td>
                                <td><?php echo $currencys ?></td>
                                <td style="text-align: right"><?php echo number_format($val['transactionAmount'], $decimalPlace); ?></td>

                                <?php
                                /*$salesOrder += $val["total_value"];
                                $invoice += $val["returnAmount"];*/
                                $total += $val['transactionAmount'];;

                            }
                            ?>
                            <tr>
                                <td colspan="7"><b>Total</b></td>
                                <td class="text-right reporttotal"><?php echo number_format($total, $decimalPlace) ?></td>

                            </tr>
                            <?php
                        }
                    } ?>

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
                <?php echo $this->lang->line('common_no_records_found'); ?><!--No Records found-->
            </div>
        </div>
    </div>

    <?php
} ?>
<script>

    $('#tbl_rpt_salesorder').tableHeadFixer({
        head: true,
        foot: true,
        left: 0,
        right: 0,
        'z-index': 10
    });

</script>