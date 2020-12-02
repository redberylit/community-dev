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
/*                            echo mPDFImage . $this->common_data['company_data']['company_logo']; */ ?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:40%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h3>
                                <strong><?php /*echo $this->common_data['company_data']['company_name'] . ' (' . $this->common_data['company_data']['company_code'] . ').'; */ ?></strong>
                            </h3>

                            <p><?php /*echo $this->common_data['company_data']['company_address1'] . ' ' . $this->common_data['company_data']['company_address2'] . ' ' . $this->common_data['company_data']['company_city'] . ' ' . $this->common_data['company_data']['company_country']; */ ?></p>
                            <h4>Dispatch Note</h4>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DPN Number</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['documentSystemCode']; */ ?></td>
                    </tr>
                    <tr>
                        <td><strong>DPN Date</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['documentDate']; */ ?></td>
                    </tr>
                    <tr>
                        <td><strong>Issued From</strong></td>
                        <td><strong>:</strong></td>
                        <td>
                            <?php /*if ($extra['master']['dispatchType'] == 1) {
                                echo 'Direct';
                            } else {
                                echo 'Load Change';
                            }; */ ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Reference Number</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['referenceNo']; */ ?></td>
                    </tr>
                    <tr>
                        <td><strong>Location</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['wareHouseLocation']; */ ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <table>
        <tbody>
        <tr>
            <td style="width:20%;"><strong>Farmer</strong></td>
            <td style="width:5%;"><strong>:</strong></td>
            <td style="width:25%;"><?php /*echo $extra['master']['farmName']; */ ?></td>
            <td style="width:20%;"><strong>Delivered Date </strong></td>
            <td style="width:5%;"><strong>:</strong></td>
            <td style="width:25%;"><?php /*echo $extra['master']['dispatchedDate']; */ ?></td>
        </tr>
        <tr>
            <td style="width:20%;"><strong>Address </strong></td>
            <td style="width:5%;"><strong>:</strong></td>
            <td style="width:25%;"><?php /*echo $extra['master']['farmAddress']; */ ?></td>
            <td style="width:20%;"><strong>Currency </strong></td>
            <td style="width:5%;"><strong>:</strong></td>
            <td style="width:25%;"><?php /*echo $extra['master']['CurrencyDes'] . ' ( ' . $extra['master']['transactionCurrency'] . ' )'; */ ?></td>
        </tr>
        <tr>
            <td style="width:20%;"><strong>Phone</strong></td>
            <td style="width:5%;"><strong>:</strong></td>
            <td style="width:25%;"><?php /*echo $extra['master']['farmTelephone']; */ ?></td>
            <td style="width:20%;"><strong>Narration </strong></td>
            <td style="width:5%;"><strong>:</strong></td>
            <td style="width:25%;"><?php /*echo $extra['master']['Narration']; */ ?></td>
        </tr>
        </tbody>
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
                            <img alt="Logo" style="height: 130px" src="<?php /*echo mPDFImage.$this->common_data['company_data']['company_logo']; */ ?>">
                        </td>
                    </tr>
                </table>
            </td>-->
            <td>
                <table>
                    <tr>
                        <td style="text-align: center;">
                            <!--<h3><strong><?php /*echo $this->common_data['company_data']['company_name']; */ ?>.</strong></h3>
                            <p><?php /*echo $this->common_data['company_data']['company_address1'].' '.$this->common_data['company_data']['company_address2'].' '.$this->common_data['company_data']['company_city'].' '.$this->common_data['company_data']['company_country']; */ ?></p>
                            <br>-->
                            <h4>Dispatch Note</h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td><strong>DPN Number</strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['documentSystemCode']; ?></td>

            <td><strong>DPN Date</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['documentDate']; ?></td>
        </tr>
        <tr>
            <td><strong>Issued From</strong></td>
            <td><strong>:</strong></td>
            <td>
                <?php if ($extra['master']['dispatchType'] == 1) {
                    echo 'Direct';
                } else {
                    echo 'Load Change';
                }; ?>
            </td>

            <td><strong>Reference Number</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['referenceNo']; ?></td>
        </tr>
        <tr>
            <td><strong>Location</strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['wareHouseLocation']; ?></td>

            <td><strong>Farmer</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['farmName']; ?></td>
        </tr>
        <tr>
            <td><strong>Delivered Date </strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['dispatchedDate']; ?></td>

            <td><strong>Address</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['farmAddress']; ?></td>
        </tr>
        <tr>
            <td><strong>Currency </strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['CurrencyDes'] . ' ( ' . $extra['master']['transactionCurrency'] . ' )'; ?></td>

            <td><strong>Phone</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['farmTelephone']; ?></td>
        </tr>
        <tr>
            <td><strong>Narration </strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['Narration']; ?></td>

            <td><strong>Batch </strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['batchCode']; ?></td>
        </tr>


        </tbody>
    </table>
