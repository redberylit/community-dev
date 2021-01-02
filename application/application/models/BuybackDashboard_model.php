<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BuybackDashboard_model extends ERP_Model
{
    function __contruct()
    {
        parent::__contruct();
        $this->load->helper('buyback_helper');
    }

    function buybackDashSum_Count()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $farm_count = $this->db->query("SELECT COUNT(*) as activeFarmCount FROM srp_erp_buyback_farmmaster where companyID = '{$companyID}' AND isActive=1")->row_array();
        $batch_count = $this->db->query("SELECT COUNT(*) as activeBatchesCount FROM srp_erp_buyback_batch where companyID = '{$companyID}' AND isclosed='0'")->row_array();

        $data['farms'] = $farm_count['activeFarmCount'];
        $data['batches'] = $batch_count['activeBatchesCount'];

        $batchID = $this->db->query("SELECT batchMasterID FROM srp_erp_buyback_batch where companyID = '{$companyID}' AND isclosed='1' ORDER BY batchMasterID ASC ")->result_array();

        $a = 0;
        $ind = 0;
        $index = 0;
        $profBatch = array();
        $lossBatch = array();
        $b = 0;
        $aa = 1;

        foreach ($batchID as $key){
            $this->db->select('sum( totalTransferAmountTransaction ) AS totalTransferAmountTransaction');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $key['batchMasterID']);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->order_by("buybackItemType ASC");
            $dispatch = $this->db->get()->row_array();

            $this->db->select('sum( pvd.transactionAmount ) AS transactionAmount');
            $this->db->from("srp_erp_buyback_paymentvoucherdetail pvd");
            $this->db->join('srp_erp_buyback_paymentvouchermaster pvm', 'pvd.pvMasterAutoID = pvm.pvMasterAutoID', 'LEFT');
            $this->db->where("pvd.BatchID", $key['batchMasterID']);
            $this->db->where("pvd.companyID", $companyID);
            $this->db->where("pvd.type", 'Expense');
            $this->db->where("pvm.approvedYN", 1);
            $this->db->order_by("pvDetailID DESC");
            $expense = $this->db->get()->row_array();

            $grandTotalrptAmount = $dispatch['totalTransferAmountTransaction'] + $expense['transactionAmount'];

            $this->db->select('sum( totalTransferAmountLocal ) AS totalTransferAmountLocal');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $key['batchMasterID']);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBGRN');
            $this->db->order_by("itemLedgerAutoID ASC");
            $buyback = $this->db->get()->row_array();

            $this->db->select('sum( disreturn.totalTransferCost ) AS totalTransferCost');
            $this->db->from("srp_erp_buyback_dispatchreturn returnmaster ");
            $this->db->join('srp_erp_buyback_dispatchreturndetails disreturn','disreturn.returnAutoID = returnmaster.returnAutoID','left');
            $this->db->join('srp_erp_buyback_dispatchnote dismaster','dismaster.dispatchAutoID = disreturn.dispatchAutoID','left');
            $this->db->where("returnmaster.batchMasterID", $key['batchMasterID']);
            $this->db->where("returnmaster.companyID", $companyID);
            $this->db->where("returnmaster.approvedYN", 1);
            $this->db->where("returnmaster.confirmedYN", 1);
            $return = $this->db->get()->row_array();

            $grandTotalBuybackAmount = $buyback['totalTransferAmountLocal'] + $return['totalTransferCost'];

            if($grandTotalBuybackAmount >= $grandTotalrptAmount){
                $a += 1;
                $profBatch[$ind++]= $key['batchMasterID'];

            } else{
                $b += 1;
                $lossBatch[$index++]= $key['batchMasterID'];

            }

            $aa ++;
        }
        $data['profit'] = $a;
        $data['loss'] = $b;
        $data['Profitid'] = $profBatch;
        $data['Lossid'] = $lossBatch;

        return $data;
    }

    function feedScheduleCalendar($feed)
    {
        $convertFormat = convert_date_format_sql();
        $companyID = current_companyID();

        $batchID = $this->db->query("SELECT batchMasterID FROM srp_erp_buyback_batch where companyID = '{$companyID}' AND isclosed='0' ORDER BY batchMasterID ASC ")->result_array();
        $aa = 1;
        $id = array();
        $idCount = 0;
        foreach ($batchID as $key) {
            $this->db->select('*, DATE_FORMAT(dpm.documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2');
            $this->db->where("batchMasterID", $key['batchMasterID']);
            $this->db->where("dpm.companyID", $companyID);
            $this->db->order_by("dpm.documentDate ASC");
            $dispatch = $this->db->get()->result_array();

            $this->db->select("sum(qty) AS chicksTotal");
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1', 'LEFT');
            $this->db->where("batchMasterID", $key['batchMasterID']);
            $this->db->where("dpm.companyID", $companyID);
            $chicks = $this->db->get()->row_array();

            $this->db->select('DATE_FORMAT(dpm.documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2');
            $this->db->where("batchMasterID", $key['batchMasterID']);
            $this->db->where("dpm.companyID", $companyID);
            $this->db->order_by("dpm.documentDate ASC");
            $dispatchFirstDate = $this->db->get()->row_array();

            if(($chicks['chicksTotal'])==''){
                $chicks['chicksTotal'] = 1;
            }

            $balanceFeed = 0;
            $a = 1;
            foreach ($dispatch as $row) {
                if ($balanceFeed == 0) {
                    $balanceFeed = $row["qty"];
                } else {
                    $balanceFeed += $row["qty"];
                }
                $cumalativeFeed = (($balanceFeed * 50) / $chicks['chicksTotal'])*1000;

                $currentAgeCalculation = $this->db->query("SELECT max(age) as currentAge FROM srp_erp_buyback_feedscheduledetail WHERE companyID = {$companyID} AND totalAmount <= {$cumalativeFeed} ")->row_array();

                if (!empty($currentAgeCalculation)) {
                    $currentAgeCalculation_days = $currentAgeCalculation['currentAge'];
                    $nextInputDay = strtotime("+ $currentAgeCalculation_days day", strtotime($dispatchFirstDate['documentDate']));
                    $format_nextInputDay = date("d-m-Y", $nextInputDay);
                }

                if($format_nextInputDay == $feed){
                    $array['id'] = $key['batchMasterID'];
                    $array['chicks'] = $chicks['chicksTotal'];
                    $id[$idCount++] = $array;
                }

                $a++ ;
            }

            $aa++;
        }

        $feedTypes = $this->db->query("SELECT feedScheduleID,feedAmount,ft.description as feedName,CONCAT(startDay, ' - ', endDay) as changedDate,buybackFeedtypeID FROM srp_erp_buyback_feedschedulemaster fsm LEFT JOIN srp_erp_buyback_feedtypes ft ON fsm.feedTypeID = ft.buybackFeedtypeID WHERE fsm.companyID = {$companyID} ORDER BY feedScheduleID ASC")->result_array();
        $b = 1;
        if(!empty($id) && !empty($feedTypes)){
            $dataarray = array();
            foreach ($id as $keydata) {
                foreach ($feedTypes as $feed) {
                    $booster = ($feed["feedAmount"] * $keydata['chicks']) / 50;

                    if ($booster < 0) {
                        $boost = round($booster);
                    } else {
                        $boost = round($booster);
                    }
                    $data['type'] = $feed['feedName'];
                    $data['feed'] = $boost;
                    $dataarray[$idCount++] = $data;
                }

                if(!empty($dataarray)){
                    foreach ($dataarray as $key){
                        echo '<div class="col-sm-6">
                            <div class="clearfix">
                                <span class="pull-left" style="padding: 10px">';
                        echo trim($key['type']),': ', trim($key['feed']);
                        echo ' </span>
</div>
</div>';
                        $b++;
                    }
                }
            }
        }
        else{
            foreach ($feedTypes as $feed) {
                echo '<div class="col-sm-6">
                            <div class="clearfix">
                                <span class="pull-left" style="padding: 10px">';
                echo trim($feed['feedName']),': ', 0;
                echo ' </span>
</div>
</div>';
                $b++;
            }
        }
    }

    function FarmLogData($ageFrom,$ageTo){
        $this->db->select('srp_erp_buyback_batch.batchMasterID AS batchID,srp_erp_buyback_batch.isclosed ,batchCode,srp_erp_buyback_farmmaster.description, COALESCE(sum(srp_erp_buyback_dispatchnotedetails.qty), 0) AS chicksTotal', false)
            ->from('srp_erp_buyback_batch')
            ->join('srp_erp_buyback_farmmaster', 'srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_batch.farmID','LEFT')
            ->join('srp_erp_buyback_dispatchnote', 'srp_erp_buyback_dispatchnote.batchMasterID = srp_erp_buyback_batch.batchMasterID','LEFT')
            ->join('srp_erp_buyback_dispatchnotedetails', 'srp_erp_buyback_dispatchnotedetails.dispatchAutoID = srp_erp_buyback_dispatchnote.dispatchAutoID AND buybackItemType = 1','INNER')
            ->where('srp_erp_buyback_batch.companyID', $this->common_data['company_data']['company_id'])
            ->where('srp_erp_buyback_batch.isclosed', 0)
            ->group_by('srp_erp_buyback_batch.batchMasterID');
        $farmLogTableData =  $this->db->get()->result_array();

       echo '<table id="tble_farmLog" class="table table-striped table-condensed">';
        echo '<thead>
                                <tr>
                                    <th style="min-width: 2%">#</th>
                                    <th style="min-width: 12%">Farm</th>
                                    <th style="min-width: 12%">Batch</th>
                                    <th style="min-width: 12%">Input</th>
                                    <th style="min-width: 12%">Balance</th>
                                    <th style="min-width: 3%">Age</th>
                                </tr>
                                </thead>';

        if(!empty($farmLogTableData)){
            echo '<tbody>';
            $a = 1;
            foreach ($farmLogTableData as $var){
                $balance = chicks_balance_dashboard($var['batchID'], $var['chicksTotal']);
                $age = chicks_age_dashboard($var['batchID'],$ageFrom,$ageTo);

                if(!empty($age)){
                    echo '<tr class="task-cat-upcoming">
                                           <td>';
                    echo $a ;
                    echo '</td><td>';
                    echo $var['description'];
                    echo '</td><td>';
                    echo  $var['batchCode'];
                    echo '</td><td>';
                    echo  $var['chicksTotal'];
                    echo '</td><td>';
                    echo  $balance;
                    echo '</td><td>';
                    echo  $age;
                    echo '</td>
                </tr>';
                }
                $a ++;
            }
            echo '</tbody>
</table>';

        }
    }

    function fetchBatchProfitLossData(){
        $companyID = $this->common_data['company_data']['company_id'];
        $idset = $this->input->post('id');
        $ids = explode(',', $idset);
        $a = 1;
        $b = 0;
        $totalAmount = 0;

        $farm = $this->input->post('farmerid');
        if(empty($farm)){
            $farmer = 0;
        }else{
            $farmer = "'" . implode("', '", $farm) . "'";
        }

        $current_date = current_format_date();
        $date_format_policy = date_format_policy();

        $date_To = $this->input->post('date_To');
        $date_from = $this->input->post('date_from');
        $datefromconvert = input_format_date($date_from, $date_format_policy);
        $datetoconvert = input_format_date($date_To, $date_format_policy);
        $date = "";
        if (!empty($date_from) && !empty($date_To)) {
            $date .= " AND ( batchStartDate BETWEEN '" . $datefromconvert . "' AND '" . $datetoconvert . " ')";
        }

   //     var_dump($datefromconvert,$date_from);
        var_dump($ids);

        foreach ($ids as $id) {
                $batchData = $this->db->query("SELECT srp_erp_buyback_batch.batchMasterID, srp_erp_buyback_batch.batchCode, srp_erp_buyback_batch.batchStartDate, srp_erp_buyback_farmmaster.description, COALESCE(sum(srp_erp_buyback_dispatchnotedetails.qty), 0) AS chicksTotal 
                                                    FROM srp_erp_buyback_batch 
                                                    LEFT JOIN srp_erp_buyback_farmmaster ON srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_batch.farmID 
                                                    LEFT JOIN srp_erp_buyback_dispatchnote ON srp_erp_buyback_dispatchnote.batchMasterID = srp_erp_buyback_batch.batchMasterID 
                                                    INNER JOIN srp_erp_buyback_dispatchnotedetails ON srp_erp_buyback_dispatchnotedetails.dispatchAutoID = srp_erp_buyback_dispatchnote.dispatchAutoID AND buybackItemType = 1 
                                                    where srp_erp_buyback_batch.companyID = '{$companyID}' AND srp_erp_buyback_batch.isclosed='1' $date AND srp_erp_buyback_batch.batchMasterID = $id                                                 
                                                    AND srp_erp_buyback_farmmaster.farmID IN ($farmer)")->row_array();

                if ($batchData['batchMasterID'] != '') {
                    $TotbalanceChick = $this->db->query("SELECT COALESCE(sum(srp_erp_buyback_grndetails.noOfBirds), 0) AS balanceChicksTotal
                                                    FROM srp_erp_buyback_grn 
                                                    INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                                    where batchMasterID = $id ")->row_array();

                    $TotdeadChick = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal
                                                    FROM srp_erp_buyback_mortalitymaster 
                                                    INNER JOIN srp_erp_buyback_mortalitydetails ON srp_erp_buyback_mortalitydetails.mortalityAutoID = srp_erp_buyback_mortalitymaster.mortalityAutoID
                                                    where batchMasterID = $id ")->row_array();

                    $totalChicks = 0;
                    if (!empty($TotbalanceChick)) {
                        $totalChicks = ($batchData['chicksTotal'] - ($TotbalanceChick['balanceChicksTotal'] + $TotdeadChick['deadChicksTotal']));
                    }

                    $wages = wagesPayableAmount($batchData['batchMasterID']);
                    $wagesPayable = $wages['transactionAmount'];
                    $amount = number_format($wagesPayable, 2);


                    $age = chicks_age_dashboard($batchData['batchMasterID'], '', '');
                    if (empty($age)) {
                        $age = 0;
                    }
                    echo '<tr>';
                    echo '<td>';
                    echo $batchData['description'];
                    echo '</td>';
                    echo '<td><a onclick="generateProductionReport_preformance(';
                    echo $batchData['batchMasterID'];
                    echo ')">';
                    echo $batchData['batchCode'];
                    echo '</a>';
                    echo '</td>';
                    echo '<td>';
                    echo $batchData['batchStartDate'];
                    echo '</td>';
                    echo '<td>';
                    echo $batchData['chicksTotal'];
                    echo '</td>';
                    echo '<td>';
                    echo $totalChicks;
                    echo '</td>';
                    echo '<td>';
                    echo $age;
                    echo '</td>';
                    echo '<td class="pull-right">';
                    echo $amount;
                    echo '</td>';
                    echo '</tr>';
                    $totalAmount += $wagesPayable;
                    $b += 1;
                }
                $a++;
            }

        if($b == 0){
            echo '<tr><td colspan="6"><center>No data Found</center></td></tr>';
        }else {
            echo '<hr>';
            echo '<tr>';
            echo '<td colspan="6"><strong>';
            echo 'Total Amount';
            echo '</strong></td>';
            echo '<td class="pull-right reporttotal">';
            echo number_format($totalAmount, 2);
            echo '</td>';
            echo '</tr>';
        }
    }

    function fcr_data(){
        $companyID = $this->common_data['company_data']['company_id'];
        $FinanceYearData = $this->db->query("SELECT YEAR(beginingDate) as year, MONTH(beginingDate) as month, DAY(beginingDate) as day FROM srp_erp_companyfinanceyear WHERE companyID = $companyID AND isCurrent = 1")->row_array();

        $period = "";
        $data_app = array();
        for ($a = 1; $a <= 3; $a++) {
            if ($a == 1){
                $period .= " AND ( srp_erp_buyback_itemledger.YEAR(documentDate) == '" . $FinanceYearData['year'] . "'  ')";
            } elseif ($a == 2){
                $period .= " AND ( srp_erp_buyback_itemledger.YEAR(documentDate) == '" . $FinanceYearData['month'] . "'  ')";
            }else{
                $period .= " AND ( srp_erp_buyback_itemledger.YEAR(documentDate) == '" . $FinanceYearData['day'] . "'  ')";
            }

            $chicksTotal = $this->db->query("SELECT sum( transactionQTY ) AS chicksTotal FROM srp_erp_buyback_itemledger 
                                              LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_itemledger.batchID 
                                              WHERE srp_erp_buyback_itemledger.companyID = $companyID 
                                                AND srp_erp_buyback_itemledger.documentCode = 'BBDPN' 
                                                AND srp_erp_buyback_itemledger.buybackItemType = 1 
                                                AND srp_erp_buyback_batch.isclosed = 1 $period")->row_array();

            $feedTotal = $this->db->query("SELECT sum( transactionQTY ) AS feedTotal 
                                                  FROM srp_erp_buyback_itemledger 
                                                  LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_itemledger.batchID 
                                                  WHERE srp_erp_buyback_itemledger.companyID = $companyID 
                                                    AND `documentCode` = 'BBDPN' 
                                                    AND `buybackItemType` = 2 
                                                    AND srp_erp_buyback_batch.isclosed = 1 $period")->row_array();

            $birdstotalcount = $this->db->query("SELECT sum( noOfBirds ) AS birdstotalcount, sum( transactionQTY ) AS birdskgsweight 
                                                  FROM srp_erp_buyback_itemledger 
                                                  LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_itemledger.batchID 
                                                  WHERE srp_erp_buyback_itemledger.companyID = $companyID
                                                    AND `documentCode` = 'BBGRN' 
                                                    AND srp_erp_buyback_batch.isclosed = 1 $period")->row_array();

            $feedTot = ($chicksTotal['chicksTotal'] + $birdstotalcount['birdstotalcount']) / 2;
            $feedPer = ($feedTot == 0) ? '0' : ($feedTotal['feedTotal'] * 50) / $feedTot;
            $feedPercentage = number_format($feedPer, 2);

            $weightPer = ($birdstotalcount['birdstotalcount'] == 0) ? '0' : ($birdstotalcount['birdskgsweight'] / $birdstotalcount['birdstotalcount']);
            $weightPercentage = round($weightPer, 2);

            $fcrdata = ($weightPercentage == 0) ? '0' : ($feedPercentage / $weightPercentage);
            $data_app['feedRate'] = number_format($fcrdata, 2);
        }
    }

}













/**
 * Created by PhpStorm.
 * User: Safeena
 * Date: 9/12/2018
 * Time: 9:48 AM
 */