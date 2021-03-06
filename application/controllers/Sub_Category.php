<?php
class Sub_category extends ERP_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Subcategory_model');
    }

    public function index(){
        $data['title'] = 'Sub Category';
        $data['main_content'] = 'sub_category_MU_add';
        $data['extra'] = NULL;

        $this->load->model('Subcategory_model');
        //$data['bank_data']=$this->srp_mu_itemMasterModel->getBankDetails();
        $this->load->view('includes/template', $data);
    }

    function header_update(){
        echo json_encode($this->Subcategory_model->header_update());
       // echo json_encode($this->Subcategory_model->save_item_category());
    }

    function load_subcategory(){

        $faeditID = $this->input->post('idedit');

        $depMaster = $this->db->query("SELECT itemCategoryID,categoryTypeID FROM srp_erp_itemcategory WHERE itemCategoryID = '{$faeditID}'")->row_array();

        if($depMaster['categoryTypeID'] != 3){
            $test = 'itemCategoryID,revenueGL,costGL,assetGL';
        }else{
            $test = 'itemCategoryID,faCostGLAutoID,faACCDEPGLAutoID,faDEPGLAutoID';
        }

        $this->datatables->select('itemCategoryID,description,masterID,revenueGL,costGL,assetGL,faCostGLAutoID,faACCDEPGLAutoID,faDEPGLAutoID,faDISPOGLAutoID')
            ->from('srp_erp_itemcategory')
            ->where('masterID', $this->input->post('idedit'))
            ->edit_column('addsubsub', '<span class="pull-right" onclick="subsubcategory($1,$2,$3,$4),resetform()"><a href="#" ><button type="button" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus" style="color:green;"></span></button></a></span>',$test )
        ->edit_column('action', '<span class="pull-right" onclick="opensubcategoryedit($1)"><a href="#" ><span class="glyphicon glyphicon-pencil" style="color:blue;"  rel="tooltip"></span></a></span>', 'itemCategoryID');

        echo $this->datatables->generate();
    }
    function load_subcategoryMaster(){
        $data['pageID']= $this->input->post('idedit');
        $companyID=$this->common_data['company_data']['company_id'];
        $data['table']=$this->db->query("select itemCategoryID,description,masterID,revenueGL,costGL,assetGL,faCostGLAutoID,faACCDEPGLAutoID,faDEPGLAutoID,faDISPOGLAutoID from srp_erp_itemcategory where companyID = '{$companyID}' AND masterID IS NOT NULL  order by itemCategoryID desc ")->result_array();
        $data['depMaster'] = $this->db->query("SELECT itemCategoryID,categoryTypeID,masterID FROM srp_erp_itemcategory WHERE itemCategoryID = '{$data['pageID']}'")->row_array();
        $this->load->view('system/inventory/erp_item_category', $data);


    }

    function load_subsubcategory(){
        $this->datatables->select('itemCategoryID,description,masterID')
            ->from('srp_erp_itemcategory')
            ->where('masterID', $this->input->post('subsubcategoryedit'))
            ->edit_column('action', '<span class="pull-right" onclick="opensubsubcategoryedit($1)"><a href="#" ><span class="glyphicon glyphicon-pencil" style="color:blue;"  rel="tooltip"></span></a></span>', 'itemCategoryID');


        echo $this->datatables->generate();
    }

    function save_subcategory()
    {
        $CategoryID = $this->input->post('master');

        $Category = $this->db->query("SELECT itemCategoryID,categoryTypeID FROM srp_erp_itemcategory WHERE itemCategoryID = '{$CategoryID}'")->row_array();
        if($Category['categoryTypeID'] == 1){
            //$this->form_validation->set_rules('costgl', 'Cost GL', 'trim|required');
           // $this->form_validation->set_rules('revnugl', 'Revenue GL', 'trim|required');
            $this->form_validation->set_rules('assetgl', 'Asset GL', 'trim|required');
            $this->form_validation->set_rules('revnugl', 'Revenue GL', 'trim|required');
            $this->form_validation->set_rules('costgl', 'Cost GL', 'trim|required');
        } else if($Category['categoryTypeID'] == 3){
            $this->form_validation->set_rules('COSTGLCODEdes', 'Cost Account', 'trim|required');
            $this->form_validation->set_rules('ACCDEPGLCODEdes', 'Acc Dep GL Code', 'trim|required');
            $this->form_validation->set_rules('DEPGLCODEdes', 'Dep GL Code', 'trim|required');
            $this->form_validation->set_rules('DISPOGLCODEdes', 'Disposal GL Code', 'trim|required');
        }
        else{
            $this->form_validation->set_rules('costgl', 'Cost GL', 'trim|required');

        }
        $this->form_validation->set_rules('subcategory', 'Sub Category', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Subcategory_model->save_sub_category());
        }
    }

    function save_subsubcategory()
    {
        $this->form_validation->set_rules('subsubcategory', 'Sub Sub Category', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Subcategory_model->save_sub_sub_category());
        }
    }

    function edit_itemsubcategory()
    {
        if($this->input->post('id') !=""){
            echo json_encode($this->Subcategory_model->edit_itemsubcategory());
        }
        else{
            echo json_encode(FALSE);
        }
    }

    function update_subcategory(){
        $CategoryID = $this->input->post('master');
        $Category = $this->db->query("SELECT itemCategoryID,categoryTypeID FROM srp_erp_itemcategory WHERE itemCategoryID = '{$CategoryID}'")->row_array();

        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if(!$Category['categoryTypeID'] == 3) {
            $this->form_validation->set_rules('revnugledit', 'Revenue GL', 'trim|required');
            $this->form_validation->set_rules('costgledit', 'Cost GL', 'trim|required');
        }
        if(!$Category['categoryTypeID'] == 2){
            $this->form_validation->set_rules('assetgledit', 'Asset GL', 'trim|required');
            $this->form_validation->set_rules('stockadjustedit', 'Stock Adjustment GL', 'trim|required');
        }




        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e',validation_errors()));

        } else {
            echo json_encode($this->Subcategory_model->update_itemsubcategory());
        }


    }

    function edit_itemsubsubcategory()
    {
        if($this->input->post('id') !=""){
            echo json_encode($this->Subcategory_model->edit_itemsubsubcategory());
        }
        else{
            echo json_encode(FALSE);
        }
    }

    function update_subsubcategory(){

        $this->form_validation->set_rules('descriptionsubsub', 'Sub Sub Category', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            //$this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(array('e',validation_errors()));
        } else {
            echo json_encode($this->Subcategory_model->update_subsubcategory());
        }
    }


}
