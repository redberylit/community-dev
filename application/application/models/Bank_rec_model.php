<?php

class Bank_rec_model extends ERP_Model
{

    function viewbankrec_detail()
    {
        $GLAutoID = trim($this->input->post('GLAutoID'));
        $bankRecAutoID = trim($this->input->post('bankRecAutoID'));
        $master = $this->get_bank_rec_header();
        $startdate = $master['year'] . '-' . $master['month'] . '-01';
        $endDate = $master['year'] . '-' . $master['month'] . '-31';
        $master['bankRecAsOf'];
        /*     $sql="SELECT transactionType,documentDate,documentSystemCode,partyCode,partyName,chequeNo,chequeDate,bankCurrencyAmount,bankLedgerAutoID,bankCurrencyDecimalPlaces,clearedYN,memo FROM srp_erp_bankledger WHERE bankGLAutoID = {$GLAutoID} AND (clearedYN = 0 OR bankRecMonthID={$bankRecAutoID}) AND (documentDate between '{$startdate}' AND '{$endDate}') ";*/
        $date_format_policy = date_format_policy();
        $bnkRecAsOf = $master['bankRecAsOf'];
        $bankRecAsOf = input_format_date($bnkRecAsOf, $date_format_policy);
        $sql = "SELECT transactionType,documentDate,documentSystemCode,partyCode,partyName,chequeNo,chequeDate,bankCurrencyAmount,bankLedgerAutoID,bankCurrencyDecimalPlaces,clearedYN,memo FROM srp_erp_bankledger WHERE bankGLAutoID = {$GLAutoID} AND (clearedYN = 0 OR bankRecMonthID={$bankRecAutoID}) AND documentDate <= '{$bankRecAsOf}' order by documentDate asc";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function get_opening_balance_bank_rec()
    {
        $GLAutoID = trim($this->input->post('GLAutoID'));
        $data = $this->db->query("Select receipt,payment,receipt-payment as balance from(SELECT SUM( IF ( transactionType = 2, bankcurrencyAmount, 0 ) ) payment, SUM( IF ( transactionType = 1, bankcurrencyAmount, 0 ) ) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID =d.bankRecMonthID WHERE confirmedYN = 1 AND d.bankGLAutoID ={$GLAutoID} and d.bankRecMonthID is not NULL AND clearedYN=1 )tt")->row_array();
        return $data['balance'];
    }

    function bank_rec_confirm()
    {
        $bankRecAutoID = $this->input->post('bankRecAutoID');
        $GLAutoID = $this->input->post('GLAutoID');
        $this->load->library('approvals');
        $master = $this->get_bank_rec_header();
        $approvals_status = $this->approvals->CreateApproval('BRC', $bankRecAutoID, $master['bankRecPrimaryCode'], 'Bank Reconsiliation', 'srp_erp_bankrecmaster', 'bankRecAutoID');
        $openingBalance = $this->get_opening_balance_bank_rec();
        if (empty($openingBalance)) {
            $openingBalance = 0;
        }
        $closingquery = $this->db->query("SELECT SUM(IF(transactionType = 2, bankcurrencyAmount, 0)) payment, SUM(IF(transactionType = 1, bankcurrencyAmount, 0)) as receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankGLAutoID = d.bankGLAutoID WHERE confirmedYN = 0 AND bankRecAutoID={$bankRecAutoID} AND  clearedYN=1")->row_array();
        $closingBalance = $closingquery['receipt'] - $closingquery['payment']; /* 'confirmedDate'      => $this->common_data['current_date'], 'confirmedByEmpID'   => $this->common_data['current_userID'], 'confirmedByName'    => $this->common_data['current_user'],*/
        if ($approvals_status==1) {
            $data = array('confirmedYN' => 1, 'openingBalance' => $openingBalance, 'closingBalance' => $closingBalance);
            $this->db->where('bankRecAutoID', $bankRecAutoID);
            $this->db->update('srp_erp_bankrecmaster', $data);
            $this->session->set_flashdata('s', 'Approvals Created Successfully.');
            return true;
        } else if($approvals_status==3){
            $this->session->set_flashdata('w', 'There are no users exist to perform approval for this document.');
            return true;
        } else {
            $this->session->set_flashdata('e', 'Document confirmation failed.');
            return false;
        }
    }

    function get_bank_rec_header()
    {
        $convertFormat = convert_date_format_sql();
        $bankRecAutoID = trim($this->input->post('bankRecAutoID'));
        $data = $this->db->query("SELECT *,CASE WHEN confirmedYN = 2 || confirmedYN = 3 THEN \" - \" WHEN confirmedYN = 1 THEN CONCAT_WS(' on ',IF( LENGTH( confirmedByname ), confirmedByname, '-' ),IF( LENGTH( DATE_FORMAT(confirmedDate, '%d-%m-%Y %h:%i:%s' ) ), DATE_FORMAT(confirmedDate, '%d-%m-%Y %h:%i:%s' ), NULL ) ) ELSE \"-\" END confirmedYNn,DATE_FORMAT(bankRecAsOf,'$convertFormat') AS bankRecAsOf,DATE_FORMAT(approvedDate,'.$convertFormat. %h:%i:%s') AS approvedDate FROM srp_erp_bankrecmaster where bankRecAutoID={$bankRecAutoID} ")->row_array();
        return $data;
    }

    function getconfirmationdetails()
    {
        $bankRecAutoID = trim($this->input->post('bankRecAutoID'));
        $data = $this->db->query("SELECT transactionType,documentDate,documentSystemCode,partyCode,partyName,chequeNo,chequeDate,bankCurrencyAmount,bankLedgerAutoID,bankCurrencyDecimalPlaces,clearedYN FROM srp_erp_bankledger WHERE  bankRecMonthID={$bankRecAutoID} AND clearedYN = 1 ")->result_array();
        return $data;
    }

    function getunconfirmedDetails()
    {
      $companyID=current_companyID();
        $bankRecAutoID = trim($this->input->post('bankRecAutoID'));
        $GLAutoID = trim($this->input->post('GLAutoID'));
        $master = $this->get_bank_rec_header();

        $startdate = $master['year'] . '-' . $master['month'] . '-01';
        $endDate = $master['year'] . '-' . $master['month'] . '-31';
      $bnkRecAsOf = $master['bankRecAsOf'];
      $date_format_policy = date_format_policy();
      $bankRecAsOf = input_format_date($bnkRecAsOf, $date_format_policy);

 /*     $openingbalance = $this->db->query( "SELECT receipt, payment, receipt - payment AS balance FROM ( SELECT SUM( IF ( transactionType = 2, bankcurrencyAmount, 0 ) ) payment, SUM( IF ( transactionType = 1, bankcurrencyAmount, 0 ) ) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID = d.bankRecMonthID WHERE m.companyID = {$companyID} AND documentDate <= '{$asOfDate}' AND d.bankGLAutoID = {$GLAutoID} AND ( d.bankRecMonthID IS  NULL OR bankRecMonthID IN ( SELECT bankRecAutoID FROM srp_erp_bankrecmaster WHERE bankGLAutoID = {$GLAutoID} AND bankRecAsOf <= '{$asOfDate}' ) ) ) tt;")->row_array();*/

        $data = $this->db->query("SELECT transactionType, documentDate, documentSystemCode, partyCode, partyName, chequeNo, chequeDate, bankCurrencyAmount, bankLedgerAutoID, bankCurrencyDecimalPlaces, clearedYN FROM srp_erp_bankledger WHERE bankGLAutoID = {$GLAutoID} AND documentDate <= '{$bankRecAsOf}' AND     (clearedYN = 0 OR bankRecMonthID != {$bankRecAutoID} AND bankRecMonthID NOT IN ( SELECT bankRecAutoID FROM `srp_erp_bankrecmaster` WHERE companyID = {$companyID} AND bankGLAutoID = {$GLAutoID} AND bankRecAsOf <= '{$bankRecAsOf}' )) ORDER BY documentDate ASC")->result_array();
/*        $data = $this->db->query("SELECT transactionType,documentDate,documentSystemCode,partyCode,partyName,chequeNo,chequeDate,bankCurrencyAmount,bankLedgerAutoID,bankCurrencyDecimalPlaces,clearedYN FROM srp_erp_bankledger WHERE clearedYN = 0 AND bankGLAutoID = {$GLAutoID} AND clearedYN = 0 AND (documentDate <= '{$bankRecAsOf}')  order by documentDate asc ")->result_array();*/
        return $data;
    }

    function getopeningbalancebyrectautoID()
    {
        $bankRecAutoID = trim($this->input->post('bankRecAutoID'));
        $data = $this->db->query("Select receipt,payment,openingBalance,receipt-payment as closingbalance from(SELECT m.openingBalance, SUM( IF (transactionType = 2, bankcurrencyAmount, 0 ) ) payment, SUM( IF (transactionType = 1, bankcurrencyAmount, 0 ) ) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID =d.bankRecMonthID WHERE m.bankRecAutoID={$bankRecAutoID} and d.clearedYN=1 )tt;")->row_array();
        return $data['openingBalance'];
    }

    function save_bank_rec_header()
    {

        $date_format_policy = date_format_policy();
        $dateAsOf = $this->input->post('bankRecAsOf');
        $bankRecAsOf = input_format_date($dateAsOf, $date_format_policy);

        $data['createdDateTime'] = $this->common_data['current_date'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $month = explode('-', trim($bankRecAsOf));
        $data['year'] = $month[0];
        $data['month'] = $month[1];
        $data['bankRecAsOf'] = $bankRecAsOf;
        $data['description'] = $this->input->post('description');
        $data['companyID'] = current_companyID();
        $data['bankGLAutoID'] = $this->input->post('bankGLAutoID');
        $data['createdBy'] = $this->common_data['current_user'];
        $data['bankRecPrimaryCode'] = $this->sequence->sequence_generator('BRC');
        $this->db->trans_start();
        $this->db->insert('srp_erp_bankrecmaster', $data);
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', 'Saved Failed ');
            $this->db->trans_rollback();
            return array('status' => false);
        } else {
            $this->session->set_flashdata('s', 'Saved Successfully.');
            $this->db->trans_commit();
            return array('status' => true);
        }
    }

    function save_bank_rec_details($clearedYN, $bankRecAutoID)
    {
        $datas = array("bankRecMonthID" => NULL, "clearedBy" => NULL, "clearedAmount" => 0, "clearedDate" => NULL, "clearedYN" => 0, "clearedBy" => NULL);
        $this->db->update('srp_erp_bankledger', $datas, array('bankRecMonthID' => $bankRecAutoID));
        $current_user = $this->common_data['current_user'];
        $current_date = current_date();
        $data = array();
        if (!empty($clearedYN)) {
            $commaList = implode(', ', $clearedYN);
            $bankAmount = $this->db->query("select bankLedgerAutoID,bankCurrencyAmount from srp_erp_bankledger where bankLedgerAutoID IN ($commaList); ")->result_array();
            for ($i = 0; $i < count($clearedYN); $i++) {
                $key = array_search($clearedYN[$i], array_column($bankAmount, 'bankLedgerAutoID'));
                if (array_key_exists($key, $bankAmount)) {
                    $Amount = !empty($bankAmount[$key]["bankCurrencyAmount"]) ? $bankAmount[$key]["bankCurrencyAmount"] : 0;
                    array_push($data, array("bankLedgerAutoID" => $clearedYN[$i], "bankrecmonthID" => $bankRecAutoID, "clearedYN" => 1, "clearedDate" => $current_date, "clearedBy" => $current_user, "clearedAmount" => $Amount));
                }
            }
        }
        if (!empty($data)) {
            $update = $this->db->update_batch('srp_erp_bankledger', $data, 'bankLedgerAutoID');
        }
        if ($update) {
            $this->session->set_flashdata('s', 'Bank Reconciliation : Draft Successfully.');
            return true;
        } else {
            $this->session->set_flashdata('e', 'Bank Reconciliation : Updated failed .');
            return true;
        }
    }

    function getGLdetails($GLAutoID)
    {
        $data = $this->db->query("SELECT * FROM srp_erp_chartofaccounts WHERE GLAutoID={$GLAutoID}")->row_array();
        return $data;

    }


    function save_bank_rec_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_code = trim($this->input->post('bankRecAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        $comments = trim($this->input->post('comments'));

        $approvals_status = $this->approvals->approve_document($system_code, $level_id, $status, $comments, 'BRC');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return true;
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('s', 'Bank Rec Approval Successfully.');
            return true;
        }
    }

    function xeditable_update($tableName, $pkColumn)
    {
        $column = $this->input->post('name');
        $value = $this->input->post('value');
        $pk = $this->input->post('pk');


        $table = $tableName;
        $data = array($column => $value);
        $this->db->where($pkColumn, $pk);
        $result = $this->db->update($table, $data);
        echo $this->db->last_query();
        return $result;
    }

    function get_glcode_currency($GLAutoID)
    {
        $data = $this->db->query("SELECT bankCurrencyID,CurrencyCode as bankCurrencyCode,bankCurrencyDecimalplaces FROM `srp_erp_chartofaccounts` LEFT JOIN  `srp_erp_currencymaster` on bankCurrencyID=currencyID WHERE  GLAutoID={$GLAutoID}")->row_array();
        return $data;
    }

    function getexchangerate($masterCurrencyID, $subCurrencyID)
    {
        $companyID = current_companyID();
        $data = $this->db->query("select masterCurrencyCode,subCurrencyCode,conversion from srp_erp_companycurrencyconversion where masterCurrencyID='{$subCurrencyID}' and subCurrencyID='{$masterCurrencyID}' AND companyID=$companyID")->row_array();
        return $data;
    }

    function bank_transfer_master($bankTransferAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $data = $this->db->query("SELECT fromCurrency.bankCurrencyID as fromcurrencyID,transferedDate as transferedDatebank, toCurrency.bankCurrencyID as tocurrencyID, srp_erp_banktransfer.*,DATE_FORMAT(transferedDate,'$convertFormat') AS transferedDate ,DATE_FORMAT(srp_erp_banktransfer.approvedDate,'.$convertFormat. %h:%i:%s') AS approvedDate, fromCurrency.bankCurrencyCode as fromcurrency, toCurrency.bankCurrencyCode as tocurrency, fromCurrency.GLDescription as bankfrom, toCurrency.GLDescription as bankto,fromCurrency.bankCurrencyDecimalPlaces AS fromDecimalPlaces, toCurrency.bankCurrencyDecimalPlaces AS toDecimalPlaces,fromCurrency.systemAccountCode as fromSystemAccountCode,fromCurrency.GLSecondaryCode as fromGLSecondaryCode, toCurrency.systemAccountCode as toCurrencySystemAccountCode,toCurrency.GLSecondaryCode as toCurrencyGLSecondaryCode, fromCurrency.GLDescription as fromGLDescription, fromCurrency.subCategory as fromSubCategory, toCurrency.GLDescription as toGLDescription, toCurrency.subCategory as toSubCategory,srp_erp_currencymaster.CurrencyCode as CurrencyCode,DATE_FORMAT(chequeDate,'$convertFormat') AS chequeDate,CASE WHEN srp_erp_banktransfer.confirmedYN = 2 || srp_erp_banktransfer.confirmedYN = 3 THEN \" - \" WHEN srp_erp_banktransfer.confirmedYN = 1 THEN CONCAT_WS(' on ',IF( LENGTH( srp_erp_banktransfer.confirmedbyName ), srp_erp_banktransfer.confirmedbyName, '-' ),IF(LENGTH( DATE_FORMAT( srp_erp_banktransfer.confirmedDate, '%d-%m-%Y %h:%i:%s'  ) ),DATE_FORMAT( srp_erp_banktransfer.confirmedDate, '%d-%m-%Y %h:%i:%s'  ),NULL ) ) ELSE \"-\" 
END confirmedYNn FROM srp_erp_banktransfer LEFT JOIN srp_erp_chartofaccounts AS fromCurrency ON fromBankGLAutoID = fromCurrency.GLAutoID LEFT JOIN srp_erp_chartofaccounts AS toCurrency ON toBankGLAutoID = toCurrency.GLAutoID LEFT JOIN srp_erp_currencymaster  ON fromBankCurrencyID = srp_erp_currencymaster.currencyID WHERE bankTransferAutoID = {$bankTransferAutoID}")->row_array();
        return $data;
    }

    function bank_transfer_confirmation()
    {

        $bankTransferAutoID = $this->input->post('bankTransferAutoID');
        $this->load->library('approvals');
        $master = $this->bank_transfer_master($bankTransferAutoID);

        $approvals_status = $this->approvals->CreateApproval('BT', $bankTransferAutoID, $master['bankTransferCode'], 'Bank Transfer', 'srp_erp_banktransfer', 'bankTransferAutoID',0,$master['transferedDatebank']);
        if ($approvals_status==1) {
            $data = array(
                'confirmedYN' => 1,
                'confirmedDate' => $this->common_data['current_date'],
                'confirmedByEmpID' => $this->common_data['current_userID'],
                'confirmedByName' => $this->common_data['current_user']
            );
            $this->db->where('bankTransferAutoID', $bankTransferAutoID);
            $this->db->update('srp_erp_banktransfer', $data);
            $this->session->set_flashdata('s', 'Approvals Created Successfully.');
            return true;
        } else if($approvals_status==3){
            $this->session->set_flashdata('w', 'There are no users exist to perform approval for this document.');
            return true;
        } else {
            $this->session->set_flashdata('e', 'Document confirmation failed.');
            return false;
        }
    }

    function confirm_bank_approval()
    {
        $companyID = current_companyID();
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_code = trim($this->input->post('bankTransferAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        $comments = trim($this->input->post('comments'));
        $master = $this->bank_transfer_master($system_code);
        $date_format_policy = date_format_policy();
        $transferedDate = $master['transferedDate'];
        $master['transferedDate'] = input_format_date($transferedDate, $date_format_policy);
        $data['exchange'] = 1 / $master['exchangeRate'];
        $data = array(array('companyID' => $master['companyID'],
            'companyCode' => $master['companyCode'],
            'documentDate' => $master['transferedDate'],
            'transactionType' => 2,
            'documentType' => 'BT',
            'transactionCurrencyID' => $master['fromBankCurrencyID'],
            'transactionCurrency' => $master['fromcurrency'],
            'transactionExchangeRate' => 1,
            'transactionAmount' => $master['transferedAmount'],
            'transactionCurrencyDecimalPlaces' => $master['fromDecimalPlaces'],
            'bankCurrencyID' => $master['fromBankCurrencyID'],
            'bankCurrency' => $master['fromcurrency'],
            'bankCurrencyExchangeRate' => 1,
            'bankCurrencyAmount' => $master['transferedAmount'],
            'bankCurrencyDecimalPlaces' => $master['fromDecimalPlaces'],
            'memo' => $master['narration'],
            'bankName' => $master['bankfrom'],
            'bankGLAutoID' => $master['fromBankGLAutoID'],
            'bankSystemAccountCode' => $master['fromSystemAccountCode'],
            'bankGLSecondaryCode' => $master['fromGLSecondaryCode'],
            'documentMasterAutoID' => $master['bankTransferAutoID'],
            'documentSystemCode' => $master['bankTransferCode'],
            'createdPCID' => $this->common_data['current_pc'],
            'createdUserID' => $this->common_data['current_userID'],
            'createdDateTime' => $this->common_data['current_date'],
            'createdUserName' => $this->common_data['current_user']),

            array('companyID' => $master['companyID'],
                'companyCode' => $master['companyCode'],
                'documentDate' => $master['transferedDate'],
                'transactionType' => 1,
                'documentType' => 'BT',
                'transactionCurrencyID' => $master['fromBankCurrencyID'],
                'transactionCurrency' => $master['fromcurrency'],
                'transactionExchangeRate' => 1,
                'transactionAmount' => $master['transferedAmount'],
                'transactionCurrencyDecimalPlaces' => $master['toDecimalPlaces'],
                'bankCurrencyID' => $master['toBankCurrencyID'],
                'bankCurrency' => $master['tocurrency'],
                'bankCurrencyExchangeRate' => $data['exchange'],
                'bankCurrencyAmount' => $master['transferedAmount'] * $master['exchangeRate'],
                'bankCurrencyDecimalPlaces' => $master['toDecimalPlaces'],
                'memo' => $master['narration'],
                'bankName' => $master['bankto'],
                'bankGLAutoID' => $master['toBankGLAutoID'],
                'bankSystemAccountCode' => $master['toCurrencySystemAccountCode'],
                'bankGLSecondaryCode' => $master['toCurrencyGLSecondaryCode'],
                'documentMasterAutoID' => $master['bankTransferAutoID'],
                'documentSystemCode' => $master['bankTransferCode'],
                'createdPCID' => $this->common_data['current_pc'],
                'createdUserID' => $this->common_data['current_userID'],
                'createdDateTime' => $this->common_data['current_date'],
                'createdUserName' => $this->common_data['current_user']));
        $transferedDate = format_date($master['transferedDate']);
        $orderdate = explode('-', $transferedDate);
        $month = $orderdate[1];
        $year = $orderdate[0];
        $localdecimal = fetch_currency_desimal($master['companyLocalCurrency']);
        $reportingdecimal = fetch_currency_desimal($master['companyReportingCurrency']);
        //echo '<pre>';print_r($master); echo '</pre>'; die();
        /*localexchange*/
        /*if ($master['fromCurrency'] == $master['companyLocalCurrencyID']) {
          $companyLocalExchangeRate = 1 / $master['exchangeRate'];
        } else {
            $default_currency = currency_conversionID($master['fromCurrency'], $master['companyLocalCurrencyID']);
         $companyLocalExchangeRate = $default_currency['conversion'];
        }*/
        $companyLocalExchangeRate =  $master['companyLocalExchangeRate'];
        $companyReportingexchangeRate =  $master['companyReportingExchangeRate'];
        /*reporting Exchange*/
        /*if ($master['fromCurrency'] == $master['companyReportingCurrencyID']) {
            $companyReportingexchangeRate = 1 / $master['exchangeRate'];
        } else {
            $report = currency_conversionID($master['fromcurrencyID'], $master['companyReportingCurrencyID']);
            $companyReportingexchangeRate = $report['conversion'];
        }*/

        $data2 = array(array('documentCode' => 'BT',
            'documentMasterAutoID' => $master['bankTransferAutoID'],
            'documentSystemCode' => $master['bankTransferCode'],
            'documentType' => 'BT',
            'documentDate' => $master['transferedDate'],
            'documentYear' => $year,
            'documentMonth' => $month,
            'documentNarration' => $master['narration'],
            'GLAutoID' => $master['fromBankGLAutoID'],
            'systemGLCode' => $master['fromSystemAccountCode'],
            'GLCode' => $master['fromGLSecondaryCode'],
            'GLDescription' => $master['fromGLDescription'],
            'GLType' => $master['fromSubCategory'],
            'amount_type' => 'cr',
            'transactionCurrencyID' => $master['fromBankCurrencyID'],
            'transactionCurrency' => $master['fromcurrency'],
            'transactionExchangeRate' => 1,
            'transactionAmount' => -1 * abs($master['transferedAmount']),
            'transactionCurrencyDecimalPlaces' => $master['fromDecimalPlaces'],
            'companyLocalCurrencyID' => $master['companyLocalCurrencyID'],
            'companyLocalCurrency' => $master['companyLocalCurrency'],
            'companyLocalExchangeRate' => $companyLocalExchangeRate,
            'companyLocalAmount' => -1 * abs($master['transferedAmount'] / $companyLocalExchangeRate),
            'companyLocalCurrencyDecimalPlaces' => $localdecimal,
            'companyReportingCurrencyID' => $master['companyReportingCurrencyID'],
            'companyReportingCurrency' => $master['companyReportingCurrency'],
            'companyReportingExchangeRate' => $companyReportingexchangeRate,
            'companyReportingAmount' => -1 * abs($master['transferedAmount'] / $companyReportingexchangeRate),
            'companyReportingCurrencyDecimalPlaces' => $reportingdecimal,
            'confirmedByEmpID' => $master['confirmedByEmpID'],
            'confirmedByName' => $master['confirmedByName'],
            'confirmedDate' => $master['confirmedDate'],
            'approvedDate' => $this->common_data['current_date'],
            'approvedbyEmpID' => $this->common_data['current_userID'],
            'approvedbyEmpName' => $this->common_data['current_user'],
            'companyID' => $master['companyID'],
            'companyCode' => $master['companyCode'],
            'createdUserGroup' => $this->common_data['user_group'],
            'createdPCID' => $this->common_data['current_pc'],
            'createdUserID' => $this->common_data['current_userID'],
            'createdDateTime' => $this->common_data['current_date'],
            'createdUserName' => $this->common_data['current_user']),

            array('documentCode' => 'BT',
                'documentMasterAutoID' => $master['bankTransferAutoID'],
                'documentSystemCode' => $master['bankTransferCode'],
                'documentType' => 'BT',
                'documentDate' => $master['transferedDate'],
                'documentYear' => $year,
                'documentMonth' => $month,
                'documentNarration' => $master['narration'],
                'GLAutoID' => $master['toBankGLAutoID'],
                'systemGLCode' => $master['toCurrencySystemAccountCode'],
                'GLCode' => $master['toCurrencyGLSecondaryCode'],
                'GLDescription' => $master['toGLDescription'],
                'GLType' => $master['toSubCategory'],
                'amount_type' => 'dr',
                'transactionCurrencyID' => $master['fromBankCurrencyID'],
                'transactionCurrency' => $master['fromcurrency'],
                'transactionExchangeRate' => 1,
                'transactionAmount' => $master['transferedAmount'],
                'transactionCurrencyDecimalPlaces' => $master['fromDecimalPlaces'],
                'companyLocalCurrencyID' => $master['companyLocalCurrencyID'],
                'companyLocalCurrency' => $master['companyLocalCurrency'],
                'companyLocalExchangeRate' => $companyLocalExchangeRate,
                'companyLocalAmount' => $master['transferedAmount'] / $companyLocalExchangeRate,
                'companyLocalCurrencyDecimalPlaces' => $localdecimal,
                'companyReportingCurrencyID' => $master['companyReportingCurrencyID'],
                'companyReportingCurrency' => $master['companyReportingCurrency'],
                'companyReportingExchangeRate' => $companyReportingexchangeRate,
                'companyReportingAmount' => $master['transferedAmount'] / $companyReportingexchangeRate,
                'companyReportingCurrencyDecimalPlaces' => $reportingdecimal,
                'confirmedByEmpID' => $master['confirmedByEmpID'],
                'confirmedByName' => $master['confirmedByName'],
                'confirmedDate' => $master['confirmedDate'],
                  'approvedDate' => $this->common_data['current_date'],
                  'approvedbyEmpID' => $this->common_data['current_userID'],
                  'approvedbyEmpName' => $this->common_data['current_user'],
                'companyID' => $master['companyID'],
                'companyCode' => $master['companyCode'],
                'createdUserGroup' => $this->common_data['user_group'],
                'createdPCID' => $this->common_data['current_pc'],
                'createdUserID' => $this->common_data['current_userID'],
                'createdDateTime' => $this->common_data['current_date'],
                'createdUserName' => $this->common_data['current_user']));

        $approvals_status = $this->approvals->approve_document($system_code, $level_id, $status, $comments, 'BT');

        $levelNo=$this->db->query("select max(levelNo) as levelNo from srp_erp_approvalusers WHERE Status=1 AND companyID={$companyID} AND documentID='BT'  ")->row_array();

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return true;
        } else {
            if (trim($this->input->post('status')) == 1) {
                if($levelNo['levelNo']==$level_id){
                $this->db->insert_batch('srp_erp_bankledger', $data);
                $this->db->insert_batch('srp_erp_generalledger', $data2);
                }
            }
            $this->db->trans_commit();
            $this->session->set_flashdata('s', 'Bank Transfer Approval Successfully.');
            return true;
        }
    }

    function get_bank_ledger_details($bankGLAutoID, $from, $to)
    {

        $filter = "";
        if ($from != 'false') {
            $filter .= ' AND documentDate >="' . $from . '"';

            $qry2 = "SELECT sum(IF(transactionType = 1, bankCurrencyAmount, 0)) as bankCurrencyAmount, sum(IF(transactionType = 2, bankCurrencyAmount*-1, 0)) as deduct, sum(IF(transactionType = 1, bankCurrencyAmount, 0))+ sum(IF(transactionType = 2, bankCurrencyAmount*-1, 0)) as total, documentDate, memo, chequeNo, documentSystemCode, transactionType, partyType, partyCode, partyName, bankCurrency, bankCurrencyDecimalPlaces, bankCurrencyAmount AS amount, FORMAT(bankCurrencyAmount, bankCurrencyDecimalPlaces) AS bankCurrencyAmount, IF(m.bankRecMonthID != '', clearedYN, 0) AS clearedYN, m.bankRecMonthID FROM srp_erp_bankledger m WHERE m.bankGLAutoID = {$bankGLAutoID} AND documentDate < '{$from}' group by bankGLAutoID ORDER BY documentDate ASC";
            $openingbalance = $this->db->query($qry2)->row_array();
        }
        if ($to != 'false') {
            $filter .= ' AND documentDate <="' . $to . '"';
        }
        $qry = "SELECT documentDate,memo,chequeNo, documentSystemCode, transactionType, partyType, partyCode, partyName, bankCurrency,bankCurrencyDecimalPlaces, bankCurrencyAmount as amount, FORMAT(bankCurrencyAmount, bankCurrencyDecimalPlaces) as bankCurrencyAmount, IF(m.bankRecMonthID !='',clearedYN,0) as clearedYN, m.bankRecMonthID FROM srp_erp_bankledger m WHERE m.bankGLAutoID = {$bankGLAutoID} {$filter} order by documentDate asc";
        $data = $this->db->query($qry)->result_array();


        return json_encode(array('data' => $data, 'openingbalance' => $openingbalance));


        /*return $data;*/
    }

    function bankrec_recieved_account()
    {
        $this->db->trans_begin();
        $bankGLAutoID = $this->input->post('bankGLAutoID');
        $date_format_policy = date_format_policy();
        $docutDate = $this->input->post('documentDate');
        $documentDate = input_format_date($docutDate, $date_format_policy);
        $bankAccountID = $this->input->post('bankAccountID');
        $type1 = $this->input->post('type');
        $refNo = $this->input->post('reference');
        $narration = $this->input->post('narration');
        $amount = $this->input->post('amount');
        $GLAutoID = $this->input->post('GLAutoID');

        $seg = explode('|', $this->input->post('segmentID'));
        $segmentID = $seg[0];
        $segmentCode = $seg[1];

        $documentType = '';
        if ($type1 == 1) {
            $documentType = 'RV';
        }
        if ($type1 == 2) {
            $documentType = 'PV';
        }
        $gl2 = $this->getGLdetails($bankAccountID);
        /*payment voucher*/
        $companyid=current_companyID();
        $FY = $this->db->query("SELECT * FROM srp_erp_companyfinanceperiod INNER JOIN srp_erp_companyfinanceyear ON srp_erp_companyfinanceyear.companyFinanceYearID = srp_erp_companyfinanceperiod.companyFinanceYearID WHERE dateFrom <= '{$documentDate}' AND dateTo >= '{$documentDate}' AND srp_erp_companyfinanceperiod.companyID = $companyid AND srp_erp_companyfinanceperiod.isActive = 1 AND srp_erp_companyfinanceyear.isActive = 1")->row_array();
        if (empty($FY)) {
            /*error*/
        }
        $gl = $this->getGLdetails($GLAutoID);
        $type = substr($documentType, 0, 3);

        $data['documentID'] = $documentType;
        $data['RVdate'] = $documentDate;
        $data['RVType'] = 'Direct';
        $data['referanceNo'] = $refNo;
        $data['companyFinanceYearID'] = $FY['companyFinanceYearID'];
        $data['FYBegin'] = $FY['beginingDate'];
        $data['FYEnd'] = $FY['endingDate'];
        $data['FYPeriodDateFrom'] = $FY['dateFrom'];
        $data['FYPeriodDateTo'] = $FY['dateTo'];
        $data['RVbankCode'] = $gl['bankShortCode'];
        $data['bankGLAutoID'] = $gl['GLAutoID'];
        $data['bankSystemAccountCode'] = $gl['systemAccountCode'];
        $data['bankGLSecondaryCode'] = $gl['GLSecondaryCode'];
        $data['RVbank'] = $gl['bankName'];
        $data['RVbankBranch'] = $gl['bankBranch'];
        $data['RVbankSwiftCode'] = $gl['bankSwiftCode'];
        $data['RVbankAccount'] = $gl['bankAccountNumber'];
        $data['RVbankType'] = $gl['subCategory'];
        $data['RVNarration'] = $narration;
        $data['confirmedYN'] = 1;
        $data['confirmedByEmpID'] = $this->common_data['current_user'];
        $data['confirmedByName'] = $this->common_data['current_userID'];
        $data['confirmedDate'] = $this->common_data['current_date'];
        $data['approvedYN'] = 1;
        $data['approvedbyEmpID'] = $this->common_data['current_userID'];
        $data['approvedbyEmpName'] = $this->common_data['current_user'];
        $data['approvedDate'] = $this->common_data['current_date'];
        $data['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $data['transactionCurrency'] = $gl['bankCurrencyCode'];
        $data['transactionExchangeRate'] = 1;
        $data['transactionAmount'] = $amount;
        $data['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $default_currency = currency_conversion($data['transactionCurrency'], $data['companyLocalCurrency']);
        $reporting_currency = currency_conversion($data['transactionCurrency'], $data['companyReportingCurrency']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalAmount'] = $amount / $data['companyLocalExchangeRate'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];;
        $data['companyReportingAmount'] = $amount / $data['companyReportingExchangeRate'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $data['bankCurrency'] = $gl['bankCurrencyCode'];


        $data['bankCurrencyID'] = $gl['bankCurrencyID'];
        $data['bankCurrencyExchangeRate'] = 1;
        $data['bankCurrencyAmount'] = $amount;
        $data['bankCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $data['companyID'] = $this->common_data['company_data']['company_id'];
        $data['companyCode'] = $this->common_data['company_data']['company_code'];
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdDateTime'] = $this->common_data['current_date'];
        $data['createdUserName'] = $this->common_data['current_user'];
        $data['segmentCode'] = $segmentCode;
        $data['segmentID'] = $segmentID;
        $this->load->library('sequence');
        //$data['RVcode'] = $this->sequence->sequence_generator($documentType);
        $invYear= date("Y", strtotime($data['RVdate']));
        $invMonth= date("m", strtotime($data['RVdate']));
        $data['RVcode'] = $this->sequence->sequence_generator_fin($documentType, $data['companyFinanceYearID'], $invYear, $invMonth);

        $insert = $this->db->insert('srp_erp_customerreceiptmaster', $data);
        $lastinsert_id=$this->db->insert_id();
        if ($insert) {

            $docapprove['departmentID'] = $documentType;
            $docapprove['documentID'] = $documentType;
            $docapprove['documentSystemCode'] = $lastinsert_id;
            $docapprove['documentCode'] = $data['RVcode'];
            $docapprove['documentDate'] = $documentDate;
            $docapprove['approvalLevelID'] = 1;
            $docapprove['isReverseApplicableYN'] = 1;
            $docapprove['docConfirmedDate'] = $this->common_data['current_date'];
            $docapprove['docConfirmedByEmpID'] = $this->common_data['current_user'];
            $docapprove['table_name'] = 'srp_erp_customerreceiptmaster';
            $docapprove['table_unique_field_name'] = 'receiptVoucherAutoId';
            $docapprove['approvedEmpID'] = $this->common_data['current_userID'];
            $docapprove['approvedYN'] = 1;
            $docapprove['approvedDate'] = $this->common_data['current_date'];
            $docapprove['approvedComments'] = 'Created from bank rec';
            $docapprove['approvedPC'] = $this->common_data['current_pc'];
            $docapprove['companyID'] = $this->common_data['company_data']['company_id'];
            $docapprove['companyCode'] = $this->common_data['company_data']['company_code'];

            $this->db->insert('srp_erp_documentapproved', $docapprove);


            $detail['receiptVoucherAutoId'] = $lastinsert_id;
            $detail['type'] = "GL";
            $detail['referenceNo'] = $refNo;
            $detail['GLAutoID'] = $gl2['GLAutoID'];
            $detail['systemGLCode'] = $gl2['systemAccountCode'];
            $detail['GLCode'] = $gl2['GLSecondaryCode'];
            $detail['GLDescription'] = $gl2['GLDescription'];
            $detail['GLType'] = $gl2['subCategory'];
            $detail['description'] = $narration;
            $detail['transactionAmount'] = $data['transactionAmount'];
            $detail['companyLocalAmount'] = $data['companyLocalAmount'];
            $detail['companyReportingAmount'] = $data['companyReportingAmount'];
            $detail['companyID'] = $data['companyID'];
            $detail['companyCode'] = $data['companyCode'];
            $detail['createdUserGroup'] = $data['createdUserGroup'];
            $detail['createdPCID'] = $data['createdPCID'];
            $detail['createdUserID'] = $data['createdUserID'];
            $detail['createdDateTime'] = $data['createdDateTime'];
            $detail['createdUserName'] = $data['createdUserName'];
            /*added*/
            $detail['segmentCode'] = $segmentCode;
            $detail['segmentID'] = $segmentID;

            $insertDetail = $this->db->insert('srp_erp_customerreceiptdetail', $detail);
        }
        $bankledger['documentDate'] = $documentDate;
        $bankledger['transactionType'] = $type1;
        $bankledger['transactionCurrencyID'] = $gl['bankCurrencyID'];

        $bankledger['transactionCurrency'] = $gl['bankCurrencyCode'];
        $bankledger['transactionExchangeRate'] = 1;
        $bankledger['transactionAmount'] = $amount;
        $bankledger['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $bankledger['bankCurrency'] = $gl['bankCurrencyCode'];
        $bankledger['bankCurrencyID'] = $gl['bankCurrencyID'];
        $bankledger['bankCurrencyExchangeRate'] = 1;
        $bankledger['bankCurrencyAmount'] = $amount;
        $bankledger['bankCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $bankledger['memo'] = $narration;

        $bankledger['bankName'] = $gl['bankName'];
        $bankledger['bankGLAutoID'] = $gl['GLAutoID'];
        $bankledger['bankSystemAccountCode'] = $gl['systemAccountCode'];
        $bankledger['bankGLSecondaryCode'] = $gl['GLSecondaryCode'];
        $bankledger['documentMasterAutoID'] = $detail['receiptVoucherAutoId'];
        $bankledger['documentSystemCode'] = $data['RVcode'];
        $bankledger['documentType'] = $documentType;
        $bankledger['createdPCID'] = $this->common_data['current_pc'];
        $bankledger['companyID'] = $this->common_data['company_data']['company_id'];
        $bankledger['companyCode'] = $this->common_data['company_data']['company_code'];
        $bankledger['createdUserID'] = $this->common_data['current_userID'];
        $bankledger['createdDateTime'] = $this->common_data['current_date'];;
        $bankledger['createdUserName'] = $this->common_data['current_user'];
        $bankledger['segmentCode'] = $segmentCode;
        $bankledger['segmentID'] = $segmentID;
        $bankledgerinsert = $this->db->insert('srp_erp_bankledger', $bankledger);
        $docdate = explode('-', $documentDate);
        $generalLedger[0]['documentCode'] = $documentType;
        $generalLedger[0]['documentMasterAutoID'] = $detail['receiptVoucherAutoId'];
        $generalLedger[0]['documentSystemCode'] = $data['RVcode'];
        $generalLedger[0]['documentType'] = "Direct";
        $generalLedger[0]['documentDate'] = $documentDate;
        $generalLedger[0]['documentYear'] = $docdate[0];
        $generalLedger[0]['documentMonth'] = $docdate[1];
        $generalLedger[0]['documentNarration'] = $narration;
        $generalLedger[0]['GLAutoID'] = $gl['GLAutoID'];
        $generalLedger[0]['systemGLCode'] = $gl['systemAccountCode'];
        $generalLedger[0]['GLCode'] = $gl['GLSecondaryCode'];
        $generalLedger[0]['GLDescription'] = $gl['GLDescription'];
        $generalLedger[0]['GLType'] = $gl['subCategory'];
        $generalLedger[0]['amount_type'] = 'dr';
        $generalLedger[0]['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $generalLedger[0]['companyLocalCurrencyID'] = $data['companyLocalCurrencyID'];
        $generalLedger[0]['companyReportingCurrencyID'] = $data['companyReportingCurrencyID'];
        $generalLedger[0]['transactionCurrency'] = $gl['bankCurrencyCode'];
        $generalLedger[0]['transactionExchangeRate'] = 1;
        $generalLedger[0]['transactionAmount'] = $amount;
        $generalLedger[0]['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $generalLedger[0]['companyLocalCurrency'] = $data['companyLocalCurrency'];
        $generalLedger[0]['companyLocalExchangeRate'] = $data['companyLocalExchangeRate'];
        $generalLedger[0]['companyLocalAmount'] = $data['companyLocalAmount'];
        $generalLedger[0]['companyLocalCurrencyDecimalPlaces'] = $data['companyLocalCurrencyDecimalPlaces'];
        $generalLedger[0]['companyReportingCurrency'] = $data['companyReportingCurrency'];
        $generalLedger[0]['companyReportingExchangeRate'] = $data['companyReportingExchangeRate'];
        $generalLedger[0]['companyReportingAmount'] = $data['companyReportingAmount'];
        $generalLedger[0]['companyReportingCurrencyDecimalPlaces'] = $data['companyReportingCurrencyDecimalPlaces'];

        $generalLedger[0]['confirmedByEmpID'] = $this->common_data['current_userID'];
        $generalLedger[0]['confirmedByName'] = $this->common_data['current_user'];
        $generalLedger[0]['confirmedDate'] = $this->common_data['current_date'];
        $generalLedger[0]['approvedDate'] = $this->common_data['current_date'];
        $generalLedger[0]['approvedbyEmpID'] = $this->common_data['current_userID'];
        $generalLedger[0]['approvedbyEmpName'] = $this->common_data['current_user'];
        $generalLedger[0]['companyID'] = $this->common_data['company_data']['company_id'];;
        $generalLedger[0]['companyCode'] = $this->common_data['company_data']['company_code'];;
        $generalLedger[0]['createdUserGroup'] = $this->common_data['user_group'];
        $generalLedger[0]['createdPCID'] = $this->common_data['current_pc'];
        $generalLedger[0]['createdUserID'] = $this->common_data['current_userID'];;
        $generalLedger[0]['createdDateTime'] = $this->common_data['current_date'];
        $generalLedger[0]['createdUserName'] = $this->common_data['current_user'];
        $generalLedger[0]['segmentCode'] = $segmentCode;
        $generalLedger[0]['segmentID'] = $segmentID;
        $generalLedger[1]['segmentCode'] = $segmentCode;
        $generalLedger[1]['segmentID'] = $segmentID;
        $generalLedger[1]['documentCode'] = $documentType;
        $generalLedger[1]['documentMasterAutoID'] = $detail['receiptVoucherAutoId'];
        $generalLedger[1]['documentSystemCode'] = $data['RVcode'];
        $generalLedger[1]['documentType'] = "Direct";
        $generalLedger[1]['documentDate'] = $documentDate;
        $generalLedger[1]['documentYear'] = $docdate[0];
        $generalLedger[1]['documentMonth'] = $docdate[1];
        $generalLedger[1]['documentNarration'] = $narration;
        $generalLedger[1]['GLAutoID'] = $gl2['GLAutoID'];
        $generalLedger[1]['systemGLCode'] = $gl2['systemAccountCode'];
        $generalLedger[1]['GLCode'] = $gl2['GLSecondaryCode'];
        $generalLedger[1]['GLDescription'] = $gl2['GLDescription'];
        $generalLedger[1]['GLType'] = $gl2['subCategory'];
        $generalLedger[1]['amount_type'] = 'cr';
        $generalLedger[1]['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $generalLedger[1]['transactionCurrency'] = $gl['bankCurrencyCode'];
        $generalLedger[1]['transactionExchangeRate'] = 1;
        $generalLedger[1]['transactionAmount'] = -1 * abs($amount);
        $generalLedger[1]['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $generalLedger[1]['companyLocalCurrencyID'] = $data['companyLocalCurrencyID'];
        $generalLedger[1]['companyReportingCurrencyID'] = $data['companyReportingCurrencyID'];
        $generalLedger[1]['companyLocalCurrency'] = $data['companyLocalCurrency'];
        $generalLedger[1]['companyLocalExchangeRate'] = $data['companyLocalExchangeRate'];
        $generalLedger[1]['companyLocalAmount'] = -1 * abs($data['companyLocalAmount']);
        $generalLedger[1]['companyLocalCurrencyDecimalPlaces'] = $data['companyLocalCurrencyDecimalPlaces'];
        $generalLedger[1]['companyReportingCurrency'] = $data['companyReportingCurrency'];
        $generalLedger[1]['companyReportingExchangeRate'] = $data['companyReportingExchangeRate'];
        $generalLedger[1]['companyReportingAmount'] = -1 * abs($data['companyReportingAmount']);
        $generalLedger[1]['companyReportingCurrencyDecimalPlaces'] = $data['companyReportingCurrencyDecimalPlaces'];
        $generalLedger[1]['confirmedByEmpID'] = $this->common_data['current_userID'];
        $generalLedger[1]['confirmedByName'] = $this->common_data['current_user'];
        $generalLedger[1]['confirmedDate'] = $this->common_data['current_date'];
        $generalLedger[1]['approvedDate'] = $this->common_data['current_date'];
        $generalLedger[1]['approvedbyEmpID'] = $this->common_data['current_userID'];
        $generalLedger[1]['approvedbyEmpName'] = $this->common_data['current_user'];
        $generalLedger[1]['companyID'] = $this->common_data['company_data']['company_id'];
        $generalLedger[1]['companyCode'] = $this->common_data['company_data']['company_code'];
        $generalLedger[1]['createdUserGroup'] = $this->common_data['user_group'];
        $generalLedger[1]['createdPCID'] = $this->common_data['current_pc'];
        $generalLedger[1]['createdUserID'] = $this->common_data['current_userID'];
        $generalLedger[1]['createdDateTime'] = $this->common_data['current_date'];
        $generalLedger[1]['createdUserName'] = $this->common_data['current_user'];


        $this->db->insert_batch('srp_erp_generalledger', $generalLedger);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('e', 'Failed. please try again');
            echo json_encode(true);
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('s', 'Records Inserted Successfully.');
            echo json_encode(true);
        }

    }

    function bankrec_payment_account()
    {
        $this->db->trans_begin();
        $bankGLAutoID = $this->input->post('bankGLAutoID');
        $date_format_policy = date_format_policy();
        $docutDate = $this->input->post('documentDate');
        $documentDate = input_format_date($docutDate, $date_format_policy);
        $bankAccountID = $this->input->post('bankAccountID');
        $type1 = $this->input->post('type');
        $refNo = $this->input->post('reference');
        $narration = $this->input->post('narration');
        $amount = $this->input->post('amount');
        $GLAutoID = $this->input->post('GLAutoID');
        $seg = explode('|', $this->input->post('segmentID'));
        $segmentID = $seg[0];
        $segmentCode = $seg[1];
        $documentType = '';
        if ($type1 == 1) {
            $documentType = 'RV';
        }
        if ($type1 == 2) {
            $documentType = 'PV';
        }
        /*payment voucher*/
        $companyid=current_companyID();
        $FY = $this->db->query("SELECT * FROM srp_erp_companyfinanceperiod INNER JOIN srp_erp_companyfinanceyear ON srp_erp_companyfinanceyear.companyFinanceYearID = srp_erp_companyfinanceperiod.companyFinanceYearID WHERE dateFrom <= '{$documentDate}' AND dateTo >= '{$documentDate}' AND srp_erp_companyfinanceperiod.companyID = $companyid AND srp_erp_companyfinanceperiod.isActive = 1 AND srp_erp_companyfinanceyear.isActive = 1")->row_array();
        if (empty($FY)) {
            /*error*/
        }
        $gl = $this->getGLdetails($GLAutoID);
        $gl2 = $this->getGLdetails($bankAccountID);
        $type = substr($documentType, 0, 3);

        $data['documentID'] = $documentType;
        $data['PVdate'] = $documentDate;
        $data['pvType'] = 'Direct';
        $data['referenceNo'] = $refNo;
        $data['companyFinanceYearID'] = $FY['companyFinanceYearID'];
        $data['FYBegin'] = $FY['beginingDate'];
        $data['FYEnd'] = $FY['endingDate'];
        $data['FYPeriodDateFrom'] = $FY['dateFrom'];
        $data['FYPeriodDateTo'] = $FY['dateTo'];
        $data['PVbankCode'] = $gl['bankShortCode'];
        $data['bankGLAutoID'] = $gl['GLAutoID'];
        $data['bankSystemAccountCode'] = $gl['systemAccountCode'];
        $data['bankGLSecondaryCode'] = $gl['GLSecondaryCode'];
        $data['PVbank'] = $gl['bankName'];
        $data['PVbankBranch'] = $gl['bankBranch'];
        $data['PVbankSwiftCode'] = $gl['bankSwiftCode'];
        $data['PVbankAccount'] = $gl['bankAccountNumber'];
        $data['PVbankType'] = $gl['subCategory'];
        $data['PVNarration'] = $narration;
        $data['confirmedYN'] = 1;
        $data['confirmedByEmpID'] = $this->common_data['current_user'];
        $data['confirmedByName'] = $this->common_data['current_userID'];
        $data['confirmedDate'] = $this->common_data['current_date'];
        $data['approvedYN'] = 1;
        $data['approvedbyEmpID'] = $this->common_data['current_userID'];
        $data['approvedbyEmpName'] = $this->common_data['current_user'];
        $data['approvedDate'] = $this->common_data['current_date'];
        $data['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $data['transactionCurrency'] = $gl['bankCurrencyCode'];
        $data['transactionExchangeRate'] = 1;
        $data['transactionAmount'] = $amount;
        $data['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $default_currency = currency_conversion($data['transactionCurrency'], $data['companyLocalCurrency']);
        $reporting_currency = currency_conversion($data['transactionCurrency'], $data['companyReportingCurrency']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalAmount'] = $amount / $data['companyLocalExchangeRate'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];;
        $data['companyReportingAmount'] = $amount / $data['companyReportingExchangeRate'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $data['bankCurrencyID'] = $gl['bankCurrencyID'];
        $data['bankCurrency'] = $gl['bankCurrencyCode'];
        $data['bankCurrencyExchangeRate'] = 1;
        $data['bankCurrencyAmount'] = $amount;
        $data['bankCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $data['companyID'] = $this->common_data['company_data']['company_id'];
        $data['companyCode'] = $this->common_data['company_data']['company_code'];
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdDateTime'] = $this->common_data['current_date'];
        $data['createdUserName'] = $this->common_data['current_user'];
        $data['segmentCode'] = $segmentCode;
        $data['segmentID'] = $segmentID;
        //$data['PVcode'] = $this->sequence->sequence_generator($documentType);
        $this->load->library('sequence');
        $invYear= date("Y", strtotime($data['PVdate']));
        $invMonth= date("m", strtotime($data['PVdate']));
        $data['PVcode'] = $this->sequence->sequence_generator_fin($documentType, $FY['companyFinanceYearID'], $invYear, $invMonth);


        $insert = $this->db->insert('srp_erp_paymentvouchermaster', $data);
        $lastinsert_id=$this->db->insert_id();
        if ($insert) {

            $docapprove['departmentID'] = $documentType;
            $docapprove['documentID'] = $documentType;
            $docapprove['documentSystemCode'] = $lastinsert_id;
            $docapprove['documentCode'] = $data['PVcode'];
            $docapprove['documentDate'] = $documentDate;
            $docapprove['approvalLevelID'] = 1;
            $docapprove['isReverseApplicableYN'] = 1;
            $docapprove['docConfirmedDate'] = $this->common_data['current_date'];
            $docapprove['docConfirmedByEmpID'] = $this->common_data['current_user'];
            $docapprove['table_name'] = 'srp_erp_paymentvouchermaster';
            $docapprove['table_unique_field_name'] = 'payVoucherAutoId';
            $docapprove['approvedEmpID'] = $this->common_data['current_userID'];
            $docapprove['approvedYN'] = 1;
            $docapprove['approvedDate'] = $this->common_data['current_date'];
            $docapprove['approvedComments'] = 'Created from bank rec';
            $docapprove['approvedPC'] = $this->common_data['current_pc'];
            $docapprove['companyID'] = $this->common_data['company_data']['company_id'];
            $docapprove['companyCode'] = $this->common_data['company_data']['company_code'];

            $this->db->insert('srp_erp_documentapproved', $docapprove);


            $detail['payVoucherAutoId'] = $lastinsert_id;
            $detail['type'] = "GL";
            $detail['referenceNo'] = $refNo;
            $detail['bookingDate'] = $documentDate;
            $detail['Invoice_amount'] = $amount;
            $detail['GLAutoID'] = $gl2['GLAutoID'];
            $detail['systemGLCode'] = $gl2['systemAccountCode'];
            $detail['GLCode'] = $gl2['GLSecondaryCode'];
            $detail['GLDescription'] = $gl2['GLDescription'];
            $detail['GLType'] = $gl2['subCategory'];
            $detail['description'] = $narration;
            $detail['transactionCurrencyID'] = $data['transactionCurrencyID'];
            $detail['transactionCurrency'] = $data['transactionCurrency'];
            $detail['transactionExchangeRate'] = $data['transactionExchangeRate'];
            $detail['transactionAmount'] = $data['transactionAmount'];
            $detail['transactionCurrencyDecimalPlaces'] = $data['transactionCurrencyDecimalPlaces'];
            $detail['companyLocalCurrency'] = $data['companyLocalCurrency'];
            $detail['companyLocalCurrencyID'] = $data['companyLocalCurrencyID'];
            $detail['companyLocalExchangeRate'] = $data['companyLocalExchangeRate'];
            $detail['companyLocalAmount'] = $data['companyLocalAmount'];
            $detail['companyLocalCurrencyDecimalPlaces'] = $data['companyLocalCurrencyDecimalPlaces'];
            $detail['companyReportingCurrencyID'] = $data['companyReportingCurrencyID'];
            $detail['companyReportingCurrency'] = $data['companyReportingCurrency'];
            $detail['companyReportingExchangeRate'] = $data['companyReportingExchangeRate'];
            $detail['companyReportingAmount'] = $data['companyReportingAmount'];
            $detail['companyReportingCurrencyDecimalPlaces'] = $data['companyReportingCurrencyDecimalPlaces'];
            $detail['companyID'] = $data['companyID'];
            $detail['companyCode'] = $data['companyCode'];
            $detail['createdUserGroup'] = $data['createdUserGroup'];
            $detail['createdPCID'] = $data['createdPCID'];
            $detail['createdUserID'] = $data['createdUserID'];
            $detail['createdDateTime'] = $data['createdDateTime'];
            $detail['createdUserName'] = $data['createdUserName'];
            $detail['segmentCode'] = $segmentCode;
            $detail['segmentID'] = $segmentID;

            $insertDetail = $this->db->insert('srp_erp_paymentvoucherdetail', $detail);


        }
        $bankledger['documentDate'] = $documentDate;
        $bankledger['transactionType'] = $type1;
        $bankledger['transactionCurrency'] = $gl['bankCurrencyCode'];
        $bankledger['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $bankledger['transactionExchangeRate'] = 1;
        $bankledger['transactionAmount'] = $amount;
        $bankledger['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $bankledger['bankCurrency'] = $gl['bankCurrencyCode'];
        $bankledger['bankCurrencyExchangeRate'] = 1;
        $bankledger['bankCurrencyAmount'] = $amount;
        $bankledger['bankCurrencyID'] = $gl['bankCurrencyID'];
        $bankledger['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $bankledger['bankCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $bankledger['memo'] = $narration;
        $bankledger['bankName'] = $gl['bankName'];
        $bankledger['bankGLAutoID'] = $gl['GLAutoID'];
        $bankledger['bankSystemAccountCode'] = $gl['systemAccountCode'];
        $bankledger['bankGLSecondaryCode'] = $gl['GLSecondaryCode'];
        $bankledger['documentMasterAutoID'] = $detail['payVoucherAutoId'];
        $bankledger['documentSystemCode'] = $data['PVcode'];
        $bankledger['documentType'] = $documentType;
        $bankledger['createdPCID'] = $this->common_data['current_pc'];
        $bankledger['companyID'] = $this->common_data['company_data']['company_id'];
        $bankledger['companyCode'] = $this->common_data['company_data']['company_code'];
        $bankledger['createdUserID'] = $this->common_data['current_userID'];
        $bankledger['createdDateTime'] = $this->common_data['current_date'];;
        $bankledger['createdUserName'] = $this->common_data['current_user'];
        $bankledger['segmentCode'] = $segmentCode;
        $bankledger['segmentID'] = $segmentID;
        $bankledgerinsert = $this->db->insert('srp_erp_bankledger', $bankledger);

        $docdate = explode('-', $documentDate);

        $generalLedger[0]['documentCode'] = $documentType;
        $generalLedger[0]['documentMasterAutoID'] = $detail['payVoucherAutoId'];
        $generalLedger[0]['documentSystemCode'] = $data['PVcode'];
        $generalLedger[0]['documentType'] = "Direct";
        $generalLedger[0]['documentDate'] = $documentDate;
        $generalLedger[0]['documentYear'] = $docdate[0];
        $generalLedger[0]['documentMonth'] = $docdate[1];
        $generalLedger[0]['documentNarration'] = $narration;
        $generalLedger[0]['GLAutoID'] = $gl['GLAutoID'];
        $generalLedger[0]['systemGLCode'] = $gl['systemAccountCode'];
        $generalLedger[0]['GLCode'] = $gl['GLSecondaryCode'];
        $generalLedger[0]['GLDescription'] = $gl['GLDescription'];
        $generalLedger[0]['GLType'] = $gl['subCategory'];
        $generalLedger[0]['amount_type'] = 'cr';
        $generalLedger[0]['transactionCurrency'] = $gl['bankCurrencyCode'];
        $generalLedger[0]['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $generalLedger[0]['transactionExchangeRate'] = 1;
        $generalLedger[0]['transactionAmount'] = -1 * abs($amount);
        $generalLedger[0]['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $generalLedger[0]['companyLocalCurrency'] = $data['companyLocalCurrency'];
        $generalLedger[0]['companyLocalCurrencyID'] = $data['companyLocalCurrencyID'];
        $generalLedger[0]['companyReportingCurrencyID'] = $data['companyReportingCurrencyID'];
        $generalLedger[0]['companyLocalExchangeRate'] = $data['companyLocalExchangeRate'];
        $generalLedger[0]['companyLocalAmount'] = -1 * abs($data['companyLocalAmount']);
        $generalLedger[0]['companyLocalCurrencyDecimalPlaces'] = $data['companyLocalCurrencyDecimalPlaces'];
        $generalLedger[0]['companyReportingCurrency'] = $data['companyReportingCurrency'];
        $generalLedger[0]['companyReportingExchangeRate'] = $data['companyReportingExchangeRate'];
        $generalLedger[0]['companyReportingAmount'] = -1 * abs($data['companyReportingAmount']);
        $generalLedger[0]['companyReportingCurrencyDecimalPlaces'] = $data['companyReportingCurrencyDecimalPlaces'];
        $generalLedger[0]['confirmedByEmpID'] = $this->common_data['current_userID'];
        $generalLedger[0]['confirmedByName'] = $this->common_data['current_user'];
        $generalLedger[0]['confirmedDate'] = $this->common_data['current_date'];
        $generalLedger[0]['approvedDate'] = $this->common_data['current_date'];
        $generalLedger[0]['approvedbyEmpID'] = $this->common_data['current_userID'];
        $generalLedger[0]['approvedbyEmpName'] = $this->common_data['current_user'];
        $generalLedger[0]['companyID'] = $this->common_data['company_data']['company_id'];;
        $generalLedger[0]['companyCode'] = $this->common_data['company_data']['company_code'];;
        $generalLedger[0]['createdUserGroup'] = $this->common_data['user_group'];
        $generalLedger[0]['createdPCID'] = $this->common_data['current_pc'];
        $generalLedger[0]['createdUserID'] = $this->common_data['current_userID'];;
        $generalLedger[0]['createdDateTime'] = $this->common_data['current_date'];
        $generalLedger[0]['createdUserName'] = $this->common_data['current_user'];
        $generalLedger[0]['segmentCode'] = $segmentCode;
        $generalLedger[0]['segmentID'] = $segmentID;
        $generalLedger[1]['segmentCode'] = $segmentCode;
        $generalLedger[1]['segmentID'] = $segmentID;

        $generalLedger[1]['documentCode'] = $documentType;
        $generalLedger[1]['documentMasterAutoID'] = $detail['payVoucherAutoId'];
        $generalLedger[1]['documentSystemCode'] = $data['PVcode'];
        $generalLedger[1]['documentType'] = "Direct";
        $generalLedger[1]['documentDate'] = $documentDate;
        $generalLedger[1]['documentYear'] = $docdate[0];
        $generalLedger[1]['documentMonth'] = $docdate[1];
        $generalLedger[1]['documentNarration'] = $narration;
        $generalLedger[1]['GLAutoID'] = $gl2['GLAutoID'];
        $generalLedger[1]['systemGLCode'] = $gl2['systemAccountCode'];
        $generalLedger[1]['GLCode'] = $gl2['GLSecondaryCode'];
        $generalLedger[1]['GLDescription'] = $gl2['GLDescription'];
        $generalLedger[1]['GLType'] = $gl2['subCategory'];
        $generalLedger[1]['amount_type'] = 'dr';
        /**/

        /**/
        $generalLedger[1]['transactionCurrencyID'] = $gl['bankCurrencyID'];
        $generalLedger[1]['transactionCurrency'] = $gl['bankCurrencyCode'];
        $generalLedger[1]['companyReportingCurrency'] = 1;
        $generalLedger[1]['companyLocalCurrency'] = $data['companyLocalCurrency'];//$this->common_data['company_data']['company_default_currency'];
        /*    $default_currency = currency_conversion($generalLedger[1]['companyLocalCurrency'],$generalLedger[1]['companyLocalCurrency']);
            $reporting_currency = currency_conversion($generalLedger[1]['companyLocalCurrency'],$generalLedger[1]['companyReportingCurrency']);*/
        $generalLedger[1]['transactionExchangeRate'] = 1;
        $generalLedger[1]['transactionAmount'] = $amount;
        $generalLedger[1]['transactionCurrencyDecimalPlaces'] = $gl['bankCurrencyDecimalPlaces'];
        $generalLedger[1]['companyLocalExchangeRate'] = $data['companyLocalExchangeRate'];//$default_currency['conversion'];
        $companyLocalAmount = $amount / $generalLedger[1]['companyLocalExchangeRate'];
        $generalLedger[1]['companyLocalAmount'] = $data['companyLocalAmount']; //$companyLocalAmount;
        $generalLedger[1]['companyLocalCurrencyDecimalPlaces'] = $data['companyReportingCurrencyDecimalPlaces'];//$default_currency['DecimalPlaces'];
        $generalLedger[1]['companyLocalCurrencyID'] = $data['companyLocalCurrencyID'];
        $generalLedger[1]['companyReportingCurrencyID'] = $data['companyReportingCurrencyID'];
        $generalLedger[1]['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $companyReportingAmount = $amount / $generalLedger[1]['companyReportingExchangeRate'];
        $generalLedger[1]['companyReportingAmount'] = $data['companyReportingAmount'];//$companyReportingAmount;
        $generalLedger[1]['companyReportingCurrencyDecimalPlaces'] = $data['companyReportingCurrencyDecimalPlaces']; //$reporting_currency['DecimalPlaces'];
        $generalLedger[1]['confirmedByEmpID'] = $this->common_data['current_userID'];
        $generalLedger[1]['confirmedByName'] = $this->common_data['current_user'];
        $generalLedger[1]['confirmedDate'] = $this->common_data['current_date'];
        $generalLedger[1]['approvedDate'] = $this->common_data['current_date'];
        $generalLedger[1]['approvedbyEmpID'] = $this->common_data['current_userID'];
        $generalLedger[1]['approvedbyEmpName'] = $this->common_data['current_user'];
        $generalLedger[1]['companyID'] = $this->common_data['company_data']['company_id'];;
        $generalLedger[1]['companyCode'] = $this->common_data['company_data']['company_code'];;
        $generalLedger[1]['createdUserGroup'] = $this->common_data['user_group'];
        $generalLedger[1]['createdPCID'] = $this->common_data['current_pc'];
        $generalLedger[1]['createdUserID'] = $this->common_data['current_userID'];;
        $generalLedger[1]['createdDateTime'] = $this->common_data['current_date'];
        $generalLedger[1]['createdUserName'] = $this->common_data['current_user'];
        $this->db->insert_batch('srp_erp_generalledger', $generalLedger);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('e', 'Failed. please try again');
            echo json_encode(true);
        } else {

            $this->db->trans_commit();
            $this->session->set_flashdata('s', 'Records Inserted Successfully.');
            echo json_encode(true);
        }
    }

    function get_assignedcurrency_company($companyID)
    {
        /*   $output= $this->db->query("SELECT srp_erp_companycurrencyassign.* FROM srp_erp_companycurrencyassign LEFT JOIN srp_erp_currencymaster on srp_erp_companycurrencyassign.currencyID=srp_erp_currencymaster.currencyID WHERE companyID = {$companyID}")->result_array();*/
        $output = $this->db->query("    SELECT srp_erp_companycurrencyassign.*, IF(company_reporting_currencyID = srp_erp_companycurrencyassign.currencyID, company_reporting_currencyID, 0) as company_reporting_currencyID FROM `srp_erp_company` LEFT JOIN srp_erp_companycurrencyassign ON srp_erp_company.company_id = srp_erp_companycurrencyassign.companyID LEFT JOIN srp_erp_currencymaster ON srp_erp_companycurrencyassign.currencyID = srp_erp_currencymaster.currencyID WHERE company_id = {$companyID} ORDER BY company_reporting_currencyID DESC ")->result_array();
        return $output;

    }

    function detail_assignedcurrency_company($companyID, $mastercurrencyassignAutoID)
    {
        $output = $this->db->query("SELECT mastercurrencyassignAutoID,subcurrencyassignAutoID,currencyConversionAutoID,m.CurrencyName as baseCurrency,s.CurrencyName as subCurrency,conversion FROM srp_erp_companycurrencyconversion LEFT JOIN srp_erp_currencymaster  m on m.CurrencyID=masterCurrencyID LEFT JOIN srp_erp_currencymaster  s on s.CurrencyID=subCurrencyID WHERE companyID = {$companyID} AND mastercurrencyassignAutoID = {$mastercurrencyassignAutoID}")->result_array();
        return $output;

    }

    function delete_banktransfer_master()
    {
        $this->db->delete('srp_erp_banktransfer', array('bankTransferAutoID' => trim($this->input->post('bankTransferAutoID'))));
        return true;
    }

    function delete_bankfacilityLoan()
    {
        $this->db->delete('srp_erp_bankfacilityloan', array('bankFacilityID' => trim($this->input->post('bankFacilityID'))));
        $this->db->delete('srp_erp_bankfacilityloandetail', array('bankFacilityID' => trim($this->input->post('bankFacilityID'))));
        $this->session->set_flashdata('s', 'Deleted Records Successfully.');

        return true;


    }

    function get_companyCountry()
    {

    }

    function get_desertAllowance_report()
    {

    }

    function get_jobBonus_report()
    {

    }

    function delete_bankrec()
    {
       $result= $this->db->delete('srp_erp_bankrecMaster', array('bankRecAutoID' => trim($this->input->post('bankRecAutoID'))));
        if($result){
            return array('s','Deleted Successfully');
        }else{
            return array('E','Deletion Failed');
        }
    }

    function getDecimalPlaces()
    {
        $bankFrom = $this->input->post('bankFrom');
        if($bankFrom ==''){
          $data['bankCurrencyDecimalPlaces']=0;
        }else{
          $data = $this->db->query("SELECT bankCurrencyDecimalPlaces FROM srp_erp_chartofaccounts  WHERE GLAutoID = {$bankFrom}")->row_array();
        }

        return $data;
    }

    function load_Cheque_templates($bankTransferAutoID)
    {
        $this->db->select('fromBankGLAutoID');
        $this->db->where('bankTransferAutoID', $bankTransferAutoID);
        $this->db->from('srp_erp_banktransfer');
        $glid = $this->db->get()->row_array();

        $this->db->select('srp_erp_chartofaccountchequetemplates.coaChequeTemplateID,srp_erp_chartofaccountchequetemplates.pageLink,srp_erp_systemchequetemplates.Description');
        $this->db->where('companyID', current_companyID());
        $this->db->where('GLAutoID', $glid['fromBankGLAutoID']);
        $this->db->join('srp_erp_systemchequetemplates', 'srp_erp_chartofaccountchequetemplates.systemChequeTemplateID = srp_erp_systemchequetemplates.chequeTemplateID', 'left');
        $this->db->from('srp_erp_chartofaccountchequetemplates');
        $data = $this->db->get()->result_array();
        return $data;
    }

    function bank_transfer_master_cheque($bankTransferAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $data = $this->db->query("SELECT fromCurrency.bankCurrencyID as fromcurrencyID, toCurrency.bankCurrencyID as tocurrencyID, srp_erp_banktransfer.*,DATE_FORMAT(transferedDate,'$convertFormat') AS transferedDate ,DATE_FORMAT(srp_erp_banktransfer.approvedDate,'.$convertFormat. %h:%i:%s') AS approvedDate, fromCurrency.bankCurrencyCode as fromcurrency, toCurrency.bankCurrencyCode as tocurrency, fromCurrency.GLDescription as bankfrom, toCurrency.GLDescription as bankto,fromCurrency.bankCurrencyDecimalPlaces AS fromDecimalPlaces, toCurrency.bankCurrencyDecimalPlaces AS toDecimalPlaces,fromCurrency.systemAccountCode as fromSystemAccountCode,fromCurrency.GLSecondaryCode as fromGLSecondaryCode, toCurrency.systemAccountCode as toCurrencySystemAccountCode,toCurrency.GLSecondaryCode as toCurrencyGLSecondaryCode, fromCurrency.GLDescription as fromGLDescription, fromCurrency.subCategory as fromSubCategory, toCurrency.GLDescription as toGLDescription, toCurrency.subCategory as toSubCategory,srp_erp_currencymaster.CurrencyCode as CurrencyCode,chequeDate FROM srp_erp_banktransfer LEFT JOIN srp_erp_chartofaccounts AS fromCurrency ON fromBankGLAutoID = fromCurrency.GLAutoID LEFT JOIN srp_erp_chartofaccounts AS toCurrency ON toBankGLAutoID = toCurrency.GLAutoID LEFT JOIN srp_erp_currencymaster  ON fromBankCurrencyID = srp_erp_currencymaster.currencyID WHERE bankTransferAutoID = {$bankTransferAutoID}")->row_array();
        return $data;
    }
    function fetch_signaturelevel()
    {
        $this->db->select('approvalSignatureLevel');
        $this->db->where('companyID', current_companyID());
        $this->db->where('documentID', 'BT');
        $this->db->from('srp_erp_documentcodemaster');
        return $this->db->get()->row_array();
    }

}