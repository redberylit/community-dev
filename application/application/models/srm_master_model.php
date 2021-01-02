<?php

class Srm_master_model extends ERP_Model
{


    function __construct()
    {
        parent::__construct();
        $this->load->helper('srm');
    }

    function save_customer()
    {
        $this->db->trans_start();
        $liability = fetch_gl_account_desc(trim($this->input->post('receivableAccount')));
        $currency_code = explode('|', trim($this->input->post('currency_code')));

        $data['secondaryCode'] = trim($this->input->post('customercode'));
        $data['customerName'] = trim($this->input->post('customerName'));
        $data['customerCurrencyID'] = trim($this->input->post('customerCurrency'));
        $data['customerCurrency'] = trim($currency_code[0]);

        $data['customerCountry'] = trim($this->input->post('customercountry'));
        $data['customerTelephone'] = trim($this->input->post('customerTelephone'));
        $data['customeremail'] = trim($this->input->post('customerEmail'));
        $data['customerFax'] = trim($this->input->post('customerFax'));
        $data['customerAddress1'] = trim($this->input->post('customerAddress1'));
        $data['customerAddress2'] = trim($this->input->post('customerAddress2'));
        $data['customerUrl'] = trim($this->input->post('customerUrl'));
        $data['isActive'] = trim($this->input->post('isActive'));


        if (trim($this->input->post('customerAutoID'))) {
            $this->db->where('customerAutoID', trim($this->input->post('customerAutoID')));
            $this->db->update('srp_erp_srm_customermaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                //$this->session->set_flashdata('e', 'customer : ' . $data['customerName'] . ' Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => 'e', 'message' => 'customer : ' . $data['customerName'] . ' Update Failed' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('status' => 's', 'message' => 'Customer Updated Successfully');
            }

        } else {
            $data['companyID'] = current_companyID();
            $data['createdUserID'] = current_userID();
            $data['createdUserName'] = current_user();
            $data['createdDateTime'] = format_date_mysql_datetime();
            $data['createdPCID'] = current_pc();
//
            $data['companyCode'] = current_companyCode();
            $data['createdUserGroup'] = user_group();

            $data['CustomerSystemCode'] = $this->sequence->sequence_generator('SRM-CUS');


            //$data['createdUserGroup'] = user_group();
            $data['timestamp'] = format_date_mysql_datetime();

            $this->db->insert('srp_erp_srm_customermaster', $data);

//        echo  $this->db->last_query();
//        exit;
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('status' => 's', 'message' => 'Customer Addition Failed');
            } else {
                $this->db->trans_commit();
                return array('status' => 's', 'message' => 'Customer Add Successfully');
            }

        }


    }

    function save_supplier()
    {
        $this->db->trans_start();
        $liability = fetch_gl_account_desc(trim($this->input->post('receivableAccount')));
        $currency_code = explode('|', trim($this->input->post('currency_code')));
        $data['secondaryCode'] = trim($this->input->post('suppliercode'));
        $data['supplierName'] = trim($this->input->post('supplierName'));
        $data['supplierCurrencyID'] = trim($this->input->post('supplierCurrency'));
        $data['supplierCurrency'] = trim($currency_code[0]);

        $data['supplierCountry'] = trim($this->input->post('suppliercountry'));
        $data['supplierTelephone'] = trim($this->input->post('supplierTelephone'));
        $data['supplieremail'] = trim($this->input->post('supplierEmail'));
        $data['supplierFax'] = trim($this->input->post('supplierFax'));
        $data['supplierAddress1'] = trim($this->input->post('supplierAddress1'));
        $data['supplierAddress2'] = trim($this->input->post('supplierAddress2'));
        $data['supplierUrl'] = trim($this->input->post('supplierUrl'));
        $data['isActive'] = trim($this->input->post('isActive'));
        if (trim($this->input->post('supplierAutoID'))) {
            $this->db->where('supplierAutoID', trim($this->input->post('supplierAutoID')));
            $this->db->update('srp_erp_srm_suppliermaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                return array('status' => 'e', 'message' => 'Supplier : ' . $data['supplierName'] . ' Update Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('status' => 's', 'message' => 'Supplier : ' . $data['supplierName'] . ' Saved  Successfully');
            }

        } else {
            $data['companyID'] = current_companyID();
            $data['createdUserID'] = current_userID();
            $data['createdUserName'] = current_user();
            $data['createdDateTime'] = format_date_mysql_datetime();
            $data['createdPCID'] = current_pc();
            $data['createdUserGroup'] = current_user_group();
            $data['companyCode'] = current_companyCode();
            $data['supplierSystemCode'] = $this->sequence->sequence_generator('SRM-SUP');
            $data['createdUserGroup'] = user_group();
            $data['timestamp'] = format_date_mysql_datetime();

            $this->db->insert('srp_erp_srm_suppliermaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('status' => 's', 'message' => 'Supplier Save Failed');
            } else {
                $this->db->trans_commit();
                return array('status' => 's', 'message' => 'Supplier Saved Successfully');
            }

        }


    }


    function save_supplierItem()
    {
        $supplierAutoID = trim($this->input->post('supplierAutoID'));
        $itemAutoID = trim($this->input->post('itemAutoID'));
        $this->db->select('*');
        $this->db->where('itemAutoID', $itemAutoID);
        $this->db->where('supplierAutoID', $supplierAutoID);
        $output = $this->db->get('srp_erp_srm_supplieritems')->row_array();

        if (empty($output)) {
            $this->db->trans_start();
            $data['supplierAutoID'] = $supplierAutoID;
            $data['itemAutoID'] = $itemAutoID;
            $data['companyID'] = current_companyID();
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['timestamp'] = format_date_mysql_datetime();
            $this->db->insert('srp_erp_srm_supplieritems', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $error = $this->db->_error_message();
                return array('error' => 1, 'message' => 'Error: ' . $error);

            } else {
                $this->db->trans_commit();
                return array('error' => 0, 'message' => 'Record Added Successfully.', 'code' => $supplierAutoID);
            }

        } else {
            return array('error' => 1, 'message' => 'This Item is already added');
        }


    }

    function load_customer_header()
    {
        $this->db->select('*');
        $this->db->where('customerAutoID', $this->input->post('customerAutoID'));
        return $this->db->get('srp_erp_srm_customermaster')->row_array();
    }

    function load_supplier_header()
    {
        $this->db->select('*');
        $this->db->where('supplierAutoID', $this->input->post('supplierAutoID'));
        return $this->db->get('srp_erp_srm_suppliermaster')->row_array();
    }

    function load_supplier_items_details()
    {
        /*get post value */
        $supplierID = $this->input->post('supplierID');
        /*modal function */
        /* query from database table => srp_erp_srm_supplieritems where  supplierAutoID , join with item master */
        $where = "masterTbl.supplierAutoID = $supplierID AND masterTbl.isDeleted = 0 AND (itemmaster.financeCategory = 1 OR itemmaster.financeCategory = 2)";
        $this->db->select('masterTbl.supplierItemID,masterTbl.isDeleted,category.catDescription,itemmaster.mainCategory,itemmaster.seconeryItemCode,itemmaster.itemDescription,itemmaster.isActive,itemmaster.itemSystemCode');
        $this->db->from('srp_erp_srm_supplieritems masterTbl');
        $this->db->join('srp_erp_itemmaster itemmaster', 'masterTbl.itemAutoID = itemmaster.itemAutoID ', 'left');
        $this->db->join('srp_erp_fa_category category', 'itemmaster.subcategoryID = category.faCatID ', 'left');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;

    }

    /*SELECT `itmmaster`.*, `category`.`catDescription` FROM `srp_erp_itemmaster` `itmmaster
    ` LEFT JOIN `srp_erp_fa_category` `category` ON `itmmaster`.`subcategoryID`= `category`.`faCatID` LIMIT 10*/

    function load_supplier_itemsmaster()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $keyword = $this->input->post('keyword');
        $supplierID = $this->input->post('supplierID');
        $where = "itemmaster.companyID = $companyid AND (itemmaster.financeCategory = 1 OR itemmaster.financeCategory = 2)";
        $this->db->select('itemmaster.*,category.catDescription,supplierItem.supplierItemID');
        $this->db->from('srp_erp_itemmaster itemmaster');
        $this->db->join('srp_erp_fa_category category', 'itemmaster.subcategoryID= category.faCatID', 'left');
        //$this->db->join('srp_erp_srm_supplieritems supplierItem', 'supplierItem.itemAutoID = itemmaster.itemAutoID AND  supplierItem.supplierAutoID=' . $supplierID, 'left');
        $this->db->join('(SELECT * FROM srp_erp_srm_supplieritems WHERE `supplierAutoID` = ' . $supplierID . '  ) AS supplierItem', '`supplierItem`.`itemAutoID` = `itemmaster`.`itemAutoID`', 'left');
        $this->db->where($where);
        if (isset($keyword) && !empty($keyword)) {
            $this->db->like('itemmaster.itemDescription', $keyword);
        }
        $this->db->limit(10);

        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $result;

        /*out put => json array */
