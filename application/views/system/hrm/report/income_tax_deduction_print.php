<?php

include_once(APPPATH . '/third_party/mpdf/mpdf.php');
$mpdf = new mPDF(
    'utf-8',    // mode - default ''
    'A5-L',    // format - A4, for example, default ''
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
//$date = date('l jS \of F Y h:i:s A');
$date = date('Y-m-d H:i:s ');
$stylesheet = file_get_contents('plugins/bootstrap/css/print_style.css');
$mpdf->SetFooter('Printed By : ' . $user . '|Page : {PAGENO}|' . $date);
$mpdf->WriteHTML($stylesheet, 1);


$processDate = $this->input->post('processDate');
$companyData = $this->common_data['company_data'];

$html = '';
$countRecord = count($payeeData);
foreach($payeeData as $key=>$row){
    $html .=
    '<div class="row" style="margin-top: 3%">
         <div class="col-md-12">
            <div class="text-center"><strong>SRI LANKA INLAND REVENUE</strong></div>
            <div class="text-center"><strong>CERTIFICATE OF INCOME TAX DEDUCTIONS</strong></div>
            <div class="text-center"><strong>P.A.Y.E</strong></div>
         </div>
     </div>

    <div class="row" style="margin-bottom: 3px">
        <div class="col-md-12">
            <div class="income-text-header">'.$companyData['company_name'].'</div>
            <div class="income-text-header"> '. $companyData['company_address1'] . ',  </div>
            <div class="income-text-header"> ' . $companyData['company_address2'] . ',  </div>
            <div class="income-text-header"> ' . $companyData['company_city'] .' </div>
        </div>
    </div>

    <table>
        <tbody>
            <tr>
                <td><span style="font-style: italic;">Prescribed under Section 103 0f the Inland Revenue Act</span></td>
                <td align="right">P.A.Y.E./T.10</td>
            </tr>
        </tbody>
    </table>


    <table class="'. table_class().'" id="incomeTexDeduction">
        <tr>
            <td>Full Name of the Employee   </td>
            <td colspan="2">'. $row['fullName'] .'</td>
        </tr>
        <tr>
            <td>NIC.Number</td>
            <td colspan="2"> '. $row['NIC'] .'</td>
        </tr>
        <tr>
            <td>Employer\'s Registration No.</td>
            <td colspan="2"> </td>
        </tr>
        <tr>
            <td>Period of service for which Remuneration was paid </td>
            <td colspan="2"> From : '. $fromDate .' &nbsp;&nbsp;&nbsp; To :'. $toDate .'</td>
        </tr>
        <tr>
            <td>Total Gross Remuneration as per Pay Sheet</td>
            <td colspan="2" align="right"> </td>
        </tr>
        <tr>
            <td>Cash Benefits</td>
            <td align="right">'. number_format($row['cashBenefit'], 2) .'</td>
            <td>Non Cash Benefits</td>
        </tr>
        <tr>
            <td>Total Amount of Tax Deducted  ( Rs.)</td>
            <td colspan="2" align="right">'. number_format($row['payee'], 2) .'</td>
        </tr>
        <tr>
            <td>In Words</td>
            <td colspan="2">'. $this->numbertowords->convert_number($row['payee']) .'</td>
        </tr>
        <tr>
            <td>Total Amount remitted to the Dept. of Inland Revenue</td>
            <td colspan="2" align="right">'. number_format($row['payee'], 2) .' Rupees Only</td>
        </tr>
        <tr>
            <td colspan="3" align="center"><strong>I certify the above particulars as correct</strong></td>
        </tr>
        <tr>
            <td>Name of Employer</td>
            <td colspan="2"> <strong>'. $companyData['company_name'] .'</strong></td>
        </tr>
        <tr>
            <td>Address</td>
            <td colspan="2">'. $companyData['company_address1'] . ' ' . $companyData['company_address2'] .'</td>
        </tr>
        <tr>
            <td colspan="3">Date &nbsp;&nbsp;&nbsp;&nbsp; '. $processDate.'</td>
        </tr>
        <tr>
            <td colspan="3">Signature of Employer  :......................................</td>
        </tr>
    </table>';
    $mpdf->WriteHTML($html, 2);
    if ($countRecord > ($key+1) ) {
        $mpdf->AddPage();
    }
    $html = "";
}
$mpdf->Output();
?>

<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 4/24/2017
 * Time: 11:38 AM
 */