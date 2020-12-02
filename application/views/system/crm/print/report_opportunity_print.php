<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

<div id="tbl_unbilled_grv">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center reportHeaderColor">
                <strong><?php echo $this->common_data['company_data']['company_name'] ?> </strong>
            </div>
            <div class="text-center reportHeader reportHeaderColor"><?php echo $this->lang->line('crm_opportunity_report_re');?> </div><!--Opportunity Report-->
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <?php if (!empty($opportunity)) { ?>
                <table class="borderSpace report-table-condensed" id="tbl_report">
                    <thead class="report-header">
                    <tr>
                        <th>#</th>
                        <th><?php echo $this->lang->line('common_name');?></th><!--Name-->
                        <th><?php echo $this->lang->line('crm_closing_date');?></th><!--Closing Date-->
                        <th>User Responsible</th>
                        <th><?php echo $this->lang->line('common_status');?></th><!--Status-->
                        <th><?php echo $this->lang->line('common_value');?></th><!--Value-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $x = 1;
                    $total = 0;
                    foreach ($opportunity as $row) { ?>
                        <tr>
                            <td><?php echo $x; ?></td>
                            <td><?php echo $row['opportunityName'] ?></td>
                            <td><?php echo $row['forcastCloseDate'] ?></td>
                            <td><?php echo $row['responsiblePerson'] ?></td>
                            <td><?php echo $row['statusDescription'] ?></td>
                            <td style="text-align: right"><?php echo $row['CurrencyCode'] . " : " . number_format($row['transactionAmount'], 2) ?></td>
                        </tr>
                        <?php
                        $total += $row['transactionAmount'];
                        $x++;
                    }
                    ?>
                    </tbody>
                    <tfoot style="border-top: 1px double #0044cc;border-bottom: 1px double #0044cc;">
                    <tr>
                        <td style="min-width: 85%  !important" class="text-right sub_total" colspan="5">
                            <?php echo $this->lang->line('common_total');?>    </td><!--Total-->
                        <td style="min-width: 15% !important"
                            class="text-right total"><?php echo number_format($total, 2); ?></td>
                    </tr>
                    </tfoot>
                </table>
                <?php
            } else {
                $norecfound=$this->lang->line('common_no_records_found');
                echo warning_message($norecfound);/*No Records Found!*/
            }
            ?>
        </div>
    </div>
</div>