<?php

class MFQ_Estimate_model extends ERP_Model
{
    function save_Estimate()
    {
        $last_id = "";
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $documentDate = input_format_date(trim($this->input->post('documentDate')), $date_format_policy);
        $deliveryDate = input_format_date(trim($this->input->post('deliveryDate')), $date_format_policy);
        if (!$this->input->post('estimateMasterID')) {
            $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_estimateMaster', 'estimateMasterID', 'companyID');
            $codes = $this->sequence->sequence_generator('EST', $serialInfo['serialNo']);
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('serialNo', $serialInfo['serialNo']);
            $this->db->set('estimateCode', $codes);
            $this->db->set('documentDate', $documentDate);
            $this->db->set('documentID', "EST");
            $this->db->set('deliveryDate', $deliveryDate);
            $this->db->set('description', $this->input->post('description'));
            $this->db->set('scopeOfWork', $this->input->post('scopeOfWork'));
            $this->db->set('technicalDetail', $this->input->post('technicalDetail'));
            $this->db->set('exclusions', $this->input->post('exclusions'));
            $this->db->set('submissionStatus', $this->input->post('submissionStatus'));
            $this->db->set('paymentTerms', $this->input->post('paymentTerms'));
            $this->db->set('termsAndCondition', $this->input->post('termsAndCondition'));
            $this->db->set('warranty', $this->input->post('warranty'));
            $this->db->set('validity', $this->input->post('validity'));
            $this->db->set('deliveryTerms', $this->input->post('deliveryTerms'));
            $this->db->set('currencyID', $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('companyID', current_companyID());
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));

            $result = $this->db->insert('srp_erp_mfq_estimateMaster');
            $last_id = $this->db->insert_id();

            $this->db->set('versionOrginID', $last_id);
            $this->db->where('estimateMasterID', $last_id);
            $result = $this->db->update('srp_erp_mfq_estimateMaster');

        } else {
            $last_id = $this->input->post('estimateMasterID');
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('documentDate', $documentDate);
            $this->db->set('deliveryDate', $deliveryDate);
            $this->db->set('documentID', "EST");
            $this->db->set('description', $this->input->post('description'));
            $this->db->set('scopeOfWork', $this->input->post('scopeOfWork'));
            $this->db->set('technicalDetail', $this->input->post('technicalDetail'));
            $this->db->set('exclusions', $this->input->post('exclusions'));
            $this->db->set('submissionStatus', $this->input->post('submissionStatus'));
            $this->db->set('paymentTerms', $this->input->post('paymentTerms'));
            $this->db->set('termsAndCondition', $this->input->post('termsAndCondition'));
            $this->db->set('warranty', $this->input->post('warranty'));
            $this->db->set('validity', $this->input->post('validity'));
            $this->db->set('deliveryTerms', $this->input->post('deliveryTerms'));
            $this->db->set('currencyID', $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('modifiedUserID', current_userID());
            $this->db->set('modifiedUserName', current_user());
            $this->db->set('modifiedDateTime', current_date(true));

            $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
            $result = $this->db->update('srp_erp_mfq_estimateMaster');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Estimate Saved Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Estimate Saved Successfully.', $last_id);
        }
    }

