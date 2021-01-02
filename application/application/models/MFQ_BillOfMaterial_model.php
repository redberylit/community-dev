<?php

class MFQ_BillOfMaterial_model extends ERP_Model
{
    function fetch_related_uom_id()
    {
        $this->db->select('defaultUnitOfMeasureID,defaultUnitOfMeasure,itemDescription,companyLocalWacAmount,companyLocalCurrencyID');
        $this->db->from('srp_erp_mfq_itemmaster');
        $this->db->where('itemAutoID', $this->input->post('itemAutoID'));
        return $this->db->get()->row_array();

        /* $this->db->select('srp_erp_unit_of_measure.UnitID,UnitShortCode,UnitDes,conversion');
         $this->db->from('srp_erp_unitsconversion');
         $this->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_unitsconversion.subUnitID');
         $this->db->where('masterUnitID',$this->input->post('masterUnitID'));
         $this->db->where('srp_erp_unitsconversion.companyID',$this->common_data['company_data']['company_id']);
         return $this->db->get()->result_array();*/
    }

    function load_unitprice_exchangerate()
    {
        $localwacAmount = trim($this->input->post('LocalWacAmount'));
        $localCurrency = trim($this->input->post('companyLocalCurrencyID'));
        $transactionCurrency = trim($this->input->post('transactionCurrency'));
        $conversion = currency_conversionID($localCurrency, $transactionCurrency);
        $unitprice = round(($localwacAmount / $conversion['conversion']));
        return array('status' => true, 'amount' => $unitprice);
    }

    function insert_BoM()
    {
        $post = $this->input->post();
        unset($post['bomMasterID']);
        $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_billofmaterial', 'bomMasterID', 'companyID');
        //print_r($serialInfo);
        $codes = $this->sequence->sequence_generator('BOM', $serialInfo['serialNo'] + 1);
        //var_dump($codes);

        //exit;
        $datetime = format_date_mysql_datetime();
        $post['documentDate'] = format_date_mysql_datetime($post['documentDate']);
        $post['serialNo'] = $serialInfo['serialNo'];
        $post['documentCode'] = $codes;
        $post['companyID'] = current_companyID();
        $post['createdPCID'] = current_pc();
        $post['createdUserID'] = current_userID();
        $post['createdDateTime'] = $datetime;
        $post['createdUserName'] = current_user();
        $post['timestamp'] = $datetime;


        $result = $this->db->insert('srp_erp_mfq_billofmaterial', $post);
        $masterID = $this->db->insert_id();
        if ($result) {
            return array('error' => 0, 'message' => 'Record successfully Added', 'code' => 1, 'masterID' => $masterID);
        } else {
            return array('error' => 1, 'message' => 'Code: ' . $this->db->_error_number() . ' <br/>Message: ' . $this->db->_error_message());
        }

    }


    function update_BoM()
    {
        $post = $this->input->post();
        $masterID = $this->input->post('bomMasterID');
        unset($post['bomMasterID']);

        $datetime = format_date_mysql_datetime();
        $post['description'] = $this->input->post('description');
        $post['industryTypeID'] = $this->input->post('industryTypeID');
        $post['documentDate'] = $this->input->post('documentDate');

        $post['modifiedUserID'] = current_userID();
        $post['modifiedUserName'] = current_user();
        $post['modifiedDateTime'] = $datetime;
        $post['modifiedPCID'] = current_pc();


        $this->db->where('bomMasterID', $this->input->post('bomMasterID'));
        $result = $this->db->update('srp_erp_mfq_billofmaterial', $post);
        if ($result) {
            return array('error' => 0, 'message' => 'document successfully updated', 'code' => 2, 'masterID' => $masterID);
        } else {
            return array('error' => 1, 'message' => 'Code: ' . $this->db->_error_number() . ' <br/>Message: ' . $this->db->_error_message());
        }
    }

    function get_srp_erp_mfq_billofmaterial($bomMasterID)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('srp_erp_mfq_billofmaterial.*, DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,UnitDes');
        $this->db->from('srp_erp_mfq_billofmaterial');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_mfq_billofmaterial.uomID', 'inner');
        $this->db->where('bomMasterID', $bomMasterID);
        $result = $this->db->get()->row_array();
        /*if (!$result) {
            $result['documentDate'] = date('d-m-Y',strtotime($result['documentDate']));
        }*/

