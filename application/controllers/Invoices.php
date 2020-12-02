<?php

class Invoices extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helpers('buyback_helper');
        $this->load->helpers('insurancetype_helper');
        $this->load->model('Invoice_model');
    }

    function fetch_invoices()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( invoiceDate >= '" . $datefromconvert . " 00:00:00' AND invoiceDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            } else if ($status == 4) {
                $status_filter = " AND ((confirmedYN = 2 AND approvedYN != 1) or (confirmedYN = 3 AND approvedYN != 1))";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $sSearch=$this->input->post('sSearch');
        $searches='';
        if($sSearch){
            $search = str_replace("\\", "\\\\", $sSearch);
            //$this->datatables->or_like('contractCode',"$search");
            $searches = " AND (( invoiceCode Like '%$search%' ESCAPE '!') OR ( invoiceType Like '%$sSearch%' ESCAPE '!') OR ( det.transactionAmount Like '%$sSearch%')  OR (invoiceNarration Like '%$sSearch%') OR (srp_erp_customermaster.customerName Like '%$sSearch%') OR (invoiceDate Like '%$sSearch%') OR (invoiceDueDate Like '%$sSearch%') OR (referenceNo Like '%$sSearch%')) ";
        }

        $where = "srp_erp_customerinvoicemaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . $searches."";
        //$this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_customerinvoicemaster.confirmedByEmpID as confirmedByEmp,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID as createdUser,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,((((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)) as total_value,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value_search,isDeleted,tempInvoiceID,referenceNo');
        $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_customerinvoicemaster.confirmedByEmpID as confirmedByEmp,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency as transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID as createdUser,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) - IFNULL( retensionTransactionAmount, 0 ) as total_value,ROUND((IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) - IFNULL(retensionTransactionAmount,0), 2) as total_value_search,isDeleted,tempInvoiceID,referenceNo,srp_erp_customerinvoicemaster.isSytemGenerated as isSytemGenerated');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(discountPercentage) as discountPercentage ,invoiceAutoID FROM srp_erp_customerinvoicediscountdetails  GROUP BY invoiceAutoID) gendiscount', '(gendiscount.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails where isTaxApplicable=1  GROUP BY invoiceAutoID) genexchargistax', '(genexchargistax.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails  GROUP BY invoiceAutoID) genexcharg', '(genexcharg.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
        $this->datatables->from('srp_erp_customerinvoicemaster');
        $this->datatables->add_column('invoice_detail', '<b>Customer Name : </b> $2 <br> <b>Document Date : </b> $3 <b style="text-indent: 1%;">&nbsp | &nbsp Due Date : </b> $4 <br> <b>Type : </b> $5 <br><b>Ref No : </b> $6 <br> <b>Comments : </b> $1 ', 'trim_desc(invoiceNarration),customermastername,invoiceDate,invoiceDueDate,invoiceType,referenceNo');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_invoice_action(invoiceAutoID,confirmedYN,approvedYN,createdUser,confirmedYN,isDeleted,tempInvoiceID,confirmedByEmp,isSytemGenerated)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function fetch_invoices_buyback()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( invoiceDate >= '" . $datefromconvert . " 00:00:00' AND invoiceDate <= '" . $datetoconvert . " 23:59:00')";
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
        $where = "srp_erp_customerinvoicemaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . "";
        $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value_search,isDeleted');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
        $this->datatables->from('srp_erp_customerinvoicemaster');
        $this->datatables->add_column('invoice_detail', '<b>Customer Name : </b> $2 <br> <b>Document Date : </b> $3 <b style="text-indent: 1%;">&nbsp | &nbsp Due Date : </b> $4 <br> <b>Type : </b> $5 <br> <b>Comments : </b> $1 ', 'trim_desc(invoiceNarration),customermastername,invoiceDate,invoiceDueDate,invoiceType');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_invoice_action_buyback(invoiceAutoID,confirmedYN,approvedYN,createdUserID,confirmedYN,isDeleted)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function save_invoice_header()
    {
        $date_format_policy = date_format_policy();
        $invDueDate = $this->input->post('invoiceDueDate');
        $invoiceDueDate = input_format_date($invDueDate, $date_format_policy);
        $invDate = $this->input->post('customerInvoiceDate');
        $invoiceDate = input_format_date($invDate, $date_format_policy);
        $docDate = $this->input->post('invoiceDate');
        $documentDate = input_format_date($docDate, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $this->form_validation->set_rules('invoiceType', 'Invoice Type', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('invoiceDate', 'Invoice Date', 'trim|required');
        $this->form_validation->set_rules('invoiceDueDate', 'Invoice Due Date', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Transaction Currency', 'trim|required');
        $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
            $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        }
        if ($this->input->post('invoiceType') == 'Direct') {
            //$this->form_validation->set_rules('referenceNo', 'Reference No', 'trim|required');
            //$this->form_validation->set_rules('invoiceNarration', 'Narration', 'trim|required');
        }


        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            if (($invoiceDate) > ($invoiceDueDate)) {
                $this->session->set_flashdata('e', ' Invoice Due Date cannot be less than Invoice Date!');
                echo json_encode(FALSE);
            } else {
                if($financeyearperiodYN==1) {
                    $financearray = $this->input->post('financeyear_period');
                    $financePeriod = fetchFinancePeriod($financearray);
                    if ($documentDate >= $financePeriod['dateFrom'] && $documentDate <= $financePeriod['dateTo']) {
                        echo json_encode($this->Invoice_model->save_invoice_header());
                    } else {
                        $this->session->set_flashdata('e', 'Document Date not between Financial period !');
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode($this->Invoice_model->save_invoice_header());
                }
            }
        }
    }

    function save_direct_invoice_detail()
    {
        $projectExist = project_is_exist();
        $gl_codes = $this->input->post('gl_code');
        $amount = $this->input->post('amount');
        $segment_gl = $this->input->post('segment_gl');

        foreach ($gl_codes as $key => $gl_code) {
            $this->form_validation->set_rules("gl_code[{$key}]", 'GL Code', 'trim|required');
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("segment_gl[{$key}]", 'Segment', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));

            $this->session->set_flashdata($msgtype = 'e', join('', $validateMsg));
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Invoice_model->save_direct_invoice_detail());
        }
    }

    function update_income_invoice_detail()
    {
        $projectExist = project_is_exist();
        $this->form_validation->set_rules('gl_code', 'GL Code', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        //$this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('segment_gl', 'Segment', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->update_income_invoice_detail());
        }
    }

    function save_con_base_items()
    {
        $ids = $this->input->post('DetailsID');
        foreach ($ids as $key => $id) {
            $num = ($key + 1);
            $this->form_validation->set_rules("DetailsID[{$key}]", "Line {$num} ID", 'trim|required');
            $this->form_validation->set_rules("amount[{$key}]", "Line {$num} Amount", 'trim|required');
            $this->form_validation->set_rules("wareHouseAutoID[{$key}]", "Line {$num} WareHouse", 'trim|required');
            $this->form_validation->set_rules("qty[{$key}]", "Line {$num} QTY", 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['e', validation_errors()]);
        } else {
            echo json_encode($this->Invoice_model->save_con_base_items());
        }
    }

    function save_delivery_based_items(){
        $ids = $this->input->post('DetailsID');
        foreach ($ids as $key => $id) {
            $num = ($key + 1);
            $this->form_validation->set_rules("DetailsID[{$key}]", "Line {$num} ID", 'trim|required');
            $this->form_validation->set_rules("amount[{$key}]", "Line {$num} Amount", 'trim|required');
            $this->form_validation->set_rules("wareHouseAutoID[{$key}]", "Line {$num} WareHouse", 'trim|required');
            $this->form_validation->set_rules("qty[{$key}]", "Line {$num} QTY", 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            die( json_encode(['e', validation_errors()]) );
        }

        echo json_encode($this->Invoice_model->save_delivery_based_items());
    }

    function fetch_con_detail_table()
    {
        echo json_encode($this->Invoice_model->fetch_con_detail_table());
    }

    function delete_item_direct()
    {
        echo json_encode($this->Invoice_model->delete_item_direct());
    }

    function referback_customer_invoice()
    {
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $this->db->select('approvedYN,invoiceCode');
        $this->db->where('invoiceAutoID', trim($invoiceAutoID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_customerinvoicemaster');
        $approved_custmoer_invoice = $this->db->get()->row_array();
        if (!empty($approved_custmoer_invoice)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_custmoer_invoice['invoiceCode']));
        } else {
            $this->load->library('Approvals');
            $status = $this->approvals->approve_delete($invoiceAutoID, 'CINV');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }


    }

    function fetch_invoice_direct_details()
    {
        echo json_encode($this->Invoice_model->fetch_invoice_direct_details());
    }

    function load_invoice_header()
    {
        echo json_encode($this->Invoice_model->load_invoice_header());
    }

    function fetch_customer_invoice_detail()
    {
        echo json_encode($this->Invoice_model->fetch_customer_invoice_detail());
    }

    function load_invoices_conformation()
    {

        $invoiceAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('invoiceAutoID'));
        $this->db->select('tempInvoiceID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['emailView'] = 0;
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();
        $this->load->library('NumberToWords');
        if(!empty($master['tempInvoiceID'])){
            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data_temp($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }

            $html = $this->load->view('system/invoices/erp_invoice_print_temp', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {
                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
            }
        }else{

            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $printHeaderFooterYN=1;
            $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            $this->db->select('printHeaderFooterYN');
            $this->db->where('companyID', current_companyID());
            $this->db->where('documentID', 'CINV');
            $this->db->from('srp_erp_documentcodemaster');
            $result = $this->db->get()->row_array();
            if(!empty($result)){
                $printHeaderFooterYN =$result['printHeaderFooterYN'];
                $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }
            $printlink = print_template_pdf('CINV','system/invoices/erp_invoice_print');

            $papersize = print_template_paper_size('CINV','A4');
            $data['papersize']=$papersize;
            //$pdfp = $this->load->view($printlink, $data, true);
            if ($this->input->post('html')) {
                $html = $this->load->view('system/invoices/erp_invoice_print_html', $data, true);
                echo $html;
            } else {
                //$html = $this->load->view('system/invoices/erp_invoice_print', $data, true);
                //$this->load->library('pdf');
                //$pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN'],null,$printHeaderFooterYN);
                //$pdf = $this->pdf->printed($pdfp, $papersize,$data['extra']['master']['approvedYN'],null,$printHeaderFooterYN);
                $this->load->view($printlink, $data);
            }
        }

    }

    function load_invoices_conformation_buyback()
    {
        $invoiceAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('invoiceAutoID'));
        $data['extra'] = $this->Invoice_model->fetch_invoice_template_data($invoiceAutoID);
        $data['html'] = $this->input->post('html');
        $data['approval'] = $this->input->post('approval');

        if (!$this->input->post('html')) {
            $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
        } else {
            $data['signature'] = '';
        }
        $html = $this->load->view('system/invoices/erp_invoice_print_buyback', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function fetch_detail()
    {
        $data['master'] = $this->Invoice_model->load_invoice_header();
        $data['invoiceAutoID'] = trim($this->input->post('invoiceAutoID'));
        $data['invoiceType'] = $data['master']['invoiceType'];
        $data['marginpercent'] = 0;
        if(!empty($data['master']['insuranceTypeID'])){
            $this->db->select('marginPercentage');
            $this->db->where('insuranceTypeID', trim($data['master']['insuranceSubTypeID']));
            $this->db->from('srp_erp_invoiceinsurancetypes');
            $margindetails = $this->db->get()->row_array();
            $data['marginpercent'] = $margindetails['marginPercentage'];
        }
        $data['customerID'] = $data['master']['customerID'];
        $data['gl_code_arr'] = fetch_all_gl_codes();
        $data['supplier_arr'] = all_supplier_drop();
        $data['gl_code_arr_income'] = fetch_all_gl_codes('PLI');
        $data['segment_arr'] = fetch_segment();
        $data['detail'] = $this->Invoice_model->fetch_detail();
        $data['customer_con'] = $this->Invoice_model->fetch_customer_con($data['master']);
        $data['tabID'] = $this->input->post('tab');
        $this->load->view('system/invoices/invoices_detail.php', $data);
    }

    function load_un_billed_delivery_orders(){
        $master = $this->Invoice_model->load_invoice_header();
        $customerID = $master['customerID'];
        $delivery_detail = $this->Invoice_model->delivery_detail($customerID);

        if (empty($delivery_detail)) {
            die( json_encode(['e', 'No records found']));
        }

        $str = '';
        for ($i = 0; $i < count($delivery_detail); $i++) {
            $auto_id = $delivery_detail[$i]['DOAutoID'];
            $dPlace = $master['transactionCurrencyDecimalPlaces'];
            $total_amount = round($delivery_detail[$i]['transactionAmount'], $dPlace);
            $invoiced_amount = round($delivery_detail[$i]['invoiced_amount'], $dPlace);
            $balance_amount = round(($total_amount - $invoiced_amount),$dPlace);
            if($balance_amount>0){
            $str .= "<tr>";
            $str .= "<td>" . ($i) . "</td>";
            $str .= "<td>" . $delivery_detail[$i]['DOCode'] . " </td>";
            $str .= "<td style='text-align: center'>" . $delivery_detail[$i]['DODate'] . "</td>";
            $str .= "<td class='text-right'>" . $delivery_detail[$i]['referenceNo'] . "</td>";

            if ($total_amount > 0) {
                $str .= "<td class='text-right'>" . number_format($total_amount, $dPlace) . "</td>";
            } else {
                $str .= "<td class='text-right'>" . number_format(0, $dPlace) . "</td>";
            }
            $str .= "<td class='text-right'>" . number_format($invoiced_amount, $dPlace) . "</td>";
            $str .= "<td class='text-right'>" . number_format($balance_amount, $dPlace) . "</td>";
            $str .= '<td><input type="hidden" name="orders[]" id="delivery_order_' . $auto_id . '" value="' . $auto_id . '">';
            $str .= '<input type="text" name="amount[]" id="amount_' . $auto_id . '" data-auto-id="' . $auto_id . '" onkeypress="return validateFloatKeyPress(this,event);" onkeyup="validate_max_receivable(this,';
            $str .= round($balance_amount, $dPlace) . ',' . $dPlace . ')" onchange="amount_round(this, ' . $dPlace . ')" class="number invoicing_amount" ></td>';
            $str .= '<td class="text-right" style="display:none;"> </td>';
            $str .= "</tr>";
            }
        }

        echo json_encode(['s', 'view'=>$str]);
    }

    function fetch_detail_buyback()
    {
        $data['master'] = $this->Invoice_model->load_invoice_header();
        $data['invoiceAutoID'] = trim($this->input->post('invoiceAutoID'));
        $data['invoiceType'] = $data['master']['invoiceType'];
        $data['customerID'] = $data['master']['customerID'];
        $data['gl_code_arr'] = fetch_all_gl_codes();
        $data['segment_arr'] = fetch_segment();
        $data['detail'] = $this->Invoice_model->fetch_detail();
        $data['customer_con'] = $this->Invoice_model->fetch_customer_con($data['master']);
        $data['tabID'] = $this->input->post('tab');
        $this->load->view('system/invoices/invoices_detail_buyback', $data);
    }

    function fetch_detail_header_lock()
    {
        echo json_encode($this->Invoice_model->fetch_detail());
    }

    function fetch_invoices_approval()
    {
        /*
        * rejected = 1
        * not rejected = 0
        * */

        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $current_user_id = current_userID();
        if($approvedYN == 0)
        {
            $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) as total_value,ROUND((IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0), 2) as total_value_search,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_customermaster.customerName as customerName,srp_erp_customerinvoicemaster.referenceNo as referenceNo', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(discountPercentage) as discountPercentage ,invoiceAutoID FROM srp_erp_customerinvoicediscountdetails  GROUP BY invoiceAutoID) gendiscount', '(gendiscount.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails where isTaxApplicable=1  GROUP BY invoiceAutoID) genexchargistax', '(genexchargistax.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails  GROUP BY invoiceAutoID) genexcharg', '(genexcharg.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->from('srp_erp_customerinvoicemaster');
            $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerinvoicemaster.invoiceAutoID AND srp_erp_documentapproved.approvalLevelID = srp_erp_customerinvoicemaster.currentLevelNo');
            $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_customerinvoicemaster.currentLevelNo');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'CINV');
            $this->datatables->where('srp_erp_approvalusers.documentID', 'CINV');
            $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
            $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
            $this->datatables->where('srp_erp_customerinvoicemaster.companyID', $companyID);
            $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
            $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('invoiceCode', '$1', 'approval_change_modal(invoiceCode,invoiceAutoID,documentApprovedID,approvalLevelID,approvedYN,CINV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CINV",invoiceAutoID)');
            $this->datatables->add_column('edit', '$1', 'inv_action_approval(invoiceAutoID,approvalLevelID,approvedYN,documentApprovedID,CINV)');
            echo $this->datatables->generate();
        }else
        {
            $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value,ROUND((((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)), 2) as total_value_search,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_customermaster.customerName as customerName,srp_erp_customerinvoicemaster.referenceNo as referenceNo', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->from('srp_erp_customerinvoicemaster');
            $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');

            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerinvoicemaster.invoiceAutoID');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'CINV');
            $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
            $this->datatables->where('srp_erp_customerinvoicemaster.companyID', $companyID);
            $this->datatables->where('srp_erp_documentapproved.approvedEmpID', $current_user_id);
            $this->datatables->group_by('srp_erp_customerinvoicemaster.invoiceAutoID');
            $this->datatables->group_by('srp_erp_documentapproved.approvalLevelID');
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('invoiceCode', '$1', 'approval_change_modal(invoiceCode,invoiceAutoID,documentApprovedID,approvalLevelID,approvedYN,CINV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CINV",invoiceAutoID)');
            $this->datatables->add_column('edit', '$1', 'inv_action_approval(invoiceAutoID,approvalLevelID,approvedYN,documentApprovedID,CINV)');
            echo $this->datatables->generate();
        }

    }

    function save_invoice_item_detail()
    {
        $projectExist = project_is_exist();
        $isBuyBackCompany = isBuyBack_company();
        $searches = $this->input->post('search');
        $itemAutoID = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $quantityRequested = $this->input->post('quantityRequested');
        $estimatedAmount = $this->input->post('estimatedAmount');

        foreach ($searches as $key => $search) {
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID[$key]);
            $serviceitm= $this->db->get()->row_array();
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            if($serviceitm['mainCategory']!='Service'){
                $this->form_validation->set_rules("wareHouseAutoID[{$key}]", 'Warehouse', 'trim|required');
            }
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Amount', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
            if ($isBuyBackCompany == 1) {
                $this->form_validation->set_rules("noOfItems[{$key}]", 'No Item', 'trim|required');
                $this->form_validation->set_rules("grossQty[{$key}]", 'Gross Qty', 'trim|required');
                $this->form_validation->set_rules("noOfUnits[{$key}]", 'Units', 'trim|required');
                $this->form_validation->set_rules("deduction[{$key}]", 'Deduction', 'trim|required');
            } else {
                $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Invoice_model->save_invoice_item_detail());
        }
    }

    function update_invoice_item_detail()
    {
        $projectExist = project_is_exist();
        $isBuyBackCompany = isBuyBack_company();
        $itemAutoID=$this->input->post('itemAutoID');
        $this->db->select('mainCategory');
        $this->db->from('srp_erp_itemmaster');
        $this->db->where('itemAutoID', $itemAutoID);
        $serviceitm= $this->db->get()->row_array();

        $this->form_validation->set_rules('search', 'Item', 'trim|required');
        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        if($serviceitm['mainCategory']!='Service') {
            $this->form_validation->set_rules("wareHouseAutoID", 'Warehouse', 'trim|required');
        }
        $this->form_validation->set_rules('quantityRequested', 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules('estimatedAmount', 'Estimated Amount', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($isBuyBackCompany == 1) {
            $this->form_validation->set_rules("noOfItems", 'No Item', 'trim|required');
            $this->form_validation->set_rules("grossQty", 'Gross Qty', 'trim|required');
            $this->form_validation->set_rules("noOfUnits", 'Units', 'trim|required');
            $this->form_validation->set_rules("deduction", 'Deduction', 'trim|required');
        } else {
            $this->form_validation->set_rules('UnitOfMeasureID', 'Unit Of Measure', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->update_invoice_item_detail());
        }
    }

    function invoice_confirmation()
    {
        echo json_encode($this->Invoice_model->invoice_confirmation());
    }

    // function save_inv_base_items(){
    //     echo json_encode($this->Invoice_model->save_inv_base_items());
    // }

    function save_invoice_approval()
    {
        $system_code = trim($this->input->post('invoiceAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));

        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'CINV', $level_id);
            if ($approvedYN) {
                //$this->session->set_flashdata('w', 'Document already approved');
                echo json_encode(array('w', 'Document already approved', 1));
            } else {
                $this->db->select('invoiceAutoID');
                $this->db->where('invoiceAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_customerinvoicemaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    //$this->session->set_flashdata('w', 'Document already rejected');
                    echo json_encode(array('w', 'Document already rejected', 1));
                } else {
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('invoiceAutoID', 'Payment Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(array('e', validation_errors(), 1));
                    } else {
                        echo json_encode($this->Invoice_model->save_invoice_approval());
                    }
                }
            }
        }
        else if ($status == 2) {
            $this->db->select('invoiceAutoID');
            $this->db->where('invoiceAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_customerinvoicemaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                //$this->session->set_flashdata('w', 'Document already rejected');
                echo json_encode(array('w', 'Document already rejected', 1));
            } else {
                $rejectYN = checkApproved($system_code, 'CINV', $level_id);
                if (!empty($rejectYN)) {
                    //$this->session->set_flashdata('w', 'Document already approved');
                    echo json_encode(array('w', 'Document already approved', 1));
                } else {
                    $this->form_validation->set_rules('status', 'Supplier Invoice Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('invoiceAutoID', 'Payment Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(array('e', validation_errors(), 1));
                    } else {
                        echo json_encode($this->Invoice_model->save_invoice_approval());
                    }
                }
            }
        }
    }

    function delete_customerInvoice_attachement()
    {
        echo json_encode($this->Invoice_model->delete_customerInvoice_attachement());
    }

    function delete_invoice_master()
    {
        echo json_encode($this->Invoice_model->delete_invoice_master());
    }


    function load_subItemList()
    {

        $detailID = $this->input->post('detailID');
        $documentID = $this->input->post('documentID');
        $warehouseID = $this->input->post('warehouseID');
        $data['subItems'] = $this->Invoice_model->load_subItem_notSold($detailID, $documentID, $warehouseID);


        switch ($documentID) {
            case "CINV":
                $data['detail'] = $this->Invoice_model->get_invoiceDetail($detailID);
                break;

            case "RV":
                $data['detail'] = $this->Invoice_model->get_receiptVoucherDetail($detailID);
                break;

            case "SR":
                $data['detail'] = $this->Invoice_model->get_stockReturnDetail($detailID);
                break;

            case "MI":
                $data['detail'] = $this->Invoice_model->get_materialIssueDetail($detailID);
                break;

            case "ST":
                $data['detail'] = $this->Invoice_model->get_stockTransferDetail($detailID);
                break;

            case "SA":
                $data['detail'] = $this->Invoice_model->get_stockAdjustmentDetail($detailID);
                break;

            case "DO":
                $this->load->model('Delivery_order_model');
                $data['detail'] = $this->Delivery_order_model->fetch_delivery_order_detail($detailID);
            break;

            default:
                echo $documentID . ' Code not configured <br/>';
                echo 'File: ' . __FILE__ . '<br/>';
                echo 'Line No: ' . __LINE__ . '<br><br>';
                die();

        }

        $data['attributes'] = fetch_company_assigned_attributes();
        $data['documentID'] = $documentID;
        $this->load->view('system/item/itemmastersub/load-sub-item-list', $data);
    }

    function save_subItemList()
    {
        $subItemCode = $this->input->post('subItemCode[]');
        $qty = $this->input->post('qty');

        if ($qty == count($subItemCode)) {
            $output = $this->Invoice_model->save_subItemList();
            echo json_encode($output);

        } else {
            echo json_encode(array('error' => 1, 'message' => 'Please select ' . $qty . ' item/s.'));
        }


    }

    function re_open_invoice()
    {
        echo json_encode($this->Invoice_model->re_open_invoice());
    }

    function customerinvoiceGLUpdate()
    {
        $this->form_validation->set_rules('PLGLAutoID', 'Cost GL Account', 'trim|required');
        if ($this->input->post('BLGLAutoID')) {
            $this->form_validation->set_rules('BLGLAutoID', 'Asset GL Account', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->customerinvoiceGLUpdate());
        }
    }

    function fetch_customer_invoice_all_detail_edit()
    {
        echo json_encode($this->Invoice_model->fetch_customer_invoice_all_detail_edit());
    }


    function updateCustomerInvoice_edit_all_Item()
    {
        $projectExist = project_is_exist();
        $searches = $this->input->post('search');
        $itemAutoID = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $quantityRequested = $this->input->post('quantityRequested');
        $estimatedAmount = $this->input->post('estimatedAmount');

        foreach ($searches as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID[$key]);
            $serviceitm= $this->db->get()->row_array();

            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Amount', 'trim|required');
            if($serviceitm['mainCategory']!='Service') {
                $this->form_validation->set_rules("wareHouseAutoID[{$key}]", 'Warehouse', 'trim|required');
            }
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Invoice_model->updateCustomerInvoice_edit_all_Item());
        }
    }

    function invoiceloademail()
    {

        echo json_encode($this->Invoice_model->invoiceloademail());

    }

    function send_invoice_email()
    {
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->send_invoice_email());
        }
    }

    function load_default_note(){
        echo json_encode($this->Invoice_model->load_default_note());
    }

    function open_all_notes(){
        echo json_encode($this->Invoice_model->open_all_notes());
    }

    function load_notes(){
        echo json_encode($this->Invoice_model->load_notes());
    }
    function saveinsurancetype(){

        $this->form_validation->set_rules('insurancetype', 'Insurance Type', 'trim|required');
        $this->form_validation->set_rules('gl_code', 'GL Code', 'trim|required');
        //$this->form_validation->set_rules('marginPercentage', 'Margin Percentage', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode( array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->saveinsurancetype());
        }

    }
    function fetchinsurancetype()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $this->datatables->select('srp_erp_invoiceinsurancetypes.insuranceTypeID as insuranceTypeID,srp_erp_invoiceinsurancetypes.insuranceType,CONCAT(srp_erp_chartofaccounts.GLDescription, " - ", srp_erp_chartofaccounts.GLSecondaryCode) as GLDescription,srp_erp_invoiceinsurancetypes.marginPercentage,srp_erp_invoiceinsurancetypes.masterTypeID,srp_erp_invoiceinsurancetypes.noofMonths,mastertyp.insuranceType as mastertype', false)
            ->from('srp_erp_invoiceinsurancetypes')
        ->join('srp_erp_chartofaccounts', 'srp_erp_chartofaccounts.GLAutoID = srp_erp_invoiceinsurancetypes.GLAutoID', 'left')
        ->join('srp_erp_invoiceinsurancetypes mastertyp', 'mastertyp.insuranceTypeID = srp_erp_invoiceinsurancetypes.masterTypeID', 'left')
        ->where('srp_erp_invoiceinsurancetypes.companyID', $companyID);
        //$this->datatables->add_column('edit', '<span class="pull-right"><a href="#" onclick="sub_insurance_type($1)"><span title="Sub Type" rel="tooltip" class="glyphicon glyphicon-menu-hamburger" ></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;<span class="pull-right"><a href="#" onclick="openinsuranceeditmodel($1)"><span title="Edit" rel="tooltip" class="fa fa-pencil"></span></a> |&nbsp;&nbsp;<span class="pull-right"><a href="#" onclick="delete_insurancetype($1)"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span>&nbsp;&nbsp;</a> ', 'insuranceTypeID');
        $this->datatables->add_column('edit', '$1', 'load_insurancetype_action(insuranceTypeID,masterTypeID)');
        echo $this->datatables->generate();
    }
    function getinsurancetype(){
        echo json_encode($this->Invoice_model->getinsurancetype());
    }
    function deleteinsurancetype(){
        echo json_encode($this->Invoice_model->deleteinsurancetype());
    }

    function save_invoice_header_insurance()
    {
        $date_format_policy = date_format_policy();
        $invDueDate = $this->input->post('invoiceDueDate');
        $invoiceDueDate = input_format_date($invDueDate, $date_format_policy);
        $invDate = $this->input->post('customerInvoiceDate');
        $invoiceDate = input_format_date($invDate, $date_format_policy);
        $docDate = $this->input->post('invoiceDate');
        $documentDate = input_format_date($docDate, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $this->form_validation->set_rules('invoiceType', 'Invoice Type', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('invoiceDate', 'Invoice Date', 'trim|required');
        $this->form_validation->set_rules('invoiceDueDate', 'Invoice Due Date', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Transaction Currency', 'trim|required');
        $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('contactPersonNumber', 'Telephone Number', 'trim|required');

        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
            $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        }
        if ($this->input->post('invoiceType') == 'Insurance') {
            $this->form_validation->set_rules('insurancetypeid', 'Insurance Type', 'trim|required');
            $this->form_validation->set_rules('insuranceSubTypeID', 'Sub Type', 'trim|required');
            $this->form_validation->set_rules('policyStartDate', 'Policy Start Date', 'trim|required');
            $this->form_validation->set_rules('policyEndDate', 'Policy End Date ', 'trim|required');
        }


        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            if (($invoiceDate) > ($invoiceDueDate)) {
                $this->session->set_flashdata('e', ' Invoice Due Date cannot be less than Invoice Date!');
                echo json_encode(FALSE);
            } else {
                if($financeyearperiodYN==1) {
                    $financearray = $this->input->post('financeyear_period');
                    $financePeriod = fetchFinancePeriod($financearray);
                    if ($documentDate >= $financePeriod['dateFrom'] && $documentDate <= $financePeriod['dateTo']) {
                        echo json_encode($this->Invoice_model->save_invoice_header_insurance());
                    } else {
                        $this->session->set_flashdata('e', 'Document Date not between Financial period !');
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode($this->Invoice_model->save_invoice_header_insurance());
                }
            }
        }
    }
    function fetch_invoices_insurance()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( invoiceDate >= '" . $datefromconvert . " 00:00:00' AND invoiceDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            }else if ($status == 4) {
                $status_filter = " AND ((confirmedYN = 2 AND approvedYN != 1) or (confirmedYN = 3 AND approvedYN != 1) )";
            }
            else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $sSearch=$this->input->post('sSearch');
        $searches='';
        if($sSearch){
            $search = str_replace("\\", "\\\\", $sSearch);
            //$this->datatables->or_like('contractCode',"$search");
            $searches = " AND (( invoiceCode Like '%$search%' ESCAPE '!') OR ( invoiceType Like '%$sSearch%' ESCAPE '!') OR ( det.transactionAmount Like '%$sSearch%')  OR (invoiceNarration Like '%$sSearch%') OR (srp_erp_customermaster.customerName Like '%$sSearch%') OR (invoiceDate Like '%$sSearch%') OR (invoiceDueDate Like '%$sSearch%') OR (referenceNo Like '%$sSearch%')) ";
        }

        $where = "srp_erp_customerinvoicemaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . $searches."";
        $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_customerinvoicemaster.confirmedByEmpID as confirmedByEmp,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID as createdUser,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) as total_value,ROUND((IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0), 2) as total_value_search,isDeleted,tempInvoiceID,referenceNo,srp_erp_customerinvoicemaster.isSytemGenerated as isSytemGenerated');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(discountPercentage) as discountPercentage ,invoiceAutoID FROM srp_erp_customerinvoicediscountdetails  GROUP BY invoiceAutoID) gendiscount', '(gendiscount.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails where isTaxApplicable=1  GROUP BY invoiceAutoID) genexchargistax', '(genexchargistax.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails  GROUP BY invoiceAutoID) genexcharg', '(genexcharg.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
        $this->datatables->from('srp_erp_customerinvoicemaster');
        $this->datatables->add_column('invoice_detail', '<b>Customer Name : </b> $2 <br> <b>Document Date : </b> $3 <b style="text-indent: 1%;">&nbsp | &nbsp Due Date : </b> $4 <br> <b>Type : </b> $5 <br> <b>Ref No : </b> $6 <br> <b>Comments : </b> $1 ', 'trim_desc(invoiceNarration),customermastername,invoiceDate,invoiceDueDate,invoiceType,referenceNo');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_invoice_action_insurancetype(invoiceAutoID,confirmedYN,approvedYN,createdUser,confirmedYN,isDeleted,tempInvoiceID,confirmedByEmp,isSytemGenerated)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }
    function load_invoices_conformation_invoicetype()
    {
        $invoiceAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('invoiceAutoID'));
        $this->db->select('tempInvoiceID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();
        $this->load->library('NumberToWords');
        if(!empty($master['tempInvoiceID'])){
            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data_temp_insurance($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }

            $html = $this->load->view('system/invoices/erp_invoice_print_temp_insurancetype', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {
                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
            }
        }else{
            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data_temp_insurance($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $printHeaderFooterYN=1;
            $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            $this->db->select('printHeaderFooterYN');
            $this->db->where('companyID', current_companyID());
            $this->db->where('documentID', 'CINV');
            $this->db->from('srp_erp_documentcodemaster');
            $result = $this->db->get()->row_array();
            if(!empty($result)){
                $printHeaderFooterYN =$result['printHeaderFooterYN'];
                $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }
            $printlink = print_template_pdf('CINV','system/invoices/erp_invoice_print_insurance');
            $papersize = print_template_paper_size('CINV','A4');
            $pdfp = $this->load->view('system/invoices/erp_invoice_print_insurance', $data, true);
            if ($this->input->post('html')) {
                $html = $this->load->view('system/invoices/erp_invoice_print_html_insurance', $data, true);
                echo $html;
            } else {
                //$html = $this->load->view('system/invoices/erp_invoice_print', $data, true);
                $this->load->library('pdf');
                //$pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN'],null,$printHeaderFooterYN);
                $pdf = $this->pdf->printed($pdfp, $papersize,$data['extra']['master']['approvedYN'],null,$printHeaderFooterYN);
            }
        }

    }
    function fetch_invoices_approval_insurance()
    {
        /*
        * rejected = 1
        * not rejected = 0
        * */

        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $curentuserid = current_userID();
        if($approvedYN == 0)
        {
            $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) as total_value,ROUND((IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0), 2) as total_value_search,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_customermaster.customerName as customerName,srp_erp_customerinvoicemaster.referenceNo as referenceNo', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(discountPercentage) as discountPercentage ,invoiceAutoID FROM srp_erp_customerinvoicediscountdetails  GROUP BY invoiceAutoID) gendiscount', '(gendiscount.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails where isTaxApplicable=1  GROUP BY invoiceAutoID) genexchargistax', '(genexchargistax.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails  GROUP BY invoiceAutoID) genexcharg', '(genexcharg.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->from('srp_erp_customerinvoicemaster');
            $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerinvoicemaster.invoiceAutoID AND srp_erp_documentapproved.approvalLevelID = srp_erp_customerinvoicemaster.currentLevelNo');
            $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_customerinvoicemaster.currentLevelNo');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'CINV');
            $this->datatables->where('srp_erp_approvalusers.documentID', 'CINV');
            $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
            $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
            $this->datatables->where('srp_erp_customerinvoicemaster.companyID', $companyID);
            $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
            $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('invoiceCode', '$1', 'approval_change_modal(invoiceCode,invoiceAutoID,documentApprovedID,approvalLevelID,approvedYN,CINV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CINV",invoiceAutoID)');
            $this->datatables->add_column('edit', '$1', 'inv_action_approval_insurance(invoiceAutoID,approvalLevelID,approvedYN,documentApprovedID,CINV)');
            echo $this->datatables->generate();
        }else
        {
            $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value,ROUND((((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)), 2) as total_value_search,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_customermaster.customerName as customerName,srp_erp_customerinvoicemaster.referenceNo as referenceNo', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->from('srp_erp_customerinvoicemaster');
            $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerinvoicemaster.invoiceAutoID');

            $this->datatables->where('srp_erp_documentapproved.documentID', 'CINV');
            $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
            $this->datatables->where('srp_erp_customerinvoicemaster.companyID', $companyID);
            $this->datatables->where('srp_erp_documentapproved.approvedEmpID', $curentuserid);
            $this->datatables->group_by('srp_erp_customerinvoicemaster.invoiceAutoID');
            $this->datatables->group_by('srp_erp_documentapproved.approvalLevelID');

            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('invoiceCode', '$1', 'approval_change_modal(invoiceCode,invoiceAutoID,documentApprovedID,approvalLevelID,approvedYN,CINV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CINV",invoiceAutoID)');
            $this->datatables->add_column('edit', '$1', 'inv_action_approval_insurance(invoiceAutoID,approvalLevelID,approvedYN,documentApprovedID,CINV)');
            echo $this->datatables->generate();
        }

    }


    function fetch_invoices_margirn()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( invoiceDate >= '" . $datefromconvert . " 00:00:00' AND invoiceDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            }else if ($status == 4) {
                $status_filter = " AND ((confirmedYN = 2 AND approvedYN != 0) or (confirmedYN = 3 AND approvedYN != 0) )";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $sSearch=$this->input->post('sSearch');
        $searches='';
        if($sSearch){
            $search = str_replace("\\", "\\\\", $sSearch);
            //$this->datatables->or_like('contractCode',"$search");
            $searches = " AND (( invoiceCode Like '%$search%' ESCAPE '!') OR ( invoiceType Like '%$sSearch%' ESCAPE '!') OR ( det.transactionAmount Like '%$sSearch%')  OR (invoiceNarration Like '%$sSearch%') OR (srp_erp_customermaster.customerName Like '%$sSearch%') OR (invoiceDate Like '%$sSearch%') OR (invoiceDueDate Like '%$sSearch%') OR (referenceNo Like '%$sSearch%')) ";
        }

        $where = "srp_erp_customerinvoicemaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . $searches."";
        $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_customerinvoicemaster.confirmedByEmpID as confirmedByEmp,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID as createdUser,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0) -IFNULL(retensionTransactionAmount,0)) as total_value,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0) -IFNULL(retensionTransactionAmount,0)) as total_value_search,isDeleted,tempInvoiceID,referenceNo,srp_erp_customerinvoicemaster.isSytemGenerated as isSytemGenerated');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
        $this->datatables->from('srp_erp_customerinvoicemaster');
        $this->datatables->add_column('invoice_detail', '<b>Customer Name : </b> $2 <br> <b>Document Date : </b> $3 <b style="text-indent: 1%;">&nbsp | &nbsp Due Date : </b> $4 <br> <b>Type : </b> $5 <br> <b>Ref No : </b> $6 <br> <b>Comments : </b> $1 ', 'trim_desc(invoiceNarration),customermastername,invoiceDate,invoiceDueDate,invoiceType,referenceNo');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_invoice_action_margin(invoiceAutoID,confirmedYN,approvedYN,createdUser,confirmedYN,isDeleted,tempInvoiceID,confirmedByEmp,isSytemGenerated)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function fetch_detail_margin()
    {
        $data['master'] = $this->Invoice_model->load_invoice_header();
        $data['invoiceAutoID'] = trim($this->input->post('invoiceAutoID'));
        $data['invoiceType'] = $data['master']['invoiceType'];
        $data['customerID'] = $data['master']['customerID'];
        $data['gl_code_arr'] = fetch_all_gl_codes();
        $data['gl_code_arr_income'] = fetch_all_gl_codes('PLI');
        $data['segment_arr'] = fetch_segment();
        $data['detail'] = $this->Invoice_model->fetch_detail();
        $data['customer_con'] = $this->Invoice_model->fetch_customer_con($data['master']);
        $data['tabID'] = $this->input->post('tab');
        $this->load->view('system/invoices/fetch_detail_margin.php', $data);
    }

    function save_direct_invoice_detail_margin()
    {
        $projectExist = project_is_exist();
        $gl_codes = $this->input->post('gl_code');
        $amount = $this->input->post('amount');
        $segment_gl = $this->input->post('segment_gl');

        foreach ($gl_codes as $key => $gl_code) {
            $this->form_validation->set_rules("gl_code[{$key}]", 'GL Code', 'trim|required');
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("marginPercentage[{$key}]", 'Percentage', 'trim|required');
            $this->form_validation->set_rules("marginAmount[{$key}]", 'Margin Amount', 'trim|required');
            $this->form_validation->set_rules("transactionAmount[{$key}]", 'Total Amount', 'trim|required');
            $this->form_validation->set_rules("segment_gl[{$key}]", 'Segment', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'description', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));

            $this->session->set_flashdata($msgtype = 'e', join('', $validateMsg));
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Invoice_model->save_direct_invoice_detail_margin());
        }
    }

    function update_income_invoice_detail_margin()
    {
        $projectExist = project_is_exist();
        $this->form_validation->set_rules('gl_code', 'GL Code', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules("marginPercentage", 'Percentage', 'trim|required');
        $this->form_validation->set_rules("marginAmount", 'Margin Amount', 'trim|required');
        $this->form_validation->set_rules('segment_gl', 'Segment', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->update_income_invoice_detail_margin());
        }
    }


    function load_invoices_conformation_margin()
    {
        $invoiceAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('invoiceAutoID'));
        $this->db->select('tempInvoiceID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();
        $this->load->library('NumberToWords');
        if(!empty($master['tempInvoiceID'])){
            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data_temp($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }

            $html = $this->load->view('system/invoices/erp_invoice_print_temp_margin', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {
                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
            }
        }else{
            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $printHeaderFooterYN=1;
            $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            $this->db->select('printHeaderFooterYN');
            $this->db->where('companyID', current_companyID());
            $this->db->where('documentID', 'CINV');
            $this->db->from('srp_erp_documentcodemaster');
            $result = $this->db->get()->row_array();
            if(!empty($result)){
                $printHeaderFooterYN =$result['printHeaderFooterYN'];
                $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }

            $html = $this->load->view('system/invoices/erp_invoice_print_margin', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {
                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
            }
        }
    }


    function save_insurance_invoice_detail_margin()
    {
        $projectExist = project_is_exist();
        $gl_codes = $this->input->post('gl_code');
        $amount = $this->input->post('amount');
        $segment_gl = $this->input->post('segment_gl');

        foreach ($segment_gl as $key => $seg_code) {
            $this->form_validation->set_rules("supplierAutoID[{$key}]", 'Supplier', 'trim|required');
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("marginPercentage[{$key}]", 'Percentage', 'trim|required');
            $this->form_validation->set_rules("marginAmount[{$key}]", 'Margin Amount', 'trim|required');
            $this->form_validation->set_rules("totalAmount[{$key}]", 'Total Amount', 'trim|required');
            $this->form_validation->set_rules("segment_gl[{$key}]", 'Segment', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'description', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));

            $this->session->set_flashdata($msgtype = 'e', join('', $validateMsg));
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Invoice_model->save_insurance_invoice_detail_margin());
        }
    }

    function update_income_invoice_detail_insurance()
    {
        $projectExist = project_is_exist();
        $this->form_validation->set_rules('supplierAutoID', 'Supplier', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules("marginPercentage", 'Percentage', 'trim|required');
        $this->form_validation->set_rules("marginAmount", 'Margin Amount', 'trim|required');
        $this->form_validation->set_rules('segment_gl', 'Segment', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->update_income_invoice_detail_insurance());
        }
    }

    function delivery_order_invoice(){
        $this->form_validation->set_rules('invoiceAutoID', 'Invoice ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            die( json_encode(['e', validation_errors()]) );
        }

        $invoice_id = $this->input->post('invoiceAutoID');
        $amount_arr = $this->input->post('amounts');
        $orders_arr = $this->input->post('deliveryOrders');
        $dateTime = current_date();

        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,
                           companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID,customerID');
        $master = $this->db->where('invoiceAutoID', $invoice_id)->get('srp_erp_customerinvoicemaster')->row_array();
        $customerID = $master['customerID'];
        $d_place = $master['transactionCurrencyDecimalPlaces'];

        $order_data = $this->Invoice_model->delivery_detail($customerID);
        $order_data = (!empty($order_data))? array_group_by($order_data, 'DOAutoID'): $order_data;

        /*Un billed Invoice GL details*/
        $companyID = current_companyID();
        $un_billed_gl = $this->db->query("SELECT GLAutoID, systemAccountCode, GLSecondaryCode, GLDescription, subCategory FROM srp_erp_chartofaccounts
                                          WHERE GLAutoID = (
                                              SELECT GLAutoID FROM srp_erp_companycontrolaccounts WHERE controlAccountType = 'UBI' AND companyID = {$companyID}
                                          ) AND companyID={$companyID} ")->row_array();

        $data = [];
        foreach ($amount_arr as $key=>$amount){
            $delivery_id = $orders_arr[$key];

            if($amount == 1){
                die( json_encode(['e', 'You can not pay `0` amount.']) );
            }

            if(!array_key_exists($delivery_id, $order_data)){
                die( json_encode(['e', 'Order details not found for <b>line no : '.($key+1).'</b>']) );
            }

            $amount = round($amount, $d_place);
            $this_order_data = $order_data[$delivery_id][0];
            $total_order_amount = round($this_order_data['transactionAmount'], $d_place);
            $invoiced_amount = round($this_order_data['invoiced_amount'], $d_place);
            $balance = $total_order_amount - $invoiced_amount;

            if($balance < $amount){
                $balance = number_format($balance, $d_place);
                die( json_encode(['e', "Maximum receivable amount <b>{$balance} for line no : ".($key+1)."</b>"]) );
            }

            $data[$key]['DOMasterID'] = $delivery_id;
            $data[$key]['invoiceAutoID'] = $invoice_id;
            $data[$key]['revenueGLAutoID'] = $un_billed_gl['GLAutoID'];
            $data[$key]['revenueSystemGLCode'] = $un_billed_gl['systemAccountCode'];
            $data[$key]['revenueGLCode'] = $un_billed_gl['GLSecondaryCode'];
            $data[$key]['revenueGLDescription'] = $un_billed_gl['GLDescription'];
            $data[$key]['revenueGLType'] = $un_billed_gl['subCategory'];

            $data[$key]['transactionAmount'] = round($amount, $d_place);
            $data[$key]['due_amount'] = round($balance, $d_place);
            $data[$key]['balance_amount'] = round(($balance-$amount), $d_place);
            $companyLocalAmount = $amount / $master['companyLocalExchangeRate'];
            $data[$key]['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $companyReportingAmount = $amount / $master['companyReportingExchangeRate'];
            $data[$key]['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $customerAmount = $amount / $master['customerCurrencyExchangeRate'];
            $data[$key]['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);

            $data[$key]['type'] = 'DO';

            $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
            $data[$key]['companyID'] = $companyID;
            $data[$key]['createdUserGroup'] = $this->common_data['user_group'];
            $data[$key]['createdPCID'] = $this->common_data['current_pc'];
            $data[$key]['createdUserID'] = $this->common_data['current_userID'];
            $data[$key]['createdUserName'] = $this->common_data['current_user'];
            $data[$key]['createdDateTime'] = $dateTime;
        }

        if(empty($data)){
            die( json_encode(['e', 'There is no data to process']) );
        }

        $this->db->trans_start();

        $this->db->insert_batch('srp_erp_customerinvoicedetails', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            die( json_encode(['e', 'Invoice details save failed ' . $this->db->_error_message()]) );
        } else {
            $this->db->trans_commit();
            die( json_encode(['s', 'Invoice details saved successfully.']) );
        }
    }

    function save_inv_discount_detail()
    {
        $this->form_validation->set_rules('discountExtraChargeID', 'Discount Type', 'trim|required');
        $this->form_validation->set_rules('discountPercentage', 'Discount Percentage', 'trim|required');
        $this->form_validation->set_rules('InvoiceAutoID', 'InvoiceAutoID', 'trim|required');
        $this->form_validation->set_rules('discount_amount', 'Discount Amounts', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Invoice_model->save_inv_discount_detail());
        }
    }

    function save_inv_extra_detail()
    {
        $this->form_validation->set_rules('discountExtraChargeIDExtra', 'Extra Type', 'trim|required');
        $this->form_validation->set_rules('InvoiceAutoID', 'InvoiceAutoID', 'trim|required');
        //$this->form_validation->set_rules('amounts[]', 'Amounts', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Invoice_model->save_inv_extra_detail());
        }
    }

    function delete_discount_gen()
    {
        echo json_encode($this->Invoice_model->delete_discount_gen());
    }

    function delete_extra_gen()
    {
        echo json_encode($this->Invoice_model->delete_extra_gen());
    }
    function fetch_customer_details_by_id()
    {
        echo json_encode($this->Invoice_model->fetch_customer_details_by_id());
    }
    function fetch_customer_details_currency()
    {
        echo json_encode($this->Invoice_model->fetch_customer_details_currency());
    }

    function fetch_invoices_suom()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $customer = $this->input->post('customerCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $customer_filter = '';
        if (!empty($customer)) {
            $customer = array($this->input->post('customerCode'));
            $whereIN = "( " . join("' , '", $customer) . " )";
            $customer_filter = " AND customerID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( invoiceDate >= '" . $datefromconvert . " 00:00:00' AND invoiceDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            } else if ($status == 4) {
                $status_filter = " AND ((confirmedYN = 2 AND approvedYN != 1) or (confirmedYN = 3 AND approvedYN != 1))";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $sSearch=$this->input->post('sSearch');
        $searches='';
        if($sSearch){
            $search = str_replace("\\", "\\\\", $sSearch);
            //$this->datatables->or_like('contractCode',"$search");
            $searches = " AND (( invoiceCode Like '%$search%' ESCAPE '!') OR ( invoiceType Like '%$sSearch%' ESCAPE '!') OR ( det.transactionAmount Like '%$sSearch%')  OR (invoiceNarration Like '%$sSearch%') OR (srp_erp_customermaster.customerName Like '%$sSearch%') OR (invoiceDate Like '%$sSearch%') OR (invoiceDueDate Like '%$sSearch%') OR (referenceNo Like '%$sSearch%')) ";
        }

        $where = "srp_erp_customerinvoicemaster.companyID = " . $companyid . $customer_filter . $date . $status_filter . $searches."";
        //$this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_customerinvoicemaster.confirmedByEmpID as confirmedByEmp,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID as createdUser,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,((((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)) as total_value,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value_search,isDeleted,tempInvoiceID,referenceNo');
        $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_customerinvoicemaster.confirmedByEmpID as confirmedByEmp,invoiceCode,invoiceNarration,srp_erp_customermaster.customerName as customermastername,transactionCurrencyDecimalPlaces,transactionCurrency, confirmedYN,approvedYN,srp_erp_customerinvoicemaster.createdUserID as createdUser,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,invoiceType,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) as total_value,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) as total_value_search,isDeleted,tempInvoiceID,referenceNo');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(discountPercentage) as discountPercentage ,invoiceAutoID FROM srp_erp_customerinvoicediscountdetails  GROUP BY invoiceAutoID) gendiscount', '(gendiscount.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails where isTaxApplicable=1  GROUP BY invoiceAutoID) genexchargistax', '(genexchargistax.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails  GROUP BY invoiceAutoID) genexcharg', '(genexcharg.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
        $this->datatables->from('srp_erp_customerinvoicemaster');
        $this->datatables->add_column('invoice_detail', '<b>Customer Name : </b> $2 <br> <b>Document Date : </b> $3 <b style="text-indent: 1%;">&nbsp | &nbsp Due Date : </b> $4 <br> <b>Type : </b> $5 <br><b>Ref No : </b> $6 <br> <b>Comments : </b> $1 ', 'trim_desc(invoiceNarration),customermastername,invoiceDate,invoiceDueDate,invoiceType,referenceNo');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"CINV",invoiceAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_invoice_action_suom(invoiceAutoID,confirmedYN,approvedYN,createdUser,confirmedYN,isDeleted,tempInvoiceID,confirmedByEmp)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function fetch_detail_suom()
    {
        $data['master'] = $this->Invoice_model->load_invoice_header();
        $data['invoiceAutoID'] = trim($this->input->post('invoiceAutoID'));
        $data['invoiceType'] = $data['master']['invoiceType'];
        $data['marginpercent'] = 0;
        if(!empty($data['master']['insuranceTypeID'])){
            $this->db->select('marginPercentage');
            $this->db->where('insuranceTypeID', trim($data['master']['insuranceTypeID']));
            $this->db->from('srp_erp_invoiceinsurancetypes');
            $margindetails = $this->db->get()->row_array();
            $data['marginpercent'] = $margindetails['marginPercentage'];
        }
        $data['customerID'] = $data['master']['customerID'];
        $data['gl_code_arr'] = fetch_all_gl_codes();
        $data['supplier_arr'] = all_supplier_drop();
        $data['gl_code_arr_income'] = fetch_all_gl_codes('PLI');
        $data['segment_arr'] = fetch_segment();
        $data['detail'] = $this->Invoice_model->fetch_detail();
        $data['customer_con'] = $this->Invoice_model->fetch_customer_con($data['master']);
        $data['tabID'] = $this->input->post('tab');
        $this->load->view('system/invoices/invoices_detail_suom.php', $data);
    }

    function save_invoice_item_detail_suom()
    {
        $projectExist = project_is_exist();
        $isBuyBackCompany = isBuyBack_company();
        $searches = $this->input->post('search');
        $itemAutoID = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $quantityRequested = $this->input->post('quantityRequested');
        $estimatedAmount = $this->input->post('estimatedAmount');

        foreach ($searches as $key => $search) {
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID[$key]);
            $serviceitm= $this->db->get()->row_array();
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            if($serviceitm['mainCategory']!='Service'){
                $this->form_validation->set_rules("wareHouseAutoID[{$key}]", 'Warehouse', 'trim|required');
            }
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Amount', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
            if ($isBuyBackCompany == 1) {
                $this->form_validation->set_rules("noOfItems[{$key}]", 'No Item', 'trim|required');
                $this->form_validation->set_rules("grossQty[{$key}]", 'Gross Qty', 'trim|required');
                $this->form_validation->set_rules("noOfUnits[{$key}]", 'Units', 'trim|required');
                $this->form_validation->set_rules("deduction[{$key}]", 'Deduction', 'trim|required');
            } else {
                $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            }
            //$this->form_validation->set_rules("SUOMIDhn[{$key}]", 'Secondary UOM', 'trim|required');
            if(!empty($this->input->post("SUOMIDhn[$key]"))){
                $this->form_validation->set_rules("SUOMQty[{$key}]", 'Secondary QTY', 'trim|required|greater_than[0]');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Invoice_model->save_invoice_item_detail());
        }
    }


    function update_invoice_item_detail_suom()
    {
        $projectExist = project_is_exist();
        $isBuyBackCompany = isBuyBack_company();
        $itemAutoID=$this->input->post('itemAutoID');
        $this->db->select('mainCategory');
        $this->db->from('srp_erp_itemmaster');
        $this->db->where('itemAutoID', $itemAutoID);
        $serviceitm= $this->db->get()->row_array();

        $this->form_validation->set_rules('search', 'Item', 'trim|required');
        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        if($serviceitm['mainCategory']!='Service') {
            $this->form_validation->set_rules("wareHouseAutoID", 'Warehouse', 'trim|required');
        }
        $this->form_validation->set_rules('quantityRequested', 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules('estimatedAmount', 'Estimated Amount', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($isBuyBackCompany == 1) {
            $this->form_validation->set_rules("noOfItems", 'No Item', 'trim|required');
            $this->form_validation->set_rules("grossQty", 'Gross Qty', 'trim|required');
            $this->form_validation->set_rules("noOfUnits", 'Units', 'trim|required');
            $this->form_validation->set_rules("deduction", 'Deduction', 'trim|required');
        } else {
            $this->form_validation->set_rules('UnitOfMeasureID', 'Unit Of Measure', 'trim|required');
        }
        $this->form_validation->set_rules("SUOMQty", 'Secondary QTY', 'trim|required');
        $this->form_validation->set_rules("SUOMIDhn", 'Secondary UOM', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->update_invoice_item_detail());
        }
    }


    function load_invoices_conformation_suom()
    {

        $invoiceAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('invoiceAutoID'));
        $this->db->select('tempInvoiceID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();
        $this->load->library('NumberToWords');
        if(!empty($master['tempInvoiceID'])){
            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data_temp($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }

            $html = $this->load->view('system/invoices/erp_invoice_print_temp', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {
                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
            }
        }else{

            $data['html'] = $this->input->post('html');
            $data['approval'] = $this->input->post('approval');

            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data($invoiceAutoID);
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $printHeaderFooterYN=1;
            $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            $this->db->select('printHeaderFooterYN');
            $this->db->where('companyID', current_companyID());
            $this->db->where('documentID', 'CINV');
            $this->db->from('srp_erp_documentcodemaster');
            $result = $this->db->get()->row_array();
            if(!empty($result)){
                $printHeaderFooterYN =$result['printHeaderFooterYN'];
                $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            }
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }
            $printlink = print_template_pdf('CINV','system/invoices/erp_invoice_print_suom');


            $papersize = print_template_paper_size('CINV','A4');
            $pdfp = $this->load->view('system/invoices/erp_invoice_print_suom', $data, true);
            if ($this->input->post('html')) {
                $html = $this->load->view('system/invoices/erp_invoice_print_suom', $data, true);
                echo $html;
            } else {
                //$html = $this->load->view('system/invoices/erp_invoice_print', $data, true);
                $this->load->library('pdf');
                //$pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN'],null,$printHeaderFooterYN);
                $pdf = $this->pdf->printed($pdfp, $papersize,$data['extra']['master']['approvedYN'],null,$printHeaderFooterYN);
            }
        }

    }


    function fetch_invoices_approval_suom()
    {
        /*
        * rejected = 1
        * not rejected = 0
        * */

        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $current_user_id = current_userID();
        if($approvedYN == 0)
        {
            $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,(IFNULL(addondet.taxPercentage,0)/100)*(IFNULL(det.transactionAmount,0)-IFNULL(det.detailtaxamount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexchargistax.transactionAmount,0))+IFNULL(det.transactionAmount,0)-((IFNULL(gendiscount.discountPercentage,0)/100)*IFNULL(det.transactionAmount,0))+IFNULL(genexcharg.transactionAmount,0) as total_value,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_customermaster.customerName as customerName,srp_erp_customerinvoicemaster.referenceNo as referenceNo', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(discountPercentage) as discountPercentage ,invoiceAutoID FROM srp_erp_customerinvoicediscountdetails  GROUP BY invoiceAutoID) gendiscount', '(gendiscount.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails where isTaxApplicable=1  GROUP BY invoiceAutoID) genexchargistax', '(genexchargistax.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount ,invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails  GROUP BY invoiceAutoID) genexcharg', '(genexcharg.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->from('srp_erp_customerinvoicemaster');
            $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerinvoicemaster.invoiceAutoID AND srp_erp_documentapproved.approvalLevelID = srp_erp_customerinvoicemaster.currentLevelNo');
            $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_customerinvoicemaster.currentLevelNo');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'CINV');
            $this->datatables->where('srp_erp_approvalusers.documentID', 'CINV');
            $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
            $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
            $this->datatables->where('srp_erp_customerinvoicemaster.companyID', $companyID);
            $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
            $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('invoiceCode', '$1', 'approval_change_modal(invoiceCode,invoiceAutoID,documentApprovedID,approvalLevelID,approvedYN,CINV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CINV",invoiceAutoID)');
            $this->datatables->add_column('edit', '$1', 'inv_action_approval_suom(invoiceAutoID,approvalLevelID,approvedYN,documentApprovedID,CINV)');
            echo $this->datatables->generate();
        }else
        {
            $this->datatables->select('srp_erp_customerinvoicemaster.invoiceAutoID as invoiceAutoID,invoiceCode,invoiceNarration,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,(((IFNULL(addondet.taxPercentage,0)/100)*((IFNULL(det.transactionAmount,0)-(IFNULL(det.detailtaxamount,0)))))+IFNULL(det.transactionAmount,0)) as total_value,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_customermaster.customerName as customerName,srp_erp_customerinvoicemaster.referenceNo as referenceNo', false);
            $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,sum(totalafterTax) as detailtaxamount,invoiceAutoID FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID)', 'left');
            $this->datatables->join('(SELECT SUM(taxPercentage) as taxPercentage ,InvoiceAutoID FROM srp_erp_customerinvoicetaxdetails  GROUP BY InvoiceAutoID) addondet', '(addondet.InvoiceAutoID = srp_erp_customerinvoicemaster.InvoiceAutoID)', 'left');
            $this->datatables->from('srp_erp_customerinvoicemaster');
            $this->datatables->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');

            $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_customerinvoicemaster.invoiceAutoID');
            $this->datatables->where('srp_erp_documentapproved.documentID', 'CINV');
            $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
            $this->datatables->where('srp_erp_customerinvoicemaster.companyID', $companyID);
            $this->datatables->where('srp_erp_documentapproved.approvedEmpID', $current_user_id);
            $this->datatables->group_by('srp_erp_customerinvoicemaster.invoiceAutoID');
            $this->datatables->group_by('srp_erp_documentapproved.approvalLevelID');
            $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
            $this->datatables->add_column('invoiceCode', '$1', 'approval_change_modal(invoiceCode,invoiceAutoID,documentApprovedID,approvalLevelID,approvedYN,CINV,0)');
            $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
            $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"CINV",invoiceAutoID)');
            $this->datatables->add_column('edit', '$1', 'inv_action_approval_suom(invoiceAutoID,approvalLevelID,approvedYN,documentApprovedID,CINV)');
            echo $this->datatables->generate();
        }

    }

    function savesubinsurancetype(){

        $this->form_validation->set_rules('insuranceType', 'Insurance Type', 'trim|required');
        $this->form_validation->set_rules('noofMonths', 'No Of Months', 'trim|required');
        $this->form_validation->set_rules('marginPercentage', 'Margin Percentage', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode( array('e', validation_errors()));
        } else {
            echo json_encode($this->Invoice_model->savesubinsurancetype());
        }
    }

    function load_sub_type()
    {
        echo json_encode($this->Invoice_model->load_sub_type());
    }

    function get_sub_type_months()
    {
        echo json_encode($this->Invoice_model->get_sub_type_months());
    }

    function fetch_customer_Dropdown_all()
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

    function fetch_customer_Dropdown_all_contract()
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
        echo form_dropdown('customerID', $data_arr, $customer, 'class="form-control select2" id="customerID" onchange="Load_customer_currency(this.value);Load_customer_details(this.value);"');
    }

    function fetch_customer_details()
    {
        echo json_encode($this->Invoice_model->fetch_customer_details());
    }

    function fetch_customer_Dropdown_all_sales_return()
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
        echo form_dropdown('customerID', $data_arr, $customer, 'class="form-control select2" id="customerID" onchange="fetch_supplier_currency_by_id(this.value);"');
    }
    function fetch_customer_Dropdown_all_insurance()
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

    function load_mail_history(){
        $this->datatables->select('autoID,srp_erp_documentemailhistory.documentID,documentAutoID,sentByEmpID,toEmailAddress,sentDateTime,srp_employeesdetails.Ename2 as ename,srp_erp_customerinvoicemaster.invoiceCode')
            ->where('srp_erp_documentemailhistory.companyID', $this->common_data['company_data']['company_id'])
            ->where('srp_erp_documentemailhistory.documentID', 'CINV')
            ->where('srp_erp_documentemailhistory.documentAutoID', $this->input->post('invoiceAutoID'))
            ->join('srp_employeesdetails','srp_erp_documentemailhistory.sentByEmpID = srp_employeesdetails.EIdNo','left')
            ->join('srp_erp_customerinvoicemaster','srp_erp_customerinvoicemaster.invoiceAutoID = srp_erp_documentemailhistory.documentAutoID','left')
            ->from('srp_erp_documentemailhistory');
        echo $this->datatables->generate();
    }

    function load_insurancetypetable(){
        $companyID = $this->common_data['company_data']['company_id'];

        $details = $this->db->query("SELECT srp_erp_invoiceinsurancetypes.insuranceTypeID AS insuranceTypeID, srp_erp_invoiceinsurancetypes.insuranceType, CONCAT( srp_erp_chartofaccounts.GLDescription, ' - ', srp_erp_chartofaccounts.GLSecondaryCode ) AS GLDescription, srp_erp_invoiceinsurancetypes.marginPercentage, srp_erp_invoiceinsurancetypes.masterTypeID, srp_erp_invoiceinsurancetypes.noofMonths FROM `srp_erp_invoiceinsurancetypes` LEFT JOIN `srp_erp_chartofaccounts` ON `srp_erp_chartofaccounts`.`GLAutoID` = `srp_erp_invoiceinsurancetypes`.`GLAutoID` WHERE `srp_erp_invoiceinsurancetypes`.`companyID` = $companyID ")->result_array();


        $data['details'] = $details;

        $html = $this->load->view('system/invoices/insurance_type_table_body', $data, true);
        echo $html;
    }

    function open_receipt_voucher_modal(){
        echo json_encode($this->Invoice_model->open_receipt_voucher_modal());
    }

    function save_receiptvoucher_from_CINV_header()
    {
        $date_format_policy = date_format_policy();
        $RVdt = $this->input->post('RVdate');
        $RVdate = input_format_date($RVdt, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $RVcqeDte = $this->input->post('RVchequeDate');
        $RVchequeDate = input_format_date($RVcqeDte, $date_format_policy);

        $this->form_validation->set_rules('RVdate', 'Receipt Voucher Date', 'trim|required');

        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
            $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        }
        $bank_detail = fetch_gl_account_desc(trim($this->input->post('RVbankCode')));
        if ($bank_detail['isCash'] == 0) {
            $this->form_validation->set_rules('RVchequeDate', 'Cheque Date', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e',validation_errors()));
        } else {
            if ($financeyearperiodYN == 1) {
                $financearray = $this->input->post('financeyear_period');
                $financePeriod = fetchFinancePeriod($financearray);
                if ($RVdate >= $financePeriod['dateFrom'] && $RVdate <= $financePeriod['dateTo']) {

                    echo json_encode($this->Invoice_model->save_receiptvoucher_from_CINV_header());
                } else {
                    echo json_encode(array('e', 'Receipt Voucher Date not between Financial period !'));
                }
            }else{
                echo json_encode($this->Invoice_model->save_receiptvoucher_from_CINV_header());
            }
        }
    }
    function fetch_quotation_segment()
    {
        $contractID = $this->input->post('contractAutoID');
        $companyID = current_companyID();

        $data = $this->db->query("SELECT
	segmentID,segmentCode
FROM
	`srp_erp_contractmaster`
	where 
	companyID =  $companyID
	AND contractAutoID  = $contractID 
	")->row_array();

        echo  json_encode($data);


    }

}
