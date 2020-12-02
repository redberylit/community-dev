<?php if($type==true){?>
<style>
    .bgcolour {
        background-color: #00a65a;
        margin-top: 3%;
    }
    .bgcolourconfirm {
        background-color: #f9ac38;
        margin-top: 3%;
    }
    .item-labellabelbuyback {
        color: #fff;
        height: 21px;
        width: 90px;
        position: absolute;
        font-weight: bold;
        padding-left: 10px;
        padding-top: 0px;
        top: 10px;
        right: -59px;
        margin-right: 0;
        border-radius: 3px 3px 0 3px;
        box-shadow: 0 3px 3px -2px #ccc;
        text-transform: capitalize;
    }
    .item-labellabelbuyback:after {
        top: 20px;
        right: 0;
        border-top: 4px solid #1f1d1d;
        border-right: 4px solid rgba(0, 0, 0, 0);
        content: "";
        position: absolute;
    }
    .item-labelapproval {
        color: #fff;
        height: 21px;
        width: 90px;
        position: absolute;
        font-weight: bold;
        padding-left: 10px;
        padding-top: 0px;
        top: 10px;
        right: -20px;
        margin-right: 0;
        border-radius: 3px 3px 0 3px;
        box-shadow: 0 3px 3px -2px #ccc;
        text-transform: capitalize;
    }
    .item-labelapproval:after {
        top: 20px;
        right: 0;
        border-top: 4px solid #1f1d1d;
        border-right: 4px solid rgba(0, 0, 0, 0);
        content: "";
        position: absolute;
    }
</style>
<?php }?>
<?php echo fetch_account_review(true, true, $approval); ?>
<!--<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:60%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px" src="<?php
/*                            echo mPDFImage . $this->common_data['company_data']['company_logo']; */?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:40%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h3>
                                <strong><?php /*echo $this->common_data['company_data']['company_name'] . ' (' . $this->common_data['company_data']['company_code'] . ').'; */?></strong>
                            </h3>

                            <p><?php /*echo $this->common_data['company_data']['company_address1'] . ' ' . $this->common_data['company_data']['company_address2'] . ' ' . $this->common_data['company_data']['company_city'] . ' ' . $this->common_data['company_data']['company_country']; */?></p>
                            <h4>Good Receipt Note</h4>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>GRN Number</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['documentSystemCode']; */?></td>
                    </tr>
                    <tr>
                        <td><strong>GRN Date</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['documentDate']; */?></td>
                    </tr>
                    <tr>
                        <td><strong>Reference Number</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['referenceNo']; */?></td>
                    </tr>
                    <tr>
                        <td><strong>Location</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['wareHouseLocation']; */?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="table-responsive">
    <hr>
    <table>
        <tr>
            <td style="width:50%;">
                <table style="width: 100%">
                    <tbody>
                    <tr>
                        <td class="td"><strong>Farmer</strong></td>
                        <td><strong>:</strong></td>
                        <td class="td"><?php /*echo $extra['master']['farmName']; */?></td>
                    </tr>
                    <tr>
                        <td style="width:15%;" class="td"><strong>Address </strong></td>
                        <td style="width:2%;"><strong>:</strong></td>
                        <td style="width:83%;" class="td"><?php /*echo $extra['master']['farmAddress']; */?></td>
                    </tr>
                    <tr>
                        <td class="td"><strong>Phone</strong></td>
                        <td><strong>:</strong></td>
                        <td class="td"><?php /*echo $extra['master']['farmTelephone']; */?></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="width:50%;">
                <table style="width: 100%">
                    <tbody>
                    <tr>
                        <td style="width:20%;" class="td"><strong>Delivered Date </strong></td>
                        <td style="width:2%;"><strong>:</strong></td>
                        <td style="width:78%;" class="td"><?php /*echo $extra['master']['deliveryDate']; */?></td>
                    </tr>
                    <tr>
                        <td class="td"><strong>Currency </strong></td>
                        <td><strong>:</strong></td>
                        <td class="td"><?php /*echo $extra['master']['CurrencyDes'] . ' ( ' . $extra['master']['transactionCurrency'] . ' )'; */?></td>
                    </tr>
                    <tr>
                        <td class="td"><strong>Narration </strong></td>
                        <td><strong>:</strong></td>
                        <td class="td"><?php /*echo $extra['master']['Narration']; */?></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</div>-->

