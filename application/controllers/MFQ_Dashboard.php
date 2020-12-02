<?php

class MFQ_Dashboard extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_Dashboard_modal');
    }

    function fetch_machine()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("srp_erp_mfq_fa_asset_master.mfq_faID,IFNULL(mfa.documentCode,'<span style=\'color: #008000 \'>Available</span>') as documentCode,assetDescription,IFNULL(mfa.hoursSpent,'-') as hoursSpent,IFNULL(DATE_FORMAT(mfa.endDate,'".$convertFormat."'),'-') AS endDate,faCode", false)
            ->from('srp_erp_mfq_fa_asset_master')
            ->join('(SELECT SUM(hoursSpent) as hoursSpent,documentCode,endDate,mfq_faID FROM srp_erp_mfq_workprocessmachines LEFT JOIN srp_erp_mfq_job ON srp_erp_mfq_job.workProcessID = srp_erp_mfq_workprocessmachines.workProcessID GROUP BY srp_erp_mfq_workprocessmachines.workProcessID,mfq_faID) mfa', 'srp_erp_mfq_fa_asset_master.mfq_faID = mfa.mfq_faID','left')->where('srp_erp_mfq_fa_asset_master.companyID',current_companyID());
        echo $this->datatables->generate();

    }

    function fetch_job_status()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("documentCode,workProcessID,DATE_FORMAT(startDate,'".$convertFormat."') AS startDate,DATE_FORMAT(endDate,'".$convertFormat."') AS endDate,description,ws.percentage as percentage,", false)
            ->from('srp_erp_mfq_job')
            ->join('(SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID)  ws', 'ws.jobID = srp_erp_mfq_job.workProcessID')->where('srp_erp_mfq_job.companyID',current_companyID());
        $this->datatables->edit_column('percentage', '<span class="text-center" style="vertical-align: middle">$1</span>', 'job_status(percentage)');
        $this->datatables->edit_column('description', '<span class="text-center" style="vertical-align: middle">$1</span>', 'trim_value(description,5)');
        echo $this->datatables->generate();
    }

    function fetch_jobs_status()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->query("documentCode,workProcessID,DATE_FORMAT(startDate,'".$convertFormat."') AS startDate,DATE_FORMAT(endDate,'".$convertFormat."') AS endDate,description,ws.percentage as percentage,", false)
            ->from('srp_erp_mfq_job')
            ->join('(SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END))/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID)  ws', 'ws.jobID = srp_erp_mfq_job.workProcessID')->where('srp_erp_mfq_job.companyID',current_companyID());
        $this->datatables->edit_column('percentage', '<span class="text-center" style="vertical-align: middle">$1</span>', 'job_status(percentage)');
        echo $this->datatables->generate();
    }

    function fetch_jobs()
    {
       echo json_encode($this->MFQ_Dashboard_modal->fetch_jobs());
    }

    function fetch_ongoing_job()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("documentCode,srp_erp_mfq_job.workProcessID,DATE_FORMAT(startDate,'".$convertFormat."') AS startDate,DATE_FORMAT(endDate,'".$convertFormat."') AS endDate,DATE_FORMAT(srp_erp_mfq_job.documentDate,'".$convertFormat."') AS documentDate,documentCode,srp_erp_mfq_job.description as description,ws.percentage as percentage,cust.CustomerName,seg.description as segment,qty,em.estimateCode,(jcm.materialCharge+jcl.totalValue+jco.totalValue) as amount,jcm.companyLocalCurrencyDecimalPlaces as companyLocalCurrencyDecimalPlaces", false)
            ->from('srp_erp_mfq_job')
            ->join('(SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID)  ws', 'ws.jobID = srp_erp_mfq_job.workProcessID')
            ->join('(SELECT * FROM srp_erp_mfq_estimatedetail) ed', 'ed.estimateDetailID = srp_erp_mfq_job.estimateDetailID','left')
            ->join('(SELECT * FROM srp_erp_mfq_estimatemaster) em', 'em.estimateMasterID = ed.estimateMasterID','left')
            ->join('(SELECT * FROM srp_erp_mfq_customermaster) cust', 'cust.mfqCustomerAutoID = srp_erp_mfq_job.mfqCustomerAutoID','left')
            ->join('(SELECT * FROM srp_erp_mfq_segment) seg', 'seg.mfqSegmentID = srp_erp_mfq_job.mfqSegmentID','left')
            ->join('(SELECT SUM(IFNULL(materialCharge,0)) as materialCharge,workProcessID,companyLocalCurrencyDecimalPlaces FROM srp_erp_mfq_jc_materialconsumption GROUP BY workProcessID) jcm', 'jcm.workProcessID = srp_erp_mfq_job.workProcessID','left')
            ->join('(SELECT SUM(IFNULL(totalValue,0)) as totalValue,workProcessID FROM srp_erp_mfq_jc_labourtask GROUP BY workProcessID) jcl', 'jcl.workProcessID = srp_erp_mfq_job.workProcessID','left')
            ->join('(SELECT SUM(IFNULL(totalValue,0)) as totalValue,workProcessID FROM srp_erp_mfq_jc_overhead GROUP BY workProcessID) jco', 'jco.workProcessID = srp_erp_mfq_job.workProcessID','left')
            ->where('srp_erp_mfq_job.companyID',current_companyID())->where('percentage != 100');
        $this->datatables->edit_column('percentage', '<div class="text-center" style="vertical-align: middle">$1%</div>', 'round_percentage(percentage)');
        $this->datatables->edit_column('description', '<div style="vertical-align: middle">$1</div>', 'trim_value(description,20)');
        $this->datatables->edit_column('amount', '<div class="text-right" style="vertical-align: middle">$1</div>', 'format_number(amount,companyLocalCurrencyDecimalPlaces)');
        echo $this->datatables->generate();
    }

    function ongoing_job_excel(){
        $this->load->library('excel');
        //set cell A1 content with some text
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Ongoing Job');
        // load database
        $this->load->database();
        // load model
        // get all users in array formate
        $data = $this->fetch_ongoing_job_excel();
        $header = array('Date','Job No','Division','Job Description','Client Name','Qty','Amount','Quote Ref','Job Completion(%)');
        // Header
        $this->excel->getActiveSheet()->fromArray($header, null, 'A1');
        // Data
        $this->excel->getActiveSheet()->fromArray($data, null, 'A2');
        //set aligment to center for that merged cell (A1 to D1)
        ob_clean();
        ob_start(); # added
        $filename = 'Ongoing job.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel;charset=utf-16'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        ob_clean(); # remove this
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function fetch_ongoing_job_excel(){
        $convertFormat = convert_date_format_sql();
        $this->db->select("DATE_FORMAT(srp_erp_mfq_job.documentDate,'".$convertFormat."') AS documentDate,documentCode,seg.description as segment,srp_erp_mfq_job.description as description,cust.CustomerName as CustomerName,qty,(jcm.materialCharge+jcl.totalValue+jco.totalValue) as amount,em.estimateCode as estimateCode,ws.percentage as percentage")
            ->from('srp_erp_mfq_job')
            ->join('(SELECT jobID,COUNT(*) as totCount,SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completedCount,(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus GROUP BY jobID)  ws', 'ws.jobID = srp_erp_mfq_job.workProcessID')
            ->join('(SELECT * FROM srp_erp_mfq_estimatedetail) ed', 'ed.estimateDetailID = srp_erp_mfq_job.estimateDetailID','left')
            ->join('(SELECT * FROM srp_erp_mfq_estimatemaster) em', 'em.estimateMasterID = ed.estimateMasterID','left')
            ->join('(SELECT * FROM srp_erp_mfq_customermaster) cust', 'cust.mfqCustomerAutoID = srp_erp_mfq_job.mfqCustomerAutoID','left')
            ->join('(SELECT * FROM srp_erp_mfq_segment) seg', 'seg.mfqSegmentID = srp_erp_mfq_job.mfqSegmentID','left')
            ->join('(SELECT SUM(IFNULL(materialCharge,0)) as materialCharge,workProcessID,companyLocalCurrencyDecimalPlaces FROM srp_erp_mfq_jc_materialconsumption GROUP BY workProcessID) jcm', 'jcm.workProcessID = srp_erp_mfq_job.workProcessID','left')
            ->join('(SELECT SUM(IFNULL(totalValue,0)) as totalValue,workProcessID FROM srp_erp_mfq_jc_labourtask GROUP BY workProcessID) jcl', 'jcl.workProcessID = srp_erp_mfq_job.workProcessID','left')
            ->join('(SELECT SUM(IFNULL(totalValue,0)) as totalValue,workProcessID FROM srp_erp_mfq_jc_overhead GROUP BY workProcessID) jco', 'jco.workProcessID = srp_erp_mfq_job.workProcessID','left')
            ->where('srp_erp_mfq_job.companyID',current_companyID())->where('percentage != 100');
        $result = $this->db->get()->result_array();
        return $result;
    }

    function pull_from_erp(){
        echo json_encode($this->MFQ_Dashboard_modal->pull_from_erp());
    }

    function update_wac_from_erp(){
        echo json_encode($this->MFQ_Dashboard_modal->update_wac_from_erp());
    }

    function load_erp_warehouse(){
        echo json_encode($this->MFQ_Dashboard_modal->load_erp_warehouse());
    }

    function pull_from_erp_warehouse(){
        echo json_encode($this->MFQ_Dashboard_modal->pull_from_erp_warehouse());
    }
}
