<!---- =============================================
-- File Name : erp_accounts_receivable_customer_statement_report.php
-- Project Name : SME ERP
-- Module Name : Report - Accounts Receivable
-- Author : Mohamed Mubashir
-- Create date : 10 - November 2016
-- Description : This file contains Customer Statement.

-- REVISION HISTORY
-- =============================================-->
<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_receivable', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$asof= $this->lang->line('accounts_receivable_common_as_of');
$currency= $this->lang->line('common_currency');
$debit= $this->lang->line('accounts_receivable_common_debit');
$credit= $this->lang->line('accounts_receivable_common_credit');
$tot=$this->lang->line('common_total');
$netbal=$this->lang->line('accounts_receivable_common_net_balance');
$grandto=$this->lang->line('common_grand_total');

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
        <?php echo export_buttons('tbl_vendor_statement', 'Customer Statement'); ?>
    </div>
</div>
<div id="tbl_vendor_statement">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center reportHeaderColor">
                <strong><?php echo $this->common_data['company_data']['company_name'] ?> </strong>
            </div>
            <div class="text-center reportHeader reportHeaderColor"> <?php echo $this->lang->line('accounts_receivable_rs_cs_customer_statement');?><!--Customer Statement--></div>
            <div


                    class="text-center reportHeaderColor"> <?php echo "<strong>$asof<!--As Of-->: </strong>" . $from ?></div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <?php if (!empty($output)) { ?>
                <div class="fixHeader_Div">
                    <table class="borderSpace report-table-condensed" id="tbl_report">
                        <thead class="report-header">
                        <th rowspan="2"> <?php echo $this->lang->line('accounts_receivable_common_doc_date');?><!--Doc Date--></th>
                        <th rowspan="2"><?php echo $this->lang->line('accounts_receivable_common_doc_due_date');?><!--Doc Due Date--></th>
                        <th rowspan="2"><?php echo $this->lang->line('accounts_receivable_common_doc_type');?><!--Doc Type--></th>
                        <th rowspan="2"><?php echo $this->lang->line('accounts_receivable_common_doc_number');?><!--Doc Number--></th>
                        <th rowspan="2"><?php echo $this->lang->line('common_narration');?><!--Narration--></th>
                        <th rowspan="2"><?php echo $this->lang->line('accounts_receivable_common_aging');?><!--Aging--></th>
                        <?php
                        if (!empty($caption)) {
                            foreach ($caption as $val) {
                                if ($val == "Transaction Currency") {
                                    echo '<th colspan="3">' . $val . '</th>';
                                } else {
                                    if ($val == "Reporting Currency") {
                                        echo '<th colspan="2">' . $val . '(' . $this->common_data['company_data']['company_reporting_currency'] . ')</th>';
                                    }
                                    if ($val == "Local Currency") {
                                        echo '<th colspan="2">' . $val . '(' . $this->common_data['company_data']['company_default_currency'] . ')</th>';
                                    }
                                }
                            }
                        }
                        ?>
                        <tr>
                            <?php
                            if (!empty($fieldNameDetails)) {
                                foreach ($fieldNameDetails as $val) {
                                    if ($val['fieldName'] == 'transactionAmount') {
                                        echo '<th>'.$currency.'<!--Currency--></th>';
                                        echo '<th>'.$debit.'<!--Debit--></th>';
                                        echo '<th>'.$credit.'<!--Credit--></th>';
                                    } else {
                                        echo '<th>'.$debit.'<!--Debit--></th>';
                                        echo '<th>'.$credit.'<!--Credit--></th>';
                                    }

                                }
                            }
                            ?>
                        </tr>
                        </thead>
                        <?php
                        $count = 9;
                        $category = array();
                        if ($isTransCost && !$isRptCost && !$isLocCost) {
                            foreach ($output as $val) {
                                $category[$val["GLSecondaryCode"] . " - " . $val["GLDescription"]][$val["customerSystemCode"] . " - " . $val["customerName"]][$val["transactionAmountcurrency"]][] = $val;
                            }
                        } else {
                            foreach ($output as $val) {
                                $category[$val["GLSecondaryCode"] . " - " . $val["GLDescription"]][$val["customerSystemCode"] . " - " . $val["customerName"]][] = $val;
                            }
                        }
                        $grandtotal = array();
                        if ($isTransCost && !$isRptCost && !$isLocCost) {
                            if (!empty($category)) {
                                foreach ($category as $key => $glcodes) {
                                    echo "<tr><td colspan='" . $count . "'><div class='mainCategoryHead2'>" . $key . "</div></td></tr>";
                                    foreach ($glcodes as $key2 => $currency) {
                                        echo "<tr><td colspan='" . $count . "'><div style='margin-left: 15px' class='mainCategoryHead2'>" . $key2 . "</div></td></tr>";
                                        foreach ($currency as $key3 => $customers) {
                                            $subtotal = array();
                                            foreach ($customers as $key4 => $val) {
                                                echo "<tr class='hoverTr'>";
                                                echo "<td><div style='margin-left: 30px'>" . $val["bookingDate"] . "</div></td>";
                                                echo "<td>" . $val["invoiceDueDate"] . "</td>";
                                                echo "<td>" . $val["document"] . "</td>";
                                                echo '<td><a href="#" class="drill-down-cursor" onclick="documentPageView_modal(\'' . $val["documentID"] . '\',' . $val["invoiceAutoID"] . ')">' . $val["bookingInvCode"] . '</a></td>';
                                                echo "<td>" . $val["comments"] . "</td>";
                                                echo "<td>" . $val["age"] . "</td>";
                                                if (!empty($fieldNameDetails)) {
                                                    foreach ($fieldNameDetails as $val2) {
                                                        $subtotal[$val2["fieldName"]][] = $val[$val2["fieldName"]];
                                                        $grandtotal[$val2["fieldName"]][] = $val[$val2["fieldName"]];
                                                        if ($val2["fieldName"] == 'transactionAmount') {
                                                            echo "<td>" . $val[$val2["fieldName"] . "currency"] . "</td>";
                                                            echo print_debit_credit($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]);
                                                        } else {
                                                            echo print_debit_credit($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]);
                                                        }
                                                    }
                                                }
                                                echo "</tr>";
                                            }
                                            echo "<tr>";
                                            echo "<td colspan='7'><div style='margin-left: 30px' class='pull-right'>$tot<!--Total--></div></td>";
                                            if (!empty($fieldNameDetails)) {
                                                foreach ($fieldNameDetails as $key => $val2) {
                                                    $newArray2 = $subtotal[$val2['fieldName']];
                                                    $pos_arr = array();
                                                    $neg_arr = array();
                                                    foreach ($newArray2 as $val) {
                                                        ($val < 0) ? $neg_arr[] = $val : $pos_arr[] = $val;
                                                    }
                                                    $positiveAmount = array_sum($pos_arr);
                                                    $negativeAmount = array_sum($neg_arr);
                                                    if ($val2['fieldName'] == "transactionAmount") {
                                                        echo '<td class="text-right reporttotal">' . number_format($positiveAmount, $this->common_data['company_data']['company_default_decimal']) . '</td>';
                                                        echo '<td class="text-right reporttotal">' . number_format(abs($negativeAmount), $this->common_data['company_data']['company_default_decimal']) . '</td>';
                                                    }
                                                }
                                            }
                                            echo "</tr>";

                                            echo "<tr>";
                                            echo "<td colspan='7'><div style='margin-left: 30px' class='pull-right'>$netbal<!--Net Balance--></div></td>";
                                            if (!empty($fieldNameDetails)) {
                                                foreach ($fieldNameDetails as $key => $val2) {
                                                    $newArray2 = $subtotal[$val2['fieldName']];
                                                    $pos_arr = array();
                                                    $neg_arr = array();
                                                    foreach ($newArray2 as $val) {
                                                        ($val < 0) ? $neg_arr[] = $val : $pos_arr[] = $val;
                                                    }
                                                    $positiveAmount = array_sum($pos_arr);
                                                    $negativeAmount = array_sum($neg_arr);
                                                    $balance = $positiveAmount + $negativeAmount;
                                                    if ($val2['fieldName'] == "transactionAmount") {
                                                        if ($balance < 0) {
                                                            echo "<td class='text-right'></td><td class='text-right reporttotal'>" . number_format(abs($balance), $this->common_data['company_data']['company_default_decimal']) . "</td>";
                                                        } else {
                                                            if ($balance > 0) {
                                                                echo "<td  class='text-right reporttotal'>" . number_format($balance, $this->common_data['company_data']['company_default_decimal']) . "</td><td class='text-right'></td>";
                                                            } else {
                                                                echo "<td  class='text-right reporttotal'></td><td class='text-right reporttotal'></td>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            echo "</tr>";
                                        }
                                    }
                                }
                            }
                        } else {
                            if (!empty($category)) {
                                foreach ($category as $key => $glcodes) {
                                    echo "<tr><td colspan='" . $count . "'><div class='mainCategoryHead2'>" . $key . "</div></td></tr>";
                                    foreach ($glcodes as $key2 => $customers) {
                                        echo "<tr><td colspan='" . $count . "'><div style='margin-left: 15px' class='mainCategoryHead2'>" . $key2 . "</div></td></tr>";
                                        $subtotal = array();
                                        foreach ($customers as $key3 => $val) {
                                            echo "<tr class='hoverTr'>";
                                            echo "<td><div style='margin-left: 30px'>" . $val["bookingDate"] . "</div></td>";
                                            echo "<td>" . $val["invoiceDueDate"] . "</td>";
                                          /*  echo "<td>" . $val["customerAddress"] . "</td>";*/
                                            echo "<td>" . $val["document"] . "</td>";
                                            echo '<td><a href="#" class="drill-down-cursor" onclick="documentPageView_modal(\'' . $val["documentID"] . '\',' . $val["invoiceAutoID"] . ')">' . $val["bookingInvCode"] . '</a></td>';
                                            echo "<td>" . $val["comments"] . "</td>";
                                            echo "<td>" . $val["age"] . "</td>";
                                            if (!empty($fieldNameDetails)) {
                                                foreach ($fieldNameDetails as $val2) {
                                                    $subtotal[$val2["fieldName"]][] = $val[$val2["fieldName"]];
                                                    $grandtotal[$val2["fieldName"]][] = $val[$val2["fieldName"]];
                                                    if ($val2["fieldName"] == 'transactionAmount') {
                                                        echo "<td>" . $val[$val2["fieldName"] . "currency"] . "</td>";
                                                        echo print_debit_credit($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]);
                                                    } else {
                                                        echo print_debit_credit($val[$val2["fieldName"]], $val[$val2["fieldName"] . "DecimalPlaces"]);
                                                    }
                                                }
                                            }
                                            echo "</tr>";
                                        }
                                        echo "<tr>";
                                        if ($isLocCost || $isRptCost) {
                                            if ($isTransCost) {
                                                echo "<td colspan='9'><div style='margin-left: 30px' class='pull-right'>$tot<!--Total--></div></td>";
                                            } else {
                                                echo "<td colspan='6'><div style='margin-left: 30px' class='pull-right'>$tot<!--Total--></div></td>";
                                            }
                                        }
                                        if (!empty($fieldNameDetails)) {
                                            foreach ($fieldNameDetails as $key => $val2) {
                                                $newArray2 = $subtotal[$val2['fieldName']];
                                                $pos_arr = array();
                                                $neg_arr = array();
                                                foreach ($newArray2 as $val) {
                                                    ($val < 0) ? $neg_arr[] = $val : $pos_arr[] = $val;
                                                }
                                                $positiveAmount = array_sum($pos_arr);
                                                $negativeAmount = array_sum($neg_arr);
                                                if ($val2['fieldName'] == "companyLocalAmount") {
                                                    echo '<td class="text-right reporttotal">' . number_format($positiveAmount, $this->common_data['company_data']['company_default_decimal']) . '</td>';
                                                    echo '<td class="text-right reporttotal">' . number_format(abs($negativeAmount), $this->common_data['company_data']['company_default_decimal']) . '</td>';
                                                }
                                                if ($val2['fieldName'] == "companyReportingAmount") {
                                                    echo '<td class="text-right reporttotal">' . number_format($positiveAmount, $this->common_data['company_data']['company_reporting_decimal']) . '</td>';
                                                    echo '<td class="text-right reporttotal">' . number_format(abs($negativeAmount), $this->common_data['company_data']['company_reporting_decimal']) . '</td>';
                                                }
                                            }
                                        }
                                        echo "</tr>";

                                        echo "<tr>";
                                        if ($isLocCost || $isRptCost) {
                                            if ($isTransCost) {
                                                echo "<td colspan='9'><div style='margin-left: 30px' class='pull-right'>$netbal<!--Net Balance--></div></td>";
                                            } else {
                                                echo "<td colspan='6'><div style='margin-left: 30px' class='pull-right'>$netbal<!--Net Balance--></div></td>";
                                            }
                                        }
                                        if (!empty($fieldNameDetails)) {
                                            foreach ($fieldNameDetails as $key => $val2) {
                                                $newArray2 = $subtotal[$val2['fieldName']];
                                                $pos_arr = array();
                                                $neg_arr = array();
                                                foreach ($newArray2 as $val) {
                                                    ($val < 0) ? $neg_arr[] = $val : $pos_arr[] = $val;
                                                }
                                                $positiveAmount = array_sum($pos_arr);
                                                $negativeAmount = array_sum($neg_arr);
                                                $balance = $positiveAmount + $negativeAmount;
                                                if ($val2['fieldName'] == "companyLocalAmount") {
                                                    if ($balance < 0) {
                                                        echo "<td class='text-right'></td><td class='text-right reporttotal'>" . number_format(abs($balance), $this->common_data['company_data']['company_default_decimal']) . "</td>";
                                                    } else {
                                                        if ($balance > 0) {
                                                            echo "<td  class='text-right reporttotal'>" . number_format($balance, $this->common_data['company_data']['company_default_decimal']) . "</td><td class='text-right'></td>";
                                                        } else {
                                                            echo "<td  class='text-right reporttotal'></td><td class='text-right reporttotal'></td>";
                                                        }
                                                    }
                                                }
                                                if ($val2['fieldName'] == "companyReportingAmount") {
                                                    if ($balance < 0) {
                                                        echo "<td class='text-right'></td><td class='text-right reporttotal'>" . number_format(abs($balance), $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                                    } else {
                                                        if ($balance > 0) {
                                                            echo "<td  class='text-right reporttotal'>" . number_format($balance, $this->common_data['company_data']['company_reporting_decimal']) . "</td><td class='text-right'></td>";
                                                        } else {
                                                            echo "<td  class='text-right reporttotal'></td><td class='text-right reporttotal'></td>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td colspan='<?php echo $count; ?>'>&nbsp;</td>
                        </tr>

                        <?php
                        if ($isLocCost || $isRptCost) {
                            if ($isTransCost) {
                                echo "<td colspan='9'><div style='margin-left: 30px' class='pull-right'><strong>$grandto<!--Grand Total--></strong></div></td>";
                            } else {
                                echo "<td colspan='6'><div style='margin-left: 30px' class='pull-right'><strong>$grandto<!--Grand Total--></strong></div></td>";
                            }
                        }
                        if (!empty($fieldNameDetails)) {
                            foreach ($fieldNameDetails as $key => $val2) {
                                $newArray2 = $grandtotal[$val2['fieldName']];
                                $pos_arr = array();
                                $neg_arr = array();
                                foreach ($newArray2 as $val) {
                                    ($val < 0) ? $neg_arr[] = $val : $pos_arr[] = $val;
                                }
                                $positiveAmount = array_sum($pos_arr);
                                $negativeAmount = array_sum($neg_arr);
                                if ($val2['fieldName'] == "companyLocalAmount") {
                                    echo '<td class="text-right reporttotal">' . number_format($positiveAmount, $this->common_data['company_data']['company_default_decimal']) . '</td>';
                                    echo '<td class="text-right reporttotal">' . number_format(abs($negativeAmount), $this->common_data['company_data']['company_default_decimal']) . '</td>';
                                }
                                if ($val2['fieldName'] == "companyReportingAmount") {
                                    echo '<td class="text-right reporttotal">' . number_format($positiveAmount, $this->common_data['company_data']['company_reporting_decimal']) . '</td>';
                                    echo '<td class="text-right reporttotal">' . number_format(abs($negativeAmount), $this->common_data['company_data']['company_reporting_decimal']) . '</td>';
                                }
                            }
                        }
                        echo "</tr>";
                        ?>

                    </table>
                </div>
                <?php
            } else {
                $norecfound=$this->lang->line('common_no_records_found');
                echo warning_message($norecfound);/*"No Records Found!"*/
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