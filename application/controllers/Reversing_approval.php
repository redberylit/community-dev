<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reversing_approval extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Reversing_modal');
    }

    function fetch_reversing_approval()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $documentID = $this->input->post('documentID');
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( documentDate >= '" . $datefrom . " 00:00:00' AND documentDate <= '" . $dateto . " 23:59:00')";
        }
        $documentID_filter = '\'PO\', \'GRV\', \'SR\', \'ST\', \'SA\', \'BSI\', \'PV\', \'MPV\', \'MI\', \'DN\', \'CINV\', \'RV\', \'MRV\', \'CN\', \'QUT\', \'CNT\', \'SO\', \'SP\',\'SPN\', \'SD\', \'JV\',\'DC\'';
        if (!empty($documentID)) {
            $documentID = explode(',', $this->input->post('documentID'));
            $documentID_filter = "'" . join("' , '", $documentID) . "'";
        }

        $sSearch=$this->input->post('sSearch');
        $searches='';
        if($sSearch){
            $search = str_replace("\\", "\\\\", $sSearch);
            $searches = " AND (( documentCode Like '%$search%' ESCAPE '!') OR (approvedTB.documentID Like '%$sSearch%') OR ( srp_employeesdetails.Ename2 Like '%$sSearch%') OR (approvedComments Like '%$sSearch%') OR (approvedDate Like '%$sSearch%') OR (documentDate Like '%$sSearch%')) ";
        }

        $where = "companyID = " . $companyID . $date . $searches . " AND approvedYN =1 AND isReverseApplicableYN =1" ;
        /*$this->datatables->select("documentApprovedID,documentCode,approvedComments,documentDate,approvedDate,documentID, Ename2,documentSystemCode");
        $this->datatables->where($where);
        $this->datatables->where_in('documentID',array('PO','GRV','SR','ST','SA','BSI','PV','MPV','MI','DN','CINV','RV','MRV','CN','QUT','CNT','SO','SP','SD'));
        $this->datatables->join('srp_employeesdetails','srp_erp_documentapproved.approvedEmpID = srp_employeesdetails.EIdNo');
        $this->datatables->group_by('documentID');
        $this->datatables->group_by('documentSystemCode');
        $this->datatables->from('srp_erp_documentapproved');
        $this->datatables->add_column('employee', '$1', 'Ename2');
        $this->datatables->add_column('action', '$1', 'reversing_approval(documentID,documentApprovedID,documentSystemCode)');*/

        /*$this->datatables->select("documentApprovedID,documentCode,approvedComments,documentDate,approvedTB.approvedTB.documentID,empName,documentSystemCode,approvedDate");
        $this->datatables->from("(SELECT approvedTB.documentApprovedID as documentApprovedID, documentCode, approvedComments, documentDate, approvedDate, approvedTB.documentID, Ename2 AS empName,
                                 approvedTB.documentSystemCode as documentSystemCode
                                 FROM srp_erp_documentapproved AS approvedTB
                                 JOIN srp_employeesdetails ON approvedTB.approvedEmpID = srp_employeesdetails.EIdNo
                                 JOIN (
                                     SELECT MAX(approvalLevelID) as MaxLevel, srp_erp_documentapproved.documentSystemCode,srp_erp_documentapproved.documentID
                                     FROM srp_erp_documentapproved WHERE companyID = {$companyID}  AND approvedYN = 1 group by documentSystemCode,documentID
                                 ) AS maxLevelTB ON approvedTB.documentSystemCode=maxLevelTB.documentSystemCode AND approvedTB.approvalLevelID=maxLevelTB.MaxLevel AND approvedTB.documentID=maxLevelTB.documentID
                                 WHERE approvedTB.documentID IN({$documentID_filter})
                                 AND {$where}
                                 GROUP BY approvedTB.documentID, documentSystemCode ) AS dataTable ");
        $this->datatables->add_column('employee', '$1', 'Ename2');
        $this->datatables->add_column('action', '$1', 'reversing_approval(documentID,documentApprovedID,documentSystemCode)');*/
        //$search = $_POST["sSearch"];


        $this->datatables->select("documentApprovedID,documentCode,approvedComments,documentDate,documentID,empName,documentSystemCode,approvedDate");
        $this->datatables->from("srp_erp_documentapproved AS t1");
        $this->datatables->join("(SELECT approvedTB.documentApprovedID as appID, srp_employeesdetails.Ename2 AS empName
                                 FROM srp_erp_documentapproved AS approvedTB
                                 JOIN srp_employeesdetails ON approvedTB.approvedEmpID = srp_employeesdetails.EIdNo
                                 JOIN (
                                     SELECT MAX(approvalLevelID) as MaxLevel, srp_erp_documentapproved.documentSystemCode,srp_erp_documentapproved.documentID
                                     FROM srp_erp_documentapproved WHERE companyID = {$companyID}  AND approvedYN = 1 group by documentSystemCode,documentID
                                 ) AS maxLevelTB ON approvedTB.documentSystemCode=maxLevelTB.documentSystemCode AND approvedTB.approvalLevelID=maxLevelTB.MaxLevel AND approvedTB.documentID=maxLevelTB.documentID
                                 WHERE approvedTB.documentID IN({$documentID_filter})
                                 AND {$where}
                                 GROUP BY approvedTB.documentID, approvedTB.documentSystemCode ) AS dataTable ", "t1.documentApprovedID=dataTable.appID");
        /*if($search) {
            $this->datatables->like('t1.documentCode', $search);
        }*/
        $this->datatables->add_column('employee', '$1', 'Ename2');
        $this->datatables->add_column('action', '$1', 'reversing_approval(documentID,documentApprovedID,documentSystemCode)');
        $this->datatables->group_by('t1.documentApprovedID');
        echo $this->datatables->generate();
    }

    function reversing_approval_document()
    {
        $this->form_validation->set_rules('auto_id', 'auto_id', 'trim|required');
        $this->form_validation->set_rules('comments', 'comments', 'trim|required');
        $this->form_validation->set_rules('document_id', 'document_id', 'trim|required');
        $this->form_validation->set_rules('document_code', 'document_code', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {

            $document_code = $this->input->post('document_code');
            $HR_documentCodes = array('SP', 'SD', 'SPN');
            if (in_array($document_code, $HR_documentCodes)) {
                $data = $this->Reversing_modal->reversing_approval_HRDocument();
            } else {
                $data = $this->Reversing_modal->reversing_approval_document();
            }

            echo json_encode($data);
        }
    }
}