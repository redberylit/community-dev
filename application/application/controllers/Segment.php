<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Segment extends ERP_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('Segment_modal');
    }

    function load_segment(){
        $this->datatables->select("segmentID,companyID,companyCode,segmentCode,description,status");
        $this->datatables->where('companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->from('srp_erp_segment');
        $this->datatables->add_column('action', '$1', 'load_segment_action(segmentID)');
        $this->datatables->add_column('status', '$1', 'load_segment_status(segmentID,status)');

        echo $this->datatables->generate();
    }

    function save_segment()
    {
        if(!$this->input->post('segmentID')) {
            $this->form_validation->set_rules('segmentcode', 'Segment Code', 'trim|required|max_length[10]');
        }
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Segment_modal->save_segment());
        }
    }

    function edit_segment()
    {
        if($this->input->post('segmentID') !=""){
            echo json_encode($this->Segment_modal->edit_segment());
        }
        else{
            echo json_encode(FALSE);
        }
    }

    function update_segmentstatus()
    {
        echo json_encode($this->Segment_modal->update_segmentstatus());

    }

}