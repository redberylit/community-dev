<?php

class MFQ_DeliveryNote_model extends ERP_Model
{

    function save_delivery_note_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $companyID = $this->common_data['company_data']['company_id'];
        $format_deliveryDate = input_format_date(trim($this->input->post('deliveryDate')), $date_format_policy);
        $deliverNoteID = trim($this->input->post('deliverNoteID'));

        $data['mfqCustomerAutoID'] = trim($this->input->post('mfqCustomerAutoID'));
        $data['deliveryDate'] = $format_deliveryDate;
        $data['jobID'] = trim($this->input->post('jobID'));
        $data['driverName'] = trim($this->input->post('driverName'));
        $data['vehicleNo'] = trim($this->input->post('vehicleNo'));
        $data['mobileNo'] = trim($this->input->post('mobileNo'));

        if ($deliverNoteID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('deliverNoteID', $deliverNoteID);
            $this->db->update('srp_erp_mfq_deliverynote', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Delivery Note Update Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Delivery Note Updated Successfully.');
            }
        } else {
            $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_deliverynote', 'deliverNoteID', 'companyID');
            $codes = $this->sequence->sequence_generator('MDN', $serialInfo['serialNo']);
            $data['serialNo'] = $serialInfo['serialNo'];
            $data['deliveryNoteCode'] = $codes;
            $data['documentID'] = 'MDN';
            $data['companyID'] = $companyID;
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_mfq_deliverynote', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Delivery Note Save Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Delivery Note Saved Successfully.',$last_id);

            }
        }
    }

    function load_delivery_note_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(deliveryDate,\'' . $convertFormat . '\') AS deliveryDate');
        $this->db->from('srp_erp_mfq_deliverynote');
        $this->db->where('deliverNoteID', trim($this->input->post('deliverNoteID')));
        return $this->db->get()->row_array();
    }

    function delivery_note_confirmation(){

        $this->db->trans_start();
        $deliverNoteID = trim($this->input->post('deliverNoteID'));
        $this->db->select('*');
        $this->db->where('deliverNoteID', $deliverNoteID);
        $this->db->from('srp_erp_mfq_deliverynote');
        $row = $this->db->get()->row_array();
        if (!empty($row['confirmedYN'] == 1)) {
            return array('w', 'Document already confirmed');
        } else {

            $this->db->select('srp_erp_mfq_job.*,customerAutoID,customerSystemCode,customerName,seg.segmentID,seg.segmentCode,itm.*,wh.*');
            $this->db->where('srp_erp_mfq_job.workProcessID', $row['jobID']);
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

            if ($master['mainCategory'] == 'Inventory') {
                $itemAutoID = $master['itemAutoID'];
                $qty = $master['qty'] / 1;
                $wareHouseAutoID = $master['wareHouseAutoID'];
                $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock - {$qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' and itemAutoID='{$itemAutoID}'");
                $item_arr['itemAutoID'] = $master['itemAutoID'];
                $item_arr['currentStock'] = ($master['currentStock'] - $qty);

                $itemledger_arr['documentID'] = $master['documentID'];
                $itemledger_arr['documentCode'] = $master['documentID'];
                $itemledger_arr['documentAutoID'] = $master['workProcessID'];
                $itemledger_arr['documentSystemCode'] = $master['documentCode'];
                $itemledger_arr['documentDate'] = $master['closedDate'];
                $itemledger_arr['referenceNumber'] = null;
                $itemledger_arr['companyFinanceYearID'] = $master['companyFinanceYearID'];
                $itemledger_arr['companyFinanceYear'] = $master['companyFinanceYear'];
                $itemledger_arr['FYBegin'] = $master['FYBegin'];
                $itemledger_arr['FYEnd'] = $master['FYEnd'];
                $itemledger_arr['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                $itemledger_arr['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                $itemledger_arr['wareHouseAutoID'] = $master['wareHouseAutoID'];
                $itemledger_arr['wareHouseCode'] = $master['wareHouseCode'];
                $itemledger_arr['wareHouseLocation'] = $master['wareHouseLocation'];
                $itemledger_arr['wareHouseDescription'] = $master['wareHouseDescription'];
                $itemledger_arr['itemAutoID'] = $master['itemAutoID'];
                $itemledger_arr['itemSystemCode'] = $master['itemSystemCode'];
                $itemledger_arr['itemDescription'] = $master['itemDescription'];
                $itemledger_arr['defaultUOMID'] = $master['defaultUnitOfMeasureID'];
                $itemledger_arr['defaultUOM'] = $master['defaultUnitOfMeasure'];
                $itemledger_arr['transactionUOM'] = $master['defaultUnitOfMeasure'];
                $itemledger_arr['transactionUOMID'] = $master['defaultUnitOfMeasureID'];
                $itemledger_arr['transactionQTY'] = $master['qty'];
                $itemledger_arr['convertionRate'] = 1;
                $itemledger_arr['currentStock'] = $item_arr['currentStock'];
                $itemledger_arr['PLGLAutoID'] = $master['costGLAutoID'];
                $itemledger_arr['PLSystemGLCode'] = $master['costSystemGLCode'];
                $itemledger_arr['PLGLCode'] = $master['costGLCode'];
                $itemledger_arr['PLDescription'] = $master['costDescription'];
                $itemledger_arr['PLType'] = $master['costType'];
                $itemledger_arr['BLGLAutoID'] = $master['assteGLAutoID'];
                $itemledger_arr['BLSystemGLCode'] = $master['assteSystemGLCode'];
                $itemledger_arr['BLGLCode'] = $master['assteGLCode'];
                $itemledger_arr['BLDescription'] = $master['assteDescription'];
                $itemledger_arr['BLType'] = $master['assteType'];
                $itemledger_arr['transactionAmount'] = $master['companyLocalWacAmount'] * $master['qty'];
                $itemledger_arr['transactionCurrencyID'] = $master['transactionCurrencyID'];
                $itemledger_arr['transactionCurrency'] = $master['transactionCurrency'];
                $itemledger_arr['transactionExchangeRate'] = $master['transactionExchangeRate'];
                $itemledger_arr['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];
                $itemledger_arr['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                $itemledger_arr['companyLocalCurrency'] = $master['companyLocalCurrency'];
                $itemledger_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $itemledger_arr['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                $itemledger_arr['companyLocalAmount'] = round(($itemledger_arr['transactionAmount'] / $itemledger_arr['companyLocalExchangeRate']), $itemledger_arr['companyLocalCurrencyDecimalPlaces']);
                $itemledger_arr['companyLocalWacAmount'] = $master['companyLocalWacAmount'];
                $itemledger_arr['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                $itemledger_arr['companyReportingCurrency'] = $master['companyReportingCurrency'];
                $itemledger_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $itemledger_arr['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                $itemledger_arr['companyReportingAmount'] = round(($itemledger_arr['transactionAmount'] / $itemledger_arr['companyReportingExchangeRate']), $itemledger_arr['companyReportingCurrencyDecimalPlaces']);
                $itemledger_arr['companyReportingWacAmount'] = $master['companyReportingWacAmount'];
                $itemledger_arr['partyCurrencyID'] = $master['mfqCustomerCurrencyID'];
                $itemledger_arr['partyCurrency'] = $master['mfqCustomerCurrency'];
                $itemledger_arr['partyCurrencyExchangeRate'] = 1;
                $itemledger_arr['partyCurrencyDecimalPlaces'] = $master['mfqCustomerCurrencyDecimalPlaces'];
                $itemledger_arr['partyCurrencyAmount'] = round(($itemledger_arr['transactionAmount'] / $itemledger_arr['partyCurrencyExchangeRate']), $itemledger_arr['partyCurrencyDecimalPlaces']);
                $itemledger_arr['confirmedYN'] = $master['confirmedYN'];
                $itemledger_arr['confirmedByEmpID'] = $master['confirmedByEmpID'];
                $itemledger_arr['confirmedByName'] = $master['confirmedByName'];
                $itemledger_arr['confirmedDate'] = $master['confirmedDate'];
                $itemledger_arr['segmentID'] = $master['segmentID'];
                $itemledger_arr['segmentCode'] = $master['segmentCode'];
                $itemledger_arr['companyID'] = $master['companyID'];
                $itemledger_arr['createdUserGroup'] = $master['createdUserGroup'];
                $itemledger_arr['createdPCID'] = $master['createdPCID'];
                $itemledger_arr['createdUserID'] = $master['createdUserID'];
                $itemledger_arr['createdDateTime'] = $master['createdDateTime'];
                $itemledger_arr['createdUserName'] = $master['createdUserName'];
                $itemledger_arr['modifiedPCID'] = $master['modifiedPCID'];
                $itemledger_arr['modifiedUserID'] = $master['modifiedUserID'];
                $itemledger_arr['modifiedDateTime'] = $master['modifiedDateTime'];
                $itemledger_arr['modifiedUserName'] = $master['modifiedUserName'];

                if (!empty($item_arr)) {
                    $item_arr2[] = $item_arr;
                    $this->db->update_batch('srp_erp_itemmaster', $item_arr2, 'itemAutoID');
                }

                if (!empty($itemledger_arr)) {
                    $itemledger_arr2[] = $itemledger_arr;
                    $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr2);
                }
            }

            $this->db->set('confirmedYN', 1);
            $this->db->set('confirmedByEmpID', current_userID());
            $this->db->set('confirmedByName', current_user());
            $this->db->set('confirmedDate', current_date(false));
            $this->db->where('deliverNoteID', $deliverNoteID);
            $this->db->update('srp_erp_mfq_deliverynote');

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Delivery Note Confirmed Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Delivery Note: Confirmed Successfully');
            }
        }
    }

    function referback_delivery_note()
    {
        $deliverNoteID = trim($this->input->post('deliverNoteID'));
        $dataUpdate = array(
            'confirmedYN' => 0,
            'confirmedByEmpID' => '',
            'confirmedByName' => '',
            'confirmedDate' => ''
        );
        $this->db->where('deliverNoteID', $deliverNoteID);
        $status = $this->db->update('srp_erp_mfq_deliverynote', $dataUpdate);
        if ($status) {
            return array('s', ' Referred Back Successfully.');
        } else {
            return array('e', ' Error in refer back.');
        }

    }

    function delete_delivery_note()
    {
        $deliverNoteID = trim($this->input->post('deliverNoteID'));
        $this->db->delete('srp_erp_mfq_deliverynote', array('deliverNoteID' => $deliverNoteID));
        return true;
    }

    function fetch_customer_jobs()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select('workProcessID,documentCode');
        $this->db->from('srp_erp_mfq_job job');
        $this->db->join('srp_erp_mfq_estimatedetail estd', 'estd.estimateDetailID = job.estimateDetailID', 'left');
        $this->db->join('srp_erp_mfq_estimatemaster estm', 'estd.estimateMasterID = estm.estimateMasterID', 'left');
        $this->db->where('job.mfqCustomerAutoID', $this->input->post("mfqCustomerAutoID"));
        $this->db->where('job.companyID', $companyID);
        $this->db->where('job.approvedYN', 1);
        $this->db->where('estm.orderStatus', 2);
        $this->db->where('job.levelNo', 2);
        $master = $this->db->get()->result_array();
        return $master;

    }


}