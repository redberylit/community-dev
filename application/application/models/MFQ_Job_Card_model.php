<?php

class MFQ_Job_Card_model extends ERP_Model
{
    function fetch_material()
    {
        $dataArr = array();
        $dataArr2 = array();
        $companyID = current_companyID();
        $search_string = "%" . $_GET['query'] . "%";
        $sql = 'SELECT mfqCategoryID,mfqSubcategoryID,secondaryItemCode,mfqSubSubCategoryID,itemSystemCode,costGLCode,defaultUnitOfMeasure,defaultUnitOfMeasureID,itemDescription,mfqItemID as itemAutoID,currentStock,companyLocalWacAmount,companyLocalSellingPrice, CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN "RM" WHEN 2 THEN "FG" WHEN 3 THEN "SF"
END," - ",IFNULL(itemDescription,"")," (",IFNULL(itemSystemCode,""),")") AS "Match",partNo,srp_erp_unit_of_measure.unitDes as uom FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID WHERE (itemSystemCode LIKE "' . $search_string . '" OR itemDescription LIKE "' . $search_string . '" OR secondaryItemCode LIKE "' . $search_string . '") AND srp_erp_mfq_itemmaster.companyID = "' . $companyID . '" AND isActive="1" LIMIT 20';
        $data = $this->db->query($sql)->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['itemSystemCode'], 'mfqItemID' => $val['itemAutoID'], 'currentStock' => $val['currentStock'], 'uom' => $val['uom'], 'defaultUnitOfMeasureID' => $val['defaultUnitOfMeasureID'], 'companyLocalSellingPrice' => $val['companyLocalSellingPrice'], 'companyLocalWacAmount' => $val['companyLocalWacAmount'], 'partNo' => $val['partNo']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_material_by_id()
    {
        $companyID = current_companyID();
        $mfqItemID = $this->input->post('mfqItemID');
        $sql = 'SELECT *,IFNULL(companyLocalWacAmount,0) as companyLocalWacAmountMod FROM srp_erp_mfq_itemmaster WHERE mfqItemID = ' . $mfqItemID . ' AND srp_erp_mfq_itemmaster.companyID = "' . $companyID . '" AND isActive=1';
        $data = $this->db->query($sql)->row_array();
        return $data;
    }

