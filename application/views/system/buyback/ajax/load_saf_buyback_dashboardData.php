<?php
$current_date = current_format_date();
$companyID = $this->common_data['company_data']['company_id'];
$date_format_policy = date_format_policy();

$this->load->helper('buyback_helper');

?>
<style>

    .datepicker-inline {
        min-width: 100%;
        max-width: 100%;
        width: 100%;
        border: none;
    }
    .datepicker {
        background-color: inherit;
        color: #ffd485;
    }
    .datepicker table tr th{
        color: #fefefe;
        background-color: #00c56a;
    }
    .datepicker table th:hover{
        background-color: #8ae98b !important;
    }
    .datepicker table tr td:hover{
        color: #000;
        background-color: #00944b !important;

    }
    .datepicker table tr td.active.day{
        color: #000;
        background-color: #007d3e !important;
    }
    .datepicker table tr td.new.day{
        color: #585858 !important;
    }
    .datepicker table tr td.old.day{
        color: #585858 !important;
    }

    .wipAmount_tble  tr{
        background-color: #605ca8;
      //  background-color: #719e1f;
        height: 60px;
        color: white;
        font-size: 35px;
        text-align: center;
        font-family: sans-serif;

    }

</style>

<!-- Morris.js charts -->
    <script type="text/javascript" src="<?php echo base_url('plugins/highchart/highcharts-more.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('plugins/highchart/modules/solid-gauge.js'); ?>"></script>

    <script src="<?php echo base_url('plugins/morris/morris.min.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/community_ngo/raphael-min.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/daterangepicker/daterangepicker.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/morris/morris.css'); ?>">

    <?php
    if($theme == 1){ ?>
        <style>
            .fcrtext{
                color: black;
            }
            .BatchStatusDetails{
                background-color: white;
            }
            .farmLog_heading{
                color: black;
            }
            .calanderField{
                background-color: white;
                color: black;
            }
            .calenderTitle{
                background-color: white;
                color: black;
            }
            .calenderDetails{
                background-color: white;
                color: black;
            }
            .calculateFeed{
                background-color: white;
                color: black;
            }
            .datepicker table th{
                background-color: white;
            }
        </style>

    <?php } else if($theme == 2){ ?>
        <style>
            .age{
                background-color: #2a2a2b;
                color: white;
            }
            .BatchStatusDetails{
                background-color: #2a2a2b;
                color: white;
            }
            .calanderField{
                background-color: #2a2a2b;
                color: white;
            }
            .calenderTitle{
                background-color: #2a2a2b;
                color: #E0E0E3;
            }
            .calenderDetails{
                background-color: #2a2a2b;
                color: #E0E0E3;
            }
            .calculateFeed{
                background-color: #2a2a2b;
                color: #E0E0E3;
            }
            .datepicker table th{
                background-color: #2a2a2b;
            }
            .fcrtext{
                color: white;
            }
            .farmLog_heading{
                color: #E0E0E3;
            }

            th {
                background-color: black;
                color: white;
            }

            .table-striped > tbody > tr:nth-child(2n) > td, .table-striped > tbody > tr:nth-child(2n) > th {
                background-color: #2a2a2b;
                color: white;
            }
            .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
                background-color:  #2a2a2b;
                color: white;
            }
            .highcharts-background {
                fill: #2a2a2b;
                font-family: sans-serif;
            }
        </style>
        <script>

        </script>
   <?php } ?>

    <div class="col-md-12" >
        <div class="col-md-1" >

        </div>
        <div class="col-md-12" >
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:20px; padding-bottom: 20px ">
                <div id="weeklyReportBarChart" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
            </div>

        </div>

    </div>

    <div class="col-md-12" style="padding-top: 5px">
        <div class="col-md-12">
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:20px; padding-bottom: 20px ">
                <div id="weightFeedContainer" style=" height: 350px; margin: 0 auto"></div>
            </div>
        </div>
    </div>

    <div class=" col-md-12" style="padding: 2px; padding-left: 30px; margin-bottom: 20px">
        <div class="col-md-5 col-sm-6 col-xs-12" style="padding:10px;">

            <table id="fcr_tbl" class="" style="width: 100%; margin-top: 2%; border: none;  box-shadow: 0px 2px 2px 0px #807979">
                <thead style="border: none">
                <tr style="height: 25px; font-size: 20px; background-color: #f39c12; color: white; text-align: center">
                    <td colspan="3">Mortality Percentage</td>
                </tr>
                <tr style="height: 1px">
                </tr>
                <tr style=" background-color: #f39c12; height: 25px; color: white; text-align: center">
                    <td>Yearly</td>
                    <td>Monthly</td>
                    <td>Today</td>
                </tr>
                <tr style="background-color: #f39c12; height: 20px; color: white; font-size: 20px; text-align: center">
                    <?php
                    if($MortalityPercentage){
                        foreach ($MortalityPercentage as $val){?>
                            <td style="font-size: 25px;"><?php echo $val ?>%</td>
                     <?php   }
                    }
                    ?>
                </tr>
                </thead>
            </table>

            <table id="fcr_tbl" class="" style="width: 100%; margin-top: 10%; border: none;  box-shadow: 0px 2px 2px 0px #807979">
                <thead style="border: none">
                <tr style="height: 25px; font-size: 20px; background-color: #00c0ef; color: white; text-align: center">
                    <td colspan="3">Overall FCR</td>
                </tr>
                <tr style="height: 1px">
                </tr>
                <tr style=" background-color: #00c0ef; height: 25px; color: white; text-align: center">
                    <td>Yearly</td>
                    <td>Monthly</td>
                    <td>Today</td>
                </tr>
                <tr style="background-color: #00c0ef; height: 20px; color: white; font-size: 20px; text-align: center">
                    <?php
                    if($feedRate){
                        foreach ($feedRate as $val){?>
                            <td style="font-size: 25px;"><?php echo $val ?></td>
                     <?php   }
                    }
                    ?>
                </tr>
                </thead>
            </table>

            <table id="" class="wipAmount_tble" style="width: 100%; margin-top: 10%;  box-shadow: 0px 2px 2px 0px #807979">
                <thead style="border: none">
                <tr style="height: 25px; font-size: 20px">
                    <td>Overall WIP Amount</td>
                </tr>
                <tr style="height: 1px">
                </tr>
                <tr>
                    <td class="pull-right" style="padding-right: 10px; font-size: 35px;"><?php echo $WIPAmount ?></td>
                </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-6 bg-green-gradient" style="padding:10px; margin-left: 20px; border: 1px solid rgba(158, 158, 158, 0.24);">
            <div class="box box-solid calanderField" style="background-color: inherit">
                <div class="box-header calenderTitle" style="background-color: inherit">
                    <h3 class="box-title" style="color: white">Feed Schedule</h3>
                </div>

                    <div class="buybackDashCalendar" id="buybackDashCalendar" data-date="<?php echo $current_date; ?>" onclick="get_calDateformat();">
                        <input class="DashCalendarPick hidden" id="DashCalendarPick" value="<?php echo $current_date; ?>" >
                   </div>


                <!--  <div class="box-footer"> -->
                    <div class="row calculateFeed" id="calculateFeed" style="margin: 20px; background-color: inherit; color: white"></div>
               <!--  </div> -->
            </div>
        </div>

    </div>

    <div class=" col-md-12" style="padding-left: 30px; margin-bottom: 20px">
        <div class="col-md-5 col-sm-6 col-xs-12" style="padding:5px; border: 1px solid rgba(158, 158, 158, 0.24);">
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                <div>
                    <div id="BatchStatus"></div>
                    <div style="padding: 0px" class="col-sm-12 BatchStatusDetails">

                        <div class="col-sm-4">
                            <li class="fa fa-circle-o text-blue" style="width: 70px">&nbsp Input:</li><span><input class="BatchStatusDetails" id="InputTotal" value="" style="border: none;font-weight: bold; width: 30px; text-align: center" disabled></span>
                        </div>
                        <div class="col-sm-4">
                            <li class="fa fa-circle-o text" style="width: 70px">&nbsp Output:</li><span><input class="BatchStatusDetails" id="OutputTotal" value="" style="border: none;font-weight: bold; width: 30px; text-align: center" disabled></span>
                        </div>
                        <div class="col-sm-4">
                            <li class="fa fa-circle-o text-green" style="width: 70px">&nbsp Mortal:</li><span><input class="BatchStatusDetails" id="MortalTotal" value="" style="border: none;font-weight: bold; width: 30px; text-align: center" disabled></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 " style="padding:10px; margin-left: 23px; border: 1px solid rgba(158, 158, 158, 0.24);">
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px; height: 310px">
                <div>
                    <div class="box-header with-border">
                        <div class="col-sm-6">
                            <h3 class="box-title farmLog_heading">Farm Log</h3>
                        </div>
                        <label>Filter Age : &nbsp </label>
                        <input class="age" id="ageFrom" name="ageFrom" placeholder="From" style="width: 45px; border-radius: 2px; border: 1px solid rgba(60, 60, 60, 0.55);">
                        <label class="">-</label>
                        <input class="age" id="ageTo" name="ageTo" placeholder="To" style="width: 45px; border-radius: 2px; border: 1px solid rgba(60, 60, 60, 0.55);">
                        <button type="button" class=" btn-xs" style="background-color: transparent; color: #00a5e6; border: none"
                                onclick="tableData()">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        <button type="button" class=" btn-xs" style="background-color: transparent; color: #00a5e6; border: none"
                                    onclick="CleartableData()">
                            <i class="fa fa-paint-brush" aria-hidden="true"></i></i>
                        </button>
                    </div>
                            <div id="tabledata"></div>
                </div>
            </div>
        </div>
    </div>

