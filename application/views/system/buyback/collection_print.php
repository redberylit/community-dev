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
<?php
$totalbalance = 0;
$totalcollectionamt = 0;

echo fetch_account_review(false, true,$extra['collectionmaster']['confirmedYN']); ?>
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
                            <h4 >Buyback Collection</h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<br>
<hr style="margin-top: -1%">
<br>
<div class="table-responsive">
    <table style="width: 90%">
        <tbody>
        <tr>
            <td ><strong>Live Collection Number </strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['collectionmaster']['collectionCode'] ?></td>

            <td><strong>Document Date </td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['collectionmaster']['createdDate'] ?></td>
        </tr>
        <tr>
            <td ><strong>Driver And Helper </strong></td>
            <td ><strong>:</strong></td>
            <td><?php echo $extra['collectionmaster']['driverhelper'] ?> </td>

            <td><strong>Narration </td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['collectionmaster']['Narration'] ?>  </td>
        </tr>
        </tbody>
    </table>
</div>

<br>
<div class="table-responsive">
    <table id="add_new_grv_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th class='theadtr' style="min-width: 5%">#</th>
            <th class='theadtr' style="min-width: 5%">Area</th>
            <th class='theadtr' style="min-width: 5%">Sub Area</th>
            <th class='theadtr' style="min-width: 10%">Farm</th>
            <th class='theadtr' style="min-width: 15%">Batch Code</th>
            <th class='theadtr' style="min-width: 10%">Balance</th>
            <th class='theadtr' style="min-width: 10%">FVR</th>
            <th class='theadtr' style="min-width: 10%">Weight</th>
            <th class='theadtr' style="min-width: 5%">Collection</th>
            <th class='theadtr' style="min-width: 5%">Age</th>
            <th class='theadtr' style="min-width: 12%">Address</th>
            <th class='theadtr' style="min-width: 12%">Contact No</th>
        </tr>
        </thead>
        <tbody id="grv_table_body">

 <?php

        if (!empty($collectiondetails['collectiondetail'])) {
            $x = 1;

            foreach ($collectiondetails['collectiondetail'] as $detailVal) {
                $totalbalance +=$detailVal['balanceQty'];
                $totalcollectionamt +=$detailVal['collectionQty'];

                ?>
                <tr>
                    <td><?php echo $x;?></td>
                    <td><?php echo $detailVal['farmlocation'];?></td>
                    <td><?php echo $detailVal['subarea'];?></td>
                    <td><?php echo $detailVal['farmname'];?></td>
                    <td><?php echo $detailVal['batchsystemcode'];?></td>
                    <td style="text-align: right"><?php echo $detailVal['balanceQty'];?></td>
                    <td style="text-align: right"><?php echo $detailVal['fvr'];?></td>
                    <td style="text-align: right"><?php echo $detailVal['avgBodyWeight'];?></td>
                    <td style="text-align: right"><?php echo $detailVal['collectionQty'];?></td>
                    <td><?php echo $detailVal['age'];?></td>
                    <td><?php echo $detailVal['farmeradd'];?></td>
                    <td><?php echo $detailVal['phonemobilefarmer'];?></td>
                </tr>
                <?php
                $x++;
            }
        } else {
            echo '<tr class="danger"><td colspan="14" class="text-center"><b>No Records Found</b></td></tr>';
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-right sub_total" colspan="5">Total</span></td>
            <td class="text-right total"><?php echo number_format($totalbalance,2); ?></td>
            <td> </td>
            <td> </td>

            <td class="text-right total"><?php echo number_format($totalcollectionamt,2); ?></td>
            <td colspan="5"></td>
        </tr>
        </tfoot>
    </table>
</div>
<br>
<div class="table-responsive">
    <table style="width: 50%">
        <tr>
            <td style="width:30%;">

                    <table style="width: 100%">
                        <tbody>
                        <?php if ( $extra['collectionmaster']['confirmedYN'] == 1 ) { ?>
                        <tr>

                            <td><b>Confirmed By</b></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['collectionmaster']['confirmedByName']?></td>
                        </tr>
                        <?php } ?>
                        <tr>

                            <td><b>Created Date And Time</b></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['collectionmaster']['createdatetime']?></td>
                        </tr>
                        <tr>

                            <td><b>Last Update Date And Time</b></td>
                            <td><strong>:</strong></td>
                            <td>

                                <?php if(!empty( $extra['collectionmaster']['updatedatetime']))
                                {
                                    echo $extra['collectionmaster']['updatedatetime'];
                                }else
                                {
                                    echo  ' - ';
                                }
                               ?>

                            </td>
                        </tr>
                        </tbody>
                    </table>

            </td>
        </tr>
    </table>
</div>

<br>
<script>
    $('.review').removeClass('hide');
    a_link = "<?php echo site_url('Buyback/load_buyback_collection_confirmation'); ?>/<?php echo $extra['collectionmaster']['collectionID'] ?>";
    $("#a_link").attr("href", a_link);
</script>