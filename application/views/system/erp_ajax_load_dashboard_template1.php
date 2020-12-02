<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <select id="period<?php echo $userDashboardID ?>" onchange="filter<?php echo $userDashboardID ?>()">
                <?php
                $years = get_last_two_financial_year();
                $countYears = count($years);
                $i = 0;
                if ($years) {
                    foreach ($years as $val) {
                        echo '<option value="' . $i . '">' . $val["beginingDate"] . "-" . $val["endingDate"] . '</option>';
                        $i++;
                    }
                }
                ?>
            </select>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 5px">
    <div class="col-md-12" id="1T<?php echo $userDashboardID ?>">

    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div id="1C<?php echo $userDashboardID ?>"></div>
        <div id="2C<?php echo $userDashboardID ?>"></div>
        <div id="3C<?php echo $userDashboardID ?>"></div>
    </div>
    <div class="col-md-6">
        <div id="">
            <div id="4C<?php echo $userDashboardID ?>"></div>
            <div id="5C<?php echo $userDashboardID ?>"></div>
            <div id="6C<?php echo $userDashboardID ?>"></div>
        </div>
    </div>
</div>
<?php
$data['userDashboardID'] = $userDashboardID;
$this->load->view('system/dashboard/common_js', $data);
?>