    function fetch_overhead()
    {
        $dataArr = array();
        $dataArr2 = array();
        $search_string = "%" . $_GET['query'] . "%";
        $data = $this->db->query('SELECT srp_erp_mfq_overhead.*,CONCAT(IFNULL(description,""), " (" ,IFNULL(overHeadCode,""),")") AS "Match",IFNULL(srp_erp_mfq_segmenthours.hours,0) as hours  FROM srp_erp_mfq_overhead LEFT JOIN srp_erp_mfq_segmenthours ON srp_erp_mfq_overhead.mfqSegmentID = srp_erp_mfq_segmenthours.mfqSegmentID WHERE overHeadCategoryID = 1 AND  (overHeadCode LIKE "' . $search_string . '" OR description LIKE "' . $search_string . '")')->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['overHeadCode'], 'overHeadID' => $val['overHeadID'], 'description' => $val['description'], 'segment' => $val['mfqSegmentID'], 'rate' => $val['rate'], 'hours' => $val['hours'], 'uom' => $val['unitOfMeasureID']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_machine()
    {
        $dataArr = array();
        $dataArr2 = array();
        $search_string = "%" . $_GET['query'] . "%";
        $data = $this->db->query('SELECT srp_erp_mfq_fa_asset_master.*,CONCAT(IFNULL(assetDescription,""), " (" ,IFNULL(faCode,""),")") AS "Match",IFNULL(srp_erp_mfq_segmenthours.hours,0) as hours,mfqSeg.mfqSegmentID FROM srp_erp_mfq_fa_asset_master LEFT JOIN srp_erp_mfq_category c1 ON mfq_faCatID = c1.itemCategoryID LEFT JOIN srp_erp_mfq_category c2 ON mfq_faSubCatID = c2.itemCategoryID LEFT JOIN srp_erp_mfq_category c3 ON mfq_faSubSubCatID = c3.itemCategoryID LEFT JOIN (SELECT segmentID,mfqSegmentID FROM srp_erp_mfq_segment WHERE companyID = ' . current_companyID() . ') mfqSeg ON mfqSeg.segmentID = srp_erp_mfq_fa_asset_master.segmentID LEFT JOIN srp_erp_mfq_segmenthours ON mfqSeg.mfqSegmentID = srp_erp_mfq_segmenthours.mfqSegmentID WHERE (faCode LIKE "' . $search_string . '" OR assetDescription LIKE "' . $search_string . '")')->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['faCode'], 'mfq_faID' => $val['mfq_faID'], 'description' => $val['assetDescription'], 'segment' => $val['mfqSegmentID'], 'rate' => $val['unitRate'], 'hours' => $val['hours'], 'uom' => $val['unitOfmeasureID']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_labourTask()
    {
        $dataArr = array();
        $dataArr2 = array();
        $search_string = "%" . $_GET['query'] . "%";
        $data = $this->db->query('SELECT srp_erp_mfq_overhead.*,CONCAT(IFNULL(description,""), " (" ,IFNULL(overHeadCode,""),")") AS "Match",IFNULL(srp_erp_mfq_segmenthours.hours,0) as hours  FROM srp_erp_mfq_overhead LEFT JOIN srp_erp_mfq_segmenthours ON srp_erp_mfq_overhead.mfqSegmentID = srp_erp_mfq_segmenthours.mfqSegmentID  WHERE overHeadCategoryID = 2 AND (overHeadCode LIKE "' . $search_string . '" OR description LIKE "' . $search_string . '")')->result_array();
        //echo $this->db->last_query();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['overHeadCode'], 'overHeadID' => $val['overHeadID'], 'description' => $val['description'], 'segment' => $val['mfqSegmentID'], 'rate' => $val['rate'], 'hours' => $val['hours'], 'uom' => $val['unitOfMeasureID']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_jobcard_material_consumption()
    {
        $workProcessID = trim($this->input->post('workProcessID'));
        $jobCardID = trim($this->input->post('jobCardID'));
        $where = "";
        if (isset($_POST["jobCardID"])) {
            $where = "AND jobCardID = $jobCardID";
        }
        $sql = "SELECT 	srp_erp_mfq_jc_materialconsumption.*,CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN 'RM' WHEN 2 THEN 'FG' WHEN 3 THEN 'SF'
END,' - ',srp_erp_mfq_itemmaster.itemDescription) as itemDescription,srp_erp_unit_of_measure.unitDes as uom,IFNULL(partNo,'') as partNo,job.confirmedYN,srp_erp_mfq_itemmaster.itemType,job.linkedJobID,job.documentCode FROM srp_erp_mfq_jc_materialconsumption LEFT JOIN srp_erp_mfq_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_jc_materialconsumption.mfqItemID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID LEFT JOIN (SELECT mfqItemID,confirmedYN,linkedJobID,documentCode FROM srp_erp_mfq_job WHERE linkedJobID = $workProcessID) job ON srp_erp_mfq_jc_materialconsumption.mfqItemID = job.mfqItemID WHERE workProcessID = $workProcessID $where";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function fetch_jobcard_labour_task()
    {
        $workProcessID = trim($this->input->post('workProcessID'));
        $jobCardID = trim($this->input->post('jobCardID'));
        $where = "";
        if (isset($_POST["jobCardID"])) {
            $where = "AND jobCardID = $jobCardID";
        }
        $sql = "SELECT srp_erp_mfq_jc_labourtask.*,srp_erp_mfq_overhead.*,srp_erp_mfq_segment.description as segment,srp_erp_unit_of_measure.unitDes as uom FROM srp_erp_mfq_jc_labourtask LEFT JOIN srp_erp_mfq_overhead ON srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_labourtask.labourTask LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_jc_labourtask.segmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = uomID WHERE workProcessID = $workProcessID $where";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function fetch_jobcard_overhead_cost()
    {
        $workProcessID = trim($this->input->post('workProcessID'));
        $jobCardID = trim($this->input->post('jobCardID'));
        $where = "";
        if (isset($_POST["jobCardID"])) {
            $where = "AND jobCardID = $jobCardID";
        }
        $sql = "SELECT srp_erp_mfq_jc_overhead.*,srp_erp_mfq_overhead.*,srp_erp_mfq_segment.description as segment,srp_erp_unit_of_measure.unitDes as uom FROM srp_erp_mfq_jc_overhead LEFT JOIN srp_erp_mfq_overhead ON srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_overhead.overHeadID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_jc_overhead.segmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = uomID WHERE workProcessID = $workProcessID $where";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function fetch_jobcard_machine_cost()
    {
        $workProcessID = trim($this->input->post('workProcessID'));
        $jobCardID = trim($this->input->post('jobCardID'));
        $where = "";
        if (isset($_POST["jobCardID"])) {
            $where = "AND jobCardID = $jobCardID";
        }
        $sql = "SELECT srp_erp_mfq_jc_machine.*,srp_erp_mfq_fa_asset_master.*,srp_erp_mfq_segment.description as segment,srp_erp_unit_of_measure.unitDes as uom,srp_erp_mfq_jc_machine.segmentID as segment2 FROM srp_erp_mfq_jc_machine LEFT JOIN srp_erp_mfq_fa_asset_master ON srp_erp_mfq_fa_asset_master.mfq_faID = srp_erp_mfq_jc_machine.mfq_faID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_jc_machine.segmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = uomID WHERE workProcessID = $workProcessID $where";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function  save_workprocess_jobcard()
    {
        $save = false;
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_job');
        $this->db->where('linkedJobID', $this->input->post('workProcessID'));
        $outputJob = $this->db->get()->result_array();
        $jobCount = count($outputJob);
        if (!empty($outputJob)) {
            $this->db->select('*');
            $this->db->from('srp_erp_mfq_job');
            $this->db->where('linkedJobID', $this->input->post('workProcessID'));
            $this->db->where('closedYN', 1);
            $outputJob = $this->db->get()->result_array();
            $jobClosedCount = count($outputJob);
            if ($jobCount == $jobClosedCount) {
                $save = true;
            } else {
                $save = false;
            }
        } else {
            $save = true;
        }

        if ($save) {
            $last_id = "";
            $this->db->trans_start();
            if (!$this->input->post('jobCardID')) {
                $this->db->set('jobNo', $this->input->post('jobNo'));
                $this->db->set('bomID', $this->input->post('bomID'));
                $this->db->set('quotationRef', $this->input->post('quotationRef'));
                $this->db->set('description', $this->input->post('description'));
                $this->db->set('workProcessID', $this->input->post('workProcessID'));
                $this->db->set('workFlowID', $this->input->post('workFlowID'));
                $this->db->set('templateDetailID', $this->input->post('templateDetailID'));
                $this->db->set('companyID', current_companyID());
                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserName', current_user());
                $this->db->set('createdDateTime', current_date(true));

                $result = $this->db->insert('srp_erp_mfq_jobcardmaster');
                $last_id = $this->db->insert_id();

                $this->db->set('unitPrice', $this->input->post('unitPrice'));
                $this->db->where('workProcessID', $this->input->post('workProcessID'));
                $result = $this->db->update('srp_erp_mfq_job');

            } else {
                $last_id = $this->input->post('jobCardID');
                $this->db->set('jobNo', $this->input->post('jobNo'));
                $this->db->set('bomID', $this->input->post('bomID'));
                $this->db->set('quotationRef', $this->input->post('quotationRef'));
                $this->db->set('description', $this->input->post('description'));
                $this->db->set('templateDetailID', $this->input->post('templateDetailID'));
                $this->db->set('companyID', current_companyID());
                $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $this->db->set('modifiedUserID', current_userID());
                $this->db->set('modifiedUserName', current_user());
                $this->db->set('modifiedDateTime', current_date(true));
                $this->db->where('jobcardID', $this->input->post('jobCardID'));
                $result = $this->db->update('srp_erp_mfq_jobcardmaster');

                $this->db->set('unitPrice', $this->input->post('unitPrice'));
                $this->db->where('workProcessID', $this->input->post('workProcessID'));
                $result = $this->db->update('srp_erp_mfq_job');
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Job Card Saved Failed ' . $this->db->_error_message());

            } else {

                $jcMaterialConsumptionID = $this->input->post('jcMaterialConsumptionID');
                $mfqItemID = $this->input->post('mfqItemID');
                if (!empty($mfqItemID)) {
                    foreach ($mfqItemID as $key => $val) {
                        if (!empty($jcMaterialConsumptionID[$key])) {
                            $this->db->set('jobCardID', $last_id);
                            $this->db->set('workProcessID', $this->input->post('workProcessID'));

                            $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                            $this->db->set('qtyUsed', $this->input->post('qtyUsed')[$key]);
                            $this->db->set('unitCost', $this->input->post('unitCost')[$key]);
                            $this->db->set('materialCost', $this->input->post('materialCost')[$key]);
                            $this->db->set('usageQty', $this->input->post('usageQty')[$key]);
                            $this->db->set('markUp', $this->input->post('markUp')[$key]);
                            $this->db->set('materialCharge', $this->input->post('materialCharge')[$key]);

                            $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                            $this->db->set('transactionExchangeRate', 1);
                            $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                            $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                            $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                            $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                            $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                            $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                            $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                            $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                            $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('modifiedUserID', current_userID());
                            $this->db->set('modifiedUserName', current_user());
                            $this->db->set('modifiedDateTime', current_date(true));
                            $this->db->where('jcMaterialConsumptionID', $jcMaterialConsumptionID[$key]);
                            $result = $this->db->update('srp_erp_mfq_jc_materialconsumption');
                        } else {
                            if (!empty($mfqItemID[$key])) {
                                $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                                $this->db->set('qtyUsed', $this->input->post('qtyUsed')[$key]);
                                $this->db->set('usageQty', $this->input->post('usageQty')[$key]);
                                $this->db->set('unitCost', $this->input->post('unitCost')[$key]);
                                $this->db->set('materialCost', $this->input->post('materialCost')[$key]);
                                $this->db->set('markUp', $this->input->post('markUp')[$key]);
                                $this->db->set('materialCharge', $this->input->post('materialCharge')[$key]);
                                $this->db->set('jobCardID', $last_id);
                                $this->db->set('workProcessID', $this->input->post('workProcessID'));
                                $this->db->set('companyID', current_companyID());

                                $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                                $this->db->set('transactionExchangeRate', 1);
                                $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                                $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                                $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                                $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                                $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                                $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                                $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                                $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                                $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                $this->db->set('createdUserID', current_userID());
                                $this->db->set('createdUserName', current_user());
                                $this->db->set('createdDateTime', current_date(true));
                                $result = $this->db->insert('srp_erp_mfq_jc_materialconsumption');

                                /*if ($this->input->post('qtyUsed')[$key] != 0) {
                                    $this->db->set('jobID', $this->input->post('workProcessID'));
                                    $this->db->set('jobDetailID', $this->db->insert_id());
                                    $this->db->set('usageAmount', $this->input->post('qtyUsed')[$key]);
                                    $this->db->set('companyID', current_companyID());
                                    $this->db->set('typeID', 1);
                                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                    $this->db->set('createdUserID', current_userID());
                                    $this->db->set('createdUserName', current_user());
                                    $this->db->set('createdDateTime', current_date(true));
                                    $result = $this->db->insert('srp_erp_mfq_jc_usage');
                                }*/
                            }
                        }
                    }
                }


                $jcLabourTaskID = $this->input->post('jcLabourTaskID');
                $labourTask = $this->input->post('labourTask');
                if (!empty($labourTask)) {
                    foreach ($labourTask as $key => $val) {
                        if (!empty($jcLabourTaskID[$key])) {
                            $this->db->set('jobCardID', $last_id);
                            $this->db->set('workProcessID', $this->input->post('workProcessID'));

                            $this->db->set('labourTask', $this->input->post('labourTask')[$key]);
                            /*$this->db->set('activityCode', $this->input->post('la_activityCode')[$key]);*/
                            $this->db->set('uomID', $this->input->post('la_uomID')[$key] == "" ? NULL : $this->input->post('la_uomID')[$key]);
                            $this->db->set('segmentID', $this->input->post('la_segmentID')[$key]);
                            $this->db->set('hourlyRate', $this->input->post('la_hourlyRate')[$key]);
                            $this->db->set('totalHours', $this->input->post('la_totalHours')[$key]);
                            $this->db->set('usageHours', $this->input->post('la_usageHours')[$key]);
                            $this->db->set('totalValue', $this->input->post('la_totalValue')[$key]);

                            $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                            $this->db->set('transactionExchangeRate', 1);
                            $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                            $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                            $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                            $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                            $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                            $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                            $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                            $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                            $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('modifiedUserID', current_userID());
                            $this->db->set('modifiedUserName', current_user());
                            $this->db->set('modifiedDateTime', current_date(true));
                            $this->db->where('jcLabourTaskID', $jcLabourTaskID[$key]);
                            $result = $this->db->update('srp_erp_mfq_jc_labourtask');
                        } else {
                            if (!empty($labourTask[$key])) {
                                $this->db->set('labourTask', $this->input->post('labourTask')[$key]);
                               /* $this->db->set('activityCode', $this->input->post('la_activityCode')[$key]);*/
                                $this->db->set('uomID', $this->input->post('la_uomID')[$key] == "" ? NULL : $this->input->post('la_uomID')[$key]);
                                $this->db->set('segmentID', $this->input->post('la_segmentID')[$key]);
                                $this->db->set('hourlyRate', $this->input->post('la_hourlyRate')[$key]);
                                $this->db->set('totalHours', $this->input->post('la_totalHours')[$key]);
                                $this->db->set('usageHours', $this->input->post('la_usageHours')[$key]);
                                $this->db->set('totalValue', $this->input->post('la_totalValue')[$key]);
                                $this->db->set('jobCardID', $last_id);
                                $this->db->set('workProcessID', $this->input->post('workProcessID'));
                                $this->db->set('companyID', current_companyID());

                                $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                                $this->db->set('transactionExchangeRate', 1);
                                $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                                $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                                $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                                $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                                $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                                $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                                $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                                $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                                $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                $this->db->set('createdUserID', current_userID());
                                $this->db->set('createdUserName', current_user());
                                $this->db->set('createdDateTime', current_date(true));
                                $result = $this->db->insert('srp_erp_mfq_jc_labourtask');

                                /*if ($this->input->post('la_totalHours')[$key] != 0) {
                                    $this->db->set('jobID', $this->input->post('workProcessID'));
                                    $this->db->set('jobDetailID', $this->db->insert_id());
                                    $this->db->set('usageAmount', $this->input->post('la_totalHours')[$key]);
                                    $this->db->set('companyID', current_companyID());
                                    $this->db->set('typeID', 2);
                                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                    $this->db->set('createdUserID', current_userID());
                                    $this->db->set('createdUserName', current_user());
                                    $this->db->set('createdDateTime', current_date(true));
                                    $result = $this->db->insert('srp_erp_mfq_jc_usage');
                                }*/
                            }
                        }
                    }
                }

                $jcOverHeadID = $this->input->post('jcOverHeadID');
                $overHeadID = $this->input->post('overHeadID');
                if (!empty($overHeadID)) {
                    foreach ($overHeadID as $key => $val) {
                        if (!empty($jcOverHeadID[$key])) {
                            $this->db->set('jobCardID', $last_id);
                            $this->db->set('workProcessID', $this->input->post('workProcessID'));

                            $this->db->set('overHeadID', $this->input->post('overHeadID')[$key]);
                            /*$this->db->set('activityCode', $this->input->post('oh_activityCode')[$key]);*/
                            $this->db->set('uomID', $this->input->post('oh_uomID')[$key] == "" ? NULL : $this->input->post('oh_uomID')[$key]);
                            $this->db->set('segmentID', $this->input->post('oh_segmentID')[$key]);
                            $this->db->set('hourlyRate', $this->input->post('oh_hourlyRate')[$key]);
                            $this->db->set('usageHours', $this->input->post('oh_usageHours')[$key]);
                            $this->db->set('totalHours', $this->input->post('oh_totalHours')[$key]);
                            $this->db->set('totalValue', $this->input->post('oh_totalValue')[$key]);

                            $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                            $this->db->set('transactionExchangeRate', 1);
                            $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                            $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                            $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                            $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                            $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                            $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                            $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                            $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                            $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('modifiedUserID', current_userID());
                            $this->db->set('modifiedUserName', current_user());
                            $this->db->set('modifiedDateTime', current_date(true));
                            $this->db->where('jcOverHeadID', $jcOverHeadID[$key]);
                            $result = $this->db->update('srp_erp_mfq_jc_overhead');
                        } else {
                            if (!empty($overHeadID[$key])) {
                                $this->db->set('overHeadID', $this->input->post('overHeadID')[$key]);
                                /*$this->db->set('activityCode', $this->input->post('oh_activityCode')[$key]);*/
                                $this->db->set('uomID', $this->input->post('oh_uomID')[$key] == "" ? NULL : $this->input->post('oh_uomID')[$key]);
                                $this->db->set('segmentID', $this->input->post('oh_segmentID')[$key]);
                                $this->db->set('hourlyRate', $this->input->post('oh_hourlyRate')[$key]);
                                $this->db->set('totalHours', $this->input->post('oh_totalHours')[$key]);
                                $this->db->set('usageHours', $this->input->post('oh_usageHours')[$key]);
                                $this->db->set('totalValue', $this->input->post('oh_totalValue')[$key]);
                                $this->db->set('jobCardID', $last_id);
                                $this->db->set('workProcessID', $this->input->post('workProcessID'));
                                $this->db->set('companyID', current_companyID());

                                $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                                $this->db->set('transactionExchangeRate', 1);
                                $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                                $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                                $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                                $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                                $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                                $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                                $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                                $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                                $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                $this->db->set('createdUserID', current_userID());
                                $this->db->set('createdUserName', current_user());
                                $this->db->set('createdDateTime', current_date(true));
                                $result = $this->db->insert('srp_erp_mfq_jc_overhead');

                                /*if ($this->input->post('oh_totalHours')[$key] != 0) {
                                    $this->db->set('jobID', $this->input->post('workProcessID'));
                                    $this->db->set('jobDetailID', $this->db->insert_id());
                                    $this->db->set('usageAmount', $this->input->post('oh_totalHours')[$key]);
                                    $this->db->set('companyID', current_companyID());
                                    $this->db->set('typeID', 3);
                                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                    $this->db->set('createdUserID', current_userID());
                                    $this->db->set('createdUserName', current_user());
                                    $this->db->set('createdDateTime', current_date(true));
                                    $result = $this->db->insert('srp_erp_mfq_jc_usage');
                                }*/
                            }
                        }
                    }
                }

                $jcMachineID = $this->input->post('jcMachineID');
                $mfq_faID = $this->input->post('mfq_faID');
                if (!empty($mfq_faID)) {
                    foreach ($mfq_faID as $key => $val) {
                        if (!empty($jcMachineID[$key])) {
                            $this->db->set('jobCardID', $last_id);
                            $this->db->set('workProcessID', $this->input->post('workProcessID'));

                            $this->db->set('mfq_faID', $this->input->post('mfq_faID')[$key]);
                            /*$this->db->set('activityCode', $this->input->post('mc_activityCode')[$key]);*/
                            $this->db->set('uomID', $this->input->post('mc_uomID')[$key] == "" ? NULL : $this->input->post('mc_uomID')[$key]);
                            $this->db->set('segmentID', $this->input->post('mc_segmentID')[$key]);
                            $this->db->set('hourlyRate', $this->input->post('mc_hourlyRate')[$key]);
                            $this->db->set('totalHours', $this->input->post('mc_totalHours')[$key]);
                            $this->db->set('usageHours', $this->input->post('mc_usageHours')[$key]);
                            $this->db->set('totalValue', $this->input->post('mc_totalValue')[$key]);

                            $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                            $this->db->set('transactionExchangeRate', 1);
                            $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                            $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                            $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                            $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                            $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                            $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                            $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                            $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                            $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                            $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('modifiedUserID', current_userID());
                            $this->db->set('modifiedUserName', current_user());
                            $this->db->set('modifiedDateTime', current_date(true));
                            $this->db->where('jcMachineID', $jcMachineID[$key]);
                            $result = $this->db->update('srp_erp_mfq_jc_machine');
                        } else {
                            if (!empty($mfq_faID[$key])) {
                                $this->db->set('mfq_faID', $this->input->post('mfq_faID')[$key]);
                                /*$this->db->set('activityCode', $this->input->post('mc_activityCode')[$key]);*/
                                $this->db->set('uomID', $this->input->post('mc_uomID')[$key] == "" ? NULL : $this->input->post('mc_uomID')[$key]);
                                $this->db->set('segmentID', $this->input->post('mc_segmentID')[$key]);
                                $this->db->set('hourlyRate', $this->input->post('mc_hourlyRate')[$key]);
                                $this->db->set('totalHours', $this->input->post('mc_totalHours')[$key]);
                                $this->db->set('usageHours', $this->input->post('mc_usageHours')[$key]);
                                $this->db->set('totalValue', $this->input->post('mc_totalValue')[$key]);
                                $this->db->set('jobCardID', $last_id);
                                $this->db->set('workProcessID', $this->input->post('workProcessID'));
                                $this->db->set('companyID', current_companyID());

                                $this->db->set('transactionCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('transactionCurrency', $this->common_data['company_data']['company_default_currency']);
                                $this->db->set('transactionExchangeRate', 1);
                                $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->common_data['company_data']['company_default_currencyID']));
                                $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                                $default_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_default_currencyID']);
                                $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
                                $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

                                $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                                $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                                $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                                $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                                $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

                                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                $this->db->set('createdUserID', current_userID());
                                $this->db->set('createdUserName', current_user());
                                $this->db->set('createdDateTime', current_date(true));
                                $result = $this->db->insert('srp_erp_mfq_jc_machine');

                                /*if ($this->input->post('mc_totalHours')[$key] != 0) {
                                    $this->db->set('jobID', $this->input->post('workProcessID'));
                                    $this->db->set('jobDetailID', $this->db->insert_id());
                                    $this->db->set('usageAmount', $this->input->post('mc_totalHours')[$key]);
                                    $this->db->set('companyID', current_companyID());
                                    $this->db->set('typeID', 4);
                                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                    $this->db->set('createdUserID', current_userID());
                                    $this->db->set('createdUserName', current_user());
                                    $this->db->set('createdDateTime', current_date(true));
                                    $result = $this->db->insert('srp_erp_mfq_jc_usage');
                                }*/
                            }
                        }
                    }
                }

                if ($this->input->post('status') == 1) {
                    $this->db->set('status', $this->input->post('status'));
                    $this->db->where('workFlowID', $this->input->post('workFlowID'));
                    $this->db->where('jobID', $this->input->post('workProcessID'));
                    $this->db->where('templateDetailID', $this->input->post('templateDetailID'));
                    $result = $this->db->update('srp_erp_mfq_workflowstatus');
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Job Card Saved Failed ' . $this->db->_error_message());

                } else {
                    $this->db->trans_commit();
                    return array('s', 'Job Card Saved Successfully.', $last_id);
                }
            }
        } else {
            return array('w', 'There are pending related job cards to be closed');
        }
    }

    function delete_materialConsumption()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('jcMaterialConsumptionID');
        $this->db->from('srp_erp_mfq_jc_materialconsumption');
        $this->db->where('jobCardID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_jc_materialconsumption', array('jcMaterialConsumptionID' => $this->input->post('jcMaterialConsumptionID')), 1);
        $result2 = $this->db->delete('srp_erp_mfq_jc_usage', array('jobCardID' => $masterID,'jobDetailID' => $this->input->post('jcMaterialConsumptionID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function delete_labour_task()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('jcLabourTaskID');
        $this->db->from('srp_erp_mfq_jc_labourtask');
        $this->db->where('jobCardID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_jc_labourtask', array('jcLabourTaskID' => $this->input->post('jcLabourTaskID')), 1);
        $result2 = $this->db->delete('srp_erp_mfq_jc_usage', array('jobCardID' => $masterID,'jobDetailID' => $this->input->post('jcLabourTaskID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function delete_overhead_cost()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('jcOverHeadID');
        $this->db->from('srp_erp_mfq_jc_overhead');
        $this->db->where('jobCardID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_jc_overhead', array('jcOverHeadID' => $this->input->post('jcOverHeadID')), 1);
        $result2 = $this->db->delete('srp_erp_mfq_jc_usage', array('jobCardID' => $masterID,'jobDetailID' => $this->input->post('jcOverHeadID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function delete_machine_cost()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('jcMachineID');
        $this->db->from('srp_erp_mfq_jc_machine');
        $this->db->where('jobCardID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_jc_machine', array('jcMachineID' => $this->input->post('jcMachineID')), 1);
        $result2 = $this->db->delete('srp_erp_mfq_jc_usage', array('jobCardID' => $masterID,'jobDetailID' => $this->input->post('jcMachineID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function load_data_from_bom()
    {

        $data = array();
        $bomID = $this->input->post("id");
        $qty = $this->input->post("qty");
        $jobID = $this->input->post("jobID");
        $this->db->select("srp_erp_mfq_bom_materialconsumption.*,(qtyUsed * $qty) as qtyUsed,(qtyUsed * $qty) * unitCost  as materialCost,(((qtyUsed * $qty) * unitCost * markUp)/100)+((qtyUsed * $qty) * unitCost) as materialCharge,srp_erp_mfq_itemmaster.itemType,job.confirmedYN,job.linkedJobID,job.documentCode,CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN 'RM' WHEN 2 THEN 'FG' WHEN 3 THEN 'SF'
END,' - ',srp_erp_mfq_itemmaster.itemDescription) as itemDescription,partNo,UnitDes");
        $this->db->from('srp_erp_mfq_bom_materialconsumption');
        $this->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_bom_materialconsumption.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID', 'inner');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID', 'inner');
        $this->db->join("(SELECT mfqItemID,confirmedYN,linkedJobID,documentCode FROM srp_erp_mfq_job WHERE linkedJobID = $jobID) job", 'srp_erp_mfq_bom_materialconsumption.mfqItemID = job.mfqItemID', 'left');
        $this->db->where('bomMasterID', $bomID);
        $result = $this->db->get()->result_array();
        $data["materialConsumption"] = $result;

        $this->db->select("*,(totalHours * $qty) as totalHours,(totalHours * $qty) * hourlyRate as totalValue");
        $this->db->from('srp_erp_mfq_bom_labourtask');
        $this->db->join('srp_erp_mfq_overhead', 'srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_bom_labourtask.labourTask', 'inner');
        $this->db->where('bomMasterID', $bomID);
        $result = $this->db->get()->result_array();
        $data["labourTask"] = $result;

        $this->db->select("*,(totalHours * $qty) as totalHours,(totalHours * $qty) * hourlyRate as totalValue");
        $this->db->from('srp_erp_mfq_bom_overhead');
        $this->db->join('srp_erp_mfq_overhead', 'srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_bom_overhead.overheadID', 'inner');
        $this->db->where('bomMasterID', $bomID);
        $result = $this->db->get()->result_array();
        $data["overheadCost"] = $result;

        $this->db->select("*,(totalHours * $qty) as totalHours,(totalHours * $qty) * hourlyRate as totalValue,srp_erp_mfq_bom_machine.segmentID as segment");
        $this->db->from('srp_erp_mfq_bom_machine');
        $this->db->join('srp_erp_mfq_fa_asset_master', 'srp_erp_mfq_fa_asset_master.mfq_faID = srp_erp_mfq_bom_machine.mfq_faID', 'inner');
        $this->db->where('bomMasterID', $bomID);
        $result = $this->db->get()->result_array();
        $data["machineCost"] = $result;

        return $data;

    }

    function fetch_finish_goods()
    {
        $dataArr = array();
        $dataArr2 = array();
        $companyID = current_companyID();
        $search_string = "%" . $_GET['query'] . "%";
        $sql = 'SELECT mfqCategoryID,mfqSubcategoryID,secondaryItemCode,mfqSubSubCategoryID,itemSystemCode,costGLCode,defaultUnitOfMeasure,defaultUnitOfMeasureID,itemDescription,mfqItemID as itemAutoID,currentStock,companyLocalWacAmount,companyLocalSellingPrice,CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN "RM" WHEN 2 THEN "FG" WHEN 3 THEN "SF"
END," - ",IFNULL(itemDescription,""), " (" ,IFNULL(itemSystemCode,""),")") AS "Match",partNo,srp_erp_unit_of_measure.unitDes as uom FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID WHERE (itemSystemCode LIKE "' . $search_string . '" OR itemDescription LIKE "' . $search_string . '" OR secondaryItemCode LIKE "' . $search_string . '") AND srp_erp_mfq_itemmaster.companyID = "' . $companyID . '" AND (srp_erp_mfq_itemmaster.itemType = 2 OR srp_erp_mfq_itemmaster.itemType = 3)  AND isActive="1" LIMIT 20';
        $data = $this->db->query($sql)->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['itemSystemCode'], 'mfqItemID' => $val['itemAutoID'], 'currentStock' => $val['currentStock'], 'uom' => $val['defaultUnitOfMeasure'], 'defaultUnitOfMeasureID' => $val['defaultUnitOfMeasureID'], 'companyLocalSellingPrice' => $val['companyLocalSellingPrice'], 'companyLocalWacAmount' => $val['companyLocalWacAmount'], 'partNo' => $val['partNo']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_job_detail()
    {
        $data = array();
        $workProcessID = trim($this->input->post('workProcessID'));
        $jobCardID = trim($this->input->post('jobCardID'));
        $where = "";
        if (isset($_POST["jobCardID"])) {
            $where = "AND jobCardID = $jobCardID";
        }
        $sql = "SELECT 	srp_erp_mfq_jc_materialconsumption.*,CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN 'RM' WHEN 2 THEN 'FG' WHEN 3 THEN 'SF'
END,' - ',srp_erp_mfq_itemmaster.itemDescription) as itemDescription,srp_erp_unit_of_measure.unitDes as uom,IFNULL(partNo,'') as partNo,job.confirmedYN,srp_erp_mfq_itemmaster.itemType,job.linkedJobID,job.documentCode FROM srp_erp_mfq_jc_materialconsumption LEFT JOIN srp_erp_mfq_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_jc_materialconsumption.mfqItemID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID LEFT JOIN (SELECT mfqItemID,confirmedYN,linkedJobID,documentCode FROM srp_erp_mfq_job WHERE linkedJobID = $workProcessID) job ON srp_erp_mfq_jc_materialconsumption.mfqItemID = job.mfqItemID WHERE workProcessID = $workProcessID $where";
        $data["material"] = $this->db->query($sql)->result_array();

        $sql = "SELECT srp_erp_mfq_jc_labourtask.*,srp_erp_mfq_overhead.*,srp_erp_mfq_segment.description as segment,srp_erp_unit_of_measure.unitDes as uom FROM srp_erp_mfq_jc_labourtask LEFT JOIN srp_erp_mfq_overhead ON srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_labourtask.labourTask LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_jc_labourtask.segmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = uomID WHERE workProcessID = $workProcessID $where";
        $data["labourTask"] = $this->db->query($sql)->result_array();

        $sql = "SELECT srp_erp_mfq_jc_overhead.*,srp_erp_mfq_overhead.*,srp_erp_mfq_segment.description as segment,srp_erp_unit_of_measure.unitDes as uom FROM srp_erp_mfq_jc_overhead LEFT JOIN srp_erp_mfq_overhead ON srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_overhead.overHeadID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_jc_overhead.segmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = uomID WHERE workProcessID = $workProcessID $where";
        $data["overhead"] = $this->db->query($sql)->result_array();

        $sql = "SELECT srp_erp_mfq_jc_machine.*,srp_erp_mfq_fa_asset_master.*,srp_erp_mfq_segment.description as segment,srp_erp_unit_of_measure.unitDes as uom,srp_erp_mfq_jc_machine.segmentID as segment2 FROM srp_erp_mfq_jc_machine LEFT JOIN srp_erp_mfq_fa_asset_master ON srp_erp_mfq_fa_asset_master.mfq_faID = srp_erp_mfq_jc_machine.mfq_faID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_jc_machine.segmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = uomID WHERE workProcessID = $workProcessID $where";
        $data["machine"] = $this->db->query($sql)->result_array();

        return $data;
    }

    function fetch_po_unit_cost()
    {
        $sql = "SELECT * FROM srp_erp_mfq_itemmaster WHERE mfqItemID =" . $this->input->post("mfqItemID");
        $item = $this->db->query($sql)->row_array();
        $result = "";
        //$gearsDB = $this->load->database('gearserp', true);
        if ($item["itemAutoID"]) {
            /*$sql = "SELECT podet2.unitCost as companyLocalWacAmount FROM erp_purchaseordermaster INNER JOIN (SELECT MAX(erp_purchaseorderdetails.purchaseOrderMasterID) as purchaseOrderMasterID FROM erp_purchaseorderdetails INNER JOIN erp_purchaseordermaster ON erp_purchaseorderdetails.purchaseOrderMasterID = erp_purchaseordermaster.purchaseOrderID WHERE erp_purchaseordermaster.approved=-1 AND erp_purchaseorderdetails.companyID='HEMT' AND documentID='PO' AND itemCode =" . $item["itemAutoID"] . ") podet ON erp_purchaseordermaster.purchaseOrderID = podet.purchaseOrderMasterID INNER JOIN (SELECT * FROM erp_purchaseorderdetails WHERE erp_purchaseorderdetails.companyID='HEMT' AND itemCode =" . $item["itemAutoID"] . ") podet2 ON erp_purchaseordermaster.purchaseOrderID = podet2.purchaseOrderMasterID";
            $result = $gearsDB->query($sql)->row_array();*/
            $sql = "SELECT podet2.totalAmount as companyLocalWacAmount FROM srp_erp_purchaseordermaster INNER JOIN (SELECT MAX(srp_erp_purchaseorderdetails.purchaseOrderID) as purchaseOrderMasterID FROM srp_erp_purchaseorderdetails INNER JOIN srp_erp_purchaseordermaster ON srp_erp_purchaseorderdetails.purchaseOrderID = srp_erp_purchaseordermaster.purchaseOrderID WHERE srp_erp_purchaseordermaster.approvedYN=1 AND srp_erp_purchaseorderdetails.companyID=".current_companyID()." AND itemAutoID =" . $item["itemAutoID"] . ") podet ON srp_erp_purchaseordermaster.purchaseOrderID = podet.purchaseOrderMasterID INNER JOIN (SELECT * FROM srp_erp_purchaseorderdetails WHERE srp_erp_purchaseorderdetails.companyID=".current_companyID()." AND itemAutoID =" . $item["itemAutoID"] . ") podet2 ON srp_erp_purchaseordermaster.purchaseOrderID = podet2.purchaseOrderID";
            $result = $this->db->query($sql)->row_array();
        } else {
            $result = array("companyLocalWacAmount" => 0);
        }
        return $result;

    }
}
