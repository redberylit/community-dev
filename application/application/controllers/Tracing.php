<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tracing extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tracing_modal');
    }

    function trace_po_document(){
        echo json_encode($this->Tracing_modal->trace_po_document());
    }

    function select_tracing_documents(){
        $data['purchaseOrderID']=$this->input->post('purchaseOrderID');
        $data['DocumentID']=$this->input->post('DocumentID');
        $html = $this->load->view('system/tracing/tracing_view', $data, true);
        echo $html;
    }
    function get_tracing_data(){
        //echo json_encode($this->Tracing_modal->get_tracing_data());
        return $this->Tracing_modal->get_tracing_data();
    }

    function deleteDocumentTracing(){
        echo json_encode($this->Tracing_modal->deleteDocumentTracing());
    }

    function trace_pr_document(){
        echo json_encode($this->Tracing_modal->trace_pr_document());
    }

    function trace_grv_document(){
        echo json_encode($this->Tracing_modal->trace_grv_document());
    }

    function trace_bsi_document(){
        echo json_encode($this->Tracing_modal->trace_bsi_document());
    }

    function trace_cnt_document(){
        echo json_encode($this->Tracing_modal->trace_cnt_document());
    }

    function trace_cinv_document(){
        echo json_encode($this->Tracing_modal->trace_cinv_document());
    }
}