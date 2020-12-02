<?php

class MFQ_CustomerInquiry_model extends ERP_Model
{
    function save_CustomerInquiry()
    {
        $last_id = "";
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $documentDate = input_format_date(trim($this->input->post('documentDate')), $date_format_policy);
        $deliveryDate = input_format_date(trim($this->input->post('deliveryDate')), $date_format_policy);
        $dueDate = input_format_date(trim($this->input->post('dueDate')), $date_format_policy);
        if (!$this->input->post('ciMasterID')) {
            $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_customerinquiry', 'ciMasterID', 'companyID');
            $codes = $this->sequence->sequence_generator('CI', $serialInfo['serialNo']);
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('serialNo', $serialInfo['serialNo']);
            $this->db->set('ciCode', $codes);
            $this->db->set('documentDate', $documentDate);
            $this->db->set('deliveryDate', $deliveryDate);
            $this->db->set('dueDate', $dueDate);
            $this->db->set('description', $this->input->post('description'));
            $this->db->set('referenceNo', $this->input->post('referenceNo'));
            $this->db->set('statusID', $this->input->post('statusID'));
            $this->db->set('type', $this->input->post('type'));
            $this->db->set('manufacturingType', $this->input->post('manufacturingType'));
            //$this->db->set('paymentTerm', $this->input->post('paymentTerm'));
            $this->db->set('currencyID', $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('companyID', current_companyID());
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));

            $result = $this->db->insert('srp_erp_mfq_customerinquiry');
            $last_id = $this->db->insert_id();

        } else {
            $last_id = $this->input->post('ciMasterID');
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('documentDate', $documentDate);
            $this->db->set('deliveryDate', $deliveryDate);
            $this->db->set('dueDate', $dueDate);
            $this->db->set('description', $this->input->post('description'));
            $this->db->set('referenceNo', $this->input->post('referenceNo'));
            $this->db->set('statusID', $this->input->post('statusID'));
            $this->db->set('type', $this->input->post('type'));
            $this->db->set('manufacturingType', $this->input->post('manufacturingType'));
            //$this->db->set('paymentTerm', $this->input->post('paymentTerm'));
            $this->db->set('currencyID', $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('modifiedUserID', current_userID());
            $this->db->set('modifiedUserName', current_user());
            $this->db->set('modifiedDateTime', current_date(true));

            $this->db->where('ciMasterID', $this->input->post('ciMasterID'));
            $result = $this->db->update('srp_erp_mfq_customerinquiry');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Job Card Saved Failed ' . $this->db->_error_message());

        } else {

            $ciDetailID = $this->input->post('ciDetailID');
            $mfqItemID = $this->input->post('mfqItemID');
            $description = $this->input->post('search');
            if (!empty($mfqItemID)) {
                foreach ($mfqItemID as $key => $val) {
                    if (!empty($ciDetailID[$key])) {
                        if (!empty($mfqItemID[$key]) || !empty($description[$key])) {
                            $date_format_policy = date_format_policy();
                            $expectedDeliveryDate = input_format_date(trim($this->input->post('expectedDeliveryDate')[$key]), $date_format_policy);
                            $this->db->set('ciMasterID', $last_id);
                            if (empty($mfqItemID[$key]) || $mfqItemID[$key] == 'null') {
                                $this->db->set('itemDescription', $description[$key]);
                                $this->db->set('mfqItemID', null);
                            } else {
                                $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                                $this->db->set('itemDescription', null);
                            }
                            $this->db->set('expectedQty', $this->input->post('expectedQty')[$key]);
                            $this->db->set('segmentID', $this->input->post('segmentID')[$key]);
                            $this->db->set('expectedDeliveryDate', $expectedDeliveryDate);
                            $this->db->set('remarks', $this->input->post('remarks')[$key]);
                            $this->db->set('deliveryTerms', $this->input->post('deliveryTerms')[$key]);

                            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('modifiedUserID', current_userID());
                            $this->db->set('modifiedUserName', current_user());
                            $this->db->set('modifiedDateTime', current_date(true));
                            $this->db->where('ciDetailID', $ciDetailID[$key]);
                            $result = $this->db->update('srp_erp_mfq_customerinquirydetail');
                        }
                    } else {
                        if (!empty($mfqItemID[$key]) || !empty($description[$key])) {
                            $date_format_policy = date_format_policy();
                            $expectedDeliveryDate = input_format_date(trim($this->input->post('expectedDeliveryDate')[$key]), $date_format_policy);
                            $this->db->set('ciMasterID', $last_id);
                            if (empty($mfqItemID[$key])) {
                                $this->db->set('itemDescription', $description[$key]);
                                $this->db->set('mfqItemID', null);
                            } else {
                                $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                                $this->db->set('itemDescription', null);
                            }
                            $this->db->set('expectedQty', $this->input->post('expectedQty')[$key]);
                            $this->db->set('segmentID', $this->input->post('segmentID')[$key]);
                            $this->db->set('expectedDeliveryDate', $expectedDeliveryDate);
                            $this->db->set('remarks', $this->input->post('remarks')[$key]);
                            $this->db->set('deliveryTerms', $this->input->post('deliveryTerms')[$key]);
                            $this->db->set('companyID', current_companyID());

                            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('createdUserID', current_userID());
                            $this->db->set('createdUserName', current_user());
                            $this->db->set('createdDateTime', current_date(true));
                            $result = $this->db->insert('srp_erp_mfq_customerinquirydetail');
                        }
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Inquiry Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Inquiry Saved Successfully.', $last_id);
            }
        }

    }

    function customer_inquiry_confirmation_bkp()
    {
        $this->db->trans_start();
        $ciMasterID = trim($this->input->post('ciMasterID'));
        $this->db->select('*');
        $this->db->where('ciMasterID', $ciMasterID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_mfq_customerinquiry');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {
            $this->load->library('approvals');
            $this->db->select('*');
            $this->db->where('ciMasterID', $ciMasterID);
            $this->db->from('srp_erp_mfq_customerinquiry');
            $row = $this->db->get()->row_array();
            $approvals_status = $this->approvals->CreateApproval('CI', $row['ciMasterID'],
                $row['ciCode'], 'Customer Inquiry', 'srp_erp_mfq_customerinquiry', 'ciMasterID', 0);
            /* if ($approvals_status == 1) {
                 $this->db->set('confirmedYN', 1);
                 $this->db->set('confirmedUserID', current_userID());
                 $this->db->set('confirmedUserName', current_user());
                 $this->db->set('confirmedDate', current_date(false));
                 $this->db->where('ciMasterID', $ciMasterID);
                 $this->db->update('srp_erp_mfq_customerinquiry');
             }*/

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Inquiry Confirmed Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Inquiry : Confirmed Successfully');
            }
        }
    }

    function customer_inquiry_confirmation()
    {
        $this->db->trans_start();
        $ciMasterID = trim($this->input->post('ciMasterID'));
        $this->db->select('*');
        $this->db->where('ciMasterID', $ciMasterID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_mfq_customerinquiry');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {
            $this->db->set('confirmedYN', 1);
            $this->db->set('confirmedByEmpID', current_userID());
            $this->db->set('confirmedByName', current_user());
            $this->db->set('confirmedDate', current_date(false));
            $this->db->set('approvedYN', 1);
            $this->db->set('approvedbyEmpID', current_userID());
            $this->db->set('approvedbyEmpName', current_user());
            $this->db->set('approvedDate', current_date(false));
            $this->db->where('ciMasterID', $ciMasterID);
            $this->db->update('srp_erp_mfq_customerinquiry');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Inquiry Approved Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Inquiry : Approved Successfully');
            }
        }
    }

