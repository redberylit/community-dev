<?php

class MFQ_CustomerInquiry extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_CustomerInquiry_model');
        $this->load->model('MFQ_Estimate_model');
    }

    function fetch_customerInquiry()
    {
        $customer = $this->input->post("customerID");
        $status = $this->input->post("statusID");

        $convertFormat = convert_date_format_sql();
        $this->datatables->select('DATE_FORMAT(ci.documentDate,\'' . $convertFormat . '\') as documentDate,DATE_FORMAT(ci.dueDate,\'' . $convertFormat . '\') as dueDate,DATE_FORMAT(ci.deliveryDate,\'' . $convertFormat . '\') as deliveryDate,ci.description,ci.paymentTerm, cust.CustomerName as CustomerName,ci.ciMasterID as ciMasterID,ci.ciCode as ciCode,ci.confirmedYN as confirmedYN,ci.statusID as statusID,statusColor,statusBackgroundColor,srp_erp_mfq_status.description as statusDescription,ci.dueDate as plannedDate,referenceNo,ci.approvedYN as approvedYN,est.confirmedYN as estConfirmedYN', false)
            ->from('srp_erp_mfq_customerinquiry ci')
            ->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = ci.mfqCustomerAutoID', 'left')->join('srp_erp_mfq_status', 'srp_erp_mfq_status.statusID = ci.statusID', 'left')
            ->join('(SELECT srp_erp_mfq_estimatedetail.estimateMasterID,srp_erp_mfq_estimatemaster.approvedYN,srp_erp_mfq_estimatedetail.ciMasterID,confirmedYN FROM srp_erp_mfq_estimatedetail INNER JOIN srp_erp_mfq_estimatemaster ON srp_erp_mfq_estimatemaster.estimateMasterID = srp_erp_mfq_estimatedetail.estimateMasterID GROUP BY ciMasterID) est', 'est.ciMasterID = ci.ciMasterID', 'left');
        $this->datatables->where('ci.companyID', $this->common_data['company_data']['company_id']);
        if ($customer) {
            $this->datatables->where('ci.mfqCustomerAutoID', $customer);
        }
        if ($status) {
            $this->datatables->where('ci.statusID', $status);
        }
        $this->datatables->add_column('edit', '$1', 'editCustomerInquiry(ciMasterID,confirmedYN,confirmedYN)');
        $this->datatables->add_column('status', '$1', 'customerInquiryStatus(confirmedYN,estConfirmedYN)');
        $this->datatables->add_column('statusID', '$1', 'customer_inquiry_approval_status(approvedYN,confirmedYN,statusID,ciMasterID,"CI")');
        echo $this->datatables->generate();
    }

    function save_CustomerInquiry()
    {
        $this->form_validation->set_rules('mfqCustomerAutoID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required');
        $this->form_validation->set_rules('deliveryDate', 'Actual Submission Date', 'trim|required');
        $this->form_validation->set_rules('dueDate', 'Planned Submission Date', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('referenceNo', 'Client Reference No', 'trim|required');
        $this->form_validation->set_rules('statusID', 'Status', 'trim|required');
        $this->form_validation->set_rules('type', 'Inquiry Type', 'trim|required');
        $this->form_validation->set_rules('manufacturingType', 'Manufacturing Type', 'trim|required');
        //$this->form_validation->set_rules('paymentTerm', 'Payment Terms', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_CustomerInquiry_model->save_CustomerInquiry());
        }
    }

    function customer_inquiry_confirmation()
    {
        echo json_encode($this->MFQ_CustomerInquiry_model->customer_inquiry_confirmation());
    }

    function delete_customerInquiryDetail()
    {
        echo json_encode($this->MFQ_CustomerInquiry_model->delete_customerInquiryDetail());
    }

    function load_mfq_customerInquiry()
    {
        echo json_encode($this->MFQ_CustomerInquiry_model->load_mfq_customerInquiry());
    }

    function load_mfq_customerInquiryDetail()
    {
        echo json_encode($this->MFQ_CustomerInquiry_model->load_mfq_customerInquiryDetail());
    }

    function fetch_customer_inquiry_print()
    {
        $data = array();
        $data["header"] = $this->MFQ_CustomerInquiry_model->load_mfq_customerInquiry();
        $data["itemDetail"] = $this->MFQ_CustomerInquiry_model->load_mfq_customerInquiryDetail();
        $this->load->view('system/mfq/ajax/customer_inquiry_print', $data);
    }


    function load_attachments()
    {
        $data['attachment'] = $this->MFQ_CustomerInquiry_model->load_attachments();
        $data['documentID'] = $this->input->post('documentID');
        $this->load->view('system/mfq/ajax/general_attachment_view', $data);
    }

    function fetch_finish_goods()
    {
        echo json_encode($this->MFQ_CustomerInquiry_model->fetch_finish_goods());
    }

    function generateEstimate()
    {
        $master = $this->MFQ_CustomerInquiry_model->load_mfq_customerInquiry();
        $detail = $this->MFQ_CustomerInquiry_model->load_mfq_customerInquiryDetailOnlyItem();
        $_POST["mfqCustomerAutoID"] = $master["mfqCustomerAutoID"];
        $_POST["documentDate"] = current_format_date();
        $_POST["deliveryDate"] = current_format_date();
        $_POST["description"] = $master["description"];
        $_POST["scopeOfWork"] = null;
        $_POST["technicalDetail"] = null;
        $_POST["currencyID"] = $this->common_data['company_data']['company_default_currencyID'];
        if (!$detail) {
            echo json_encode($this->MFQ_Estimate_model->save_Estimate());
        } else {
            $estimateMasterID = $this->MFQ_Estimate_model->save_Estimate();
            $_POST["estimateMasterID"] = $estimateMasterID[2];
            $_POST["ciMasterID"] = array_column($detail, 'ciMasterID');
            $_POST["mfqItemID"] = array_column($detail, 'mfqItemID');
            $_POST["ciDetailID"] = array_column($detail, 'ciDetailID');
            $_POST["bomMasterID"] = array_column($detail, 'bomMasterID');
            $_POST["expectedQty"] = array_column($detail, 'expectedQty');
            $_POST["estimatedCost"] = array_column($detail, 'estimatedCost');
            echo json_encode($this->MFQ_Estimate_model->save_EstimateDetail());
        }
    }


    function fetch_customer_inquiry_approval()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('DATE_FORMAT(ci.documentDate,\'' . $convertFormat . '\') as documentDate,DATE_FORMAT(ci.dueDate,\'' . $convertFormat . '\') as dueDate,DATE_FORMAT(ci.deliveryDate,\'' . $convertFormat . '\') as deliveryDate,ci.description,ci.paymentTerm, cust.CustomerName as CustomerName,ci.ciMasterID as ciMasterID,ci.ciCode,ci.confirmedYN as confirmedYN,ci.statusID as statusID,ci.dueDate as plannedDate,referenceNo,srp_erp_documentapproved.approvalLevelID as approvalLevelID,srp_erp_documentapproved.approvedYN as approvedYN', false);
        $this->datatables->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = ci.mfqCustomerAutoID', 'left');
        $this->datatables->from('srp_erp_mfq_customerinquiry ci');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = ci.ciMasterID AND srp_erp_documentapproved.approvalLevelID = ci.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = ci.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'CI');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'CI');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('ci.companyID', $companyID);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        $this->datatables->add_column('detail', '<b>Client : </b> $1 <b> <br>Inquiry Date : </b> $2  <br>Client Ref No : </b> $3 <b>',
            'CustomerName,documentDate,referenceNo');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CI",ciMasterID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'approval_action(ciMasterID,approvalLevelID,approvedYN,documentApprovedID,"CI")');
        echo $this->datatables->generate();
    }

    function save_customer_inquiry_approval()
    {
        $system_code = trim($this->input->post('ciMasterID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'CI', $level_id);
            if ($approvedYN) {
                echo json_encode(array('w', 'Document already approved'));
            } else {
                $this->db->select('ciMasterID');
                $this->db->where('ciMasterID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_mfq_customerinquiry');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    echo json_encode(array('w', 'Document already rejected'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('ciMasterID', 'Customer Inquiry ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        echo json_encode(array('e', validation_errors()));
                    } else {
                        echo json_encode($this->MFQ_CustomerInquiry_model->save_customer_inquiry_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('ciMasterID');
            $this->db->where('ciMasterID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_mfq_customerinquiry');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'CI', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Customer Inquiry Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('ciMasterID', 'Customer Inquiry ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->MFQ_CustomerInquiry_model->save_customer_inquiry_approval());
                    }
                }
            }
        }
    }

    function referback_customer_inquiry()
    {
        $ciMasterID = trim($this->input->post('ciMasterID'));
        $this->load->library('approvals');
        $status = $this->approvals->approve_delete($ciMasterID, 'CI');
        if ($status == 1) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }
    }
}