    function save_estimate_version()
    {
        $this->db->trans_start();
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_estimateMaster');
        $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
        $result = $this->db->get()->row_array();
        $documentCode = $result["estimateCode"];
        $estimateMasterID = $this->input->post('estimateMasterID');
        if ($result['versionOrginID'] != $this->input->post('estimateMasterID')) {
            $this->db->select('estimateCode');
            $this->db->from('srp_erp_mfq_estimateMaster');
            $this->db->where('estimateMasterID', $result['versionOrginID']);
            $docCode = $this->db->get()->row_array();
            $documentCode = $docCode["estimateCode"];
            $estimateMasterID = $result['versionOrginID'];
        }

        $this->db->select_max('versionLevel');
        $this->db->from('srp_erp_mfq_estimateMaster');
        $this->db->where('versionOrginID', $estimateMasterID);
        $max = $this->db->get()->row_array();

        $this->db->set('mfqCustomerAutoID', $result['mfqCustomerAutoID']);
        $this->db->set('serialNo', $result['serialNo']);
        $this->db->set('estimateCode', $documentCode . '/V' . ($max["versionLevel"] + 1));
        $this->db->set('documentDate', $result["documentDate"]);
        $this->db->set('documentID', "EST");
        $this->db->set('deliveryDate', $result["deliveryDate"]);
        $this->db->set('description', $result['description']);
        $this->db->set('scopeOfWork', $result['scopeOfWork']);
        $this->db->set('technicalDetail', $result['technicalDetail']);
        $this->db->set('submissionStatus', 6);
        $this->db->set('paymentTerms', $result['paymentTerms']);
        $this->db->set('termsAndCondition', $result['termsAndCondition']);
        $this->db->set('warranty', $result['warranty']);
        $this->db->set('validity', $result['validity']);
        $this->db->set('deliveryTerms', $result['deliveryTerms']);
        $this->db->set('versionOrginID', $estimateMasterID);
        $this->db->set('versionLevel', $max["versionLevel"] + 1);
        $this->db->set('currencyID', $this->common_data['company_data']['company_default_currencyID']);
        $this->db->set('companyID', current_companyID());
        $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
        $this->db->set('createdUserID', current_userID());
        $this->db->set('createdUserName', current_user());
        $this->db->set('createdDateTime', current_date(true));

        $result = $this->db->insert('srp_erp_mfq_estimateMaster');
        $last_id = $this->db->insert_id();

        $this->db->select('*');
        $this->db->from('srp_erp_mfq_estimatedetail');
        $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
        $result = $this->db->get()->result_array();
        foreach ($result as $val) {
            $this->db->set('estimateMasterID', $last_id);
            $this->db->set('ciMasterID', $val['ciMasterID']);
            $this->db->set('ciDetailID', $val['ciDetailID']);
            $this->db->set('bomMasterID', $val['bomMasterID']);
            $this->db->set('mfqItemID', $val['mfqItemID']);
            $this->db->set('expectedQty', $val['expectedQty']);
            $this->db->set('estimatedCost', $val['estimatedCost']);
            $this->db->set('transactionCurrencyID', $val['transactionCurrencyID']);
            $this->db->set('transactionCurrency', $val['transactionCurrency']);
            $this->db->set('transactionExchangeRate', $val['transactionExchangeRate']);
            $this->db->set('transactionCurrencyDecimalPlaces', $val['transactionCurrencyDecimalPlaces']);
            $this->db->set('companyLocalCurrencyID', $val['companyLocalCurrencyID']);
            $this->db->set('companyLocalCurrency', $val['companyLocalCurrency']);
            $this->db->set('companyLocalExchangeRate', $val['companyLocalExchangeRate']);
            $this->db->set('companyLocalCurrencyDecimalPlaces', $val['companyLocalCurrencyDecimalPlaces']);
            $this->db->set('companyReportingCurrency', $val['companyReportingCurrency']);
            $this->db->set('companyReportingCurrencyID', $val['companyReportingCurrencyID']);
            $this->db->set('companyReportingExchangeRate', $val['companyReportingExchangeRate']);
            $this->db->set('companyReportingCurrencyDecimalPlaces', $val['companyReportingCurrencyDecimalPlaces']);
            $this->db->set('companyID', $val['companyID']);
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));
            $this->db->set('sellingPrice', $val['sellingPrice']);
            $this->db->set('discountedPrice', $val['discountedPrice']);
            $result = $this->db->insert('srp_erp_mfq_estimateDetail');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Estimate Saved Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Estimate Saved Successfully.', $last_id);
        }
    }

    function delete_estimateDetail()
    {
        $this->db->trans_start();
        $result = $this->db->delete('srp_erp_mfq_estimateDetail', array('estimateDetailID' => $this->input->post('estimateDetailID')), 1);
        if ($result) {
            $this->db->select('SUM(estd.estimatedCost) as estimatedCost,SUM(discountedPrice) as discountedPrice,(((totMargin*SUM(discountedPrice)) / 100) + SUM(discountedPrice)) as totalSellingPrice,((((totMargin*SUM(discountedPrice)) / 100) + SUM(discountedPrice)) - ((totDiscount * (((totMargin*SUM(discountedPrice)) / 100) + SUM(discountedPrice))) / 100)) as totDiscountPrice');
            $this->db->from('srp_erp_mfq_estimateDetail estd');
            $this->db->join('srp_erp_mfq_estimateMaster est', 'est.estimateMasterID = estd.estimateMasterID', 'left');
            $this->db->where('estd.estimateMasterID', $this->input->post('estimateMasterID'));
            $result = $this->db->get()->row_array();

            $this->db->set('totalSellingPrice', $result["totalSellingPrice"]);
            $this->db->set('totDiscountPrice', $result["totDiscountPrice"]);
            $this->db->set('totalCost', $result["estimatedCost"]);
            $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
            $result = $this->db->update('srp_erp_mfq_estimateMaster');
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');

        } else {
            $this->db->trans_commit();
            return array('error' => 0, 'message' => 'Record deleted successfully!');
        }
    }

    function load_mfq_estimate()
    {
        $convertFormat = convert_date_format_sql();
        $estimateMasterID = $this->input->post('estimateMasterID');
        $this->db->select('DATE_FORMAT(est.documentDate,\'' . $convertFormat . '\') as documentDate,DATE_FORMAT(est.deliveryDate,\'' . $convertFormat . '\') as deliveryDate,est.description, cust.CustomerName as CustomerName,est.estimateMasterID,est.estimateCode,cust.mfqCustomerAutoID,est.scopeOfWork,est.technicalDetail,est.totMargin,est.totDiscount,est.submissionStatus,est.paymentTerms,est.termsAndCondition,est.totalSellingPrice,est.totDiscountPrice,est.totalCost,est.exclusions,est.designCode,est.designEditor,est.warranty,est.engineeringDrawings,est.engineeringDrawingsComment,est.submissionOfITP,est.itpComment,est.qcqtDocumentation,est.scopeOfWork,est.deliveryTerms,est.mfqSegmentID,est.mfqWarehouseAutoID,est.orderStatus,est.poNumber,srp_months.MonthName,est.validity,est.createdUserID,est.approvedbyEmpID,est.materialCertificationComment,est.manufacturingType');
        $this->db->from('srp_erp_mfq_estimateMaster est');
        $this->db->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = est.mfqCustomerAutoID', 'left');
        $this->db->join('srp_months', 'srp_months.MonthId = est.warranty', 'left');
        $this->db->where('est.estimateMasterID', $estimateMasterID);
        $result = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from('srp_erp_mfq_materialcertificate');
        $this->db->where('estimateMasterID', $estimateMasterID);
        $result2 = $this->db->get()->result_array();
        $finalarray = $result;
        $finalarray["materialcertificate"] = array_column($result2, 'materialCertificateID');
        return $finalarray;
    }

    function load_mfq_estimate_detail()
    {
        $estimateMasterID = $this->input->post('estimateMasterID');
        $this->db->select('itemSystemCode,itemDescription,IFNULL(UnitDes,"") as UnitDes,expectedQty,estimatedCost,(expectedQty*estimatedCost) as totalCost,estimateDetailID,est.companyLocalCurrencyDecimalPlaces,ciCode,est.margin,est.sellingPrice,est.estimateMasterID,est.mfqItemID,bomm.bomMasterID,est.discount,est.discountedPrice,estm.mfqCustomerAutoID,estm.description,srp_erp_mfq_itemmaster.itemType,CONCAT(itemDescription," (",itemSystemCode,")") as concatItemDescription');
        $this->db->from('srp_erp_mfq_estimateDetail est');
        $this->db->join('srp_erp_mfq_estimateMaster estm', 'estm.estimateMasterID = est.estimateMasterID', 'left');
        $this->db->join('srp_erp_mfq_itemmaster', 'est.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID', 'left');
        $this->db->join('srp_erp_unit_of_measure', 'unitID = defaultUnitOfMeasureID', 'left');
        $this->db->join('srp_erp_mfq_customerinquiry', 'est.ciMasterID = srp_erp_mfq_customerinquiry.ciMasterID', 'left');
        $this->db->join('(SELECT ((IFNULL(bmc.materialCharge,0) + IFNULL(lt.totalValue,0) + IFNULL(oh.totalValue,0))/bom.Qty) as cost,bom.mfqItemID,bom.bomMasterID FROM srp_erp_mfq_billofmaterial bom LEFT JOIN (SELECT SUM(materialCharge) as materialCharge,bomMasterID FROM srp_erp_mfq_bom_materialconsumption GROUP BY bomMasterID) bmc ON bmc.bomMasterID = bom.bomMasterID  LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_labourtask GROUP BY bomMasterID) lt ON lt.bomMasterID = bom.bomMasterID LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_overhead GROUP BY bomMasterID) oh ON oh.bomMasterID = bom.bomMasterID  GROUP BY mfqItemID) bomm', 'bomm.mfqItemID = est.mfqItemID', 'left');
        $this->db->where('est.estimateMasterID', $estimateMasterID);
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $result;
    }

    function fetch_customer_inquiry()
    {
        $this->db->where('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->where('approvedYN', 1);
        $data = $this->db->get('srp_erp_mfq_customerinquiry')->result_array();
        return $data;
    }

    function load_mfq_customerInquiryDetail()
    {
        $convertFormat = convert_date_format_sql();
        $ciMasterID = $this->input->post('ciMasterID');
        $this->db->select('srp_erp_mfq_customerinquirydetail.*,DATE_FORMAT(srp_erp_mfq_customerinquirydetail.expectedDeliveryDate,\'' . $convertFormat . '\') as expectedDeliveryDate,IFNULL(srp_erp_mfq_customerinquirydetail.itemDescription,CONCAT(srp_erp_mfq_itemmaster.itemDescription," (",itemSystemCode,")")) as itemDescription,itemSystemCode,IFNULL(UnitDes,"") as UnitDes,IFNULL(bomm.cost,0) as cost,bomm.bomMasterID,(IFNULL(expectedQty,0)-IFNULL(ed.pulledQty,0)) as balanceQty,IFNULL(srp_erp_mfq_customerinquirydetail.mfqItemID,"") as mfqItemID');
        $this->db->from('srp_erp_mfq_customerinquirydetail');
        $this->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_customerinquirydetail.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID', 'left');
        $this->db->join('srp_erp_unit_of_measure', 'unitID = defaultUnitOfMeasureID', 'left');
        $this->db->join('(SELECT ((IFNULL(bmc.materialCharge,0) + IFNULL(lt.totalValue,0) + IFNULL(oh.totalValue,0) + IFNULL(mac.totalValue,0))/bom.Qty) as cost,bom.mfqItemID,bom.bomMasterID FROM srp_erp_mfq_billofmaterial bom LEFT JOIN (SELECT SUM(materialCharge) as materialCharge,bomMasterID FROM srp_erp_mfq_bom_materialconsumption GROUP BY bomMasterID) bmc ON bmc.bomMasterID = bom.bomMasterID  LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_labourtask GROUP BY bomMasterID) lt ON lt.bomMasterID = bom.bomMasterID LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_overhead GROUP BY bomMasterID) oh ON oh.bomMasterID = bom.bomMasterID LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_machine GROUP BY bomMasterID) mac ON mac.bomMasterID = bom.bomMasterID  GROUP BY mfqItemID) bomm', 'bomm.mfqItemID = srp_erp_mfq_customerinquirydetail.mfqItemID', 'left');
        $this->db->join('(SELECT SUM(expectedQty) as pulledQty,ciDetailID FROM srp_erp_mfq_estimateDetail GROUP BY ciDetailID) ed', 'srp_erp_mfq_customerinquirydetail.ciDetailID = ed.ciDetailID', 'left');
        $this->db->where('ciMasterID', $ciMasterID);
        $result = $this->db->get()->result_array();
        return $result;
    }

    function save_EstimateDetail()
    {
        $result = $this->db->query("SELECT * FROM srp_erp_mfq_estimateDetail WHERE estimateMasterID=" . $this->input->post('estimateMasterID'))->row_array();
        $ciMasterID = array_unique($this->input->post('ciMasterID'));
        if (empty($result) || $result["ciMasterID"] == $ciMasterID[0]) {
            $result2 = $this->db->query("SELECT * FROM srp_erp_mfq_estimateDetail WHERE estimateMasterID=" . $this->input->post('estimateMasterID') . " AND mfqItemID IN (" . join(",", $this->input->post('mfqItemID')) . ")")->result_array();
            if (empty($result2)) { // check for item already exist
                $this->db->trans_start();
                $mfqCustomerAutoID = $this->input->post('mfqCustomerAutoID');
                $estimateMasterID = $this->input->post('estimateMasterID');
                $mfqItemID = $this->input->post('mfqItemID');
                $totEstimatedCost = 0;
                $totEstimatedCostFinal = 0;
                $manufacturingType = null;

                if (!empty($mfqItemID)) {
                    foreach ($mfqItemID as $key => $val) {
                        if (!empty($mfqItemID[$key])) {
                            $totEstimatedCost += $this->input->post('estimatedCost')[$key];
                            if(!$manufacturingType){
                                $result = $this->db->query("SELECT manufacturingType FROM srp_erp_mfq_customerinquiry WHERE ciMasterID=" . $this->input->post('ciMasterID')[$key])->row_array();
                                $manufacturingType = $result["manufacturingType"];
                            }

                            $this->db->set('estimateMasterID', $estimateMasterID);
                            $this->db->set('ciMasterID', $this->input->post('ciMasterID')[$key]);
                            $this->db->set('ciDetailID', $this->input->post('ciDetailID')[$key]);
                            $this->db->set('bomMasterID', $this->input->post('bomMasterID')[$key]);
                            $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                            $this->db->set('expectedQty', $this->input->post('expectedQty')[$key]);
                            $this->db->set('estimatedCost', $this->input->post('estimatedCost')[$key]);
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
                            $this->db->set('companyID', current_companyID());
                            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('createdUserID', current_userID());
                            $this->db->set('createdUserName', current_user());
                            $this->db->set('createdDateTime', current_date(true));
                            $totalCost = ($this->input->post('expectedQty')[$key] * $this->input->post('estimatedCost')[$key]);
                            $totEstimatedCostFinal += $totalCost;
                            $this->db->set('sellingPrice', $totalCost, false);
                            $this->db->set('discountedPrice', $totalCost, false);
                            $result = $this->db->insert('srp_erp_mfq_estimateDetail');
                        }
                    }

                    //Update Estimated Cost to estimatemaster table as totalCost
                    $this->db->select('SUM(estd.estimatedCost) as estimatedCost,SUM(discountedPrice) as discountedPrice,(((totMargin*SUM(discountedPrice)) / 100) + SUM(discountedPrice)) as totalSellingPrice,((((totMargin*SUM(discountedPrice)) / 100) + SUM(discountedPrice)) - ((totDiscount * (((totMargin*SUM(discountedPrice)) / 100) + SUM(discountedPrice))) / 100)) as totDiscountPrice');
                    $this->db->from('srp_erp_mfq_estimateDetail estd');
                    $this->db->join('srp_erp_mfq_estimateMaster est', 'est.estimateMasterID = estd.estimateMasterID', 'left');
                    $this->db->where('estd.estimateMasterID', $estimateMasterID);
                    $result = $this->db->get()->row_array();

                    $this->db->set('totalSellingPrice', $result["totalSellingPrice"]);
                    $this->db->set('totDiscountPrice', $result["totDiscountPrice"]);
                    $this->db->set('totalCost', $result["estimatedCost"]);
                    $this->db->set('manufacturingType', $manufacturingType);
                    $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
                    $result = $this->db->update('srp_erp_mfq_estimateMaster');

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return array('e', 'Estimate detail Failed ' . $this->db->_error_message());

                    } else {
                        $this->db->trans_commit();
                        return array('s', 'Estimate detail added Successfully.', $estimateMasterID);
                    }
                }
            } else {
                return array('w', 'Item already added to estimate');
            }
        } else {
            return array('w', 'Only one custmer inquiry item can be pulled to estimate');
        }
    }

    function confirm_Estimate()
    {
        $this->db->trans_start();
        $estimateMasterID = trim($this->input->post('estimateMasterID'));
        $this->db->select('*');
        $this->db->where('estimateMasterID', $estimateMasterID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_mfq_estimateMaster');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {
            $this->load->library('approvals');
            $this->db->select('*');
            $this->db->where('estimateMasterID', $estimateMasterID);
            $this->db->from('srp_erp_mfq_estimateMaster');
            $row = $this->db->get()->row_array();
            $approvals_status = $this->approvals->CreateApproval('EST', $row['estimateMasterID'],
                $row['estimateCode'], 'Estimate', 'srp_erp_mfq_estimateMaster', 'estimateMasterID', 0);
            if ($approvals_status == 1) {

            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Estimate Confirmed Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Estimate : Confirmed Successfully');
            }
        }
    }

    function save_estimate_detail_margin()
    {
        $this->db->trans_start();
        $this->db->set('margin', $this->input->post('margin'));
        $this->db->set('sellingPrice', $this->input->post('sellingPrice'));
        $this->db->set('discountedPrice', $this->input->post('discountedPrice'));
        $this->db->where('estimateDetailID', $this->input->post('estimateDetailID'));
        $result = $this->db->update('srp_erp_mfq_estimateDetail');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Margin updated Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Margin Updated Successfully.');
        }

    }

    function save_estimate_detail_discount()
    {
        $this->db->trans_start();
        $this->db->set('discount', $this->input->post('discount'));
        $this->db->set('discountedPrice', $this->input->post('discountedPrice'));
        $this->db->where('estimateDetailID', $this->input->post('estimateDetailID'));
        $result = $this->db->update('srp_erp_mfq_estimateDetail');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Discount updated Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Discount Updated Successfully.');
        }
    }

    function save_estimate_detail_margin_total()
    {
        $this->db->trans_start();
        $this->db->set('totMargin', $this->input->post('totalMargin'));
        $this->db->set('totalSellingPrice', $this->input->post('totalSellingPrice'));
        $this->db->set('totDiscountPrice', $this->input->post('totDiscountPrice'));
        $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
        $result = $this->db->update('srp_erp_mfq_estimateMaster');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Margin updated Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Margin Updated Successfully.');
        }

    }

    function save_estimate_detail_discount_total()
    {
        $this->db->trans_start();
        $this->db->set('totDiscount', $this->input->post('totDiscount'));
        $this->db->set('totDiscountPrice', $this->input->post('totDiscountPrice'));
        $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
        $result = $this->db->update('srp_erp_mfq_estimateMaster');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Discount updated Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Discount Updated Successfully.');
        }

    }

    function load_mfq_estimate_version($status = true)
    {
        $estimateMasterID = $this->input->post('estimateMasterID');
        $this->db->select('versionOrginID');
        $this->db->from('srp_erp_mfq_estimateMaster est');
        $this->db->where('estimateMasterID', $estimateMasterID);
        $result1 = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from('srp_erp_mfq_estimateMaster est');
        $this->db->where('est.versionOrginID', $result1["versionOrginID"]);
        $result = $this->db->get()->result_array();

        return $result;
    }

    function load_emails()
    {
        $estimateMasterID = $this->input->post('estimateMasterID');
        $this->db->select('customermail.*');
        $this->db->from('srp_erp_mfq_customeremail customermail');
        $this->db->join('srp_erp_mfq_estimatemaster estimatemaster', 'customermail.mfqCustomerAutoID = estimatemaster.mfqCustomerAutoID');
        $this->db->where('estimatemaster.estimateMasterID', $estimateMasterID);
        $result = $this->db->get()->result_array();

        return $result;

    }

    function send_emails()
    {
        $checkmailid = $this->input->post('checkmailid');
        $estimateMasterID = $this->input->post('estimateMasterID');
        $newkmail = $this->input->post('emailNW');
        if ($checkmailid || !empty(array_filter($newkmail))) {
            $this->db->select('estimatedetail.estimateMasterID,itemmaster.itemDescription,inq.description');
            $this->db->from('srp_erp_mfq_estimatedetail estimatedetail');
            $this->db->join('srp_erp_mfq_itemmaster itemmaster', 'estimatedetail.mfqItemID = itemmaster.mfqItemID');
            $this->db->join('srp_erp_mfq_customerinquiry inq', 'inq.ciMasterID = estimatedetail.ciMasterID');
            $this->db->where('estimatedetail.estimateMasterID', $estimateMasterID);
            $result1 = $this->db->get()->result_array();
            $des = array_column($result1, 'itemDescription');
            $datadis = join(",", $des);

            $data['header'] =  $this->load_mfq_estimate();
            $data['detail'] =  $this->load_mfq_estimate_detail();
            $data["mode"] = "pdf";
            $subject = array_unique(array_column($result1, 'description'))[0]." - Quotation No:". $data['header']["estimateCode"];
            $this->load->library('NumberToWords');
            $html = $this->load->view('system/mfq/ajax/quotation_print', $data, true);
            //$this->load->library('pdf');
            $path = UPLOAD_PATH_MFQ . $estimateMasterID . "-QUT-" . current_userID() . ".pdf";
            //$this->pdf->save_pdf($html, 'A4', 1, $path);

            $this->db->set('isMailSent', 1);
            $this->db->where('estimateMasterID', $estimateMasterID);
            $this->db->update('srp_erp_mfq_estimatemaster');

            if ($checkmailid) {
                $this->db->select('email');
                $this->db->from('srp_erp_mfq_customeremail');
                $this->db->where_in('customerEmailAutoID', $checkmailid);
                $result = $this->db->get()->result_array();
                foreach ($result as $val) {
                    $param["empName"] = '';
                    $param["body"] = 'Thank you for forwarding us your valued Inquiry. Based on information furnished, we are pleased to submit our quotation as follows. <br/>
                                          <table border="0px">
                                          </table>';
                    $mailData = [
                        'approvalEmpID' => '',
                        'documentCode' => '',
                        'toEmail' => $val["email"],
                        'subject' => $subject,
                        'param' => $param,
                        'from' => current_companyName()
                    ];
                    send_approvalEmail($mailData,1,$path);
                }
            }
            if ($newkmail) {
                foreach ($newkmail as $val) {
                    $param["empName"] = '';
                    $param["body"] = 'Thank you for forwarding us your valued Inquiry. Based on information furnished, we are pleased to submit our quotation as follows. <br/>
                                          <table border="0px">
                                          </table>';
                    $mailData = [
                        'approvalEmpID' => '',
                        'documentCode' => '',
                        'toEmail' => $val,
                        'subject' => $subject,
                        'param' => $param,
                        'from' => current_companyName()
                    ];
                    send_approvalEmail($mailData,1,$path);

                }
            }
            return array('s', 'Email Send Successfully.');

        } else {
            return array('e', 'Please Select an Email ID.');

        }
    }

    function save_estimate_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_id = trim($this->input->post('estimateMasterID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));
        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'EST');
        if ($approvals_status == 1) {
            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            $this->db->where('estimateMasterID', $system_id);
            $this->db->update('srp_erp_mfq_estimatemaster', $data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('s', 'Error occurred');
        } else {
            $this->db->trans_commit();
            return array('s', 'Estimate Approved Successfully');
        }
    }


    function save_additional_order_detail()
    {
        $this->db->trans_start();
        $last_id = "";
        $codes="";
        $this->db->set('exclusions', $this->input->post('exclusions'));
        $this->db->set('designCode', $this->input->post('designCode'));
        $this->db->set('designEditor', $this->input->post('designEditor'));
        $this->db->set('engineeringDrawings', $this->input->post('engineeringDrawings'));
        $this->db->set('engineeringDrawingsComment', $this->input->post('engineeringDrawingsComment'));
        $this->db->set('submissionOfITP', $this->input->post('submissionOfITP'));
        $this->db->set('itpComment', $this->input->post('itpComment'));
        $this->db->set('qcqtDocumentation', $this->input->post('qcqtDocumentation'));
        $this->db->set('scopeOfWork', $this->input->post('scopeOfWork'));
        $this->db->set('mfqSegmentID', $this->input->post('mfqSegmentID'));
        $this->db->set('mfqWarehouseAutoID', $this->input->post('mfqWarehouseAutoID'));
        $this->db->set('orderStatus', $this->input->post('orderStatus'));
        $this->db->set('poNumber', $this->input->post('poNumber'));
        $this->db->set('materialCertificationComment', $this->input->post('materialCertificationComment'));
        $this->db->where('estimateMasterID', $this->input->post('estimateMasterID'));
        $result = $this->db->update('srp_erp_mfq_estimatemaster');

        if ($result) {
            if ($this->input->post('materialCertificateID')) {
                $data = [];
                $this->db->delete('srp_erp_mfq_materialcertificate', array('estimateMasterID' => $this->input->post('estimateMasterID')));
                foreach ($this->input->post('materialCertificateID') as $key => $val) {
                    $data[$key]['materialCertificateID'] = $val;
                    $data[$key]['estimateMasterID'] = $this->input->post('estimateMasterID');
                    $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];
                }
                $this->db->insert_batch('srp_erp_mfq_materialcertificate', $data);
            }
           $master =  $this->load_mfq_estimate();
            $this->db->select('segmentCode');
            $this->db->from("srp_erp_mfq_segment");
            $this->db->where("companyID", current_companyID());
            $this->db->where("mfqSegmentID", $this->input->post('mfqSegmentID'));
            $segmentCode = $this->db->get()->row('segmentCode');

            $date_format_policy = date_format_policy();
            $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_job', 'workProcessID', 'companyID');
            $codes = $this->sequence->mfq_sequence_generator('JOB', $serialInfo['serialNo'],$segmentCode);
            $this->db->set('description', $master["description"]);
            $this->db->set('serialNo', $serialInfo['serialNo']);
            $this->db->set('documentCode', $codes);
            $this->db->set('documentDate', date('Y-m-d'));
            $this->db->set('startDate', date('Y-m-d'));
            $this->db->set('endDate', date('Y-m-d'));
            $this->db->set('manufacturingType', $master["manufacturingType"]);
            /*$this->db->set('mfqItemID', $this->input->post('mfqItemID'));
            $this->db->set('estimateDetailID', $this->input->post('estimateDetailID'));
            $this->db->set('bomMasterID', $this->input->post('bomMasterID'));
            $this->db->set('qty', $this->input->post('expectedQty'));*/
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('mfqSegmentID', $this->input->post('mfqSegmentID'));
            $this->db->set('type', 2);
            $this->db->set('mfqWarehouseAutoID', $this->input->post('mfqWarehouseAutoID'));
            $this->db->set('estimateMasterID', $this->input->post('estimateMasterID'));
            $this->db->set('documentID', 'JOB');
            $this->db->set('levelNo', 1);

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
            $this->db->set('isSaved', 0);
            $this->db->set('isFromEstimate', 1);

            $this->db->set('companyID', current_companyID());
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));

            $result = $this->db->insert('srp_erp_mfq_job');
            $last_id = $this->db->insert_id();

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Additional Order detail failed.' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Job No: '.$codes, $this->input->post('estimateMasterID'), $last_id);
        }

    }

    function fetch_job_order_save()
    {
        $estimateMasterID = trim($this->input->post('estimateMasterID'));
        $workProcessID = trim($this->input->post('workProcessID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('DATE_FORMAT(est.createdDateTime,\'' . $convertFormat . '\') as createdDateTime, cust.CustomerName as CustomerName,est.estimateMasterID,est.estimateCode,est.scopeOfWork,est.createdUserName,est.createdUserID,designationMaster.DesDescription,est.exclusions,est.approvedbyEmpName,DATE_FORMAT(est.approvedDate,\'' . $convertFormat . '\') as approvedDate,est.description as jobTitle,est.poNumber,est.designCode,est.designEditor,est.engineeringDrawings,est.submissionOfITP,est.qcqtDocumentation,est.versionLevel,est.deliveryTerms');
        $this->db->from('srp_erp_mfq_estimateMaster est');
        $this->db->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = est.mfqCustomerAutoID', 'left');
        $this->db->join('srp_employeedesignation designationPD', 'designationPD.EmpDesignationID = est.createdUserID AND designationPD.isActive = 1', 'left');
        $this->db->join('srp_designation designationMaster', 'designationMaster.DesignationID = designationPD.DesignationID', 'left');
        $this->db->where('est.estimateMasterID', $estimateMasterID);
        $data["header"] = $this->db->get()->row_array();

        $data["jobMaster"] = $this->MFQ_Job_model->load_job_header();
        $data["certifications"] = $this->load_mfq_estimate_certifications();
        $data["estimateDetail"] = $this->load_mfq_estimate_detail();

        $userInput = $this->input->post();
        $this->db->set('companyID', current_companyID());
        $this->db->set('estimateMasterID', $estimateMasterID);
        $this->db->set('designCode', $userInput["designCode"]);
        $this->db->set('designEditor', $userInput["designEditor"]);
        $this->db->set('addenta', $userInput["addenta"]);
        $this->db->set('paintingSpecifications', $userInput["paintingSpecifications"]);
        $this->db->set('submisionDRG', $userInput["submisionDRG"]);
        $this->db->set('submisionITP', $userInput["submisionITP"]);
        $this->db->set('activity', $userInput["activity"]);
        $this->db->set('heatTreatment', $userInput["heatTreatment"]);
        $this->db->set('pressureTestingPneumatic', $userInput["pressureTestingPneumatic"]);
        $this->db->set('pressureTestingHydro', $userInput["pressureTestingHydro"]);
        $this->db->set('pressureTestingComment', $userInput["pressureTestingComment"]);
        $this->db->set('NDT1Comment', $userInput["NDT1Comment"]);
        $this->db->set('RT', $userInput["RT"]);
        $this->db->set('UT', $userInput["UT"]);
        $this->db->set('RTUTComment', $userInput["RTUTComment"]);
        $this->db->set('NDT2Comment', $userInput["NDT2Comment"]);
        $this->db->set('MPT', $userInput["MPT"]);
        $this->db->set('LPT', $userInput["LPT"]);
        $this->db->set('MPTLPTComment', $userInput["MPTLPTComment"]);
        $this->db->set('inspectionDocumentation', $userInput["inspectionDocumentation"]);
        $this->db->set('remarks', $userInput["remarks"]);
        $this->db->set('deliverycomments', $userInput["deliverycomments"]);
        $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
        $this->db->set('createdUserID', current_userID());
        $this->db->set('createdUserName', current_user());
        $this->db->set('createdDateTime', current_date(true));
        $result = $this->db->insert('srp_erp_mfq_jobordercomments');
        $last_id = $this->db->insert_id();

        if($userInput["materialCertificateID"]){
            foreach ($userInput["materialCertificateID"] as $key => $val){
                $this->db->set('companyID', current_companyID());
                $this->db->set('estimateMasterID', $estimateMasterID);
                $this->db->set('comment', $userInput["materialCertificationComment"][$key]);
                $this->db->set('materialCertificateID', $val);
                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserName', current_user());
                $this->db->set('createdDateTime', current_date(true));
                $result = $this->db->insert('srp_erp_mfq_jobordermccomment');
            }
        }

        $this->db->select('*');
        $this->db->from('srp_erp_mfq_jobordercomments');
        $this->db->where('commentID',$last_id);
        $data['userInput'] = $this->db->get()->row_array();

        $data["type"] = 'pdf';

        $html = $this->load->view('system/mfq/ajax/estimate_job_order_print_preview', $data, true);
        $this->load->library('pdf');
        $path = UPLOAD_PATH_MFQ . $estimateMasterID . "-" . time() . ".pdf";
        $footer = 'Doc No:HEMT-QM-RC-008 Rev.7';
        $this->pdf->save_pdf($html, 'A4', 1, $path,$footer);

        $this->db->select('ed.Ename2,ugd.empID,ed.EEmail');
        $this->db->from('srp_erp_mfq_usergroupdetails ugd');
        $this->db->join('srp_employeesdetails ed', 'ugd.empID = ed.EIdNo');
        $this->db->where_in('ugd.userGroupID', $this->input->post("usergroup"));
        $this->db->where('ugd.companyID', current_companyID());
        $operationEmployees = $this->db->get()->result_array();

        if (!empty($operationEmployees)) {
            foreach ($operationEmployees as $row) {
                $body = "Job Card " . $data["jobMaster"]['documentCode'] . " has been created. Please refer to the attached PDF.<br><br><strong>Client : </strong>" . $data["header"]['CustomerName'] . "<br><strong>Scope : </strong>" . $data["header"]['scopeOfWork'] . "<br><br>Best Regards<br>Quotation Team";

                $param["empName"] = $row['Ename2'];
                $param["body"] = $body;
                $mailData = [
                    'approvalEmpID' => "-",
                    'documentCode' => "-",
                    'toEmail' => $row['EEmail'],
                    'subject' => $data["jobMaster"]['documentCode'] . " - " . $data["header"]['CustomerName'],
                    'param' => $param
                ];
                send_approvalEmail($mailData, 1, $path);
            }
            return array('s', 'Successfully email sent');
        }else{
            return array('e', 'No emails found');
        }
    }

    function load_mfq_estimate_certifications()
    {
        $estimateMasterID = $this->input->post('estimateMasterID');
        $this->db->select('mcm.Description,mcm.materialCertificateID');
        $this->db->from('srp_erp_mfq_materialcertificate mcd');
        $this->db->join('srp_erp_mfq_materialcertificatemaster mcm', 'mcm.materialCertificateID = mcd.materialCertificateID');
        $this->db->where('mcd.estimateMasterID', $estimateMasterID);
        $result = $this->db->get()->result_array();

        return $result;
    }

    function save_estimate_detail_selling_price(){
        $this->db->trans_start();
        $this->db->set('margin', $this->input->post('margin'));
        $this->db->set('sellingPrice', $this->input->post('sellingPrice'));
        $this->db->where('estimateDetailID', $this->input->post('estimateDetailID'));
        $result = $this->db->update('srp_erp_mfq_estimateDetail');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Selling Price Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Selling Price Successfully.');
        }
    }

    function load_mfq_estimate_job_order(){
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_jobordercomments');
        $this->db->where('estimateMasterID',$this->input->post('estimateMasterID'));
        $data = $this->db->get()->row_array();
        return $data;
    }

    function load_mfq_estimate_job_order_mc_comment(){
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_jobordermccomment');
        $this->db->join('srp_erp_mfq_materialcertificatemaster mcm', 'mcm.materialCertificateID = srp_erp_mfq_jobordermccomment.materialCertificateID');
        $this->db->where('estimateMasterID',$this->input->post('estimateMasterID'));
        $data = $this->db->get()->result_array();
        return $data;
    }
}
