<?php

class Invoice_model extends ERP_Model
{

    function save_invoice_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $invDueDate = $this->input->post('invoiceDueDate');
        $invoiceDueDate = input_format_date($invDueDate, $date_format_policy);
        $invDate = $this->input->post('invoiceDate');
        $invoiceDate = input_format_date($invDate, $date_format_policy);
        $customerDate = $this->input->post('customerInvoiceDate');
        $customerInvoiceDate = input_format_date($customerDate, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        //$period = explode('|', trim($this->input->post('financeyear_period')));
        if($financeyearperiodYN==1) {
            $financeyr = explode(' - ', trim($this->input->post('companyFinanceYear')));

            $FYBegin = input_format_date($financeyr[0], $date_format_policy);
            $FYEnd = input_format_date($financeyr[1], $date_format_policy);
        }else{
            $financeYearDetails=get_financial_year($invoiceDate);
            if(empty($financeYearDetails)){
                $this->session->set_flashdata('e', 'Finance period not found for the selected document date');
                return array('status' => false);
                exit;
            }else{
                $FYBegin=$financeYearDetails['beginingDate'];
                $FYEnd=$financeYearDetails['endingDate'];
                $_POST['companyFinanceYear'] = $FYBegin.' - '.$FYEnd;
                $_POST['financeyear'] = $financeYearDetails['companyFinanceYearID'];
            }
            $financePeriodDetails=get_financial_period_date_wise($invoiceDate);

            if(empty($financePeriodDetails)){
                $this->session->set_flashdata('e', 'Finance period not found for the selected document date');
                return array('status' => false);
                exit;
            }else{

                $_POST['financeyear_period'] = $financePeriodDetails['companyFinancePeriodID'];
            }
        }
        $segment = explode('|', trim($this->input->post('segment')));
        $customer_arr = $this->fetch_customer_data(trim($this->input->post('customerID')));
        //$location = explode('|', trim($this->input->post('location_dec')));
        $currency_code = explode('|', trim($this->input->post('currency_code')));
        if ($this->input->post('RVbankCode')) {
            $bank_detail = fetch_gl_account_desc(trim($this->input->post('RVbankCode')));
            $data['bankGLAutoID'] = $bank_detail['GLAutoID'];
            $data['bankSystemAccountCode'] = $bank_detail['systemAccountCode'];
            $data['bankGLSecondaryCode'] = $bank_detail['GLSecondaryCode'];
            $data['bankCurrencyID'] = $bank_detail['bankCurrencyID'];
            $data['bankCurrency'] = $bank_detail['bankCurrencyCode'];
            $data['invoicebank'] = $bank_detail['bankName'];
            $data['invoicebankBranch'] = $bank_detail['bankBranch'];
            $data['invoicebankSwiftCode'] = $bank_detail['bankSwiftCode'];
            $data['invoicebankAccount'] = $bank_detail['bankAccountNumber'];
            $data['invoicebankType'] = $bank_detail['subCategory'];
        }
        $data['documentID'] = 'CINV';
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
        $data['contactPersonName'] = trim($this->input->post('contactPersonName'));
        $data['contactPersonNumber'] = trim($this->input->post('contactPersonNumber'));
        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        /*$data['FYPeriodDateFrom'] = trim($period[0]);
        $data['FYPeriodDateTo'] = trim($period[1]);*/
        $data['invoiceDate'] = trim($invoiceDate);
        $data['customerInvoiceDate'] = trim($customerInvoiceDate);
        $data['invoiceDueDate'] = trim($invoiceDueDate);
        $data['invoiceNarration'] = trim_desc($this->input->post('invoiceNarration'));
        $data['invoiceNote'] = trim($this->input->post('invoiceNote'));
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['salesPersonID'] = trim($this->input->post('salesPersonID'));
        if ($data['salesPersonID']) {
            $code = explode(' | ', trim($this->input->post('salesPerson')));
            $data['SalesPersonCode'] = trim($code[0]);
        }
        // $data['wareHouseCode'] = trim($location[0]);
        // $data['wareHouseLocation'] = trim($location[1]);
        // $data['wareHouseDescription'] = trim($location[2]);
        $data['invoiceType'] = trim($this->input->post('invoiceType'));
        $data['referenceNo'] = trim($this->input->post('referenceNo'));
        $data['isPrintDN'] = trim($this->input->post('isPrintDN'));
        $data['customerID'] = $customer_arr['customerAutoID'];
        $data['customerSystemCode'] = $customer_arr['customerSystemCode'];
        $data['customerName'] = $customer_arr['customerName'];
        $data['customerAddress'] = $customer_arr['customerAddress1'] . ' ' . $customer_arr['customerAddress2'];
        $data['customerTelephone'] = $customer_arr['customerTelephone'];
        $data['customerFax'] = $customer_arr['customerFax'];
        $data['customerEmail'] = $customer_arr['customerEmail'];
        $data['customerReceivableAutoID'] = $customer_arr['receivableAutoID'];
        $data['customerReceivableSystemGLCode'] = $customer_arr['receivableSystemGLCode'];
        $data['customerReceivableGLAccount'] = $customer_arr['receivableGLAccount'];
        $data['customerReceivableDescription'] = $customer_arr['receivableDescription'];
        $data['customerReceivableType'] = $customer_arr['receivableType'];
        $data['customerCurrency'] = $customer_arr['customerCurrency'];
        $data['customerCurrencyID'] = $customer_arr['customerCurrencyID'];
        $data['customerCurrencyDecimalPlaces'] = $customer_arr['customerCurrencyDecimalPlaces'];
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $customer_currency = currency_conversionID($data['transactionCurrencyID'], $data['customerCurrencyID']);
        $data['customerCurrencyExchangeRate'] = $customer_currency['conversion'];
        $data['customerCurrencyDecimalPlaces'] = $customer_currency['DecimalPlaces'];

        if (trim($this->input->post('invoiceAutoID'))) {
            $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
            $this->db->update('srp_erp_customerinvoicemaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Invoice Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                update_warehouse_items();
                update_item_master();
                $this->session->set_flashdata('s', 'Invoice Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('invoiceAutoID'));
            }
        } else {
            //$this->load->library('sequence');
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['invoiceCode'] = 0;
            //if ($data['isPrintDN']==1) {
            $data['deliveryNoteSystemCode'] = $this->sequence->sequence_generator('DLN');
            //}

            $this->db->insert('srp_erp_customerinvoicemaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Invoice   Saved Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                update_warehouse_items();
                update_item_master();
                $this->session->set_flashdata('s', 'Invoice Saved Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $last_id);
            }
        }
    }

    function fetch_customer_data($customerID)
    {
        $this->db->select('*');
        $this->db->from('srp_erp_customermaster');
        $this->db->where('customerAutoID', $customerID);
        return $this->db->get()->row_array();
    }

    function delete_item_direct()
    {
        $id = $this->input->post('invoiceDetailsAutoID');

        $this->db->select('*');
        $this->db->from('srp_erp_customerinvoicedetails');
        $this->db->where('invoiceDetailsAutoID', $id);
        $rTmp = $this->db->get()->row_array();


        /** update sub item master */

        $dataTmp['isSold'] = null;
        $dataTmp['soldDocumentAutoID'] = null;
        $dataTmp['soldDocumentDetailID'] = null;
        $dataTmp['soldDocumentID'] = null;
        $dataTmp['modifiedPCID'] = current_pc();
        $dataTmp['modifiedUserID'] = current_userID();
        $dataTmp['modifiedDatetime'] = format_date_mysql_datetime();

        $this->db->where('soldDocumentAutoID', $rTmp['invoiceAutoID']);
        $this->db->where('soldDocumentDetailID', $rTmp['invoiceDetailsAutoID']);
        $this->db->where('soldDocumentID', 'CINV');
        $this->db->update('srp_erp_itemmaster_sub', $dataTmp);

        /** end update sub item master */

        $this->db->where('invoiceDetailsAutoID', $id);
        $results = $this->db->delete('srp_erp_customerinvoicedetails');
        if ($results) {
            $this->session->set_flashdata('s', 'Invoice Detail Deleted Successfully');
            return true;
        }
    }

    function save_invoice_item_detail()
    {
        $projectExist = project_is_exist();
        $invoiceDetailsAutoID = $this->input->post('invoiceDetailsAutoID');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $itemAutoIDs = $this->input->post('itemAutoID');
        $item_text = $this->input->post('item_text');
        $wareHouse = $this->input->post('wareHouse');
        $uom = $this->input->post('uom');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $projectID = $this->input->post('projectID');
        $quantityRequested = $this->input->post('quantityRequested');
        $item_taxPercentage = $this->input->post('item_taxPercentage');
        $comment = $this->input->post('comment');
        $remarks = $this->input->post('remarks');
        $wareHouseAutoID = $this->input->post('wareHouseAutoID');
        $estimatedAmount = $this->input->post('estimatedAmount');
        $discount = $this->input->post('discount');
        $discount_amount = $this->input->post('discount_amount');
        $SUOMQty = $this->input->post('SUOMQty');
        $SUOMIDhn = $this->input->post('SUOMIDhn');

        $noOfItems = $this->input->post('noOfItems');
        $grossQty = $this->input->post('grossQty');
        $noOfUnits = $this->input->post('noOfUnits');
        $deduction = $this->input->post('deduction');

        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate ,transactionCurrency,segmentID,segmentCode,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        foreach ($itemAutoIDs as $key => $itemAutoID) {
            $tax_master = array();
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID);
            $serviceitm= $this->db->get()->row_array();

            if (!trim($this->input->post('invoiceDetailsAutoID'))) {
                if($serviceitm['mainCategory']=="Inventory") {
                    $this->db->select('invoiceAutoID,,itemDescription,itemSystemCode');
                    $this->db->from('srp_erp_customerinvoicedetails');
                    $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                    $this->db->where('itemAutoID', $itemAutoID);
                    $this->db->where('wareHouseAutoID', $wareHouseAutoID[$key]);
                    $order_detail = $this->db->get()->row_array();
                    if (!empty($order_detail)) {
                        return array('w', 'Invoice Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
                    }
                }
            }

            if (isset($item_text[$key])) {
                $this->db->select('*');
                $this->db->where('taxMasterAutoID', $item_text[$key]);
                $tax_master = $this->db->get('srp_erp_taxmaster')->row_array();

                $this->db->select('*');
                $this->db->where('supplierSystemCode', $tax_master['supplierSystemCode']);
                $Supplier_master = $this->db->get('srp_erp_suppliermaster')->row_array();

                $this->db->select('srp_erp_taxmaster.*,srp_erp_chartofaccounts.GLAutoID as liabilityAutoID,srp_erp_chartofaccounts.systemAccountCode as liabilitySystemGLCode,srp_erp_chartofaccounts.GLSecondaryCode as liabilityGLAccount,srp_erp_chartofaccounts.GLDescription as liabilityDescription,srp_erp_chartofaccounts.CategoryTypeDescription as liabilityType,srp_erp_currencymaster.CurrencyCode,srp_erp_currencymaster.DecimalPlaces');
                $this->db->where('taxMasterAutoID', $item_text[$key]);
                $this->db->from('srp_erp_taxmaster');
                $this->db->join('srp_erp_chartofaccounts', 'srp_erp_chartofaccounts.GLAutoID = srp_erp_taxmaster.supplierGLAutoID');
                $this->db->join('srp_erp_currencymaster', 'srp_erp_currencymaster.currencyID = srp_erp_taxmaster.supplierCurrencyID');
                $tax_master = $this->db->get()->row_array();
            }

            $wareHouse_location = explode('|', $wareHouse[$key]);
            $item_arr = fetch_item_data($itemAutoID);
            $uomEx = explode('|', $uom[$key]);

            $data['invoiceAutoID'] = trim($invoiceAutoID);
            $data['itemAutoID'] = $itemAutoID;
            $data['itemSystemCode'] = $item_arr['itemSystemCode'];
            if ($projectExist == 1) {
                $projectCurrency = project_currency($projectID[$key]);
                $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
                $data['projectID'] = $projectID[$key];
                $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
            }
            $data['itemDescription'] = $item_arr['itemDescription'];

            $data['SUOMQty'] = $SUOMQty[$key];
            $data['SUOMID'] = $SUOMIDhn[$key];
            $data['unitOfMeasure'] = trim($uomEx[0]);
            $data['unitOfMeasureID'] = $UnitOfMeasureID[$key];
            $data['defaultUOM'] = $item_arr['defaultUnitOfMeasure'];
            $data['defaultUOMID'] = $item_arr['defaultUnitOfMeasureID'];
            $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
            $data['requestedQty'] = $quantityRequested[$key];
            $data['discountPercentage'] = $discount[$key];
            $data['discountAmount'] = $discount_amount[$key];
            $amountafterdiscount = $estimatedAmount[$key] - $data['discountAmount'];
            $data['unittransactionAmount'] = round($estimatedAmount[$key], $master['transactionCurrencyDecimalPlaces']);
            $data['taxPercentage'] = $item_taxPercentage[$key];
            $taxAmount = ($data['taxPercentage'] / 100) * $amountafterdiscount;
            $data['taxAmount'] = round($taxAmount, $master['transactionCurrencyDecimalPlaces']);
            $totalAfterTax = $data['taxAmount'] * $data['requestedQty'];
            $data['totalAfterTax'] = round($totalAfterTax, $master['transactionCurrencyDecimalPlaces']);
            $transactionAmount = ($data['taxAmount'] + $amountafterdiscount) * $quantityRequested[$key];
            $data['transactionAmount'] = round($transactionAmount, $master['transactionCurrencyDecimalPlaces']);
            $companyLocalAmount = $data['transactionAmount'] / $master['companyLocalExchangeRate'];
            $data['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $companyReportingAmount = $data['transactionAmount'] / $master['companyReportingExchangeRate'];
            $data['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $customerAmount = $data['transactionAmount'] / $master['customerCurrencyExchangeRate'];
            $data['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
            $data['comment'] = $comment[$key];
            $data['remarks'] = $remarks[$key];
            $data['type'] = 'Item';
            $item_data = fetch_item_data($data['itemAutoID']);
            if($serviceitm['mainCategory']=="Service") {
                $data['wareHouseAutoID'] = 0;
                $data['wareHouseCode'] = null;
                $data['wareHouseLocation'] = null;
                $data['wareHouseDescription'] = null;
            }else{
                $data['wareHouseAutoID'] = $wareHouseAutoID[$key];
                $data['wareHouseCode'] = trim($wareHouse_location[0]);
                $data['wareHouseLocation'] = trim($wareHouse_location[1]);
                $data['wareHouseDescription'] = trim($wareHouse_location[2]);
            }
            $data['segmentID'] = $master['segmentID'];
            $data['segmentCode'] = $master['segmentCode'];
            $data['expenseGLAutoID'] = $item_data['costGLAutoID'];
            $data['expenseGLCode'] = $item_data['costGLCode'];
            $data['expenseSystemGLCode'] = $item_data['costSystemGLCode'];
            $data['expenseGLDescription'] = $item_data['costDescription'];
            $data['expenseGLType'] = $item_data['costType'];
            $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
            $data['revenueGLCode'] = $item_data['revanueGLCode'];
            $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
            $data['revenueGLDescription'] = $item_data['revanueDescription'];
            $data['revenueGLType'] = $item_data['revanueType'];
            $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
            $data['assetGLCode'] = $item_data['assteGLCode'];
            $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
            $data['assetGLDescription'] = $item_data['assteDescription'];
            $data['assetGLType'] = $item_data['assteType'];
            $data['companyLocalWacAmount'] = $item_data['companyLocalWacAmount'];
            $data['itemCategory'] = $item_data['mainCategory'];

            $data['noOfItems'] = $noOfItems[$key];
            $data['grossQty'] = $grossQty[$key];
            $data['noOfUnits'] = $noOfUnits[$key];
            $data['deduction'] = $deduction[$key];

            if (!empty($tax_master)) {
                $data['taxMasterAutoID'] = $tax_master['taxMasterAutoID'];
                $data['taxDescription'] = $tax_master['taxDescription'];
                $data['taxShortCode'] = $tax_master['taxShortCode'];
                $data['taxSupplierAutoID'] = $tax_master['supplierAutoID'];
                $data['taxSupplierSystemCode'] = $tax_master['supplierSystemCode'];
                $data['taxSupplierName'] = $tax_master['supplierName'];
                $data['taxSupplierCurrencyID'] = $tax_master['supplierCurrencyID'];
                $data['taxSupplierCurrency'] = $tax_master['CurrencyCode'];
                $data['taxSupplierCurrencyDecimalPlaces'] = $tax_master['DecimalPlaces'];
                $data['taxSupplierliabilityAutoID'] = $tax_master['liabilityAutoID'];
                $data['taxSupplierliabilitySystemGLCode'] = $tax_master['liabilitySystemGLCode'];
                $data['taxSupplierliabilityGLAccount'] = $tax_master['liabilityGLAccount'];
                $data['taxSupplierliabilityDescription'] = $tax_master['liabilityDescription'];
                $data['taxSupplierliabilityType'] = $tax_master['liabilityType'];
                $supplierCurrency = currency_conversion($master['transactionCurrency'], $data['taxSupplierCurrency']);
                $data['taxSupplierCurrencyExchangeRate'] = $supplierCurrency['conversion'];
                $data['taxSupplierCurrencyDecimalPlaces'] = $supplierCurrency['DecimalPlaces'];
                $data['taxSupplierCurrencyAmount'] = ($data['transactionAmount'] / $data['taxSupplierCurrencyExchangeRate']);
            } else {
                $data['taxSupplierCurrencyExchangeRate'] = 1;
                $data['taxSupplierCurrencyDecimalPlaces'] = 2;
                $data['taxSupplierCurrencyAmount'] = 0;
            }

            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];


            if ($invoiceDetailsAutoID) {
                /*$this->db->where('invoiceDetailsAutoID', trim($invoiceDetailsAutoID));
                $this->db->update('srp_erp_customerinvoicedetails', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('e', 'Invoice Detail : ' . $data['itemSystemCode'] . ' Update Failed ' . $this->db->_error_message());
                    $this->db->trans_rollback();
                    return array('status' => false);
                } else {
                    $this->session->set_flashdata('s', 'Invoice Detail : ' . $data['itemSystemCode'] . ' Updated Successfully.');
                    $this->db->trans_commit();
                    return array('status' => true, 'last_id' => $this->input->post('invoiceDetailsAutoID'));
                }*/
            } else {
                $data['companyID'] = $this->common_data['company_data']['company_id'];
                $data['companyCode'] = $this->common_data['company_data']['company_code'];
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_customerinvoicedetails', $data);
                $last_id = $this->db->insert_id();

                if ($item_data['mainCategory'] == 'Inventory' or $item_data['mainCategory'] == 'Non Inventory') {
                    $this->db->select('itemAutoID');
                    $this->db->where('itemAutoID', $itemAutoID);
                    $this->db->where('wareHouseAutoID', $data['wareHouseAutoID']);
                    $this->db->where('companyID', $this->common_data['company_data']['company_id']);
                    $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

                    if (empty($warehouseitems)) {
                        $data_arr = array(
                            'wareHouseAutoID' => $data['wareHouseAutoID'],
                            'wareHouseLocation' => $data['wareHouseLocation'],
                            'wareHouseDescription' => $data['wareHouseDescription'],
                            'itemAutoID' => $data['itemAutoID'],
                            'itemSystemCode' => $data['itemSystemCode'],
                            'barCodeNo' => $item_data['barcode'],
                            'salesPrice' => $item_data['companyLocalSellingPrice'],
                            'ActiveYN' => $item_data['isActive'],
                            'itemDescription' => $data['itemDescription'],
                            'unitOfMeasureID' => $data['defaultUOMID'],
                            'unitOfMeasure' => $data['defaultUOM'],
                            'currentStock' => 0,
                            'companyID' => $this->common_data['company_data']['company_id'],
                            'companyCode' => $this->common_data['company_data']['company_code'],
                        );
                        $this->db->insert('srp_erp_warehouseitems', $data_arr);
                    }
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Invoice Detail : Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Invoice Detail : Saved Successfully.');
        }
    }

    function update_invoice_item_detail()
    {
        $invoiceDetailsAutoID = $this->input->post('invoiceDetailsAutoID');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $itemAutoID = $this->input->post('itemAutoID');
        $item_text = $this->input->post('item_text');
        $wareHouse = $this->input->post('wareHouse');
        $projectID = $this->input->post('projectID');
        $uom = $this->input->post('uom');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $quantityRequested = $this->input->post('quantityRequested');
        $item_taxPercentage = $this->input->post('item_taxPercentage');
        $comment = $this->input->post('comment');
        $remarks = $this->input->post('remarks');
        $wareHouseAutoID = $this->input->post('wareHouseAutoID');
        $estimatedAmount = $this->input->post('estimatedAmount');
        $discount_amount = $this->input->post('discount_amount');
        $discount = $this->input->post('discount');
        $projectExist = project_is_exist();

        $this->db->select('mainCategory');
        $this->db->from('srp_erp_itemmaster');
        $this->db->where('itemAutoID', $itemAutoID);
        $serviceitm= $this->db->get()->row_array();

        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate ,transactionCurrency,segmentID,segmentCode,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $tax_master = array();
        if($serviceitm['mainCategory']=="Inventory") {
            if (!empty($this->input->post('invoiceDetailsAutoID'))) {
                $this->db->select('invoiceAutoID,,itemDescription,itemSystemCode');
                $this->db->from('srp_erp_customerinvoicedetails');
                $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                $this->db->where('itemAutoID', $itemAutoID);
                $this->db->where('invoiceDetailsAutoID !=', $invoiceDetailsAutoID);
                $order_detail = $this->db->get()->row_array();
                if (!empty($order_detail)) {
                    return array('w', 'Invoice Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
                }
            }
        }
        if (isset($item_text)) {
            $this->db->select('*');
            $this->db->where('taxMasterAutoID', $item_text);
            $tax_master = $this->db->get('srp_erp_taxmaster')->row_array();

            $this->db->select('*');
            $this->db->where('supplierSystemCode', $tax_master['supplierSystemCode']);
            $Supplier_master = $this->db->get('srp_erp_suppliermaster')->row_array();
        }

        $wareHouse_location = explode('|', $wareHouse);
        $item_arr = fetch_item_data($itemAutoID);
        $uomEx = explode('|', $uom);

        $data['invoiceAutoID'] = trim($invoiceAutoID);
        $data['itemAutoID'] = $itemAutoID;
        $data['itemSystemCode'] = $item_arr['itemSystemCode'];
        $data['projectID'] = $projectID;
        if ($projectExist == 1) {
            $projectCurrency = project_currency($projectID);
            $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
            $data['projectID'] = $projectID;
            $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
        }
        $data['itemDescription'] = $item_arr['itemDescription'];
        $data['SUOMQty'] = $this->input->post('SUOMQty');
        $data['SUOMID'] = $this->input->post('SUOMIDhn');
        $data['unitOfMeasure'] = trim($uomEx[0]);
        $data['unitOfMeasureID'] = $UnitOfMeasureID;
        $data['defaultUOM'] = $item_arr['defaultUnitOfMeasure'];
        $data['defaultUOMID'] = $item_arr['defaultUnitOfMeasureID'];
        $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
        $data['requestedQty'] = $quantityRequested;
        $data['discountPercentage'] = $discount;
        $data['discountAmount'] = $discount_amount;
        $amountafterdiscount = $estimatedAmount - $discount_amount;
        $data['unittransactionAmount'] = round($estimatedAmount, $master['transactionCurrencyDecimalPlaces']);
        $data['taxPercentage'] = $item_taxPercentage;
        $taxAmount = ($data['taxPercentage'] / 100) * $amountafterdiscount;
        $data['taxAmount'] = round($taxAmount, $master['transactionCurrencyDecimalPlaces']);
        $totalAfterTax = $data['taxAmount'] * $data['requestedQty'];
        $data['totalAfterTax'] = round($totalAfterTax, $master['transactionCurrencyDecimalPlaces']);
        $transactionAmount = ($data['taxAmount'] + $amountafterdiscount) * $quantityRequested;
        $data['transactionAmount'] = round($transactionAmount, $master['transactionCurrencyDecimalPlaces']);
        $companyLocalAmount = $data['transactionAmount'] / $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
        $companyReportingAmount = $data['transactionAmount'] / $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
        $customerAmount = $data['transactionAmount'] / $master['customerCurrencyExchangeRate'];
        $data['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
        $data['comment'] = $comment;
        $data['remarks'] = $remarks;
        $data['type'] = 'Item';
        $item_data = fetch_item_data($data['itemAutoID']);
        if($serviceitm['mainCategory']=="Service") {
            $data['wareHouseAutoID'] = 0;
            $data['wareHouseCode'] = null;
            $data['wareHouseLocation'] = null;
            $data['wareHouseDescription'] = null;
        }else{
            $data['wareHouseAutoID'] = $wareHouseAutoID;
            $data['wareHouseCode'] = trim($wareHouse_location[0]);
            $data['wareHouseLocation'] = trim($wareHouse_location[1]);
            $data['wareHouseDescription'] = trim($wareHouse_location[2]);
        }
        $data['segmentID'] = $master['segmentID'];
        $data['segmentCode'] = $master['segmentCode'];
        $data['expenseGLAutoID'] = $item_data['costGLAutoID'];
        $data['expenseGLCode'] = $item_data['costGLCode'];
        $data['expenseSystemGLCode'] = $item_data['costSystemGLCode'];
        $data['expenseGLDescription'] = $item_data['costDescription'];
        $data['expenseGLType'] = $item_data['costType'];
        $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
        $data['revenueGLCode'] = $item_data['revanueGLCode'];
        $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
        $data['revenueGLDescription'] = $item_data['revanueDescription'];
        $data['revenueGLType'] = $item_data['revanueType'];
        $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
        $data['assetGLCode'] = $item_data['assteGLCode'];
        $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
        $data['assetGLDescription'] = $item_data['assteDescription'];
        $data['assetGLType'] = $item_data['assteType'];
        $data['companyLocalWacAmount'] = $item_data['companyLocalWacAmount'];
        $data['itemCategory'] = $item_data['mainCategory'];

        if (!empty($tax_master)) {
            $data['taxMasterAutoID'] = $tax_master['taxMasterAutoID'];
            $data['taxDescription'] = $tax_master['taxDescription'];
            $data['taxShortCode'] = $tax_master['taxShortCode'];
            $data['taxSupplierAutoID'] = $tax_master['supplierAutoID'];
            $data['taxSupplierSystemCode'] = $tax_master['supplierSystemCode'];
            $data['taxSupplierName'] = $tax_master['supplierName'];
            $data['taxSupplierCurrencyID'] = $tax_master['supplierCurrencyID'];
            $data['taxSupplierCurrency'] = $tax_master['supplierCurrency'];
            $data['taxSupplierCurrencyDecimalPlaces'] = $tax_master['supplierCurrencyDecimalPlaces'];
            $data['taxSupplierliabilityAutoID'] = $tax_master['supplierGLAutoID'];
            $data['taxSupplierliabilitySystemGLCode'] = $tax_master['supplierGLSystemGLCode'];
            $data['taxSupplierliabilityGLAccount'] = $tax_master['supplierGLAccount'];
            $data['taxSupplierliabilityDescription'] = $tax_master['supplierGLDescription'];
            $data['taxSupplierliabilityType'] = $tax_master['supplierGLType'];
            $supplierCurrency = currency_conversion($master['transactionCurrency'], $data['taxSupplierCurrency']);
            $data['taxSupplierCurrencyExchangeRate'] = $supplierCurrency['conversion'];
            $data['taxSupplierCurrencyDecimalPlaces'] = $supplierCurrency['DecimalPlaces'];
            $data['taxSupplierCurrencyAmount'] = ($data['transactionAmount'] / $data['taxSupplierCurrencyExchangeRate']);
        } else {
            $data['taxSupplierCurrencyExchangeRate'] = 1;
            $data['taxSupplierCurrencyDecimalPlaces'] = 2;
            $data['taxSupplierCurrencyAmount'] = 0;
        }

        $data['noOfItems'] = $this->input->post('noOfItems');
        $data['grossQty'] = $this->input->post('grossQty');
        $data['noOfUnits'] = $this->input->post('noOfUnits');
        $data['deduction'] = $this->input->post('deduction');

        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if ($item_data['mainCategory'] == 'Inventory' or $item_data['mainCategory'] == 'Non Inventory') {
            $this->db->select('itemAutoID');
            $this->db->where('itemAutoID', $itemAutoID);
            $this->db->where('wareHouseAutoID', $data['wareHouseAutoID']);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

            if (empty($warehouseitems)) {
                $data_arr = array(
                    'wareHouseAutoID' => $data['wareHouseAutoID'],
                    'wareHouseLocation' => $data['wareHouseLocation'],
                    'wareHouseDescription' => $data['wareHouseDescription'],
                    'itemAutoID' => $data['itemAutoID'],
                    'itemSystemCode' => $data['itemSystemCode'],
                    'barCodeNo' => $item_data['barcode'],
                    'salesPrice' => $item_data['companyLocalSellingPrice'],
                    'ActiveYN' => $item_data['isActive'],
                    'itemDescription' => $data['itemDescription'],
                    'unitOfMeasureID' => $data['defaultUOMID'],
                    'unitOfMeasure' => $data['defaultUOM'],
                    'currentStock' => 0,
                    'companyID' => $this->common_data['company_data']['company_id'],
                    'companyCode' => $this->common_data['company_data']['company_code'],
                );
                $this->db->insert('srp_erp_warehouseitems', $data_arr);
            }
        }

        if ($invoiceDetailsAutoID) {
            $this->db->where('invoiceDetailsAutoID', trim($invoiceDetailsAutoID));
            $this->db->update('srp_erp_customerinvoicedetails', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Invoice Detail : ' . $data['itemSystemCode'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Invoice Detail : ' . $data['itemSystemCode'] . ' Updated Successfully.');
            }
        }
    }

    function fetch_invoice_template_data($invoiceAutoID)
    {

        $convertFormat = convert_date_format_sql();
        $this->db->select('srp_erp_customerinvoicemaster.*,srp_erp_segment.description as segDescription,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDate,\'' . $convertFormat . '\') AS invoiceDate ,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,DATE_FORMAT(srp_erp_customerinvoicemaster.customerInvoiceDate,\'' . $convertFormat . '\') AS customerInvoiceDate,DATE_FORMAT(srp_erp_customerinvoicemaster.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,CASE WHEN confirmedYN = 2 || confirmedYN = 3   THEN " - " WHEN confirmedYN = 1 THEN CONCAT_WS(\' on \',IF(LENGTH(confirmedbyName),confirmedbyName,\'-\'),IF(LENGTH(DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' )),DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' ),NULL)) ELSE "-" END confirmedYNn,srp_erp_salespersonmaster.SalesPersonName as SalesPersonName,srp_designation.DesDescription as DesDescription');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->join('srp_erp_salespersonmaster', 'srp_erp_salespersonmaster.salesPersonID = srp_erp_customerinvoicemaster.salesPersonID','LEFT');
        $this->db->join('srp_employeesdetails', 'srp_employeesdetails.EIdNo = srp_erp_salespersonmaster.EIdNo','LEFT');
        $this->db->join('srp_designation', 'srp_designation.DesignationID = srp_employeesdetails.EmpDesignationId','LEFT');
        $this->db->join('srp_erp_segment', 'srp_erp_segment.segmentID = srp_erp_customerinvoicemaster.segmentID', 'Left');
        $this->db->from('srp_erp_customerinvoicemaster');
        $data['master'] = $this->db->get()->row_array();

        $data['master']['retentionInvoiceCode']='';
        if($data['master']){
            if($data['master']['retensionInvoiceID'] <> ''){

                /*Retention*/
                $this->db->select('invoiceCode');
                $this->db->where('invoiceAutoID', $data['master']['retensionInvoiceID']);
                $this->db->from('srp_erp_customerinvoicemaster');
                $retention = $this->db->get()->row_array();

                $data['master']['retentionInvoiceCode']=$retention['invoiceCode'];

                /**/

            }
        }

        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);



        $this->db->select('customerName,customerAddress1,customerTelephone,customerSystemCode,customerFax,customerCountry');
        $this->db->where('customerAutoID', $data['master']['customerID']);
        $this->db->from('srp_erp_customermaster');
        $data['customer'] = $this->db->get()->row_array();

        $this->db->select('wareHouseLocation');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('wareHouseAutoID !=','');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['warehousearea'] = $this->db->get()->row_array();

        $this->db->select('srp_erp_customerinvoicedetails.*,srp_erp_itemmaster.partNo,srp_erp_itemmaster.seconeryItemCode as seconeryItemCode,srp_erp_unit_of_measure.UnitShortCode as secuom');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'Item');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_customerinvoicedetails.itemAutoID');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_customerinvoicedetails.SUOMID','left');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['item_detail'] = $this->db->get()->result_array();


        $convertFormat = convert_date_format_sql();
        $this->db->select('cus.*, DOMasterID,DATE_FORMAT(DODate,\''.  $convertFormat .'\') AS DODate,DOCode,referenceNo,del_ord.transactionAmount AS do_tr_amount,due_amount,balance_amount');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'DO');
        $this->db->from('srp_erp_customerinvoicedetails cus');
        $this->db->join('srp_erp_deliveryorder del_ord', 'del_ord.DOAutoID = cus.DOMasterID');
        $data['delivery_order'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['extracharge'] = $this->db->get('srp_erp_customerinvoiceextrachargedetails')->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['discount'] = $this->db->get('srp_erp_customerinvoicediscountdetails')->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'GL');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['gl_detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['tax'] = $this->db->get('srp_erp_customerinvoicetaxdetails')->result_array();
        return $data;
    }

    function conversionRateUOM($umo, $default_umo)
    {
        $this->db->select('UnitID');
        $this->db->where('UnitShortCode', $default_umo);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $masterUnitID = $this->db->get('srp_erp_unit_of_measure')->row('UnitID');

        $this->db->select('UnitID');
        $this->db->where('UnitShortCode', $umo);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $subUnitID = $this->db->get('srp_erp_unit_of_measure')->row('UnitID');

        $this->db->select('conversion');
        $this->db->from('srp_erp_unitsconversion');
        $this->db->where('masterUnitID', $masterUnitID);
        $this->db->where('subUnitID', $subUnitID);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        return $this->db->get()->row('conversion');
    }

    function load_invoice_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(customerInvoiceDate,\'' . $convertFormat . '\') AS customerInvoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,DATE_FORMAT(policyStartDate,\'' . $convertFormat . '\') AS policyStartDate,DATE_FORMAT(policyEndDate,\'' . $convertFormat . '\') AS policyEndDate');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        return $this->db->get('srp_erp_customerinvoicemaster')->row_array();
    }

    function fetch_invoice_direct_details()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,invoiceType');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $data['currency'] = $this->db->get('srp_erp_customerinvoicemaster')->row_array();
        $this->db->select('srp_erp_customerinvoicedetails.*,srp_erp_itemmaster.isSubitemExist,srp_erp_itemmaster.partNo,srp_erp_suppliermaster.supplierName as supplierName, 
                DOMasterID,DATE_FORMAT(DODate,\''.  $convertFormat .'\') AS DODate,DOCode,referenceNo,del_ord.transactionAmount AS do_tr_amount,due_amount,balance_amount,srp_erp_unit_of_measure.UnitShortCode as secuom');
        $this->db->from('srp_erp_customerinvoicedetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_customerinvoicedetails.itemAutoID', 'left');
        $this->db->join('srp_erp_suppliermaster', 'srp_erp_suppliermaster.supplierAutoID = srp_erp_customerinvoicedetails.supplierAutoID', 'left');
        $this->db->join('srp_erp_deliveryorder del_ord', 'del_ord.DOAutoID = srp_erp_customerinvoicedetails.DOMasterID', 'left');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_customerinvoicedetails.SUOMID','left');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $data['detail'] = $this->db->get()->result_array();


        $this->db->select('*');
        $this->db->from('srp_erp_customerinvoicediscountdetails');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $data['discount_detail'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $data['extraChargeDetail'] = $this->db->get('srp_erp_customerinvoiceextrachargedetails')->result_array();

        $this->db->select('*');
        $this->db->from('srp_erp_customerinvoicetaxdetails');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $data['tax_detail'] = $this->db->get()->result_array();
        return $data;
    }

    function fetch_detail()
    {
        $data = array();
        $this->db->select('*');
        $this->db->from('srp_erp_customerinvoicedetails');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $data['detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $data['tax'] = $this->db->get('srp_erp_customerinvoicetaxdetails')->result_array();
        return $data;
    }

    function save_direct_invoice_detail()
    {
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $projectExist = project_is_exist();
        $segment_gls = $this->input->post('segment_gl');
        $gl_code_des = $this->input->post('gl_code_des');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $gl_code = $this->input->post('gl_code');
        $projectID = $this->input->post('projectID');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $discountPercentage = $this->input->post('discountPercentage');
        foreach ($segment_gls as $key => $segment_gl) {
            $segment = explode('|', $segment_gl);
            $gl_code_de = explode(' | ', $gl_code_des[$key]);
            $data[$key]['invoiceAutoID'] = trim($invoiceAutoID);
            if ($projectExist == 1) {
                $projectCurrency = project_currency($projectID[$key]);
                $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
                $data[$key]['projectID'] = $projectID[$key];
                $data[$key]['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
            }
            $data[$key]['revenueGLAutoID'] = $gl_code[$key];
            $data[$key]['revenueSystemGLCode'] = trim($gl_code_de[0]);
            $data[$key]['revenueGLCode'] = trim($gl_code_de[1]);
            $data[$key]['revenueGLDescription'] = trim($gl_code_de[2]);
            $data[$key]['revenueGLType'] = trim($gl_code_de[3]);
            $data[$key]['segmentID'] = trim($segment[0]);
            $data[$key]['segmentCode'] = trim($segment[1]);
            $data[$key]['discountPercentage'] = trim($discountPercentage[$key]);
            $data[$key]['discountAmount'] = trim(($amount[$key]*$discountPercentage[$key])/100);
            $data[$key]['transactionAmount'] = round($amount[$key]-$data[$key]['discountAmount'], $master['transactionCurrencyDecimalPlaces']);
            $companyLocalAmount = $data[$key]['transactionAmount'] / $master['companyLocalExchangeRate'];
            $data[$key]['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $companyReportingAmount = $data[$key]['transactionAmount'] / $master['companyReportingExchangeRate'];
            $data[$key]['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $customerAmount = $data[$key]['transactionAmount'] / $master['customerCurrencyExchangeRate'];
            $data[$key]['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
            $data[$key]['description'] = trim($description[$key]);
            $data[$key]['type'] = 'GL';
            $data[$key]['modifiedPCID'] = $this->common_data['current_pc'];
            $data[$key]['modifiedUserID'] = $this->common_data['current_userID'];
            $data[$key]['modifiedUserName'] = $this->common_data['current_user'];
            $data[$key]['modifiedDateTime'] = $this->common_data['current_date'];

            if (trim($this->input->post('invoiceDetailsAutoID'))) {
                /*$this->db->where('invoiceDetailsAutoID', trim($this->input->post('invoiceDetailsAutoID')));
                $this->db->update('srp_erp_customerinvoicedetails', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('e', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Update Failed ' . $this->db->_error_message());
                    $this->db->trans_rollback();
                    return array('status' => false);
                } else {
                    $this->session->set_flashdata('s', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Updated Successfully.');
                    $this->db->trans_commit();
                    return array('status' => true, 'last_id' => $this->input->post('invoiceDetailsAutoID'));
                }*/
            } else {
                $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
                $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];
                $data[$key]['createdUserGroup'] = $this->common_data['user_group'];
                $data[$key]['createdPCID'] = $this->common_data['current_pc'];
                $data[$key]['createdUserID'] = $this->common_data['current_userID'];
                $data[$key]['createdUserName'] = $this->common_data['current_user'];
                $data[$key]['createdDateTime'] = $this->common_data['current_date'];

            }
        }

        $this->db->insert_batch('srp_erp_customerinvoicedetails', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', 'Invoice Detail : Save Failed ' . $this->db->_error_message());
            $this->db->trans_rollback();
            return array('status' => false);

        } else {
            $this->session->set_flashdata('s', 'Invoice Detail Saved Successfully');
            $this->db->trans_commit();
            return array('status' => true);
        }
    }

    function update_income_invoice_detail()
    {
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $segment_gl = $this->input->post('segment_gl');
        $gl_code_des = $this->input->post('gl_code_des');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $projectID = $this->input->post('projectID');
        $gl_code = $this->input->post('gl_code');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $discountPercentage = $this->input->post('discountPercentage');
        $projectExist = project_is_exist();

        $segment = explode('|', $segment_gl);
        $gl_code_de = explode(' | ', $gl_code_des);
        $data['invoiceAutoID'] = trim($invoiceAutoID);
        $data['revenueGLAutoID'] = $gl_code;
        if ($projectExist == 1) {
            $projectCurrency = project_currency($projectID);
            $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
            $data['projectID'] = $projectID;
            $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
        }
        $data['revenueSystemGLCode'] = trim($gl_code_de[0]);
        $data['revenueGLCode'] = trim($gl_code_de[1]);
        $data['revenueGLDescription'] = trim($gl_code_de[2]);
        $data['revenueGLType'] = trim($gl_code_de[3]);
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['transactionAmount'] = round($amount, $master['transactionCurrencyDecimalPlaces']);

        $data['discountPercentage'] = trim($discountPercentage);
        $data['discountAmount'] = trim(($amount*$discountPercentage)/100);
        $data['transactionAmount'] = round($amount-$data['discountAmount'], $master['transactionCurrencyDecimalPlaces']);

        $companyLocalAmount = $data['transactionAmount'] / $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
        $companyReportingAmount = $data['transactionAmount'] / $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
        $customerAmount = $data['transactionAmount'] / $master['customerCurrencyExchangeRate'];
        $data['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
        $data['description'] = trim($description);
        $data['type'] = 'GL';
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('invoiceDetailsAutoID'))) {
            $this->db->where('invoiceDetailsAutoID', trim($this->input->post('invoiceDetailsAutoID')));
            $this->db->update('srp_erp_customerinvoicedetails', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Updated Successfully.');
            }
        }
    }

    function fetch_customer_invoice_detail()
    {
        $this->db->select('srp_erp_customerinvoicedetails.*,srp_erp_customerinvoicemaster.invoiceType,srp_erp_itemmaster.currentStock,srp_erp_itemmaster.mainCategory,srp_erp_unit_of_measure.UnitShortCode as secuom,srp_erp_unit_of_measure.UnitDes as secuomdec');
        $this->db->where('invoiceDetailsAutoID', trim($this->input->post('invoiceDetailsAutoID')));
        $this->db->join('srp_erp_customerinvoicemaster', 'srp_erp_customerinvoicedetails.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID', 'left');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_customerinvoicedetails.itemAutoID = srp_erp_itemmaster.itemAutoID', 'left');
        $this->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_customerinvoicedetails.SUOMID','left');
        $this->db->from('srp_erp_customerinvoicedetails');
        return $this->db->get()->row_array();
    }

    function invoice_confirmation()
    {
        $this->db->trans_start();
        $total_amount = 0;
        $tax_total = 0;
        $t_arr = array();
        $companyID = current_companyID();
        $currentuser  = current_userID();
        $locationwisecodegenerate = getPolicyValues('LDG', 'All');
        $locationemployee = $this->common_data['emplanglocationid'];

        $this->db->select('invoiceDetailsAutoID');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $this->db->from('srp_erp_customerinvoicedetails');
        $results = $this->db->get()->result_array();
        if (empty($results)) {
            return array('w', 'There are no records to confirm this document!');
        }

        else
        {
        $this->db->select('invoiceAutoID');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_customerinvoicemaster');
        $Confirmed = $this->db->get()->row_array();
        if (!empty($Confirmed)) {
            return array('w', 'Document already confirmed');
        }
        else {
            $this->load->library('Approvals');
            $this->db->select('documentID,invoiceCode,DATE_FORMAT(invoiceDate, "%Y") as invYear,DATE_FORMAT(invoiceDate, "%m") as invMonth,companyFinanceYearID');
            $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
            $this->db->from('srp_erp_customerinvoicemaster');
            $master_dt = $this->db->get()->row_array();
            $this->load->library('sequence');
            $lenth=strlen($master_dt['invoiceCode']);
            if($lenth == 1){
                if($locationwisecodegenerate == 1)
                {
                        $this->db->select('locationID');
                        $this->db->where('Erp_companyID', $companyID);
                        $this->db->where('EIdNo', $currentuser);
                        $this->db->from('srp_employeesdetails');
                        $location = $this->db->get()->row_array();
                        if ((empty($location)) || ($location ==' ')) {
                            return array('w' ,'Location is not assigned for current employee');
                        }else
                        {
                            if($locationemployee!='')
                            {
                                $codegerator = $this->sequence->sequence_generator_location($master_dt['documentID'],$master_dt['companyFinanceYearID'], $locationemployee,$master_dt['invYear'],$master_dt['invMonth']);
                            }else
                            {
                                return array('w' ,'Location is not assigned for current employee');
                            }

                        }
                }
                else
                {
                    $codegerator = $this->sequence->sequence_generator_fin($master_dt['documentID'],$master_dt['companyFinanceYearID'],$master_dt['invYear'],$master_dt['invMonth']);
                }
                $invcod = array(
                    'invoiceCode' => $codegerator,
                );
                $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                $this->db->update('srp_erp_customerinvoicemaster', $invcod);
            }


            $this->db->select('invoiceAutoID, invoiceCode, documentID,transactionCurrency, transactionExchangeRate, companyLocalExchangeRate, companyReportingExchangeRate,customerCurrencyExchangeRate,DATE_FORMAT(invoiceDate, "%Y") as invYear,DATE_FORMAT(invoiceDate, "%m") as invMonth,companyFinanceYearID,invoiceDate ');
            $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
            $this->db->from('srp_erp_customerinvoicemaster');
            $master_data = $this->db->get()->row_array();

            //$sql = "SELECT (srp_erp_customerinvoicedetails.requestedQty / srp_erp_customerinvoicedetails.conversionRateUOM) AS qty,srp_erp_warehouseitems.currentStock,(srp_erp_warehouseitems.currentStock-(srp_erp_customerinvoicedetails.requestedQty / srp_erp_customerinvoicedetails.conversionRateUOM)) as stock ,srp_erp_warehouseitems.itemAutoID,srp_erp_customerinvoicedetails.wareHouseAutoID FROM srp_erp_customerinvoicedetails INNER JOIN srp_erp_warehouseitems ON srp_erp_warehouseitems.itemAutoID = srp_erp_customerinvoicedetails.itemAutoID AND srp_erp_customerinvoicedetails.wareHouseAutoID = srp_erp_warehouseitems.wareHouseAutoID where invoiceAutoID = '{$this->input->post('invoiceAutoID')}' AND (itemCategory != 'Service' AND itemCategory != 'Non Inventory')   Having stock < 0";
            $sql = "SELECT
	(
		srp_erp_customerinvoicedetails.requestedQty / srp_erp_customerinvoicedetails.conversionRateUOM
	) AS qty,
	srp_erp_warehouseitems.currentStock,
	(
		srp_erp_warehouseitems.currentStock - (
			srp_erp_customerinvoicedetails.requestedQty / srp_erp_customerinvoicedetails.conversionRateUOM
		)
	) AS stock,
	srp_erp_warehouseitems.itemAutoID,
	srp_erp_customerinvoicedetails.wareHouseAutoID
FROM
	srp_erp_customerinvoicedetails
INNER JOIN srp_erp_warehouseitems ON srp_erp_warehouseitems.itemAutoID = srp_erp_customerinvoicedetails.itemAutoID
JOIN `srp_erp_itemmaster` ON `srp_erp_customerinvoicedetails`.`itemAutoID` = `srp_erp_itemmaster`.`itemAutoID`
AND srp_erp_customerinvoicedetails.wareHouseAutoID = srp_erp_warehouseitems.wareHouseAutoID
WHERE
	invoiceAutoID = '{$this->input->post('invoiceAutoID')}'
AND (
	mainCategory != 'Service'
	AND mainCategory != 'Non Inventory'
)
HAVING
	stock < 0";
            $item_low_qty = $this->db->query($sql)->result_array();
            if (!empty($item_low_qty)) {
                return array('e', 'Some Item quantities are not sufficient to confirm this transaction.',$item_low_qty);
            }

            $autoApproval= get_document_auto_approval('CINV');

            if($autoApproval==0){
                $approvals_status = $this->approvals->auto_approve($master_data['invoiceAutoID'], 'srp_erp_customerinvoicemaster','invoiceAutoID', 'CINV',$master_data['invoiceCode'],$master_data['invoiceDate']);
            }elseif($autoApproval==1){
                $approvals_status = $this->approvals->CreateApproval($master_data['documentID'], $master_data['invoiceAutoID'], $master_data['invoiceCode'], 'Invoice', 'srp_erp_customerinvoicemaster', 'invoiceAutoID',0,$master_data['invoiceDate']);
            }else{
                return array('e', 'Approval levels are not set for this document');
                exit;
            }
            if ($approvals_status == 1) {


                /** item Master Sub check */
                $invoiceAutoID = trim($this->input->post('invoiceAutoID'));
                $validate = $this->validate_itemMasterSub($invoiceAutoID);

                /** end of item master sub */
                if ($validate) {
                    $this->db->select_sum('transactionAmount');
                    $this->db->where('InvoiceAutoID', $master_data['invoiceAutoID']);
                    $transaction_total_amount = $this->db->get('srp_erp_customerinvoicedetails')->row('transactionAmount');

                    $this->db->select_sum('totalAfterTax');
                    $this->db->where('InvoiceAutoID', $master_data['invoiceAutoID']);
                    $item_tax = $this->db->get('srp_erp_customerinvoicedetails')->row('totalAfterTax');
                    $total_amount = ($transaction_total_amount - $item_tax);
                    $this->db->select('taxDetailAutoID,supplierCurrencyExchangeRate,companyReportingExchangeRate ,companyLocalExchangeRate ,taxPercentage');
                    $this->db->where('InvoiceAutoID', $master_data['invoiceAutoID']);
                    $tax_arr = $this->db->get('srp_erp_customerinvoicetaxdetails')->result_array();
                    for ($x = 0; $x < count($tax_arr); $x++) {
                        $tax_total_amount = (($tax_arr[$x]['taxPercentage'] / 100) * $total_amount);
                        $t_arr[$x]['taxDetailAutoID'] = $tax_arr[$x]['taxDetailAutoID'];
                        $t_arr[$x]['transactionAmount'] = $tax_total_amount;
                        $t_arr[$x]['supplierCurrencyAmount'] = ($t_arr[$x]['transactionAmount'] / $tax_arr[$x]['supplierCurrencyExchangeRate']);
                        $t_arr[$x]['companyLocalAmount'] = ($t_arr[$x]['transactionAmount'] / $tax_arr[$x]['companyLocalExchangeRate']);
                        $t_arr[$x]['companyReportingAmount'] = ($t_arr[$x]['transactionAmount'] / $tax_arr[$x]['companyReportingExchangeRate']);
                        $tax_total = $t_arr[$x]['transactionAmount'];
                    }
                    /*updating transaction amount using the query used in the master data table  done by mushtaq*/
                    $companyID=current_companyID();
                    $invautoid=$this->input->post('invoiceAutoID');
                    $r1 = "SELECT
	`srp_erp_customerinvoicemaster`.`invoiceAutoID` AS `invoiceAutoID`,
	`srp_erp_customerinvoicemaster`.`companyLocalExchangeRate` AS `companyLocalExchangeRate`,
	`srp_erp_customerinvoicemaster`.`companyLocalCurrencyDecimalPlaces` AS `companyLocalCurrencyDecimalPlaces`,
	`srp_erp_customerinvoicemaster`.`companyReportingExchangeRate` AS `companyReportingExchangeRate`,
	`srp_erp_customerinvoicemaster`.`companyReportingCurrencyDecimalPlaces` AS `companyReportingCurrencyDecimalPlaces`,
	`srp_erp_customerinvoicemaster`.`customerCurrencyExchangeRate` AS `customerCurrencyExchangeRate`,
	`srp_erp_customerinvoicemaster`.`customerCurrencyDecimalPlaces` AS `customerCurrencyDecimalPlaces`,
	`srp_erp_customerinvoicemaster`.`transactionCurrencyDecimalPlaces` AS `transactionCurrencyDecimalPlaces`,

	(
		IFNULL(addondet.taxPercentage, 0) / 100
	) * (
		IFNULL(det.transactionAmount, 0) - IFNULL(det.detailtaxamount, 0) - (
			(
				IFNULL(
					gendiscount.discountPercentage,
					0
				) / 100
			) * IFNULL(det.transactionAmount, 0)
		) + IFNULL(
			genexchargistax.transactionAmount,
			0
		)
	) + IFNULL(det.transactionAmount, 0) - (
		(
			IFNULL(
				gendiscount.discountPercentage,
				0
			) / 100
		) * IFNULL(det.transactionAmount, 0)
	) + IFNULL(
		genexcharg.transactionAmount,
		0
	) AS total_value

FROM
	`srp_erp_customerinvoicemaster`
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		sum(totalafterTax) AS detailtaxamount,
		invoiceAutoID
	FROM
		srp_erp_customerinvoicedetails
	GROUP BY
		invoiceAutoID
) det ON (
	`det`.`invoiceAutoID` = srp_erp_customerinvoicemaster.invoiceAutoID
)
LEFT JOIN (
	SELECT
		SUM(taxPercentage) AS taxPercentage,
		InvoiceAutoID
	FROM
		srp_erp_customerinvoicetaxdetails
	GROUP BY
		InvoiceAutoID
) addondet ON (
	`addondet`.`InvoiceAutoID` = srp_erp_customerinvoicemaster.InvoiceAutoID
)
LEFT JOIN (
	SELECT
		SUM(discountPercentage) AS discountPercentage,
		invoiceAutoID
	FROM
		srp_erp_customerinvoicediscountdetails
	GROUP BY
		invoiceAutoID
) gendiscount ON (
	`gendiscount`.`InvoiceAutoID` = srp_erp_customerinvoicemaster.InvoiceAutoID
)
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		invoiceAutoID
	FROM
		srp_erp_customerinvoiceextrachargedetails
	WHERE
		isTaxApplicable = 1
	GROUP BY
		invoiceAutoID
) genexchargistax ON (
	`genexchargistax`.`InvoiceAutoID` = srp_erp_customerinvoicemaster.InvoiceAutoID
)
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		invoiceAutoID
	FROM
		srp_erp_customerinvoiceextrachargedetails
	GROUP BY
		invoiceAutoID
) genexcharg ON (
	`genexcharg`.`InvoiceAutoID` = srp_erp_customerinvoicemaster.InvoiceAutoID
)
WHERE
	`companyID` = $companyID
and srp_erp_customerinvoicemaster.invoiceAutoID= $invautoid ";
                    $totalValue = $this->db->query($r1)->row_array();
                    $data = array(
                        'confirmedYN' => 1,
                        'confirmedDate' => $this->common_data['current_date'],
                        'confirmedByEmpID' => $this->common_data['current_userID'],
                        'confirmedByName' => $this->common_data['current_user'],
                        'transactionAmount' => (round($totalValue['total_value'],$totalValue['transactionCurrencyDecimalPlaces'])),
                        'companyLocalAmount' => (round($totalValue['total_value'] / $totalValue['companyLocalExchangeRate'],$totalValue['companyLocalCurrencyDecimalPlaces'])),
                        'companyReportingAmount' => (round($totalValue['total_value'] / $totalValue['companyReportingExchangeRate'],$totalValue['companyReportingCurrencyDecimalPlaces'])),
                        'customerCurrencyAmount' => (round($totalValue['total_value'] / $totalValue['customerCurrencyExchangeRate'],$totalValue['customerCurrencyDecimalPlaces'])),
                    );
                    $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                    $this->db->update('srp_erp_customerinvoicemaster', $data);
                    if (!empty($t_arr)) {
                        $this->db->update_batch('srp_erp_customerinvoicetaxdetails', $t_arr, 'taxDetailAutoID');
                    }
                } else {
                    return array('e', 'Please complete your sub item configurations<br/><br/> Please add sub item/s before confirm this document.');
                    /*return array('error' => 1, 'message' => 'Please complete your sub item configurations<br/><br/> Please add sub item/s before confirm this document.');*//*return array('error' => 1, 'message' => 'Please complete your sub item configurations<br/><br/> Please add sub item/s before confirm this document.');*/
                    exit;

                }


            }elseif($approvals_status == 3){
                return array('w', 'There are no users exist to perform approval for this document.');
                exit;
            }


        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            //$this->session->set_flashdata('e', 'Supplier Invoice Detail : ' . $data['GLDescription']. '  Saved Failed ' . $this->db->_error_message());
            $this->db->trans_rollback();
            /* return array('error' => 0, 'message' => 'Supplier Invoice Detail : ' . $data['GLDescription'] . '  Saved Failed ' . $this->db->_error_message());*/
            return array('e', 'Supplier Invoice Detail : ' . $data['GLDescription'] . '  Saved Failed ' . $this->db->_error_message());
            //return array('status' => false);
        } else {
            $autoApproval= get_document_auto_approval('CINV');

            if($autoApproval==0) {
                $result = $this->save_invoice_approval(0, $master_data['invoiceAutoID'], 1, 'Auto Approved');
                if($result){
                    $this->db->trans_commit();
                    return array('s', 'Document confirmed successfully');
                }
            }else{
                $this->db->trans_commit();
                return array('s', 'Document confirmed successfully');
            }
        }
    }
    }

    function validate_itemMasterSub($itemAutoID)
    {
        $query1 = "SELECT
                        count(*) AS countAll 
                    FROM
                        srp_erp_customerinvoicemaster cinv
                    LEFT JOIN srp_erp_customerinvoicedetails cinvDetail ON cinv.invoiceAutoID = cinvDetail.invoiceAutoID
                    LEFT JOIN srp_erp_itemmaster_sub subItemMaster ON subItemMaster.soldDocumentDetailID = cinvDetail.invoiceDetailsAutoID
                    LEFT JOIN srp_erp_itemmaster itemmaster ON itemmaster.itemAutoID = cinvDetail.itemAutoID
                    WHERE
                        cinv.invoiceAutoID = '" . $itemAutoID . "'
                    AND itemmaster.isSubitemExist = 1 ";
        $r1 = $this->db->query($query1)->row_array();

        $query2 = "SELECT
                        SUM(cinvDetail.requestedQty) AS totalQty
                    FROM
                        srp_erp_customerinvoicemaster cinv
                    LEFT JOIN srp_erp_customerinvoicedetails cinvDetail ON cinv.invoiceAutoID = cinvDetail.invoiceAutoID
                    LEFT JOIN srp_erp_itemmaster itemmaster ON itemmaster.itemAutoID = cinvDetail.itemAutoID
                    WHERE
                        cinv.invoiceAutoID = '" . $itemAutoID . "'
                    AND itemmaster.isSubitemExist = 1";


        $r2 = $this->db->query($query2)->row_array();


        if (empty($r1) && empty($r2)) {
            $validate = true;
        } else if (empty($r1) || $r1['countAll'] == 0) {
            $validate = true;
        } else {
            if ($r1['countAll'] == $r2['totalQty']) {
                $validate = true;
            } else {
                $validate = false;
            }
        }
        return $validate;

    }

    function fetch_customer_con($master)
    {
        $customerID = $master['customerID'];
        $currencyID = $master['transactionCurrencyID'];
        $invType = $master['invoiceType'];

        //$invoiceDate    = format_date($master['invoiceDate']);
        //$contractExp    = $master['contractExpDate'];
        $data = $this->db->query("SELECT srp_erp_contractmaster.contractAutoID,srp_erp_contractmaster.contractCode 
                                  FROM srp_erp_contractdetails INNER JOIN srp_erp_contractmaster ON srp_erp_contractdetails.contractAutoID = srp_erp_contractmaster.contractAutoID 
                                  LEFT JOIN srp_erp_customerinvoicedetails ON srp_erp_customerinvoicedetails.contractDetailsAutoID = srp_erp_contractdetails.contractDetailsAutoID 
                                  WHERE `customerID` = '{$customerID}' AND `contractType` = '{$invType}' AND `transactionCurrencyID` = '{$currencyID}' AND `confirmedYN` = 1 AND `closedYN` = 0 
                                  AND srp_erp_contractdetails.invoicedYN = 0 AND `approvedYN` = 1  GROUP BY srp_erp_contractmaster.contractCode")->result_array();
        //AND '{$invoiceDate}' BETWEEN contractDate AND contractExpDate

        return $data;
    }

    function fetch_con_detail_table()
    {
        $companyID = current_companyID();
        $contract_id = trim($this->input->post('contractAutoID'));

        $data['detail'] = $this->db->query("SELECT conDet.*, SUM(recTB.requestedQty) AS receivedQty
                            FROM srp_erp_contractdetails conDet
                            LEFT JOIN (
                                SELECT contractDetailsAutoID, requestedQty FROM srp_erp_customerinvoicedetails WHERE contractAutoID = {$contract_id}
                                UNION ALL
                                SELECT contractDetailsAutoID, requestedQty FROM srp_erp_deliveryorderdetails WHERE contractAutoID = {$contract_id}
                            ) recTB ON recTB.contractDetailsAutoID = conDet.contractDetailsAutoID
                            WHERE conDet.contractAutoID = '{$contract_id}' AND invoicedYN = 0
                            GROUP BY contractDetailsAutoID")->result_array();

        $this->db->select("wareHouseCode,wareHouseDescription,companyCode,wareHouseAutoID,wareHouseLocation");
        $this->db->from('srp_erp_warehousemaster');
        $this->db->where('companyID', $companyID);
        $data['ware_house'] = $this->db->get()->result_array();
        $data['tax_master'] = all_tax_drop(1, 0);
        return $data;
    }

    function save_con_base_items()
    {
        $this->db->trans_start();
        $items_arr = array();
        $this->db->select('srp_erp_contractdetails.*,sum(srp_erp_customerinvoicedetails.requestedQty) AS receivedQty,srp_erp_contractmaster.contractCode');
        $this->db->from('srp_erp_contractdetails');
        $this->db->where_in('srp_erp_contractdetails.contractDetailsAutoID', $this->input->post('DetailsID'));
        $this->db->join('srp_erp_contractmaster', 'srp_erp_contractmaster.contractAutoID = srp_erp_contractdetails.contractAutoID');
        $this->db->join('srp_erp_customerinvoicedetails', 'srp_erp_customerinvoicedetails.contractDetailsAutoID = srp_erp_contractdetails.contractDetailsAutoID', 'left');
        $this->db->group_by("contractDetailsAutoID");
        $query = $this->db->get()->result_array();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate ,segmentID,segmentCode,transactionCurrency,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces');
        $this->db->from('srp_erp_customerinvoicemaster');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $master = $this->db->get()->row_array();
        $qty = $this->input->post('qty');
        $amount = $this->input->post('amount');
        $discount = $this->input->post('discount');
        $wareHouseAutoID = $this->input->post('wareHouseAutoID');
        $whrehouse = $this->input->post('whrehouse');
        $tex_id = $this->input->post('tex_id');
        $tex_percntage = $this->input->post('tex_percntage');
        $remarks = $this->input->post('remarks');



        for ($i = 0; $i < count($query); $i++) {
            $discount_percentage = ($discount[$i] / $amount[$i])*100;
            $this->db->select('contractAutoID');
            $this->db->from('srp_erp_customerinvoicedetails');
            $this->db->where('contractAutoID', $query[$i]['contractAutoID']);
            $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
            $this->db->where('itemAutoID', $query[$i]['itemAutoID']);
            $order_detail = $this->db->get()->result_array();
            $item_data = fetch_item_data($query[$i]['itemAutoID']);
            $wareHouse_arr = explode('|', $whrehouse[$i]);

            if (isset($tex_id[$i])) {
                /*$this->db->select('*');
                $this->db->where('taxMasterAutoID', $tex_id[$i]);
                $tax_master = $this->db->get('srp_erp_taxmaster')->row_array();*/

                /*$this->db->select('*');
                $this->db->where('supplierSystemCode', $tax_master['supplierSystemCode']);
                $Supplier_master = $this->db->get('srp_erp_suppliermaster')->row_array();*/

                $this->db->select('srp_erp_taxmaster.*,srp_erp_chartofaccounts.GLAutoID as liabilityAutoID,srp_erp_chartofaccounts.systemAccountCode as liabilitySystemGLCode,srp_erp_chartofaccounts.GLSecondaryCode as liabilityGLAccount,srp_erp_chartofaccounts.GLDescription as liabilityDescription,srp_erp_chartofaccounts.CategoryTypeDescription as liabilityType,srp_erp_currencymaster.CurrencyCode,srp_erp_currencymaster.DecimalPlaces');
                $this->db->where('taxMasterAutoID', $tex_id[$i]);
                $this->db->from('srp_erp_taxmaster');
                $this->db->join('srp_erp_chartofaccounts', 'srp_erp_chartofaccounts.GLAutoID = srp_erp_taxmaster.supplierGLAutoID');
                $this->db->join('srp_erp_currencymaster', 'srp_erp_currencymaster.currencyID = srp_erp_taxmaster.supplierCurrencyID');
                $tax_master = $this->db->get()->row_array();
            }
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $query[$i]['itemAutoID']);
            $serviceitm= $this->db->get()->row_array();

            if (!empty($order_detail) && $serviceitm['mainCategory']=="Inventory") {
                $this->session->set_flashdata('w', 'Invoice Detail : ' . trim($this->input->post('itemCode')) . '  already exists.');
                $this->db->trans_rollback();
                return array('w', 'Invoice Detail : ' . trim($this->input->post('itemCode')) . '  already exists.');
            }
            else {
                $data[$i]['type'] = 'Item';
                $data[$i]['contractAutoID'] = $query[$i]['contractAutoID'];
                $data[$i]['contractCode'] = $query[$i]['contractCode'];
                $data[$i]['contractDetailsAutoID'] = $query[$i]['contractDetailsAutoID'];
                $data[$i]['invoiceAutoID'] = trim($this->input->post('invoiceAutoID'));
                $data[$i]['itemAutoID'] = $query[$i]['itemAutoID'];
                $data[$i]['itemSystemCode'] = $query[$i]['itemSystemCode'];
                $data[$i]['itemDescription'] = $query[$i]['itemDescription'];
                $data[$i]['defaultUOM'] = $query[$i]['defaultUOM'];
                $data[$i]['defaultUOMID'] = $query[$i]['defaultUOMID'];
                $data[$i]['unitOfMeasure'] = $query[$i]['unitOfMeasure'];
                $data[$i]['unitOfMeasureID'] = $query[$i]['unitOfMeasureID'];
                $data[$i]['conversionRateUOM'] = $query[$i]['conversionRateUOM'];
                $data[$i]['contractQty'] = $query[$i]['requestedQty'];
                $data[$i]['contractAmount'] = $query[$i]['unittransactionAmount'];
                $data[$i]['comment'] = $query[$i]['comment'];
                $data[$i]['requestedQty'] = $qty[$i];
                $data[$i]['unittransactionAmount'] = $amount[$i];
                $data[$i]['discountAmount'] = $discount[$i];
                $data[$i]['discountPercentage'] = $discount_percentage;
                $data[$i]['companyLocalWacAmount'] = $item_data['companyLocalWacAmount'];
                $data[$i]['itemCategory'] = trim($item_data['mainCategory']);
                $data[$i]['segmentID'] = $master['segmentID'];
                $data[$i]['segmentCode'] = $master['segmentCode'];
                $data[$i]['expenseGLAutoID'] = $item_data['costGLAutoID'];
                $data[$i]['expenseSystemGLCode'] = $item_data['costSystemGLCode'];
                $data[$i]['expenseGLCode'] = $item_data['costGLCode'];
                $data[$i]['expenseGLDescription'] = $item_data['costDescription'];
                $data[$i]['expenseGLType'] = $item_data['costType'];
                $data[$i]['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
                $data[$i]['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
                $data[$i]['revenueGLCode'] = $item_data['revanueGLCode'];
                $data[$i]['revenueGLDescription'] = $item_data['revanueDescription'];
                $data[$i]['revenueGLType'] = $item_data['revanueType'];
                $data[$i]['assetGLAutoID'] = $item_data['assteGLAutoID'];
                $data[$i]['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
                $data[$i]['assetGLCode'] = $item_data['assteGLCode'];
                $data[$i]['assetGLDescription'] = $item_data['assteDescription'];
                $data[$i]['assetGLType'] = $item_data['assteType'];
                $data[$i]['comment'] = $query[$i]['comment'];
                $data[$i]['remarks'] = $remarks[$i];
                $data[$i]['wareHouseAutoID'] = $wareHouseAutoID[$i];//$master['wareHouseAutoID'];
                $data[$i]['wareHouseCode'] = $wareHouse_arr[0]; //$master['wareHouseCode'];
                $data[$i]['wareHouseLocation'] = $wareHouse_arr[1]; //$master['wareHouseLocation'];
                //$data[$i]['wareHouseDescription'] = $wareHouse_arr[2]; //$master['wareHouseDescription'];
                $data[$i]['taxPercentage'] = $tex_percntage[$i];
                $tax_amount = ($data[$i]['taxPercentage'] / 100) * ($data[$i]['unittransactionAmount'] - $data[$i]['discountAmount']);
                $data[$i]['taxAmount'] =round($tax_amount, $master['transactionCurrencyDecimalPlaces']);
                $totalAfterTax  = ($data[$i]['taxAmount'] * $data[$i]['requestedQty']);
                $data[$i]['totalAfterTax'] = round($totalAfterTax, $master['transactionCurrencyDecimalPlaces']);
                $transactionAmount = ($data[$i]['requestedQty'] * ($data[$i]['unittransactionAmount'] - $discount[$i] )) + $data[$i]['totalAfterTax'];
                $data[$i]['transactionAmount'] = round($transactionAmount, $master['transactionCurrencyDecimalPlaces']);
                $companyLocalAmount = $data[$i]['transactionAmount'] / $master['companyLocalExchangeRate'];
                $data[$i]['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
                $companyReportingAmount = $data[$i]['transactionAmount'] / $master['companyReportingExchangeRate'];
                $data[$i]['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
                $customerAmount = $data[$i]['transactionAmount'] / $master['customerCurrencyExchangeRate'];
                $data[$i]['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);

                if (!empty($tax_master)) {
                    $data[$i]['taxMasterAutoID'] = $tax_master['taxMasterAutoID'];
                    $data[$i]['taxDescription'] = $tax_master['taxDescription'];
                    $data[$i]['taxShortCode'] = $tax_master['taxShortCode'];
                    $data[$i]['taxSupplierAutoID'] = $tax_master['supplierAutoID'];
                    $data[$i]['taxSupplierSystemCode'] = $tax_master['supplierSystemCode'];
                    $data[$i]['taxSupplierName'] = $tax_master['supplierName'];
                    $data[$i]['taxSupplierCurrencyID'] = $tax_master['supplierCurrencyID'];
                    $data[$i]['taxSupplierCurrency'] = $tax_master['CurrencyCode'];
                    $data[$i]['taxSupplierCurrencyDecimalPlaces'] = $tax_master['DecimalPlaces'];
                    $data[$i]['taxSupplierliabilityAutoID'] = $tax_master['liabilityAutoID'];
                    $data[$i]['taxSupplierliabilitySystemGLCode'] = $tax_master['liabilitySystemGLCode'];
                    $data[$i]['taxSupplierliabilityGLAccount'] = $tax_master['liabilityGLAccount'];
                    $data[$i]['taxSupplierliabilityDescription'] = $tax_master['liabilityDescription'];
                    $data[$i]['taxSupplierliabilityType'] = $tax_master['liabilityType'];
                    $supplierCurrency = currency_conversion($master['transactionCurrency'], $data[$i]['taxSupplierCurrency']);
                    $data[$i]['taxSupplierCurrencyExchangeRate'] = $supplierCurrency['conversion'];
                    $data[$i]['taxSupplierCurrencyDecimalPlaces'] = $supplierCurrency['DecimalPlaces'];
                    $data[$i]['taxSupplierCurrencyAmount'] = ($data[$i]['transactionAmount'] / $data[$i]['taxSupplierCurrencyExchangeRate']);
                } else {
                    $data[$i]['taxMasterAutoID'] = null;
                    $data[$i]['taxDescription'] = null;
                    $data[$i]['taxShortCode'] = null;
                    $data[$i]['taxSupplierAutoID'] = null;
                    $data[$i]['taxSupplierSystemCode'] = null;
                    $data[$i]['taxSupplierName'] = null;
                    $data[$i]['taxSupplierCurrencyID'] = null;
                    $data[$i]['taxSupplierCurrency'] = null;
                    $data[$i]['taxSupplierCurrencyDecimalPlaces'] = null;
                    $data[$i]['taxSupplierliabilityAutoID'] = null;
                    $data[$i]['taxSupplierliabilitySystemGLCode'] = null;
                    $data[$i]['taxSupplierliabilityGLAccount'] = null;
                    $data[$i]['taxSupplierliabilityDescription'] = null;
                    $data[$i]['taxSupplierliabilityType'] = null;

                    $data[$i]['taxSupplierCurrencyExchangeRate'] = 1;
                    $data[$i]['taxSupplierCurrencyDecimalPlaces'] = 2;
                    $data[$i]['taxSupplierCurrencyAmount'] = 0;
                }
                $data[$i]['companyCode'] = $this->common_data['company_data']['company_code'];
                $data[$i]['companyID'] = $this->common_data['company_data']['company_id'];
                $data[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $data[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $data[$i]['modifiedUserName'] = $this->common_data['current_user'];
                $data[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $data[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $data[$i]['createdPCID'] = $this->common_data['current_pc'];
                $data[$i]['createdUserID'] = $this->common_data['current_userID'];
                $data[$i]['createdUserName'] = $this->common_data['current_user'];
                $data[$i]['createdDateTime'] = $this->common_data['current_date'];

                // $con_data[$i]['contractDetailsAutoID']  = $query[$i]['contractDetailsAutoID'];
                // $con_data[$i]['invoicedYN']         = 0;
                // if ($query[$i]['requestedQty'] <= (floatval($qty[$i])+floatval($query[$i]['receivedQty']))) {
                //     $con_data[$i]['invoicedYN']         = 1;
                // }
            }
        }

        if (!empty($data)) {
            $this->db->insert_batch('srp_erp_customerinvoicedetails', $data);
            //$this->db->update_batch('srp_erp_contractdetails', $con_data, 'contractDetailsAutoID'); 
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Invoice Details Save Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Invoice ' . count($query) . ' Item Details Saved Successfully.');
            }
        }
        else {
            return array('e', 'There is no data to process');
        }
    }

    function save_invoice_approval($autoappLevel=1,$system_idAP=0,$statusAP=0,$commentsAP=0)
    {

        $this->load->library('Approvals');
        if($autoappLevel==1){
            $system_id = trim($this->input->post('invoiceAutoID'));
            $level_id = trim($this->input->post('Level'));
            $status = trim($this->input->post('status'));
            $comments = trim($this->input->post('comments'));
        }else{
            $system_id = $system_idAP;
            $level_id = 0;
            $status = $statusAP;
            $comments = $commentsAP;
            $_post['invoiceAutoID']=$system_id;
            $_post['Level']=$level_id;
            $_post['status']=$status;
            $_post['comments']=$comments;
        }


        $sql = "SELECT
	(
		srp_erp_warehouseitems.currentStock - srp_erp_customerinvoicedetails.requestedQty
	) AS stockDiff,
	srp_erp_itemmaster.itemSystemCode,
	srp_erp_itemmaster.itemDescription,
	srp_erp_warehouseitems.currentStock as availableStock
FROM
	`srp_erp_customerinvoicedetails`
JOIN `srp_erp_warehouseitems` ON `srp_erp_customerinvoicedetails`.`itemAutoID` = `srp_erp_warehouseitems`.`itemAutoID`
AND `srp_erp_customerinvoicedetails`.`wareHouseAutoID` = `srp_erp_warehouseitems`.`wareHouseAutoID`
JOIN `srp_erp_itemmaster` ON `srp_erp_customerinvoicedetails`.`itemAutoID` = `srp_erp_itemmaster`.`itemAutoID`

WHERE
	`srp_erp_customerinvoicedetails`.`invoiceAutoID` = '$system_id'
AND `srp_erp_warehouseitems`.`companyID` = " . current_companyID() . "
AND (itemCategory != 'Service' AND itemCategory != 'Non Inventory')
HAVING
	`stockDiff` < 0";
        $items_arr = $this->db->query($sql)->result_array();
        if($status!=1){
            $items_arr='';
        }
        if (!$items_arr) {
            if($autoappLevel==0){
                $approvals_status=1;
            }else{
                $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'CINV');
            }
            if ($approvals_status == 1) {
                $this->db->select('*');
                $this->db->where('invoiceAutoID', $system_id);
                $this->db->from('srp_erp_customerinvoicemaster');
                $master = $this->db->get()->row_array();
                $this->db->select('*');
                $this->db->where('invoiceAutoID', $system_id);
                $this->db->from('srp_erp_customerinvoicedetails');
                $invoice_detail = $this->db->get()->result_array();
                if($master["invoiceType"] != "Manufacturing") {
                    if($master["invoiceType"] != "Insurance") {
                        for ($a = 0; $a < count($invoice_detail); $a++) {
                            if ($invoice_detail[$a]['type'] == 'Item') {
                                $item = fetch_item_data($invoice_detail[$a]['itemAutoID']);
                                if ($item['mainCategory'] == 'Inventory' or $item['mainCategory'] == 'Non Inventory') {
                                    $itemAutoID = $invoice_detail[$a]['itemAutoID'];
                                    $qty = $invoice_detail[$a]['requestedQty'] / $invoice_detail[$a]['conversionRateUOM'];
                                    $wareHouseAutoID = $invoice_detail[$a]['wareHouseAutoID'];
                                    $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock - {$qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' and itemAutoID='{$itemAutoID}'");

                                    $item_arr[$a]['itemAutoID'] = $invoice_detail[$a]['itemAutoID'];
                                    $item_arr[$a]['currentStock'] = ($item['currentStock'] - $qty);
                                    $item_arr[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) - ($item['companyLocalWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                                    $item_arr[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) - ($item['companyReportingWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);
                                    if (!empty($item_arr)) {
                                        $this->db->where('itemAutoID', trim($invoice_detail[$a]['itemAutoID']));
                                        $this->db->update('srp_erp_itemmaster', $item_arr[$a]);
                                    }
                                    $itemledger_arr[$a]['documentID'] = $master['documentID'];
                                    $itemledger_arr[$a]['documentCode'] = $master['documentID'];
                                    $itemledger_arr[$a]['documentAutoID'] = $master['invoiceAutoID'];
                                    $itemledger_arr[$a]['documentSystemCode'] = $master['invoiceCode'];
                                    $itemledger_arr[$a]['documentDate'] = $master['invoiceDate'];
                                    $itemledger_arr[$a]['referenceNumber'] = $master['referenceNo'];
                                    $itemledger_arr[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                                    $itemledger_arr[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                                    $itemledger_arr[$a]['FYBegin'] = $master['FYBegin'];
                                    $itemledger_arr[$a]['FYEnd'] = $master['FYEnd'];
                                    $itemledger_arr[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                                    $itemledger_arr[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                                    $itemledger_arr[$a]['wareHouseAutoID'] = $invoice_detail[$a]['wareHouseAutoID'];
                                    $itemledger_arr[$a]['wareHouseCode'] = $invoice_detail[$a]['wareHouseCode'];
                                    $itemledger_arr[$a]['wareHouseLocation'] = $invoice_detail[$a]['wareHouseLocation'];
                                    $itemledger_arr[$a]['wareHouseDescription'] = $invoice_detail[$a]['wareHouseDescription'];
                                    $itemledger_arr[$a]['itemAutoID'] = $invoice_detail[$a]['itemAutoID'];
                                    $itemledger_arr[$a]['itemSystemCode'] = $invoice_detail[$a]['itemSystemCode'];
                                    $itemledger_arr[$a]['itemDescription'] = $invoice_detail[$a]['itemDescription'];
                                    $itemledger_arr[$a]['SUOMID'] = $invoice_detail[$a]['SUOMID'];
                                    $itemledger_arr[$a]['SUOMQty'] = $invoice_detail[$a]['SUOMQty'];
                                    $itemledger_arr[$a]['defaultUOMID'] = $invoice_detail[$a]['defaultUOMID'];
                                    $itemledger_arr[$a]['defaultUOM'] = $invoice_detail[$a]['defaultUOM'];
                                    $itemledger_arr[$a]['transactionUOMID'] = $invoice_detail[$a]['unitOfMeasureID'];
                                    $itemledger_arr[$a]['transactionUOM'] = $invoice_detail[$a]['unitOfMeasure'];
                                    $itemledger_arr[$a]['transactionQTY'] = ($invoice_detail[$a]['requestedQty'] * -1);
                                    $itemledger_arr[$a]['convertionRate'] = $invoice_detail[$a]['conversionRateUOM'];
                                    $itemledger_arr[$a]['currentStock'] = $item_arr[$a]['currentStock'];
                                    $itemledger_arr[$a]['PLGLAutoID'] = $item['costGLAutoID'];
                                    $itemledger_arr[$a]['PLSystemGLCode'] = $item['costSystemGLCode'];
                                    $itemledger_arr[$a]['PLGLCode'] = $item['costGLCode'];
                                    $itemledger_arr[$a]['PLDescription'] = $item['costDescription'];
                                    $itemledger_arr[$a]['PLType'] = $item['costType'];
                                    $itemledger_arr[$a]['BLGLAutoID'] = $item['assteGLAutoID'];
                                    $itemledger_arr[$a]['BLSystemGLCode'] = $item['assteSystemGLCode'];
                                    $itemledger_arr[$a]['BLGLCode'] = $item['assteGLCode'];
                                    $itemledger_arr[$a]['BLDescription'] = $item['assteDescription'];
                                    $itemledger_arr[$a]['BLType'] = $item['assteType'];
                                    $itemledger_arr[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];
                                    $ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                                    $itemledger_arr[$a]['transactionAmount'] = round((($invoice_detail[$a]['companyLocalWacAmount'] / $ex_rate_wac) * ($itemledger_arr[$a]['transactionQTY'] / $invoice_detail[$a]['conversionRateUOM'])), $itemledger_arr[$a]['transactionCurrencyDecimalPlaces']);
                                    $itemledger_arr[$a]['salesPrice'] = (($invoice_detail[$a]['transactionAmount'] / ($itemledger_arr[$a]['transactionQTY'] / $invoice_detail[$a]['conversionRateUOM'])) * -1);
                                    $itemledger_arr[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                                    $itemledger_arr[$a]['transactionCurrency'] = $master['transactionCurrency'];
                                    $itemledger_arr[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                                    $itemledger_arr[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                                    $itemledger_arr[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                                    $itemledger_arr[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                                    $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                                    $itemledger_arr[$a]['companyLocalAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyLocalExchangeRate']), $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces']);
                                    $itemledger_arr[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                                    $itemledger_arr[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                                    $itemledger_arr[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                                    $itemledger_arr[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                                    $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                                    $itemledger_arr[$a]['companyReportingAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyReportingExchangeRate']), $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces']);
                                    $itemledger_arr[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];
                                    $itemledger_arr[$a]['partyCurrencyID'] = $master['customerCurrencyID'];
                                    $itemledger_arr[$a]['partyCurrency'] = $master['customerCurrency'];
                                    $itemledger_arr[$a]['partyCurrencyExchangeRate'] = $master['customerCurrencyExchangeRate'];
                                    $itemledger_arr[$a]['partyCurrencyDecimalPlaces'] = $master['customerCurrencyDecimalPlaces'];
                                    $itemledger_arr[$a]['partyCurrencyAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['partyCurrencyExchangeRate']), $itemledger_arr[$a]['partyCurrencyDecimalPlaces']);
                                    $itemledger_arr[$a]['confirmedYN'] = $master['confirmedYN'];
                                    $itemledger_arr[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                                    $itemledger_arr[$a]['confirmedByName'] = $master['confirmedByName'];
                                    $itemledger_arr[$a]['confirmedDate'] = $master['confirmedDate'];
                                    $itemledger_arr[$a]['approvedYN'] = $master['approvedYN'];
                                    $itemledger_arr[$a]['approvedDate'] = $master['approvedDate'];
                                    $itemledger_arr[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                                    $itemledger_arr[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                                    $itemledger_arr[$a]['segmentID'] = $master['segmentID'];
                                    $itemledger_arr[$a]['segmentCode'] = $master['segmentCode'];
                                    $itemledger_arr[$a]['companyID'] = $master['companyID'];
                                    $itemledger_arr[$a]['companyCode'] = $master['companyCode'];
                                    $itemledger_arr[$a]['createdUserGroup'] = $master['createdUserGroup'];
                                    $itemledger_arr[$a]['createdPCID'] = $master['createdPCID'];
                                    $itemledger_arr[$a]['createdUserID'] = $master['createdUserID'];
                                    $itemledger_arr[$a]['createdDateTime'] = $master['createdDateTime'];
                                    $itemledger_arr[$a]['createdUserName'] = $master['createdUserName'];
                                    $itemledger_arr[$a]['modifiedPCID'] = $master['modifiedPCID'];
                                    $itemledger_arr[$a]['modifiedUserID'] = $master['modifiedUserID'];
                                    $itemledger_arr[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                                    $itemledger_arr[$a]['modifiedUserName'] = $master['modifiedUserName'];
                                }
                            }
                        }
                        if (!empty($itemledger_arr)) {
                            $itemledger_arr = array_values($itemledger_arr);
                            $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr);
                        }
                    }
                    $this->load->model('Double_entry_model');
                    if($master["invoiceType"] != "Insurance") {
                        $double_entry = $this->Double_entry_model->fetch_double_entry_customer_invoice_data($system_id, 'CINV');
                    }else{
                        $double_entry = $this->Double_entry_model->fetch_double_entry_customer_invoice_data_insurance($system_id, 'CINV');
                    }

                    //echo '<pre>';print_r($double_entry['gl_detail']); echo '</pre>';
                    for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                        $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['invoiceAutoID'];
                        $generalledger_arr[$i]['documentCode'] = $double_entry['master_data']['documentID'];
                        $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['invoiceCode'];
                        $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['invoiceDate'];
                        $generalledger_arr[$i]['documentType'] = '';
                        $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['invoiceDate'];
                        $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['invoiceDate']));
                        $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['invoiceNarration'];
                        $generalledger_arr[$i]['chequeNumber'] = '';
                        $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                        $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                        $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                        $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                        $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                        $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                        $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                        $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                        $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                        $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['partyContractID'] = '';
                        $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                        $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                        $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                        $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                        $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                        $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                        $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                        $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['taxMasterAutoID'] = $double_entry['gl_detail'][$i]['taxMasterAutoID'];
                        $generalledger_arr[$i]['partyVatIdNo'] = $double_entry['gl_detail'][$i]['partyVatIdNo'];
                        $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                        $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                        $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                        $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                        $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                        $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                        $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                        $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                        $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                        if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                            $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                        }
                        $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['partyCurrencyAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['partyExchangeRate']), $generalledger_arr[$i]['partyCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                        $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                        $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                        $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                        $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                        $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                        $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                        $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                        $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                        $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                        $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                        $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                        $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                        $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                        $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                        $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                        $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                        $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                        $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                        $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                        $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                        $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                        $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
                    }

                    if (!empty($generalledger_arr)) {
                        $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);
                    }

                }else{
                    for ($a = 0; $a < count($invoice_detail); $a++) {
                        if ($invoice_detail[$a]['type'] == 'Item') {
                            $item = fetch_item_data($invoice_detail[$a]['itemAutoID']);
                            if ($item['mainCategory'] == 'Inventory' or $item['mainCategory'] == 'Non Inventory') {
                                $itemAutoID = $invoice_detail[$a]['itemAutoID'];
                                $qty = $invoice_detail[$a]['requestedQty'] / $invoice_detail[$a]['conversionRateUOM'];
                                $wareHouseAutoID = $invoice_detail[$a]['wareHouseAutoID'];

                                $item_arr[$a]['itemAutoID'] = $invoice_detail[$a]['itemAutoID'];
                                $item_arr[$a]['currentStock'] = ($item['currentStock'] - $qty);
                                $item_arr[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) - ($item['companyLocalWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                                $item_arr[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) - ($item['companyReportingWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);

                            }
                        }
                    }

                    $this->load->model('Double_entry_model');
                    $double_entry = $this->Double_entry_model->fetch_double_entry_mfq_customer_invoice_data($system_id, 'CINV');
                    //echo '<pre>';print_r($double_entry['gl_detail']); echo '</pre>';
                    for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                        $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['invoiceAutoID'];
                        $generalledger_arr[$i]['documentCode'] = $double_entry['master_data']['documentID'];
                        $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['invoiceCode'];
                        $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['invoiceDate'];
                        $generalledger_arr[$i]['documentType'] = '';
                        $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['invoiceDate'];
                        $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['invoiceDate']));
                        $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['invoiceNarration'];
                        $generalledger_arr[$i]['chequeNumber'] = '';
                        $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                        $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                        $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                        $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                        $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                        $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                        $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                        $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                        $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                        $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['partyContractID'] = '';
                        $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                        $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                        $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                        $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                        $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                        $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                        $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                        $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['taxMasterAutoID'] = $double_entry['gl_detail'][$i]['taxMasterAutoID'];
                        $generalledger_arr[$i]['partyVatIdNo'] = $double_entry['gl_detail'][$i]['partyVatIdNo'];
                        $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                        $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                        $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                        $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                        $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                        $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                        $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                        $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                        $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                        if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                            $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                        }
                        $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['partyCurrencyAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['partyExchangeRate']), $generalledger_arr[$i]['partyCurrencyDecimalPlaces']);
                        $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                        $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                        $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                        $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                        $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                        $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                        $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                        $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                        $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                        $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                        $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                        $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                        $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                        $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                        $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                        $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                        $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                        $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                        $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                        $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                        $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                        $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                        $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
                    }

                    if (!empty($generalledger_arr)) {
                        $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);
                    }

                }

                $this->db->select_sum('transactionAmount');
                $this->db->where('invoiceAutoID', $system_id);
                $total = $this->db->get('srp_erp_customerinvoicedetails')->row('transactionAmount');

                $data['approvedYN'] = $status;
                $data['approvedbyEmpID'] = $this->common_data['current_userID'];
                $data['approvedbyEmpName'] = $this->common_data['current_user'];
                $data['approvedDate'] = $this->common_data['current_date'];

                $this->db->where('invoiceAutoID', $system_id);
                $this->db->update('srp_erp_customerinvoicemaster', $data);
                //$this->session->set_flashdata('s', 'Invoice Approval Successfully.');

                if($master["invoiceType"] == "Insurance") {
                    $sumsup = "SELECT (sum(transactionAmount)-sum(marginAmount)) as transactionAmount,
srp_erp_customerinvoicedetails.supplierAutoID as supplierAutoID,
srp_erp_customerinvoicedetails.segmentID as segmentID,
srp_erp_customerinvoicedetails.segmentCode as segmentCode,
srp_erp_suppliermaster.supplierName as supplierName,
srp_erp_suppliermaster.supplierSystemCode as supplierSystemCode,
srp_erp_suppliermaster.supplierAddress1 as supplierAddress,
srp_erp_suppliermaster.supplierTelephone as supplierTelephone,
srp_erp_suppliermaster.supplierFax as supplierFax,
srp_erp_suppliermaster.liabilityAutoID as liabilityAutoID,
srp_erp_suppliermaster.liabilitySystemGLCode as liabilitySystemGLCode,
srp_erp_suppliermaster.liabilityGLAccount as liabilityGLAccount,
srp_erp_suppliermaster.liabilityDescription as liabilityDescription,
srp_erp_suppliermaster.liabilityType as liabilityType,
srp_erp_suppliermaster.supplierCurrencyID as supplierCurrencyID,
srp_erp_suppliermaster.supplierCurrency as supplierCurrency,
srp_erp_suppliermaster.supplierCurrencyDecimalPlaces as supplierCurrencyDecimalPlaces
FROM
	`srp_erp_customerinvoicedetails`
Left JOIN srp_erp_suppliermaster ON srp_erp_suppliermaster.supplierAutoID = srp_erp_customerinvoicedetails.supplierAutoID
WHERE
	`invoiceAutoID` = $system_id
	GROUP BY
		supplierAutoID";
                    $sumsupdetail = $this->db->query($sumsup)->result_array();
                    $this->load->library('sequence');
                    $invdate=explode("-",$master['invoiceDate']);

                    foreach($sumsupdetail as $val){
                        $datasup['documentID'] = 'BSI';
                        $datasup['invoiceType'] = 'Standard';
                        $datasup['companyFinanceYearID'] = $master['companyFinanceYearID'];
                        $datasup['companyFinanceYear'] = $master['companyFinanceYear'];
                        $datasup['warehouseAutoID'] = $master['wareHouseAutoID'];
                        $datasup['isSytemGenerated'] = 1;
                        $datasup['documentOrigin'] = 'CINV';
                        $datasup['documentOriginAutoID'] = $system_id;
                        $datasup['FYBegin'] = $master['FYBegin'];
                        $datasup['FYEnd'] = $master['FYEnd'];
                        $datasup['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                        $datasup['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                        $datasup['companyFinancePeriodID'] = $master['companyFinancePeriodID'];
                        $datasup['bookingInvCode'] = $this->sequence->sequence_generator_fin('BSI',$master['companyFinanceYearID'],$invdate[0],$invdate[1]);
                        $datasup['bookingDate'] = $master['invoiceDate'];
                        $datasup['invoiceDate'] = $master['invoiceDate'];
                        $datasup['invoiceDueDate'] = $master['invoiceDueDate'];
                        $datasup['comments'] = 'From custome invoice '.$master['invoiceCode'];
                        $datasup['RefNo'] = $master['invoiceCode'];
                        $datasup['supplierID'] = $val['supplierAutoID'];
                        $datasup['supplierCode'] = $val['supplierSystemCode'];
                        $datasup['supplierName'] = $val['supplierName'];
                        $datasup['supplierAddress'] = $val['supplierAddress'];
                        $datasup['supplierTelephone'] = $val['supplierTelephone'];
                        $datasup['supplierFax'] = $val['supplierFax'];
                        $datasup['supplierliabilityAutoID'] = $val['liabilityAutoID'];
                        $datasup['supplierliabilitySystemGLCode'] = $val['liabilitySystemGLCode'];
                        $datasup['supplierliabilityGLAccount'] = $val['liabilityGLAccount'];
                        $datasup['supplierliabilityDescription'] = $val['liabilityDescription'];
                        $datasup['supplierliabilityType'] = $val['liabilityType'];
                        $datasup['supplierInvoiceDate'] = $master['invoiceDate'];
                        $datasup['transactionCurrencyID'] = $master['transactionCurrencyID'];
                        $datasup['transactionCurrency'] = $master['transactionCurrency'];
                        $datasup['transactionExchangeRate'] = $master['transactionExchangeRate'];
                        $datasup['transactionAmount'] = $val['transactionAmount'];
                        $datasup['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];
                        $datasup['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                        $datasup['companyLocalCurrency'] = $master['companyLocalCurrency'];
                        $datasup['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                        $datasup['companyLocalAmount'] = $val['transactionAmount']/$master['companyLocalExchangeRate'];
                        $datasup['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                        $datasup['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                        $datasup['companyReportingCurrency'] = $master['companyReportingCurrency'];
                        $datasup['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                        $datasup['companyReportingAmount'] = $val['transactionAmount']/$master['companyReportingExchangeRate'];
                        $datasup['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                        $datasup['supplierCurrencyID'] = $val['supplierCurrencyID'];
                        $datasup['supplierCurrency'] = $val['supplierCurrency'];
                        $datasup['segmentID'] = $val['segmentID'];
                        $datasup['segmentCode'] = $val['segmentCode'];
                        $datasup['companyID'] = current_companyID();
                        $datasup['companyCode'] = current_companyCode();
                        $supplier_currency = currency_conversionID($master['transactionCurrencyID'], $val['supplierCurrencyID']);
                        $datasup['supplierCurrencyExchangeRate'] = $supplier_currency['conversion'];
                        $datasup['supplierCurrencyAmount'] = $val['transactionAmount']/$supplier_currency['conversion'];
                        $datasup['supplierCurrencyDecimalPlaces'] = $val['supplierCurrencyDecimalPlaces'];
                        $datasup['confirmedYN'] = 1;
                        $datasup['confirmedByEmpID'] = current_userID();
                        $datasup['confirmedByName'] = current_user();
                        $datasup['confirmedDate'] = $this->common_data['current_date'];
                        $datasup['createdUserGroup'] = $this->common_data['user_group'];
                        $datasup['createdPCID'] = $this->common_data['current_pc'];
                        $datasup['createdUserID'] = $this->common_data['current_userID'];
                        $datasup['createdDateTime'] = $this->common_data['current_date'];
                        $datasup['createdUserName'] = $this->common_data['current_user'];

                        $supresult=$this->db->insert('srp_erp_paysupplierinvoicemaster', $datasup);
                        $last_idsup = $this->db->insert_id();
                        if($supresult){
                            $supid=$val['supplierAutoID'];
                            $supd = "SELECT * FROM `srp_erp_customerinvoicedetails` WHERE `invoiceAutoID` = $system_id AND `supplierAutoID` = $supid";
                            $supdetail = $this->db->query($supd)->result_array();

                            foreach($supdetail as $detl){
                                $datasupd['InvoiceAutoID'] = $last_idsup;
                                $datasupd['segmentID'] = $detl['segmentID'];
                                $datasupd['segmentCode'] = $detl['segmentCode'];
                                $datasupd['description'] = $detl['description'];
                                $datasupd['GLCode'] = "-";
                                $datasupd['transactionAmount'] = round($detl['transactionAmount']-$detl['marginAmount'],$master['transactionCurrencyDecimalPlaces']);
                                $datasupd['transactionExchangeRate'] = $master['transactionExchangeRate'];
                                $datasupd['companyLocalAmount'] = round($datasupd['transactionAmount']/$master['companyLocalExchangeRate'], $master['companyLocalCurrencyDecimalPlaces']);
                                $datasupd['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                                $datasupd['companyReportingAmount'] = round($datasupd['transactionAmount']/$master['companyReportingExchangeRate'], $master['companyReportingCurrencyDecimalPlaces']);
                                $datasupd['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                                $datasupd['supplierAmount'] = round($datasupd['transactionAmount']/$datasup['supplierCurrencyExchangeRate'], $datasup['supplierCurrencyDecimalPlaces']);
                                $datasupd['supplierCurrencyExchangeRate'] = $datasup['supplierCurrencyExchangeRate'];
                                $datasupd['companyCode'] = $this->common_data['company_data']['company_code'];
                                $datasupd['companyID'] = $this->common_data['company_data']['company_id'];
                                $datasupd['createdUserGroup'] = $this->common_data['user_group'];
                                $datasupd['createdPCID'] = $this->common_data['current_pc'];
                                $datasupd['createdUserID'] = $this->common_data['current_userID'];
                                $datasupd['createdUserName'] = $this->common_data['current_user'];
                                $datasupd['createdDateTime'] = $this->common_data['current_date'];
                                $this->db->insert('srp_erp_paysupplierinvoicedetail', $datasupd);
                            }
                            $this->load->library('Approvals');
                            $approvals_status_sup = $this->approvals->auto_approve($last_idsup, 'srp_erp_paysupplierinvoicemaster','InvoiceAutoID', 'BSI',$master['invoiceDate'],$master['invoiceDate']);
                            if($approvals_status_sup==1){
                                $this->load->model('Payable_modal');
                                $this->Payable_modal->save_supplier_invoice_approval(0, $last_idsup, 1, 'Auto Approved');
                            }
                        }
                    }
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Invoice Approval Failed.', 1);
            } else {
                $this->db->trans_commit();
                return array('s', 'Invoice Approval Successfull.', 1);
            }
        } else {
            return array('e', 'Item quantities are insufficient.', $items_arr);
        }
    }

    function delete_customerInvoice_attachement()
    {
        $this->db->delete('srp_erp_documentattachments', array('attachmentID' => trim($this->input->post('attachmentID'))));
        return true;
    }

    function delete_invoice_master()
    {
        /* $this->db->delete('srp_erp_customerinvoicemaster', array('invoiceAutoID' => trim($this->input->post('invoiceAutoID'))));
         $this->db->delete('srp_erp_customerinvoicedetails', array('invoiceAutoID' => trim($this->input->post('invoiceAutoID'))));
         $this->db->delete('srp_erp_customerinvoicetaxdetails', array('invoiceAutoID' => trim($this->input->post('invoiceAutoID'))));*/
        $this->db->select('*');
        $this->db->from('srp_erp_customerinvoicedetails');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $datas = $this->db->get()->row_array();

        $this->db->select('invoiceCode');
        $this->db->from('srp_erp_customerinvoicemaster');
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $master = $this->db->get()->row_array();

        if ($datas) {
            $this->session->set_flashdata('e', 'please delete all detail records before delete this document.');
            return true;
        } else {
            $lenth=strlen($master['invoiceCode']);
            if($lenth > 1){
                $data = array(
                    'isDeleted' => 1,
                    'deletedEmpID' => current_userID(),
                    'deletedDate' => current_date(),
                );
                $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                $this->db->update('srp_erp_customerinvoicemaster', $data);
                return true;

            }else{
                $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
                $results = $this->db->delete('srp_erp_customerinvoicemaster');
                if ($results) {
                    $this->db->where('InvoiceAutoID', $this->input->post('InvoiceAutoID'));
                    $this->db->delete('srp_erp_customerinvoicedetails');
                    $this->session->set_flashdata('s', 'Deleted Successfully');
                    return true;
                }
            }

        }
    }

    function load_subItem_notSold_QueryGen($itemAutoID, $detailID, $warehouseID)
    {
        $query = "SELECT * FROM srp_erp_itemmaster_sub iSub 
                                    WHERE iSub.itemAutoID = '" . $itemAutoID . "' AND  iSub.wareHouseAutoID ='" . $warehouseID . "'
                                    AND ( (( ISNULL(iSub.isSold) OR iSub.isSold = '' OR iSub.isSold = 0 ) ) OR (iSub.soldDocumentDetailID='" . $detailID . "' ) ) ;";

        return $query;
    }

    function load_subItem_notSold($detailID, $documentID, $warehouseID)
    {
        $subItemArray = array();

        switch ($documentID) {
            case "CINV":
                $item = $this->db->query(" SELECT itemAutoID FROM srp_erp_customerinvoicedetails WHERE invoiceDetailsAutoID = '" . $detailID . "' ")->row_array();
                if (isset($item['itemAutoID']) && !empty($item['itemAutoID'])) {
                    $query = $this->load_subItem_notSold_QueryGen($item['itemAutoID'], $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();
                    $subItemArray = $result;
                }
                break;

            case "RV":
                $item = $this->db->query(" SELECT itemAutoID FROM srp_erp_customerreceiptdetail WHERE receiptVoucherDetailAutoID = '" . $detailID . "' ")->row_array();
                if (isset($item['itemAutoID']) && !empty($item['itemAutoID'])) {
                    $query = $this->load_subItem_notSold_QueryGen($item['itemAutoID'], $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();
                    $subItemArray = $result;
                }
                break;

            case "SR":
                $item = $this->db->query(" SELECT itemAutoID FROM srp_erp_stockreturndetails WHERE stockReturnDetailsID = '" . $detailID . "' ")->row_array();
                if (isset($item['itemAutoID']) && !empty($item['itemAutoID'])) {
                    $query = $this->load_subItem_notSold_QueryGen($item['itemAutoID'], $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();
                    $subItemArray = $result;
                }
                break;

            case "MI":
                $item = $this->db->query(" SELECT itemAutoID FROM srp_erp_itemissuedetails WHERE itemIssueDetailID = '" . $detailID . "' ")->row_array();
                if (isset($item['itemAutoID']) && !empty($item['itemAutoID'])) {
                    $query = $this->load_subItem_notSold_QueryGen($item['itemAutoID'], $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();
                    $subItemArray = $result;
                }

                break;

            case "ST":
                $item = $this->db->query(" SELECT itemAutoID FROM srp_erp_stocktransferdetails WHERE stockTransferDetailsID = '" . $detailID . "' ")->row_array();
                if (isset($item['itemAutoID']) && !empty($item['itemAutoID'])) {
                    $query = $this->load_subItem_notSold_QueryGen($item['itemAutoID'], $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();

                    $subItemArray = $result;
                }
                break;

            case "SA":
                $item = $this->db->query(" SELECT itemAutoID FROM srp_erp_stockadjustmentdetails WHERE stockAdjustmentDetailsAutoID = '" . $detailID . "' ")->row_array();
                if (isset($item['itemAutoID']) && !empty($item['itemAutoID'])) {
                    $query = $this->load_subItem_notSold_QueryGen($item['itemAutoID'], $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();

                    $subItemArray = $result;
                }
                break;

            case "DO":
                $itemAutoID = $this->db->query(" SELECT itemAutoID FROM srp_erp_deliveryorderdetails WHERE DODetailsAutoID = '{$detailID}' ")->row('itemAutoID');
                if (!empty($itemAutoID)) {
                    $query = $this->load_subItem_notSold_QueryGen($itemAutoID, $detailID, $warehouseID);
                    $result = $this->db->query($query)->result_array();
                    $subItemArray = $result;
                }
            break;

            default:
                echo $documentID . ' Error: Code not configured!<br/>';
                echo 'File: ' . __FILE__ . '<br/>';
                echo 'Line No: ' . __LINE__ . '<br><br>';
        }

        return $subItemArray;
    }

    function get_invoiceDetail($id)
    {
        $this->db->select('srp_erp_customerinvoicedetails.*,srp_erp_itemmaster.isSubitemExist');
        $this->db->from('srp_erp_customerinvoicedetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_customerinvoicedetails.itemAutoID', 'left');
        $this->db->where('invoiceDetailsAutoID', $id);
        $r = $this->db->get()->row_array();
        return $r;
    }

    function get_receiptVoucherDetail($id)
    {
        $this->db->select('srp_erp_customerreceiptdetail.*,srp_erp_itemmaster.isSubitemExist');
        $this->db->from('srp_erp_customerreceiptdetail');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_customerreceiptdetail.itemAutoID', 'left');
        $this->db->where('srp_erp_customerreceiptdetail.receiptVoucherDetailAutoID', $id);
        $r = $this->db->get()->row_array();
        return $r;
    }

    function get_stockReturnDetail($id)
    {
        $this->db->select('srp_erp_stockreturndetails.*,srp_erp_itemmaster.isSubitemExist');
        $this->db->from('srp_erp_stockreturndetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_stockreturndetails.itemAutoID', 'left');
        $this->db->where('srp_erp_stockreturndetails.stockReturnDetailsID', $id);
        $r = $this->db->get()->row_array();
        //echo $this->db->last_query();
        return $r;
    }

    function get_materialIssueDetail($id)
    {
        $this->db->select('srp_erp_itemissuedetails.*,srp_erp_itemmaster.isSubitemExist');
        $this->db->from('srp_erp_itemissuedetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_itemissuedetails.itemAutoID', 'left');
        $this->db->where('srp_erp_itemissuedetails.itemIssueDetailID', $id);
        $r = $this->db->get()->row_array();
        //echo $this->db->last_query();
        return $r;
    }

    function get_stockTransferDetail($id)
    {
        $this->db->select('srp_erp_stocktransferdetails.*,srp_erp_itemmaster.isSubitemExist');
        $this->db->from('srp_erp_stocktransferdetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_stocktransferdetails.itemAutoID', 'left');
        $this->db->where('srp_erp_stocktransferdetails.stockTransferDetailsID', $id);
        $r = $this->db->get()->row_array();
        //echo $this->db->last_query();
        return $r;
    }

    function get_stockAdjustmentDetail($id)
    {
        $this->db->select('srp_erp_stockadjustmentdetails.*,srp_erp_itemmaster.isSubitemExist');
        $this->db->from('srp_erp_stockadjustmentdetails');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_itemmaster.itemAutoID = srp_erp_stockadjustmentdetails.itemAutoID', 'left');
        $this->db->where('srp_erp_stockadjustmentdetails.stockAdjustmentDetailsAutoID', $id);
        $r = $this->db->get()->row_array();
        //echo $this->db->last_query();
        return $r;
    }

    function load_invoice_header_id($id)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(customerInvoiceDate,\'' . $convertFormat . '\') AS customerInvoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate');
        $this->db->where('invoiceAutoID', $id);
        return $this->db->get('srp_erp_customerinvoicemaster')->row_array();
    }

    function save_subItemList()
    {
        $subItems = $this->input->post('subItemCode[]');
        $soldDocumentID = $this->input->post('soldDocumentID');
        $soldDocumentAutoID = $this->input->post('soldDocumentAutoID');
        $soldDocumentDetailID = $this->input->post('soldDocumentDetailID');

        $currentUser = current_pc();
        $modifiedUserID = current_userID();
        $modifiedDatetime = format_date_mysql_datetime();
        if (!empty($subItems)) {
            $i = 0;
            foreach ($subItems as $subItem) {
                $data[$i]['subItemAutoID'] = $subItem;
                $data[$i]['soldDocumentID'] = $soldDocumentID;
                $data[$i]['isSold'] = 1;
                $data[$i]['soldDocumentAutoID'] = $soldDocumentAutoID;
                $data[$i]['soldDocumentDetailID'] = $soldDocumentDetailID;
                $data[$i]['modifiedPCID'] = $currentUser;
                $data[$i]['modifiedUserID'] = $modifiedUserID;
                $data[$i]['modifiedDatetime'] = $modifiedDatetime;
                $i++;
            }


            if (!empty($data)) {

                $dataTmp['isSold'] = null;
                $dataTmp['soldDocumentAutoID'] = null;
                $dataTmp['soldDocumentDetailID'] = null;
                $dataTmp['soldDocumentID'] = null;
                $dataTmp['modifiedPCID'] = $currentUser;
                $dataTmp['modifiedUserID'] = $modifiedUserID;
                $dataTmp['modifiedDatetime'] = $modifiedDatetime;

                $this->db->where('soldDocumentAutoID', $soldDocumentAutoID);
                $this->db->where('soldDocumentDetailID', $soldDocumentDetailID);
                $this->db->update('srp_erp_itemmaster_sub', $dataTmp);


                $this->db->update_batch('srp_erp_itemmaster_sub', $data, 'subItemAutoID');
            }
            return array('error' => 0, 'message' => 'Record/s updated successfully');

        } else {
            return array('error' => 1, 'message' => 'Please select sub items!');
        }

    }

    function re_open_invoice()
    {
        $data = array(
            'isDeleted' => 0,
        );
        $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $this->db->update('srp_erp_customerinvoicemaster', $data);
        $this->session->set_flashdata('s', 'Re Opened Successfully.');
        return true;
    }

    function customerinvoiceGLUpdate()
    {
        $gl = fetch_gl_account_desc($this->input->post('PLGLAutoID'));

        $BLGLAutoID = $this->input->post('BLGLAutoID');

        $data = array(
            'expenseGLAutoID' => $this->input->post('PLGLAutoID'),
            'expenseSystemGLCode' => $gl['systemAccountCode'],
            'expenseGLCode' => $gl['GLSecondaryCode'],
            'expenseGLDescription' => $gl['GLDescription'],
            'expenseGLType' => $gl['subCategory'],

        );
        if (isset($BLGLAutoID)) {
            $bl = fetch_gl_account_desc($this->input->post('BLGLAutoID'));
            $data = array_merge($data, array(
                'revenueGLAutoID' => $this->input->post('BLGLAutoID'),
                'revenueGLCode' => $bl['systemAccountCode'],
                'revenueSystemGLCode' => $bl['GLSecondaryCode'],
                'revenueGLDescription' => $bl['GLSecondaryCode']));
            /*'revenueGLType'=>'',*/


        }


        if ($this->input->post('applyAll') == 1) {
            $this->db->where('invoiceAutoID', trim($this->input->post('masterID')));
        } else {
            $this->db->where('invoiceDetailsAutoID', trim($this->input->post('detailID')));
        }
        $this->db->update('srp_erp_customerinvoicedetails ', $data);
        return array('s', 'GL Account Successfully Changed');
    }


    function fetch_customer_invoice_all_detail_edit()
    {
        $this->db->select('srp_erp_customerinvoicedetails.*,srp_erp_customerinvoicemaster.invoiceType,srp_erp_itemmaster.currentStock,srp_erp_itemmaster.mainCategory');
        $this->db->where('srp_erp_customerinvoicedetails.invoiceAutoID', trim($this->input->post('invoiceAutoID')));
        $this->db->where('srp_erp_customerinvoicedetails.type', 'Item');
        $this->db->join('srp_erp_customerinvoicemaster', 'srp_erp_customerinvoicedetails.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID', 'left');
        $this->db->join('srp_erp_itemmaster', 'srp_erp_customerinvoicedetails.itemAutoID = srp_erp_itemmaster.itemAutoID', 'left');
        $this->db->from('srp_erp_customerinvoicedetails');
        return $this->db->get()->result_array();
    }

    function updateCustomerInvoice_edit_all_Item()
    {
        $projectExist = project_is_exist();
        $invoiceDetailsAutoID = $this->input->post('invoiceDetailsAutoID');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $itemAutoIDs = $this->input->post('itemAutoID');
        $item_text = $this->input->post('item_text');
        $wareHouse = $this->input->post('wareHouse');
        $uom = $this->input->post('uom');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $projectID = $this->input->post('projectID');
        $quantityRequested = $this->input->post('quantityRequested');
        $item_taxPercentage = $this->input->post('item_taxPercentage');
        $comment = $this->input->post('comment');
        $remarks = $this->input->post('remarks');
        $wareHouseAutoID = $this->input->post('wareHouseAutoID');
        $estimatedAmount = $this->input->post('estimatedAmount');
        $discount = $this->input->post('discount');
        $discount_amount = $this->input->post('discount_amount');

        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate ,transactionCurrency,segmentID,segmentCode,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        foreach ($itemAutoIDs as $key => $itemAutoID) {
            $tax_master = array();
            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID);
            $serviceitm= $this->db->get()->row_array();

            if (!trim($invoiceDetailsAutoID[$key])) {
                $this->db->select('invoiceAutoID,,itemDescription,itemSystemCode');
                $this->db->from('srp_erp_customerinvoicedetails');
                $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                $this->db->where('itemAutoID', $itemAutoID);
                $this->db->where('wareHouseAutoID', $wareHouseAutoID[$key]);
                $order_detail = $this->db->get()->row_array();
                if($serviceitm['mainCategory']=="Inventory") {
                    if (!empty($order_detail)) {
                        return array('w', 'Invoice Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
                    }
                }
            }else{
                $this->db->select('invoiceAutoID,,itemDescription,itemSystemCode');
                $this->db->from('srp_erp_customerinvoicedetails');
                $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
                $this->db->where('itemAutoID', $itemAutoID);
                $this->db->where('wareHouseAutoID', $wareHouseAutoID[$key]);
                $this->db->where('invoiceDetailsAutoID !=', $invoiceDetailsAutoID[$key]);
                $order_detail = $this->db->get()->row_array();
                if($serviceitm['mainCategory']=="Inventory") {
                    if (!empty($order_detail)) {
                        return array('w', 'Invoice Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
                    }
                }
            }

            if (isset($item_text[$key])) {
                $this->db->select('*');
                $this->db->where('taxMasterAutoID', $item_text[$key]);
                $tax_master = $this->db->get('srp_erp_taxmaster')->row_array();

                $this->db->select('*');
                $this->db->where('supplierSystemCode', $tax_master['supplierSystemCode']);
                $Supplier_master = $this->db->get('srp_erp_suppliermaster')->row_array();

                $this->db->select('srp_erp_taxmaster.*,srp_erp_chartofaccounts.GLAutoID as liabilityAutoID,srp_erp_chartofaccounts.systemAccountCode as liabilitySystemGLCode,srp_erp_chartofaccounts.GLSecondaryCode as liabilityGLAccount,srp_erp_chartofaccounts.GLDescription as liabilityDescription,srp_erp_chartofaccounts.CategoryTypeDescription as liabilityType,srp_erp_currencymaster.CurrencyCode,srp_erp_currencymaster.DecimalPlaces');
                $this->db->where('taxMasterAutoID', $item_text[$key]);
                $this->db->from('srp_erp_taxmaster');
                $this->db->join('srp_erp_chartofaccounts', 'srp_erp_chartofaccounts.GLAutoID = srp_erp_taxmaster.supplierGLAutoID');
                $this->db->join('srp_erp_currencymaster', 'srp_erp_currencymaster.currencyID = srp_erp_taxmaster.supplierCurrencyID');
                $tax_master = $this->db->get()->row_array();
            }

            $wareHouse_location = explode('|', $wareHouse[$key]);
            $item_arr = fetch_item_data($itemAutoID);
            $uomEx = explode('|', $uom[$key]);

            $this->db->select('mainCategory');
            $this->db->from('srp_erp_itemmaster');
            $this->db->where('itemAutoID', $itemAutoID);
            $serviceitm= $this->db->get()->row_array();

            $data['invoiceAutoID'] = trim($invoiceAutoID);
            $data['itemAutoID'] = $itemAutoID;
            $data['itemSystemCode'] = $item_arr['itemSystemCode'];
            if ($projectExist == 1) {
                $projectCurrency = project_currency($projectID[$key]);
                $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
                $data['projectID'] = $projectID[$key];
                $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
            }
            $data['itemDescription'] = $item_arr['itemDescription'];
            $data['unitOfMeasure'] = trim($uomEx[0]);
            $data['unitOfMeasureID'] = $UnitOfMeasureID[$key];
            $data['defaultUOM'] = $item_arr['defaultUnitOfMeasure'];
            $data['defaultUOMID'] = $item_arr['defaultUnitOfMeasureID'];
            $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
            $data['requestedQty'] = $quantityRequested[$key];
            $data['discountPercentage'] = $discount[$key];
            $data['discountAmount'] = $discount_amount[$key];
            $amountafterdiscount = $estimatedAmount[$key] - $data['discountAmount'];
            $data['unittransactionAmount'] = round($estimatedAmount[$key], $master['transactionCurrencyDecimalPlaces']);
            $data['taxPercentage'] = $item_taxPercentage[$key];
            $taxAmount = ($data['taxPercentage'] / 100) * $amountafterdiscount;
            $data['taxAmount'] = round($taxAmount, $master['transactionCurrencyDecimalPlaces']);
            $totalAfterTax = $data['taxAmount'] * $data['requestedQty'];
            $data['totalAfterTax'] = round($totalAfterTax, $master['transactionCurrencyDecimalPlaces']);
            $transactionAmount = ($data['taxAmount'] + $amountafterdiscount) * $quantityRequested[$key];
            $data['transactionAmount'] = round($transactionAmount, $master['transactionCurrencyDecimalPlaces']);
            $companyLocalAmount = $data['transactionAmount'] / $master['companyLocalExchangeRate'];
            $data['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $companyReportingAmount = $data['transactionAmount'] / $master['companyReportingExchangeRate'];
            $data['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $customerAmount = $data['transactionAmount'] / $master['customerCurrencyExchangeRate'];
            $data['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
            $data['comment'] = $comment[$key];
            $data['remarks'] = $remarks[$key];
            $data['type'] = 'Item';
            $item_data = fetch_item_data($data['itemAutoID']);
            if($serviceitm['mainCategory']=="Service") {
                $data['wareHouseAutoID'] = null;
                $data['wareHouseCode'] = null;
                $data['wareHouseLocation'] = null;
                $data['wareHouseDescription'] = null;
            }else{
                $data['wareHouseAutoID'] = $wareHouseAutoID[$key];
                $data['wareHouseCode'] = trim($wareHouse_location[0]);
                $data['wareHouseLocation'] = trim($wareHouse_location[1]);
                $data['wareHouseDescription'] = trim($wareHouse_location[2]);
            }
            $data['segmentID'] = $master['segmentID'];
            $data['segmentCode'] = $master['segmentCode'];
            $data['expenseGLAutoID'] = $item_data['costGLAutoID'];
            $data['expenseGLCode'] = $item_data['costGLCode'];
            $data['expenseSystemGLCode'] = $item_data['costSystemGLCode'];
            $data['expenseGLDescription'] = $item_data['costDescription'];
            $data['expenseGLType'] = $item_data['costType'];
            $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
            $data['revenueGLCode'] = $item_data['revanueGLCode'];
            $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
            $data['revenueGLDescription'] = $item_data['revanueDescription'];
            $data['revenueGLType'] = $item_data['revanueType'];
            $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
            $data['assetGLCode'] = $item_data['assteGLCode'];
            $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
            $data['assetGLDescription'] = $item_data['assteDescription'];
            $data['assetGLType'] = $item_data['assteType'];
            $data['companyLocalWacAmount'] = $item_data['companyLocalWacAmount'];
            $data['itemCategory'] = $item_data['mainCategory'];

            if (!empty($tax_master)) {
                $data['taxMasterAutoID'] = $tax_master['taxMasterAutoID'];
                $data['taxDescription'] = $tax_master['taxDescription'];
                $data['taxShortCode'] = $tax_master['taxShortCode'];
                $data['taxSupplierAutoID'] = $tax_master['supplierAutoID'];
                $data['taxSupplierSystemCode'] = $tax_master['supplierSystemCode'];
                $data['taxSupplierName'] = $tax_master['supplierName'];
                $data['taxSupplierCurrencyID'] = $tax_master['supplierCurrencyID'];
                $data['taxSupplierCurrency'] = $tax_master['CurrencyCode'];
                $data['taxSupplierCurrencyDecimalPlaces'] = $tax_master['DecimalPlaces'];
                $data['taxSupplierliabilityAutoID'] = $tax_master['liabilityAutoID'];
                $data['taxSupplierliabilitySystemGLCode'] = $tax_master['liabilitySystemGLCode'];
                $data['taxSupplierliabilityGLAccount'] = $tax_master['liabilityGLAccount'];
                $data['taxSupplierliabilityDescription'] = $tax_master['liabilityDescription'];
                $data['taxSupplierliabilityType'] = $tax_master['liabilityType'];
                $supplierCurrency = currency_conversion($master['transactionCurrency'], $data['taxSupplierCurrency']);
                $data['taxSupplierCurrencyExchangeRate'] = $supplierCurrency['conversion'];
                $data['taxSupplierCurrencyDecimalPlaces'] = $supplierCurrency['DecimalPlaces'];
                $data['taxSupplierCurrencyAmount'] = ($data['transactionAmount'] / $data['taxSupplierCurrencyExchangeRate']);
            } else {
                $data['taxSupplierCurrencyExchangeRate'] = 1;
                $data['taxSupplierCurrencyDecimalPlaces'] = 2;
                $data['taxSupplierCurrencyAmount'] = 0;
            }

            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];


            if (trim($invoiceDetailsAutoID[$key])) {
                $this->db->where('invoiceDetailsAutoID', trim($invoiceDetailsAutoID[$key]));
                $this->db->update('srp_erp_customerinvoicedetails', $data);
                $this->db->trans_complete();
            } else {
                $data['companyID'] = $this->common_data['company_data']['company_id'];
                $data['companyCode'] = $this->common_data['company_data']['company_code'];
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_customerinvoicedetails', $data);

                if ($item_data['mainCategory'] == 'Inventory' or $item_data['mainCategory'] == 'Non Inventory') {
                    $this->db->select('itemAutoID');
                    $this->db->where('itemAutoID', $itemAutoID);
                    $this->db->where('wareHouseAutoID', $data['wareHouseAutoID']);
                    $this->db->where('companyID', $this->common_data['company_data']['company_id']);
                    $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

                    if (empty($warehouseitems)) {
                        $data_arr = array(
                            'wareHouseAutoID' => $data['wareHouseAutoID'],
                            'wareHouseLocation' => $data['wareHouseLocation'],
                            'wareHouseDescription' => $data['wareHouseDescription'],
                            'itemAutoID' => $data['itemAutoID'],
                            'itemSystemCode' => $data['itemSystemCode'],
                            'barCodeNo' => $item_data['barcode'],
                            'salesPrice' => $item_data['companyLocalSellingPrice'],
                            'ActiveYN' => $item_data['isActive'],
                            'itemDescription' => $data['itemDescription'],
                            'unitOfMeasureID' => $data['defaultUOMID'],
                            'unitOfMeasure' => $data['defaultUOM'],
                            'currentStock' => 0,
                            'companyID' => $this->common_data['company_data']['company_id'],
                            'companyCode' => $this->common_data['company_data']['company_code'],
                        );
                        $this->db->insert('srp_erp_warehouseitems', $data_arr);
                    }
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Invoice Detail : Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Invoice Detail : Saved Successfully.');
        }
    }
    function fetch_signaturelevel()
    {
        $this->db->select('approvalSignatureLevel');
        $this->db->where('companyID', current_companyID());
        $this->db->where('documentID', 'CINV');
        $this->db->from('srp_erp_documentcodemaster');
        return $this->db->get()->row_array();
    }
    function invoiceloademail()
    {
        $invoiceautoid = $this->input->post('invoiceAutoID');
        $this->db->select('srp_erp_customerinvoicemaster.*,srp_erp_customermaster.customerEmail as customerEmail');
        $this->db->where('invoiceAutoID', $invoiceautoid);
        $this->db->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
        $this->db->from('srp_erp_customerinvoicemaster ');
        return $this->db->get()->row_array();
    }
    function send_invoice_email()

        {
            $invoiceautoid = trim($this->input->post('invoiceid'));
            $invoiceemail = trim($this->input->post('email'));
            $this->db->select('srp_erp_customerinvoicemaster.*,srp_erp_customermaster.customerEmail as customerEmail,srp_erp_customermaster.customerName as customerName');
            $this->db->where('invoiceAutoID', $invoiceautoid);
            $this->db->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_customerinvoicemaster.customerID', 'left');
            $this->db->from('srp_erp_customerinvoicemaster ');
            $results = $this->db->get()->row_array();

            if (!empty($results)) {
                if ($results['customerEmail'] == '') {
                    $data_master['customerEmail'] = $invoiceemail;
                    $this->db->where('customerAutoID', $results['customerID']);
                    $this->db->update('srp_erp_customermaster', $data_master);
                }
            }
            $this->db->select('customerEmail,customerName');
            $this->db->where('customerAutoID', $results['customerID']);
            $this->db->from('srp_erp_customermaster ');
            $customerMaster = $this->db->get()->row_array();

            $this->load->library('NumberToWords');
            $data['extra'] = $this->Invoice_model->fetch_invoice_template_data($invoiceautoid);
            $data['approval'] = $this->input->post('approval');
            $data['printHeaderFooterYN'] = 1;
            $data['signature'] = $this->Invoice_model->fetch_signaturelevel();
            $data['logo']=mPDFImage;
            if($this->input->post('html')){
                $data['logo']=htmlImage;
            }
            $data['printHeaderFooterYN']=1;
            $data['emailView'] = 1; // to get the html view otherwise it will set two headers
            $html = $this->load->view('system/invoices/erp_invoice_print', $data, true);



            $this->load->library('pdf');
            $path = UPLOAD_PATH.base_url().'/uploads/invoice/'. $invoiceautoid .$results["documentID"] . current_userID() . ".pdf";
            $this->pdf->save_pdf($html, 'A4', 1, $path);


            if (!empty($customerMaster)) {
                if ($customerMaster['customerEmail'] != '') {
                    $param = array();
                    $param["empName"] = 'Sir/Madam';
                    $param["body"] = 'we are pleased to submit our invoice as follows.<br/>
                                          <table border="0px">
                                          </table>';
                    $mailData = [
                        'approvalEmpID' => '',
                        'documentCode' => '',
                        'toEmail' => $invoiceemail ,
                        'subject' => ' Customer Invoice for '.$customerMaster['customerName'],
                        'param' => $param
                    ];
                    send_approvalEmail($mailData, 1,$path);
                    return array('s', 'Email Send Successfully.',$invoiceemail,$invoiceautoid);
                } else {
                    return array('e', 'Please enter an Email ID.');
                }
            }
        }

    function fetch_invoice_template_data_temp($invoiceAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $companyID=current_companyID();
        $this->db->select('*,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDate,\'' . $convertFormat . '\') AS invoiceDate ,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,DATE_FORMAT(srp_erp_customerinvoicemaster.customerInvoiceDate,\'' . $convertFormat . '\') AS customerInvoiceDate,DATE_FORMAT(srp_erp_customerinvoicemaster.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,CASE WHEN confirmedYN = 2 || confirmedYN = 3   THEN " - " WHEN confirmedYN = 1 THEN CONCAT_WS(\' on \',IF(LENGTH(confirmedbyName),confirmedbyName,\'-\'),IF(LENGTH(DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' )),DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' ),NULL)) ELSE "-" END confirmedYNn');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->from('srp_erp_customerinvoicemaster');
        $data['master'] = $this->db->get()->row_array();
        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);

        $this->db->select('customerName,customerAddress1,customerTelephone,customerSystemCode,customerFax');
        $this->db->where('customerAutoID', $data['master']['customerID']);
        $this->db->from('srp_erp_customermaster');
        $data['customer'] = $this->db->get()->row_array();


        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'Item');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['item_detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'GL');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['gl_detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['tax'] = $this->db->get('srp_erp_customerinvoicetaxdetails')->result_array();

        $convertFormat = convert_date_format_sql();
        $this->db->select('cus.*, DOMasterID,DATE_FORMAT(DODate,\''.  $convertFormat .'\') AS DODate,DOCode,referenceNo,del_ord.transactionAmount AS do_tr_amount,due_amount,balance_amount');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'DO');
        $this->db->from('srp_erp_customerinvoicedetails cus');
        $this->db->join('srp_erp_deliveryorder del_ord', 'del_ord.DOAutoID = cus.DOMasterID');
        $data['delivery_order'] = $this->db->get()->result_array();

        $data['taxledger'] = $this->db->query("SELECT
	tax.taxDescription,tax.taxShortCode,srp_erp_taxledger.taxMasterID,SUM(srp_erp_taxledger.amount)as amount
FROM
	`srp_erp_taxledger`
LEFT JOIN srp_erp_taxmaster tax on srp_erp_taxledger.taxMasterID=tax.taxMasterAutoID
WHERE
	documentMasterAutoID = $invoiceAutoID
AND	documentID = 'CINV'
AND srp_erp_taxledger.companyID = $companyID

GROUP BY srp_erp_taxledger.taxMasterID ")->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['extracharge'] = $this->db->get('srp_erp_customerinvoiceextrachargedetails')->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['discount'] = $this->db->get('srp_erp_customerinvoicediscountdetails')->result_array();

        return $data;
    }

    function load_default_note(){
        $docid = trim($this->input->post('docid'));
        $this->db->select('description');
        $this->db->where('documentID', $docid);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->where('isDefault', 1);
        $data = $this->db->get('srp_erp_termsandconditions')->row_array();
        return $data;
    }

    function open_all_notes(){
        $docid = trim($this->input->post('docid'));
        $this->db->select('autoID,description');
        $this->db->where('documentID', $docid);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $data = $this->db->get('srp_erp_termsandconditions')->result_array();
        return $data;
    }

    function load_notes(){
        $autoID = trim($this->input->post('allnotedesc'));
        $this->db->select('description');
        $this->db->where('autoID', $autoID);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $data = $this->db->get('srp_erp_termsandconditions')->row_array();
        return $data;
    }
    function saveinsurancetype()
    {
        $companyid = current_companyID();
        $insurancetype = $this->input->post('insurancetype');
        $insurancetypeID = $this->input->post('insurancetypeId');
        $GLAutoID = $this->input->post('gl_code');
        $marginPercentage = $this->input->post('marginPercentage');

        $data['insuranceType'] = $insurancetype;
        $data['GLAutoID'] = $GLAutoID;
        $data['marginPercentage'] = $marginPercentage;


        if(!empty($insurancetypeID))
        {
            $q = "SELECT insuranceType FROM srp_erp_invoiceinsurancetypes WHERE insuranceType = '{$insurancetype }' AND companyID = $companyid AND insuranceTypeID != $insurancetypeID" ;
            $result = $this->db->query($q)->row_array();
            if ($result) {
                return array('e', 'Insurance Type Already Exist');
            }else
            {
                $data['modifiedPCID'] = $this->common_data['current_pc'];
                $data['modifiedUserID'] = $this->common_data['current_userID'];
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['modifiedUserName'] = $this->common_data['current_user'];
                $this->db->where('insuranceTypeID', $insurancetypeID);
                $this->db->update('srp_erp_invoiceinsurancetypes', $data);
            }


        }else
        {
            $q = "SELECT insuranceType FROM srp_erp_invoiceinsurancetypes WHERE insuranceType =  '{$insurancetype }' AND companyID = $companyid";
            $result = $this->db->query($q)->row_array();
            if ($result) {
                return array('e', 'Insurance Type Already Exist');
            }else
            {
                $data['companyID'] = $companyid;
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_invoiceinsurancetypes', $data);
                $last_id = $this->db->insert_id();
            }

        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Insurance Type Added Failed.');
        } else {
            $this->db->trans_commit();
            return array('s', 'Insurance Type Added Successfully.');
        }
    }
    function getinsurancetype()
    {
        $insurancetypeid = $this->input->post('insuranceTypeID');
        $comapnyid = current_companyID();
        $data = $this->db->query("select * from srp_erp_invoiceinsurancetypes where companyID = $comapnyid And insuranceTypeID = $insurancetypeid")->row_array();
        return $data;
    }
    function deleteinsurancetype()
    {
        $comapnyid = current_companyID();
            $insurancetypeid = $this->input->post('insuranceTypeID');
        $insuranceexist = $this->db->query("select invoiceAutoID from srp_erp_customerinvoicemaster where companyID = $comapnyid AND insuranceTypeID = $insurancetypeid")->row_array();
        if(!empty($insuranceexist))
        {
            return array('e', 'Insurance Type Already selected for a invoice.');
        }else
        {
            $this->db->delete('srp_erp_invoiceinsurancetypes', array('insuranceTypeID' => $insurancetypeid));
            return array('s', 'Insurance Type Deleted Successfully.');
        }


    }
    function save_invoice_header_insurance()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $invDueDate = $this->input->post('invoiceDueDate');
        $invoiceDueDate = input_format_date($invDueDate, $date_format_policy);
        $invDate = $this->input->post('invoiceDate');
        $invoiceDate = input_format_date($invDate, $date_format_policy);
        $customerDate = $this->input->post('customerInvoiceDate');
        $customerInvoiceDate = input_format_date($customerDate, $date_format_policy);
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        //$period = explode('|', trim($this->input->post('financeyear_period')));
        if($financeyearperiodYN==1) {
            $financeyr = explode(' - ', trim($this->input->post('companyFinanceYear')));

            $FYBegin = input_format_date($financeyr[0], $date_format_policy);
            $FYEnd = input_format_date($financeyr[1], $date_format_policy);
        }else{
            $financeYearDetails=get_financial_year($invoiceDate);
            if(empty($financeYearDetails)){
                $this->session->set_flashdata('e', 'Finance period not found for the selected document date');
                return array('status' => false);
                exit;
            }else{
                $FYBegin=$financeYearDetails['beginingDate'];
                $FYEnd=$financeYearDetails['endingDate'];
                $_POST['companyFinanceYear'] = $FYBegin.' - '.$FYEnd;
                $_POST['financeyear'] = $financeYearDetails['companyFinanceYearID'];
            }
            $financePeriodDetails=get_financial_period_date_wise($invoiceDate);

            if(empty($financePeriodDetails)){
                $this->session->set_flashdata('e', 'Finance period not found for the selected document date');
                return array('status' => false);
                exit;
            }else{

                $_POST['financeyear_period'] = $financePeriodDetails['companyFinancePeriodID'];
            }
        }

        if($this->input->post('invoiceType')=='Insurance'){
            $date_format_policy = date_format_policy();
            $policyStDt = $this->input->post('policyStartDate');
            $policyStartDate = input_format_date($policyStDt, $date_format_policy);

            $policyendDt = $this->input->post('policyEndDate');
            $policyEndDate = input_format_date($policyendDt, $date_format_policy);
        }else{
            $policyStartDate=null;
            $policyEndDate=null;
        }

        $segment = explode('|', trim($this->input->post('segment')));
        $customer_arr = $this->fetch_customer_data(trim($this->input->post('customerID')));
        //$location = explode('|', trim($this->input->post('location_dec')));
        $currency_code = explode('|', trim($this->input->post('currency_code')));
        if ($this->input->post('RVbankCode')) {
            $bank_detail = fetch_gl_account_desc(trim($this->input->post('RVbankCode')));
            $data['bankGLAutoID'] = $bank_detail['GLAutoID'];
            $data['bankSystemAccountCode'] = $bank_detail['systemAccountCode'];
            $data['bankGLSecondaryCode'] = $bank_detail['GLSecondaryCode'];
            $data['bankCurrencyID'] = $bank_detail['bankCurrencyID'];
            $data['bankCurrency'] = $bank_detail['bankCurrencyCode'];
            $data['invoicebank'] = $bank_detail['bankName'];
            $data['invoicebankBranch'] = $bank_detail['bankBranch'];
            $data['invoicebankSwiftCode'] = $bank_detail['bankSwiftCode'];
            $data['invoicebankAccount'] = $bank_detail['bankAccountNumber'];
            $data['invoicebankType'] = $bank_detail['subCategory'];
        }
        $data['documentID'] = 'CINV';
        $data['insuranceTypeID'] = $this->input->post('insurancetypeid');
        $data['insuranceSubTypeID'] = $this->input->post('insuranceSubTypeID');
        $data['policyStartDate'] = $policyStartDate;
        $data['policyEndDate'] = $policyEndDate;
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
        $data['contactPersonName'] = trim($this->input->post('contactPersonName'));
        $data['contactPersonNumber'] = trim($this->input->post('contactPersonNumber'));
        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        /*$data['FYPeriodDateFrom'] = trim($period[0]);
        $data['FYPeriodDateTo'] = trim($period[1]);*/
        $data['invoiceDate'] = trim($invoiceDate);
        $data['customerInvoiceDate'] = trim($customerInvoiceDate);
        $data['invoiceDueDate'] = trim($invoiceDueDate);
        $data['invoiceNarration'] = trim_desc($this->input->post('invoiceNarration'));
        $data['invoiceNote'] = trim($this->input->post('invoiceNote'));
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['salesPersonID'] = trim($this->input->post('salesPersonID'));
        if ($data['salesPersonID']) {
            $code = explode(' | ', trim($this->input->post('salesPerson')));
            $data['SalesPersonCode'] = trim($code[0]);
        }
        // $data['wareHouseCode'] = trim($location[0]);
        // $data['wareHouseLocation'] = trim($location[1]);
        // $data['wareHouseDescription'] = trim($location[2]);
        $data['invoiceType'] = trim($this->input->post('invoiceType'));
        $data['referenceNo'] = trim($this->input->post('referenceNo'));
        $data['isPrintDN'] = trim($this->input->post('isPrintDN'));
        $data['customerID'] = $customer_arr['customerAutoID'];
        $data['customerSystemCode'] = $customer_arr['customerSystemCode'];
        $data['customerName'] = $customer_arr['customerName'];
        $data['customerAddress'] = $customer_arr['customerAddress1'] . ' ' . $customer_arr['customerAddress2'];
        $data['customerTelephone'] = $customer_arr['customerTelephone'];
        $data['customerFax'] = $customer_arr['customerFax'];
        $data['customerEmail'] = $customer_arr['customerEmail'];
        $data['customerReceivableAutoID'] = $customer_arr['receivableAutoID'];
        $data['customerReceivableSystemGLCode'] = $customer_arr['receivableSystemGLCode'];
        $data['customerReceivableGLAccount'] = $customer_arr['receivableGLAccount'];
        $data['customerReceivableDescription'] = $customer_arr['receivableDescription'];
        $data['customerReceivableType'] = $customer_arr['receivableType'];
        $data['customerCurrency'] = $customer_arr['customerCurrency'];
        $data['customerCurrencyID'] = $customer_arr['customerCurrencyID'];
        $data['customerCurrencyDecimalPlaces'] = $customer_arr['customerCurrencyDecimalPlaces'];
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $customer_currency = currency_conversionID($data['transactionCurrencyID'], $data['customerCurrencyID']);
        $data['customerCurrencyExchangeRate'] = $customer_currency['conversion'];
        $data['customerCurrencyDecimalPlaces'] = $customer_currency['DecimalPlaces'];

        if (trim($this->input->post('invoiceAutoID'))) {
            $this->db->where('invoiceAutoID', trim($this->input->post('invoiceAutoID')));
            $this->db->update('srp_erp_customerinvoicemaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Invoice Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                update_warehouse_items();
                update_item_master();
                $this->session->set_flashdata('s', 'Invoice Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('invoiceAutoID'));
            }
        } else {
            //$this->load->library('sequence');
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['invoiceCode'] = 0;
            //if ($data['isPrintDN']==1) {
            $data['deliveryNoteSystemCode'] = $this->sequence->sequence_generator('DLN');
            //}

            $this->db->insert('srp_erp_customerinvoicemaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Invoice   Saved Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                update_warehouse_items();
                update_item_master();
                $this->session->set_flashdata('s', 'Invoice Saved Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $last_id);
            }
        }
    }
    function fetch_invoice_template_data_temp_insurance($invoiceAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $companyID=current_companyID();
        $this->db->select('srp_erp_customerinvoicemaster.*,srp_erp_segment.description as segDescription,insurancetype.insuranceType as insurance,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDate,\'' . $convertFormat . '\') AS invoiceDate ,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,DATE_FORMAT(srp_erp_customerinvoicemaster.customerInvoiceDate,\'' . $convertFormat . '\') AS customerInvoiceDate,DATE_FORMAT(srp_erp_customerinvoicemaster.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,CASE WHEN confirmedYN = 2 || confirmedYN = 3   THEN " - " WHEN confirmedYN = 1 THEN CONCAT_WS(\' on \',IF(LENGTH(confirmedbyName),confirmedbyName,\'-\'),IF(LENGTH(DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' )),DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' ),NULL)) ELSE "-" END confirmedYNn,srp_erp_salespersonmaster.SalesPersonName as SalesPersonName');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->from('srp_erp_customerinvoicemaster');
        $this->db->join('srp_erp_invoiceinsurancetypes insurancetype','insurancetype.insuranceTypeID = srp_erp_customerinvoicemaster.insuranceTypeID','left');
        $this->db->join('srp_erp_salespersonmaster','srp_erp_salespersonmaster.salesPersonID = srp_erp_customerinvoicemaster.salesPersonID','left');
        $this->db->join('srp_erp_segment', 'srp_erp_segment.segmentID = srp_erp_customerinvoicemaster.segmentID', 'Left');
        $data['master'] = $this->db->get()->row_array();
        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);

        $this->db->select('customerName,customerAddress1,customerTelephone,customerSystemCode,customerFax');
        $this->db->where('customerAutoID', $data['master']['customerID']);
        $this->db->from('srp_erp_customermaster');
        $data['customer'] = $this->db->get()->row_array();


        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'Item');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['item_detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'GL');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['gl_detail'] = $this->db->get()->result_array();

        $this->db->select('srp_erp_customerinvoicedetails.*,srp_erp_suppliermaster.supplierName as supplierName');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'insurance');
        $this->db->join('srp_erp_suppliermaster','srp_erp_suppliermaster.supplierAutoID = srp_erp_customerinvoicedetails.supplierAutoID','left');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['insurance_detail'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['tax'] = $this->db->get('srp_erp_customerinvoicetaxdetails')->result_array();

        $data['taxledger'] = $this->db->query("SELECT
	tax.taxDescription,tax.taxShortCode,srp_erp_taxledger.taxMasterID,SUM(srp_erp_taxledger.amount)as amount
FROM
	`srp_erp_taxledger`
LEFT JOIN srp_erp_taxmaster tax on srp_erp_taxledger.taxMasterID=tax.taxMasterAutoID
WHERE
	documentMasterAutoID = $invoiceAutoID
AND	documentID = 'CINV'
AND srp_erp_taxledger.companyID = $companyID

GROUP BY srp_erp_taxledger.taxMasterID ")->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['extracharge'] = $this->db->get('srp_erp_customerinvoiceextrachargedetails')->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['discount'] = $this->db->get('srp_erp_customerinvoicediscountdetails')->result_array();

        return $data;
    }


    function save_direct_invoice_detail_margin()
    {
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $projectExist = project_is_exist();
        $segment_gls = $this->input->post('segment_gl');
        $gl_code_des = $this->input->post('gl_code_des');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $gl_code = $this->input->post('gl_code');
        $projectID = $this->input->post('projectID');
        $amount = $this->input->post('amount');
        $marginPercentage = $this->input->post('marginPercentage');
        $marginAmount = $this->input->post('marginAmount');
        //$transactionAmount = $this->input->post('transactionAmount');
        $description = $this->input->post('description');

        foreach ($segment_gls as $key => $segment_gl) {
            $segment = explode('|', $segment_gl);
            $gl_code_de = explode(' | ', $gl_code_des[$key]);
            $data[$key]['invoiceAutoID'] = trim($invoiceAutoID);
            if ($projectExist == 1) {
                $projectCurrency = project_currency($projectID[$key]);
                $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
                $data[$key]['projectID'] = $projectID[$key];
                $data[$key]['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
            }
            $data[$key]['revenueGLAutoID'] = $gl_code[$key];
            $data[$key]['revenueSystemGLCode'] = trim($gl_code_de[0]);
            $data[$key]['revenueGLCode'] = trim($gl_code_de[1]);
            $data[$key]['revenueGLDescription'] = trim($gl_code_de[2]);
            $data[$key]['revenueGLType'] = trim($gl_code_de[3]);
            $data[$key]['segmentID'] = trim($segment[0]);
            $data[$key]['segmentCode'] = trim($segment[1]);
            $data[$key]['transactionAmount'] = round($amount[$key]+$marginAmount[$key], $master['transactionCurrencyDecimalPlaces']);
            $data[$key]['marginPercentage'] = $marginPercentage[$key];
            $data[$key]['marginAmount'] = round($marginAmount[$key], $master['transactionCurrencyDecimalPlaces']);
            $companyLocalAmount = $data[$key]['transactionAmount'] / $master['companyLocalExchangeRate'];
            $data[$key]['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $marginLocalAmount = $data[$key]['marginAmount'] / $master['companyLocalExchangeRate'];
            $data[$key]['marginLocalAmount'] = round($marginLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $companyReportingAmount = $data[$key]['transactionAmount'] / $master['companyReportingExchangeRate'];
            $data[$key]['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $marginReportingAmount = $data[$key]['marginAmount'] / $master['companyReportingExchangeRate'];
            $data[$key]['marginReportingAmount'] = round($marginReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $customerAmount = $data[$key]['transactionAmount'] / $master['customerCurrencyExchangeRate'];
            $data[$key]['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
            $marginCustomerAmount = $data[$key]['marginAmount'] / $master['customerCurrencyExchangeRate'];
            $data[$key]['marginCustomerAmount'] = round($marginCustomerAmount, $master['customerCurrencyDecimalPlaces']);
            $data[$key]['description'] = trim($description[$key]);
            $data[$key]['type'] = 'GL';
            $data[$key]['modifiedPCID'] = $this->common_data['current_pc'];
            $data[$key]['modifiedUserID'] = $this->common_data['current_userID'];
            $data[$key]['modifiedUserName'] = $this->common_data['current_user'];
            $data[$key]['modifiedDateTime'] = $this->common_data['current_date'];

            if (trim($this->input->post('invoiceDetailsAutoID'))) {
                /*$this->db->where('invoiceDetailsAutoID', trim($this->input->post('invoiceDetailsAutoID')));
                $this->db->update('srp_erp_customerinvoicedetails', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('e', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Update Failed ' . $this->db->_error_message());
                    $this->db->trans_rollback();
                    return array('status' => false);
                } else {
                    $this->session->set_flashdata('s', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Updated Successfully.');
                    $this->db->trans_commit();
                    return array('status' => true, 'last_id' => $this->input->post('invoiceDetailsAutoID'));
                }*/
            } else {
                $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
                $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];
                $data[$key]['createdUserGroup'] = $this->common_data['user_group'];
                $data[$key]['createdPCID'] = $this->common_data['current_pc'];
                $data[$key]['createdUserID'] = $this->common_data['current_userID'];
                $data[$key]['createdUserName'] = $this->common_data['current_user'];
                $data[$key]['createdDateTime'] = $this->common_data['current_date'];

            }
        }

        $this->db->insert_batch('srp_erp_customerinvoicedetails', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', 'Invoice Detail : Save Failed ' . $this->db->_error_message());
            $this->db->trans_rollback();
            return array('status' => false);

        } else {
            $this->session->set_flashdata('s', 'Invoice Detail Saved Successfully');
            $this->db->trans_commit();
            return array('status' => true);
        }
    }

    function update_income_invoice_detail_margin()
    {
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $segment_gl = $this->input->post('segment_gl');
        $gl_code_des = $this->input->post('gl_code_des');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $projectID = $this->input->post('projectID');
        $gl_code = $this->input->post('gl_code');
        $amount = $this->input->post('amount');
        $marginPercentage = $this->input->post('marginPercentage');
        $marginAmount = $this->input->post('marginAmount');
        $description = $this->input->post('description');
        $projectExist = project_is_exist();

        $segment = explode('|', $segment_gl);
        $gl_code_de = explode(' | ', $gl_code_des);
        $data['invoiceAutoID'] = trim($invoiceAutoID);
        $data['revenueGLAutoID'] = $gl_code;
        if ($projectExist == 1) {
            $projectCurrency = project_currency($projectID);
            $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
            $data['projectID'] = $projectID;
            $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
        }
        $data['revenueSystemGLCode'] = trim($gl_code_de[0]);
        $data['revenueGLCode'] = trim($gl_code_de[1]);
        $data['revenueGLDescription'] = trim($gl_code_de[2]);
        $data['revenueGLType'] = trim($gl_code_de[3]);
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['marginPercentage'] = $marginPercentage;
        $data['transactionAmount'] = round($amount+$marginAmount, $master['transactionCurrencyDecimalPlaces']);
        $data['marginAmount'] = round($marginAmount, $master['transactionCurrencyDecimalPlaces']);

        $companyLocalAmount = $data['transactionAmount'] / $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
        $marginLocalAmount = $data['marginAmount'] / $master['companyLocalExchangeRate'];
        $data['marginLocalAmount'] = round($marginLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
        $companyReportingAmount = $data['transactionAmount'] / $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
        $marginReportingAmount = $data['marginAmount'] / $master['companyReportingExchangeRate'];
        $data['marginReportingAmount'] = round($marginReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
        $customerAmount = $data['transactionAmount'] / $master['customerCurrencyExchangeRate'];
        $data['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
        $marginCustomerAmount = $data['marginAmount'] / $master['customerCurrencyExchangeRate'];
        $data['marginCustomerAmount'] = round($marginCustomerAmount, $master['customerCurrencyDecimalPlaces']);

        $data['description'] = trim($description);
        $data['type'] = 'GL';
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('invoiceDetailsAutoID'))) {
            $this->db->where('invoiceDetailsAutoID', trim($this->input->post('invoiceDetailsAutoID')));
            $this->db->update('srp_erp_customerinvoicedetails', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Invoice Detail : ' . $data['revenueSystemGLCode'] . ' ' . $data['revenueGLDescription'] . ' Updated Successfully.');
            }
        }
    }

    function fetch_invoice_template_data_temp_margin($invoiceAutoID)
    {

        $convertFormat = convert_date_format_sql();
        $companyID=current_companyID();
        $this->db->select('*,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDate,\'' . $convertFormat . '\') AS invoiceDate ,DATE_FORMAT(srp_erp_customerinvoicemaster.invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate,DATE_FORMAT(srp_erp_customerinvoicemaster.customerInvoiceDate,\'' . $convertFormat . '\') AS customerInvoiceDate,DATE_FORMAT(srp_erp_customerinvoicemaster.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,CASE WHEN confirmedYN = 2 || confirmedYN = 3   THEN " - " WHEN confirmedYN = 1 THEN CONCAT_WS(\' on \',IF(LENGTH(confirmedbyName),confirmedbyName,\'-\'),IF(LENGTH(DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' )),DATE_FORMAT( confirmedDate, \'' . $convertFormat . ' %h:%i:%s\' ),NULL)) ELSE "-" END confirmedYNn');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->from('srp_erp_customerinvoicemaster');
        $data['master'] = $this->db->get()->row_array();
        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);

        $this->db->select('customerName,customerAddress1,customerTelephone,customerSystemCode,customerFax');
        $this->db->where('customerAutoID', $data['master']['customerID']);
        $this->db->from('srp_erp_customermaster');
        $data['customer'] = $this->db->get()->row_array();


        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'Item');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['item_detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('type', 'GL');
        $this->db->from('srp_erp_customerinvoicedetails');
        $data['gl_detail'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['tax'] = $this->db->get('srp_erp_customerinvoicetaxdetails')->result_array();

        $data['taxledger'] = $this->db->query("SELECT
	tax.taxDescription,tax.taxShortCode,srp_erp_taxledger.taxMasterID,SUM(srp_erp_taxledger.amount)as amount
FROM
	`srp_erp_taxledger`
LEFT JOIN srp_erp_taxmaster tax on srp_erp_taxledger.taxMasterID=tax.taxMasterAutoID
WHERE
	documentMasterAutoID = $invoiceAutoID
AND	documentID = 'CINV'
AND srp_erp_taxledger.companyID = $companyID

GROUP BY srp_erp_taxledger.taxMasterID ")->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['extracharge'] = $this->db->get('srp_erp_customerinvoiceextrachargedetails')->result_array();

        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['discount'] = $this->db->get('srp_erp_customerinvoicediscountdetails')->result_array();

        return $data;
    }

    function save_insurance_invoice_detail_margin()
    {
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID,srp_erp_invoiceinsurancetypes.GLAutoID as marginGLAutoID');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $this->db->join('srp_erp_invoiceinsurancetypes', 'srp_erp_invoiceinsurancetypes.insuranceTypeID = srp_erp_customerinvoicemaster.insuranceTypeID', 'left');
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $projectExist = project_is_exist();
        $segment_gls = $this->input->post('segment_gl');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $supplierAutoID = $this->input->post('supplierAutoID');
        $projectID = $this->input->post('projectID');
        $amount = $this->input->post('amount');
        $marginPercentage = $this->input->post('marginPercentage');
        $marginAmount = $this->input->post('marginAmount');
        $description = $this->input->post('description');

        foreach ($segment_gls as $key => $segment_gl) {
            $segment = explode('|', $segment_gl);

            $this->db->select('*');
            $this->db->where('supplierAutoID', $supplierAutoID[$key]);
            $supplierdetail = $this->db->get('srp_erp_suppliermaster')->row_array();

            $data[$key]['invoiceAutoID'] = trim($invoiceAutoID);
            if ($projectExist == 1) {
                $projectCurrency = project_currency($projectID[$key]);
                $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
                $data[$key]['projectID'] = $projectID[$key];
                $data[$key]['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
            }
            $data[$key]['supplierAutoID'] = $supplierAutoID[$key];
            $data[$key]['revenueGLAutoID'] = $supplierdetail['liabilityAutoID'];
            $data[$key]['revenueSystemGLCode'] = $supplierdetail['liabilitySystemGLCode'];
            $data[$key]['revenueGLCode'] =  $supplierdetail['liabilityGLAccount'];
            $data[$key]['revenueGLDescription'] = $supplierdetail['liabilityDescription'];
            $data[$key]['revenueGLType'] = $supplierdetail['liabilityType'];
            $data[$key]['segmentID'] = trim($segment[0]);
            $data[$key]['segmentCode'] = trim($segment[1]);
            $data[$key]['transactionAmount'] = round($amount[$key], $master['transactionCurrencyDecimalPlaces']);
            $data[$key]['marginPercentage'] = $marginPercentage[$key];
            $data[$key]['marginAmount'] = round($marginAmount[$key], $master['transactionCurrencyDecimalPlaces']);
            $companyLocalAmount = $data[$key]['transactionAmount'] / $master['companyLocalExchangeRate'];
            $data[$key]['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $marginLocalAmount = $data[$key]['marginAmount'] / $master['companyLocalExchangeRate'];
            $data[$key]['marginLocalAmount'] = round($marginLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
            $companyReportingAmount = $data[$key]['transactionAmount'] / $master['companyReportingExchangeRate'];
            $data[$key]['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $marginReportingAmount = $data[$key]['marginAmount'] / $master['companyReportingExchangeRate'];
            $data[$key]['marginReportingAmount'] = round($marginReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
            $customerAmount = $data[$key]['transactionAmount'] / $master['customerCurrencyExchangeRate'];
            $data[$key]['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
            $marginCustomerAmount = $data[$key]['marginAmount'] / $master['customerCurrencyExchangeRate'];
            $data[$key]['marginCustomerAmount'] = round($marginCustomerAmount, $master['customerCurrencyDecimalPlaces']);
            $data[$key]['marginGLAutoID'] = $master['marginGLAutoID'];
            $data[$key]['description'] = trim($description[$key]);
            $data[$key]['type'] = 'insurance';
            $data[$key]['modifiedPCID'] = $this->common_data['current_pc'];
            $data[$key]['modifiedUserID'] = $this->common_data['current_userID'];
            $data[$key]['modifiedUserName'] = $this->common_data['current_user'];
            $data[$key]['modifiedDateTime'] = $this->common_data['current_date'];

            if (trim($this->input->post('invoiceDetailsAutoID'))) {

            } else {
                $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
                $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];
                $data[$key]['createdUserGroup'] = $this->common_data['user_group'];
                $data[$key]['createdPCID'] = $this->common_data['current_pc'];
                $data[$key]['createdUserID'] = $this->common_data['current_userID'];
                $data[$key]['createdUserName'] = $this->common_data['current_user'];
                $data[$key]['createdDateTime'] = $this->common_data['current_date'];

            }
        }

        $this->db->insert_batch('srp_erp_customerinvoicedetails', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', 'Invoice Detail : Save Failed ' . $this->db->_error_message());
            $this->db->trans_rollback();
            return array('status' => false);

        } else {
            $this->session->set_flashdata('s', 'Invoice Detail Saved Successfully');
            $this->db->trans_commit();
            return array('status' => true);
        }
    }

    function update_income_invoice_detail_insurance()
    {
        $this->db->trans_start();
        $this->db->select('companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate,transactionCurrencyDecimalPlaces,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,customerCurrencyDecimalPlaces,transactionCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
        $master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $segment_gl = $this->input->post('segment_gl');
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $projectID = $this->input->post('projectID');
        $supplierAutoID = $this->input->post('supplierAutoID');
        $amount = $this->input->post('amount');
        $marginPercentage = $this->input->post('marginPercentage');
        $marginAmount = $this->input->post('marginAmount');
        $description = $this->input->post('description');
        $projectExist = project_is_exist();

        $this->db->select('*');
        $this->db->where('supplierAutoID', $supplierAutoID);
        $supplierdetail = $this->db->get('srp_erp_suppliermaster')->row_array();

        $segment = explode('|', $segment_gl);
        $data['invoiceAutoID'] = trim($invoiceAutoID);
        $data['revenueGLAutoID'] = $supplierdetail['liabilityAutoID'];
        if ($projectExist == 1) {
            $projectCurrency = project_currency($projectID);
            $projectCurrencyExchangerate = currency_conversionID($master['transactionCurrencyID'], $projectCurrency);
            $data['projectID'] = $projectID;
            $data['projectExchangeRate'] = $projectCurrencyExchangerate['conversion'];
        }
        $data['revenueSystemGLCode'] = $supplierdetail['liabilitySystemGLCode'];
        $data['revenueGLCode'] =  $supplierdetail['liabilityGLAccount'];
        $data['revenueGLDescription'] = $supplierdetail['liabilityDescription'];
        $data['revenueGLType'] = $supplierdetail['liabilityType'];
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['marginPercentage'] = $marginPercentage;
        $data['transactionAmount'] = round($amount, $master['transactionCurrencyDecimalPlaces']);
        $data['marginAmount'] = round($marginAmount, $master['transactionCurrencyDecimalPlaces']);

        $companyLocalAmount = $data['transactionAmount'] / $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = round($companyLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
        $marginLocalAmount = $data['marginAmount'] / $master['companyLocalExchangeRate'];
        $data['marginLocalAmount'] = round($marginLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);
        $companyReportingAmount = $data['transactionAmount'] / $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = round($companyReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
        $marginReportingAmount = $data['marginAmount'] / $master['companyReportingExchangeRate'];
        $data['marginReportingAmount'] = round($marginReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
        $customerAmount = $data['transactionAmount'] / $master['customerCurrencyExchangeRate'];
        $data['customerAmount'] = round($customerAmount, $master['customerCurrencyDecimalPlaces']);
        $marginCustomerAmount = $data['marginAmount'] / $master['customerCurrencyExchangeRate'];
        $data['marginCustomerAmount'] = round($marginCustomerAmount, $master['customerCurrencyDecimalPlaces']);

        $data['description'] = trim($description);
        $data['type'] = 'insurance';
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('invoiceDetailsAutoID'))) {
            $this->db->where('invoiceDetailsAutoID', trim($this->input->post('invoiceDetailsAutoID')));
            $this->db->update('srp_erp_customerinvoicedetails', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Invoice Detail Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Invoice Detail Updated Successfully.');
            }
        }
    }

    function delivery_detail($customerID){
        $companyID = current_companyID();
        $data = $this->db->query("SELECT ord.DOAutoID, DOCode, DODate, referenceNo, transactionAmount, transactionCurrencyDecimalPlaces,
                          (IFNULL(paid_amount,0) + IFNULL(return_amount,0)) AS invoiced_amount
                          FROM srp_erp_deliveryorder ord
                          LEFT JOIN (
                              SELECT DOMasterID, SUM(det.transactionAmount) paid_amount FROM srp_erp_customerinvoicedetails det
                              JOIN srp_erp_customerinvoicemaster mas ON mas.invoiceAutoID = det.invoiceAutoID                              
                              WHERE mas.companyID = {$companyID} AND customerID = {$customerID} GROUP BY DOMasterID
                          ) paidDet ON paidDet.DOMasterID = ord.DOAutoID
                          LEFT JOIN(
                              SELECT returnDet.DOAutoID, SUM(returnDet.totalValue) return_amount
                              FROM srp_erp_salesreturnmaster AS returnMas
                              JOIN srp_erp_salesreturndetails AS returnDet ON returnMas.salesReturnAutoID = returnDet.salesReturnAutoID
                              WHERE returnMas.companyID = {$companyID} AND customerID = {$customerID}  
                              AND returnDet.invoiceAutoID IS NULL GROUP BY returnDet.DOAutoID
                          ) AS return_tb ON return_tb.DOAutoID = ord.DOAutoID
                          WHERE companyID = {$companyID} AND approvedYN = 1 AND customerID = {$customerID}")->result_array();
        //echo '<pre>'.$this->db->last_query().'</pre>';
        return $data;
    }


    function save_inv_discount_detail(){
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $this->input->post('InvoiceAutoID'));
        $this->db->where('discountMasterAutoID', $this->input->post('discountExtraChargeID'));
        $tax_detail = $this->db->get('srp_erp_customerinvoicediscountdetails')->row_array();
        if (!empty($tax_detail)) {
            $this->session->set_flashdata('w', 'Discount Detail added already ! ');
            return array('status' => true);
        }
        $this->db->select('*');
        $this->db->where('discountExtraChargeID', $this->input->post('discountExtraChargeID'));
        $master = $this->db->get('srp_erp_discountextracharges')->row_array();

        $this->db->select('segmentCode,segmentID,customerCurrencyDecimalPlaces,customerCurrencyExchangeRate,customerCurrencyID,customerCurrency,transactionCurrency,transactionExchangeRate,transactionCurrencyDecimalPlaces ,transactionCurrencyID,companyLocalCurrency, companyLocalExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrency,companyReportingExchangeRate, companyReportingCurrencyDecimalPlaces,companyLocalCurrencyID, companyReportingCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('InvoiceAutoID'));
        $inv_master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $data['invoiceAutoID']                   = trim($this->input->post('InvoiceAutoID'));
        $data['discountMasterAutoID']            = $master['discountExtraChargeID'];
        $data['discountDescription']             = $master['Description'];
        $data['isChargeToExpense']               = $master['isChargeToExpense'];
        $data['discountPercentage']              = trim($this->input->post('discountPercentage'));
        $data['transactionAmount']               = trim($this->input->post('discount_amount'));
        $data['transactionCurrencyID']           = $inv_master['transactionCurrencyID'];
        $data['transactionCurrency']             = $inv_master['transactionCurrency'];
        $data['transactionExchangeRate']         = $inv_master['transactionExchangeRate'];
        $data['transactionCurrencyDecimalPlaces']= $inv_master['transactionCurrencyDecimalPlaces'];
        $data['companyLocalCurrencyID']          = $inv_master['companyLocalCurrencyID'];
        $data['companyLocalCurrency']            = $inv_master['companyLocalCurrency'];
        $data['companyLocalExchangeRate']        = $inv_master['companyLocalExchangeRate'];
        $data['companyReportingCurrencyID']      = $inv_master['companyReportingCurrencyID'];
        $data['companyReportingCurrency']        = $inv_master['companyReportingCurrency'];
        $data['companyReportingExchangeRate']    = $inv_master['companyReportingExchangeRate'];
        $data['customerCurrencyID']              = $inv_master['customerCurrencyID'];
        $data['customerCurrency']                = $inv_master['customerCurrency'];
        $data['customerCurrencyExchangeRate']    = $inv_master['customerCurrencyExchangeRate'];
        $data['customerCurrencyDecimalPlaces']   = $inv_master['customerCurrencyDecimalPlaces'];
        $data['segmentID']                       = $inv_master['segmentID'];
        $data['segmentCode']                     = $inv_master['segmentCode'];
        $data['customerCurrencyAmount']          =  round(($data['transactionAmount']/$data['customerCurrencyExchangeRate']), $data['customerCurrencyDecimalPlaces']);
        $data['companyLocalAmount']              =  $data['transactionAmount']/$data['companyLocalExchangeRate'];
        $data['companyReportingAmount']          =  $data['transactionAmount']/$data['companyReportingExchangeRate'];
        if(!empty($master['glCode'])){
            $data['GLAutoID']                        = $master['glCode'];
            $gl = fetch_gl_account_desc($master['glCode']);
            $data['systemGLCode']                    = $gl['systemAccountCode'];
            $data['GLCode']                          = $gl['GLSecondaryCode'];
            $data['GLDescription']                   = $gl['GLDescription'];
            $data['GLType']                          = $gl['subCategory'];
        }
        $data['modifiedPCID']                    = $this->common_data['current_pc'];
        $data['modifiedUserID']                  = $this->common_data['current_userID'];
        $data['modifiedUserName']                = $this->common_data['current_user'];
        $data['modifiedDateTime']                = $this->common_data['current_date'];
        $data['companyCode']        = $this->common_data['company_data']['company_code'];
        $data['companyID']          = $this->common_data['company_data']['company_id'];
        $data['createdUserGroup']   = $this->common_data['user_group'];
        $data['createdPCID']        = $this->common_data['current_pc'];
        $data['createdUserID']      = $this->common_data['current_userID'];
        $data['createdUserName']    = $this->common_data['current_user'];
        $data['createdDateTime']    = $this->common_data['current_date'];
        $this->db->insert('srp_erp_customerinvoicediscountdetails', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', 'Discount Detail Save Failed ' . $this->db->_error_message());
            $this->db->trans_rollback();
            return array('status' => false);
        } else {
            $this->session->set_flashdata('s', 'Discount Detail Saved Successfully.');
            $this->db->trans_commit();
            return array('status' => true, 'last_id' => $last_id);
        }
    }


    function save_inv_extra_detail(){
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $this->input->post('InvoiceAutoID'));
        $this->db->where('extraChargeMasterAutoID', $this->input->post('discountExtraChargeIDExtra'));
        $tax_detail = $this->db->get('srp_erp_customerinvoiceextrachargedetails')->row_array();
        if (!empty($tax_detail)) {
            $this->session->set_flashdata('w', 'Extra Charges added already ! ');
            return array('status' => true);
        }
        $this->db->select('*');
        $this->db->where('discountExtraChargeID', $this->input->post('discountExtraChargeIDExtra'));
        $master = $this->db->get('srp_erp_discountextracharges')->row_array();

        $this->db->select('segmentCode,segmentID,customerCurrencyDecimalPlaces,customerCurrencyExchangeRate,customerCurrencyID,customerCurrency,transactionCurrency,transactionExchangeRate,transactionCurrencyDecimalPlaces ,transactionCurrencyID,companyLocalCurrency, companyLocalExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrency,companyReportingExchangeRate, companyReportingCurrencyDecimalPlaces,companyLocalCurrencyID, companyReportingCurrencyID');
        $this->db->where('invoiceAutoID', $this->input->post('InvoiceAutoID'));
        $inv_master = $this->db->get('srp_erp_customerinvoicemaster')->row_array();

        $data['invoiceAutoID']                   = trim($this->input->post('InvoiceAutoID'));
        $data['extraChargeMasterAutoID']         = $master['discountExtraChargeID'];
        $data['extraChargeDescription']          = $master['Description'];
        $data['isTaxApplicable']                 = $master['isTaxApplicable'];
        $data['transactionAmount']               = trim($this->input->post('extra_amount'));
        $data['transactionCurrencyID']           = $inv_master['transactionCurrencyID'];
        $data['transactionCurrency']             = $inv_master['transactionCurrency'];
        $data['transactionExchangeRate']         = $inv_master['transactionExchangeRate'];
        $data['transactionCurrencyDecimalPlaces']= $inv_master['transactionCurrencyDecimalPlaces'];
        $data['companyLocalCurrencyID']          = $inv_master['companyLocalCurrencyID'];
        $data['companyLocalCurrency']            = $inv_master['companyLocalCurrency'];
        $data['companyLocalExchangeRate']        = $inv_master['companyLocalExchangeRate'];
        $data['companyReportingCurrencyID']      = $inv_master['companyReportingCurrencyID'];
        $data['companyReportingCurrency']        = $inv_master['companyReportingCurrency'];
        $data['companyReportingExchangeRate']    = $inv_master['companyReportingExchangeRate'];
        $data['customerCurrencyID']              = $inv_master['customerCurrencyID'];
        $data['customerCurrency']                = $inv_master['customerCurrency'];
        $data['customerCurrencyExchangeRate']    = $inv_master['customerCurrencyExchangeRate'];
        $data['customerCurrencyDecimalPlaces']   = $inv_master['customerCurrencyDecimalPlaces'];
        $data['segmentID']                       = $inv_master['segmentID'];
        $data['segmentCode']                     = $inv_master['segmentCode'];
        $data['customerCurrencyAmount']          =  round(($data['transactionAmount']/$data['customerCurrencyExchangeRate']), $data['customerCurrencyDecimalPlaces']);
        $data['companyLocalAmount']              =  $data['transactionAmount']/$data['companyLocalExchangeRate'];
        $data['companyReportingAmount']          =  $data['transactionAmount']/$data['companyReportingExchangeRate'];
        $data['GLAutoID']                        = $master['glCode'];
        $gl = fetch_gl_account_desc($master['glCode']);
        $data['systemGLCode']                    = $gl['systemAccountCode'];
        $data['GLCode']                          = $gl['GLSecondaryCode'];
        $data['GLDescription']                   = $gl['GLDescription'];
        $data['GLType']                          = $gl['subCategory'];
        $data['modifiedPCID']                    = $this->common_data['current_pc'];
        $data['modifiedUserID']                  = $this->common_data['current_userID'];
        $data['modifiedUserName']                = $this->common_data['current_user'];
        $data['modifiedDateTime']                = $this->common_data['current_date'];
        $data['companyCode']        = $this->common_data['company_data']['company_code'];
        $data['companyID']          = $this->common_data['company_data']['company_id'];
        $data['createdUserGroup']   = $this->common_data['user_group'];
        $data['createdPCID']        = $this->common_data['current_pc'];
        $data['createdUserID']      = $this->common_data['current_userID'];
        $data['createdUserName']    = $this->common_data['current_user'];
        $data['createdDateTime']    = $this->common_data['current_date'];
        $this->db->insert('srp_erp_customerinvoiceextrachargedetails', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', 'Extra Charge Save Failed ' . $this->db->_error_message());
            $this->db->trans_rollback();
            return array('status' => false);
        } else {
            $this->session->set_flashdata('s', 'Extra Charge Saved Successfully.');
            $this->db->trans_commit();
            return array('status' => true, 'last_id' => $last_id);
        }
    }

    function delete_discount_gen(){
        $this->db->delete('srp_erp_customerinvoicediscountdetails',array('discountDetailID' => trim($this->input->post('discountDetailID'))));
        return true;
    }

    function delete_extra_gen(){
        $this->db->delete('srp_erp_customerinvoiceextrachargedetails',array('extraChargeDetailID' => trim($this->input->post('extraChargeDetailID'))));
        return true;
    }
    function fetch_customer_details_by_id()
    {
        $CustomerAutoID = trim($this->input->post('customerAutoID'));
        $this->db->select('customerAutoID as cusAuto,customerName,customerTelephone');
        $this->db->from('srp_erp_customermaster');
        $this->db->where('customerAutoID', $CustomerAutoID);
        return $this->db->get()->row_array();

    }
    function fetch_customer_details_currency()
    {
        $this->db->select('customerCurrencyID,customerCreditPeriod');
        $this->db->from('srp_erp_customermaster');
        $this->db->where('customerAutoID', trim($this->input->post('customerAutoID')));
        $data['currency'] = $this->db->get()->row_array();

        $this->db->select('customerAutoID as cusAuto,customerName,customerTelephone');
        $this->db->from('srp_erp_customermaster');
        $this->db->where('customerAutoID',trim($this->input->post('customerAutoID')));
        $data['detail'] = $this->db->get()->row_array();
        return $data;
    }


    function savesubinsurancetype()
    {
        $companyid = current_companyID();
        $insuranceType = $this->input->post('insuranceType');
        $insuranceTypeID = $this->input->post('insuranceTypeID');
        $masterTypeID = $this->input->post('masterTypeID');
        $marginPercentage = $this->input->post('marginPercentage');
        $noofMonths = $this->input->post('noofMonths');

        $q = "SELECT GLAutoID FROM srp_erp_invoiceinsurancetypes WHERE insuranceTypeID = $masterTypeID" ;
        $master = $this->db->query($q)->row_array();

        $data['insuranceType'] = $insuranceType;
        $data['GLAutoID'] = $master['GLAutoID'];
        $data['marginPercentage'] = $marginPercentage;
        $data['masterTypeID'] = $masterTypeID;
        $data['noofMonths'] = $noofMonths;

        if(!empty($insuranceTypeID))
        {
            $qm = "SELECT insuranceType FROM srp_erp_invoiceinsurancetypes WHERE insuranceType = '{$insuranceType }' AND companyID = $companyid AND masterTypeID = $masterTypeID AND insuranceTypeID != $insuranceTypeID" ;
            $master = $this->db->query($qm)->row_array();
            if(!empty($master)){
                return array('e', 'Sub Insurance Type Already Exist');
            }else
            {
                $data['modifiedPCID'] = $this->common_data['current_pc'];
                $data['modifiedUserID'] = $this->common_data['current_userID'];
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['modifiedUserName'] = $this->common_data['current_user'];
                $this->db->where('insuranceTypeID', $insuranceTypeID);
                $this->db->update('srp_erp_invoiceinsurancetypes', $data);
            }
        }else
        {
            $qm = "SELECT insuranceType FROM srp_erp_invoiceinsurancetypes WHERE insuranceType = '{$insuranceType }' AND companyID = $companyid AND masterTypeID = $masterTypeID" ;
            $master = $this->db->query($qm)->row_array();
            if(!empty($master)){
                return array('e', 'Sub Insurance Type Already Exist');
            }else
            {
                $data['companyID'] = $companyid;
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_invoiceinsurancetypes', $data);
                $last_id = $this->db->insert_id();
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Sub Insurance Type Added Failed.');
        } else {
            $this->db->trans_commit();
            return array('s', 'Sub Insurance Type Added Successfully.');
        }
    }

    function load_sub_type()
    {
        $this->db->select('insuranceTypeID,insuranceType');
        $this->db->where('masterTypeID', $this->input->post('insuranceTypeID'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_invoiceinsurancetypes');
        return $subtype = $this->db->get()->result_array();
    }

    function get_sub_type_months()
    {
        $this->db->select('noofMonths');
        $this->db->where('insuranceTypeID', $this->input->post('insuranceTypeID'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_invoiceinsurancetypes');
        $noofMonths = $this->db->get()->row_array();
        if (!empty($noofMonths)) {

            $date_format_policy = date_format_policy();
            $policyStartDate = $this->input->post('policyStartDate');
            $currDate = input_format_date($policyStartDate, $date_format_policy);

            $months = $noofMonths['noofMonths'];
            $convertFormat = convert_date_format_sql();
            $effectiveDate = date('Y-m-d', strtotime("+$months months", strtotime($currDate)));
            $convertedDate = convert_date_format($effectiveDate);

            return $convertedDate;
        } else {
            return 0;
        }
    }
    function fetch_customer_details()
    {
        $CustomerAutoID = trim($this->input->post('customer'));
        $this->db->select('customerTelephone,customerCurrencyID');
        $this->db->from('srp_erp_customermaster');
        $this->db->where('customerAutoID', $CustomerAutoID);
        return $this->db->get()->row_array();
    }

    function open_receipt_voucher_modal(){
        $invoiceAutoID = trim($this->input->post('invoiceAutoID'));
        $this->db->select('*');
        $this->db->from('srp_erp_customerinvoicemaster');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $data['master']=$this->db->get()->row_array();

        $this->db->select("GLAutoID");
        $this->db->from('srp_erp_chartofaccounts');
        $this->db->where('isDefaultlBank', 1);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $data['GL']=$this->db->get()->row_array();

        $customerID = $data['master']['customerID'];
        $RVdate = current_date();
        $currencyID = $data['master']['transactionCurrencyID'];

        $dataw = $this->db->query("SELECT srp_erp_customerinvoicemaster.invoiceAutoID, invoiceCode, receiptTotalAmount, advanceMatchedTotal, creditNoteTotalAmount, referenceNo, (( ( ( cid.transactionAmount - cid.totalAfterTax ) - ( ( ( IFNULL( gendiscount.discountPercentage, 0 ) / 100 ) * IFNULL(cid.transactionAmount, 0) ) )+ IFNULL( genexchargistax.transactionAmount, 0 ) ) * ( IFNULL(tax.taxPercentage, 0) / 100 ) + IFNULL(cid.transactionAmount, 0) ) - ( ( IFNULL( gendiscount.discountPercentage, 0 ) / 100 ) * IFNULL(cid.transactionAmount, 0) ) + IFNULL( genexcharg.transactionAmount, 0 )) AS transactionAmount, invoiceDate, slr.returnsalesvalue as salesreturnvalue FROM srp_erp_customerinvoicemaster LEFT JOIN ( SELECT invoiceAutoID, IFNULL(SUM(transactionAmount), 0) AS transactionAmount, IFNULL(SUM(totalAfterTax), 0) AS totalAfterTax FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID ) cid ON srp_erp_customerinvoicemaster.invoiceAutoID = cid.invoiceAutoID LEFT JOIN ( SELECT invoiceAutoID, SUM(taxPercentage) AS taxPercentage FROM srp_erp_customerinvoicetaxdetails GROUP BY invoiceAutoID ) tax ON tax.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT SUM(discountPercentage) AS discountPercentage, invoiceAutoID FROM srp_erp_customerinvoicediscountdetails GROUP BY invoiceAutoID ) gendiscount ON gendiscount.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT SUM(transactionAmount) AS transactionAmount, invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails WHERE isTaxApplicable = 1 GROUP BY invoiceAutoID ) genexchargistax ON genexchargistax.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT SUM(transactionAmount) AS transactionAmount, invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails GROUP BY invoiceAutoID ) genexcharg ON genexcharg.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT invoiceAutoID, IFNULL( SUM(slaesdetail.totalValue), 0 ) AS returnsalesvalue FROM srp_erp_salesreturndetails slaesdetail GROUP BY invoiceAutoID ) slr ON slr.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID WHERE confirmedYN = 1 AND approvedYN = 1 AND receiptInvoiceYN = 0 AND `customerID` = '{$customerID}' AND `transactionCurrencyID` = '{$currencyID}' AND invoiceDate <= '{$RVdate}' AND srp_erp_customerinvoicemaster.invoiceAutoID = $invoiceAutoID ")->row_array();
        $data['balance'] = number_format($dataw['transactionAmount'] - ($dataw['receiptTotalAmount'] + $dataw['creditNoteTotalAmount'] + $dataw['advanceMatchedTotal'] + $dataw['salesreturnvalue']),$data['master']['transactionCurrencyDecimalPlaces']);

        return $data;
    }




    function save_receiptvoucher_from_CINV_header()
    {
        $invoiceAutoID=$this->input->post('invoiceAutoID');
        $date_format_policy = date_format_policy();
        $financeyearperiodYN = getPolicyValues('FPC', 'All');
        $RVdates = $this->input->post('RVdate');
        $RVdate = input_format_date($RVdates, $date_format_policy);
        $RVcheqDate = $this->input->post('RVchequeDate');
        $RVchequeDate = input_format_date($RVcheqDate, $date_format_policy);
        //$period = explode('|', trim($this->input->post('financeyear_period')));
        $this->db->select('invoiceDate');
        $this->db->from('srp_erp_customerinvoicemaster');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $invdate=$this->db->get()->row_array();

        if($RVdate>=$invdate['invoiceDate']){
            if ($financeyearperiodYN == 1) {
                $financeyr = explode(' - ', trim($this->input->post('companyFinanceYear')));
                $FYBegin = input_format_date($financeyr[0], $date_format_policy);
                $FYEnd = input_format_date($financeyr[1], $date_format_policy);
            } else {
                $financeYearDetails = get_financial_year($RVdate);
                if (empty($financeYearDetails)) {
                    return array('e', 'Finance period not found for the selected document date');
                    exit;
                } else {
                    $FYBegin = $financeYearDetails['beginingDate'];
                    $FYEnd = $financeYearDetails['endingDate'];
                    $_POST['companyFinanceYear'] = $FYBegin . ' - ' . $FYEnd;
                    $_POST['financeyear'] = $financeYearDetails['companyFinanceYearID'];
                }
                $financePeriodDetails = get_financial_period_date_wise($RVdate);

                if (empty($financePeriodDetails)) {
                    return array('e', 'Finance period not found for the selected document date');
                    exit;
                } else {
                    $_POST['financeyear_period'] = $financePeriodDetails['companyFinancePeriodID'];
                }
            }
            $this->db->select("segmentCode");
            $this->db->from('srp_erp_segment');
            $this->db->where('segmentID', $this->input->post('segment'));
            $segment = $this->db->get()->row_array();

            $currency_code = fetch_currency_code($this->input->post('transactionCurrencyID'));


            $bank_detail = fetch_gl_account_desc(trim($this->input->post('RVbankCode')));
            $data['documentID'] = 'RV';
            $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
            $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
            $data['FYBegin'] = trim($FYBegin);
            $data['FYEnd'] = trim($FYEnd);
            $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));

            $data['RVdate'] = trim($RVdate);
            $data['RVNarration'] = trim_desc($this->input->post('RVNarration'));
            $data['segmentID'] = trim($this->input->post('segment'));
            $data['segmentCode'] = trim($segment['segmentCode']);
            $data['bankGLAutoID'] = $bank_detail['GLAutoID'];
            $data['bankSystemAccountCode'] = $bank_detail['systemAccountCode'];
            $data['bankGLSecondaryCode'] = $bank_detail['GLSecondaryCode'];
            $data['bankCurrencyID'] = $bank_detail['bankCurrencyID'];
            $data['bankCurrency'] = $bank_detail['bankCurrencyCode'];
            $data['RVbank'] = $bank_detail['bankName'];
            $data['RVbankBranch'] = $bank_detail['bankBranch'];
            $data['RVbankSwiftCode'] = $bank_detail['bankSwiftCode'];
            $data['RVbankAccount'] = $bank_detail['bankAccountNumber'];
            $data['RVbankType'] = $bank_detail['subCategory'];
            $data['modeOfPayment'] = ($bank_detail['isCash'] == 1 ? 1 : 2);
            $data['RVchequeNo'] = trim($this->input->post('RVchequeNo'));
            if ($bank_detail['isCash'] == 0) {
                $data['RVchequeDate'] = trim($RVchequeDate);
            } else {
                $data['RVchequeDate'] = null;
            }
            $data['RvType'] = trim($this->input->post('vouchertype'));
            $data['referanceNo'] = trim_desc($this->input->post('referenceno'));
            $data['RVbankCode'] = trim($this->input->post('RVbankCode'));

            $customer_arr = $this->fetch_customer_data(trim($this->input->post('customerID')));
            $data['customerID'] = $customer_arr['customerAutoID'];
            $data['customerSystemCode'] = $customer_arr['customerSystemCode'];
            $data['customerName'] = $customer_arr['customerName'];
            $data['customerAddress'] = $customer_arr['customerAddress1'] . ' ' . $customer_arr['customerAddress2'];
            $data['customerTelephone'] = $customer_arr['customerTelephone'];
            $data['customerFax'] = $customer_arr['customerFax'];
            $data['customerEmail'] = $customer_arr['customerEmail'];
            $data['customerreceivableAutoID'] = $customer_arr['receivableAutoID'];
            $data['customerreceivableSystemGLCode'] = $customer_arr['receivableSystemGLCode'];
            $data['customerreceivableGLAccount'] = $customer_arr['receivableGLAccount'];
            $data['customerreceivableDescription'] = $customer_arr['receivableDescription'];
            $data['customerreceivableType'] = $customer_arr['receivableType'];
            $data['customerCurrency'] = $customer_arr['customerCurrency'];
            $data['customerCurrencyID'] = $customer_arr['customerCurrencyID'];
            $data['customerCurrencyDecimalPlaces'] = $customer_arr['customerCurrencyDecimalPlaces'];

            $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
            $data['transactionCurrency'] = trim($currency_code);
            $data['transactionExchangeRate'] = 1;
            $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
            $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
            $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
            $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
            $data['companyLocalExchangeRate'] = $default_currency['conversion'];
            $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
            $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
            $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
            $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
            $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
            $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
            $customer_currency = currency_conversionID($data['transactionCurrencyID'], $data['customerCurrencyID']);
            $data['customerExchangeRate'] = $customer_currency['conversion'];
            $data['customerCurrencyDecimalPlaces'] = $customer_currency['DecimalPlaces'];
            $bank_currency = currency_conversionID($data['transactionCurrencyID'], $data['bankCurrencyID']);
            $data['bankCurrencyExchangeRate'] = $bank_currency['conversion'];
            $data['bankCurrencyDecimalPlaces'] = $bank_currency['DecimalPlaces'];

            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['RVcode'] = 0;

            $result=$this->db->insert('srp_erp_customerreceiptmaster', $data);
            $last_id = $this->db->insert_id();
            if ($result) {
                update_warehouse_items();
                update_item_master();

                $currencyID= $this->input->post('transactionCurrencyID');
                $customerID= $this->input->post('customerID');

                $this->db->select('transactionAmount');
                $this->db->from('srp_erp_customerinvoicemaster');
                $this->db->where('invoiceAutoID', $invoiceAutoID);
                $invAmount=$this->db->get()->row_array();

                $data = $this->db->query("SELECT srp_erp_customerinvoicemaster.invoiceAutoID, invoiceCode, receiptTotalAmount, advanceMatchedTotal, creditNoteTotalAmount, referenceNo, (( ( ( cid.transactionAmount - cid.totalAfterTax ) - ( ( ( IFNULL( gendiscount.discountPercentage, 0 ) / 100 ) * IFNULL(cid.transactionAmount, 0) ) )+ IFNULL( genexchargistax.transactionAmount, 0 ) ) * ( IFNULL(tax.taxPercentage, 0) / 100 ) + IFNULL(cid.transactionAmount, 0) ) - ( ( IFNULL( gendiscount.discountPercentage, 0 ) / 100 ) * IFNULL(cid.transactionAmount, 0) ) + IFNULL( genexcharg.transactionAmount, 0 )) AS transactionAmount, invoiceDate, slr.returnsalesvalue as salesreturnvalue FROM srp_erp_customerinvoicemaster LEFT JOIN ( SELECT invoiceAutoID, IFNULL(SUM(transactionAmount), 0) AS transactionAmount, IFNULL(SUM(totalAfterTax), 0) AS totalAfterTax FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID ) cid ON srp_erp_customerinvoicemaster.invoiceAutoID = cid.invoiceAutoID LEFT JOIN ( SELECT invoiceAutoID, SUM(taxPercentage) AS taxPercentage FROM srp_erp_customerinvoicetaxdetails GROUP BY invoiceAutoID ) tax ON tax.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT SUM(discountPercentage) AS discountPercentage, invoiceAutoID FROM srp_erp_customerinvoicediscountdetails GROUP BY invoiceAutoID ) gendiscount ON gendiscount.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT SUM(transactionAmount) AS transactionAmount, invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails WHERE isTaxApplicable = 1 GROUP BY invoiceAutoID ) genexchargistax ON genexchargistax.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT SUM(transactionAmount) AS transactionAmount, invoiceAutoID FROM srp_erp_customerinvoiceextrachargedetails GROUP BY invoiceAutoID ) genexcharg ON genexcharg.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID LEFT JOIN ( SELECT invoiceAutoID, IFNULL( SUM(slaesdetail.totalValue), 0 ) AS returnsalesvalue FROM srp_erp_salesreturndetails slaesdetail GROUP BY invoiceAutoID ) slr ON slr.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID WHERE confirmedYN = 1 AND approvedYN = 1 AND receiptInvoiceYN = 0 AND `customerID` = '{$customerID}' AND `transactionCurrencyID` = '{$currencyID}' AND invoiceDate <= '{$RVdate}' AND srp_erp_customerinvoicemaster.invoiceAutoID = $invoiceAutoID ")->row_array();
                $balance = $data['transactionAmount'] - ($data['receiptTotalAmount'] + $data['creditNoteTotalAmount'] + $data['advanceMatchedTotal'] + $data['salesreturnvalue']);

                if ($balance > 0) {
                    // echo $invAmount['transactionAmount'] .' | '.$balance;exit;
                    $receiptVoucherAutoID =  $last_id;
                    $settlementAmount =  0;
                    $this->db->select('customerReceivableAutoID,slr.returnsalesvalue as returnsalesvalue,companyLocalExchangeRate,companyReportingExchangeRate,customerCurrencyExchangeRate ,srp_erp_customerinvoicemaster.invoiceAutoID,invoiceCode,referenceNo,invoiceDate,invoiceNarration,(( ( ( cid.transactionAmount - cid.totalAfterTax ) - ( ( ( IFNULL( gendiscount.discountPercentage, 0 ) / 100 ) * IFNULL(cid.transactionAmount, 0) ) )+ IFNULL( genexchargistax.transactionAmount, 0 ) ) * ( IFNULL(tax.taxPercentage, 0) / 100 ) + IFNULL(cid.transactionAmount, 0) ) - ( ( IFNULL( gendiscount.discountPercentage, 0 ) / 100 ) * IFNULL(cid.transactionAmount, 0) ) + IFNULL( genexcharg.transactionAmount, 0 )) AS transactionAmount,receiptTotalAmount,advanceMatchedTotal,creditNoteTotalAmount,customerReceivableSystemGLCode,customerReceivableGLAccount,customerReceivableDescription,customerReceivableType,segmentID,segmentCode,transactionCurrencyDecimalPlaces');
                    $this->db->from('srp_erp_customerinvoicemaster');
                    $this->db->join('(SELECT invoiceAutoID,IFNULL(SUM( transactionAmount ),0) as transactionAmount,IFNULL(SUM(totalAfterTax ),0) as totalAfterTax FROM srp_erp_customerinvoicedetails GROUP BY invoiceAutoID) cid', 'srp_erp_customerinvoicemaster.invoiceAutoID = cid.invoiceAutoID', 'left');

                    $this->db->join('(SELECT
	invoiceAutoID,
	IFNULL( SUM(slaesdetail.totalValue), 0 ) AS returnsalesvalue
	from
	srp_erp_salesreturndetails slaesdetail
	GROUP BY invoiceAutoID) slr', 'srp_erp_customerinvoicemaster.invoiceAutoID = slr.invoiceAutoID', 'left');

                    $this->db->join('(SELECT
	SUM(discountPercentage) AS discountPercentage,
		invoiceAutoID
	from
	srp_erp_customerinvoicediscountdetails
	GROUP BY invoiceAutoID) gendiscount', 'srp_erp_customerinvoicemaster.invoiceAutoID = gendiscount.invoiceAutoID', 'left');


                    $this->db->join('(SELECT
	SUM(transactionAmount) AS transactionAmount,
		invoiceAutoID
	from
	srp_erp_customerinvoiceextrachargedetails
	WHERE
		isTaxApplicable = 1
	GROUP BY invoiceAutoID) genexchargistax', 'srp_erp_customerinvoicemaster.invoiceAutoID = genexchargistax.invoiceAutoID', 'left');


                    $this->db->join('(SELECT
	SUM(transactionAmount) AS transactionAmount,
		invoiceAutoID
	from
	srp_erp_customerinvoiceextrachargedetails
	GROUP BY invoiceAutoID) genexcharg', 'srp_erp_customerinvoicemaster.invoiceAutoID = genexcharg.invoiceAutoID', 'left');



                    $this->db->join('(SELECT invoiceAutoID,SUM(taxPercentage) as taxPercentage FROM srp_erp_customerinvoicetaxdetails GROUP BY invoiceAutoID) tax', 'tax.invoiceAutoID = srp_erp_customerinvoicemaster.invoiceAutoID', 'left');
                    $this->db->where_in('srp_erp_customerinvoicemaster.invoiceAutoID', $this->input->post('invoiceAutoID'));
                    $master_recode = $this->db->get()->result_array();
                    $amount = $balance;
                    for ($i = 0; $i < count($master_recode); $i++) {
                        $dataD[$i]['receiptVoucherAutoId'] = $last_id;
                        $dataD[$i]['invoiceAutoID'] = $master_recode[$i]['invoiceAutoID'];
                        $dataD[$i]['type'] = 'Invoice';
                        $dataD[$i]['invoiceCode'] = $master_recode[$i]['invoiceCode'];
                        $dataD[$i]['referenceNo'] = $master_recode[$i]['referenceNo'];
                        $dataD[$i]['invoiceDate'] = $master_recode[$i]['invoiceDate'];
                        $dataD[$i]['GLAutoID'] = $master_recode[$i]['customerReceivableAutoID'];
                        $dataD[$i]['systemGLCode'] = $master_recode[$i]['customerReceivableSystemGLCode'];
                        $dataD[$i]['GLCode'] = $master_recode[$i]['customerReceivableGLAccount'];
                        $dataD[$i]['GLDescription'] = $master_recode[$i]['customerReceivableDescription'];
                        $dataD[$i]['GLType'] = $master_recode[$i]['customerReceivableType'];
                        $dataD[$i]['description'] = $master_recode[$i]['invoiceNarration'];
                        $dataD[$i]['Invoice_amount'] = $master_recode[$i]['transactionAmount'];
                        $dataD[$i]['segmentID'] = $master_recode[$i]['segmentID'];
                        $dataD[$i]['segmentCode'] = $master_recode[$i]['segmentCode'];
                        $dataD[$i]['due_amount'] = ($master_recode[$i]['transactionAmount'] - ($master_recode[$i]['receiptTotalAmount'] + $master_recode[$i]['advanceMatchedTotal'] + $master_recode[$i]['creditNoteTotalAmount'] + $master_recode[$i]['returnsalesvalue']));
                        $dataD[$i]['balance_amount'] = ($dataD[$i]['due_amount'] - round($amount, $master_recode[$i]['transactionCurrencyDecimalPlaces']));
                        $dataD[$i]['transactionAmount'] = round($amount, $master_recode[$i]['transactionCurrencyDecimalPlaces']);
                        $dataD[$i]['companyLocalAmount'] = ($dataD[$i]['transactionAmount'] / $master_recode[$i]['companyLocalExchangeRate']);
                        $dataD[$i]['companyLocalExchangeRate'] = $master_recode[$i]['companyLocalExchangeRate'];
                        $dataD[$i]['companyReportingAmount'] = ($dataD[$i]['transactionAmount'] / $master_recode[$i]['companyReportingExchangeRate']);
                        $dataD[$i]['companyReportingExchangeRate'] = $master_recode[$i]['companyReportingExchangeRate'];
                        $dataD[$i]['customerAmount'] = ($dataD[$i]['transactionAmount'] / $master_recode[$i]['customerCurrencyExchangeRate']);
                        $dataD[$i]['customerCurrencyExchangeRate'] = $master_recode[$i]['customerCurrencyExchangeRate'];
                        $dataD[$i]['companyCode'] = $this->common_data['company_data']['company_code'];
                        $dataD[$i]['companyID'] = $this->common_data['company_data']['company_id'];
                        $dataD[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                        $dataD[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                        $dataD[$i]['modifiedUserName'] = $this->common_data['current_user'];
                        $dataD[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                        $dataD[$i]['createdUserGroup'] = $this->common_data['user_group'];
                        $dataD[$i]['createdPCID'] = $this->common_data['current_pc'];
                        $dataD[$i]['createdUserID'] = $this->common_data['current_userID'];
                        $dataD[$i]['createdUserName'] = $this->common_data['current_user'];
                        $dataD[$i]['createdDateTime'] = $this->common_data['current_date'];

                        $grv_m[$i]['invoiceAutoID'] = $invoiceAutoID;
                        $grv_m[$i]['receiptTotalAmount'] = ($master_recode[$i]['receiptTotalAmount'] + $amount);
                        $grv_m[$i]['receiptInvoiceYN'] = 0;
                        if ($dataD[$i]['balance_amount'] <= 0) {
                            $grv_m[$i]['receiptInvoiceYN'] = 1;
                        }
                    }
                    $data_up_settlement['settlementTotal'] =$settlementAmount;
                    $this->db->where('receiptVoucherAutoId', $receiptVoucherAutoID);
                    $this->db->update('srp_erp_customerreceiptmaster', $data_up_settlement);

                    if (!empty($dataD)) {
                        $this->db->update_batch('srp_erp_customerinvoicemaster', $grv_m, 'invoiceAutoID');
                        $this->db->insert_batch('srp_erp_customerreceiptdetail', $dataD);
                        return array('s', 'Receipt Voucher Saved Successfully.',$last_id);
                    } else {
                        $this->db->delete('srp_erp_customerreceiptmaster',array('receiptVoucherAutoId' => trim($last_id)));
                        return array('e', 'Receipt voucher not Created');
                    }

                }else{
                    $this->db->delete('srp_erp_customerreceiptmaster',array('receiptVoucherAutoId' => trim($last_id)));
                    return array('e', 'Balance amount should be greater than zero');
                }
            } else {
                return array('e', 'Receipt Voucher   Saved Failed ');
            }
        }else{
            return array('e', 'Receipt voucher date should be greater than or equal to invoice date');
        }
    }
}