    function delete_customerInquiryDetail()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('ciDetailID');
        $this->db->from('srp_erp_mfq_customerinquirydetail');
        $this->db->where('ciMasterID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_customerinquirydetail', array('ciDetailID' => $this->input->post('ciDetailID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function load_mfq_customerInquiry()
    {
        $convertFormat = convert_date_format_sql();
        $ciMasterID = $this->input->post('ciMasterID');
        $this->db->select('DATE_FORMAT(documentDate,\'' . $convertFormat . '\') as documentDate,DATE_FORMAT(dueDate,\'' . $convertFormat . '\') as dueDate,DATE_FORMAT(deliveryDate,\'' . $convertFormat . '\') as deliveryDate,description,paymentTerm, srp_erp_mfq_customerinquiry.mfqCustomerAutoID,ciMasterID as ciMasterID,ciCode,srp_erp_mfq_customermaster.CustomerName,referenceNo,statusID,type');
        $this->db->join('srp_erp_mfq_customermaster', 'srp_erp_mfq_customermaster.mfqCustomerAutoID = srp_erp_mfq_customerinquiry.mfqCustomerAutoID', 'left');
        $this->db->from('srp_erp_mfq_customerinquiry');
        $this->db->where('ciMasterID', $ciMasterID);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function load_mfq_customerInquiryDetail()
    {
        $convertFormat = convert_date_format_sql();
        $ciMasterID = $this->input->post('ciMasterID');
        $this->db->select('srp_erp_mfq_customerinquirydetail.*,DATE_FORMAT(srp_erp_mfq_customerinquirydetail.expectedDeliveryDate,\'' . $convertFormat . '\') as expectedDeliveryDate,IFNULL(srp_erp_mfq_customerinquirydetail.itemDescription,CONCAT(srp_erp_mfq_itemmaster.itemDescription," (",itemSystemCode,")")) as itemDescription,itemSystemCode,IFNULL(UnitDes,"") as UnitDes,srp_erp_mfq_segment.description as segment,bomm.bomMasterID,IFNULL(bomm.cost,0) as estimatedCost');
        $this->db->from('srp_erp_mfq_customerinquirydetail');
        $this->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_customerinquirydetail.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID', 'left');
        $this->db->join('srp_erp_unit_of_measure', 'unitID = defaultUnitOfMeasureID', 'left');
        $this->db->join('srp_erp_mfq_segment', 'mfqSegmentID = srp_erp_mfq_customerinquirydetail.segmentID', 'left');
        $this->db->join('(SELECT ((IFNULL(bmc.materialCharge,0) + IFNULL(lt.totalValue,0) + IFNULL(oh.totalValue,0))/bom.Qty) as cost,bom.mfqItemID,bom.bomMasterID FROM srp_erp_mfq_billofmaterial bom LEFT JOIN (SELECT SUM(materialCharge) as materialCharge,bomMasterID FROM srp_erp_mfq_bom_materialconsumption GROUP BY bomMasterID) bmc ON bmc.bomMasterID = bom.bomMasterID  LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_labourtask GROUP BY bomMasterID) lt ON lt.bomMasterID = bom.bomMasterID LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_overhead GROUP BY bomMasterID) oh ON oh.bomMasterID = bom.bomMasterID  GROUP BY mfqItemID) bomm', 'bomm.mfqItemID = srp_erp_mfq_customerinquirydetail.mfqItemID', 'left');
        $this->db->where('ciMasterID', $ciMasterID);
        $result = $this->db->get()->result_array();
        return $result;
    }

    function load_mfq_customerInquiryDetailOnlyItem()
    {
        $convertFormat = convert_date_format_sql();
        $ciMasterID = $this->input->post('ciMasterID');
        $this->db->select('srp_erp_mfq_customerinquirydetail.*,DATE_FORMAT(srp_erp_mfq_customerinquirydetail.expectedDeliveryDate,\'' . $convertFormat . '\') as expectedDeliveryDate,IFNULL(srp_erp_mfq_customerinquirydetail.itemDescription,CONCAT(srp_erp_mfq_itemmaster.itemDescription," (",itemSystemCode,")")) as itemDescription,itemSystemCode,IFNULL(UnitDes,"") as UnitDes,srp_erp_mfq_segment.description as segment,bomm.bomMasterID,IFNULL(bomm.cost,0) as estimatedCost');
        $this->db->from('srp_erp_mfq_customerinquirydetail');
        $this->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_customerinquirydetail.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID', 'left');
        $this->db->join('srp_erp_unit_of_measure', 'unitID = defaultUnitOfMeasureID', 'left');
        $this->db->join('srp_erp_mfq_segment', 'mfqSegmentID = srp_erp_mfq_customerinquirydetail.segmentID', 'left');
        $this->db->join('(SELECT ((IFNULL(bmc.materialCharge,0) + IFNULL(lt.totalValue,0) + IFNULL(oh.totalValue,0))/bom.Qty) as cost,bom.mfqItemID,bom.bomMasterID FROM srp_erp_mfq_billofmaterial bom LEFT JOIN (SELECT SUM(materialCharge) as materialCharge,bomMasterID FROM srp_erp_mfq_bom_materialconsumption GROUP BY bomMasterID) bmc ON bmc.bomMasterID = bom.bomMasterID  LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_labourtask GROUP BY bomMasterID) lt ON lt.bomMasterID = bom.bomMasterID LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_overhead GROUP BY bomMasterID) oh ON oh.bomMasterID = bom.bomMasterID  GROUP BY mfqItemID) bomm', 'bomm.mfqItemID = srp_erp_mfq_customerinquirydetail.mfqItemID', 'left');
        $this->db->where('ciMasterID', $ciMasterID);
        $this->db->where('srp_erp_mfq_customerinquirydetail.mfqItemID IS NOT NULL');
        $result = $this->db->get()->result_array();
        return $result;
    }


    function attachement_upload()
    {
        $this->db->trans_start();
        $file_name = 'MFQ_' . $this->input->post('documentID') . '_' . time();
        $config['upload_path'] = realpath(APPPATH . '../attachments/MFQ');
        $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|docx|ppt|pptx|ppsx|pdf|xls|xlsx|xlsxm|rtf|msg|txt|7zip|zip|rar';
        $config['max_size'] = '5120'; // 5 MB
        $config['file_name'] = $file_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload("document_file")) {
            echo json_encode(array('status' => 0, 'type' => 'w', 'message' => 'Upload failed ' . $this->upload->display_errors()));
        } else {
            $upload_data = $this->upload->data();
            //$fileName                       = $file_name.'_'.$upload_data["file_ext"];
            $data['workFlowID'] = trim($this->input->post('workFlowID'));
            $data['workProcessID'] = trim($this->input->post('workProcessID'));
            $data['attachmentDescription'] = trim($this->input->post('attachmentDescription'));
            $data['myFileName'] = $file_name . $upload_data["file_ext"];
            $data['fileType'] = trim($upload_data["file_ext"]);
            $data['fileSize'] = trim($upload_data["file_size"]);
            $data['timestamp'] = date('Y-m-d H:i:s');
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_mfq_workflowattachments', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Upload failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Successfully Uploaded.');
            }
        }
    }

    function load_attachments()
    {
        $this->db->where('documentSystemCode', $this->input->post('documentSystemCode'));
        $this->db->where('documentID', $this->input->post('documentID'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $data = $this->db->get('srp_erp_documentattachments')->result_array();
        return $data;
    }

    function fetch_finish_goods()
    {
        $dataArr = array();
        $dataArr2 = array();
        $companyID = current_companyID();
        $search_string = "%" . $_GET['query'] . "%";
        $sql = 'SELECT mfqCategoryID,mfqSubcategoryID,secondaryItemCode,mfqSubSubCategoryID,itemSystemCode,costGLCode,defaultUnitOfMeasure,defaultUnitOfMeasureID,itemDescription,srp_erp_mfq_itemmaster.mfqItemID as itemAutoID,currentStock,companyLocalWacAmount,companyLocalSellingPrice,CONCAT(CASE srp_erp_mfq_itemmaster.itemType WHEN 1 THEN "RM" WHEN 2 THEN "FG" WHEN 3 THEN "SF"
END," - ",IFNULL(itemDescription,""), " (" ,IFNULL(itemSystemCode,""),")") AS "Match",partNo,srp_erp_unit_of_measure.unitDes as uom,IFNULL(bomm.cost,0) as cost,bomm.bomMasterID FROM srp_erp_mfq_itemmaster LEFT JOIN srp_erp_unit_of_measure ON srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID
 LEFT JOIN (SELECT ((IFNULL(bmc.materialCharge,0) + IFNULL(lt.totalValue,0) + IFNULL(oh.totalValue,0))/bom.Qty) as cost,bom.mfqItemID,bom.bomMasterID FROM srp_erp_mfq_billofmaterial bom LEFT JOIN (SELECT SUM(materialCharge) as materialCharge,bomMasterID FROM srp_erp_mfq_bom_materialconsumption GROUP BY bomMasterID) bmc ON bmc.bomMasterID = bom.bomMasterID  LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_labourtask GROUP BY bomMasterID) lt ON lt.bomMasterID = bom.bomMasterID LEFT JOIN (SELECT SUM(totalValue) as totalValue,bomMasterID FROM srp_erp_mfq_bom_overhead GROUP BY bomMasterID) oh ON oh.bomMasterID = bom.bomMasterID  GROUP BY mfqItemID) bomm ON bomm.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID
 WHERE (itemSystemCode LIKE "' . $search_string . '" OR itemDescription LIKE "' . $search_string . '" OR secondaryItemCode LIKE "' . $search_string . '") AND srp_erp_mfq_itemmaster.companyID = "' . $companyID . '" AND (srp_erp_mfq_itemmaster.itemType = 2 OR srp_erp_mfq_itemmaster.itemType = 3) AND isActive="1"';
        $data = $this->db->query($sql)->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['itemSystemCode'], 'mfqItemID' => $val['itemAutoID'], 'currentStock' => $val['currentStock'], 'uom' => $val['uom'], 'defaultUnitOfMeasureID' => $val['defaultUnitOfMeasureID'], 'companyLocalSellingPrice' => $val['companyLocalSellingPrice'], 'companyLocalWacAmount' => $val['companyLocalWacAmount'], 'partNo' => $val['partNo'], 'cost' => $val['cost'], 'bomMasterID' => $val['bomMasterID']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function save_customer_inquiry_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_id = trim($this->input->post('ciMasterID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));
        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'CI');
        if ($approvals_status == 1) {
            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            $this->db->where('ciMasterID', $system_id);
            $this->db->update('srp_erp_mfq_customerinquiry', $data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('s', 'Error occurred');
        } else {
            $this->db->trans_commit();
            return array('s', 'Customer Inquiry Approved Successfully');
        }
    }
}
