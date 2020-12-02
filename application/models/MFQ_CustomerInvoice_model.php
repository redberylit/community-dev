<?php

class MFQ_CustomerInvoice_model extends ERP_Model
{
    function fetch_delivery_note()
    {
        $invoiceAutoID = $this->input->post('invoiceAutoID');
        $this->db->select('*,IFNULL(qty,0) as qty,IFNULL(unitPrice,0) as unitPrice');
        $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_deliverynote.jobID", "left");
        $this->db->join('srp_erp_mfq_itemmaster', "srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_job.mfqItemID", "left");
        $this->db->where('srp_erp_mfq_deliverynote.mfqCustomerAutoID', $this->input->post("mfqCustomerAutoID"));
        $this->db->where('srp_erp_mfq_deliverynote.confirmedYN', 1);
        $this->db->where('NOT EXISTS (SELECT *
                   FROM srp_erp_mfq_customerinvoicemaster
                   WHERE srp_erp_mfq_deliverynote.deliverNoteID = srp_erp_mfq_customerinvoicemaster.deliveryNoteID AND srp_erp_mfq_customerinvoicemaster.invoiceAutoID != ' . $invoiceAutoID . ')');
        $master = $this->db->get('srp_erp_mfq_deliverynote')->result_array();
        return $master;

    }

    function save_customer_invoice()
    {
        $last_id = "";
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $invoiceDate = input_format_date(trim($this->input->post('invoiceDate')), $date_format_policy);
        $invoiceDueDate = input_format_date(trim($this->input->post('invoiceDueDate')), $date_format_policy);
        $dueDate = input_format_date(trim($this->input->post('dueDate')), $date_format_policy);
        if (!$this->input->post('invoiceAutoID')) {
            $serialInfo = generateMFQ_SystemCode('srp_erp_mfq_customerinvoicemaster', 'invoiceAutoID', 'companyID');
            $codes = $this->sequence->sequence_generator('MCINV', $serialInfo['serialNo']);
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('serialNo', $serialInfo['serialNo']);
            $this->db->set('invoiceCode', $codes);
            $this->db->set('invoiceDate', $invoiceDate);
            $this->db->set('invoiceDueDate', $invoiceDueDate);
            $this->db->set('invoiceNarration', $this->input->post('invoiceNarration'));
            $this->db->set('deliveryNoteID', $this->input->post('deliveryNoteID'));

            $this->db->select("srp_erp_customermaster.customerCurrencyID,srp_erp_customermaster.customerCurrency");
            $this->db->from("srp_erp_mfq_customermaster");
            $this->db->join("srp_erp_customermaster", "srp_erp_mfq_customermaster.CustomerAutoID=srp_erp_customermaster.customerAutoID", "LEFT");
            $this->db->where("mfqCustomerAutoID", $this->input->post('mfqCustomerAutoID'));
            $custInfo = $this->db->get()->row_array();

            $this->db->set('customerCurrencyID', $custInfo["customerCurrencyID"]);
            $this->db->set('customerCurrency', $custInfo["customerCurrency"]);

            $customer_currency = currency_conversionID($this->input->post('currencyID'), $custInfo['customerCurrencyID']);
            $this->db->set('customerCurrencyExchangeRate', $customer_currency['conversion']);
            $this->db->set('customerCurrencyDecimalPlaces', $customer_currency['DecimalPlaces']);

            $this->db->set('transactionCurrencyID', $this->input->post('currencyID'));
            $this->db->set('transactionCurrency', null);
            $this->db->set('transactionExchangeRate', 1);
            $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->input->post('currencyID')));

            $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
            $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
            $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

            $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
            $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
            $reporting_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_reporting_currencyID']);
            $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
            $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

            $this->db->set('companyID', current_companyID());
            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('createdUserID', current_userID());
            $this->db->set('createdUserName', current_user());
            $this->db->set('createdDateTime', current_date(true));

