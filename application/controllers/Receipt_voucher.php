<?php

class Receipt_voucher extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helpers('payable');
        $this->load->helpers('receivable');
        $this->load->model('Receipt_voucher_model');
    }

    function fetch_receipt_voucher()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( RVdate >= '" . $datefromconvert . " 00:00:00' AND RVdate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            }else if ($status == 4) {
                $status_filter = " AND ( (confirmedYN = 2 AND approvedYN != 1) or (confirmedYN = 3 AND approvedYN != 1))";
            }

            else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $where = "srp_erp_customerreceiptmaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . "";
        $this->datatables->select('srp_erp_customerreceiptmaster.receiptVoucherAutoId as receiptVoucherAutoId,RVNarration,RVcode,DATE_FORMAT(RVdate,\'' . $convertFormat . '\') AS RVdate,confirmedYN,approvedYN,srp_erp_customerreceiptmaster.createdUserID as createdUser,transactionCurrency,transactionCurrencyDecimalPlaces,IF(RVType = "Direct","Direct","Customer") as RVType,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value_search,isDeleted,if(customerID IS NULL OR customerID = 0,srp_erp_customerreceiptmaster.customerName,srp_erp_customermaster.customerName) as customerName,srp_erp_customerreceiptmaster.confirmedByEmpID as confirmedByEmp,srp_erp_customerreceiptmaster.isSystemGenerated as isSystemGenerated');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE (srp_erp_customerreceiptdetail.type!="creditnote" AND srp_erp_customerreceiptdetail.type!="SLR") GROUP BY receiptVoucherAutoId) det', '(det.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,receiptVoucherAutoId FROM srp_erp_customerreceipttaxdetails  GROUP BY receiptVoucherAutoId) addondet', '(addondet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="GL" OR srp_erp_customerreceiptdetail.type="Item"  GROUP BY receiptVoucherAutoId) tyepdet', '(tyepdet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE (srp_erp_customerreceiptdetail.type="creditnote" OR srp_erp_customerreceiptdetail.type="SLR")  GROUP BY receiptVoucherAutoId) Creditnots', '(Creditnots.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_customerreceiptmaster.customerID','left');
        $this->datatables->from('srp_erp_customerreceiptmaster');
        $this->datatables->add_column('rv_detail', '<b>Customer Name : </b> $2 <br> <b> Document Date : </b> $3 <b>&nbsp;&nbsp; Type : </b> $5  <br> <b>Comments : </b> $1  ', 'trim_desc(RVNarration),customerName,RVdate,transactionCurrency,RVType');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"RV",receiptVoucherAutoId)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"RV",receiptVoucherAutoId)');
        $this->datatables->add_column('edit', '$1', 'load_rv_action(receiptVoucherAutoId,confirmedYN,approvedYN,createdUser,isDeleted,confirmedByEmp,isSystemGenerated)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function save_receiptvoucher_header()
    {
        $date_format_policy = date_format_policy();
        $RVdt = $this->input->post('RVdate');
        $RVdate = input_format_date($RVdt, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $RVcqeDte = $this->input->post('RVchequeDate');
        $RVchequeDate = input_format_date($RVcqeDte, $date_format_policy);

        if ($this->input->post('vouchertype') != 'Direct') {
            $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        }
        $this->form_validation->set_rules('vouchertype', 'Voucher Type', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('RVdate', 'Receipt Voucher Date', 'trim|required');
        if ($this->input->post('vouchertype') == 'Direct') {
            /*$this->form_validation->set_rules('referenceno', 'Reference No', 'trim|required');
            $this->form_validation->set_rules('RVNarration', 'Narration', 'trim|required');*/
        }
        $this->form_validation->set_rules('transactionCurrencyID', 'Transaction Currency', 'trim|required');
        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
            $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        }
        $bank_detail = fetch_gl_account_desc(trim($this->input->post('RVbankCode')));
        if ($bank_detail['isCash'] == 0) {
            //$this->form_validation->set_rules('RVchequeNo', 'Cheque Number', 'trim|required');
            $this->form_validation->set_rules('RVchequeDate', 'Cheque Date', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            if ($financeyearperiodYN == 1) {
                $financearray = $this->input->post('financeyear_period');
                $financePeriod = fetchFinancePeriod($financearray);
                if ($RVdate >= $financePeriod['dateFrom'] && $RVdate <= $financePeriod['dateTo']) {
                    /* if ($RVchequeDate < $RVdate && $bank_detail['isCash'] == 0) {
                         $this->session->set_flashdata('e', 'Cheque Date Cannot be less than Receipt Voucher Date!');
                         echo json_encode(FALSE);

                     } else {*/
                    echo json_encode($this->Receipt_voucher_model->save_receiptvoucher_header());
                    //}
                } else {
                    $this->session->set_flashdata('e', 'Receipt Voucher Date not between Financial period !');
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode($this->Receipt_voucher_model->save_receiptvoucher_header());
            }
        }
    }

    function save_receiptvoucher_header_suom()
    {
        $date_format_policy = date_format_policy();
        $RVdt = $this->input->post('RVdate');
        $RVdate = input_format_date($RVdt, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');

        if ($this->input->post('vouchertype') != 'Direct') {
            $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        }
        $this->form_validation->set_rules('vouchertype', 'Voucher Type', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('RVdate', 'Receipt Voucher Date', 'trim|required');

        $this->form_validation->set_rules('transactionCurrencyID', 'Transaction Currency', 'trim|required');
        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
            $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            if ($financeyearperiodYN == 1) {
                $financearray = $this->input->post('financeyear_period');
                $financePeriod = fetchFinancePeriod($financearray);
                if ($RVdate >= $financePeriod['dateFrom'] && $RVdate <= $financePeriod['dateTo']) {
                    echo json_encode($this->Receipt_voucher_model->save_receiptvoucher_header_suom());
                } else {
                    $this->session->set_flashdata('e', 'Receipt Voucher Date not between Financial period !');
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode($this->Receipt_voucher_model->save_receiptvoucher_header_suom());
            }
        }
    }

    function save_inv_tax_detail()
    {
        $this->form_validation->set_rules('text_type', 'Tax Type', 'trim|required');
        $this->form_validation->set_rules('percentage', 'Percentage', 'trim|required');
        $this->form_validation->set_rules('receiptVoucherAutoId', 'Receipt Voucher ID', 'trim|required');
        //$this->form_validation->set_rules('amounts[]', 'Amounts', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'data' => validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->save_inv_tax_detail());
        }
    }

    function delete_tax_detail()
    {
        echo json_encode($this->Receipt_voucher_model->delete_tax_detail());
    }

    function save_direct_rv_detail()
    {
        $gl_codes = $this->input->post('gl_code');
        $projectExist = project_is_exist();
        foreach ($gl_codes as $key => $gl_code) {
            $this->form_validation->set_rules("gl_code[{$key}]", 'GL Code', 'trim|required');
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            /*$this->form_validation->set_rules('description', 'Description', 'trim|required');*/
            $this->form_validation->set_rules("segment_gl[{$key}]", 'Segment', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));

            $this->session->set_flashdata($msgtype = 'e', join('', $validateMsg));
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Receipt_voucher_model->save_direct_rv_detail());
        }
    }

    function save_match_amount()
    {
        $this->form_validation->set_rules('matchID', 'Match ID', 'trim|required');
        $amounts = $this->input->post('amounts');
        foreach ($amounts as $key => $amount) {
            $this->form_validation->set_rules("amounts[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("invoiceAutoID[{$key}]", 'Invoice', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'messsage' => validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->save_match_amount());
        }
    }

    function fetch_receipt_match()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('srp_erp_rvadvancematch.matchID as matchID,DATE_FORMAT(matchDate,\'' . $convertFormat . '\') AS matchDate,matchSystemCode,refNo,Narration,srp_erp_customermaster.customerName as cusmascustomername,transactionCurrency ,transactionCurrencyDecimalPlaces,confirmedYN,,det.transactionAmount as total_value,det.transactionAmount as detTransactionAmount,isDeleted,srp_erp_rvadvancematch.confirmedByEmpID as confirmedByEmp,srp_erp_rvadvancematch.createdUserID as createdUser');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,matchID FROM srp_erp_rvadvancematchdetails GROUP BY matchID) det', '(det.matchID = srp_erp_rvadvancematch.matchID)', 'left');
        $this->datatables->where('srp_erp_rvadvancematch.companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->from('srp_erp_rvadvancematch');
        $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_rvadvancematch.customerID');
        $this->datatables->add_column('detail', ' <b>Customer Name : </b> $2 <br> <b>Voucher Date : </b> $3  <b> <br> <b>Comments : </b> $1 ', 'Narration,cusmascustomername,matchDate,transactionCurrency');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_approval(confirmedYN)');
        $this->datatables->add_column('edit', '$1', 'load_rvm_action(matchID,confirmedYN,isDeleted,confirmedByEmp,createdUser)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function Receipt_match_confirmation()
    {
        echo json_encode($this->Receipt_voucher_model->Receipt_match_confirmation());
    }

    function save_receipt_match_header()
    {
        // $this->form_validation->set_rules('PVdate', 'Payment Voucher Date', 'trim|required');
        // $this->form_validation->set_rules('referenceno', 'Reference No', 'trim|required');
        $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Receipt Currency', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Receipt_voucher_model->save_receipt_match_header());
        }
    }

    function delete_item_direct()
    {
        echo json_encode($this->Receipt_voucher_model->delete_item_direct());
    }

    function delete_rv_match_detail()
    {
        echo json_encode($this->Receipt_voucher_model->delete_rv_match_detail());
    }

    function delete_rv_match()
    {
        echo json_encode($this->Receipt_voucher_model->delete_rv_match());
    }

    function fetch_rv_details()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_rv_details());
    }

    function load_receipt_voucher_header()
    {
        echo json_encode($this->Receipt_voucher_model->load_receipt_voucher_header());
    }

    function load_receipt_match_header()
    {
        echo json_encode($this->Receipt_voucher_model->load_receipt_match_header());
    }

    function fetch_match_detail()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_match_detail());
    }

    function fetch_rv_advance_detail()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_rv_advance_detail());
    }

    function load_rv_match_conformation()
    {
        $matchID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('matchID'));
        $data['extra'] = $this->Receipt_voucher_model->fetch_receipt_voucher_match_template_data($matchID);
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        $html = $this->load->view('system/receipt_voucher/erp_receipt_voucher_match_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function load_rv_conformation()
    {
        $receiptVoucherAutoId = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('receiptVoucherAutoId'));
        $data['extra'] = $this->Receipt_voucher_model->fetch_receipt_voucher_template_data($receiptVoucherAutoId);
        $data['approval'] = $this->input->post('approval');
        if (!$this->input->post('html')) {
            $data['signature']=$this->Receipt_voucher_model->fetch_signaturelevel();
        } else {
            $data['signature']='';
        }
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        $printlink = print_template_pdf('RV','system/receipt_voucher/erp_receipt_voucher_print');

        $html = $this->load->view($printlink, $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function fetch_detail()
    {
        $data['master'] = $this->Receipt_voucher_model->load_receipt_voucher_header();
        $data['receiptVoucherAutoId'] = trim($this->input->post('receiptVoucherAutoId'));
        if ($data['master']['RVType'] == 'Invoices') {
            $data['customer_inv'] = $this->Receipt_voucher_model->customer_inv($data['master']['customerID'], $data['master']['transactionCurrency'], $data['master']['RVdate']);
            $data['credit_note'] = $this->Receipt_voucher_model->fetch_credit_note($data['master']['customerID'], $data['master']['transactionCurrencyID'], $data['master']['RVdate']);

        }
        $data['totalamountreceipt'] =  $this->Receipt_voucher_model->totalamountreceipt($data['receiptVoucherAutoId']);
        $data['RVType'] = $data['master']['RVType'];
        $data['customerID'] = $data['master']['customerID'];
        $data['gl_code_arr'] = dropdown_all_revenue_gl();
        $data['gl_code_arr_income'] = fetch_all_gl_codes('PLI');
        $data['segment_arr'] = fetch_segment();
        $data['detail'] = $this->Receipt_voucher_model->fetch_rv_details();
        $data['tab'] = $this->input->post('tab');
        $this->load->view('system/receipt_voucher/receipt_voucher_detail.php', $data);
    }

    function fetch_Receipt_voucher_approval()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $approvedYN = trim($this->input->post('approvedYN'));
        $currentuserid = current_userID();
        if($approvedYN == 0)
        {
            $this->datatables->select('srp_erp_customerreceiptmaster.receiptVoucherAutoId as receiptVoucherAutoId,RVcode,RVNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(RVdate,\'' . $convertFormat . '\') AS RVdate,,transactionCurrency,transactionCurrencyDecimalPlaces,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value,det.transactionAmount as detTransactionAmount,if(customerID IS NULL OR customerID = 0,srp_erp_customerreceiptmaster.customerName,srp_erp_customermaster.customerName) as customerName', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE (srp_erp_customerreceiptdetail.type!="creditnote" AND srp_erp_customerreceiptdetail.type!="SLR") GROUP BY receiptVoucherAutoId) det', '(det.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->from('srp_erp_customerreceiptmaster');
            $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_customerreceiptmaster.customerID','left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerreceiptmaster.receiptVoucherAutoId AND srp_erp_documentapproved.approvalLevelID = srp_erp_customerreceiptmaster.currentLevelNo');
            $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_customerreceiptmaster.currentLevelNo');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE (srp_erp_customerreceiptdetail.type="creditnote" OR srp_erp_customerreceiptdetail.type="SLR")  GROUP BY receiptVoucherAutoId) Creditnots', '(Creditnots.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,receiptVoucherAutoId FROM srp_erp_customerreceipttaxdetails  GROUP BY receiptVoucherAutoId) addondet', '(addondet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="GL" OR srp_erp_customerreceiptdetail.type="Item"  GROUP BY receiptVoucherAutoId) tyepdet', '(tyepdet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'RV');
            $this->datatables->where('srp_erp_approvalusers.documentID', 'RV');
            $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
            $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
            $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
            $this->datatables->where('srp_erp_customerreceiptmaster.companyID', $companyID);
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('RVcode', '$1', 'approval_change_modal(RVcode,receiptVoucherAutoId,documentApprovedID,approvalLevelID,approvedYN,RV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "RV", receiptVoucherAutoId)');
            $this->datatables->add_column('edit', '$1', 'RV_action_approval(receiptVoucherAutoId,approvalLevelID,approvedYN,documentApprovedID,0)');
            echo $this->datatables->generate();
        }else
        {
            $this->datatables->select('srp_erp_customerreceiptmaster.receiptVoucherAutoId as receiptVoucherAutoId,RVcode,RVNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(RVdate,\'' . $convertFormat . '\') AS RVdate,,transactionCurrency,transactionCurrencyDecimalPlaces,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value,det.transactionAmount as detTransactionAmount,if(customerID IS NULL OR customerID = 0,srp_erp_customerreceiptmaster.customerName,srp_erp_customermaster.customerName) as customerName', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE (srp_erp_customerreceiptdetail.type!="creditnote" AND srp_erp_customerreceiptdetail.type!="SLR") GROUP BY receiptVoucherAutoId) det', '(det.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->from('srp_erp_customerreceiptmaster');
            $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_customerreceiptmaster.customerID','left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerreceiptmaster.receiptVoucherAutoId');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE (srp_erp_customerreceiptdetail.type="creditnote" OR srp_erp_customerreceiptdetail.type="creditnote")  GROUP BY receiptVoucherAutoId) Creditnots', '(Creditnots.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,receiptVoucherAutoId FROM srp_erp_customerreceipttaxdetails  GROUP BY receiptVoucherAutoId) addondet', '(addondet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="GL" OR srp_erp_customerreceiptdetail.type="Item"  GROUP BY receiptVoucherAutoId) tyepdet', '(tyepdet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'RV');
            $this->datatables->where('srp_erp_customerreceiptmaster.companyID', $companyID);
            $this->datatables->where('srp_erp_documentapproved.approvedEmpID', $currentuserid);
            $this->datatables->group_by('srp_erp_customerreceiptmaster.receiptVoucherAutoId');
            $this->datatables->group_by('srp_erp_documentapproved.approvalLevelID');
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('RVcode', '$1', 'approval_change_modal(RVcode,receiptVoucherAutoId,documentApprovedID,approvalLevelID,approvedYN,RV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "RV", receiptVoucherAutoId)');
            $this->datatables->add_column('edit', '$1', 'RV_action_approval(receiptVoucherAutoId,approvalLevelID,approvedYN,documentApprovedID,0)');
            echo $this->datatables->generate();
        }

    }

    function save_rv_item_detail()
    {
        $projectExist = project_is_exist();
        $searchs = $this->input->post('search');
        $itemAutoID = $this->input->post('itemAutoID');

        foreach ($searchs as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID[$key]);
            $serviceitm= $this->db->get()->row_array();

            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            if($serviceitm['mainCategory']!='Service') {
                $this->form_validation->set_rules("wareHouseAutoID[{$key}]", 'Ware House', 'trim|required');
            }
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity Requested', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Estimated Amount', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
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
            echo json_encode($this->Receipt_voucher_model->save_rv_item_detail());
        }
    }

    function receipt_confirmation_suom()
    {
        echo json_encode($this->Receipt_voucher_model->receipt_confirmation_suom());
    }

    function receipt_confirmation()
    {
        echo json_encode($this->Receipt_voucher_model->receipt_confirmation());
    }

    function save_inv_base_items()
    {
        echo json_encode($this->Receipt_voucher_model->save_inv_base_items());
    }

    function save_rv_approval()
    {
        $system_code = trim($this->input->post('receiptVoucherAutoId'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        $companyid = current_companyID();
        $currentdate = current_date(false);
        $PostDatedChequeManagement = getPolicyValues('PDC', 'All'); // policy for post dated cheque

        $mastertbl = $this->db->query("SELECT RVdate, RVchequeDate FROM `srp_erp_customerreceiptmaster` where companyID = $companyid And receiptVoucherAutoId = $system_code ")->row_array();
        $mastertbldetail = $this->db->query("SELECT receiptVoucherAutoId FROM `srp_erp_customerreceiptdetail` WHERE companyID = $companyid AND type = 'Item' AND receiptVoucherAutoId = $system_code")->row_array();

        if ($PostDatedChequeManagement == 1 && ($mastertbl['RVchequeDate'] != '' || !empty($mastertbl['RVchequeDate'])) && (empty($mastertbldetail['receiptVoucherAutoId']) || $mastertbldetail['receiptVoucherAutoId']==' ') && $status == 1) {
            if ($mastertbl['RVchequeDate'] > $mastertbl['RVdate']) {
                if ($currentdate >= $mastertbl['RVchequeDate']) {
                    if ($status == 1) {
                        $approvedYN = checkApproved($system_code, 'RV', $level_id);
                        if ($approvedYN) {
                            //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                            echo json_encode(array('w', 'Document already approved', 1));
                        } else {
                            $this->db->select('receiptVoucherAutoId');
                            $this->db->where('receiptVoucherAutoId', trim($system_code));
                            $this->db->where('confirmedYN', 2);
                            $this->db->from('srp_erp_customerreceiptmaster');
                            $po_approved = $this->db->get()->row_array();
                            if (!empty($po_approved)) {
                                // $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                                echo json_encode(array('w', 'Document already rejected', 1));
                            } else {
                                $this->form_validation->set_rules('status', 'Status', 'trim|required');
                                if ($this->input->post('status') == 2) {
                                    $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                                }
                                $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                                $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                                if ($this->form_validation->run() == FALSE) {
                                    //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                    echo json_encode(array('e', validation_errors(), 1));
                                } else {
                                    echo json_encode($this->Receipt_voucher_model->save_rv_approval());
                                }
                            }
                        }
                    } else if ($status == 2) {
                        $this->db->select('receiptVoucherAutoId');
                        $this->db->where('receiptVoucherAutoId', trim($system_code));
                        $this->db->where('confirmedYN', 2);
                        $this->db->from('srp_erp_customerreceiptmaster');
                        $po_approved = $this->db->get()->row_array();
                        if (!empty($po_approved)) {
                            //$this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                            echo json_encode(array('w', 'Document already rejected', 1));
                        } else {
                            $rejectYN = checkApproved($system_code, 'RV', $level_id);
                            if (!empty($rejectYN)) {
                                //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                                echo json_encode(array('w', 'Document already approved', 1));
                            } else {
                                $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                                if ($this->input->post('status') == 2) {
                                    $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                                }
                                $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                                $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                                if ($this->form_validation->run() == FALSE) {
                                    //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                    echo json_encode(array('e', validation_errors(), 1));
                                } else {
                                    echo json_encode($this->Receipt_voucher_model->save_rv_approval());
                                }
                            }
                        }
                    }
                }else
                {
                    echo json_encode(array('e','This is a post dated cheque document. you cannot approve this document before the cheque date.'));
                }


            }else
            {
                if ($status == 1) {
                    $approvedYN = checkApproved($system_code, 'RV', $level_id);
                    if ($approvedYN) {
                        //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                        echo json_encode(array('w', 'Document already approved', 1));
                    } else {
                        $this->db->select('receiptVoucherAutoId');
                        $this->db->where('receiptVoucherAutoId', trim($system_code));
                        $this->db->where('confirmedYN', 2);
                        $this->db->from('srp_erp_customerreceiptmaster');
                        $po_approved = $this->db->get()->row_array();
                        if (!empty($po_approved)) {
                            // $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                            echo json_encode(array('w', 'Document already rejected', 1));
                        } else {
                            $this->form_validation->set_rules('status', 'Status', 'trim|required');
                            if ($this->input->post('status') == 2) {
                                $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                            }
                            $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                            $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                            if ($this->form_validation->run() == FALSE) {
                                //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                echo json_encode(array('e', validation_errors(), 1));
                            } else {
                                echo json_encode($this->Receipt_voucher_model->save_rv_approval());
                            }
                        }
                    }
                } else if ($status == 2) {
                    $this->db->select('receiptVoucherAutoId');
                    $this->db->where('receiptVoucherAutoId', trim($system_code));
                    $this->db->where('confirmedYN', 2);
                    $this->db->from('srp_erp_customerreceiptmaster');
                    $po_approved = $this->db->get()->row_array();
                    if (!empty($po_approved)) {
                        //$this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                        echo json_encode(array('w', 'Document already rejected', 1));
                    } else {
                        $rejectYN = checkApproved($system_code, 'RV', $level_id);
                        if (!empty($rejectYN)) {
                            //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                            echo json_encode(array('w', 'Document already approved', 1));
                        } else {
                            $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                            if ($this->input->post('status') == 2) {
                                $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                            }
                            $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                            $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                            if ($this->form_validation->run() == FALSE) {
                                //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                echo json_encode(array('e', validation_errors(), 1));
                            } else {
                                echo json_encode($this->Receipt_voucher_model->save_rv_approval());
                            }
                        }
                    }
                }
               // echo json_encode(array('e','This is a post dated cheque Document, cannot Approve before the cheque Date'));
            }


        }





        else
        {
            if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'RV', $level_id);
            if ($approvedYN) {
                //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(array('w', 'Document already approved', 1));
            } else {
                $this->db->select('receiptVoucherAutoId');
                $this->db->where('receiptVoucherAutoId', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_customerreceiptmaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    // $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(array('w', 'Document already rejected', 1));
                } else {
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(array('e', validation_errors(), 1));
                    } else {
                        echo json_encode($this->Receipt_voucher_model->save_rv_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('receiptVoucherAutoId');
            $this->db->where('receiptVoucherAutoId', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_customerreceiptmaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                //$this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(array('w', 'Document already rejected', 1));
            } else {
                $rejectYN = checkApproved($system_code, 'RV', $level_id);
                if (!empty($rejectYN)) {
                    //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(array('w', 'Document already approved', 1));
                } else {
                    $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(array('e', validation_errors(), 1));
                    } else {
                        echo json_encode($this->Receipt_voucher_model->save_rv_approval());
                    }
                }
            }
        }
    }
    }

    function update_rv_item_detail()
    {
        $projectExist = project_is_exist();
        $itemAutoID = $this->input->post('itemAutoID');

        $this->db->select('mainCategory');
        $this->db->from('srp_erp_itemmaster');
        $this->db->where('itemAutoID', $itemAutoID);
        $serviceitm= $this->db->get()->row_array();

        $this->form_validation->set_rules("itemAutoID", 'Item', 'trim|required');
        $this->form_validation->set_rules("UnitOfMeasureID", 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules("quantityRequested", 'Quantity Requested', 'trim|required');
        if($serviceitm['mainCategory']!='Service') {
            $this->form_validation->set_rules("wareHouseAutoID", 'Ware House', 'trim|required');
        }
        $this->form_validation->set_rules("estimatedAmount", 'Estimated Amount', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->update_rv_item_detail());
        }
    }

    // function save_rv_po_detail(){
    //     $this->form_validation->set_rules('po_code', 'PO Code', 'trim|required');
    //     $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
    //     $this->form_validation->set_rules('description', 'Description', 'trim|required');
    //     if($this->form_validation->run()==FALSE){
    //         $this->session->set_flashdata($msgtype='e',validation_errors());
    //         echo json_encode(FALSE);
    //     }else{
    //         echo json_encode($this->Receipt_voucher_model->save_rv_po_detail());
    //     }
    // }

    function save_rv_advance_detail()
    {
        /*$this->form_validation->set_rules('po_code', 'PO Code', 'trim|required');*/
        $amounts = $this->input->post('amount');


        foreach ($amounts as $key => $amount) {
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'Description', 'trim|required');
        }


        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));

            $this->session->set_flashdata($msgtype = 'e', join('', $validateMsg));
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Receipt_voucher_model->save_rv_advance_detail());
        }
    }

    function delete_receipt_voucher()
    {
        echo json_encode($this->Receipt_voucher_model->delete_receipt_voucher());
    }

    function delete_receipt_voucher_attachement()
    {
        echo json_encode($this->Receipt_voucher_model->delete_receipt_voucher_attachement());
    }

    function referback_receipt_voucher()
    {
        $receiptVoucherId = $this->input->post('receiptVoucherId');

        $this->db->select('approvedYN,RVcode');
        $this->db->where('receiptVoucherAutoId', trim($receiptVoucherId));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_customerreceiptmaster');
        $approved_inventory_receipt_voucher= $this->db->get()->row_array();
        if (!empty($approved_inventory_receipt_voucher)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_inventory_receipt_voucher['RVcode']));
        }
        else
        {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($receiptVoucherId, 'RV');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }

    }

    function referback_receipt_match()
    {
        $matchID = $this->input->post('matchID');

        $data['confirmedYN'] = 3;
        $data['confirmedByEmpID'] = NULL;
        $data['confirmedByName'] = NULL;
        $data['confirmedDate'] = NULL;
        $this->db->where('matchID', $matchID);
        $result = $this->db->update('srp_erp_rvadvancematch', $data);
        $this->db->delete('srp_erp_documentapproved', array('documentSystemCode' => $matchID, 'documentID' => 'RVM', 'companyID' => $this->common_data['company_data']['company_id']));
        if ($result) {
            echo json_encode(array('s', ' Referred Back Successfully.', $result));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $result));
        }
    }

    function fetch_income_all_detail()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_income_all_detail());
    }

    function update_direct_rv_detail()
    {
        $projectExist = project_is_exist();
        $gl_codes = $this->input->post('gl_code');

        $this->form_validation->set_rules("gl_code", 'GL Code', 'trim|required');
        $this->form_validation->set_rules("amount", 'Amount', 'trim|required');
        /*$this->form_validation->set_rules('description', 'Description', 'trim|required');*/
        $this->form_validation->set_rules("segment_gl", 'Segment', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->update_direct_rv_detail());
        }
    }

    function re_open_receipt_voucher()
    {
        echo json_encode($this->Receipt_voucher_model->re_open_receipt_voucher());
    }

    function re_open_receipt_match()
    {
        echo json_encode($this->Receipt_voucher_model->re_open_receipt_match());
    }

    function fetch_itemrecode_po()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_itemrecode_po());
    }

    function fetch_rv_warehouse_item()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_rv_warehouse_item());
    }


    function updateReceiptVoucher_edit_all_Item()
    {
        $projectExist = project_is_exist();
        $searchs = $this->input->post('search');
        $itemAutoID = $this->input->post('itemAutoID');

        foreach ($searchs as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID[$key]);
            $serviceitm= $this->db->get()->row_array();

            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            if($serviceitm['mainCategory']!='Service') {
                $this->form_validation->set_rules("wareHouseAutoID[{$key}]", 'Ware House', 'trim|required');
            }
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity Requested', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Estimated Amount', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
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
            echo json_encode($this->Receipt_voucher_model->updateReceiptVoucher_edit_all_Item());
        }
    }


    function fetch_rv_details_all()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_rv_details_all());
    }

    function save_creditNote_base_items()
    {
        echo json_encode($this->Receipt_voucher_model->save_creditNote_base_items());
    }

    function showBalanceAmount_matching(){
        echo json_encode($this->Receipt_voucher_model->showBalanceAmount_matching());
    }

    function fetch_receipt_voucher_suom()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( RVdate >= '" . $datefromconvert . " 00:00:00' AND RVdate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            }else if ($status == 4) {
                $status_filter = " AND ( (confirmedYN = 2 AND approvedYN = 0) or (confirmedYN = 3 AND approvedYN = 0))";
            }
            else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $where = "srp_erp_customerreceiptmaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . "";
        $this->datatables->select('srp_erp_customerreceiptmaster.receiptVoucherAutoId as receiptVoucherAutoId,RVNarration,RVcode,DATE_FORMAT(RVdate,\'' . $convertFormat . '\') AS RVdate,confirmedYN,approvedYN,srp_erp_customerreceiptmaster.createdUserID as createdUser,transactionCurrency,transactionCurrencyDecimalPlaces,IF(RVType = "Direct","Direct","Customer") as RVType,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value_search,isDeleted,if(customerID IS NULL OR customerID = 0,srp_erp_customerreceiptmaster.customerName,srp_erp_customermaster.customerName) as customerName,srp_erp_customerreceiptmaster.confirmedByEmpID as confirmedByEmp,srp_erp_customerreceiptmaster.isSystemGenerated as isSystemGenerated');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type!="creditnote" GROUP BY receiptVoucherAutoId) det', '(det.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,receiptVoucherAutoId FROM srp_erp_customerreceipttaxdetails  GROUP BY receiptVoucherAutoId) addondet', '(addondet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="GL" OR srp_erp_customerreceiptdetail.type="Item"  GROUP BY receiptVoucherAutoId) tyepdet', '(tyepdet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="creditnote"  GROUP BY receiptVoucherAutoId) Creditnots', '(Creditnots.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_customerreceiptmaster.customerID','left');
        $this->datatables->from('srp_erp_customerreceiptmaster');
        $this->datatables->add_column('rv_detail', '<b>Customer Name : </b> $2 <br> <b> Document Date : </b> $3 <b>&nbsp;&nbsp; Type : </b> $5  <br> <b>Comments : </b> $1  ', 'trim_desc(RVNarration),customerName,RVdate,transactionCurrency,RVType');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"RV",receiptVoucherAutoId)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"RV",receiptVoucherAutoId)');
        $this->datatables->add_column('edit', '$1', 'load_rv_action_suom(receiptVoucherAutoId,confirmedYN,approvedYN,createdUser,isDeleted,confirmedByEmp,isSystemGenerated)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }


    function fetch_detail_suom()
    {
        $data['master'] = $this->Receipt_voucher_model->load_receipt_voucher_header();
        if ($data['master']['RVType'] == 'Invoices') {
            $data['customer_inv'] = $this->Receipt_voucher_model->customer_inv($data['master']['customerID'], $data['master']['transactionCurrency'], $data['master']['RVdate']);
            $data['credit_note'] = $this->Receipt_voucher_model->fetch_credit_note($data['master']['customerID'], $data['master']['transactionCurrencyID'], $data['master']['RVdate']);
        }
        $data['receiptVoucherAutoId'] = trim($this->input->post('receiptVoucherAutoId'));
        $data['RVType'] = $data['master']['RVType'];
        $data['customerID'] = $data['master']['customerID'];
        $data['gl_code_arr'] = dropdown_all_revenue_gl();
        $data['gl_code_arr_income'] = fetch_all_gl_codes('PLI');
        $data['segment_arr'] = fetch_segment();
        $data['detail'] = $this->Receipt_voucher_model->fetch_rv_details();
        $data['tab'] = $this->input->post('tab');
        $this->load->view('system/receipt_voucher/receipt_voucher_detail_suom.php', $data);
    }


    function save_rv_item_detail_suom()
    {
        $projectExist = project_is_exist();
        $searchs = $this->input->post('search');
        $itemAutoID = $this->input->post('itemAutoID');

        foreach ($searchs as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID[$key]);
            $serviceitm= $this->db->get()->row_array();

            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            if($serviceitm['mainCategory']!='Service') {
                $this->form_validation->set_rules("wareHouseAutoID[{$key}]", 'Ware House', 'trim|required');
            }
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity Requested', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Estimated Amount', 'trim|required');
            //$this->form_validation->set_rules("SUOMIDhn[{$key}]", 'Secondary Unit Of Measure', 'trim|required');
            if(!empty($this->input->post("SUOMIDhn[$key]"))){
                $this->form_validation->set_rules("SUOMQty[{$key}]", 'Secondary QTY', 'trim|required|greater_than[0]');
            }
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
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
            echo json_encode($this->Receipt_voucher_model->save_rv_item_detail());
        }
    }

    function fetch_rv_details_suom()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_rv_details_suom());
    }

    function fetch_income_all_detail_suom()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_income_all_detail_suom());
    }

    function update_rv_item_detail_suom()
    {
        $projectExist = project_is_exist();
        $itemAutoID = $this->input->post('itemAutoID');

        $this->db->select('mainCategory');
        $this->db->from('srp_erp_itemmaster');
        $this->db->where('itemAutoID', $itemAutoID);
        $serviceitm= $this->db->get()->row_array();

        $this->form_validation->set_rules("itemAutoID", 'Item', 'trim|required');
        $this->form_validation->set_rules("UnitOfMeasureID", 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules("quantityRequested", 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules("SUOMQty", 'Secondary QTY', 'trim|required');
        $this->form_validation->set_rules("SUOMIDhn", 'Secondary UOM', 'trim|required');
        if($serviceitm['mainCategory']!='Service') {
            $this->form_validation->set_rules("wareHouseAutoID", 'Ware House', 'trim|required');
        }
        $this->form_validation->set_rules("estimatedAmount", 'Estimated Amount', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->update_rv_item_detail());
        }
    }
    function fetch_customer_Dropdown_all_receiprtvoucer()
    {
        $data_arr = array();
        $customerid = $this->input->post('customer');
        if($customerid)
        {
            $customer = $customerid;
        }else
        {
            $customer = '';
        }

        $companyID = $this->common_data['company_data']['company_id'];
        $customerqry = "SELECT customerAutoID,customerName,customerSystemCode,customerCountry FROM srp_erp_customermaster WHERE companyID = {$companyID}";
        $customermMaster = $this->db->query($customerqry)->result_array();
        $data_arr = array('' => 'Select Customer');
        if (!empty($customermMaster)) {
            foreach ($customermMaster as $row) {
                $data_arr[trim($row['customerAutoID'])] = (trim($row['customerSystemCode']) ? trim($row['customerSystemCode']) . ' | ' : '') . trim($row['customerName']) . (trim($row['customerCountry']) ? ' | ' . trim($row['customerCountry']) : '');
            }
        }
        echo form_dropdown('customerID', $data_arr, $customer, 'class="form-control select2" id="customerID" onchange="Load_customer_currency(this.value);"');
    }

    function fetchTotalAmount_receipt()
    {
        $receiptVoucherAutoId = trim($this->input->post('receiptVoucherAutoId'));
        echo json_encode($this->Receipt_voucher_model->fetchTotalAmount_receipt($receiptVoucherAutoId));
    }

    function save_receiptVoucher_payment_details()
    {
        $payAmounts = $this->input->post('payAmount');
        $bank = $this->input->post('paymentMode');
        $this->form_validation->set_rules("receiptVoucherAutoId", 'receiptVoucherAutoId', 'trim|required');

        foreach ($payAmounts as $key => $payAmount){
            $this->form_validation->set_rules("payAmount[$key]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("payBankCode[$key]", 'Bank or Cash', 'trim|required');
            $this->form_validation->set_rules("paymentMode[$key]", 'Payment Type', 'trim|required');
            //$bank_detail = fetch_gl_account_desc(trim($bank[$key]));
           // if ($bank_detail['isCash'] == 0) {
            if (trim($bank[$key]) == 2) {
                $this->form_validation->set_rules("payChequeNo[$key]", 'Cheque No', 'trim|required');
                $this->form_validation->set_rules("payChequeDate[$key]", 'Cheque Date', 'trim|required');
            }
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->save_receiptVoucher_payment_details());
        }
    }

    function update_receiptVoucher_payment_details()
    {
        $payAmounts = $this->input->post('edit_payAmount');
        $bank = $this->input->post('edit_paymentMode');
        $this->form_validation->set_rules("receiptPaymentID", 'edit_receiptPaymentID', 'trim|required');
        $this->form_validation->set_rules("edit_paymentMode", 'edit_receiptPaymentID', 'trim|required');
        $this->form_validation->set_rules("receiptVoucherAutoId", 'receiptVoucherAutoId', 'trim|required');
        $this->form_validation->set_rules("edit_payAmount", 'Amount', 'trim|required');
        $this->form_validation->set_rules("edit_payBankCode", 'Bank or Cash', 'trim|required');
       // $bank_detail = fetch_gl_account_desc(trim($bank));
        if (trim($bank) == 2) {
            $this->form_validation->set_rules("edit_payChequeNo", 'Cheque No', 'trim|required');
            $this->form_validation->set_rules("edit_payChequeDate", 'Cheque Date', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Receipt_voucher_model->update_receiptVoucher_payment_details());
        }
    }

    function fetch_receipt_payment_details()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_receipt_payment_details());
    }

    function delete_payment_details()
    {
        echo json_encode($this->Receipt_voucher_model->delete_payment_details());
    }

    function fetch_receiptVocher_payment_detail()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_receiptVocher_payment_detail());
    }

    function load_rv_conformation_suom()
    {
        $receiptVoucherAutoId = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('receiptVoucherAutoId'));
        $data['extra'] = $this->Receipt_voucher_model->fetch_receipt_voucher_template_data_suom($receiptVoucherAutoId);
        $data['approval'] = $this->input->post('approval');
        $data['html'] = $this->input->post('html');
        if (!$this->input->post('html')) {
            $data['signature']=$this->Receipt_voucher_model->fetch_signaturelevel();
        } else {
            $data['signature']='';
        }
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        $printlink = print_template_pdf('RV','system/receipt_voucher/erp_receipt_voucher_print_suom');

        $html = $this->load->view($printlink, $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }


    function fetch_Receipt_voucher_approval_suom()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $approvedYN = trim($this->input->post('approvedYN'));
        $currentuserid = current_userID();
        if($approvedYN == 0)
        {
            $this->datatables->select('srp_erp_customerreceiptmaster.receiptVoucherAutoId as receiptVoucherAutoId,RVcode,RVNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(RVdate,\'' . $convertFormat . '\') AS RVdate,,transactionCurrency,transactionCurrencyDecimalPlaces,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value,ROUND(det.transactionAmount, 2) as detTransactionAmount,if(customerID IS NULL OR customerID = 0,srp_erp_customerreceiptmaster.customerName,srp_erp_customermaster.customerName) as customerName', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type!="creditnote" GROUP BY receiptVoucherAutoId) det', '(det.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->from('srp_erp_customerreceiptmaster');
            $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_customerreceiptmaster.customerID','left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerreceiptmaster.receiptVoucherAutoId AND srp_erp_documentapproved.approvalLevelID = srp_erp_customerreceiptmaster.currentLevelNo');
            $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_customerreceiptmaster.currentLevelNo');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="creditnote"  GROUP BY receiptVoucherAutoId) Creditnots', '(Creditnots.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,receiptVoucherAutoId FROM srp_erp_customerreceipttaxdetails  GROUP BY receiptVoucherAutoId) addondet', '(addondet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="GL" OR srp_erp_customerreceiptdetail.type="Item"  GROUP BY receiptVoucherAutoId) tyepdet', '(tyepdet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'RV');
            $this->datatables->where('srp_erp_approvalusers.documentID', 'RV');
            $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
            $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
            $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
            $this->datatables->where('srp_erp_customerreceiptmaster.companyID', $companyID);
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('RVcode', '$1', 'approval_change_modal(RVcode,receiptVoucherAutoId,documentApprovedID,approvalLevelID,approvedYN,RV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "RV", receiptVoucherAutoId)');
            $this->datatables->add_column('edit', '$1', 'RV_action_approval_suom(receiptVoucherAutoId,approvalLevelID,approvedYN,documentApprovedID,0)');
            echo $this->datatables->generate();
        }else
        {
            $this->datatables->select('srp_erp_customerreceiptmaster.receiptVoucherAutoId as receiptVoucherAutoId,RVcode,RVNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(RVdate,\'' . $convertFormat . '\') AS RVdate,,transactionCurrency,transactionCurrencyDecimalPlaces,(((IFNULL(addondet.taxPercentage,0)/100)*IFNULL(tyepdet.transactionAmount,0))+IFNULL(det.transactionAmount,0)-IFNULL(Creditnots.transactionAmount,0)) as total_value,ROUND(det.transactionAmount, 2) as detTransactionAmount,if(customerID IS NULL OR customerID = 0,srp_erp_customerreceiptmaster.customerName,srp_erp_customermaster.customerName) as customerName', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type!="creditnote" GROUP BY receiptVoucherAutoId) det', '(det.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->from('srp_erp_customerreceiptmaster');
            $this->datatables->join('srp_erp_customermaster','srp_erp_customermaster.customerAutoID = srp_erp_customerreceiptmaster.customerID','left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerreceiptmaster.receiptVoucherAutoId');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="creditnote"  GROUP BY receiptVoucherAutoId) Creditnots', '(Creditnots.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,receiptVoucherAutoId FROM srp_erp_customerreceipttaxdetails  GROUP BY receiptVoucherAutoId) addondet', '(addondet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,receiptVoucherAutoId FROM srp_erp_customerreceiptdetail WHERE srp_erp_customerreceiptdetail.type="GL" OR srp_erp_customerreceiptdetail.type="Item"  GROUP BY receiptVoucherAutoId) tyepdet', '(tyepdet.receiptVoucherAutoId = srp_erp_customerreceiptmaster.receiptVoucherAutoId)', 'left');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'RV');
            $this->datatables->where('srp_erp_customerreceiptmaster.companyID', $companyID);
            $this->datatables->where('srp_erp_documentapproved.approvedEmpID', $currentuserid);
            $this->datatables->group_by('srp_erp_customerreceiptmaster.receiptVoucherAutoId');
            $this->datatables->group_by('srp_erp_documentapproved.approvalLevelID');
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('RVcode', '$1', 'approval_change_modal(RVcode,receiptVoucherAutoId,documentApprovedID,approvalLevelID,approvedYN,RV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "RV", receiptVoucherAutoId)');
            $this->datatables->add_column('edit', '$1', 'RV_action_approval_suom(receiptVoucherAutoId,approvalLevelID,approvedYN,documentApprovedID,0)');
            echo $this->datatables->generate();
        }
    }

    function save_rv_approval_suom()
    {
        $system_code = trim($this->input->post('receiptVoucherAutoId'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        $companyid = current_companyID();
        $currentdate = current_date(false);
        $PostDatedChequeManagement = getPolicyValues('PDC', 'All'); // policy for post dated cheque

        $mastertbl = $this->db->query("SELECT RVdate, RVchequeDate FROM `srp_erp_customerreceiptmaster` where companyID = $companyid And receiptVoucherAutoId = $system_code ")->row_array();
        $mastertbldetail = $this->db->query("SELECT receiptVoucherAutoId FROM `srp_erp_customerreceiptdetail` WHERE companyID = $companyid AND type = 'Item' AND receiptVoucherAutoId = $system_code")->row_array();

        if ($PostDatedChequeManagement == 1 && ($mastertbl['RVchequeDate'] != '' || !empty($mastertbl['RVchequeDate'])) && (empty($mastertbldetail['receiptVoucherAutoId']) || $mastertbldetail['receiptVoucherAutoId']==' ') && $status == 1) {
            if ($mastertbl['RVchequeDate'] > $mastertbl['RVdate']) {
                if ($currentdate >= $mastertbl['RVchequeDate']) {
                    if ($status == 1) {
                        $approvedYN = checkApproved($system_code, 'RV', $level_id);
                        if ($approvedYN) {
                            //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                            echo json_encode(array('w', 'Document already approved', 1));
                        } else {
                            $this->db->select('receiptVoucherAutoId');
                            $this->db->where('receiptVoucherAutoId', trim($system_code));
                            $this->db->where('confirmedYN', 2);
                            $this->db->from('srp_erp_customerreceiptmaster');
                            $po_approved = $this->db->get()->row_array();
                            if (!empty($po_approved)) {
                                // $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                                echo json_encode(array('w', 'Document already rejected', 1));
                            } else {
                                $this->form_validation->set_rules('status', 'Status', 'trim|required');
                                if ($this->input->post('status') == 2) {
                                    $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                                }
                                $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                                $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                                if ($this->form_validation->run() == FALSE) {
                                    //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                    echo json_encode(array('e', validation_errors(), 1));
                                } else {
                                    echo json_encode($this->Receipt_voucher_model->save_rv_approval_suom());
                                }
                            }
                        }
                    } else if ($status == 2) {
                        $this->db->select('receiptVoucherAutoId');
                        $this->db->where('receiptVoucherAutoId', trim($system_code));
                        $this->db->where('confirmedYN', 2);
                        $this->db->from('srp_erp_customerreceiptmaster');
                        $po_approved = $this->db->get()->row_array();
                        if (!empty($po_approved)) {
                            //$this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                            echo json_encode(array('w', 'Document already rejected', 1));
                        } else {
                            $rejectYN = checkApproved($system_code, 'RV', $level_id);
                            if (!empty($rejectYN)) {
                                //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                                echo json_encode(array('w', 'Document already approved', 1));
                            } else {
                                $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                                if ($this->input->post('status') == 2) {
                                    $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                                }
                                $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                                $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                                if ($this->form_validation->run() == FALSE) {
                                    //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                    echo json_encode(array('e', validation_errors(), 1));
                                } else {
                                    echo json_encode($this->Receipt_voucher_model->save_rv_approval_suom());
                                }
                            }
                        }
                    }
                }else
                {
                    echo json_encode(array('e','This is a post dated cheque document. you cannot approve this document before the cheque date.'));
                }

            }else
            {
                if ($status == 1) {
                    $approvedYN = checkApproved($system_code, 'RV', $level_id);
                    if ($approvedYN) {
                        //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                        echo json_encode(array('w', 'Document already approved', 1));
                    } else {
                        $this->db->select('receiptVoucherAutoId');
                        $this->db->where('receiptVoucherAutoId', trim($system_code));
                        $this->db->where('confirmedYN', 2);
                        $this->db->from('srp_erp_customerreceiptmaster');
                        $po_approved = $this->db->get()->row_array();
                        if (!empty($po_approved)) {
                            // $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                            echo json_encode(array('w', 'Document already rejected', 1));
                        } else {
                            $this->form_validation->set_rules('status', 'Status', 'trim|required');
                            if ($this->input->post('status') == 2) {
                                $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                            }
                            $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                            $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                            if ($this->form_validation->run() == FALSE) {
                                //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                echo json_encode(array('e', validation_errors(), 1));
                            } else {
                                echo json_encode($this->Receipt_voucher_model->save_rv_approval_suom());
                            }
                        }
                    }
                } else if ($status == 2) {
                    $this->db->select('receiptVoucherAutoId');
                    $this->db->where('receiptVoucherAutoId', trim($system_code));
                    $this->db->where('confirmedYN', 2);
                    $this->db->from('srp_erp_customerreceiptmaster');
                    $po_approved = $this->db->get()->row_array();
                    if (!empty($po_approved)) {
                        //$this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                        echo json_encode(array('w', 'Document already rejected', 1));
                    } else {
                        $rejectYN = checkApproved($system_code, 'RV', $level_id);
                        if (!empty($rejectYN)) {
                            //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                            echo json_encode(array('w', 'Document already approved', 1));
                        } else {
                            $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                            if ($this->input->post('status') == 2) {
                                $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                            }
                            $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                            $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                            if ($this->form_validation->run() == FALSE) {
                                //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                                echo json_encode(array('e', validation_errors(), 1));
                            } else {
                                echo json_encode($this->Receipt_voucher_model->save_rv_approval_suom());
                            }
                        }
                    }
                }
                // echo json_encode(array('e','This is a post dated cheque Document, cannot Approve before the cheque Date'));
            }


        }

        else
        {
            if ($status == 1) {
                $approvedYN = checkApproved($system_code, 'RV', $level_id);
                if ($approvedYN) {
                    //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(array('w', 'Document already approved', 1));
                } else {
                    $this->db->select('receiptVoucherAutoId');
                    $this->db->where('receiptVoucherAutoId', trim($system_code));
                    $this->db->where('confirmedYN', 2);
                    $this->db->from('srp_erp_customerreceiptmaster');
                    $po_approved = $this->db->get()->row_array();
                    if (!empty($po_approved)) {
                        // $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                        echo json_encode(array('w', 'Document already rejected', 1));
                    } else {
                        $this->form_validation->set_rules('status', 'Status', 'trim|required');
                        if ($this->input->post('status') == 2) {
                            $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                        }
                        $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                        $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                        if ($this->form_validation->run() == FALSE) {
                            //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                            echo json_encode(array('e', validation_errors(), 1));
                        } else {
                            echo json_encode($this->Receipt_voucher_model->save_rv_approval_suom());
                        }
                    }
                }
            } else if ($status == 2) {
                $this->db->select('receiptVoucherAutoId');
                $this->db->where('receiptVoucherAutoId', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_customerreceiptmaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    //$this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(array('w', 'Document already rejected', 1));
                } else {
                    $rejectYN = checkApproved($system_code, 'RV', $level_id);
                    if (!empty($rejectYN)) {
                        //$this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                        echo json_encode(array('w', 'Document already approved', 1));
                    } else {
                        $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                        if ($this->input->post('status') == 2) {
                            $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                        }
                        $this->form_validation->set_rules('receiptVoucherAutoId', 'Payment Voucher ID ', 'trim|required');
                        $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                        if ($this->form_validation->run() == FALSE) {
                            //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                            echo json_encode(array('e', validation_errors(), 1));
                        } else {
                            echo json_encode($this->Receipt_voucher_model->save_rv_approval_suom());
                        }
                    }
                }
            }
        }
    }

    function delete_item_direct_suom()
    {
        echo json_encode($this->Receipt_voucher_model->delete_item_direct_suom());
    }

    function fetch_bankCard_details_suom()
    {
        echo json_encode($this->Receipt_voucher_model->fetch_bankCard_details_suom());
    }
}