<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <!-- <td style="width:50%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px" src="<?php /*echo mPDFImage.$this->common_data['company_data']['company_logo']; */?>">
                        </td>
                    </tr>
                </table>
            </td>-->
            <td>
                <table>
                    <tr>
                        <td style="text-align: center;">
                            <!--<h3><strong><?php /*echo $this->common_data['company_data']['company_name']; */?>.</strong></h3>
                            <p><?php /*echo $this->common_data['company_data']['company_address1'].' '.$this->common_data['company_data']['company_address2'].' '.$this->common_data['company_data']['company_city'].' '.$this->common_data['company_data']['company_country']; */?></p>
                            <br>-->
                            <h4 >Good Receipt Note</h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php if($type==true){
    $class ='theadtr';
    ?>


<?php if($extra['master']['approvedYN']== 1 && $extra['master']['confirmedYN']== 1) {
    echo '<div class="post-area" >
    <article class="post" style="padding-bottom: 2%">
        <div class="item-labellabelbuyback file bgcolour">Approved</div>
    </article>';
}?>
<?php if($extra['master']['confirmedYN']==1 && $extra['master']['approvedYN']!= 1 && $size !=1) {
    echo '<div class="post-area" >
    <article class="post" style="padding-bottom: 2%">
        <div class="item-labellabelbuyback file bgcolourconfirm">Confirmed</div>
    </article>';
}?>
<?php } else {
    $class ='';
} ?>

<?php if($extra['master']['confirmedYN']== 1 && $size ==1) {
    echo '<div class="post-area" >
    <article class="post" style="padding-bottom: 2%">
        <div class="item-labelapproval file bgcolourconfirm">Confirmed</div>
    </article>';
}?>