        return $result;
    }

    function load_mfq_billOfMaterial_detail($bomMasterID)
    {
        $result=array();
        $result["material"] = $this->load_bom_material_consumption($bomMasterID);
        $result["labour"] = $this->fetch_bom_labour_task();
        $result["overhead"] = $this->fetch_bom_overhead_cost();
        $result["machine"] = $this->fetch_bom_machine_cost();
        return $result;
    }


    function add_edit_BillOfMaterial()
    {
        try {
            $bomMasterID = $this->input->post('bomMasterID');
            $masterID = "";
            if (!$bomMasterID) {
                /** Insert */
                $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_billofmaterial', 'bomMasterID', 'companyID');
                $codes = $this->sequence->sequence_generator('BOM', $serialInfo['serialNo']);
                $datetime = format_date_mysql_datetime();

                $data['mfqItemID'] = $this->input->post('product');
                $data['industryTypeID'] = $this->input->post('industryTypeID');
                $data['uomID'] = $this->input->post('uomID');
                $data['Qty'] = $this->input->post('Qty');
                $data['documentDate'] = format_date_mysql_datetime($this->input->post('documentDate'));
                $data['serialNo'] = $serialInfo['serialNo'];
                $data['documentCode'] = $codes;
                $data['productImage'] = $this->input->post('productImage');
                $data['companyID'] = current_companyID();
                $data['createdPCID'] = current_pc();
                $data['createdUserID'] = current_userID();
                $data['createdDateTime'] = $datetime;
                $data['createdUserName'] = current_user();
                $data['timestamp'] = $datetime;

                if ($this->input->post('status') == 2) {
                    $data['confirmedYN'] = 1;
                    $data['confirmedUserID'] = current_userID();
                }
                $result = $this->db->insert('srp_erp_mfq_billofmaterial', $data);
                $masterID = $this->db->insert_id();

            } else {
                /** Update */
                $masterID = $this->input->post('bomMasterID');
                $datetime = format_date_mysql_datetime();
                $data['mfqItemID'] = trim($this->input->post('product'));
                $data['Qty'] = trim($this->input->post('Qty'));
                $data['uomID'] = $this->input->post('uomID');
                $data['industryTypeID'] = $this->input->post('industryTypeID');
                $data['documentDate'] = format_date_mysql_datetime($this->input->post('documentDate'));
                $data['productImage'] = $this->input->post('productImage');
                $data['modifiedUserID'] = current_userID();
                $data['modifiedUserName'] = current_user();
                $data['modifiedDateTime'] = $datetime;
                $data['modifiedPCID'] = current_pc();

                if ($this->input->post('status') == 2) {
                    $data['confirmedYN'] = 1;
                    $data['confirmedUserID'] = current_userID();
                }

                $this->db->where('bomMasterID', $this->input->post('bomMasterID'));
                $result = $this->db->update('srp_erp_mfq_billofmaterial', $data);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('error' => 1, 'message' => 'Error while updating');

            } else {

                $grandTotal = 0;
                /** Material Consumption */
                $bomMaterialConsumptionID = $this->input->post('bomMaterialConsumptionID');
                $mfqItemID = $this->input->post('mfqItemID');

                if (!empty($mfqItemID)) {
                    foreach ($mfqItemID as $key => $val) {
                        if (!empty($bomMaterialConsumptionID[$key])) {
                            $materialCost = ($this->input->post('qtyUsed')[$key]) * ($this->input->post('unitCost')[$key]);
                            $materialCharge = $materialCost + (($this->input->post('markUp')[$key] * $materialCost) / 100);
                            $grandTotal += $materialCharge;
                            $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                            $this->db->set('qtyUsed', $this->input->post('qtyUsed')[$key]);
                            $this->db->set('unitCost', $this->input->post('unitCost')[$key]);
                            $this->db->set('costingType', $this->input->post('costingType')[$key]);
                            $this->db->set('materialCost', $materialCost);
                            $this->db->set('markUp', $this->input->post('markUp')[$key]);
                            $this->db->set('materialCharge', $materialCharge);

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
                            $this->db->where('bomMaterialConsumptionID', $bomMaterialConsumptionID[$key]);
                            $this->db->update('srp_erp_mfq_bom_materialconsumption');

                        } else {
                            if (!empty($mfqItemID[$key])) {
                                $materialCost = ($this->input->post('qtyUsed')[$key]) * ($this->input->post('unitCost')[$key]);
                                $materialCharge = $materialCost + (($this->input->post('markUp')[$key] * $materialCost) / 100);
                                $grandTotal += $materialCharge;
                                $this->db->set('bomMasterID', $masterID);
                                $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                                $this->db->set('qtyUsed', $this->input->post('qtyUsed')[$key]);
                                $this->db->set('unitCost', $this->input->post('unitCost')[$key]);
                                $this->db->set('costingType', $this->input->post('costingType')[$key]);
                                $this->db->set('materialCost', $materialCost);
                                $this->db->set('markUp', $this->input->post('markUp')[$key]);
                                $this->db->set('materialCharge', $materialCharge);
                                $this->db->set('modifiedPCID', current_pc());
                                $this->db->set('modifiedUserID', current_userID());
                                $this->db->set('modifiedDateTime', format_date_mysql_datetime());

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

                                $this->db->insert('srp_erp_mfq_bom_materialconsumption');
                            }
                        }
                    }
                }


                /** Labour Task */
                $bomLabourTaskID = $this->input->post('bomLabourTaskID');
                $labourTask = $this->input->post('labourTask');
                if (!empty($labourTask)) {
                    foreach ($labourTask as $key => $val) {

                        if (!empty($bomLabourTaskID[$key])) {

                            $grandTotal += $this->input->post('la_totalValue')[$key];
                            $this->db->set('bomMasterID', $masterID);

                            $this->db->set('labourTask', $this->input->post('labourTask')[$key]);
                            $this->db->set('activityCode', $this->input->post('la_activityCode')[$key]);
                            $this->db->set('uomID', $this->input->post('la_uomID')[$key]);
                            $this->db->set('segmentID', $this->input->post('la_segmentID')[$key]);
                            $this->db->set('hourlyRate', $this->input->post('la_hourlyRate')[$key]);
                            $this->db->set('totalHours', $this->input->post('la_totalHours')[$key]);
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
                            $this->db->where('bomLabourTaskID', $bomLabourTaskID[$key]);
                            $result = $this->db->update('srp_erp_mfq_bom_labourtask');

                        } else {
                            if (!empty($labourTask[$key])) {
                                $grandTotal += $this->input->post('la_totalValue')[$key];
                                $this->db->set('labourTask', $this->input->post('labourTask')[$key]);
                                $this->db->set('activityCode', $this->input->post('la_activityCode')[$key]);
                                $this->db->set('uomID', $this->input->post('la_uomID')[$key]);
                                $this->db->set('segmentID', $this->input->post('la_segmentID')[$key]);
                                $this->db->set('hourlyRate', $this->input->post('la_hourlyRate')[$key]);
                                $this->db->set('totalHours', $this->input->post('la_totalHours')[$key]);
                                $this->db->set('totalValue', $this->input->post('la_totalValue')[$key]);
                                $this->db->set('bomMasterID', $masterID);
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
                                $this->db->insert('srp_erp_mfq_bom_labourtask');
                            }
                        }
                    }
                }

                /** Overhead Cost */
                $bomOverheadID = $this->input->post('bomOverheadID');
                $overheadID = $this->input->post('overheadID');

                /* print_r($bomOverheadID);
                 print_r($overheadID);
                 exit;*/

                if (!empty($overheadID)) {
                    foreach ($overheadID as $key => $val) {
                        if (!empty($bomOverheadID[$key])) {
                            $grandTotal += $this->input->post('oh_totalValue')[$key];
                            $this->db->set('bomMasterID', $masterID);

                            $this->db->set('overheadID', $this->input->post('overheadID')[$key]);
                            $this->db->set('activityCode', $this->input->post('oh_activityCode')[$key]);
                            $this->db->set('uomID', $this->input->post('oh_uomID')[$key]);
                            $this->db->set('segmentID', $this->input->post('oh_segmentID')[$key]);
                            $this->db->set('hourlyRate', $this->input->post('oh_hourlyRate')[$key]);
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
                            $this->db->where('bomOverheadID', $bomOverheadID[$key]);
                            $result = $this->db->update('srp_erp_mfq_bom_overhead');

                        } else {
                            if (!empty($overheadID[$key])) {
                                $grandTotal += $this->input->post('oh_totalValue')[$key];
                                $this->db->set('overheadID', $this->input->post('overheadID')[$key]);
                                $this->db->set('activityCode', $this->input->post('oh_activityCode')[$key]);
                                $this->db->set('uomID', $this->input->post('oh_uomID')[$key]);
                                $this->db->set('segmentID', $this->input->post('oh_segmentID')[$key]);
                                $this->db->set('hourlyRate', $this->input->post('oh_hourlyRate')[$key]);
                                $this->db->set('totalHours', $this->input->post('oh_totalHours')[$key]);
                                $this->db->set('totalValue', $this->input->post('oh_totalValue')[$key]);
                                $this->db->set('bomMasterID', $masterID);
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
                                $this->db->insert('srp_erp_mfq_bom_overhead');
                            }
                        }
                    }
                }


                /** Machine Cost */
                $bomMachineID = $this->input->post('bomMachineID');
                $mfq_faID = $this->input->post('mfq_faID');

                if (!empty($mfq_faID)) {
                    foreach ($mfq_faID as $key => $val) {
                        if (!empty($bomMachineID[$key])) {
                            $grandTotal += $this->input->post('mc_totalValue')[$key];
                            $this->db->set('bomMasterID', $masterID);

                            $this->db->set('mfq_faID', $this->input->post('mfq_faID')[$key]);
                            $this->db->set('activityCode', $this->input->post('mc_activityCode')[$key]);
                            $this->db->set('uomID', $this->input->post('mc_uomID')[$key]);
                            $this->db->set('segmentID', $this->input->post('mc_segmentID')[$key]);
                            $this->db->set('hourlyRate', $this->input->post('mc_hourlyRate')[$key]);
                            $this->db->set('totalHours', $this->input->post('mc_totalHours')[$key]);
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
                            $this->db->where('bomMachineID', $bomMachineID[$key]);
                            $result = $this->db->update('srp_erp_mfq_bom_machine');

                        } else {
                            if (!empty($mfq_faID[$key])) {
                                $grandTotal += $this->input->post('mc_totalValue')[$key];
                                $this->db->set('mfq_faID', $this->input->post('mfq_faID')[$key]);
                                $this->db->set('activityCode', $this->input->post('mc_activityCode')[$key]);
                                $this->db->set('uomID', $this->input->post('mc_uomID')[$key]);
                                $this->db->set('segmentID', $this->input->post('mc_segmentID')[$key]);
                                $this->db->set('hourlyRate', $this->input->post('mc_hourlyRate')[$key]);
                                $this->db->set('totalHours', $this->input->post('mc_totalHours')[$key]);
                                $this->db->set('totalValue', $this->input->post('mc_totalValue')[$key]);
                                $this->db->set('bomMasterID', $masterID);
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
                                $this->db->insert('srp_erp_mfq_bom_machine');
                            }
                        }
                    }
                }

                $estimateMasterID = "";
                if (isset($_POST["estimateDetailID"])) {
                    $this->db->select('*');
                    $this->db->from('srp_erp_mfq_estimatedetail');
                    $this->db->join('srp_erp_mfq_estimatemaster','srp_erp_mfq_estimatedetail.estimateMasterID = srp_erp_mfq_estimatemaster.estimateMasterID','inner');
                    $this->db->where('estimateDetailID', $this->input->post('estimateDetailID'));
                    $result = $this->db->get()->row_array();

                    $estimateMasterID = $result["estimateMasterID"];

                    $dataEst['estimatedCost'] = $grandTotal;
                    $dataEst['sellingPrice'] = ((($grandTotal * $result["expectedQty"]) * $result["margin"])/100) + ($grandTotal * $result["expectedQty"]);
                    $dataEst['discountedPrice'] = ($dataEst['sellingPrice'] - (($dataEst['sellingPrice'] * $result["margin"])/100));
                    $this->db->where('estimateDetailID', $this->input->post('estimateDetailID'));
                    $resultUpdate = $this->db->update('srp_erp_mfq_estimatedetail', $dataEst);

                    $this->db->select('SUM(sellingPrice) as sellingPrice,SUM(estimatedCost) as estimatedCost');
                    $this->db->from('srp_erp_mfq_estimatedetail');
                    $this->db->where('estimateMasterID', $estimateMasterID);
                    $sellingPrice = $this->db->get()->row_array();

                    $dataM['totalSellingPrice'] = (($sellingPrice["sellingPrice"] * $result["totMargin"])/100) + $sellingPrice["sellingPrice"];
                    $dataM['totDiscountPrice'] = $dataM['totalSellingPrice'] - (($dataM['totalSellingPrice']  * $result["totDiscount"])/100);
                    $dataM['totalCost'] = $sellingPrice["estimatedCost"];
                    $this->db->where('estimateMasterID', $result["estimateMasterID"]);
                    $result = $this->db->update('srp_erp_mfq_estimatemaster', $dataM);
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('error' => 1, 'message' => 'Error while updating');

                } else {
                    $this->db->trans_commit();
                    if (isset($_POST["estimateDetailID"])) {
                        return array('error' => 0, 'message' => 'Document successfully updated', 'masterID' => $masterID,'estimateMasterID' => $estimateMasterID);
                    }else{
                        return array('error' => 0, 'message' => 'Document successfully updated', 'masterID' => $masterID);
                    }

                }
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('error' => 1, 'message' => 'Error while updating');
        }
    }

    function load_bom_material_consumption($bomMasterID)
    {
        $this->db->select('mc.bomMaterialConsumptionID, mc.bomMasterID, mc.mfqItemID,  mc.qtyUsed, mc.unitCost, mc.materialCost, mc.markUp, mc.materialCharge, CONCAT(CASE itemmaster.itemType WHEN 1 THEN "RM" WHEN 2 THEN "FG" WHEN 3 THEN "SF"
END," - ",itemmaster.itemDescription," (",itemSystemCode,")") as itemName,itemmaster.defaultUnitOfMeasure, itemmaster.partNo,mc.costingType');
        $this->db->from('srp_erp_mfq_bom_materialconsumption mc');
        $this->db->join('srp_erp_mfq_itemmaster itemmaster', 'itemmaster.mfqItemID = mc.mfqItemID', 'INNER');
        $this->db->where('bomMasterID', $bomMasterID);
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $result;
    }

    function delete_materialConsumption($id)
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('bomMaterialConsumptionID');
        $this->db->from('srp_erp_mfq_bom_materialconsumption');
        $this->db->where('bomMasterID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_bom_materialconsumption', array('bomMaterialConsumptionID' => $id), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    /** Job Card */
    function fetch_labourTask()
    {
        $dataArr = array();
        $dataArr2 = array();
        $search_string = "%" . $_GET['query'] . "%";
        $data = $this->db->query('SELECT srp_erp_mfq_overhead.*,CONCAT(IFNULL(description,""), " (" ,IFNULL(overHeadCode,""),")") AS "Match",srp_erp_mfq_segmenthours.hours FROM srp_erp_mfq_overhead LEFT JOIN srp_erp_mfq_segmenthours ON srp_erp_mfq_overhead.mfqSegmentID = srp_erp_mfq_segmenthours.mfqSegmentID  WHERE overHeadCategoryID = 2 AND (overHeadCode LIKE "' . $search_string . '" OR description LIKE "' . $search_string . '")')->result_array();
        //echo $this->db->last_query();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['overHeadCode'], 'overHeadID' => $val['overHeadID'], 'description' => $val['description'],'segment' => $val['mfqSegmentID'],'rate' => $val['rate'],'hours' => $val['hours'],'uom' => $val['unitOfMeasureID']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_overhead()
    {
        $dataArr = array();
        $dataArr2 = array();
        $search_string = "%" . $_GET['query'] . "%";
        $data = $this->db->query('SELECT srp_erp_mfq_overhead.*,CONCAT(IFNULL(description,""), " (" ,IFNULL(overHeadCode,""),")") AS "Match",srp_erp_mfq_segmenthours.hours FROM srp_erp_mfq_overhead LEFT JOIN srp_erp_mfq_segmenthours ON srp_erp_mfq_overhead.mfqSegmentID = srp_erp_mfq_segmenthours.mfqSegmentID WHERE overHeadCategoryID = 1 AND  (overHeadCode LIKE "' . $search_string . '" OR description LIKE "' . $search_string . '")')->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['overHeadCode'], 'overHeadID' => $val['overHeadID'], 'description' => $val['description'],'segment' => $val['mfqSegmentID'],'rate' => $val['rate'],'hours' => $val['hours'],'uom' => $val['unitOfMeasureID']);
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
        $data = $this->db->query('SELECT srp_erp_mfq_fa_asset_master.*,CONCAT(IFNULL(assetDescription,""), " (" ,IFNULL(faCode,""),")") AS "Match",srp_erp_mfq_segmenthours.hours,mfqSeg.mfqSegmentID FROM srp_erp_mfq_fa_asset_master LEFT JOIN srp_erp_mfq_category c1 ON mfq_faCatID = c1.itemCategoryID LEFT JOIN srp_erp_mfq_category c2 ON mfq_faSubCatID = c2.itemCategoryID LEFT JOIN srp_erp_mfq_category c3 ON mfq_faSubSubCatID = c3.itemCategoryID LEFT JOIN (SELECT segmentID,mfqSegmentID FROM srp_erp_mfq_segment WHERE companyID = '.current_companyID().') mfqSeg ON mfqSeg.segmentID = srp_erp_mfq_fa_asset_master.segmentID LEFT JOIN srp_erp_mfq_segmenthours ON mfqSeg.mfqSegmentID = srp_erp_mfq_segmenthours.mfqSegmentID WHERE (faCode LIKE "' . $search_string . '" OR assetDescription LIKE "' . $search_string . '")')->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['faCode'], 'mfq_faID' => $val['mfq_faID'], 'description' => $val['assetDescription'],'segment' => $val['mfqSegmentID'],'rate' => $val['unitRate'],'hours' => $val['hours'],'uom' => $val['unitOfmeasureID']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function fetch_bom_labour_task()
    {
        $bomMasterID = trim($this->input->post('bomMasterID'));
        $sql = "SELECT * FROM srp_erp_mfq_bom_labourtask LEFT JOIN srp_erp_mfq_overhead ON srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_bom_labourtask.labourTask WHERE  bomMasterID = $bomMasterID";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function delete_labour_task()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('bomLabourTaskID');
        $this->db->from('srp_erp_mfq_bom_labourtask');
        $this->db->where('bomMasterID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_bom_labourtask', array('bomLabourTaskID' => $this->input->post('bomLabourTaskID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function fetch_bom_overhead_cost()
    {
        $bomMasterID = trim($this->input->post('bomMasterID'));
        $data = $this->db->query("SELECT * FROM srp_erp_mfq_bom_overhead LEFT JOIN srp_erp_mfq_overhead ON srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_bom_overhead.overHeadID WHERE bomMasterID = $bomMasterID ")->result_array();
        return $data;
    }

    function fetch_bom_machine_cost()
    {
        $bomMasterID = trim($this->input->post('bomMasterID'));
        $data = $this->db->query("SELECT *,srp_erp_mfq_bom_machine.segmentID as segment FROM srp_erp_mfq_bom_machine LEFT JOIN srp_erp_mfq_fa_asset_master ON srp_erp_mfq_fa_asset_master.mfq_faID = srp_erp_mfq_bom_machine.mfq_faID WHERE bomMasterID = $bomMasterID ")->result_array();
        return $data;
    }

    function delete_overhead_cost()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_bom_overhead');
        $this->db->where('bomMasterID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;
        //echo $this->db->last_query();

        $result = $this->db->delete('srp_erp_mfq_bom_overhead', array('bomOverheadID' => $this->input->post('bomOverheadID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function delete_machine_cost()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_bom_machine');
        $this->db->where('bomMasterID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;
        //echo $this->db->last_query();

        $result = $this->db->delete('srp_erp_mfq_bom_machine', array('bomMachineID' => $this->input->post('bomMachineID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function deleteBOM()
    {
        $masterID = $this->input->post('bomMasterID');
        $this->db->trans_start();
        $result = $this->db->delete('srp_erp_mfq_billofmaterial', array('bomMasterID' => $masterID), 1);
        if ($result) {
            $this->db->delete('srp_erp_mfq_bom_materialconsumption', array('bomMasterID' => $masterID));
            $this->db->delete('srp_erp_mfq_bom_labourtask', array('bomMasterID' => $masterID));
            $this->db->delete('srp_erp_mfq_bom_overhead', array('bomMasterID' => $masterID));
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
            } else {
                $this->db->trans_commit();
                return array('error' => 0, 'message' => 'Record deleted successfully');
            }
        }
    }

    function load_segment_hours()
    {
        $masterID = $this->input->post('segmentID');
        $this->db->select('hours');
        $this->db->from('srp_erp_mfq_segmenthours');
        $this->db->where('mfqSegmentID', $masterID);
        $result = $this->db->get()->row_array();
        return $result;
    }
}