<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

<div class="width100p">
    <section class="past-posts">
        <div class="posts-holder settings">
            <div class="past-info">
                <div id="toolbar">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="toolbar-title">
                                <i class="fa fa-file-text" aria-hidden="true"></i> <?php echo $this->lang->line('crm_opportunity_reports');?>
                            </div><!--Opportunity Reports-->
                        </div>
                        <div class="col-sm-4">
                               <span class="no-print pull-right" style="margin-top: -1%;margin-right: -5%;"> <a class="btn btn-danger btn-sm pull-right" style="padding: 4px 12px;font-size: 9px;" target="_blank" onclick="generateReportPdf('opportunity')">
                                <span class="fa fa-file-pdf-o" aria-hidden="true"> PDF
            </span> </a></span>
                            <span class="no-print pull-right" style="margin-top: -2%;margin-right: 1%;">
                                      <?php  echo export_buttons('opportunityrpt', 'Opportunity Report', True, false)?>
                              </span>
                        <!--<span class="no-print pull-right" style="margin-top: -3%;margin-right: -7%;"> <a
                                class="btn btn-default btn-sm no-print pull-right" target="_blank" onclick="generateReportPdf('opportunity')">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> </a></span>-->
                        </div>
                    </div>
                </div>
                <div class="post-area">
                    <article class="page-content">

                        <div class="system-settings">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped" id="opportunityrpt">
                                        <thead>
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
                                        $currency = '';
                                        if (!empty($opportunity)) {
                                            foreach ($opportunity as $row) {
                                                $currency =$row['CurrencyCode'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $x; ?></td>
                                                    <td><?php echo $row['opportunityName'] ?></td>
                                                    <td><?php echo $row['forcastCloseDate'] ?></td>
                                                    <td><?php echo $row['responsiblePerson'] ?></td>
                                                    <td><?php echo $row['statusDescription'] ?></td>
                                                    <td style="text-align: right"><?php echo number_format($row['transactionAmount'], 2) ?></td>
                                                </tr>
                                                <?php
                                                $total += $row['transactionAmount'];
                                                $x++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <td colspan="5" style="text-align: center"><?php echo $this->lang->line('common_no_records_found');?> </td><!--No Records Found-->
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td style="min-width: 85%  !important" class="text-right sub_total" colspan="5">
                                                <?php echo $this->lang->line('common_total');?>   </td><!--Total-->
                                            <td style="min-width: 15% !important"
                                                class="text-right total"><?php echo number_format($total, 2); ?></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</div>

