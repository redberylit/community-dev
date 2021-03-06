<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_payable', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$this->load->helper('configuration_helper');
$projectExist = project_is_exist();
$colspan = 3;
if ($projectExist == 1) {
    $colspan = 4;
 } ?>
<div class="modal-body">
    <table class="table table-bordered table-striped table-condesed ">
        <thead>
        <tr>
            <th colspan="4"><?php echo $this->lang->line('accounts_payable_invoice_details');?><!--Invoice Details--></th>
            <th colspan="<?php echo $colspan; ?>"><?php echo $this->lang->line('accounts_payable_debit_note');?><!--Debit Note--> <span class="currency">( <?php echo $master['transactionCurrency']; ?> )</span>
            </th>
        </tr>
        <tr>
            <th style="width: 3%">#</th>
            <th style="width: 25%"><?php echo $this->lang->line('accounts_payable_invoice_code');?><!--Invoice Code--></th>
            <th style="width: 10%"><?php echo $this->lang->line('common_amount');?><!--Amount--></th>
            <!-- <th style="width: 10%">Paid </th>
            <th style="width: 10%">Dabit </th> -->
            <th style="width: 10%"><?php echo $this->lang->line('accounts_payable_balance');?><!--Balance--></th>
            <th style="width: 12%"><?php echo $this->lang->line('common_gl_code');?><!--GL Code--></th>
            <th style="width: 10%"><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
            <?php if ($projectExist == 1) { ?>
                <th style="width: 10%"><?php echo $this->lang->line('common_project');?><!--Project--></th>
            <?php } ?>
            <th style="width: 10%"><?php echo $this->lang->line('common_amount');?><!--Amount--></th>
        </tr>
        </thead>
        <tbody id="table_inv_body">
        <?php
        if (!empty($detail)) {
            $segment_arr = fetch_segment();
            $gl_code_arr = fetch_all_gl_codes();
            for ($i = 0; $i < count($detail); $i++) {
                $balance = $detail[$i]['transactionAmount'] - ($detail[$i]['paymentTotalAmount'] + $detail[$i]['DebitNoteTotalAmount']+$detail[$i]['advanceMatchedTotal']);
                if ($balance > 0) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td>' . $detail[$i]['bookingInvCode'] . ' - ' . $detail[$i]['bookingDate'] . '</td>';

                    echo '<td class="text-right">' . number_format($detail[$i]['transactionAmount'], $master['transactionCurrencyDecimalPlaces']) . '</td>';
                    echo '<td class="text-right">' . number_format($balance, $master['transactionCurrencyDecimalPlaces']) . '</td>';
                    echo '<td>' . form_dropdown('gl_code[]', $gl_code_arr, '', ' id="gl_code_' . $detail[$i]['InvoiceAutoID'] . '" style="width: 100px"') . '</td>';
                    echo '<td>' . form_dropdown('segment[]', $segment_arr, $this->common_data['company_data']['default_segment'], 'id="segment_' . $detail[$i]['InvoiceAutoID'] . '" onchange="load_segmentBase_projectID_income(this,'.$detail[$i]['InvoiceAutoID'].')" style="width: 100px"') . '</td>';
                    if ($projectExist == 1) {
                        $selectproject=$this->lang->line('common_select_project');
                        echo '<td> <div class="div_projectID_income"><select name="projectID"><option value="">'.$selectproject.'<!--Select Project--></option></select></div> </td>';
                    }
                    //echo '<td class="text-right">'.number_format($detail[$i]['paymentTotalAmount'],$master['transactionCurrencyDecimalPlaces']).'</td>';
                    //echo '<td class="text-right">'.number_format($detail[$i]['DebitNoteTotalAmount'],$master['transactionCurrencyDecimalPlaces']).'</td>';

                    echo '<td class="text-right"><input type="hidden" name="code[]" style="width: 100px" id="code_' . $detail[$i]['InvoiceAutoID'] . '" value="' . $detail[$i]['bookingInvCode'] . '"><input type="text" name="amount[]" style="width: 100px" id="amount_' . $detail[$i]['InvoiceAutoID'] . '" onkeyup="select_check_box(this,' . $detail[$i]['InvoiceAutoID'] . ',' . $balance . ')" class="number"></td>';
                    echo '<td class="text-right" style="display:none;"><input class="checkbox" id="check_' . $detail[$i]['InvoiceAutoID'] . '" type="checkbox" value="' . $detail[$i]['InvoiceAutoID'] . '"></td>';
                    echo '</tr>';
                }
            }
        } else {
            $norecfound=$this->lang->line('common_no_records_found');
            echo '<tr class="danger">';
            echo '<td class="text-center" colspan="8">'.$norecfound.'<!--No Recode Found--></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-default" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
    <button class="btn btn-primary" onclick="save_debit_base_items()"><?php echo $this->lang->line('common_save_change');?><!--Save changes--></button>
</div>
<script type="text/javascript">
    debitNoteMasterAutoID = <?php echo json_encode(trim($master['debitNoteMasterAutoID'])); ?>;
    $( document ).ready(function() {
        number_validation();
    });

    function select_check_box(data, id, total) {
        $("#check_" + id).prop("checked", false)
        if (data.value > 0) {
            if (total >= data.value) {
                $("#check_" + id).prop("checked", true);
            } else {
                $("#check_" + id).prop("checked", false);
                $("#amount_" + id).val('');
                myAlert('w', '<?php echo $this->lang->line('accounts_payable_you_can_not_enter_an_invoice');?>');/*You can not enter an invoice amount greater than selected Dabit Amount*/
            }
        }
    }

    function save_debit_base_items() {
        var selected = [];
        var code = [];
        var amount = [];
        var segment = [];
        var segment_dec = [];
        var gl_code = [];
        var gl_code_dec = [];
        var project = [];

        $('#table_inv_body input:checked').each(function () {
            selected.push($(this).val());
            code.push($('#code_' + $(this).val()).val());
            amount.push($('#amount_' + $(this).val()).val());
            project.push($('#projectID_' + $(this).val()).val());
            segment.push($('#segment_' + $(this).val()).val());
            segment_dec.push($('#segment_' + $(this).val() + ' option:selected').text());
            gl_code.push($('#gl_code_' + $(this).val()).val());
            gl_code_dec.push($('#gl_code_' + $(this).val() + ' option:selected').text());
        });
        if (!jQuery.isEmptyObject(selected)) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {
                    'InvoiceAutoID': selected,
                    'bookingInvCode': code,
                    'project': project,
                    'segment': segment,
                    'segment_dec': segment_dec,
                    'amounts': amount,
                    'gl_code': gl_code,
                    'gl_code_dec': gl_code_dec,
                    'debitNoteMasterAutoID': debitNoteMasterAutoID
                },
                url: "<?php echo site_url('Payable/save_debit_base_items'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    refreshNotifications(true);
                    stopLoad();
                    if (data) {
                        $('#dn_detail_modal').modal('hide');
                        setTimeout(function () {
                            fetch_dn_details();
                        }, 300);
                    }
                }, error: function () {
                    $('#dn_detail_modal').modal('hide');
                    stopLoad();
                    swal("Cancelled", "Try Again ", "error");
                }
            });
        }
    }

    function load_segmentBase_projectID_income(segment, detailID) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Procurement/load_project_segmentBase_multiple_noclass"); ?>',
            dataType: 'html',
            data: {segment: segment.value,detailID:detailID},
            async: true,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $(segment).closest('tr').find('.div_projectID_income').html(data);
                stopLoad();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                stopLoad();
            }
        });
    }
</script>