<?php
$mortalityPercentage = '';
$feedTot = '';
$feedPercentage = '';
$costBird = '';
$weightPercentage = '';
$wagesPayable = '';
$profitBird = '';
$fcr = '';
?>

<?php if (!empty($details)) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('buybackpreformacerpt', 'BuyBack preformace', True, True);
            } ?>
        </div>
    </div>
    <?php
    if ($type == 'html') {
        echo '<div class="row" style="margin-top: 5px">
    <div class="col-md-3" style="left: 2%">
    <table>
        <tr>
            <td><span class="label label-success">&nbsp;</span> Farm Active </td>
            <td><span class="label label-danger">&nbsp;</span> Farm In-Active </td>
        </tr>
    </table>
</div>
</div>';
    } ?>

    <br>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="buybackpreformacerpt">
            <br>
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong>Buyback Preformance Report</strong></div>
            <div style="">
                <table id="tbl_rpt_buybackpreformance" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>

                        <th>Farm</th>
                        <th>Batch Code</th>
                        <th>Start Date</th>
                        <th>Input</th>
                        <th>Output</th>
                        <th>Balance</th>
                        <th>Mor</th>
                        <th>%</th>
                        <th>Feed</th>
                        <th>Cost/Bird</th>
                        <th>Weight</th>
                        <th>Profit/Bird</th>
                        <th>F.C.R</th>
                        <th>Diff</th>
                        <th>Wages</th>
                        <th>Age</th>
                        <th>Staus</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if ($details) {
                    $totalChicks = 0;
                    foreach ($details

                    as $val) {
                    $chicksTotalbatch = $this->db->query("SELECT COALESCE(sum(qty), 0) AS chicksTotal FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 WHERE batchMasterID ={$val['batchMasterID']}")->row_array();
                    $balancechicksTotal = $this->db->query("SELECT COALESCE(sum(grnd.noOfBirds), 0) AS balanceChicksTotal FROM srp_erp_buyback_grn grn INNER JOIN srp_erp_buyback_grndetails grnd ON grnd.grnAutoID = grn.grnAutoID WHERE batchMasterID ={$val['batchMasterID']}")->row_array();
                    $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID ={$val['batchMasterID']}")->row_array();
                    ?>

                    <tr>
                        <td><?php echo $val["farmerName"] ?></td>
                        <td><a href="#"
                               onclick="generateProductionReport_preformance(<?php echo $val['batchMasterID']; ?>)"><?php echo $val['batchCode'] ?></a></td>
                        <td><?php echo $val["batchStartDate"] ?></td>
                        <td style="text-align: right"><?php echo number_format($chicksTotalbatch["chicksTotal"]); ?></td>
                        <td style="text-align: right"><?php
                            if ($val['isclosed'] == 1) {
                                echo number_format($val['birdstotalcount']);
                            } else {
                                echo '-';
                            }


                            ?></td>
                        <td style="text-align: right">  <?php
                            if (!empty($balancechicksTotal)) {
                                $totalChicks = ($chicksTotalbatch['chicksTotal'] - ($balancechicksTotal['balanceChicksTotal'] + $mortalityChicks['deadChicksTotal']));
                                echo $totalChicks;
                            }
                            ?></td>
                        <td style="text-align: right"><?php
                            if ($val["totalBirds"] == 0) {
                                echo '0';
                            }
                            echo $val["totalBirds"]

                            ?></td>
                        <td style="text-align: right">
                            <?php
                            $mortalityPercentage = ($val['chicksTotal'] == 0) ? '0' : ($val['totalBirds'] / $val['chicksTotal']) * 100;
                            echo number_format($mortalityPercentage, 1);
                            ?>
                        </td>
                        <td style="text-align: right">
                            <?php
                            $feedTot = ($val['chicksTotal'] + $val['birdstotalcount']) / 2;
                            $feedPercentage = ($feedTot == 0) ? '0' : ($val['feedTotal'] * 50) / $feedTot;
                            echo number_format($feedPercentage, 2);
                            ?>

                        </td>
                        <td style="text-align: right">
                            <?php
                            $feedTot = ($val['chicksTotal'] + $val['birdstotalcount']) / 2;
                            $costBird = ($feedTot == 0) ? '0' : ($val['grandTotalrptAmount'] / $feedTot);
                            echo number_format($costBird, 2);
                            ?>

                        </td>
                        <td style="text-align: right">
                            <?php
                            $weightPercentage = ($val['birdstotalcount'] == 0) ? '0' : ($val['birdskgsweight'] / $val['birdstotalcount']);
                            echo round($weightPercentage, 2);
                            ?>

                        </td>
                        <td style="text-align: right">
                            <?php
                            $wagesPayable = ($val['grandTotalBuybackAmount'] - $val['grandTotalrptAmount']);
                            $profitBird = ($val['chicksTotal'] == 0) ? '0' : ($wagesPayable / $val['chicksTotal']);
                            echo number_format($profitBird, 2);
                            ?>

                        </td>
                        <td style="text-align: right">
                            <?php
                            $fcr = ($weightPercentage == 0) ? '0' : ($feedPercentage / $weightPercentage);
                            echo number_format($fcr, 2);
                            ?>

                        </td>
                        <td style="text-align: right">
                            <?php echo '0' ?>

                        </td>
                        <td style="text-align: right">
                            <?php

                            $wagesPayable = ($val['grandTotalBuybackAmount'] - $val['grandTotalrptAmount']);
                            echo number_format($wagesPayable,2);
                            ?>

                        </td>
                        <td style="text-align: right">
                            <?php
                            $chicksAge = $this->db->query("SELECT dpm.dispatchedDate,batch.closedDate FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpm.batchMasterID ={$val['batchMasterID']} ORDER BY dpm.dispatchAutoID ASC")->row_array();
                            if (!empty($chicksAge)) {
                                $dStart = new DateTime($chicksAge['dispatchedDate']);
                                if ($chicksAge['closedDate'] != ' ') {
                                    $dEnd = new DateTime($chicksAge['closedDate']);
                                } else {
                                    $dEnd = new DateTime(current_date());
                                }
                                $dDiff = $dStart->diff($dEnd);
                                $newFormattedDate = $dDiff->days + 1;
                                echo $newFormattedDate;
                            }
                            ?>
                        </td>
                        <td style="text-align: center">
                            <?php if ($val['isclosed'] == 1) { ?>
                                <span class="label label-danger">&nbsp;</span>
                                <?php
                            } else { ?>
                                <span class="label label-success">&nbsp;</span>
                                <?php
                            }
                            ?>
                        </td>

                        <?php
                        }
                        } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <br>
    <?php
    if ($type == 'pdf') {
        echo '<table style="width: 40%">
        <tr>
            <td><span class="label label-success">&nbsp;</span> Farm Active </td>
            <td><span class="label label-danger">&nbsp;</span> Farm In-Active </td>
        </tr>
    </table>';
    } ?>

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
    $('#tbl_rpt_buybackpreformance tr').mouseover(function (e) {
        $('#tbl_rpt_buybackpreformance tr').removeClass('highlighted');
        $(this).addClass('highlighted');
    });

    $('#tbl_rpt_buybackpreformance').tableHeadFixer({
        head: true,
        foot: true,
        left: 0,
        right: 0,
        'z-index': 10
    });
</script>