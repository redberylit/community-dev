
<div class="table-responsive">
    <table style="width: 100%;">
        <tbody style="border: 1px solid black;">
        <tr>
            <td style="width:40%;border: 1px solid black;">
                <img alt="Logo" style="height: 80px"
                     src="<?php echo mPDFImage . $this->common_data['company_data']['company_logo']; ?>"></td>
            <td style="width:60%;height:25px;border: 1px solid black;text-align: center;">
                <h5>
                    <strong><?php echo $this->common_data['company_data']['company_name'] . ' (' . $this->common_data['company_data']['company_code'] . ')'; ?></strong>
                </h5><h4>DELIVERY NOTE</h4></td>
        </tr>
        <tr style="border: 1px solid black;">
            <td colspan="2" style="width:100%;height:25px;border: 1px solid black;text-align: center;">&nbsp;
                <p><?php echo $this->common_data['company_data']['company_address1'] . ' ' . $this->common_data['company_data']['company_address2'] . ' ' . $this->common_data['company_data']['company_city'] . ' ' . $this->common_data['company_data']['company_country']; ?></p>
            </td>
        </tr>
        <tr style="border: 1px solid black;">
            <td colspan="2" style="width:100%;border: 1px solid black;">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;">
        <tbody style="border: 1px solid black;">
        <tr>
            <td colspan="3" rowspan="2" style="width:40%;height:40px;border: 1px solid black;"><strong>
                    &nbsp;&nbsp;TO </strong> &nbsp;<?php echo $header['CustomerName']; ?></td>
            <td style="width:5%;height:40px;border: 1px solid black;text-align: right;background-color: lightgray;"><strong>&nbsp;D.N. No</strong></td>
            <td style="width:25%;height:40px;border: 1px solid black;">
                &nbsp;<?php echo $header['deliveryNoteCode']; ?></td>
            <td style="width:5%;height:40px;border: 1px solid black;text-align: right;background-color: lightgray;"><strong>&nbsp;Date</strong></td>
            <td style="width:20%;height:40px;border: 1px solid black;"><?php echo $header['deliveryDate']; ?></td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="width:20%;height:40px;border: 1px solid black;text-align: right;background-color: lightgray;"><strong>&nbsp;Job No</strong>
            </td>
            <td style="width:30%;height:25px;border: 1px solid black;" colspan="3">
                &nbsp;<?php echo $header['jobCode']; ?></td>
        </tr>
        <tr style="border: 1px solid black;">
            <td colspan="7" style="width:100%;height:25px;border: 1px solid black;">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;">
        <tbody>
        <tr style="border: 1px solid black;background-color: lightgray;">
            <td style="width:10%;height:25px;border: 1px solid black;text-align: center;"><strong>Sr No</strong></td>
            <td style="width:10%;height:25px;border: 1px solid black;text-align: center;"><strong>QTY</strong></td>
            <td style="width:20%;height:25px;border: 1px solid black;text-align: center;"><strong>PO Ref #</strong></td>
            <td style="width:60%;height:25px;border: 1px solid black;text-align: center;"><strong>DESCRIPTION / PARTICULARS</strong></td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="width:10%;height:25px;border: 1px solid black;text-align: center;">1</td>
            <td style="width:10%;height:25px;border: 1px solid black;text-align: center;"><?php echo $header['detailQty']; ?></td>
            <td style="width:20%;height:25px;border: 1px solid black;text-align: center;">&nbsp;<?php echo $header['estmPoNumber']; ?></td>
            <td style="width:60%;height:25px;border: 1px solid black;">&nbsp;<?php echo $header['itemName']; ?></td>
        </tr>
        <?php
        for ($x = 0; $x <= 15; $x++) { ?>
            <tr style="border: 1px solid black;">
                <td style="width:10%;height:25px;border: 1px solid black;text-align: center;">&nbsp;</td>
                <td style="width:10%;height:25px;border: 1px solid black;text-align: center;">&nbsp;</td>
                <td style="width:20%;height:25px;border: 1px solid black;text-align: center;">&nbsp;</td>
                <td style="width:60%;height:25px;border: 1px solid black;text-align: center;">&nbsp;</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <table style="width: 100%;">
        <tbody>
        <tr style="border: 1px solid black;background-color: lightgray;">
            <td style="width:40%;height:30px;border: 1px solid black;text-align: center;"><strong>Name</strong></td>
            <td style="width:20%;height:30px;border: 1px solid black;text-align: center;"><strong>Vehicle No</strong>
            </td>
            <td style="width:20%;height:30px;border: 1px solid black;text-align: center;"><strong>Mobile No</strong>
            </td>
            <td style="width:20%;height:30px;border: 1px solid black;text-align: center;"><strong>Signature</strong>
            </td>
        </tr>
        <tr style="border: 1px solid black;width:100%;">
            <td style="width:40%;height:30px;border: 1px solid black;text-align: center;">
                &nbsp;<?php echo $header['driverName']; ?></td>
            <td style="width:20%;border: 1px solid black;text-align: center;">
                &nbsp;<?php echo $header['vehicleNo']; ?></td>
            <td style="width:20%;border: 1px solid black;text-align: center;">
                &nbsp;<?php echo $header['mobileNo']; ?></td>
            <td style="width:20%;border: 1px solid black;text-align: center;">&nbsp;</td>
        </tr>
        <tr style="border: 1px solid black;">
            <td colspan="4" style="width:100%;height:30px;border: 1px solid black;text-align: center;">Certifies that
                the above mentioned materials have been received in good order and condition / as per scope of work
            </td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;border: 1px solid black" border="1">
        <tbody>
        <tr style="border: 1px solid black;background-color: lightgray;">
            <td colspan="2" style="width:40%;height:30px;border: 1px solid black;text-align: center;"><strong>Signed for
                    HEMT STORES</strong></td>
            <td colspan="2" style="width:60%;height:30px;border: 1px solid black;text-align: center;"><strong>Customer
                    Signature & Stamp after completion / receipt</strong></td>
        </tr>
        <tr>
            <td style="width:20%;height:30px;background-color: lightgray;">&nbsp;Name</td>
            <td style="width:30%;"><?php echo $header['confirmedByName']; ?></td>
            <td style="width:20%;background-color: lightgray;">&nbsp;Name</td>
            <td style="width:30%;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:20%;height:30px;background-color: lightgray;">
                &nbsp;Signature
            </td>
            <td style="width:30%;">&nbsp;</td>
            <td style="width:20%;background-color: lightgray;">&nbsp;Signature</td>
            <td style="width:30%;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:20%;height:30px;background-color: lightgray;">
                &nbsp;Date
            </td>
            <td style="width:30%;"><?php echo $header['confirmedDate']; ?></td>
            <td style="width:20%;background-color: lightgray;">
                &nbsp;Date
            </td>
            <td style="width:30%;">
                &nbsp;</td>
        </tr>
        </tbody>
    </table>
</div>