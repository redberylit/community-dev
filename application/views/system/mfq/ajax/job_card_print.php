<?php
$jobMasterRec = get_job_master($workProcessID);
?>
<br>
<div id="" class="row review">
    <div class="col-md-12"><span class="no-print pull-right"> <button class="btn btn-xs btn-danger" id="btn-pdf" type="button"
                                                                      onclick="generateReportPdf()"> <span
                        class="glyphicon glyphicon-print" aria-hidden="true"></span> </button> </span>
        <?php echo form_open('login/loginSubmit', ' id="frm_filter" class="form-horizontal" name="frm_filter" role="form"'); ?>
            <input type="hidden" id="workProcessID" name="workProcessID" value="<?php echo $workProcessID ?>">
            <input type="hidden" id="jobCardID" name="jobCardID" value="<?php echo $jobCardID ?>">
            <input type="hidden" id="workFlowID" name="workFlowID" value="<?php echo $workFlowID ?>">
            <input type="hidden" id="templateDetailID" name="templateDetailID" value="<?php echo $templateDetailID ?>">
            <input type="hidden" id="linkworkFlow" name="linkworkFlow" value="<?php echo $linkworkFlow ?>">
            <input type="hidden" id="templateMasterID" name="templateMasterID" value="<?php echo $templateMasterID ?>">
            <input type="hidden" id="type" name="type" value="<?php echo $type ?>">
            <div id="filters"> <!--load report content-->

            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<div id="div_print" style="padding:5px;">
    <table width="100%">
        <tbody>
        <tr>
            <td width="200px"><img alt="Logo" style="height: 130px"
                                   src="<?php echo mPDFImage . $this->common_data['company_data']['company_logo']; ?>">
            </td>
            <td>
                <div style="text-align: center; font-size: 17px; line-height: 26px; margin-top: 10px;">
                    <strong> <?php echo $this->common_data['company_data']['company_name'] ?></strong><br>
                    <center>Job Card</center>
                </div>
            </td>
            <td style="text-align:right;">
                <div style="text-align:right; font-size: 17px; vertical-align: top;">

                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="4" border="1">
        <tbody>
        <tr>
            <td colspan="2" width="123"><b>Job No</b></td>
            <td width="79"><?php echo $type == 2 ? $jobheader["documentCode"] : ""; ?></td>
            <td colspan="2" width="135"><b>Customer</b></td>
            <td colspan="5" width="141"><?php echo $type == 2 ? $jobheader["CustomerName"] : ""; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Job Date</b></td>
            <td><?php echo $type == 2 ? convert_date_format($jobheader["documentDate"]) : ""; ?></td>
            <td colspan="2"><b>Department</b></td>
            <td colspan="5"><?php echo $type == 2 ? $jobheader["segment"] : ""; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Quote Ref.</b></td>
            <td colspan="8" width="214"><?php echo $type == 2 ? $jobcardheader["quotationRef"] : ""; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Description</b></td>
            <td colspan="8"><?php echo $type == 2 ? $jobcardheader["description"] : ""; ?>
            </td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="10" style="text-align:center;">MATERIAL CONSUMPTION</td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="2">Material Consumption</td>
            <td>Part No</td>
            <td>UoM</td>
            <td>Qty Used</td>
            <td>Usage Qty</td>
            <td>Unit Cost</td>
            <td>Material Cost</td>
            <td>Mark Up%</td>
            <td>Material Charge</td>
        </tr>
        <?php
        $qtyUsed = 0;
        $usageQty = 0;
        $unitCost = 0;
        $materialCost = 0;
        $markUp = 0;
        $materialCharge = 0;
        if (!empty($material)){
        foreach ($material

                 as $val) {
        $qtyUsed += $val['qtyUsed'];
        $usageQty += $val['usageQty'];
        $unitCost += $val['unitCost'];
        $materialCost += $val['materialCost'];
        $markUp += $val['markUp'];
        $materialCharge += $val['materialCharge'];
        ?>
        <tr>
            <td width="25%" colspan="2"><?php echo $val['itemDescription'] ?></td>
            <td width=""><?php echo $val['partNo'] ?></td>
            <td><?php echo $val['uom'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['qtyUsed'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['usageQty'] ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['unitCost']) ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['materialCost'], 2) ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['markUp']) ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['materialCharge'], 2) ?></td>

            <?php }
            }
            ?>
        </tr>
        <tr>
            <td width="" colspan="4" style="text-align: right"><strong>Total</strong></td>
            <td width="" style="text-align: right"><strong><?php echo $qtyUsed ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $usageQty ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($unitCost) ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($materialCost, 2) ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($markUp) ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($materialCharge, 2) ?></strong></td>
        </tr>

        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="10" style="text-align:center;">LABOUR TASKS</td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="3">Labour Tasks</td>
            <td>Activity Code</td>
            <td>UoM</td>
            <td>Department</td>
            <td>Unit Rate</td>
            <td>Total Hours</td>
            <td>Usage Hours</td>
            <td>Total Value</td>
        </tr>
        <?php
        $lt_hourlyRate = 0;
        $lt_totalHours = 0;
        $lt_usageHours = 0;
        $lt_totalValue = 0;
        if (!empty($labourTask)){
        foreach ($labourTask

                 as $val) {
        $lt_hourlyRate += $val['hourlyRate'];
        $lt_totalHours += $val['totalHours'];
        $lt_usageHours += $val['usageHours'];
        $lt_totalValue += $val['totalValue'];
        ?>
        <tr>
            <td colspan="3"><?php echo $val['description'] ?></td>
            <td width=""><?php echo $val['activityCode'] ?></td>
            <td width=""><?php echo $val['uom'] ?></td>
            <td style=""><?php echo $val['segment'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['hourlyRate'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['totalHours'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['usageHours'] ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['totalValue'], 2) ?></td>
            <?php }
            }
            ?>
        </tr>
        <tr>
            <td width="" colspan="6" style="text-align: right"><strong>Total</strong></td>
            <td width="" style="text-align: right"><strong><?php echo $lt_hourlyRate ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $lt_totalHours ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $lt_usageHours ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($lt_totalValue, 2) ?></strong></td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="10" style="text-align:center;">OVERHEAD COST</td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="3">Overhead Cost</td>
            <td>Activity Code</td>
            <td>UoM</td>
            <td>Department</td>
            <td>Unit Rate</td>
            <td>Total Hours</td>
            <td>Usage Hours</td>
            <td>Total Value</td>
        </tr>
        <?php
        $oh_hourlyRate = 0;
        $oh_totalHours = 0;
        $oh_usageHours = 0;
        $oh_totalValue = 0;
        if (!empty($overhead)){
        foreach ($overhead

                 as $val) {
        $oh_hourlyRate += $val['hourlyRate'];
        $oh_totalHours += $val['totalHours'];
        $oh_usageHours += $val['usageHours'];
        $oh_totalValue += $val['totalValue']; ?>
        <tr>
            <td colspan="3"><?php echo $val['description'] ?></td>
            <td width=""><?php echo $val['activityCode'] ?></td>
            <td width=""><?php echo $val['uom'] ?></td>
            <td style=""><?php echo $val['segment'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['hourlyRate'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['totalHours'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['usageHours'] ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['totalValue'], 2) ?></td>
            <?php }
            }
            ?>
        </tr>
        <tr>
            <td width="" colspan="6" style="text-align: right"><strong>Total</strong></td>
            <td width="" style="text-align: right"><strong><?php echo $oh_hourlyRate ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $oh_totalHours ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $oh_usageHours ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($oh_totalValue, 2) ?></strong></td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="10" style="text-align:center;">MACHINE</td>
        </tr>
        <?php
        $mc_hourlyRate = 0;
        $mc_totalHours = 0;
        $mc_usageHours = 0;
        $mc_totalValue = 0;
        if (!empty($machine)){
        foreach ($machine

                 as $val) {
        $mc_hourlyRate += $val['hourlyRate'];
        $mc_totalHours += $val['totalHours'];
        $mc_usageHours += $val['usageHours'];
        $mc_totalValue += $val['totalValue']; ?>
        <tr>
            <td colspan="3"><?php echo $val['assetDescription'] ?></td>
            <td width=""><?php echo $val['activityCode'] ?></td>
            <td width=""><?php echo $val['uom'] ?></td>
            <td style=""><?php echo $val['segment'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['hourlyRate'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['totalHours'] ?></td>
            <td width="" style="text-align: right"><?php echo $val['usageHours'] ?></td>
            <td width="" style="text-align: right"><?php echo number_format($val['totalValue'], 2) ?></td>
            <?php }
            }
            ?>
        </tr>
        <tr>
            <td width="" colspan="6" style="text-align: right"><strong>Total</strong></td>
            <td width="" style="text-align: right"><strong><?php echo $mc_hourlyRate ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $mc_totalHours ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo $mc_usageHours ?></strong></td>
            <td width="" style="text-align: right"><strong><?php echo number_format($mc_totalValue, 2) ?></strong></td>
        </tr>
        <tr bgcolor="#CCCCCC" style="font-size: 12px;font-weight: bold ">
            <td colspan="10" style="text-align:center;">ITEM DETAIL</td>
        </tr>
        <?php
        $totalCost = $materialCharge + $lt_totalValue + $oh_totalValue + $mc_totalValue;
        ?>
        <tr>
            <td width="" colspan="2"><b>Item</b></td>
            <td width="" colspan="2"><?php echo $type == 2 ? $jobMasterRec['itemDescription'] : ""; ?></td>
            <td width=""><b>UoM</b></td>
            <td width=""><?php echo $type == 2 ? $jobMasterRec['UnitDes'] : ""; ?></td>
            <td width=""><b>Qty</b></td>
            <td width="" colspan="3"><?php echo $type == 2 ? $jobMasterRec['qty'] : ""; ?></td>
        </tr>
        <tr>
            <td width="" colspan="5"><span
                        style="font-size:15px;color: #4a8cdb"><b>Total Cost: <?php echo number_format($totalCost, 2) ?></b></span>
            </td>
            <td width="" colspan="5"><span style="font-size:15px;color: #4a8cdb"><b>Cost per unit: <?php
                        if ($jobMasterRec['qty'] > 0) {
                            echo number_format($totalCost / $jobMasterRec['qty'], 2);
                        } else {
                            echo '0.00';
                        }
                        ?></b></span></td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    function generateReportPdf() {
        var form = document.getElementById('frm_filter');
        form.target = '_blank';
        form.action = '<?php echo site_url('MFQ_Job_Card/fetch_jobcard_print'); ?>';
        form.submit();
    }
</script>
