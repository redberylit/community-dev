<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_receivable', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$datefrom = $this->lang->line('accounts_receivable_common_date_from');
$dateto = $this->lang->line('accounts_receivable_common_date_to');
$currency = $this->lang->line('common_currency');
$netbalance = $this->lang->line('accounts_receivable_common_net_balance');
$subtot = $this->lang->line('accounts_receivable_common_sub_tot');

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
            echo export_buttons('tbl_customer_ledger', 'Farm Ledger');
        } ?>
    </div>
</div>
<div id="tbl_customer_ledger">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center reportHeaderColor">
                <strong><?php echo $this->common_data['company_data']['company_name'] ?> </strong>
            </div>
            <div class="text-center reportHeader reportHeaderColor"> Farm Ledger</div>
            <div
                class="text-center reportHeaderColor"> <?php echo "<strong>As of : </strong>" . $from .  $to ?></div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <?php if (!empty($output)) { ?>
                <div class="fixHeader_Div">
                    <table class="borderSpace report-table-condensed" id="tbl_report">
                        <thead class="report-header">
                        <th rowspan="2">Farm</th>
                        <th rowspan="2">Batch Code</th>
                        <th rowspan="2">Doc Date</th>
                        <th rowspan="2">Doc Type</th>
                        <th rowspan="2">Doc Number</th>
                        <th rowspan="2">Narration</th>
                        <?php
                        if (!empty($caption)) {
                            foreach ($caption as $val) {
                                if ($val == "Transaction Currency") {
                                    echo '<th>' . $currency . '<!--Currency--></th>';
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
                        </thead>
                        <?php
                        $count = 10;
                        $category = array();
                        $date_format = date_format_policy();
                        foreach ($output as $val) {
                            $category[$val["GLSecondaryCode"] . " - " . $val["GLDescription"]][$val["farmSystemCode"] . " - " . $val["farmName"]][] = $val;
                        }
                        if (!empty($category)) {
                            foreach ($category as $key => $glcodes) {
                                $grandtotal = array();
                                echo "<tr><td colspan='" . $count . "'><div class='mainCategoryHead'>" . $key . "</div></td></tr>";
                                foreach ($glcodes as $key2 => $customer) {
                                  //  echo "<tr><td colspan='" . $count . "'><div style='margin-left: 15px' class='mainCategoryHead2'>" . $key2 . "</div></td></tr>";
                                    $subtotal = array();
                                    foreach ($customer as $key3 => $val) {
                                      //  echo "<tr class='hoverTr'>";
                                        echo "<td>" . $key2 . "</td>";
                                        echo "<td>" . $val["batchCode"] . "</td>";
                                        if (input_format_date($val["documentDate"], $date_format) == '1970-01-01') {
                                            echo "<td><div style='margin-left: 30px;color: #ffffff;opacity: 0'>" . $val["documentDate"] . "</div></td>";
                                        } else {
                                            echo "<td><div style='margin-left: 30px'>" . $val["documentDate"] . "</div></td>";
                                        }
                                        echo "<td>" . $val["documentName"] . "</td>";
                                        echo '<td><a href="#" class="drill-down-cursor" onclick="documentPageView_modal(\'' . $val["documentCode"] . '\',\'' . $val["documentMasterAutoID"] . '\',\' '. $val["batchMasterID"] .'\')">' . $val["documentSystemCode"] . '</a></td>';
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
                                            echo "<td colspan='8'><div style='margin-left: 30px'>$netbalance<!--Net Balance--></div></td>";
                                        } else {
                                            echo "<td colspan='6'><div style='margin-left: 30px'>$netbalance<!--Net Balance--></div></td>";
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
                                    if ($isTransCost) {
                                        echo "<td colspan='8'><div style='margin-left: 30px'><strong>$subtot<!--Sub Total--></strong></div></td>";
                                    } else {
                                        echo "<td colspan='6'><div style='margin-left: 30px'><strong>$subtot<!--Sub Total--></strong></div></td>";
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
                $norecfound = $this->lang->line('common_no_records_found');
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