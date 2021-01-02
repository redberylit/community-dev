<!---- =============================================
-- File Name : erp_procurement_purchase_order_list_report.php
-- Project Name : SME ERP
-- Module Name : Report - Procurement
-- Author : Mohamed Mubashir
-- Create date : 15 - October 2016
-- Description : This file contains Purchase Order List.

-- REVISION HISTORY
-- =============================================-->
<?php
$isRptCost = false;
$isLocCost = false;
$statusText = "";
if (isset($fieldName)) {
    if (in_array("companyReportingAmount", $fieldName)) {
        $isRptCost = true;
    }
    if (in_array("supplierCurrencyAmount", $fieldName)) {
        $isLocCost = true;
    }
}

if ($status == 0) {
    $statusText = "Not Received";
} else if ($status == 1) {
    $statusText = "Partially Received";
} else if ($status == 2) {
    $statusText = "Fully Received";
} else {
    $statusText = "All";
}


?>
<div class="row">
    <div class="col-md-12">
        <?php if ($type == 'html') {
            echo export_buttons('tbl_purchase_order_list', 'Purchase Order List');
        } ?>
    </div>
</div>
<div id="tbl_purchase_order_list">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center reportHeaderColor">
                <strong><?php echo $this->common_data['company_data']['company_name'] ?> </strong>
            </div>
            <div class="text-center reportHeader reportHeaderColor"> Purchase Order List
                - <?php echo $statusText ?></div>
            <div
                    class="text-center reportHeaderColor"> <?php echo "<strong>Date From: </strong>" . $from . " - <strong>Date To: </strong>" . $to ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <strong>Filters <i class="fa fa-filter"></i></strong><br>
            <strong><i>Segment:</i></strong> <?php echo join(",", $segmentfilter) ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <?php if (!empty($output)) { ?>
                <div class="fixHeader_Div">
                    <table class="borderSpace report-table-condensed" id="tbl_report">
                        <thead class="report-header">
                        <tr>
                            <th rowspan="2">Type</th>
                            <th rowspan="2">PO Number</th>
                            <th rowspan="2">Date</th>
                            <th rowspan="2">Narration</th>
                            <th rowspan="2">Delivery Date</th>
                            <?php
                            if (!empty($caption)) {
                                foreach ($caption as $val) {
                                    echo '<th colspan="4">' . $val . '</th>';
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <?php
                            if (!empty($fieldName)) {
                                foreach ($fieldName as $val) {
                                    echo '<th>Currency</th>';
                                    echo '<th>PO Value</th>';
                                    echo '<th>Received Value</th>';
                                    echo '<th>Balance Value</th>';
                                }
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $category = array();
                        foreach ($output as $val) {
                            $category[$val["supplierName"]][] = $val;
                        }
                        $grandTotalrptAmount = 0;
                        $grandTotalReceiveAmount = 0;
                        $grandTotalBalanceAmount = 0;
                        if (!empty($category)) {
                            foreach ($category as $key => $supplierName) {
                                echo "<tr><td colspan='10'><div class='mainCategoryHead2'>" . $key . "</div></td></tr>";
                                $rptAmount = 0;
                                $supplierCurrencyAmount = 0;
                                $rptReceiveAmount = 0;
                                $rptBalanceAmount = 0;
                                foreach ($supplierName as $val) {
                                    echo "<tr class='hoverTr'>";
                                    echo "<td>" . $val["purchaseOrderType"] . "</td>";
                                    if ($type == 'html') {
                                        echo '<td><a href="#" class="drill-down-cursor" onclick="documentPageView_modal(\'' . $val["documentID"] . '\',' . $val["purchaseOrderID"] . ')"> ' . $val["purchaseOrderCode"] . '</a></td>';
                                    } else {
                                        echo '<td>' . $val["purchaseOrderCode"] . '</td>';
                                    }
                                    echo "<td>" . $val["documentDate"] . "</td>";
                                    echo "<td>" . $val["narration"] . "</td>";
                                    echo "<td>" . $val["expectedDeliveryDate"] . "</td>";
                                    if (!empty($fieldNameDetails)) {
                                        foreach ($fieldNameDetails as $val2) {
                                            if ($val2["fieldName"] == "companyReportingAmount") {
                                                $rptAmount += $val[$val2["fieldName"]];
                                                $rptReceiveAmount += $val["GRV".$val2["fieldName"]];
                                                $rptBalanceAmount += $val[$val2["fieldName"] . "Balance"];
                                                $grandTotalrptAmount += $val[$val2["fieldName"]];
                                                $grandTotalReceiveAmount += $val["GRV".$val2["fieldName"]];
                                                $grandTotalBalanceAmount += $val[$val2["fieldName"] . "Balance"];
                                                echo "<td>" . $val["companyReportingCurrency"] . "</td>";
                                                echo "<td class='text-right'>" . format_number($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]) . "</td>";
                                                echo "<td class='text-right'>" . format_number($val["GRV".$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]) . "</td>";
                                                echo "<td class='text-right'>" . format_number($val[$val2["fieldName"] . "Balance"], $val[$val2["fieldName"] . "DecimalPlaces"]) . "</td>";
                                            }
                                            if ($val2["fieldName"] == "supplierCurrencyAmount") {
                                                $supplierCurrencyAmount += $val[$val2["fieldName"]];
                                                echo "<td>" . $val["supplierCurrency"] . "</td>";
                                                echo "<td class='text-right'>" . format_number($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]) . "</td>";
                                                echo "<td class='text-right'>" . format_number($val["GRV".$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]) . "</td>";
                                                echo "<td class='text-right'>" . format_number($val[$val2["fieldName"] . "Balance"], $val[$val2["fieldName"] . "DecimalPlaces"]) . "</td>";
                                            }
                                        }
                                    }
                                    echo "</tr>";
                                }

                                echo "<tr>";
                                echo "<td colspan='5'></td>";
                                if (!empty($fieldNameDetails)) {
                                    foreach ($fieldNameDetails as $val2) {
                                        if ($val2["fieldName"] == "companyReportingAmount") {
                                            echo "<td></td>";
                                            echo "<td class='reporttotal text-right'>" . format_number($rptAmount, $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                            echo "<td class='reporttotal text-right'>" . format_number($rptReceiveAmount, $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                            echo "<td class='reporttotal text-right'>" . format_number($rptBalanceAmount, $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                        }
                                    }
                                }
                                echo "</tr>";
                            }
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan='10'>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <?php
                            echo "<tr>";
                            echo "<td colspan='5'> <strong>Grand Total</strong></td>";
                            if ($isRptCost) {
                                echo "<td></td>";
                                echo "<td class='reporttotal text-right'>" . format_number($grandTotalrptAmount, $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                echo "<td class='reporttotal text-right'>" . format_number($grandTotalReceiveAmount, $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                echo "<td class='reporttotal text-right'>" . format_number($grandTotalBalanceAmount, $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                            }
                            ?>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <?php
            } else {
                echo warning_message("No Records Found!");
            }
            ?>
        </div>
    </div>
</div>
<script>

    /* $(document).ready(function() {
     $('#demo').dragtable();
     });*/

    /*$('#tbl_report').tableHeadFixer({
        head: true,
        foot: false,
        left: 0,
        right: 0,
        'z-index': 0
    });*/
</script>