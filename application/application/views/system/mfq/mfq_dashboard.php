<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/TabStylesInspiration/css/normalize.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/TabStylesInspiration/css/tabs.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/TabStylesInspiration/css/tabstyles.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/dhtmlxGantt/codebase/dhtmlxgantt.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/dhtmlxGantt/codebase/skins/dhtmlxgantt_broadway.css'); ?>" rel="stylesheet">
<script type="text/javascript"
        src="<?php echo base_url('plugins/TabStylesInspiration/js/modernizr.custom.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('plugins/TabStylesInspiration/js/cbpFWTabs.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('plugins/dhtmlxGantt/codebase/dhtmlxgantt.js'); ?>"></script>
<script type="text/javascript"
        src="<?php echo base_url('plugins/dhtmlxGantt/codebase/ext/dhtmlxgantt_tooltip.js'); ?>"></script>
<style>

    .panel.with-nav-tabs .panel-heading {
        padding: 5px 5px 0 5px;
    }

    .panel.with-nav-tabs .nav-tabs {
        border-bottom: none;
    }

    .panel.with-nav-tabs .nav-justified {
        margin-bottom: -1px;
    }

    /********************************************************************/
    /*** PANEL SUCCESS ***/
    .with-nav-tabs.panel-success .nav-tabs > li > a,
    .with-nav-tabs.panel-success .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li > a:focus {
        color: #3c763d;
    }

    .with-nav-tabs.panel-success .nav-tabs > .open > a,
    .with-nav-tabs.panel-success .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-success .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li > a:focus {
        color: #3c763d;
        background-color: white;
        border-color: transparent;
    }

    .with-nav-tabs.panel-success .nav-tabs > li.active > a,
    .with-nav-tabs.panel-success .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li.active > a:focus {
        color: #3c763d;
        background-color: #fff;
        border-color: #d6e9c6;
        border-bottom-color: transparent;
    }

    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #3c763d;
    }

    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #d6e9c6;
    }

    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff;
        background-color: #3c763d;
    }

    .panel-success > .panel-heading {
        background-color: white;
    }

    .with-nav-tabs.panel-success .nav-tabs > li.active > a, .with-nav-tabs.panel-success .nav-tabs > li.active > a:hover, .with-nav-tabs.panel-success .nav-tabs > li.active > a:focus {
        color: #000000;
        background-color: #ecf0f5;
        border-color: #ecf0f5;
        border-bottom-color: transparent;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        font-size: 12px;
    }

    .pagination > li > a, .pagination > li > span {
        padding: 2px 8px;
    }

    .content-wrap section {
        text-align: left;
    }
