<?php

class Bank_rec extends ERP_Controller
{
    public $InterestSchedule;
    public $schedule;
    public $thirdArray;
    public $interestPayment;
    public $installmentID;
    public $rateOfInterest;
    public $noInstallment;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Journal_entry_model');
        $this->load->model('Bank_rec_model');
        $this->load->model('template_paySheet_model');

    }

    function fetch_bank_rec_entry()
    {
        $this->datatables->select('c.systemAccountCode as systemAccountCode, c.GLSecondaryCode as GLSecondaryCode, c.GLDescription as GLDescription, c.isBank as isBank, c.bankName as bankName, c.bankBranch as bankBranch, c.bankSwiftCode as bankSwiftCode, c.bankAccountNumber as bankAccountNumber, c.bankCurrencyID, c.bankCurrencyCode as bankCurrencyCode,b.bankCurrencyDecimalPlaces As decimal2 ,GLAutoID,SUM(IF(transactionType = 2, - 1 * COALESCE(bankCurrencyAmount, 0), 0))+  SUM(IF(transactionType = 1, COALESCE(bankCurrencyAmount, 0), 0)) AS SumbankAmount', false);
        $this->datatables->from('srp_erp_chartofaccounts AS c');
        $this->datatables->join('srp_erp_bankledger AS b', 'c.GLAutoID = b.bankGLAutoID', 'LEFT');
        $this->datatables->where('c.isBank', 1);
        $this->datatables->where('c.iscash<>', 1);
        $this->datatables->where('c.companyID', current_companyID());
        $this->datatables->group_by('c.systemAccountCode , c.GLSecondaryCode , c.GLDescription , c.isBank , c.bankName , c.bankBranch , c.bankSwiftCode , c.bankAccountNumber , c.bankCurrencyID , c.bankCurrencyCode');
        $this->datatables->add_column('totalAmount', '<div class="pull-right"> $1 </div>', 'format_number(SumbankAmount,decimal2)');
        $this->datatables->add_column('edit', ' $1 ', 'load_bank_rec_action(GLAutoID)');
        echo $this->datatables->generate();

    }

    function fetch_bank_rec_summary()
    {
        $convertFormat = convert_date_format_sql();

        $bankGLAutoID = $this->input->post('bankGLAutoID');
        $this->datatables->select("bankRecPrimaryCode,bankRecAutoID,MONTHNAME(STR_TO_DATE(month, '%m')) as month,year, DATE_FORMAT(bankRecAsOf,'$convertFormat') AS bankRecAsOf,description,createdUserID,approvedYN,createdBy,confirmedYN,confirmedByEmpID");
        $this->datatables->from('srp_erp_bankrecmaster');

        $this->datatables->where('bankGLAutoID', $bankGLAutoID);
        $this->datatables->add_column('edit', ' $1 ', 'load_bank_rec_summary_action(' . $bankGLAutoID . ',bankRecAutoID,confirmedYN,approvedYN,createdUserID,confirmedByEmpID)');
        $this->datatables->add_column('confirm', '$1', 'confirm_user_approval_drilldown(confirmedYN,"BRC",bankRecAutoID)');
        $this->datatables->add_column('approvedYN', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"BRC",bankRecAutoID)');

        echo $this->datatables->generate();

    }

    function fetch_bank_rec_approval()
    {
        $companyID = current_companyID();
        $this->datatables->select('b.bankRecAutoID as bankRecAutoID,bankGLAutoID,concat(month,"/", year) as month,createdBy,DATE_FORMAT(bankRecAsOf,"%d/%m/%y") as bankRecAsOf,description, bankRecPrimaryCode, createdBy,d.approvedYN as approvedYN ,documentApprovedID, approvalLevelID,c.systemAccountCode,c.bankName,c.bankAccountNumber');
        $this->datatables->from('srp_erp_bankrecmaster AS b');
        $this->datatables->join('srp_erp_documentapproved as d', 'd.documentSystemCode = b.bankRecAutoID AND d.approvalLevelID = b.currentLevelNo', 'LEFT');
        $this->datatables->join('srp_erp_chartofaccounts  AS c', 'c.GLAutoID = b.bankGLAutoID', 'LEFT');
        $this->datatables->join('srp_erp_approvalusers As au', 'au.levelNo = b.currentLevelNo');
        $this->datatables->where('d.documentID', 'BRC');
        $this->datatables->where('au.documentID', 'BRC');
        $this->datatables->where('au.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('au.companyID', trim($companyID));
        $this->datatables->where('d.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->where('b.companyID', trim($companyID));
        /*    $this->datatables->add_column('bankRecPrimaryCode', '<a onclick=\'documentPageView_modal("BR","$2","$3"); \'>$1</a>', 'bankRecPrimaryCode,bankRecAutoID,bankGLAutoID');*/
        $this->datatables->add_column('bankRecPrimaryCode', '$1', 'approval_change_modal_treasury(bankRecPrimaryCode,bankRecAutoID,bankGLAutoID,documentApprovedID,approvalLevelID,approvedYN,BR)');
        $this->datatables->add_column('detail', '<b>Month : </b> $1 <b> &nbsp;&nbsp;As Of Date : </b> $2 <b><br> &nbsp;&nbsp;Narration : </b> $3', 'month,bankRecAsOf,description');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "BRC", bankRecAutoID)');
        //  $this->datatables->add_column('edit', '$1', 'jv_approval(bankRecAutoID,approvalLevelID,approvedYN,documentApprovedID)');
        $this->datatables->add_column('edit', '$1', 'bankrec_approval(bankRecAutoID,bankGLAutoID,approvalLevelID,approvedYN,documentApprovedID)');
        echo $this->datatables->generate();
    }

    function viewbankrec_detail()
    {
        $data['details'] = $this->Bank_rec_model->viewbankrec_detail();
        $master = $this->Bank_rec_model->get_bank_rec_header();
        if ($master['confirmedYN'] == 1) {
            $data['openingbalance'] = $this->Bank_rec_model->getopeningbalancebyrectautoID();
        } else {
            $data['openingbalance'] = $this->Bank_rec_model->get_opening_balance_bank_rec();
        }

        $originalDate = $master['bankRecAsOf'];
        $bankRecAsOf = date("Y-m-d", strtotime($originalDate));

        $data['bankRecAsOf'] = $bankRecAsOf;
        $data['confirmedYN'] = $master['confirmedYN'];
        $data['bankRecAutoID'] = $this->input->post('bankRecAutoID');
        $data['master'] = $master;
        $html = $this->load->view('system/bank_rec/erp_bank_rec_generated_data', $data, true);
        echo $html;

    }

    function save_bank_rec_header()
    {
        $this->form_validation->set_rules('bankRecAsOf', 'GL Code', 'trim|required|validate_date');
        //$this->form_validation->set_rules('month', 'GL Type', 'trim|required');
        $this->form_validation->set_rules('description', 'Segment', 'trim|required');
        $this->form_validation->set_rules('bankGLAutoID', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $companyID = current_companyID();
            $bankGLAutoID = $this->input->post('bankGLAutoID');
            $validate = $this->db->query("SELECT * FROM srp_erp_bankrecmaster WHERE bankGLAutoID = {$bankGLAutoID} AND approvedYN = 0 AND companyID={$companyID}")->row_array();
            if (!empty($validate)) {
                $this->session->set_flashdata('e', 'You can not create new bank reconciliation. please confirm pending aprovals');
                echo json_encode(array('status' => false));
                exit;
            }
            //$date = $this->input->post('bankRecAsOf');
            $date_format_policy = date_format_policy();
            $bankRecAsOf = $this->input->post('bankRecAsOf');
            $date = input_format_date($bankRecAsOf, $date_format_policy);

            $validate = $this->db->query("select * from srp_erp_bankrecmaster  where bankGLAutoID = {$bankGLAutoID} AND bankRecAsOf >='{$date}' AND companyID={$companyID}")->row_array();
            if (!empty($validate)) {
                $this->session->set_flashdata('e', 'You can not create new bank reconciliation. As of Date should be greater than last Bank Reconciliation Date');
                echo json_encode(array('status' => false));
                exit;
            }
            $month = explode('-', trim($date));
            $d['year'] = $month[0];
            $d['month'] = $month[1];
            $startdate = $d['year'] . '-' . $d['month'] . '-01';
            $endDate = $d['year'] . '-' . $d['month'] . '-31';
            $validate = $this->db->query("SELECT * FROM srp_erp_bankledger WHERE bankGLAutoID = {$bankGLAutoID}  AND documentDate <='{$date}' ")->row_array();
            if (empty($validate)) {
                $this->session->set_flashdata('e', 'No records found to create bank reconciliation for this month.');
                echo json_encode(array('status' => false));
                exit;
            }

            echo json_encode($this->Bank_rec_model->save_bank_rec_header());

        }
    }

    function save_bank_rec_details()
    {
        $clearedYN = $this->input->post('clearedYN');
        $bankRecAutoID = $this->input->post('bankRecAutoID');

        echo json_encode($this->Bank_rec_model->save_bank_rec_details($clearedYN, $bankRecAutoID));

    }

    function bank_rec_confirm()
    {
        echo json_encode($this->Bank_rec_model->bank_rec_confirm());
    }

    function save_bank_rec_approval()
    {
        $system_code = trim($this->input->post('bankRecAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        if($status==1){
            $approvedYN=checkApproved($system_code,'BRC',$level_id);
            if($approvedYN){
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            }else{
                $this->db->select('bankRecAutoID');
                $this->db->where('bankRecAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_bankrecmaster');
                $po_approved = $this->db->get()->row_array();
                if(!empty($po_approved)){
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                }else{
                    $this->form_validation->set_rules('bankRecAutoID', 'bankRecAutoID', 'trim|required');
                    $this->form_validation->set_rules('GLAutoID', 'GLAutoID', 'trim|required');
                    $this->form_validation->set_rules('Level', 'Level', 'trim|required');
                    if($this->input->post('status') ==2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Bank_rec_model->save_bank_rec_approval());
                    }
                }
            }
        }else if($status==2){
            $this->db->select('bankRecAutoID');
            $this->db->where('bankRecAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_bankrecmaster');
            $po_approved = $this->db->get()->row_array();
            if(!empty($po_approved)){
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            }else{
                $rejectYN=checkApproved($system_code,'BRC',$level_id);
                if(!empty($rejectYN)){
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                }else{
                    $this->form_validation->set_rules('bankRecAutoID', 'bankRecAutoID', 'trim|required');
                    $this->form_validation->set_rules('GLAutoID', 'GLAutoID', 'trim|required');
                    $this->form_validation->set_rules('Level', 'Level', 'trim|required');
                    if($this->input->post('status') ==2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Bank_rec_model->save_bank_rec_approval());
                    }
                }
            }
        }
    }


    function bank_rec_confirmation()
    {
        $_POST['bankRecAutoID'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('bankRecAutoID'));
        $_POST['GLAutoID'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('GLAutoID'));
        $data['details'] = $this->Bank_rec_model->getconfirmationdetails();
        $data['master'] = $this->Bank_rec_model->get_bank_rec_header();

        $data['bankRecAutoID'] = $this->input->post('bankRecAutoID');
        $html = $this->load->view('system/bank_rec/erp_bank_rec_print', $data, true);

        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            /*,$data['extra']['master']['approvedYN']*/
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function bank_rec_book_balance()
    {
        $_POST['bankRecAutoID'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('bankRecAutoID'));
        $_POST['GLAutoID'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('GLAutoID'));
        $data['details'] = $this->Bank_rec_model->getunconfirmedDetails();
        $data['master'] = $this->Bank_rec_model->get_bank_rec_header();
        $data['GLdetails'] = $this->Bank_rec_model->getGLdetails($_POST['GLAutoID']);
        $GLAutoID = trim($this->input->post('GLAutoID'));
       /* $openingbalance = $this->db->query("Select receipt,payment,receipt-payment as balance from(SELECT SUM( IF ( transactionType = 2, bankcurrencyAmount, 0 ) ) payment, SUM( IF ( transactionType = 1, bankcurrencyAmount, 0 ) ) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID =d.bankRecMonthID
WHERE d.bankGLAutoID ={$GLAutoID} AND d.bankRecMonthID is not NULL )tt;")->row_array();*/

      $asOfDate=$data['master']['bankRecAsOf'];
      $date_format_policy     = date_format_policy();
      $date = $this->input->post('asOfDate');
      $asOfDate = input_format_date($asOfDate, $date_format_policy);

       $companyID=current_companyID();
      $openingbalance = $this->db->query( "SELECT receipt, payment, receipt - payment AS balance FROM ( SELECT SUM( IF ( transactionType = 2, bankcurrencyAmount, 0 ) ) payment, SUM( IF ( transactionType = 1, bankcurrencyAmount, 0 ) ) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID = d.bankRecMonthID WHERE m.companyID = {$companyID} AND documentDate <= '{$asOfDate}' AND d.bankGLAutoID = {$GLAutoID} AND ( d.bankRecMonthID IS  NULL OR bankRecMonthID IN ( SELECT bankRecAutoID FROM srp_erp_bankrecmaster WHERE bankGLAutoID = {$GLAutoID} AND bankRecAsOf <= '{$asOfDate}' ) ) ) tt;")->row_array();

        $data['openingbalance'] = $openingbalance['balance'];
        $data['bankRecAutoID'] = $this->input->post('bankRecAutoID');
        $html = $this->load->view('system/bank_rec/erp_bank_rec_book_balance', $data, true);
        if ($this->input->post('html')) {

            echo $html;
        } else {

            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function get_post_dated_cheques()
    {
        $companyID = current_companyID();

        //documentDate
        $date = date('Y-m-d');
        $data['details'] = $this->db->query("SELECT bankLedgerAutoID,remainIn, CURDATE() + INTERVAL remainIn DAY as remainingDays,documentSystemCode,bankName,clearedYN, documentDate, chequeDate, transactionType, partyCode, partyName, bankCurrency, bankCurrencyAmount, bankCurrencyDecimalPlaces, chequeNo, isThirdPartyCheque, bankGLAutoID FROM srp_erp_bankledger WHERE companyID='{$companyID}' AND clearedYN = 0 AND chequeNo <> '' AND chequeDate >= '{$date}' AND transactionType=1
")->result_array();


        $bookbalance = $this->db->query("SELECT receipt - payment AS balance FROM (SELECT SUM(IF(transactionType = 2, bankcurrencyAmount, 0)) payment, SUM(IF(transactionType = 1, bankcurrencyAmount, 0)) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID = d.bankRecMonthID WHERE approvedYN = 1 AND clearedYN = 1) tt")->row_array();
        $data['bookbalance'] = $bookbalance['balance'];

        $data['recieptAmount'] = $this->db->query("SELECT bankName, bankGLAutoID,sum(bankCurrencyAmount) as totalbankCurrencyAmount FROM srp_erp_bankledger WHERE companyID='{$companyID}' AND clearedYN = 0 AND chequeNo <> '' AND chequeDate >= '{$date}' AND transactionType=1 group by bankGLAutoID")->result_array();

        $html = $this->load->view('system/bank_rec/erp_post_dated_cheque_data', $data, true);
        echo $html;
    }

    function get_post_dated_cheques_payment()
    {
        $date = date('Y-m-d');
        $companyID = current_companyID();
        $data['details'] = $this->db->query("SELECT bankLedgerAutoID,remainIn, CURDATE() + INTERVAL remainIn DAY as remainingDays,documentSystemCode,bankName,clearedYN, documentDate, chequeDate, transactionType, partyCode, partyName, bankCurrency, bankCurrencyAmount, bankCurrencyDecimalPlaces, chequeNo, isThirdPartyCheque, bankGLAutoID FROM srp_erp_bankledger WHERE clearedYN = 0 AND chequeNo <> '' AND chequeDate >= '{$date}' AND transactionType=2 AND companyID='{$companyID}'
")->result_array();
        /*$data['details2']= $this->db->query("SELECT DATEDIFF(chequeDate,documentDate) as remainingDays,documentSystemCode,bankName,clearedYN, documentDate, chequeDate, transactionType, partyCode, partyName, bankCurrency, bankCurrencyAmount, bankCurrencyDecimalPlaces, chequeNo, isThirdPartyCheque, bankGLAutoID FROM srp_erp_bankledger WHERE clearedYN = 0 AND chequeNo <> '' AND chequeDate > documentDate AND transactionType=2
")->result_array();*/

        $data['recieptAmount'] = $this->db->query("SELECT bankName, bankGLAutoID,sum(bankCurrencyAmount) as totalbankCurrencyAmount FROM srp_erp_bankledger WHERE clearedYN = 0 AND chequeNo <> '' AND chequeDate >= '{$date}' AND transactionType=2 AND companyID='{$companyID}' group by bankGLAutoID")->result_array();

        $bookbalance = $this->db->query("SELECT receipt - payment AS balance FROM (SELECT SUM(IF(transactionType = 2, bankcurrencyAmount, 0)) payment, SUM(IF(transactionType = 1, bankcurrencyAmount, 0)) AS receipt FROM srp_erp_bankrecmaster m LEFT JOIN srp_erp_bankledger d ON m.bankRecAutoID = d.bankRecMonthID WHERE approvedYN = 1 AND clearedYN = 1) tt")->row_array();
        $data['bookbalance'] = $bookbalance['balance'];
        $html = $this->load->view('system/bank_rec/erp_post_dated_cheque_payment_data.php', $data, true);
        echo $html;

    }

    function ajax_update_postdated_cheque_remainIn()
    {
        $result = $this->Bank_rec_model->xeditable_update('srp_erp_bankledger', 'bankLedgerAutoID');
        if ($result) {
            echo json_encode(array('error' => 0, 'message' => 'updated'));
        } else {
            echo json_encode(array('error' => 1, 'message' => 'updated Fail'));
        }
    }

    /*bank transfer*/
    function getexchangerate()
    {
        $bankFrom = $this->input->post('bankFrom');
        $bankTo = $this->input->post('bankTo');
        $currencyFrom = $this->Bank_rec_model->get_glcode_currency($bankFrom);
        $currencyTo = $this->Bank_rec_model->get_glcode_currency($bankTo);

        // $data = $this->Bank_rec_model->getexchangerate($currencyFrom['bankCurrencyID'], $currencyTo['bankCurrencyID']);
        $data = $this->Bank_rec_model->getexchangerate($currencyFrom['bankCurrencyID'], $currencyTo['bankCurrencyID']);

        $book = $this->db->query("select GLAutoID,srp_erp_chartofaccounts.GLDescription,(SUM(if(srp_erp_bankledger.transactionType = 1,srp_erp_bankledger.bankcurrencyAmount,0)) - SUM(if(srp_erp_bankledger.transactionType = 2,srp_erp_bankledger.bankcurrencyAmount,0))) as bookBalance,(SUM(if(srp_erp_bankledger.transactionType = 2 AND srp_erp_bankrecmaster.approvedYN = 1,srp_erp_bankledger.bankcurrencyAmount,0)) - SUM(if(srp_erp_bankledger.transactionType = 1 AND srp_erp_bankrecmaster.approvedYN = 1,srp_erp_bankledger.bankcurrencyAmount,0))) as bankBalance from srp_erp_chartofaccounts INNER JOIN srp_erp_bankledger ON srp_erp_chartofaccounts.GLAutoID = srp_erp_bankledger.bankGLAutoID LEFT JOIN srp_erp_bankrecmaster on srp_erp_bankrecmaster.bankrecAutoID = srp_erp_bankledger.bankRecMonthID WHERE srp_erp_chartofaccounts.GLAutoID='{$bankFrom}'  group by srp_erp_chartofaccounts.GLAutoID")->row_array();
        if (empty($book)) {
            $book['bookBalance'] = 0;
        } else {
            $book['bookBalance'] = number_format($book['bookBalance'], 2);
        }

        echo json_encode(array('error' => 0, $data, 'decimal' => $currencyTo['bankCurrencyDecimalplaces'], 'fromBankCurrencyID' => $currencyFrom['bankCurrencyID'], 'toBankCurrencyID' => $currencyTo['bankCurrencyID'], 'fromBankCurrentBalance' => $book['bookBalance']));
    }

    function save_bank_transaction()
    {
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        $this->form_validation->set_rules('description', 'Segment', 'trim|required');
        $this->form_validation->set_rules('bankFrom', 'Amount', 'trim|required');
        $this->form_validation->set_rules('fromAmount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('bankTo', 'Amount', 'trim|required');
        $this->form_validation->set_rules('toAmount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('transferType', 'Type', 'trim|required');
        $transferType = $this->input->post('transferType');
        if($transferType==2){
            $this->form_validation->set_rules('chequeNo', 'Cheque No', 'trim|required');
            $this->form_validation->set_rules('chequeDate', 'Cheque Date', 'trim|required');
            $this->form_validation->set_rules('nameOnCheque', 'Name On Cheque', 'trim|required');
        }
        $accountPayeeOnly = 0;
        if (!empty($this->input->post('accountPayeeOnly'))) {
            $accountPayeeOnly = 1;
        }



        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            $date_format_policy = date_format_policy();
            //$trnsfrdDate = $this->input->post('transferedDate');
            $date_format_policy = date_format_policy();
            $trfrDte = $this->input->post('transferedDate');
            $trnsdDate = input_format_date($trfrDte, $date_format_policy);
            $transferedDate = input_format_date($trnsdDate, $date_format_policy);

            if ($transferedDate >= $financePeriod['dateFrom'] && $transferedDate <= $financePeriod['dateTo']) {
                $bankTransferAutoID = $this->input->post('bankTransferAutoID');
                if ($bankTransferAutoID == 0) {
                    $this->load->library('sequence');
                     $data['bankTransferCode'] = $this->sequence->sequence_generator('BT');

                    $data['companyID'] = current_companyID();
                    $data['companyCode'] = current_companyCode();
                }
                // $period = explode('|', trim($this->input->post('financeyear_period')));
                $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
                $FYBegin = input_format_date($year[0], $date_format_policy);
                $FYEnd = input_format_date($year[1], $date_format_policy);

                $data['FYBegin'] = trim($FYBegin);
                $data['FYEnd'] = trim($FYEnd);
                $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
                /*$data['FYPeriodDateFrom'] = trim($period[0]);
                $data['FYPeriodDateTo'] = trim($period[1]);*/
                $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
                $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
                $data['fromBankGLAutoID'] = $this->input->post('bankFrom');
                $data['toBankGLAutoID'] = $this->input->post('bankTo');
                $data['narration'] = $this->input->post('description');
                $data['transferedAmount'] = $this->input->post('fromAmount');
                $data['fromBankCurrencyID'] = fetch_currency_ID($this->input->post('fromBankCurrencyCode'));//$this->input->post('fromBankCurrencyID');
                $data['toBankCurrencyID'] = fetch_currency_ID($this->input->post('toBankCurrencyCode'));//$this->input->post('toBankCurrencyID');
                $data['toBankCurrencyAmount'] = $this->input->post('toAmount');
                $data['transferedDate'] = $transferedDate;
                $data['referenceNo'] = $this->input->post('referenceNo');
                $data['fromBankCurrentBalance'] = $this->removecommanumber($this->input->post('fromBankCurrentBalance'));
                $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
                $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
                $default_currency = currency_conversion($this->input->post('toBankCurrencyCode'), $data['companyLocalCurrency']);
                //  $data['companyLocalExchangeRate'] = $default_currency['conversion'];
                $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
                $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
                $reporting_currency = currency_conversion($this->input->post('fromBankCurrencyCode'), $data['companyReportingCurrency']);

                $data['transferType'] = $transferType;
                if($transferType==2){
                    $chqDate = $this->input->post('chequeDate');
                    $chequeDate = input_format_date($chqDate, $date_format_policy);

                    $data['chequeNo'] = $this->input->post('chequeNo');
                    $data['chequeDate'] = $chequeDate;
                    $data['nameOnCheque'] = $this->input->post('nameOnCheque');
                    $data['accountPayeeOnly'] = $accountPayeeOnly;
                }

                $data['exchangeRate'] = $this->input->post('conversion');
                /*if ($data['companyLocalCurrency'] == $this->input->post('toBankCurrencyCode')) {
                    $data['companyLocalExchangeRate'] = $this->input->post('conversion');
                } else {
                    $data['companyLocalExchangeRate'] = 1 / $default_currency['conversion'];
                }*/
                $loc_exchange = currency_conversion($this->input->post('fromBankCurrencyCode'),$this->common_data['company_data']['company_default_currency']);
                $data['companyLocalExchangeRate'] = $loc_exchange['conversion'];

                $rep_exchange = currency_conversion($this->input->post('fromBankCurrencyCode'),$this->common_data['company_data']['company_reporting_currency']);
                $data['companyReportingExchangeRate'] = $rep_exchange['conversion'];
                /*if ($data['companyReportingCurrency'] == $this->input->post('toBankCurrencyCode')) {
                    $data['companyReportingExchangeRate'] = $this->input->post('conversion');
                } else {
                    $data['companyReportingExchangeRate'] = 1 / $reporting_currency['conversion'];
                }*/


                if ($bankTransferAutoID == 0) {
                    if($transferType==2) {
                        $this->db->where('GLAutoID', $data['fromBankGLAutoID']);
                        $this->db->update('srp_erp_chartofaccounts', array('bankCheckNumber' => $data['chequeNo']));
                    }
                    $data['createdPCID'] = $this->common_data['current_pc'];
                    $data['createdUserID'] = $this->common_data['current_userID'];
                    $data['createdUserName'] = $this->common_data['current_user'];
                    $data['createdDateTime'] = $this->common_data['current_date'];
                    $this->db->insert('srp_erp_banktransfer', $data);

                    $this->session->set_flashdata('s', 'Bank Transfer : Draft Successfully.');
                    echo json_encode(array('status' => true,'masterID'=> $this->db->insert_id()));
                } else {
                    if($transferType==2) {
                        $this->db->where('GLAutoID', $data['fromBankGLAutoID']);
                        $this->db->update('srp_erp_chartofaccounts', array('bankCheckNumber' => $data['chequeNo']));
                    }
                    $this->db->where('bankTransferAutoID', $bankTransferAutoID);
                    $this->db->update('srp_erp_banktransfer', $data);
                    $this->session->set_flashdata('s', 'Bank Transfer : Updated Successfully.');
                    echo json_encode(array('status' => true,'masterID'=>$bankTransferAutoID));
                }


                // echo json_encode($this->Bank_rec_model->save_bank_rec_header());
            } else {
                $this->session->set_flashdata('e', 'Document Date not between Financial period !');
                echo json_encode(FALSE);
            }
        }
    }

    function fetch_bank_transaction()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("bankTransferAutoID,DATE_FORMAT(transferedDate,'$convertFormat') AS transferedDate ,bankTransferCode,transferedAmount,referenceNo,narration,a.GLDescription as frombank,b.GLDescription as toBank,srp_erp_banktransfer.confirmedYN as confirmedYN ,srp_erp_banktransfer.approvedYN as approvedYN,srp_erp_banktransfer.createdUserID as createdUserID,transferType,fromBankGLAutoID,srp_erp_banktransfer.confirmedByEmpID as confirmedByEmp ");
        $this->datatables->from('srp_erp_banktransfer');
        $this->datatables->join('srp_erp_chartofaccounts a', 'fromBankGLAutoID=a.GLAutoID', 'LEFT');
        $this->datatables->join('srp_erp_chartofaccounts b', 'toBankGLAutoID=b.GLAutoID', 'LEFT');
        $this->datatables->where('srp_erp_banktransfer.companyID', current_companyID());
        $this->datatables->add_column('edit', ' $1 ', 'bank_transaction_edit(bankTransferAutoID,confirmedYN,approvedYN,createdUserID,transferType,fromBankGLAutoID,confirmedByEmp)');
        $this->datatables->add_column('confirm', '$1', 'confirm_user_approval_drilldown(confirmedYN,"BT",bankTransferAutoID)');
        $this->datatables->add_column('approvedYN', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"BT",bankTransferAutoID)');
        $this->datatables->add_column('frombank', '$1', 'trim_value(frombank,25)');
        $this->datatables->add_column('toBank', '$1', 'trim_value(toBank,25)');

        $this->datatables->add_column('transferedAmount', '<div class="pull-right"> $1 </div>', 'format_number(transferedAmount,2)');
        /*    $this->datatables->group_by('c.systemAccountCode , c.GLSecondaryCode , c.GLDescription , c.isBank , c.bankName , c.bankBranch , c.bankSwiftCode , c.bankAccountNumber , c.bankCurrencyID , c.bankCurrencyCode');
            $this->datatables->add_column('totalAmount', '<div class="pull-right"> $1 </div>', 'format_number(SumOfbankCurrencyAmount,bankCurrencyDecimalPlaces)');
            $this->datatables->add_column('edit', ' $1 ', 'load_bank_rec_action(GLAutoID)');*/
        echo $this->datatables->generate();


    }

    function getbankTransferform()
    {
        $data['bankTransferAutoID'] = $this->input->post('bankTransferAutoID');
        if ($data['bankTransferAutoID'] == '') {
            $data['bankTransferAutoID'] = 0;
        }
        $master = $this->Bank_rec_model->bank_transfer_master($data['bankTransferAutoID']); /*$this->db->query("SELECT srp_erp_banktransfer.*, fromCurrency.bankCurrencyCode as fromcurrency, toCurrency.bankCurrencyCode as tocurrency FROM srp_erp_banktransfer LEFT JOIN srp_erp_chartofaccounts AS fromCurrency ON fromBankGLAutoID = fromCurrency.GLAutoID LEFT JOIN srp_erp_chartofaccounts AS toCurrency ON toBankGLAutoID = toCurrency.GLAutoID WHERE bankTransferAutoID = {$data['bankTransferAutoID']}")->row_array();*/

        $data['master'] = $master;
        $html = $this->load->view('system/bank_rec/erp_bank_transfer_form.php', $data, true);
        echo $html;
    }

    function bank_transfer_confirmation()
    {
        $this->form_validation->set_rules('financeyear', 'GL Code', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Finance year period', 'trim|required');
        $this->form_validation->set_rules('description', 'Segment', 'trim|required');
        $this->form_validation->set_rules('bankFrom', 'Amount', 'trim|required');
        $this->form_validation->set_rules('fromAmount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('bankTo', 'Amount', 'trim|required');
        $this->form_validation->set_rules('toAmount', 'Amount', 'trim|required');



        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            $date_format_policy = date_format_policy();
            //$trnsfrdDate = $this->input->post('transferedDate');
            $date_format_policy = date_format_policy();
            $trfrDte = $this->input->post('transferedDate');
            $trnsdDate = input_format_date($trfrDte, $date_format_policy);
            $transferedDate = input_format_date($trnsdDate, $date_format_policy);

            if ($transferedDate >= $financePeriod['dateFrom'] && $transferedDate <= $financePeriod['dateTo']) {
                $bankTransferAutoID = $this->input->post('bankTransferAutoID');
                if ($bankTransferAutoID == 0) {
                    $doc = get_document_code('BT');
                    $this->load->library('sequence');
                    $data['bankTransferCode'] = $this->sequence->sequence_generator($doc['prefix']); //generate_seq_number($doc['prefix'], $doc['serialNo'], $doc['startSerialNo']);
                    $data['companyID'] = current_companyID();
                    $data['companyCode'] = current_companyCode();
                }
                // $period = explode('|', trim($this->input->post('financeyear_period')));
                $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
                /*$FYBegin = input_format_date($year[0], $date_format_policy);
                $FYEnd = input_format_date($year[1], $date_format_policy);*/

                /*$data['FYBegin'] = trim($FYBegin);
                $data['FYEnd'] = trim($FYEnd);*/
                $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
                /*$data['FYPeriodDateFrom'] = trim($period[0]);
                $data['FYPeriodDateTo'] = trim($period[1]);*/
                $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
                $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
                $data['fromBankGLAutoID'] = $this->input->post('bankFrom');
                $data['toBankGLAutoID'] = $this->input->post('bankTo');
                $data['narration'] = $this->input->post('description');
                $data['transferedAmount'] = $this->input->post('fromAmount');
                $data['fromBankCurrencyID'] = fetch_currency_ID($this->input->post('fromBankCurrencyCode'));//$this->input->post('fromBankCurrencyID');
                $data['toBankCurrencyID'] = fetch_currency_ID($this->input->post('toBankCurrencyCode'));//$this->input->post('toBankCurrencyID');
                $data['toBankCurrencyAmount'] = $this->input->post('toAmount');
                $data['transferedDate'] = $transferedDate;
                $data['referenceNo'] = $this->input->post('referenceNo');
                $data['fromBankCurrentBalance'] = $this->removecommanumber($this->input->post('fromBankCurrentBalance'));
                $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
                $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
                $default_currency = currency_conversion($this->input->post('toBankCurrencyCode'), $data['companyLocalCurrency']);
                //  $data['companyLocalExchangeRate'] = $default_currency['conversion'];
                $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
                $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
                $reporting_currency = currency_conversion($this->input->post('fromBankCurrencyCode'), $data['companyReportingCurrency']);


                $data['exchangeRate'] = $this->input->post('conversion');
                /*if ($data['companyLocalCurrency'] == $this->input->post('toBankCurrencyCode')) {
                    $data['companyLocalExchangeRate'] = $this->input->post('conversion');
                } else {
                    $data['companyLocalExchangeRate'] = 1 / $default_currency['conversion'];
                }
                if ($data['companyReportingCurrency'] == $this->input->post('toBankCurrencyCode')) {
                    $data['companyReportingExchangeRate'] = $this->input->post('conversion');
                } else {
                    $data['companyReportingExchangeRate'] = 1 / $reporting_currency['conversion'];
                }*/
                $loc_exchange = currency_conversion($this->input->post('fromBankCurrencyCode'),$this->common_data['company_data']['company_default_currency']);
                $data['companyLocalExchangeRate'] = $loc_exchange['conversion'];

                $rep_exchange = currency_conversion($this->input->post('fromBankCurrencyCode'),$this->common_data['company_data']['company_reporting_currency']);
                $data['companyReportingExchangeRate'] = $rep_exchange['conversion'];



                    $this->db->where('bankTransferAutoID', $bankTransferAutoID);
                    $this->db->update('srp_erp_banktransfer', $data);
                    echo json_encode($this->Bank_rec_model->bank_transfer_confirmation());



                // echo json_encode($this->Bank_rec_model->save_bank_rec_header());
            } else {
                $this->session->set_flashdata('e', 'Document Date not between Financial period !');
                echo json_encode(FALSE);
            }
        }

    }

    function delete_banktransfer_master()
    {
        echo json_encode($this->Bank_rec_model->delete_banktransfer_master());
    }

    function fetch_transfer_approval()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("bankTransferAutoID, DATE_FORMAT(transferedDate,'$convertFormat') AS transferedDate, bankTransferCode, round(transferedAmount,2) as transferedAmount, referenceNo, narration, a.GLDescription AS frombank, b.GLDescription AS toBank, srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,srp_erp_banktransfer.confirmedYN,srp_erp_banktransfer.approvedYN,transferedAmount", false);
        $this->datatables->from('srp_erp_banktransfer');
        $this->datatables->join('srp_erp_chartofaccounts a', 'fromBankGLAutoID=a.GLAutoID', 'LEFT');
        $this->datatables->join('srp_erp_chartofaccounts b', 'toBankGLAutoID=b.GLAutoID', 'LEFT');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_banktransfer.bankTransferAutoID AND srp_erp_documentapproved.approvalLevelID = srp_erp_banktransfer.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_banktransfer.currentLevelNo');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'BT');
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.documentID', 'BT');
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->where('srp_erp_banktransfer.companyID', current_companyID());
        $this->datatables->where('srp_erp_approvalusers.companyID', current_companyID());
        $this->datatables->add_column('transferedAmount', '<div class="pull-right"> $1 </div>', 'format_number(transferedAmount,2)');
        $this->datatables->add_column('bankTransferCode', '$1', 'approval_change_modal(bankTransferCode,bankTransferAutoID,documentApprovedID,approvalLevelID,approvedYN,BT)');
        $this->datatables->add_column('detail', '<b>FROM : </b> $1 <b> &nbsp;&nbsp;To : </b> $2 <b> Date : </b> $3 &nbsp;&nbsp;<b>Amount : </b> $4', 'frombank,toBank,transferedDate,transferedAmount');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "BT", bankTransferAutoID)');
        $this->datatables->add_column('edit', '$1', 'bank_transfer_approval(bankTransferAutoID,approvalLevelID,approvedYN,documentApprovedID)');
      echo   $this->datatables->generate();

    }

    function bank_transfer_view()
    {
        $_POST['bankTransferAutoID'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('bankTransferAutoID'));
        $_POST['GLAutoID'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('GLAutoID'));
        $data['master'] = $this->Bank_rec_model->bank_transfer_master($_POST['bankTransferAutoID']);
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        $html = $this->load->view('system/bank_rec/erp_bank_transfer_view', $data, true);
        if ($this->input->post('html')) {

            echo $html;
        } else {

            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }


    function save_bank_transfer_approval()
    {
        $system_code = trim($this->input->post('bankTransferAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        if($status==1){
            $approvedYN=checkApproved($system_code,'BT',$level_id);
            if($approvedYN){
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            }else{
                $this->db->select('bankTransferAutoID');
                $this->db->where('bankTransferAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_banktransfer');
                $po_approved = $this->db->get()->row_array();
                if(!empty($po_approved)){
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                }else{
                    $this->form_validation->set_rules('bankTransferAutoID', 'bankTransferAutoID', 'trim|required');
                    $this->form_validation->set_rules('Level', 'Level', 'trim|required');
                    if($this->input->post('status') ==2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Bank_rec_model->confirm_bank_approval());
                    }
                }
            }
        }else if($status==2){
            $this->db->select('bankTransferAutoID');
            $this->db->where('bankTransferAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_banktransfer');
            $po_approved = $this->db->get()->row_array();
            if(!empty($po_approved)){
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            }else{
                $rejectYN=checkApproved($system_code,'BT',$level_id);
                if(!empty($rejectYN)){
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                }else{
                    $this->form_validation->set_rules('bankTransferAutoID', 'bankTransferAutoID', 'trim|required');
                    $this->form_validation->set_rules('Level', 'Level', 'trim|required');
                    if($this->input->post('status') ==2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Bank_rec_model->confirm_bank_approval());
                    }
                }
            }
        }
    }

    function refer_bank_transaction()
    {

        $bankTransferAutoID = $this->input->post('bankTransferAutoID');

        $this->db->select('approvedYN,bankTransferCode');
        $this->db->where('bankTransferAutoID', trim($bankTransferAutoID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_banktransfer');
        $approved_bank_transfer = $this->db->get()->row_array();
        if (!empty($approved_bank_transfer)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_bank_transfer['bankTransferCode']));
        }else
        {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($bankTransferAutoID, 'BT');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }


    }


    function getcurrencyID()
    {
        $bankFrom = $this->input->post('bankFrom');
        $bankTo = $this->input->post('bankTo');
        if ($bankFrom != '') {
            $currencyFrom = $this->Bank_rec_model->get_glcode_currency($bankFrom);
        } else {
            $currencyFrom['bankCurrencyCode'] = '';
        }
        if ($bankTo != '') {
            $currencyTo = $this->Bank_rec_model->get_glcode_currency($bankTo);
        } else {
            $currencyTo['bankCurrencyCode'] = '';
        }

        echo json_encode(array('error' => 0, 'fromBankCurrencyCode' => $currencyFrom['bankCurrencyCode'], 'toBankCurrencyCode' => $currencyTo['bankCurrencyCode'] , 'bankCurrencyDecimalplaces' => $currencyFrom['bankCurrencyDecimalplaces']));
    }


    function removecommanumber($amount)
    {
        $a = $amount;
        $b = str_replace(',', '', $a);

        if (is_numeric($b)) {
            $a = $b;
        }
        return $a;
    }

    function fetch_bank_register_entry()
    {

        $dateto = $this->input->post('dateto');
        $datefrom = $this->input->post('datefrom');
        $date_format_policy = date_format_policy();
        $to = 'false';
        $from = 'false';
        $filter = 'c.companyID=' . current_companyID();
        if ($dateto != '') {
            $to = input_format_date($dateto, $date_format_policy);
            $filter .= " AND b.documentDate <='{$to}'";
        }
        if ($datefrom != '') {
            $from = input_format_date($datefrom, $date_format_policy);
            $filter .= " AND b.documentDate >='{$from}'";
        }


        /*echo $dateto;
        echo $datefrom;*/
        $this->datatables->select("c.systemAccountCode, c.GLSecondaryCode, c.GLDescription, c.isBank, c.bankName, c.bankBranch, c.bankSwiftCode, c.bankAccountNumber, c.bankCurrencyID, c.bankCurrencyCode, sum(if(transactionType=1,b.bankCurrencyAmount,0)) + (sum(if(transactionType=2,b.bankCurrencyAmount,0))*-1) as SumOfbankCurrencyAmount,b.bankCurrencyDecimalPlaces,GLAutoID,b.documentType,b.documentMasterAutoID");
        $this->datatables->from('srp_erp_chartofaccounts AS c');
        $this->datatables->join('srp_erp_bankledger AS b', 'c.GLAutoID = b.bankGLAutoID', 'INNER');
        $this->datatables->where('c.isBank', 1);
        $this->datatables->where($filter);
        $this->datatables->group_by('c.systemAccountCode , c.GLSecondaryCode , c.GLDescription , c.isBank , c.bankName , c.bankBranch , c.bankSwiftCode , c.bankAccountNumber , c.bankCurrencyID , c.bankCurrencyCode');
        $this->datatables->add_column('totalAmount', '<div class="pull-right"> $1 </div>', 'format_number(SumOfbankCurrencyAmount,2)');
        $this->datatables->add_column('edit', ' $1 ', 'load_bank_register_action(GLAutoID,' . "$from" . ',' . "$to" . ')');
        echo $this->datatables->generate();
    }

    function load_bank_register_details()
    {
        $GLAutoID = $this->input->post('GLAutoID');
        $data_arr = $this->input->post('data_arr');

        $date = explode('_', $data_arr);

        $to = $this->input->post('dateto');
        $from = $this->input->post('datefrom');
        if ($from == '' ) {

            $from = date('Y-m-d');
        }
        if ($to == '') {
            $to = date('Y-m-t');

        }

            $date_format_policy = date_format_policy();

            $from = input_format_date($from, $date_format_policy);
            $to = input_format_date($to, $date_format_policy);

        $filter = "";
        if ($from != '') {
            $filter .= ' AND documentDate >="' . $from . '"';

            $qry2 = "SELECT documentMasterAutoID,m.documentType, sum(IF(transactionType = 1, bankCurrencyAmount, 0)) as bankCurrencyAmount, sum(IF(transactionType = 2, bankCurrencyAmount*-1, 0)) as deduct, sum(IF(transactionType = 1, bankCurrencyAmount, 0))+ sum(IF(transactionType = 2, bankCurrencyAmount*-1, 0)) as total, documentDate, memo, chequeNo, documentSystemCode, transactionType, partyType, partyCode, partyName, bankCurrency, bankCurrencyDecimalPlaces, bankCurrencyAmount AS amount, FORMAT(bankCurrencyAmount, bankCurrencyDecimalPlaces) AS bankCurrencyAmount, IF(m.bankRecMonthID != '', clearedYN, 0) AS clearedYN, m.bankRecMonthID FROM srp_erp_bankledger m WHERE m.bankGLAutoID = {$GLAutoID} AND documentDate < '{$from}' group by bankGLAutoID ORDER BY documentDate ASC";
            $data['openingbalance'] = $this->db->query($qry2)->row_array();

        }
        if ($to != '') {
            $filter .= ' AND documentDate <="' . $to . '"';
        }
        $qry = "SELECT documentMasterAutoID,m.documentType,documentDate,memo,chequeNo, documentSystemCode, transactionType, partyType, partyCode, partyName, bankCurrency,bankCurrencyDecimalPlaces, bankCurrencyAmount as amount, FORMAT(bankCurrencyAmount, bankCurrencyDecimalPlaces) as bankCurrencyAmount, IF(m.bankRecMonthID !='',clearedYN,0) as clearedYN, m.bankRecMonthID FROM srp_erp_bankledger m WHERE m.bankGLAutoID = {$GLAutoID} {$filter} order by documentDate asc";
        $data['details'] = $this->db->query($qry)->result_array();
        $currency = $this->db->query("SELECT GLAutoID, bankCurrencyID FROM `srp_erp_chartofaccounts` WHERE GLAutoID = {$GLAutoID}")->row_array();

        $data['currencycode'] = fetch_currency_code($currency['bankCurrencyID']);

       $data['GLdetail'] =  $this->db->query("SELECT systemAccountCode,GLSecondaryCode,GLDescription,bankName,bankBranch,bankAccountNumber,bankCurrencyCode FROM `srp_erp_chartofaccounts` WHERE `GLAutoID` = {$GLAutoID} ")->row_array();
        $html = $this->load->view('system/bank_register/erp_bank_register_generated_data', $data, true);
        echo $html;

    }

    function save_bank_rec_add_row()
    {
        $this->form_validation->set_rules('GLAutoID', 'GLAutoID', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'documentDate', 'trim|required|validate_date');
        $this->form_validation->set_rules('type', 'type', 'trim|required');
        $this->form_validation->set_rules('narration', 'narration', 'trim|required');
        $this->form_validation->set_rules('segmentID', 'segment', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $type = $this->input->post('type');
            if ($type == 1) {
                $this->Bank_rec_model->bankrec_recieved_account();
            }
            if ($type == 2) {
                $this->Bank_rec_model->bankrec_payment_account();
            }

        }

    }

    function get_assignedcurrency_company()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $data['details'] = $this->Bank_rec_model->get_assignedcurrency_company($companyID);
        $html = $this->load->view('system/currency_exchange/ajax/ajax_srp_erp_currency_exchangeMasterData', $data, true);
        echo $html;
    }

    function detail_assignedcurrency_company()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $mastercurrencyassignAutoID = $this->input->post('mastercurrencyassignAutoID');
        $data['status'] = $this->input->post('status');
        $data['mastercurrencyassignAutoID'] = $mastercurrencyassignAutoID;
        $curr = $this->db->query("select CurrencyCode from srp_erp_companycurrencyassign where companyID={$companyID} AND currencyassignAutoID={$mastercurrencyassignAutoID}")->row_array();
        $data['basecurrencyCode'] = $curr['CurrencyCode'];
        $data['details'] = $this->Bank_rec_model->detail_assignedcurrency_company($companyID, $mastercurrencyassignAutoID);
        $html = $this->load->view('system/currency_exchange/ajax/ajax_srp_erp_currency_exchangedetailData', $data, true);
        echo $html;
    }

    function update_currencyexchange()
    {
        $currencyConversionAutoID = $this->input->post('currencyConversionAutoID');
        $mastercurrencyassignAutoID = $this->input->post('mastercurrencyassignAutoID');
        $subcurrencyassignAutoID = $this->input->post('subcurrencyassignAutoID');
        $conversion = $this->input->post('conversion');

        $masterconversion = round($conversion, 8);
        $subConversion = 1 / $conversion;
        $subConversion = round($subConversion, 8);

        $subData = array('conversion' => $subConversion);
        $masterData = array('conversion' => $masterconversion);

        $update = $this->db->update('srp_erp_companycurrencyconversion', $masterData, array('mastercurrencyassignAutoID' => $mastercurrencyassignAutoID, 'subcurrencyassignAutoID' => $subcurrencyassignAutoID));
        if ($update) {
            $update = $this->db->update('srp_erp_companycurrencyconversion', $subData, array('mastercurrencyassignAutoID' => $subcurrencyassignAutoID, 'subcurrencyassignAutoID' => $mastercurrencyassignAutoID));
            $this->session->set_flashdata('s', 'Records updated Successfully.');
            echo json_encode(true);
        } else {
            $this->session->set_flashdata('e', 'Updated failed.');
            echo json_encode(true);

        }
    }

    function update_cross_exchange()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $companyCode = $this->common_data['company_data']['company_code'];
        $mastercurrencyassignAutoID = $this->input->post('mastercurrencyassignAutoID');
        $validate = $this->db->query("SELECT srp_erp_currencymaster.CurrencyName FROM srp_erp_companycurrencyassign LEFT JOIN srp_erp_currencymaster  on srp_erp_companycurrencyassign.currencyID=srp_erp_currencymaster.currencyID WHERE NOT EXISTS( SELECT NULL FROM srp_erp_companycurrencyconversion WHERE currencyassignAutoID = subcurrencyassignAutoID AND mastercurrencyassignAutoID={$mastercurrencyassignAutoID} AND companyID = {$companyID}) AND companyID = {$companyID}")->result_array();
        $validate2 = $this->db->query("SELECT CurrencyName FROM srp_erp_companycurrencyconversion LEFT JOIN srp_erp_companycurrencyassign ON subcurrencyassignAutoID = currencyassignAutoID WHERE srp_erp_companycurrencyconversion.companyID = {$companyID} AND mastercurrencyassignAutoID = {$mastercurrencyassignAutoID} AND subcurrencyassignAutoID != {$mastercurrencyassignAutoID} AND conversion =0 OR conversion is null")->result_array();

        if (!empty($validate)) {

            $commaList = array_column($validate, 'CurrencyName');
            $commaList = implode(',', $commaList);
            echo json_encode(array('title' => 'listed currency not available for to proceed', 'validate' => $commaList));
            exit;
        }

        if (!empty($validate2)) {

            $commaList = array_column($validate2, 'CurrencyName');
            $commaList = implode(',', $commaList);
            echo json_encode(array('title' => 'Please update currency exchange value for listed currency ', 'validate' => $commaList));
            exit;
        }


        $master = $this->db->query("SELECT * FROM srp_erp_companycurrencyconversion LEFT JOIN srp_erp_companycurrencyassign on subcurrencyassignAutoID=currencyassignAutoID WHERE srp_erp_companycurrencyconversion.companyID = {$companyID} AND mastercurrencyassignAutoID={$mastercurrencyassignAutoID} AND subcurrencyassignAutoID !={$mastercurrencyassignAutoID}")->result_array();
        $globalArray = array();

        if ($master) {
            foreach ($master as $value) {
                for ($i = 0; $i < count($master); $i++) {
                    $exchangerate = 0;
                    $exchangerate = $master[$i]['conversion'] / $value['conversion'];
                    $exchangerate = round($exchangerate, 8);
                    array_push($globalArray, array('companyID' => $companyID, 'companyCode' => $companyCode, 'masterCurrencyID' => $value['subCurrencyID'], 'masterCurrencyCode' => $value['subCurrencyCode'], 'subCurrencyID' => $master[$i]['subCurrencyID'], 'subCurrencyCode' => $master[$i]['subCurrencyCode'], 'mastercurrencyassignAutoID' => $value['subcurrencyassignAutoID'], 'subcurrencyassignAutoID' => $master[$i]['subcurrencyassignAutoID'], 'conversion' => $exchangerate));
                }

            }
        }
        if ($globalArray) {
            $this->db->delete('srp_erp_companycurrencyconversion', array('mastercurrencyassignAutoID !=' => $mastercurrencyassignAutoID, 'subcurrencyassignAutoID !=' => $mastercurrencyassignAutoID, "companyID" => $companyID));
            $this->db->insert_batch('srp_erp_companycurrencyconversion', $globalArray);
            $this->session->set_flashdata('s', 'Records updated Successfully.');
            echo json_encode(true);
        } else {
            $this->session->set_flashdata('w', 'Records updated Successfully.');
            echo json_encode(true);
        }


    }

    function save_currencyAssign()
    {
        $currency = $this->input->post('currencyID');
        $companyID = $this->common_data['company_data']['company_id'];
        $companyCode = $this->common_data['company_data']['company_code'];
        $reportingCurrencyID = $this->common_data['company_data']['company_reporting_currencyID'];
        $sub = $this->db->query("select * from srp_erp_companycurrencyassign where companyID={$companyID} AND currencyID={$reportingCurrencyID}")->row_array();


        if ($currency) {
            $cur = explode('|', $currency);
            $data['currencyID'] = $cur[0];
            $data['CurrencyCode'] = $cur[1];
            $data['CurrencyName'] = fetch_currency_dec($cur[1]);
            $data['DecimalPlaces'] = $cur[2];
            $data['companyID'] = $companyID;
            $data['companyCode'] = $companyCode;


            $insert = $this->db->insert('srp_erp_companycurrencyassign', $data);

            if ($insert) {
                $masterassign = $this->db->insert_id();

                $datas = array();

                $datas[0]['companyID'] = $companyID;
                $datas[0]['companyCode'] = $companyCode;
                $datas[0]['mastercurrencyassignAutoID'] = $masterassign;
                $datas[0]['masterCurrencyID'] = $cur[0];
                $datas[0]['masterCurrencyCode'] = $cur[1];
                $datas[0]['subcurrencyassignAutoID'] = $sub['currencyassignAutoID'];
                $datas[0]['subCurrencyID'] = $sub['currencyID'];
                $datas[0]['subCurrencyCode'] = $sub['CurrencyCode'];
                $datas[0]['conversion'] = 0;

                $datas[1]['companyID'] = $companyID;
                $datas[1]['companyCode'] = $companyCode;
                $datas[1]['mastercurrencyassignAutoID'] = $sub['currencyassignAutoID'];
                $datas[1]['masterCurrencyID'] = $sub['currencyID'];
                $datas[1]['masterCurrencyCode'] = $sub['CurrencyCode'];
                $datas[1]['subcurrencyassignAutoID'] = $masterassign;
                $datas[1]['subCurrencyID'] = $cur[0];
                $datas[1]['subCurrencyCode'] = $cur[1];
                $datas[1]['conversion'] = 0;


                $this->db->insert_batch('srp_erp_companycurrencyconversion', $datas);
            }
        }

        if (isset($insert)) {
            $this->session->set_flashdata('s', 'Records Inserted Successfully.');
            echo json_encode(true);
        } else {
            $this->session->set_flashdata('e', 'Failed.');
            echo json_encode(false);

        }
    }

    function save_addNewcurrencyExchange()
    {
        $mastercurrencyassignAutoID = $this->input->post('mastercurrencyassignAutoID');
        $currency = $this->input->post('currency');
        $conversion = $this->input->post('conversion');
        $companyID = $this->common_data['company_data']['company_id'];
        $companyCode = $this->common_data['company_data']['company_code'];

        $masterconversion = round($conversion, 8);
        $subConversion = 1 / $conversion;
        $subConversion = round($subConversion, 8);

        $exist = $this->db->query("SELECT * FROM srp_erp_companycurrencyconversion WHERE companyID = {$companyID} AND mastercurrencyassignAutoID = {$mastercurrencyassignAutoID} AND subCurrencyID={$currency}")->result_array();
        if (!empty($exist)) {
            $this->session->set_flashdata('e', 'The selected currency already exist. ');
            echo json_encode(false);
            exit;
        }

        $master = $this->db->query("select * from srp_erp_companycurrencyassign where companyID={$companyID} AND currencyassignAutoID={$mastercurrencyassignAutoID}")->row_array();
        $sub = $this->db->query("select * from srp_erp_companycurrencyassign where companyID={$companyID} AND currencyID={$currency}")->row_array();


        $data = array();

        $data[0]['companyID'] = $companyID;
        $data[0]['companyCode'] = $companyCode;
        $data[0]['mastercurrencyassignAutoID'] = $master['currencyassignAutoID'];
        $data[0]['masterCurrencyID'] = $master['currencyID'];
        $data[0]['masterCurrencyCode'] = $master['CurrencyCode'];
        $data[0]['subcurrencyassignAutoID'] = $sub['currencyassignAutoID'];
        $data[0]['subCurrencyID'] = $sub['currencyID'];
        $data[0]['subCurrencyCode'] = $sub['CurrencyCode'];
        $data[0]['conversion'] = $masterconversion;

        if ($master['currencyID'] != $sub['currencyID']) {

            $data[1]['companyID'] = $companyID;
            $data[1]['companyCode'] = $companyCode;
            $data[1]['mastercurrencyassignAutoID'] = $sub['currencyassignAutoID'];
            $data[1]['masterCurrencyID'] = $sub['currencyID'];
            $data[1]['masterCurrencyCode'] = $sub['CurrencyCode'];
            $data[1]['subcurrencyassignAutoID'] = $master['currencyassignAutoID'];
            $data[1]['subCurrencyID'] = $master['currencyID'];
            $data[1]['subCurrencyCode'] = $master['CurrencyCode'];
            $data[1]['conversion'] = $subConversion;
        }

        $this->db->insert_batch('srp_erp_companycurrencyconversion', $data);

        $this->session->set_flashdata('s', 'Records Inserted Successfully.');
        echo json_encode(true);


    }

    function referback_grv()
    {
        $bankRecAutoID = $this->input->post('bankRecAutoID');

        $this->db->select('approvedYN,bankRecPrimaryCode');
        $this->db->where('bankRecAutoID', trim($bankRecAutoID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_bankrecmaster');
        $approved_inventory_bank_rec = $this->db->get()->row_array();
        if (!empty($approved_inventory_bank_rec)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_inventory_bank_rec['bankRecPrimaryCode']));
        }else
        {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($bankRecAutoID, 'BRC');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }


    }


    function save_loanManagementMaster()
    {

        $this->form_validation->set_rules('documentCode', 'Document Code', 'trim|required');
        $this->form_validation->set_rules('bankID', 'Bank', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        $this->form_validation->set_rules('currency', 'currency', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'documentDate', 'trim|required|validate_date');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        $this->form_validation->set_rules('narration', 'narration', 'trim|required');
        $this->form_validation->set_rules('installmentID', 'installmentID', 'trim|required');
        $this->form_validation->set_rules('noInstallment', 'noInstallment', 'trim|required');
        $this->form_validation->set_rules('facilityDateFrom', 'facilityDateFrom', 'trim|required|validate_date');
        $this->form_validation->set_rules('facilityDateTo', 'facilityDateTo', 'trim|required|validate_date');
        $this->form_validation->set_rules('ratetypeID', 'ratetypeID', 'trim|required');
        $this->form_validation->set_rules('rateOfInterest', 'rateOfInterest', 'trim|required');
        $this->form_validation->set_rules('interestPayment', 'interestPayment', 'trim|required');
        $this->form_validation->set_rules('DateofInterestPayment', 'DateofInterestPayment', 'trim|required|validate_date');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            if ($this->input->post('masterID') != '') {
                $this->session->set_flashdata('e', 'You cannot update data');
                echo json_encode(array('status' => true, 'bankFacilityID' => $this->input->post('masterID')));
                exit;
            }
            /*loan*/
            if ($this->input->post('DateofInterestPayment') < $this->input->post('documentDate')) {
                $this->session->set_flashdata('e', 'Date of Drawdown is greater than Initial Interest Payment date  ');
                echo json_encode(false);
                exit;
            }
            if ($this->input->post('DateofInterestPayment') > $this->input->post('facilityDateFrom')) {
                $this->session->set_flashdata('e', 'Initial Interest Payment date is greater than Facility Date from');
                echo json_encode(false);
                exit;
            }
            if ($this->input->post('documentDate') > $this->input->post('facilityDateFrom')) {
                $this->session->set_flashdata('e', 'Facility Date from is greater than Date of Drawdown');
                echo json_encode(false);
                exit;
            }

            $data['facilityCode'] = $this->sequence->sequence_generator('TLO');
            $data['companyID'] = current_companyID();
            $data['typeOfFacility'] = 5;
            $data['installmentID'] = $this->input->post('installmentID');
            $data['installmentType'] = ($this->input->post('installmentID') == 1 ? 'Monthly' : 'Quaterly');
            $data['noInstallment'] = $this->input->post('noInstallment');
            $data['interestPaymentID'] = $this->input->post('interestPayment');
            $data['interestPaymentType'] = ($this->input->post('interestPayment') == 1 ? 'Monthly' : 'Quaterly');
            $data['interestPaymentDate'] = $this->input->post('DateofInterestPayment');
            $data['documentDate'] = $this->input->post('documentDate');
            $data['documentCode'] = $this->input->post('documentCode');
            $data['facilityDateTo'] = $this->input->post('facilityDateTo');
            $data['facilityDateFrom'] = $this->input->post('facilityDateFrom');
            $data['bankID'] = $this->input->post('bankID');
            $data['currencyID'] = $this->input->post('currency');
            $data['amount'] = $this->input->post('amount');
            $data['rateOfInterest'] = $this->input->post('rateOfInterest');
            $data['status'] = $this->input->post('status');
            $data['statusDate'] = date('Y-m-d');
            $data['ratetypeID'] = $this->input->post('ratetypeID');
            $data['narration'] = $this->input->post('narration');
            $currencyCode = fetch_currency_code($data['currencyID']);
            $companyLocalCurrency = $this->common_data['company_data']['company_default_currency'];
            $companyReportingCurrency = $this->common_data['company_data']['company_reporting_currency'];
            $default_currency = currency_conversion($currencyCode, $companyLocalCurrency);
            $reporting_currency = currency_conversion($currencyCode, $companyReportingCurrency);
            $data['localcurrencyID'] = $this->input->post('localcurrencyID');
            $data['rptcurrencyID'] = $this->input->post('rptcurrencyID');
            $data['localER'] = $default_currency['conversion'];
            $data['rptER'] = $reporting_currency['conversion'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdPCID'] = $this->common_data['current_pc'];


            $this->db->trans_begin();
            $insert = $this->db->insert('srp_erp_bankfacilityloan', $data);
            $lastid = $this->db->insert_id();

            /*detail*/

            /*  $this->$this->InterestSchedule = array();
              $this->$this->schedule = array();*/
            /*payment scedule*/


            $this->noInstallment = $this->input->post('noInstallment');

            $_POST['DateofInterestPayment'] = $this->input->post('DateofInterestPayment');

            $_POST['facilityDateTo'] = $this->input->post('facilityDateTo');
            $_POST['facilityDateFrom'] = $this->input->post('facilityDateFrom');
            $_POST['amount'] = $this->input->post('amount');

            $_POST['companyID'] = current_companyID();

            $this->interestPayment = $this->input->post('interestPayment');
            $this->installmentID = $this->input->post('installmentID');
            $this->rateOfInterest = $this->input->post('rateOfInterest');


            $this->InterestSchedule = array();
            $this->schedule = array();
            /*payment scedule*/
            if ($this->installmentID == 1) {
                $openbalance = 0;
                $principleAmount = $_POST['amount'];
                if ($this->installmentID == 1 && $this->interestPayment == 2) {
                    $time2 = strtotime($_POST['facilityDateFrom']); //check if condition for this second
                    $_POST['facilityDateFrom'] = date("Y-m-d", strtotime("-1 month", $time2));
                    $this->noInstallment = $this->noInstallment + 1;
                }
                for ($x = 1; $x <= $this->noInstallment; $x++) {
                    $time = strtotime($_POST['facilityDateFrom']);
                    $postDate = date("Y-m-d", $time);
                    $postDateBegin = date("Y-m-01", $time);
                    $postDateStr = strtotime($postDateBegin);
                    $monthEnd = date("Y-m-t", $time);
                    if ($x == 1) {
                        $installmentDate = date("Y-m-d", $time);
                    } else {
                        if ($postDate == $monthEnd) {
                            $installmentDate = date("Y-m-t", strtotime("+1 months", $postDateStr));
                        } else {

                            $installmentDate = date("Y-m-d", strtotime("+1 months", $time));
                        }
                    }


                    if ($this->installmentID == 1 && $this->interestPayment == 2) {
                        if ($x != 1) {
                            if ($openbalance == 0) {
                                $openbalance = $_POST['amount'];
                            } else {
                                $openbalance = $principleAmount;
                            }
                            $amount = $_POST['amount'] / ($this->noInstallment - 1);
                            $totalamount = round($amount);
                            $principleAmount = $principleAmount - $totalamount;
                        } else {
                            $openbalance = 0;
                            $amount = $_POST['amount'] / ($this->noInstallment - 1);
                            $totalamount = round($amount);
                            $principleAmount = $_POST['amount'];
                        }
                    } else {
                        if ($openbalance == 0) {
                            $openbalance = $_POST['amount'];
                        } else {
                            $openbalance = $principleAmount;
                        }
                        $amount = $_POST['amount'] / ($this->noInstallment);
                        $totalamount = round($amount);
                        /*   echo $installmentDate;
                            echo "-";
                            echo $totalamount;
                            echo "-";

                            echo $principleAmount;
                            echo "<br>";*/
                        $principleAmount = $principleAmount - $totalamount;
                    }
                    if ($postDate == $monthEnd) {
                        $i = 30;
                        $installmentDatetime = strtotime($installmentDate);
                        $diffrentDate = date("Y-m-t", strtotime("-1 months", $installmentDatetime));
                    } else {
                        $i = 1;
                        $installmentDatetime = strtotime($installmentDate);
                        $diffrentDate = date("Y-m-d", strtotime("-1 months", $installmentDatetime));
                    }

                    $date1 = date_create($installmentDate);
                    $date2 = date_create($diffrentDate);
                    $diff = date_diff($date1, $date2);
                    $numberDays = $diff->days;
                    $fixed = $openbalance * ($this->rateOfInterest / 100) * ($numberDays / 360);
                    $fixed = round($fixed);
                    $variableTotal = $totalamount + $fixed;
                    array_push($this->schedule, array('bankFacilityID' => $lastid, "companyID" => $_POST['companyID'], 'referenceNo' => 'SET/001', 'date' => $installmentDate, 'principleAmount' => $openbalance, 'previousDate' => $diffrentDate, 'principalRepayment' => $totalamount, 'closingBalance' => $principleAmount, 'installmentDueDays' => $numberDays, 'interestAmount' => $fixed, 'variableTotal' => $variableTotal, 'isSettlement' => -1, 'isIntrest' => 0));
                    $_POST['facilityDateFrom'] = $installmentDate;

                }
            }
            if ($this->installmentID == 2) {
                $openbalance = 0;
                $principleAmount = $_POST['amount'];
                for ($x = 1; $x <= $this->noInstallment; $x++) {
                    $time = strtotime($_POST['facilityDateFrom']);
                    $postDate = date("Y-m-d", $time);
                    $monthEnd = date("Y-m-t", $time);
                    /*If set same month end date*/
                    $postDateBegin = date("Y-m-01", $time);
                    $postDateStr = strtotime($postDateBegin);
                    /**/
                    if ($x == 1) {
                        $installmentDate = date("Y-m-d", $time);
                    } else {
                        if ($postDate == $monthEnd) {
                            $i = ($x - 1) * 90;
                            $installmentDate = date("Y-m-t", strtotime("+3 months", $postDateStr));
                        } else {
                            $i = ($x - 1) * 3;
                            $installmentDate = date("Y-m-d", strtotime("+3 months", $time));
                        }
                    }
                    if ($openbalance == 0) {
                        $openbalance = $_POST['amount'];
                    } else {
                        $openbalance = $principleAmount;
                    }
                    $amount = $_POST['amount'] / $this->noInstallment;
                    $totalamount = round($amount);
                    $principleAmount = $principleAmount - $totalamount;

                    if ($postDate == $monthEnd) {
                        $i = 90;
                        $installmentDatetime = strtotime($installmentDate);
                        $diffrentDate = date("Y-m-t", strtotime("-3 months", $installmentDatetime));

                    } else {
                        $i = 3;
                        $installmentDatetime = strtotime($installmentDate);
                        $diffrentDate = date("Y-m-d", strtotime("-3 months", $installmentDatetime));
                    }
                    $date1 = date_create($installmentDate);
                    $date2 = date_create($diffrentDate);
                    $diff = date_diff($date2, $date1);
                    $numberDays = $diff->days;
                    $fixed = $openbalance * ($this->rateOfInterest / 100) * ($numberDays / 360);
                    $fixed = round($fixed);
                    $variableTotal = $totalamount + $fixed;
                    array_push($this->schedule, array('bankFacilityID' => $lastid, "companyID" => $_POST['companyID'], 'referenceNo' => 'SET/001', 'date' => $installmentDate, 'previousDate' => $diffrentDate, 'principleAmount' => $openbalance, 'principalRepayment' => $totalamount, 'closingBalance' => $principleAmount, 'installmentDueDays' => $numberDays, 'interestAmount' => $fixed, 'variableTotal' => $variableTotal, 'isSettlement' => -1, 'isIntrest' => 0));
                    $_POST['facilityDateFrom'] = $installmentDate;
                }

            }
            /*end of payment scdule*/

            if ($this->interestPayment == 1) {
                $IntOpenbalance = 0;
                $IntprincipleAmount = $_POST['amount'];

                $time2 = strtotime($_POST['facilityDateTo']);
                $_POST['facilityDateTo'] = date("Y-m-d", strtotime("+1 month", $time2));
                $IntInstallmentDate = $_POST['DateofInterestPayment'];
                $x = 1;
                while (strtotime($_POST['facilityDateTo']) > strtotime($IntInstallmentDate)) {

                    $IntTime = strtotime($_POST['DateofInterestPayment']);
                    $IntPostDate = date("Y-m-d", $IntTime);
                    /*If set same month end date*/
                    $IntDateBegin = date("Y-m-01", $IntTime);
                    $IntDateBeginStr = strtotime($IntDateBegin);
                    /**/
                    $IntMonthEnd = date("Y-m-t", $IntTime);
                    if ($x == 1) {
                        $IntInstallmentDate = date("Y-m-d", $IntTime);
                    } else {
                        if ($IntPostDate == $IntMonthEnd) {
                            /* $i = ($x - 1) * 30;*/
                            $i = ($x - 1) * 28;
                            $IntInstallmentDate = date("Y-m-t", strtotime("+$i days", $IntTime));
                        } else {
                            $i = ($x - 1);
                            $IntInstallmentDate = date("Y-m-d", strtotime("+$i months", $IntTime));
                        }
                    }

                    if ($IntOpenbalance == 0) {
                        $IntOpenbalance = $_POST['amount'];
                    } else {
                        $IntOpenbalance = $IntprincipleAmount;
                    }
                    $IntAmount = 0;
                    $IntTotalamount = round($IntAmount);
                    $IntprincipleAmount = $IntprincipleAmount - $IntTotalamount;

                    if ($IntPostDate == $IntMonthEnd) {
                        $i = 31;
                        /*  $i = 87;*/
                        $date = date_create($IntInstallmentDate);
                        date_sub($date, date_interval_create_from_date_string("$i days"));
                        $IntDiffrentDate = date_format($date, "Y-m-t");
                    } else {
                        $i = 1;
                        $date = date_create($IntInstallmentDate);
                        date_sub($date, date_interval_create_from_date_string("$i months"));
                        $IntDiffrentDate = date_format($date, "Y-m-d");
                    }
                    $IntDate1 = date_create($IntInstallmentDate);
                    $IntDate2 = date_create($IntDiffrentDate);
                    $IntDiff = date_diff($IntDate1, $IntDate2);
                    $IntNumberDays = $IntDiff->days;
                    $Intfixed = $IntOpenbalance * ($this->rateOfInterest / 100) * ($IntNumberDays / 360);
                    $Intfixed = round($Intfixed);
                    $IntVariableTotal = $IntTotalamount + $Intfixed;


                    if ($_POST['facilityDateTo'] <= $IntInstallmentDate) {
                        break;
                    }
                    array_push($this->InterestSchedule, array('bankFacilityID' => $lastid, "companyID" => $_POST['companyID'], 'referenceNo' => 'SET/001', 'date' => $IntInstallmentDate, 'previousDate' => $IntDiffrentDate, 'principleAmount' => $IntOpenbalance, 'principalRepayment' => $IntTotalamount, 'closingBalance' => $IntprincipleAmount, 'installmentDueDays' => $IntNumberDays, 'interestAmount' => $Intfixed, 'variableTotal' => $IntVariableTotal, 'isSettlement' => -1, 'isIntrest' => 1));
                    $x++;
                }
            }


            if ($this->interestPayment == 2) {
                $IntOpenbalance = 0;
                $IntprincipleAmount = $_POST['amount'];
                if ($this->installmentID == 1 && $this->interestPayment == 2) {

                } else {
                    $time2 = strtotime($_POST['facilityDateTo']); //check if condition for second
                    $_POST['facilityDateTo'] = date("Y-m-d", strtotime("+3 month", $time2));
                }
                $IntInstallmentDate = $_POST['DateofInterestPayment'];
                $x = 1;
                while ($_POST['facilityDateTo'] >= $IntInstallmentDate) {
                    $IntTime = strtotime($_POST['DateofInterestPayment']);
                    $IntPostDate = date("Y-m-d", $IntTime);
                    $IntMonthEnd = date("Y-m-t", $IntTime);
                    if ($x == 1) {
                        $IntInstallmentDate = date("Y-m-d", $IntTime);
                    } else {
                        if ($IntPostDate == $IntMonthEnd) {
                            $i = ($x - 1) * 90;
                            $IntInstallmentDate = date("Y-m-t", strtotime("+$i days", $IntTime));
                        } else {
                            $i = ($x - 1) * 3;
                            $IntInstallmentDate = date("Y-m-d", strtotime("+$i months", $IntTime));
                        }
                    }
                    if ($IntOpenbalance == 0) {
                        $IntOpenbalance = $_POST['amount'];
                    } else {
                        $IntOpenbalance = $IntprincipleAmount;
                    }
                    $IntAmount = 0;
                    $IntTotalamount = round($IntAmount);
                    $IntprincipleAmount = $IntprincipleAmount - $IntTotalamount;
                    if ($IntPostDate == $IntMonthEnd) {
                        /*   $i = 90;*/
                        $i = 87;
                        $IntInstallmentDatetime = strtotime($IntInstallmentDate);
                        $IntDiffrentDate = date("Y-m-t", strtotime("$i days", $IntInstallmentDatetime));

                    } else {
                        $i = 3;
                        $IntInstallmentDatetime = strtotime($IntInstallmentDate);
                        $IntDiffrentDate = date("Y-m-d", strtotime("-$i months", $IntInstallmentDatetime));
                    }
                    $IntDate1 = date_create($IntInstallmentDate);
                    $IntDate2 = date_create($IntDiffrentDate);
                    $IntDiff = date_diff($IntDate2, $IntDate1);
                    $IntNumberDays = $IntDiff->days;
                    $Intfixed = $IntOpenbalance * ($this->rateOfInterest / 100) * ($IntNumberDays / 360);
                    $Intfixed = round($Intfixed);
                    $IntVariableTotal = $IntTotalamount + $Intfixed;
                    if ($_POST['facilityDateTo'] <= $IntInstallmentDate) {
                        break;
                    }
                    array_push($this->InterestSchedule, array('bankFacilityID' => $lastid, "companyID" => $_POST['companyID'], 'referenceNo' => 'SET/001', 'date' => $IntInstallmentDate, 'previousDate' => $IntDiffrentDate, 'principleAmount' => $IntOpenbalance, 'principalRepayment' => $IntTotalamount, 'closingBalance' => $IntprincipleAmount, 'installmentDueDays' => $IntNumberDays, 'interestAmount' => $Intfixed, 'variableTotal' => $IntVariableTotal, 'isSettlement' => -1, 'isIntrest' => 0));
                    $x++;
                }
            }
            $this->thirdArray = array();


            if ($this->installmentID == 1 && $this->interestPayment == 2) {
                /*installment monthly and ineterest Quaterly*/

                $f = 1;
                foreach ($this->schedule as $key => $st_schedule) {
                    $interestAr = $this->find_match2($key);
                    if ($interestAr != null) {
                        /*days calculation based on installment for interest*/
                        $sdate = $interestAr['date'];
                        $iDate = $this->schedule[$key]['date'];
                        $d = date_create($sdate);
                        $d2 = date_create($iDate);
                        $dDiff = date_diff($d2, $d);
                        $dNumberDays = $dDiff->days;

                        /*interest rate*/

                        $this->schedule[$key]['installmentDueDays'] = $dNumberDays;
                        $fixed = $this->schedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                        $IntFixed = round($fixed);
                        $this->schedule[$key]['interestAmount'] = $IntFixed;
                        $this->schedule[$key]['variableTotal'] = $IntFixed + $this->schedule[$key]['principalRepayment'];
                        /*--*/
                        $third = array_push($this->thirdArray, $interestAr);
                        if ($f != 1) {
                            $third = array_push($this->thirdArray, $this->schedule[$key]);
                        }
                    } else {
                        $dNumberDays = $this->schedule[$key]['installmentDueDays'];
                        $fixed = $this->schedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                        $IntFixed = round($fixed);
                        $this->schedule[$key]['interestAmount'] = $IntFixed;
                        $this->schedule[$key]['variableTotal'] = $IntFixed + $this->schedule[$key]['principalRepayment'];
                        if ($f != 1) {
                            $third = array_push($this->thirdArray, $this->schedule[$key]);
                        }
                    }
                    $f++;
                }

            } else {

                foreach ($this->InterestSchedule as $key => $st_interest) {
                    $sceduleAr = $this->find_match($key);
                    if ($sceduleAr != null) {
                        /*days calculation based on installment for interest*/
                        $sdate = $sceduleAr['date'];
                        $iDate = $this->InterestSchedule[$key]['date'];
                        $d = date_create($sdate);
                        $d2 = date_create($iDate);
                        $dDiff = date_diff($d2, $d);
                        $dNumberDays = $dDiff->days;

                        $this->InterestSchedule[$key]['principleAmount'] = $sceduleAr['closingBalance'];
                        $this->InterestSchedule[$key]['closingBalance'] = $sceduleAr['closingBalance'];

                        /*interest rate*/
                        $this->InterestSchedule[$key]['installmentDueDays'] = $dNumberDays;
                        $fixed = $this->InterestSchedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                        $IntFixed = round($fixed);
                        $this->InterestSchedule[$key]['interestAmount'] = $IntFixed;
                        $this->InterestSchedule[$key]['variableTotal'] = $IntFixed;

                        /*--*/
                        if ($this->in_array_r($sceduleAr['date'], $this->thirdArray)) {
                        } else {
                            $third = array_push($this->thirdArray, $sceduleAr);
                        }
                        $third = array_push($this->thirdArray, $this->InterestSchedule[$key]);

                    } else {
                        /* if installment quaterly and interest monthly*/
                        if ($this->installmentID == 2 && $this->interestPayment == 1) {
                            if ($key == 0) {

                            } else {
                                //$key;
                                $this->InterestSchedule[$key]['principleAmount'] = $this->InterestSchedule[$key - 1]['closingBalance'];
                                $this->InterestSchedule[$key]['closingBalance'] = $this->InterestSchedule[$key - 1]['closingBalance'];
                                $dNumberDays = $this->InterestSchedule[$key]['installmentDueDays'];
                                $fixed = $this->InterestSchedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                                $IntFixed = round($fixed);
                                $this->InterestSchedule[$key]['interestAmount'] = $IntFixed;
                                $this->InterestSchedule[$key]['variableTotal'] = $IntFixed;

                            }
                        }
                        $third = array_push($this->thirdArray, $this->InterestSchedule[$key]);
                    }
                    //$y++;
                }

                array_pop($this->thirdArray);
            }


            $sum = 0;
            $date = null;
            $i = 0;


            foreach ($this->thirdArray as $value) {
                if ($date == $value['date']) {
                    if ($value['isSettlement'] == -1) {
                        continue;
                    } else {
                        $fourthArray[] = $value;
                    }
                } else {
                    $fourthArray[] = $value;
                }

                $i++;
                $date = $value['date'];

            }


            $this->db->insert_batch('srp_erp_bankfacilityloandetail', $fourthArray);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('e', 'Failed. Please contact support team');
                echo json_encode(FALSE);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('s', 'Successfully loan schedule created');
                echo json_encode(array('status' => true, 'bankFacilityID' => $lastid));
            }

        }

    }

    function in_array_r($needle, $haystack, $strict = false)
    {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

    function find_match($index)
    {

        foreach ($this->schedule as $key => $str_scedule) {
            if (($this->schedule[$key]['date'] <= $this->InterestSchedule[$index]['date']) && ($this->schedule[$key]['date'] >= $this->InterestSchedule[$index]['previousDate'])) {
                if ($this->installmentID == 2 && $this->interestPayment == 2) {
                    $previntdays = $this->InterestSchedule[$index - 1]['date']; //get previous intrest scedule
                    $intallmentdays = $this->schedule[$key]['date'];
                    $d = date_create($previntdays);
                    $d2 = date_create($intallmentdays);
                    $dDiff = date_diff($d2, $d);
                    $dNumberDays = $dDiff->days;
                    $this->schedule[$key]['installmentDueDays'] = $dNumberDays;
                    $fixed = $this->schedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                    $IntFixed = round($fixed);
                    $this->schedule[$key]['interestAmount'] = $IntFixed;
                    $this->schedule[$key]['variableTotal'] = $this->schedule[$key]['principalRepayment'] + $IntFixed;
                } else {
                    $previntdays = $this->InterestSchedule[$index]['previousDate']; //get previous intrest scedule
                    $intallmentdays = $this->schedule[$key]['date'];
                    $d = date_create($previntdays);
                    $d2 = date_create($intallmentdays);
                    $dDiff = date_diff($d2, $d);
                    $dNumberDays = $dDiff->days;
                    $this->schedule[$key]['installmentDueDays'] = $dNumberDays;
                    $fixed = $this->schedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                    $IntFixed = round($fixed);
                    $this->schedule[$key]['interestAmount'] = $IntFixed;
                    $this->schedule[$key]['variableTotal'] = $this->schedule[$key]['principalRepayment'] + $IntFixed;
                }
                $data = $this->schedule[$key];
            }
        }
        if (!empty($data)) {
            return $data;
        } else {
            return null;
        }
    }

    function find_match2($index)
    {

        $y = 1;
        foreach ($this->InterestSchedule as $key => $str_scedule) {
            if (($this->InterestSchedule[$key]['date'] <= $this->schedule[$index]['date']) && ($this->InterestSchedule[$key]['date'] >= $this->schedule[$index]['previousDate'])) {
                if ($y != 1) {
                    $this->InterestSchedule[$key]['principleAmount'] = $this->schedule[$index]['principleAmount'];
                    $this->InterestSchedule[$key]['closingBalance'] = $this->schedule[$index]['principleAmount'];
                    $previntdays = $this->schedule[$index]['date']; //get previous intrest scedule
                    $intallmentdays = $this->InterestSchedule[$key]['date'];
                    $d = date_create($previntdays);
                    $d2 = date_create($intallmentdays);
                    $dDiff = date_diff($d2, $d);
                    $dNumberDays = $dDiff->days;
                    $this->InterestSchedule[$key]['installmentDueDays'] = $dNumberDays;
                    $fixed = $this->InterestSchedule[$key]['principleAmount'] * ($this->rateOfInterest / 100) * ($dNumberDays / 360);
                    $IntFixed = round($fixed);
                    $this->InterestSchedule[$key]['interestAmount'] = $IntFixed;
                    $this->InterestSchedule[$key]['variableTotal'] = $this->schedule[$key]['principalRepayment'] + $IntFixed;
                } else {
                }
                $data = $this->InterestSchedule[$key];
            }
            $y++;
        }
        if (!empty($data)) {
            return $data;
        } else {
            return null;
        }
    }

    function setinitlainterstpaymentDate()
    {
        if ($this->input->post('installmentID') == '') {
            echo json_encode(array('error' => 0, 'value' => ''));
            exit;
        }
        if ($this->input->post('documentDate') == '') {
            echo json_encode(array('error' => 0, 'value' => ''));
            exit;
        }

        /* $df = explode('/', $_POST['documentDate']);
         $_POST['documentDate'] = $df[2] . '-' . $df[1] . '-' . $df[0];*/

        if ($this->input->post('installmentID') == 1) {
            $time = strtotime($this->input->post('documentDate'));
            $postDate = date("Y-m-d", $time);
            $monthEnd = date("Y-m-t", $time);

            if ($postDate == $monthEnd) {
                $interestdate = date("Y-m-t", strtotime("+28 days", $time));

            } else {
                $interestdate = date("Y-m-d", strtotime("+1 months", $time));
            }


        }
        if ($this->input->post('installmentID') == 2) {
            $time = strtotime($this->input->post('documentDate'));
            $postDate = date("Y-m-d", $time);
            $monthEnd = date("Y-m-t", $time);

            if ($postDate == $monthEnd) {
                $interestdate = date("Y-m-t", strtotime("+84 days", $time));

            } else {
                $interestdate = date("Y-m-d", strtotime("+3 months", $time));
            }


        }

        /* $in = explode('-', $interestdate);
         $interestdate = $in[2] . '/' . $in[1] . '/' . $in[0];*/

        echo json_encode(array('error' => 0, 'value' => $interestdate));
        exit;
    }

    function getfacilityfatefrom()
    {
        if ($this->input->post('noInstallment') == '') {
            echo json_encode(array('error' => 0, 'value' => ''));
            exit;
        }
        if ($this->input->post('datefrom') == '') {
            echo json_encode(array('error' => 0, 'value' => ''));
            exit;
        }


        if ($this->input->post('installmentType') == 1) {


            for ($x = 1; $x <= $this->input->post('noInstallment'); $x++) {

                $time = strtotime($this->input->post('datefrom'));
                $postDate = date("Y-m-d", $time);
                $monthEnd = date("Y-m-t", $time);
                $postDateBegin = date("Y-m-01", $time);
                $postDateStr = strtotime($postDateBegin);


                if ($x == 1) {
                    $installmentDate = date("Y-m-d", $time);


                } else {

                    if ($postDate == $monthEnd) {

                        /*    $i = ($x - 1) * 30;*/
                        $i = ($x - 1) * 28;
                        $installmentDate = date("Y-m-t", strtotime("+1 months", $postDateStr));
                    } else {
                        $i = ($x - 1);
                        $installmentDate = date("Y-m-d", strtotime("+1 months", $time));
                    }
                }


                if ($postDate == $monthEnd) {

                    $i = 30;
                    $installmentDatetime = strtotime($installmentDate);


                    $diffrentDate = date("Y-m-t", strtotime("-1 months", $installmentDatetime));
                } else {
                    $i = 1;
                    $installmentDatetime = strtotime($installmentDate);

                    $diffrentDate = date("Y-m-d", strtotime("-1 months", $installmentDatetime));
                }

                $date1 = date_create($installmentDate);
                $date2 = date_create($diffrentDate);
                $diff = date_diff($date1, $date2);

                $numberDays = $diff->days;

                $_POST['datefrom'] = $installmentDate;
            }

        }
        if ($this->input->post('installmentType') == 2) {


            for ($x = 1; $x <= $this->input->post('noInstallment'); $x++) {
                $time = strtotime($_POST['datefrom']);
                $postDate = date("Y-m-d", $time);
                $monthEnd = date("Y-m-t", $time);
                if ($x == 1) {
                    $installmentDate = date("Y-m-d", $time);
                } else {

                    if ($postDate == $monthEnd) {
                        $i = ($x - 1) * 90;
                        $installmentDate = date("Y-m-t", strtotime("+$i days", $time));
                    } else {
                        $i = ($x - 1) * 3;
                        $installmentDate = date("Y-m-d", strtotime("+$i months", $time));
                    }
                }


                if ($postDate == $monthEnd) {
                    $i = 90;
                    $installmentDatetime = strtotime($installmentDate);
                    $diffrentDate = date("Y-m-t", strtotime("-$i days", $installmentDatetime));
                } else {
                    $i = 3;
                    $installmentDatetime = strtotime($installmentDate);
                    $diffrentDate = date("Y-m-d", strtotime("-$i months", $installmentDatetime));
                }

                $date1 = date_create($installmentDate);
                $date2 = date_create($diffrentDate);
                $diff = date_diff($date1, $date2);

                $numberDays = $diff->days;


            }

        }
        echo json_encode(array('error' => 0, 'value' => $installmentDate));
        exit;

    }

    function bankfacilityloan()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select(" srp_erp_bankfacilityloan.bankFacilityID as bankFacilityID ,rateOfInterest, narration, srp_erp_bankfacilityloan.bankFacilityID, typeOfFacility, status, statusID, facilityCode, DATE_FORMAT(documentDate, '%Y/%m/%d') AS documentDate, DATE_FORMAT(facilityDateFrom,'$convertFormat') AS facilityDateFrom , DATE_FORMAT(facilityDateTo,'$convertFormat') AS facilityDateTo , srp_erp_bankfacilityborrowingtype.description AS facilityType, srp_erp_bankfacilitystatus.description AS status, CurrencyShortCode, GLDescription AS bank, amount, IF(isSettlement = -1, sum(principalRepayment), 0) as settlement, IF(isSettlement = 0, sum(principalRepayment), 0) as utilized, amount-(IF(isSettlement = 0, sum(principalRepayment), 0)) as balance,color");
        $this->datatables->from('srp_erp_bankfacilityloan');
        $this->datatables->join('srp_erp_bankfacilityloandetail', 'srp_erp_bankfacilityloandetail.bankFacilityID=srp_erp_bankfacilityloan.bankFacilityID', 'LEFT');
        $this->datatables->join('srp_erp_bankfacilityborrowingtype', 'typeOfFacility = borrowingTypeID', 'LEFT');
        $this->datatables->join('srp_erp_bankfacilitystatus', 'srp_erp_bankfacilitystatus.statusID = status', 'LEFT');
        $this->datatables->join('srp_currencymaster', 'srp_currencymaster.currencyID = srp_erp_bankfacilityloan.currencyID', 'LEFT');
        $this->datatables->join('srp_erp_chartofaccounts', 'bankID = GLAutoID', 'LEFT');
        $this->datatables->where('srp_erp_bankfacilityloan.companyID', current_companyID());
        $this->datatables->group_by('srp_erp_bankfacilityloan.bankFacilityID');
        $this->datatables->add_column('status', '<span class="label $1 ">  </span>', 'color');
        $this->datatables->add_column('edit', ' $1 ', 'edit_loantreasury(bankFacilityID)');
        echo $this->datatables->generate();
    }

    function bankfacilityloansettlement()
    {

        $master = $this->input->post('masterID');
        $data['settlement'] = $this->db->query("select isSettlement,variableLibor, variableAmount, variableTotal, installmentDueDays, closingBalance, principalRepayment, bankFacilityDetailID, DATE_FORMAT(date, '%d/%m/%Y') AS date, referenceNo, principleAmount, interestAmount, systemDocumentReference from srp_erp_bankfacilityloandetail WHERE isSettlement=-1 AND bankFacilityID= {$master} ")->result_array();

        /*echo $this->datatables->generate();*/
        $html = $this->load->view('system/bank_rec/erp_loan_settlementtable', $data, true);
        echo $html;
    }

    function bank_facilityLoanHeader()
    {
        $masterID = $this->input->post('masterID');
        $result = $this->db->query("SELECT bankFacilityID, facilityCode, companyID, DATE_FORMAT(documentDate,'%Y-%m-%d') as documentDate, documentCode, typeOfFacility, installmentID, installmentType, noInstallment, interestPaymentID, interestPaymentType, interestPaymentDate, bankID, ratetypeID, DATE_FORMAT(facilityDateFrom,'%Y-%m-%d') as facilityDateFrom, DATE_FORMAT(facilityDateTo,'%Y-%m-%d') as facilityDateTo, narration, currencyID, amount, rateOfInterest, status FROM `srp_erp_bankfacilityloan` WHERe bankFacilityID={$masterID}")->row_array();
        echo json_encode($result);
    }

    function update_loandetail()
    {

        /* $this->db->where('bankFacilityDetailID', $this->input->post('pk'));
         $this->db->update('erp_bankfacilityloandetail', array($this->input->post('name')=>$this->input->post('value')));
         echo json_encode(TRUE);*/
        if ($this->input->post('name') == 'installmentDueDays') {

            $data = $this->db->query("Select * from srp_erp_bankfacilityloandetail LEFT JOIN srp_erp_bankfacilityloan ON srp_erp_bankfacilityloandetail.bankFacilityID = srp_erp_bankfacilityloan.bankFacilityID where  bankFacilityDetailID={$this->input->post('pk')}")->row_array();

            $fixed = $data['principleAmount'] * ($data['rateOfInterest'] / 100) * ($this->input->post('value') / 360);
            $fixed = $fixed;
            $variableAmount = $data['principleAmount'] * ($data['variableLibor'] / 100) * ($this->input->post('value') / 360);
            round($variableAmount);
            $variableTotal = $data['principalRepayment'] + $fixed + $variableAmount;

            $this->db->where('bankFacilityDetailID', $this->input->post('pk'));
            $update = $this->db->update('srp_erp_bankfacilityloandetail', array('installmentDueDays' => $this->input->post('value'), 'interestAmount' => $fixed, 'variableTotal' => $variableTotal, 'variableAmount' => $variableAmount));


            $id = $this->db->query("select bankFacilityID from srp_erp_bankfacilityloandetail where bankFacilityDetailID ={$this->input->post('pk')} ")->row_array();
            $data = $this->db->query("SELECT sum(interestAmount) as totalinterestAmount ,sum(principalRepayment) as totalprincipalRepayment,sum(variableAmount) as totalvariableAmount,sum(variableTotal) as Total FROM srp_erp_bankfacilityloandetail  where bankFacilityID= {$id['bankFacilityID']} ")->row_array();


            if ($update) {
                echo json_encode(array('error' => 0, 'message' => 'done', 'interestAmount' => number_format($fixed, 2), 'variableAmount' => number_format($variableAmount, 2), 'variableTotal' => number_format($variableTotal, 2), 'totalinterestAmount' => number_format($data['totalinterestAmount'], 2), 'totalprincipalRepayment' => number_format($data['totalprincipalRepayment'], 2), 'totalvariableAmount' => number_format($data['totalvariableAmount'], 2), 'Total' => number_format($data['Total'], 2)));
            } else {
                echo json_encode(array('error' => 1, 'message' => 'error'));

            }
        } else {

            $data = $this->db->query("Select * from srp_erp_bankfacilityloandetail WHERE bankFacilityDetailID={$this->input->post('pk')}")->row_array();

            $variableAmount = $data['principleAmount'] * ($this->input->post('value') / 100) * ($data['installmentDueDays'] / 360);
            $variableAmount = round($variableAmount);

            $variableTotal = $data['principalRepayment'] + $data['interestAmount'] + $variableAmount;

            $this->db->where('bankFacilityDetailID', $this->input->post('pk'));
            $update = $this->db->update('srp_erp_bankfacilityloandetail', array('variableLibor' => $this->input->post('value'),
                'variableAmount' => $variableAmount,
                'variableTotal' => $variableTotal

            ));
            $id = $this->db->query("select bankFacilityID from srp_erp_bankfacilityloandetail where bankFacilityDetailID ={$this->input->post('pk')} ")->row_array();
            $data = $this->db->query("SELECT sum(interestAmount) as totalinterestAmount ,sum(principalRepayment) as totalprincipalRepayment,sum(variableAmount) as totalvariableAmount,sum(variableTotal) as Total FROM srp_erp_bankfacilityloandetail  where bankFacilityID= {$id['bankFacilityID']} ")->row_array();

            if ($update) {
                echo json_encode(array('error' => 0, 'message' => 'done', 'variableAmount' => number_format($variableAmount, 2), 'variableTotal' => number_format($variableTotal, 2), 'totalinterestAmount' => number_format($data['totalinterestAmount'], 2), 'totalprincipalRepayment' => number_format($data['totalprincipalRepayment'], 2), 'totalvariableAmount' => number_format($data['totalvariableAmount'], 2), 'Total' => number_format($data['Total'], 2)));
            } else {
                echo json_encode(array('error' => 1, 'message' => 'error'));

            }

        }
    }

    function delete_bankloan()
    {
        echo json_encode($this->Bank_rec_model->delete_bankfacilityLoan());
    }

    function delete_bankrec()
    {
        echo json_encode($this->Bank_rec_model->delete_bankrec());
    }

    function getDecimalPlaces(){
        echo json_encode($this->Bank_rec_model->getDecimalPlaces());
    }

    function load_Cheque_templates(){
        $bankTransferAutoID=$this->input->post('bankTransferAutoID');

        $data['extra'] = $this->Bank_rec_model->load_Cheque_templates($bankTransferAutoID);
        $html = $this->load->view('system/payment_voucher/ajax-erp_load_Cheque_templates', $data, true);
        echo $html;
    }

    function cheque_print()
    {

        $bankTransferAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('bankTransferAutoID'));
        $coaChequeTemplateID = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('coaChequeTemplateID'));
        //$data['extra'] = $this->Bank_rec_model->fetch_payment_voucher_cheque_data($bankTransferAutoID);
        $data['extra'] = $this->Bank_rec_model->bank_transfer_master_cheque($bankTransferAutoID);
        $data['signature'] = $this->Bank_rec_model->fetch_signaturelevel();
        $this->db->select('pageLink');
        $this->db->where('coaChequeTemplateID', $coaChequeTemplateID);
        $this->db->from('srp_erp_chartofaccountchequetemplates');
        $pagelink= $this->db->get()->row_array();

        $this->load->library('NumberToWords');
        $html = $this->load->view('system/bank_rec/'.$pagelink['pageLink'].'', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4', '1');

    }




}
