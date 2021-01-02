<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

echo fetch_account_review(false); ?>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:50%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px"
                                 src="<?php echo $logo . $this->common_data['company_data']['company_logo']; ?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h3>
                                <strong><?php echo $this->common_data['company_data']['company_name'] . ' (' . $this->common_data['company_data']['company_code'] . ').'; ?></strong>
                            </h3>
                            <h4><?php echo $this->lang->line('crm_quotation');?> </h4><!--Quotation-->
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('crm_quotation_number');?> </strong></td><!--Quotation Number-->

                        <td><strong>:</strong></td>
                        <td><?php echo $master['quotationCode'] ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('crm_quotation_date');?> </strong></td><!--Quotation Date-->
                        <td><strong>:</strong></td>
                        <td><?php echo $master['quotationDate']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('common_reference_number');?> </strong></td><!--Reference Number-->
                        <td><strong>:</strong></td>
                        <td><?php echo $master['referenceNo']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('crm_organization_name');?> </strong></td><!--Organization Name-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['organizationName']; ?></td>

            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('common_currency');?> </strong></td><!--Currency-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['CurrencyCode'] ?></td>
        </tr>
        <tr>
            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('crm_organization_address');?> </strong></td><!--Organization Address-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['orgAddress']; ?></td>

            <td style="width:18%;vertical-align: top;"><strong><?php echo $this->lang->line('crm_quotations_expiry_date');?></strong></td><!--Quotations Expiry Date-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:30%;vertical-align: top;"><?php echo $master['quotationExpDate'] ?></td>
        </tr>
        <tr>
            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('crm_telephone_fax');?></strong></td><!--Telephone / Fax-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['telephoneNo']; ?></td>

            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('common_narration');?></strong></td><!--Narration-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['quotationNarration'] ?></td>
        </tr>
        <tr>
            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('crm_contact_person_name');?></strong></td><!--Contact Person Name-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['quotationPersonName']; ?></td>

            <td style="width:15%;vertical-align: top;"><strong><?php echo $this->lang->line('crm_person_telephone_number');?> </strong></td><!--Person's Telephone Number-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:33%;vertical-align: top;"><?php echo $master['quotationPersonNumber'] ?></td>
        </tr>
        </tbody>
    </table>
</div>
<br>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class='thead'>
        <tr>
            <th style="min-width: 4%" class='theadtr'>#</th>
            <th style="min-width: 20%" class='theadtr'><?php echo $this->lang->line('crm_product');?> </th><!--Product-->
            <th style="min-width: 10%" class='theadtr'><?php echo $this->lang->line('crm_product');?> </th><!--UOM-->
            <th style="min-width: 5%" class='theadtr'><?php echo $this->lang->line('crm_delivery_date');?></th><!--Delivery Date-->
            <th style="min-width: 5%" class='theadtr'><?php echo $this->lang->line('common_narration');?> </th><!--Narration-->
            <th style="min-width: 10%" class='theadtr'><?php echo $this->lang->line('common_qty');?></th><!--Qty-->
            <th style="min-width: 10%" class='theadtr'><?php echo $this->lang->line('common_price');?></th><!--Price-->
            <th style="min-width: 10%" class='theadtr'><?php echo $this->lang->line('crm_total_price');?></th><!--Total Price-->
        </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        $num = 1;
        $lineTotal = 0;
        $grandTotal = 0;
        if (!empty($detail)) {
            foreach ($detail as $val) { ?>
                <tr>
                    <td class="text-right"><?php echo $num; ?>.&nbsp;</td>
                    <td class="text-center"><?php echo $val['productName']; ?></td>
                    <td class="text-center"><?php echo $val['unitOfMeasure']; ?></td>
                    <td class="text-center"><?php echo $val['expectedDeliveryDate']; ?></td>
                    <td class="text-center"><?php echo $val['comment']; ?></td>
                    <td class="text-center"><?php echo $val['requestedQty']; ?></td>
                    <td class="text-right"><?php echo number_format($val['unittransactionAmount'], 2); ?></td>
                    <td class="text-right"><?php
                        $lineTotal = $val['requestedQty'] * $val['unittransactionAmount'];
                        echo number_format($lineTotal, 2);
                        ?>
                    </td>
                </tr>
                <?php
                $num++;
                $total += $val['unittransactionAmount'];
                $grandTotal += $lineTotal;
            }
        } else {
            $norecfound= $this->lang->line('common_no_records_found');
            echo '<tr class="danger"><td colspan="9" class="text-center">'.$norecfound.'</td></tr>';
        } ?><!--No Records Found-->
        </tbody>
        <tfoot>
        <tr>
            <td style="min-width: 85%  !important" class="text-right sub_total" colspan="6">
                Total <?php echo $master['CurrencyCode']; ?></td>
            <td style="min-width: 15% !important"
                class="text-right total"><?php echo number_format($total, 2); ?></td>
            <td style="min-width: 15% !important"
                class="text-right total"><?php echo number_format($grandTotal, 2); ?></td>
        </tr>
        </tfoot>
    </table>
</div>
<br>
<hr>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:23%;"><strong><?php echo $this->lang->line('common_confirmed_by');?></strong></td><!--Confirmed By-->
            <td style="width:2%;"><strong>:</strong></td>
            <td style="width:75%;"><?php echo $master['qutConfirmedUser']; ?></td>
        </tr>
        <tr>
            <td><strong> <?php echo $this->lang->line('common_confirmed_date');?> </strong></td><!--Confirmed Date-->
            <td><strong>:</strong></td>
            <td> <?php echo $master['qutConfirmDate']; ?></td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td><strong> <?php echo $this->lang->line('crm_terms_and_condition');?> : </strong></td><!--Terms & conditions-->
            <td></td>
        </tr>
        <tr>
            <td colspan="2"> <?php echo $master['termsAndConditions']; ?></td>
        </tr>
        </tbody>
    </table>
</div>