</style>
<section>
    <div class="panel with-nav-tabs panel-success" style="border: none;">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#section-bar-1" data-toggle="tab" aria-expanded="true"><span><i class="fa fa-tachometer"></i> Dashboard</span>
                    </a>
                </li>
                <li class="">
                    <a href="#section-bar-2" onclick="loadGantt()" data-toggle="tab"
                       aria-expanded="true"><span> <i class="fa fa-calendar"></i> Production Calendar</span>
                    </a>
                </li>
                <li class="">
                    <a href="#section-bar-3" onclick="fetchOngoingJob()" data-toggle="tab"
                       aria-expanded="true"><span> <i class="fa fa-refresh" aria-hidden="true"></i> Ongoing Job</span>
                    </a>
                </li>
                <!--<li class="pull-right">
                    <button type="button" data-text="Sync" id="" class="btn button-royal" onclick="PullDataFromErp()"><i class="fa fa-level-down" aria-hidden="true"></i> Pull data from ERP </button>&nbsp;
                    <button type="button" data-text="Sync" id="" class="btn button-royal" onclick="openErpWarehouse()"><i class="fa fa-level-down" aria-hidden="true"></i> Pull data from ERP Warehouse </button>&nbsp;
                    <button type="button" data-text="Sync" id="" class="btn button-royal" onclick="UpdateWacFromErp()"><i class="fa fa-level-down" aria-hidden="true"></i> Update WAC from ERP </button>
                </li>-->
            </ul>
        </div>
        <div class="panel-body" style="background-color: #ecf0f5;">
            <div class="tab-content">
                <div class="tab-pane active" id="section-bar-1">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h4 class="box-title">MACHINES</h4>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" id="" style="display: block;width: 100%">
                                    <div class="table-responsive">
                                        <table id="tbl_machine" class="table table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th style="min-width: 2%">#</th>
                                                <th style="min-width: 12%">MACHINE ID</th>
                                                <th style="min-width: 12%">JOB NO</th>
                                                <th style="min-width: 12%">HOURS</th>
                                                <th style="min-width: 3%">END DATE</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h4 class="box-title">JOB STATUS</h4>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" id="" style="display: block;width: 100%">
                                    <div class="table-responsive">
                                        <table id="tble_jobstatus" class="table table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th style="min-width: 2%">#</th>
                                                <th style="min-width: 12%">JOB ID</th>
                                                <th style="min-width: 12%">START DATE</th>
                                                <th style="min-width: 12%">CLOSE DATE</th>
                                                <th style="min-width: 12%">NARRATION</th>
                                                <th style="min-width: 3%">STATUS</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h4 class="box-title">JOBS</h4>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" id="" style="display: block;width: 100%">
                                    <div class="table-responsive">
                                        <table id="tbl_jobs"
                                               class="mfqTable table table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th style="min-width: 12%">STATUS</th>
                                                <th style="min-width: 12%">JAN - <?php echo date('Y') ?></th>
                                                <th style="min-width: 12%">FEB - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">MAR - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">APR - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">MAY - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">JUN - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">JUL - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">AUG - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">SEP - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">OCT - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">NOV - <?php echo date('Y') ?></th>
                                                <th style="min-width: 3%">DEC - <?php echo date('Y') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $ci =& get_instance();
                                            $ci->load->model('MFQ_Dashboard_modal');
                                            $result = $ci->MFQ_Dashboard_modal->fetch_jobs();
                                            if ($result) {
                                                foreach ($result as $val) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val["description"] ?></td>
                                                        <td class="text-center"><?php echo $val["jan"] == null?0:$val["jan"]; ?></td>
                                                        <td class="text-center"><?php echo $val["feb"] == null?0:$val["feb"]; ?></td>
                                                        <td class="text-center"><?php echo $val["mar"] == null?0:$val["mar"]; ?></td>
                                                        <td class="text-center"><?php echo $val["apr"] == null?0:$val["apr"]; ?></td>
                                                        <td class="text-center"><?php echo $val["may"] == null?0:$val["may"]; ?></td>
                                                        <td class="text-center"><?php echo $val["jun"] == null?0:$val["jun"]; ?></td>
                                                        <td class="text-center"><?php echo $val["jul"] == null?0:$val["jul"]; ?></td>
                                                        <td class="text-center"><?php echo $val["aug"] == null?0:$val["aug"]; ?></td>
                                                        <td class="text-center"><?php echo $val["sept"] == null?0:$val["sept"]; ?></td>
                                                        <td class="text-center"><?php echo $val["oct"] == null?0:$val["oct"]; ?></td>
                                                        <td class="text-center"><?php echo $val["nov"] == null?0:$val["nov"]; ?></td>
                                                        <td class="text-center"><?php echo $val["dece"] == null?0:$val["dece"]; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="section-bar-2">
                    <label for="scale1" class="radio-inline"><input type="radio" id="scale1" name="scale" value="1"/>Day
                        scale</label>
                    <label for="scale2" class="radio-inline"><input type="radio" id="scale2" name="scale" value="2"/>Week
                        scale</label>
                    <label for="scale3" class="radio-inline"><input type="radio" id="scale3" name="scale" value="3"
                                                                    checked/>Month scale</label>
                    <div class="row">
                        <div id="gantt_here" style='width:100%; height:100%'></div>
                    </div>
                </div>
                <div class="tab-pane" id="section-bar-3">
                    <div class="row">
                        <div class="col-md-5">&nbsp;</div>
                        <div class="col-md-3 pull-right">
                            <a href="<?php echo site_url('MFQ_Dashboard/ongoing_job_excel'); ?>" type="button" class="btn btn-success btn-sm pull-right">
                                <i class="fa fa-file-excel-o"></i> Excel
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl_ongoin_job" class="<?php echo table_class(); ?>">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th style="width: auto">DATE</th>
                                    <th style="width: auto">JOB NO</th>
                                    <th style="width: auto;">DIVISION</th>
                                    <th style="width: auto;">JOB DESCRIPTION</th>
                                    <th style="width: auto">CLIENT NAME</th>
                                    <th style="width: auto">QTY</th>
                                    <th style="width: auto">AMOUNT</th>
                                    <th style="width: auto">QUOTE REF</th>
                                    <th style="width: auto">JOB COMPLETION</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="tabs tabs-style-bar">
        <nav>
            <ul>
                <li><a href="#section-bar-1" class="fa fa-tachometer"> <span>Dashboard</span></a></li>
                <li><a href="#section-bar-2" class="fa fa-calendar" onclick="loadGantt()">
                        <span>Production Calendar</span></a></li>
            </ul>
        </nav>
        <div class="content-wrap">
            <section id="section-bar-1">


            </section>
            <section id="section-bar-2">

            </section>
        </div><!-- /content -->
    <!-- </div> /tabs -->
    <div class="modal fade" id="erp_warehouse_modal" role="dialog" aria-labelledby="myModalLabel"
         data-width="95%" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width: 50%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="">Warehouse</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="margin-top: 10px;">
                                <div class="form-group col-sm-4">
                                    <label class="title">Warehouse </label>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-req" title="Required Field">
                                        <!--<div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                                        <?php echo form_dropdown('warehouseAutoID', array("" => "Select"), "", 'class="form-control select2" id="warehoueAutoID"'); ?>
                                        <span class="input-req-inner"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onClick="PullDataFromErpWarehouse()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        /*[].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });*/
        machine_table();
        job_status_table();
        $('.select2').select2();

    });

    function job_status_table() {
        oTable = $('#tble_jobstatus').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": false,
            "sAjaxSource": "<?php echo site_url('MFQ_Dashboard/fetch_job_status'); ?>",
            //"aaSorting": [[1, 'desc']],
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,
            "aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            "pageLength": 5,
            language: {
                paginate: {
                    previous: '‹‹',
                    next: '››'
                }
            },
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
                $("[rel='tooltip']").tooltip();
            },
            "aoColumns": [
                {"mData": "workProcessID"},
                {"mData": "documentCode"},
                {"mData": "startDate"},
                {"mData": "endDate"},
                {"mData": "description"},
                {"mData": "percentage"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function machine_table() {
        oTable = $('#tbl_machine').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": false,
            "sAjaxSource": "<?php echo site_url('MFQ_Dashboard/fetch_machine'); ?>",
            //"aaSorting": [[1, 'desc']],
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,
            "aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            "pageLength": 5,
            language: {
                paginate: {
                    previous: '‹‹',
                    next: '››'
                }
            },
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "mfq_faID"},
                {"mData": "faCode"},
                {"mData": "documentCode"},
                {"mData": "hoursSpent"},
                {"mData": "endDate"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function setScaleConfig(value) {
        switch (value) {
            case "1":
                gantt.config.scale_unit = "year";
                gantt.config.step = 1;
                gantt.config.subscales = [{unit: "day", step: 1, date: "%d, %M"}];
                gantt.config.scale_height = 50;
                gantt.config.min_column_width = 60;
                gantt.templates.date_scale = null;
                break;
            case "2":
                var weekScaleTemplate = function (date) {
                    var dateToStr = gantt.date.date_to_str("%d %M, %Y");
                    var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
                    return dateToStr(date) + " - " + dateToStr(endDate);
                };
                gantt.config.scale_unit = "year";
                gantt.config.date_scale = "%Y";
                gantt.config.step = 1;
                gantt.config.subscales = [
                    {unit: "week", step: 1, date: "%d, %M"}
                ];
                gantt.config.scale_height = 50;
                gantt.config.min_column_width = 60;
                break;
            case "3":
                gantt.config.scale_unit = "year";
                gantt.config.step = 1;
                gantt.config.date_scale = "%Y";
                gantt.config.min_column_width = 50;

                gantt.config.scale_height = 50;
                gantt.templates.date_scale = null;


                gantt.config.subscales = [
                    {unit: "month", step: 1, date: "%M"}
                ];
                break;
        }
    }

    function loadGantt() {

        setTimeout(function () {
            gantt.config.autosize = true;
            var demo_tasks = {
                /*"data":[
                 {"id":1, "text":"Project #1", "start_date":"28-03-2013", "duration":"11", "progress": 0.6},
                 {"id":2, "text":"Project #2", "start_date":"01-04-2013", "duration":"18", "progress": 0.4}
                 ]*/
                "data": <?php fetch_ongoing_jobs() ?>
            };

            /* gantt.config.scale_unit = "month";
             gantt.config.step = 1;
             gantt.config.date_scale = "%F, %Y";*/
            gantt.config.readonly = true;
            gantt.config.min_column_width = 50;
            gantt.config.scale_height = 90;
            /*gantt.config.drag_move = false;
             gantt.config.drag_links = false;
             gantt.config.drag_highlight = false;
             gantt.config.drag_progress = false;*/
            gantt.config.container_autoresize = true;
            gantt.config.row_height = 30;

            /*gantt.config.subscales = [
             {unit: "day", step: 1, date: "%j, %D"}
             ];*/

            gantt.config.columns = [
                {name: "text", label: "Job No", align: "left", tree: false, width: 150}
                /*{name:"start_date",label:"Description",  align: "left", tree:false }*/
            ];
            gantt.config.details_on_dblclick = false;
            gantt.templates.tooltip_text = function (start, end, task) {
                return "<div style='text-align: left;margin-bottom: 0px'><b>Task:</b> " + task.text + "</div>" +
                    "<div style='text-align: left'><b>Start date:</b> " + gantt.templates.tooltip_date_format(start) +
                    "</div>" +
                    "<div style='text-align: left'><b>End date:</b> " + gantt.templates.tooltip_date_format(end) + "</div><div style='text-align: left'><b>Description:</b> " + task.description + "</div>";
            };

            setScaleConfig('3');
            gantt.init("gantt_here");
            gantt.parse(demo_tasks);

            var func = function (e) {
                e = e || window.event;
                var el = e.target || e.srcElement;
                var value = el.value;
                setScaleConfig(value);
                gantt.render();
            };

            var els = document.getElementsByName("scale");
            for (var i = 0; i < els.length; i++) {
                els[i].onclick = func;
            }
        }, 100);
    }

    function fetchOngoingJob() {
        oTable = $('#tbl_ongoin_job').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "scrollX": true,
            "sAjaxSource": "<?php echo site_url('MFQ_Dashboard/fetch_ongoing_job'); ?>",
            "aaSorting": [[1, 'desc']],
            "bFilter": false,
            "bLengthChange": false,
            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
            "pageLength": 20,
            "fixedColumns":   {
                leftColumns: 8
            },
            /*"search": {
             "caseInsensitive": false
             },*/
            "scrollCollapse": true,
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "workProcessID"},
                {"mData": "documentDate"},
                {"mData": "documentCode"},
                {"mData": "segment"},
                {"mData": "description"},
                {"mData": "CustomerName"},
                {"mData": "qty"},
                {"mData": "amount"},
                {"mData": "estimateCode"},
                {"mData": "percentage"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });

    }
    
    function PullDataFromErp() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {},
            url: "<?php echo site_url('MFQ_Dashboard/pull_from_erp'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0],data[1]);
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }
    
    function openErpWarehouse() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {},
            url: "<?php echo site_url('MFQ_Dashboard/load_erp_warehouse'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#warehoueAutoID').empty();
                var mySelect = $('#warehoueAutoID');
                mySelect.append($('<option></option>').val("").html("Select"));
                if (!$.isEmptyObject(data)) {
                    $.each(data, function (k, text) {
                        mySelect.append($('<option></option>').val(text['wareHouseAutoID']).html(text['wareHouseDescription']));
                    });
                }
                $("#erp_warehouse_modal").modal();
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function PullDataFromErpWarehouse() {
        if($('#warehoueAutoID').val() != "") {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {warehouseAutoID: $('#warehoueAutoID').val()},
                url: "<?php echo site_url('MFQ_Dashboard/pull_from_erp_warehouse'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    $("#erp_warehouse_modal").modal('hide');
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }else{
            myAlert('e', "Please select a warehouse");
        }
    }

    function UpdateWacFromErp() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {},
            url: "<?php echo site_url('MFQ_Dashboard/update_wac_from_erp'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0],data[1]);
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

</script>