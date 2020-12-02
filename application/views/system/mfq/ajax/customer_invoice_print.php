<?php echo fetch_account_review(true, false, $approval); ?>
<div id="div_print" style="padding:5px;">
    <table width="100%">
        <tbody>
        <tr>
            <td width="200px"><img alt="Logo" style="height: 130px"
                                   src="<?php echo mPDFImage . $this->common_data['company_data']['company_logo']; ?>"></td>
            <td>
                <div style="text-align: center; font-size: 17px; line-height: 26px; margin-top: 10px;">
                    <strong> <?php echo $this->common_data['company_data']['company_name'] ?></strong><br>
                    <center>Customer Invoice</center>
                </div>
            </td>
            <td style="text-align:right;">
                <div style="text-align:right; font-size: 17px; vertical-align: top;">

                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="4" border="1">
        <tbody>
        <tr>
            <td colspan="2"><b>Invoice Number</b></td>
            <td colspan="3" width="79"><?php echo $header["invoiceCode"] ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Invoice Date</b></td>
            <td colspan="3"><?php echo $header["invoiceDate"]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>SE No</b></td>
            <td colspan="3"><?php //echo $header[""]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Due Date</b></td>
            <td colspan="3" width="214"><?php echo $header["invoiceDueDate"]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Contract</b></td>
            <td colspan="3" width="214"><?php //echo $header[""]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>PO Number</b></td>
            <td colspan="3" width="214"><?php //echo $header[""]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Comments</b></td>
            <td colspan="3" width="214"><?php echo $header["invoiceNarration"]; ?></td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="5" style="text-align:center;">Item Detail</td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td >Item Description</td>
            <td >UoM</td>
            <td>Qty</td>
            <td>Unit Rate</td>
            <td>Amount</td>
        </tr>
        <?php
        $totalAmount = 0;
        if (!empty($itemDetail)) {
            foreach ($itemDetail as $val) {
                if ($val['type'] == 2) {
                    $totalAmount += $val['transactionAmount'];
                    ?>
                    <tr>
                        <td width="25%"><?php echo $val['itemDescription']; ?></td>
                        <td width="25%"><?php echo $val['defaultUnitOfMeasure']; ?></td>
                        <td style="text-align: right"><?php echo $val['requestedQty']; ?></td>
                        <td style="text-align: right"><?php echo number_format($val['unitRate'], $header["transactionCurrencyDecimalPlaces"]); ?></td>
                        <td style="text-align: right"><?php echo number_format($val['transactionAmount'], $header["transactionCurrencyDecimalPlaces"]); ?></td>
                    </tr>
                <?php }
            }
        }else{
            ?>
            <tr class="danger"><td colspan="5" class="text-center"><b>No Records Found</b></td></tr>
            <?php
        }
        ?>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="5" style="text-align:center;">GL Detail</td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td >GL Code</td>
            <td >GL Code Description</td>
            <td>Qty</td>
            <td>Unit Rate</td>
            <td>Amount</td>
        </tr>
        <?php
        if (!empty($itemDetail)) {
            foreach ($itemDetail as $val) {
                if ($val['type'] == 1) {
                    $totalAmount += $val['transactionAmount'];
                    ?>
                    <tr>
                        <td width="25%"><?php echo $val['revenueGLAutoID']; ?></td>
                        <td width="25%"><?php echo $val['GLDescription']; ?></td>
                        <td style="text-align: right"><?php echo $val['requestedQty']; ?></td>
                        <td style="text-align: right"><?php echo number_format($val['unitRate'], $header["transactionCurrencyDecimalPlaces"]); ?></td>
                        <td style="text-align: right"><?php echo number_format($val['transactionAmount'], $header["transactionCurrencyDecimalPlaces"]); ?></td>
                    </tr>
                <?php }
            }
            ?>
            <tr>
                <td style="text-align: right" colspan="4"><b>Total</b></td>
                <td style="text-align: right"><b><?php echo number_format($totalAmount,$header["transactionCurrencyDecimalPlaces"]) ?></b></td>
            </tr>
            <?php
        }else{
            ?>
            <tr class="danger"><td colspan="5" class="text-center"><b>No Records Found</b></td></tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    $('.review').removeClass('hide');
    de_link = "<?php echo site_url('MFQ_CustomerInvoice/fetch_double_entry_mfq_customerInvoice'); ?>/" + <?php echo $header['invoiceAutoID'] ?> +'/MCINV';
    //$("#a_link").attr("href", a_link);
    $("#de_link").attr("href", de_link);
</script>