<?php

class Journal_entry_model extends ERP_Model
{

    function save_journal_entry_header()
    {
        $this->db->trans_start();
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $date_format_policy = date_format_policy();
        $Jdates = $this->input->post('JVdate');
        $JVdate = input_format_date($Jdates, $date_format_policy);



        //$period = explode('|', trim($this->input->post('financeyear_period')));
        if($financeyearperiodYN==1) {
            $companyFinancePeriod = trim($this->input->post('companyFinancePeriod'));
            $period = explode(' - ', trim($companyFinancePeriod));
            $PeriodBegin = input_format_date($period[0], $date_format_policy);
            $PeriodEnd = input_format_date($period[1], $date_format_policy);

            $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
            $FYBegin = input_format_date($year[0], $date_format_policy);
            $FYEnd = input_format_date($year[1], $date_format_policy);
        }else{
            $financeYearDetails=get_financial_year($JVdate);
            if(empty($financeYearDetails)){
                $this->session->set_flashdata('e', 'Finance period not found for the selected document date');
                return array('status' => false);
                exit;
            }else{
                $FYBegin=$financeYearDetails['beginingDate'];
                $FYEnd=$financeYearDetails['endingDate'];
                $_POST['companyFinanceYear'] = $FYBegin.' - '.$FYEnd;
                $_POST['financeyear'] = $financeYearDetails['companyFinanceYearID'];
            }
            $financePeriodDetails=get_financial_period_date_wise($JVdate);

            if(empty($financePeriodDetails)){
                $this->session->set_flashdata('e', 'Finance period not found for the selected document date');
                return array('status' => false);
                exit;
            }else{
                $_POST['financeyear_period'] = $financePeriodDetails['companyFinancePeriodID'];
                $PeriodBegin = $financePeriodDetails['dateFrom'];
                $PeriodEnd = $financePeriodDetails['dateTo'];
            }
        }



        $currency_code = explode('|', trim($this->input->post('currency_code')));



        $data['documentID'] = 'JV';
        $data['JVType'] = trim($this->input->post('JVType'));
        $data['JVdate'] = trim($JVdate);
        $data['JVNarration'] = trim_desc($this->input->post('JVNarration'));
        $data['referenceNo'] = trim($this->input->post('referenceNo'));
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        $data['FYPeriodDateFrom'] = trim($PeriodBegin);
        $data['FYPeriodDateTo'] = trim($PeriodEnd);
        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];

        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];

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

        if (trim($this->input->post('JVMasterAutoId'))) {
            $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
            $this->db->update('srp_erp_jvmaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Journal Entry : (' . $data['JVType'] . ' ) ' . $data['JVNarration'] . ' Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Journal Entry : (' . $data['JVType'] . ' ) ' . $data['JVNarration'] . ' Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('JVMasterAutoId'));
            }
        } else {
            //$this->load->library('sequence');
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['JVcode'] = 0;
            $this->db->insert('srp_erp_jvmaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Journal Entry : (' . $data['JVType'] . ' ) ' . $data['JVNarration'] . ' Save Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Journal Entry : (' . $data['JVType'] . ' ) ' . $data['JVNarration'] . ' Saved Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $last_id);
            }
        }
    }

    function save_gl_detail()
    {
        $projectExist = project_is_exist();
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $master = $this->db->get('srp_erp_jvmaster')->row_array();

        $gl_codes = $this->input->post('gl_code');
        $gl_code_des = $this->input->post('gl_code_des');
        /*$gl_types = $this->input->post('gl_type');*/
        $debitAmount = $this->input->post('debitAmount');
        $creditAmount = $this->input->post('creditAmount');
        $descriptions = $this->input->post('description');
        $segment_gls = $this->input->post('segment_gl');
        $projectID = $this->input->post('projectID');

        foreach ($gl_codes as $key => $gl_code) {
            $segment = explode('|', $segment_gls[$key]);
            $gldata = fetch_gl_account_desc($gl_codes[$key]);

            if ($gldata['masterCategory'] == 'PL') {
                $data[$key]['segmentID'] = trim($segment[0]);
                $data[$key]['segmentCode'] = trim($segment[1]);
            } else {
                /*   $data[$key]['segmentID'] = trim($segment[0]);
                   $data[$key]['segmentCode'] = trim($segment[1]);*/
                $data[$key]['segmentID'] = null;
                $data[$key]['segmentCode'] = null;
            }

            if ($projectExist == 1) {
                $projectCurrency = project_currency($projectID[$key]);
                $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
                $data[$key]['projectID'] = $projectID[$key];
                $data[$key]['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
            }

            $gl_des = explode('|', $gl_code_des[$key]);
            $data[$key]['JVMasterAutoId'] = trim($this->input->post('JVMasterAutoId'));
            $data[$key]['GLAutoID'] = $gl_codes[$key];
            $data[$key]['systemGLCode'] = trim($gl_des[0]);
            $data[$key]['GLCode'] = trim($gl_des[1]);
            $data[$key]['GLDescription'] = trim($gl_des[2]);
            $data[$key]['GLType'] = trim($gl_des[3]);
            $data[$key]['projectID'] = $projectID[$key];

            if ($creditAmount[$key] > 0) {
                $data[$key]['gl_type'] = 'Cr';
            } else {
                $data[$key]['gl_type'] = 'Dr';
            }

            if ($data[$key]['gl_type'] == 'Cr') {
                $data[$key]['creditAmount'] = round($creditAmount[$key], $master['transactionCurrencyDecimalPlaces']);
                $creditCompanyLocalAmount = $data[$key]['creditAmount'] / $master['companyLocalExchangeRate'];
                $data[$key]['creditCompanyLocalAmount'] = round($creditCompanyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
                $creditCompanyReportingAmount = $data[$key]['creditAmount'] / $master['companyReportingExchangeRate'];
                $data[$key]['creditCompanyReportingAmount'] = round($creditCompanyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);

                //updating the value as 0
                $data[$key]['debitAmount'] = 0;
                $data[$key]['debitCompanyLocalAmount'] = 0;
                $data[$key]['debitCompanyReportingAmount'] = 0;

                if($gldata['isBank']==1){
                    $data[$key]['isBank'] = 1;
                    $data[$key]['bankCurrencyID'] = $gldata['bankCurrencyID'];
                    $data[$key]['bankCurrency'] = $gldata['bankCurrencyCode'];
                    $bank_currency = currency_conversionID($master['transactionCurrencyID'], $gldata['bankCurrencyID']);
                    $data[$key]['bankCurrencyExchangeRate'] = $bank_currency['conversion'];
                    $data[$key]['bankCurrencyAmount'] = $data[$key]['creditAmount'] / $bank_currency['conversion'];
                }else{
                    $data[$key]['isBank'] = 0;
                    $data[$key]['bankCurrencyID'] = null;
                    $data[$key]['bankCurrency'] = null;
                    $data[$key]['bankCurrencyExchangeRate'] = null;
                    $data[$key]['bankCurrencyAmount'] = null;
                }
            } else {


                $data[$key]['debitAmount'] = round($debitAmount[$key], $master['transactionCurrencyDecimalPlaces']);
                $debitCompanyLocalAmount = $data[$key]['debitAmount'] / $master['companyLocalExchangeRate'];
                $data[$key]['debitCompanyLocalAmount'] = round($debitCompanyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
                $debitCompanyReportingAmount = $data[$key]['debitAmount'] / $master['companyReportingExchangeRate'];
                $data[$key]['debitCompanyReportingAmount'] = round($debitCompanyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);

                //updating the value as 0
                $data[$key]['creditAmount'] = 0;
                $data[$key]['creditCompanyLocalAmount'] = 0;
                $data[$key]['creditCompanyReportingAmount'] = 0;

                if($gldata['isBank']==1){
                    $data[$key]['isBank'] = 1;
                    $data[$key]['bankCurrencyID'] = $gldata['bankCurrencyID'];
                    $data[$key]['bankCurrency'] = $gldata['bankCurrencyCode'];
                    $bank_currency = currency_conversionID($master['transactionCurrencyID'], $gldata['bankCurrencyID']);
                    $data[$key]['bankCurrencyExchangeRate'] = $bank_currency['conversion'];
                    $data[$key]['bankCurrencyAmount'] = $data[$key]['debitAmount'] / $bank_currency['conversion'];
                }else{
                    $data[$key]['isBank'] = 0;
                    $data[$key]['bankCurrencyID'] = null;
                    $data[$key]['bankCurrency'] = null;
                    $data[$key]['bankCurrencyExchangeRate'] = null;
                    $data[$key]['bankCurrencyAmount'] = null;
                }
            }
            $data[$key]['description'] = $descriptions[$key];
            $data[$key]['type'] = 'GL';

            $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
            $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];



        }

        $this->db->insert_batch('srp_erp_jvdetail', $data);
        /*$last_id = $this->db->insert_id();*/
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'GL Description : Saved Failed ');
        } else {
            $this->db->trans_commit();
            return array('s', 'GL Description :  Saved Successfully.');
        }


        if (trim($this->input->post('JVDetailAutoID'))) {
            /*$this->db->where('JVDetailAutoID', trim($this->input->post('JVDetailAutoID')));
            $this->db->update('srp_erp_jvdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'GL Description : ' . $data['GLDescription'] . ' Update Failed ');
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'GL Description : ' . $data['GLDescription'] . ' Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('JVDetailAutoID'));
            }*/
        } else {

            //insert
        }
    }


    function update_gl_detail()
    {
        $projectExist = project_is_exist();
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $master = $this->db->get('srp_erp_jvmaster')->row_array();
        $segment = explode('|', trim($this->input->post('edit_segment_gl')));
        $gl = $this->input->post('gl_code_des');
        $creditAmount = $this->input->post('editcreditAmount');
        $projectID = $this->input->post('projectID');
        $debitAmount = $this->input->post('editdebitAmount');

        $gldata = fetch_gl_account_desc($this->input->post('edit_gl_code'));
        if ($gldata['masterCategory'] == 'PL') {
            $data['segmentID'] = trim($segment[0]);
            $data['segmentCode'] = trim($segment[1]);

        }

        $gl_code = explode('|', trim($gl));
        $data['JVMasterAutoId'] = trim($this->input->post('JVMasterAutoId'));
        $data['GLAutoID'] = trim($this->input->post('edit_gl_code'));
        $data['systemGLCode'] = trim($gl_code[0]);
        $data['GLCode'] = trim($gl_code[1]);
        $data['GLDescription'] = trim($gl_code[2]);
        $data['GLType'] = trim($gl_code[3]);

        if ($projectExist == 1) {
            $projectCurrency = project_currency($projectID);
            $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
            $data['projectID'] = $projectID;
            $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
        }
        if ($creditAmount > 0) {
            $data['gl_type'] = 'Cr';
        } else {
            $data['gl_type'] = 'Dr';
        }

        if ($data['gl_type'] == 'Cr') {
            $data['creditAmount'] = round(trim($this->input->post('editcreditAmount')), $master['transactionCurrencyDecimalPlaces']);
            $creditCompanyLocalAmount = $data['creditAmount'] / $master['companyLocalExchangeRate'];
            $data['creditCompanyLocalAmount'] = round($creditCompanyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $creditCompanyReportingAmount = $data['creditAmount'] / $master['companyReportingExchangeRate'];
            $data['creditCompanyReportingAmount'] = round($creditCompanyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);

            //updating the value as 0
            $data['debitAmount'] = 0;
            $data['debitCompanyLocalAmount'] = 0;
            $data['debitCompanyReportingAmount'] = 0;

            if($gldata['isBank']==1){
                $data['isBank'] = 1;
                $data['bankCurrencyID'] = $gldata['bankCurrencyID'];
                $data['bankCurrency'] = $gldata['bankCurrencyCode'];
                $bank_currency = currency_conversionID($master['transactionCurrencyID'], $gldata['bankCurrencyID']);
                $data['bankCurrencyExchangeRate'] = $bank_currency['conversion'];
                $data['bankCurrencyAmount'] = $data['creditAmount'] / $bank_currency['conversion'];
            }

        } else {
            $data['debitAmount'] = round(trim($this->input->post('editdebitAmount')), $master['transactionCurrencyDecimalPlaces']);
            $debitCompanyLocalAmount = $data['debitAmount'] / $master['companyLocalExchangeRate'];
            $data['debitCompanyLocalAmount'] = round($debitCompanyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $debitCompanyReportingAmount = $data['debitAmount'] / $master['companyReportingExchangeRate'];
            $data['debitCompanyReportingAmount'] = round($debitCompanyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);

            //updating the value as 0
            $data['creditAmount'] = 0;
            $data['creditCompanyLocalAmount'] = 0;
            $data['creditCompanyReportingAmount'] = 0;

            if($gldata['isBank']==1){
                $data['isBank'] = 1;
                $data['bankCurrencyID'] = $gldata['bankCurrencyID'];
                $data['bankCurrency'] = $gldata['bankCurrencyCode'];
                $bank_currency = currency_conversionID($master['transactionCurrencyID'], $gldata['bankCurrencyID']);
                $data['bankCurrencyExchangeRate'] = $bank_currency['conversion'];
                $data['bankCurrencyAmount'] = $data['debitAmount'] / $bank_currency['conversion'];
            }
        }
        $data['description'] = trim($this->input->post('editdescription'));
        $data['type'] = 'GL';

        if (trim($this->input->post('JVDetailAutoID'))) {
            $this->db->where('JVDetailAutoID', trim($this->input->post('JVDetailAutoID')));
            $this->db->update('srp_erp_jvdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'GL Description : ' . $data['GLDescription'] . ' Update Failed ');
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'GL Description : ' . $data['GLDescription'] . ' Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('JVDetailAutoID'));
            }
        } else {
        }
    }

    function fetch_Journal_entry_template_data($JVMasterAutoId)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(JVdate,\'' . $convertFormat . '\') AS JVdate,DATE_FORMAT(approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,CASE WHEN confirmedYN = 2 || confirmedYN = 3   THEN " - " WHEN confirmedYN = 1 THEN CONCAT_WS(\' on \',IF(LENGTH(confirmedbyName),confirmedbyName,\'-\'),IF(LENGTH(DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' )),DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' ),NULL)) ELSE "-" END confirmedYNn');
        $this->db->where('JVMasterAutoId', $JVMasterAutoId);
        $this->db->from('srp_erp_jvmaster');
        $data['master'] = $this->db->get()->row_array();
        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);
        $this->db->select('*');
        $this->db->where('JVMasterAutoId', $JVMasterAutoId);
        $this->db->from('srp_erp_jvdetail');
        $data['detail'] = $this->db->get()->result_array();
        return $data;
    }

    function fetch_journal_entry_detail()
    {
        $this->db->select('transactionCurrency,transactionCurrencyDecimalPlaces');
        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $this->db->from('srp_erp_jvmaster');
        $data['currency'] = $this->db->get()->row_array();
        $this->db->select('*');
        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $this->db->from('srp_erp_jvdetail');
        $data['detail'] = $this->db->get()->result_array();
        return $data;
    }

    function load_journal_entry_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(JVdate,\'' . $convertFormat . '\') AS JVdate');
        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $this->db->from('srp_erp_jvmaster');
        return $this->db->get()->row_array();
    }

    function delete_Journal_entry_detail()
    {
        $this->db->where('JVDetailAutoID', $this->input->post('JVDetailAutoID'));
        $this->db->delete('srp_erp_jvdetail');
        $this->session->set_flashdata('s', 'Journal entry : deleted Successfully.');
        return true;
    }

    function delete_Journal_entry()
    {
        /*$this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $this->db->delete('srp_erp_jvmaster');

        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $this->db->delete('srp_erp_jvdetail');
        $this->session->set_flashdata('s', 'Journal entry : deleted Successfully.');
        return true;*/
        $this->db->select('*');
        $this->db->from('srp_erp_jvdetail');
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $datas = $this->db->get()->row_array();

        $this->db->select('JVcode');
        $this->db->from('srp_erp_jvmaster');
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $master = $this->db->get()->row_array();

        if ($datas) {
            $this->session->set_flashdata('e', 'please delete all detail records before delete this document.');
            return true;
        } else {
            if($master['JVcode']=="0"){
                $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
                $results = $this->db->delete('srp_erp_jvmaster');
                if ($results) {
                    $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
                    $this->db->delete('srp_erp_jvdetail');
                    $this->session->set_flashdata('s', 'Deleted Successfully');
                    return true;
                }
            }else{
                $data = array(
                    'isDeleted' => 1,
                    'deletedEmpID' => current_userID(),
                    'deletedDate' => current_date(),
                );
                $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
                $this->db->update('srp_erp_jvmaster', $data);
                $this->session->set_flashdata('s', 'Deleted Successfully.');
                return true;
            }

        }
    }

    function journal_entry_confirmation()
    {
        $locationwisecodegenerate = getPolicyValues('LDG', 'All');
        $this->db->select('documentID, JVcode,DATE_FORMAT(JVdate, "%Y") as invYear,DATE_FORMAT(JVdate, "%m") as invMonth,companyFinanceYearID');
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $this->db->from('srp_erp_jvmaster');
        $master_dt = $this->db->get()->row_array();

        $companyID = current_companyID();
        $currentuser  = current_userID();
        $locationemp = $this->common_data['emplanglocationid'];

        $this->db->select('*');
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $detl = $this->db->get('srp_erp_jvdetail')->row_array();
        if(empty($detl)){
            $this->session->set_flashdata('w', 'JV Detail can not be empty');
            return false;
            exit;
        }

            $this->load->library('sequence');
        if($master_dt['JVcode'] == "0"){
            if($locationwisecodegenerate == 1)
            {
                $this->db->select('locationID');
                $this->db->where('EIdNo', $currentuser);
                $this->db->where('Erp_companyID', $companyID);
                $this->db->from('srp_employeesdetails');
                $location = $this->db->get()->row_array();
                if ((empty($location)) || ($location =='')) {
                    $this->session->set_flashdata('w', 'Location is not assigned for current employee');
                    return false;
                }else
                {
                    if($locationemp!='')
                    {
                        $jvcd = $this->sequence->sequence_generator_location($master_dt['documentID'],$master_dt['companyFinanceYearID'],$locationemp,$master_dt['invYear'],$master_dt['invMonth']);
                    }else
                    {
                        $this->session->set_flashdata('w', 'Location is not assigned for current employee');
                        return false;
                    }

                }

            }else
            {
                $jvcd = $this->sequence->sequence_generator_fin($master_dt['documentID'], $master_dt['companyFinanceYearID'], $master_dt['invYear'], $master_dt['invMonth']);
            }
            $jvcd = array(
                'JVcode' => $jvcd
            );
            $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
            $this->db->update('srp_erp_jvmaster', $jvcd);
        }

        $this->load->library('approvals');
        $this->db->select('documentID,JVMasterAutoId, JVcode,DATE_FORMAT(JVdate, "%Y") as invYear,DATE_FORMAT(JVdate, "%m") as invMonth,companyFinanceYearID,JVdate');
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $this->db->from('srp_erp_jvmaster');
        $app_data = $this->db->get()->row_array();

        $this->db->select_sum('debitAmount');
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $amount = $this->db->get('srp_erp_jvdetail')->row_array();

        $autoApproval= get_document_auto_approval('JV');

        if($autoApproval==0){
            $approvals_status = $this->approvals->auto_approve($app_data['JVMasterAutoId'], 'srp_erp_jvmaster','JVMasterAutoId', 'JV',$app_data['JVcode'],$app_data['JVdate']);
        }elseif($autoApproval==1){
            $approvals_status = $this->approvals->CreateApproval('JV', $app_data['JVMasterAutoId'], $app_data['JVcode'], 'Journal Entry', 'srp_erp_jvmaster', 'JVMasterAutoId',0,$app_data['JVdate']);
        }else{
            $this->session->set_flashdata('e', 'Approval levels are not set for this document');
            return false;
            exit;
        }

        if ($approvals_status==1) {
            $autoApproval= get_document_auto_approval('JV');

            if($autoApproval==0) {
                $result = $this->save_jv_approval(0, $app_data['JVMasterAutoId'], 1, 'Auto Approved');
                if($result){
                    $this->session->set_flashdata('s', 'Approvals Created Successfully.');
                    return true;
                }
            }else{
                $data = array(
                    'confirmedYN' => 1,
                    'confirmedDate' => $this->common_data['current_date'],
                    'confirmedByEmpID' => $this->common_data['current_userID'],
                    'confirmedByName' => $this->common_data['current_user'],
                    'transactionAmount' => $amount['debitAmount']
                );

                $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
                $this->db->update('srp_erp_jvmaster', $data);
                $this->session->set_flashdata('s', 'Approvals Created Successfully.');
                return true;
            }

        }else if($approvals_status==3){
            /*$this->session->set_flashdata('w', 'There are no users exist to perform approval for this document.');
            return true;*/
        } else {
            $this->session->set_flashdata('e', 'Document confirmation failed.');
            return false;
        }

    }

    function save_jv_approval($autoappLevel=1,$system_idAP=0,$statusAP=0,$commentsAP=0)
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        if($autoappLevel==1) {
            $system_code = trim($this->input->post('JVMasterAutoId'));
            $level_id = trim($this->input->post('Level'));
            $status = trim($this->input->post('status'));
            $comments = trim($this->input->post('comments'));
        }else{
            $system_code = $system_idAP;
            $level_id = 0;
            $status = $statusAP;
            $comments = $commentsAP;
            $_post['JVMasterAutoId']=$system_code;
            $_post['Level']=$level_id;
            $_post['status']=$status;
            $_post['comments']=$comments;
        }
        $companyID = current_companyID();

        $JVDetails = $this->db->query('SELECT
	srp_erp_jvdetail.*,srp_erp_chartofaccounts.bankCurrencyID,srp_erp_chartofaccounts.bankCurrencyCode,srp_erp_chartofaccounts.bankCurrencyDecimalPlaces,srp_erp_chartofaccounts.isBank,srp_erp_chartofaccounts.bankName
FROM
	srp_erp_jvdetail
LEFT JOIN srp_erp_chartofaccounts ON srp_erp_jvdetail.GLAutoID = srp_erp_chartofaccounts.GLAutoID
WHERE
	JVMasterAutoId = '.$system_code.'
AND srp_erp_jvdetail.companyID= '.$companyID.'  ')->result_array();
        if($autoappLevel==0){
            $approvals_status=1;
        }else{
            $approvals_status = $this->approvals->approve_document($system_code, $level_id, $status, $comments, 'JV');
        }

        if ($approvals_status == 1) {
            $this->load->model('Double_entry_model');
            $double_entry = $this->Double_entry_model->fetch_double_entry_journal_entry_data($system_code, 'JV');
            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['JVMasterAutoId'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['code'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['JVcode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['JVdate'];
                $generalledger_arr[$i]['documentType'] = $double_entry['master_data']['JVType'];
                $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['JVdate'];
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['JVdate']));
                $generalledger_arr[$i]['documentNarration'] = $double_entry['gl_detail'][$i]['description'];
                $generalledger_arr[$i]['chequeNumber'] = null;
                $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['partyContractID'] = '';
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                //$generalledger_arr[$i]['partyCurrencyAmount']                       = round(($generalledger_arr[$i]['transactionAmount']/$generalledger_arr[$i]['partyExchangeRate']),$generalledger_arr[$i]['partyCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['projectID'] = $double_entry['gl_detail'][$i]['projectID'];
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
                $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);
            }


            foreach($JVDetails as $val){
                if($val['isBank']==1){
                    if($val['gl_type']=='Cr'){
                        $transactionType=2;
                        $transactionAmount=$val['creditAmount'];
                    }else{
                        $transactionType=1;
                        $transactionAmount=$val['debitAmount'];
                    }
                    $bankledger['documentDate']=$double_entry['master_data']['JVdate'];
                    $bankledger['transactionType']=$transactionType;
                    $bankledger['transactionCurrencyID']=$double_entry['master_data']['transactionCurrencyID'];
                    $bankledger['transactionCurrency']=$double_entry['master_data']['transactionCurrency'];
                    $bankledger['transactionExchangeRate']=$double_entry['master_data']['transactionExchangeRate'];
                    $bankledger['transactionAmount']=$transactionAmount;
                    $bankledger['transactionCurrencyDecimalPlaces']=$double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                    $bankledger['bankCurrencyID']=$val['bankCurrencyID'];
                    $bankledger['bankCurrency']=$val['bankCurrencyCode'];
                    $bankledger['bankCurrencyExchangeRate']=$val['bankCurrencyExchangeRate'];
                    $bankledger['bankCurrencyAmount']=$val['bankCurrencyAmount'];
                    $bankledger['bankCurrencyDecimalPlaces']=$val['bankCurrencyDecimalPlaces'];
                    $bankledger['memo']=$val['description'];
                    $bankledger['bankName']=$val['bankName'];
                    $bankledger['bankGLAutoID']=$val['GLAutoID'];
                    $bankledger['bankSystemAccountCode']=$val['systemGLCode'];
                    $bankledger['bankGLSecondaryCode']=$val['GLCode'];
                    $bankledger['documentMasterAutoID']=$val['JVMasterAutoId'];
                    $bankledger['documentType']='JV';
                    $bankledger['documentSystemCode']=$double_entry['master_data']['JVcode'];
                    $bankledger['createdPCID']=$this->common_data['current_pc'];
                    $bankledger['companyID']=$val['companyID'];
                    $bankledger['companyCode']=$val['companyCode'];
                    $bankledger['segmentID']=$val['segmentID'];
                    $bankledger['segmentCode']=$val['segmentCode'];
                    $bankledger['createdUserID']=current_userID();
                    $bankledger['createdDateTime']=current_date();
                    $bankledger['createdUserName']=current_user();
                    $this->db->insert('srp_erp_bankledger', $bankledger);

                }
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return true;
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('s', 'Journal entry Approval Successfully.');
            return true;
        }
    }

    function re_open_journal_entry()
    {
        $data = array(
            'isDeleted' => 0,
        );
        $this->db->where('JVMasterAutoId', trim($this->input->post('JVMasterAutoId')));
        $this->db->update('srp_erp_jvmaster', $data);
        $this->session->set_flashdata('s', 'Re Opened Successfully.');
        return true;
    }

    function get_recurringjv_details()
    {
        $this->db->select('*');
        $this->db->where('RJVMasterAutoId', $this->input->post('RJVMasterAutoId'));
        $this->db->from('srp_erp_recurringjvdetail');
        $data['detail'] = $this->db->get()->result_array();
        return $data;
    }

    function add_recarring_details()
    {
        $jvMasterID = $this->input->post('jvMasterID');
        $RJVDetailAutoID = $this->input->post('JVDetailAutoID');
        if(!empty($RJVDetailAutoID)){
            if ($jvMasterID) {
                foreach ($RJVDetailAutoID as $val) {
                    $this->db->select('srp_erp_recurringjvdetail.*,srp_erp_recurringjvmaster.RJVcode');
                    $this->db->where('RJVDetailAutoID', $val);
                    $this->db->join('srp_erp_recurringjvmaster', 'srp_erp_recurringjvdetail.RJVMasterAutoId = srp_erp_recurringjvmaster.RJVMasterAutoId');
                    $this->db->from('srp_erp_recurringjvdetail');
                    $result = $this->db->get()->row_array();

                    $data['JVMasterAutoId'] = $jvMasterID;
                    $data['rjvSystemCode'] = $result['RJVcode'];
                    $data['projectID'] = $result['projectID'];
                    $data['projectExchangeRate'] = $result['projectExchangeRate'];
                    $data['recurringjvMasterAutoId'] = $result['RJVMasterAutoId'];
                    $data['recurringjvDetailAutoID'] = $result['RJVDetailAutoID'];
                    $data['type'] = $result['type'];
                    $data['segmentID'] = $result['segmentID'];
                    $data['segmentCode'] = $result['segmentCode'];
                    $data['gl_type'] = $result['gl_type'];
                    $data['GLAutoID'] = $result['GLAutoID'];
                    $data['systemGLCode'] = $result['systemGLCode'];
                    $data['GLCode'] = $result['GLCode'];
                    $data['GLDescription'] = $result['GLDescription'];
                    $data['GLType'] = $result['GLType'];
                    $data['description'] = $result['description'];
                    $data['debitAmount'] = $result['debitAmount'];
                    $data['debitCompanyLocalAmount'] = $result['debitCompanyLocalAmount'];
                    $data['debitCompanyReportingAmount'] = $result['debitCompanyReportingAmount'];
                    $data['creditAmount'] = $result['creditAmount'];
                    $data['creditCompanyLocalAmount'] = $result['creditCompanyLocalAmount'];
                    $data['creditCompanyReportingAmount'] = $result['creditCompanyReportingAmount'];
                    $data['companyID'] = $result['companyID'];
                    $data['companyCode'] = $result['companyCode'];
                    $results = $this->db->insert('srp_erp_jvdetail', $data);
                }
                if ($results) {
                    /*$rjvMasterIds = $this->input->post('rjvMasterIds');
                    $str = "$rjvMasterIds";
                    $masterid = explode(",", $str);
                    foreach ($masterid as $valu) {
                        $this->db->select('*');
                        $this->db->where('documentID', 'RJV');
                        $this->db->where('documentSystemCode', $valu);
                        $this->db->from('srp_erp_documentattachments');
                        $rjvAttachments = $this->db->get()->row_array();
                    }*/
                    return array('s', 'Detail saved successfully');
                } else {
                    return array('e', 'error in saving details');
                }
            }else{
                return array('e', 'JV Master Id Required');
            }
        }else{
            return array('e', 'Select Recurring JV');
        }

    }

    function delete_Journal_entry_recurring_detail()
    {
        $this->db->where('JVMasterAutoId', $this->input->post('JVMasterAutoId'));
        $this->db->where('recurringjvMasterAutoId', $this->input->post('recurringjvMasterAutoId'));
        $this->db->delete('srp_erp_jvdetail');
        $this->session->set_flashdata('s', 'Journal entry : deleted Successfully.');
        return true;
    }
    function fetch_signaturelevel_journal_voucher()
    {
        $this->db->select('approvalSignatureLevel');
        $this->db->where('companyID', current_companyID());
        $this->db->where('documentID', 'JV');
        $this->db->from('srp_erp_documentcodemaster ');
        return $this->db->get()->row_array();


    }
}