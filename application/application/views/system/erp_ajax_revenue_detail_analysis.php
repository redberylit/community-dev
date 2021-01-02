<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('dashboard', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>
<div class="box box-danger">
    <div class="box-header with-border">
        <h4 class="box-title"><?php echo $this->lang->line('dashboard_revenue_detail_analysis');?><!--Revenue Detail Analysis--></h4>

        <div class="box-tools pull-right">
            <strong class="btn-box-tool"><?php echo $this->lang->line('common_currency');?><!--Currency--> : (<?php echo $this->common_data['company_data']['company_reporting_currency'] ?>)</strong>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                    class="fa fa-times"></i>
            </button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body" style="display: block;height: 250px;overflow: auto "
         id="revenuedetailanalysis<?php echo $userDashboardID; ?>">
        <?php
        $color = array("#00C0EF", "#DD4B39", "#00A65A", "#F39C12", "#4B94C0", "#666666", "#ffc0cb", "#c39797", "#6dc066", "#794044", "#6f5b57", "#b2c4ff", "#ffc7b2", "#ffb2c4",
            "#339988", "#D4E79E", "#78A6B0", "#9BBFA6", "#723F00", "#FFA459");

        if (!empty($revenueDetailAnalysis)) {
            foreach ($revenueDetailAnalysis as $key => $val) {
                $percentage = 0;
                if (!empty($totalRevenue)) {
                    $percentage = (($val["companyReportingAmount"] / $totalRevenue) * 100);
                } else {
                    $percentage = 0;
                }
                ?>
                <div class="progress-group">
                    <span class="progress-text"><?php echo $val["subCategory"] ?></span>
                    <span class="progress-number"><span
                            style="color: #4B94C0"><?php echo number_format($val["companyReportingAmount"]) ?></span> ► <b><span
                                class="text-orange"><?php echo round($percentage) . "%" ?></span></b></span>

                    <div class="progress sm">
                        <div class="progress-bar"
                             style="background-color: <?php echo $color[$key] ?>;width: <?php echo $percentage . "%" ?>;"></div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="overlay" id="overlay2<?php echo $userDashboardID; ?>"><i class="fa fa-refresh fa-spin"></i></div>
    <!-- /.box-body -->
</div>
