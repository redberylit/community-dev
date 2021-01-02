<?php

class Srm_master extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('srm');
        $this->load->model('Srm_master_model');

    }

    function save_customer()
    {
        if (!$this->input->post('customerAutoID')) {
            $this->form_validation->set_rules('customerCurrency', 'customer Currency', 'trim|required');
        }
        $this->form_validation->set_rules('customercode', 'customer Code', 'trim|required');
        $this->form_validation->set_rules('customerName', 'customer Name', 'trim|required');
        $this->form_validation->set_rules('customercountry', 'customer country', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 'e', 'message' => validation_errors()));
        } else {
            ;
            echo json_encode($this->Srm_master_model->save_customer());
        }
    }


    function save_supplier()
    {
        if (!$this->input->post('supplierAutoID')) {
            $this->form_validation->set_rules('supplierCurrency', 'supplier Currency', 'trim|required');
        }
        $this->form_validation->set_rules('suppliercode', 'supplier Code', 'trim|required');
        $this->form_validation->set_rules('supplierName', 'suplier Name', 'trim|required');
        $this->form_validation->set_rules('suppliercountry', 'supplier country', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 'e', 'message' => validation_errors()));
        } else {
            ;
            echo json_encode($this->Srm_master_model->save_supplier());
        }
    }

    function fetch_customer()
    {
        $customer_filter = '';
        $category_filter = '';
        $currency_filter = '';

        $companyid = $this->common_data['company_data']['company_id'];
        $where = "srp_erp_srm_customermaster.companyID = {$companyid} ";
        $this->datatables->select('customerAutoID,customerSystemCode,secondaryCode')
            ->where($where)
            ->from('srp_erp_customermaster');

        $this->datatables->add_column('customer_detail', '<b>Name : </b> $1 &nbsp;&nbsp;&nbsp;<b>Secondary Code : </b>$5<br><b>Address : </b> $2 &nbsp;&nbsp;$3 &nbsp;&nbsp;$4.<br><b>customer Currency : </b>$6 &nbsp;&nbsp;&nbsp;<b> Email </b> $7  &nbsp;&nbsp;&nbsp;<b>Telephone</b> $8', 'customerName,customerAddress1, customerAddress2, customerCountry, secondaryCode, customerCurrency, customerEmail,customerTelephone');
        $this->datatables->add_column('confirmed', '$1', 'confirm(isActive)');
        $this->datatables->add_column('edit', '$1', 'editcustomer(customerAutoID)');
        $this->datatables->edit_column('amt', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(Amount,partyCurrencyDecimalPlaces),customerCurrency');

        echo $this->datatables->generate();
    }

    function fetch_customer_all()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $status = trim($this->input->post('status'));
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND CustomerName Like '%" . $text . "%'";
        }

        $search_sorting = '';
        if (isset($sorting) && $sorting != '#') {
            $search_sorting = " AND CustomerName Like '" . $sorting . "%'";
        }

        $filter_status = '';
        if (isset($status) && $status == 1) {
            $filter_status = " AND isActive = 1";
        } else if (isset($status) && $status == 0) {
            $filter_status = " AND isActive = 0";
        }

        $where = "companyID = " . $companyid . $search_string . $search_sorting . $filter_status;

        $this->db->select('*,CountryDes');
        $this->db->from('srp_erp_srm_customermaster');
        $this->db->join('srp_erp_countrymaster', 'srp_erp_countrymaster.countryID = srp_erp_srm_customermaster.customerCountry', 'LEFT');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        $data['output'] = $result;
        $this->load->view('system/srm/customer/ajax/load_customer_master', $data); //_style2
    }


    function fetch_supplier_all()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $status = trim($this->input->post('status'));
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND supplierName Like '%" . $text . "%'";
        }

        $search_sorting = '';
        if (isset($sorting) && $sorting != '#') {
            $search_sorting = " AND supplierName Like '" . $sorting . "%'";
        }

        $filter_status = '';
        if (isset($status) && $status == 1) {
            $filter_status = " AND isActive = 1";
        } else if (isset($status) && $status == 0) {
            $filter_status = " AND isActive = 0";
        }

        $where = "companyID = " . $companyid . $search_string . $search_sorting . $filter_status;
        $this->db->select('*,CountryDes');
        $this->db->from('srp_erp_srm_suppliermaster');
        $this->db->join('srp_erp_countrymaster', 'srp_erp_countrymaster.countryID = srp_erp_srm_suppliermaster.supplierCountry', 'LEFT');
        $this->db->where($where);
        $data['output'] = $this->db->get()->result_array();
        $this->load->view('system/srm/supplier/ajax/load_supplier_master', $data);

    }

    function fetch_supplier_view()
    {
        $supplierID = $this->input->post('supplierID');
        $this->db->select('*');
        $this->db->from('srp_erp_srm_suppliermaster');
        $this->db->where('supplierAutoID', $supplierID);
        $result = $this->db->get()->row_array();
        $data['output'] = $result;
        $this->load->view('system/srm/supplier/ajax/ajax_view_supplier_detiles', $data);
    }


    function fetch_itemcode_view()
    {
        $supplierID = $this->input->post('supplierID');
        $this->db->select('*');
        $this->db->from('srp_erp_srm_suppliermaster');
        $this->db->where('supplierAutoID', $supplierID);
        $result = $this->db->get()->row_array();
        $data['output'] = $result;
        $this->load->view('system/srm/item/ajax/ajax_load_item_master_style', $data);
    }


    function load_supplier_items_details()
    {
        echo json_encode($this->Srm_master_model->load_supplier_items_details());
    }

    function load_supplier_itemsmaster()
    {

        echo json_encode($this->Srm_master_model->load_supplier_itemsmaster());


        /*out put => json array */
//        echo json_encode($this->Srm_master_model->load_supplier_items_details());


    }

    function save_supplierItem()
    {
        echo json_encode($this->Srm_master_model->save_supplierItem());
    }

    function load_customer_header()
    {
        echo json_encode($this->Srm_master_model->load_customer_header());
    }

    function load_supplier_header()
    {
        echo json_encode($this->Srm_master_model->load_supplier_header());
    }


    function delete_supplier_item()
    {
        echo json_encode($this->Srm_master_model->delete_supplier_item());
    }

    function delete_supplier()
    {
        echo json_encode($this->Srm_master_model->delete_supplier());
    }

    function delete_customer()
    {
        echo json_encode($this->Srm_master_model->delete_customer());
    }

    function load_supplier_editView()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $supplierAutoID = trim($this->input->post('supplierAutoID'));
        $this->db->select('*,CountryDes,DATE_FORMAT(srp_erp_srm_suppliermaster.createdDateTime,\'' . $convertFormat . '\') AS createdDate,DATE_FORMAT(srp_erp_srm_suppliermaster.modifiedDateTime,\'' . $convertFormat . '\') AS modifydate,srp_erp_srm_suppliermaster.createdUserName as createdUserName');
        $this->db->from('srp_erp_srm_suppliermaster');
        $this->db->where('supplierAutoID', $supplierAutoID);
        $this->db->join('srp_erp_countrymaster', 'srp_erp_countrymaster.countryID = srp_erp_srm_suppliermaster.supplierCountry', 'LEFT');
        $data['header'] = $this->db->get()->row_array();

        $this->load->view('system/srm/supplier/ajax/load_supplier_edit_view', $data);
    }

    function load_customer_editView()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $CustomerAutoID = trim($this->input->post('customerAutoID'));
        $this->db->select('*,CountryDes,DATE_FORMAT(srp_erp_srm_customermaster.createdDateTime,\'' . $convertFormat . '\') AS createdDate,DATE_FORMAT(srp_erp_srm_customermaster.modifiedDateTime,\'' . $convertFormat . '\') AS modifydate,srp_erp_srm_customermaster.createdUserName as createdUserName');
        $this->db->from('srp_erp_srm_customermaster');
        $this->db->where('CustomerAutoID', $CustomerAutoID);
        $this->db->join('srp_erp_countrymaster', 'srp_erp_countrymaster.countryID = srp_erp_srm_customermaster.customerCountry', 'LEFT');
        $data['header'] = $this->db->get()->row_array();

        $this->load->view('system/srm/customer/ajax/load_customer_edit_view', $data);
    }

    function load_supplier_all_notes()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $supplierAutoID = trim($this->input->post('supplierAutoID'));

        $where = "companyID = " . $companyid . " AND documentID = 1 AND documentAutoID = " . $supplierAutoID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_srm_notes');
        $this->db->where($where);
        $this->db->order_by('notesID', 'desc');
        $data['notes'] = $this->db->get()->result_array();
        $this->load->view('system/srm/supplier/ajax/load_supplier_notes', $data);
    }

    function load_supplier_all_attachments()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $supplierAutoID = trim($this->input->post('supplierAutoID'));

        $where = "companyID = " . $companyid . " AND documentID = 1  AND documentAutoID = " . $supplierAutoID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_srm_attachments');
        $this->db->where($where);
        $this->db->order_by('attachmentID', 'desc');
        $data['attachment'] = $this->db->get()->result_array();
        $this->load->view('system/srm/supplier/ajax/load_all_supplier_attachements', $data);
    }


    function load_customer_order_detail_item_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];

        $customerOrderID = trim($this->input->post('customerOrderID'));

        $this->db->select('*,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode');
        $this->db->from('srp_erp_srm_customerorderdetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_customerorderdetails.itemAutoID', 'LEFT');
        $this->db->where('srp_erp_srm_customerorderdetails.companyID', $companyid);
        $this->db->where('srp_erp_srm_customerorderdetails.customerOrderID', $customerOrderID);
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_customer_item_order_table', $data);
    }

    function save_customer_order_header()
    {
        $this->form_validation->set_rules('customerID', 'Customer Name', 'trim|required');
        $this->form_validation->set_rules('customerTelephone', 'Customer Phone', 'trim|required');
        $this->form_validation->set_rules('CustomerAddress1', 'Customer Address', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required');
        $this->form_validation->set_rules('expiryDate', 'Expiry Date', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->save_customer_order_header());
        }
    }

    function add_supplier_notes()
    {
        $this->form_validation->set_rules('supplierAutoID', 'Supplier ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->add_supplier_notes());
        }
    }

    function load_customer_order_autoGeneratedID()
    {
        echo json_encode($this->Srm_master_model->load_customer_order_autoGeneratedID());
    }

    function save_customer_ordermaster_add()
    {
        echo json_encode($this->Srm_master_model->save_customer_ordermaster_add());
    }

    function save_customer_order_detail()
    {

        $searches = $this->input->post('search');

        foreach ($searches as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item ID', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Unit Cost', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules("expectedDeliveryDate[{$key}]", 'Expected Delivery Date', 'trim|required');
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
            echo json_encode($this->Srm_master_model->save_customer_order_detail());
        }
    }

    function load_customer_order_master()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $customerID = trim($this->input->post('customerID'));
        $text = trim($this->input->post('searchOrder'));
        $statusID = trim($this->input->post('statusID'));
        $convertFormat = convert_date_format_sql();

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND customerOrderCode Like '%" . $text . "%'";
        }
        $filter_statusID = '';
        if (isset($statusID) && !empty($statusID)) {
            $filter_statusID = " AND status = {$statusID}";
        }
        $filter_customerID = '';
        if (isset($customerID) && !empty($customerID)) {
            $filter_customerID = " AND customerID = {$customerID}";
        }
        $where = "srp_erp_srm_customerordermaster.companyID = " . $companyid . $search_string . $filter_statusID . $filter_customerID;
        $this->db->select("customerOrderID,customerOrderCode,customerName,contactPersonNumber,confirmedYN,srp_erp_srm_status.description as statusDescription,backgroundColor,fontColor,srp_erp_srm_customerordermaster.narration,CurrencyCode,DATE_FORMAT(expiryDate,'" . $convertFormat . "') AS expiryDate");
        $this->db->from('srp_erp_srm_customerordermaster');
        $this->db->join('srp_erp_srm_customermaster', 'srp_erp_srm_customermaster.CustomerAutoID = srp_erp_srm_customerordermaster.customerID', 'LEFT');
        $this->db->join('srp_erp_srm_status', 'srp_erp_srm_status.statusID = srp_erp_srm_customerordermaster.status', 'LEFT');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_srm_customerordermaster.transactionCurrencyID = srp_erp_currencymaster.currencyID', 'LEFT');
        $this->db->where($where);
        $this->db->order_by('customerOrderID', 'DESC');
        $data['output'] = $this->db->get()->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_customer_order_management', $data);
    }

    function load_customer_order_inquiry_master()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchInquiry'));
        $confirmedYN = trim($this->input->post('confirmedYN'));

        $filterconfirmedYN = '';
        if ($confirmedYN == 0) {
            $filterconfirmedYN = " AND srp_erp_srm_orderinquirymaster.confirmedYN = 0";
        } else if ($confirmedYN == 1) {
            $filterconfirmedYN = " AND srp_erp_srm_orderinquirymaster.confirmedYN = 1";
        }

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND documentCode Like '%" . $text . "%'";
        }

        $where = "srp_erp_srm_orderinquirymaster.companyID = " . $companyid . $search_string . $filterconfirmedYN;
        $this->db->select('srp_erp_srm_orderinquirymaster.inquiryID,srp_erp_srm_orderinquirymaster.documentCode as orderCode, customerName,customerOrderCode,srp_erp_srm_orderinquirymaster.confirmedYN as inquiryConfirm,CurrencyCode');
        $this->db->from('srp_erp_srm_orderinquirymaster');
        $this->db->join('srp_erp_srm_customermaster', 'srp_erp_srm_customermaster.CustomerAutoID = srp_erp_srm_orderinquirymaster.customerID', 'LEFT');
        $this->db->join('srp_erp_srm_customerordermaster', 'srp_erp_srm_customerordermaster.customerOrderID = srp_erp_srm_orderinquirymaster.customerOrderID', 'LEFT');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_srm_orderinquirymaster.transactionCurrencyID = srp_erp_currencymaster.currencyID', 'LEFT');
        $this->db->where($where);
        $this->db->order_by('inquiryID', 'DESC');
        $data['output'] = $this->db->get()->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_customer_order_inquiry_management', $data);
    }

    function load_customerOrder_header()
    {
        echo json_encode($this->Srm_master_model->load_customerOrder_header());
    }

    function load_customerInquiry_header()
    {
        echo json_encode($this->Srm_master_model->load_customerInquiry_header());
    }

    function delete_customer_order_master()
    {
        echo json_encode($this->Srm_master_model->delete_customer_order_master());
    }

    function delete_customer_inquiry_master()
    {
        echo json_encode($this->Srm_master_model->delete_customer_inquiry_master());
    }

    function load_customerbase_ordersID()
    {
        $data_arr = array();
        $companyID = $this->common_data['company_data']['company_id'];
        $orderID = $this->db->query("Select * from (SELECT
		detailtb.itemAutoID,
    detailtb.`customerOrderID`,
    mastertb.`customerOrderCode`,
		IFNULL(item.isChecked,0) as checked
FROM
    `srp_erp_srm_customerordermaster` mastertb
		 LEFT JOIN srp_erp_srm_customerorderdetails detailtb on mastertb.customerOrderID=detailtb.customerOrderID
     LEFT join srp_erp_srm_inquiryitem item on detailtb.customerOrderID=item.orderMasterID and detailtb.itemAutoID=item.itemAutoID
WHERE
    mastertb.`customerID` = " . $this->input->post('customerID') . "
AND mastertb.`transactionCurrencyID` = " . $this->input->post('currency') . "
AND mastertb.`confirmedYN` = 1
AND mastertb.`companyID` = " . $companyID . "
GROUP BY detailtb.customerOrderID,detailtb.itemAutoID )tbl1 where checked=0 group by customerOrderID")->result_array();

        if (isset($orderID)) {
            foreach ($orderID as $row) {
                $data_arr[trim($row['customerOrderID'])] = trim($row['customerOrderCode']);
            }
        }
        echo form_dropdown('customer_orderID[]', $data_arr, '', 'class="form-control select2" id="customer_orderID" multiple="" ');
    }

    function load_customerOrder_BaseItem()
    {
        echo json_encode($this->Srm_master_model->load_customerOrder_BaseItem());
    }

