<?php echo fetch_account_review(false, true, ''); ?>
<div id="div_print">
    <div class="table-responsive">
        <table width="100%">
            <tbody>
            <tr>
                <td width="200px"><img alt="Logo" style="height: 130px"
                                       src="<?php echo mPDFImage . $this->common_data['company_data']['company_logo']; ?>">
                </td>
                <td>
                    <div style="text-align: center; font-size: 17px; line-height: 26px; margin-top: 10px;">
                        <strong> <?php echo $this->common_data['company_data']['company_name'] ?></strong><br>
                        <center>Estimation</center>
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
                <td><b>Document Date</b></td>
                <td colspan="9"><?php echo $header["documentDate"] ?></td>
            </tr>
            <tr>
                <td><b>Reference</b></td>
                <td colspan="9"><?php echo $header["estimateCode"]; ?></td>
            </tr>
            <tr>
                <td><b>Customer</b></td>
                <td colspan="9"><?php echo $header["CustomerName"]; ?></td>
            </tr>
            <tr>
                <td><b>Description</b></td>
                <td colspan="9"><?php echo $header["description"]; ?></td>
            </tr>
            <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
                <td colspan="10" style="text-align:center;">ITEM DETAIL</td>
            </tr>
            <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
                <td>Item Code</td>
                <td colspan="4">Item Description</td>
                <td>UOM</td>
                <td>Qty</td>
                <td>Unit Price</td>
                <td>Amount</td>
                <td>Discounted Amount</td>
            </tr>
            <?php
            $totCost = 0;
            $total = 0;
            $discount = 0;
            if (!empty($itemDetail)) {
                foreach ($itemDetail as $val) {
                    $expectedQty = 1;
                    if($val['expectedQty']){
                        $expectedQty = $val['expectedQty'];
                    }
                    $totCost += ($val['sellingPrice']/$expectedQty);
                    $total += $val['sellingPrice'];
                    $discount += $val['discountedPrice'];
                    ?>
                    <tr>
                        <td width="25%"><?php echo $val['itemSystemCode'] ?></td>
                        <td width="25%" colspan="4"><?php echo $val['itemDescription'] ?></td>
                        <td><?php echo $val['UnitDes'] ?></td>
                        <td width=""><?php echo $val['expectedQty'] ?></td>
                        <td width=""
                            style="text-align: right"><?php echo number_format(($val['sellingPrice']/$expectedQty), $val['companyLocalCurrencyDecimalPlaces']) ?></td>
                        <td width=""
                            style="text-align: right"><?php echo number_format($val['sellingPrice'], $val['companyLocalCurrencyDecimalPlaces']) ?></td>
                        <td width=""
                            style="text-align: right"><?php echo number_format($val['discountedPrice'], $val['companyLocalCurrencyDecimalPlaces']) ?></td>
                    </tr>
                <?php }
            } else {
                ?>
                <tr class="danger">
                    <td colspan="10" class="text-center"><b>No Records Found</b></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="7" style="text-align: right"><strong>Total</strong></td>
                <td style="text-align: right">
                    <strong><?php echo number_format($totCost, $this->common_data['company_data']['company_default_decimal']) ?></strong>
                </td>
                <td style="text-align: right">
                    <strong><?php echo number_format($total, $this->common_data['company_data']['company_default_decimal']) ?></strong>
                </td>
                <td style="text-align: right">
                    <strong><?php echo number_format($discount, $this->common_data['company_data']['company_default_decimal']) ?></strong>
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right"><strong>Margin</strong></td>
                <td style="text-align: right">
                    <strong><?php echo number_format(($discount *(100+$header["totMargin"]))/100, $this->common_data['company_data']['company_default_decimal']) ?></strong>
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right"><strong>Discount</strong></td>
                <td style="text-align: right">
                    <strong><?php echo number_format(((($discount *(100+$header["totMargin"]))/100) *(100 - $header["totDiscount"]))/100, $this->common_data['company_data']['company_default_decimal']) ?></strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive" style="margin-top: 5px">
        <table style="width: 100%">
            <tbody>
            <tr>
                <td style="width:10%;" valign="top"><strong>Scope of Work </strong></td>
                <td style="width:2%;" valign="top"><strong>:</strong></td>
                <td style="text-align: justify"><?php echo $header["scopeOfWork"]; ?></td>
            </tr>
            <tr>
                <td valign="top"><strong>Technical Detail </strong></td>
                <td valign="top"><strong>:</strong></td>
                <td style="text-align: justify"><?php echo $header["technicalDetail"]; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $('.review').removeClass('hide');
    a_link = "<?php echo site_url('MFQ_Estimate/fetch_estimate_print'); ?>/<?php echo $header['estimateMasterID'] ?>";
    $("#a_link").attr("href", a_link);
</script>