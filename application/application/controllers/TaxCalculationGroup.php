<?php

class TaxCalculationGroup extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Taxcalculationgroup_model');
        $this->load->helpers('Tax_formula');
    }

    function fetch_calculation_group()
    {

        $companyid = $this->common_data['company_data']['company_id'];
        $where = "srp_erp_taxcalculationformulamaster.companyID = " . $companyid . "";
        $this->datatables->select('taxCalculationformulaID,Description,taxType')
            ->where($where)
            ->from('srp_erp_taxcalculationformulamaster');
        $this->datatables->add_column('type_detail', '$1', 'get_tax_type(taxType)');
        $this->datatables->add_column('edit', '<span class="pull-right"><a onclick=\'fetchPage("system/tax/tax_formula_edit",$1,"Edit Tax Formula Group","TAX"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="assign_items($1,$2)"><span title="Link" rel="tooltip" class="glyphicon glyphicon-link"></span></a> </span>', 'taxCalculationformulaID,taxType');
        echo $this->datatables->generate();
    }

    function save_tax_calculation_header()
    {
        $this->form_validation->set_rules('Description', 'Description', 'trim|required');
        $this->form_validation->set_rules('taxType', 'Tax Type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e',validation_errors()));
        } else {
            echo json_encode($this->Taxcalculationgroup_model->save_tax_calculation_header());
        }
    }

    function load_calculation_group()
    {
        echo json_encode($this->Taxcalculationgroup_model->load_calculation_group());
    }

    function delete_authority()
    {
        echo json_encode($this->Authoritymaster_model->delete_authority());
    }

    function fetch_formula_detail()
    {
        $url = site_url('TaxCalculationGroup/formulaDecodeTax');
        $companyid = $this->common_data['company_data']['company_id'];
        $taxCalculationformulaID = $this->input->post('taxCalculationformulaID');
        $where = "srp_erp_taxcalculationformuladetails.companyID = " . $companyid . " And taxCalculationformulaID = " . $taxCalculationformulaID . "";
        $this->datatables->select('formulaDetailID,description,sortOrder,srp_erp_taxmaster.taxDescription as taxDescription,srp_erp_taxmaster.taxShortCode as taxShortCode')
            ->where($where)
            ->join('srp_erp_taxmaster ', 'srp_erp_taxmaster.taxMasterAutoID = srp_erp_taxcalculationformuladetails.taxMasterAutoID')
            ->from('srp_erp_taxcalculationformuladetails');
        $this->datatables->add_column('type_detail', '<b>Description : </b> $1 &nbsp;&nbsp;&nbsp;<b>Secondary Code : </b>$2', 'taxDescription,taxShortCode');
        $this->datatables->add_column('edit', '<span class="pull-right"><a onclick="formulaModalOpen(\'$2\',$1,\''.$url.'\', \'\',1)"><span title="" rel="tooltip" class="fa fa-superscript" data-original-title="Formula"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="open_formula_detail_edit($1)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a></span>', 'formulaDetailID,description');
        echo $this->datatables->generate();
    }

    function save_tax_formula_detail_form(){
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('taxMasterAutoID', 'Tax Type', 'trim|required');
        $this->form_validation->set_rules('sortOrder', 'Sort Order', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e',validation_errors()));
        } else {
            echo json_encode($this->Taxcalculationgroup_model->save_tax_formula_detail_form());
        }
    }

    function load_formula_detail(){
        echo json_encode($this->Taxcalculationgroup_model->load_formula_detail());
    }

    function formulaDecodeTax()
    {
        $payGroupID = $this->input->post('payGroupID');
        $decodeType = $this->uri->segment(3);
        $companyID = current_companyID();

        $formula = $this->db->select('formula')->from('srp_erp_taxcalculationformuladetails')
            ->where('formulaDetailID', $payGroupID)->where('companyID', $companyID)->get()->row('formula');


        $sortOrder = $this->db->query("SELECT sortOrder,taxCalculationformulaID FROM srp_erp_taxcalculationformuladetails WHERE formulaDetailID='$payGroupID' ")->row_array();
        $taxCalculationformulaID=$sortOrder['taxCalculationformulaID'];
        $sortOrder=$sortOrder['sortOrder'];

        $tax_categories = $this->db->query("SELECT
	srp_erp_taxcalculationformuladetails.*,srp_erp_taxmaster.taxDescription
FROM
	srp_erp_taxcalculationformuladetails
LEFT JOIN srp_erp_taxmaster on srp_erp_taxmaster.taxMasterAutoID = srp_erp_taxcalculationformuladetails.taxMasterAutoID
WHERE
	taxCalculationformulaID = $taxCalculationformulaID
AND srp_erp_taxcalculationformuladetails.companyID = $companyID AND sortOrder < $sortOrder  ")->result_array();


        $formulaDecodeData = ['decodedList' => '','taxes' => ''];

        if (!empty($formula) && $formula != null) {
            $formulaDecodeData['decodedList'] = formulaDecodeTax($formula);
        }

        if (!empty($tax_categories)) {
            $formulaDecodeData['taxes'] = $tax_categories;
        }
        $formulaDecodeData['from-tax'] = 1;
        echo json_encode($formulaDecodeData);
    }

    function saveFormula_tax(){
        $this->form_validation->set_rules('payGroupID', 'ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Taxcalculationgroup_model->saveFormula_tax());
        }
    }

    function fetch_item()
    {
        $type='';
        if($this->input->post('taxType')==1){
            $type='salesTaxFormulaID';
        }else{
            $type='purchaseTaxFormulaID';
        }
        $companyid=$this->common_data['company_data']['company_id'];
        $taxCalculationformulaID=$this->input->post('taxCalculationformulaID');
        $where = "srp_erp_itemmaster.companyID = " . $companyid . " And (srp_erp_itemmaster.$type is null OR srp_erp_itemmaster.$type = " .$taxCalculationformulaID. ") ";
        $this->datatables->select('itemAutoID,itemSystemCode,itemName,seconeryItemCode,itemImage,itemDescription,mainCategoryID,mainCategory,defaultUnitOfMeasure,currentStock,companyLocalSellingPrice,companyLocalCurrency,companyLocalCurrencyDecimalPlaces,revanueDescription,costDescription,assteDescription,isActive,companyLocalWacAmount,subcat.description as SubCategoryDescription,subsubcat.description as SubSubCategoryDescription,CONCAT(currentStock,\'  \',defaultUnitOfMeasure) as CurrentStock,CONCAT(companyLocalWacAmount,\'  \',companyLocalCurrency) as TotalWacAmount,CONCAT(itemSystemCode," - ",itemDescription) as description, isSubitemExist,'.$type.'', false)
            ->from('srp_erp_itemmaster')
            ->join('srp_erp_itemcategory subcat', 'srp_erp_itemmaster.subcategoryID = subcat.itemCategoryID')
            ->join('srp_erp_itemcategory subsubcat', 'srp_erp_itemmaster.subSubCategoryID = subsubcat.itemCategoryID','left');
        if (!empty($this->input->post('mainCategory'))) {
            $this->datatables->where('mainCategoryID', $this->input->post('mainCategory'));
        }
        if (!empty($this->input->post('subcategory'))) {
            $this->datatables->where('subcategoryID', $this->input->post('subcategory'));
        }
        if (!empty($this->input->post('subsubcategoryID'))) {
            $this->datatables->where('subSubCategoryID', $this->input->post('subsubcategoryID'));
        }
        $this->datatables->where($where);
        /*$this->datatables->where('srp_erp_itemmaster.'.$type.'', null);
        $this->datatables->or_where('srp_erp_itemmaster.'.$type.'', $this->input->post('taxCalculationformulaID'));*/
        $this->datatables->add_column('item_inventryCode', '$1 - $2 <b></b>', 'itemSystemCode,itemDescription');
        $this->datatables->add_column('TotalWacAmount', '$1  $2', 'number_format(companyLocalWacAmount,2),companyLocalCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm(isActive)');
        //$this->datatables->add_column('edit', '<div style="text-align: center;"><div class="skin skin-square item-iCheck"> <div class="skin-section extraColumns"><input id="selectItem_$1" onclick="ItemsSelectedSync(this)" name="checkedInvoice[]" type="checkbox" class="columnSelected"  value="$1" ><label for="checkbox">&nbsp;</label> </div></div></div>', 'itemAutoID,'.$type.'');
        $this->datatables->add_column('edit', '$1', 'taxChkbox(itemAutoID,'.$type.','.$taxCalculationformulaID.')');


        echo $this->datatables->generate();
    }

    function update_item_taxid(){
        echo json_encode($this->Taxcalculationgroup_model->update_item_taxid());
    }


}
