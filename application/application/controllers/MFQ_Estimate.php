<?php

class MFQ_Estimate extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_Estimate_model');
        $this->load->model('MFQ_Job_model');
        $this->load->helper('email');
    }

    function fetch_estimate()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('DATE_FORMAT(est.documentDate,\'' . $convertFormat . '\') as documentDate,est.description, cust.CustomerName as CustomerName,est.estimateMasterID as estimateMasterID,est.estimateCode,est.confirmedYN as confirmedYN,est.submissionStatus as submissionStatus,statusColor,statusBackgroundColor,srp_erp_mfq_status.description as statusDescription,estd.dueDate as dueDate,job.estimateMasterID as estimateMasterIDJob,est.approvedYN as approvedYN,job.workProcessID as workProcessID,docApp.docApprovedYN as docApprovedYN,est.isMailSent as isMailSent', false)
            ->from('srp_erp_mfq_estimateMaster est')->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = est.mfqCustomerAutoID', 'left')->join('srp_erp_mfq_status', 'srp_erp_mfq_status.statusID = est.submissionStatus', 'left')
            ->join('(SELECT estimateMasterID,workProcessID FROM srp_erp_mfq_job GROUP BY estimateMasterID) job', 'job.estimateMasterID = est.estimateMasterID', 'left')
            ->join("(SELECT dueDate,srp_erp_mfq_estimatedetail.ciMasterID,srp_erp_mfq_estimatedetail.estimateMasterID,srp_erp_mfq_estimatedetail.estimateDetailID FROM srp_erp_mfq_estimatedetail LEFT JOIN srp_erp_mfq_customerinquiry ON srp_erp_mfq_estimatedetail.ciMasterID = srp_erp_mfq_customerinquiry.ciMasterID WHERE srp_erp_mfq_estimatedetail.companyID = " . $this->common_data['company_data']['company_id'] . " GROUP BY srp_erp_mfq_estimatedetail.estimateMasterID) estd", 'estd.estimateMasterID = est.estimateMasterID', 'left')->join('(SELECT MAX(versionLevel),versionOrginID,MAX(estimateMasterID) as estimateMasterID FROM srp_erp_mfq_estimateMaster est2 GROUP BY versionOrginID) maxl', 'maxl.estimateMasterID = est.estimateMasterID', 'INNER')->join('(SELECT IF(SUM(approvedYN) > 0,1,0) as docApprovedYN,documentSystemCode from srp_erp_documentapproved WHERE documentID="EST" AND companyID='.current_companyID().' GROUP BY documentSystemCode) docApp', 'est.estimateMasterID = docApp.documentSystemCode', 'left');
        $this->datatables->where('est.companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->add_column('edit', '$1', 'editEstimate(estimateMasterID,confirmedYN,estimateMasterIDJob,approvedYN,workProcessID,docApprovedYN)');
        $this->datatables->add_column('submissionStatus', '$1', 'estimate_approval_status(approvedYN,confirmedYN,submissionStatus,estimateMasterID,"EST")');
        $this->datatables->add_column('estimateStatus', '$1', ' get_customerinquiry_status(confirmedYN,dueDate,isMailSent)');
        echo $this->datatables->generate();
    }

    function save_Estimate()
    {
        $this->form_validation->set_rules('mfqCustomerAutoID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Estimate Date', 'trim|required');
        //$this->form_validation->set_rules('deliveryDate', 'Delivery Date', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('submissionStatus', 'Submission Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Estimate_model->save_Estimate());
        }
    }

    function save_EstimateDetail()
    {
        $this->form_validation->set_rules('expectedQty[]', 'Qty', 'trim|required');
        $this->form_validation->set_rules('estimatedCost[]', 'Cost', 'trim|required');
        $this->form_validation->set_rules('mfqItemID[]', 'Item', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Estimate_model->save_EstimateDetail());
        }
    }

    function confirm_Estimate()
    {
        $result = $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        if ($result) {
            echo json_encode($this->MFQ_Estimate_model->confirm_Estimate());
        } else {
            echo json_encode(array('w', 'Please add items before confirm'));
        }

    }

    function delete_estimateDetail()
    {
        echo json_encode($this->MFQ_Estimate_model->delete_estimateDetail());
    }

    function load_mfq_estimate()
    {
        echo json_encode($this->MFQ_Estimate_model->load_mfq_estimate());
    }

    function load_mfq_estimate_detail()
    {
        echo json_encode($this->MFQ_Estimate_model->load_mfq_estimate_detail());
    }

    function fetch_customer_inquiry()
    {
        echo json_encode($this->MFQ_Estimate_model->fetch_customer_inquiry());
    }

    function load_mfq_customerInquiryDetail()
    {
        echo json_encode($this->MFQ_Estimate_model->load_mfq_customerInquiryDetail());
    }

    function save_estimate_detail_margin()
    {
        echo json_encode($this->MFQ_Estimate_model->save_estimate_detail_margin());
    }

    function save_estimate_detail_margin_total()
    {
        echo json_encode($this->MFQ_Estimate_model->save_estimate_detail_margin_total());
    }

    function save_estimate_detail_discount_total()
    {
        echo json_encode($this->MFQ_Estimate_model->save_estimate_detail_discount_total());
    }

    function save_estimate_detail_discount()
    {
        echo json_encode($this->MFQ_Estimate_model->save_estimate_detail_discount());
    }

    function save_estimate_detail_selling_price(){
        echo json_encode($this->MFQ_Estimate_model->save_estimate_detail_selling_price());
    }

    function fetch_estimate_print()
    {
        $_POST["estimateMasterID"] = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('estimateMasterID'));
        $data = array();
        $data["header"] = $this->MFQ_Estimate_model->load_mfq_estimate();
        $data["itemDetail"] = $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        $data["version"] = $this->MFQ_Estimate_model->load_mfq_estimate_version();
        $html = $this->load->view('system/mfq/ajax/estimate_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4-L');
        }
    }


    function save_estimate_version()
    {
        echo json_encode($this->MFQ_Estimate_model->save_estimate_version());
    }

    function load_mfq_estimate_version()
    {
        echo json_encode($this->MFQ_Estimate_model->load_mfq_estimate_version());
    }

    function load_emails()
    {
        echo json_encode($this->MFQ_Estimate_model->load_emails());
    }

    function send_emails()
    {
        $this->form_validation->set_rules('emailNW[]', 'email', 'trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Estimate_model->send_emails());
        }
    }

    function fetch_estimate_approval()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('DATE_FORMAT(est.documentDate,\'' . $convertFormat . '\') as documentDate,est.description as description, cust.CustomerName as CustomerName,est.estimateMasterID as estimateMasterID,est.estimateCode,est.confirmedYN as confirmedYN,srp_erp_documentapproved.approvalLevelID as approvalLevelID,documentApprovedID,srp_erp_documentapproved.approvedYN as approvedYN', false);
        $this->datatables->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = est.mfqCustomerAutoID', 'left');
        $this->datatables->from('srp_erp_mfq_estimateMaster est');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = est.estimateMasterID AND srp_erp_documentapproved.approvalLevelID = est.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = est.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'EST');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'EST');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('est.companyID', $companyID);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        $this->datatables->add_column('detail', '<b>Client : </b> $1 <b> <br>Estimate Date : </b> $2  <b><br> Description : </b> $3',
            'CustomerName,documentDate,description');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"EST",estimateMasterID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'approval_action(estimateMasterID,approvalLevelID,approvedYN,documentApprovedID,"EST")');
        echo $this->datatables->generate();
    }

    function save_estimate_approval()
    {
        $system_code = trim($this->input->post('estimateMasterID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'EST', $level_id);
            if ($approvedYN) {
                echo json_encode(array('w', 'Document already approved'));
            } else {
                $this->db->select('estimateMasterID');
                $this->db->where('estimateMasterID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_mfq_estimateMaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    echo json_encode(array('w', 'Document already rejected'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('estimateMasterID', 'Estimate ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        echo json_encode(array('e', validation_errors()));
                    } else {
                        echo json_encode($this->MFQ_Estimate_model->save_estimate_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('estimateMasterID');
            $this->db->where('estimateMasterID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_mfq_estimateMaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                echo json_encode(array('w', 'Document already rejected'));
            } else {
                $rejectYN = checkApproved($system_code, 'EST', $level_id);
                if (!empty($rejectYN)) {
                    echo json_encode(array('w', 'Document already approved'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Estimate Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('estimateMasterID', 'Estimate ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        echo json_encode(array('e', validation_errors()));
                    } else {
                        echo json_encode($this->MFQ_Estimate_model->save_estimate_approval());
                    }
                }
            }
        }
    }

    function referback_estimate()
    {
        $estimateMasterID = trim($this->input->post('estimateMasterID'));
        $this->load->library('approvals');
        $status = $this->approvals->approve_delete($estimateMasterID, 'EST');
        if ($status == 1) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }
    }


    function save_additional_order_detail()
    {
        $this->form_validation->set_rules('exclusions', 'Customer', 'trim|required');
        $this->form_validation->set_rules('engineeringDrawings', 'Submission of Engineering Drawings', 'trim|required');
        $this->form_validation->set_rules('engineeringDrawingsComment', 'Submission of Engineering Drawings comment', 'trim|required');
        $this->form_validation->set_rules('submissionOfITP', 'Submission of ITP', 'trim|required');
        $this->form_validation->set_rules('itpComment', 'Submission of ITP Comment', 'trim|required');
        $this->form_validation->set_rules('qcqtDocumentation', 'QC/QT documentation', 'trim|required');
        $this->form_validation->set_rules('scopeOfWork', 'Scope of work', 'trim|required');
        $this->form_validation->set_rules('materialCertificateID[]', 'Material certificate', 'trim|required');
        $this->form_validation->set_rules('mfqSegmentID', 'Segment', 'trim|required');
        $this->form_validation->set_rules('mfqWarehouseAutoID', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('orderStatus', 'Order status', 'trim|required');

        if($this->input->post("orderStatus") == 2){
            $this->form_validation->set_rules('poNumber', 'PO Number', 'trim|required');
        }
        //$this->form_validation->set_rules('poNumber', 'PO Number', 'trim|required');

        /*$this->form_validation->set_rules('deliveryTerms', 'Delivery terms', 'trim|required');
        $this->form_validation->set_rules('warranty', 'Description', 'trim|required');
        $this->form_validation->set_rules('validity', 'validity', 'trim|required');*/

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Estimate_model->save_additional_order_detail());
        }
    }

    function fetch_job_order_view()
    {
        $this->load->model("MFQ_Job_model");
        $estimateMasterID = trim($this->input->post('estimateMasterID'));
        $workProcessID = trim($this->input->post('workProcessID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('DATE_FORMAT(est.createdDateTime,\'' . $convertFormat . '\') as createdDateTime, cust.CustomerName as CustomerName,est.estimateMasterID,est.estimateCode,est.scopeOfWork,est.createdUserName,est.createdUserID,designationMaster.DesDescription,est.exclusions,est.approvedbyEmpName,DATE_FORMAT(est.approvedDate,\'' . $convertFormat . '\') as approvedDate,est.description as jobTitle,est.poNumber,est.designCode,est.designEditor,est.engineeringDrawings,est.submissionOfITP,est.qcqtDocumentation,est.deliveryTerms as deliveryTerms');
        $this->db->from('srp_erp_mfq_estimateMaster est');
        $this->db->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = est.mfqCustomerAutoID', 'left');
        $this->db->join('srp_employeedesignation designationPD', 'designationPD.EmpDesignationID = est.createdUserID AND designationPD.isActive = 1', 'left');
        $this->db->join('srp_designation designationMaster', 'designationMaster.DesignationID = designationPD.DesignationID', 'left');
        $this->db->where('est.estimateMasterID', $estimateMasterID);
        $data["header"] = $this->db->get()->row_array();
        $data["estimateDetail"] = $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        $data["certifications"] = $this->MFQ_Estimate_model->load_mfq_estimate_certifications();
        $data["jobMaster"] = $this->MFQ_Job_model->load_job_header();
        $data["detail"] = $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        $data["userInput"] = $this->MFQ_Estimate_model->load_mfq_estimate_job_order();
        $data["certificationComment"] = $this->MFQ_Estimate_model->load_mfq_estimate_job_order_mc_comment();
        $html = $this->load->view('system/mfq/ajax/estimate_job_order_print_preview', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $footer = 'Doc No:'. $data["jobMaster"]["documentCode"].' Rev No:'.$data["header"]["versionLevel"];
            $pdf = $this->pdf->printed($html, 'A4',1,$footer);
        }
    }


    function fetch_job_order_view_for_save()
    {
        $this->load->model("MFQ_Job_model");
        $estimateMasterID = trim($this->input->post('estimateMasterID'));
        $workProcessID = trim($this->input->post('workProcessID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('DATE_FORMAT(est.createdDateTime,\'' . $convertFormat . '\') as createdDateTime, cust.CustomerName as CustomerName,est.estimateMasterID,est.estimateCode,est.scopeOfWork,est.createdUserName,est.createdUserID,designationMaster.DesDescription,est.exclusions,est.approvedbyEmpName,DATE_FORMAT(est.approvedDate,\'' . $convertFormat . '\') as approvedDate,est.description as jobTitle,est.poNumber,est.designCode,est.designEditor,est.engineeringDrawings,est.submissionOfITP,est.qcqtDocumentation,est.deliveryTerms as deliveryTerms');
        $this->db->from('srp_erp_mfq_estimateMaster est');
        $this->db->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = est.mfqCustomerAutoID', 'left');
        $this->db->join('srp_employeedesignation designationPD', 'designationPD.EmpDesignationID = est.createdUserID AND designationPD.isActive = 1', 'left');
        $this->db->join('srp_designation designationMaster', 'designationMaster.DesignationID = designationPD.DesignationID', 'left');
        $this->db->where('est.estimateMasterID', $estimateMasterID);
        $data["header"] = $this->db->get()->row_array();
        $data["estimateDetail"] = $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        $data["certifications"] = $this->MFQ_Estimate_model->load_mfq_estimate_certifications();
        $data["jobMaster"] = $this->MFQ_Job_model->load_job_header();
        $data["detail"] = $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        $data["userInput"] = $this->MFQ_Estimate_model->load_mfq_estimate_job_order();
        $data["certificationComment"] = $this->MFQ_Estimate_model->load_mfq_estimate_job_order_mc_comment();
        $html = $this->load->view('system/mfq/ajax/estimate_job_order_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $footer = 'Doc No:'. $data["jobMaster"]["documentCode"].' Rev No:'.$data["header"]["versionLevel"];
            $pdf = $this->pdf->printed($html, 'A4',1,$footer);
        }
    }

    function fetch_job_order_save()
    {
        echo json_encode($this->MFQ_Estimate_model->fetch_job_order_save());
    }


    function fetch_quotation_view()
    {
        $convertFormat = convert_date_format_sql();
        $this->load->library('NumberToWords');
        $data['header'] =  $this->MFQ_Estimate_model->load_mfq_estimate();
        $data['detail'] =  $this->MFQ_Estimate_model->load_mfq_estimate_detail();
        $data['mode'] = "html";
        $html = $this->load->view('system/mfq/ajax/quotation_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

}
