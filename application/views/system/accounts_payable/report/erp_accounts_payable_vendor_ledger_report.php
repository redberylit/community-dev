<!---- =============================================
-- File Name : erp_accounts_payable_vendor_ledger_report.php
-- Project Name : SME ERP
-- Module Name : Report - Accounts Payable
-- Author : Mohamed Mubashir
-- Create date : 05 - November 2016
-- Description : This file contains Vendor Ledger.

-- REVISION HISTORY
-- =============================================-->
<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_payable', $primaryLanguage);
$datefrom=$this->lang->line('accounts_payable_reports_vl_date_from'); /*Date from language*/
    $dateto=$this->lang->line('accounts_payable_reports_vl_date_to');  /*Date To language*/



$this->lang->load('common', $primaryLanguage);
$isRptCost = false;
$isLocCost = false;
$isTransCost = false;
if (isset($fieldName)) {
    if (in_array("companyReportingAmount", $fieldName)) {
        $isRptCost = true;
    }

    if (in_array("companyLocalAmount", $fieldName)) {
        $isLocCost = true;
    }

    if (in_array("transactionAmount", $fieldName)) {
        $isTransCost = true;
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <?php if ($type == 'html') {
            echo export_buttons('tbl_vendor_ledger', 'Vendor Ledger');
        } ?>
    </div>
</div>
<div id="tbl_vendor_ledger">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center reportHeaderColor">
                <strong><?php echo $this->common_data['company_data']['company_name'] ?> </strong>
            </div>
            <div class="text-center reportHeader reportHeaderColor"> <?php echo $this->lang->line('accounts_payable_reports_vl_vendor_ledger');?><!--Vendor Ledger--></div>
            <div
                    class="text-center reportHeaderColor"> <?php echo "<strong>$datefrom<!--Date From-->: </strong>" . $from . " - <strong>$dateto<!--Date To-->: </strong>" . $to ?></div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <?php if (!empty($output)) { ?>
                <div class="fixHeader_Div">
                    <table class="borderSpace report-table-condensed" id="tbl_report">
                        <thead class="report-header">
                        <tr>
                            <th><?php echo $this->lang->line('accounts_payable_reports_vl_doc_date');?><!--Doc Date--></th>
                            <th><?php echo $this->lang->line('accounts_payable_reports_vl_doc_type');?><!--Doc Type--></th>
                            <th><?php echo $this->lang->line('accounts_payable_reports_vl_doc_number');?><!--Doc Number--></th>
                            <th><?php echo $this->lang->line('common_narration');?><!--Narration--></th>
                            <?php
                            if (!empty($caption)) {
                                foreach ($caption as $val) {
                                    if ($val == "Transaction Currency") {
                                        $currency=$this->lang->line('common_currency');
                                        echo '<th>'.$currency.'<!--Currency--></th>';
                                        echo '<th>' . $val . '</th>';
                                    } else {
                                        if ($val == "Reporting Currency") {
                                            echo '<th>' . $val . '(' . $this->common_data['company_data']['company_reporting_currency'] . ')</th>';
                                        }
                                        if ($val == "Local Currency") {
                                            echo '<th>' . $val . '(' . $this->common_data['company_data']['company_default_currency'] . ')</th>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        </thead>
                        <?php
                        $count = 10;
                        $category = array();
                        foreach ($output as $val) {
                            $category[$val["GLSecondaryCode"] . " - " . $val["GLDescription"]][$val["supplierSystemCode"] . " - " . $val["supplierName"]][] = $val;
                        }

                        if (!empty($category)) {
                            foreach ($category as $key => $glcodes) {
                                $grandtotal = array();
                                echo "<tr><td colspan='" . $count . "'><div class='mainCategoryHead'>" . $key . "</div></td></tr>";
                                foreach ($glcodes as $key2 => $suppliers) {
                                    echo "<tr><td colspan='" . $count . "'><div style='margin-left: 15px' class='mainCategoryHead2'>" . $key2 . "</div></td></tr>";
                                    $subtotal = array();
                                    foreach ($suppliers as $key3 => $val) {
                                        echo "<tr class='hoverTr'>";
                                        if ($val["documentDate"] == "1970-01-01") {
                                            echo "<td><div style='margin-left: 30px;color: #ffffff;opacity: 0'>" . $val["documentDate"] . "</div></td>";
                                        } else {
                                            echo "<td><div style='margin-left: 30px'>" . $val["documentDate"] . "</div></td>";
                                        }
                                        echo "<td>" . $val["documentCode"] . "</td>";
                                        if ($type == 'html') {
                                            echo '<td><a href="#"  class="drill-down-cursor" onclick="documentPageView_modal(\'' . $val["documentCode"] . '\',' . $val["documentMasterAutoID"] . ')">' . $val["documentSystemCode"] . '</a></td>';
                                        } else {
                                            echo '<td>' . $val["documentSystemCode"] . '</td>';
                                        }
                                        echo "<td>" . $val["documentNarration"] . "</td>";
                                        if (!empty($fieldName)) {
                                            foreach ($fieldName as $val2) {
                                                $subtotal[$val2][] = (float)$val[$val2];
                                                $grandtotal[$val2][] = (float)$val[$val2];
                                                if ($val2 == 'transactionAmount') {
                                                    echo "<td>" . $val["transactionCurrency"] . "</td>";
                                                    echo "<td class='text-right'>" . format_number($val[$val2], $val[$val2 . "DecimalPlaces"]) . "</td>";
                                                } else {
                                                    echo "<td class='text-right'>" . format_number($val[$val2], $val[$val2 . "DecimalPlaces"]) . "</td>";
                                                }
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                    echo "<tr>";
                                    if ($isLocCost || $isRptCost) {
                                        if ($isTransCost) {
                                            $netbalance=$this->lang->line('accounts_payable_reports_vl_net_balance');/*Net balance language*/

                                            echo "<td colspan='6'><div style='margin-left: 30px'> $netbalance<!--Net Balance--></div></td>";
                                        } else {
                                            $netbalance=$this->lang->line('accounts_payable_reports_vl_net_balance');/*Net balance language*/

                                            echo "<td colspan='4'><div style='margin-left: 30px'> $netbalance<!--Net Balance--></div></td>";
                                        }
                                    }
                                    if (!empty($fieldName)) {
                                        foreach ($fieldName as $val2) {
                                            if ($val2 == "companyLocalAmount") {
                                                echo "<td class='text-right reporttotal'>" . format_number(array_sum($subtotal[$val2]), $this->common_data['company_data']['company_default_decimal']) . "</td>";
                                            }
                                            if ($val2 == "companyReportingAmount") {
                                                echo "<td class='text-right reporttotal'>" . format_number(array_sum($subtotal[$val2]), $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                            }
                                        }
                                    }
                                    echo "</tr>";
                                }
                                echo "<tr><td colspan='" . $count . "'>&nbsp;</td></tr>";
                                echo "<tr>";
                                if ($isLocCost || $isRptCost) {

                                    $gran=$this->lang->line('common_grand_total'); /*grand total language*/

                                    if ($isTransCost) {

                                        echo "<td colspan='6'><div style='margin-left: 30px'><strong>$gran<!--Grand Total--></strong></div></td>";
                                    } else {
                                        echo "<td colspan='4'><div style='margin-left: 30px'><strong>$gran<!--Grand Total--></strong></div></td>";
                                    }
                                }
                                if (!empty($fieldName)) {
                                    foreach ($fieldName as $val2) {
                                        if ($val2 == "companyLocalAmount") {
                                            echo "<td class='text-right reporttotal'>" . format_number(array_sum($grandtotal[$val2]), $this->common_data['company_data']['company_default_decimal']) . "</td>";
                                        }
                                        if ($val2 == "companyReportingAmount") {
                                            echo "<td class='text-right reporttotal'>" . format_number(array_sum($grandtotal[$val2]), $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                        }
                                    }
                                }
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <?php
            } else {
                $norecfound=$this->lang->line('common_no_records_found');
                echo warning_message($norecfound);/*No Records Found!*/
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