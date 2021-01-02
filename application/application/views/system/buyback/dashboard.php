<?php

$primaryLanguage = getPrimaryLanguage();
$this->load->helper('buyback_helper');

$yearfilter = load_yearfilter_dashboard();
$farmer = load_all_farms();
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$cdate=current_date(FALSE);
$startdate =date('Y-01-01', strtotime($cdate));
$start_date = convert_date_format($startdate);
?>
    <script src="<?php echo base_url('plugins/daterangepicker/daterangepicker.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/daterangepicker/daterangepicker-bs3.css'); ?>">
    <style>

        .pagination > li > a, .pagination > li > span {
            padding: 2px 8px;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .panel.with-nav-tabs .panel-heading {
            padding: 5px 5px 0 5px;
        }

        .panel.with-nav-tabs .mainpanel {
            border-bottom: none;
        }

        .panel.with-nav-tabs .nav-justified {
            margin-bottom: -1px;
        }

        /********************************************************************/

        .panel-success > .panel-heading {
            background-color: white;
        }

        .with-nav-tabs.panel-success .mainpanel > li.active > a, .with-nav-tabs.panel-success .mainpanel > li.active > a:hover, .with-nav-tabs.panel-success .mainpanel > li.active > a:focus {
            color: #000000;
            background-color: #ecf0f5;
            border-color: #ecf0f5;
            border-bottom-color: transparent;
        }

        .pagination > li > a, .pagination > li > span {
            padding: 2px 8px;
        }

        .r-icon-stats {
            text-align: center;
        }

        .r-icon-stats i {
            width: 66px;
            height: 66px;
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 24px;
            display: inline-block;
            border-radius: 100%;
            vertical-align: top;
            background: #01c0c8;
        }

        .r-icon-stats .bodystate {
            padding-left: 20px;
            display: inline-block;
            vertical-align: middle;
        }

        .r-icon-stats .bodystate h4 {
            margin-bottom: 0px;
            font-size: 25px;
            font-weight: 800;
        }
        .white-box .box-title {
            margin: 0px 0px 12px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }

        .fullBody{
            background-color: #ffffff;
        }

        .batchIcon{
            background-color: #00a65a;
        }
        .batchBody {
            background-color: #ffffff;
        }

        .WidgetNo{
            color: #adadad;
        }
        .theme{
           -webkit-appearance: normal;
            border: none;
            background-color: inherit;
            color: black;
            -webkit-border-radius: 10px;
        }
        .theme:hover {
            background-color: #ededed;
        }

    </style>

   <section class="content" id="ajax_body_container" >
        <div id="dashboard_content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-success" style="border: none;">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs mainpanel">
                                <li class="active">
                                    <a class="buybackTab" onclick="" id="" data-id="0"
                                       href="#comGeneral_dashboardTemp" data-toggle="tab"
                                       aria-expanded="true"><span><i class="fa fa-tachometer tachometerColor" aria-hidden="true"
                                                                     style="color: #50749f;font-size: 16px;"></i> Buyback</span></a>
                                </li>
                            </ul>
                        </div>

                        <div class="panel-body bodyBorder" style="background-color: #ecf0f5; box-shadow: 0px 2px 2px 0px #807979" >
                            <div class="tab-content">
                                <div class="tab-pane active comGeneral_dashboardTemp" id="comGeneral_dashboardTemp">
                                    <div class="fullBody">
                                        <div class="box-header with-border">
                                            <div class="row" style="margin-top: 5px; margin-bottom: 5px">
                                                <div class="col-md-12" id="">
                                                    <div class="col-sm-7">
                                                        <h4 class="box-title">Dashboard</h4>
                                                    </div>
                                                    <div class="col-sm-1" style="width: 100px">
                                                        <?php echo form_dropdown('theme', array('1' => 'Default', '2' => 'Dark'), '', 'class="form-control select2 theme" id="theme" onchange="buybackDashboardChangeTheme(this)"'); ?>
                                                    </div>
                                                    <div class="col-sm-1" style="width: 100px">
                                                        <?php echo form_dropdown('companyFinanceYearID', $yearfilter, '', ' class="form-control select2 theme" id="companyFinanceYearID" onchange="fetch_finance_year_period(this.value)"'); ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <?php echo form_dropdown('financeyear_period', array('' => 'Period'), '', 'class="form-control select2 theme" id="financeyear_period" onchange="buybackDashboard_Data()"'); ?>                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <br>
                                            <div class="row">
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                                    <div class="info-box batchBody">
                                                        <span class="info-box-icon batchIcon bg-aqua"><i class="fa fa-home" style="color: #eee9e9"></i></span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Farms</span>
                                                            <span class="info-box-number WidgetNo" id="total_active_farms" style="font-size: 35px; text-align: center">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                                    <div class="info-box batchBody">
                                                        <span class="info-box-icon batchIcon bg-yellow"><i class="fa fa-clipboard" aria-hidden="true" style="color: #eee9e9"></i></i></span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Batches</span>
                                                            <span class="info-box-number WidgetNo" id="total_active_batches" style="font-size: 35px; text-align: center">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12" style="cursor: pointer;">
                                                    <div class="info-box batchBody" onclick="ProfitView_model()">
                                                        <span class="info-box-icon batchIcon bg-green"><i class="fa fa-line-chart" style="color: #eee9e9"></i></span>

                                                        <div class="info-box-content">
                                                           <span class="info-box-text">Profit Batches</span>
                                                            <span class="info-box-number WidgetNo" id="total_profit_batches" style="font-size: 35px; text-align: center">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12" style="cursor: pointer;">
                                                    <div class="info-box batchBody"  onclick="LossView_model()">
                                                        <span class="info-box-icon batchIcon bg-red"><i class="fa fa-line-chart" style="color: #eee9e9"></i></span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Loss Batches</span>
                                                            <span class="info-box-number WidgetNo" id="total_loss_batches" style="font-size: 35px; text-align: center">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <input class="hidden" id="Profitid" name="Profitid[]">
                                        <input class="hidden" id="Lossid" name="Lossid[]">

                                            <div class="row" id="buybackdashDiv">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <div class="modal fade bs-example-modal-lg" id="ProfitView" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 70%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title ProfitTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-sm-3">
                                <label for="">Date From</label>
                                <div class="input-group datepic col-sm-10">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="datefrom"
                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                           value="<?php echo $start_date; ?>" id="dateFrom" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="">Date To</label>
                                <div class="input-group datepicto col-sm-10">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="dateto"
                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                           value="<?php echo $current_date; ?>" id="datesTo" class="form-control">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <label for="">Select Farm :</label>
                                <?php echo form_dropdown('farmID[]', $farmer, '', ' class="form-control" multiple="multiple" id="farmID"'); ?>
                            </div>
                            <div class="form-group col-sm-2 pull-right">
                                <label for=""></label>
                                <button style="margin-top: 25px" type="button" onclick="ProfitView()"
                                        class="btn btn-primary btn-xs">
                                    Generate</button>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <div class="Profit" id="Profit">
                        <table id="tbl_Profit" class="borderSpace report-table-condensed" style="width: 100%">
                            <thead class="report-header">
                            <tr>
                                <th>Farm</th>
                                <th>Batch Code</th>
                                <th>Started Date</th>
                                <th>Input</th>
                                <th>Balance</th>
                                <th>Age</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody id="profitData">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" id="LossView" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 70%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title LossTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-sm-3">
                                <label for="">Date From :</label>
                                <div class="input-group datepick col-sm-10">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="date_from"
                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                           value="<?php echo $start_date; ?>" id="date_From" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="">Date To :</label>
                                <div class="input-group datepickerto col-sm-10">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="date_to"
                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                           value="<?php echo $current_date; ?>" id="date_To" class="form-control">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <label for="">Select Farm :</label>
                                <?php echo form_dropdown('farmID[]', $farmer, '', ' class="form-control" multiple="multiple" id="farmer"'); ?>
                            </div>
                            <div class="form-group col-sm-2 pull-right">
                                <label for=""></label>
                                <button style="margin-top: 25px" type="button" onclick="LossView()"
                                        class="btn btn-primary btn-xs">
                                    Generate</button>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="Loss" id="Loss">
                        <table id="tbl_ProfitLoss" class="borderSpace report-table-condensed" style="width: 100%">
                            <thead class="report-header">
                            <tr>
                                <th>Farm</th>
                                <th>Batch Code</th>
                                <th>Started Date</th>
                                <th>Input</th>
                                <th>Balance</th>
                                <th>Age</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody id="lossData">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="buyback_production_report_modal" tabindex="2" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" style="width: 80%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Production Statement<span class="myModalLabel"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="productionReportDrilldown"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            var sat = 2;
            buybackDashSum_Count();
            buybackDashboard_Data(sat);

            $('#farmID').multiselect2({
                includeSelectAllOption: true,
                selectAllValue: 'select-all-value',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 200,
                numberDisplayed: 2,
                buttonWidth: '180px'
            });
            $("#farmID").multiselect2('selectAll', false);
            $("#farmID").multiselect2('updateButtonText');

            $('#farmer').multiselect2({
                includeSelectAllOption: true,
                selectAllValue: 'select-all-value',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 200,
                numberDisplayed: 2,
                buttonWidth: '180px'
            });
            $("#farmer").multiselect2('selectAll', false);
            $("#farmer").multiselect2('updateButtonText');

            $('#LocationId').multiselect2({
                enableCaseInsensitiveFiltering: true,
                includeSelectAllOption: true,
                numberDisplayed: 1,
                buttonWidth: '180px',
                maxHeight: '30px'
            });

            Inputmask().mask(document.querySelectorAll("input"));
            var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

            $('.datepic').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            }).on('dp.change', function (ev) {

            });
            $('.datepicto').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            }).on('dp.change', function (ev) {

            });
            $('.datepick').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            }).on('dp.change', function (ev) {

            });
            $('.datepickerto').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            }).on('dp.change', function (ev) {

            });

            FinanceYearID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinanceYearID'])); ?>;
            fetch_finance_year_period(FinanceYearID);
        });

        function fetch_finance_year_period(companyFinanceYearID, select_value) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'companyFinanceYearID': companyFinanceYearID},
                url: "<?php echo site_url('Dashboard/fetch_finance_year_period'); ?>",
                success: function (data) {
                    $('#financeyear_period').empty();
                    var mySelect = $('#financeyear_period');
                    mySelect.append($('<option></option>').val('').html('Periods'));
                    if (!jQuery.isEmptyObject(data)) {
                        $.each(data, function (val, text) {
                            mySelect.append($('<option></option>').val(text['companyFinancePeriodID']).html(text['dateFrom'] + ' - ' + text['dateTo']));
                        });
                        if (select_value) {
                            $("#financeyear_period").val(select_value);

                        };
                    }
                    buybackDashboard_Data();
                }, error: function () {
                    swal("Cancelled", "Your " + value + " file is safe :)", "error");
                }
            });
        }

        function ProfitView_model() {
            $("#farmID").multiselect2('selectAll', false);
            $("#farmID").multiselect2('updateButtonText');
            $('#dateFrom').val('<?php echo $start_date; ?>');
            $('#datesTo').val('<?php echo $current_date; ?>');
            ProfitView();
        }

        function ProfitView() {
                $('.ProfitTitle').text('Profit Batches');
                var id =  ($('#Profitid').val());
                var farmerid =  ($('#farmID').val());
                var date_from =  ($('#dateFrom').val());
                var date_To =  ($('#datesTo').val());

            $.ajax({
                type: 'post',
                data: {'id': id, 'date_from': date_from, 'date_To': date_To, 'farmerid': farmerid},
                url: "<?php echo site_url('BuybackDashboard/fetchBatchProfitLoss'); ?>",
                dataType: 'html',
                cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#profitData').html(data);
                    $('#ProfitView').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function LossView_model() {
            $("#farmer").multiselect2('selectAll', false);
            $("#farmer").multiselect2('updateButtonText');
            $('#date_From').val('<?php echo $start_date; ?>');
            $('#date_To').val('<?php echo $current_date; ?>');
            LossView();
        }

        function LossView() {
            $('.LossTitle').text('Loss Batches');
            var id =  ($('#Lossid').val());
            var farmerid =  ($('#farmer').val());
            var date_from =  ($('#date_From').val());
            var date_To =  ($('#date_To').val());

            $.ajax({
                type: 'post',
                data: {'id': id, 'date_from': date_from, 'date_To': date_To, 'farmerid': farmerid},
                url: "<?php echo site_url('BuybackDashboard/fetchBatchProfitLoss'); ?>",
                dataType: 'html',
                cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#lossData').html(data);
                    $('#LossView').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function generateProductionReport_preformance(batchMasterID) {
            $.ajax({
                async: true,
                type: 'POST',
                dataType: 'html',
                data: {batchMasterID: batchMasterID,'typecostYN':1},
                url: '<?php echo site_url('Buyback/buyback_production_report'); ?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $("#productionReportDrilldown").html(data);
                    $('#buyback_production_report_modal').modal("show");
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }

        function buybackDashSum_Count() {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                url: "<?php echo site_url('BuybackDashboard/buybackDashSum_Count'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#total_active_farms').html(data['farms']);
                        $('#total_active_batches').html(data['batches']);
                        $('#total_profit_batches').html(data['profit']);
                        $('#total_loss_batches').html(data['loss']);
                        $('#Profitid').val(data['Profitid']);
                        $('#Lossid').val(data['Lossid']);
                    }
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }

        function buybackDashboard_Data(sat) {
            var year =  $.trim($('#companyFinanceYearID').val());
            var themeSec =  ($('#theme').val());
            var financeyear_period =  ($('#financeyear_period').val());

            $.ajax({
              //  async: true,
                type: 'post',
                data: {'theme': sat, 'FinanceYear': year, 'themeSec': themeSec, 'financeyear_period': financeyear_period},
                url: "<?php echo site_url('BuybackDashboard/buybackDashboard_Data'); ?>",
                dataType: 'html',
                cache: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#buybackdashDiv').html(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function buybackDashboardChangeTheme() {
            var theme = $.trim($('#theme').val());

            if(theme == 2){
                $(".buybackTab").css('background-color', '#2a2a2b');
                $(".buybackTab").css('color', 'white');
                $(".theme").css('color', 'white');
                $(".theme").css('background-color', '#2a2a2b');
              //  $(".theme:hover").css('background-color', 'black');
                $(".tachometerColor").css('color', '#2a2a2b');
                $(".panel-heading").css('background-color', '#2a2a2b');
                $(".bodyBorder").css('background-color', '#2a2a2b');
                $(".fullBody").css('background-color', '#2a2a2b');
                $(".box-title").css('color', 'white');
                $(".batchBody").css('background-color', '#2a2a2b');
                $(".batchBody").css('color', 'white');
                $(".WidgetNo").css('color', 'white');
                $(".batchIcon").css('background-color', '#2a2a2b');

                var sat = 1;
                buybackDashboard_Data(sat);

            } else{
                $(".buybackTab").css('background-color', '');
                $(".buybackTab").css('color', '');
                $(".theme").css('color', '#2a2a2b');
                $(".theme").css('background-color', 'white');
                $(".tachometerColor").css('color', '');
                $(".panel-heading").css('background-color', 'white');
                $(".bodyBorder").css('background-color', '#ecf0f5');
                $(".fullBody").css('background-color', 'white');
                $(".box-title").css('color', 'black');

                $(".batchBody").css('background-color', 'white');
                $(".batchBody").css('color', 'black');
                $(".WidgetNo").css('color', '#adadad');
                $(".batchIcon").css('background-color', '#00a65a');

                var sat = 2;
                buybackDashboard_Data(sat);
            }
        }


    </script>




<?php
/**
 * Created by PhpStorm.
 * User: Safeena
 * Date: 9/11/2018
 * Time: 2:39 PM
 */