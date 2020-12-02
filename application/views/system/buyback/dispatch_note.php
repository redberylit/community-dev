<?php echo head_page('Dispatch Note', true);
$this->load->helper('buyback_helper');
$date_format_policy = date_format_policy();
$current_date = current_format_date();
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
</style>
<div id="filter-panel" class="collapse filter-panel">
    <div class="row">
        <div class="form-group col-sm-3">
                <label for="supplierPrimaryCode">Date From</label>
                <div class="input-group datepic">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" name="dispatchedDatefrom"
                       data-inputmask="'alias': '<?php echo $date_format_policy ?>'" id="dispatchedDatefrom" class="form-control"  value=""  >
                </div>
        </div>
        <div class="form-group col-sm-3">
        <label for="supplierPrimaryCode">&nbsp&nbspTo&nbsp&nbsp</label>
        <div class="input-group datepic">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <input type="text" name="dispatchedDateto"
                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"  id="dispatchedDateto"  class="form-control" value="" >
        </div>
        </div>
        <br>

        <div class="col-sm-2" style="margin-top: 5px;">
            <?php echo form_dropdown('farmType', array('' => 'Farmer Type', '1' => 'Third Party', '2' => 'Own'), '', 'class="form-control" onchange="startMasterSearch()" id="farmType"'); ?>
        </div>
        <div class="col-sm-2" style="margin-top: 5px;">
            <?php echo form_dropdown('farmername',$farmer, '', 'class="form-control select2" onchange="startMasterSearch()" id="farmername"'); ?>
        </div>
        <div class="col-sm-2" style="margin-top: 5px;">
            <?php echo form_dropdown('dispatchType', array('' => 'Dispatch type', '1' => 'Direct', '2' => 'Load Change'), '', 'class="form-control select2" onchange="startMasterSearch()" id="dispatchType"'); ?>
        </div>




    </div>
    <br>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="box-tools">
            <div class="has-feedback">
                <input name="searchTask" type="text" class="form-control input-sm"
                       placeholder="Search Dispatch Note"
                       id="searchTask">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>

        </div>
    </div>


    <div class="col-sm-1">
        <div class="hide" id="search_cancel">
                    <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clearSearchFilter()"><img
                                src="<?php echo base_url("images/crm/cancel-search.gif") ?>"></a></span>
        </div>
    </div>

    <div class="col-md-2">
        <?php echo form_dropdown('dp_status', array('' => 'Status', '1' => 'Draft', '2' => 'Confirmed', '3' => 'Approved'), '', 'class="form-control" onchange="startMasterSearch()" id="dp_status"'); ?>
    </div>


    <div class="col-md-2 text-center">
        &nbsp;
    </div>


    <div class="col-md-3">
        <button type="button" class="btn btn-primary pull-right"
                onclick="fetchPage('system/buyback/create_dispatch_note',null,'Add New Dispatch Note','BUYBACK');"><i
                class="fa fa-plus"></i> New Dispatch Note
        </button>


    </div>




</div>
<div class="row">
    <div class="col-md-12">
        <div class="box-body no-padding">
            <div class="row">

            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">
                    <div id="DispatchNoteMaster_view"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
    $(document).ready(function () {
        $('.select2').select2();
        $('.headerclose').click(function () {
            fetchPage('system/buyback/dispatch_note', '', 'Dispatch Note');
        });
        getDispatchNoteManagement_tableView();

    });
    Inputmask().mask(document.querySelectorAll("input"));
    $('#searchTask').bind('input', function () {
        startMasterSearch();
    });

    function getDispatchNoteManagement_tableView() {
        var searchTask = $('#searchTask').val();
        var dispatchType = $('#dispatchType').val();
        var farmType = $('#farmType').val();
        var status = $('#dp_status').val();
        var datefrom =$('#dispatchedDatefrom').val();
        var dateto=$('#dispatchedDateto').val();
        var farmername=$('#farmername').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'searchTask': searchTask, dispatchType: dispatchType,farmType:farmType,status:status,dispatchedDatefrom:datefrom,dispatchedDateto:dateto,farmername:farmername},
            url: "<?php echo site_url('Buyback/load_dispatchNoteManagement_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#DispatchNoteMaster_view').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function delete_dispatchnote(id) {
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
                    data: {'dispatchAutoID': id},
                    url: "<?php echo site_url('Buyback/delete_dispatchNote_master'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        getDispatchNoteManagement_tableView();
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getDispatchNoteManagement_tableView();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('.farmsorting').removeClass('selected');
        $('#searchTask').val('');
        $('#dispatchType').val(null).trigger("change");
        $('#farmType').val('');
        $('#dp_status').val('');
        $('#dispatchedDatefrom').val('');
        $('#dispatchedDateto').val('');
        $('#farmername').val(null).trigger("change");
        $('#sorting_1').addClass('selected');
        getDispatchNoteManagement_tableView();
    }

    function referback_dispatchnote(dispatchAutoID) {
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
                    data: {'dispatchAutoID': dispatchAutoID},
                    url: "<?php echo site_url('Buyback/referback_dispatchnote'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            getDispatchNoteManagement_tableView();
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }
    $('.datepic').datetimepicker({
        useCurrent: false,
        format: date_format_policy,
    }).on('dp.change', function (e) {
        startMasterSearch();
    });


</script>