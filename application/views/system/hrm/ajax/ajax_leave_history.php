<div class="row">
    <?php $class = ($isFromEmployeeMaster == 'Y')? 'col-md-12' : 'col-md-5' ;  ?>
    <div class="<?php echo $class ?>">
        <table class="<?php echo table_class() ?>">
            <thead>
            <tr>
                <th>Code</th>
                <th>Accrued Date</th>
                <th>Description</th>
                <th style="text-align: right">Days/Hour</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totEntitle = 0;
            if($accrued){
              foreach($accrued as $row){
                  $totEntitle += $row['entitle'];
                ?>
                  <tr>
                      <td><?php echo $row['leaveaccrualMasterCode'] ?></td>
                      <td><?php echo $row['createDate'] ?></td>
                      <td><?php echo $row['description'] ?></td>
                      <td style="text-align: right"><?php echo $row['entitle'] ?></td>
                  </tr>
              <?php }
            }?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">Total Accrued</td>
                <td colspan="" style="text-align: right"><?php echo $totEntitle?></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <?php
    $class = 'col-md-7' ;
    if($isFromEmployeeMaster == 'Y'){
        $class = 'col-md-12';

        echo '<div class="col-sm-12">&nbsp;</div>';
    }

    ?>
    <div class="<?php echo $class ?>">
        <table class="<?php echo table_class() ?>">
            <thead>
            <tr>
                <th>Code</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Description</th>
                <th>Policy</th>
                <th>Approved By</th>
                <th>Approved Date</th>
                <th>Days/Hour</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totUtilize = 0;
            if(!empty($leave)){
                foreach($leave as $row){
                    $totUtilize += $row['leavedays'];
                ?>
            <tr>
                <td><?php echo $row['documentCode'] ?></td>
                <td><?php echo $row['startDate'] ?></td>
                <td><?php echo $row['endDate'] ?></td>
                <td><?php echo $row['comments'] ?></td>
                <td><?php echo $row['policy'] ?></td>
                <td><?php echo $row['approvedbyEmpName'] ?></td>
                <td><?php echo $row['approvedDate'] ?></td>
                <td style="text-align: right"><?php echo $row['leavedays'] ?></td>
            </tr>
            <?php }}?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">Total Utilized</td>
                <td style="text-align: right"><?php echo $totUtilize ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php //$balance = ($accruedTotal['total'] - $leaveTotal['total']); ?>
<?php $balance = ($totEntitle - $totUtilize); ?>

<script>
    $('#balance-span').html( '<?php echo $balance; ?>');
</script>