            $result = $this->db->insert('srp_erp_mfq_customerinvoicemaster');
            $last_id = $this->db->insert_id();

        } else {
            $last_id = $this->input->post('invoiceAutoID');
            $this->db->set('mfqCustomerAutoID', $this->input->post('mfqCustomerAutoID'));
            $this->db->set('invoiceDate', $invoiceDate);
            $this->db->set('invoiceDueDate', $invoiceDueDate);
            $this->db->set('invoiceNarration', $this->input->post('invoiceNarration'));
            //$this->db->set('currencyID', $this->input->post('currencyID'));
            $this->db->set('deliveryNoteID', $this->input->post('deliveryNoteID'));

            $this->db->select("srp_erp_customermaster.customerCurrencyID,srp_erp_customermaster.customerCurrency");
            $this->db->from("srp_erp_mfq_customermaster");
            $this->db->join("srp_erp_customermaster", "srp_erp_mfq_customermaster.CustomerAutoID=srp_erp_customermaster.customerAutoID", "LEFT");
            $this->db->where("mfqCustomerAutoID", $this->input->post('mfqCustomerAutoID'));
            $custInfo = $this->db->get()->row_array();

            $this->db->set('customerCurrencyID', $custInfo["customerCurrencyID"]);
            $this->db->set('customerCurrency', $custInfo["customerCurrency"]);

            $customer_currency = currency_conversionID($this->input->post('currencyID'), $custInfo['customerCurrencyID']);
            $this->db->set('customerCurrencyExchangeRate', $customer_currency['conversion']);
            $this->db->set('customerCurrencyDecimalPlaces', $customer_currency['DecimalPlaces']);

            $this->db->set('transactionCurrencyID', $this->input->post('currencyID'));
            $this->db->set('transactionCurrency', null);
            $this->db->set('transactionExchangeRate', 1);
            $this->db->set('transactionCurrencyDecimalPlaces', fetch_currency_desimal_by_id($this->input->post('currencyID')));

            $this->db->set('companyLocalCurrencyID', $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('companyLocalCurrency', $this->common_data['company_data']['company_default_currency']);
            $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
            $this->db->set('companyLocalExchangeRate', $default_currency['conversion']);
            $this->db->set('companyLocalCurrencyDecimalPlaces', $default_currency['DecimalPlaces']);

            $this->db->set('companyReportingCurrency', $this->common_data['company_data']['company_reporting_currency']);
            $this->db->set('companyReportingCurrencyID', $this->common_data['company_data']['company_reporting_currencyID']);
            $reporting_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_reporting_currencyID']);
            $this->db->set('companyReportingExchangeRate', $reporting_currency['conversion']);
            $this->db->set('companyReportingCurrencyDecimalPlaces', $reporting_currency['DecimalPlaces']);

            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $this->db->set('modifiedUserID', current_userID());
            $this->db->set('modifiedUserName', current_user());
            $this->db->set('modifiedDateTime', current_date(true));

            $this->db->where('invoiceAutoID', $this->input->post('invoiceAutoID'));
            $result = $this->db->update('srp_erp_mfq_customerinvoicemaster');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Customer invoice failed ' . $this->db->_error_message());

        } else {
            $invoiceDetailID = $this->input->post('invoiceDetailsAutoID');
            $gl_code = $this->input->post('revenueGLAutoID');
            if (!empty($gl_code)) {
                foreach ($gl_code as $key => $val) {
                    if (!empty($invoiceDetailID[$key])) {
                        if (!empty($gl_code[$key])) {
                            $this->db->set('invoiceAutoID', $last_id);
                            $this->db->set('revenueGLAutoID', $this->input->post('revenueGLAutoID')[$key]);
                            $this->db->set('segmentID', $this->input->post('segmentID')[$key]);
                            $this->db->set('requestedQty', $this->input->post('requestedQty')[$key]);
                            $this->db->set('unitRate', $this->input->post('amount')[$key]);
                            $this->db->set('type', 1);
                            $amount = $this->input->post('requestedQty')[$key] * $this->input->post('amount')[$key];
                            $transactionAmount = $amount;
                            $this->db->set('transactionAmount', round($transactionAmount, fetch_currency_desimal_by_id($this->input->post('currencyID'))));
                            $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                            $companyLocalAmount = $transactionAmount / $default_currency['conversion'];
                            $this->db->set('companyLocalAmount', round($companyLocalAmount, $default_currency['DecimalPlaces']));
                            $reporting_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_reporting_currencyID']);
                            $companyReportingAmount = $transactionAmount / $reporting_currency['conversion'];
                            $this->db->set('companyReportingAmount', round($companyReportingAmount, $reporting_currency['DecimalPlaces']));

                            $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('modifiedUserID', current_userID());
                            $this->db->set('modifiedUserName', current_user());
                            $this->db->set('modifiedDateTime', current_date(true));
                            $this->db->where('invoiceDetailsAutoID', $invoiceDetailID[$key]);
                            $result = $this->db->update('srp_erp_mfq_customerinvoicedetails');
                        }
                    } else {
                        if (!empty($gl_code[$key])) {
                            $this->db->set('revenueGLAutoID', $this->input->post('revenueGLAutoID')[$key]);
                            $this->db->set('segmentID', $this->input->post('segmentID')[$key]);
                            $this->db->set('requestedQty', $this->input->post('requestedQty')[$key]);
                            $this->db->set('unitRate', $this->input->post('amount')[$key]);
                            $this->db->set('companyID', current_companyID());
                            $this->db->set('invoiceAutoID', $last_id);
                            $this->db->set('type', 1);

                            $amount = $this->input->post('requestedQty')[$key] * $this->input->post('amount')[$key];
                            $transactionAmount = $amount;
                            $this->db->set('transactionAmount', round($transactionAmount, fetch_currency_desimal_by_id($this->input->post('currencyID'))));
                            $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                            $companyLocalAmount = $transactionAmount / $default_currency['conversion'];
                            $this->db->set('companyLocalAmount', round($companyLocalAmount, $default_currency['DecimalPlaces']));
                            $reporting_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_reporting_currencyID']);
                            $companyReportingAmount = $transactionAmount / $reporting_currency['conversion'];
                            $this->db->set('companyReportingAmount', round($companyReportingAmount, $reporting_currency['DecimalPlaces']));

                            $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                            $this->db->set('createdUserID', current_userID());
                            $this->db->set('createdUserName', current_user());
                            $this->db->set('createdDateTime', current_date(true));
                            $result = $this->db->insert('srp_erp_mfq_customerinvoicedetails');
                        }
                    }
                }
            }

            if (!empty($this->input->post('itemInvoiceDetailsAutoID'))) {
                $this->db->select("srp_erp_itemmaster.*,srp_erp_mfq_itemmaster.unbilledServicesGLAutoID");
                $this->db->from("srp_erp_mfq_itemmaster");
                $this->db->join("srp_erp_itemmaster", "srp_erp_itemmaster.itemAutoID=srp_erp_mfq_itemmaster.itemAutoID", "LEFT");
                $this->db->where("mfqItemID", $this->input->post('itemAutoID'));
                $itemDet = $this->db->get()->row_array();

                $this->db->set('invoiceAutoID', $last_id);
                $this->db->set('itemAutoID', $this->input->post('itemAutoID'));
                $this->db->set('requestedQty', $this->input->post('expectedQty'));
                $this->db->set('unitRate', $this->input->post('unitRate'));
                $amount = $this->input->post('expectedQty') * $this->input->post('unitRate');
                $this->db->set('type', 2);
                $this->db->set('uomID', $itemDet['defaultUnitOfMeasureID']);
                $this->db->set('expenseGLAutoID', $itemDet['costGLAutoID']);
                if ($itemDet["mainCategory"] == "Service") {
                    $this->db->set('assetGLAutoID', $itemDet['unbilledServicesGLAutoID']);

                    $this->db->select('*,IFNULL(qty,0) as qty,IFNULL(unitPrice,0) as unitPrice');
                    $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_deliverynote.jobID", "left");
                    $this->db->where('srp_erp_mfq_deliverynote.deliverNoteID', $this->input->post('deliveryNoteID'));
                    $master = $this->db->get('srp_erp_mfq_deliverynote')->row_array();

                    $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                    $this->db->set('unitCost', $master["unitPrice"] * $default_currency['conversion']);
                    $this->db->set('totalCost', ($master["unitPrice"] * $default_currency['conversion'] * $master["qty"]));
                } else {
                    $this->db->set('assetGLAutoID', $itemDet['assteGLAutoID']);
                    $this->db->set('companyLocalWacAmount', $itemDet['companyLocalWacAmount']);
                    $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                    $this->db->set('unitCost', $itemDet['companyLocalWacAmount'] * $default_currency['conversion']);
                    $this->db->set('totalCost', ($itemDet['companyLocalWacAmount'] * $default_currency['conversion'] * $this->input->post('expectedQty')));
                }
                $this->db->set('revenueGLAutoID', $itemDet['revanueGLAutoID']);
                $this->db->set('revenueSystemGLCode', $itemDet['revanueSystemGLCode']);
                $this->db->set('revenueGLCode', $itemDet['revanueGLCode']);
                $this->db->set('revenueGLDescription', $itemDet['revanueDescription']);
                $this->db->set('revenueGLType', $itemDet['revanueType']);

                $transactionAmount = $amount;
                $this->db->set('transactionAmount', round($transactionAmount, fetch_currency_desimal_by_id($this->input->post('currencyID'))));
                $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                $companyLocalAmount = $transactionAmount / $default_currency['conversion'];
                $this->db->set('companyLocalAmount', round($companyLocalAmount, $default_currency['DecimalPlaces']));
                $reporting_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_reporting_currencyID']);
                $companyReportingAmount = $transactionAmount / $reporting_currency['conversion'];
                $this->db->set('companyReportingAmount', round($companyReportingAmount, $reporting_currency['DecimalPlaces']));

                $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $this->db->set('modifiedUserID', current_userID());
                $this->db->set('modifiedUserName', current_user());
                $this->db->set('modifiedDateTime', current_date(true));
                $this->db->where('invoiceDetailsAutoID', $this->input->post('itemInvoiceDetailsAutoID'));
                $result = $this->db->update('srp_erp_mfq_customerinvoicedetails');
            } else {

                $this->db->delete('srp_erp_mfq_customerinvoicedetails', 'invoiceAutoID=' . $last_id . ' AND type=2');

                $this->db->select("srp_erp_itemmaster.*,srp_erp_mfq_itemmaster.unbilledServicesGLAutoID");
                $this->db->from("srp_erp_mfq_itemmaster");
                $this->db->join("srp_erp_itemmaster", "srp_erp_itemmaster.itemAutoID=srp_erp_mfq_itemmaster.itemAutoID", "LEFT");
                $this->db->where("mfqItemID", $this->input->post('itemAutoID'));
                $itemDet = $this->db->get()->row_array();

                $this->db->set('revenueGLAutoID', $itemDet['revanueGLAutoID']);
                $this->db->set('revenueSystemGLCode', $itemDet['revanueSystemGLCode']);
                $this->db->set('revenueGLCode', $itemDet['revanueGLCode']);
                $this->db->set('revenueGLDescription', $itemDet['revanueDescription']);
                $this->db->set('revenueGLType', $itemDet['revanueType']);

                $this->db->set('itemAutoID', $this->input->post('itemAutoID'));
                $this->db->set('requestedQty', $this->input->post('expectedQty'));
                $this->db->set('unitRate', $this->input->post('unitRate'));
                $this->db->set('companyID', current_companyID());
                $this->db->set('invoiceAutoID', $last_id);
                $this->db->set('type', 2);
                $this->db->set('uomID', $itemDet['defaultUnitOfMeasureID']);
                $this->db->set('expenseGLAutoID', $itemDet['costGLAutoID']);
                if ($itemDet["mainCategory"] == "Service") {
                    $this->db->set('assetGLAutoID', $itemDet['unbilledServicesGLAutoID']);
                    $this->db->select('*,IFNULL(qty,0) as qty,IFNULL(unitPrice,0) as unitPrice');
                    $this->db->join('srp_erp_mfq_job', "srp_erp_mfq_job.workProcessID = srp_erp_mfq_deliverynote.jobID", "left");
                    $this->db->where('srp_erp_mfq_deliverynote.deliverNoteID', $this->input->post('deliveryNoteID'));
                    $master = $this->db->get('srp_erp_mfq_deliverynote')->row_array();

                    $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                    $this->db->set('unitCost', $master["unitPrice"]);
                    $this->db->set('totalCost', ($master["unitPrice"] * $master["qty"]));

                    $this->db->set('companyLocalWacAmount', $master["unitPrice"] * $default_currency['conversion']);
                } else {
                    $this->db->set('assetGLAutoID', $itemDet['assteGLAutoID']);
                    $this->db->set('companyLocalWacAmount', $itemDet['companyLocalWacAmount']);
                    $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                    $this->db->set('unitCost', $itemDet['companyLocalWacAmount'] * $default_currency['conversion']);
                    $this->db->set('totalCost', ($itemDet['companyLocalWacAmount'] * $default_currency['conversion'] * $this->input->post('expectedQty')));
                }

                $amount = $this->input->post('expectedQty') * $this->input->post('unitRate');
                $transactionAmount = $amount;
                $this->db->set('transactionAmount', round($transactionAmount, fetch_currency_desimal_by_id($this->input->post('currencyID'))));
                $default_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_default_currencyID']);
                $companyLocalAmount = $transactionAmount / $default_currency['conversion'];
                $this->db->set('companyLocalAmount', round($companyLocalAmount, $default_currency['DecimalPlaces']));
                $reporting_currency = currency_conversionID($this->input->post('currencyID'), $this->common_data['company_data']['company_reporting_currencyID']);
                $companyReportingAmount = $transactionAmount / $reporting_currency['conversion'];
                $this->db->set('companyReportingAmount', round($companyReportingAmount, $reporting_currency['DecimalPlaces']));

                $this->db->set('createdPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserName', current_user());
                $this->db->set('createdDateTime', current_date(true));
                $result = $this->db->insert('srp_erp_mfq_customerinvoicedetails');
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Invoice Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Invoice Saved Successfully.', $last_id);
            }
        }
    }

    function load_mfq_customerInvoice()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(invoiceDate,\'' . $convertFormat . '\') AS invoiceDate,DATE_FORMAT(invoiceDueDate,\'' . $convertFormat . '\') AS invoiceDueDate');
        $this->db->where('invoiceAutoID', $this->input->post("invoiceAutoID"));
        $master = $this->db->get('srp_erp_mfq_customerinvoicemaster')->row_array();
        return $master;

    }

    function load_mfq_customerinvoicedetail()
    {
        $this->db->select('srp_erp_mfq_customerinvoicedetails.*,CONCAT(GLSecondaryCode," | ",GLDescription," | ",subCategory ) as GLDescription,CONCAT(srp_erp_mfq_itemmaster.itemSystemCode," - ",srp_erp_mfq_itemmaster.itemDescription) as itemDescription,srp_erp_mfq_itemmaster.defaultUnitOfMeasure');
        $this->db->where('invoiceAutoID', $this->input->post("invoiceAutoID"));
        $this->db->join('srp_erp_chartofaccounts', 'srp_erp_chartofaccounts.GLAutoID = srp_erp_mfq_customerinvoicedetails.revenueGLAutoID', 'left');
        $this->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_customerinvoicedetails.itemAutoID', 'left');
        $detail = $this->db->get('srp_erp_mfq_customerinvoicedetails')->result_array();
        return $detail;

    }

    function fetch_double_entry_mfq_customerInvoice($invoiceAutoID)
    {
        $gl_array = array();
        $gl_array['gl_detail'] = array();

        $invoiceMaster = $this->db->query("SELECT cinm.invoiceAutoID,cinm.transactionCurrencyID,currency.CurrencyCode as transactionCurrency,cinm.transactionExchangeRate,cinm.transactionCurrencyDecimalPlaces,cinm.companyLocalCurrencyID,cinm.companyLocalCurrency,cinm.companyLocalExchangeRate,cinm.companyLocalCurrencyDecimalPlaces,cinm.companyReportingCurrencyID,companyReportingCurrency,cinm.companyReportingExchangeRate,cinm.companyReportingCurrencyDecimalPlaces,detail.detailAmount,cinm.invoiceCode,cinm.invoiceDate,cinm.companyFinanceYear,cinm.FYPeriodDateFrom,cinm.FYPeriodDateTo,cinm.mfqCustomerAutoID,cinm.documentID,cinm.invoiceNarration,cinm.confirmedByEmpID,cinm.confirmedDate,cinm.confirmedByName,cinm.approvedDate,cinm.approvedDate,cinm.approvedbyEmpID,cinm.approvedbyEmpName,cinm.companyID FROM srp_erp_mfq_customerinvoicemaster cinm LEFT JOIN (SELECT SUM(transactionAmount) AS detailAmount,invoiceAutoID FROM srp_erp_mfq_customerinvoicedetails WHERE invoiceAutoID = $invoiceAutoID) detail ON cinm.invoiceAutoID = detail.invoiceAutoID INNER JOIN srp_erp_currencymaster currency ON cinm.transactionCurrencyID = currency.currencyID WHERE cinm.invoiceAutoID = $invoiceAutoID")->row_array();

        $this->db->select('cm.CustomerAutoID,ca.GLAutoID,ca.systemAccountCode,ca.GLSecondaryCode,ca.GLDescription,ca.subCategory,cm.CustomerSystemCode,cm.customerCurrencyID,cm.customerCurrency,cm.customerCurrencyDecimalPlaces,cm.CustomerName');
        $this->db->where('mfqCustomerAutoID', $invoiceMaster['mfqCustomerAutoID']);
        $this->db->from('srp_erp_mfq_customermaster mcm');
        $this->db->join('srp_erp_customermaster cm', 'mcm.CustomerAutoID = cm.customerAutoID', 'left');
        $this->db->join('srp_erp_chartofaccounts ca', 'ca.GLAutoID = cm.receivableAutoID', 'left');
        $customerMaster = $this->db->get()->row_array();

        $globalArray = array();
        /*creditGL*/
        if ($customerMaster) {
            $data_arr['auto_id'] = $invoiceAutoID;
            $data_arr['gl_auto_id'] = $customerMaster['GLAutoID'];
            $data_arr['gl_code'] = $customerMaster['systemAccountCode'];
            $data_arr['secondary'] = $customerMaster['GLSecondaryCode'];
            $data_arr['gl_desc'] = $customerMaster['GLDescription'];
            $data_arr['gl_type'] = $customerMaster['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Customer';
            $data_arr['partyAutoID'] = $customerMaster['CustomerAutoID'];
            $data_arr['partySystemCode'] = $customerMaster['CustomerSystemCode'];
            $data_arr['partyName'] = $customerMaster['CustomerName'];
            $data_arr['partyCurrencyID'] = $customerMaster['customerCurrencyID'];
            $data_arr['partyCurrency'] = $customerMaster['customerCurrency'];
            $data_arr['companyLocalExchangeRate'] = $invoiceMaster['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $invoiceMaster['companyReportingExchangeRate'];
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['partyExchangeRate'] = 1;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = $customerMaster['customerCurrencyDecimalPlaces'];
            $data_arr['gl_dr'] = $invoiceMaster['detailAmount'];
            $data_arr['gl_cr'] = '';
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);

        }

        /*item revenue*/
        $creditGL = $this->db->query("SELECT cid.invoiceAutoID,sum(cid.transactionAmount) AS transactionTotal,srp_erp_segment.segmentID,srp_erp_segment.segmentCode,ca.GLAutoID,ca.systemAccountCode,ca.GLSecondaryCode,ca.GLDescription,ca.subCategory FROM srp_erp_mfq_customerinvoicedetails cid INNER JOIN srp_erp_chartofaccounts ca ON ca.GLAutoID = cid.revenueGLAutoID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_segment.mfqSegmentID = cid.segmentID LEFT JOIN srp_erp_segment ON  srp_erp_segment.segmentID = srp_erp_mfq_segment.segmentID WHERE cid.invoiceAutoID = $invoiceAutoID AND type=1 GROUP BY revenueGLAutoID")->result_array();
        if ($creditGL) {
            foreach ($creditGL as $credit) {
                $data_arr['auto_id'] = $invoiceAutoID;
                $data_arr['gl_auto_id'] = $credit['GLAutoID'];
                $data_arr['gl_code'] = $credit['systemAccountCode'];
                $data_arr['secondary'] = $credit['GLSecondaryCode'];
                $data_arr['gl_desc'] = $credit['GLDescription'];
                $data_arr['gl_type'] = $credit['subCategory'];
                $data_arr['segment_id'] = $credit['segmentID'];
                $data_arr['segment'] = $credit['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $customerMaster['CustomerAutoID'];
                $data_arr['partySystemCode'] = $customerMaster['CustomerSystemCode'];
                $data_arr['partyName'] = $customerMaster['CustomerName'];
                $data_arr['partyCurrencyID'] = $customerMaster['customerCurrencyID'];
                $data_arr['partyCurrency'] = $customerMaster['customerCurrency'];
                $data_arr['companyLocalExchangeRate'] = $invoiceMaster['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $invoiceMaster['companyReportingExchangeRate'];
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $customerMaster['customerCurrencyDecimalPlaces'];
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $credit['transactionTotal'];
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }
        /*item revenue*/
        $creditGL = $this->db->query("SELECT cid.invoiceAutoID,sum(cid.transactionAmount) AS transactionTotal,srp_erp_segment.segmentID,srp_erp_segment.segmentCode,ca.GLAutoID,ca.systemAccountCode,ca.GLSecondaryCode,ca.GLDescription,ca.subCategory FROM srp_erp_mfq_customerinvoicedetails cid INNER JOIN srp_erp_chartofaccounts ca ON ca.GLAutoID = cid.revenueGLAutoID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_segment.mfqSegmentID = cid.segmentID LEFT JOIN srp_erp_segment ON  srp_erp_segment.segmentID = srp_erp_mfq_segment.segmentID WHERE cid.invoiceAutoID = $invoiceAutoID AND type=2 GROUP BY revenueGLAutoID")->result_array();
        if ($creditGL) {
            foreach ($creditGL as $credit) {
                $data_arr['auto_id'] = $invoiceAutoID;
                $data_arr['gl_auto_id'] = $credit['GLAutoID'];
                $data_arr['gl_code'] = $credit['systemAccountCode'];
                $data_arr['secondary'] = $credit['GLSecondaryCode'];
                $data_arr['gl_desc'] = $credit['GLDescription'];
                $data_arr['gl_type'] = $credit['subCategory'];
                $data_arr['segment_id'] = $credit['segmentID'];
                $data_arr['segment'] = $credit['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $customerMaster['CustomerAutoID'];
                $data_arr['partySystemCode'] = $customerMaster['CustomerSystemCode'];
                $data_arr['partyName'] = $customerMaster['CustomerName'];
                $data_arr['partyCurrencyID'] = $customerMaster['customerCurrencyID'];
                $data_arr['partyCurrency'] = $customerMaster['customerCurrency'];
                $data_arr['companyLocalExchangeRate'] = $invoiceMaster['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $invoiceMaster['companyReportingExchangeRate'];
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $customerMaster['customerCurrencyDecimalPlaces'];
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $credit['transactionTotal'];
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        /*item expense*/
        $creditGL = $this->db->query("SELECT cid.invoiceAutoID,sum(cid.totalCost) AS transactionTotal,srp_erp_segment.segmentID,srp_erp_segment.segmentCode,ca.GLAutoID,ca.systemAccountCode,ca.GLSecondaryCode,ca.GLDescription,ca.subCategory FROM srp_erp_mfq_customerinvoicedetails cid INNER JOIN srp_erp_chartofaccounts ca ON ca.GLAutoID = cid.expenseGLAutoID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_segment.mfqSegmentID = cid.segmentID LEFT JOIN srp_erp_segment ON  srp_erp_segment.segmentID = srp_erp_mfq_segment.segmentID WHERE cid.invoiceAutoID = $invoiceAutoID AND type=2 GROUP BY expenseGLAutoID")->result_array();
        if ($creditGL) {
            foreach ($creditGL as $credit) {
                $data_arr['auto_id'] = $invoiceAutoID;
                $data_arr['gl_auto_id'] = $credit['GLAutoID'];
                $data_arr['gl_code'] = $credit['systemAccountCode'];
                $data_arr['secondary'] = $credit['GLSecondaryCode'];
                $data_arr['gl_desc'] = $credit['GLDescription'];
                $data_arr['gl_type'] = $credit['subCategory'];
                $data_arr['segment_id'] = $credit['segmentID'];
                $data_arr['segment'] = $credit['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $customerMaster['CustomerAutoID'];
                $data_arr['partySystemCode'] = $customerMaster['CustomerSystemCode'];
                $data_arr['partyName'] = $customerMaster['CustomerName'];
                $data_arr['partyCurrencyID'] = $customerMaster['customerCurrencyID'];
                $data_arr['partyCurrency'] = $customerMaster['customerCurrency'];
                $data_arr['companyLocalExchangeRate'] = $invoiceMaster['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $invoiceMaster['companyReportingExchangeRate'];
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $customerMaster['customerCurrencyDecimalPlaces'];
                $data_arr['gl_dr'] = $credit['transactionTotal'];;
                $data_arr['gl_cr'] = '';
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        /*item asset*/
        $creditGL = $this->db->query("SELECT cid.invoiceAutoID,sum(cid.totalCost) AS transactionTotal,srp_erp_segment.segmentID,srp_erp_segment.segmentCode,ca.GLAutoID,ca.systemAccountCode,ca.GLSecondaryCode,ca.GLDescription,ca.subCategory FROM srp_erp_mfq_customerinvoicedetails cid INNER JOIN srp_erp_chartofaccounts ca ON ca.GLAutoID = cid.assetGLAutoID LEFT JOIN srp_erp_mfq_segment ON srp_erp_mfq_segment.mfqSegmentID = cid.segmentID LEFT JOIN srp_erp_segment ON  srp_erp_segment.segmentID = srp_erp_mfq_segment.segmentID WHERE cid.invoiceAutoID = $invoiceAutoID AND type=2 GROUP BY assetGLAutoID")->result_array();
        if ($creditGL) {
            foreach ($creditGL as $credit) {
                $data_arr['auto_id'] = $invoiceAutoID;
                $data_arr['gl_auto_id'] = $credit['GLAutoID'];
                $data_arr['gl_code'] = $credit['systemAccountCode'];
                $data_arr['secondary'] = $credit['GLSecondaryCode'];
                $data_arr['gl_desc'] = $credit['GLDescription'];
                $data_arr['gl_type'] = $credit['subCategory'];
                $data_arr['segment_id'] = $credit['segmentID'];
                $data_arr['segment'] = $credit['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Customer';
                $data_arr['partyAutoID'] = $customerMaster['CustomerAutoID'];
                $data_arr['partySystemCode'] = $customerMaster['CustomerSystemCode'];
                $data_arr['partyName'] = $customerMaster['CustomerName'];
                $data_arr['partyCurrencyID'] = $customerMaster['customerCurrencyID'];
                $data_arr['partyCurrency'] = $customerMaster['customerCurrency'];
                $data_arr['companyLocalExchangeRate'] = $invoiceMaster['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $invoiceMaster['companyReportingExchangeRate'];
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['partyExchangeRate'] = 1;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $customerMaster['customerCurrencyDecimalPlaces'];
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $credit['transactionTotal'];
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        $gl_array['currency'] = $invoiceMaster['transactionCurrency'];
        $gl_array['decimal_places'] = $invoiceMaster['transactionCurrencyDecimalPlaces'];
        $gl_array['code'] = 'MCINV';
        $gl_array['name'] = 'Customer Invoice';
        $gl_array['primary_Code'] = $invoiceMaster['invoiceCode'];
        $gl_array['date'] = $invoiceMaster['invoiceDate'];
        $gl_array['finance_year'] = $invoiceMaster['companyFinanceYear'];
        $gl_array['finance_period'] = $invoiceMaster['FYPeriodDateFrom'] . ' - ' . $invoiceMaster['FYPeriodDateTo'];
        $gl_array['master_data'] = $invoiceMaster;
        $gl_array['gl_detail'] = $globalArray;

        return $gl_array;
    }

    function customer_invoice_confirmation()
    {
        $this->db->trans_start();
        $invoiceAutoID = trim($this->input->post('invoiceAutoID'));
        $this->db->select('*');
        $this->db->where('invoiceAutoID', $invoiceAutoID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_mfq_customerinvoicemaster');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {

            $this->db->select('*');
            $this->db->where('invoiceAutoID', $invoiceAutoID);
            $this->db->from('srp_erp_mfq_customerinvoicemaster');
            $invoiceMaster = $this->db->get()->row_array();

            $this->db->select("*");
            $this->db->from('srp_erp_companyfinanceperiod');
            $this->db->join('srp_erp_companyfinanceyear', "srp_erp_companyfinanceyear.companyFinanceYearID=srp_erp_companyfinanceperiod.companyFinanceYearID", "LEFT");
            $this->db->where('srp_erp_companyfinanceperiod.companyID', $this->common_data['company_data']['company_id']);
            $this->db->where("'{$invoiceMaster['invoiceDate']}' BETWEEN dateFrom AND dateTo");
            $this->db->where("srp_erp_companyfinanceperiod.isActive", 1);
            $financePeriod = $this->db->get()->row_array();

            if ($financePeriod) {
                $this->db->set('confirmedYN', 1);
                $this->db->set('confirmedByEmpID', current_userID());
                $this->db->set('confirmedByName', current_user());
                $this->db->set('confirmedDate', current_date(false));
                $this->db->where('invoiceAutoID', $invoiceAutoID);
                $result = $this->db->update('srp_erp_mfq_customerinvoicemaster');

                if ($result) {
                    /*$gearsDB = $this->load->database('gearserp', TRUE);

                    $gearserpCustomerInvoice = $gearsDB->query("SELECT max(serialNo) as serialNo FROM erp_custinvoicedirect WHERE companyID = 'HEMT'")->row_array();

                    $smeCustomerInvoice = $this->db->query("SELECT cinm.*,detail.detailAmount FROM srp_erp_mfq_customerinvoicemaster cinm LEFT JOIN (SELECT SUM(transactionAmount) AS detailAmount,invoiceAutoID FROM srp_erp_mfq_customerinvoicedetails WHERE invoiceAutoID = {$invoiceAutoID}) detail ON cinm.invoiceAutoID = detail.invoiceAutoID WHERE cinm.invoiceAutoID = {$invoiceAutoID}")->row_array();

                    $smeCustomerInvoiceDetail = $this->db->query("SELECT cid.revenueGLAutoID as glCode,ca.GLDescription as glDescription,ca.masterCategory as glType,segment.segmentCode as mSegmentCode,cid.requestedQty as requestedQty,unitRate,transactionAmount,companyLocalAmount,companyReportingAmount FROM srp_erp_mfq_customerinvoicedetails cid LEFT JOIN srp_erp_chartofaccounts ca ON ca.GLAutoID = cid.revenueGLAutoID LEFT JOIN srp_erp_mfq_segment segment ON segment.mfqSegmentID = cid.segmentID WHERE invoiceAutoID = {$invoiceAutoID}")->result_array();

                    $smeMFQCustomerMaster = $this->db->query("SELECT CustomerAutoID FROM srp_erp_mfq_customermaster WHERE mfqCustomerAutoID = {$smeCustomerInvoice['mfqCustomerAutoID']}")->row_array();

                    $gearserpCustomerDetail = $gearsDB->query("SELECT custGLaccount FROM customermaster WHERE customerCodeSystem = {$smeMFQCustomerMaster['CustomerAutoID']}")->row_array();

                    $gearserpFinanceYear = $gearsDB->query("SELECT companyfinanceperiod.companyFinanceYearID,bigginingDate,endingDate,dateFrom,dateTo,companyFinancePeriodID FROM companyfinanceperiod LEFT JOIN companyfinanceyear ON companyfinanceperiod.companyFinanceYearID = companyfinanceyear.companyFinanceYearID WHERE '{$smeCustomerInvoice['invoiceDate']}' BETWEEN dateFrom AND dateTo AND companyfinanceperiod.companyID='HEMT' AND departmentID = 'AR'")->row_array();

                    $newSerialNumber = $gearserpCustomerInvoice['serialNo'] + 1;
                    $systemCode = "HEMT" . '\\' . date("Y") . '\\' . "INV" . str_pad($newSerialNumber, 6, '0', STR_PAD_LEFT);

                    $data['companyID'] = 'HEMT';
                    $data['documentID'] = 'INV';
                    $data['serialNo'] = $newSerialNumber;
                    $data['bookingInvCode'] = $systemCode;
                    $data['bookingDate'] = $smeCustomerInvoice['invoiceDate'];
                    $data['comments'] = $smeCustomerInvoice['invoiceNarration'];
                    $data['invoiceDueDate'] = $smeCustomerInvoice['invoiceDueDate'];
                    $data['customerID'] = $smeMFQCustomerMaster['CustomerAutoID'];
                    $data['customerGLCode'] = $gearserpCustomerDetail['custGLaccount'];
                    $data['custTransactionCurrencyID'] = $smeCustomerInvoice['transactionCurrencyID'];
                    $data['custTransactionCurrencyER'] = 1;
                    $data['companyReportingCurrencyID'] = $smeCustomerInvoice['companyReportingCurrencyID'];
                    $data['companyReportingER'] = $smeCustomerInvoice['companyReportingExchangeRate'];
                    $data['localCurrencyID'] = $smeCustomerInvoice['transactionCurrencyID'];
                    $data['localCurrencyER'] = 1;
                    $data['bookingAmountTrans'] = $smeCustomerInvoice['detailAmount'];
                    $data['isPerforma'] = 0;
                    $data['bookingAmountLocal'] = ($smeCustomerInvoice['detailAmount'] / $data['localCurrencyER']);
                    $data['bookingAmountRpt'] = ($smeCustomerInvoice['detailAmount'] / $data['companyReportingER']);
                    $data['companyFinanceYearID'] = $gearserpFinanceYear["companyFinanceYearID"];
                    $data['FYBiggin'] = $gearserpFinanceYear["bigginingDate"];
                    $data['FYEnd'] = $gearserpFinanceYear["endingDate"];
                    $data['companyFinancePeriodID'] = $gearserpFinanceYear["companyFinancePeriodID"];
                    $data['FYPeriodDateFrom'] = $gearserpFinanceYear["dateFrom"];
                    $data['FYPeriodDateTo'] = $gearserpFinanceYear["dateTo"];

                    $gearsDB->insert('erp_custinvoicedirect', $data);
                    $smeCustomerInvoiceMaster_id = $gearsDB->insert_id();

                    if ($smeCustomerInvoiceMaster_id) {
                        if (!empty($smeCustomerInvoiceDetail)) {
                            foreach ($smeCustomerInvoiceDetail as $row) {
                                $data_detail['custInvoiceDirectID'] = $smeCustomerInvoiceMaster_id;
                                $data_detail['companyID'] = 'HEMT';
                                $data_detail['serviceLineCode'] = $row['mSegmentCode'];
                                $data_detail['customerID'] = $smeMFQCustomerMaster['CustomerAutoID'];
                                $data_detail['glCode'] = $row['glCode'];
                                $data_detail['glCodeDes'] = $row['glDescription'];
                                $data_detail['accountType'] = $row['glType'];
                                $data_detail['comments'] = $smeCustomerInvoice['invoiceNarration'];
                                $data_detail['invoiceAmountCurrency'] = $smeCustomerInvoice['transactionCurrencyID'];
                                $data_detail['invoiceAmountCurrencyER'] = $smeCustomerInvoice['transactionExchangeRate'];
                                $data_detail['invoiceQty'] = $row['requestedQty'];
                                $data_detail['unitCost'] = $row['unitRate'];
                                $data_detail['invoiceAmount'] = $row['transactionAmount'];
                                $data_detail['localCurrency'] = $smeCustomerInvoice['companyLocalCurrencyID'];
                                $data_detail['localCurrencyER'] = $smeCustomerInvoice['companyLocalExchangeRate'];
                                $data_detail['localAmount'] = $row['companyLocalAmount'];
                                $data_detail['comRptCurrency'] = $smeCustomerInvoice['companyReportingCurrencyID'];
                                $data_detail['comRptCurrencyER'] = $smeCustomerInvoice['companyReportingExchangeRate'];
                                $data_detail['comRptAmount'] = $row['companyReportingAmount'];
                                $gearsDB->insert('erp_custinvoicedirectdet', $data_detail);
                            }
                        }
                    }*/

                    $smeCustomerInvoice = $this->db->query("SELECT cinm.*,detail.detailAmount FROM srp_erp_mfq_customerinvoicemaster cinm LEFT JOIN (SELECT SUM(transactionAmount) AS detailAmount,invoiceAutoID FROM srp_erp_mfq_customerinvoicedetails WHERE invoiceAutoID = {$invoiceAutoID}) detail ON cinm.invoiceAutoID = detail.invoiceAutoID LEFT JOIN srp_erp_mfq_deliverynote ON srp_erp_mfq_deliverynote.deliverNoteID = cinm.deliveryNoteID WHERE cinm.invoiceAutoID = {$invoiceAutoID}")->row_array();

                    $smeCustomerInvoiceDetail = $this->db->query("SELECT cid.revenueGLAutoID as glCode,ca.GLDescription as glDescription,ca.subCategory as glType,segment.segmentCode as mSegmentCode,cid.requestedQty as requestedQty,unitRate,transactionAmount,companyLocalAmount,companyReportingAmount,ca.GLSecondaryCode,ca.systemAccountCode,itm.*,cid.type,cid.assetGLAutoID as asstglCode,asst.GLDescription as asstglDescription,asst.GLSecondaryCode as asstGLSecondaryCode,asst.systemAccountCode as asstsystemAccountCode,asst.subCategory as asstglType
FROM srp_erp_mfq_customerinvoicedetails cid
LEFT JOIN srp_erp_chartofaccounts ca ON ca.GLAutoID = cid.revenueGLAutoID
LEFT JOIN srp_erp_chartofaccounts asst ON asst.GLAutoID = cid.assetGLAutoID
LEFT JOIN srp_erp_mfq_segment segment ON segment.mfqSegmentID = cid.segmentID
LEFT JOIN (SELECT srp_erp_itemmaster.*,srp_erp_mfq_itemmaster.mfqItemID
FROM srp_erp_mfq_itemmaster
INNER JOIN srp_erp_itemmaster ON srp_erp_itemmaster.itemAutoID =  srp_erp_mfq_itemmaster.itemAutoID) itm ON itm.mfqItemID = cid.itemAutoID
WHERE invoiceAutoID = {$invoiceAutoID}")->result_array();

                    $smeMFQCustomerMaster = $this->db->query("SELECT CustomerAutoID FROM srp_erp_mfq_customermaster WHERE mfqCustomerAutoID = {$smeCustomerInvoice['mfqCustomerAutoID']}")->row_array();

                    $jobDetail = $this->db->query("SELECT srp_erp_mfq_segment.*,srp_erp_segment.segmentCode as segCode,srp_erp_warehousemaster.* FROM srp_erp_mfq_deliverynote INNER JOIN srp_erp_mfq_job ON workProcessID = jobID LEFT JOIN srp_erp_mfq_segment ON  srp_erp_mfq_job.mfqSegmentID = srp_erp_mfq_segment.mfqSegmentID LEFT JOIN srp_erp_segment ON srp_erp_mfq_segment.segmentID = srp_erp_segment.segmentID LEFT JOIN srp_erp_mfq_warehousemaster ON  srp_erp_mfq_warehousemaster.mfqWarehouseAutoID = srp_erp_mfq_job.mfqWarehouseAutoID LEFT JOIN srp_erp_warehousemaster ON srp_erp_mfq_warehousemaster.warehouseAutoID = srp_erp_warehousemaster.wareHouseAutoID  WHERE deliverNoteID = {$smeCustomerInvoice['deliveryNoteID']}")->row_array();

                    $this->load->library('sequence');

                    $this->db->select('*');
                    $this->db->from('srp_erp_customermaster');
                    $customer_arr = $this->db->where('customerAutoID', $smeMFQCustomerMaster['CustomerAutoID'])->get()->row_array();

                    $data['companyID'] = $smeCustomerInvoice['companyID'];
                    $data['invoiceType'] = 'Direct';
                    $data['documentID'] = 'CINV';
                    $data['invoiceDate'] = $smeCustomerInvoice['invoiceDate'];
                    $data['invoiceNarration'] = $smeCustomerInvoice['invoiceNarration'];
                    $data['invoiceDueDate'] = $smeCustomerInvoice['invoiceDueDate'];
                    $data['customerInvoiceDate'] = $smeCustomerInvoice['invoiceDate'];

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

                    $data['transactionCurrencyID'] = $smeCustomerInvoice['transactionCurrencyID'];
                    $data['transactionCurrency'] = fetch_currency_code($smeCustomerInvoice['transactionCurrencyID']);
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

                    $data['createdUserGroup'] = $this->common_data['user_group'];
                    $data['createdPCID'] = $this->common_data['current_pc'];
                    $data['createdUserID'] = $this->common_data['current_userID'];
                    $data['createdUserName'] = $this->common_data['current_user'];
                    $data['createdDateTime'] = $this->common_data['current_date'];

                    $data['companyFinanceYearID'] = $financePeriod["companyFinanceYearID"];
                    $data['FYBegin'] = trim($financePeriod["beginingDate"]);
                    $data['FYEnd'] = trim($financePeriod["endingDate"]);
                    $data['companyFinanceYear'] = trim($financePeriod["beginingDate"]) . ' - ' . trim($financePeriod["endingDate"]);
                    $data['companyFinancePeriodID'] = $financePeriod['companyFinancePeriodID'];

                    $data['segmentID'] = trim($jobDetail['segmentID']);
                    $data['segmentCode'] = trim($jobDetail['segCode']);
                    $data['invoiceType'] = 'Manufacturing';

                    $data['invoiceCode'] = $this->sequence->sequence_generator($data['documentID']);
                    $data['timestamp'] = current_date();

                    $this->db->insert('srp_erp_customerinvoicemaster', $data);
                    $smeCustomerInvoiceMaster_id = $this->db->insert_id();

                    if ($smeCustomerInvoiceMaster_id) {
                        if (!empty($smeCustomerInvoiceDetail)) {
                            foreach ($smeCustomerInvoiceDetail as $row) {
                                if ($row["type"] == 1) {
                                    $data_detail['invoiceAutoID'] = $smeCustomerInvoiceMaster_id;
                                    $data_detail['type'] = 'GL';
                                    $data_detail['revenueGLAutoID'] = $row['glCode'];
                                    $data_detail['revenueGLCode'] = $row['GLSecondaryCode'];
                                    $data_detail['revenueSystemGLCode'] = $row['systemAccountCode'];
                                    $data_detail['revenueGLDescription'] = $row['glDescription'];
                                    $data_detail['revenueGLType'] = $row['glType'];
                                    $data_detail['description'] = $smeCustomerInvoice['invoiceNarration'];

                                    $data_detail['transactionAmount'] = $row["transactionAmount"];
                                    $data_detail['companyLocalAmount'] = $row["companyLocalAmount"];
                                    $data_detail['companyReportingAmount'] = $row["companyReportingAmount"];
                                    $customerAmount = 0;
                                    if ($smeCustomerInvoice['customerCurrencyExchangeRate']) {
                                        $customerAmount = $data_detail['transactionAmount'] / $smeCustomerInvoice['customerCurrencyExchangeRate'];
                                    } else {
                                        $customerAmount = $data_detail['transactionAmount'];
                                    }

                                    $data_detail['customerAmount'] = $customerAmount;
                                    $data_detail['segmentID'] = $data['segmentID'];
                                    $data_detail['companyID'] = $smeCustomerInvoice['companyID'];

                                    $data_detail['segmentCode'] = trim($jobDetail['segCode']);

                                    $data_detail['createdUserGroup'] = $this->common_data['user_group'];
                                    $data_detail['createdPCID'] = $this->common_data['current_pc'];
                                    $data_detail['createdUserID'] = $this->common_data['current_userID'];
                                    $data_detail['createdUserName'] = $this->common_data['current_user'];
                                    $data_detail['createdDateTime'] = $this->common_data['current_date'];
                                    $data_detail['timestamp'] = current_date();

                                    $this->db->insert('srp_erp_customerinvoicedetails', $data_detail);
                                } else {

                                    $data_item_detail['invoiceAutoID'] = $smeCustomerInvoiceMaster_id;
                                    $data_item_detail['type'] = 'Item';
                                    $data_item_detail['itemAutoID'] = $row['itemAutoID'];
                                    $data_item_detail['itemSystemCode'] = $row['itemSystemCode'];
                                    $data_item_detail['itemDescription'] = $row['itemDescription'];
                                    $data_item_detail['itemCategory'] = $row['mainCategory'];
                                    $data_item_detail['expenseGLAutoID'] = $row['costGLAutoID'];
                                    $data_item_detail['expenseSystemGLCode'] = $row['costSystemGLCode'];
                                    $data_item_detail['expenseGLCode'] = $row['costGLCode'];
                                    $data_item_detail['expenseGLDescription'] = $row['costDescription'];
                                    $data_item_detail['expenseGLType'] = $row['costType'];

                                    $data_item_detail['assetGLAutoID'] = $row['asstglCode'];
                                    $data_item_detail['assetGLCode'] = $row['asstGLSecondaryCode'];
                                    $data_item_detail['assetSystemGLCode'] = $row['asstsystemAccountCode'];
                                    $data_item_detail['assetGLDescription'] = $row['asstglDescription'];

                                    $data_item_detail['revenueGLAutoID'] = $row['revanueGLAutoID'];
                                    $data_item_detail['revenueGLCode'] = $row['revanueGLCode'];
                                    $data_item_detail['revenueSystemGLCode'] = $row['revanueSystemGLCode'];
                                    $data_item_detail['revenueGLDescription'] = $row['revanueDescription'];
                                    $data_item_detail['revenueGLType'] = $row['revanueType'];

                                    $data_item_detail['description'] = $smeCustomerInvoice['invoiceNarration'];

                                    $data_item_detail['requestedQty'] = $row['requestedQty'];
                                    $data_item_detail['companyLocalWacAmount'] = $row['companyLocalWacAmount'];
                                    $data_item_detail['defaultUOMID'] = $row['defaultUnitOfMeasureID'];
                                    $data_item_detail['defaultUOM'] = $row['defaultUnitOfMeasure'];
                                    $data_item_detail['unitOfMeasureID'] = $row['defaultUnitOfMeasureID'];
                                    $data_item_detail['unitOfMeasure'] = $row['defaultUnitOfMeasure'];
                                    $data_item_detail['conversionRateUOM'] = 1;

                                    $data_item_detail['transactionAmount'] = $row["transactionAmount"];
                                    $data_item_detail['companyLocalAmount'] = $row["companyLocalAmount"];
                                    $data_item_detail['companyReportingAmount'] = $row["companyReportingAmount"];
                                    $customerAmount = 0;
                                    if ($smeCustomerInvoice['customerCurrencyExchangeRate']) {
                                        $customerAmount = $data_item_detail['transactionAmount'] / $smeCustomerInvoice['customerCurrencyExchangeRate'];
                                    } else {
                                        $customerAmount = $data_item_detail['transactionAmount'];
                                    }

                                    $data_item_detail['customerAmount'] = $customerAmount;
                                    $data_item_detail['unittransactionAmount'] = $row['unitRate'];
                                    $data_item_detail['wareHouseAutoID'] = $jobDetail['wareHouseAutoID'];
                                    $data_item_detail['wareHouseCode'] = $jobDetail['wareHouseCode'];
                                    $data_item_detail['wareHouseDescription'] = $jobDetail['wareHouseDescription'];
                                    $data_item_detail['wareHouseLocation'] = $jobDetail['wareHouseLocation'];
                                    $data_item_detail['discountPercentage'] = 0;
                                    $data_item_detail['discountAmount'] = 0;
                                    $data_item_detail['segmentID'] = $data['segmentID'];
                                    $data_item_detail['companyID'] = $smeCustomerInvoice['companyID'];
                                    $data_item_detail['segmentCode'] = trim($jobDetail['segCode']);
                                    $data_item_detail['createdUserGroup'] = $this->common_data['user_group'];
                                    $data_item_detail['createdPCID'] = $this->common_data['current_pc'];
                                    $data_item_detail['createdUserID'] = $this->common_data['current_userID'];
                                    $data_item_detail['createdUserName'] = $this->common_data['current_user'];
                                    $data_item_detail['createdDateTime'] = $this->common_data['current_date'];
                                    $data_item_detail['timestamp'] = current_date();
                                    $this->db->insert('srp_erp_customerinvoicedetails', $data_item_detail);
                                }
                            }
                        }
                    }

                    $double_entry = $this->fetch_double_entry_mfq_customerInvoice($invoiceAutoID);
                    for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                        $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['invoiceAutoID'];
                        $generalledger_arr[$i]['documentCode'] = $double_entry['master_data']['documentID'];
                        $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['invoiceCode'];
                        $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['invoiceDate'];
                        $generalledger_arr[$i]['documentYear'] = date("Y", strtotime($double_entry['master_data']['invoiceDate']));
                        $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['invoiceDate']));
                        $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['invoiceNarration'];
                        $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                        $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                        $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                        $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                        $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                        $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['master_data']['companyLocalExchangeRate'];
                        $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                        $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                        $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['master_data']['companyReportingExchangeRate'];
                        $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                        $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                        $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                        $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                        $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                        $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                        $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                        $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                        $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                        $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                        $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                        $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                        $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                        $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                        $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                        /*$generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];*/
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
                        //$this->db->insert_batch('srp_erp_mfq_generalledger', $generalledger_arr);
                        $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);
                    }
                }
            } else {
                return array('w', 'Finance period not active for customer invoice');
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Customer Invoice Confirmed Failed ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Customer Invoice : Confirmed Successfully');
            }
        }
    }

    function delete_customerInvoiceDetail()
    {
        $masterID = $this->input->post('masterID');
        $this->db->select('invoiceDetailsAutoID');
        $this->db->from('srp_erp_mfq_customerinvoicedetails');
        $this->db->where('invoiceAutoID', $masterID);
        $result = $this->db->get()->result_array();
        $code = count($result) == 1 ? 1 : 2;

        $result = $this->db->delete('srp_erp_mfq_customerinvoicedetails', array('invoiceDetailsAutoID' => $this->input->post('invoiceDetailsID')), 1);
        if ($result) {
            return array('error' => 0, 'message' => 'Record deleted successfully!', 'code' => $code);
        } else {
            return array('error' => 1, 'message' => 'Error while deleting, please contact your system team!');
        }
    }

    function fetch_chartofaccount()
    {
        $dataArr = array();
        $dataArr2 = array();
        $companyID = current_companyID();
        $search_string = "%" . $_GET['query'] . "%";
        $sql = 'SELECT GLAutoID,GLSecondaryCode,GLDescription,CONCAT(IFNULL(GLSecondaryCode,"")," | ",IFNULL(GLDescription,"")," | ",IFNULL(subCategory,"") ) AS `Match` FROM srp_erp_chartofaccounts  WHERE (GLSecondaryCode LIKE "' . $search_string . '" OR GLDescription LIKE "' . $search_string . '" OR subCategory LIKE "' . $search_string . '" OR systemAccountCode LIKE "' . $search_string . '") AND companyID = "' . $companyID . '" AND isActive="1" AND `controllAccountYN` =0 AND `masterAccountYN` =0 AND `isActive` = 1 AND `isBank` =0 LIMIT 20';
        $data = $this->db->query($sql)->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'GLAutoID' => $val['GLAutoID'], 'GLSecondaryCode' => $val['GLSecondaryCode']);
            }
        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

}