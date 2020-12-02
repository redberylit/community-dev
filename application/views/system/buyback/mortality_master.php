<?php echo head_page('Mortality', true);
$date_format_policy = date_format_policy();

$this->load->helper('buyback_helper');
$farmer = load_all_farms();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<style>
    #search_cancel img {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;
        vertical-align: middle;
        padding: 4px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .alpha-box{
        font-size: 14px;
        line-height: 25px;
        list-style: none outside none;
        margin: 0 0 0 12px;
        padding: 0 0 0;
        text-align: center;
        text-transform: uppercase;
        width: 24px;
    }

    ul, ol {
        padding: 0;
        margin: 0 0 10px 25px;
    }

    .alpha-box li a {
        text-decoration: none;
        color: #555;
        padding: 4px 8px 4px 8px;
    }

    .alpha-box li a.selected {
        color: #fff;
        font-weight: bold;
        background-color: #4b8cf7;
    }
    .alpha-box li a:hover {
        color: #000;
        font-weight: bold;
        background-color: #ddd;
    }
</style>

<form id="mortality_filter_frm">
    <div id="filter-panel" class="collapse filter-panel">
        <div class="row">
            <div class="form-group col-sm-2">
                <label for="supplierPrimaryCode">Date From</label>
                <div class="input-group datepic">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="mortalityDatefrom"
                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'" id="mortalityDatefrom" class="form-control"  value=""  >
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label for="supplierPrimaryCode">&nbsp&nbspTo&nbsp&nbsp</label>
                <div class="input-group datepic">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="mortalityDateto"
                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'"  id="mortalityDateto"  class="form-control" value="" >
                </div>
            </div>
            <br>

            <div class="col-sm-2" style="margin-top: 5px;">
                <?php echo form_dropdown('farmername',$farmer, '', 'class="form-control select2" onchange="startMasterSearch()" id="farmername"'); ?>
            </div>
        </div>
        </br>
    </div>

    <div class="row">
        <div class="col-md-5">
        </div>
        <div class="col-md-4 text-center">
            &nbsp;
        </div>
        <div class="col-md-3 text-right">
            <button type="button" class="btn btn-primary pull-right"
                    onclick="fetchPage('system/buyback/create_new_mortality',null,'Add New Mortality','BUYBACK');"><i
                    class="fa fa-plus"></i> New Mortality
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body no-padding">
                <div class="row">
                    <div class="col-sm-1" style="margin-left: 2%;">
                        <div class="mailbox-controls">
                            <div class="skin skin-square">
                                <div class="skin-section extraColumns"><input id="isAttended" type="checkbox"
                                                                              data-caption="" class="columnSelected"
                                                                              name="isActive" value="1"><label
                                        for="checkbox">&nbsp;</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1" style="margin-left: -4%;">
                        <span data-id="57698933" class="noselect follow unfollowing" title="Following" onclick="updatefavouritesfarms()"></span>
                    </div>
                    <div class="col-sm-4" style="margin-left: -4%;">
                        <div class="box-tools">
                            <div class="has-feedback">
                                <input name="searchTask" type="text" class="form-control input-sm"
                                       placeholder="Search Mortality"
                                       id="searchTask" onkeypress="startMasterSearch()">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>


                        </div>
                    </div>
                    <div class="col-sm-1 hide" id="search_cancel">
                    <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clearSearchFilter()"><img
                                src="<?php echo base_url("images/crm/cancel-search.gif") ?>"></a></span>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-12">
                        <div id="MortalityMaster_view"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<!--modal report-->
<!--modal report-->
<div class="modal fade" id="finance_report_modal" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" style="width: 90%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Production Statement</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="lock_batchMasterID" name="batchMasterID">
                <div id="reportContent"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.select2').select2();

        $('.headerclose').click(function () {
            fetchPage('system/buyback/mortality_master.php','','Mortality');
        });
        //load_farm_filter('#', 1);
        getMortalityManagement_tableView();

    });

    $('#searchTask').bind('input', function () {
        getMortalityManagement_tableView();
    });

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getMortalityManagement_tableView();
    }

    Inputmask().mask(document.querySelectorAll("input"));
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

    $('.datepic').datetimepicker({
        useCurrent: false,
        format: date_format_policy,
    }).on('dp.change', function (e) {
        startMasterSearch();
    });


    function getMortalityManagement_tableView() {
        var searchTask = $('#searchTask').val();
        var data = $('#mortality_filter_frm').serialize();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: data,
            url: "<?php echo site_url('Buyback/load_mortality_Master_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#MortalityMaster_view').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function delete_mortality(id) {
        swal({
                title: "Are you sure?",
                text: "You want to delete this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'mortalityAutoID': id},
                    url: "<?php echo site_url('Buyback/delete_mortality_master'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        getMortalityManagement_tableView();
                        myAlert('s','Mortality Deleted Successfully');
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getMortalityManagement_tableView();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('.farmsorting').removeClass('selected');
        $('#searchTask').val('');
        $('#mortalityDateto').val('');
        $('#farmername').val('').change();
        $('#mortalityDatefrom').val('');
        $('#sorting_1').addClass('selected');
        getMortalityManagement_tableView();
    }

    /*call report content*/
    function generateMortalityProductionReport(batchMasterID) {
        $('#btn_lockGenerateReport').removeClass('hide');
        $('#lock_batchMasterID').val(batchMasterID);
        $.ajax({
            async: true,
            type: 'POST',
            dataType: 'html',
            data: {batchMasterID:batchMasterID},
            url: '<?php echo site_url('Buyback/buyback_Mortality_ClosingLock'); ?>',
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#reportContent").html(data);
                $('#finance_report_modal').modal("show");
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
            }
        });
    }

    function generateMortalityProductionReport_view(batchMasterID) {
        $('#btn_lockGenerateReport').addClass('hide');
        $.ajax({
            async: true,
            type: 'POST',
            dataType: 'html',
            data: {batchMasterID:batchMasterID},
            url: '<?php echo site_url('Buyback/buyback_production_report'); ?>',
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#reportContent").html(data);
                $('#finance_report_modal').modal("show");
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
            }
        });
    }

    function referback_mortality(mortalityAutoID) {
        swal({
                title: "Are you sure?",
                text: "You want to refer back!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes!"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'mortalityAutoID': mortalityAutoID},
                    url: "<?php echo site_url('Buyback/referback_mortality'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            getMortalityManagement_tableView();
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }


</script>