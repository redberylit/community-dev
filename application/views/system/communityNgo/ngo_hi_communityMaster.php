<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('communityngo_members');
echo head_page($title, true);
$this->load->helper('community_ngo_helper');

$date_format_policy = date_format_policy();
$member_arr = all_member_drop_for_community();
$division_arr = load_division_for_member();
$area_arr = load_region_fo_members();
$gender_arr = load_gender();
$status_arr = array('1' => 'Active', '0' => 'Inactive');

$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);

?>

    <style>
        fieldset {
            border: 1px solid #f5f5f5;
            border-radius: 5px;
            padding: 1%;
            padding-bottom: 15px;
            margin: 10px 15px;
            -webkit-box-shadow: 0 0 30px 0 rgba(82, 63, 105, 0.05);
            box-shadow: 0 0 30px 0 rgba(82, 63, 105, 0.05);
        }

        legend {
            width: auto;
            border-bottom: none;
            margin: 0px 10px;
            font-size: 20px;
            font-weight: 500
        }
    </style>
    <style>
        .switchMem {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 18px;
            margin-top:2px;
            margin-left:50px;
        }

        .switchMem input {display:none;}

        .slidrMem {
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

        .slidrMem:before {
            position: absolute;
            content: "";
            height: 13px;
            width: 13px;
            left: 4px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slidrMem {
            background-color: #2196F3;
        }

        input:focus + .slidrMem {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slidrMem:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }

        /* Rounded slidrMems */
        .slidrMem.round {
            border-radius: 30px;
        }

        .slidrMem.round:before {
            border-radius: 50%;
        }
    </style>

    <div id="filter-panel" class="collapse filter-panel">
        <form id="filterForm">
            <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
            <div class="row">
                <fieldset>
                    <legend><?php echo $this->lang->line('common_filters'); ?><!--Columns--></legend>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-2 set-animation set-input-style-1">
                                <label for="GS_Division"><?php echo $this->lang->line('communityngo_GS_Division'); ?>
                                    <!--GS_division--></label><br>
                                <?php echo form_dropdown('GS_Division[]', $division_arr, '', 'class="form-control" id="GS_Division" onchange="fetch_all_member_details(\'GS_Division\')" multiple="multiple"'); ?>
                            </div>

                            <div class="form-group col-sm-2 set-animation set-input-style-1">
                                <label for="RegionID"><?php echo $this->lang->line('communityngo_region'); ?>
                                    <!--Area--></label><br>
                                <?php echo form_dropdown('RegionID[]', $area_arr, '', 'class="form-control" id="RegionID" onchange="fetch_all_member_details(\'RegionID\')" multiple="multiple"'); ?>
                            </div>

                            <div class="form-group col-sm-2 set-animation set-input-style-1">
                                <label for="GenderID"><?php echo $this->lang->line('communityngo_gender'); ?>
                                    <!--Gender--></label><br>
                                <?php echo form_dropdown('GenderID[]', $gender_arr, '', 'class="form-control" id="GenderID" onchange="fetch_all_member_details(\'GenderID\')" multiple="multiple"'); ?>
                            </div>

                            <div class="form-group col-sm-2 col-xs-6 set-animation set-input-style-1" id="memberdrp">
                                <label
                                    for="Com_MasterID"><?php echo $this->lang->line('communityngo_member_name_with_int'); ?>
                                    <!--Name--></label><br>
                                <?php echo form_dropdown('Com_MasterID[]', $member_arr, '', 'class="form-control" id="Com_MasterID" onchange="fetch_all_member_details(\'Com_MasterID\')" multiple="multiple"'); ?>

                            </div>
                            <div class="form-group col-sm-2 set-animation set-input-style-1">
                                <label
                                        for="isActive"><?php echo $this->lang->line('communityngo_com_member_header_Status'); ?>
                                    <!--Status--></label><br>
                                <?php echo form_dropdown('isActive[]', $status_arr, '', 'class="form-control" id="isActive" onchange="fetch_all_member_details(\'isActive\')" multiple="multiple"'); ?>
                            </div>
                            <div class="form-group col-sm-1 col-xs-6">
                                <button type="button" class="btn btn-primary pull-right" onclick="clear_all_filters()"
                                        style="margin-top: 7%;">
                                    <i class="fa fa-paint-brush"></i>
                                    <?php echo $this->lang->line('common_clear'); ?><!--Clear-->
                                </button>
                            </div>
                            <div class="form-group col-sm-1">
                                <label class="switchMem" style="">
                                    <input type="checkbox"
                                           id="chDate" onclick="switchMemInApplySN(this);">
                                    <span id="titleId" class="slidrMem round snMemAppTitleCls" title="Switch On Apply Serial No"></span>
                                </label>
                                <button type="button" id="applySNbtn" class="btn-small btn-default pull-right" onclick="apply_memberSNFormat()"
                                        style="font-size: 10px;display: none;">
                                    <i class="fa fa-check"></i>
                                    <?php echo $this->lang->line('CommunityNgo_apply_format'); ?><!--apply-->
                                </button>
                            </div>
                        </div>

                    </div>

                </fieldset>
            </div>

        </form>
    </div>


    <div class="row">
        <div class="col-md-5">
            <table class="<?php echo table_class(); ?>">
                <tr>
                    <td style="background-color: white">
                        <span class="glyphicon glyphicon-stop"
                              style="color:#8bc34a; font-size:15px;"></span> <?php echo $this->lang->line('communityngo_Active'); ?>
                    </td><!--Active-->
                    <td style="background-color: white">
                        <span class="glyphicon glyphicon-stop"
                              style="color:rgba(255, 72, 49, 0.96); font-size:15px;"></span> <?php echo $this->lang->line('communityngo_Inactive'); ?>
                    </td><!--Inactive-->
                </tr>
            </table>
        </div>
        <div class="col-md-4 text-center">
            &nbsp;
        </div>
        <div class="col-md-3">
            <a href="#" type="button" class="btn btn-success pull-right btn-sm CA_Print_Excel_btn" onclick="excel_Export()">
                <i class="fa fa-file-excel-o"></i> Excel
            </a>
            <a href="#" type="button" class="btn btn-danger pull-right btn-sm CA_Print_Excel_btn" style="margin-right: 2px;" onclick="generate_memberToPdf()">
                <i class="fa fa-file-pdf-o"></i> PDF
            </a>
            <?php
            $company_id = current_companyID();
            $page = $this->db->query("SELECT createPageLink FROM srp_erp_templatemaster
                              LEFT JOIN srp_erp_templates ON srp_erp_templatemaster.TempMasterID = srp_erp_templates.TempMasterID
                              WHERE srp_erp_templates.FormCatID = 530 AND companyID={$company_id}
                              ORDER BY srp_erp_templatemaster.FormCatID")->row('createPageLink');
            ?>
            <button type="button" class="btn btn-primary pull-right CA_Alter_btn" style="margin-right: 2px;"
                    onclick="fetchPage('<?php echo $page; ?>',null,'<?php echo $this->lang->line('communityngo_add_new_member'); ?>','CRM');">
                <i class="fa fa-plus"></i> <?php echo $this->lang->line('communityngo_add_new'); ?>
            </button>

            
        </div>
    </div>

    <hr>

    <div class="table-responsive">
        <table id="memberTB" class="<?php echo table_class(); ?>">
            <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th style="width: 30px"></th>
                <th style="width: 120px;"><?php echo $this->lang->line('communityngo_MemberCode'); ?></th>
                <th style="width: 220px;">
                    <?php echo $this->lang->line('communityngo_member_name_with_int'); ?><!--Member Name--></th>
                <th style="width: 70px"><?php echo $this->lang->line('communityngo_nic'); ?><!--NIC--></th>
                <th style="width: 70px"><?php echo $this->lang->line('communityngo_gender'); ?><!--Gender--></th>
                <th style="width: 85px"><?php echo $this->lang->line('communityngo_TP_Mobile'); ?><!--Mobile--></th>
                <th style="width: 150px"><?php echo $this->lang->line('communityngo_region'); ?><!--Region--></th>
                <th style="width: 150px">
                    <?php echo $this->lang->line('communityngo_GS_Division'); ?><!--GS Division--></th>
                <th style="width: 75px">
                    <?php echo $this->lang->line('communityngo_com_member_header_Status'); ?><!--Status--></th>
                <th style="width: 150px"></th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">


        $(document).ready(function () {

            control_staff_access(0, 'system/communityNgo/ngo_hi_communityMaster', 0);


            $('.headerclose').click(function () {
                fetchPage('system/communityNgo/ngo_hi_communityMaster', '', '<?php $this->lang->line('communityngo_members'); ?>');
            });


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
            $('#isActive').multiselect2({
                enableCaseInsensitiveFiltering: true,
                includeSelectAllOption: true,
                numberDisplayed: 1,
                buttonWidth: '180px',
                maxHeight: '30px'
            });
            $("#isActive").multiselect2('selectAll', false);
            $("#isActive").multiselect2('updateButtonText');
            $('#Com_MasterID').multiselect2({
                enableCaseInsensitiveFiltering: true,
                includeSelectAllOption: true,
                numberDisplayed: 1,
                buttonWidth: '180px',
                maxHeight: '30px'
            });
            $("#Com_MasterID").multiselect2('selectAll', false);
            $("#Com_MasterID").multiselect2('updateButtonText');

            $('.select2').select2();

            fetch_all_member_details();
        });

        function fetch_all_member_details(name) {

            var Com_MasterID = $('#Com_MasterID').val();
            var GenderID = $('#GenderID').val();
            var RegionID = $('#RegionID').val();
            var GS_Division = $('#GS_Division').val();
            var isActive = $('#isActive').val();

            $('#memberTB').DataTable({

                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_all_member_details'); ?>",
                "aaSorting": [[2, 'desc']],
                "aoColumnDefs": [{"bSortable": false, "aTargets": [1, 7]}],
                "fnInitComplete": function () {

                },
                "fnDrawCallback": function (oSettings) {
                    $("[rel=tooltip]").tooltip();
                    var selectedRowID = parseInt('<?php echo (!empty($this->input->post('page_id'))) ? $this->input->post('page_id') : 0; ?>');
                    var tmp_i = oSettings._iDisplayStart;
                    var iLen = oSettings.aiDisplay.length;
                    var x = 0;
                    for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                        if (parseInt(oSettings.aoData[x]._aData['Com_MasterID']) == selectedRowID) {
                            var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                            $(thisRow).addClass('dataTable_selectedTr');
                        }
                        x++;
                    }
                },
                "aoColumns": [
                    {"mData": "Com_MasterID"},
                    {"mData": "image"},
                    {"mData": "MemberCode"},
                    {"mData": "CName_with_initials"},
                    {"mData": "CNIC_No"},
                    {"mData": "Gender"},
                    {"mData": "PrimaryNumber"},
                    {"mData": "Region"},
                    {"mData": "GS_Division"},
                    {"mData": "status"},
                    {"mData": "edit"}
                ],
                "fnServerData": function (sSource, aoData, fnCallback) {
                    aoData.push({"name": "GenderID", "value": GenderID});
                    aoData.push({"name": "GS_Division", "value": GS_Division});
                    aoData.push({"name": "RegionID", "value": RegionID});
                    aoData.push({"name": "Com_MasterID", "value": Com_MasterID});
                    aoData.push({"name": "isActive", "value": isActive});
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

        function loadEmployees() {
            var Com_MasterID = $('#Com_MasterID').val();
            var GenderID = $('#GenderID').val();
            var RegionID = $('#RegionID').val();
            var GS_Division = $('#GS_Division').val();

            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {
                    'Com_MasterID': Com_MasterID,
                    'GenderID': GenderID,
                    'RegionID': RegionID,
                    'GS_Division': GS_Division
                },
                url: '<?php echo site_url("CommunityNgo/loadEmployees"); ?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();

                    $('#memberdrp').html(data);

                    $('#Com_MasterID').multiselect2({
                        enableCaseInsensitiveFiltering: true,
                        includeSelectAllOption: true,
                        numberDisplayed: 1,
                        buttonWidth: '180px',
                        maxHeight: '30px'
                    });

                    $('#GS_Division').multiselect2({
                        enableCaseInsensitiveFiltering: true,
                        includeSelectAllOption: true,
                        numberDisplayed: 1,
                        buttonWidth: '180px',
                        maxHeight: '30px'
                    });

                    $('#GenderID').multiselect2({
                        enableCaseInsensitiveFiltering: true,
                        includeSelectAllOption: true,
                        numberDisplayed: 1,
                        buttonWidth: '180px',
                        maxHeight: '30px'
                    });

                    $('#RegionID').multiselect2({
                        enableCaseInsensitiveFiltering: true,
                        includeSelectAllOption: true,
                        numberDisplayed: 1,
                        buttonWidth: '180px',
                        maxHeight: '30px'
                    });


                }, error: function () {
                    myAlert('e', 'An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }


        function clear_all_filters() {
            $('#isActive').val("");
            $('#Com_MasterID').multiselect2('deselectAll', false);
            $('#Com_MasterID').multiselect2('updateButtonText');
            $('#GS_Division').multiselect2('deselectAll', false);
            $('#GS_Division').multiselect2('updateButtonText');
            $('#RegionID').multiselect2('deselectAll', false);
            $('#RegionID').multiselect2('updateButtonText');
            $('#GenderID').multiselect2('deselectAll', false);
            $('#GenderID').multiselect2('updateButtonText');

            fetch_all_member_details();
        }

        function callOTable(name) {
            fetch_all_member_details(name);
        }

        function generate_memberToPdf() {

            var form = document.getElementById('filterForm');
            form.target = '_blank';
            form.method = 'post';
            form.post = $('#filterForm').serializeArray();
            form.action = '<?php echo site_url('CommunityNgo/load_community_allMembers_details'); ?>';
            form.submit();

        }

        function excel_Export() {
            var form = document.getElementById('filterForm');
            form.target = '_blank';
            form.method = 'post';
            form.post = $('#filterForm').serializeArray();
            form.action = '<?php echo site_url('CommunityNgo/export_excel'); ?>';
            form.submit();
        }

        function memberReportPdf(Com_MasterID) {
            var win = window.open('<?php echo site_url('CommunityNgo/load_community_member_details'); ?>' + '//' + Com_MasterID);
            win.focus();
        }

        function memberFillupPdf(){
            var win = window.open('<?php echo base_url("images/community/MemberFillupForm.pdf") ?>');
            win.focus();
        }

        function delete_communityMembers(id) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>", /*You want to delete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>", /*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'Com_MasterID': id},
                        url: "<?php echo site_url('CommunityNgo/delete_community_members'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            loadEmployees();
                            fetch_all_member_details();

                            if (data['error'] == 1) {
                                myAlert('e', data['message']);
                            }
                            else if (data['error'] == 0) {
                                myAlert('s', data['message']);
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function switchMemInApplySN(x) {

            var datValue = x.id;

            if (document.getElementById(datValue).checked == true) {

                document.getElementById('applySNbtn').style.display = 'block';
                $('span.snMemAppTitleCls').attr('title','Switch Off Apply Serial No');

            } else {

                document.getElementById('applySNbtn').style.display = 'none';

                $('span.snMemAppTitleCls').attr('title','Switch On Apply Serial No');

            }

        }

        function apply_memberSNFormat(){
            bootbox.confirm("<?php echo $this->lang->line('CommunityNgo_apply_confirmation'); ?>", function (result) {
                if (result) {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {},
                        url: "<?php echo site_url('CommunityNgo/apply_memberSNFormat'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            myAlert(data[0], data[1]);
                            loadEmployees();
                            fetch_all_member_details();
                            stopLoad();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            myAlert('e', '<br>Message: ' + errorThrown);
                        }
                    });
                }
            });

        }


    </script>

    <!-- add class for filter menu buttons - effect  --->
    <script>
        $(".set-animation .btn-default").addClass('hvr-shutter-in-horizontal');
    </script>


<?php
/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 2016-10-30
 * Time: 4:12 PM
 */