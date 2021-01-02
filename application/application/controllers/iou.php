<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class iou extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('iou_model');
        $this->load->helper('iou_helper');
        $this->load->helpers('payable');
        $this->load->model('Payment_voucher_model');
        $this->load->model('Receipt_voucher_model');
    }

    function load_iou_voucher_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $date_format_policy = date_format_policy();
        $text = trim($this->input->post('q'));
        $status = trim($this->input->post('staus'));
        $dateto = $this->input->post('dateto');
        $employeesearch = explode('|', trim($this->input->post('employee')));
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $emp = trim($this->input->post('emptype'));

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((iou.empName Like '%" . $text . "%') OR (iouCode Like '%" . $text . "%')  OR (iou.narration Like '%" . $text . "%'))";
        }

        $filter_status = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND iou.confirmedYN = 0 AND iou.approvedYN = 0";
            } else if ($status == 2) {
                $filter_status = " AND iou.confirmedYN = 1 AND iou.approvedYN = 0";
            } elseif ($status == 3) {
                $filter_status = " AND iou.confirmedYN = 1 AND iou.approvedYN = 1";
            }

        }

        $employee = "";
        if (isset($employeesearch[0]) && !empty($employeesearch[0])) {
            $employee = " AND iou.empID = {$employeesearch[0]} AND iou.userType = {$employeesearch[1]}";
        }

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( voucherDate >= '" . $datefromconvert . " 00:00:00' AND voucherDate <= '" . $datetoconvert . " 23:59:00')";
        }


        $where_admin = "WHERE companyID = " . $companyid . $search_string . $filter_status . $employee . $date;

        $data['master'] = $this->db->query("SELECT iou.voucherAutoID,iou.paymentVoucherAutoID as paymentVoucherAutoID,bookdet.ioubookingexpence as bookingamount,iou.closedYN as closedYN,	IFNULL(	det.transactionamount - bookdet.ioubookingexpence, 0 ) AS expamt,iou.userType,iou.createdUserID,iou.isDeleted,iou.empID,iou.empName as empNameiou,det.transactionamount,currency.CurrencyCode,voucherDate,iouCode,employees.Ename2 as employeename,iou.narration as narration,iou.confirmedYN ,iou.approvedYN,iou.transactionCurrencyDecimalPlaces FROM srp_erp_iouvouchers iou LEFT JOIN srp_employeesdetails employees on employees.EIdNo = iou.empID LEFT JOIN srp_erp_currencymaster currency on currency.currencyID = iou.transactionCurrencyID LEFT Join ( SELECT sum( transactionAmount ) AS ioubookingexpence, iouVoucherAutoID FROM srp_erp_ioubookingdetails GROUP BY iouVoucherAutoID ) bookdet ON bookdet.iouVoucherAutoID = iou.voucherAutoID  LEFT JOIN(Select sum(transactionAmount) as transactionamount,voucherAutoID from srp_erp_iouvoucherdetails GROUP BY voucherAutoID)det on iou.voucherAutoID = det.voucherAutoID $where_admin ORDER BY voucherAutoID DESC")->result_array();
        $this->load->view('system/iou/ajax/load_io_voucher_view.php', $data);

    }

    function save_iou_voucher_header()
    {
        $date_format_policy = date_format_policy();
        $documentDate = $this->input->post('voucherdate');
        $paymenttype = $this->input->post('paymentType');
        $formatted_documentDate = input_format_date($documentDate, $date_format_policy);

        $this->form_validation->set_rules('voucherdate', 'Voucher Date', 'trim|required');
        $this->form_validation->set_rules('employeeid', 'Employee', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Transaction Currency', 'trim|required');
        $this->form_validation->set_rules('PVbankCode', 'Bank Or Cash', 'trim|required');
        $this->form_validation->set_rules('financeyear', 'Finance Year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Finance Period', 'trim|required');


        $bank_detail = fetch_gl_account_desc($this->input->post('PVbankCode'));

        if ($bank_detail['isCash'] != 1) {
            {
                $this->form_validation->set_rules('paymentType', 'Payment Type', 'trim|required');
                if ($paymenttype == 1) {
                    $this->form_validation->set_rules('PVchequeDate', 'Cheque Date', 'trim|required');
                    $this->form_validation->set_rules('PVchequeNo', 'Cheque Number', 'trim|required');
                }
            }

        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {

            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($formatted_documentDate >= $financePeriod['dateFrom'] && $formatted_documentDate <= $financePeriod['dateTo']) {
                echo json_encode($this->iou_model->save_iou_voucher_header());
            } else {
                echo json_encode(array('e', 'Voucher Date is not between Financial period !'));
            }


        }
    }

    function load_iou_voucher_detail_items_view()
    {
        $ioumasterid = trim($this->input->post('IOUmasterid'));
        $comapnyid = $this->common_data['company_data']['company_id'];
        $data['detail'] = $this->db->query("select * from srp_erp_iouvoucherdetails where voucherAutoID = $ioumasterid AND companyID = $comapnyid")->result_array();
        $this->load->view('system/iou/ajax/load_iou_voucher_detial', $data);

    }

    function save_iou_voucher_details()
    {
        $description = $this->input->post('description');

        foreach ($description as $key => $val) {
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'Description', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->iou_model->save_iou_voucher_details());
        }
    }

    function delete_iouVoucher_detail()
    {
        echo json_encode($this->iou_model->delete_iouVoucher_detail());
    }

    function load_voucherHeader()
    {
        echo json_encode($this->iou_model->load_voucherHeader());
    }

    function load_iou_voucher_confirmation()
    {
        $ioumasterid = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('voucherAutoID'));
        $data['extra'] = $this->iou_model->fetch_iou_voucher($ioumasterid);
        $data['approval'] = $this->input->post('approval');
        if (!$this->input->post('html')) {
            $data['signature'] = $this->iou_model->fetch_signaturelevel_iou_voucher();
        } else {
            $data['signature'] = '';
        }
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        $html = $this->load->view('system/iou/iou_voucher_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function iouvoucher_confirmation()
    {
        echo json_encode($this->iou_model->iou_Voucher_confirmation());
    }

    function fetch_iou_voucher_details()
    {
        echo json_encode($this->iou_model->fetch_iou_voucher_details());
    }

    function update_iou_voucher_details()
    {
        $this->form_validation->set_rules('iouvoucherdetails_edit', 'IOU Voucher Detail ID is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->update_iou_voucher_details());
        }

    }

    function iou_voucher_approval()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $this->datatables->select('	masterTbl.voucherAutoID AS voucherAutoID,masterTbl.empName as employeename,det.transactionAmount AS transactionAmount,masterTbl.iouCode AS systemcode,masterTbl.narration AS COMMENT,confirmedYN,masterTbl.transactionCurrencyDecimalPlaces AS transactionCurrencyDecimalPlaces,transactionCurrency,masterTbl.approvedYN AS approvedYN,approvalLevelID,documentApprovedID,DATE_FORMAT(voucherDate,\'' . $convertFormat . '\') AS voucherDate', false);
        $this->datatables->join('(SELECT SUM( transactionAmount ) AS transactionAmount, voucherAutoID FROM srp_erp_iouvoucherdetails detailTbl GROUP BY voucherAutoID) det', '(masterTbl.voucherAutoID = det.voucherAutoID )', 'left');
        $this->datatables->from('srp_erp_iouvouchers masterTbl');
        $this->datatables->join('srp_employeesdetails employee', 'employee.EIdNo = masterTbl.empID', 'left');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = masterTbl.voucherAutoID AND srp_erp_documentapproved.approvalLevelID = masterTbl.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = masterTbl.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'IOU');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'IOU');
        $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->add_column('total_TransferCost', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(transactionAmount,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', "<div style='text-align: center'>Level $1</div>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"IOU",voucherAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_iou_action(voucherAutoID,approvalLevelID,approvedYN,documentApprovedID,IOU)');
        echo $this->datatables->generate();
    }

    function save_iou_voucher_approval()
    {
        $system_code = trim($this->input->post('iouvoucherid'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'IOU', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('voucherAutoID');
                $this->db->where('voucherAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_iouvouchers');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('iouvoucherid', 'IOU Voucher ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->iou_model->save_iou_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('voucherAutoID');
            $this->db->where('voucherAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_iouvouchers');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'IOU', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('status', 'IOU Voucher Status', 'trim|required');
                    $this->form_validation->set_rules('comments', 'Comment', 'trim|required');

                    $this->form_validation->set_rules('iouvoucherid', 'IOU Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->iou_model->save_iou_approval());
                    }
                }
            }
        }
    }

    function delete_iou_voucher_delete()
    {
        echo json_encode($this->iou_model->delete_iou_voucher_delete());
    }

    function reopen_iou_voucher()
    {
        echo json_encode($this->iou_model->reopern_iou_voucher());
    }

    function iou_referback()
    {
        $iouvoucherautoid = $this->input->post('returnAutoID');

        $this->db->select('approvedYN,documentID');
        $this->db->where('voucherAutoID', trim($iouvoucherautoid));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_iouvouchers');
        $approved_iou_voucher = $this->db->get()->row_array();
        if (!empty($approved_iou_voucher)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_iou_voucher['iouCode']));
        } else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($iouvoucherautoid, 'IOU');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }


    }

    function iou_categorymaster_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];

        $data['category'] = $this->db->query("Select *,CONCAT(glCode,'-',glCodeDescription) as description from srp_erp_expenseclaimcategories where companyID = $companyid And type = 2 ")->result_array();


        $this->load->view('system/iou/ajax/load_iou_categorymaster_view', $data);
    }

    function save_iou_category()
    {
        $this->form_validation->set_rules('Description', 'Description', 'trim|required');
        $this->form_validation->set_rules('glcode', 'Gl Code', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->save_iou_catergory());
        }
    }

    function iou_categoryheader()
    {
        echo json_encode($this->iou_model->load_iou_cat_header());
    }

    function delete_ioucategory()
    {
        echo json_encode($this->iou_model->delete_ioucategory());
    }

    function load_iou_voucherbooking_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];

        $date_format_policy = date_format_policy();
        $text = trim($this->input->post('q'));
        $status = trim($this->input->post('status'));
        $dateto = $this->input->post('dateto');
        $employeesearch = explode(' - ', trim($this->input->post('empid')));
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $EmpYN = $this->input->post('EmployeeYN');
        $employeeID = current_userID();


        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((masters.empName Like '%" . $text . "%') OR (bookingCode Like '%" . $text . "%')  OR (comments Like '%" . $text . "%'))";
        }

        $filter_status = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND confirmedYN = 0 AND approvedYN = 0";
            } else if ($status == 2) {
                $filter_status = " AND confirmedYN = 1 AND approvedYN = 0";
            } elseif ($status == 3) {
                $filter_status = " AND confirmedYN = 1 AND approvedYN = 1";
            } else if ($status == 4) {
                $filter_status = " AND submittedYN = 1 AND approvedYN = 0 AND confirmedYN = 0";
            }

        }

        $employee = "";
        if (isset($employeesearch[0]) && !empty($employeesearch[0])) {
            $employee = " AND masters.empID = {$employeesearch[0]} AND masters.userType = {$employeesearch[1]}";
        }

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( bookingDate >= '" . $datefromconvert . " 00:00:00' AND bookingDate <= '" . $datetoconvert . " 23:59:00')";
        }


        $where_admin = "WHERE companyID = " . $companyid . $search_string . $employee . $date . $filter_status;

        $data['EmpYN'] = $EmpYN;

        if ($EmpYN == 0) {
            $data['master'] = $this->db->query("select *,masters.confirmedYN as confirmedYN,masters.empName as empnamemaster,masters.userType,masters.approvedYN as approvedYN,masters.submittedYN as submittedYN,masters.bookingMasterID as bookingMasterID,masters.empID,det.Ename2,bookingdet.totalamtbookingamount,masters.isDeleted as isDeleted  from  srp_erp_ioubookingmaster masters LEFT JOIN srp_employeesdetails det on masters.empID = det.EIdNo LEFT JOIN (select sum(transactionAmount) as totalamtbookingamount ,bookingMasterID from srp_erp_ioubookingdetails where companyID = $companyid GROUP BY bookingMasterID)bookingdet on bookingdet.bookingMasterID = masters.bookingMasterID $where_admin ORDER BY masters.bookingMasterID DESC ")->result_array();

        } else if ($EmpYN == 1) {
            $data['master'] = $this->db->query("select *,masters.confirmedYN as confirmedYN,masters.empName as empnamemaster,masters.userType,masters.approvedYN as approvedYN,masters.submittedYN as submittedYN,masters.bookingMasterID as bookingMasterID,masters.empID,det.Ename2,bookingdet.totalamtbookingamount,masters.isDeleted as isDeleted  from  srp_erp_ioubookingmaster masters LEFT JOIN srp_employeesdetails det on masters.empID = det.EIdNo LEFT JOIN (select sum(transactionAmount) as totalamtbookingamount ,bookingMasterID from srp_erp_ioubookingdetails where companyID = $companyid GROUP BY bookingMasterID)bookingdet on bookingdet.bookingMasterID = masters.bookingMasterID $where_admin AND masters.empID = $employeeID 	AND masters.userType = 1  ORDER BY masters.bookingMasterID DESC ")->result_array();
        }
        $this->load->view('system/iou/ajax/iou_bookingview', $data);

    }

    function save_iou_booking_header()
    {
        $date_format_policy = date_format_policy();
        $documentDate = $this->input->post('bookingdate');
        $formatted_documentDate = input_format_date($documentDate, $date_format_policy);
        $companyid = current_companyID();

        $this->form_validation->set_rules('bookingdate', 'Booking Date', 'trim|required');
        $this->form_validation->set_rules('employeeid', 'Employee', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
        $this->form_validation->set_rules('iouvoucher', 'IOU Voucher', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {

            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($formatted_documentDate >= $financePeriod['dateFrom'] && $formatted_documentDate <= $financePeriod['dateTo']) {
                echo json_encode($this->iou_model->save_iou_booking());
            } else {
                echo json_encode(array('e', 'Booking Date is not between Financial period !'));
            }
        }
    }

    function load_iou_booking_detail_items_view()
    {
        $comapnyid = $this->common_data['company_data']['company_id'];
        $ioubookingmasterid = trim($this->input->post('IOUbookingmasterid'));
        $data['detail'] = $this->db->query("select *,bookingdet.description as bookingdescription,bookingdet.categoryDescription,segment.segmentCode,vouchers.iouCode,bookingdet.transactionAmount as bookingAmount,bookingdet.transactionCurrencyDecimalPlaces as transactionCurrencyDecimalPlacesbooking from srp_erp_ioubookingdetails bookingdet LEFT JOIN srp_erp_iouvouchers vouchers on vouchers.voucherAutoID =  bookingdet.iouVoucherAutoID LEFT JOIN srp_erp_expenseclaimcategories catergorie on catergorie.expenseClaimCategoriesAutoID = bookingdet.expenseCategoryAutoID LEFT JOIN srp_erp_segment segment on segment.segmentID = bookingdet.segmentID where bookingdet.companyID = $comapnyid AND bookingMasterID = $ioubookingmasterid")->result_array();
        $data['master'] = $this->db->query("select * from srp_erp_ioubookingmaster where bookingMasterID = $ioubookingmasterid AND companyID = $comapnyid")->row_array();

        $this->load->view('system/iou/ajax/load_iou_booking_detial', $data);

    }

    function fetch_iou_booking_detail()
    {
        echo json_encode($this->iou_model->fetch_iou_booking_detail());
    }

    function save_ioubooking_amt()
    {
        $amount = $this->input->post('amounts');

        foreach ($amount as $key => $amt) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item 1', 'trim|required');
            $this->form_validation->set_rules("category[{$key}]", 'Category', 'trim|required');
            $this->form_validation->set_rules("amounts[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("segment[{$key}]", 'Segment', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'Description', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {

            echo json_encode($this->iou_model->save_ioubooking_amt());

        }


    }

    function load_voucher_booking_header()
    {
        echo json_encode($this->iou_model->load_voucher_booking_header());
    }

    function delete_ioubooking_detail()
    {
        echo json_encode($this->iou_model->delete_ioubooking_detail());
    }

    function fetch_iou_booking_details()
    {
        echo json_encode($this->iou_model->fetch_iou_booking_details());
    }

    function load_iou_voucher_booking_confirmation()
    {
        $ioubookingid = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('IOUbookingmasterid'));
        $data['extra'] = $this->iou_model->fetch_iou_booking($ioubookingid);
        $data['approval'] = $this->input->post('approval');


        /*   if (!$this->input->post('html')) {
               $data['signature'] = $this->iou_model->fetch_signaturelevel_iou_voucher();
           } else {
               $data['signature'] = '';
           }*/
        $data['logo']=mPDFImage;
        if($this->input->post('html')){
            $data['logo']=htmlImage;
        }
        $html = $this->load->view('system/iou/iou_booking_print', $data, true);
        $pdfprint = $this->load->view('system/iou/iou_booking_print_pdf', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($pdfprint, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function fetch_double_entry_iou_booking()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $code = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('code'));
        $data['extra'] = $this->iou_model->fetch_double_entry_iou_bookingded($masterID, $code);
        $html = $this->load->view('system/double_entry/erp_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['approved_YN']);
        }
    }

    function ioubooking_confirmation()
    {
        echo json_encode($this->iou_model->ioubooking_confirmation());
    }

    function delete_iou_booking_delete()
    {
        echo json_encode($this->iou_model->delete_iou_booking_delete());
    }

    function reopen_iou_booking()
    {
        echo json_encode($this->iou_model->reopen_iou_booking());
    }

    function iou_referback_booking()
    {
        $bookingMasterID = $this->input->post('bookingMasterID');

        $this->db->select('approvedYN,documentID,bookingCode');
        $this->db->where('bookingMasterID', trim($bookingMasterID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_ioubookingmaster');
        $approved_iou_booking = $this->db->get()->row_array();
        if (!empty($approved_iou_booking)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_iou_booking['bookingCode']));
        } else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($bookingMasterID, 'IOUE');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }


    }

    function iou_referback_booking_master_view()
    {
        $bookingMasterID = $this->input->post('bookingMasterID');

        $this->db->select('approvedYN,documentID,bookingCode');
        $this->db->where('bookingMasterID', trim($bookingMasterID));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_ioubookingmaster');
        $approved_iou_booking = $this->db->get()->row_array();
        if (!empty($approved_iou_booking)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_iou_booking['bookingCode']));
        } else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($bookingMasterID, 'IOUE');
            if ($status == 1) {
                $data['submittedYN'] = '';
                $data['submittedDate'] = '';
                $data['submittedEmpID'] = '';
                $this->db->set('timestamp', current_date(true));
                $this->db->where('bookingMasterID', trim($bookingMasterID));
                $result = $this->db->update('srp_erp_ioubookingmaster', $data);
                if ($result) {
                    echo json_encode(array('s', ' Referred Back Successfully.', $status));
                }

            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }


    }

    function iou_booking_approval()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $this->datatables->select('masterTbl.bookingMasterID AS bookingMasterID,masterTbl.empName as employeename,det.transactionAmount AS transactionAmount,masterTbl.bookingCode AS systemcode,masterTbl.comments AS COMMENT,confirmedYN,masterTbl.transactionCurrencyDecimalPlaces AS transactionCurrencyDecimalPlaces,transactionCurrency,masterTbl.approvedYN AS approvedYN,approvalLevelID,documentApprovedID,DATE_FORMAT(bookingDate,\'' . $convertFormat . '\') AS bookingDate', false);
        $this->datatables->join('(SELECT SUM( transactionAmount ) AS transactionAmount, bookingMasterID FROM srp_erp_ioubookingdetails detailTbl GROUP BY bookingMasterID) det', '(masterTbl.bookingMasterID = det.bookingMasterID )', 'left');
        $this->datatables->from('srp_erp_ioubookingmaster masterTbl');
        $this->datatables->join('srp_employeesdetails employee', 'employee.EIdNo = masterTbl.empID', 'left');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = masterTbl.bookingMasterID AND srp_erp_documentapproved.approvalLevelID = masterTbl.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = masterTbl.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'IOUE');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'IOUE');
        $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->add_column('total_TransferCost', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(transactionAmount,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('confirmed', "<div style='text-align: center'>Level $1</div>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"IOUE",bookingMasterID)');
        $this->datatables->add_column('edit', '$1', 'load_iou_action(bookingMasterID,approvalLevelID,approvedYN,documentApprovedID,IOUE)');
        echo $this->datatables->generate();
    }

    function save_iou_booking_approval()
    {
        $system_code = trim($this->input->post('ioubookingid'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));

        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'IOUE', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('bookingMasterID');
                $this->db->where('bookingMasterID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_ioubookingmaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('ioubookingid', 'IOUE Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->iou_model->save_ioub_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('bookingMasterID');
            $this->db->where('bookingMasterID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_ioubookingmaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'IOUE', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('status', 'IOUE Voucher Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('ioubookingid', 'IOU Booking Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->iou_model->save_ioub_approval());
                    }
                }
            }
        }
    }

    function iou_booking_details_exist()
    {
        $IOUbookingmasterid = trim($this->input->post('IOUbookingmasterid'));
        $data = $this->db->query("select * from srp_erp_ioubookingdetails WHERE bookingMasterID={$IOUbookingmasterid} ")->row_array();
        echo json_encode($data);
    }
    function iou_voucher_details_exist()
    {
        $IOUmasterid= trim($this->input->post('IOUmasterid'));
        $data = $this->db->query("select * from srp_erp_iouvoucherdetails WHERE voucherAutoID={$IOUmasterid} ")->row_array();
        echo json_encode($data);
    }

    function ioubooking_submit()
    {
        echo json_encode($this->iou_model->ioubooking_submit());
    }

    function iou_book_emp_submit()
    {
        $IOUbookingmasterid = trim($this->input->post('IOUbookingmasterid'));
        $data = $this->db->query("select submittedYN from srp_erp_ioubookingmaster WHERE bookingMasterID={$IOUbookingmasterid} AND submittedYN = 1")->row_array();
        echo json_encode($data);
    }

    function iou_referback_booking_emp()
    {
        echo json_encode($this->iou_model->iou_referback_booking_emp());
    }

    function get_currency_decimal_places()
    {

        $ioumasterid = $this->input->post('IOUmasterid');
        $companyid = current_companyID();
        $ioumasteridcurrency = $this->db->query("select transactionCurrencyID from srp_erp_iouvouchers where voucherAutoID = $ioumasterid AND companyID = $companyid")->row_array();

        $this->db->select('DecimalPlaces');
        $this->db->where('currencyID', $ioumasteridcurrency['transactionCurrencyID']);
        $this->db->from('srp_erp_currencymaster');
        $data = $this->db->get()->row_array();
        echo json_encode($data);
    }

    function get_currency_decimal_places_booking()
    {

        $ioumasterbookingid = $this->input->post('IOUbookingmasterid');
        $companyid = current_companyID();
        $ioumasterbookingcurrency = $this->db->query("SELECT transactionCurrencyID FROM srp_erp_ioubookingmaster  WHERE bookingMasterID = $ioumasterbookingid  AND companyID = $companyid")->row_array();

        $this->db->select('DecimalPlaces');
        $this->db->where('currencyID', $ioumasterbookingcurrency['transactionCurrencyID']);
        $this->db->from('srp_erp_currencymaster');
        $data = $this->db->get()->row_array();
        echo json_encode($data);
    }

    function iou_book_emp_submit_confirmation()//confirmation btn enable
    {
        $IOUbookingmasterid = trim($this->input->post('IOUbookingmasterid'));
        $data = $this->db->query("select submittedYN from srp_erp_ioubookingmaster WHERE bookingMasterID={$IOUbookingmasterid} AND submittedYN = 1")->row_array();
        echo json_encode($data);
    }

    function get_iou_booking_attachments()
    {
        $this->load->view('system/iou/iou_booking_attachment', true);
    }

    function load_iou_user_view()
    {
        $companyid = current_companyID();
        $text = trim($this->input->post('q'));
        $status = trim($this->input->post('iouuserstatus'));

        $filter_status = '';
        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((userCode Like '%" . $text . "%') OR (userName Like '%" . $text . "%')) ";
        }
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND isActive = 1 ";
            } else if ($status == 2) {
                $filter_status = " AND isActive = 0 ";
            }
        }
        $where_admin = "WHERE companyID = " . $companyid . $search_string . $filter_status;
        $data['header'] = $this->db->query("SELECT * FROM  srp_erp_iouusers $where_admin")->result_array();
        $this->load->view('system/iou/ajax/iou_user_master', $data);
    }

    function fetch_iou_employee_currency()
    {
        echo json_encode($this->iou_model->fetch_iou_employee_currency());
    }

    function save_user_header()
    {
        $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('phonenumber', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('active', 'User Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->save_iou_user());
        }
    }

    function load_iou_header()
    {
        echo json_encode($this->iou_model->load_iou_header());
    }

    function delete_iou_master()
    {
        echo json_encode($this->iou_model->delete_iou_master());
    }

    function load_iou_booking_detail_voucher_generate()
    {
        $this->load->view('system/iou/ajax/load_iou_voucher_genarate');
    }

    function fetch_iou_iouvouchers()
    {
        $data_arr = array();
        $empidtype = explode('|', trim($this->input->post('empid')));
        $transactioncurrencyid = trim($this->input->post('transactioncurrenyid'));
        $companyID = $this->common_data['company_data']['company_id'];
        if (!empty($empidtype)) {
            $voucherqry = "select voucherAutoID,iouCode,empName,CONCAT(transactionCurrency,' ',transactionAmount) as transactionamountcurrency from srp_erp_iouvouchers where empID = $empidtype[0] AND userType = $empidtype[1] AND companyID = $companyID AND transactionCurrencyID = $transactioncurrencyid AND confirmedYN = 1 AND closedYN != 1  AND approvedYN = 1";
            $iouvoucher = $this->db->query($voucherqry)->result_array();
            $data_arr = array('' => 'Select IOU Voucher');
            if (!empty($iouvoucher)) {
                foreach ($iouvoucher as $row) {
                    $data_arr[trim($row['voucherAutoID'])] = trim($row['iouCode']) . ' | ' . trim($row['transactionamountcurrency']);
                }
            }
            echo form_dropdown('iouvoucher', $data_arr, '', 'class="form-control select2 " id="advance_iouvoucher"');
        }
    }

    function fetch_iou_iouvoucher_details()
    {
        $this->form_validation->set_rules('iouvoucherid', 'Voucher ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->fetch_iou_iouvoucher_details());
        }
    }

    function generatevoucher()//generating receipt or payment vocher according to balance
    {
        echo json_encode($this->iou_model->generatevoucher());
    }

    function save_iou_voucher_receipt_voucher()
    {
        $date_format_policy = date_format_policy();
        $RVcqeDte = $this->input->post('RVchequeDate');
        $RVchequeDate = input_format_date($RVcqeDte, $date_format_policy);
        $bank_detail = fetch_gl_account_desc(trim($this->input->post('RVbankCode')));
        $this->form_validation->set_rules('RVbankCode', 'Bank Or Cash', 'trim|required');
        $this->form_validation->set_rules('RVdate', 'Voucher Date', 'trim|required');
        if ($bank_detail['isCash'] == 0) {
            $this->form_validation->set_rules('RVchequeNo', 'Cheque Number', 'trim|required');
            $this->form_validation->set_rules('RVchequeDate', 'Cheque Date', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->save_iou_voucher_receipt_voucher());
        }
    }

    function save_iou_voucher_payment_voucher()
    {

        $paymenttype = $this->input->post('paymentType');
        $this->form_validation->set_rules('PVbankCode', 'Bank Or Cash', 'trim|required');
        $this->form_validation->set_rules('voucheridpayment', 'Voucher', 'trim|required');
        $this->form_validation->set_rules('balanceamtpaymentvoucher', 'Balance Amount', 'trim|required');
        $bank_detail = fetch_gl_account_desc($this->input->post('PVbankCode'));
        if ($bank_detail['isCash'] != 1) {
            {
                $this->form_validation->set_rules('paymentType', 'Payment Type', 'trim|required');
                if ($paymenttype == 1) {
                    $this->form_validation->set_rules('PVchequeDate', 'Cheque Date', 'trim|required');
                    $this->form_validation->set_rules('PVchequeNo', 'Cheque Number', 'trim|required');
                }
            }

        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->save_iou_voucher_payment_voucher());
        }
    }

    function close_iou_voucher()
    {

        $this->form_validation->set_rules('voucherid', 'Voucher', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->close_iou_voucher());
        }
    }

    function fetch_iou_closed_details()
    {
        $this->form_validation->set_rules('voucherid', 'Voucher', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->iou_model->fetch_iou_closed_details());
        }
    }

    function load_pv_conformation()
    {
        $type = trim($this->input->post('type'));

        if ($type == 1) {
            $receiptVoucherAutoId = trim($this->input->post('VoucherAutoId'));
            $data['extra'] = $this->Receipt_voucher_model->fetch_receipt_voucher_template_data($receiptVoucherAutoId);
            $data['approval'] = $this->input->post('approval');
            if (!$this->input->post('html')) {
                $data['signature'] = $this->Receipt_voucher_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }
            $html = $this->load->view('system/receipt_voucher/erp_receipt_voucher_print', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {
                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
            }

        } else if ($type == 2) {
            $payVoucherAutoId = trim($this->input->post('VoucherAutoId'));
            $data['extra'] = $this->Payment_voucher_model->fetch_payment_voucher_template_data($payVoucherAutoId);
            $data['approval'] = $this->input->post('approval');

            $this->db->select('documentID');
            $this->db->where('payVoucherAutoId', trim($payVoucherAutoId));
            $this->db->where('companyID', current_companyID());
            $this->db->from('srp_erp_paymentvouchermaster');
            $documentid = $this->db->get()->row_array();

            $printHeaderFooterYN = 1;
            $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            $this->db->select('printHeaderFooterYN');
            $this->db->where('companyID', current_companyID());
            $this->db->where('documentID', $documentid['documentID']);
            $this->db->from('srp_erp_documentcodemaster');
            $result = $this->db->get()->row_array();
            if (!empty($result)) {
                $printHeaderFooterYN = $result['printHeaderFooterYN'];
                $data['printHeaderFooterYN'] = $printHeaderFooterYN;
            }

            if (!$this->input->post('html')) {
                $data['signature'] = $this->Payment_voucher_model->fetch_signaturelevel();
            } else {
                $data['signature'] = '';
            }

            $html = $this->load->view('system/payment_voucher/erp_payment_voucher_print', $data, true);
            if ($this->input->post('html')) {
                echo $html;
            } else {

                $this->load->library('pdf');
                $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN'], null, $printHeaderFooterYN);
            }
        }

    }

    function load_iou_voucher_detail_editView()
    {
        $iouvoucher = trim($this->input->post('iouvoucherid'));
        $companyid = current_companyID();
        $convertFormat = convert_date_format_sql();

        $data['header'] = $data = $this->db->query("select vouchers.*,	segment.segmentCode,employee.Ename2 as employeenameclosed,  DATE_FORMAT(voucherDate,\"" . $convertFormat . "\") AS voucherDate, DATE_FORMAT(closedDate,\"" . $convertFormat . "\") AS closedDate,CASE WHEN vouchers.balanceVoucherType = \"1\" THEN \"Receipt Voucher\" WHEN vouchers.balanceVoucherType = \"2\" THEN \"Payment Voucher\" ELSE \"-\" END Vouchertype,CASE WHEN vouchers.modeOfPayment = \"1\" THEN \"Cash\" WHEN vouchers.modeOfPayment = \"2\" THEN \"Bank\" ELSE \"-\"  END paymentT,	CASE WHEN vouchers.paymentType = \"1\" THEN \"Cheque\" WHEN vouchers.paymentType = \"2\" THEN \"Bank Trasfer\" ELSE \"-\" END paymenttypevoucher,DATE_FORMAT(chequeDate,\"" . $convertFormat . "\") AS chequeDate  from srp_erp_iouvouchers vouchers LEFT JOIN srp_employeesdetails employee on employee.EIdNo = vouchers.closedByEmpID LEFT JOIN srp_erp_segment segment on segment.segmentID = vouchers.segmentID where  voucherAutoID = $iouvoucher And vouchers.companyID = $companyid ")->row_array();
        $this->load->view('system/iou/ajax/load_voucher_details', $data);
    }

    function load_iou_voucher_detail_view()
    {
        $ioumasterid = trim($this->input->post('IOUmasterid'));
        $comapnyid = $this->common_data['company_data']['company_id'];
        $data['detail'] = $this->db->query("select * from srp_erp_iouvoucherdetails where voucherAutoID = $ioumasterid AND companyID = $comapnyid")->result_array();
        $this->load->view('system/iou/ajax/load_iou_voucher_detial_voucher', $data);

    }

    function load_iou_voucher_expences()
    {
        $ioumasterid = trim($this->input->post('voucherAutoID'));
        $comapnyid = current_companyID();
        $data['detail'] = $this->db->query("SELECT bookmater.*,bookingdetails.detailtransactionamount FROM srp_erp_ioubookingmaster bookmater LEFT JOIN ( SELECT SUM( transactionAmount ) AS detailtransactionamount,bookingMasterID FROM srp_erp_ioubookingdetails GROUP BY bookingMasterID ) bookingdetails ON bookingdetails.bookingMasterID = bookmater.bookingMasterID WHERE bookmater.iouVoucherAutoID = $ioumasterid AND bookmater.companyid = $comapnyid  GROUP BY bookingdetails.bookingMasterID")->result_array();
        $this->load->view('system/iou/iou_voucher_view_expences', $data);
    }
    function load_iou_voucher_view_employee()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $date_format_policy = date_format_policy();
        $text = trim($this->input->post('q'));
        $status = trim($this->input->post('staus'));
        $dateto = $this->input->post('dateto');
        $employeesearch = explode('|', trim($this->input->post('employee')));
        $datefrom = $this->input->post('datefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $emp = trim($this->input->post('emptype'));
        $employeeID = current_userID();

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((iou.empName Like '%" . $text . "%') OR (iouCode Like '%" . $text . "%')  OR (iou.narration Like '%" . $text . "%'))";
        }

        $filter_status = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND iou.confirmedYN = 0 AND iou.approvedYN = 0";
            } else if ($status == 2) {
                $filter_status = " AND iou.confirmedYN = 1 AND iou.approvedYN = 0";
            } elseif ($status == 3) {
                $filter_status = " AND iou.confirmedYN = 1 AND iou.approvedYN = 1";
            }

        }

        $employee = "";
        if (isset($employeesearch[0]) && !empty($employeesearch[0])) {
            $employee = " AND iou.empID = {$employeesearch[0]} AND iou.userType = {$employeesearch[1]}";
        }

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( voucherDate >= '" . $datefromconvert . " 00:00:00' AND voucherDate <= '" . $datetoconvert . " 23:59:00')";
        }


        $where_admin = "WHERE companyID = " . $companyid . $search_string . $filter_status . $employee . $date;


            $data['master'] = $this->db->query("SELECT iou.voucherAutoID,bookdet.ioubookingexpence as bookingamount,iou.closedYN as closedYN,	IFNULL(	det.transactionamount - bookdet.ioubookingexpence, 0 ) AS expamt,iou.userType,iou.createdUserID,iou.isDeleted,iou.empID,iou.empName as empNameiou,det.transactionamount,currency.CurrencyCode,voucherDate,iouCode,employees.Ename2 as employeename,iou.narration as narration,iou.confirmedYN ,iou.approvedYN,iou.transactionCurrencyDecimalPlaces FROM srp_erp_iouvouchers iou LEFT JOIN srp_employeesdetails employees on employees.EIdNo = iou.empID LEFT JOIN srp_erp_currencymaster currency on currency.currencyID = iou.transactionCurrencyID LEFT Join ( SELECT sum( transactionAmount ) AS ioubookingexpence, iouVoucherAutoID FROM srp_erp_ioubookingdetails GROUP BY iouVoucherAutoID ) bookdet ON bookdet.iouVoucherAutoID = iou.voucherAutoID  LEFT JOIN(Select sum(transactionAmount) as transactionamount,voucherAutoID from srp_erp_iouvoucherdetails GROUP BY voucherAutoID)det on iou.voucherAutoID = det.voucherAutoID $where_admin AND iou.userType = 1 AND iou.empID = $employeeID  ORDER BY voucherAutoID DESC")->result_array();
            $this->load->view('system/iou/ajax/load_io_voucher_view_employee.php', $data);


    }

}




