<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ExpenseClaim extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Expense_claim_modal');
        $this->load->helpers('Expense_claim');
    }

    function fetch_expanse_claim()
    {
        // date inter change according to company policy
        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $claimedByEmpID = current_userID();
        //$supplier = $this->input->post('supplierPrimaryCode');
        $status = $this->input->post('status');
        $supplier_filter = '';
        /*if (!empty($supplier)) {
            $supplier = array($this->input->post('supplierPrimaryCode'));
            $whereIN = "( " . join("' , '", $supplier) . " )";
            $supplier_filter = " AND supplierID IN " . $whereIN;
        }*/
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( expenseClaimDate >= '" . $datefromconvert . " 00:00:00' AND expenseClaimDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $where = "companyID = " . $companyid . " And claimedByEmpID = " . $claimedByEmpID . $date . $status_filter . "";
        $convertFormat = convert_date_format_sql();
        $company_reporting_currency=$this->common_data['company_data']['company_reporting_currency'];
        $company_reporting_DecimalPlaces=$this->common_data['company_data']['company_reporting_decimal'];
        $this->datatables->select("srp_erp_expenseclaimmaster.expenseClaimMasterAutoID as expenseClaimMasterAutoID,expenseClaimCode,comments,claimedByEmpName,confirmedYN,approvedYN ,DATE_FORMAT(expenseClaimDate,'$convertFormat') AS expenseClaimDate,createdUserID,det.transactionAmount as total_value,det.empCurrencyDecimalPlaces as empCurrencyDecimal,srp_employeesdetails.payCurrency as empCurrency");
        $this->datatables->join('(SELECT SUM(empCurrencyAmount) as transactionAmount,expenseClaimMasterAutoID,empCurrencyDecimalPlaces,empCurrency FROM srp_erp_expenseclaimdetails GROUP BY expenseClaimMasterAutoID) det', '(det.expenseClaimMasterAutoID = srp_erp_expenseclaimmaster.expenseClaimMasterAutoID)', 'left');
        $this->datatables->join('srp_employeesdetails ', 'srp_erp_expenseclaimmaster.claimedByEmpID = srp_employeesdetails.EIdNo');
        $this->datatables->from('srp_erp_expenseclaimmaster');
        $this->datatables->add_column('Ec_detail', '<b>Claimed By Name : </b> $1 <br> <b>Claimed Date : </b> $2 <br><b>Description : </b> $3', 'claimedByEmpName,expenseClaimDate,comments');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,empCurrencyDecimal),empCurrency');
        $this->datatables->where($where);
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"EC",expenseClaimMasterAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_EC(approvedYN,confirmedYN,"EC",expenseClaimMasterAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_EC_action(expenseClaimMasterAutoID,confirmedYN,approvedYN,createdUserID)');
        echo $this->datatables->generate();
    }

    function save_expense_claim_header()
    {
        $this->form_validation->set_rules('comments', 'Description', 'trim|required');
        $this->form_validation->set_rules('expenseClaimDate', 'Expense Claim Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('segmentID', 'Segment', 'trim|required');
        $this->form_validation->set_rules('claimedByEmpID', 'Employee', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Expense_claim_modal->save_expense_claim_header());
        }
    }

    function load_expense_claim_header()
    {
        echo json_encode($this->Expense_claim_modal->load_expense_claim_header());
    }

    function fetch_calim_category()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $where = "companyID = " . $companyid . "";
        $this->datatables->select("srp_erp_expenseclaimcategories.expenseClaimCategoriesAutoID as expenseClaimCategoriesAutoID,claimcategoriesDescription,glCode,glCodeDescription");
        $this->datatables->from('srp_erp_expenseclaimcategories');
        $this->datatables->add_column('Ec_detail', ' $1 - $2 ', 'glCode,glCodeDescription');
        $this->datatables->where($where);
        $this->datatables->add_column('edit', '$1', 'load_claim_category_action(expenseClaimCategoriesAutoID)');
        echo $this->datatables->generate();
    }

    function save_expense_claim_category()
    {
        $this->form_validation->set_rules('claimcategoriesDescription', 'Description', 'trim|required');
        $this->form_validation->set_rules('glAutoID', 'GL Code', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Expense_claim_modal->save_expense_claim_category());
        }
    }

    function editClaimCategory(){
        echo json_encode($this->Expense_claim_modal->editClaimCategory());
    }


    function save_expense_claim_detail()
    {

        $expenseClaimCategoriesAutoID = $this->input->post('expenseClaimCategoriesAutoID');
        $itemAutoID = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $quantityRequested = $this->input->post('quantityRequested');
        $estimatedAmount = $this->input->post('estimatedAmount');

        foreach ($expenseClaimCategoriesAutoID as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("expenseClaimCategoriesAutoID[{$key}]", 'Claim Category', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'Description', 'trim|required');
            $this->form_validation->set_rules("referenceNo[{$key}]", 'Doc Ref', 'trim|required');
            $this->form_validation->set_rules("transactionCurrencyID[{$key}]", 'Currency', 'trim|required');
            $this->form_validation->set_rules("segmentIDDetail[{$key}]", 'Segment', 'trim|required');
            $this->form_validation->set_rules("transactionAmount[{$key}]", 'Amount', 'trim|required|greater_than[0]');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
           // echo"oky";
            echo json_encode($this->Expense_claim_modal->save_expense_claim_detail());
        }
    }

    function fetch_Ec_detail_table()
    {
        echo json_encode($this->Expense_claim_modal->fetch_Ec_detail_table());
    }

    function fetch_expense_claim_detail()
    {
        echo json_encode($this->Expense_claim_modal->fetch_expense_claim_detail());
    }

    function update_expense_claim_detail()
    {
        $this->form_validation->set_rules("expenseClaimCategoriesAutoIDEdit", 'Claim Category', 'trim|required');
        $this->form_validation->set_rules("descriptionEdit", 'Description', 'trim|required');
        $this->form_validation->set_rules("referenceNoEdit", 'Doc Ref', 'trim|required');
        $this->form_validation->set_rules("transactionCurrencyIDEdit", 'Currency', 'trim|required');
        $this->form_validation->set_rules("transactionAmountEdit", 'Amount', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules("segmentIDDetailEdit", 'Segment', 'trim|required|greater_than[0]');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Expense_claim_modal->update_expense_claim_detail());
        }
    }

    function delete_expense_claim_detail()
    {
        echo json_encode($this->Expense_claim_modal->delete_expense_claim_detail());
    }

    function load_expense_claim_conformation(){
        $expenseClaimMasterAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('expenseClaimMasterAutoID'));
        $data['extra'] = $this->Expense_claim_modal->fetch_template_data($expenseClaimMasterAutoID);
        $data['approval'] = $this->input->post('approval');
        $html = $this->load->view('system/expenseClaim/erp_expense_claim_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function expense_claim_confirmation()
    {
        $this->db->select('claimedByEmpID');
        $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
        $this->db->from('srp_erp_expenseclaimmaster');
        $empid = $this->db->get()->row_array();
        if($empid){
            $this->db->select('managerID');
            $this->db->where('empID', trim($empid['claimedByEmpID']));
            $this->db->where('active', 1);
            $this->db->from('srp_erp_employeemanagers');
            $managerid = $this->db->get()->row_array();
            if(!empty($managerid)){
                echo json_encode($this->Expense_claim_modal->expense_claim_confirmation());
            }else{
                echo json_encode(array('w','Reporting manager not available for this employee'));
            }
        }
    }

    function referback_expense_claim()
    {
        $expenseClaimMasterAutoID = $this->input->post('expenseClaimMasterAutoID');

        $data = array(
            'confirmedYN' => 0,
            'confirmedDate' => null,
            'confirmedByEmpID' => null,
            'confirmedByName' => null,
        );
        $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
        $status= $this->db->update('srp_erp_expenseclaimmaster', $data);
        if ($status) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }

    }

    function delete_expense_claim(){
        $status=$this->db->delete('srp_erp_expenseclaimmaster', array('expenseClaimMasterAutoID' => trim($this->input->post('expenseClaimMasterAutoID'))));
        if($status){
            $this->db->delete('srp_erp_expenseclaimdetails', array('expenseClaimMasterAutoID' => trim($this->input->post('expenseClaimMasterAutoID'))));
            echo json_encode(array('s', ' Deleted Successfully.', $status));
        }else {
            echo json_encode(array('e', ' Error in Deletion.', $status));
        }
    }

    function fetch_expanse_claim_approval()
    {
        // date inter change according to company policy
        $date_format_policy = date_format_policy();


        $companyid = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $empID=current_userID();
        $convertFormat = convert_date_format_sql();
        $company_reporting_currency=$this->common_data['company_data']['company_reporting_currency'];
        $company_reporting_DecimalPlaces=$this->common_data['company_data']['company_reporting_decimal'];
        $this->datatables->select("srp_erp_expenseclaimmaster.expenseClaimMasterAutoID as expenseClaimMasterAutoID,expenseClaimCode,comments,claimedByEmpName,confirmedYN,approvedYN ,DATE_FORMAT(expenseClaimDate,'$convertFormat') AS expenseClaimDate,srp_erp_expenseclaimmaster.createdUserID,det.empCurrency as empCurrency,det.transactionAmount as total_value");
        $this->datatables->join('(SELECT SUM(empCurrencyAmount) as transactionAmount,expenseClaimMasterAutoID,empCurrency FROM srp_erp_expenseclaimdetails GROUP BY expenseClaimMasterAutoID) det', '(det.expenseClaimMasterAutoID = srp_erp_expenseclaimmaster.expenseClaimMasterAutoID)', 'left');
        $this->datatables->join('srp_erp_employeemanagers ', 'srp_erp_expenseclaimmaster.claimedByEmpID = srp_erp_employeemanagers.empID');
        $this->datatables->from('srp_erp_expenseclaimmaster');
        $this->datatables->add_column('Ec_detail', '<b>Claimed By Name : </b> $1 <br> <b>Claimed Date : </b> $2 <br><b>Description : </b> $3', 'claimedByEmpName,expenseClaimDate,comments');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,'.$company_reporting_DecimalPlaces.'),empCurrency');
        $this->datatables->where('srp_erp_expenseclaimmaster.companyID', $companyid);
        $this->datatables->where('srp_erp_expenseclaimmaster.confirmedYN', 1);
        $this->datatables->where('srp_erp_expenseclaimmaster.approvedYN', $approvedYN);
        $this->datatables->where('srp_erp_employeemanagers.managerID', $empID);
        $this->datatables->where('srp_erp_employeemanagers.active', 1);
        $this->datatables->add_column('approved', '$1', 'confirm_aproval_EC(approvedYN,confirmedYN,"EC",expenseClaimMasterAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_EC_approval_action(expenseClaimMasterAutoID,confirmedYN,approvedYN,createdUserID)');
        echo $this->datatables->generate();
    }

    function save_expense_Claim_approval()
    {
        $this->form_validation->set_rules('ec_status', 'Expense Claim Status', 'trim|required');
        if($this->input->post('ec_status') ==2) {
            $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
        }
        $this->form_validation->set_rules('expenseClaimMasterAutoID', 'Expense Claim ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Expense_claim_modal->save_expense_Claim_approval());
        }
    }

    function fetch_approval_user_modal_ec(){
        echo json_encode($this->Expense_claim_modal->fetch_approval_user_modal_ec());
    }

    function deleteClaimCategory(){
        echo json_encode($this->Expense_claim_modal->deleteClaimCategory());
    }

    function fetch_expanse_claim_hrms()
    {
        // date inter change according to company policy
        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $claimedByEmpID = current_userID();
        //$supplier = $this->input->post('supplierPrimaryCode');
        $status = $this->input->post('status');
        $supplier_filter = '';
        /*if (!empty($supplier)) {
            $supplier = array($this->input->post('supplierPrimaryCode'));
            $whereIN = "( " . join("' , '", $supplier) . " )";
            $supplier_filter = " AND supplierID IN " . $whereIN;
        }*/
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( expenseClaimDate >= '" . $datefromconvert . " 00:00:00' AND expenseClaimDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $where = "companyID = " . $companyid . $date . $status_filter . "";
        $convertFormat = convert_date_format_sql();
        $company_reporting_currency=$this->common_data['company_data']['company_reporting_currency'];
        $company_reporting_DecimalPlaces=$this->common_data['company_data']['company_reporting_decimal'];
        $this->datatables->select("srp_erp_expenseclaimmaster.expenseClaimMasterAutoID as expenseClaimMasterAutoID,expenseClaimCode,comments,claimedByEmpName,confirmedYN,approvedYN ,DATE_FORMAT(expenseClaimDate,'$convertFormat') AS expenseClaimDate,createdUserID,det.transactionAmount as total_value,det.empCurrencyDecimalPlaces as empCurrencyDecimal,det.empCurrency as empCurrency");
        $this->datatables->join('(SELECT SUM(empCurrencyAmount) as transactionAmount,expenseClaimMasterAutoID,empCurrencyDecimalPlaces,empCurrency FROM srp_erp_expenseclaimdetails GROUP BY expenseClaimMasterAutoID) det', '(det.expenseClaimMasterAutoID = srp_erp_expenseclaimmaster.expenseClaimMasterAutoID)', 'left');
        $this->datatables->from('srp_erp_expenseclaimmaster');
        $this->datatables->add_column('Ec_detail', '<b>Claimed By Name : </b> $1 <br> <b>Claimed Date : </b> $2 <br><b>Description : </b> $3', 'claimedByEmpName,expenseClaimDate,comments');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,empCurrencyDecimal),empCurrency');
        $this->datatables->where($where);
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"EC",expenseClaimMasterAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_EC(approvedYN,confirmedYN,"EC",expenseClaimMasterAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_EC_action_hrms(expenseClaimMasterAutoID,confirmedYN,approvedYN,createdUserID)');
        echo $this->datatables->generate();
    }

    function checkDetailexsist(){
        echo json_encode($this->Expense_claim_modal->checkDetailexsist());
    }

    function get_user_segemnt(){
        echo json_encode($this->Expense_claim_modal->get_user_segemnt());
    }
}