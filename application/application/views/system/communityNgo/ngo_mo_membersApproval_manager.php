<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$boxTitle = $this->lang->line('communityngo_member_approval');
echo head_page_employee();

$isPendingDataAvailable = 0;

 //   $isPendingDataAvailable = isPendingDataAvailable();

$empCode_isAutoGenerate = getPolicyValues('ECG', 'All');

$this->load->helper('community_ngo_helper');

$statusCount = fetch_memApproval_status();
$regions_arr = fetch_approvalRegions();
$regions_length = count($regions_arr);
$genderFil_arr = fetch_memGender_approvals();
$genderFil_length = count($genderFil_arr);
$filterPost = $this->input->post('filterPost');
$alphas = range('A', 'Z');

/**** Pagination variables ***/
$isInitialLoad = 1;
$memApproval_list = '';
$pagination = '';
$per_page = 10;
$filterDisplay = '';
$femCount = 0;

$isFiltered = 0;
if(!empty($filterPost)){
    $s_alphaSearch = $filterPost['alphaSearch'];
    $s_searchKeyword = $filterPost['searchKeyword'];
    $ap_genderApr = $filterPost['genderApr'];
    $s_regionAr = $filterPost['regionAr'];
    $s_status = $filterPost['apprvlStatus'];
    $s_pagination = $filterPost['pagination'];

    if( !empty($s_alphaSearch) || !empty($s_searchKeyword) || !empty($ap_genderApr) || !empty($s_regionAr) || !empty($s_pagination) || $s_status != '' && $s_status != 'null' ){
        $isFiltered = 1;
        $isInitialLoad = 0;
    }
}

