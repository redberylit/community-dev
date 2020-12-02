<?php 
// =============================================
// -  File Name : Tax.php
// -  Project Name : MERP
// -  Module Name : Tax
// -  Author : Nuski Mohamed
// -  Create date : 11 - September 2016
// -  Description : This file contains the add function for tax.

// - REVISION HISTORY
// - Date: 5-November 2016 By: Nuski Description: Added a new function named as load_tax()
// - Date: 2-November 2016 By: Nuski Description: changed the function to add multiple items with different location in save_tax_header(),delete_tax(),laad_tax_header()
// -  =============================================

defined('BASEPATH') OR exit('No direct script access allowed');
class Tax extends ERP_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('Tax_modal');
    }

    function load_tax(){
        $this->datatables->select("taxMasterAutoID,companyID,companyCode,taxShortCode,taxDescription,isActive,taxType,supplierSystemCode, supplierName");
        $this->datatables->where('companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->from('srp_erp_taxmaster');
        $this->datatables->add_column('type', '$1', 'text_type(taxType)');
        $this->datatables->add_column('status', '$1', 'confirm(isActive)');
        $this->datatables->add_column('supplier', '( $1 ) $2', 'supplierSystemCode,supplierName');
        //$this->datatables->add_column('action', '<span class="pull-right"><a onclick="fetchPage(\'system/tax/erp_tax_new\',$1,\'Add Tax\',\'Tax\');"><span title="Edit" class="glyphicon glyphicon-pencil" style="color:blue;" rel="tooltip"></span></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a onclick="delete_tax($1,\'$2\')"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a></span>','taxMasterAutoID,taxShortCode');
        $this->datatables->add_column('action', '<span class="pull-right"><a onclick="fetchPage(\'system/tax/erp_tax_new\',$1,\'Add Tax\',\'Tax\');"><span title="Edit" class="glyphicon glyphicon-pencil" style="color:blue;" rel="tooltip"></span></a>&nbsp;&nbsp;&nbsp;</span>','taxMasterAutoID,taxShortCode');
        echo $this->datatables->generate();
    }

    function save_tax_header(){
        $this->form_validation->set_rules('taxDescription', 'taxDescription', 'trim|required');
        $this->form_validation->set_rules('supplierID', 'Supplier', 'trim|required');
        $this->form_validation->set_rules('taxShortCode', 'taxShortCode', 'trim|required');
        $this->form_validation->set_rules('taxType', 'taxType', 'trim|required');
        $this->form_validation->set_rules('effectiveFrom', 'effectiveFrom', 'trim|required');
        $this->form_validation->set_rules('supplierGLAutoID', 'Liability Account', 'trim|required');
        //$this->form_validation->set_rules('grvDate', 'Delivered Date', 'trim|required');
        //$this->form_validation->set_rules('location', 'Delivery Location', 'trim|required');
        if($this->form_validation->run()==FALSE)
        {
            $this->session->set_flashdata($msgtype='e',validation_errors());
            echo json_encode(FALSE);
        }
        else
        { 
            echo json_encode($this->Tax_modal->save_tax_header());
        } 
    }

    function delete_tax()
    {
        echo json_encode($this->Tax_modal->delete_tax());
    }

    function laad_tax_header()
    {
        echo json_encode($this->Tax_modal->laad_tax_header());
    }

    function load_tax_group_master(){
        $this->datatables->select('taxGroupID,taxType,Description')
            ->from('srp_erp_taxgroup')
            ->where('companyID', $this->common_data['company_data']['company_id'])
            ->edit_column('taxType', '$1', 'tax_groupMaster(taxType)')
            ->add_column('action', '<span class="pull-right"><a onclick="openTaxGgroupEdit($1);"><span title="Edit" class="glyphicon glyphicon-pencil" style="color:blue;" rel="tooltip"></span></span>','taxGroupID');
        echo $this->datatables->generate();
    }

    function save_tax_group_header(){
        $this->form_validation->set_rules('taxgroup', 'Tax Group', 'trim|required');
        $this->form_validation->set_rules('taxdescription', 'Description', 'trim|required');
        if($this->form_validation->run()==FALSE)
        {
            $this->session->set_flashdata($msgtype='e',validation_errors());
            echo json_encode(FALSE);
        }
        else
        {
            echo json_encode($this->Tax_modal->save_tax_group_header());
        }
    }

    function get_tax_group_edit(){
        if($this->input->post('id') !=""){
            echo json_encode($this->Tax_modal->get_tax_group_edit());
        }
        else{
            echo json_encode(FALSE);
        }
    }

    function changesupplierGLAutoID(){
        echo json_encode($this->Tax_modal->changesupplierGLAutoID());
    }

    function get_tax_details()
    {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $taxType = $this->input->post('taxType');
        $currency = $this->input->post('currency');
        if (empty($datefrom)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date From is required
            </div>';
        } else if (empty($dateto)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date To is required
            </div>';
        }  else {
            $this->form_validation->set_rules('taxType[]', 'Tax Type', 'required');
            if ($this->form_validation->run() == FALSE) {
                echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
            } else {
                $data["taxtype"] = $this->Tax_modal->get_tax_type($taxType);
                $data["details"] = $this->Tax_modal->get_tax_details($taxType,$datefrom,$dateto);
                $data["currency"] = $currency;
                $data["type"] = "html";
                echo $html = $this->load->view('system/tax/load-tax-detail-report', $data, true);
            }
        }
    }

    function get_tax_details_report_pdf()
    {
        $currency = $this->input->post('currency');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $taxType = $this->input->post('taxType');
        $data["taxtype"] = $this->Tax_modal->get_tax_type($taxType);
        $data["details"] = $this->Tax_modal->get_tax_details($taxType,$datefrom,$dateto);
        $data["type"] = "pdf";
        $data["currency"] = $currency;
        $html = $this->load->view('system/tax/load-tax-detail-report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4-L');
    }


}