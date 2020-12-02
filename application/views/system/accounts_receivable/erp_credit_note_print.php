<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_receivable', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

echo fetch_account_review(true,true,$approval); ?>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:50%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px" src="<?php echo $logo.$this->common_data['company_data']['company_logo']; ?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h3><strong><?php echo $this->common_data['company_data']['company_name']?></strong></h3>
                            <p><?php echo $this->common_data['company_data']['company_address1'].', '.$this->common_data['company_data']['company_address2'].', '.$this->common_data['company_data']['company_city'].', '.$this->common_data['company_data']['company_country']; ?></p>
                            <h4><?php echo $this->lang->line('accounts_receivable_ap_credit_note');?><!--Credit Note--></h4>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('accounts_receivable_ap_credit_note_number');?><!--Credit Note Number--></strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $extra['master']['creditNoteCode']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('accounts_receivable_ap_credit_note_date');?><!--Credit Note Date--></strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $extra['master']['creditNoteDate']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('common_reference_number');?><!--Reference Number--></strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $extra['master']['docRefNo']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive"><br>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="width:15%;"><strong><?php echo $this->lang->line('common_customer_name');?> <!--Customer Name--> </strong></td>
                <td style="width:2%;"><strong>:</strong></td>
                <td style="width:33%;"> <?php echo $extra['customer']['customerName'].' ( '.$extra['customer']['customerSystemCode'].' )'; ?></td>
                <td style="width:15%;"><strong>&nbsp; </strong></td>
                <td style="width:2%;"><strong>&nbsp;</strong></td>
                <td style="width:33%;">&nbsp;</td>
            </tr>
            <tr>
                <td><strong><?php echo $this->lang->line('accounts_receivable_common_customer_address');?> <!--Customer Address--> </strong></td>
                <td><strong>:</strong></td>
                <td> <?php echo $extra['customer']['customerAddress1']; ?></td>
                <td><strong><?php echo $this->lang->line('common_currency');?><!--Currency--> </strong></td>
                <td><strong>:</strong></td>
                <td><?php echo $extra['master']['CurrencyDes'].' ( '.$extra['master']['transactionCurrency'].' )'; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $this->lang->line('accounts_receivable_common_telephone_fax');?> <!--Telephone / Fax--> </strong></td>
                <td><strong>:</strong></td>
                <td> <?php echo $extra['customer']['customerTelephone'].' / '.$extra['customer']['customerFax']; ?></td>
                <td><strong><?php echo $this->lang->line('common_narration');?><!--Narration--> </strong></td>
                <td><strong>:</strong></td>
                <td colspan="4"> <?php echo $extra['master']['comments']; ?></td>
            </tr>
       </tbody>
    </table>
