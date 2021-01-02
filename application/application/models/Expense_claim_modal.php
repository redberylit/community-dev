<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Expense_claim_modal extends ERP_Model
{

    function __contruct()
    {
        parent::__contruct();
    }

    function save_expense_claim_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $expClaimDate = trim($this->input->post('expenseClaimDate'));
        $expenseClaimDate = input_format_date($expClaimDate, $date_format_policy);
        $segment = explode('|', trim($this->input->post('segmentID')));
        $claimedByEmpID = explode('|', trim($this->input->post('claimedByEmpID')));
        $data['claimedByEmpID'] = trim($claimedByEmpID[0]);
        $data['claimedByEmpName'] = trim($claimedByEmpID[1]);
        $data['documentID'] = 'EC';
        $data['comments'] = trim_desc($this->input->post('comments'));
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['expenseClaimDate'] = $expenseClaimDate;
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        if (trim($this->input->post('expenseClaimMasterAutoID'))) {
            $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
            $this->db->update('srp_erp_expenseclaimmaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Expense Claim Updating  Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                //$this->lib_log->log_event('Purchase Order','Error','Purchase Order For : ( '.$data['supplierCode'].' ) '.$data['supplierName']. ' Update Failed '.$this->db->_error_message(),'Purchase Order');
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Expense Claim Updated Successfully.');
                $this->db->trans_commit();
                //$this->lib_log->log_event('Purchase Order','Success','Purchase Order For : ( '.$data['supplierCode'].' ) '.$data['supplierName'].' Update Successfully. Affected Rows - ' . $this->db->affected_rows(),'Purchase Order');
                return array('status' => true, 'last_id' => $this->input->post('expenseClaimMasterAutoID'),'segmentID' => $segment[0]);
            }
        } else {
            $this->load->library('sequence');
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['expenseClaimCode'] = $this->sequence->sequence_generator($data['documentID']);

            $this->db->insert('srp_erp_expenseclaimmaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Expense Claim Save Failed ' . $this->db->_error_message());
                //$this->lib_log->log_event('Purchase Order','Error','Purchase Order For : ( '.$data['supplierCode'].' ) '.$this->input->post('desc') . ' Save Failed '.$this->db->_error_message(),'Purchase Order');
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Expense Claim Saved Successfully.');
                //$this->lib_log->log_event('Purchase Order','Success','Purchase Order For : ( '.$data['supplierCode'].' ) '.$this->input->post('desc') . ' Save Successfully. Affected Rows - ' . $this->db->affected_rows(),'Purchase Order');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $last_id,'segmentID' => $segment[0]);
            }
        }
    }

    function load_expense_claim_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(expenseClaimDate,\'' . $convertFormat . '\') AS expenseClaimDate');
        $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
        $this->db->from('srp_erp_expenseclaimmaster');
        return $this->db->get()->row_array();
    }

    function save_expense_claim_category()
    {
        $this->db->trans_start();
        $claimcategoriesDescription = $this->input->post('claimcategoriesDescription');
        $expenseClaimCategoriesAutoID = $this->input->post('expenseClaimCategoriesAutoID');
        $companyID = $this->common_data['company_data']['company_id'];
        $glcd = explode('|', trim($this->input->post('GLCode')));
        $data['claimcategoriesDescription'] = trim_desc($this->input->post('claimcategoriesDescription'));
        $data['glAutoID'] = trim_desc($this->input->post('glAutoID'));
        $data['glCode'] = trim_desc($glcd[0]);
        $data['glCodeDescription'] = trim_desc($glcd[1]);
        if (trim($this->input->post('expenseClaimCategoriesAutoID'))) {
            $descexist = $this->db->query("SELECT expenseClaimCategoriesAutoID FROM srp_erp_expenseclaimcategories WHERE claimcategoriesDescription='$claimcategoriesDescription' AND expenseClaimCategoriesAutoID !=$expenseClaimCategoriesAutoID AND companyID = $companyID; ")->row_array();
        } else {
            $descexist = $this->db->query("SELECT expenseClaimCategoriesAutoID FROM srp_erp_expenseclaimcategories WHERE claimcategoriesDescription='$claimcategoriesDescription' AND companyID = $companyID; ")->row_array();
        }
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        if (trim($this->input->post('expenseClaimCategoriesAutoID'))) {
            if ($descexist) {
                return array('e', 'Description Already Exist');
            } else {
                $this->db->where('expenseClaimCategoriesAutoID', trim($this->input->post('expenseClaimCategoriesAutoID')));
                $this->db->update('srp_erp_expenseclaimcategories', $data);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return array('e', 'Expense Claim Updating  Failed');
            } else {
                return array('s', 'Expense Claim Updated Successfully');
            }
        } else {
            $this->load->library('sequence');
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            if ($descexist) {
                return array('e', 'Description Already Exist');
            } else {
                $this->db->insert('srp_erp_expenseclaimcategories', $data);
            }
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return array('e', 'Expense Claim Category Save Failed');
            } else {
                return array('s', 'Expense Claim Category Saved Successfully');
            }
        }
    }

    function editClaimCategory()
    {
        $this->db->select('*');
        $this->db->where('expenseClaimCategoriesAutoID', trim($this->input->post('expenseClaimCategoriesAutoID')));
        $this->db->from('srp_erp_expenseclaimcategories');
        return $this->db->get()->row_array();
    }

    function save_expense_claim_detail()
    {
        $expenseClaimCategoriesAutoID = $this->input->post('expenseClaimCategoriesAutoID');
        $description = $this->input->post('description');
        $referenceNo = $this->input->post('referenceNo');
        $transactionCurrencyID = $this->input->post('transactionCurrencyID');
        $transactionAmount = $this->input->post('transactionAmount');
        $tCurrencyID = $this->input->post('tCurrencyID');
        $segmentID = $this->input->post('segmentIDDetail');
        $expenseClaimMasterAutoID = $this->input->post('expenseClaimMasterAutoID');

        $this->db->select('payCurrencyID,payCurrency');
        $this->db->where('EIdNo', current_userID());
        $this->db->from('srp_employeesdetails');
        $empcurr= $this->db->get()->row_array();

        $this->db->trans_start();

        foreach ($expenseClaimCategoriesAutoID as $key => $expenseClaimCatAutoID) {

            $tCurrencyIDEx = explode('|', $tCurrencyID[$key]);

            $data['expenseClaimMasterAutoID'] = $expenseClaimMasterAutoID;
            $data['expenseClaimCategoriesAutoID'] = $expenseClaimCategoriesAutoID[$key];
            $data['description'] = $description[$key];
            $data['referenceNo'] = $referenceNo[$key];
            $data['segmentID'] = $segmentID[$key];
            $data['transactionCurrencyID'] = $transactionCurrencyID[$key];
            $data['transactionCurrency'] = trim($tCurrencyIDEx[0]);
            $data['transactionExchangeRate'] = 1;
            $data['transactionAmount'] = $transactionAmount[$key];
            $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($transactionCurrencyID[$key]);
            $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
            $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
            $data['companyLocalExchangeRate'] = $this->common_data['company_data']['company_default_currency'];
            $default_currency = currency_conversionID($transactionCurrencyID[$key], $data['companyLocalCurrencyID']);
            $data['companyLocalExchangeRate'] = $default_currency['conversion'];
            $LocalAmount = $transactionAmount[$key] / $default_currency['conversion'];
            $data['companyLocalAmount'] = $LocalAmount;
            $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
            $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
            $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
            $reporting_currency = currency_conversionID($transactionCurrencyID[$key], $data['companyReportingCurrencyID']);
            $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
            $ReportingAmount = $transactionAmount[$key] / $reporting_currency['conversion'];
            $data['companyReportingAmount'] = $ReportingAmount;
            $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];

            $data['empCurrencyID'] = $empcurr['payCurrencyID'];
            $data['empCurrency'] = $empcurr['payCurrency'];
            $emp_currency = currency_conversionID($transactionCurrencyID[$key], $empcurr['payCurrencyID']);
            $data['empCurrencyExchangeRate'] = $emp_currency['conversion'];
            $empCurrencyAmount = $transactionAmount[$key] / $emp_currency['conversion'];
            $data['empCurrencyAmount'] = round($empCurrencyAmount, $emp_currency['DecimalPlaces']);
            $data['empCurrencyDecimalPlaces'] = $emp_currency['DecimalPlaces'];

            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_expenseclaimdetails', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Expense Claim Details :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Expense Claim Details :  Saved Successfully.');
        }

    }

    function fetch_Ec_detail_table()
    {
        $expenseClaimMasterAutoID = $this->input->post('expenseClaimMasterAutoID');
        $companyID = $this->common_data['company_data']['company_id'];
        $data['detail'] = $this->db->query('SELECT
	expenseClaimDetailsID,
	expenseClaimMasterAutoID,
	claimcategoriesDescription,
	srp_erp_expenseclaimdetails.description,
	referenceNo,
	transactionCurrency,
	transactionAmount,
	transactionCurrencyDecimalPlaces,
	srp_erp_segment.segmentCode,
	srp_erp_segment.description as segdescription
FROM
	srp_erp_expenseclaimdetails
JOIN srp_erp_expenseclaimcategories ON srp_erp_expenseclaimdetails.expenseClaimCategoriesAutoID = srp_erp_expenseclaimcategories.expenseClaimCategoriesAutoID
JOIN srp_erp_segment ON srp_erp_expenseclaimdetails.segmentID = srp_erp_segment.segmentID
WHERE
	expenseClaimMasterAutoID = ' . $expenseClaimMasterAutoID . ' AND
	srp_erp_expenseclaimdetails.companyID =' . $companyID . ' ')->result_array();

        return $data;
    }

    function fetch_expense_claim_detail()
    {
        $this->db->select('*');
        $this->db->where('expenseClaimDetailsID', trim($this->input->post('expenseClaimDetailsID')));
        $this->db->from('srp_erp_expenseclaimdetails');
        return $this->db->get()->row_array();
    }

    function update_expense_claim_detail()
    {

        $this->db->select('payCurrencyID,payCurrency');
        $this->db->where('EIdNo', current_userID());
        $this->db->from('srp_employeesdetails');
        $empcurr= $this->db->get()->row_array();

        $expenseClaimCategoriesAutoID = $this->input->post('expenseClaimCategoriesAutoIDEdit');
        $description = $this->input->post('descriptionEdit');
        $referenceNo = $this->input->post('referenceNoEdit');
        $transactionCurrencyID = $this->input->post('transactionCurrencyIDEdit');
        $transactionAmount = $this->input->post('transactionAmountEdit');
        $tCurrencyID = $this->input->post('tCurrencyID');
        $expenseClaimMasterAutoID = $this->input->post('expenseClaimMasterAutoID');
        $segmentID = $this->input->post('segmentIDDetailEdit');

        $this->db->trans_start();
        $tCurrencyIDEx = explode('|', $tCurrencyID);

        $data['expenseClaimMasterAutoID'] = $expenseClaimMasterAutoID;
        $data['expenseClaimCategoriesAutoID'] = $expenseClaimCategoriesAutoID;
        $data['description'] = $description;
        $data['referenceNo'] = $referenceNo;
        $data['segmentID'] = $segmentID;
        $data['transactionCurrencyID'] = $transactionCurrencyID;
        $data['transactionCurrency'] = trim($tCurrencyIDEx[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionAmount'] = $transactionAmount;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($transactionCurrencyID);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $data['companyLocalExchangeRate'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($transactionCurrencyID, $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $LocalAmount = $transactionAmount / $default_currency['conversion'];
        $data['companyLocalAmount'] = $LocalAmount;
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $reporting_currency = currency_conversionID($transactionCurrencyID, $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $ReportingAmount = $transactionAmount / $reporting_currency['conversion'];
        $data['companyReportingAmount'] = $ReportingAmount;
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];

        $data['empCurrencyID'] = $empcurr['payCurrencyID'];
        $data['empCurrency'] = $empcurr['payCurrency'];
        $emp_currency = currency_conversionID($transactionCurrencyID, $empcurr['payCurrencyID']);
        $data['empCurrencyExchangeRate'] = $emp_currency['conversion'];
        $empCurrencyAmount = $transactionAmount / $emp_currency['conversion'];
        $data['empCurrencyAmount'] = round($empCurrencyAmount, $emp_currency['DecimalPlaces']);
        $data['empCurrencyDecimalPlaces'] = $emp_currency['DecimalPlaces'];

        $data['companyID'] = $this->common_data['company_data']['company_id'];
        $data['companyCode'] = $this->common_data['company_data']['company_code'];
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('expenseClaimDetailsID'))) {
            $this->db->where('expenseClaimDetailsID', trim($this->input->post('expenseClaimDetailsID')));
            $this->db->update('srp_erp_expenseclaimdetails', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Expense Claim Detail : Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Expense Claim Detail : Updated Successfully.');

            }
        }
    }

    function delete_expense_claim_detail()
    {
        $this->db->delete('srp_erp_expenseclaimdetails', array('expenseClaimDetailsID' => trim($this->input->post('expenseClaimDetailsID'))));
        return true;
    }

    function fetch_template_data($expenseClaimMasterAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('expenseClaimMasterAutoID,documentID,expenseClaimCode, DATE_FORMAT(expenseClaimDate,\'' . $convertFormat . '\') AS expenseClaimDate,claimedByEmpID,claimedByEmpName,comments,confirmedYN,confirmedByEmpID,confirmedByName,approvedYN,approvedByEmpName,DATE_FORMAT(approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,srp_employeesdetails.ECode as ECode ');
        $this->db->where('expenseClaimMasterAutoID', $expenseClaimMasterAutoID);
        $this->db->from('srp_erp_expenseclaimmaster');
        $this->db->join('srp_employeesdetails', 'srp_employeesdetails.EIdNo = srp_erp_expenseclaimmaster.claimedByEmpID');
        $data['master'] = $this->db->get()->row_array();

        $companyID = $this->common_data['company_data']['company_id'];
        $data['detail'] = $this->db->query('SELECT
	expenseClaimDetailsID,
	expenseClaimMasterAutoID,
	claimcategoriesDescription,
	srp_erp_expenseclaimdetails.description,
	referenceNo,
	transactionCurrency,
	transactionAmount,
	transactionCurrencyDecimalPlaces,
	srp_erp_segment.segmentCode,
	srp_erp_segment.description as segdescription
FROM
	srp_erp_expenseclaimdetails
JOIN srp_erp_expenseclaimcategories ON srp_erp_expenseclaimdetails.expenseClaimCategoriesAutoID = srp_erp_expenseclaimcategories.expenseClaimCategoriesAutoID
JOIN srp_erp_segment ON srp_erp_expenseclaimdetails.segmentID = srp_erp_segment.segmentID
WHERE
	expenseClaimMasterAutoID = ' . $expenseClaimMasterAutoID . ' AND
	srp_erp_expenseclaimdetails.companyID =' . $companyID . '
	ORDER BY transactionCurrency ')->result_array();

        $this->db->select('approvedYN, approvedDate, approvalLevelID,Ename1,Ename2,Ename3,Ename4');
        $this->db->where('documentSystemCode', $expenseClaimMasterAutoID);
        $this->db->where('documentID', 'EC');
        $this->db->from('srp_erp_documentapproved');
        $this->db->join('srp_employeesdetails', 'srp_employeesdetails.ECode = srp_erp_documentapproved.approvedEmpID');
        $data['approval'] = $this->db->get()->result_array();
        return $data;
    }

    function expense_claim_confirmation()
    {
        //$this->load->library('approvals');
        $this->db->select('*');
        $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
        $this->db->from('srp_erp_expenseclaimmaster');
        $ec_data = $this->db->get()->row_array();

        $this->db->select('expenseClaimDetailsID');
        $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
        $this->db->from('srp_erp_expenseclaimdetails');
        $detail = $this->db->get()->row_array();
        //$approvals_status = $this->approvals->CreateApproval('EC', $ec_data['expenseClaimMasterAutoID'], $ec_data['expenseClaimCode'], 'Expense Claim', 'srp_erp_expenseclaimmaster', 'expenseClaimMasterAutoID');
        /*if ($approvals_status == 1) {*/
        if($detail){
            $data = array(
                'confirmedYN' => 1,
                'confirmedDate' => $this->common_data['current_date'],
                'confirmedByEmpID' => $this->common_data['current_userID'],
                'confirmedByName' => $this->common_data['current_user'],
            );
            $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
            $this->db->update('srp_erp_expenseclaimmaster', $data);
            //$this->session->set_flashdata('s', 'Create Approval : ' . $ec_data['expenseClaimCode'] . ' Approvals Created Successfully ');
            return array('s','Approvals Created Successfully');
        }else{
            //$this->session->set_flashdata('e', 'No records found to confirm this document');
            return array('e','No records found to confirm this document');
        }

        /* } else {
             return false;
         }*/
    }

    function save_expense_Claim_approval()
    {
        if ($this->input->post('ec_status') == 1) {
            $data = array(
                'approvedYN' => 1,
                'approvedDate' => $this->common_data['current_date'],
                'approvedByEmpID' => $this->common_data['current_userID'],
                'approvedByEmpName' => $this->common_data['current_user'],
                'approvalComments' => $this->input->post('comments'),
            );
            $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
            $this->db->update('srp_erp_expenseclaimmaster', $data);
            $this->session->set_flashdata('s', ' Approved Successfully ');
            return true;
        } else {

            $this->db->select('expenseClaimCode');
            $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
            $this->db->from('srp_erp_expenseclaimmaster');
            $documentCode = $this->db->get()->row_array();


            $datas = array(
                'confirmedYN' => 3,
                /*'confirmedDate' => null,
                'confirmedByEmpID' => null,
                'confirmedByName' => null,*/
            );
            $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
            $update = $this->db->update('srp_erp_expenseclaimmaster', $datas);
            if ($update) {
                $data = array(
                    'documentID' => "EC",
                    'systemID' => $this->input->post('expenseClaimMasterAutoID'),
                    'documentCode' => $documentCode['expenseClaimCode'],
                    'comment' => $this->input->post('comments'),
                    'rejectedLevel' => 1,
                    'rejectByEmpID' => $this->common_data['current_userID'],
                    'rejectByEmpName' => $this->common_data['current_user'],
                    'table_name' => "srp_erp_expenseclaimmaster",
                    'table_unique_field' => "expenseClaimMasterAutoID",
                    'companyID' => current_companyID(),
                    'companyCode' => current_companyCode(),
                    'createdUserGroup' => $this->common_data['user_group'],
                    'createdPCID' => $this->common_data['current_pc'],
                    'createdUserID' => $this->common_data['current_userID'],
                    'createdUserName' => $this->common_data['current_user'],
                    'createdDateTime' => $this->common_data['current_date'],
                );
                $this->db->insert('srp_erp_approvalreject', $data);
                $this->session->set_flashdata('s', ' Rejected Successfully ');
                return true;
            }

        }
    }

    function fetch_approval_user_modal_ec(){
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(srp_erp_expenseclaimmaster.approvedDate,\'' . $convertFormat . '\') AS approvedDate,DATE_FORMAT(srp_erp_expenseclaimmaster.confirmedDate,\'' . $convertFormat . '\') AS confirmedDate,DATE_FORMAT(srp_erp_expenseclaimmaster.expenseClaimDate,\'' . $convertFormat . '\') AS expenseClaimDate,srp_employeesdetails.Ename2');
        $this->db->where('expenseClaimMasterAutoID',$this->input->post('documentSystemCode'));
        $this->db->where('srp_erp_employeemanagers.active', 1);
        $this->db->join('srp_erp_employeemanagers', 'srp_erp_expenseclaimmaster.claimedByEmpID = srp_erp_employeemanagers.empID');
        $this->db->join('srp_employeesdetails', 'srp_erp_employeemanagers.managerID = srp_employeesdetails.EIdNo');
        $this->db->from('srp_erp_expenseclaimmaster');
        $data = $this->db->get()->row_array();
        return $data;


    }

    function deleteClaimCategory(){
        $this->db->select('expenseClaimMasterAutoID');
        $this->db->where('expenseClaimCategoriesAutoID', trim($this->input->post('expenseClaimCategoriesAutoID')));
        $this->db->where('companyID', current_companyID());
        $this->db->from('srp_erp_expenseclaimdetails');
        $categoryExsist = $this->db->get()->row_array();
        if($categoryExsist){
            return array('e','Category has been used');
        }else{
            $this->db->delete('srp_erp_expenseclaimcategories', array('expenseClaimCategoriesAutoID' => trim($this->input->post('expenseClaimCategoriesAutoID'))));
            return array('s','Deleted Successfully');
        }
    }

    function checkDetailexsist()
    {
        $this->db->select('expenseClaimDetailsID');
        $this->db->where('expenseClaimMasterAutoID', trim($this->input->post('expenseClaimMasterAutoID')));
        $this->db->from('srp_erp_expenseclaimdetails');
        $result= $this->db->get()->result_array();
        if($result){
            return array('w','Delete detail to change employee');
        }else{
            return array('s','Employee changed successfully');
        }
    }

    function get_user_segemnt(){
        $this->db->select("segmentID");
        $this->db->from('srp_employeesdetails');
        $this->db->where('EIdNo', $this->input->post('empid'));
        $data = $this->db->get()->row_array();

        $this->db->select("segmentCode");
        $this->db->from('srp_erp_segment');
        $this->db->where('segmentID', $data['segmentID']);
        $datas = $this->db->get()->row_array();
        $result=$data['segmentID'].'|'.$datas['segmentCode'];
        return $result;
    }

}
