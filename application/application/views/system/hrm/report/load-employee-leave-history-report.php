<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('sales_marketing_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
if ($details) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('salesOrderReport', 'Leave History', True, True);
            } ?>
        </div>
    </div>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="salesOrderReport">
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong>Leave History</strong></div>
            <div style="">
                <table id="tbl_rpt_salesorder" class="borderSpace report-table-condensed" style="width: 100%">
                    <thead class="report-header">
                    <tr>
                        <th>Name</th>
                        <th>Document Code</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                       <th>Days</th>
                       <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($details) {
                        $details = array_group_by($details, 'empname');
                        foreach ($details as $key => $value) {
?>
                            <tr>
                            <td class="" colspan="7"><?php echo $key ?></td>
                            </tr>
                            <?php
                            $total=0;
                            foreach ($value as $val) {
                                ?>
                                <tr>
                                    <td ></td>
                                    <td ><?php echo $val["documentCode"] ?></td>
                                    <td ><?php echo $val["description"] ?></td>
                                    <td ><?php echo $val["startDate"] ?></td>
                                    <td ><?php echo $val["endDate"] ?></td>
                                    <td class="text-right"><?php echo $val["days"] ?></td>
                                    <td class="text-center"><?php echo $val["comments"] ?></td>
                                </tr>
                                <?php

                                $total += $val["days"];
                            }
                            ?>
                            <tr>
                                <td colspan="5" class="text-right"><b>Total Leaves </b></td>
                                <td class="text-right reporttotal"><?php echo $total ?></td>

                            </tr>
                            <?php
                        }
                    } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } else {
    ?>
    <br>
    <div class="row">
        <div class="col-md-12 xxcol-md-offset-2">
            <div class="alert alert-warning" role="alert">
                <?php echo $this->lang->line('common_no_records_found'); ?><!--No Records found-->
            </div>
        </div>
    </div>

    <?php
} ?>
<script>
    $('#tbl_rpt_salesorder').tableHeadFixer({
        head: true,
        foot: true,
        left: 0,
        right: 0,
        'z-index': 10
    });

</script>