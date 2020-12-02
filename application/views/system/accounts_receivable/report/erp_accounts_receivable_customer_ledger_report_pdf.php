<?php
include_once(APPPATH . '/third_party/mpdf/mpdf.php');
$mpdf = new mPDF(
    'utf-8',    // mode - default ''
    'A4',    // format - A4, for example, default ''
    '9',       // font size - default 0
    'arial',    // default font family
    5,          // margin_left
    5,          // margin right
    5,          // margin top
    10,          // margin bottom
    0,          // margin header
    3,          // margin footer
    'P'         // L - landscape, P - portrait
);

$user = ucwords($this->session->userdata('username'));
$date = date('l jS \of F Y h:i:s A');
$stylesheet = file_get_contents('plugins/bootstrap/css/print_style.css');
$mpdf->SetFooter('Printed By : ' . $user . '|Page : {PAGENO}|' . $date);
$mpdf->WriteHTML($stylesheet, 1);

/*$isRptCost = false;
$isLocCost = false;*/
$isTransCost = true;
/*if (isset($fieldName)) {
    if (in_array("companyReportingAmount", $fieldName)) {
        $isRptCost = true;
    }

    if (in_array("companyLocalAmount", $fieldName)) {
        $isLocCost = true;
    }

    if (in_array("transactionAmount", $fieldName)) {
        $isTransCost = true;
    }
}*/
$html = "";
if (!empty($output)) {
    $customerArr = array();
    $customer = get_all_customers();
    if (!empty($customer)) {
        foreach ($customer as $val) {
            $customerArr[$val["customerSystemCode"] . " - " . $val["customerName"]] = $val;
        }
    }
    $count = 8;
    $category = array();
    if ($isTransCost && !$isRptCost && !$isLocCost) {
        foreach ($output as $val) {
            $category[$val["customerSystemCode"] . " - " . $val["customerName"]][$val["transactioncurrency"]][] = $val;
        }
    } else {
        foreach ($output as $val) {
            $category[$val["customerSystemCode"] . " - " . $val["customerName"]][] = $val;
        }
    }
    $countCategory = count($category);
    $i = 1;
    $grandtotal = array();
    if ($isTransCost && !$isRptCost && !$isLocCost) {
        if (!empty($category)) {
            $htmlHeader = "";

            foreach ($category as $key2 => $currency) {
                $html .= '<table>
            <tbody>
            <tr>
                <td style="width:30%;">
                    <table>
                        <tr>
                            <td>
                                <img alt="Logo" style="height: 50px" src="' .mPDFImage. '/' . $this->common_data['company_data']['company_logo'] . '">
                            </td>
                        </tr>             
                    </table>
                </td>
                <td style="width:70%;">
                    <table>
                        <tr>
                            <td>
                                <h3><strong>' . $this->common_data['company_data']['company_name'] . ' (' . $this->common_data['company_data']['company_code'] . ').' . '</strong></h3>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>';
                $html .= '<hr>';
                $html .= '<table>
            <tbody>
            <tr>
                <td style="width:70%;">
                    <table>
                        <tr>
                            <td colspan="2">
                                Customer Address: <br>
                                ' . $key2 . '<br>
                                ' . $customerArr[$key2]["customerAddress1"] . '<br>
                               ' . $customerArr[$key2]["customerAddress2"] . '
                            </td>
                        </tr>                     
                         <tr>
                            <td>
                               Tel : ' . $customerArr[$key2]["customerTelephone"] . '<br>
                               Fax : ' . $customerArr[$key2]["customerFax"] . '
                            </td>
                 
                        </tr> 
                    </table>
                </td>
                <td style="width:30%;" valign="top">
                    <table>
                        <tr>
                            <td colspan="2">
                               <h4><strong>Customer Ledger</strong></h4>
                            </td>
                        </tr>
                         <tr>
                            <td style="width: 19%">Date :</td>
                            <td>' . current_format_date() . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>';
                $html .= '<table class="table" id="tbl_report"><thead><tr>
<th align="left" style="border-bottom: 1px solid black;border-top:1px solid black;width:10%">Doc Date</th>
<th align="left" style="border-bottom: 1px solid black;border-top:1px solid black;width:20%">Doc Type</th>
<th align="left" style="border-bottom: 1px solid black;border-top:1px solid black;width:20%">Doc Number</th>
<th align="left" style="border-bottom: 1px solid black;border-top:1px solid black;width:20%">Narration</th>';
                if (!empty($fieldNameDetails)) {
                    foreach ($fieldNameDetails as $val) {
                        if ($val['fieldName'] == 'transactionAmount') {
                            $html .= '<th align="left" style="border-bottom: 1px solid black;border-top:1px solid black;width:10%">Currency</th>';
                            $html .= '<th align="right" style="border-bottom: 1px solid black;border-top:1px solid black">Transaction Currency</th>';
                        }
                        else if($val['fieldName'] == 'companylocalAmount'){
                            $html .= '<th align="right" style="border-bottom: 1px solid black;border-top:1px solid black">Local Currency</th>';
                        }
                        else if($val['fieldName'] == 'companyReportingAmount'){
                            $html .= '<th align="right" style="border-bottom: 1px solid black;border-top:1px solid black">Reporting Currency</th>';
                        }
                    }
                }
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                $date_format_policy = date_format_policy();
                foreach ($currency as $key3 => $customers) {
                    $subtotal = array();
                    foreach ($customers as $key4 => $val) {
                        $html .= "<tr>";
                        $datefromconvert = input_format_date("1970-01-01", $date_format_policy);
                        if($val["documentDate"]== $datefromconvert){
                            $html .= "<td><div style='margin-left: 30px;color: #ffffff;opacity: 0'>" . $val["documentDate"] . "</div></td>";
                        }else{
                            $html .= "<td><div style='margin-left: 30px'>" . $val["documentDate"] . "</div></td>";
                        }
                        $html .= "<td>" . $val["document"] . "</td>";
                        $html .= '<td>' . $val["documentSystemCode"] . '</td>';
                        $html .= "<td>" . $val["documentNarration"] . "</td>";
                        if (!empty($fieldNameDetails)) {
                            foreach ($fieldNameDetails as $val2) {
                                $subtotal[$val2["fieldName"]][] = $val[$val2["fieldName"]];
                                $grandtotal[$val2["fieldName"]][] = $val[$val2["fieldName"]];
                                if ($val2["fieldName"] == 'transactionAmount') {
                                    $html .= "<td>" . $val["transactionCurrency"] . "</td>";
                                    $html .= "<td align='right'>" .format_number($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"])."</td>";
                                } else {
                                    $html .= "<td class='text-right'>".format_number($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"])."</td>";
                                }
                            }
                        }
                        $html .= "</tr>";
                    }

                     $html .= "<tr>";
                    if ($isLocCost || $isRptCost) {
                        if($isTransCost){
                             $html .= "<td colspan='6'><div style='margin-left: 30px'>Net Balance</div></td>";
                        }else{
                             $html .= "<td colspan='4'><div style='margin-left: 30px'>Net Balance</div></td>";
                        }
                    }
                    if (!empty($fieldNameDetails)) {
                        foreach ($fieldNameDetails as $key => $val2) {
                            if($val2['fieldName'] == "companyLocalAmount"){
                                 $html .= "<td class='reporttotal' align='right'>" . format_number(array_sum($subtotal[$val2['fieldName']]),$this->common_data['company_data']['company_default_decimal']) . "</td>";
                            }
                            if($val2['fieldName'] == "companyReportingAmount"){
                                 $html .= "<td class='reporttotal' align='right'>" . format_number(array_sum($subtotal[$val2['fieldName']]),$this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                            }
                        }
                    }
                     $html .= "</tr>";
                }

                $html .= '</tbody>';
                $html .= '</table>';
                $mpdf->WriteHTML($html, 2);
                if($countCategory != $i){
                    $mpdf->AddPage();
                }
                $html="";

                $i++;
            }
        }
    }
} else {
    $html = warning_message("No Records Found!");
}

//$mpdf->WriteHTML($html, 2);

$mpdf->Output();
?>