</div>
<br>
<div class="table-responsive">
    <table id="add_new_grv_table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class='theadtr' colspan="5">Invoice Details</th>
                <th class='theadtr'> <?php echo $this->lang->line('common_amount');?><!--Amount--> </th>
            </tr>
            <tr>
                <th class='theadtr' style="min-width: 5%">#</th>
                <th class='theadtr' style="min-width: 8%">Invoice Code</th>
                <th class='theadtr' style="min-width: 10%"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--></th>
                <th class='theadtr' style="min-width: 35%"><?php echo $this->lang->line('common_gl_code_description');?><!--GL Code Description--></th>
                <th class='theadtr' style="min-width: 15%"><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
                <th class='theadtr' style="min-width: 15%"><?php echo $this->lang->line('common_transaction');?><!--Transaction--> (<?php echo $extra['master']['transactionCurrency']; ?>) </th>
                <!-- <th class='theadtr' style="min-width: 10%">Local  (<?php //echo $extra['master']['companyLocalCurrency']; ?>)</th>
                <th class='theadtr' style="min-width: 15%">Customer (<?php //echo $extra['master']['customerCurrency']; ?>)</th> -->
            </tr>
        </thead>
        <tbody>
            <?php $cus_total = 0;$Local_total = 0;$rporting_total = 0;$grand_total = 0;
            if (!empty($extra['detail'])) {
                    for ($i=0; $i < count($extra['detail']); $i++) {
                        if($extra['detail'][$i]['isFromInvoice']==1){
                        echo '<tr>';
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$extra['detail'][$i]['invoiceSystemCode'].'</td>';
                        echo '<td>'.$extra['detail'][$i]['GLCode'].'</td>';
                        echo '<td>'.$extra['detail'][$i]['GLDescription'].' '.$extra['detail'][$i]['description'].'</td>';
                        echo '<td class="text-center">'.$extra['detail'][$i]['segmentCode'].'</td>';
                        echo '<td class="text-right">'.format_number($extra['detail'][$i]['transactionAmount'], $extra['master']['transactionCurrencyDecimalPlaces']).'</td>';
                        //echo '<td class="text-right">'.format_number($extra['detail'][$i]['companyLocalAmount'],$extra['master']['companyLocalCurrencyDecimalPlaces']).'</td>';
                        //echo '<td class="text-right">'.format_number($extra['detail'][$i]['customerAmount'],$extra['master']['customerCurrencyDecimalPlaces']).'</td>';
                        echo '</tr>';
                        $cus_total   += ($extra['detail'][$i]['transactionAmount']);
                        $grand_total += $extra['detail'][$i]['transactionAmount'];
                        //$Local_total      += ($extra['detail'][$i]['companyLocalAmount']);
                        //$rporting_total   += ($extra['detail'][$i]['customerAmount']);
                    }
                }

            }else{
                $norecfound=$this->lang->line('common_no_records_found');
                echo '<tr class="danger"><td colspan="9" class="text-center"><b>'.$norecfound.'<!--No Records Found--></b></td></tr>';
            }
            ?>    
        </tbody>
        <tfoot>
            <tr>
                <td class="text-right sub_total" colspan="5"><?php echo $this->lang->line('common_total');?><!--Total--> </td>
                <td class="text-right total"><?php echo format_number($cus_total,$extra['master']['transactionCurrencyDecimalPlaces']); ?></td>
                <!-- <td class="text-right total"><?php //echo format_number($Local_total,$extra['master']['companyLocalCurrencyDecimalPlaces']); ?></td>
                <td class="text-right total"><?php //echo format_number($rporting_total,$extra['master']['customerCurrencyDecimalPlaces']); ?></td> -->
            </tr>
        </tfoot>
    </table>
</div>
<br>
<div class="table-responsive">
    <table id="add_new_grv_table_gl" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th class='theadtr' colspan="4"><?php echo $this->lang->line('common_gl_details');?><!--GL Details--></th>
            <th class='theadtr'> <?php echo $this->lang->line('common_amount');?><!--Amount--> </th>
        </tr>
        <tr>
            <th class='theadtr' style="min-width: 5%">#</th>
            <th class='theadtr' style="min-width: 10%"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--></th>
            <th class='theadtr' style="min-width: 35%"><?php echo $this->lang->line('common_gl_code_description');?><!--GL Code Description--></th>
            <th class='theadtr' style="min-width: 15%"><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
            <th class='theadtr' style="min-width: 15%"><?php echo $this->lang->line('common_transaction');?><!--Transaction--> (<?php echo $extra['master']['transactionCurrency']; ?>) </th>
            <!-- <th class='theadtr' style="min-width: 10%">Local  (<?php //echo $extra['master']['companyLocalCurrency']; ?>)</th>
                <th class='theadtr' style="min-width: 15%">Customer (<?php //echo $extra['master']['customerCurrency']; ?>)</th> -->
        </tr>
        </thead>
        <tbody>
        <?php $cus_total_gl = 0;$Local_total = 0;$rporting_total = 0;
        if (!empty($extra['detail'])) {

            for ($i=0; $i < count($extra['detail']); $i++) {
                if($extra['detail'][$i]['isFromInvoice']==0){
                    echo '<tr>';
                    echo '<td>'.($i+1).'</td>';
                    echo '<td>'.$extra['detail'][$i]['GLCode'].'</td>';
                    echo '<td>'.$extra['detail'][$i]['GLDescription'].' '.$extra['detail'][$i]['description'].'</td>';
                    echo '<td class="text-center">'.$extra['detail'][$i]['segmentCode'].'</td>';
                    echo '<td class="text-right">'.format_number($extra['detail'][$i]['transactionAmount'], $extra['master']['transactionCurrencyDecimalPlaces']).'</td>';
                    echo '</tr>';
                    $cus_total_gl   += ($extra['detail'][$i]['transactionAmount']);
                    $grand_total += $extra['detail'][$i]['transactionAmount'];
                    //$Local_total      += ($extra['detail'][$i]['companyLocalAmount']);
                    //$rporting_total   += ($extra['detail'][$i]['customerAmount']);
                }
            }

        }else{
            $norecfound=$this->lang->line('common_no_records_found');
            echo '<tr class="danger"><td colspan="8" class="text-center"><b>'.$norecfound.'<!--No Records Found--></b></td></tr>';
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-right sub_total" colspan="4"><?php echo $this->lang->line('common_total');?><!--Total--> </td>
            <td class="text-right total"><?php echo format_number($cus_total_gl,$extra['master']['transactionCurrencyDecimalPlaces']); ?></td>
        </tr>
        </tfoot>
    </table>
</div>
<br>
<h5 class="text-right"><!--Grand Total--><?php echo $this->lang->line('common_grand_total');?> (<?php echo $extra['master']['transactionCurrency']; ?> )
    : <?php echo format_number($grand_total, $extra['master']['transactionCurrencyDecimalPlaces']); ?></h5>
<div class="table-responsive">
    <br>
    <table style="width: 100%">
        <tbody>
        <?php if($extra['master']['confirmedYN']==1){ ?>
        <tr>
            <td><b>Confirmed By</b></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['confirmedYNn']; ?></td>
        </tr>
        <?php } ?>
        <?php if($extra['master']['approvedYN']){ ?>
            <tr>
                <td style="width:30%;"><b><?php echo $this->lang->line('common_electronically_approved_by');?><!--Electronically Approved By--> </b></td>
                <td><strong>:</strong></td>
                <td style="width:70%;"><?php echo $extra['master']['approvedbyEmpName']; ?></td>
            </tr>
            <tr>
                <td style="width:30%;"><b><?php echo $this->lang->line('common_electronically_approved_date');?><!--Electronically Approved Date--> </b></td>
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
    a_link=  "<?php echo site_url('Receivable/load_cn_conformation'); ?>/<?php echo $extra['master']['creditNoteMasterAutoID'] ?>";
    de_link="<?php echo site_url('Double_entry/fetch_double_entry_credit_note'); ?>/" + <?php echo $extra['master']['creditNoteMasterAutoID'] ?> + '/CN';
    $("#a_link").attr("href",a_link);
    $("#de_link").attr("href",de_link);

</script>