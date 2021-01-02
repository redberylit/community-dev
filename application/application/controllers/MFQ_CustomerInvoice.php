<?php

class MFQ_CustomerInvoice extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_CustomerInvoice_model');
    }

    function fetch_customer_invoice()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("invoiceCode,DATE_FORMAT(invoiceDate,'" . $convertFormat . "') AS invoiceDate,DATE_FORMAT(invoiceDueDate,'" . $convertFormat . "') AS invoiceDueDate,invoiceNarration,srp_erp_mfq_customermaster.CustomerName as customerName,approvedYN,confirmedYN,det.transactionAmount as transactionAmount,transactionCurrencyDecimalPlaces,transactionCurrency,srp_erp_mfq_customerinvoicemaster.invoiceAutoID as invoiceAutoID,srp_erp_currencymaster.CurrencyCode as CurrencyCode", false)
            ->from('srp_erp_mfq_customerinvoicemaster')
            ->join('srp_erp_mfq_customermaster', 'srp_erp_mfq_customermaster.mfqCustomerAutoID = srp_erp_mfq_customerinvoicemaster.mfqCustomerAutoID', 'left')
            ->join('srp_erp_currencymaster', 'srp_erp_currencymaster.currencyID = srp_erp_mfq_customerinvoicemaster.transactionCurrencyID', 'left')
            ->join('(SELECT SUM(transactionAmount) as transactionAmount,invoiceAutoID FROM srp_erp_mfq_customerinvoicedetails GROUP BY invoiceAutoID) det', '(det.invoiceAutoID = srp_erp_mfq_customerinvoicemaster.invoiceAutoID)', 'left')
            ->where("srp_erp_mfq_customerinvoicemaster.companyID =" . current_companyID());
        $this->datatables->add_column('edit', '$1', 'editCustomerInvoice(invoiceAutoID,confirmedYN,approvedYN)');
        $this->datatables->add_column('invoice_detail', '<b>Customer Name : </b> $2 <br> <b>Document Date : </b> $3 <b style="text-indent: 1%;">&nbsp | &nbsp Due Date : </b> $4 <br>  <b>Comments : </b> $1 ', 'trim_desc(invoiceNarration),customerName,invoiceDate,invoiceDueDate');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(transactionAmount,transactionCurrencyDecimalPlaces),CurrencyCode');
        $this->datatables->add_column('confirmed', '$1', 'confirmation_status(confirmedYN)');
        echo $this->datatables->generate();
    }

    function save_customer_invoice()
    {
        $this->form_validation->set_rules('invoiceNarration', 'Comment', 'trim|required');
        $this->form_validation->set_rules('invoiceDueDate', 'Invoice due date', 'trim|required');
        $this->form_validation->set_rules('deliveryNoteID', 'Delivery Note', 'trim|required');
        $this->form_validation->set_rules('mfqCustomerAutoID', 'Customer', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->MFQ_CustomerInvoice_model->save_customer_invoice());
        }
    }

    function fetch_delivery_note()
    {
        echo json_encode($this->MFQ_CustomerInvoice_model->fetch_delivery_note());
    }

    function load_mfq_customerInvoice()
    {
        echo json_encode($this->MFQ_CustomerInvoice_model->load_mfq_customerInvoice());
    }

    function load_mfq_customerinvoicedetail()
    {
        echo json_encode($this->MFQ_CustomerInvoice_model->load_mfq_customerinvoicedetail());
    }

    function fetch_customer_invoice_print()
    {
        $data = array();
        $data["header"] = $this->MFQ_CustomerInvoice_model->load_mfq_customerInvoice();
        $data["itemDetail"] = $this->MFQ_CustomerInvoice_model->load_mfq_customerinvoicedetail();
        $data['approval'] = $this->input->post('approval');
        $this->load->view('system/mfq/ajax/customer_invoice_print', $data);
    }

    function customer_invoice_confirmation()
    {
        echo json_encode($this->MFQ_CustomerInvoice_model->customer_invoice_confirmation());
    }

    function delete_customerInvoiceDetail()
    {
        echo json_encode($this->MFQ_CustomerInvoice_model->delete_customerInvoiceDetail());
    }

    function fetch_double_entry_mfq_customerInvoice()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $data['extra'] = $this->MFQ_CustomerInvoice_model->fetch_double_entry_mfq_customerInvoice($masterID);
        $html = $this->load->view('system/double_entry/erp_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }

    function fetch_chartofAccount()
    {
        echo json_encode($this->MFQ_CustomerInvoice_model->fetch_chartofAccount());
    }
}
