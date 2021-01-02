<?php

class MFQ_Job_model extends ERP_Model
{
    function save_job_header()
    {

        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $format_startdate = input_format_date(trim($this->input->post('startDate')), $date_format_policy);
        $format_enddate = input_format_date(trim($this->input->post('endDate')), $date_format_policy);
        $fromType = $this->input->post('fromType');
        if (!$this->input->post('workProcessID')) {
            $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_job', 'workProcessID', 'companyID');
            $this->db->select('segmentCode');
            $this->db->from("srp_erp_mfq_segment");
            $this->db->where("companyID", current_companyID());
            $this->db->where("mfqSegmentID", $this->input->post('mfqSegmentID'));
            $segmentCode = $this->db->get()->row('segmentCode');
            $codes = $this->sequence->mfq_sequence_generator('JOB', $serialInfo['serialNo'], $segmentCode);
            $this->db->set('description', $this->input->post('description'));
            $this->db->set('workFlowTemplateID', $this->input->post('workFlowTemplateID'));
            $this->db->set('serialNo', $serialInfo['serialNo']);
            $this->db->set('documentCode', $codes);
            $this->db->set('documentDate', date('Y-m-d'));
            $this->db->set('startDate', $format_startdate);
            $this->db->set('endDate', $format_enddate);
            if ($this->input->post('type') == 2) {
                $this->db->set('mfqItemID', $this->input->post('estMfqItemID'));
                $this->db->set('estimateDetailID', $this->input->post('estimateDetailID'));
                $this->db->set('bomMasterID', $this->input->post('bomMasterID'));
            } else {
                $this->db->set('mfqItemID', $this->input->post('mfqItemID'));
            }
            $this->db->set('qty', $this->input->post('qty'));
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('mfqSegmentID', $this->input->post('mfqSegmentID'));
            $this->db->set('type', $this->input->post('type'));
            $this->db->set('mfqWarehouseAutoID', $this->input->post('mfqWarehouseAutoID'));
            $this->db->set('documentID', 'JOB');

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

            $this->db->select("srp_erp_customermaster.customerCurrencyID,srp_erp_customermaster.customerCurrency");
            $this->db->from("srp_erp_mfq_customermaster");
            $this->db->join("srp_erp_customermaster", "srp_erp_mfq_customermaster.CustomerAutoID=srp_erp_customermaster.customerAutoID", "LEFT");
            $this->db->where("mfqCustomerAutoID", $this->input->post('mfqCustomerAutoID'));
            $custInfo = $this->db->get()->row_array();

            $this->db->set('mfqCustomerCurrencyID', $custInfo["customerCurrencyID"]);
            $this->db->set('mfqCustomerCurrency', $custInfo["customerCurrency"]);

            $customer_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $custInfo['customerCurrencyID']);
            $this->db->set('mfqCustomerCurrencyExchangeRate', $customer_currency['conversion']);
            $this->db->set('mfqCustomerCurrencyDecimalPlaces', $customer_currency['DecimalPlaces']);
            $this->db->set('isSaved', 1);

            $this->db->set('companyID', current_companyID());
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));

            $result = $this->db->insert('srp_erp_mfq_job');
            $last_id = $this->db->insert_id();

            $workFlowID = "";
            $workFlowTemplateID = "";
            $description = "";
            $linkWorkflow = "";
            if (isset($_POST["customWorkFlowID"])) {
                $workFlowID = explode(',', $this->input->post("customWorkFlowID"));
                $workFlowTemplateID = explode(',', $this->input->post("customWorkFlowTemplateID"));
                $description = explode(',', $this->input->post("customDescription"));
                $linkWorkflow = $this->input->post("linkProcess");
            }

            $createdJobArr = array();
            $data = array();
            $templateDet = "";
            if ($workFlowID) {
                foreach ($workFlowID as $key => $val) {
                    $data[] = array('jobID' => $last_id, 'workFlowID' => $val, 'workFlowTemplateID' => $workFlowTemplateID[$key], 'sortOrder' => $key + 1, 'description' => $description[$key], 'companyID' => current_companyID(), 'templateMasterID' => $this->input->post('workFlowTemplateID'));
                }
                $this->db->insert_batch("srp_erp_mfq_customtemplatedetail", $data);

                if ($linkWorkflow) {
                    $this->db->query("UPDATE srp_erp_mfq_customtemplatedetail AS cust
LEFT JOIN (
SELECT
	prev_id,
	cur_id 
FROM
	(
SELECT
	templateDetailID AS cur_id,
	( SELECT min( templateDetailID ) FROM srp_erp_mfq_customtemplatedetail WHERE templateDetailID = (cur_id-1) AND jobID = {$last_id} ) AS prev_id 
FROM
	srp_erp_mfq_customtemplatedetail 
WHERE
	jobID = {$last_id} 
	) AS tmp 
WHERE
	prev_id IS NOT NULL
	) target ON target.cur_id = cust.templateDetailID 
	SET cust.linkWorkFlow = target.prev_id WHERE cust.jobID = {$last_id}");
                }

                $this->db->select("*");
                $this->db->from("srp_erp_mfq_customtemplatedetail");
                $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                $this->db->where("jobID", $last_id);
                $templateDet = $this->db->get()->result_array();
                $data = array();
                if ($templateDet) {
                    foreach ($templateDet as $val) {
                        $data[] = array('workFlowID' => $val["workFlowID"], 'templateDetailID' => $val["templateDetailID"], 'jobID' => $last_id, 'companyID' => current_companyID());
                    }
                    $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                }
            } else {
                $this->db->select("*");
                $this->db->from("srp_erp_mfq_templatedetail");
                $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                $templateDet = $this->db->get()->result_array();
                $data = array();
                if ($templateDet) {
                    foreach ($templateDet as $val) {
                        $data[] = array('workFlowID' => $val["workFlowID"], 'templateDetailID' => $val["templateDetailID"], 'jobID' => $last_id, 'companyID' => current_companyID());
                    }
                    $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                }
            }
            if ($fromType == "EST") {
                if ($this->input->post('bomMasterID')) {
                    $this->db->select("*");
                    $this->db->from("srp_erp_mfq_warehousemaster");
                    $this->db->where("mfqWarehouseAutoID", $this->input->post('mfqWarehouseAutoID'));
                    $warehouse = $this->db->get()->row_array();

                    $this->db->select("*");
                    $this->db->from("srp_erp_warehousemaster");
                    $this->db->where("warehouseAutoID", $warehouse["warehouseAutoID"]);
                    $warehouseERP = $this->db->get()->row_array();

                    $this->db->query("INSERT INTO srp_erp_warehouseitems (wareHouseAutoID,wareHouseLocation,wareHouseDescription,itemAutoID,itemSystemCode,itemDescription,unitOfMeasureID,unitOfMeasure,currentStock,companyID,companyCode) SELECT " . $warehouseERP["wareHouseAutoID"] . ",'" . $warehouseERP["wareHouseLocation"] . "','" . $warehouseERP["wareHouseDescription"] . "',srp_erp_itemmaster.itemAutoID,srp_erp_itemmaster.itemSystemCode,srp_erp_itemmaster.itemDescription,srp_erp_itemmaster.defaultUnitOfMeasureID,srp_erp_itemmaster.defaultUnitOfMeasure,0,srp_erp_itemmaster.companyID,srp_erp_itemmaster.companyCode FROM srp_erp_mfq_bom_materialconsumption mc INNER JOIN srp_erp_mfq_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = mc.mfqItemID INNER JOIN srp_erp_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = srp_erp_itemmaster.itemAutoID WHERE NOT EXISTS (SELECT * FROM srp_erp_warehouseitems WHERE srp_erp_warehouseitems.itemAutoID = srp_erp_mfq_itemmaster.itemAutoID AND warehouseAutoID = " . $warehouse["warehouseAutoID"] . ") AND srp_erp_mfq_itemmaster.itemType = 3 AND mc.bomMasterID =" . $this->input->post('bomMasterID'));

                    $this->db->select("srp_erp_mfq_bom_materialconsumption.*,srp_erp_mfq_itemmaster.*,(((IFNULL(srp_erp_warehouseitems.currentStock,0)- IFNULL(jcm.qtyUsed,0)) + IFNULL(jc.qty,0))-(srp_erp_mfq_bom_materialconsumption.qtyUsed* {$this->input->post('qty')})) as remainingQty,srp_erp_mfq_billofmaterial.bomMasterID as bomID");
                    $this->db->from("srp_erp_mfq_bom_materialconsumption");
                    $this->db->join("srp_erp_mfq_itemmaster", "srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                    $this->db->join("srp_erp_warehouseitems", "srp_erp_warehouseitems.itemAutoID = srp_erp_mfq_itemmaster.itemAutoID AND srp_erp_warehouseitems.companyID = " . current_companyID() . " AND srp_erp_warehouseitems.warehouseAutoID =" . $warehouseERP["warehouseAutoID"], "left");
                    $this->db->join("srp_erp_mfq_billofmaterial", "srp_erp_mfq_billofmaterial.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                    $this->db->join("(SELECT SUM(qtyUsed) as qtyUsed,srp_erp_mfq_jc_materialconsumption.mfqItemID FROM srp_erp_mfq_jc_materialconsumption LEFT JOIN srp_erp_mfq_job ON srp_erp_mfq_job.workProcessID = srp_erp_mfq_jc_materialconsumption.workProcessID WHERE approvedYN = 0 AND srp_erp_mfq_jc_materialconsumption.companyID = " . current_companyID() . " GROUP BY srp_erp_mfq_jc_materialconsumption.mfqItemID) jcm", "jcm.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                    $this->db->join("(SELECT SUM(qty) as qty,mfqItemID FROM srp_erp_mfq_job WHERE mfqWarehouseAutoID = " . $this->input->post('mfqWarehouseAutoID') . " AND approvedYN = 0 AND companyID = " . current_companyID() . " GROUP BY mfqItemID) jc", "jc.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");

                    $this->db->where("srp_erp_mfq_bom_materialconsumption.bomMasterID", $this->input->post('bomMasterID'));
                    $this->db->where("itemType", 3);
                    $this->db->where("srp_erp_mfq_itemmaster.mainCategory", "Inventory");
                    $this->db->having("remainingQty < ", 0);
                    $bomDetail = $this->db->get()->result_array();

                    if ($bomDetail) {
                        $i = 1;
                        foreach ($bomDetail as $val) {
                            $codes = $codes . " - " . (str_pad($i, 2, '0', STR_PAD_LEFT));

                            $this->db->set('description', $this->input->post('description'));
                            $this->db->set('workFlowTemplateID', $this->input->post('workFlowTemplateID'));
                            $this->db->set('serialNo', $serialInfo['serialNo']);
                            $this->db->set('documentCode', $codes);
                            $this->db->set('documentDate', date('Y-m-d'));
                            $this->db->set('startDate', $format_startdate);
                            $this->db->set('endDate', $format_enddate);

                            $this->db->set('mfqItemID', $val["mfqItemID"]);
                            $this->db->set('estimateDetailID', $this->input->post('estimateDetailID'));
                            $this->db->set('bomMasterID', $val['bomID']);
                            $this->db->set('isSaved', 1);

                            $this->db->set('qty', abs($val["remainingQty"]));
                            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
                            $this->db->set('mfqSegmentID', $this->input->post('mfqSegmentID'));
                            $this->db->set('type', $this->input->post('type'));
                            $this->db->set('documentID', 'JOB');
                            $this->db->set('companyID', current_companyID());
                            $this->db->set('linkedJobID', $last_id);
                            $this->db->set('mfqWarehouseAutoID', $this->input->post('mfqWarehouseAutoID'));

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

                            $this->db->select("srp_erp_customermaster.customerCurrencyID,srp_erp_customermaster.customerCurrency");
                            $this->db->from("srp_erp_mfq_customermaster");
                            $this->db->join("srp_erp_customermaster", "srp_erp_mfq_customermaster.CustomerAutoID=srp_erp_customermaster.customerAutoID", "LEFT");
                            $this->db->where("mfqCustomerAutoID", $this->input->post('mfqCustomerAutoID'));
                            $custInfo = $this->db->get()->row_array();

                            $this->db->set('mfqCustomerCurrencyID', $custInfo["customerCurrencyID"]);
                            $this->db->set('mfqCustomerCurrency', $custInfo["customerCurrency"]);

                            $customer_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $custInfo['customerCurrencyID']);
                            $this->db->set('mfqCustomerCurrencyExchangeRate', $customer_currency['conversion']);
                            $this->db->set('mfqCustomerCurrencyDecimalPlaces', $customer_currency['DecimalPlaces']);

                            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('createdUserID', current_userID());
                            $this->db->set('createdUserName', current_user());
                            $this->db->set('createdDateTime', current_date(true));
                            $result = $this->db->insert('srp_erp_mfq_job');
                            $last_id2 = $this->db->insert_id();
                            $createdJobArr[] = "Job Card " . $codes . " created for semi finished good items " . $val["itemSystemCode"] . " - " . $val["itemDescription"];
                            if ($workFlowID) {
                                $data = array();
                                foreach ($workFlowID as $key => $val2) {
                                    $data[] = array('jobID' => $last_id2, 'workFlowID' => $val2, 'workFlowTemplateID' => $workFlowTemplateID[$key], 'sortOrder' => $key + 1, 'description' => $description[$key], 'companyID' => current_companyID(), 'templateMasterID' => $this->input->post('workFlowTemplateID'));
                                }
                                $this->db->insert_batch("srp_erp_mfq_customtemplatedetail", $data);

                                if ($linkWorkflow) {
                                    $this->db->query("UPDATE srp_erp_mfq_customtemplatedetail AS cust
LEFT JOIN (
SELECT
	prev_id,
	cur_id 
FROM
	(
SELECT
	templateDetailID AS cur_id,
	( SELECT min( templateDetailID ) FROM srp_erp_mfq_customtemplatedetail WHERE templateDetailID = (cur_id-1) AND jobID = {$last_id2} ) AS prev_id 
FROM
	srp_erp_mfq_customtemplatedetail 
WHERE
	jobID = {$last_id2} 
	) AS tmp 
WHERE 
	prev_id IS NOT NULL
	) target ON target.cur_id = cust.templateDetailID 
	SET cust.linkWorkFlow = target.prev_id WHERE cust.jobID = {$last_id2}");
                                }

                                $this->db->select("*");
                                $this->db->from("srp_erp_mfq_customtemplatedetail");
                                $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                                $this->db->where("jobID", $last_id2);
                                $templateDet = $this->db->get()->result_array();
                                $data = array();
                                if ($templateDet) {
                                    foreach ($templateDet as $val3) {
                                        $data[] = array('workFlowID' => $val3["workFlowID"], 'templateDetailID' => $val3["templateDetailID"], 'jobID' => $last_id2, 'companyID' => current_companyID());
                                    }
                                    $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                                }
                            } else {
                                $this->db->select("*");
                                $this->db->from("srp_erp_mfq_templatedetail");
                                $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                                $templateDet = $this->db->get()->result_array();
                                $data = array();
                                if ($templateDet) {
                                    foreach ($templateDet as $val3) {
                                        $data[] = array('workFlowID' => $val3["workFlowID"], 'templateDetailID' => $val3["templateDetailID"], 'jobID' => $last_id2, 'companyID' => current_companyID());
                                    }
                                    $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                                }
                            }
                            $i++;
                        }
                    }
                }
            }
            $data = array();
            if ($this->input->post('estimateDetailID')) {
                $data = $this->db->query('SELECT estimateCode,ciCode FROM srp_erp_mfq_estimatedetail LEFT JOIN srp_erp_mfq_estimatemaster ON srp_erp_mfq_estimatedetail.estimateMasterID = srp_erp_mfq_estimatemaster.estimateMasterID LEFT JOIN srp_erp_mfq_customerinquiry ON srp_erp_mfq_customerinquiry.ciMasterID = srp_erp_mfq_estimatedetail.ciMasterID WHERE srp_erp_mfq_estimatedetail.estimateDetailID=' . $this->input->post('estimateDetailID'))->row_array();
            } else {
                $data["estimateCode"] = "";
                $data["ciCode"] = "";
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Job saved Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Job saved Successfully.', $last_id, $codes, $data["estimateCode"], $data["ciCode"], $createdJobArr);
            }
        } else {
            $mfqItemID = "";
            if ($this->input->post('type') == 2) {
                $mfqItemID = $this->input->post('estMfqItemID');
            } else {
                $mfqItemID = $this->input->post('mfqItemID');
            }
            $header = $this->load_job_header();
            $itemDetail = get_specific_mfq_item($this->input->post('mfqItemID'));
            $expectedQty = $this->db->query('SELECT expectedQty FROM srp_erp_mfq_estimatedetail WHERE estimateDetailID=' . $this->input->post('estimateDetailID'))->row('expectedQty');
            $qtyExceed = $this->db->query('SELECT SUM(qty) as qty FROM srp_erp_mfq_job WHERE mfqItemID = ' . $mfqItemID . ' AND workProcessID != ' . $this->input->post('workProcessID') . ' AND linkedJobID=' . $header['linkedJobID'])->row('qty');
            $balanceQty = $expectedQty - $qtyExceed;
            if ($itemDetail["mainCategory"] == "Inventory" && (($qtyExceed + $this->input->post('qty')) > $expectedQty) && $header["levelNo"] != 3) {
                return array('w', 'You cannot create more than ' . $balanceQty . ' quantity');
            } else {
                $data['description'] = $this->input->post('description');
                $data['workFlowTemplateID'] = $this->input->post('workFlowTemplateID');
                $data['description'] = $this->input->post('description');
                $data['startDate'] = $format_startdate;
                $data['endDate'] = $format_enddate;
                $data['mfqItemID'] = $this->input->post('mfqItemID');
                if ($this->input->post('type') == 2) {
                    $data['mfqItemID'] = $this->input->post('estMfqItemID');
                    $data['estimateDetailID'] = $this->input->post('estimateDetailID');
                    $data['bomMasterID'] = $this->input->post('bomMasterID');
                } else {
                    $data['mfqItemID'] = $this->input->post('mfqItemID');
                    $data['estimateDetailID'] = null;
                    $data['bomMasterID'] = null;
                }
                $data['qty'] = $this->input->post('qty');
                $data['mfqCustomerAutoID'] = $this->input->post('mfqCustomerAutoID');
                $data['mfqSegmentID'] = $this->input->post('mfqSegmentID');
                $data['type'] = $this->input->post('type');
                $data['mfqWarehouseAutoID'] = $this->input->post('mfqWarehouseAutoID');
                $data['companyID'] = current_companyID();
                $data['modifiedPCID'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);//gethostbyaddr($_SERVER['REMOTE_ADDR']);
                $data['modifiedUserID'] = current_userID();//$this->session->userdata("username");
                $data['modifiedUserName'] = current_user();//$this->session->userdata("username");
                $data['modifiedDateTime'] = current_date(true);

                $this->db->where('workProcessID', $this->input->post('workProcessID'));
                $result = $this->db->update('srp_erp_mfq_job', $data);

                $createdJobArr = array();
                $master = $this->db->query('SELECT * FROM srp_erp_mfq_job WHERE workProcessID=' . $this->input->post('workProcessID'))->row_array();
                if (!$master["isSaved"]) {
                    $data = [];
                    $data['isSaved'] = 1;
                    $this->db->where('workProcessID', $this->input->post('workProcessID'));
                    $result = $this->db->update('srp_erp_mfq_job', $data);

                    $workFlowID = "";
                    $workFlowTemplateID = "";
                    $description = "";
                    $linkWorkflow = "";
                    $last_id = $this->input->post('workProcessID');
                    if (isset($_POST["customWorkFlowID"])) {
                        $workFlowID = explode(',', $this->input->post("customWorkFlowID"));
                        $workFlowTemplateID = explode(',', $this->input->post("customWorkFlowTemplateID"));
                        $description = explode(',', $this->input->post("customDescription"));
                        $linkWorkflow = $this->input->post("linkProcess");
                    }

                    $data = array();
                    $templateDet = "";
                    if ($workFlowID) {
                        foreach ($workFlowID as $key => $val) {
                            $data[] = array('jobID' => $last_id, 'workFlowID' => $val, 'workFlowTemplateID' => $workFlowTemplateID[$key], 'sortOrder' => $key + 1, 'description' => $description[$key], 'companyID' => current_companyID(), 'templateMasterID' => $this->input->post('workFlowTemplateID'));
                        }
                        $this->db->insert_batch("srp_erp_mfq_customtemplatedetail", $data);

                        if ($linkWorkflow) {
                            $this->db->query("UPDATE srp_erp_mfq_customtemplatedetail AS cust
LEFT JOIN (
SELECT
	prev_id,
	cur_id 
FROM
	(
SELECT
	templateDetailID AS cur_id,
	( SELECT min( templateDetailID ) FROM srp_erp_mfq_customtemplatedetail WHERE templateDetailID = (cur_id-1) AND jobID = {$last_id} ) AS prev_id 
FROM
	srp_erp_mfq_customtemplatedetail 
WHERE
	jobID = {$last_id} 
	) AS tmp 
WHERE
	prev_id IS NOT NULL
	) target ON target.cur_id = cust.templateDetailID 
	SET cust.linkWorkFlow = target.prev_id WHERE cust.jobID = {$last_id}");
                        }

                        $this->db->select("*");
                        $this->db->from("srp_erp_mfq_customtemplatedetail");
                        $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                        $this->db->where("jobID", $last_id);
                        $templateDet = $this->db->get()->result_array();
                        $data = array();
                        if ($templateDet) {
                            foreach ($templateDet as $val) {
                                $data[] = array('workFlowID' => $val["workFlowID"], 'templateDetailID' => $val["templateDetailID"], 'jobID' => $last_id, 'companyID' => current_companyID());
                            }
                            $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                        }
                    } else {
                        $this->db->select("*");
                        $this->db->from("srp_erp_mfq_templatedetail");
                        $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                        $templateDet = $this->db->get()->result_array();
                        $data = array();
                        if ($templateDet) {
                            foreach ($templateDet as $val) {
                                $data[] = array('workFlowID' => $val["workFlowID"], 'templateDetailID' => $val["templateDetailID"], 'jobID' => $last_id, 'companyID' => current_companyID());
                            }
                            $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                        }
                    }
                    if ($fromType == "EST") {
                        $data = [];
                        $data['isFromEstimate'] = 2;
                        $this->db->where('workProcessID', $this->input->post('workProcessID'));
                        $result = $this->db->update('srp_erp_mfq_job', $data);

                        if ($this->input->post('bomMasterID')) {
                            $this->db->select("*");
                            $this->db->from("srp_erp_mfq_warehousemaster");
                            $this->db->where("mfqWarehouseAutoID", $this->input->post('mfqWarehouseAutoID'));
                            $warehouse = $this->db->get()->row_array();

                            $this->db->select("*");
                            $this->db->from("srp_erp_warehousemaster");
                            $this->db->where("warehouseAutoID", $warehouse["warehouseAutoID"]);
                            $warehouseERP = $this->db->get()->row_array();

                            $this->db->query("INSERT INTO srp_erp_warehouseitems (wareHouseAutoID,wareHouseLocation,wareHouseDescription,itemAutoID,itemSystemCode,itemDescription,unitOfMeasureID,unitOfMeasure,currentStock,companyID,companyCode) SELECT " . $warehouseERP["wareHouseAutoID"] . ",'" . $warehouseERP["wareHouseLocation"] . "','" . $warehouseERP["wareHouseDescription"] . "',srp_erp_itemmaster.itemAutoID,srp_erp_itemmaster.itemSystemCode,srp_erp_itemmaster.itemDescription,srp_erp_itemmaster.defaultUnitOfMeasureID,srp_erp_itemmaster.defaultUnitOfMeasure,0,srp_erp_itemmaster.companyID,srp_erp_itemmaster.companyCode FROM srp_erp_mfq_bom_materialconsumption mc INNER JOIN srp_erp_mfq_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = mc.mfqItemID INNER JOIN srp_erp_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = srp_erp_itemmaster.itemAutoID WHERE NOT EXISTS (SELECT * FROM srp_erp_warehouseitems WHERE srp_erp_warehouseitems.itemAutoID = srp_erp_mfq_itemmaster.itemAutoID AND warehouseAutoID = " . $warehouse["warehouseAutoID"] . ") AND srp_erp_mfq_itemmaster.itemType = 3 AND mc.bomMasterID =" . $this->input->post('bomMasterID'));

                            $this->db->select("srp_erp_mfq_bom_materialconsumption.*,srp_erp_mfq_itemmaster.*,(((IFNULL(srp_erp_warehouseitems.currentStock,0)- IFNULL(jcm.qtyUsed,0)) + IFNULL(jc.qty,0))-(srp_erp_mfq_bom_materialconsumption.qtyUsed* {$this->input->post('qty')})) as remainingQty,srp_erp_mfq_billofmaterial.bomMasterID as bomID");
                            $this->db->from("srp_erp_mfq_bom_materialconsumption");
                            $this->db->join("srp_erp_mfq_itemmaster", "srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                            $this->db->join("srp_erp_warehouseitems", "srp_erp_warehouseitems.itemAutoID = srp_erp_mfq_itemmaster.itemAutoID AND srp_erp_warehouseitems.companyID = " . current_companyID() . " AND srp_erp_warehouseitems.wareHouseAutoID =" . $warehouse["warehouseAutoID"], "left");
                            $this->db->join("srp_erp_mfq_billofmaterial", "srp_erp_mfq_billofmaterial.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                            $this->db->join("(SELECT SUM(qtyUsed) as qtyUsed,srp_erp_mfq_jc_materialconsumption.mfqItemID FROM srp_erp_mfq_jc_materialconsumption LEFT JOIN srp_erp_mfq_job ON srp_erp_mfq_job.workProcessID = srp_erp_mfq_jc_materialconsumption.workProcessID WHERE approvedYN = 0 AND srp_erp_mfq_jc_materialconsumption.companyID = " . current_companyID() . " GROUP BY srp_erp_mfq_jc_materialconsumption.mfqItemID) jcm", "jcm.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                            $this->db->join("(SELECT SUM(qty) as qty,mfqItemID FROM srp_erp_mfq_job WHERE mfqWarehouseAutoID = " . $this->input->post('mfqWarehouseAutoID') . " AND approvedYN = 0 AND companyID = " . current_companyID() . " GROUP BY mfqItemID) jc", "jc.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
                            $this->db->where("srp_erp_mfq_bom_materialconsumption.bomMasterID", $this->input->post('bomMasterID'));
                            $this->db->where("itemType", 3);
                            $this->db->where("srp_erp_mfq_itemmaster.mainCategory", "Inventory");
                            $this->db->having("remainingQty < ", 0);
                            $bomDetail = $this->db->get()->result_array();

                            $this->db->select('*');
                            $this->db->from("srp_erp_mfq_job");
                            $this->db->where("workProcessID", $last_id);
                            $jodResult = $this->db->get()->row_array();

                            if ($bomDetail) {
                                $i = 1;
                                foreach ($bomDetail as $val) {
                                    $codes = $jodResult["documentCode"] . " - " . (str_pad($i, 2, '0', STR_PAD_LEFT));
                                    $this->db->set('description', $this->input->post('description'));
                                    $this->db->set('workFlowTemplateID', $this->input->post('workFlowTemplateID'));
                                    $this->db->set('serialNo', $jodResult['serialNo']);
                                    $this->db->set('documentCode', $codes);
                                    $this->db->set('documentDate', date('Y-m-d'));
                                    $this->db->set('startDate', $format_startdate);
                                    $this->db->set('endDate', $format_enddate);

                                    $this->db->set('mfqItemID', $val["mfqItemID"]);
                                    $this->db->set('estimateDetailID', $this->input->post('estimateDetailID'));
                                    $this->db->set('bomMasterID', $val['bomID']);
                                    $this->db->set('isSaved', 1);

                                    $this->db->set('qty', abs($val["remainingQty"]));
                                    $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
                                    $this->db->set('mfqSegmentID', $this->input->post('mfqSegmentID'));
                                    $this->db->set('type', $this->input->post('type'));
                                    $this->db->set('documentID', 'JOB');
                                    $this->db->set('levelNo', 3);
                                    $this->db->set('companyID', current_companyID());
                                    $this->db->set('linkedJobID', $last_id);
                                    $this->db->set('mfqWarehouseAutoID', $this->input->post('mfqWarehouseAutoID'));

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

                                    $this->db->select("srp_erp_customermaster.customerCurrencyID,srp_erp_customermaster.customerCurrency");
                                    $this->db->from("srp_erp_mfq_customermaster");
                                    $this->db->join("srp_erp_customermaster", "srp_erp_mfq_customermaster.CustomerAutoID=srp_erp_customermaster.customerAutoID", "LEFT");
                                    $this->db->where("mfqCustomerAutoID", $this->input->post('mfqCustomerAutoID'));
                                    $custInfo = $this->db->get()->row_array();

                                    $this->db->set('mfqCustomerCurrencyID', $custInfo["customerCurrencyID"]);
                                    $this->db->set('mfqCustomerCurrency', $custInfo["customerCurrency"]);

                                    $customer_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $custInfo['customerCurrencyID']);
                                    $this->db->set('mfqCustomerCurrencyExchangeRate', $customer_currency['conversion']);
                                    $this->db->set('mfqCustomerCurrencyDecimalPlaces', $customer_currency['DecimalPlaces']);

                                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                                    $this->db->set('createdUserID', current_userID());
                                    $this->db->set('createdUserName', current_user());
                                    $this->db->set('createdDateTime', current_date(true));
                                    $result = $this->db->insert('srp_erp_mfq_job');
                                    $last_id2 = $this->db->insert_id();
                                    $createdJobArr[] = "Job Card " . $codes . " created for semi finished good items " . $val["itemSystemCode"] . " - " . $val["itemDescription"];
                                    if ($workFlowID) {
                                        $data = array();
                                        foreach ($workFlowID as $key => $val2) {
                                            $data[] = array('jobID' => $last_id2, 'workFlowID' => $val2, 'workFlowTemplateID' => $workFlowTemplateID[$key], 'sortOrder' => $key + 1, 'description' => $description[$key], 'companyID' => current_companyID(), 'templateMasterID' => $this->input->post('workFlowTemplateID'));
                                        }
                                        $this->db->insert_batch("srp_erp_mfq_customtemplatedetail", $data);

                                        if ($linkWorkflow) {
                                            $this->db->query("UPDATE srp_erp_mfq_customtemplatedetail AS cust
LEFT JOIN (
SELECT
	prev_id,
	cur_id 
FROM
	(
SELECT
	templateDetailID AS cur_id,
	( SELECT min( templateDetailID ) FROM srp_erp_mfq_customtemplatedetail WHERE templateDetailID = (cur_id-1) AND jobID = {$last_id2} ) AS prev_id 
FROM
	srp_erp_mfq_customtemplatedetail 
WHERE
	jobID = {$last_id2} 
	) AS tmp 
WHERE 
	prev_id IS NOT NULL
	) target ON target.cur_id = cust.templateDetailID 
	SET cust.linkWorkFlow = target.prev_id WHERE cust.jobID = {$last_id2}");
                                        }

                                        $this->db->select("*");
                                        $this->db->from("srp_erp_mfq_customtemplatedetail");
                                        $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                                        $this->db->where("jobID", $last_id2);
                                        $templateDet = $this->db->get()->result_array();
                                        $data = array();
                                        if ($templateDet) {
                                            foreach ($templateDet as $val3) {
                                                $data[] = array('workFlowID' => $val3["workFlowID"], 'templateDetailID' => $val3["templateDetailID"], 'jobID' => $last_id2, 'companyID' => current_companyID());
                                            }
                                            $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                                        }
                                    } else {
                                        $this->db->select("*");
                                        $this->db->from("srp_erp_mfq_templatedetail");
                                        $this->db->where("templateMasterID", $this->input->post('workFlowTemplateID'));
                                        $templateDet = $this->db->get()->result_array();
                                        $data = array();
                                        if ($templateDet) {
                                            foreach ($templateDet as $val3) {
                                                $data[] = array('workFlowID' => $val3["workFlowID"], 'templateDetailID' => $val3["templateDetailID"], 'jobID' => $last_id2, 'companyID' => current_companyID());
                                            }
                                            $this->db->insert_batch("srp_erp_mfq_workflowstatus", $data);
                                        }
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Job saved Failed ' . $this->db->_error_message());
                } else {
                    $this->db->trans_commit();
                    return array('s', 'Job saved Successfully.', $this->input->post('workProcessID'), "", "", "", $createdJobArr);
                }
            }
        }
    }

    function save_job_detail()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $format_Jobdate = input_format_date(trim($this->input->post('documentDate')), $date_format_policy);

        $data['mfqItemID'] = $this->input->post('mfqItemID');
        $data['qty'] = $this->input->post('qty');
        $data['mfqCustomerAutoID'] = $this->input->post('mfqCustomerAutoID');
        $data['mfqSegmentID'] = $this->input->post('mfqSegmentID');
        $data['companyID'] = current_companyID();
        $data['modifiedPCID'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);//gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $data['modifiedUserID'] = current_userID();//$this->session->userdata("username");
        $data['modifiedUserName'] = current_user();//$this->session->userdata("username");
        $data['modifiedDateTime'] = current_date(true);
        $this->db->set('documentDate', $format_Jobdate);
        $this->db->where('workProcessID', $this->input->post('workProcessID'));
        $result = $this->db->update('srp_erp_mfq_job', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Job detail saved Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Job detail saved Successfully.', $this->input->post('workProcessID'));
        }
    }

    function save_sub_job()
    {
        $qtyRequired = 0;
        $itemDetail = get_specific_mfq_item($this->input->post('mfqItemID'));
        if ($itemDetail["mainCategory"] == "Inventory") {
            $qtyExceed = $this->db->query('SELECT IFNULL(SUM(qty),0) as qty FROM srp_erp_mfq_job WHERE mfqItemID=' . $this->input->post('mfqItemID') . ' AND linkedJobID=' . $this->input->post('workProcessID'))->row('qty');
            $qtyRequired = $this->input->post('expectedQty') - $qtyExceed;
        } else {
            $qtyRequired = $this->input->post('expectedQty');
        }
        if ($itemDetail["mainCategory"] == "Inventory" && $qtyRequired <= 0) {
            return array('w', 'You have already created the sufficient quantity ' . $this->input->post('expectedQty'));
        } else {
            $this->db->trans_start();
            $jobCount = $this->db->query('SELECT COUNT(*) as jobCount FROM srp_erp_mfq_job WHERE linkedJobID=' . $this->input->post('workProcessID'))->row('jobCount');
            $header = $this->load_job_header();
            $code = str_replace("JOB", "PO", $header["documentCode"]) . " - " . (str_pad(($jobCount + 1), 2, '0', STR_PAD_LEFT));
            $this->db->set('description', $header['description']);
            $this->db->set('serialNo', $header['serialNo']);
            $this->db->set('documentCode', $code);
            $this->db->set('documentDate', date('Y-m-d'));
            $this->db->set('startDate', date('Y-m-d'));
            $this->db->set('endDate', date('Y-m-d'));
            $this->db->set('mfqItemID', $this->input->post('mfqItemID'));
            $this->db->set('estimateDetailID', $this->input->post('estimateDetailID'));
            $this->db->set('bomMasterID', $this->input->post('bomMasterID'));
            $this->db->set('qty', $qtyRequired);
            $this->db->set('mfqCustomerAutoID', $header['mfqCustomerAutoID']);
            $this->db->set('mfqSegmentID', $header['mfqSegmentID']);
            $this->db->set('type', 2);
            $this->db->set('mfqWarehouseAutoID', $header['mfqWarehouseAutoID']);
            $this->db->set('estimateMasterID', $this->input->post('estimateMasterID'));
            $this->db->set('documentID', 'JOB');
            $this->db->set('levelNo', 2);

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

            $this->db->select("srp_erp_customermaster.customerCurrencyID,srp_erp_customermaster.customerCurrency");
            $this->db->from("srp_erp_mfq_customermaster");
            $this->db->join("srp_erp_customermaster", "srp_erp_mfq_customermaster.CustomerAutoID=srp_erp_customermaster.customerAutoID", "LEFT");
            $this->db->where("mfqCustomerAutoID", $header['mfqCustomerAutoID']);
            $custInfo = $this->db->get()->row_array();

            $this->db->set('mfqCustomerCurrencyID', $custInfo["customerCurrencyID"]);
            $this->db->set('mfqCustomerCurrency', $custInfo["customerCurrency"]);

            $customer_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $custInfo['customerCurrencyID']);
            $this->db->set('mfqCustomerCurrencyExchangeRate', $customer_currency['conversion']);
            $this->db->set('mfqCustomerCurrencyDecimalPlaces', $customer_currency['DecimalPlaces']);
            $this->db->set('isSaved', 0);
            $this->db->set('isFromEstimate', 1);
            $this->db->set('linkedJobID', $this->input->post('workProcessID'));

            $this->db->set('companyID', current_companyID());
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));

            $result = $this->db->insert('srp_erp_mfq_job');
            $last_id = $this->db->insert_id();
            $last_material_id = "";

            $wareHouseAutoID = $this->db->query('SELECT warehouseAutoID FROM srp_erp_mfq_warehousemaster  WHERE mfqWarehouseAutoID=' . $header['mfqWarehouseAutoID'])->row('warehouseAutoID');

            // insert record to warehouse if no item found for warehouse
            $bomMaterialConsumtion = $this->db->query('SELECT srp_erp_mfq_itemmaster.*  FROM srp_erp_mfq_bom_materialconsumption INNER JOIN srp_erp_mfq_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID WHERE srp_erp_mfq_itemmaster.itemType = 1 AND bomMasterID=' . $this->input->post('bomMasterID') . ' AND srp_erp_mfq_itemmaster.mainCategory = "Inventory"')->result_array();

            if ($bomMaterialConsumtion) {
                foreach ($bomMaterialConsumtion as $val) {
                    $this->db->select('itemAutoID');
                    $this->db->where('itemAutoID', $val['itemAutoID']);
                    $this->db->where('wareHouseAutoID', $wareHouseAutoID);
                    $this->db->where('companyID', $this->common_data['company_data']['company_id']);
                    $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

                    if (empty($warehouseitems)) {
                        $wareHouseDetail = $this->db->query('SELECT * FROM srp_erp_warehousemaster  WHERE warehouseAutoID=' . $wareHouseAutoID)->row_array();
                        $data_arr = array(
                            'wareHouseAutoID' => $wareHouseAutoID,
                            'wareHouseLocation' => $wareHouseDetail['wareHouseLocation'],
                            'wareHouseDescription' => $wareHouseDetail['wareHouseDescription'],
                            'itemAutoID' => $val['itemAutoID'],
                            'itemSystemCode' => $val['itemSystemCode'],
                            'itemDescription' => $val['itemDescription'],
                            'unitOfMeasureID' => $val['defaultUnitOfMeasureID'],
                            'unitOfMeasure' => $val['defaultUnitOfMeasure'],
                            'currentStock' => 0,
                            'companyID' => $this->common_data['company_data']['company_id'],
                            'companyCode' => $this->common_data['company_data']['company_code'],
                        );
                        $this->db->insert('srp_erp_warehouseitems', $data_arr);
                    }
                }
            }

            $bomMaterialConsumtion = $this->db->query('SELECT srp_erp_mfq_bom_materialconsumption.*,(srp_erp_mfq_bom_materialconsumption.qtyUsed * ' . $qtyRequired . ') as required,srp_erp_mfq_itemmaster.itemAutoID as itemAutoID,((srp_erp_mfq_bom_materialconsumption.qtyUsed * ' . $qtyRequired . ') - IFNULL(wi.currentStock,0)) as qtyRequired,IFNULL(wi.currentStock,0) as currentStock  FROM srp_erp_mfq_bom_materialconsumption INNER JOIN srp_erp_mfq_itemmaster ON srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID INNER JOIN (SELECT * FROM srp_erp_warehouseitems WHERE wareHouseAutoID = ' . $wareHouseAutoID . ' AND companyID=' . current_companyID() . ') wi ON wi.itemAutoID =  srp_erp_mfq_itemmaster.itemAutoID WHERE srp_erp_mfq_itemmaster.itemType = 1 AND bomMasterID=' . $this->input->post('bomMasterID') . ' AND srp_erp_mfq_itemmaster.mainCategory = "Inventory" HAVING required > currentStock')->result_array();

            if ($bomMaterialConsumtion) {
                $wareHouseDetails = $this->db->query('SELECT * FROM srp_erp_warehousemaster WHERE warehouseAutoID=' . $wareHouseAutoID)->row_array();
                $this->load->library('sequence');
                $this->db->set('documentID', 'MR');
                $this->db->set('itemType', 'Inventory');
                $this->db->set('MRCode', $this->sequence->sequence_generator('MR'));
                $this->db->set('requestedDate', current_date(true));
                $this->db->set('wareHouseAutoID', $wareHouseAutoID);
                $this->db->set('wareHouseCode', $wareHouseDetails["wareHouseCode"]);
                $this->db->set('wareHouseLocation', $wareHouseDetails["wareHouseLocation"]);
                $this->db->set('wareHouseDescription', $wareHouseDetails["wareHouseDescription"]);
                $this->db->set('employeeName', current_user());
                $this->db->set('employeeCode', current_userCode());
                $this->db->set('employeeID', current_userID());
                $this->db->set('comment', $header['description']);
                $this->db->set('createdUserGroup', $this->common_data['user_group']);
                $this->db->set('createdPCID', $this->common_data['current_pc']);
                $this->db->set('createdUserID', $this->common_data['current_userID']);
                $this->db->set('createdUserName', $this->common_data['current_user']);
                $this->db->set('createdDateTime', $this->common_data['current_date']);
                $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
                $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
                $this->db->set('companyLocalExchangeRate', 1);
                $this->db->set('companyLocalCurrencyDecimalPlaces', $this->common_data['company_data']['company_default_decimal']);
                $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
                $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
                $reporting_currency = currency_conversionID($this->common_data['company_data']['company_default_currencyID'], $this->common_data['company_data']['company_reporting_currencyID']);
                $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
                $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);
                $this->db->set('companyID', current_companyID());
                $this->db->set('companyCode', $this->common_data['company_data']['company_code']);
                $result = $this->db->insert('srp_erp_materialrequest');
                $last_material_id = $this->db->insert_id();
                foreach ($bomMaterialConsumtion as $val) {
                    //insert record to material request and detail
                    if ($last_material_id) {
                        $item_data = fetch_item_data($val['itemAutoID']);
                        $data['mrAutoID'] = $last_material_id;
                        $data['itemAutoID'] = $item_data['itemAutoID'];
                        $data['itemSystemCode'] = $item_data['itemSystemCode'];
                        $data['itemDescription'] = $item_data['itemDescription'];
                        $data['unitOfMeasure'] = $item_data['defaultUnitOfMeasure'];
                        $data['unitOfMeasureID'] = $item_data['defaultUnitOfMeasureID'];
                        $data['defaultUOM'] = $item_data['defaultUnitOfMeasure'];
                        $data['defaultUOMID'] = $item_data['defaultUnitOfMeasureID'];
                        $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
                        $data['qtyRequested'] = $val['qtyRequired'];
                        $data['comments'] = $header['description'];
                        $data['remarks'] = '';
                        $data['currentWareHouseStock'] = $val['currentStock'];
                        $data['itemFinanceCategory'] = $item_data['subcategoryID'];
                        $data['itemFinanceCategorySub'] = $item_data['subSubCategoryID'];
                        $data['financeCategory'] = $item_data['financeCategory'];
                        $data['itemCategory'] = $item_data['mainCategory'];
                        $data['currentlWacAmount'] = $item_data['companyLocalWacAmount'];
                        $data['currentStock'] = $item_data['currentStock'];

                        if ($data['financeCategory'] == 1 or $data['financeCategory'] == 3) {
                            $data['PLGLAutoID'] = $item_data['costGLAutoID'];
                            $data['PLSystemGLCode'] = $item_data['costSystemGLCode'];
                            $data['PLGLCode'] = $item_data['costGLCode'];
                            $data['PLDescription'] = $item_data['costDescription'];
                            $data['PLType'] = $item_data['costType'];

                            $data['BLGLAutoID'] = $item_data['assteGLAutoID'];
                            $data['BLSystemGLCode'] = $item_data['assteSystemGLCode'];
                            $data['BLGLCode'] = $item_data['assteGLCode'];
                            $data['BLDescription'] = $item_data['assteDescription'];
                            $data['BLType'] = $item_data['assteType'];
                        } elseif ($data['financeCategory'] == 2) {
                            $data['PLGLAutoID'] = $item_data['costGLAutoID'];
                            $data['PLSystemGLCode'] = $item_data['costSystemGLCode'];
                            $data['PLGLCode'] = $item_data['costGLCode'];
                            $data['PLDescription'] = $item_data['costDescription'];
                            $data['PLType'] = $item_data['costType'];

                            $data['BLGLAutoID'] = '';
                            $data['BLSystemGLCode'] = '';
                            $data['BLGLCode'] = '';
                            $data['BLDescription'] = '';
                            $data['BLType'] = '';
                        }

                        $data['totalValue'] = ($data['currentlWacAmount'] * ($data['qtyRequested'] / $data['conversionRateUOM']));

                        $data['companyCode'] = $this->common_data['company_data']['company_code'];
                        $data['companyID'] = $this->common_data['company_data']['company_id'];
                        $data['createdUserGroup'] = $this->common_data['user_group'];
                        $data['createdPCID'] = $this->common_data['current_pc'];
                        $data['createdUserID'] = $this->common_data['current_userID'];
                        $data['createdUserName'] = $this->common_data['current_user'];
                        $data['createdDateTime'] = $this->common_data['current_date'];
                        $this->db->insert('srp_erp_materialrequestdetails', $data);

                        $this->db->select('itemAutoID');
                        $this->db->where('itemAutoID', $item_data['itemAutoID']);
                        $this->db->where('wareHouseAutoID', $wareHouseAutoID);
                        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
                        $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

                        if (empty($warehouseitems)) {
                            $data_arr = array(
                                'wareHouseAutoID' => $wareHouseAutoID,
                                'wareHouseLocation' => $wareHouseDetails['wareHouseLocation'],
                                'wareHouseDescription' => $wareHouseDetails['wareHouseDescription'],
                                'itemAutoID' => $item_data['itemAutoID'],
                                'itemSystemCode' => $item_data['itemSystemCode'],
                                'itemDescription' => $item_data['itemDescription'],
                                'unitOfMeasureID' => $item_data['defaultUnitOfMeasureID'],
                                'unitOfMeasure' => $item_data['defaultUnitOfMeasure'],
                                'currentStock' => 0,
                                'companyID' => $this->common_data['company_data']['company_id'],
                                'companyCode' => $this->common_data['company_data']['company_code'],
                            );
                            $this->db->insert('srp_erp_warehouseitems', $data_arr);
                        }
                    }
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Job card added failed.' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Job No: ' . $code, $last_id, $last_material_id);
            }
        }
    }

    function load_job_header()
    {
        $workProcessID = $this->input->post('workProcessID');
        $convertFormat = convert_date_format_sql();
        $data = $this->db->query('select srp_erp_mfq_job.*,DATE_FORMAT(startDate,\'' . $convertFormat . '\') AS startDate,DATE_FORMAT(endDate,\'' . $convertFormat . '\') AS endDate,UnitDes,CustomerName,srp_erp_mfq_segment.description as segment,CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN "RM" WHEN 2 THEN "FG" WHEN 3 THEN "SF"
END," - ",srp_erp_mfq_itemmaster.itemSystemCode,\' - \',srp_erp_mfq_itemmaster.itemDescription) as itemDescription,srp_erp_mfq_job.estimateDetailID,type,IFNULL(est.estimateCode,"") as estimateCode,IFNULL(ciCode,"") as ciCode FROM srp_erp_mfq_job LEFT JOIN srp_erp_mfq_itemmaster ON  srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_job.mfqItemID LEFT JOIN srp_erp_unit_of_measure ON UnitID = defaultUnitOfMeasureID LEFT JOIN srp_erp_mfq_customermaster ON srp_erp_mfq_job.mfqCustomerAutoID=srp_erp_mfq_customermaster.mfqCustomerAutoID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_segment.mfqSegmentID=srp_erp_mfq_job.mfqSegmentID LEFT JOIN (SELECT IFNULL(estimateCode,"") as estimateCode,srp_erp_mfq_estimatedetail.estimateDetailID,IFNULL(ciCode,"") as ciCode FROM srp_erp_mfq_estimatedetail LEFT JOIN srp_erp_mfq_estimatemaster ON srp_erp_mfq_estimatedetail.estimateMasterID = srp_erp_mfq_estimatemaster.estimateMasterID LEFT JOIN srp_erp_mfq_customerinquiry ON srp_erp_mfq_customerinquiry.ciMasterID = srp_erp_mfq_estimatedetail.ciMasterID) est ON est.estimateDetailID = srp_erp_mfq_job.estimateDetailID WHERE workProcessID=' . $workProcessID)->row_array();
        //echo $this->db->last_query();
        return $data;
    }

    function load_unit_of_measure()
    {
        $mfqItemID = $this->input->post('mfqItemID');
        if ($mfqItemID) {
            $data = $this->db->query('select UnitDes,defaultUnitOfMeasureID FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_unit_of_measure ON UnitID = defaultUnitOfMeasureID WHERE mfqItemID=' . $mfqItemID)->row_array();
            //echo $this->db->last_query();
            return $data;
        } else {
            return '';
        }
    }

    function load_mfq_estimate()
    {
        $this->db->where('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->where('approvedYN', 1);
        $data = $this->db->get('srp_erp_mfq_estimateMaster')->result_array();
        return $data;
    }

    function get_workflow_status()
    {
        $this->db->where('jobID', $this->input->post('workProcessID'));
        //$this->db->where('status', 1);
        $this->db->join('srp_erp_mfq_templatedetail', 'srp_erp_mfq_templatedetail.templateDetailID = srp_erp_mfq_workflowstatus.templateDetailID', 'inner');
        $data = $this->db->get('srp_erp_mfq_workflowstatus')->result_array();

        $this->db->where('srp_erp_mfq_workflowstatus.jobID', $this->input->post('workProcessID'));
        //$this->db->where('status', 1);
        $this->db->join('srp_erp_mfq_customtemplatedetail', 'srp_erp_mfq_customtemplatedetail.templateDetailID = srp_erp_mfq_workflowstatus.templateDetailID', 'inner');
        $data2 = $this->db->get('srp_erp_mfq_workflowstatus')->result_array();

        return array_merge($data, $data2);
    }

    function get_jobs()
    {
        $this->db->where('workProcessID', $this->input->post('workProcessID'));
        $data = $this->db->get('srp_erp_mfq_jobcardmaster')->result_array();
        return $data;
    }

    function close_job()
    {
        $method = $this->input->post('method');
        $this->db->select('warehouseAutoID');
        $this->db->where('workProcessID', $this->input->post('workProcessID'));
        $this->db->join("srp_erp_mfq_warehousemaster", "srp_erp_mfq_job.mfqWarehouseAutoID=srp_erp_mfq_warehousemaster.mfqWarehouseAutoID", "left");
        $warehouse = $this->db->get('srp_erp_mfq_job')->row_array();

        /*$this->db->select('itm.*,(srp_erp_mfq_jc_materialconsumption.qtyUsed-itm.whCurrentStock) as remainingQty');
        $this->db->where('srp_erp_mfq_jc_materialconsumption.workProcessID', $this->input->post('workProcessID'));
        $this->db->where('srp_erp_mfq_jc_materialconsumption.qtyUsed > itm.whCurrentStock');
        $this->db->where('itm.mainCategory', "Inventory");
        $this->db->join("(SELECT srp_erp_mfq_itemmaster.*,wh.currentStock as whCurrentStock  FROM srp_erp_mfq_itemmaster LEFT JOIN (SELECT itemAutoID,currentStock FROM srp_erp_warehouseitems WHERE companyID = " . current_companyID() . " AND wareHouseAutoID = " . $warehouse["warehouseAutoID"] . ") wh ON wh.itemAutoID = srp_erp_mfq_itemmaster.itemAutoID) itm", "itm.mfqItemID=srp_erp_mfq_jc_materialconsumption.mfqItemID", "left");
        $material = $this->db->get('srp_erp_mfq_jc_materialconsumption')->result_array();*/

        //if ($method == 2) {
        $this->db->where('jobID', $this->input->post('workProcessID'));
        $this->db->where('status', 0);
        $data = $this->db->get('srp_erp_mfq_workflowstatus')->result_array();
        if ($data) {
            return array('w', 'Please complete all the process in job');
        } else {
            $date_format_policy = date_format_policy();
            $format_closedDate = input_format_date(trim($this->input->post('closedDate')), $date_format_policy);
            /*$this->db->select("*");
            $this->db->from('srp_erp_companyfinanceperiod');
            $this->db->join('srp_erp_companyfinanceyear', "srp_erp_companyfinanceyear.companyFinanceYearID=srp_erp_companyfinanceperiod.companyFinanceYearID", "LEFT");
            $this->db->where('srp_erp_companyfinanceperiod.companyID', $this->common_data['company_data']['company_id']);
            $this->db->where("'{$format_closedDate}' BETWEEN dateFrom AND dateTo");
            $this->db->where("srp_erp_companyfinanceperiod.isActive", 1);
            $financePeriod = $this->db->get()->row_array();
            if ($financePeriod) {*/
            try {
                $this->db->trans_start();
                $date_format_policy = date_format_policy();
                $format_closedDate = input_format_date(trim($this->input->post('closedDate')), $date_format_policy);
                $this->db->set('closedDate', $format_closedDate);
                $this->db->set('closedComment', $this->input->post('closedComment'));
                $this->db->set('closedByEmpID', current_userID());
                $this->db->set('closedYN', 1);
                $this->db->set('postingFinanceDate', $format_closedDate);
                /*$this->db->set('companyFinancePeriodID', $financePeriod["companyFinancePeriodID"]);
                $this->db->set('companyFinanceYearID', $financePeriod["companyFinanceYearID"]);
                $this->db->set('FYBegin', $financePeriod["beginingDate"]);
                $this->db->set('FYEnd', $financePeriod["endingDate"]);
                $this->db->set('FYPeriodDateFrom', $financePeriod["dateFrom"]);
                $this->db->set('FYPeriodDateTo', $financePeriod["dateTo"]);*/
                $this->db->where('workProcessID', $this->input->post('workProcessID'));
                $result = $this->db->update('srp_erp_mfq_job');

                if ($result) {
                    $this->load->library('approvals');
                    $this->db->select('*');
                    $this->db->where('workProcessID', $this->input->post('workProcessID'));
                    $this->db->from('srp_erp_mfq_job');
                    $row = $this->db->get()->row_array();
                    $approvals_status = $this->approvals->CreateApproval('JOB', $row['workProcessID'],
                        $row['documentCode'], 'Job', 'srp_erp_mfq_job', 'workProcessID', 0);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', "Error Occurred");
                } else {
                    $this->db->trans_commit();
                    return array('s', "Successfully job closed");
                }
            } catch (Exception $e) {
                return array('e', $e->getMessage());
            }
            /*} else {
                return array('w', 'Closing date not between financial period');
            }*/
        }
        /*} else {
            return array('w', 'Some item quantities are not sufficient to confirm this transaction', $material);
        }*/
    }

    function save_job_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $financePeriod = "";
        $date_format_policy = date_format_policy();
        $system_id = trim($this->input->post('workProcessID'));
        $jobcardID = trim($this->input->post('jobcardID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));
        $maxLevel = trim($this->input->post('maxLevel'));
        $format_postingFinanceDate = input_format_date(trim($this->input->post('postingFinanceDate')), $date_format_policy);

        if($level_id == $maxLevel) {
            $this->db->select("*");
            $this->db->from('srp_erp_companyfinanceperiod');
            $this->db->join('srp_erp_companyfinanceyear', "srp_erp_companyfinanceyear.companyFinanceYearID=srp_erp_companyfinanceperiod.companyFinanceYearID", "LEFT");
            $this->db->where('srp_erp_companyfinanceperiod.companyID', $this->common_data['company_data']['company_id']);
            $this->db->where("'{$format_postingFinanceDate}' BETWEEN dateFrom AND dateTo");
            $this->db->where("srp_erp_companyfinanceperiod.isActive", 1);
            $financePeriod = $this->db->get()->row_array();

            if (!$financePeriod) {
                return array('w', 'Finance date not between financial period');
            }
        }

        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'JOB');
        if ($approvals_status == 1) {
            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            $this->db->where('workProcessID', $system_id);
            $this->db->update('srp_erp_mfq_job', $data);
            $double_entry = $this->fetch_double_entry_job($this->input->post('workProcessID'), $jobcardID);
            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['workProcessID'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['master_data']['documentID'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['documentCode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['closedDate'];
                $generalledger_arr[$i]['documentYear'] = date("Y", strtotime($double_entry['master_data']['closedDate']));
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['closedDate']));
                $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['description'];
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = 1;
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                /*$generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];*/
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['partyCurrencyAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['partyExchangeRate']), $generalledger_arr[$i]['partyCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
            }

            if (!empty($generalledger_arr)) {
                //$this->db->insert_batch('srp_erp_mfq_generalledger', $generalledger_arr);
                $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);
            }

            $this->db->select('materialCharge as materialCharge,qtyUsed,itm.*');
            $this->db->where('workProcessID', $this->input->post('workProcessID'));
            $this->db->join('(SELECT srp_erp_itemmaster.*,mfqItemID FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_itemmaster ON srp_erp_mfq_itemmaster.itemAutoID = srp_erp_itemmaster.itemAutoID) itm', 'itm.mfqItemID=srp_erp_mfq_jc_materialconsumption.mfqItemID', 'LEFT');
            $materialConsumption = $this->db->get('srp_erp_mfq_jc_materialconsumption')->result_array();

            for ($a = 0; $a < count($materialConsumption); $a++) {
                if ($materialConsumption[$a]['mainCategory'] == 'Inventory') {
                    $itemAutoID = $materialConsumption[$a]['itemAutoID'];
                    $qty = $materialConsumption[$a]['qtyUsed'] / 1;
                    $wareHouseAutoID = $double_entry['master_data']['wareHouseAutoID'];
                    $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock - {$qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' and itemAutoID='{$itemAutoID}'");
                    $item_arr[$a]['itemAutoID'] = $materialConsumption[$a]['itemAutoID'];
                    $item_arr[$a]['currentStock'] = ($materialConsumption[$a]['currentStock'] - $qty);
                    /*$item_arr[$a]['companyLocalWacAmount'] = round(((($materialConsumption[$a]['currentStock'] * $materialConsumption[$a]['companyLocalWacAmount']) + $materialConsumption[$a]['materialCharge']) / $item_arr[$a]['currentStock']), $double_entry['master_data']['companyLocalCurrencyDecimalPlaces']);
                    $item_arr[$a]['companyReportingWacAmount'] = round(((($item_arr[$a]['currentStock'] * $materialConsumption[$a]['companyReportingWacAmount']) + ($materialConsumption[$a]['materialCharge'] / $double_entry['master_data']['companyReportingExchangeRate'])) / $item_arr[$a]['currentStock']), $double_entry['master_data']['companyReportingCurrencyDecimalPlaces']);*/

                    $itemledger_arr[$a]['documentID'] = $double_entry['master_data']['documentID'];
                    $itemledger_arr[$a]['documentCode'] = $double_entry['master_data']['documentID'];
                    $itemledger_arr[$a]['documentAutoID'] = $double_entry['master_data']['workProcessID'];
                    $itemledger_arr[$a]['documentSystemCode'] = $double_entry['master_data']['documentCode'];
                    $itemledger_arr[$a]['documentDate'] = $double_entry['master_data']['closedDate'];
                    $itemledger_arr[$a]['referenceNumber'] = null;
                    $itemledger_arr[$a]['companyFinanceYearID'] = $double_entry['master_data']['companyFinanceYearID'];
                    $itemledger_arr[$a]['companyFinanceYear'] = $double_entry['master_data']['companyFinanceYear'];
                    $itemledger_arr[$a]['FYBegin'] = $double_entry['master_data']['FYBegin'];
                    $itemledger_arr[$a]['FYEnd'] = $double_entry['master_data']['FYEnd'];
                    $itemledger_arr[$a]['FYPeriodDateFrom'] = $double_entry['master_data']['FYPeriodDateFrom'];
                    $itemledger_arr[$a]['FYPeriodDateTo'] = $double_entry['master_data']['FYPeriodDateTo'];
                    $itemledger_arr[$a]['wareHouseAutoID'] = $double_entry['master_data']['wareHouseAutoID'];
                    $itemledger_arr[$a]['wareHouseCode'] = $double_entry['master_data']['wareHouseCode'];
                    $itemledger_arr[$a]['wareHouseLocation'] = $double_entry['master_data']['wareHouseLocation'];
                    $itemledger_arr[$a]['wareHouseDescription'] = $double_entry['master_data']['wareHouseDescription'];
                    $itemledger_arr[$a]['itemAutoID'] = $materialConsumption[$a]['itemAutoID'];
                    $itemledger_arr[$a]['itemSystemCode'] = $materialConsumption[$a]['itemSystemCode'];
                    $itemledger_arr[$a]['itemDescription'] = $materialConsumption[$a]['itemDescription'];
                    $itemledger_arr[$a]['defaultUOMID'] = $materialConsumption[$a]['defaultUnitOfMeasureID'];
                    $itemledger_arr[$a]['defaultUOM'] = $materialConsumption[$a]['defaultUnitOfMeasure'];
                    $itemledger_arr[$a]['transactionUOM'] = $materialConsumption[$a]['defaultUnitOfMeasure'];
                    $itemledger_arr[$a]['transactionUOMID'] = $materialConsumption[$a]['defaultUnitOfMeasureID'];
                    $itemledger_arr[$a]['transactionQTY'] = $materialConsumption[$a]['qtyUsed'] * -1;
                    $itemledger_arr[$a]['convertionRate'] = 1;
                    $itemledger_arr[$a]['currentStock'] = $item_arr[$a]['currentStock'];
                    $itemledger_arr[$a]['PLGLAutoID'] = $materialConsumption[$a]['costGLAutoID'];
                    $itemledger_arr[$a]['PLSystemGLCode'] = $materialConsumption[$a]['costSystemGLCode'];
                    $itemledger_arr[$a]['PLGLCode'] = $materialConsumption[$a]['costGLCode'];
                    $itemledger_arr[$a]['PLDescription'] = $materialConsumption[$a]['costDescription'];
                    $itemledger_arr[$a]['PLType'] = $materialConsumption[$a]['costType'];
                    $itemledger_arr[$a]['BLGLAutoID'] = $materialConsumption[$a]['assteGLAutoID'];
                    $itemledger_arr[$a]['BLSystemGLCode'] = $materialConsumption[$a]['assteSystemGLCode'];
                    $itemledger_arr[$a]['BLGLCode'] = $materialConsumption[$a]['assteGLCode'];
                    $itemledger_arr[$a]['BLDescription'] = $materialConsumption[$a]['assteDescription'];
                    $itemledger_arr[$a]['BLType'] = $materialConsumption[$a]['assteType'];
                    $itemledger_arr[$a]['transactionAmount'] = $materialConsumption[$a]['materialCharge'] * -1;
                    $itemledger_arr[$a]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                    $itemledger_arr[$a]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                    $itemledger_arr[$a]['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                    $itemledger_arr[$a]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                    $itemledger_arr[$a]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                    $itemledger_arr[$a]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                    $itemledger_arr[$a]['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                    $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                    $itemledger_arr[$a]['companyLocalAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyLocalExchangeRate']), $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['companyLocalWacAmount'] = $materialConsumption[$a]['companyLocalWacAmount'];
                    $itemledger_arr[$a]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                    $itemledger_arr[$a]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                    $itemledger_arr[$a]['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                    $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                    $itemledger_arr[$a]['companyReportingAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyReportingExchangeRate']), $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['companyReportingWacAmount'] = ($materialConsumption[$a]['companyLocalWacAmount'] / $itemledger_arr[$a]['companyReportingExchangeRate']);
                    $itemledger_arr[$a]['partyCurrencyID'] = $double_entry['master_data']['mfqCustomerCurrencyID'];
                    $itemledger_arr[$a]['partyCurrency'] = $double_entry['master_data']['mfqCustomerCurrency'];
                    $itemledger_arr[$a]['partyCurrencyExchangeRate'] = 1;
                    $itemledger_arr[$a]['partyCurrencyDecimalPlaces'] = $double_entry['master_data']['mfqCustomerCurrencyDecimalPlaces'];
                    $itemledger_arr[$a]['partyCurrencyAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['partyCurrencyExchangeRate']), $itemledger_arr[$a]['partyCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['confirmedYN'] = $double_entry['master_data']['confirmedYN'];
                    $itemledger_arr[$a]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                    $itemledger_arr[$a]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                    $itemledger_arr[$a]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                    /* $itemledger_arr[$a]['approvedYN'] =  $double_entry['master_data']['approvedYN'];
                     $itemledger_arr[$a]['approvedDate'] =  $double_entry['master_data']['approvedDate'];
                     $itemledger_arr[$a]['approvedbyEmpID'] =  $double_entry['master_data']['approvedbyEmpID'];
                     $itemledger_arr[$a]['approvedbyEmpName'] =  $double_entry['master_data']['approvedbyEmpName'];*/
                    $itemledger_arr[$a]['segmentID'] = $double_entry['master_data']['segmentID'];
                    $itemledger_arr[$a]['segmentCode'] = $double_entry['master_data']['segmentCode'];
                    $itemledger_arr[$a]['companyID'] = $double_entry['master_data']['companyID'];
                    /*$itemledger_arr[$a]['companyCode'] =  $double_entry['master_data']['companyCode'];*/
                    $itemledger_arr[$a]['createdUserGroup'] = $double_entry['master_data']['createdUserGroup'];
                    $itemledger_arr[$a]['createdPCID'] = $double_entry['master_data']['createdPCID'];
                    $itemledger_arr[$a]['createdUserID'] = $double_entry['master_data']['createdUserID'];
                    $itemledger_arr[$a]['createdDateTime'] = $double_entry['master_data']['createdDateTime'];
                    $itemledger_arr[$a]['createdUserName'] = $double_entry['master_data']['createdUserName'];
                    $itemledger_arr[$a]['modifiedPCID'] = $double_entry['master_data']['modifiedPCID'];
                    $itemledger_arr[$a]['modifiedUserID'] = $double_entry['master_data']['modifiedUserID'];
                    $itemledger_arr[$a]['modifiedDateTime'] = $double_entry['master_data']['modifiedDateTime'];
                    $itemledger_arr[$a]['modifiedUserName'] = $double_entry['master_data']['modifiedUserName'];
                }
            }

            if (!empty($item_arr)) {
                //$item_arr = array_values($item_arr);
                $this->db->update_batch('srp_erp_itemmaster', $item_arr, 'itemAutoID');
            }

            if (!empty($itemledger_arr)) {
                //$itemledger_arr = array_values($itemledger_arr);
                $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr);
            }

            $itemledger_arr = array();
            $item_arr = array();
            $itemledger_arr2 = array();
            $item_arr2 = array();
            if ($double_entry['master_data']['mainCategory'] == 'Inventory' or $double_entry['master_data']['mainCategory'] == 'Non Inventory') {
                $itemAutoID = $double_entry['master_data']['itemAutoID'];
                $qty = $double_entry['master_data']['qty'] / 1;
                $wareHouseAutoID = $double_entry['master_data']['wareHouseAutoID'];
                $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock + {$qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' and itemAutoID='{$itemAutoID}'");
                $item_arr['itemAutoID'] = $double_entry['master_data']['itemAutoID'];
                $item_arr['currentStock'] = ($double_entry['master_data']['currentStock'] + $qty);
                $item_arr['companyLocalWacAmount'] = round(((($double_entry['master_data']['currentStock'] * $double_entry['master_data']['companyLocalWacAmount']) + $double_entry['total']) / $item_arr['currentStock']), $double_entry['master_data']['companyLocalCurrencyDecimalPlaces']);
                $item_arr['companyReportingWacAmount'] = round(((($item_arr['currentStock'] * $double_entry['master_data']['companyReportingWacAmount']) + ($double_entry['total'] / $double_entry['master_data']['companyReportingExchangeRate'])) / $item_arr['currentStock']), $double_entry['master_data']['companyReportingCurrencyDecimalPlaces']);

                $itemledger_arr['documentID'] = $double_entry['master_data']['documentID'];
                $itemledger_arr['documentCode'] = $double_entry['master_data']['documentID'];
                $itemledger_arr['documentAutoID'] = $double_entry['master_data']['workProcessID'];
                $itemledger_arr['documentSystemCode'] = $double_entry['master_data']['documentCode'];
                $itemledger_arr['documentDate'] = $double_entry['master_data']['closedDate'];
                $itemledger_arr['referenceNumber'] = null;
                $itemledger_arr['companyFinanceYearID'] = $double_entry['master_data']['companyFinanceYearID'];
                $itemledger_arr['companyFinanceYear'] = $double_entry['master_data']['companyFinanceYear'];
                $itemledger_arr['FYBegin'] = $double_entry['master_data']['FYBegin'];
                $itemledger_arr['FYEnd'] = $double_entry['master_data']['FYEnd'];
                $itemledger_arr['FYPeriodDateFrom'] = $double_entry['master_data']['FYPeriodDateFrom'];
                $itemledger_arr['FYPeriodDateTo'] = $double_entry['master_data']['FYPeriodDateTo'];
                $itemledger_arr['wareHouseAutoID'] = $double_entry['master_data']['wareHouseAutoID'];
                $itemledger_arr['wareHouseCode'] = $double_entry['master_data']['wareHouseCode'];
                $itemledger_arr['wareHouseLocation'] = $double_entry['master_data']['wareHouseLocation'];
                $itemledger_arr['wareHouseDescription'] = $double_entry['master_data']['wareHouseDescription'];
                $itemledger_arr['itemAutoID'] = $double_entry['master_data']['itemAutoID'];
                $itemledger_arr['itemSystemCode'] = $double_entry['master_data']['itemSystemCode'];
                $itemledger_arr['itemDescription'] = $double_entry['master_data']['itemDescription'];
                $itemledger_arr['defaultUOMID'] = $double_entry['master_data']['defaultUnitOfMeasureID'];
                $itemledger_arr['defaultUOM'] = $double_entry['master_data']['defaultUnitOfMeasure'];
                $itemledger_arr['transactionUOM'] = $double_entry['master_data']['defaultUnitOfMeasure'];
                $itemledger_arr['transactionUOMID'] = $double_entry['master_data']['defaultUnitOfMeasureID'];
                $itemledger_arr['transactionQTY'] = $double_entry['master_data']['qty'];
                $itemledger_arr['convertionRate'] = 1;
                $itemledger_arr['currentStock'] = $item_arr['currentStock'];
                $itemledger_arr['PLGLAutoID'] = $double_entry['master_data']['costGLAutoID'];
                $itemledger_arr['PLSystemGLCode'] = $double_entry['master_data']['costSystemGLCode'];
                $itemledger_arr['PLGLCode'] = $double_entry['master_data']['costGLCode'];
                $itemledger_arr['PLDescription'] = $double_entry['master_data']['costDescription'];
                $itemledger_arr['PLType'] = $double_entry['master_data']['costType'];
                $itemledger_arr['BLGLAutoID'] = $double_entry['master_data']['assteGLAutoID'];
                $itemledger_arr['BLSystemGLCode'] = $double_entry['master_data']['assteSystemGLCode'];
                $itemledger_arr['BLGLCode'] = $double_entry['master_data']['assteGLCode'];
                $itemledger_arr['BLDescription'] = $double_entry['master_data']['assteDescription'];
                $itemledger_arr['BLType'] = $double_entry['master_data']['assteType'];
                $itemledger_arr['transactionAmount'] = $double_entry['total'];
                $itemledger_arr['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $itemledger_arr['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $itemledger_arr['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                $itemledger_arr['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $itemledger_arr['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $itemledger_arr['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $itemledger_arr['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                $itemledger_arr['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $itemledger_arr['companyLocalAmount'] = round(($itemledger_arr['transactionAmount'] / $itemledger_arr['companyLocalExchangeRate']), $itemledger_arr['companyLocalCurrencyDecimalPlaces']);
                $itemledger_arr['companyLocalWacAmount'] = $item_arr['companyLocalWacAmount'];
                $itemledger_arr['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $itemledger_arr['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $itemledger_arr['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                $itemledger_arr['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $itemledger_arr['companyReportingAmount'] = round(($itemledger_arr['transactionAmount'] / $itemledger_arr['companyReportingExchangeRate']), $itemledger_arr['companyReportingCurrencyDecimalPlaces']);
                $itemledger_arr['companyReportingWacAmount'] = $item_arr['companyReportingWacAmount'];
                $itemledger_arr['partyCurrencyID'] = $double_entry['master_data']['mfqCustomerCurrencyID'];
                $itemledger_arr['partyCurrency'] = $double_entry['master_data']['mfqCustomerCurrency'];
                $itemledger_arr['partyCurrencyExchangeRate'] = 1;
                $itemledger_arr['partyCurrencyDecimalPlaces'] = $double_entry['master_data']['mfqCustomerCurrencyDecimalPlaces'];
                $itemledger_arr['partyCurrencyAmount'] = round(($itemledger_arr['transactionAmount'] / $itemledger_arr['partyCurrencyExchangeRate']), $itemledger_arr['partyCurrencyDecimalPlaces']);
                $itemledger_arr['confirmedYN'] = $double_entry['master_data']['confirmedYN'];
                $itemledger_arr['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $itemledger_arr['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $itemledger_arr['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                /* $itemledger_arr['approvedYN'] =  $double_entry['master_data']['approvedYN'];
                 $itemledger_arr['approvedDate'] =  $double_entry['master_data']['approvedDate'];
                 $itemledger_arr['approvedbyEmpID'] =  $double_entry['master_data']['approvedbyEmpID'];
                 $itemledger_arr['approvedbyEmpName'] =  $double_entry['master_data']['approvedbyEmpName'];*/
                $itemledger_arr['segmentID'] = $double_entry['master_data']['segmentID'];
                $itemledger_arr['segmentCode'] = $double_entry['master_data']['segmentCode'];
                $itemledger_arr['companyID'] = $double_entry['master_data']['companyID'];
                /*$itemledger_arr['companyCode'] =  $double_entry['master_data']['companyCode'];*/
                $itemledger_arr['createdUserGroup'] = $double_entry['master_data']['createdUserGroup'];
                $itemledger_arr['createdPCID'] = $double_entry['master_data']['createdPCID'];
                $itemledger_arr['createdUserID'] = $double_entry['master_data']['createdUserID'];
                $itemledger_arr['createdDateTime'] = $double_entry['master_data']['createdDateTime'];
                $itemledger_arr['createdUserName'] = $double_entry['master_data']['createdUserName'];
                $itemledger_arr['modifiedPCID'] = $double_entry['master_data']['modifiedPCID'];
                $itemledger_arr['modifiedUserID'] = $double_entry['master_data']['modifiedUserID'];
                $itemledger_arr['modifiedDateTime'] = $double_entry['master_data']['modifiedDateTime'];
                $itemledger_arr['modifiedUserName'] = $double_entry['master_data']['modifiedUserName'];

                if (!empty($item_arr)) {
                    $item_arr2[] = $item_arr;
                    $this->db->update_batch('srp_erp_itemmaster', $item_arr2, 'itemAutoID');
                }

                if (!empty($itemledger_arr)) {
                    $itemledger_arr2[] = $itemledger_arr;
                    $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr2);
                }
            }
            $machine = $this->db->query("SELECT SUM(totalValue) as totalValue FROM srp_erp_mfq_jc_machine WHERE workProcessID='{$double_entry['master_data']['workProcessID']}'")->row_array();
            $unitPrice = (($double_entry['total'] + $machine["totalValue"]) / $double_entry['master_data']['qty']);

            $this->db->set('postingFinanceDate', $format_postingFinanceDate);
            $this->db->set('companyFinancePeriodID', $financePeriod["companyFinancePeriodID"]);
            $this->db->set('companyFinanceYearID', $financePeriod["companyFinanceYearID"]);
            $this->db->set('FYBegin', $financePeriod["beginingDate"]);
            $this->db->set('FYEnd', $financePeriod["endingDate"]);
            $this->db->set('FYPeriodDateFrom', $financePeriod["dateFrom"]);
            $this->db->set('FYPeriodDateTo', $financePeriod["dateTo"]);
            $this->db->set('unitPrice', $unitPrice);
            $this->db->where('workProcessID', $double_entry['master_data']['workProcessID']);
            $result = $this->db->update('srp_erp_mfq_job');
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', "Error Occurred");
        } else {
            $this->db->trans_commit();
            return array('s', "Successfully approved");
        }
    }


    function fetch_double_entry_job($jobID, $jobcardID)
    {
        $gl_array = array();
        $gl_array['gl_detail'] = array();
        $master = "";
        $this->db->select('srp_erp_mfq_itemmaster.*');
        $this->db->where('srp_erp_mfq_job.workProcessID', $jobID);
        $this->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_itemmaster.mfqItemID=srp_erp_mfq_job.mfqItemID', 'LEFT');
        $chk_category = $this->db->get('srp_erp_mfq_job')->row_array();

        if($chk_category["mainCategory"] == "Service" || $chk_category["mainCategory"] == "Non Inventory"){
            $this->db->select('srp_erp_mfq_job.*,customerAutoID,customerSystemCode,customerName,seg.segmentID,seg.segmentCode,itm.*,wh.*');
            $this->db->where('srp_erp_mfq_job.workProcessID', $jobID);
            $this->db->join("(SELECT srp_erp_segment.segmentCode,srp_erp_mfq_segment.segmentID,mfqSegmentID FROM srp_erp_mfq_segment LEFT JOIN srp_erp_segment ON srp_erp_mfq_segment.segmentID = srp_erp_segment.segmentID) seg", "srp_erp_mfq_job.mfqSegmentID=seg.mfqSegmentID", "left");
            $this->db->join("(SELECT srp_erp_customermaster.*,mfqCustomerAutoID FROM srp_erp_mfq_customermaster LEFT JOIN srp_erp_customermaster ON srp_erp_mfq_customermaster.CustomerAutoID = srp_erp_customermaster.CustomerAutoID) cust", "srp_erp_mfq_job.mfqCustomerAutoID=cust.mfqCustomerAutoID", "INNER");
            $this->db->join('(SELECT GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,masterCategory,subCategory,srp_erp_mfq_itemmaster.mfqItemID as itemID,srp_erp_itemmaster.itemAutoID,
	srp_erp_itemmaster.itemSystemCode,
	srp_erp_itemmaster.itemDescription,
	srp_erp_itemmaster.defaultUnitOfMeasureID,
	srp_erp_itemmaster.defaultUnitOfMeasure,
	srp_erp_itemmaster.currentStock,
	srp_erp_itemmaster.mainCategory,
	srp_erp_itemmaster.costGLAutoID,
	srp_erp_itemmaster.costSystemGLCode,
	srp_erp_itemmaster.costGLCode,
	srp_erp_itemmaster.costDescription,
	srp_erp_itemmaster.costType,
	srp_erp_itemmaster.assteGLAutoID,
	srp_erp_itemmaster.assteSystemGLCode,
	srp_erp_itemmaster.assteGLCode,
	srp_erp_itemmaster.assteDescription,
	srp_erp_itemmaster.assteType, 
	srp_erp_itemmaster.companyLocalWacAmount, 
	srp_erp_itemmaster.companyReportingWacAmount 
	FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_itemmaster ON srp_erp_mfq_itemmaster.itemAutoID = srp_erp_itemmaster.itemAutoID LEFT JOIN srp_erp_chartofaccounts ON srp_erp_mfq_itemmaster.unbilledServicesGLAutoID = srp_erp_chartofaccounts.GLAutoID) itm', 'itm.itemID=srp_erp_mfq_job.mfqItemID', 'LEFT');
            $this->db->join("(SELECT srp_erp_warehousemaster.wareHouseAutoID,srp_erp_warehousemaster.wareHouseCode,srp_erp_warehousemaster.wareHouseLocation,srp_erp_warehousemaster.wareHouseDescription,mfqWarehouseAutoID FROM srp_erp_mfq_warehousemaster LEFT JOIN srp_erp_warehousemaster ON srp_erp_mfq_warehousemaster.warehouseAutoID = srp_erp_warehousemaster.warehouseAutoID) wh", "wh.mfqWarehouseAutoID=srp_erp_mfq_job.mfqWarehouseAutoID", "left");
            $master = $this->db->get('srp_erp_mfq_job')->row_array();
        }else{
            $this->db->select('srp_erp_mfq_job.*,customerAutoID,customerSystemCode,customerName,seg.segmentID,seg.segmentCode,itm.*,wh.*');
            $this->db->where('srp_erp_mfq_job.workProcessID', $jobID);
            $this->db->join("(SELECT srp_erp_segment.segmentCode,srp_erp_mfq_segment.segmentID,mfqSegmentID FROM srp_erp_mfq_segment LEFT JOIN srp_erp_segment ON srp_erp_mfq_segment.segmentID = srp_erp_segment.segmentID) seg", "srp_erp_mfq_job.mfqSegmentID=seg.mfqSegmentID", "left");
            $this->db->join("(SELECT srp_erp_customermaster.*,mfqCustomerAutoID FROM srp_erp_mfq_customermaster LEFT JOIN srp_erp_customermaster ON srp_erp_mfq_customermaster.CustomerAutoID = srp_erp_customermaster.CustomerAutoID) cust", "srp_erp_mfq_job.mfqCustomerAutoID=cust.mfqCustomerAutoID", "INNER");
            $this->db->join('(SELECT GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,masterCategory,subCategory,srp_erp_mfq_itemmaster.mfqItemID as itemID,srp_erp_itemmaster.itemAutoID,
	srp_erp_itemmaster.itemSystemCode,
	srp_erp_itemmaster.itemDescription,
	srp_erp_itemmaster.defaultUnitOfMeasureID,
	srp_erp_itemmaster.defaultUnitOfMeasure,
	srp_erp_itemmaster.currentStock,
	srp_erp_itemmaster.mainCategory,
	srp_erp_itemmaster.costGLAutoID,
	srp_erp_itemmaster.costSystemGLCode,
	srp_erp_itemmaster.costGLCode,
	srp_erp_itemmaster.costDescription,
	srp_erp_itemmaster.costType,
	srp_erp_itemmaster.assteGLAutoID,
	srp_erp_itemmaster.assteSystemGLCode,
	srp_erp_itemmaster.assteGLCode,
	srp_erp_itemmaster.assteDescription,
	srp_erp_itemmaster.assteType, 
	srp_erp_itemmaster.companyLocalWacAmount, 
	srp_erp_itemmaster.companyReportingWacAmount 
	FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_itemmaster ON srp_erp_mfq_itemmaster.itemAutoID = srp_erp_itemmaster.itemAutoID LEFT JOIN srp_erp_chartofaccounts ON srp_erp_itemmaster.assteGLAutoID = srp_erp_chartofaccounts.GLAutoID) itm', 'itm.itemID=srp_erp_mfq_job.mfqItemID', 'LEFT');
            $this->db->join("(SELECT srp_erp_warehousemaster.wareHouseAutoID,srp_erp_warehousemaster.wareHouseCode,srp_erp_warehousemaster.wareHouseLocation,srp_erp_warehousemaster.wareHouseDescription,mfqWarehouseAutoID FROM srp_erp_mfq_warehousemaster LEFT JOIN srp_erp_warehousemaster ON srp_erp_mfq_warehousemaster.warehouseAutoID = srp_erp_warehousemaster.warehouseAutoID) wh", "wh.mfqWarehouseAutoID=srp_erp_mfq_job.mfqWarehouseAutoID", "left");
            $master = $this->db->get('srp_erp_mfq_job')->row_array();
        }

        $this->db->select('oh.*,srp_erp_mfq_jc_overhead.*');
        $this->db->where('workProcessID', $jobID);
        $this->db->where('jobCardID', $jobcardID);
        $this->db->join('(SELECT srp_erp_chartofaccounts.*,overHeadID FROM srp_erp_mfq_overhead LEFT JOIN srp_erp_chartofaccounts ON srp_erp_mfq_overhead.financeGLAutoID = srp_erp_chartofaccounts.GLAutoID) oh', 'oh.overHeadID=srp_erp_mfq_jc_overhead.overHeadID', 'LEFT');
        $overheadGL = $this->db->get('srp_erp_mfq_jc_overhead')->result_array();

        $this->db->select('oh.*,srp_erp_mfq_jc_labourtask.*');
        $this->db->where('workProcessID', $jobID);
        $this->db->where('jobCardID', $jobcardID);
        $this->db->join('(SELECT srp_erp_chartofaccounts.*,overHeadID FROM srp_erp_mfq_overhead LEFT JOIN srp_erp_chartofaccounts ON srp_erp_mfq_overhead.financeGLAutoID = srp_erp_chartofaccounts.GLAutoID) oh', 'oh.overHeadID=srp_erp_mfq_jc_labourtask.labourTask', 'LEFT');
        $labourGL = $this->db->get('srp_erp_mfq_jc_labourtask')->result_array();

        $this->db->select('materialCharge as materialCharge,jcMaterialConsumptionID,wh.*');
        $this->db->where('srp_erp_mfq_jc_materialconsumption.workProcessID', $jobID);
        $this->db->where('srp_erp_mfq_jc_materialconsumption.jobCardID', $jobcardID);
        $this->db->join("(SELECT GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,masterCategory,subCategory,workProcessID FROM srp_erp_mfq_job LEFT JOIN srp_erp_mfq_warehousemaster ON srp_erp_mfq_job.mfqWarehouseAutoID = srp_erp_mfq_warehousemaster.mfqWarehouseAutoID LEFT JOIN srp_erp_warehousemaster ON srp_erp_mfq_warehousemaster.warehouseAutoID = srp_erp_warehousemaster.warehouseAutoID LEFT JOIN srp_erp_chartofaccounts ON srp_erp_warehousemaster.WIPGLAutoID = srp_erp_chartofaccounts.GLAutoID) wh", "wh.workProcessID=srp_erp_mfq_jc_materialconsumption.workProcessID", "left");
        $materialGL = $this->db->get('srp_erp_mfq_jc_materialconsumption')->result_array();

        $globalArray = array();
        $total = 0;

        /*overhead GL*/
        if ($overheadGL) {
            foreach ($overheadGL as $val) {
                $data_arr['auto_id'] = $val['jcOverHeadID'];
                $data_arr['gl_auto_id'] = $val['GLAutoID'];
                $data_arr['gl_code'] = $val['systemAccountCode'];
                $data_arr['secondary'] = $val['GLSecondaryCode'];
                $data_arr['gl_desc'] = $val['GLDescription'];
                $data_arr['gl_type'] = $val['subCategory'];
                $data_arr['segment_id'] = $master['segmentID'];
                $data_arr['segment'] = $master['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $master['customerAutoID'];
                $data_arr['partySystemCode'] = $master['customerSystemCode'];
                $data_arr['partyName'] = $master['customerName'];
                $data_arr['partyCurrencyID'] = $master['mfqCustomerCurrencyID'];
                $data_arr['partyCurrency'] = $master['mfqCustomerCurrency'];
                $data_arr['transactionExchangeRate'] = $val['transactionExchangeRate'];
                $data_arr['companyLocalExchangeRate'] = $val['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $val['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['mfqCustomerCurrencyDecimalPlaces'];
                $data_arr['partyCurrencyAmount'] = ($val['totalValue'] / $data_arr['partyExchangeRate']);
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $val['totalValue'];
                $data_arr['amount_type'] = 'cr';
                $total += $val['totalValue'];
                array_push($globalArray, $data_arr);
            }
        }

        /*labour GL*/
        if ($labourGL) {
            foreach ($labourGL as $val) {
                $data_arr['auto_id'] = $val['jcLabourTaskID'];
                $data_arr['gl_auto_id'] = $val['GLAutoID'];
                $data_arr['gl_code'] = $val['systemAccountCode'];
                $data_arr['secondary'] = $val['GLSecondaryCode'];
                $data_arr['gl_desc'] = $val['GLDescription'];
                $data_arr['gl_type'] = $val['subCategory'];
                $data_arr['segment_id'] = $master['segmentID'];
                $data_arr['segment'] = $master['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $master['customerAutoID'];
                $data_arr['partySystemCode'] = $master['customerSystemCode'];
                $data_arr['partyName'] = $master['customerName'];
                $data_arr['partyCurrencyID'] = $master['mfqCustomerCurrencyID'];
                $data_arr['partyCurrency'] = $master['mfqCustomerCurrency'];
                $data_arr['transactionExchangeRate'] = $val['transactionExchangeRate'];
                $data_arr['companyLocalExchangeRate'] = $val['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $val['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['mfqCustomerCurrencyDecimalPlaces'];
                $data_arr['partyCurrencyAmount'] = ($val['totalValue'] / $data_arr['partyExchangeRate']);
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $val['totalValue'];
                $data_arr['amount_type'] = 'cr';
                $total += $val['totalValue'];
                array_push($globalArray, $data_arr);
            }
        }
        /*material consumption GL*/
        if ($materialGL) {
            foreach ($materialGL as $val) {
                $data_arr['auto_id'] = $val['jcMaterialConsumptionID'];
                $data_arr['gl_auto_id'] = $val['GLAutoID'];
                $data_arr['gl_code'] = $val['systemAccountCode'];
                $data_arr['secondary'] = $val['GLSecondaryCode'];
                $data_arr['gl_desc'] = $val['GLDescription'];
                $data_arr['gl_type'] = $val['subCategory'];
                $data_arr['segment_id'] = $master['segmentID'];
                $data_arr['segment'] = $master['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $master['customerAutoID'];
                $data_arr['partySystemCode'] = $master['customerSystemCode'];
                $data_arr['partyName'] = $master['customerName'];
                $data_arr['partyCurrencyID'] = $master['mfqCustomerCurrencyID'];
                $data_arr['partyCurrency'] = $master['mfqCustomerCurrency'];
                $data_arr['transactionExchangeRate'] = $master['transactionExchangeRate'];
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['mfqCustomerCurrencyDecimalPlaces'];
                $data_arr['partyCurrencyAmount'] = ($val['materialCharge'] / $data_arr['partyExchangeRate']);
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $val['materialCharge'];
                $data_arr['amount_type'] = 'cr';
                $total += $val['materialCharge'];
                array_push($globalArray, $data_arr);
            }
        }

        $data_arr['auto_id'] = $master['workProcessID'];
        $data_arr['gl_auto_id'] = $master['GLAutoID'];
        $data_arr['gl_code'] = $master['systemAccountCode'];
        $data_arr['secondary'] = $master['GLSecondaryCode'];
        $data_arr['gl_desc'] = $master['GLDescription'];
        $data_arr['gl_type'] = $master['subCategory'];
        $data_arr['segment_id'] = $master['segmentID'];
        $data_arr['segment'] = $master['segmentCode'];
        $data_arr['projectID'] = NULL;
        $data_arr['projectExchangeRate'] = NULL;
        $data_arr['isAddon'] = 0;
        $data_arr['subLedgerType'] = 0;
        $data_arr['subLedgerDesc'] = null;
        $data_arr['partyContractID'] = null;
        $data_arr['partyType'] = '';
        $data_arr['partyAutoID'] = $master['customerAutoID'];
        $data_arr['partySystemCode'] = $master['customerSystemCode'];
        $data_arr['partyName'] = $master['customerName'];
        $data_arr['partyCurrencyID'] = $master['mfqCustomerCurrencyID'];
        $data_arr['partyCurrency'] = $master['mfqCustomerCurrency'];
        $data_arr['transactionExchangeRate'] = $master['transactionExchangeRate'];
        $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
        $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
        $data_arr['partyExchangeRate'] = 1;
        $data_arr['partyCurrencyDecimalPlaces'] = $master['mfqCustomerCurrencyDecimalPlaces'];
        $data_arr['partyCurrencyAmount'] = ($total / $data_arr['partyExchangeRate']);
        $data_arr['gl_dr'] = $total;
        $data_arr['gl_cr'] = '';
        $data_arr['amount_type'] = 'dr';
        array_push($globalArray, $data_arr);

        $gl_array['currency'] = $master['transactionCurrency'];
        $gl_array['decimal_places'] = $master['transactionCurrencyDecimalPlaces'];
        $gl_array['code'] = 'JOB';
        $gl_array['name'] = 'Job';
        $gl_array['primary_Code'] = $master['documentCode'];
        $gl_array['master_data'] = $master;
        $gl_array['date'] = $master['documentDate'];
        $gl_array['gl_detail'] = $globalArray;
        $gl_array['total'] = $total;
        return $gl_array;
    }

    function getSemifinishGoods()
    {
        $this->db->select("*");
        $this->db->from("srp_erp_mfq_warehousemaster");
        $this->db->where("mfqWarehouseAutoID", $this->input->post('mfqWarehouseAutoID'));
        $warehouse = $this->db->get()->row_array();

        $this->db->select("srp_erp_mfq_itemmaster.*,IFNULL(srp_erp_warehouseitems.currentStock,0) as currentStock,IFNULL(jcm.qtyUsed,0) as qtyInUse,IFNULL(jc.qty,0) as qtyInProduction, (((IFNULL(srp_erp_warehouseitems.currentStock,0)- IFNULL(jcm.qtyUsed,0)) + IFNULL(jc.qty,0))-(srp_erp_mfq_bom_materialconsumption.qtyUsed * {$this->input->post('qty')})) as remainingQty,srp_erp_mfq_billofmaterial.bomMasterID as bomID,(srp_erp_mfq_bom_materialconsumption.qtyUsed * {$this->input->post('qty')}) as bomQty");
        $this->db->from("srp_erp_mfq_bom_materialconsumption");
        $this->db->join("srp_erp_mfq_itemmaster", "srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
        $this->db->join("srp_erp_warehouseitems", "srp_erp_warehouseitems.itemAutoID = srp_erp_mfq_itemmaster.itemAutoID AND srp_erp_warehouseitems.companyID = " . current_companyID() . " AND srp_erp_warehouseitems.warehouseAutoID =" . $warehouse["warehouseAutoID"], "left");
        $this->db->join("srp_erp_mfq_billofmaterial", "srp_erp_mfq_billofmaterial.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
        $this->db->join("(SELECT SUM(qtyUsed) as qtyUsed,srp_erp_mfq_jc_materialconsumption.mfqItemID FROM srp_erp_mfq_jc_materialconsumption LEFT JOIN srp_erp_mfq_job ON srp_erp_mfq_job.workProcessID = srp_erp_mfq_jc_materialconsumption.workProcessID WHERE approvedYN = 0 AND srp_erp_mfq_jc_materialconsumption.companyID = " . current_companyID() . " GROUP BY srp_erp_mfq_jc_materialconsumption.mfqItemID) jcm", "jcm.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");
        $this->db->join("(SELECT SUM(qty) as qty,mfqItemID FROM srp_erp_mfq_job WHERE mfqWarehouseAutoID = " . $this->input->post('mfqWarehouseAutoID') . " AND approvedYN = 0 AND companyID = " . current_companyID() . " GROUP BY mfqItemID) jc", "jc.mfqItemID = srp_erp_mfq_bom_materialconsumption.mfqItemID", "left");

        $this->db->where("srp_erp_mfq_bom_materialconsumption.bomMasterID", $this->input->post('bomMasterID'));
        $this->db->where("itemType", 3);
        $this->db->where("srp_erp_mfq_itemmaster.mainCategory", "Inventory");
        $this->db->having("remainingQty < ", 0);
        $bomDetail = $this->db->get()->result_array();
        return $bomDetail;
    }

    function load_route_card()
    {
        $this->db->where('jobID', $this->input->post('jobID'));
        $this->db->where('workProcessFlowID', $this->input->post('workFlowID'));
        $data = $this->db->get('srp_erp_mfq_job_routecard')->result_array();
        return $data;
    }

    function save_route_card()
    {
        $this->db->trans_start();
        $routeCardDetailID = $this->input->post('routeCardDetailID');
        $process = $this->input->post('process');
        if (!empty($process)) {
            foreach ($process as $key => $val) {
                if (!empty($routeCardDetailID[$key])) {
                    if (!empty($process[$key])) {
                        $this->db->set('jobID', $this->input->post('jobID'));
                        $this->db->set('workProcessFlowID', $this->input->post('workProcessFlowID'));
                        $this->db->set('process', $this->input->post('process')[$key]);
                        $this->db->set('Instructions', $this->input->post('Instructions')[$key]);
                        $this->db->set('acceptanceCriteria', $this->input->post('acceptanceCriteria')[$key]);
                        $this->db->set('QAQC', $this->input->post('QAQCO')[$key]);
                        $this->db->set('production', $this->input->post('productionO')[$key]);
                        $this->db->set('companyID', current_companyID());
                        $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                        $this->db->set('modifiedUserID', current_userID());
                        $this->db->set('modifiedUserName', current_user());
                        $this->db->set('modifiedDateTime', current_date(true));
                        $this->db->where('routeCardDetailID', $routeCardDetailID[$key]);
                        $result = $this->db->update('srp_erp_mfq_job_routecard');
                    }
                } else {
                    if (!empty($process[$key])) {
                        $this->db->set('jobID', $this->input->post('jobID'));
                        $this->db->set('workProcessFlowID', $this->input->post('workProcessFlowID'));
                        $this->db->set('process', $this->input->post('process')[$key]);
                        $this->db->set('Instructions', $this->input->post('Instructions')[$key]);
                        $this->db->set('acceptanceCriteria', $this->input->post('acceptanceCriteria')[$key]);
                        $this->db->set('QAQC', $this->input->post('QAQCO')[$key]);
                        $this->db->set('production', $this->input->post('productionO')[$key]);
                        $this->db->set('companyID', current_companyID());
                        $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                        $this->db->set('createdUserID', current_userID());
                        $this->db->set('createdUserName', current_user());
                        $this->db->set('createdDateTime', current_date(true));
                        $result = $this->db->insert('srp_erp_mfq_job_routecard');
                    }
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Route card saved failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Route Card Saved Successfully.');
        }
    }

    function delete_routecard()
    {
        $result = $this->db->delete('srp_erp_mfq_job_routecard', array('routeCardDetailID' => $this->input->post('routeCardDetailID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!');
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function load_material_consumption_qty()
    {
        $this->db->where('jobID', $this->input->post('jobID'));
        $this->db->where('status', 0);
        $this->db->order_by('workProcessFlowID', 'asc');
        $templateDetailID = $this->db->get('srp_erp_mfq_workflowstatus')->row('templateDetailID');

        $this->db->select("srp_erp_mfq_jc_materialconsumption.workProcessID,jcMaterialConsumptionID,CONCAT(itemSystemCode,' - ',itemDescription) as itemDescription,IFNULL(wh.currentStock,0) as currentStock,srp_erp_mfq_jc_materialconsumption.qtyUsed,usageQty,srp_erp_mfq_jc_materialconsumption.jobCardID,srp_erp_mfq_jc_materialconsumption.mfqItemID as typeMasterAutoID");
        $this->db->from("srp_erp_mfq_jobcardmaster");
        $this->db->where('templateDetailID', $templateDetailID);
        $this->db->where('srp_erp_mfq_jobcardmaster.workProcessID', $this->input->post('jobID'));
        $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_warehousemaster', "srp_erp_mfq_warehousemaster.mfqWarehouseAutoID = srp_erp_mfq_job.mfqWarehouseAutoID", 'inner');
        $this->db->join('srp_erp_mfq_jc_materialconsumption', "srp_erp_mfq_jc_materialconsumption.jobCardID = srp_erp_mfq_jobcardmaster.jobcardID AND srp_erp_mfq_jc_materialconsumption.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_itemmaster', "srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_jc_materialconsumption.mfqItemID", 'inner');
        $this->db->join('(SELECT SUM(currentStock) as currentStock,wareHouseAutoID,itemAutoID FROM srp_erp_warehouseitems GROUP BY wareHouseAutoID,itemAutoID) wh', "wh.wareHouseAutoID = srp_erp_mfq_warehousemaster.warehouseAutoID AND srp_erp_mfq_itemmaster.itemAutoID = wh.itemAutoID", 'left');
        $data["material"] = $this->db->get()->result_array();

        $this->db->select("srp_erp_mfq_jc_overhead.workProcessID,jcOverHeadID,totalHours,CONCAT(overHeadCode,' - ',srp_erp_mfq_overhead.description) as description,usageHours,srp_erp_mfq_jc_overhead.jobCardID,srp_erp_mfq_jc_overhead.overHeadID as typeMasterAutoID");
        $this->db->from("srp_erp_mfq_jobcardmaster");
        $this->db->where('templateDetailID', $templateDetailID);
        $this->db->where('srp_erp_mfq_jobcardmaster.workProcessID', $this->input->post('jobID'));
        $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_jc_overhead', "srp_erp_mfq_jc_overhead.jobCardID = srp_erp_mfq_jobcardmaster.jobcardID AND srp_erp_mfq_jc_overhead.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_overhead', "srp_erp_mfq_jc_overhead.overHeadID = srp_erp_mfq_overhead.overHeadID", 'inner');
        $data["overhead"] = $this->db->get()->result_array();

        $this->db->select("srp_erp_mfq_jc_labourtask.workProcessID,jcLabourTaskID,totalHours,CONCAT(overHeadCode,' - ',srp_erp_mfq_overhead.description) as description,usageHours,srp_erp_mfq_jc_labourtask.jobCardID,srp_erp_mfq_jc_labourtask.labourTask as typeMasterAutoID");
        $this->db->from("srp_erp_mfq_jobcardmaster");
        $this->db->where('templateDetailID', $templateDetailID);
        $this->db->where('srp_erp_mfq_jobcardmaster.workProcessID', $this->input->post('jobID'));
        $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_jc_labourtask', "srp_erp_mfq_jc_labourtask.jobCardID = srp_erp_mfq_jobcardmaster.jobcardID AND srp_erp_mfq_jc_labourtask.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_overhead', "srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_labourtask.labourTask", 'inner');
        $data["labour"] = $this->db->get()->result_array();

        $this->db->select("srp_erp_mfq_jc_machine.workProcessID,totalHours,jcMachineID,CONCAT(faCode,' - ',assetDescription) as description,usageHours");
        $this->db->from("srp_erp_mfq_jobcardmaster");
        $this->db->where('templateDetailID', $templateDetailID);
        $this->db->where('srp_erp_mfq_jobcardmaster.workProcessID', $this->input->post('jobID'));
        $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_jc_machine', "srp_erp_mfq_jc_machine.jobCardID = srp_erp_mfq_jobcardmaster.jobcardID AND srp_erp_mfq_jc_machine.workProcessID = srp_erp_mfq_jobcardmaster.workProcessID", 'inner');
        $this->db->join('srp_erp_mfq_fa_asset_master', "srp_erp_mfq_fa_asset_master.mfq_faID = srp_erp_mfq_jc_machine.mfq_faID", 'inner');
        $data["machine"] = $this->db->get()->result_array();

        return $data;
    }

    function save_usage_qty()
    {
        $this->db->trans_start();
        $jobID = $this->input->post('jobID');
        if (!empty($jobID)) {
            foreach ($jobID as $key => $val) {
                if (!empty($jobID[$key]) && $this->input->post('qtyUsage')[$key] != 0) {
                    $this->db->set('jobID', $jobID[$key]);
                    $this->db->set('jobDetailID', $this->input->post('jcMaterialConsumptionID')[$key]);
                    $this->db->set('jobCardID', $this->input->post('jobCardID')[$key]);
                    $this->db->set('typeMasterAutoID', $this->input->post('typeMasterAutoID')[$key]);
                    $this->db->set('usageAmount', $this->input->post('qtyUsage')[$key]);
                    $this->db->set('companyID', current_companyID());
                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                    $this->db->set('createdUserID', current_userID());
                    $this->db->set('createdUserName', current_user());
                    $this->db->set('createdDateTime', current_date(true));
                    $this->db->set('typeID', 1);
                    $result = $this->db->insert('srp_erp_mfq_jc_usage');

                    $this->db->where('jobID', $jobID[$key]);
                    $this->db->where('typeID', 1);
                    $this->db->where('jobDetailID', $this->input->post('jcMaterialConsumptionID')[$key]);
                    $this->db->SELECT('SUM(usageAmount) as usageAmount');
                    $this->db->FROM('srp_erp_mfq_jc_usage');
                    $updateQtyUsed = $this->db->get()->row('usageAmount');

                    $result = $this->db->query("UPDATE srp_erp_mfq_jc_materialconsumption SET usageQty = {$updateQtyUsed},materialCost = unitCost * {$updateQtyUsed},materialCharge = (unitCost * {$updateQtyUsed})+((unitCost * {$updateQtyUsed})*(markUp/100))  WHERE jcMaterialConsumptionID=" . $this->input->post('jcMaterialConsumptionID')[$key]);
                }
            }
        }

        $ljobID = $this->input->post('ljobID');
        if (!empty($ljobID)) {
            foreach ($ljobID as $key => $val) {
                if (!empty($ljobID[$key]) && $this->input->post('ltotalHours')[$key] != 0) {
                    $this->db->set('jobID', $ljobID[$key]);
                    $this->db->set('jobDetailID', $this->input->post('jcLabourTaskID')[$key]);
                    $this->db->set('jobCardID', $this->input->post('jobCardID')[$key]);
                    $this->db->set('typeMasterAutoID', $this->input->post('typeMasterAutoID')[$key]);
                    $this->db->set('usageAmount', $this->input->post('ltotalHours')[$key]);
                    $this->db->set('companyID', current_companyID());
                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                    $this->db->set('createdUserID', current_userID());
                    $this->db->set('createdUserName', current_user());
                    $this->db->set('createdDateTime', current_date(true));
                    $this->db->set('typeID', 2);
                    $result = $this->db->insert('srp_erp_mfq_jc_usage');

                    $this->db->where('jobID', $ljobID[$key]);
                    $this->db->where('typeID', 2);
                    $this->db->where('jobDetailID', $this->input->post('jcLabourTaskID')[$key]);
                    $this->db->SELECT('SUM(usageAmount) as usageAmount');
                    $this->db->FROM('srp_erp_mfq_jc_usage');
                    $updateUsageAmount = $this->db->get()->row('usageAmount');

                    $result = $this->db->query("UPDATE srp_erp_mfq_jc_labourtask SET usageHours={$updateUsageAmount},totalValue = hourlyRate*{$updateUsageAmount}  WHERE jcLabourTaskID=" . $this->input->post('jcLabourTaskID')[$key]);
                }
            }
        }

        $ojobID = $this->input->post('ojobID');
        if (!empty($ojobID)) {
            foreach ($ojobID as $key => $val) {
                if (!empty($ojobID[$key]) && $this->input->post('ototalHours')[$key] != 0) {
                    $this->db->set('jobID', $ojobID[$key]);
                    $this->db->set('jobDetailID', $this->input->post('jcOverHeadID')[$key]);
                    $this->db->set('jobCardID', $this->input->post('jobCardID')[$key]);
                    $this->db->set('typeMasterAutoID', $this->input->post('typeMasterAutoID')[$key]);
                    $this->db->set('usageAmount', $this->input->post('ototalHours')[$key]);
                    $this->db->set('companyID', current_companyID());
                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                    $this->db->set('createdUserID', current_userID());
                    $this->db->set('createdUserName', current_user());
                    $this->db->set('createdDateTime', current_date(true));
                    $this->db->set('typeID', 3);
                    $result = $this->db->insert('srp_erp_mfq_jc_usage');

                    $this->db->where('jobID', $ojobID[$key]);
                    $this->db->where('typeID', 3);
                    $this->db->where('jobDetailID', $this->input->post('jcOverHeadID')[$key]);
                    $this->db->SELECT('SUM(usageAmount) as usageAmount');
                    $this->db->FROM('srp_erp_mfq_jc_usage');
                    $updateUsageAmount = $this->db->get()->row('usageAmount');

                    $result = $this->db->query("UPDATE srp_erp_mfq_jc_overhead SET usageHours={$updateUsageAmount} ,totalValue = hourlyRate*{$updateUsageAmount}  WHERE jcOverHeadID=" . $this->input->post('jcOverHeadID')[$key]);
                }
            }
        }

        $mjobID = $this->input->post('mjobID');
        if (!empty($mjobID)) {
            foreach ($mjobID as $key => $val) {
                if (!empty($mjobID[$key]) && $this->input->post('mtotalHours')[$key] != 0) {
                    $this->db->set('jobID', $mjobID[$key]);
                    $this->db->set('jobCardID', $this->input->post('jobCardID')[$key]);
                    $this->db->set('jobDetailID', $this->input->post('jcMachineID')[$key]);
                    $this->db->set('typeMasterAutoID', $this->input->post('typeMasterAutoID')[$key]);
                    $this->db->set('usageAmount', $this->input->post('mtotalHours')[$key]);
                    $this->db->set('companyID', current_companyID());
                    $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                    $this->db->set('createdUserID', current_userID());
                    $this->db->set('createdUserName', current_user());
                    $this->db->set('createdDateTime', current_date(true));
                    $this->db->set('typeID', 4);
                    $result = $this->db->insert('srp_erp_mfq_jc_usage');

                    $this->db->where('jobID', $mjobID[$key]);
                    $this->db->where('typeID', 4);
                    $this->db->where('jobDetailID', $this->input->post('jcMachineID')[$key]);
                    $this->db->SELECT('SUM(usageAmount) as usageAmount');
                    $this->db->FROM('srp_erp_mfq_jc_usage');
                    $updateUsageAmount = $this->db->get()->row('usageAmount');

                    $result = $this->db->query("UPDATE srp_erp_mfq_jc_machine SET usageHours={$updateUsageAmount},totalValue = hourlyRate*{$updateUsageAmount} WHERE jcMachineID=" . $this->input->post('jcMachineID')[$key]);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Usage quantity saved failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Usage Quantity Saved Successfully.');
        }
    }

    function load_usage_history()
    {
        $this->db->select("CONCAT(itemSystemCode,' - ',itemDescription) as itemDescription,qtyUsage,srp_employeesdetails.Ename1");
        $this->db->from("srp_erp_mfq_jc_usage");
        $this->db->where('jobID', $this->input->post("jobID"));
        $this->db->where('srp_erp_mfq_jc_usage.jcMaterialConsumptionID', $this->input->post("jcMaterialConsumptionID"));
        $this->db->where('srp_erp_mfq_jc_usage.companyID', current_companyID());
        $this->db->join('srp_erp_mfq_jc_materialconsumption', "srp_erp_mfq_jc_materialconsumption.jcMaterialConsumptionID = srp_erp_mfq_jc_usage.jcMaterialConsumptionID", 'inner');
        $this->db->join('srp_erp_mfq_itemmaster', "srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_jc_materialconsumption.mfqItemID", 'inner');
        $this->db->join('srp_employeesdetails', "srp_employeesdetails.EidNo = srp_erp_mfq_jc_usage.createdUserID", 'inner');
        $data = $this->db->get()->result_array();

        return $data;
    }

    function save_material_request()
    {
        $this->db->trans_start();

        $this->db->set('wareHouseAutoID', $this->input->post('wareHouseAutoID'));
        $this->db->where('mrAutoID', $this->input->post('mrAutoID'));
        $result = $this->db->update('srp_erp_materialrequest');

        $mrDetailID = $this->input->post('mrDetailID');
        if (!empty($mrDetailID)) {
            foreach ($mrDetailID as $key => $val) {
                if (!empty($mrDetailID[$key]) && $this->input->post('qtyRequested')[$key] >= 0) {
                    $this->db->set('qtyRequested', $this->input->post('qtyRequested')[$key]);
                    $this->db->where('mrDetailID', $mrDetailID[$key]);
                    $result = $this->db->update('srp_erp_materialrequestdetails');
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Material Request saved failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Material Request Saved Successfully.');
        }
    }

    function fetch_usage_history()
    {
        $workProcessID = $this->input->post('jobID');
        $autoID = $this->input->post('autoID');
        $typeID = $this->input->post('typeID');
        $convertFormat = convert_date_format_sql();

        $sql = "SELECT *,DATE_FORMAT(createdDateTime,'" . $convertFormat . "') as createdDateTime FROM srp_erp_mfq_jc_usage WHERE jobID =" . $workProcessID . " AND jobDetailID =" . $autoID . " AND typeID =" . $typeID . " AND companyID=" . current_companyID();
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
}
