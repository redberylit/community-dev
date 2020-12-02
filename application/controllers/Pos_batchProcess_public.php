<?php
/**
 *
 * -- =============================================
 * -- File Name : Pos_batchProcess.php
 * -- Project Name : POS
 * -- Module Name : POS Batch
 * -- Author : Mohamed Shafri
 * -- Create date : 23 October 2018
 * -- Description : Batch File .
 *
 * --REVISION HISTORY
 * -- =============================================
 **/
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos_batchProcess_public extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('pos');
        $this->load->model('Pos_batchProcess_model');
    }

    public function dailySalesSummeryReport($id, $date = null)
    {

        /**
         * Calling URL example :
         *
         *      Custom Date     :   http://localhost/community/index.php/Pos_batchProcess_public/dailySalesSummeryReport/13/2018-10-20
         *      Current Date    :   http://localhost/community/index.php/Pos_batchProcess_public/dailySalesSummeryReport/13
         */


        $message = '';
        $message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                    <meta name="viewport" content="width=device-width" />
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>Daily Sales Report Summery </title>';
        $message .= '<style type="text/css">

            table {
                border-spacing: 0;
                border-collapse: collapse
            }

            td, th {
                padding: 0;
                font-size: 12px !important
            }

            .table {
                border-collapse: collapse !important;
                width: 100%;
                max-width: 100%
            }

            .table td, .table th {
                background-color: #fff !important
            }

            .table-bordered td, .table-bordered th {
                border: 1px solid #ddd !important
            }

            .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #ddd
            }

            .table > thead > tr > th {
                vertical-align: bottom;
                border-bottom: 2px solid #ddd;
                background: 0 0 !important;
                color: #2a2a2a
            }

            .table > thead > tr {
                background: #C9D6FF;
                background: -webkit-linear-gradient(to right, #E2E2E2, #C9D6FF);
                background: linear-gradient(to right, #E2E2E2, #C9D6FF);
                color: #000
            }

            .table > caption + thead > tr:first-child > td, .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table > thead:first-child > tr:first-child > th {
                border-top: 0
            }

            .table > tbody + tbody {
                border-top: 2px solid #ddd
            }

            .table .table {
                background-color: #fff
            }

            .table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th {
                padding: 5px
            }

            .table-bordered, .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
                border: 1px solid #ddd
            }

            .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
                border-bottom-width: 2px
            }

            .table-striped > tbody > tr:nth-of-type(odd) {
                background-color: #f9f9f9
            }

            .table-hover > tbody > tr:hover, .table > tbody > tr.active > td, .table > tbody > tr.active > th, .table > tbody > tr > td.active, .table > tbody > tr > th.active, .table > tfoot > tr.active > td, .table > tfoot > tr.active > th, .table > tfoot > tr > td.active, .table > tfoot > tr > th.active, .table > thead > tr.active > td, .table > thead > tr.active > th, .table > thead > tr > td.active, .table > thead > tr > th.active {
                background-color: #f5f5f5
            }

            table col[class*=col-] {
                position: static;
                display: table-column;
                float: none
            }

            table td[class*=col-], table th[class*=col-] {
                position: static;
                display: table-cell;
                float: none
            }

            .table-hover > tbody > tr.active:hover > td, .table-hover > tbody > tr.active:hover > th, .table-hover > tbody > tr:hover > .active, .table-hover > tbody > tr > td.active:hover, .table-hover > tbody > tr > th.active:hover {
                background-color: #e8e8e8
            }

            .table > tbody > tr.success > td, .table > tbody > tr.success > th, .table > tbody > tr > td.success, .table > tbody > tr > th.success, .table > tfoot > tr.success > td, .table > tfoot > tr.success > th, .table > tfoot > tr > td.success, .table > tfoot > tr > th.success, .table > thead > tr.success > td, .table > thead > tr.success > th, .table > thead > tr > td.success, .table > thead > tr > th.success {
                background-color: #dff0d8
            }

            .table-hover > tbody > tr.success:hover > td, .table-hover > tbody > tr.success:hover > th, .table-hover > tbody > tr:hover > .success, .table-hover > tbody > tr > td.success:hover, .table-hover > tbody > tr > th.success:hover {
                background-color: #d0e9c6
            }

            .table > tbody > tr.info > td, .table > tbody > tr.info > th, .table > tbody > tr > td.info, .table > tbody > tr > th.info, .table > tfoot > tr.info > td, .table > tfoot > tr.info > th, .table > tfoot > tr > td.info, .table > tfoot > tr > th.info, .table > thead > tr.info > td, .table > thead > tr.info > th, .table > thead > tr > td.info, .table > thead > tr > th.info {
                background-color: #d9edf7
            }

            .table-hover > tbody > tr.info:hover > td, .table-hover > tbody > tr.info:hover > th, .table-hover > tbody > tr:hover > .info, .table-hover > tbody > tr > td.info:hover, .table-hover > tbody > tr > th.info:hover {
                background-color: #c4e3f3
            }

            .table > tbody > tr.warning > td, .table > tbody > tr.warning > th, .table > tbody > tr > td.warning, .table > tbody > tr > th.warning, .table > tfoot > tr.warning > td, .table > tfoot > tr.warning > th, .table > tfoot > tr > td.warning, .table > tfoot > tr > th.warning, .table > thead > tr.warning > td, .table > thead > tr.warning > th, .table > thead > tr > td.warning, .table > thead > tr > th.warning {
                background-color: #fcf8e3
            }

            .table-hover > tbody > tr.warning:hover > td, .table-hover > tbody > tr.warning:hover > th, .table-hover > tbody > tr:hover > .warning, .table-hover > tbody > tr > td.warning:hover, .table-hover > tbody > tr > th.warning:hover {
                background-color: #faf2cc
            }

            .table > tbody > tr.danger > td, .table > tbody > tr.danger > th, .table > tbody > tr > td.danger, .table > tbody > tr > th.danger, .table > tfoot > tr.danger > td, .table > tfoot > tr.danger > th, .table > tfoot > tr > td.danger, .table > tfoot > tr > th.danger, .table > thead > tr.danger > td, .table > thead > tr.danger > th, .table > thead > tr > td.danger, .table > thead > tr > th.danger {
                background-color: #f2dede
            }

            .table-hover > tbody > tr.danger:hover > td, .table-hover > tbody > tr.danger:hover > th, .table-hover > tbody > tr:hover > .danger, .table-hover > tbody > tr > td.danger:hover, .table-hover > tbody > tr > th.danger:hover {
                background-color: #ebcccc
            }

            @media screen and (max-width: 767px) {
                .table-responsive > .table {
                    margin-bottom: 0
                }

                .table-responsive > .table > tbody > tr > td, .table-responsive > .table > tbody > tr > th, .table-responsive > .table > tfoot > tr > td, .table-responsive > .table > tfoot > tr > th, .table-responsive > .table > thead > tr > td, .table-responsive > .table > thead > tr > th {
                    white-space: nowrap
                }

                .table-responsive > .table-bordered {
                    border: 0
                }

                .table-responsive > .table-bordered > tbody > tr > td:first-child, .table-responsive > .table-bordered > tbody > tr > th:first-child, .table-responsive > .table-bordered > tfoot > tr > td:first-child, .table-responsive > .table-bordered > tfoot > tr > th:first-child, .table-responsive > .table-bordered > thead > tr > td:first-child, .table-responsive > .table-bordered > thead > tr > th:first-child {
                    border-left: 0
                }

                .table-responsive > .table-bordered > tbody > tr > td:last-child, .table-responsive > .table-bordered > tbody > tr > th:last-child, .table-responsive > .table-bordered > tfoot > tr > td:last-child, .table-responsive > .table-bordered > tfoot > tr > th:last-child, .table-responsive > .table-bordered > thead > tr > td:last-child, .table-responsive > .table-bordered > thead > tr > th:last-child {
                    border-right: 0
                }

                .table-responsive > .table-bordered > tbody > tr:last-child > td, .table-responsive > .table-bordered > tbody > tr:last-child > th, .table-responsive > .table-bordered > tfoot > tr:last-child > td, .table-responsive > .table-bordered > tfoot > tr:last-child > th {
                    border-bottom: 0
                }

                .table-borderless td, .table-borderless th {
                    border: 0 !important
                }
            }

            .container {
                margin: 30px 50px;
                border-radius: 5px;
                padding: 15px;
                background: #fff;
                font-size: 13px
            }

            .ac {
                text-align: center
            }

            .h1, h1 {
                font-size: 36px;
                margin: 2px;
            }

            .h2, h2 {
                font-size: 30px;
                margin: 5px;
            }

            .h3, h3 {
                font-size: 24px
            }

            .h4, h4 {
                font-size: 18px
            }

            .h5, h5 {
                font-size: 14px;
                margin: 2px;
            }

            .h6, h6 {
                font-size: 12px
            }
        </style> ';

        $message .= '</head><body style=" font-family: sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin: 0; font-size: 11px; background: #e8e8e8; background: #e8e8e8">';

        $companyID = $id;

        $companyInfo = get_companyInformation($companyID);
        if (!empty($companyInfo)) {
            $config['hostname'] = trim($this->encryption->decrypt($companyInfo["host"]));
            $config['username'] = trim($this->encryption->decrypt($companyInfo["db_username"]));
            $config['password'] = trim($this->encryption->decrypt($companyInfo["db_password"]));
            $config['database'] = trim($this->encryption->decrypt($companyInfo["db_name"]));
            $config['dbdriver'] = 'mysqli';
            $config['db_debug'] = FALSE;
            $config['char_set'] = 'utf8';
            $config['dbcollat'] = 'utf8_general_ci';
            $config['cachedir'] = '';
            $config['swap_pre'] = '';
            $config['encrypt'] = FALSE;
            $config['compress'] = FALSE;
            $config['stricton'] = FALSE;
            $config['failover'] = array();
            $config['save_queries'] = TRUE;
            $this->load->database($config, FALSE, TRUE);
        } else {
            echo 'company not found!.';
            exit;
        }

        $batchId = 1;


        /** get Mailing List and Send the Email */
        $list = $this->Pos_batchProcess_model->get_mailingList($batchId, $id);
        $outlets = $this->Pos_batchProcess_model->getAllActiveOutlet($id);
        if ($list && $outlets) {

            if ($date) {
                $todayIs = strtotime($date);
            } else {
                $todayIs = time();
            }
            $day_before_time_string = strtotime("yesterday", $todayIs);
            $day_before = date('Y-m-d', $day_before_time_string);


            $message .= '<div class="container">';

            $message .= '<h2 class="ac">' . $companyInfo['company_name'] . '</h2>';


            $message .= $formatted = 'Processing Date: <strong>' . date('Y-m-d', $todayIs) . "</strong><br><br>";
            $message .= $formatted = 'Report Date: <strong>' . $day_before . '</strong><br><hr>';

            $netGrandTotal = 0;
            $GrandTotal_billsCount = 0;
            $GrandTotal_voidBills = 0;
            $GrandTotal_avg = 0;


            $message .= '<table class="table table-hover table-condensed table-bordered "><thead><tr>    <th>Outlet</th><th>Net Sales</th><th>No of Bills</th><th>Void Bills</th><th>Average Sales</th><th>Deductions</th><th>Shift wise Net Sales</th></tr></thead><tbody>';

            if (!empty($outlets)) {
                foreach ($outlets as $outlet) {
                    $outletID = $outlet['wareHouseAutoID'];
                    $outletDisplayName = $outlet['wareHouseCode'] . ' - ' . $outlet['wareHouseDescription'];


                    $data = $this->getSalesSummeryData($day_before . ' 00:00:00', $day_before . ' 23:59:59', null, $outletID, $id);

                    $d = 2;
                    $netTotal = 0;
                    $lessTotal = 0;
                    $paymentTypeTransaction = 0;
                    $voidedTotal = !empty($data['voidBills']['NetTotal']) ? $data['voidBills']['NetTotal'] : 0;
                    if (!empty($data['paymentMethod'])) {
                        foreach ($data['paymentMethod'] as $report2) {
                            $netTotal += $report2['NetTotal'];
                            $paymentTypeTransaction += $report2['countTransaction'];
                        }
                    }


                    if (!empty($data['lessAmounts'])) {
                        foreach ($data['lessAmounts'] as $less) {
                            $lessTotal += $less['lessAmount'];
                        }
                    }

                    $grandTotalCount = 0;
                    $billCountTotal = 0;

                    /*echo '<pre>';
                    print_r($data['customerTypeCount']);
                    exit;*/

                    if (!empty($data['customerTypeCount'])) {
                        foreach ($data['customerTypeCount'] as $report1) {
                            /*echo $report1['countTotal'].' - '.$report1['subTotal'].'<br/>';
                            continue;*/
                            $grandTotalCount += $report1['countTotal'];
                            $billCountTotal += $report1['subTotal'];

                        }
                    }
                    /*print_r($data['fullyDiscountBill']['fullyDiscountBills']);
                    exit;*/

                    $grandTotalCount = $grandTotalCount - $data['fullyDiscountBill']['fullyDiscountBills'];
                    //echo $grandTotalCount.'<br/>';


                    $grossTotal = $netTotal + $lessTotal;
                    $totalBill = $grossTotal + $voidedTotal;
                    $message .= '<tr> <td>';
                    $message .= $outletDisplayName;
                    $message .= '</td> <td style="text-align: right;">';

                    $netGrandTotal += $netTotal;
                    $message .= number_format($netTotal, $d);
                    $message .= '</td> <td style="text-align: center;">';

                    /*No of Bills */
                    $message .= number_format($grandTotalCount);
                    $GrandTotal_billsCount += $grandTotalCount;


                    $message .= '</td> <td style="text-align: center;">';
                    $message .= isset($data['voidBills']['countTransaction']) ? $data['voidBills']['countTransaction'] : 0;
                    $GrandTotal_voidBills += $data['voidBills']['countTransaction'];

                    $message .= '</td> <td style="text-align: right;">';

                    if ($paymentTypeTransaction > 0) {
                        $message .= $avg = $grandTotalCount > 0 ? number_format(($netTotal / $paymentTypeTransaction), $d) : 0;
                        $GrandTotal_avg += $avg;
                    } else {
                        $message .= 0;
                    }
                    $message .= ' </td><td><table border="0" cellspacing="0" cellpadding="0" width="100%">';

                    if (!empty($data['lessAmounts'])) {
                        foreach ($data['lessAmounts'] as $less) {
                            if ($less['lessAmount'] > 0) {

                                $message .= '<tr> <td style=" border: none !important;">';
                                $message .= $less['customerName'];
                                $message .= ' </td> <td class="text-right" style=" border: none !important;">';
                                $message .= number_format($less['lessAmount'], $d);
                                $message .= '</td></tr>';
                            }
                        }
                        if ($lessTotal > 0) {
                            $message .= '<tr> <td style="padding-top: 10px; border: none !important;"><strong> Total Discount</strong></td> <td style="padding-top: 10px;  border: none !important;"class="text-right"><strong>(';
                            $message .= number_format($lessTotal, $d);
                            $message .= ')</strong></td></tr>';
                        }
                    }
                    $message .= '</table> </td> <td>';

                    $shiftWiseSales = $this->Pos_batchProcess_model->get_report_paymentMethod_admin($day_before . ' 00:00:00', $day_before . ' 23:59:59', null, $outletID, $id, true);
                    if (!empty($shiftWiseSales)) {
                        $tmpShiftWiseTotal = 0;
                        $message .= '<table border="0" cellspacing="0" cellpadding="0" width="100%">';
                        $message .= '<tbody><tr><th style="border: none !important;">Start</th><th style="border: none !important;">End</th><th style="border: none !important;">Sales </th></tr>';
                        foreach ($shiftWiseSales as $shiftWiseSale) {
                            $tmpShiftWiseTotal += $shiftWiseSale['NetTotal'];
                            $message .= '<tr>';
                            $message .= '<td style="text-align: center;  border: none !important;">' . $shiftWiseSale['startTime'] . '</td>';
                            $message .= '<td style="text-align: center;  border: none !important;">' . $shiftWiseSale['endTime'] . '</td>';
                            $message .= '<td style="text-align: center;   border: none !important; text-align: right;">' . number_format($shiftWiseSale['NetTotal'], $d) . '</td>';
                            $message .= '</tr>';
                        }
                        $message .= '<tr>';
                        $message .= '<td style="text-align: center;  border: none !important;">&nbsp;</td>';
                        $message .= '<th style="text-align: right;  border: none !important;">Total</th>';
                        $message .= '<th style="text-align: center; border: none !important; border-top: 1px solid gray !important; text-align: right;">' . number_format($tmpShiftWiseTotal, $d) . '</th>';
                        $message .= '</tr>';

                        $message .= '</tbody></table>';
                    }
                    $message .= '</td></tr>';
                }
            }

            $message .= '</tbody><tfoot><tr><th>Total</th><th style="text-align: right;">';
            $message .= number_format($netGrandTotal, $d);
            $message .= '</th><th style="text-align: center;">';
            $message .= number_format($GrandTotal_billsCount);
            $message .= '</th><th style="text-align: center;">';
            $message .= number_format($GrandTotal_voidBills);
            $message .= '</th><th style="text-align: right;">';
            $grossBills = $GrandTotal_billsCount;
            if ($grossBills > 0) {
                $avgGross = $netGrandTotal / $GrandTotal_billsCount;
            } else {
                $avgGross = 0;
            }
            $message .= number_format($avgGross, $d);
            $message .= '</th><th>&nbsp;</th><th>&nbsp;</th></tr></tfoot></table>';
        }

        $message .= '<br/><br/><br/><i>This is an automatically generated email, created on ' . date('d-m-Y g:i A') . '</i>';
        $message .= '</div></body></html>';


        if (!empty($list)) {
            foreach ($list as $user) {

                $emailAddress = $user->email;
                if ($emailAddress) {
                    $mail_config['wordwrap'] = TRUE;
                    $mail_config['protocol'] = 'smtp';
                    $mail_config['smtp_host'] = 'smtp.sendgrid.net';
                    $mail_config['smtp_user'] = 'apikey';
                    $mail_config['smtp_pass'] = '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';
                    $mail_config['smtp_crypto'] = 'tls';

                    $mail_config['smtp_port'] = '587';
                    $mail_config['crlf'] = "\r\n";
                    $mail_config['newline'] = "\r\n";


                    $this->load->library('email', $mail_config);
                    $this->email->from('noreply@spur-int.com', SYS_NAME);
                    $this->email->set_mailtype('html');

                    $this->email->to($emailAddress);
                    $this->email->subject('Daily Sales Summery - ' . $day_before);
                    $this->email->message($message);

                    $result = $this->email->send();

                    if ($result) {
                        //echo 'Email Sent to ' . $emailAddress . '<br/>';
                    } else {
                        echo 'Fail sending email';
                    }
                }

            }
        }

    }

    private function getSalesSummeryData($filterDate, $date2, $cashier, $outlets, $companyID, $shift = null)
    {
        $lessAmounts = $this->Pos_batchProcess_model->get_report_lessAmount_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $lessAmounts_promotion = $this->Pos_batchProcess_model->get_report_lessAmount_promotion_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $lessAmounts_discounts = $this->Pos_batchProcess_model->get_report_salesReport_discount_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $lessAmounts_discounts_item_wise = $this->Pos_batchProcess_model->get_report_salesReport_discount_item_wise_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $lessAmounts_discountsJavaApp = $this->Pos_batchProcess_model->get_report_salesReport_javaAppDiscount_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $lessAmountsAll = array_merge($lessAmounts_discounts, $lessAmounts, $lessAmounts_promotion, $lessAmounts_discountsJavaApp, $lessAmounts_discounts_item_wise);

        $data['paymentMethod'] = $this->Pos_batchProcess_model->get_report_paymentMethod_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $data['customerTypeCount'] = $this->Pos_batchProcess_model->get_report_customerTypeCount_2_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $data['lessAmounts'] = $lessAmountsAll;
        $data['totalSales'] = $this->Pos_batchProcess_model->get_report_salesReport_totalSales_admin($filterDate, $date2, $cashier, $outlets, $companyID, $shift);
        $data['voidBills'] = $this->Pos_batchProcess_model->get_report_voidBills_admin($filterDate, $date2, $cashier, $outlets, $companyID);
        $data['fullyDiscountBill'] = $this->Pos_batchProcess_model->get_report_fullyDiscountBills_admin($filterDate, $date2, $cashier, $outlets, $companyID);

        return $data;
    }

}