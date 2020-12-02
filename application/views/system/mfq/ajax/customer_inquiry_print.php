<div id="div_print" style="padding:5px;">
    <table width="100%">
        <tbody>
        <tr>
            <td width="200px"><img alt="Logo" style="height: 130px"
                     src="<?php echo mPDFImage . $this->common_data['company_data']['company_logo']; ?>"></td>
            <td>
                <div style="text-align: center; font-size: 17px; line-height: 26px; margin-top: 10px;">
                    <strong> <?php echo $this->common_data['company_data']['company_name'] ?></strong><br>
                    <center>Customer Inquiry</center>
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
            <td colspan="2"><b>Inquiry Date</b></td>
            <td colspan="7" width="79"><?php echo $header["documentDate"] ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Inquiry Code</b></td>
            <td colspan="7"><?php echo $header["ciCode"]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Client Reference No</b></td>
            <td colspan="7"><?php echo $header["referenceNo"]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Customer</b></td>
            <td colspan="7" width="214"><?php echo $header["CustomerName"]; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Description</b></td>
            <td colspan="7" width="214"><?php echo $header["description"]; ?></td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="9" style="text-align:center;">ITEM DETAIL</td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="2">Item Code</td>
            <td colspan="3">Item Description</td>
            <td>Department</td>
            <td>Delivery Date</td>
            <td>UOM</td>
            <td>Qty</td>
        </tr>
        <?php
        $qtyUsed = 0;
        if (!empty($itemDetail)) {
            foreach ($itemDetail as $val) {
                ?>
                <tr>
                    <td width="25%" colspan="2"><?php echo $val['itemSystemCode'] ?></td>
                    <td width="25%" colspan="3"><?php echo $val['itemDescription'] ?></td>
                    <td><?php echo $val['segment'] ?></td>
                    <td><?php echo $val['expectedDeliveryDate'] ?></td>
                    <td width=""><?php echo $val['UnitDes'] ?></td>
                    <td width="" class="text-right"><?php echo $val['expectedQty'] ?></td>
                </tr>
            <?php }
        }else{
            ?>
            <tr class="danger"><td colspan="9" class="text-center"><b>No Records Found</b></td></tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
