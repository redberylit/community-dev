<?php

class Supplier extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Suppliermaster_model');
    }

    public function index()
    {
        $data['title'] = 'Supplier Master';
        $data['main_content'] = 'srp_mu_suppliermaster_view';
        $data['extra'] = NULL;
        $this->load->view('includes/template', $data);
    }


    function fetch_supplier()
    {
        $supplier_filter = '';
        $currency_filter = '';
        $category_filter = '';
        $supplier = $this->input->post('supplierCode');
        $category = $this->input->post('category');
        $currency = $this->input->post('currency');
        if (!empty($supplier)) {
            $supplier = array($this->input->post('supplierCode'));
            $whereIN = "( " . join("' , '", $supplier) . " )";
            $supplier_filter = " AND supplierAutoID IN " . $whereIN;
        }
        if (!empty($currency)) {
            $currency = array($this->input->post('currency'));
            $whereIN = "( " . join("' , '", $currency) . " )";
            $currency_filter = " AND supplierCurrencyID IN " . $whereIN;
        }
        if (!empty($category)) {
            $category = array($this->input->post('category'));
            $whereIN = "( " . join("' , '", $category) . " )";
            $category_filter = " AND srp_erp_suppliermaster.partyCategoryID IN " . $whereIN;
        }
        $companyid = $this->common_data['company_data']['company_id'];
        $where = "srp_erp_suppliermaster.companyID = " . $companyid . $supplier_filter . $currency_filter . $category_filter. "";
        $this->datatables->select('srp_erp_partycategories.categoryDescription as categoryDescription,supplierAutoID,supplierSystemCode,supplierName,secondaryCode,supplierName,supplierAddress1,supplierAddress2,supplierCountry,supplierTelephone,supplierEmail,supplierUrl,supplierFax,isActive,supplierCurrency,supplierEmail,supplierTelephone,supplierCurrencyID,cust.Amount as Amount,cust.partyCurrencyDecimalPlaces as partyCurrencyDecimalPlaces')
            ->where($where)
            ->from('srp_erp_suppliermaster')
            ->join('srp_erp_partycategories', 'srp_erp_suppliermaster.partyCategoryID = srp_erp_partycategories.partyCategoryID', 'left')
            ->join('(SELECT sum(srp_erp_generalledger.transactionAmount/srp_erp_generalledger.partyExchangeRate)*-1 as Amount,partyAutoID,partyCurrencyDecimalPlaces FROM srp_erp_generalledger WHERE partyType = "SUP" AND subLedgerType=2 GROUP BY partyAutoID) cust', 'cust.partyAutoID = srp_erp_suppliermaster.supplierAutoID', 'left');
        $this->datatables->add_column('supplier_detail', '<b>Name : </b> $1 &nbsp;&nbsp;&nbsp;<b>Secondary Code : </b>$5<br><b>Address : </b> $2 &nbsp;&nbsp;$3 &nbsp;&nbsp;$4.<br><b> Email </b> $7  &nbsp;&nbsp;&nbsp;<b>Telephone</b> $8', 'supplierName,supplierAddress1, supplierAddress2, supplierCountry, secondaryCode, supplierCurrency, supplierEmail,supplierTelephone');
        $this->datatables->add_column('confirmed', '$1', 'confirm(isActive)');
        $this->datatables->edit_column('amt', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(Amount,partyCurrencyDecimalPlaces),supplierCurrency');
       // $this->datatables->add_column('edit', '<spsn class="pull-right"><a onclick="attachment_modal($1,\'Supplier\',\'SUP\');"><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="fetchPage(\'system/supplier/erp_supplier_master_new\',$1,\'Edit Supplier\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_supplier($1)"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a></span>', 'supplierAutoID');
        $this->datatables->add_column('edit', '$1', 'editsupplier(supplierAutoID)');
        echo $this->datatables->generate();
    }

    function save_suppliermaster()
    {
        if (!$this->input->post('supplierAutoID')) {
            $this->form_validation->set_rules('supplierCurrency', 'supplier Currency', 'trim|required');
        }
        $this->form_validation->set_rules('suppliercode', 'Supplier Code', 'trim|required');
        $this->form_validation->set_rules('supplierName', 'supplier Name', 'trim|required');
        $this->form_validation->set_rules('suppliercountry', 'supplier country', 'trim|required');
        $this->form_validation->set_rules('nameOnCheque', 'Name On Cheque', 'trim|required');
        /*        $this->form_validation->set_rules('supplierTelephone', 'supplier Telephone', 'trim|required');
                $this->form_validation->set_rules('supplierEmail', 'supplier Email', 'trim|required');
                $this->form_validation->set_rules('supplierAddress1', 'Address 1', 'trim|required');
                $this->form_validation->set_rules('supplierAddress2', 'Address 2', 'trim|required');
                $this->form_validation->set_rules('supplierCreditLimit', 'Credit Limit', 'trim|required');
                $this->form_validation->set_rules('supplierCreditPeriod', 'Credit Period', 'trim|required|max_length[3]');*/
        $this->form_validation->set_rules('liabilityAccount', 'liabilityAccount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Suppliermaster_model->save_supplier_master());
        }
    }


    function edit_supplier()
    {
        if ($this->input->post('id') != "") {
            echo json_encode($this->Suppliermaster_model->get_supplier());
        } else {
            echo json_encode(FALSE);
        }
    }

    function load_supplier_header()
    {
        echo json_encode($this->Suppliermaster_model->load_supplier_header());
    }

    function delete_supplier()
    {
        echo json_encode($this->Suppliermaster_model->delete_supplier());
    }

    function fetch_supplier_category()
    {
        $this->datatables->select('partyCategoryID,partyType,categoryDescription')
            ->where('companyID', $this->common_data['company_data']['company_id'])
            ->where('partyType', 2)
            ->from('srp_erp_partycategories');
        $this->datatables->add_column('edit', '$1', 'editsuppliercategory(partyCategoryID)');
        echo $this->datatables->generate();
    }

    function saveCategory()
    {
        $this->form_validation->set_rules('categoryDescription', 'Category', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Suppliermaster_model->saveCategory());
        }
    }

    function getCategory()
    {
        echo json_encode($this->Suppliermaster_model->getCategory());
    }

    function delete_category()
    {
        echo json_encode($this->Suppliermaster_model->delete_category());
    }

    function save_supplierbank()
    {
        $this->form_validation->set_rules('bankName', 'Bank Name', 'trim|required');
        $this->form_validation->set_rules('currencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('accountName', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('accountNumber', 'Account Number', 'trim|required');
        $this->form_validation->set_rules('swiftCode', 'Swift Code', 'trim|required');
        $this->form_validation->set_rules('ibanCode', 'IBAN Code', 'trim|required');
        $this->form_validation->set_rules('address', 'Bank Address', 'trim|required');
        $this->form_validation->set_rules('supplierAutoID', 'MasterID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Suppliermaster_model->save_bank_detail());
        }
    }

    function fetch_supplierbank()
    {
        $supplierAutoID=$this->input->post('supplierAutoID');
        $companyID = current_companyID();
        $this->datatables->select('supplierBankMasterID, supplierAutoID, accountName, accountNumber, swiftCode, IbanCode, bankName, srp_erp_supplierBankMaster.currencyID, srp_erp_currencymaster.CurrencyCode, bankAddress')
            ->from('srp_erp_supplierBankMaster')
            ->join('srp_erp_currencymaster', 'srp_erp_supplierBankMaster.CurrencyID = srp_erp_currencymaster.currencyID', 'left')
            ->where('companyID', $companyID)
            ->where('supplierAutoID',$supplierAutoID)
        ->add_column('edit', '<span class="pull-right"><a onclick="editBankDetails($1)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_supplierbank($1)"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a></span>', 'supplierBankMasterID');
        echo $this->datatables->generate();
    }

    function delete_supplierbank()
    {
        echo json_encode($this->Suppliermaster_model->delete_supplierbank());
    }

    function edit_Bank_Details(){
        echo json_encode($this->Suppliermaster_model->edit_Bank_Details());
    }

}
