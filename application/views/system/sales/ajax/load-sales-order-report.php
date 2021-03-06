<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('sales_marketing_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
if ($details) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('salesOrderReport', 'Sales Order', True, True);
            } ?>
        </div>
    </div>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="salesOrderReport">
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong><?php echo $this->lang->line('sales_markating_sales_order_report'); ?></strong></div>
            <div style="">
                <table id="tbl_rpt_salesorder" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>
                        <th><?php echo $this->lang->line('common_customer_name'); ?><!--Customer Name--></th>
                        <th><?php echo $this->lang->line('common_document_code'); ?><!--Document Code--></th>
                        <th><?php echo $this->lang->line('common_document_date'); ?><!--Document Date--></th>
                        <th><?php echo $this->lang->line('common_currency'); ?><!--Currency--></th>
                        <th>
                            <?php echo $this->lang->line('sales_markating_sales_order_amount'); ?><!--Sales order amount--></th>
                        <th><?php echo $this->lang->line('sales_markating_invoice_amount'); ?><!--Invoice Amount--></th>
                        <th><?php echo $this->lang->line('sales_markating_receipt_amount'); ?><!--Receipt Amount--></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($details) {
                        $details = array_group_by($details, 'transactionCurrency');
                        foreach ($details as $value) {
                            $salesOrder = 0;
                            $invoice = 0;
                            $receipt = 0;
                            $decimalPlace = 2;
                            foreach ($value as $val) {
                                $decimalPlace = $val["transactionCurrencyDecimalPlaces"];
                                ?>
                                <tr>
                                    <td width="200px"><?php echo $val["customerName"] ?></td>
                                    <td><a href="#" class="drill-down-cursor"
                                           onclick="documentPageView_modal('<?php echo $val["documentID"] ?>',<?php echo $val["contractAutoID"] ?>)"><?php echo $val["contractCode"] ?></a>
                                    </td>
                                    <td><?php echo $val["documentDate"] ?></td>
                                    <td><?php echo $val["transactionCurrency"] ?></td>
                                    <td style="text-align: right"><?php echo number_format($val["transactionAmount"], $val["transactionCurrencyDecimalPlaces"]) ?></td>
                                    <?php if ($type == "html") { ?>
                                        <td style="text-align: right"><a href="#"
                                                                         onclick="drilldownSalesOrder(<?php echo $val["contractAutoID"] ?>,'<?php echo $val["contractCode"] ?>',1,'Invoice')"> <?php echo number_format($val["invoiceAmount"], $val["transactionCurrencyDecimalPlaces"]) ?> </a>
                                        </td>
                                        <td style="text-align: right"><a href="#"
                                                                         onclick="drilldownSalesOrder(<?php echo $val["contractAutoID"] ?>,'<?php echo $val["contractCode"] ?>',2,'Receipt')"> <?php echo number_format($val["receiptAmount"], $val["transactionCurrencyDecimalPlaces"]) ?> </a>
                                        </td>
                                    <?php } else { ?>
                                        <td style="text-align: right"> <?php echo number_format($val["invoiceAmount"], $val["transactionCurrencyDecimalPlaces"]) ?></td>
                                        <td style="text-align: right"><?php echo number_format($val["receiptAmount"], $val["transactionCurrencyDecimalPlaces"]) ?></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                $salesOrder += $val["transactionAmount"];
                                $invoice += $val["invoiceAmount"];
                                $receipt += $val["receiptAmount"];
                            }
                            ?>
                            <tr>
                                <td colspan="4"><b>Total</b></td>
                                <td class="text-right reporttotal"><?php echo number_format($salesOrder,$decimalPlace) ?></td>
                                <td class="text-right reporttotal"><?php echo number_format($invoice,$decimalPlace) ?></td>
                                <td class="text-right reporttotal"><?php echo number_format($receipt,$decimalPlace) ?></td>
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