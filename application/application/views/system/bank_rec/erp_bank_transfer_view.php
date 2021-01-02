<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('treasury', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>
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
                            <h4> <?php echo $this->lang->line('treasury_bta_bank_transfer');?><!--Bank Transfer--></h4>
                        </td>
                    </tr>

                    <tr>
                        <td><strong><?php echo $this->lang->line('common_code');?><!--Code--></strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $master['bankTransferCode']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $this->lang->line('common_document_date');?><!--Document Date--></strong></td>
                        <td><strong>:</strong></td>
                        <td><?php echo $master['transferedDate']; ?></td>
                    </tr>


                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <br>
    <table style="width: 100%">
        <tbody>
        <tr>
            <td><strong><?php echo $this->lang->line('treasury_bta_bank_from');?><!--Bank From --></strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $master['bankfrom']; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $this->lang->line('treasury_bta_bank_to');?><!--Bank To--></strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $master['bankto']; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $this->lang->line('treasury_common_reference_no');?><!--Reference No--></strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $master['referenceNo']; ?></td>
        </tr>


        <tr>
            <td><strong><?php echo $this->lang->line('common_narration');?><!--Narration--></strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $master['narration']; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $this->lang->line('common_amount');?><!--Amount--></strong></td>
            <td><strong>:</strong></td>
            <td><?php echo number_format($master['transferedAmount'],2); ?> (<?php echo $master['CurrencyCode'];?>)</td>
        </tr>
        </tbody>
    </table>
</div>




<div class="table-responsive">
    <br>
    <table style="width: 100%">
        <tbody>
        <?php if($master['confirmedYN']==1){ ?>
        <tr>
            <td><b>Confirmed By</b></td>
            <td><strong>:</strong></td>
            <td><?php echo $master['confirmedYNn']; ?></td>
        </tr>
        <?php }?>
        <?php if($master['approvedYN']){ ?>
        <tr>
            <td style="width:30%;"><b><?php echo $this->lang->line('common_electronically_approved_by');?><!--Electronically Approved By--> </b></td>
            <td><strong>:</strong></td>
            <td style="width:70%;"><?php echo $master['approvedbyEmpName']; ?></td>
        </tr>
        <tr>
            <td style="width:30%;"><b><?php echo $this->lang->line('common_electronically_approved_date');?><!--Electronically Approved Date --></b></td>
            <td><strong>:</strong></td>
            <td style="width:70%;"><?php echo $master['approvedDate']; ?></td>
        </tr>
        <?php }?>
        </tbody>
    </table>
</div>
<br>


