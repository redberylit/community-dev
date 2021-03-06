<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('inventory', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);


echo fetch_account_review(true, true, $approval); ?>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:50%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px"
                                 src="<?php echo mPDFImage . $this->common_data['company_data']['company_logo']; ?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h3>
                                <strong><?php echo $this->common_data['company_data']['company_name']; ?></strong>
                            </h3>
                            <h4><?php echo $this->lang->line('transaction_material_issue'); ?> </h4>
                            <!--Material Issue-->
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('transaction_material_issue_number'); ?> </strong></td>
                        <!--Material issue Number-->
                        <td><strong>:</strong></td>
                        <td><?php echo $extra['master']['itemIssueCode']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('transaction_material_issue_date'); ?> </strong></td>
                        <!--Material Issue Date-->
                        <td><strong>:</strong></td>
                        <td><?php echo $extra['master']['issueDate']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('common_reference_number'); ?></strong></td>
                        <!--Reference Number-->
                        <td><strong>:</strong></td>
                        <td><?php echo $extra['master']['issueRefNo']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <table style="width: 100%;font-size:12px;">
        <tbody>
        <tr>
            <td style="width:20%;">
                <strong><?php echo $this->lang->line('transaction_material_requested_by'); ?> </strong></td>
            <!--Requested By-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:78%;"><?php echo $extra['master']['employeeName'] . ' (' . $extra['master']['employeeCode'] . ' ) '; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $this->lang->line('transaction_common_phone'); ?> </strong></td><!--Phone-->
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['employeePhone']; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $this->lang->line('common_fax'); ?> </strong></td><!--Fax-->
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['employeeFax']; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $this->lang->line('common_email'); ?> </strong></td><!--Email-->
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['employeeEmail']; ?></td>
        </tr>
        <tr>
            <td style="width:15%;"><strong>Issued Warehouse</strong></td>
            <!--Warehouse-->
            <td><strong>:</strong></td>
            <td style="width:85%;"><?php echo $extra['master']['wareHouseDescription'] . ' ( ' . $extra['master']['wareHouseCode'] . ' )'; ?></td>
        </tr>
        <?php
        if ($extra['master']['issueType'] == 'Material Request') { ?>
            <tr>
                <td style="width:15%;"><strong>Requested Warehouse </strong></td>
                <!--Warehouse-->
                <td><strong>:</strong></td>
                <td style="width:85%;"><?php echo $extra['master']['requestedWareHouseDescription'] . ' ( ' . $extra['master']['requestedWareHouseCode'] . ' )'; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td><strong><?php echo $this->lang->line('transaction_common_narration'); ?> </strong></td><!--Narration-->
            <td><strong>:</strong></td>
            <td colspan="4"><?php echo $extra['master']['comment']; ?></td>
        </tr>

        <tr>
            <td><strong>Financial Period </strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['FYbegining'] .' - ' . $extra['master']['FYend'] ?> </td>
        </tr>
        <tr>
            <td><strong>Primary Segment </strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['segmentCode'] ?> </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
$mrClass = '';
$colspan = 7;
if ($extra['master']['issueType'] != 'Material Request') {
    $mrClass = 'hide';
    $colspan = 6;
}?>
<div class="table-responsive">
    <br>
    <table class="table table-bordered table-striped">
        <thead>
        <!-- <tr>
            <th class='theadtr' colspan="4">Item Details</th>
            <th class='theadtr' colspan="1">Qty </th>
            <th>&nbsp;</th>
        </tr> -->
        <tr>
            <th class='theadtr' style="min-width: 5%">#</th>
            <th class='theadtr <?php echo $mrClass; ?>' style="min-width: 10%">MR Code</th>
            <th class='theadtr'
                style="min-width: 10%"><?php echo $this->lang->line('transaction_common_item_code'); ?></th>
            <!--Item Code-->
            <th class='theadtr'
                style="min-width: 40%"><?php echo $this->lang->line('transaction_common_item_description'); ?></th>
            <!--Item Description-->
            <th class='theadtr'
                style="min-width: 10%">Cost GL A/C</th>

            <th class='theadtr' style="min-width: 10%"><?php echo $this->lang->line('transaction_common_uom'); ?></th>
            <!--UOM-->
            <!--<th class='theadtr' style="min-width: 10%">Requested</th>-->
            <th class='theadtr'
                style="min-width: 10%"><?php echo $this->lang->line('transaction_material_issued'); ?></th><!--Issued-->
            <th class='theadtr' style="min-width: 15%"><?php echo $this->lang->line('common_value'); ?>
                (<?php echo $extra['master']['companyLocalCurrency']; ?>)
            </th><!--Value-->
        </tr>
        </thead>
        <tbody>
        <?php
        $num = 1;
        $total_count = 0;
        if (!empty($extra['detail'])) {
            foreach ($extra['detail'] as $val) { ?>
                <tr>
                    <td style="text-align:right;"><?php echo $num; ?>.&nbsp;</td>
                    <td class="<?php echo $mrClass; ?>"><?php echo $val['MRCode']; ?>.&nbsp;</td>
                    <td style="text-align:center;"><?php echo $val['itemSystemCode']; ?></td>
                    <td><?php echo $val['itemDescription']; ?></td>
                    <td><?php echo $val['costglname']; ?></td>
                    <td style="text-align:center;"><?php echo $val['unitOfMeasure']; ?></td>
                    <!--<td style="text-align:right;"><?php /*echo $val['qtyRequested']; */ ?></td>-->
                    <td style="text-align:right;"><?php echo $val['qtyIssued']; ?></td>
                    <td style="text-align:right;"><?php echo format_number($val['totalValue'], $extra['master']['companyLocalCurrencyDecimalPlaces']); ?></td>
                </tr>
                <?php
                $num++;
                $total_count += $val['totalValue'];
            }
        } else {
            $norecfound = $this->lang->line('common_no_records_found');
            echo '<tr class="danger"><td colspan="8" class="text-center">' . $norecfound . '<!--No Records Found--></td></tr>';
        } ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-right sub_total" colspan="<?php echo $colspan; ?>"><?php echo $this->lang->line('transaction_common_item_total'); ?>
                (<?php echo $extra['master']['companyLocalCurrency']; ?>)
            </td><!--Item Total-->
            <td class="text-right total"><?php echo format_number($total_count, $extra['master']['companyLocalCurrencyDecimalPlaces']); ?></td>
        </tr>
        </tfoot>
    </table>
</div>
<div class="table-responsive">
    <hr>
<table style="width: 100%">
    <tbody>
    <tr>
        <td style="width:30%;"><b>Confirmed By </b></td>
        <td><strong>:</strong></td>
        <td style="width:70%;"> <?php echo $extra['master']['confirmedYNn']; ?></td>
    </tr>
    <?php if ($extra['master']['approvedYN']) { ?>
        <tr>
            <td style="width:30%;">
                <b><?php echo $this->lang->line('transaction_common_electronically_approved_by'); ?> </b></td>
            <!--Electronically Approved By-->
            <td><strong>:</strong></td>
            <td style="width:70%;"><?php echo $extra['master']['approvedbyEmpName']; ?></td>
        </tr>
        <tr>
            <td style="width:30%;">
                <b><?php echo $this->lang->line('transaction_common_electronically_approved_date'); ?> </b></td>
            <!--Electronically Approved Date-->
            <td><strong>:</strong></td>
            <td style="width:70%;"><?php echo $extra['master']['approvedDate']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
<br>
<br>
<br>
<br>
<?php if($extra['master']['approvedYN']){ ?>
    <?php
    if ($signature) { ?>
        <?php
        if ($signature['approvalSignatureLevel'] <= 2) {
            $width = "width: 50%";
        } else {
            $width = "width: 100%";
        }
        ?>
        <div class="table-responsive">
            <table style="<?php echo $width ?>">
                <tbody>
                <tr>
                    <?php
                    for ($x = 0; $x < $signature['approvalSignatureLevel']; $x++) {

                        ?>

                        <td>
                            <span>____________________________</span><br><br><span><b>&nbsp; Authorized Signature</b></span>
                        </td>

                        <?php
                    }
                    ?>
                </tr>


                </tbody>
            </table>
        </div>
    <?php } ?>
<?php } ?>

<script>
    $('.review').removeClass('hide');
    a_link = "<?php echo site_url('Inventory/load_material_issue_conformation'); ?>/<?php echo $extra['master']['itemIssueAutoID'] ?>";
    de_link = "<?php echo site_url('Double_entry/fetch_double_material_issue'); ?>/" + <?php echo $extra['master']['itemIssueAutoID'] ?> +'/MI';
    $("#a_link").attr("href", a_link);
    $("#de_link").attr("href", de_link);
</script>
