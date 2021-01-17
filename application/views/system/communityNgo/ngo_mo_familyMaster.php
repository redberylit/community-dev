<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('CommunityNgo_com_families');
echo head_page($title, true);

$this->load->helper('community_ngo_helper');
$date_format_policy = date_format_policy();

$date_format_policy = date_format_policy();
$division_arr = load_division_for_member();
$area_arr = load_region_fo_members();
$gender_arr = load_gender();
$ancestry_arr = array('1' => 'Outside', '0' => 'Local');
$status_arr = array('1' => 'Deleted', '0' => 'Active');

$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
?>
<style>
    fieldset {
        border: 1px solid silver;
        border-radius: 5px;
        padding: 1%;
        padding-bottom: 15px;
        margin: 10px 15px;
    }

    legend {
        width: auto;
        border-bottom: none;
        margin: 0px 10px;
        font-size: 20px;
        font-weight: 500
    }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<style>
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

    .task-cat-upcoming {
        border-bottom: solid 1px #f76f01;
    }

    .task-cat-upcoming-label {
        display: inline;
        float: left;
        color: #f76f01;
        font-weight: bold;
        margin-top: 5px;
        font-size: 15px;
    }

    .taskcount {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #eee;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 1px 3px 0 3px;
        line-height: 14px;
        margin-left: 8px;
        margin-top: 9px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .numberColoring {
        font-size: 13px;
        font-weight: 600;
        color: saddlebrown;
    }
</style>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 36px;
        height: 18px;
        margin-top: 2px;
        margin-left: 50px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cccccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 13px;
        width: 13px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(18px);
        -ms-transform: translateX(18px);
        transform: translateX(18px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 30px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

<div class="row">
    <div class="col-md-5">
        <table class="<?php echo table_class(); ?>">
            <tr>
                <td><span class="label label-success">&nbsp;</span> <?php echo $this->lang->line('common_confirmed'); ?>
                    <!--Confirmed-->
                </td>
                <td><span class="label label-danger">&nbsp;</span> <?php echo $this->lang->line('common_not_confirmed'); ?>
                    <!--Not Confirmed-->
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <a href="#" type="button" class="btn btn-success btn-sm pull-right CA_Print_Excel_btn" onclick="excelFam_Export()">
            <i class="fa fa-file-excel-o"></i> Excel
        </a>
        <a href="#" type="button" style="margin-right: 2px;" class="btn btn-danger btn-sm pull-right CA_Print_Excel_btn" onclick="generate_familyToPdf()">
            <i class="fa fa-file-pdf-o"></i> PDF
        </a>
        <button type="button" class="btn btn-primary pull-right CA_Alter_btn" style="margin-right: 2px;" onclick="fetchPage('system/communityNgo/ngo_mo_familyCreate',null,'<?php echo $this->lang->line('CommunityNgo_add_new_family'); ?>'/*Add New Family*/,'NGO');"><i class="fa fa-plus"></i> <?php echo $this->lang->line('CommunityNgo_create_family'); ?>
            <!--Create Family-->
        </button>
        <button type="button" class="btn btn-warning pull-left CA_Alter_btn" style="display: none;" onclick="UpdateFamily_del()"><i class="fa fa-plus"></i> <?php echo $this->lang->line('CommunityNgo_update_family'); ?>
            <!--Update Family Del-->
        </button>
    </div>
</div>
<br>
<form method="post" name="searchForm" id="searchForm" class="">
    <div id="filter-panel" class="collapse filter-panel">
        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

        <div class="row">
            <fieldset>
                <legend><?php echo $this->lang->line('common_filters'); ?>
                    <!--Columns-->
                </legend>

                <div class="form-group col-sm-12">
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label for="GS_Division"><?php echo $this->lang->line('communityngo_GS_Division'); ?>
                                <!--GS_division-->
                            </label><br>
                            <?php echo form_dropdown('GS_Division[]', $division_arr, '', 'class="form-control" id="GS_Division" onchange="getNgoFamilyMasterTable(\'GS_Division\')" multiple="multiple"'); ?>
                        </div>

                        <div class="form-group col-sm-2">
                            <label for="RegionID"><?php echo $this->lang->line('communityngo_region'); ?>
                                <!--Area-->
                            </label><br>
                            <?php echo form_dropdown('RegionID[]', $area_arr, '', 'class="form-control" id="RegionID" onchange="getNgoFamilyMasterTable(\'RegionID\')" multiple="multiple"'); ?>
                        </div>

                        <div class="form-group col-sm-2">
                            <label for="GenderID"><?php echo $this->lang->line('communityngo_gender'); ?>
                                <!--Gender-->
                            </label><br>
                            <?php echo form_dropdown('GenderID[]', $gender_arr, '', 'class="form-control" id="GenderID" onchange="getNgoFamilyMasterTable(\'GenderID\')" multiple="multiple"'); ?>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="AncestId"><?php echo $this->lang->line('CommunityNgo_fam_ancestryState'); ?>
                                <!--Status-->
                            </label><br>
                            <?php echo form_dropdown('AncestId[]', $ancestry_arr, '', 'class="form-control" id="AncestId" onchange="getNgoFamilyMasterTable(\'AncestId\')" multiple="multiple"'); ?>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="isDeleted"><?php echo $this->lang->line('communityngo_com_member_header_Status'); ?>
                                <!--Status-->
                            </label><br>
                            <?php echo form_dropdown('isDeleted[]', $status_arr, '0', 'class="form-control" id="isDeleted" onchange="getNgoFamilyMasterTable(\'isDeleted\')" multiple="multiple"'); ?>
                        </div>
                        <div class="form-group col-sm-1">
                            <button type="button" class="btn btn-primary pull-left" onclick="clearFamily_all_filters()" style="margin-top:9%;">
                                <i class="fa fa-paint-brush"></i>
                                <?php echo $this->lang->line('common_clear'); ?>
                                <!--Clear-->
                            </button>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="switch" style="">
                                <input type="checkbox" id="chDate" onclick="switchInApplySN(this);">
                                <span id="titleId" class="slider round snAppTitleCls" title="Switch On Apply Serial No"></span>
                            </label>
                            <button type="button" id="applySNbtn" class="btn-small btn-default pull-right" onclick="apply_serialNoFormat()" style="font-size: 10px;display: none;">
                                <i class="fa fa-check"></i>
                                <?php echo $this->lang->line('CommunityNgo_apply_format'); ?>
                                <!--apply-->
                            </button>
                        </div>
                    </div>

                </div>

            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="display: none;">
            <div class="form-group col-sm-4 text-center">
                <div class="box-tools">
                    <label></label>
                    <div class="has-feedback">
                        <input name="femKey" type="text" class="form-control input-sm" placeholder="<?php echo $this->lang->line('communityngo_searchmembers'); ?>" id="femKey">
                        <!--Search by all-->
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-2 hide" id="search_cancel">
                <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clearSearchFilter()"><img src="<?php echo base_url("images/crm/cancel-search.gif") ?>"></a></span>

            </div>
            <div class="form-group col-sm-6">

            </div>

        </div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="familyMaster_view" class="<?php echo table_class(); ?>">
                <thead>
                    <tr>
                        <th style="width:4%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;">#</th>
                        <th style="width:10%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('CommunityNgo_ledger_no'); ?>
                            <!--Ledger No-->
                        </th>
                        <th style="width:12%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('CommunityNgo_ref_no'); ?>
                            <!--Reference No-->
                        </th>
                        <th style="width:14%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('CommunityNgo_famName'); ?>
                            <!--Family Name-->
                        </th>
                        <th style="width:15%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('CommunityNgo_leader'); ?>
                            <!--Leader-->
                        </th>
                        <th style="width:8%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('CommunityNgo_fam_ancestry'); ?>
                            <!--Ancestory-->
                        </th>
                        <th style="width:8%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('CommunityNgo_famAddedDate'); ?>
                            <!--Added Date-->
                        </th>
                        <th style="width:5%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;" title="Total Members"><?php echo $this->lang->line('CommunityNgo_famTotMem'); ?>
                            <!--Total Members-->
                        </th>
                        <th style="width:5%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;" title="Is Enroll To House Count"><?php echo $this->lang->line('communityngo_famHusEnrl'); ?>
                            <!-- House Enrolled -->
                        </th>
                        <th style="width:5%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_status'); ?>
                            <!--Status-->
                        </th>
                        <th style="width:14%;font-size: 11px;font-weight: bold;border-top: 1px solid #ffffff;"></th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function() {

        control_staff_access(0, 'system/communityNgo/ngo_mo_familyMaster', 0);

        $('.headerclose').click(function() {
            fetchPage('system/communityNgo/ngo_mo_familyMaster', '', 'Community Families');
        });

        $('.select2').select2();

        $('#GS_Division').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });
        $("#GS_Division").multiselect2('selectAll', false);
        $("#GS_Division").multiselect2('updateButtonText');
        $('#RegionID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });
        $("#RegionID").multiselect2('selectAll', false);
        $("#RegionID").multiselect2('updateButtonText');
        $('#GenderID').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });
        $("#GenderID").multiselect2('selectAll', false);
        $("#GenderID").multiselect2('updateButtonText');
        $('#AncestId').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });
        $("#AncestId").multiselect2('selectAll', false);
        $("#AncestId").multiselect2('updateButtonText');
        $('#isDeleted').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });

        load_family_commitments('#', 1);
        getNgoFamilyMasterTable();

    });

    function referback_family_creation(FamMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                'FamMasterID': FamMasterID
            },
            url: "<?php echo site_url('CommunityNgo/referback_family_creation'); ?>",
            beforeSend: function() {
                startLoad();
            },
            success: function(data) {
                myAlert(data[0], data[1]);
                load_family_commitments();
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    $('#femKey').bind('input', function() {
        startMasterSearch();
    });

    function getNgoFamilyMasterTable(name) {

        var AncestId = $('#AncestId').val();
        var GenderID = $('#GenderID').val();
        var RegionID = $('#RegionID').val();
        var GS_Division = $('#GS_Division').val();
        var isDeleted = $('#isDeleted').val();

        $('#familyMaster_view').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('CommunityNgo/load_familyMasterView'); ?>",
            "aaSorting": [[1, 'desc']],
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [10]
            }],
            "fnInitComplete": function() {

            },
            "fnDrawCallback": function(oSettings) {
                $("[rel=tooltip]").tooltip();
                var selectedRowID = parseInt('<?php echo (!empty($this->input->post('page_id'))) ? $this->input->post('page_id') : 0; ?>');
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i;
                    (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    if (parseInt(oSettings.aoData[x]._aData['FamMasterID']) == selectedRowID) {
                        var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                        $(thisRow).addClass('dataTable_selectedTr');
                    }
                    x++;
                }
            },
            "aoColumns": [{
                    "mData": "FamMasterID"
                },
                {
                    "mData":"FamilySystemCode"
                },
                {
                    "mData": "LedgerNo"
                },
                {
                    "mData": "FamilyName"
                },
                {
                    "mData": "CName_with_initials"
                },
                {
                    "mData": "famAncesState"
                },
                {
                    "mData": "FamilyAddedDate"
                },
                {
                    "mData": "memTotalStatus"
                },
                {
                    "mData": "houseEnrollStatus"
                },
                {
                    "mData": "famStatus"
                },
                {
                    "mData": "editFamily"
                }
            ],
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "GenderID",
                    "value": GenderID
                });
                aoData.push({
                    "name": "GS_Division",
                    "value": GS_Division
                });
                aoData.push({
                    "name": "RegionID",
                    "value": RegionID
                });
                aoData.push({
                    "name": "AncestId",
                    "value": AncestId
                });
                aoData.push({
                    "name": "isDeleted",
                    "value": isDeleted
                });
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

    $('.table-row-select tbody').on('click', 'tr', function () {
            $('.table-row-select tr').removeClass('dataTable_selectedTr');
            $(this).toggleClass('dataTable_selectedTr');
        });

    function delete_family_master(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure'); ?>",
                /*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_delete'); ?>",
                /*You want to delete this record!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_delete'); ?>",
                /*Delete*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel'); ?>"
            },
            function() {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'FamMasterID': id
                    },
                    url: "<?php echo site_url('CommunityNgo/delete_family_master'); ?>",
                    beforeSend: function() {
                        startLoad();
                    },
                    success: function(data) {
                        //refreshNotifications(true);
                        stopLoad();
                        load_family_commitments();

                        if (data['error'] == 1) {
                            myAlert('e', data['message']);
                        } else if (data['error'] == 0) {
                            oTable.draw();
                            myAlert('s', data['message']);
                        }
                        // getNgoFamilyMasterTable();
                    },
                    error: function() {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getNgoFamilyMasterTable();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('#femKey').val('');
        $('#sorting_1').addClass('selected');
        getNgoFamilyMasterTable();
    }

    function clearFamily_all_filters() {
        $('#search_cancel').addClass('hide');
        $('#femKey').val('');
        $('#sorting_1').addClass('selected');
        $('#GS_Division').multiselect2('deselectAll', false);
        $('#GS_Division').multiselect2('updateButtonText');
        $('#RegionID').multiselect2('deselectAll', false);
        $('#RegionID').multiselect2('updateButtonText');
        $('#GenderID').multiselect2('deselectAll', false);
        $('#GenderID').multiselect2('updateButtonText');

        $('#AncestId').multiselect2('deselectAll', false);
        $('#AncestId').multiselect2('updateButtonText');
        $('#isDeleted').multiselect2('deselect', '1');
        $('#isDeleted').multiselect2('updateButtonText');

        getNgoFamilyMasterTable();
    }

    function callOTable(name) {
        getNgoFamilyMasterTable(name);
        }

    function load_family_commitments(value, id) {

        $('#sorting_' + id).addClass('selected');
        if (value != '#') {
            $('#search_cancel').removeClass('hide');
        }
        getNgoFamilyMasterTable(value)
    }

    function excelFam_Export() {
        var form = document.getElementById('searchForm');
        form.target = '_blank';
        form.method = 'post';
        form.post = $('#searchForm').serializeArray();
        form.action = '<?php echo site_url('CommunityNgo/exportFamily_excel'); ?>';
        form.submit();
    }

    function generate_familyToPdf() {

        var form = document.getElementById('searchForm');
        form.target = '_blank';
        form.method = 'post';
        form.post = $('#searchForm').serializeArray();
        form.action = '<?php echo site_url('CommunityNgo/get_communityFamily_status__pdf'); ?>';
        form.submit();

    }

    function print_aFamilyToPdf(FamMasterID) {

        var win = window.open('<?php echo site_url('CommunityNgo/load_community_family_confirmation'); ?>' + '//' + FamMasterID);
        win.focus();

    }

    function apply_serialNoFormat() {
        bootbox.confirm("<?php echo $this->lang->line('CommunityNgo_apply_confirmation'); ?>", function(result) {
            if (result) {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {},
                    url: "<?php echo site_url('CommunityNgo/apply_serialNoFormat'); ?>",
                    beforeSend: function() {
                        startLoad();
                    },
                    success: function(data) {
                        myAlert(data[0], data[1]);
                        load_family_commitments();
                        stopLoad();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        myAlert('e', '<br>Message: ' + errorThrown);
                    }
                });
            }
        });

    }

    function UpdateFamily_del() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {},
            url: "<?php echo site_url('CommunityNgo/UpdateFamily_dels'); ?>",
            beforeSend: function() {
                startLoad();
            },
            success: function(data) {
                myAlert(data[0], data[1]);
                load_family_commitments();
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function switchInApplySN(x) {

        var datValue = x.id;

        if (document.getElementById(datValue).checked == true) {

            document.getElementById('applySNbtn').style.display = 'block';
            $('span.snAppTitleCls').attr('title', 'Switch Off Apply Serial No');

        } else {

            document.getElementById('applySNbtn').style.display = 'none';

            $('span.snAppTitleCls').attr('title', 'Switch On Apply Serial No');

        }

    }
</script>
<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 1/18/2018
 * Time: 4:00 PM
 */
