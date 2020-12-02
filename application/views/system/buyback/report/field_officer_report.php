<!--Added By Aflal-->
<?php echo head_page('Farm Visit Report', true);
$date_format_policy = date_format_policy();

$this->load->helper('buyback_helper');
$fieldOfficer = buyback_farm_fieldOfficers_drop();
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

    .alpha-box {
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

    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .label {
        display: inline;
        padding: .2em .8em .3em;
    }

    .actionicon {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #89e68d;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 2px 5px 2px 5px;
        line-height: 14px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }
    .headrowtitle {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0 25px;
        font-weight: bold;
        text-align: left;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
        background-color: white;
        border-top: 1px solid #ffffff;
    }
</style>

<form id="fieldVisit_filter_frm">
<div id="filter-panel" class="collapse filter-panel">
    <div class="row">
        <div class="form-group col-sm-2">
            <label for="supplierPrimaryCode">Date From</label>
            <div class="input-group datepic">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" name="fieldVisitDatefrom"
                       data-inputmask="'alias': '<?php echo $date_format_policy ?>'" id="fieldVisitDatefrom" class="form-control"  value=""  >
            </div>
        </div>
        <div class="form-group col-sm-2">
            <label for="supplierPrimaryCode">&nbsp&nbspTo&nbsp&nbsp</label>
            <div class="input-group datepic">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" name="fieldVisitDateto"
                       data-inputmask="'alias': '<?php echo $date_format_policy ?>'"  id="fieldVisitDateto"  class="form-control" value="" >
            </div>
        </div>
        <br>

        <div class="col-sm-2" style="margin-top: 5px;">
            <?php echo form_dropdown('farmername',$farmer, '', 'class="form-control select2" onchange="startMasterSearch()" id="farmername"'); ?>
        </div>
        <div class="col-sm-2" style="margin-top: 5px;">
            <?php echo form_dropdown('FieldVisitID',$fieldOfficer, '', 'class="form-control select2" onchange="startMasterSearch()" id="FieldVisitID"'); ?>
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
                onclick="fetchPage('system/buyback/create_new_farm_visit_report',null,'Add New Farm Visit','BUYBACK');">
            <i
                    class="fa fa-plus"></i> New Farm Visit
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
                    <span data-id="57698933" class="noselect follow unfollowing" title="Following" onclick=""></span>
                </div>
                <div class="col-sm-4" style="margin-left: -4%;">
                    <div class="box-tools">
                        <div class="has-feedback">
                            <input name="searchfarm" type="text" class="form-control input-sm"
                                   placeholder="Search Farm"
                                   id="searchfarm" onkeypress="startMasterSearch()">
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
                    <div id="Farmvisitview"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.select2').select2();

        $('.headerclose').click(function () {
            fetchPage('system/buyback/report/field_officer_report.php', '', 'Field Officer');
        });
        //load_farm_filter('#', 1);
        getMortalityManagement_tableView();

    });

    $('.datepic').datetimepicker({
        useCurrent: false,
        format: date_format_policy,
    }).on('dp.change', function (e) {
        startMasterSearch();
    });

    $('#searchfarm').bind('input', function () {
        getMortalityManagement_tableView();
    });

    function getMortalityManagement_tableView() {
        var searchfarm = $('#searchfarm').val();
        var data = $('#fieldVisit_filter_frm').serialize();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: data,
            url: "<?php echo site_url('Buyback/load_farm_visit_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#Farmvisitview').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getMortalityManagement_tableView();
    }


    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('.farmsorting').removeClass('selected');
        $('#searchfarm').val('');
        $('#fieldVisitDatefrom').val('');
        $('#fieldVisitDateto').val('');
        $('#FieldVisitID').val('').change();
        $('#farmername').val('').change();
        $('#sorting_1').addClass('selected');
        getMortalityManagement_tableView();
    }

    function delete_farmVisitReport(id) {
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
                    data: {'farmerVisitID': id},
                    url: "<?php echo site_url('Buyback/delete_farmVisitReport_master'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        getMortalityManagement_tableView();
                    },
                  //  success: function (data) {
                  //      stopLoad();
                   //     getMortalityManagement_tableView();
                   //     myAlert('s','Farm Visit Report Deleted Successfully');
                 //   },
                     error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                        stopLoad();
                    }
                });
            });
    }

    function referback_farmVisitReport(farmerVisitID) {
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
                    data: {'farmerVisitID': farmerVisitID},
                    url: "<?php echo site_url('Buyback/referback_farmVisit_Report'); ?>",
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