<!--
    <div class=" col-md-12" style="padding: 2px;">

        <div class="col-md-6 col-sm-6 col-xs-12" style="padding:10px;">
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Overdue Payables</a>
                        </li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Overdue Receivables</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <table id="overdue_payable" class="<?php echo table_class(); ?>">
                                <thead>
                                <tr>
                                    <th style="min-width: 48%">Farm</th>
                                    <th style="min-width: 48%">Batch</th>
                                    <th style="min-width: 15%">Amount</th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="tab-pane" id="tab_2">
                            <table id="overdue_receivable" class="<?php echo table_class(); ?>">
                                <thead>
                                <tr>
                                    <th style="min-width: 48%">Name</th>
                                    <th style="min-width: 48%">Batch</th>
                                    <th style="min-width: 15%">Amount</th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>

                </div>
            </div>

        </div>


    </div>
-->

    <script type="text/javascript">
        function get_calDateformat() {
            var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

            $('#buybackDashCalendar').datepicker({
                format: date_format_policy,
            // inline: true
            }).on('changeDate', function (ev) {
                var date = $("#buybackDashCalendar").datepicker("getDate");
                convertDate(date);
            });
        }
        function convertDate(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth()+1)).slice(-2),
                day  = ("0" + date.getFullYear()).slice(-4);
            var formatDate = [ date.getDate(), mnth, day ].join("-");

            $("#DashCalendarPick").val(formatDate);
        }

        $(function () {
            var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

            $('#buybackDashCalendar').datepicker({
                format: date_format_policy,
            }).on('changeDate', function (ev) {
                var date = $("#buybackDashCalendar").datepicker("getDate");
                convertDate(date);
                feedScheduleCalenderData();
            });
            tableData();
            feedScheduleCalenderData();

            Highcharts.chart('weeklyReportBarChart', {
                <?php  if($theme == 2){ ?>

                colors: ['#2b908f', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066',
                    '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
                legend: {
                    itemStyle: {
                        color: '#E0E0E3',
                    }
                },
                <?php } ?>
                title: {
                    text: 'Monthly Report',
                    style: {
                        color:
                            <?php  if($theme == 1){
                                ?>'black'<?php
                        }
                        else {
                        ?>  '#E0E0E3',
                        fontSize: '20px'<?php
                        } ?>
                        ,
                    }
                },
                xAxis: {
                    <?php  if($theme == 2){
                    ?>
                    labels: {
                        style: {
                            color: '#E0E0E3'
                        }
                    },
                    <?php } ?>
                    categories: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    <?php  if($theme == 2){
                    ?>
                    labels: {
                        style: {
                            color: '#E0E0E3'
                        }
                    },
                    <?php } ?>
                    min: 0,
                    title: {
                        text: 'Amount'
                    }
                },
                tooltip: {
                    <?php  if($theme == 2){ ?>
                    backgroundColor: 'rgba(0, 0, 0, 0.85)',
                    style: {
                        color: '#F0F0F0'
                    },
                    <?php } ?>
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: false,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    type: 'column',
                    name: 'Input',
                    data: [
                        <?php foreach ($columnChicks as $val){
                        echo $val['noOfChicks']; ?>,<?php
                        } ?>
                    ]
                }, {
                    type: 'column',
                    name: 'Output',
                    data: [<?php foreach ($columnLiveBirds as $val){
                        echo $val['noOfliveBirds']; ?>,<?php
                        } ?>]
                }, {
                    type: 'column',
                    name: 'Mortal',
                    data: [<?php foreach ($columnMortal as $val){
                        echo $val['noOfBirds']; ?>,<?php
                        } ?>]
                },{
                    type: 'spline',
                    name: 'Input',
                    data: [
                        <?php foreach ($columnChicks as $val){
                        echo $val['noOfChicks']; ?>,<?php
                        } ?>
                    ],
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }, {
                    type: 'spline',
                    name: 'Output',
                    data: [<?php foreach ($columnLiveBirds as $val){
                        echo $val['noOfliveBirds']; ?>,<?php
                        } ?>
                    ],
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }, {
                    type: 'spline',
                    name: 'Mortal',
                    data: [<?php foreach ($columnMortal as $val){
                        echo $val['noOfBirds']; ?>,<?php
                        } ?>
                    ],
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }]
            });

            Highcharts.chart('weightFeedContainer', {
                chart: {
                    type: 'scatter',
                    zoomType: 'xy',
                    fontFamily: 'serif'
                },
                title: {
                    text: 'Feed Versus Weight of Farm Batches (<?php echo $month ?>)',
                    color:'#FFFFFF',
                    style: {
                        color:
                            <?php  if($theme == 1){
                                ?>'black'<?php
                        }
                        else {
                        ?>  '#E0E0E3',
                        fontSize: '20px' <?php
                        } ?>
                        ,
                    }
                },
                xAxis: {
                    <?php  if($theme == 2){
                    ?>
                    labels: {
                        style: {
                            color: '#E0E0E3'
                        }
                    },
                    <?php } ?>
                    title: {
                        enabled: true,
                        text: 'Feed',
                    },
                    startOnTick: true,
                    endOnTick: true,
                    showLastLabel: true
                },
                yAxis: {
                    <?php  if($theme == 2){
                    ?>
                    labels: {
                        style: {
                            color: '#E0E0E3'
                        }
                    },
                    <?php } ?>
                    title: {
                        text: 'Weight'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -30,
                    y: -10,
                    floating: true,
                    <?php  if($theme == 2){ ?>
                    backgroundColor: '#aaa4a4',
                    <?php } else{ ?>
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
                    <?php } ?>
                    borderWidth: 1,
                 //   enable: false
                },
                plotOptions: {
                    scatter: {
                        marker: {
                            radius: 5,
                            states: {
                                hover: {
                                    enabled: true,
                                    lineColor: 'rgb(100,100,100)'
                                }
                            }
                        },
                        states: {
                            hover: {
                                marker: {
                                    enabled: false
                                }
                            }
                        },
                        tooltip: {
                            headerFormat: '',
                            pointFormat: 'Farm:{point.name}<br>Batch:{point.batch}<br>Feed:{point.x}, Weight: {point.y}'
                        },
                    }
                },
                series: [{
                    name: 'Production',
                    color: 'rgba(223, 83, 83, .5)',
                    data:
                        [<?php
                            if(!empty($scatterPlot)){
                            if(!empty($scatterchick)){
                            foreach ($scatterPlot as $buy) {

                            foreach ($scatterchick as $data){
                                if($buy['batchMasterID'] == $data['batchMasterID']){

                            $this->db->select(' sum( noOfBirds ) AS birdstotalcount,sum( transactionQTY ) AS birdskgsweight');
                            $this->db->from("srp_erp_buyback_itemledger");
                            $this->db->where("batchID", $buy['batchMasterID']);
                            $this->db->where("documentCode", 'BBGRN');
                            $this->db->order_by("itemLedgerAutoID ASC");
                            $birds = $this->db->get()->row_array();

                            $feedTot = ($data['chicksTotal'] +  $birds['birdstotalcount']) / 2;
                            $feedPer = ($buy['feedTotal'] * 50) / $feedTot;
                            $feedPercentage = number_format($feedPer, 2);

                            if(!empty($birds['birdskgsweight'] OR $birds['birdstotalcount'])){

                                $weightPer = ($birds['birdskgsweight'] / $birds['birdstotalcount']);
                            $weightPercentage= round($weightPer, 2);
                            }else{
                                $weightPercentage = 0;
                            }?>

                            {"name":"<?php echo $buy['description'] ?>","batch":"<?php echo $buy['batchCode'] ?>", "x":<?php echo $feedPercentage ?>,"y":<?php echo $weightPercentage ?>},


                            <?php
                            }
                            }

                            }
                        }}
                        ?>],

                },{
                    name: 'Farm Visit',
                    color: 'rgba(119, 152, 191, .5)',
                    data:
                        [<?php
                            if(!empty($sactterFeildReport)){

                            foreach ($sactterFeildReport as $buy) { ?>
                            {"name":"<?php echo $buy['description'] ?>","batch":"<?php echo $buy['batchCode']; ?> <br> Visit Code: <?php echo $buy['documentSystemCode'] ?>", "x":<?php if(!empty($buy['avgFeedperBird'])){ echo $buy['avgFeedperBird']; } else{ echo 0; } ?>,"y":<?php if(!empty($buy['avgBodyWeight'])){ echo $buy['avgBodyWeight']; } else{ echo 0; } ?>},

                        <?php  }
                        }
                        ?>],

                }]
            });

            <?php
            if(!empty($totalChicksCount)) {
                $occ1Pecrnt = round((($input_chicks / $totalChicksCount) * 100), 0);
                $occ2Pecrnt = round((($output_chicks / $totalChicksCount) * 100), 0);
                $occ3Pecrnt = round((($mortality_chicks / $totalChicksCount) * 100), 0);
            } else{
                $occ1Pecrnt = 0;
                $occ2Pecrnt = 0;
                $occ3Pecrnt = 0;
            } ?>

            var charcolor = {
                type: 'solidgauge',
                marginTop: 30,
            }
            var chartype = {
                type: 'solidgauge',
                marginTop: 30,
                height: 300,
                fontFamily: 'serif',
            }
            var chartitle = {
                text: 'Batch Status (<?php echo $month ?>)',
                style: {
                    color:
                        <?php  if($theme == 1){
                            ?>'black'<?php
                    }
                    else {
                    ?>
                    '#E0E0E3',
                    fontSize: '20px'<?php
                    } ?>
                    ,
                    fontSize: '18px'
                }
            }
            var chartooltip = {
                borderWidth: 0,
                backgroundColor: 'none',
                shadow: false,
                style: {
                    fontSize: '14px'
                },
                pointFormat: '<span class="batchstatus" style="color: {point.color};">{series.name}</span><br><span style="font-size:1.5em; color: {point.color}; font-weight: bold">{point.y}%</span>',
                positioner: function (labelWidth, labelHeight) {
                    return {
                        x: 210 - labelWidth / 2,
                        y: 125
                    };
                }
            }
            var chartpane = {
                startAngle: 0,
                endAngle: 360,
                background: [{ // Track for Move
                    outerRadius: '102%',
                    innerRadius: '78%',
                    backgroundColor: Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0.3).get(),
                    borderWidth: 0
                }, { // Track for Exercise
                    outerRadius: '77%',
                    innerRadius: '53%',
                    backgroundColor: Highcharts.Color(Highcharts.getOptions().colors[1]).setOpacity(0.3).get(),
                    borderWidth: 0
                }, { // Track for Stand
                    outerRadius: '52%',
                    innerRadius: '28%',
                    backgroundColor: Highcharts.Color(Highcharts.getOptions().colors[2]).setOpacity(0.3).get(),
                    borderWidth: 0
                }]
            }
            var chartyaxis = {
                min: 0,
                max: 100,
                lineWidth: 0,
                tickPositions: []
            }
            var chartplotOptions= {
                solidgauge: {
                    borderWidth: '25px',
                    dataLabels: {
                        enabled: false
                    },
                    linecap: 'round',
                    stickyTracking: false
                }
            }
            var chartseries = [{
                name: 'Input',
                <?php  if($theme == 1){
                    ?>borderColor: Highcharts.getOptions().colors[0],<?php
                }
                else {
                ?>
                borderColor: '#2b908f',
                <?php
                } ?>
                data: [{
                   <?php  if($theme == 1){
                        ?>color: Highcharts.getOptions().colors[0],<?php
                        }
                        else {
                        ?>
                color: '#2b908f',
                    <?php
                    } ?>
                   //
                    radius: '90%',
                    innerRadius: '90%',
                    y: <?php echo $occ1Pecrnt; ?>
                }]
            }, {
                name: 'Output',
                <?php  if($theme == 1){
                    ?>borderColor: Highcharts.getOptions().colors[1],<?php
                }
                else {
                ?>
                borderColor: '#90ee7e',
                <?php
                } ?>
                data: [{
                    <?php  if($theme == 1){
                        ?>color: Highcharts.getOptions().colors[1],<?php
                    }
                    else {
                    ?>
                    color: '#90ee7e',
                    <?php
                    } ?>
                    radius: '65%',
                    innerRadius: '65%',
                    y: <?php echo $occ2Pecrnt; ?>
                }]
            }, {
                name: 'Mortal',
                <?php  if($theme == 1){
                    ?>borderColor: Highcharts.getOptions().colors[2],<?php
                }
                else {
                ?>
                borderColor: '#f45b5b',
                <?php
                } ?>
                data: [{
                    <?php  if($theme == 1){
                        ?>color: Highcharts.getOptions().colors[2],<?php
                    }
                    else {
                    ?>
                    color: '#f45b5b',
                    <?php
                    } ?>
                    radius: '40%',
                    innerRadius: '40%',
                    y: <?php echo $occ3Pecrnt; ?>
                }]
            }]
            Highcharts.chart('BatchStatus', {
                    colors: ['#2b908f', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066',
                        '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],

                    chart:chartype,
                    title: chartitle,
                    tooltip: chartooltip,
                    pane:chartpane,
                    yAxis: chartyaxis,
                    plotOptions:chartplotOptions,
                    series: chartseries
                },
                /**
                 * In the chart load callback, add icons on top of the circular shapes
                 */
                function callback() {
                });

            document.getElementById('InputTotal').value =  '<?php echo $occ1Pecrnt; ?>%';
            document.getElementById('OutputTotal').value = '<?php echo $occ2Pecrnt; ?>%';
            document.getElementById('MortalTotal').value = '<?php echo$occ3Pecrnt; ?>%';

        });

        function feedScheduleCalenderData() {
            var feedUpTo = $.trim($('#DashCalendarPick').val());
            $.ajax({
                async: true,
                type: "POST",
                data: {'feedUpTo': feedUpTo},
                url: "<?php echo site_url('BuybackDashboard/feedScheduleCalenderData') ?>",
              //  dataType: "html",
            //    cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                 $("#calculateFeed").html(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }

        function CleartableData() {
            $('#ageFrom').val('');
            $('#ageTo').val('');
            tableData();
        }

        function tableData() {
            var ageFrom = $('#ageFrom').val();
            var ageTo = $('#ageTo').val();

            $.ajax({
                async: true,
                type: "POST",
                data: {'ageFrom': ageFrom, 'ageTo': ageTo},
                url: "<?php echo site_url('BuybackDashboard/fetch_FarmLog') ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                   // alert(data);
                    stopLoad();
                    $("#tabledata").html(data);
                    $('#tble_farmLog').DataTable({
                        "scrollY": "210px",
                        "bFilter": false,
                        "bInfo": false,
                        "bPaginate": false
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }

    </script>

<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 6/29/2018
 * Time: 10:53 AM
 */