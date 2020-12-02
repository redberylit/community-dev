<?php

class Budget extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('Budget_model');
        $this->load->model('dashboard_model');

    }

    function save_budget_header()
    {
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');

        $this->form_validation->set_rules('segment_gl', 'Segement', 'trim|required');
        $this->form_validation->set_rules('description', 'Narration', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $segment_gl = explode('|', $this->input->post('segment_gl'));
            $financeyear=$this->input->post('financeyear');
            $validate = $this->db->query("SELECT * FROM srp_erp_budgetmaster WHERE companyFinanceYearID ={$financeyear} AND segmentID={$segment_gl[0]}  ")->row_array();
            if (!empty($validate)) {
                $this->session->set_flashdata('e', 'Budget already created for the selected financial year and segment');
                echo json_encode(false);
                exit;
            }

            $doc = get_document_code('BD');

           $period = explode(' - ', trim($this->input->post('companyFinanceYear')));
          /*  $financeperiod = $this->input->post('financeyear_period');
            $period=fetchFinancePeriod($financeperiod);*/
            $date_format_policy = date_format_policy();
            $FYBegin = input_format_date($period[0], $date_format_policy);
            $FYEnd = input_format_date($period[1], $date_format_policy);

            $data['FYBegin'] = $FYBegin;
            $data['FYEnd'] = $FYEnd;

            $this->load->library('sequence');
            $date_format_policy = date_format_policy();
            $documentDate = $this->input->post('documentDate');
            $date = input_format_date($documentDate,$date_format_policy);
            $data['documentSystemCode'] = $this->sequence->sequence_generator($doc['prefix']);
            $data['narration'] = $this->input->post('description');
            $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
            $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
            $data['documentDate'] = $date;
            $data['companyID'] = current_companyID();
            $data['companyCode'] = current_companyCode();
            $data['segmentID'] = $segment_gl[0];
            $data['segmentCode'] = $segment_gl[1];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['createdUserName'] = $this->common_data['current_user'];

            $data['transactionCurrency']                    =$this->common_data['company_data']['company_reporting_currency'];
            $data['transactionCurrencyID']                    =$this->common_data['company_data']['company_reporting_currencyID'];
              $data['transactionExchangeRate']                = 1;
              $data['transactionCurrencyDecimalPlaces']       = fetch_currency_desimal($data['transactionCurrency']);
             $data['companyLocalCurrency']                   = $this->common_data['company_data']['company_default_currency'];
             $data['companyLocalCurrencyID']                   = $this->common_data['company_data']['company_default_currencyID'];
            $default_currency      = currency_conversion($data['transactionCurrency'],$data['companyLocalCurrency']);
            $data['companyLocalExchangeRate']               = $default_currency['conversion'];
            $data['companyLocalCurrencyDecimalPlaces']      = $default_currency['DecimalPlaces'];
            $data['companyReportingCurrency']               = $this->common_data['company_data']['company_reporting_currency'];
            $data['companyReportingCurrencyID']               = $this->common_data['company_data']['company_reporting_currencyID'];
            $reporting_currency    = currency_conversion($data['transactionCurrency'],$data['companyReportingCurrency']);
            $data['companyReportingExchangeRate']           = $reporting_currency['conversion'];
            $data['companyReportingCurrencyDecimalPlaces']  = $reporting_currency['DecimalPlaces'];

          /*  $companyFinanceYear = explode(' - ', $this->input->post('companyFinanceYear'));
            $fromMonth = $companyFinanceYear[0];
            $toMonth = $companyFinanceYear[1];*/
            $_POST['companyFinanceYearID'] = $data['companyFinanceYearID'];

            $insert = $this->db->insert('srp_erp_budgetmaster', $data);
            $last_id = $this->db->insert_id();
            $companyID=current_companyID();


            $financialperiod = $this->Budget_model->fetch_finance_year_period_budget();
       /*     $result=$this->db->query("SELECT d.accountCategoryTypeID AS accountCategoryID, d.CategoryTypeDescription AS accountCategoryDesc, dd.GLAutoID AS masterGLAutoID, dd.GLDescription AS masterAccount, m.GLAutoID AS GLAutoID, m.GLDescription AS GLDescription FROM srp_erp_chartofaccounts AS m LEFT JOIN srp_erp_accountcategorytypes AS d ON d.accountCategoryTypeID = m.accountCategoryTypeID LEFT JOIN ( SELECT GLDescription, GLAutoID FROM srp_erp_chartofaccounts WHERE srp_erp_chartofaccounts.masterCategory = 'PL' ) dd ON ( dd.GLAutoID = m.masterAutoID ) WHERE m.masterCategory = 'PL' AND d.accountCategoryTypeID IN (10 , 11, 12) order by accountCategoryID,masterAccount ")->result_array();   */
            $result=$this->db->query("SELECT d.accountCategoryTypeID AS accountCategoryID, d.CategoryTypeDescription AS accountCategoryDesc, dd.GLAutoID AS masterGLAutoID, dd.GLDescription AS masterAccount, m.GLAutoID AS GLAutoID, m.GLDescription AS GLDescription, m.companyID FROM srp_erp_chartofaccounts AS m LEFT JOIN srp_erp_accountcategorytypes AS d ON d.accountCategoryTypeID = m.accountCategoryTypeID LEFT JOIN (SELECT GLDescription, GLAutoID FROM srp_erp_chartofaccounts WHERE srp_erp_chartofaccounts.masterCategory = 'PL') dd ON (dd.GLAutoID = m.masterAutoID) WHERE m.masterCategory = 'PL'  AND m.companyID={$companyID} ORDER BY accountCategoryID , masterAccount")->result_array();
            $detail = array();
            $x = 0;
            if($result){
                foreach($result as $value){
                    if ($financialperiod) {

                        foreach ($financialperiod as $period) {
                            $datefrom = explode('-', $period['dateFrom']);
                            $year = $datefrom[2];
                            $month = $datefrom[1];
                            $detail[$x]['companyID'] = $data['companyID'];
                            $detail[$x]['companyCode'] = $data['companyCode'];
                            $detail[$x]['segmentID'] = $data['segmentID'];
                            $detail[$x]['segmentCode'] = $data['segmentCode'];
                            $detail[$x]['companyFinancePeriodID'] = $period['companyFinanceYearID'];

                            $date_format_policy = date_format_policy();
                            $dtFrm = $period['dateFrom'];
                            $dateFrom = input_format_date($dtFrm,$date_format_policy);
                            $dtto = $period['dateTo'];
                            $dateTo = input_format_date($dtto,$date_format_policy);
                            $detail[$x]['FYPeriodDateFrom'] = $dateFrom;
                            $detail[$x]['FYPeriodDateTo'] = $dateTo;
                            $detail[$x]['budgetAutoID'] = $last_id;
                            $detail[$x]['accountCategoryID'] = $value['accountCategoryID'];
                            $detail[$x]['accountCategoryDesc'] = $value['accountCategoryDesc'];
                            $detail[$x]['masterGLAutoID'] = $value['masterGLAutoID'];
                            $detail[$x]['masterAccount'] = $value['masterAccount'];
                            $detail[$x]['GLAutoID'] = $value['GLAutoID'];
                            $detail[$x]['GLDescription'] = $value['GLDescription'];
                            $detail[$x]['budgetMonth'] = $month;
                            $detail[$x]['budgetYear'] = $year;

                            $detail[$x]['transactionCurrency']                    = trim($this->input->post('transactionCurrency'));
                            $detail[$x]['transactionCurrencyID']                    = $this->common_data['company_data']['company_reporting_currencyID'];
                            $detail[$x]['transactionExchangeRate']                = 1;
                            $detail[$x]['transactionCurrencyDecimalPlaces']       = fetch_currency_desimal($detail[$x]['transactionCurrency']);
                            $detail[$x]['companyLocalCurrency']                   = $this->common_data['company_data']['company_default_currency'];
                            $detail[$x]['companyLocalCurrencyID']                   = $this->common_data['company_data']['company_default_currencyID'];
                            $default_currency      = currency_conversion($detail[$x]['transactionCurrency'],$detail[$x]['companyLocalCurrency']);
                            $detail[$x]['companyLocalExchangeRate']               = $default_currency['conversion'];
                            $detail[$x]['companyLocalCurrencyDecimalPlaces']      = $default_currency['DecimalPlaces'];
                            $detail[$x]['companyReportingCurrency']               = $this->common_data['company_data']['company_reporting_currency'];
                            $detail[$x]['companyReportingCurrencyID']               = $this->common_data['company_data']['company_reporting_currencyID'];
                            $reporting_currency    = currency_conversion($detail[$x]['transactionCurrency'],$detail[$x]['companyReportingCurrency']);
                            $detail[$x]['companyReportingExchangeRate']           = $reporting_currency['conversion'];
                            $detail[$x]['companyReportingCurrencyDecimalPlaces']  = $reporting_currency['DecimalPlaces'];
                            $detail[$x]['createdUserGroup'] = $data['createdUserGroup'];
                            $detail[$x]['createdPCID'] = $data['createdPCID'];
                            $detail[$x]['createdUserID'] = $data['createdUserID'];
                            $detail[$x]['createdDateTime'] = $data['createdDateTime'];
                            $detail[$x]['createdUserName'] = $data['createdUserName'];


                            $x++;

                        }
                    }
                }
            }

            $detail_insert = $this->db->insert_batch('srp_erp_budgetdetail', $detail);
            if($detail_insert) {
                $this->session->set_flashdata('s', 'Records Inserted Successfully');
                echo json_encode(TRUE);
            }else{
                $this->session->set_flashdata('e', 'Failed. Please Contact IT Team');
                echo json_encode(FALES);
            }

        }
    }

    function fetch_budget_entry(){

        $this->datatables->select("budgetAutoID,documentSystemCode,narration,documentDate ,companyFinanceYearID ,companyFinanceYear, FYBegin, FYEnd, transactionCurrency, confirmedYN,srp_erp_segment.description,srp_erp_budgetmaster.confirmedYN",false);
        $this->datatables->from('srp_erp_budgetmaster');
        $this->datatables->join('srp_erp_segment','srp_erp_budgetmaster.segmentID=srp_erp_segment.segmentID AND srp_erp_budgetmaster.companyID=srp_erp_segment.companyID','left');
        $this->datatables->where('srp_erp_budgetmaster.companyID', current_companyID());
        $this->datatables->add_column('edit', ' $1 ', 'load_budget_action(budgetAutoID,confirmedYN)');
        $this->datatables->add_column('confirmedYN', '$1', 'confirm(confirmedYN)');
        echo $this->datatables->generate();
    }

    function get_budget_detail_data(){
        $budgetAutoID=$this->input->post('budgetAutoID');
        $viewtype=$this->input->post('viewtype');
        $detail=$this->db->query("SELECT ca2.GLDescription as subCategory,budgetDetailAutoID, accountCategoryID, accountCategoryDesc, masterGLAutoID, srp_erp_budgetdetail.masterAccount,srp_erp_budgetdetail.GLAutoID,srp_erp_budgetdetail.GLDescription, budgetMonth, budgetYear,IF (srp_erp_chartofaccounts.subCategory = 'PLE','EXPENSE',IF (srp_erp_chartofaccounts.subCategory = 'PLI','INCOME','ND')) AS mainCategory,SUM(IF(budgetMonth = 1, transactionAmount, 0)) AS myJan, SUM(IF(budgetMonth = 2, transactionAmount, 0)) AS myFeb, SUM(IF(budgetMonth = 3, transactionAmount, 0)) AS myMar, SUM(IF(budgetMonth = 4, transactionAmount, 0)) AS myApr, SUM(IF(budgetMonth = 5, transactionAmount, 0)) AS myMay, SUM(IF(budgetMonth = 6, transactionAmount, 0)) AS myJun, SUM(IF(budgetMonth = 7, transactionAmount, 0)) AS myJul, SUM(IF(budgetMonth = 8, transactionAmount, 0)) AS myAug, SUM(IF(budgetMonth = 9, transactionAmount, 0)) AS mySep, SUM(IF(budgetMonth = 10, transactionAmount, 0)) AS myOct, SUM(IF(budgetMonth = 11, transactionAmount, 0)) AS myNov, SUM(IF(budgetMonth = 12, transactionAmount, 0)) AS myDec FROM srp_erp_budgetdetail INNER JOIN srp_erp_chartofaccounts ON srp_erp_budgetdetail.GLAutoID = srp_erp_chartofaccounts.GLAutoID AND srp_erp_chartofaccounts.masterCategory = 'PL' AND srp_erp_chartofaccounts.companyID = " . $this->common_data['company_data']['company_id'] . " LEFT JOIN ( SELECT GLDescription, GLAutoID FROM srp_erp_chartofaccounts WHERE srp_erp_chartofaccounts.masterCategory = 'PL' AND srp_erp_chartofaccounts.companyID = " . $this->common_data['company_data']['company_id'] . " ) ca2 ON ( ca2.GLAutoID = srp_erp_chartofaccounts.masterAutoID ) WHERE budgetAutoID = {$budgetAutoID} GROUP BY GLAutoID order BY accountCategoryID,masterGLAutoID asc")->result_array();

        $data['detail']=$detail;
        $master=$this->db->query("select * from srp_erp_budgetmaster  LEFT JOIN
    `srp_erp_segment` ON srp_erp_segment.segmentID = srp_erp_budgetmaster.segmentID
        AND srp_erp_budgetmaster.companyID = srp_erp_segment.companyID where budgetAutoID = {$budgetAutoID} ")->row_array();
        $_POST['companyFinanceYearID'] = $master['companyFinanceYearID'];
        //$financialperiodactive = $this->dashboard_model->fetch_finance_year_period();
        $financialperiodactive = $this->Budget_model->fetch_finance_year_period_budget();
        $financial_from_to = get_financial_from_to($_POST['companyFinanceYearID']);
        $financialperiod = get_month_list_from_date($financial_from_to["beginingDate"], $financial_from_to["endingDate"], "Y-m", "1 month");
        $data['financialperiod']=$financialperiod;
        $data['activeFP']=$financialperiodactive;
        $data['master']=$master;
        if($viewtype=='view'){
            echo   $html = $this->load->view('system/budget/erp_budget_detail_view_disable', $data, true);
        }else{
            echo   $html = $this->load->view('system/budget/erp_budget_detail_view', $data, true);
        }

    }

    function update_budget_row(){
        $glAutoID = $this->input->post('glAutoID');
        $budgetyear = $this->input->post('budgetyear');
        $budgetmonth = $this->input->post('budgetmonth');
        $amount = $this->input->post('amount');
        $budgetAutoID = $this->input->post('budgetAutoID');
        $master=$this->Budget_model->get_budget_master_header($budgetAutoID);

        $data['transactionAmount']=$amount;
        $data['companyLocalAmount']         = ($amount/$master['companyLocalExchangeRate']);
        $data['companyReportingAmount']     = ($amount/$master['companyReportingExchangeRate']);
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        $data['modifiedUserName'] = $this->common_data['current_user'];



        $this->db->update('srp_erp_budgetdetail', $data, array('GLAutoID' => $glAutoID,'budgetYear'=>$budgetyear,'budgetMonth'=>$budgetmonth,'budgetAutoID'=>$budgetAutoID));
        echo json_encode(TRUE);
    }
    function update_apply_all_row(){
        $myArray= $this->input->post('myArray');
        $budgetAutoID=$this->input->post('budgetAutoID');
        $master=$this->Budget_model->get_budget_master_header($budgetAutoID);
        if($myArray){
            foreach ($myArray as $value){
                $data['transactionAmount']=$value['amount'];
                $data['companyLocalAmount']         = ($value['amount']/$master['companyLocalExchangeRate']);
                $data['companyReportingAmount']     = ($value['amount']/$master['companyReportingExchangeRate']);
                $data['modifiedPCID'] = $this->common_data['current_pc'];
                $data['modifiedUserID'] = $this->common_data['current_userID'];
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['modifiedUserName'] = $this->common_data['current_user'];

                $this->db->update('srp_erp_budgetdetail', $data, array('GLAutoID' => $value['GLAutoID'],'budgetYear'=>$value['budgetYear'],'budgetMonth'=>$value['budgetMonth'],'budgetAutoID'=>$budgetAutoID));
            }
        }

        echo json_encode(TRUE);
    }

    function get_budget_footer_total(){
        $budgetAutoID=$this->input->post('budgetAutoID');
        $detail=$this->db->query("SELECT

	IF
	( srp_erp_chartofaccounts.subCategory = 'PLE', 'EXPENSE', IF ( srp_erp_chartofaccounts.subCategory = 'PLI', 'INCOME', 'ND' ) ) AS mainCategory,
	SUM( IF ( budgetMonth = 1, transactionAmount, 0 ) ) AS myJan,
	SUM( IF ( budgetMonth = 2, transactionAmount, 0 ) ) AS myFeb,
	SUM( IF ( budgetMonth = 3, transactionAmount, 0 ) ) AS myMar,
	SUM( IF ( budgetMonth = 4, transactionAmount, 0 ) ) AS myApr,
	SUM( IF ( budgetMonth = 5, transactionAmount, 0 ) ) AS myMay,
	SUM( IF ( budgetMonth = 6, transactionAmount, 0 ) ) AS myJun,
	SUM( IF ( budgetMonth = 7, transactionAmount, 0 ) ) AS myJul,
	SUM( IF ( budgetMonth = 8, transactionAmount, 0 ) ) AS myAug,
	SUM( IF ( budgetMonth = 9, transactionAmount, 0 ) ) AS mySep,
	SUM( IF ( budgetMonth = 10, transactionAmount, 0 ) ) AS myOct,
	SUM( IF ( budgetMonth = 11, transactionAmount, 0 ) ) AS myNov,
	SUM( IF ( budgetMonth = 12, transactionAmount, 0 ) ) AS myDec 
FROM
	srp_erp_budgetdetail 
	INNER JOIN srp_erp_chartofaccounts ON srp_erp_budgetdetail.GLAutoID = srp_erp_chartofaccounts.GLAutoID 
WHERE
srp_erp_chartofaccounts.masterCategory = 'PL' AND 
	budgetAutoID = {$budgetAutoID} 
	GROUP BY srp_erp_chartofaccounts.subCategory")->result_array();

        echo json_encode($detail);
    }

    function budget_confirmation(){
        $budgetAutoID = trim($this->input->post('budgetAutoID'));

            $data = array(
                'confirmedYN'        => 1,
                'confirmedDate'      => $this->common_data['current_date'],
                'confirmedByEmpID'   => $this->common_data['current_userID'],
                'confirmedByName'    => $this->common_data['current_user']
            );
            $this->db->where('budgetAutoID', $budgetAutoID);
            $this->db->update('srp_erp_budgetmaster', $data);
        echo json_encode(TRUE);


    }
    function referback_budjet()
    {

        $budgetAutoID = trim($this->input->post('budgetAutoID'));
        $dataUpdate = array(
            'confirmedYN' => 0,
            'confirmedByEmpID' => '',
            'confirmedByName' => '',
            'confirmedDate' => '',
        );

        $this->db->where('budgetAutoID', $budgetAutoID);
        $this->db->update('srp_erp_budgetmaster', $dataUpdate);

        echo json_encode(array('s', ' Referred Back Successfully.'));



    }

    function load_missing_gl_tobudget(){
        $budgetAutoID=$this->input->post('budgetAutoID');
        $companyID=current_companyID();
        $budmastr=$this->db->query("SELECT * FROM srp_erp_budgetmaster WHERE budgetAutoID = $budgetAutoID")->row_array();

       // $financialperiod = $this->Budget_model->fetch_finance_year_period_budget();
        $financialperiod = $this->Budget_model->fetch_finance_year_period_budget_load_missing($budmastr['companyFinanceYearID']);

        $result=$this->db->query("SELECT d.accountCategoryTypeID AS accountCategoryID, d.CategoryTypeDescription AS accountCategoryDesc, dd.GLAutoID AS masterGLAutoID, dd.GLDescription AS masterAccount, m.GLAutoID AS GLAutoID, m.GLDescription AS GLDescription, m.companyID FROM srp_erp_chartofaccounts AS m LEFT JOIN srp_erp_accountcategorytypes AS d ON d.accountCategoryTypeID = m.accountCategoryTypeID LEFT JOIN (SELECT GLDescription, GLAutoID FROM srp_erp_chartofaccounts WHERE srp_erp_chartofaccounts.masterCategory = 'PL') dd ON (dd.GLAutoID = m.masterAutoID) WHERE m.masterCategory = 'PL'  AND m.companyID={$companyID} AND m.GLAutoID Not IN(SELECT  srp_erp_budgetdetail.GLAutoID FROM srp_erp_budgetdetail WHERE budgetAutoID = $budgetAutoID GROUP BY GLAutoID) ORDER BY accountCategoryID , masterAccount")->result_array();
        $detail = array();
        $x = 0;
        if($result){
            foreach($result as $value){
                if ($financialperiod) {

                    foreach ($financialperiod as $period) {
                        $datefrom = explode('-', $period['dateFrom']);
                        $year = $datefrom[2];
                        $month = $datefrom[1];
                        $detail[$x]['companyID'] = $budmastr['companyID'];
                        $detail[$x]['companyCode'] = $budmastr['companyCode'];
                        $detail[$x]['segmentID'] = $budmastr['segmentID'];
                        $detail[$x]['segmentCode'] = $budmastr['segmentCode'];
                        $detail[$x]['companyFinancePeriodID'] = $period['companyFinanceYearID'];

                        $date_format_policy = date_format_policy();
                        $dtFrm = $period['dateFrom'];
                        $dateFrom = input_format_date($dtFrm,$date_format_policy);
                        $dtto = $period['dateTo'];
                        $dateTo = input_format_date($dtto,$date_format_policy);
                        $detail[$x]['FYPeriodDateFrom'] = $dateFrom;
                        $detail[$x]['FYPeriodDateTo'] = $dateTo;
                        $detail[$x]['budgetAutoID'] = $budgetAutoID;
                        $detail[$x]['accountCategoryID'] = $value['accountCategoryID'];
                        $detail[$x]['accountCategoryDesc'] = $value['accountCategoryDesc'];
                        $detail[$x]['masterGLAutoID'] = $value['masterGLAutoID'];
                        $detail[$x]['masterAccount'] = $value['masterAccount'];
                        $detail[$x]['GLAutoID'] = $value['GLAutoID'];
                        $detail[$x]['GLDescription'] = $value['GLDescription'];
                        $detail[$x]['budgetMonth'] = $month;
                        $detail[$x]['budgetYear'] = $year;

                        $detail[$x]['transactionCurrency']                    = trim($this->input->post('transactionCurrency'));
                        $detail[$x]['transactionCurrencyID']                    = $this->common_data['company_data']['company_reporting_currencyID'];
                        $detail[$x]['transactionExchangeRate']                = 1;
                        $detail[$x]['transactionCurrencyDecimalPlaces']       = fetch_currency_desimal($detail[$x]['transactionCurrency']);
                        $detail[$x]['companyLocalCurrency']                   = $this->common_data['company_data']['company_default_currency'];
                        $detail[$x]['companyLocalCurrencyID']                   = $this->common_data['company_data']['company_default_currencyID'];
                        $default_currency      = currency_conversion($detail[$x]['transactionCurrency'],$detail[$x]['companyLocalCurrency']);
                        $detail[$x]['companyLocalExchangeRate']               = $default_currency['conversion'];
                        $detail[$x]['companyLocalCurrencyDecimalPlaces']      = $default_currency['DecimalPlaces'];
                        $detail[$x]['companyReportingCurrency']               = $this->common_data['company_data']['company_reporting_currency'];
                        $detail[$x]['companyReportingCurrencyID']               = $this->common_data['company_data']['company_reporting_currencyID'];
                        $reporting_currency    = currency_conversion($detail[$x]['transactionCurrency'],$detail[$x]['companyReportingCurrency']);
                        $detail[$x]['companyReportingExchangeRate']           = $reporting_currency['conversion'];
                        $detail[$x]['companyReportingCurrencyDecimalPlaces']  = $reporting_currency['DecimalPlaces'];
                        $detail[$x]['createdUserGroup'] = $budmastr['createdUserGroup'];
                        $detail[$x]['createdPCID'] = $budmastr['createdPCID'];
                        $detail[$x]['createdUserID'] = $budmastr['createdUserID'];
                        $detail[$x]['createdDateTime'] = $budmastr['createdDateTime'];
                        $detail[$x]['createdUserName'] = $budmastr['createdUserName'];


                        $x++;

                    }
                }
            }
            $detail_insert = $this->db->insert_batch('srp_erp_budgetdetail', $detail);
            if($detail_insert) {
                echo json_encode(array('s', 'Records Inserted Successfully'));
            }else{
                echo json_encode(array('e', 'Failed. Please Contact IT Team'));
            }
        }else{
            echo json_encode(array('s', 'Records Inserted Successfully'));
        }



    }

}