//        echo json_encode($this->Srm_master_model->load_supplier_items_details());


    }


    function delete_supplier_item()
    {
        $supplierItemID = $this->input->post('supplierItemID');
        $this->db->where('supplierItemID', $supplierItemID);
        $results = $this->db->delete('srp_erp_srm_supplieritems');
        if ($results) {
            return array('error' => 0, 'message' => 'Record Deleted Successfully ');

        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact the system team!');
        }
    }


    function delete_supplier()
    {
        $supplierAutoID = trim($this->input->post('supplierID'));

        $this->db->select('*');
        $this->db->where('supplierAutoID', $supplierAutoID);
        $output = $this->db->get('srp_erp_srm_suppliermaster')->row_array();
        if (empty($output)) {
            $this->db->where('supplierAutoID', $supplierAutoID);
            $results = $this->db->delete('srp_erp_srm_suppliermaster');
            if ($results) {
                return array('s', 'Record Deleted Successfully !');

            } else {
                return array('e', 'Error in Record deleting');
            }
        } else {
            return array('w', 'This supplier has item assigned, please remove all the items before deleting the supplier');

        }

    }

    function delete_customer()
    {
        $CustomerAutoID = trim($this->input->post('customerID'));
        $this->db->where('CustomerAutoID', $CustomerAutoID);
        $results = $this->db->delete('srp_erp_srm_customermaster');
        if ($results) {
            return array('s', 'Record Deleted Successfully');

        } else {
            return array('e', 'Error while deleting, please contact the system team!');
        }
    }

    function save_customer_order_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $this->load->library('sequence');

        $companyID = $this->common_data['company_data']['company_id'];
        $customerOrderID = trim($this->input->post('customerOrderID'));
        $cus_order_code = $this->sequence->sequence_generator('SRM-ORD');

        $documentDate = trim($this->input->post('documentDate'));
        $expiryDate = trim($this->input->post('expiryDate'));
        $confirmedYN = trim($this->input->post('confirmedYN'));

        $format_documentDate = null;
        if (isset($documentDate) && !empty($documentDate)) {
            $format_documentDate = input_format_date($documentDate, $date_format_policy);
        }
        $format_expiryDate = null;
        if (isset($expiryDate) && !empty($expiryDate)) {
            $format_expiryDate = input_format_date($expiryDate, $date_format_policy);
        }
        if (isset($confirmedYN) && $confirmedYN == 1) {
            $data["status"] = 2;
            $data["confirmedYN"] = 1;
            $data["confirmedDate"] = $this->common_data['current_date'];
            $data["confirmedByEmpID"] = $this->common_data['current_userID'];
            $data["confirmedByName"] = $this->common_data['current_user'];
        }
        $data["documentID"] = 3;
        $data["contactPersonName"] = $this->input->post('customer_name');
        $data["contactPersonNumber"] = $this->input->post('customerTelephone');
        $data["customerID"] = $this->input->post('customerID');
        $data["CustomerAddress"] = $this->input->post('CustomerAddress1');
        $data["documentDate"] = $format_documentDate;
        $data["expiryDate"] = $format_expiryDate;
        $data["narration"] = $this->input->post('narration');
        $data["referenceNumber"] = $this->input->post('ref_number');
        $data["paymentTerms"] = $this->input->post('payment_term');
        //$data["status"] = trim($this->input->post('statusID'));
        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];

        if ($customerOrderID) {

            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('customerOrderID', $customerOrderID);
            $this->db->update('srp_erp_srm_customerordermaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Order Update Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Order Updated Successfully.');
            }
        } else {
            $data["customerOrderCode"] = $cus_order_code;
            $data["status"] = 1;
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_srm_customerordermaster', $data);
            $last_id = $this->db->insert_id();

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Order Save Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Order Added Successfully.', $last_id);

            }
        }
    }

    function add_supplier_notes()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];

        $SupplierAutoID = trim($this->input->post('supplierAutoID'));

        $data['documentAutoID'] = $SupplierAutoID;
        $data['description'] = trim($this->input->post('description'));
        $data['companyID'] = $companyID;
        $data['documentID'] = 1;
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdUserName'] = $this->common_data['current_user'];
        $data['createdDateTime'] = $this->common_data['current_date'];
        $this->db->insert('srp_erp_srm_notes', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Supplier Note Save Failed ' . $this->db->_error_message(), $last_id);
        } else {
            $this->db->trans_commit();
            return array('s', 'Supplier Note Saved Successfully.');

        }
    }

    function load_customer_order_autoGeneratedID()
    {
        $customerOrderID = trim($this->input->post('customerOrderID'));

        $lastID = $this->db->query('SELECT customerOrderCode FROM srp_erp_srm_customerordermaster WHERE customerOrderID = ' . $customerOrderID . '')->row_array();
        $cus_order_code = $lastID['customerOrderCode'];
        return array($cus_order_code);
    }

    function save_customer_ordermaster_add()
    {

        $this->db->trans_start();
        $this->load->library('sequence');
        $companyID = $this->common_data['company_data']['company_id'];

        $cus_order_code = $this->sequence->sequence_generator('SRM-ORD');

        $data["customerOrderCode"] = $cus_order_code;
        $data["status"] = 1;
        $data['companyID'] = $companyID;
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdUserName'] = $this->common_data['current_user'];
        $data['createdDateTime'] = $this->common_data['current_date'];
        $this->db->insert('srp_erp_srm_customerordermaster', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Customer Order Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Customer Order Created Successfully.', $last_id);

        }
    }

    function save_customer_order_detail()
    {
        $date_format_policy = date_format_policy();
        $companyID = $this->common_data['company_data']['company_id'];
        $customerOrderID = trim($this->input->post('customerOrderID_orderDetail'));
        $itemAutoIDs = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $uom = $this->input->post('uom');
        $estimatedAmount = $this->input->post('estimatedAmount');
        $quantityRequested = $this->input->post('quantityRequested');
        $expectedDeliveryDate = $this->input->post('expectedDeliveryDate');
        $comment = $this->input->post('comment');

        $this->db->trans_start();

        foreach ($itemAutoIDs as $key => $itemAutoID) {

            $format_expectedDeliveryDate = null;
            if (isset($expectedDeliveryDate[$key]) && !empty($expectedDeliveryDate[$key])) {
                $format_expectedDeliveryDate = input_format_date($expectedDeliveryDate[$key], $date_format_policy);
            }
            $item_arr = fetch_item_data($itemAutoID);
            $uomEx = explode('|', $uom[$key]);

            $this->db->select('itemAutoID');
            $this->db->from('srp_erp_srm_customerorderdetails');
            $this->db->where('customerOrderID', $customerOrderID);
            $this->db->where('itemAutoID', $itemAutoID);
            $this->db->where('companyID', $companyID);
            $order_detail = $this->db->get()->row_array();
            if (!empty($order_detail)) {
                return array('w', 'Ordered Item already exists.');
                exit();
            }

            $data['customerOrderID'] = $customerOrderID;
            $data['itemAutoID'] = $itemAutoID;
            $data['unitOfMeasureID'] = $UnitOfMeasureID[$key];
            $data['defaultUOMID'] = $item_arr['defaultUnitOfMeasureID'];
            $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
            $data['requestedQty'] = $quantityRequested[$key];
            $data['expectedDeliveryDate'] = $format_expectedDeliveryDate;
            $data['unitAmount'] = ($estimatedAmount[$key]);
            $data['totalAmount'] = ($data['unitAmount'] * $quantityRequested[$key]);
            $data['comment'] = $comment[$key];

            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_srm_customerorderdetails', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Customer Order Details :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Customer Order Details :  Saved Successfully.');
        }

    }

    function load_customerOrder_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->where('customerOrderID', $this->input->post('customerOrderID'));
        $data['header'] = $this->db->get('srp_erp_srm_customerordermaster')->row_array();
        return $data;
    }


    function load_customerInquiry_header()
    {
        $convertFormat = convert_date_format_sql();
        $inquiryID = trim($this->input->post('inquiryID'));
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->where('inquiryID', $inquiryID);
        $data['header'] = $this->db->get('srp_erp_srm_orderinquirymaster')->row_array();

        $this->db->select('customerOrderID');
        $this->db->where('inquiryMasterID', $inquiryID);
        $this->db->group_by('customerOrderID');
        $orderID = $this->db->get('srp_erp_srm_orderinquirydetails')->result_array();
        $data['orders'] = array_values($orderID);

        $this->db->select('itemAutoID');
        $this->db->where('inquiryMasterID', $inquiryID);
        $this->db->where('isChecked', 1);
        $data['orderItem'] = $this->db->get('srp_erp_srm_inquiryitem')->result_array();
        //$data['orderItem'] = array_values($orderItem);
        return $data;
    }

    function delete_customer_order_master()
    {
        $customerOrderID = trim($this->input->post('customerOrderID'));
        $this->db->delete('srp_erp_srm_customerordermaster', array('customerOrderID' => $customerOrderID));
        $this->db->delete('srp_erp_srm_customerorderdetails', array('customerOrderID' => $customerOrderID));
        return true;
    }

    function delete_customer_inquiry_master()
    {
        $inquiryID = trim($this->input->post('inquiryID'));
        $this->db->delete('srp_erp_srm_orderinquirymaster', array('inquiryID' => $inquiryID));
        $this->db->delete('srp_erp_srm_orderinquirydetails', array('inquiryMasterID' => $inquiryID));
        $this->db->delete('srp_erp_srm_inquiryitem', array('inquiryMasterID' => $inquiryID));
        return true;
    }

    function delete_customer_order_detail()
    {
        $customerOrderDetailsID = trim($this->input->post('customerOrderDetailsID'));
        $this->db->delete('srp_erp_srm_customerorderdetails', array('customerOrderDetailsID' => $customerOrderDetailsID));
        return true;
    }

    function load_customerOrder_BaseItem()
    {
        $this->db->select('srp_erp_srm_customerorderdetails.itemAutoID,srp_erp_itemmaster.itemDescription');
        $this->db->where('customerOrderID', $this->input->post('customerOrderID'));
        $this->db->where('srp_erp_srm_customerorderdetails.companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_srm_customerorderdetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_srm_customerorderdetails.itemAutoID = srp_erp_itemmaster.itemAutoID', 'LEFT');
        return $subcat = $this->db->get()->result_array();
    }

    function save_order_inquiry()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();

        $this->load->library('sequence');
        $documentCode = $this->sequence->sequence_generator('SRM-ORD-INQ');

        $companyID = $this->common_data['company_data']['company_id'];
        $inquiryID = trim($this->input->post('inquiryID'));

        $documentDate = trim($this->input->post('documentDate'));
        $format_documentDate = null;
        if (isset($documentDate) && !empty($documentDate)) {
            $format_documentDate = input_format_date($documentDate, $date_format_policy);
        }

        $data["customerID"] = trim($this->input->post('customerID'));
        //$data["customerOrderID"] = trim($this->input->post('customer_orderID'));
        $data["transactionCurrencyID"] = trim($this->input->post('transactionCurrencyID'));
        $data["documentDate"] = $format_documentDate;
        $data["narration"] = trim($this->input->post('narration'));;
        $data["documentCode"] = $documentCode;
        $data["documentID"] = 6;

        if ($inquiryID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('inquiryID', $inquiryID);
            $this->db->update('srp_erp_srm_orderinquirymaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Order Inquiry Update Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Order Inquiry Updated Successfully.');
            }
        } else {
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_srm_orderinquirymaster', $data);
            $last_id = $this->db->insert_id();

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Order Inquiry Save Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Order Inquiry Added Successfully.', $last_id);

            }
        }
    }

    function load_OrderID_BaseCurrency()
    {
        $this->db->select('transactionCurrencyID');
        $this->db->where('customerOrderID', $this->input->post('customerOrderID'));
        return $this->db->get('srp_erp_srm_customerordermaster')->row_array();
    }

    function save_order_inquiry_itemDetail()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];
        $inquiryID = trim($this->input->post('inquiryID'));
        $orderID = trim($this->input->post('orderID'));
        $itemAutoIDs = $this->input->post('selectedItemsSync');
        $resault = $this->db->delete('srp_erp_srm_inquiryitem', array('inquiryMasterID' => $inquiryID));
        $resault = $this->db->delete('srp_erp_srm_orderinquirydetails', array('inquiryMasterID' => $inquiryID));
        if ($resault) {
            foreach ($itemAutoIDs as $key => $itemAutoID) {
                $autoID = explode('_', $itemAutoID);
                $suppliers = $this->db->query("SELECT supplierAutoID FROM srp_erp_srm_supplieritems WHERE companyID = " . $companyID . " AND itemAutoID = " . $autoID[0] . "")->result_array();

                $orderItems = $this->db->query("SELECT itemAutoID FROM srp_erp_srm_customerorderdetails WHERE companyID = " . $companyID . " AND customerOrderID = " . $autoID[1] . "")->result_array();

                $orderQty = $this->db->query("SELECT requestedQty,expectedDeliveryDate,defaultUOMID FROM srp_erp_srm_customerorderdetails WHERE customerOrderID = " . $autoID[1] . " AND itemAutoID = " . $autoID[0] . "")->row_array();

                if (!empty($orderItems)) {
                    foreach ($orderItems as $itm) {
                        $data_item["itemAutoID"] = $itm['itemAutoID'];
                        $data_item['inquiryMasterID'] = $inquiryID;
                        $data_item['orderMasterID'] = $autoID[1];
                        $data_item['companyID'] = $companyID;
                        $data_item['createdUserGroup'] = $this->common_data['user_group'];
                        $data_item['createdPCID'] = $this->common_data['current_pc'];
                        $data_item['createdUserID'] = $this->common_data['current_userID'];
                        $data_item['createdUserName'] = $this->common_data['current_user'];
                        $data_item['createdDateTime'] = $this->common_data['current_date'];
                        $this->db->insert('srp_erp_srm_inquiryitem', $data_item);
                    }
                }

                if (!empty($suppliers)) {
                    foreach ($suppliers as $val) {
                        $data["inquiryMasterID"] = $inquiryID;
                        $data["itemAutoID"] = $autoID[0];
                        $data["supplierID"] = $val['supplierAutoID'];
                        $data["customerOrderID"] = $autoID[1];
                        $data["defaultUOMID"] = $orderQty['defaultUOMID'];
                        $data["requestedQty"] = $orderQty['requestedQty'];
                        $data["expectedDeliveryDate"] = $orderQty['expectedDeliveryDate'];
                        $data['companyID'] = $companyID;
                        $data['createdUserGroup'] = $this->common_data['user_group'];
                        $data['createdPCID'] = $this->common_data['current_pc'];
                        $data['createdUserID'] = $this->common_data['current_userID'];
                        $data['createdUserName'] = $this->common_data['current_user'];
                        $data['createdDateTime'] = $this->common_data['current_date'];
                        $this->db->insert('srp_erp_srm_orderinquirydetails', $data);
                    }
                }

                $data_inquiry['isChecked'] = 1;
                $this->db->where('itemAutoID', $autoID[0]);
                $this->db->where('inquiryMasterID', $inquiryID);
                $this->db->where('orderMasterID', $autoID[1]);
                $this->db->update('srp_erp_srm_inquiryitem', $data_inquiry);

            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Inquiry Detail Save Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
        return false;
    }

    function xeditable_update($tableName, $pkColumn)
    {
        $column = $this->input->post('name');
        $value = $this->input->post('value');
        $pk = $this->input->post('pk');
        switch ($column) {
            case 'DOB_O':
            case 'dateAssumed_O':
            case 'endOfContract_O':
            case 'SLBSeniority_O':
            case 'WSISeniority_O':
            case 'passportExpireDate_O':
            case 'VisaexpireDate_O':
            case 'coverFrom_O':
                $value = format_date_mysql_datetime($value);
                break;
        }
        $table = $tableName;
        $data = array($column => $value);
        $this->db->where($pkColumn, $pk);
        $result = $this->db->update($table, $data);
        echo $this->db->last_query();
        return $result;
    }

    function order_inquiry_generate_supplier_rfq()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];
        $inquiryID = trim($this->input->post('inquiryID'));
        $inquiryDetailIDs = $this->input->post('selectedSupplierSync');
        $orderID = $this->input->post('orderID');

        if (!empty($inquiryDetailIDs)) {
            foreach ($inquiryDetailIDs as $key => $inquiryDetailID) {

                $data_detail['isRfqCreated'] = 1;
                $data_detail['modifiedPCID'] = $this->common_data['current_pc'];
                $data_detail['modifiedUserID'] = $this->common_data['current_userID'];
                $data_detail['modifiedUserName'] = $this->common_data['current_user'];
                $data_detail['modifiedDateTime'] = $this->common_data['current_date'];
                $this->db->where('inquiryDetailID', $inquiryDetailID);
                $this->db->update('srp_erp_srm_orderinquirydetails', $data_detail);
            }

            if (!empty($orderID)) {
                foreach ($orderID as $row) {
                    if (trim($this->input->post('confirmed')) == 1) {
                        $data_order_master['status'] = 4;
                        $this->db->where('customerOrderID', $row);
                        $this->db->update('srp_erp_srm_customerordermaster', $data_order_master);
                    }
                }
            }
            if (trim($this->input->post('confirmed')) == 1) {
                $data_master['confirmedYN'] = 1;
                $data_master['confirmedDate'] = $this->common_data['current_date'];
                $data_master['confirmedByEmpID'] = $this->common_data['current_userID'];
                $data_master['confirmedByName'] = $this->common_data['current_user'];
            }
            $data_master['deliveryTerms'] = $this->input->post('deliveryTerms');
            $this->db->where('inquiryID', $inquiryID);
            $this->db->update('srp_erp_srm_orderinquirymaster', $data_master);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'RFQ Generate Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'RFQ Generated Successfully');
            }
        } else {
            return array('e', 'Atleast one item must be selected ! ');
        }
    }

    function load_customer_BaseDetail()
    {
        $this->db->select('CustomerAddress1,customerCurrencyID,customerTelephone');
        $this->db->where('CustomerAutoID', $this->input->post('customerID'));
        return $this->db->get('srp_erp_srm_customermaster')->row_array();
    }

    function assignItems_supplier_orderInquiry()
    {
        $itemAutoID = trim($this->input->post('itemAutoID'));
        $supplierIDs = $this->input->post('assignSupplierItemSync');

        if (!empty($supplierIDs)) {
            foreach ($supplierIDs as $key => $supplierID) {

                $data['supplierAutoID'] = $supplierID;
                $data['itemAutoID'] = $itemAutoID;
                $data['companyID'] = current_companyID();
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['timestamp'] = format_date_mysql_datetime();
                $this->db->insert('srp_erp_srm_supplieritems', $data);
            }

        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Supplier Assigned Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Supplier Assigned Successfully');
        }
    }

    function add_customer_order_notes()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];

        $customerOrderID = trim($this->input->post('customerOrderID'));

        $data['documentAutoID'] = $customerOrderID;
        $data['description'] = trim($this->input->post('description'));
        $data['companyID'] = $companyID;
        $data['documentID'] = 3;
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdUserName'] = $this->common_data['current_user'];
        $data['createdDateTime'] = $this->common_data['current_date'];
        $this->db->insert('srp_erp_srm_notes', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Order Note Save Failed ' . $this->db->_error_message(), $last_id);
        } else {
            $this->db->trans_commit();
            return array('s', 'Order  Note Saved Successfully.');

        }
    }

    function send_rfq_email_suppliers()
    {

        $supplierID = trim($this->input->post('supplierID'));
        $inquiryMasterID = trim($this->input->post('inquiryMasterID'));
        $companyID = $this->common_data['company_data']['company_id'];
        $currentUser = $this->common_data['current_userID'];

        $this->db->select('supplierName,supplierEmail,supplierAddress1');
        $this->db->where('supplierAutoID', $supplierID);
        $this->db->from('srp_erp_srm_suppliermaster');
        $masterRecordSupplier = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->where('inquiryID', $inquiryMasterID);
        $this->db->from('srp_erp_srm_orderinquirymaster');
        $masterRecordInquiry = $this->db->get()->row_array();

        $newurl = explode("/", $_SERVER['SCRIPT_NAME']);
        //$link = "http://$_SERVER[HTTP_HOST]/$newurl[1]/supplierPortal/index.php?link=" . $inquiryMasterID . '_' . $supplierID;
        $link = "https://$_SERVER[HTTP_HOST]/supplierPortal/index.php?link=" . $inquiryMasterID . '_' . $supplierID;
        $emailsubject = $masterRecordInquiry['narration'] . "-" . $masterRecordInquiry['documentCode'] . "- RFQ";
        $emailbody = "<div style='width: 80%;margin: auto;background-color:#fbfbfb;padding: 2%;font-family: sans-serif;'><b>To : " . $masterRecordSupplier['supplierName'] . "</b> <br><p>" . $masterRecordSupplier['supplierAddress1'] . "</p><br><p> ( ".current_companyCode()." ) ".ucwords($this->common_data['company_data']['company_name'])." issues this Purchasing Document detailed below through the iSupplier
Portal. NO ACTION IS REQUIRED but you can view the document at your discretion.</p><br><br><a href='$link'>Click here to access the portal online.</a><br><br><p>For more detail contact the supply chain department.</p><br><p>Thank You</p></div>";

        if (!empty($masterRecordSupplier)) {
            if (!empty($masterRecordSupplier['supplierEmail'])) {
                $config['mailtype'] = "html";
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.sparkpostmail.com';
                $config['smtp_user'] = 'SMTP_Injection';
                $config['smtp_pass'] = '6d911d3e2ffe9faabc3af1e289eb067908deb1c5';
                $config['smtp_crypto'] = 'tls';
                $config['smtp_port'] = '587';
                $condig['crlf'] = "\r\n";
                $config['newline'] = "\r\n";
                $this->load->library('email', $config);
                $this->email->from('noreply@spur-int.com', 'SME-SRM');
                $this->email->to($masterRecordSupplier['supplierEmail']);
                $this->email->subject($emailsubject);
                $this->email->message($emailbody);
                $result = $this->email->send();

                if ($result) {
                    $data['isRfqEmailed'] = 1;
                    $this->db->where('supplierID', $supplierID);
                    $this->db->where('inquiryMasterID', $inquiryMasterID);
                    $update = $this->db->update('srp_erp_srm_orderinquirydetails', $data);
                }
                return array('s', 'RFQ Email Send Successfully');
            } else {
                return array('e', 'No Email ID Found for supplier');
            }

        } else {
            return array('e', 'No Supplier Records Found');
        }
    }

    function load_inquiry_reviewHeader()
    {
        $convertFormat = convert_date_format_sql();
        $inquiryID = trim($this->input->post('inquiryID'));
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,CustomerName');
        $this->db->join('srp_erp_srm_customermaster', 'srp_erp_srm_customermaster.CustomerAutoID = srp_erp_srm_orderinquirymaster.customerID', 'LEFT');
        $this->db->where('inquiryID', $inquiryID);
        $data = $this->db->get('srp_erp_srm_orderinquirymaster')->row_array();
        return $data;
    }

    function generate_order_review_supplier()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];
        $inquiryID = trim($this->input->post('inquiryID'));;
        $supplierIDs = $this->input->post('supplierReviewSync');

        if (!empty($supplierIDs)) {
            $data_master['isSelectedForPO'] = 0;
            $this->db->where('inquiryMasterID', $inquiryID);
            $update = $this->db->update('srp_erp_srm_orderinquirydetails', $data_master);
            if ($update) {
                foreach ($supplierIDs as $key => $supplierID) {
                    $autoID = explode('_', $supplierID);
                    $data['isSelectedForPO'] = 1;
                    $this->db->where('inquiryMasterID', $inquiryID);
                    $this->db->where('supplierID', $autoID[1]);
                    $this->db->where('itemAutoID', $autoID[0]);
                    $this->db->update('srp_erp_srm_orderinquirydetails', $data);
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Order Review update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Order Review updated successfully ');
            }

        } else {
            return array('e', 'No Supplier Selected');
        }
    }

    function supplier_image_upload()
    {
        $this->db->trans_start();
        $output_dir = "uploads/srm/supplierimage/";
        if (!file_exists($output_dir)) {
            mkdir("uploads/srm", 007);
            mkdir("uploads/srm/supplierimage", 007);
        }
        $attachment_file = $_FILES["files"];
        $info = new SplFileInfo($_FILES["files"]["name"]);
        $fileName = 'Supplier_' . trim($this->input->post('supplierAutoID')) . '.' . $info->getExtension();
        move_uploaded_file($_FILES["files"]["tmp_name"], $output_dir . $fileName);

        $data['supplierImage'] = $fileName;

        $this->db->where('supplierAutoID', trim($this->input->post('supplierAutoID')));
        $this->db->update('srp_erp_srm_suppliermaster', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', "Image Upload Failed." . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Image uploaded  Successfully.');
        }
    }

}