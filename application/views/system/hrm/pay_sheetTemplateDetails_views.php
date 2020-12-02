<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/font-awesome/css/font-awesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/dist/css/AdminLTE.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/dist/css/skins/_all-skins.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/animate/animate.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('plugins/iCheck/all.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('plugins/themify-icons/themify-icons.css'); ?>"/>
    <!--<link rel="stylesheet" href="<?php /*echo base_url('plugins/Dragtable/dragtable.css'); */ ?>" />-->

    <!--Bootstrap Country flag-->
    <link rel="stylesheet" href="<?php echo base_url('plugins/country_flag/flags.css'); ?>"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url('plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
</head>
<hr>
<?php
$viewTD = '';
foreach ($header_det as $det) {
    $caption = $det['captionName'];
    $columnName = $det['fieldName']; //columnName
    $salaryCatID = $det['catID'];
    $payGroupID = $det['payID'];
    $dType = $det['detailType'];
    $count = 0;
    $salaryCatID = ($salaryCatID == 0) ? $columnName : $salaryCatID;  //if it is not from salary declaration
    //console.log(  caption +' || '+ dType);
    switch ($dType) {
        case 'H':
            $viewTD .= '<th class="thCols" data-column="' . $columnName . '" data-dtype="' . $dType . '">' . $caption . '</th>';
            break;

        case 'A':
            $viewTD .= '<th class="thCols" data-column="' . $salaryCatID . '" data-dtype="' . $dType . '">' . $caption . '</th>';
            break;

        case 'D':
            $viewTD .= '<th class="thCols" data-column="' . $salaryCatID . '" data-dtype="' . $dType . '">' . $caption . '</th>';
            break;

        case 'G':
            $viewTD .= '<th class="thCols" data-column="G_' . $payGroupID . '" data-dtype="' . $dType . '">' . $caption . '</th>';
            break;
    }
}
$totalTDCount = count($header_det);
$tableID = 'payTB';
echo '
<div class="col-md-6" style="margin-top: 10px; margin-left: -10px; font-size: 15px;"></div>
<table class="' . table_class() . ' paySheetTB"  id="' . $tableID . '" style="margin-top: 3%">
    <thead>

    <tr class="designTR"  id="headerDetTR">
        ' . $viewTD . '
        <th style="width: auto" id="netSalaryTH" data-column="netSalary" rowspan="2"> Net Salary </th>
    </tr>
    </thead>';

/*<tr class="designTR"  id="headerDetTR">'.$H_viewTD.' '.$A_viewTD.' '.$D_viewTD.' '.$G_viewTD.'  </tr>*/

foreach ($currency_groups as $group) {

    $totalCols = $totalTDCount;

    echo '<tr id="tr_' . $group['currency'] . '" class="currencyHeader" style="font-size:12px">
           <th style="width: auto" colspan="' . ($totalTDCount + 1) . '"> <strong>Currency : ' . $group['currency'] . '</strong> </th>
         </tr>
         <tr>
            <td colspan="' . $totalCols . '" align="right" style="font-size:12px"> <strong>Total</strong> </td>
            <td align="right" style="font-size:12px !important;"> <strong>' . number_format($group['amount'], $group['dPlace']) . '</strong> </td>
         </tr>';

}
?>
</table>
<div style="margin-bottom: 30px">&nbsp;</div>
<?php /*$this->load->view('include/footer'); */ ?>
<script type="text/javascript">
    var detTB = $('.paySheetTB');
    appendDataToTable();
    function appendDataToTable() {
        var payID = '<?php echo $payrollMasterID ?>';
        var templateId = '<?php echo $templateID ?>';
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'hidden_payrollID': payID, 'templateId': templateId},
            url: "<?php echo site_url('Template_paysheet/fetchPaySheetData'); ?>",
            beforeSend: function () {
                //startLoad();
            },
            success: function (data) {
                //stopLoad();

                if (data[0] == 'e') {
                    myAlert(data[0], data[1]);
                } else {

                    if (data[0] == 's') {
                        var j = 1;
                        var det = data[1];
                        //console.log(det);
                        $.each(det, function () {
                            var tableDet = '';
                            var netSalary = 0;
                            var dPlace = det[j]['empDet']['dPlaces'];
                            var headerCount = 0;
                            //var table = $('#payTB_'+det[j]['empDet']['payCurrency']);
                            var table = $('#payTB');
                            var tableTh = $('#tr_' + det[j]['empDet']['payCurrency']);
                            var urlMore = $('#payrollHeaderDet').text() + ' - ' + det[j]['empDet']['ECode'];
                            //var urlMore = $('#payrollHeaderDet').text();

                            table.find('.thCols').each(function () {
                                var thisTD = $.trim($(this).attr('data-column'));
                                var dType = $.trim($(this).attr('data-dtype'));
                                var val = '';

                                if (dType == 'H') {
                                    var E_ID = det[j]['empDet']['E_ID'];
                                    val = ( $.trim(det[j]['empDet'][thisTD]) == '' ) ? '' : det[j]['empDet'][thisTD];
                                    if (headerCount == 0) {
                                        var fontColor = ( det[j]['netSalary'] < 0 ) ? 'style="color: #0e23c7"' : '';
                                        val = '<a href="<?php echo site_url('Template_paysheet/pay_slip'); ?>/' + payID + '/' + E_ID + '/' + urlMore + '" target="_blank" ' + fontColor + '>' + val + '</a>';
                                    }

                                    headerCount++;
                                }
                                else {
                                    var salDec = det[j]['empSalDec'];
                                    var notCount = 0;
                                    var arrayLength = salDec.length;

                                    $.each(salDec, function () {
                                        if (this.catID == thisTD) {
                                            val = this.amount;
                                            val = '<div align="right"  data-value="' + val + '">' + commaSeparateNumber(val, dPlace) + '</div>';

                                            if (this.catType == 'A') {
                                                netSalary += parseFloat(this.amount);
                                            } else {
                                                netSalary -= parseFloat(this.amount);
                                            }
                                        } else {
                                            notCount++;
                                        }
                                    });

                                    if (notCount == arrayLength) {
                                        val = '<div align="right"> - </div>';
                                    }

                                }

                                tableDet += '<td>' + val + '</td>';


                            });

                            var redTR = ( det[j]['netSalary'] < 0 ) ? 'style="background : red; color:#FFF"' : '';
                            tableDet += '<td><div align="right"> ' + commaSeparateNumber(det[j]['netSalary'], dPlace) + ' </div></td>';
                            tableTh.after('<tr class="detailTR" ' + redTR + '>' + tableDet + '</tr>');
                            //tableDet  +='<td><div align="right"> '+ commaSeparateNumber( netSalary, dPlace)+' </div></td>';

                            j++;

                        });

                        /*setTimeout(function () {
                         detTB.dataTable({
                         "destroy": true,
                         "paging": false,
                         "ordering": false,
                         "info": false
                         });
                         }, 500);*/
                    }
                }

            },
            error: function () {
                myAlert('e', 'An Error Occurred! Please Try Again.');
                stopLoad();
            }
        });
    }
    function commaSeparateNumber(val, dPlace = 2) {
        var toFloat = parseFloat(val);
        var a = toFloat.toFixed(dPlace);
        while (/(\d+)(\d{3})/.test(a.toString())) {
            a = a.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
        return a;
    }
</script>
<?php
/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 2016-08-11
 * Time: 10:36 AM
 */