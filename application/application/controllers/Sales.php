<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_modal');
        $this->load->model('Report_model');
    }

    function fetch_sales_commission()
    {
        $convertFormat = convert_date_format_sql();
        // $date_format_policy = date_format_policy();
        // $datefrom = $this->input->post('datefrom');
        // $datefromconvert = input_format_date($datefrom, $date_format_policy);
        // $dateto = $this->input->post('dateto');
        // $datetoconvert = input_format_date($dateto, $date_format_policy);
        $companyid = $this->common_data['company_data']['company_id'];
        // $supplier = $this->input->post('supplierPrimaryCode');
        // $status = $this->input->post('status');
        // $supplier_filter = '';
        // if (!empty($supplier)) {
        //     $supplier = array($this->input->post('supplierPrimaryCode'));
        //     $whereIN = "( " . join("' , '", $supplier) . " )";
        //     $supplier_filter = " AND supplierID IN " . $whereIN;
        // }
        // $date = "";
        // if (!empty($datefrom) && !empty($dateto)) {
        //     $date .= " AND ( grvDate >= '" . $datefromconvert . " 00:00:00' AND grvDate <= '" . $datetoconvert . " 23:59:00')";
        // }
        // $status_filter = "";
        // if ($status != 'all') {
        //     if ($status == 1) {
        //         $status_filter = " AND ( confirmedYN = 0 AND approvedYN = 0)";
        //     } else if ($status == 2) {
        //         $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 0)";
        //     } else {
        //         $status_filter = " AND ( confirmedYN = 1 AND approvedYN = 1)";
        //     }
        // }
        // $where = "companyID = " . $companyid . $supplier_filter . $date . $status_filter . "";
        $this->datatables->select("srp_erp_salescommisionmaster.salesCommisionID as salesCommisionID,srp_erp_salescommisionmaster.confirmedByEmpID as confirmedByEmp ,transactionAmount, srp_erp_salescommisionmaster.salesCommisionID,DATE_FORMAT(asOfDate,' $convertFormat ') AS asOfDate ,Description, transactionCurrencyDecimalPlaces, transactionCurrency,confirmedYN,approvedYN,salesCommisionCode,createdUserID,isDeleted");
        $this->datatables->where('srp_erp_salescommisionmaster.companyID', $companyid);
        $this->datatables->from('srp_erp_salescommisionmaster');
        $this->datatables->join('(SELECT SUM(netCommision) as transactionAmount,salesCommisionID FROM srp_erp_salescommisionperson GROUP BY salesCommisionID) det', '(det.salesCommisionID = srp_erp_salescommisionmaster.salesCommisionID)', 'left');

        $this->datatables->add_column('detail', '$1', 'Description');
        $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(transactionAmount,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', '$1', 'confirm_user_approval_drilldown(confirmedYN,"SC",salesCommisionID)');
        $this->datatables->add_column('approved', '$1', 'confirm_ap_user(approvedYN,confirmedYN,"SC",salesCommisionID)');
        $this->datatables->add_column('edit', '$1', 'load_sc_action(salesCommisionID,confirmedYN,approvedYN,createdUserID,isDeleted,confirmedByEmp)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function fetch_sc_approval()
    {
        /** rejected = 1* not rejected = 0* */

        $convertFormat = convert_date_format_sql();
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $this->datatables->select('srp_erp_salescommisionmaster.salesCommisionID as salesCommisionID ,det2.transactionAmount as  transactionAmount,srp_erp_salescommisionmaster.companyCode,salesCommisionCode,Description,confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,,DATE_FORMAT(asOfDate,\'' . $convertFormat . '\') AS asOfDate,transactionCurrencyDecimalPlaces, transactionCurrency');
        $this->datatables->join('(SELECT SUM(transactionAmount) as transactionAmount,salesCommisionID FROM srp_erp_salescommisiondetail GROUP BY salesCommisionID) det', '(det.salesCommisionID = srp_erp_salescommisionmaster.salesCommisionID)', 'left');
        $this->datatables->join('(SELECT SUM(netCommision) as transactionAmount,salesCommisionID FROM srp_erp_salescommisionperson GROUP BY salesCommisionID) det2', '(det2.salesCommisionID = srp_erp_salescommisionmaster.salesCommisionID)', 'left');
        $this->datatables->from('srp_erp_salescommisionmaster');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = srp_erp_salescommisionmaster.salesCommisionID AND srp_erp_documentapproved.approvalLevelID = srp_erp_salescommisionmaster.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = srp_erp_salescommisionmaster.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'SC');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'SC');
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->where('srp_erp_salescommisionmaster.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(transactionAmount,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('salesCommisionCode', '$1', 'approval_change_modal(salesCommisionCode,salesCommisionID,documentApprovedID,approvalLevelID,approvedYN,SC,0)');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "SC", salesCommisionID)');
        $this->datatables->add_column('edit', '$1', 'sc_action_approval(salesCommisionID,approvalLevelID,approvedYN,documentApprovedID,0)');

        echo $this->datatables->generate();
    }

    function save_sales_commision_header()
    {
        $date_format_policy = date_format_policy();
        $date = $this->input->post('asOfDate');
        $asOfDate = input_format_date($date, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $this->form_validation->set_rules('salesPersonID[]', 'sales Person', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        //$this->form_validation->set_rules('narration', 'Narration', 'trim|required');
        if($financeyearperiodYN==1) {
            $this->form_validation->set_rules('financeyear', 'Finance Year', 'trim|required');
            $this->form_validation->set_rules('financeyear_period', 'Finance Year Period', 'trim|required');
        }
        $this->form_validation->set_rules('asOfDate', 'As Of Date', 'trim|required|validate_date');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'message' => validation_errors()));
        } else {
            if($financeyearperiodYN==1) {
                $financearray = $this->input->post('financeyear_period');
                $financePeriod = fetchFinancePeriod($financearray);
                if ($asOfDate >= $financePeriod['dateFrom'] && $asOfDate <= $financePeriod['dateTo']) {
                    echo json_encode($this->Sales_modal->save_sales_commision_header());
                } else {
                    echo json_encode(array('status' => 0, 'type' => 'e', 'message' => 'As Of Date not between Financial period !'));
                }
            }else{
                echo json_encode($this->Sales_modal->save_sales_commision_header());
            }
        }
    }

    function save_sales_target()
    {
        /* $this->form_validation->set_rules('datefrom', 'date from', 'trim|required|validate_date');
         $this->form_validation->set_rules('dateTo', 'date To', 'trim|required|validate_date');*/
        $this->form_validation->set_rules('fromTargetAmount', 'Form Amount', 'trim|required');
        $this->form_validation->set_rules('toTargetAmount', 'To Amount', 'trim|required');
        $this->form_validation->set_rules('percentage', 'Commision Percentage', 'trim|required|less_than[100]');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'message' => validation_errors()));
        } else {
            $fromTargetAmount = $this->input->post('fromTargetAmount');
            $toTargetAmount = $this->input->post('toTargetAmount');
            if ($fromTargetAmount < $toTargetAmount) {
                $datefrom = strtotime($this->input->post('datefrom'));
                $dateTo = strtotime($this->input->post('dateTo'));
                if ($datefrom <= $dateTo) {
                    echo json_encode($this->Sales_modal->save_sales_target());
                } else {
                    echo json_encode(array('status' => 0, 'type' => 'e', 'message' => 'Date from value cannot be greater than Date to value !'));
                }
            } else {
                echo json_encode(array('status' => 0, 'type' => 'e', 'message' => 'From Amount cannot be greater than To amount !'));
            }
        }
    }

    function laad_sales_commision_header()
    {
        echo json_encode($this->Sales_modal->laad_sales_commision_header());
    }

    function fetch_detail_header_lock()
    {
        echo json_encode($this->Sales_modal->fetch_detail_header_lock());
    }

    function load_sc_conformation()
    {
        $salesCommisionID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('salesCommisionID'));
        $data['extra'] = $this->Sales_modal->fetch_template_data($salesCommisionID);
        $data['approval'] = $this->input->post('approval');
        if (!$this->input->post('html')) {
            $data['signature'] = $this->Sales_modal->fetch_signaturelevel();
        } else {
            $data['signature'] = '';
        }
        $data['logo']=mPDFImage;
        $data['sales_img']='';
        if($this->input->post('html')){
            $data['logo']=htmlImage;
            $data['sales_img']=base_url();
        }
        $html = $this->load->view('system/sales/erp_sc_print', $data, true);


        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function fetch_inv_detail()
    {
        $salesCommisionID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('salesCommisionID'));
        $data['extra'] = $this->Sales_modal->fetch_inv_detail($salesCommisionID);
        echo $this->load->view('system/sales/erp_sc_detail', $data, true);
    }

    function sales_commission_detail()
    {
        echo json_encode($this->Sales_modal->sales_commission_detail());
    }

    function delete_sc()
    {
        echo json_encode($this->Sales_modal->delete_sc());
    }

    function save_sales_person()
    {
        $this->form_validation->set_rules('SalesPersonName', 'Sales Person Name', 'trim|required');
        $this->form_validation->set_rules('salesPersonTargetType', 'Target Type', 'trim|required');
        $this->form_validation->set_rules('receivableAutoID', 'Receivable', 'trim|required');
        $this->form_validation->set_rules('expanseAutoID', 'Expanse', 'trim|required');
        $this->form_validation->set_rules('wareHouseAutoID', 'Ware House', 'trim|required');
        $this->form_validation->set_rules('wareHouseAutoID', 'Location', 'trim|required');
        $this->form_validation->set_rules('salesPersonTarget', 'Target', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'message' => validation_errors()));
        } else {
            echo json_encode($this->Sales_modal->save_sales_person());
        }
    }

    function sc_confirmation()
    {
        echo json_encode($this->Sales_modal->sc_confirmation());
    }

    function save_sc_approval()
    {
        $system_code = trim($this->input->post('salesCommisionID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));

        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'SC', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('salesCommisionID');
                $this->db->where('salesCommisionID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_salescommisionmaster');
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
                        echo json_encode($this->Sales_modal->save_sc_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('salesCommisionID');
            $this->db->where('salesCommisionID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_salescommisionmaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'SC', $level_id);
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
                        echo json_encode($this->Sales_modal->save_sc_approval());
                    }
                }
            }
        }
    }

    function referbacksc()
    {
        $salesCommisionID = $this->input->post('salesCommisionID');
        $this->db->select('approvedYN,salesCommisionCode');
        $this->db->where('salesCommisionID', trim($salesCommisionID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_salescommisionmaster');
        $approved_sales_commisiion_master = $this->db->get()->row_array();
        if (!empty($approved_sales_commisiion_master)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_sales_commisiion_master['salesCommisionCode']));
        }
        else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($salesCommisionID, 'SC');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }

    }

    function re_open_salescommishion()
    {
        echo json_encode($this->Sales_modal->re_open_salescommishion());
    }

    function get_sales_order_report()
    {
        $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $data["details"] = $this->Sales_modal->get_sales_order_report();
            $data["type"] = "html";
            echo $html = $this->load->view('system/sales/ajax/load-sales-order-report', $data, true);
        }
    }

    function get_group_sales_order_report()
    {

        $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {

            $errorHTML = $this->group_unlink(array("CUST"));

            if ($errorHTML) {
                echo warning_message($errorHTML);
            } else {

                $data["details"] = $this->Sales_modal->get_group_sales_order_report();

                $data["type"] = "html";
                echo $html = $this->load->view('system/sales/ajax/load-sales-order-report', $data, true);
            }
        }
    }


    function get_sales_order_drilldown_report()
    {
        $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $data["details"] = $this->Sales_modal->get_sales_order_drilldown_report();
            $data["type"] = "html";
            $data["amountType"] = $this->input->post("type");
            echo $html = $this->load->view('system/sales/ajax/load-sales-order-drilldown-report', $data, true);
        }
    }

    function get_group_sales_order_drilldown_report()
    {
        $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $data["details"] = $this->Sales_modal->get_group_sales_order_drilldown_report();
            $data["type"] = "html";
            $data["amountType"] = $this->input->post("type");
            echo $html = $this->load->view('system/sales/ajax/load-sales-order-drilldown-report', $data, true);
        }
    }

    function get_sales_order_report_pdf()
    {
        $data["details"] = $this->Sales_modal->get_sales_order_report();
        $data["type"] = "pdf";
        $html = $this->load->view('system/sales/ajax/load-sales-order-report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4-L');
    }

    function get_group_sales_order_report_pdf()
    {
        $data["details"] = $this->Sales_modal->get_group_sales_order_report();
        $data["type"] = "pdf";
        $html = $this->load->view('system/sales/ajax/load-sales-order-report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4-L');
    }

    function get_customer_invoice_report()
    {
        $currency = $this->input->post('currency');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        if (empty($datefrom)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date From is required
            </div>';
        } else if (empty($dateto)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date To is required
            </div>';
        } else {
            $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
            $this->form_validation->set_rules('segmentID[]', 'Segment', 'required');
            if ($this->form_validation->run() == FALSE) {
                echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
            } else {
                $data["details"] = $this->Sales_modal->get_customer_invoice_report();
                $data["type"] = "html";
                $data["currency"] = $currency;
                echo $html = $this->load->view('system/sales/ajax/load-customer-invoice-report', $data, true);
            }
        }
    }

    function get_group_customer_invoice_report()
    {
        $currency = $this->input->post('currency');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        if (empty($datefrom)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date From is required
            </div>';
        } else if (empty($dateto)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date To is required
            </div>';
        } else {
            $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
            if ($this->form_validation->run() == FALSE) {
                echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
            } else {
                $errorHTML = $this->group_unlink(array("CUST","SEG"));
                if ($errorHTML) {
                    echo warning_message($errorHTML);
                } else {
                    $data["details"] = $this->Sales_modal->get_group_customer_invoice_report();
                    $data["type"] = "html";
                    $data["currency"] = $currency;
                    echo $html = $this->load->view('system/sales/ajax/load-customer-invoice-report', $data, true);
                }
            }
        }
    }

    function get_customer_invoice_report_pdf()
    {
        $currency = $this->input->post('currency');
        $data["details"] = $this->Sales_modal->get_customer_invoice_report();
        $data["type"] = "pdf";
        $data["currency"] = $currency;
        $html = $this->load->view('system/sales/ajax/load-customer-invoice-report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4-L');
    }

    function get_group_customer_invoice_report_pdf()
    {
        $currency = $this->input->post('currency');
        $data["details"] = $this->Sales_modal->get_group_customer_invoice_report();
        $data["type"] = "pdf";
        $data["currency"] = $currency;
        $html = $this->load->view('system/sales/ajax/load-customer-invoice-report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4-L');
    }

    function get_sales_order_return_drilldown_report()
    {
        echo json_encode($this->Sales_modal->get_sales_order_return_drilldown_report());
    }

    function get_group_sales_order_return_drilldown_report()
    {
        echo json_encode($this->Sales_modal->get_group_sales_order_return_drilldown_report());
    }

    function get_sales_order_credit_drilldown_report()
    {
        echo json_encode($this->Sales_modal->get_sales_order_credit_drilldown_report());
    }

    function get_group_sales_order_credit_drilldown_report()
    {
        echo json_encode($this->Sales_modal->get_group_sales_order_credit_drilldown_report());
    }

    function get_get_revenue_summery_report()
    {
        $currency = $this->input->post('currency');
        $financeyear = $this->input->post('financeyear');
        $this->db->select('beginingDate,endingDate');
        $this->db->where('companyFinanceYearID', $financeyear);
        $this->db->from('srp_erp_companyfinanceyear ');
        $financeyeardtl = $this->db->get()->row_array();
        $beginingDate = $financeyeardtl['beginingDate'];
        $endingDate = $financeyeardtl['endingDate'];

        $start = (new DateTime($beginingDate));
        $end = (new DateTime($endingDate));

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        $datearr = [];
        foreach ($period as $dt) {
            $dat = $dt->format("Y-m");
            $text = $dt->format("Y-M");
            $datearr[$dat] = $text;
        }
        //echo '<pre>';print_r($datearr); echo '</pre>'; die();

        $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
        $this->form_validation->set_rules('segmentID[]', 'Segment', 'required');
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $data["details"] = $this->Sales_modal->get_get_revenue_summery_report($datearr);
            $data["header"] = $datearr;
            $data["type"] = "html";
            $data["currency"] = $currency;
            echo $html = $this->load->view('system/sales/ajax/load-revenue-summary-report', $data, true);
        }
    }

    function get_group_revenue_summery_report()
    {
        $currency = $this->input->post('currency');
        $financeyear = $this->input->post('financeyear');
        $this->db->select('beginingDate,endingDate');
        $this->db->where('companyFinanceYearID', $financeyear);
        $this->db->from('srp_erp_companyfinanceyear ');
        $financeyeardtl = $this->db->get()->row_array();
        $beginingDate = $financeyeardtl['beginingDate'];
        $endingDate = $financeyeardtl['endingDate'];

        $start = (new DateTime($beginingDate));
        $end = (new DateTime($endingDate));

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        $datearr = [];
        foreach ($period as $dt) {
            $dat = $dt->format("Y-m");
            $text = $dt->format("Y-M");
            $datearr[$dat] = $text;
        }
        //echo '<pre>';print_r($datearr); echo '</pre>'; die();

        $this->form_validation->set_rules('customerID[]', 'Customer', 'required');
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $errorHTML = $this->group_unlink(array("CUST","SEG"));
            if ($errorHTML) {
                echo warning_message($errorHTML);
            } else {
                $data["details"] = $this->Sales_modal->get_group_revenue_summery_report($datearr);
                $data["header"] = $datearr;
                $data["type"] = "html";
                $data["currency"] = $currency;
                echo $html = $this->load->view('system/sales/ajax/load-revenue-summary-report', $data, true);
            }
        }
    }


    function get_revanue_details_drilldown_report()
    {
        $currency = $this->input->post('currency');


        $data["details"] = $this->Sales_modal->get_revanue_details_drilldown_report();
        $data["type"] = "html";
        $data["currency"] = $currency;
        echo $html = $this->load->view('system/sales/ajax/load-customer-invoice-summary-report', $data, true);

    }

    function get_group_revanue_details_drilldown_report()
    {
        $currency = $this->input->post('currency');


        $data["details"] = $this->Sales_modal->get_group_revanue_details_drilldown_report();
        $data["type"] = "html";
        $data["currency"] = $currency;
        echo $html = $this->load->view('system/sales/ajax/load-customer-invoice-summary-report', $data, true);

    }

    function get_revenue_summery_report_pdf(){
        $currency = $this->input->post('currency');
        $financeyear = $this->input->post('financeyear');
        $this->db->select('beginingDate,endingDate');
        $this->db->where('companyFinanceYearID', $financeyear);
        $this->db->from('srp_erp_companyfinanceyear ');
        $financeyeardtl = $this->db->get()->row_array();
        $beginingDate = $financeyeardtl['beginingDate'];
        $endingDate = $financeyeardtl['endingDate'];

        $start = (new DateTime($beginingDate));
        $end = (new DateTime($endingDate));

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        $datearr = [];
        foreach ($period as $dt) {
            $dat = $dt->format("Y-m");
            $text = $dt->format("Y-M");
            $datearr[$dat] = $text;
        }

        $data["details"] = $this->Sales_modal->get_get_revenue_summery_report($datearr);
        $data["header"] = $datearr;
        $data["type"] = "pdf";
        $data["currency"] = $currency;
        $html = $this->load->view('system/sales/ajax/load-revenue-summary-report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4-L');
    }

    function group_customer_linked()
    {
        return $this->Report_model->group_customer_linked();
    }

    function group_segment_linked()
    {
        return $this->Report_model->group_segment_linked();
    }

    function group_unlink($report)
    {
        $errorHTML = "";
        if (in_array('CUST', $report)) {
            if ($this->group_customer_linked()) {
                $errorHTML .= "<h4>Please link the following customer</h4>";
                $errorHTML .= "<ul>";
                foreach ($this->group_customer_linked() as $val) {
                    $errorHTML .= "<li>" . $val . "</li>";
                }
                $errorHTML .= "</ul>";
            }
        }

        if (in_array('SEG', $report)) {
            if ($this->group_segment_linked()) {
                $errorHTML .= "<h4>Please link the following segment</h4>";
                $errorHTML .= "<ul>";
                foreach ($this->group_segment_linked() as $val) {
                    $errorHTML .= "<li>" . $val . "</li>";
                }
                $errorHTML .= "</ul>";
            }
        }

        return $errorHTML;
    }
    function get_sales_person_performance_report()
    {
        $currency = $this->input->post('currency');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');



        $this->form_validation->set_rules('salesperson[]', 'Sales Person', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        }
        else if (empty($datefrom)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date From is required
            </div>';
        } else if (empty($dateto)) {
            echo ' <div class="alert alert-warning" role="alert">
                Date To is required
            </div>';
        }
        else {

            $data["details"] = $this->Sales_modal->get_sales_person_performance_report();
            $data["type"] = "html";
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $data["currency"] = $currency;
            echo $html = $this->load->view('system/sales/ajax/load-sales-person-report', $data, true);
        }
    }
    function get_sales_person_performance_report_pdf()
    {
        $currency = $this->input->post('currency'); $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $data["details"] = $this->Sales_modal->get_sales_person_performance_report();
        $data["type"] = "pdf";
        $data["currency"] = $currency;
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data["currency"] = $currency;
        $html = $this->load->view('system/sales/ajax/load-sales-person-report_pdf', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');
    }
    function get_sales_preformance_dd()
    {

        $currency = $this->input->post('currency');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $salesPersonID = $this->input->post('salesPersonID');

        $data["details"] = $this->Sales_modal->get_sales_preformance_dd();
        $data["type"] = "html";
        $data["currency"] = $currency;
        $data["datefrom"] = $datefrom;
        $data["dateto"] = $dateto;
        $data["salesPersonID"] = $salesPersonID;
        echo $html = $this->load->view('system/sales/ajax/load-customer-sales-person-summary-report', $data, true);

        //echo json_encode($this->Sales_modal->get_sales_preformance_dd());
    }

}