if($isFiltered == 0){
    $data_arr = memApprovalsPagination();
    $memApproval_list = $data_arr['memApproval_list'];
    $pagination = $data_arr['pagination'];
    $per_page = $data_arr['per_page'];
    $filterDisplay = $data_arr['filterDisplay'];
    $femCount = $data_arr['femCount'];
}
?>

    <style>
        #menu ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;

        }

        #menu li {
            float: left;
        }

        #menu li div {
            display: block;
            color: black;
            text-align: center;

            text-decoration: none;
            border: 1px solid #efefef;
        }

        #menu li a:hover {
            cursor: pointer;
        }

        #designation-area{
            max-height: 300px;
            overflow-y: scroll;
        }

        #segment-area{
            max-height: 150px;
            overflow-y: scroll;
        }

        .scroll_emp{
            height: 722px;
            overflow-y: auto;
            overflow-x: hidden;
            direction:ltr;
        }

        .scroll_style::-webkit-scrollbar {
            width: 5px;
        }

        .scroll_style::-webkit-scrollbar-track {
            background: #ddd;
        }

        .scroll_style::-webkit-scrollbar-thumb {
            background: #666;
        }

        #first-in-emp-list{
            width: 2px;
            height: 0px;
            border: 0px;
        }

        .emp-status-label{
            padding: 4px 14px;
        }

        .emp-status-label:hover {
            cursor: default;
        }

        .status-list{
            font-weight: bold;
        }

        fieldset {
            border: 1px solid silver;
            border-radius: 5px;
            padding: 1%;
            padding-bottom: 15px;
            margin: auto;
            margin-bottom: 10px;
        }

        legend {
            width: auto;
            border-bottom: none;
            margin: 0px 10px;
            font-size: 20px;
            font-weight: 500
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/community_ngo/css/styles.css'); ?>" class="community_master_styles">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/community_ngo/css/approvalMem-tab.css'); ?>" class="community_master_styles">

    <style>
        .icons:hover {
            font-size: 18px;
        }

        .imgs:hover {
            width: 140px;
            height: 134px;
        }

        .bs{
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.12), 0 1px 4px rgba(0, 0, 0, 0.24);
        }
        .carousel-inner > .item {
            height: 58vh;
        }

        .carousel-inner > .item > img {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            max-height: 500px;
            max-width : 600px;
        }

        .carousel-caption {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 10;
            padding-top: 5px;
            padding-bottom: 5px;
            color: #fff;
            text-align: center;
            background: rgba(0, 0, 0, 0.4);
        }

        @media screen and (max-width: 600px) {
            #title_message {
                visibility: hidden;
                clear: both;
                float: left;
                margin: 10px auto 5px 20px;
                width: 28%;
                display: none;
            }
        }

        .dlg {
            position: fixed;
            top: 15%;
            left: 25%;
            transform: translate(30%, 70%);
        }
    </style>
    <div class="row" id="memAproval_head">
        <div class="col-sm-12">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 page-sidebar visible-lg"><span style="font-size:24px"><?php echo $boxTitle; ?></span></div>
            <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 remove-margin community-create-header">
                <div class="hidden-lg">
                    <span style="font-size:24px"><?php echo $boxTitle; ?></span>
                </div>
                <div class="box-tools hidden-lg pull-right close-sm" style="left: 35px; top:-40px; position: relative;">
                    <button class="btn btn-box-tool headerclose" style="color: #fff; margin-left: 200%;"><i class="fa fa-times"></i></button>
                    <button class="btn btn-box-tool headerclose" style="color: #fff; margin-left: 200%;"><i class="fa fa-bell" aria-hidden="true"></i></button>
                </div>
                <div class="box-tools visible-lg pull-right emp-master-close-lg">
                    <?php
                    if(count($isPendingDataAvailable)){
                        echo '<button  id="" class="btn btn-box-tool" style="color: #fff; padding: 8px 0px 0px 0px"><i class="fa fa-bell" aria-hidden="true" onclick="openMem_notificationModal()"></i></button>';
                    }
                    ?>

                    <button class="btn btn-box-tool headerclose" style="color: #fff; padding: 8px 8px 0px 0px"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-left: -10px; margin-right: -2px">
        <div class="container1">
            <div class="row">
                <div class="col-sm-12" id="filter-display">
                    <div class="col-sm-8" id="filter-text"></div>
                    <div class="col-sm-2 pull-right">
                        <div class="btn-group pull-right">
                            <!--<button type="button" class="btn btn-default"><i class="fa fa-align-justify" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-default"><i class="fa fa-user" aria-hidden="true"></i></button>-->
                        </div>
                    </div>
                </div>
                <div class="clearfix visible-xs visible-sm">&nbsp;</div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 page-sidebar">
                    <aside>
                        <div class="white-container mb0">
                            <div class="widget sidebar-widget jobs-search-widget" style="margin-top: -30px;">
                                <div class="widget-content">
                                    <input type="text" id="searchKey" class="form-control mt10" onkeyup="approveMem_search(this)"
                                           placeholder="<?php echo $this->lang->line('common_search');?>" ><!--Search-->
                                </div>
                            </div>

                            <div class="widget sidebar-widget jobs-filter-widget">
                                <div class="widget-content">

                                    <h6 style="font-size: 16px;"><?php echo $this->lang->line('communityngo_approval_status'); ?><!--Approval Status--></h6>
                                    <div>
                                        <ul class="filter-list" id="status-area">
                                            <li class="status-list" onclick="selectFilter('status-list', this)" data-val="1">
                                                <a href="#"><?php echo $this->lang->line('communityngo_approved').'<span> ('.$statusCount['memApproved'].') </span>'; ?><!--Approved--></a>
                                            </li>
                                            <li class="status-list" onclick="selectFilter('status-list', this)" data-val="4">
                                                <a href="#"><?php echo $this->lang->line('communityngo_not_approved').'<span> ('.$statusCount['noMemApproved'].') </span>'; ?>
                                                    <!--Not Approved--></a>
                                            </li>
                                            <li class="status-list" onclick="selectFilter('status-list', this)" data-val="5">
                                                <a href="#"><?php echo $this->lang->line('communityngo_pending_clarification').'<span> ('.$statusCount['memAppClarify'].') </span>'; ?>
                                                    <!--Pending For Clarification--></a>
                                            </li>
                                            <li class="status-list" onclick="selectFilter('status-list', this)" data-val="3">
                                                <a href="#"><?php echo $this->lang->line('communityngo_pending_approvals').'<span> ('.$statusCount['pendingApprovals'].') </span>'; ?>
                                                    <!--Pending Approvals--></a>
                                            </li>
                                        </ul>

                                        <a href="#" class="toggle"></a>
                                    </div>

                                    <h6 style="font-size: 16px; margin-top: 50px;"><?php echo $this->lang->line('communityngo_region');?><!--Regions--></h6>
                                    <div>
                                        <ul class="filter-list scroll_style" id="segment-area">
                                            <?php
                                            if(!empty($regions_arr)){
                                                foreach($regions_arr as $seg){
                                                    $description = toolTip_filter($seg['Description'], 15);
                                                    $stateID = $seg['stateID'];
                                                    $fn = 'class="segment-list" onclick="selectFilter(\'segment-list\', this)" data-val="'.$stateID.'"';
                                                    $fn .= 'data-text="'.trim($seg['Description']).'"';
                                                    echo '<li '.$fn.'><a href="#">'.$description.' <span>('.$seg['femCount'].')</span></a></li>';
                                                }
                                            }
                                            ?>
                                        </ul>

                                        <a href="#" class="toggle"></a>
                                    </div>

                                    <h6 style="font-size: 16px; margin-top: 50px;"><?php echo $this->lang->line('common_gender');?><!--Gender--></h6>
                                    <div>
                                        <ul class="filter-list scroll_style" id="designation-area" >
                                            <?php
                                            if(!empty($genderFil_arr)){
                                                foreach($genderFil_arr as $genderFil){
                                                    $description = toolTip_filter($genderFil['genderName'], 15, 18);
                                                    $genderFilID = $genderFil['genderID'];
                                                    $fn = 'class="designation-list" onclick="selectFilter(\'designation-list\', this)" data-val="'.$genderFilID.'"';
                                                    $fn .= 'data-text="'.trim($genderFil['genderName']).'"';
                                                    echo '<li '.$fn.'><a href="#">'.$description.' <span>('.$genderFil['femCount'].')</span></a></li>';
                                                }
                                            }
                                            ?>
                                        </ul>

                                        <a href="#" class="toggle"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 page-content">
                    <div id="alphas" class="">
                        <div class="row">
                            <div class="col-md-12 alpha-tab-container">
                                <div class="clearfix visible-xs visible-sm col-xs-1">&nbsp;</div>
                                <div class="col-lg-11 col-xs-9 alpha-tab">
                                    <div class="alpha-tab-content active scroll_emp scroll_style" id="">
                                        <div class="row " id="memApprovals_list" style="padding-right: 1%; padding-left: 3%;">
                                            <?php echo $memApproval_list; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-xs-3 alpha-tab-menu tabs-right">
                                    <div class="list-group">
                                        <?php
                                        foreach ($alphas as $key => $val) {
                                            //$active = ($key == 0)? 'active' : '';
                                            $active = '';
                                            $onClick = 'onclick="memApprovalsFilter(\''.$val.'\',\'yes\', this)"';
                                            $dataVal = 'data-value="'.$val.'"';
                                            ?>
                                            <a href="#" class="list-group-item alpha-list text-center <?php echo $active;?>" <?php echo  $dataVal; ?> <?php echo  $onClick; ?>>
                                                <span class="glyphicon"><?php echo $val; ?></span>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding-right: 5px;">
                        <div class="pagination-content clearfix" id="emp-master-pagination" style="padding-top: 10px">
                            <p id="filterDisplay"><?php echo $filterDisplay; ?></p>

                            <nav>
                                <ul class="list-inline" id="pagination-ul">
                                    <?php echo $pagination; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="memDoc_app_modal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="close_verifyAppMod();">&times;</button>
                    <h4 class="modal-title" id="memDoc_title"> <?php echo $this->lang->line('communityngo_memDoc_approval_verify'); ?><!--Member Document Approval Verification--> </h4>
                </div>
                <?php echo form_open('', 'role="form" id="memApproval_mas_form"'); ?>

                <div class="modal-body">
                    <div class="row">
                        <div id="docApprovalDiv">

                        </div>
                        <div  class="col-sm-12">
                            <div class="form-group col-md-3">
                                <label class="title">Send SMS Notification</label>
                            </div>
                            <div class="form-group col-md-9">
                                <div class="skin skin-square item-iCheck">
                                    <div class="skin-section extraColumns" style="float: left;"><input id="IsActive" type="checkbox"
                                                                                  class="IsActive" value="1" checked><label
                                                for="checkbox">&nbsp;</label></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="appLeaderId" name="appLeaderId">
                        <input type="hidden" id="phone_no" name="phone_no">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-floppy-disk"
                                                                               aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?><!--Save-->
                    </button>
                    <button data-dismiss="modal" class="btn btn-default btn-sm" type="button" onclick="close_verifyAppMod();"><?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Carousel modal -->
    <div class="modal fade" id="carousel_modal" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog dlg" role="document" style="width: 50%;">
            <div class="modal-content" style="background-color: rgba(0, 0, 0, 1);">
                <button type="button" class="close" data-dismiss="modal" id="closeBtn2" style="color: white;margin-right: 5px;margin-top: 5px;" onclick="close_docAppMod();"><i class="fa fa-close"></i></button>
                <div class="modal-body" id="carouselDiv" style="max-height: 600px;">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approval_mem_notifications" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Member Notifications</h3>
                </div>
                <div role="form" id="" class="form-horizontal" autocomplete="off">
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('plugins/community_ngo/scripts.js'); ?>" class="community_master_styles"></script>
    <!--<script type="text/javascript" src="<?php /*echo base_url('plugins/community_ngo/jquery-ui.js'); */?>" class="community_master_styles" id="jquery-ui-file"></script>-->

    <script type="text/javascript">
        var isSearchedWithTextBox = null;
        var isInitialLoad = '<?php  echo $isInitialLoad; ?>';
        var regionsLength = '<?php  echo $regions_length; ?>';
        var designation_length = '<?php  echo $genderFil_length; ?>';
        var per_page = '<?php  echo $per_page; ?>';
        var lastKeyWordSearch = '';
        var data_paginationFromInitial = 0;
        var searchRequest = null;
        var error_occurred_str = '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.';/*An Error Occurred! Please Try Again*/

        if(regionsLength > 10){
            /*$('#segment-area').slimScroll({
             height: '200px',
             size : '3px',
             alwaysVisible: true,
             wheelStep:  '4',
             barClass : 'filter-slim-scroll-bar',
             wrapperClass : 'filter-slim-scroll-wrapper'
             });*/
        }

        if(designation_length > 10){
            /*$('#designation-area').slimScroll({
             size : '3px',
             alwaysVisible: true,
             wheelStep:  '4',
             barClass : 'filter-slim-scroll-bar',
             wrapperClass : 'filter-slim-scroll-wrapper'
             });*/
        }

        $('.filter-slim-scroll-wrapper').css('height', 'auto');

        memApproval_list_scroll();

        /** Trigger first alphabet **/
        /*$('.list-group a:first').trigger("click");*/

        $("div.alpha-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();

            if($(this).hasClass('alpha-active')){
                $(this).removeClass("active alpha-active");
                $('.remove-filter-alpha').remove();
            }
            else{

                $(this).siblings('a.active').removeClass("active alpha-active");
                $(this).addClass("active alpha-active");
                $('.remove-filter-alpha').remove();

                var str = '<span class="remove-filter-alpha"><i class="fa fa-times" style=" color: black; background: none;"></i></span>';
                $(this).append(str);
            }

        });

        $('input.example').on('change', function() {
            $('input.example').not(this).prop('checked', false);
        });

        $(document).ready(function () {
            $('.headerclose').click(function () {
                fetchPage('system/communityNgo/ngo_mo_membersApproval_manager','','Member Approval')
            });


            $('#memApproval_mas_form').bootstrapValidator({
                live: 'enabled',
                message: 'This value is not valid.',
                excluded: [':disabled'],
                fields: {
                    description: {validators: {notEmpty: {message: 'Approval Remark is required.'}}},
                },
            }).on('success.form.bv', function (e) {
                e.preventDefault();
                var $form = $(e.target);
                var bv = $form.data('bootstrapValidator');
                var data = $form.serializeArray();
                var isDefault;
                if ($("#IsActive").is(':checked')) {
                    IsActive = 1;
                } else {
                    IsActive = 0;
                }
                data.push({name: "IsActive", value: IsActive});
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('CommunityNgo/save_mApproval_del'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            $('#memDoc_app_modal').modal('hide');
                            $('#memApproval_mas_form')[0].reset();
                            $('#memApproval_mas_form').bootstrapValidator('resetForm', true);
                            var alpha = $('.list-group-item.active').attr('data-value');
                            memApprovalsFilter(alpha);
                        } else {
                            $('.btn-primary').prop('disabled', false);
                        }
                    },
                    error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        stopLoad();
                        refreshNotifications(true);
                    }
                });
            });
        });

        function selectFilter(filterType, obj){
            isSearchedWithTextBox = null;
            $('.'+filterType).removeClass('active-list');
            $('.'+filterType+'-remove-filter').remove();
            var removeSpan = '<span class="remove-filter '+filterType+'-remove-filter" onclick="removeFilterItem(this, \''+filterType+'\')"><i class="fa fa-times"></i></span>';
            $(obj).addClass('active-list');
            $('.'+filterType+'.active-list').after(removeSpan);

            window.localStorage.setItem('emp-master-'+filterType, $(obj).attr('data-val'));

            filterText();

        }

        function removeFilterItem(obj, filterType){
            $(obj).parent().find('li').removeClass('active-list');
            $(obj).remove();
            window.localStorage.setItem('emp-master-'+filterType, '');

            filterText();
        }

        function filterText(){
            per_page = 0;

            var alpha = $('.list-group-item.active').attr('data-value');
            memApprovalsFilter(alpha);

            var regionAr = $('.segment-list.active-list').attr('data-text');
            var genderApr = $('.designation-list.active-list').attr('data-text');
            var apprvlStatus = $('.status-list.active-list').attr('data-text');
            var str = '';

            if(regionAr != undefined){
                str += '<li ><?php echo $this->lang->line('communityngo_region');?> > '+regionAr +' &nbsp;&nbsp;&nbsp;&nbsp;</li>';<!--Region-->
            }

            var separatorStr = '<li class="divider-vertical-menu"><div class="docs-toolbar-small-separator goog-toolbar-separator goog-inline-block"';
            separatorStr += 'id="slideLayoutSeparator" aria-disabled="true" role="separator" style="user-select: none;">&nbsp;</div></li>';
            if(genderApr != undefined){
                var separator = (str != '')? separatorStr : '';
                var separator2 = (str != '')? '&nbsp;&nbsp;&nbsp;&nbsp;' : '';
                str += separator+'<li >'+separator2+' Gender > '+genderApr +'</li>';
            }

            str = '<ul class="filter-item-ul"> '+str;
            str += '</ul>';

            $('#filter-text').html(str);
        }

        function pagination(obj){
            $('.employee-pagination').removeClass('paginationSelected');
            $(obj).addClass('paginationSelected');

            var data_pagination = $('.employee-pagination.paginationSelected').attr('data-emp-pagination');
            window.localStorage.setItem('emp-master-pagination', data_pagination);

            var alpha = $('.list-group-item.active').attr('data-value');
            memApprovalsFilter(alpha);
        }

        function approveMem_search(obj){
            isSearchedWithTextBox = 1;
            var keyword = $.trim($(obj).val());
            if(keyword != lastKeyWordSearch){
                lastKeyWordSearch = keyword;
                per_page = 0;
                var alpha = $('.list-group-item.active').attr('data-value');
                memApprovalsFilter(alpha);
            }

            window.localStorage.setItem('emp-master-searchKeyword', keyword);
            window.localStorage.setItem('emp-master-pagination', 0);
        }

        function memApprovalsFilter(letter, isFromAlphas=null, obj=null){

            if(isInitialLoad == 1){
                if(searchRequest){
                    searchRequest.abort();
                }
                if(isFromAlphas == 'yes'){
                    isSearchedWithTextBox = null;
                    per_page = 0;
                    window.localStorage.setItem('emp-master-alpha-search', letter);
                    if($(obj).hasClass('alpha-active')){
                        letter = '';
                        window.localStorage.setItem('emp-master-alpha-search', '');
                    }
                }

                var searchKey = $.trim($('#searchKey').val());
                var regionAr = $('.segment-list.active-list').attr('data-val');
                var genderApr = $('.designation-list.active-list').attr('data-val');
                var apprvlStatus = $('.status-list.active-list').attr('data-val');

                var data_pagination = 0;

                if(isFromAlphas == 'no' ){
                    data_pagination = data_paginationFromInitial;
                    per_page = 10;
                }
                else{
                    data_pagination = $('.employee-pagination.paginationSelected').attr('data-emp-pagination');
                }

                var uriSegment = ( data_pagination == undefined ) ? per_page :  ((parseInt(data_pagination)-1)*per_page);
                var dataPost = [{'name': 'letter', 'value':letter}];

                dataPost.push({'name': 'searchKey', 'value':searchKey});
                dataPost.push({'name': 'regionAr', 'value':regionAr});
                dataPost.push({'name': 'genderApr', 'value':genderApr});
                dataPost.push({'name': 'apprvlStatus', 'value':apprvlStatus});
                dataPost.push({'name': 'data_pagination', 'value':data_pagination});

                searchRequest = $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: dataPost,
                    url: '<?php echo site_url("CommunityNgo/memApprovalsFilter"); ?>/'+uriSegment,
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        $('#memApprovals_list').html(data['memApproval_list']);
                        $('#pagination-ul').html(data['pagination']);
                        $('#filterDisplay').html(data['filterDisplay']);
                        $('#empTotalCount').html('('+data['femCount']+')');
                        per_page = data['per_page'];
                        setTimeout(function(){ memApproval_list_scroll(); },300);
                        //$("html, body").animate({scrollTop: "0px"}, 10);

                    }, error: function (xhr, textStatus, errorThrown) {
                        stopLoad();
                        if (xhr.status != 0) {
                            myAlert('e', error_occurred_str);
                        }
                    }
                });
            }

        }

        function memApproval_list_scroll(){
            /*$('#memApprovals_list').slimScroll({
             scrollTo: '0px',
             height : '715px',
             size : '3px',
             alwaysVisible: true,
             barClass : 'filter-slim-scroll-bar-large',
             wrapperClass : 'filter-slim-scroll-wrapper-memApprovals_list',
             railVisible: true,
             railColor: '#222',
             position: 'left',
             wheelStep:  '5'
             });*/
            $('#first-in-emp-list').show().focus();
            $('#first-in-emp-list').hide();

            if(isSearchedWithTextBox == 1){
                $('#searchKey').focus();
            }

        }

        function clear_all_filters() {
            $('#apprvlStatus').val("");
            $('#regionAr').multiselect2('deselectAll', false);
            $('#regionAr').multiselect2('updateButtonText');
            window.localStorage.removeItem("apprvlStatus");
            window.localStorage.removeItem("regionAr");
            fetchEmployees();
        }

        function openVerify_docs(appMemID, appMemName,VerificationDocID,memDocDescription,phone_no){
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {appMemID:appMemID, appMemName:appMemName,VerificationDocID:VerificationDocID,memDocDescription:memDocDescription},
                url: '<?php echo site_url("CommunityNgo/memDoc_app_details"); ?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#phone_no').val(phone_no);
                    $('#appLeaderId').val(appMemID);

                    $('#memDoc_title').html(appMemName +' -'+ '<?php echo $this->lang->line('communityngo_approval_verify'); ?>'+' ('+ memDocDescription +')');
                    $('#memDoc_app_modal').modal('show');
                    $('#docApprovalDiv').html(data);


                }, error: function () {
                    myAlert('e', error_occurred_str);
                    stopLoad();
                }
            });
        }

        function close_docAppMod() {
            document.getElementById('closeBtn2').click();
        }

        function close_verifyAppMod() {
            $('#IsActive').iCheck('check');
        }

        function open_ApproveDocViewer(docType,FamMasterID)
        {
            $.ajax({
                type: "POST",
                url: "CommunityNgo/memApprovalBody",
                data: {'docType' : docType,'FamMasterID':FamMasterID},
                success: function (data) {
                    $('#carouselDiv').html(data);
                    $('#carousel_modal').modal('show');
                    $('#carousel-example-generic').carousel({
                        interval: 2500,
                        cycle: true
                    });
                    var modal = document.getElementById('carousel_modal');
                }
            });
        }

        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-purple',
            radioClass: 'iradio_square_relative-purple',
            increaseArea: '20%'
        });

        function openMem_notificationModal(){
            $('#approval_mem_notifications').modal('show');
        }

        function openPersonal_notifiyModal(appMemID){
            $('#approval_mem_notifications').modal('show');
        }

    </script>

