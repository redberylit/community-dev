<?php

class BuybackDashboard extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('BuybackDashboard_model');
        $this->load->helper('buyback_helper');
    }

    function buybackDashSum_Count()
    {
        echo json_encode($this->BuybackDashboard_model->buybackDashSum_Count());
    }

    function buybackDashboard_Data()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $data['theme'] = $this->input->post('themeSec');
        $companyFinanceYearID = $this->input->post('FinanceYear');
        $FinanceYearData = $this->db->query("SELECT YEAR(beginingDate) as year FROM srp_erp_companyfinanceyear WHERE companyFinanceYearID = $companyFinanceYearID")->row_array();
        $FinanceYear = $FinanceYearData['year'];
        $financeyear_period = $this->input->post('financeyear_period');
        $period = "";
        if ($financeyear_period) {
            $FinancePeriodData = $this->db->query("SELECT dateFrom,dateTo FROM srp_erp_companyfinanceperiod WHERE companyFinancePeriodID = $financeyear_period")->row_array();
            $period .= " AND ( documentDate BETWEEN '" . $FinancePeriodData['dateFrom'] . "' AND '" . $FinancePeriodData['dateTo'] . " ')";
        }
        $data['month'] = load_dashboard_monthTitle($financeyear_period);

        // Batch Status //
        $input_chicks = $this->db->query("SELECT COALESCE ( sum( qty ), 0 ) AS inputChicks 
                                                FROM srp_erp_buyback_dispatchnote dpm 
                                                INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 
                                                where dpm.companyID  = $companyID AND YEAR(documentDate) = $FinanceYear $period")->row_array();
        $output_chicks = $this->db->query("SELECT
                                      sum( noOfBirds ) AS outputChicks
                                    FROM `srp_erp_buyback_itemledger` 
                                    WHERE `companyID` = $companyID 
                                       AND YEAR(documentDate) = $FinanceYear
                                      AND `documentCode` = 'BBGRN' $period")->row_array();
        $mortality_chicks = $this->db->query("SELECT COALESCE
                                            ( sum( noOfBirds ), 0 ) AS mortalChicks 
                                        FROM srp_erp_buyback_mortalitymaster mm
                                            INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID 
                                        WHERE mm.companyID  = $companyID  AND YEAR(documentDate) = $FinanceYear $period")->row_array();

        $data['input_chicks'] = $input_chicks['inputChicks'];
        $data['output_chicks'] = $output_chicks['outputChicks'];
        $data['mortality_chicks'] = $mortality_chicks['mortalChicks'];
        $data['totalChicksCount'] = $data['input_chicks'] + $data['output_chicks'] + $data['mortality_chicks'];
        // End of Batch Status //

        // Feed Verses Weight Chart //
        $data['scatterPlot'] = $this->db->query("SELECT batchMasterID, batchCode, sum( transactionQTY ) AS feedTotal,srp_erp_buyback_farmmaster.description FROM srp_erp_buyback_itemledger
                                           LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_itemledger.batchID
                                           LEFT JOIN srp_erp_buyback_farmmaster ON srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_batch.farmID
                                            WHERE srp_erp_buyback_itemledger.companyID = $companyID 
                                            AND srp_erp_buyback_itemledger.documentCode = 'BBDPN' AND srp_erp_buyback_itemledger.buybackItemType = 2
                                            AND srp_erp_buyback_batch.isclosed = 1 AND YEAR(srp_erp_buyback_batch.closedDate) = $FinanceYear $period
                                            GROUP BY srp_erp_buyback_batch.batchMasterID")->result_array();

        $data['scatterchick'] = $this->db->query("SELECT batchMasterID, sum( transactionQTY ) AS chicksTotal FROM srp_erp_buyback_itemledger
                                           LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_itemledger.batchID                            
                                            WHERE srp_erp_buyback_itemledger.companyID = $companyID 
                                            AND srp_erp_buyback_itemledger.documentCode = 'BBDPN' 
                                            AND srp_erp_buyback_itemledger.buybackItemType = 1
                                            AND srp_erp_buyback_batch.isclosed = 1 
                                            AND YEAR(srp_erp_buyback_batch.closedDate) = $FinanceYear $period
                                            GROUP BY srp_erp_buyback_batch.batchMasterID")->result_array();

        $data['sactterFeildReport'] = $this->db->query("SELECT documentSystemCode, srp_erp_buyback_farmervisitreportdetails.avgBodyWeight, srp_erp_buyback_farmervisitreportdetails.avgFeedperBird, srp_erp_buyback_batch.batchCode, srp_erp_buyback_farmmaster.description
                                                          FROM srp_erp_buyback_farmervisitreport
                                                          LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_farmervisitreport.batchMasterID = srp_erp_buyback_batch.batchMasterID
                                                          LEFT JOIN srp_erp_buyback_farmmaster ON srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_batch.farmID
                                                          LEFT JOIN srp_erp_buyback_farmervisitreportdetails ON srp_erp_buyback_farmervisitreportdetails.farmerVisitMasterID = srp_erp_buyback_farmervisitreport.farmerVisitID
                                                          WHERE srp_erp_buyback_batch.companyID = $companyID
                                                           AND YEAR(srp_erp_buyback_farmervisitreport.documentDate) = $FinanceYear $period
                                                          GROUP BY srp_erp_buyback_farmervisitreport.farmerVisitID")->result_array();
       // var_dump($data['sactterFeildReport']);

        // End of Feed Verses Weight Chart //

        // column Chart //
        $data['columnMortal'] =  $this->db->query("SELECT sum( noOfBirds ) AS noOfBirds, MONTH(documentDate), YEAR(documentDate) AS documentYear FROM srp_erp_buyback_mortalitymaster LEFT JOIN srp_erp_buyback_mortalitydetails ON srp_erp_buyback_mortalitydetails.mortalityAutoID = srp_erp_buyback_mortalitymaster.mortalityAutoID WHERE srp_erp_buyback_mortalitymaster.companyID = $companyID AND YEAR(documentDate) = $FinanceYear AND confirmedYN = 1 GROUP BY MONTH(documentDate) ORDER BY MONTH(documentDate)")->result_array();

        $data['columnChicks'] =   $this->db->query("SELECT sum( qty ) AS noOfChicks, MONTH(documentDate), YEAR(documentDate) AS documentYear FROM srp_erp_buyback_dispatchnote LEFT JOIN srp_erp_buyback_dispatchnotedetails ON srp_erp_buyback_dispatchnotedetails.dispatchAutoID = srp_erp_buyback_dispatchnote.dispatchAutoID WHERE srp_erp_buyback_dispatchnote.companyID = $companyID AND YEAR(documentDate) = $FinanceYear AND confirmedYN = 1 AND srp_erp_buyback_dispatchnotedetails.buybackItemType = 1 GROUP BY MONTH(documentDate) ORDER BY MONTH(documentDate)")->result_array();

        $data['columnLiveBirds'] =   $this->db->query("SELECT sum( noOfBirds ) AS noOfliveBirds, MONTH(documentDate), YEAR(documentDate) AS documentYear FROM srp_erp_buyback_grn LEFT JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID WHERE srp_erp_buyback_grn.companyID = $companyID AND YEAR(documentDate) = $FinanceYear AND confirmedYN = 1 GROUP BY MONTH(documentDate) ORDER BY MONTH(documentDate)")->result_array();

        // End of column Chart //

        // calendar //
        $data['feedTypes'] = $this->db->query("SELECT feedScheduleID,feedAmount,ft.description as feedName,CONCAT(startDay, ' - ', endDay) as changedDate,buybackFeedtypeID FROM srp_erp_buyback_feedschedulemaster fsm LEFT JOIN srp_erp_buyback_feedtypes ft ON fsm.feedTypeID = ft.buybackFeedtypeID WHERE fsm.companyID = {$companyID} ORDER BY feedScheduleID ASC")->result_array();
        // End Calender //

        // FCR Data //
        $FinanceYearData = $this->db->query("SELECT YEAR(beginingDate) as year, MONTH(beginingDate) as month, DAY(beginingDate) as day FROM srp_erp_companyfinanceyear WHERE companyID = $companyID AND isCurrent = 1")->row_array();
        $period = "";
        $periodMortal = "";
        $index = 0;
        $data_fcr = array();
        $data_mortality = array();
        for ($a = 1; $a <= 3; $a++) {
            if ($a == 1){
                $period .= " AND ( YEAR(srp_erp_buyback_itemledger.documentDate) = '" . $FinanceYearData['year'] . " ')";
            } elseif ($a == 2){
                $period .= " AND ( MONTH(srp_erp_buyback_itemledger.documentDate) = '" . $FinanceYearData['month'] . " ')";
            }else{
                $period .= " AND ( DAY(srp_erp_buyback_itemledger.documentDate) = '" . $FinanceYearData['day'] . " ')";
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
            $data_fcr[$index++] = number_format($fcrdata, 2);
        }
         $data['feedRate'] =$data_fcr;
        // End OF FCR Data //

        // Mortality percentage //
        for ($a = 1; $a <= 3; $a++) {
            if ($a == 1){
                $periodMortal .= " AND ( YEAR(srp_erp_buyback_mortalitymaster.documentDate) = '" . $FinanceYearData['year'] . " ')";
                $period .= " AND ( YEAR(srp_erp_buyback_itemledger.documentDate) = '" . $FinanceYearData['year'] . " ')";
            } elseif ($a == 2){
                $periodMortal .= " AND ( MONTH(srp_erp_buyback_mortalitymaster.documentDate) = '" . $FinanceYearData['month'] . " ')";
                $period .= " AND ( YEAR(srp_erp_buyback_itemledger.documentDate) = '" . $FinanceYearData['month'] . " ')";
            }else{
                $periodMortal .= " AND ( DAY(srp_erp_buyback_mortalitymaster.documentDate) = '" . $FinanceYearData['day'] . " ')";
                $period .= " AND ( YEAR(srp_erp_buyback_itemledger.documentDate) = '" . $FinanceYearData['day'] . " ')";
            }

            $chicksTotal = $this->db->query("SELECT sum( transactionQTY ) AS chicksTotal FROM srp_erp_buyback_itemledger 
                                              LEFT JOIN srp_erp_buyback_batch ON srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_itemledger.batchID 
                                              WHERE srp_erp_buyback_itemledger.companyID = $companyID 
                                                AND srp_erp_buyback_itemledger.documentCode = 'BBDPN' 
                                                AND srp_erp_buyback_itemledger.buybackItemType = 1 
                                                AND srp_erp_buyback_batch.isclosed = 1 $period")->row_array();


            $MortalChickTotal = $this->db->query("SELECT sum( noOfBirds ) AS MortalChicksTotal FROM srp_erp_buyback_mortalitymaster 
                                              INNER JOIN srp_erp_buyback_mortalitydetails ON srp_erp_buyback_mortalitydetails.mortalityAutoID = srp_erp_buyback_mortalitymaster.mortalityAutoID 
                                              WHERE srp_erp_buyback_mortalitymaster.companyID = $companyID 
                                                AND srp_erp_buyback_mortalitymaster.confirmedYN = 1 $periodMortal")->row_array();

            if(!empty($chicksTotal['chicksTotal'])){
                $mortalPer = ($MortalChickTotal['MortalChicksTotal'] / $chicksTotal['chicksTotal'])*100;
                $mortalPer = number_format($mortalPer, 2);
            } else{
                $mortalPer = 0;
            }
            $data_mortality[$index++] = $mortalPer;
            $data['MortalityPercentage'] =$data_mortality;
        }
        // End Of Mortality percentage //


        $qry = "SELECT batch.batchMasterID, wipamt.workinprogressamount as workinprogressamount  FROM srp_erp_buyback_batch batch 
LEFT JOIN srp_erp_chartofaccounts c1 ON c1.GLAutoID = batch.WIPGLAutoID
 LEFT JOIN srp_erp_buyback_dispatchnote disnotemaster ON disnotemaster.batchMasterID = batch.batchMasterID 
 LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID
 LEFT JOIN (SELECT COALESCE( sum( qty ), 0 ) AS chicksTotal,batchMasterID,confirmedYN,approvedYN FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID AND buybackItemType = 1 WHERE dpm.confirmedYN = 1 AND dpm.approvedYN = 1 GROUP BY batchMasterID) chicksTotaltbl ON chicksTotaltbl.batchMasterID = batch.batchMasterID 
 LEFT JOIN (SELECT sum( dpd.totalActualCost ) AS workinprogressamount,dpm.dispatchAutoID,batchMasterID FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID GROUP BY batchMasterID) wipamt ON wipamt.batchMasterID = batch.batchMasterID 
WHERE batch.companyID = $companyID AND chicksTotaltbl.confirmedYN = 1 AND chicksTotaltbl.approvedYN = 1 AND batch.isclosed = 0 AND fm.isActive = 1 GROUP BY batch.batchMasterID";
        $output = $this->db->query($qry)->result_array();

        $WIPAmount = 0;
        foreach ($output as $var){
            $WIPAmount += $var['workinprogressamount'];
        }
        $data['WIPAmount'] = number_format($WIPAmount, 2);

        $this->load->view('system/buyback/ajax/load_saf_buyback_dashboardData', $data);

    }

    function fetch_FarmLog(){
        $ageFrom = $this->input->post('ageFrom');
        $ageTo = $this->input->post('ageTo');

        json_encode($this->BuybackDashboard_model->FarmLogData($ageFrom,$ageTo));

    }

    function feedScheduleCalenderData(){
        $feedUpTo = $this->input->post('feedUpTo');
        $this->form_validation->set_rules('feedUpTo', 'Notice ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
             json_encode($this->BuybackDashboard_model->feedScheduleCalendar($feedUpTo));
        }

    }

    function fetchBatchProfitLoss(){
        $this->form_validation->set_rules("id", 'Batch ID', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->BuybackDashboard_model->fetchBatchProfitLossData());
        }
    }

}



/**
 * Created by PhpStorm.
 * User: Safeena
 * Date: 9/12/2018
 * Time: 9:44 AM
 */