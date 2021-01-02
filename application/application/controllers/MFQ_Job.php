<?php

class MFQ_Job extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_Job_model');
        $this->load->model('MFQ_Job_Card_model');
        $this->load->model('Inventory_modal');
    }

    function fetch_job()
    {
        $convertFormat = convert_date_format_sql();
        $where = "srp_erp_mfq_job.companyID = " . current_companyID() . " AND (srp_erp_mfq_job.linkedJobID = 0 OR srp_erp_mfq_job.linkedJobID = '' OR srp_erp_mfq_job.linkedJobID IS NULL)";
        $this->datatables->select("documentCode,workProcessID,DATE_FORMAT(documentDate,'" . $convertFormat . "') AS documentDate,description,templateDescription,job2.percentage as percentage,CONCAT(itemSystemCode,' - ',itemDescription) as itemDescription,approvedYN,confirmedYN,isFromEstimate,cust.CustomerName as CustomerName,estimateMasterID,srp_erp_mfq_job.linkedJobID as linkedJobID", false)
            ->from('srp_erp_mfq_job')
            ->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = srp_erp_mfq_job.mfqCustomerAutoID', 'left')
            ->join('srp_erp_mfq_templatemaster', 'srp_erp_mfq_templatemaster.templateMasterID = srp_erp_mfq_job.workFlowTemplateID', 'left')
            ->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_job.mfqItemID', 'left')
            ->join('(SELECT (SUM(a.percentage)/COUNT( * )) as percentage,linkedJobID FROM srp_erp_mfq_job LEFT JOIN (SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID) a ON a.jobID = srp_erp_mfq_job.workProcessID  GROUP BY linkedJobID)  job2', 'job2.linkedJobID = srp_erp_mfq_job.workProcessID', 'left')
            ->where($where);
        $this->datatables->add_column('edit', '$1', 'editJob(workProcessID,confirmedYN,approvedYN,isFromEstimate,estimateMasterID,linkedJobID)');
        $this->datatables->edit_column('percentage', '<span class="text-center" style="vertical-align: middle">$1</span>', 'job_status(percentage)');
        $this->datatables->add_column('jobStatus', '$1', '');
        $this->datatables->add_column('status', '$1', '');
        echo $this->datatables->generate();
    }

    function save_job_header()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('workFlowTemplateID', 'Template', 'trim|required');
        $this->form_validation->set_rules('startDate', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('endDate', 'End Date', 'trim|required');
        $this->form_validation->set_rules('mfqCustomerAutoID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('mfqSegmentID', 'Segment', 'trim|required');
        if ($this->input->post('type') == 1) {
            $this->form_validation->set_rules('mfqItemID', 'Item', 'trim|required');
        } else {
            $this->form_validation->set_rules('estMfqItemID', 'Item', 'trim|required');
        }
        $this->form_validation->set_rules('mfqWarehouseAutoID', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('itemUoM', 'UOM', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {

            echo json_encode($this->MFQ_Job_model->save_job_header());
        }
    }

    function save_job_detail()
    {
        $this->form_validation->set_rules('mfqItemID', 'Item', 'trim|required');
        $this->form_validation->set_rules('qty', 'Template', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Job_model->save_job_detail());
        }
    }

    function load_job_header()
    {
        $data = $this->MFQ_Job_model->load_job_header();
        $workflow = $this->MFQ_Job_model->get_jobs();
        if ($workflow) {
            $data['completeStatus'] = 1;
        } else {
            $data['completeStatus'] = 0;
        }
        echo json_encode($data);
    }

    function load_unit_of_measure()
    {
        echo json_encode($this->MFQ_Job_model->load_unit_of_measure());
    }

    function load_mfq_estimate()
    {
        echo json_encode($this->MFQ_Job_model->load_mfq_estimate());
    }

    function get_workflow_status()
    {
        echo json_encode($this->MFQ_Job_model->get_workflow_status());
    }

    function get_jobs()
    {
        echo json_encode($this->MFQ_Job_model->get_jobs());
    }

    function close_job()
    {
        $this->form_validation->set_rules('closedDate', 'Closed Date', 'trim|required');
        $this->form_validation->set_rules('closedComment', 'Comment', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Job_model->close_job());
        }
    }

    function fetch_double_entry_job()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $jobCardID = $this->db->select_max("jobcardID")->where('workProcessID',$masterID)->get('srp_erp_mfq_jobcardmaster')->row_array();
        $data['extra'] = $this->MFQ_Job_model->fetch_double_entry_job($masterID,$jobCardID['jobcardID']);
        $html = $this->load->view('system/double_entry/erp_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }

    function getSemifinishGoods()
    {
        echo json_encode($this->MFQ_Job_model->getSemifinishGoods());
    }

    function fetch_job_approval()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('srp_erp_mfq_job.documentCode,srp_erp_mfq_job.workProcessID as workProcessID,DATE_FORMAT(srp_erp_mfq_job.documentDate,\'' . $convertFormat . '\') AS documentDate,srp_erp_mfq_job.description,CONCAT(itemSystemCode,\' - \',itemDescription) as itemDescription,srp_erp_documentapproved.approvalLevelID as approvalLevelID,documentApprovedID,srp_erp_documentapproved.approvedYN as approvedYN,cust.CustomerName as CustomerName,jcMax.jobcardID as jobcardID,DATE_FORMAT(srp_erp_mfq_job.postingFinanceDate,\'' . $convertFormat . '\') AS postingFinanceDate,appMax.approvalLevel as approvalLevel', false);
        $this->datatables->join('srp_erp_mfq_customermaster cust', 'cust.mfqCustomerAutoID = srp_erp_mfq_job.mfqCustomerAutoID', 'left');
        $this->datatables->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_job.mfqItemID', 'left');
        $this->datatables->from('srp_erp_mfq_job');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_mfq_job.workProcessID AND srp_erp_documentapproved.approvalLevelID = srp_erp_mfq_job.currentLevelNo');
        $this->datatables->join('(SELECT MAX(levelNo) as approvalLevel,documentID,companyID
           FROM srp_erp_approvalusers GROUP BY documentID,companyID) appMax', 'appMax.documentID = srp_erp_mfq_job.documentID AND srp_erp_mfq_job.companyID = appMax.companyID','left');
        $this->datatables->join('(SELECT MAX(jobcardID) as jobcardID,workProcessID
           FROM srp_erp_mfq_jobcardmaster GROUP BY workProcessID) jcMax', 'jcMax.workProcessID = srp_erp_mfq_job.workProcessID');
        //$this->datatables->join('srp_erp_mfq_jobcardmaster', 'jcMax.jobcardID = srp_erp_mfq_jobcardmaster.jobcardID');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_mfq_job.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'JOB');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'JOB');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_mfq_job.companyID', $companyID);
        $this->datatables->where('srp_erp_mfq_job.linkedJobID IS NOT NULL');
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        $this->datatables->add_column('detail', '<b>Client : </b> $1 <b> <br>Job Date : </b> $2  <b><br>Item : </b> $3 <b><br>Description : </b> $4',
            'CustomerName,documentDate,itemDescription,description');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"JOB",workProcessID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'approval_action(workProcessID,approvalLevelID,approvedYN,documentApprovedID,"JOB",jobcardID,approvalLevel,postingFinanceDate)');
        echo $this->datatables->generate();
    }

    function save_job_approval()
    {
        $system_code = trim($this->input->post('workProcessID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'JOB', $level_id);
            if ($approvedYN) {
                echo json_encode(array('w', 'Document already approved'));
            } else {
                $this->db->select('workProcessID');
                $this->db->where('workProcessID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_mfq_job');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    echo json_encode(array('w', 'Document already rejected'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('workProcessID', 'Job ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        echo json_encode(array('e', validation_errors()));
                    } else {
                        echo json_encode($this->MFQ_Job_model->save_job_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('workProcessID');
            $this->db->where('workProcessID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_mfq_job');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                echo json_encode(array('w', 'Document already rejected'));
            } else {
                $rejectYN = checkApproved($system_code, 'JOB', $level_id);
                if (!empty($rejectYN)) {
                    echo json_encode(array('w', 'Document already approved'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Job Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('workProcessID', 'Job ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        echo json_encode(array('e', validation_errors()));
                    } else {
                        echo json_encode($this->MFQ_Job_model->save_job_approval());
                    }
                }
            }
        }
    }

    function referback_job()
    {
        $workProcessID = trim($this->input->post('workProcessID'));
        $this->load->library('approvals');
        $status = $this->approvals->approve_delete($workProcessID, 'JOB');
        if ($status == 1) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }
    }


    function fetch_job_print()
    {
        $data = array();
        $jobcard = $this->db->query("SELECT * FROM srp_erp_mfq_jobcardmaster INNER JOIN (SELECT MAX( jobCardID ) AS jobCardID FROM srp_erp_mfq_jobcardmaster WHERE workProcessID = " . $this->input->post('workProcessID') . ") job ON job.jobCardID = srp_erp_mfq_jobcardmaster.jobCardID")->row_array();

        $job = $this->db->query("SELECT * FROM srp_erp_mfq_templatedetail WHERE templateDetailID = " . $jobcard["templateDetailID"])->row_array();

        $_POST["jobCardID"] = $jobcard["jobCardID"];
        $_POST["workFlowID"] = $jobcard["workFlowID"];
        $_POST["templateDetailID"] = $jobcard["templateDetailID"];
        $_POST["type"] = 2;
        $_POST["templateMasterID"] = $job["templateMasterID"];
        $_POST["linkworkFlow"] = $job["linkWorkFlow"];

        $data["workProcessID"] = $this->input->post('workProcessID');
        $data["type"] = $this->input->post('type');
        $data["material"] = $this->MFQ_Job_Card_model->fetch_jobcard_material_consumption();
        $data["overhead"] = $this->MFQ_Job_Card_model->fetch_jobcard_overhead_cost();
        $data["labourTask"] = $this->MFQ_Job_Card_model->fetch_jobcard_labour_task();
        $data["machine"] = $this->MFQ_Job_Card_model->fetch_jobcard_machine_cost();
        $data["jobheader"] = $this->MFQ_Job_model->load_job_header();
        $data["jobCardID"] = $this->input->post('jobCardID');
        $data["workFlowID"] = $this->input->post('workFlowID');
        $data["templateDetailID"] = $this->input->post('templateDetailID');
        $data["linkworkFlow"] = $this->input->post('linkworkFlow');
        $data["templateMasterID"] = $this->input->post('templateMasterID');

        if ($this->input->post('linkworkFlow')) {
            $data["prevJobCard"] = get_prev_job_card($this->input->post('workProcessID'), $this->input->post('workFlowID'), $this->input->post('linkworkFlow'), $this->input->post('templateDetailID'), $this->input->post('templateMasterID'));
        }
        $data["jobcardheader"] = get_job_cardID($this->input->post('workProcessID'), $this->input->post('workFlowID'), $this->input->post('templateDetailID'));
        $html = $this->load->view('system/mfq/ajax/job_card_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4-L', 1, 'L');
        }
    }

    function fetch_job_approval_print()
    {
        $data = array();

        $data["workProcessID"] = $this->input->post('workProcessID');
        $data["material"] = $this->MFQ_Job_Card_model->fetch_jobcard_material_consumption();
        $data["overhead"] = $this->MFQ_Job_Card_model->fetch_jobcard_overhead_cost();
        $data["labourTask"] = $this->MFQ_Job_Card_model->fetch_jobcard_labour_task();
        $data["machine"] = $this->MFQ_Job_Card_model->fetch_jobcard_machine_cost();
        $data["jobheader"] = $this->MFQ_Job_model->load_job_header();
        $html = $this->load->view('system/mfq/ajax/job_card_print_approval', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4-L', 1, 'L');
        }
    }

    function get_mfq_job_drilldown()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $workProcessID = trim($this->input->post('workProcessID'));
        $data["workProcessID"] = $workProcessID;
        $data['mfqJobDetail'] = $this->db->query("SELECT documentCode, workProcessID, DATE_FORMAT(documentDate, '%d-%m-%Y') AS documentDate, description, templateDescription, ws.percentage as percentage, CONCAT(itemSystemCode, ' - ', itemDescription) as itemDescription, approvedYN, confirmedYN,isFromEstimate,linkedJobID,estimateMasterID,cust.CustomerName FROM `srp_erp_mfq_job` LEFT JOIN `srp_erp_mfq_templatemaster` ON `srp_erp_mfq_templatemaster`.`templateMasterID` = `srp_erp_mfq_job`.`workFlowTemplateID` LEFT JOIN `srp_erp_mfq_itemmaster` ON `srp_erp_mfq_itemmaster`.`mfqItemID` = `srp_erp_mfq_job`.`mfqItemID` LEFT JOIN (SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID) ws ON `ws`.`jobID` = `srp_erp_mfq_job`.`workProcessID` LEFT JOIN srp_erp_mfq_customermaster cust ON cust.mfqCustomerAutoID = srp_erp_mfq_job.mfqCustomerAutoID WHERE `srp_erp_mfq_job`.`linkedJobID` = {$workProcessID} AND `srp_erp_mfq_job`.`companyID` = {$companyID} ORDER BY `workProcessID`")->result_array();

        $this->load->view('system/mfq/ajax/job_drilldown_view', $data);

    }

    function get_mfq_job_drilldown2()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $workProcessID = trim($this->input->post('workProcessID'));
        $data["workProcessID"] = $workProcessID;
        $data['mfqJobDetail'] = $this->db->query("SELECT documentCode, workProcessID, DATE_FORMAT(documentDate, '%d-%m-%Y') AS documentDate, description, templateDescription, ws.percentage as percentage, CONCAT(itemSystemCode, ' - ', itemDescription) as itemDescription, approvedYN, confirmedYN,isFromEstimate,linkedJobID,estimateMasterID,cust.CustomerName FROM `srp_erp_mfq_job` LEFT JOIN `srp_erp_mfq_templatemaster` ON `srp_erp_mfq_templatemaster`.`templateMasterID` = `srp_erp_mfq_job`.`workFlowTemplateID` LEFT JOIN `srp_erp_mfq_itemmaster` ON `srp_erp_mfq_itemmaster`.`mfqItemID` = `srp_erp_mfq_job`.`mfqItemID` LEFT JOIN (SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID) ws ON `ws`.`jobID` = `srp_erp_mfq_job`.`workProcessID` LEFT JOIN srp_erp_mfq_customermaster cust ON cust.mfqCustomerAutoID = srp_erp_mfq_job.mfqCustomerAutoID WHERE `srp_erp_mfq_job`.`linkedJobID` = {$workProcessID} AND `srp_erp_mfq_job`.`companyID` = {$companyID} ORDER BY `workProcessID`")->result_array();

        $this->load->view('system/mfq/ajax/job_drilldown2_view', $data);

    }

    function save_sub_job()
    {
        echo json_encode($this->MFQ_Job_model->save_sub_job());
    }

    function load_route_card()
    {
        echo json_encode($this->MFQ_Job_model->load_route_card());
    }

    function save_route_card()
    {
        $this->form_validation->set_rules('workProcessFlowID', 'Work Process Flow', 'trim|required');
        $this->form_validation->set_rules('process[]', 'Process', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Job_model->save_route_card());
        }
    }

    function delete_routecard()
    {
        echo json_encode($this->MFQ_Job_model->delete_routecard());
    }

    function load_material_consumption_qty()
    {
        echo json_encode($this->MFQ_Job_model->load_material_consumption_qty());
    }

    function load_usage_history()
    {
        echo json_encode($this->MFQ_Job_model->load_usage_history());
    }

    function save_usage_qty()
    {
        //$this->form_validation->set_rules('qtyUsage[]', 'Qty', 'trim|required');
        //if ($this->form_validation->run() == FALSE) {
        //echo json_encode(array('e', validation_errors()));
        //} else {
        echo json_encode($this->MFQ_Job_model->save_usage_qty());
        //}
    }

    function get_material_request()
    {
        $data["master"] = $this->Inventory_modal->load_material_request_header();
        $data['detail'] = $this->Inventory_modal->fetch_material_request_detail();
        $data['location'] = load_location_drop();
        $html = $this->load->view('system/mfq/ajax/material_request_view', $data, true);
        echo $html;
    }

    function save_material_request(){
        $this->form_validation->set_rules('wareHouseAutoID', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('qtyRequested[]', 'Qty Requested', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_Job_model->save_material_request());
        }
    }

    function fetch_usage_history(){
        echo json_encode($this->MFQ_Job_model->fetch_usage_history());
    }
}