</div>

<br>
<div class="table-responsive">
    <table id="add_new_grv_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="min-width: 10%">Item Code</th>
            <th style="min-width: 15%">Item Description</th>
            <th style="min-width: 10%">UOM</th>
            <th style="min-width: 5%">Qty</th>
        </tr>
        </thead>
        <tbody id="grv_table_body">
        <?php $requested_total = 0;
        $received_total = 0;
        if (!empty($extra['detail'])) {
            for ($i = 0; $i < count($extra['detail']); $i++) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . $extra['detail'][$i]['itemSystemCode'] . '</td>';
                echo '<td>' . $extra['detail'][$i]['itemDescription'] . '</td>';
                echo '<td class="text-center">' . $extra['detail'][$i]['defaultUOM'] . '</td>';
                echo '<td class="text-right">' . $extra['detail'][$i]['qty'] . '</td>';
            }
        } else {
            echo '<tr class="danger"><td colspan="10" class="text-center"><b>No Records Found</b></td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<br>

<div class="row">
    <h6 class="modal-title" align="center">Feed Summary</h6>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed table-row-select" style="width: 50%"align="right">
            <tbody>
            <thead>
            <tr>
                <th style="min-width: 5px">#</th>
                <th style="min-width: 50px">Item</th>
                <th style="min-width: 50px">Qty R</th>
                <th style="min-width: 10px">Qty D</th>
            </tr>
            </thead>
            <?php
            if (!empty($feed_header)) {
                $x=1;
                $qtrtot =0;
                foreach ($feed_header as $row) {
                    $qtrtot = ($row["feedAmount"] * $totalChicksGiven) / 50;

                    echo "<tr>";
                    echo "<td>" . $x. "</td>";
                    echo "<td>" . $row['feedName']. "</td>";
                    echo "<td style='text-align: right'>" .round($qtrtot) . "</td>";
                    echo "<td style='text-align: right'>" .number_format($row['qtyD']). "</td>";
                    echo "</tr>";


                    $x++;
                }

            } else {
                echo '<tr class="danger"><td colspan="10" class="text-center"><b>No Records Found</b></td></tr>';
            }
            ?>

            </tbody>
            <tfoot>

            </tfoot>
        </table>

    </div>
</div>

<br>
<div class="table-responsive">
    <table style="width: 100%">
        <tr>
            <td style="width:50%;">
                <?php if ($extra['master']['confirmedYN'] && $extra['master']['approvedYN'] != 1) { ?>
                    <table style="width: 100%">
                        <tbody>
                        <tr>
                            <td><strong>Confirmed By </strong></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['master']['confirmedByName']; ?>
                                / <?php echo $extra['master']['confirmedDate']; ?> </td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </td>
            <td style="width:70%;">
                &nbsp;
            </td>
        </tr>
    </table>
</div>
<br>
<div class="table-responsive">
    <table style="width: 100%">
        <tr>
            <td style="width:50%;">
                <?php if ($extra['master']['approvedYN']) { ?>
                    <table style="width: 100%">
                        <tbody>
                        <tr>
                            <td><strong>Approved By </strong></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['master']['approvedbyEmpName']; ?>
                                / <?php echo $extra['master']['approvedDate']; ?> </td>

                        </tr>
                        <tr>
                            <td><strong>Confirmed By </strong></td>
                            <td><strong>:</strong></td>
                            <td><?php echo $extra['master']['confirmedByName']; ?>
                                / <?php echo $extra['master']['confirmedDate']; ?> </td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </td>
            <td style="width:70%;">
                &nbsp;
            </td>
        </tr>
    </table>
</div>
<br><br><br>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width: 33%;text-align: center;">
                <span>.....................................</span><br><br><span><b>&nbsp; Prepared</b></span>
            </td>
            <td style="width: 33%;text-align: center;">
                <span>.....................................</span><br><br><span><b>&nbsp; Approved</b></span>
            </td>
            <td style="width: 33%;text-align: center;">
                <span>.....................................</span><br><br><span><b>&nbsp; Received</b></span>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    $('.review').removeClass('hide');
    a_link = "<?php echo site_url('Buyback/load_dispatchNote_confirmation'); ?>/<?php echo $extra['master']['dispatchAutoID'] ?>";
    de_link = "<?php echo site_url('Buyback/fetch_double_entry_buyback_dispatchNote'); ?>/" + <?php echo $extra['master']['dispatchAutoID'] ?> +'/BBDPN';
    $("#a_link").attr("href", a_link);
    $("#de_link").attr("href", de_link);
</script>