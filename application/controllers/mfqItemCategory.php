<?php
class MfqItemCategory extends ERP_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Itemcategory_model');
    }


    function load_category(){
        $this->datatables->select('itemCategoryID,description,masterID')
            ->from('srp_erp_itemcategory')
            ->where('masterID',NULL )
            ->where('companyID', $this->common_data['company_data']['company_id'])
            ->edit_column('addsub', '$1', 'opensubcat(itemCategoryID,description)');


        echo $this->datatables->generate();
    }

    function save_itemcategory()
    {
        $this->form_validation->set_rules('codeprefix', 'Code Prefix', 'trim|required');
        $this->form_validation->set_rules('startserial', 'Start Serial', 'trim|required');
        $this->form_validation->set_rules('codelength', 'Code Length', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('itemtype', 'Item Type', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Itemcategory_model->save_item_category());
        }
    }

    function edit_itemcategory()
    {
        if($this->input->post('id') !=""){
            echo json_encode($this->Itemcategory_model->edit_itemcategory());
        }
        else{
            echo json_encode(FALSE);
        }
    }

}
