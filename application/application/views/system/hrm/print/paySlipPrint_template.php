<?php $date = $masterData['payrollYear']."-".$masterData['payrollMonth']."-01" ?>
<div style="padding: 12px;">
<h4 style="text-align:center;"> <?php echo $this->common_data['company_data']['company_name']; ?></h4>
<hr>
<table style="width: 100%" border="0px">
    <tbody>
        <tr>
            <th align="center">Pay Slip For The Month Of <?php echo date('F',  strtotime($date )); ?></th>
        </tr>
        <tr>
            <th align="center"><?php echo date('Y',  strtotime($date )); ?></th>
        </tr>
    </tbody>
</table>

<hr>
<?php
/*echo '<pre>'; print_r($header_det); echo '</pre>';
echo '<pre>'; print_r($details); echo '</pre>';*/

$thisEmpDet = $details[1]['empDet'];
$thisEmpSalaryDet = $details[1]['empSalDec'];
$dPlace = 2;
?>

<table style="width: 100%" border="0px">
    <tbody>
    <tr>
        <td width="20%"><strong>Number</strong></td>
        <td width="2%" align="center"><strong>:</strong></td>
        <td width="78%"><strong><?php echo $thisEmpDet['ECode'] ?></strong></td>
    </tr>
    <tr>
        <td><strong>Name</strong></td>
        <td align="center"><strong>:</strong></td>
        <td><strong><?php echo $thisEmpDet['Ename2'] ?></strong></td>
    </tr>
    </tbody>
</table>

<table class="<?php echo table_class();?>" id="paysheet-tb" style="margin-top: -3px">
    <tbody>
    <?php

    foreach($header_det as $key=>$row){
        /**** Making the header [th] ****/
        echo '<tr>';

        if( $row['detailType'] != 'H'){
            echo '<th class="theadtr" align="left">'.$row['captionName'].'</th>';
        }

        $value = '';
        /*if ($row['detailType'] == 'H') {
            $value = '<th align="left">' . $thisEmpDet[ $row['fieldName'] ] . '</th>';
        }*/
        if ($row['detailType'] == 'A' || $row['detailType'] == 'D') {
            /************************************************************************************
             * If 'fieldName' name equal to 'MA'=> Monthly Addition or 'MD' => Monthly Deduction
             * than the searching key will be 'MA' OR 'MD'
             ************************************************************************************/

            $searchingKey = ($row['fieldName'] == 'MA' OR $row['fieldName'] == 'MD' OR $row['fieldName'] == 'LO')? $row['fieldName'] : $row['catID'];
            $val = search_paysheetEmpDetails($thisEmpSalaryDet, $searchingKey);

            $value = '<td align="right">'. format_number($val, $dPlace) . '</td>';
        }

        else if ($row['detailType'] == 'G') {
            $searchingKey = 'G_'.$row['payID'];
            $val = search_paysheetEmpDetails($thisEmpSalaryDet, $searchingKey);

            $value = '<td align="right">' . format_number($val, $dPlace). '</td>';
        }
      
        echo  $value;
        echo '</tr>';
    }
    ?>
    </tbody>
</table>
</div>
    <script>
        $('.review').removeClass('hidden');
        a_link = "<?php echo site_url('Template_paysheet/get_paySlip_reports_pdf'); ?>/<?php echo $payrollMasterID ?>/<?php echo $empID ?>/<?php echo $isNonPayroll ?>";
        $("#a_link").attr("href", a_link);
    </script>
<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 3/28/2017
 * Time: 11:24 AM
 */