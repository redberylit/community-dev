<?php
if (!empty($bothHelpMem)) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('communityWilling', 'Community Member Willing To Help Report', True, True);
            } ?>
        </div>
    </div>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12 " id="communityWilling">
            <div class="reportHeaderColor" style="text-align: center">
                <strong><?php echo current_companyName(); ?></strong></div>
            <div class="reportHeader reportHeaderColor" style="text-align: center">
                <strong>Community Member Requirements & Willing To Help Report</strong></div>
            <div style="">
                <table id="tbl_rpt_salesorder" class="borderSpace report-table-condensed" border="1" style="width: 100%;border-collapse: collapse;border: 1px solid white;">
                    <thead class="report-header">
                        <tr>
                            <th>#</th>
                            <th>CODE</th>
                            <th>NAME</th>
                            <th>NIC NO</th>
                            <th>GENDER</th>
                            <th>MOBILE</th>
                            <th>ADDRESS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bothHelpMem) {
                            $r = 1;
                            $totalQual = 1;
                            foreach ($bothHelpMem as $val) {

                        ?>
                                <tr>

                                    <td><?php echo $r ?></td>
                                    <td><?php echo $val["MemberCode"] ?></td>
                                    <td width="180px"><?php echo $val["CName_with_initials"] ?></td>
                                    <td><?php echo $val["CNIC_No"] ?></td>
                                    <td><?php echo $val["Gender"] ?></td>
                                    <td><?php echo $val["PrimaryNumber"] ?></td>
                                    <td><?php echo $val["C_Address"] ?></td>
                                </tr>
                            <?php
                                $r++;
                                $totQual = $totalQual++;
                            }

                            ?>

                        <?php }
                        ?>
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
            <div class="alert alert-warning" role="alert">No Records Found</div>
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

<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 11/14/2018
 * Time: 9:48 AM
 */
