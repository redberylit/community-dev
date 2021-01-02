<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_receivable', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$datefrom = $this->lang->line('accounts_receivable_common_date_from');
$dateto = $this->lang->line('accounts_receivable_common_date_to');
$currency = $this->lang->line('common_currency');
$netbalance = $this->lang->line('accounts_receivable_common_net_balance');

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
            echo export_buttons('tbl_farm_outstanding', 'Outstanding');
        } ?>
    </div>
    </div>

    <div id="tbl_customer_ledger">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center reportHeaderColor">
                    <strong><?php echo $this->common_data['company_data']['company_name'] ?> </strong>
                </div>
                <div class="text-center reportHeader reportHeaderColor">Farm Outstanding</div>
                <div
                    class="text-center reportHeaderColor"> <?php echo "<strong>As of : </strong>" . $from .  $to ?></div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <?php if (!empty($output)) { ?>
                    <div class="fixHeader_Div">
                        <table class="borderSpace report-table-condensed" id="tbl_farm_outstanding">
                            <thead class="report-header">
                            <th>Farm</th>
                            <th>Batch Code</th>
                            <th>Batch Date</th>
                            <th>Description</th>
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
                                $wagesPayable = wagesPayableAmount($val['batchMasterID']);

                                echo '<tr>';
                                echo '<td>' . $val['farmSystemCode'] . ' - ' . $val['farmName'] . '<!--farm--></td>';
                                echo '<td>' . $val['batchCode'] . '<!--bach--></td>';
                                echo '<td>' . $val['batchClosingDate'] . '<!--closing date--></td>';
                                echo '<td>' . $val['description'] . '<!--batch description--></td>';


                                if (!empty($fieldName)) {
                                    foreach ($fieldName as $val2) {
                                        $subtotal[$val2][] = (float)$wagesPayable[$val2];
                                        $grandtotal[$val2][] = (float)$wagesPayable[$val2];
                                        if ($val2 == 'transactionAmount') {
                                            echo "<td>" . $val["CurrencyCode"] . "</td>";
                                            echo "<td class='text-right'>" . format_number($wagesPayable[$val2], 2) . "</td>";
                                        } else {
                                            echo "<td class='text-right'>" . format_number($wagesPayable[$val2], 2) . "</td>";
                                        }
                                    }
                                }
                                echo '</tr>';



                           }
                         //   echo "<tr><td colspan='" . $count . "'>&nbsp;</td></tr>";
                            echo "<tr>";
                            if ($isLocCost || $isRptCost) {
                                if ($isTransCost) {
                                    echo "<td colspan='5'><div style='margin-left: 30px'><strong>Total Amount<!--Total Amount--></strong></div></td>";
                                } else {
                                    echo "<td colspan='4'><div style='margin-left: 30px'><strong>Total Amount<!--Total Amount--></strong></div></td>";
                                }
                            } else{
                                echo "<td colspan='5'><div style='margin-left: 30px'><strong>Total Amount<!--Total Amount--></strong></div></td>";
                            }
                            if (!empty($fieldName)) {
                                foreach ($fieldName as $val2) {
                                    if ($val2 == "transactionAmount") {
                                        echo "<td class='text-right reporttotal'>" . format_number(array_sum($grandtotal[$val2]), $this->common_data['company_data']['company_default_decimal']) . "</td>";
                                    }
                                    if ($val2 == "companyLocalAmount") {
                                        echo "<td class='text-right reporttotal'>" . format_number(array_sum($grandtotal[$val2]), $this->common_data['company_data']['company_default_decimal']) . "</td>";
                                    }
                                    if ($val2 == "companyReportingAmount") {
                                        echo "<td class='text-right reporttotal'>" . format_number(array_sum($grandtotal[$val2]), $this->common_data['company_data']['company_reporting_decimal']) . "</td>";
                                    }
                                }
                            }
                            echo "</tr>";
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

</script>

<?php
/**
 * Created by PhpStorm.
 * User: Safeena
 * Date: 10/29/2018
 * Time: 3:52 PM
 */