/*    function load_customer_inquiry_detail_items_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $orderID = join($this->input->post('orderID'), ",");

        $where = "srp_erp_srm_inquiryitem.companyID = '{$companyID}' AND srp_erp_srm_inquiryitem.orderMasterID IN ($orderID) ";

        $this->db->select('srp_erp_itemmaster.itemAutoID,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode,srp_erp_srm_inquiryitem.orderMasterID,srp_erp_srm_customerordermaster.customerOrderCode');
        $this->db->from('srp_erp_srm_inquiryitem');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_inquiryitem.itemAutoID', 'LEFT');
        $this->db->join('srp_erp_srm_customerordermaster', 'srp_erp_srm_customerordermaster.customerOrderID = srp_erp_srm_inquiryitem.orderMasterID', 'LEFT');
        $this->db->where($where);
        $data['header'] = $this->db->get()->result_array();
        $this->load->view('system/srm/customer-order/ajax/load_customerbase_inquiry_item_table', $data);
    }*/

    function load_customer_inquiry_detail_items_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $inquiryID = $this->input->post('inquiry_ID');
        $orderID = join($this->input->post('orderID'), ",");

        $where = "srp_erp_srm_customerorderdetails.companyID = '{$companyID}' AND srp_erp_srm_customerorderdetails.customerOrderID IN ($orderID)";

        $this->db->select('srp_erp_itemmaster.itemAutoID,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode,srp_erp_srm_customerorderdetails.customerOrderID,srp_erp_srm_customerordermaster.customerOrderCode,srp_erp_srm_customerorderdetails.customerOrderID');
        $this->db->from('srp_erp_srm_customerorderdetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_customerorderdetails.itemAutoID', 'LEFT');
        $this->db->join('srp_erp_srm_customerordermaster', 'srp_erp_srm_customerordermaster.customerOrderID = srp_erp_srm_customerorderdetails.customerOrderID', 'LEFT');
        $this->db->where($where);
        $data['header'] = $this->db->get()->result_array();
        $data['inquiryID'] = $inquiryID;

        $this->load->view('system/srm/customer-order/ajax/load_customerbase_inquiry_item_table', $data);
    }

    function load_customer_inquiry_detail_sellars_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $inquiryMasterID = trim($this->input->post('inquiryID'));

        $where = "srp_erp_srm_inquiryitem.companyID = '{$companyID}' AND srp_erp_srm_inquiryitem.isChecked = 1 AND srp_erp_srm_inquiryitem.inquiryMasterID = '{$inquiryMasterID}'";

        $this->db->select('*,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode');
        $this->db->from('srp_erp_srm_inquiryitem');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_inquiryitem.itemAutoID', 'LEFT');
        $this->db->join('srp_erp_srm_customerorderdetails', 'srp_erp_srm_customerorderdetails.itemAutoID = srp_erp_srm_inquiryitem.itemAutoID', 'LEFT');

        $this->db->where($where);
        $this->db->group_by('srp_erp_srm_inquiryitem.itemAutoID');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_orderbase_inquiry_supplier_table', $data);
    }

    function save_order_inquiry()
    {
        $this->form_validation->set_rules('customerID', 'Customer Name', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array('e', validation_errors()));

        } else {
            echo json_encode($this->Srm_master_model->save_order_inquiry());
        }
    }

    function load_OrderID_BaseCurrency()
    {
        echo json_encode($this->Srm_master_model->load_OrderID_BaseCurrency());
    }

    function save_order_inquiry_itemDetail()
    {
        $this->form_validation->set_rules('inquiryID', 'InquiryID', 'trim|required');
        //$this->form_validation->set_rules('orderID', 'Order ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->save_order_inquiry_itemDetail());
        }
    }

    function ajax_update_orderInquiry_supplier()
    {
        $result = $this->Srm_master_model->xeditable_update('srp_erp_srm_orderinquirydetails', 'inquiryDetailID');
        if ($result) {
            echo json_encode(array('error' => 0, 'message' => 'updated'));
        } else {
            echo json_encode(array('error' => 1, 'message' => 'updated Fail'));
        }
    }

    function order_inquiry_generate_supplier_rfq()
    {
        $this->form_validation->set_rules('deliveryTerms', 'Delivery Terms', 'trim|required');
        $this->form_validation->set_rules('inquiryID', 'InquiryID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->order_inquiry_generate_supplier_rfq());
        }
    }

    function load_orderbase_generated_rfq_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $inquiryMasterID = trim($this->input->post('inquiryID'));

        $where = "srp_erp_srm_orderinquirydetails.companyID = '{$companyID}' AND srp_erp_srm_orderinquirydetails.inquiryMasterID = '{$inquiryMasterID}' AND isRfqCreated = 1";

        $this->db->select('*,supplierSystemCode,supplierName');
        $this->db->from('srp_erp_srm_orderinquirydetails');
        $this->db->join('srp_erp_srm_suppliermaster', 'srp_erp_srm_orderinquirydetails.supplierID = srp_erp_srm_suppliermaster.supplierAutoID', 'LEFT');
        $this->db->where($where);
        $this->db->group_by('srp_erp_srm_orderinquirydetails.supplierID');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_orderbase_generated_rfq_table', $data);
    }

    function supplier_rfq_print_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $inquiryMasterID = trim($this->input->post('inquiryMasterID'));
        $supplierID = trim($this->input->post('supplierID'));
        $where_header = "srp_erp_srm_orderinquirymaster.companyID = '{$companyID}' AND srp_erp_srm_orderinquirymaster.inquiryID = '{$inquiryMasterID}'";
        $this->db->select('documentCode,deliveryTerms,DATE_FORMAT(documentDate,"' . $convertFormat . '") AS inquiryDocumentDate');
        $this->db->from('srp_erp_srm_orderinquirymaster');
        $this->db->where($where_header);
        $data['header'] = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from('srp_erp_srm_suppliermaster');
        $this->db->where('supplierAutoID', $supplierID);
        $data['supplier'] = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from('srp_erp_company');
        $this->db->where('company_id', $companyID);
        $data['company'] = $this->db->get()->row_array();

        $where_detail = "srp_erp_srm_orderinquirydetails.companyID = '{$companyID}' AND srp_erp_srm_orderinquirydetails.inquiryMasterID = '{$inquiryMasterID}' AND srp_erp_srm_orderinquirydetails.supplierID = '{$supplierID}'";
        $this->db->select('*,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode,UnitShortCode,DATE_FORMAT(srp_erp_srm_orderinquirydetails.expectedDeliveryDate,"' . $convertFormat . '") AS expectedDeliveryDate');
        $this->db->from('srp_erp_srm_orderinquirydetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_orderinquirydetails.itemAutoID', 'LEFT');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_srm_orderinquirydetails.defaultUOMID = srp_erp_unit_of_measure.UnitID', 'LEFT');
        $this->db->where($where_detail);
        $data['detail'] = $this->db->get()->result_array();
        $html = $this->load->view('system/srm/customer-order/srm_order_inquiry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function load_customer_BaseDetail()
    {
        echo json_encode($this->Srm_master_model->load_customer_BaseDetail());
    }

    function assignItems_supplier_orderInquiry()
    {
        echo json_encode($this->Srm_master_model->assignItems_supplier_orderInquiry());
    }

    function delete_customer_order_detail()
    {
        echo json_encode($this->Srm_master_model->delete_customer_order_detail());
    }

    function load_inquiry_reviewHeader()
    {
        echo json_encode($this->Srm_master_model->load_inquiry_reviewHeader());
    }

    function load_order_multiple_attachemts()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $documentAutoID = trim($this->input->post('customerOrderID'));

        $where = "companyID = " . $companyid . " AND documentID = 3 AND documentAutoID = " . $documentAutoID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_srm_attachments');
        $this->db->where($where);
        $this->db->order_by('attachmentID', 'desc');
        $data['attachment'] = $this->db->get()->result_array();
        $this->load->view('system/srm/customer-order/ajax/load_order_multiple_attachement', $data);
    }

    function delete_srm_attachment()
    {
        $attachmentID = $this->input->post('attachmentID');
        $myFileName = $this->input->post('myFileName');
        $url = base_url("attachments/SRM");
        $link = "$url/$myFileName";
        if (!unlink(UPLOAD_PATH . $link)) {
            echo json_encode(false);
        } else {
            $this->db->delete('srp_erp_srm_attachments', array('attachmentID' => trim($attachmentID)));
            echo json_encode(true);
        }
    }

    function attachement_upload()
    {
        $this->form_validation->set_rules('attachmentDescription', 'Attachment Description', 'trim|required');
        $this->form_validation->set_rules('documentID', 'documentID', 'trim|required');
        $this->form_validation->set_rules('documentAutoID', 'Document Auto ID', 'trim|required');

        //$this->form_validation->set_rules('document_file', 'File', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'message' => validation_errors()));
        } else {

            $this->db->trans_start();
            $this->db->select('companyID');
            $this->db->where('documentID', trim($this->input->post('documentID')));
            $num = $this->db->get('srp_erp_srm_attachments')->result_array();
            $file_name = $this->input->post('document_name') . '_' . $this->input->post('documentID') . '_' . (count($num) + 1);
            $config['upload_path'] = realpath(APPPATH . '../attachments/SRM');
            $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|docx|ppt|pptx|ppsx|pdf|xls|xlsx|xlsxm|rtf|msg|txt|7zip|zip|rar';
            $config['max_size'] = '5120'; // 5 MB
            $config['file_name'] = $file_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload("document_file")) {
                echo json_encode(array('status' => 0, 'type' => 'w', 'message' => 'Upload failed ' . $this->upload->display_errors()));
            } else {
                $upload_data = $this->upload->data();
                $data['documentID'] = trim($this->input->post('documentID'));
                $data['documentAutoID'] = trim($this->input->post('documentAutoID'));
                $data['attachmentDescription'] = trim($this->input->post('attachmentDescription'));
                $data['myFileName'] = $file_name . $upload_data["file_ext"];
                $data['fileType'] = trim($upload_data["file_ext"]);
                $data['fileSize'] = trim($upload_data["file_size"]);
                $data['timestamp'] = date('Y-m-d H:i:s');
                $data['companyID'] = $this->common_data['company_data']['company_id'];
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['modifiedPCID'] = $this->common_data['current_pc'];
                $data['modifiedUserID'] = $this->common_data['current_userID'];
                $data['modifiedUserName'] = $this->common_data['current_user'];
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_srm_attachments', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 0, 'type' => 'e', 'message' => 'Upload failed ' . $this->db->_error_message()));
                } else {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 1, 'type' => 's', 'message' => 'Successfully ' . $file_name . ' uploaded.'));
                }
            }
        }
    }

    function assignItem_supplier_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $itemAutoID = $this->input->post('itemAutoID');
        $data['supplier'] = $this->db->query("SELECT * FROM srp_erp_srm_suppliermaster where companyID = {$companyID} AND supplierAutoID NOT IN (SELECT supplierAutoID FROM srp_erp_srm_supplieritems WHERE itemAutoID = {$itemAutoID} AND companyID = {$companyID} )")->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_suppliers_forAssign_item', $data);
    }

    function load_OrderInquiry_editView()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $inquiryID = trim($this->input->post('inquiryID'));
        $this->db->select('srp_erp_srm_orderinquirymaster.inquiryID,srp_erp_srm_orderinquirymaster.documentCode,DATE_FORMAT(srp_erp_srm_orderinquirymaster.documentDate,\'' . $convertFormat . '\') AS documentDate,srp_erp_srm_customermaster.CustomerName,CurrencyCode,narration,srp_erp_srm_orderinquirymaster.confirmedYN as confirmStatus');
        $this->db->from('srp_erp_srm_orderinquirymaster');
        $this->db->join('srp_erp_srm_customermaster', 'srp_erp_srm_customermaster.CustomerAutoID = srp_erp_srm_orderinquirymaster.customerID', 'LEFT');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_srm_orderinquirymaster.transactionCurrencyID = srp_erp_currencymaster.currencyID', 'LEFT');
        //$this->db->join('srp_employeesdetails', 'srp_employeesdetails.EIdNo = srp_erp_crm_task.completedBy', 'LEFT');
        //$this->db->join('srp_erp_crm_project', 'srp_erp_crm_project.projectID = srp_erp_crm_task.projectID', 'LEFT');
        $this->db->where('inquiryID', $inquiryID);
        $data['header'] = $this->db->get()->row_array();

        $where_rfq = "srp_erp_srm_orderinquirydetails.companyID = '{$companyID}' AND srp_erp_srm_orderinquirydetails.inquiryMasterID = '{$inquiryID}' AND isRfqCreated = 1";

        $this->db->select('*,supplierSystemCode,supplierName');
        $this->db->from('srp_erp_srm_orderinquirydetails');
        $this->db->join('srp_erp_srm_suppliermaster', 'srp_erp_srm_orderinquirydetails.supplierID = srp_erp_srm_suppliermaster.supplierAutoID', 'LEFT');
        $this->db->where($where_rfq);
        $this->db->group_by('srp_erp_srm_orderinquirydetails.supplierID');
        $data['detailrfq'] = $this->db->get()->result_array();


        $this->load->view('system/srm/customer-order/ajax/load_order_inquiry_edit_view', $data);
    }

    function load_order_master_editView()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $customerOrderID = trim($this->input->post('customerOrderID'));
        $this->db->select('srp_erp_srm_customerordermaster.customerOrderID,srp_erp_srm_customerordermaster.customerOrderCode,DATE_FORMAT(srp_erp_srm_customerordermaster.documentDate,\'' . $convertFormat . '\') AS documentDate,DATE_FORMAT(srp_erp_srm_customerordermaster.expiryDate,\'' . $convertFormat . '\') AS expiryDate,srp_erp_srm_customermaster.CustomerName,CurrencyCode,narration,srp_erp_srm_customerordermaster.status as orderStatus,contactPersonNumber,CustomerAddress,referenceNumber,srp_erp_srm_status.description as statusDescription,backgroundColor,fontColor');
        $this->db->from('srp_erp_srm_customerordermaster');
        $this->db->join('srp_erp_srm_customermaster', 'srp_erp_srm_customermaster.CustomerAutoID = srp_erp_srm_customerordermaster.customerID', 'LEFT');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_srm_customerordermaster.transactionCurrencyID = srp_erp_currencymaster.currencyID', 'LEFT');
        $this->db->join('srp_erp_srm_status', 'srp_erp_srm_customerordermaster.status = srp_erp_srm_status.statusID', 'LEFT');
        $this->db->where('customerOrderID', $customerOrderID);
        $data['header'] = $this->db->get()->row_array();

        $where_rfq = "srp_erp_srm_orderinquirydetails.companyID = '{$companyID}' AND srp_erp_srm_orderinquirydetails.customerOrderID = '{$customerOrderID}' AND isRfqCreated = 1";

        $this->db->select('*,supplierSystemCode,supplierName');
        $this->db->from('srp_erp_srm_orderinquirydetails');
        $this->db->join('srp_erp_srm_suppliermaster', 'srp_erp_srm_orderinquirydetails.supplierID = srp_erp_srm_suppliermaster.supplierAutoID', 'LEFT');
        $this->db->where($where_rfq);
        $this->db->group_by('srp_erp_srm_orderinquirydetails.supplierID');
        $data['detailrfq'] = $this->db->get()->result_array();

        $this->db->select('*,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode');
        $this->db->from('srp_erp_srm_customerorderdetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_customerorderdetails.itemAutoID', 'LEFT');
        $this->db->where('srp_erp_srm_customerorderdetails.companyID', $companyID);
        $this->db->where('srp_erp_srm_customerorderdetails.customerOrderID', $customerOrderID);
        $data['orderitem'] = $this->db->get()->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_order_master_edit_view', $data);
    }

    function load_customer_order_all_notes()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $customerOrderID = trim($this->input->post('customerOrderID'));

        $where = "companyID = " . $companyid . " AND documentID = 3 AND documentAutoID = " . $customerOrderID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_srm_notes');
        $this->db->where($where);
        $this->db->order_by('notesID', 'desc');
        $data['notes'] = $this->db->get()->result_array();
        $this->load->view('system/srm/customer-order/ajax/load_customer_order_notes', $data);
    }

    function add_customer_order_notes()
    {
        $this->form_validation->set_rules('customerOrderID', 'Order ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->add_customer_order_notes());
        }
    }

    function send_rfq_email_suppliers()
    {
        $this->form_validation->set_rules('inquiryMasterID', 'Inquiry ID', 'trim|required');
        $this->form_validation->set_rules('supplierID', 'Supplier ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->send_rfq_email_suppliers());
        }
    }

    function order_review_detail_view()
    {
        $convertFormat = convert_date_format_sql();
        $inquiryID = trim($this->input->post('inquiryID'));

        $this->db->select('*,srp_erp_itemmaster.itemName,srp_erp_itemmaster.itemSystemCode,UnitShortCode');
        $this->db->where('inquiryMasterID', $inquiryID);
        $this->db->where('isSupplierSubmited', 1);
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_srm_orderinquirydetails.itemAutoID', 'LEFT');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_srm_orderinquirydetails.defaultUOMID = srp_erp_unit_of_measure.UnitID', 'LEFT');
        $this->db->group_by('srp_erp_srm_orderinquirydetails.itemAutoID');
        $data['item'] = $this->db->get('srp_erp_srm_orderinquirydetails')->result_array();

        $this->load->view('system/srm/customer-order/ajax/load_order_review_detail', $data);
    }

    function generate_order_review_supplier()
    {
        $this->form_validation->set_rules('inquiryID', 'InquiryID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->generate_order_review_supplier());
        }
    }

    function supplier_image_upload()
    {
        $this->form_validation->set_rules('supplierAutoID', 'Supplier ID is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Srm_master_model->supplier_image_upload());
        }
    }

}