<?php

if($isFiltered == 1){
    $s_alphaSearch = $filterPost['alphaSearch'];
    $s_searchKeyword = $filterPost['searchKeyword'];
    $ap_genderApr = $filterPost['genderApr'];
    $s_regionAr = $filterPost['regionAr'];
    $s_status = $filterPost['apprvlStatus'];


    if( !empty($s_searchKeyword) ){
        echo "<script> $('#searchKey').val(\"".$s_searchKeyword."\"); </script>";
    }

    if( !empty($ap_genderApr) ){
        echo "<script> $('.designation-list[data-val=\"".$ap_genderApr."\"]').click(); </script>";
    }

    if( !empty($s_regionAr) ){
        echo "<script> $('.segment-list[data-val=\"".$s_regionAr."\"]').click(); </script>";
    }

    if( $s_status != '' && $s_status != 'null' ){
        echo "<script> $('.status-list[data-val=\"".$s_status."\"]').click(); </script>";
    }

    if( !empty($s_alphaSearch)){
        echo "<script> $('.alpha-list[data-value=\"".$s_alphaSearch."\"]').click(); </script>";
    }



    if( !empty($s_pagination)){
        echo "<script> data_paginationFromInitial = $s_pagination; </script>";
    }

    echo "<script>
            setTimeout(function(){
                isInitialLoad = 1;
                per_page=0;
                memApprovalsFilter('".$s_alphaSearch."', 'no');
            }, 400);
         </script>";

}

?>

<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 12/11/2018
 * Time: 11:30 AM
 */