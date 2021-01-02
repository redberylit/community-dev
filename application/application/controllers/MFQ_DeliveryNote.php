<?php

class MFQ_DeliveryNote extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_DeliveryNote_model');
    }

    function fetch_delivery_note()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("srp_erp_mfq_deliverynote.deliverNoteID as deliverNoteID,srp_erp_mfq_deliverynote.deliveryNoteCode as deliveryNoteCode,srp_erp_mfq_job.documentCode as jobCode,DATE_FORMAT(deliveryDate,'" . $convertFormat . "') AS deliveryDate,srp_erp_mfq_customermaster.CustomerName as CustomerName,srp_erp_mfq_deliverynote.confirmedYN as confirmedYN,srp_erp_mfq_deliverynote.approvedYN as approvedYN,srp_erp_mfq_deliverynote.createdUserID as createdUserID", false)
            ->from('srp_erp_mfq_deliverynote')
            ->join('srp_erp_mfq_job', 'srp_erp_mfq_job.workProcessID = srp_erp_mfq_deliverynote.jobID', 'left')
            ->join('srp_erp_mfq_customermaster', 'srp_erp_mfq_customermaster.mfqCustomerAutoID = srp_erp_mfq_deliverynote.mfqCustomerAutoID', 'left')
            ->where('srp_erp_mfq_deliverynote.companyID', current_companyID());
        $this->datatables->add_column('status', '$1', 'confirmation_status(confirmedYN)');
        $this->datatables->add_column('edit', '$1', 'load_delivery_note_action(deliverNoteID,confirmedYN,approvedYN,createdUserID)');
        echo $this->datatables->generate();
    }

    function save_delivery_note_header()
    {
        $this->form_validation->set_rules('mfqCustomerAutoID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('jobID', 'Job', 'trim|required');
        $this->form_validation->set_rules('deliveryDate', 'Delivery Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('driverName', 'Driver Name', 'trim|required');
        $this->form_validation->set_rules('mobileNo', 'Mobile No', 'trim|required');
        $this->form_validation->set_rules('vehicleNo', 'Vehicle No', 'trim|required');
        //$this->form_validation->set_rules('deliveryNoteCode', 'Delivery Note Code', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {

            echo json_encode($this->MFQ_DeliveryNote_model->save_delivery_note_header());
        }
    }

    function load_deliveryNote_confirmation()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $deliverNoteID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('deliverNoteID'));

        $data['header'] = $this->db->query("SELECT dn.deliverNoteID as deliverNoteID, dn.deliveryNoteCode as deliveryNoteCode, job.documentCode as jobCode,DATE_FORMAT(dn.deliveryDate,'$convertFormat') AS deliveryDate,cus.CustomerName as CustomerName, dn.confirmedYN, dn.approvedYN, dn.createdUserID,driverName,vehicleNo,mobileNo,job.qty as detailQty,item.itemName as itemName, estm.poNumber as estmPoNumber,DATE_FORMAT(dn.confirmedDate,'$convertFormat') as confirmedDate,dn.confirmedByName FROM srp_erp_mfq_deliverynote dn LEFT JOIN srp_erp_mfq_job job ON job.workProcessID = dn.jobID LEFT JOIN srp_erp_mfq_customermaster cus ON cus.mfqCustomerAutoID = dn.mfqCustomerAutoID LEFT JOIN srp_erp_mfq_itemmaster item ON item.mfqItemID = job.mfqItemID LEFT JOIN srp_erp_mfq_estimatedetail estd ON estd.estimateDetailID = job.estimateDetailID LEFT JOIN srp_erp_mfq_estimatemaster estm ON estd.estimateMasterID = estm.estimateMasterID WHERE dn.companyID = {$companyID} AND dn.deliverNoteID = {$deliverNoteID}")->row_array();

        $html = $this->load->view('system/mfq/ajax/delivery_note_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function load_delivery_note_header()
    {
        echo json_encode($this->MFQ_DeliveryNote_model->load_delivery_note_header());
    }

    function delivery_note_confirmation()
    {
        echo json_encode($this->MFQ_DeliveryNote_model->delivery_note_confirmation());
    }

    function referback_delivery_note()
    {
        echo json_encode($this->MFQ_DeliveryNote_model->referback_delivery_note());

    }

    function delete_delivery_note()
    {
        echo json_encode($this->MFQ_DeliveryNote_model->delete_delivery_note());
    }

    function fetch_customer_jobs()
    {
        echo json_encode($this->MFQ_DeliveryNote_model->fetch_customer_jobs());
    }

}