<hr style="margin-top: -1%">
<br>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td ><strong>GRN Number</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['documentSystemCode']; ?></td>

            <td><strong>GRN Date</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['documentDate']; ?></td>
        </tr>
        <tr>
            <td ><strong>Reference Number</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['referenceNo']; ?></td>

            <td><strong>Location</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['wareHouseLocation']; ?></td>
        </tr>
        <tr>
            <td ><strong>Farmer</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['farmName']; ?></td>

            <td><strong>Address</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['farmAddress']; ?></td>
        </tr>
        <tr>
            <td ><strong>Phone</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['farmTelephone']; ?></td>

            <td><strong>Delivered Date</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['deliveryDate']; ?></td>
        </tr>

        <tr>
            <td ><strong>Currency</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['CurrencyDes'] . ' ( ' . $extra['master']['transactionCurrency'] . ' )'; ?></td>

            <td><strong>Narration</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['Narration']; ?></td>
        </tr>
        <tr>
            <td ><strong>Batch</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['batchCode']; ?></td>

            <td ><strong>GRN Type</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['grnType']; ?></td>

        </tr>
        <tr>
            <td ><strong>Segment</strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['master']['segmentdescription']; ?></td>

            <td ><strong>Vehicle No</strong></td>
            <td ><strong>:</strong></td>
            <td><?php
                if($extra['master']['vehicleNo']!='')
                {
                echo $extra['master']['vehicleNo'];
                }
                else
                {
                    echo '-';
                } ?>
            </td>

        </tr>
        <tr>
            <td ><strong>Driver And Helper Name</strong></td>
            <td ><strong>:</strong></td>
            <td>
                <?php
                if($extra['master']['DriverName']!='' && $extra['master']['HelperName']!='')
                {
                    echo $extra['master']['DriverName']; echo ' AND '; echo $extra['master']['HelperName'];
                }
                else if($extra['master']['DriverName']!='')
                {
                    echo $extra['master']['DriverName'];
                }
                else if($extra['master']['HelperName']!='')
                {
                    echo $extra['master']['HelperName'];
                }
                else{
                    echo '-';
                }
                ?>
            </td>

            <td ><strong>Journey Time</strong></td>
            <td ><strong>:</strong></td>
            <td>  <?php
                if($extra['master']['JourneyStartTime']!='' && $extra['master']['JourneyEndTime']!='')
                {
                    echo $extra['master']['JourneyStartTime']; echo ' to '; echo $extra['master']['JourneyEndTime'];
                }
                else if($extra['master']['JourneyStartTime']!='')
                {
                    echo $extra['master']['JourneyStartTime'];
                }
                else if($extra['master']['JourneyEndTime']!='')
                {
                    echo $extra['master']['JourneyEndTime'];
                }
                else{
                    echo '-';
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<br>
<div class="table-responsive">
    <table id="add_new_grv_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th class='<?php echo $class?>' style="min-width: 5%">#</th>
            <th class='<?php echo $class?>' style="min-width: 10%">Item Code</th>
            <th class='<?php echo $class?>' style="min-width: 15%">Item Description</th>
            <th class='<?php echo $class?>' style="min-width: 10%">No of Birds</th>
            <th class='<?php echo $class?>' style="min-width: 10%">UOM</th>
            <th class='<?php echo $class?>' style="min-width: 5%">Qty</th>
            <th class='<?php echo $class?>' style="min-width: 12%">Unit Cost</th>
            <th class='<?php echo $class?>' style="min-width: 12%">Net Amount</th>
        </tr>
        </thead>
        <tbody id="grv_table_body">
        <?php
        $detail_total = 0;
        if (!empty($extra['detail'])) {
            $detail_x = 0;
            foreach ($extra['detail'] as $detailVal) { ?>
                <tr>
                    <td><?php echo $detail_x; ?></td>
                    <td><?php echo $detailVal['itemSystemCode']; ?></td>
                    <td><?php echo $detailVal['itemDescription'];?></td>
                    <td><?php echo $detailVal['noOfBirds']; ?></td>
                    <td><?php echo $detailVal['defaultUOM']; ?></td>
                    <td class="text-right"><?php echo $detailVal['qty']; ?></td>
                    <td class="text-right"><?php echo format_number($detailVal['unitTransferCost'],$extra['master']['transactionCurrencyDecimalPlaces']); ?></td>
                    <td class="text-right"><?php echo format_number($detailVal['totalCostTransfer'],$extra['master']['transactionCurrencyDecimalPlaces'] ); ?></td>
                </tr>
                <?php
                $detail_total += $detailVal['totalCostTransfer'];
                $detail_x++;
            }
        } else {
            echo '<tr class="danger"><td colspan="10" class="text-center"><b>No Records Found</b></td></tr>';
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-right sub_total" colspan="7">Total <span class="currency"> (<?php echo $extra['master']['transactionCurrency']; ?>)</span></td>
            <td class="text-right total"><?php echo format_number($detail_total, $extra['master']['transactionCurrencyDecimalPlaces']); ?></td>
        </tr>
        </tfoot>
    </table>
</div>
<br>
<div class="table-responsive">
    <table style="width: 100%">
        <tr>
            <td style="width:30%;">
                <?php if ($extra['master']['approvedYN']!=1 && $extra['master']['confirmedYN'] ) { ?>
                    <table style="width: 100%">
                        <tbody>
                        <tr>
                            <td><b>Confirmed By</b></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['master']['confirmedByName']; ?> / <?php echo $extra['master']['confirmedDate']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>

<br>
<div class="table-responsive">
    <table style="width: 100%">
        <tr>
            <td style="width:30%;">
                <?php if ($extra['master']['approvedYN']) { ?>
                    <table style="width: 100%">
                        <tbody>
                        <tr>
                            <td><b>Approved By </b></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['master']['approvedbyEmpName']; ?> / <?php echo $extra['master']['approvedDate']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Confirmed By</b></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['master']['confirmedByName']; ?> / <?php echo $extra['master']['confirmedDate']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>
<script>
    $('.review').removeClass('hide');
    a_link = "<?php echo site_url('Buyback/load_goodReceiptNote_confirmation'); ?>/<?php echo $extra['master']['grnAutoID'] ?>";
    de_link = "<?php echo site_url('Buyback/fetch_double_entry_buyback_goodReceiptNote'); ?>/" + <?php echo $extra['master']['grnAutoID'] ?> +'/BBGRN';
    $("#a_link").attr("href", a_link);
    $("#de_link").attr("href", de_link);
</script>