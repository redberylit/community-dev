<style>
    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .label {
        display: inline;
        padding: .2em .8em .3em;
    }

    .actionicon {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #89e68d;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 2px 5px 2px 5px;
        line-height: 14px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .headrowtitle {
        font-size: 11px !important;
        line-height: 20px !important;
        height: 20px !important;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0 25px;
        font-weight: bold;
        text-align: left;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
        background-color: white;
        border-top: 1px solid #ffffff;
    }

    .redfont {
        border: solid 1px #e6e6e0 !important;
        color: #faffff;
        background-color: #ff090e;
    }


    .tableHeader {
             border: solid 1px #e6e6e6 !important;
         }

    .center {
        text-align: center;
    }




</style>
<?php
$companyID = $this->common_data['company_data']['company_id'];
$convertFormat = convert_date_format_sql();

$feedTypes = $this->db->query("SELECT feedScheduleID,feedAmount,ft.description as feedName,CONCAT(startDay, ' - ', endDay) as changedDate,buybackFeedtypeID FROM srp_erp_buyback_feedschedulemaster fsm LEFT JOIN srp_erp_buyback_feedtypes ft ON fsm.feedTypeID = ft.buybackFeedtypeID WHERE fsm.companyID = {$companyID} ORDER BY feedScheduleID ASC")->result_array();

$feedTypesArr = array_column($feedTypes, 'feedScheduleID');
$feedSum = [];
/*echo '<pre>';
print_r($feedTypesArr);
echo '</pre>';*/

//echo $this->db->last_query();

if (!empty($batch)) { ?>
<div class="table-responsive mailbox-messages">
    <table class="table table-hover table-striped">
        <thead style="border: 1px solid #da9393;">
        <tr>
            <th class="headrowtitle tableHeader" rowspan="2">#</th>
            <th class="headrowtitle tableHeader" rowspan="2">Farmer</th>
            <th class="headrowtitle tableHeader" rowspan="2">Batch Code</th>
            <th class="headrowtitle tableHeader center" colspan="5">Chicks</th>
            <th class="headrowtitle tableHeader" rowspan="2">Feed up To</th>
            <th class="headrowtitle tableHeader" rowspan="2">Next Feed</th>
            <?php
            if (!empty($feedTypes)) {
                foreach ($feedTypes as $feed) { ?>
                    <th class="headrowtitle tableHeader center" colspan="3"><?php echo $feed['feedName']; ?></th>
                    <?php
                }
            }
            ?>
            <th class="headrowtitle tableHeader center" colspan="2" rowspan="2">#</th>
        </tr>
        <tr>

            <th class="headrowtitle tableHeader center" style="background-color: #a3ffa9;" title="Input">I</th>
            <th class="headrowtitle tableHeader center" title="Return"  style="background-color: #ffafaf;">R</th>
            <th class="headrowtitle tableHeader center" title="Grn" style="background-color: #c1ffc5;">G</th>
            <th class="headrowtitle tableHeader center" title="Mortality" style="background-color: #ffc9c9;">M</th>
            <th class="headrowtitle tableHeader center" title="Balance" style="background-color:#ddffdf;">B</th>

            <?php
            if (!empty($feedTypes)) {
                foreach ($feedTypes as $feed) { ?>
                    <th class="headrowtitle tableHeader center" title="Required">R</th>
                    <th class="headrowtitle tableHeader center" title="Delivered">D</th>
                    <th class="headrowtitle tableHeader center" title="Balance">B</th>
                    <?php
                }
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $x = 1;
        $chicksTotal = 0;
        $booster = 0;
        $grntot = 0;
        $mortalitychicks = 0;
        $feedBooster = 0;
        $balanceFeedType = 0;
        $format_nextInputDay = '';
        $format_daysBeforeDay = '';
        $sbR = 0;
        $sbD = 0;
        $sbB = 0;
        $frR = 0;
        $frD = 0;
        $frB = 0;
        $chicksreturn = 0;
        $chicksbalance = 0;
        $feedSum = [];
        foreach ($batch as $val) {
            ?>
            <tr>
                <td class="mailbox-star tableHeader" width="5%"><?php echo $x; ?></td>
                <td class="mailbox-star tableHeader" width="10%"><?php echo $val['farmerName']; ?></td>
                <td class="mailbox-star tableHeader" width="10%">
                    <a href="#"
                       onclick="generateProductionReport(<?php echo $val['batchMasterID']; ?>)"><?php echo $val['batchCode'] ?></a>
                </td>




                <td class="mailbox-star tableHeader center" style="background-color: #a8ffad;" width="5%">
                    <?php
                    $chicksTotal = $this->db->query("SELECT COALESCE
	( sum( dpd.qty ), 0 ) AS chicksTotal
FROM
	srp_erp_buyback_dispatchnote dpm
	INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID
WHERE
	dpm.batchMasterID = {$val['batchMasterID']}
	AND dpd.buybackItemType = 1
		AND dpm.approvedYN = 1
	AND dpm.confirmedYN = 1")->row_array();
                    if (!empty($chicksTotal)) {
                        echo $chicksTotal['chicksTotal'];
                        $chicksTotal = $chicksTotal['chicksTotal'];
                    }
                    ?>
                </td>



                <td class="mailbox-star tableHeader center" width="5%" style="background-color: #ffafaf;">


                    <?php
                    $retutnqty = $this->db->query("SELECT COALESCE
	( sum( dpdr.qty ), 0 ) AS returnqty,
	dispatchAutoID,
	dpdr.returnAutoID
	
FROM srp_erp_buyback_dispatchreturndetails dpdr 
LEFT JOIN srp_erp_buyback_dispatchreturn retun on retun.returnAutoID = dpdr.returnAutoID
WHERE 
approvedYN  = 1
AND confirmedYN = 1
AND dpdr.buybackItemType = 1
AND retun.batchMasterID = {$val['batchMasterID']}
GROUP BY dispatchAutoID")->row_array();
                    if (!empty($retutnqty)) {
                        echo $retutnqty['returnqty'];
                        $chicksreturn =  $retutnqty['returnqty'];
                    }else
                    {
                        echo '0';

                    }
                    ?>


                </td>
                <td class="mailbox-star tableHeader center" width="5%" style="background-color: #c1ffc5;">
                    <?php


                    $grn = $this->db->query("SELECT COALESCE
	( sum( grndetail.noOfBirds ), 0 ) AS grn

FROM srp_erp_buyback_grn grn 

LEFT JOIN srp_erp_buyback_grndetails grndetail on grndetail.grnAutoID = grn.grnAutoID
WHERE 
approvedYN  = 1
AND confirmedYN = 1
AND grn.batchMasterID = {$val['batchMasterID']}
")->row_array();



                    if (!empty($grn)) {
                        echo $grn['grn'];
                        $grntot =  $grn['grn'];
                        $chicksbalance = ($chicksTotal) - ($retutnqty['returnqty'] + $grn['grn']) ;
                    }else
                    {
                        echo '0';
                        $chicksbalance = $chicksTotal - $grn['grn'];
                    }
                    ?>



                </td>
                <td class="mailbox-star tableHeader center" width="5%" style="background-color: #ffc9c9;">
                    <?php

            $mortality = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID ={$val['batchMasterID']} AND confirmedYN = 1")->row_array();


                    if (!empty($mortality)) {
                        echo $mortality['deadChicksTotal'];
                        $mortalitychicks =  $mortality['deadChicksTotal'];
                        $chicksbalance = ($chicksTotal) - ($retutnqty['returnqty'] + $grn['grn'] + $mortalitychicks) ;
                    }else
                    {
                        echo '0';
                        $chicksbalance =($chicksTotal) - ($retutnqty['returnqty'] + $grn['grn'] + $mortalitychicks) ;
                    }
                    ?>


                </td>

                <td class="mailbox-star tableHeader center" width="5%" style="background-color:#ddffdf;">
                   <?php

                    echo  $chicksbalance;

                   ?>
                </td>

                <?php
                $nextInputDate = $this->db->query("SELECT DATE_FORMAT(max(dpm.documentDate),'%d-%m-%Y') AS documentDate,sum(qty) AS totalQty FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2 WHERE batchMasterID ={$val['batchMasterID']}")->row_array();

                $dispatchFirstDate = $this->db->query("SELECT DATE_FORMAT(dpm.documentDate, ' . $convertFormat . ') AS documentDate FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2 WHERE batchMasterID ={$val['batchMasterID']} AND dpm.companyID = {$companyID} ORDER BY dpm.documentDate ASC")->row_array();

                if (!empty($nextInputDate)) {
                    if ($chicksTotal != 0) {
                        $cumalativeFeed = ($nextInputDate['totalQty'] * 50) / $chicksTotal;
                        $cal_nextinputDate = $cumalativeFeed * 1000;
                        $currentAgeCalculation = $this->db->query("SELECT max(age) as currentAge FROM srp_erp_buyback_feedscheduledetail WHERE companyID = {$companyID} AND totalAmount <= {$cal_nextinputDate} ")->row_array();
                        if (!empty($currentAgeCalculation)) {
                            $currentAgeCalculation_days = $currentAgeCalculation['currentAge'];
                            $nextInputDay = strtotime("+ $currentAgeCalculation_days day", strtotime($dispatchFirstDate["documentDate"]));
                            $format_nextInputDay = date("d-m-Y", $nextInputDay);

                            $daysBefore_days = ($currentAgeCalculation_days - 4);
                            $daysBefore_date = strtotime("+ $daysBefore_days day", strtotime($dispatchFirstDate["documentDate"]));
                            $format_daysBeforeDay = date("d-m-Y", $daysBefore_date);
                        }
                    }

                }
                ?>
                <td class="mailbox-star tableHeader" width="10%"><?php echo $format_nextInputDay; ?></td>
                <td class="mailbox-star tableHeader" width="10%"><?php echo $format_daysBeforeDay; ?></td>
                <?php
                if (!empty($feedTypes)) {
                    foreach ($feedTypes as $feed) {

                        $feedBooster = $this->db->query("SELECT sum(qty) AS booster, dpdr.returnqty as boosterreturn FROM srp_erp_buyback_dispatchnote dpm LEFT JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID LEFT JOIN ( SELECT COALESCE ( sum( dpdr.qty ), 0 ) AS returnqty, dispatchAutoID,dpdr.returnAutoID
	
FROM srp_erp_buyback_dispatchreturndetails dpdr 
LEFT JOIN srp_erp_buyback_dispatchreturn retun on retun.returnAutoID = dpdr.returnAutoID
WHERE 
approvedYN  = 1
AND confirmedYN = 1
AND dpdr.buybackItemType = 2
AND feedType = {$feed['buybackFeedtypeID']}
GROUP BY dispatchAutoID 

) dpdr ON dpdr.dispatchAutoID = dpd.dispatchAutoID   WHERE batchMasterID ={$val['batchMasterID']} AND dpm.companyID = {$companyID} AND dpm.confirmedYN = 1 AND dpm.approvedYN = 1  AND buybackItemType = 2 AND feedType = {$feed['buybackFeedtypeID']} ")->row_array();
                        if (!empty($feedBooster)) {
                            $feedBooster = $feedBooster['booster'] - $feedBooster['boosterreturn'] ;
                        }
                        $booster = ($feed["feedAmount"] * $chicksTotal) / 50;

                        $balanceFeedType = $booster - $feedBooster;

                        ?>
                        <?php
                        if($booster < 0){
                            echo '<td class="mailbox-star center redfont" width="5%">';
                            echo round($booster);
                            echo '</td>';
                        }
                        else {
                            echo'<td class="mailbox-star center tableHeader" width="5%">';
                            echo round($booster);
                            echo '</td>';
                        }
                        ?>
                        <?php
                        if($feedBooster < 0){
                            echo '<td class="mailbox-star center redfont" width="5%">';
                            echo  round($feedBooster);
                            echo '</td>';
                        }
                        else {
                            echo'<td class="mailbox-star center tableHeader" width="5%">';
                            echo round($feedBooster);
                            echo '</td>';
                        }
                        ?>
                        <?php
                        if($balanceFeedType < 0){
                            echo '<td class="mailbox-star center redfont" width="5%">';
                            echo round($balanceFeedType);
                            echo '</td>';
                        }
                        else {
                            echo'<td class="mailbox-star center tableHeader" width="5%">';
                            echo round($balanceFeedType);
                            echo '</td>';
                        }
                        ?>

                        <?php
                        $feedTypeID = $feed['buybackFeedtypeID'];
                        if (array_key_exists($feedTypeID, $feedSum)) {
                            $feedSum[$feedTypeID]['booster'] = $feedSum[$feedTypeID]['booster'] + round($booster);
                            $feedSum[$feedTypeID]['feedBooster'] = $feedSum[$feedTypeID]['feedBooster'] + round($feedBooster);
                            $feedSum[$feedTypeID]['balanceFeedType'] = $feedSum[$feedTypeID]['balanceFeedType'] + round($balanceFeedType);

                        } else {
                            $feedSum[$feedTypeID]['booster'] = round($booster);
                            $feedSum[$feedTypeID]['feedBooster'] = round($feedBooster);
                            $feedSum[$feedTypeID]['balanceFeedType'] = round($balanceFeedType);

                        }
                    }
                }
                ?>
                <td class="mailbox-star tableHeader center" width="5%" style="text-align: center">
                    <?php if ($val['isclosed'] == 1) { ?>
                        <span class="label label-danger">&nbsp;</span>
                        <?php
                    } else { ?>
                        <span class="label label-success">&nbsp;</span>
                        <?php
                    }
                    ?>
                </td>
                <td class="mailbox-star tableHeader center" width="5%">
                        <span class="pull-right">
                             <a href="#" onclick="feedScheduleReport_view(<?php echo $val['batchMasterID'] ?>)"><i
                                         class="fa fa-bar-chart" aria-hidden="true" title="Feed Shedule Day Wise" rel="tooltip"
                                         style="font-size: 14px"></i>
                             </a>
                        </span>
                </td>
            </tr>
            <?php
            $x++;
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-right sub_total" colspan="10">Total</td>

            <?php
            if(isset($feedTypes)) {
              foreach ($feedTypes as $row) {
                    ?>
                    <td class="text-right total"
                        style="text-align: center;"><?php echo $feedSum[$row['feedScheduleID']]['booster'] ?></td>
                    <td class="text-right total"
                        style="text-align: center;"><?php echo $feedSum[$row['feedScheduleID']]['feedBooster'] ?></td>
                    <td class="text-right total"
                        style="text-align: center;"><?php echo $feedSum[$row['feedScheduleID']]['balanceFeedType'] ?></td>
                <?php }
            }
            ?>
                <td class="text-right sub_total" colspan="2">&nbsp;</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php

} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO BATCHES TO DISPLAY.</div>
    <?php
}
?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

    });

</script>