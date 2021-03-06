<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grv extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Grv_modal');
        $this->load->helpers('grv');
        $this->load->helpers('exceedmatch');
    }

    function fetch_grv()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $supplier = $this->input->post('supplierPrimaryCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $supplier_filter = '';
        if (!empty($supplier)) {
            $supplier = array($this->input->post('supplierPrimaryCode'));
            $whereIN = "( " . join("' , '", $supplier) . " )";
            $supplier_filter = " AND supplierID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( grvDate >= '" . $datefromconvert . " 00:00:00' AND grvDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $where = "srp_erp_grvmaster.companyID = " . $companyid . $supplier_filter . $date . $status_filter . "";
        $this->datatables->select("srp_erp_grvmaster.grvAutoID as grvAutoID,srp_erp_grvmaster.companyCode,grvPrimaryCode,grvNarration,srp_erp_suppliermaster.supplierName as supliermastername,DATE_FORMAT(deliveredDate,'$convertFormat ') AS deliveredDate,transactionCurrency,grvType,confirmedYN,approvedYN,srp_erp_grvmaster.createdUserID as createdUser,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value_search,transactionCurrencyDecimalPlaces,isDeleted,srp_erp_grvmaster.confirmedByEmpID as confirmedByEmp");
        $this->datatables->join('(SELECT SUM(receivedTotalAmount) as receivedTotalAmount,grvAutoID FROM srp_erp_grvdetails GROUP BY grvAutoID) det', '(det.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(total_amount) as total_amount,grvAutoID FROM srp_erp_grv_addon  GROUP BY grvAutoID) addondet', '(addondet.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->from('srp_erp_grvmaster');
        $this->datatables->join('srp_erp_suppliermaster', 'srp_erp_suppliermaster.supplierAutoID=srp_erp_grvmaster.supplierID', 'left');
        $this->datatables->add_column('grv_detail', '<b>Supplier Name : </b> $2<br><b>Delivered Date : </b> $3<b>&nbsp;&nbsp; Type : </b> $5<br><b>Narration : </b> $1', 'grvNarration,supliermastername,deliveredDate,transactionCurrency,grvType');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"GRV",grvAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"GRV",grvAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_grv_action(grvAutoID,confirmedYN,approvedYN,createdUser,isDeleted,confirmedByEmp)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function fetch_grv_approval()
    {
        /** rejected = 1* not rejected = 0* */
        $convertFormat = convert_date_format_sql();
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $this->datatables->select('srp_erp_grvmaster.grvAutoID as grvAutoID,srp_erp_grvmaster.companyCode,grvPrimaryCode,grvNarration,srp_erp_suppliermaster.supplierName as supplierName,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(deliveredDate,\'' . $convertFormat . '\') AS deliveredDate,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value_search,transactionCurrencyDecimalPlaces,transactionCurrency');
        $this->datatables->join('(SELECT SUM(receivedTotalAmount) as receivedTotalAmount,grvAutoID FROM srp_erp_grvdetails GROUP BY grvAutoID) det', '(det.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(total_amount) as total_amount,grvAutoID FROM srp_erp_grv_addon  GROUP BY grvAutoID) addondet', '(addondet.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->from('srp_erp_grvmaster');
        $this->datatables->join('srp_erp_suppliermaster', 'srp_erp_suppliermaster.supplierAutoID=srp_erp_grvmaster.supplierID', 'left');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_grvmaster.grvAutoID AND srp_erp_documentapproved.approvalLevelID = srp_erp_grvmaster.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_grvmaster.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'GRV');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'GRV');
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->where('srp_erp_grvmaster.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('grvPrimaryCode', '$1', 'approval_change_modal(grvPrimaryCode,grvAutoID,documentApprovedID,approvalLevelID,approvedYN,GRV,0)');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "GRV", grvAutoID)');
        $this->datatables->add_column('edit', '$1', 'grv_action_approval(grvAutoID,approvalLevelID,approvedYN,documentApprovedID,0)');

        echo $this->datatables->generate();
    }

    function fetch_grv_approval_buyback()
    {
        /** rejected = 1* not rejected = 0* */
        $convertFormat = convert_date_format_sql();
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $this->datatables->select('srp_erp_grvmaster.grvAutoID as grvAutoID,srp_erp_grvmaster.companyCode,grvPrimaryCode,grvNarration,srp_erp_suppliermaster.supplierName as supplierName,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(deliveredDate,\'' . $convertFormat . '\') AS deliveredDate,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value_search,transactionCurrencyDecimalPlaces,transactionCurrency');
        $this->datatables->join('(SELECT SUM(receivedTotalAmount) as receivedTotalAmount,grvAutoID FROM srp_erp_grvdetails GROUP BY grvAutoID) det', '(det.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(total_amount) as total_amount,grvAutoID FROM srp_erp_grv_addon  GROUP BY grvAutoID) addondet', '(addondet.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->from('srp_erp_grvmaster');
        $this->datatables->join('srp_erp_suppliermaster', 'srp_erp_suppliermaster.supplierAutoID=srp_erp_grvmaster.supplierID', 'left');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_grvmaster.grvAutoID AND srp_erp_documentapproved.approvalLevelID = srp_erp_grvmaster.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_grvmaster.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'GRV');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'GRV');
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->where('srp_erp_grvmaster.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('grvPrimaryCode', '$1', 'approval_change_modal_buyback(grvPrimaryCode,grvAutoID,documentApprovedID,approvalLevelID,approvedYN,GRV,0,buy)');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "GRV", grvAutoID)');
        $this->datatables->add_column('edit', '$1', 'grv_action_approval_buyback(grvAutoID,approvalLevelID,approvedYN,documentApprovedID,0)');

        echo $this->datatables->generate();
    }

    function fetch_addon_data()
    {
        $this->datatables->select('category_id,description')
            ->from('srp_erp_addon_category')
            ->where('companyID', $this->common_data['company_data']['company_id'])
            ->edit_column('action', '<span class="pull-right"><a onclick="openaddoneditmodel($1)"><span title="Edit" class="glyphicon glyphicon-pencil" rel="tooltip"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="deleteaddonmaster($1)" ><span title="Delete" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"  rel="tooltip"></span></a></span>', 'category_id');
        echo $this->datatables->generate();
    }

    function save_grv_header()
    {
        $date_format_policy = date_format_policy();
        $gvDte = $this->input->post('grvDate');
        $grvDate = input_format_date($gvDte, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $deeredDte = $this->input->post('deliveredDate');
        $deliveredDate = input_format_date($deeredDte, $date_format_policy);

        $this->form_validation->set_rules('grvType', 'GRV Type', 'trim|required');
        $this->form_validation->set_rules('supplierID', 'Supplier', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Supplier Currency', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('grvDate', 'Delivered Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('location', 'Delivery Location', 'trim|required');
        $this->form_validation->set_rules('deliveredDate', 'Delivery Location', 'trim|required|validate_date');
        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        }


        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            if($financeyearperiodYN==1) {
                $financearray = $this->input->post('financeyear_period');
                $financePeriod = fetchFinancePeriod($financearray);
                if ($grvDate >= $financePeriod['dateFrom'] && $grvDate <= $financePeriod['dateTo']) {

                    if ($deliveredDate < $grvDate) {
                        $this->session->set_flashdata('e', 'Delivered Date cannot be less than GRV date !');
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Grv_modal->save_grv_header());
                    }

                } else {
                    $this->session->set_flashdata('e', 'GRV Date not between Financial period !');
                    echo json_encode(FALSE);
                }
            }else{
                if ($deliveredDate < $grvDate) {
                    $this->session->set_flashdata('e', 'Delivered Date cannot be less than GRV date !');
                    echo json_encode(FALSE);
                } else {
                    echo json_encode($this->Grv_modal->save_grv_header());
                }
            }
        }
    }

    function save_addon()
    {
        $projectExist = project_is_exist();
        $isChargeToExpense = $this->input->post('isChargeToExpense');
        //$this->form_validation->set_rules('uom', 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules('bookingCurrencyID', 'Booking Currency', 'trim|required');
        $this->form_validation->set_rules('total_amount', 'Total Amount', 'trim|required');
        $this->form_validation->set_rules('addonCatagory', 'Addon Catagory', 'trim|required');
        if ($isChargeToExpense == 1) {
            $this->form_validation->set_rules('GLAutoID', 'GL Code', 'trim|required');
        }
        if ($projectExist == 1) {
            $this->form_validation->set_rules('projectID', 'Project', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Grv_modal->save_addon());
        }
    }

    function delete_grv()
    {
        echo json_encode($this->Grv_modal->delete_grv());
    }

    function fetch_po_detail_table()
    {
        echo json_encode($this->Grv_modal->fetch_po_detail_table());
    }

    function fetch_addons()
    {
        echo json_encode($this->Grv_modal->fetch_addons());
    }

    function delete_grv_detail()
    {
        echo json_encode($this->Grv_modal->delete_grv_detail());
    }

    function fetch_grv_detail()
    {
        echo json_encode($this->Grv_modal->fetch_grv_detail());
    }

    function load_grv_header()
    {
        echo json_encode($this->Grv_modal->load_grv_header());
    }

    function save_po_base_items()
    {
        echo json_encode($this->Grv_modal->save_po_base_items());
    }

    function fetch_detail()
    {
        $data['master'] = $this->Grv_modal->load_grv_header();
        if ($this->input->post('grvType') == 'PO Base') {
            $data['supplier_po'] = $this->Grv_modal->fetch_supplier_po($data['master']);
        }
        $data['grvAutoID'] = trim($this->input->post('grvAutoID'));
        $data['grvType'] = trim($this->input->post('grvType'));
        $data['supplierID'] = trim($this->input->post('supplierID'));
        $data['detail'] = $this->Grv_modal->fetch_detail();

        $this->load->view('system/grv/fetch_detail', $data);
    }

    function fetch_detail_header_lock()
    {

        echo json_encode($this->Grv_modal->fetch_detail());
    }

    function save_grv_detail()
    {
        $projectExist = project_is_exist();
        $this->form_validation->set_rules('search', 'Item', 'trim|required');
        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        $this->form_validation->set_rules('UnitOfMeasureID', 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules('quantityRequested', 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules('estimatedAmount', 'Estimated Amount', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Grv_modal->save_grv_detail());
        }
    }

    function save_grv_st_bulk_detail()
    {
        $searches = $this->input->post('search');
        $projectExist = project_is_exist();
        foreach ($searches as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Amount', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Grv_modal->save_grv_st_bulk_detail());
        }
    }

    function load_grv_conformation()
    {
        $grvAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('grvAutoID'));
        $data['extra'] = $this->Grv_modal->fetch_template_data($grvAutoID);
        $data['approval'] = $this->input->post('approval');

        if (!$this->input->post('html')) {
            $data['signature'] = $this->Grv_modal->fetch_signaturelevel();
        } else {
            $data['signature'] = '';
        }
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        if ($this->input->post('html')) {
            $html = $this->load->view('system/grv/erp_grv_print', $data, true);
            echo $html;
        } else {
            $printlink = print_template_pdf('GRV','system/grv/erp_grv_print');
            $papersize = print_template_paper_size('GRV','A4-L');
            $pdfp = $this->load->view($printlink, $data, true);
            /*$html = $this->load->view('system/grv/erp_grv_print', $data, true);*/
            $this->load->library('pdf');
          /* echo '<pre>';print_r($papersize); echo '</pre>'; die();*/
            $pdf = $this->pdf->printed($pdfp, $papersize,$data['extra']['master']['approvedYN']);
            /*$pdf = $this->pdf->printed_mc($pdfp,$papersize,$data['extra']['master']['approvedYN'],1);*/
        }
    }


    function load_grv_conformation_buyback()
    {
        $grvAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('grvAutoID'));
        $data['extra'] = $this->Grv_modal->fetch_template_data($grvAutoID);
        $data['approval'] = $this->input->post('approval');

        if (!$this->input->post('html')) {
            $data['signature'] = $this->Grv_modal->fetch_signaturelevel();
        } else {
            $data['signature'] = '';
        }
        if ($this->input->post('html')) {
            $html = $this->load->view('system/grv/erp_grv_print_buyback', $data, true);
            echo $html;
        } else {
            $printlink = print_template_pdf('GRV','system/grv/erp_grv_print_buyback');
            $papersize = print_template_paper_size('GRV','A4-L');
            $pdfp = $this->load->view($printlink, $data, true);
            /*$html = $this->load->view('system/grv/erp_grv_print', $data, true);*/
            $this->load->library('pdf');
            /* echo '<pre>';print_r($papersize); echo '</pre>'; die();*/
            $pdf = $this->pdf->printed($pdfp, $papersize,$data['extra']['master']['approvedYN']);
            /*$pdf = $this->pdf->printed_mc($pdfp,$papersize,$data['extra']['master']['approvedYN'],1);*/
        }
    }

    function save_grv_approval()
    {
        $system_code = trim($this->input->post('grvAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'GRV', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('grvAutoID');
                $this->db->where('grvAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_grvmaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Grv_modal->save_grv_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('grvAutoID');
            $this->db->where('grvAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_grvmaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'GRV', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Grv_modal->save_grv_approval());
                    }
                }
            }
        }
    }
    function save_grv_approval_buyback()
    {
        $system_code = trim($this->input->post('grvAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'GRV', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('grvAutoID');
                $this->db->where('grvAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_grvmaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Grv_modal->save_grv_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('grvAutoID');
            $this->db->where('grvAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_grvmaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'GRV', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Grv_modal->save_grv_approval());
                    }
                }
            }
        }
    }

    function grv_confirmation()
    {
        echo json_encode($this->Grv_modal->grv_confirmation());
    }

    function save_addonmaster()
    {
        $this->form_validation->set_rules('description', 'Addon Catagory', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Grv_modal->save_addonmaster());
        }
    }

    function edit_addonmaster()
    {
        if ($this->input->post('id') != "") {
            echo json_encode($this->Grv_modal->get_addonmaster());
        } else {
            echo json_encode(FALSE);
        }
    }

    function delete_addonmaster()
    {
        echo json_encode($this->Grv_modal->delete_addonmaster());
    }

    function get_addon_details()
    {
        echo json_encode($this->Grv_modal->get_addon_details());
    }

    function get_addon_details_projectBase()
    {
        echo json_encode($this->Grv_modal->get_addon_details_projectBase());
    }

    function delete_addondetails()
    {
        echo json_encode($this->Grv_modal->delete_addondetails());
    }

    function referback_grv()
    {

        $grvAutoID = $this->input->post('grvAutoID');

        $this->db->select('approvedYN,grvPrimaryCode');
        $this->db->where('grvAutoID', trim($grvAutoID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_grvmaster');
        $approved_inventory_grv = $this->db->get()->row_array();
        if (!empty($approved_inventory_grv)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_inventory_grv['grvPrimaryCode']));
        } else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($grvAutoID, 'GRV');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }

        }

    }

    function fetch_all_item()
    {
        echo json_encode($this->Grv_modal->fetch_detail());
    }

    //created by MS on 21-April-2017 to retrive data to sub item master
    function load_itemMasterSub()
    {
        $grvDetailsID = $this->input->post('grvDetailsID');
        $documentID = $this->input->post('documentID');

        $output = $this->Grv_modal->load_itemMasterSub_tmp($grvDetailsID, $documentID);
        $data['attributes'] = fetch_company_assigned_attributes();
        $data['grvDetailsID'] = $grvDetailsID;
        $data['documentID'] = $documentID;
        $data['itemMasterSubTemp'] = $output;


        $this->load->view('system/grv/sub-views/ajax-load-sub-item-master-tmp', $data);
    }

    function saveSubItemMasterTmp()
    {

        /** convert date according to policy */
        $policyDate = date_format_policy();

        /** post variables */
        $description = $this->input->post('description');
        $batchNos = $this->input->post('batchNo');
        $productReferenceNos = $this->input->post('productReferenceNo');
        $customerInvoiceDate = $this->input->post('customerInvoiceDate');

        $data = array();
        if (!empty($description)) {
            $i = 0;
            foreach ($description as $key => $val) {
                $data[$i]['subItemAutoID'] = $key;
                $data[$i]['description'] = $val;
                $data[$i]['productReferenceNo'] = $productReferenceNos[$key];
                $data[$i]['batchno'] = $batchNos[$key];


                $date = $customerInvoiceDate[$key];

                if (!empty($date)) {
                    $finalDate = input_format_date($date, $policyDate);
                } else {
                    $finalDate = null;
                }
                $data[$i]['expiryDate'] = $finalDate;
                $i++;
            }


        }

        if (!empty($data)) {
            $this->Grv_modal->update_batch_srp_erp_itemmaster_subtemp($data);
        }
        echo json_encode(array('error' => 0, 'message' => 'done'));
    }

    function load_itemMasterSub_approval()
    {
        $receivedDocumentID = trim($this->input->post('receivedDocumentID'));
        $grvAutoID = trim($this->input->post('grvAutoID'));

        $output = $this->Grv_modal->load_itemMasterSub_approval($grvAutoID, $receivedDocumentID);

        $data['output'] = $output;
        $data['receivedDocumentID'] = $receivedDocumentID;

        $this->load->view('system/grv/sub-views/ajax-load-item-master-sub-approval', $data);
    }

    function load_itemMasterSub_approval_buyback()
    {
        $receivedDocumentID = trim($this->input->post('receivedDocumentID'));
        $grvAutoID = trim($this->input->post('grvAutoID'));

        $output = $this->Grv_modal->load_itemMasterSub_approval($grvAutoID, $receivedDocumentID);

        $data['output'] = $output;
        $data['receivedDocumentID'] = $receivedDocumentID;

        $this->load->view('system/grv/sub-views/ajax-load-item-master-sub-approval_buyback', $data);
    }

    function re_open_grv()
    {
        echo json_encode($this->Grv_modal->re_open_grv());
    }

    function saveSubItemMasterTmpDynamic()
    {
        $attributes = fetch_company_assigned_attributes();
        foreach ($attributes as $val) {
            if ($val['isMandatory'] == 1) {
                foreach ($this->input->post($val['columnName'] . '[]') as $value) {
                    if (empty($value)) {
                        echo json_encode(array('e', $val['attributeDescription'] . ' is Required'));
                        exit;
                    }
                }
            }
        }
        echo json_encode($this->Grv_modal->saveSubItemMasterTmpDynamic());
    }
    function fetch_grv_buyback()
    {
        $convertFormat = convert_date_format_sql();

        $date_format_policy = date_format_policy();
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $dateto = $this->input->post('dateto');
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $companyid = $this->common_data['company_data']['company_id'];
        $supplier = $this->input->post('supplierPrimaryCode');
        //$datefrom = $this->input->post('datefrom');
        //$dateto = $this->input->post('dateto');
        $status = $this->input->post('status');
        $supplier_filter = '';
        if (!empty($supplier)) {
            $supplier = array($this->input->post('supplierPrimaryCode'));
            $whereIN = "( " . join("' , '", $supplier) . " )";
            $supplier_filter = " AND supplierID IN " . $whereIN;
        }
        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( grvDate >= '" . $datefromconvert . " 00:00:00' AND grvDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $status_filter = "";
        if ($status != 'all') {
            if ($status == 1) {
                $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
            } else if ($status == 2) {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
            } else {
                $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
            }
        }
        $where = "srp_erp_grvmaster.companyID = " . $companyid . $supplier_filter . $date . $status_filter . "";
        $this->datatables->select("srp_erp_grvmaster.grvAutoID as grvAutoID,srp_erp_grvmaster.companyCode,grvPrimaryCode,grvNarration,srp_erp_suppliermaster.supplierName as supliermastername,DATE_FORMAT(deliveredDate,'$convertFormat ') AS deliveredDate,transactionCurrency,grvType,confirmedYN,approvedYN,srp_erp_grvmaster.createdUserID as createdUser,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value,(IFNULL(det.receivedTotalAmount,0)+IFNULL(addondet.total_amount,0)) as total_value_search,transactionCurrencyDecimalPlaces,isDeleted");
        $this->datatables->join('(SELECT SUM(receivedTotalAmount) as receivedTotalAmount,grvAutoID FROM srp_erp_grvdetails GROUP BY grvAutoID) det', '(det.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->join('(SELECT SUM(total_amount) as total_amount,grvAutoID FROM srp_erp_grv_addon  GROUP BY grvAutoID) addondet', '(addondet.grvAutoID = srp_erp_grvmaster.grvAutoID)', 'left');
        $this->datatables->where($where);
        $this->datatables->from('srp_erp_grvmaster');
        $this->datatables->join('srp_erp_suppliermaster', 'srp_erp_suppliermaster.supplierAutoID=srp_erp_grvmaster.supplierID', 'left');
        $this->datatables->add_column('grv_detail', '<b>Supplier Name : </b> $2<br><b>Delivered Date : </b> $3<b>&nbsp;&nbsp; Type : </b> $5<br><b>Narration : </b> $1', 'grvNarration,supliermastername,deliveredDate,transactionCurrency,grvType');
        $this->datatables->edit_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"GRV",grvAutoID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"GRV",grvAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_grv_action_buyback(grvAutoID,confirmedYN,approvedYN,createdUser,isDeleted)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }
    function delete_grv_buyback()
    {
        echo json_encode($this->Grv_modal->delete_grv_buyback());
    }
    function referback_grv_buyback()
    {

        $grvAutoID = $this->input->post('grvAutoID');

        $this->db->select('approvedYN,grvPrimaryCode');
        $this->db->where('grvAutoID', trim($grvAutoID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_grvmaster');
        $approved_inventory_grv = $this->db->get()->row_array();
        if (!empty($approved_inventory_grv)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_inventory_grv['grvPrimaryCode']));
        } else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($grvAutoID, 'GRV');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }

        }

    }
    function re_open_grv_buyback()
    {
        echo json_encode($this->Grv_modal->re_open_grv_buyback());
    }
    function save_addonmaster_buyback()
    {
        $this->form_validation->set_rules('addonCatagory', 'Addon Catagory', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Grv_modal->save_addonmaster());
        }
    }
    function save_grv_header_buyback()
    {
        $date_format_policy = date_format_policy();
        $gvDte = $this->input->post('grvDate');
        $grvDate = input_format_date($gvDte, $date_format_policy);

        $deeredDte = $this->input->post('deliveredDate');
        $deliveredDate = input_format_date($deeredDte, $date_format_policy);

        $this->form_validation->set_rules('grvType', 'GRV Type', 'trim|required');
        $this->form_validation->set_rules('supplierID', 'Supplier', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Supplier Currency', 'trim|required');
        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('grvDate', 'Delivered Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('location', 'Delivery Location', 'trim|required');
        $this->form_validation->set_rules('deliveredDate', 'Delivery Location', 'trim|required|validate_date');
        $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($grvDate >= $financePeriod['dateFrom'] && $grvDate <= $financePeriod['dateTo']) {

                if ($deliveredDate < $grvDate) {
                    $this->session->set_flashdata('e', 'Delivered Date cannot be less than GRV date !');
                    echo json_encode(FALSE);
                } else {
                    echo json_encode($this->Grv_modal->save_grv_header_buyback());
                }

            } else {
                $this->session->set_flashdata('e', 'GRV Date not between Financial period !');
                echo json_encode(FALSE);
            }
        }
    }
    function fetch_all_item_buyback()
    {
        echo json_encode($this->Grv_modal->fetch_detail_buyback());
    }
    function fetch_detail_buyback()
    {
        $data['master'] = $this->Grv_modal->load_grv_header();
        if ($this->input->post('grvType') == 'PO Base') {
            $data['supplier_po'] = $this->Grv_modal->fetch_supplier_po($data['master']);
        }
        $data['grvAutoID'] = trim($this->input->post('grvAutoID'));
        $data['grvType'] = trim($this->input->post('grvType'));
        $data['supplierID'] = trim($this->input->post('supplierID'));
        $data['detail'] = $this->Grv_modal->fetch_detail();

        $this->load->view('system/grv/fetch_detail_buyback', $data);
    }
    function save_grv_st_bulk_detail_buyback()
    {
        $searches = $this->input->post('search');
        $projectExist = project_is_exist();
        foreach ($searches as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Amount', 'trim|required');

            $this->form_validation->set_rules("noOfItems[{$key}]", 'No  Of Items', 'trim|required');
            $this->form_validation->set_rules("grossQty[{$key}]", 'Gross Qty', 'trim|required');
            $this->form_validation->set_rules("noOfUnits[{$key}]", 'No Of Buckets', 'trim|required');
            $this->form_validation->set_rules("deduction[{$key}]", 'B Weight', 'trim|required');
            if ($projectExist == 1) {
                $this->form_validation->set_rules("projectID[{$key}]", 'Project', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Grv_modal->save_grv_st_bulk_detail_buyback());
        }
    }
    function save_grv_detail_buyback()
    {
        $projectExist = project_is_exist();
        $this->form_validation->set_rules('search', 'Item', 'trim|required');
        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        $this->form_validation->set_rules('UnitOfMeasureID', 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules('quantityRequested', 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules('estimatedAmount', 'Estimated Amount', 'trim|required');
        $this->form_validation->set_rules('noOfItems', 'No Of Items', 'trim|required');
        $this->form_validation->set_rules('grossQty', 'Gross Qty', 'trim|required');
        $this->form_validation->set_rules('noOfUnits', 'No Of Buckets', 'trim|required');
        $this->form_validation->set_rules('deductionedit[]', 'B Weight', 'trim|required');
        if ($projectExist == 1) {
            $this->form_validation->set_rules("projectID", 'Project', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Grv_modal->save_grv_detail_buyback());
        }
    }



}