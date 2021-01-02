<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$boxTitle = $this->lang->line('communityngo_advertisement_approval');
echo head_page_employee();

$isPendingDataAvailable = 0;

//   $isPendingDataAvailable = isPendingDataAvailable();

$empCode_isAutoGenerate = getPolicyValues('ECG', 'All');

$this->load->helper('community_ngo_helper');

$date_format_policy = date_format_policy();

$statusCount = fetch_adApproval_status();
$adType_arr = fetch_advertiseTypes();
$adType_length = count($adType_arr);
$addCategory_arr = fetch_advertisement_cat_approval();
$addCategory_length = count($addCategory_arr);
$filterPost = $this->input->post('filterPost');
$alphas = range('A', 'Z');

/**** Pagination variables ***/
$isInitialLoad = 1;
$adApprovals_list = '';
$pagination = '';
$per_page = 10;
$filterDisplay = '';
$comAdCount = 0;

$isFiltered = 0;
if(!empty($filterPost)){
    $s_alphaSearch = $filterPost['alphaSearch'];
    $s_searchKeyword = $filterPost['searchKeyword'];
    $ap_categoryApr = $filterPost['categoryApr'];
    $s_adTypeAr = $filterPost['adTypeAr'];
    $s_status = $filterPost['apprvlStatus'];
    $s_pagination = $filterPost['pagination'];

    if( !empty($s_alphaSearch) || !empty($s_searchKeyword) || !empty($ap_categoryApr) || !empty($s_adTypeAr) || !empty($s_pagination) || $s_status != '' && $s_status != 'null' ){
        $isFiltered = 1;
        $isInitialLoad = 0;
    }
}

if($isFiltered == 0){
    $data_arr = addApprovalsPagination();
    $adApprovals_list = $data_arr['adApprovals_list'];
    $pagination = $data_arr['pagination'];
    $per_page = $data_arr['per_page'];
    $filterDisplay = $data_arr['filterDisplay'];
    $comAdCount = $data_arr['comAdCount'];
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
    <style>
        /*! video Modals v10.1.2 | (c) 2017 Chris Ferdinandi | MIT License | http://github.com/cferdinandi/modals */
        .videoModal{max-width:100%;padding:.5em 1em;visibility:hidden;}@media (min-width:40em){.videoModal{max-width:98%}}.videoModal.active{display:block;height:100%;left:0;max-height:100%;overflow:auto;position:fixed;right:0;top:0;visibility:visible;-webkit-overflow-scrolling:touch}@media (min-width:30em){.videoModal.active{height:auto;left:3%;margin-left:auto;margin-right:auto;right:3%;top:50px}}@media (min-width:20em){.videoModal.active{left:20%;right:8%}.videoModal.active.videoModal-medium{width:35em}.videoModal.active.videoModal-small{width:25em}}.videoModal:focus{outline:none}.videoModal-bg{bottom:0;position:fixed;left:0;opacity:.9;right:0;top:0;}  .close{color:gray;cursor:pointer;float:right;font-weight:700;font-size:1.5em;text-decoration:none}  .close:hover{color:#5a5a5a;cursor:pointer}

    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/community_ngo/css/addApprovalStyle.css'); ?>" class="community_master_styles">
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
    <div class="row" id="addmntApproval_head">
        <div class="col-sm-12">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 page-sidebar visible-lg"><span style="font-size:24px;color:#e8e8e8;"><?php echo $boxTitle; ?></span></div>
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

                                    <h6 style="font-size: 16px;"><?php echo $this->lang->line('communityngo_AddApproval_status'); ?><!--Approval Status--></h6>
                                    <div>
                                        <ul class="filter-list" id="status-area">
                                            <li class="status-list" onclick="select_adFilter('status-list', this)" data-val="1">
                                                <a href="#"><?php echo $this->lang->line('communityngo_approved').'<span> ('.$statusCount['adApproved'].') </span>'; ?><!--Approved--></a>
                                            </li>
                                            <li class="status-list" onclick="select_adFilter('status-list', this)" data-val="4">
                                                <a href="#"><?php echo $this->lang->line('communityngo_not_approved').'<span> ('.$statusCount['noAdApproved'].') </span>'; ?>
                                                    <!--Not Approved--></a>
                                            </li>
                                            <li class="status-list" onclick="select_adFilter('status-list', this)" data-val="5">
                                                <a href="#"><?php echo $this->lang->line('communityngo_pending_clarification').'<span> ('.$statusCount['adAppClarify'].') </span>'; ?>
                                                    <!--Pending For Clarification--></a>
                                            </li>
                                            <li class="status-list" onclick="select_adFilter('status-list', this)" data-val="3">
                                                <a href="#"><?php echo $this->lang->line('communityngo_pending_approvals').'<span> ('.$statusCount['pendingAdApprovals'].') </span>'; ?>
                                                    <!--Pending Approvals--></a>
                                            </li>
                                        </ul>

                                        <a href="#" class="toggle"></a>
                                    </div>

                                    <h6 style="font-size: 16px; margin-top: 50px;"><?php echo $this->lang->line('communityngo_advertisement_type');?><!--Types--></h6>
                                    <div>
                                        <ul class="filter-list scroll_style" id="segment-area">
                                            <?php
                                            if(!empty($adType_arr)){
                                                foreach($adType_arr as $seg){
                                                    $advertisementType = toolTip_filter($seg['advertisementType'], 15);
                                                    $typeID = $seg['type_id'];
                                                    $fn = 'class="segment-list" onclick="select_adFilter(\'segment-list\', this)" data-val="'.$typeID.'"';
                                                    $fn .= 'data-text="'.trim($seg['advertisementType']).'"';
                                                    echo '<li '.$fn.'><a href="#">'.$advertisementType.' <span>('.$seg['comAdCount'].')</span></a></li>';
                                                }
                                            }
                                            ?>
                                        </ul>

                                        <a href="#" class="toggle"></a>
                                    </div>

                                    <h6 style="font-size: 16px; margin-top: 50px;"><?php echo $this->lang->line('common_category');?><!--category--></h6>
                                    <div>
                                        <ul class="filter-list scroll_style" id="designation-area" >
                                            <?php
                                            if(!empty($addCategory_arr)){
                                                foreach($addCategory_arr as $categoryFil){
                                                    $categoryName = toolTip_filter($categoryFil['categoryName'], 15, 18);
                                                    $categoryFilID = $categoryFil['id'];
                                                    $fn = 'class="designation-list" onclick="select_adFilter(\'designation-list\', this)" data-val="'.$categoryFilID.'"';
                                                    $fn .= 'data-text="'.trim($categoryFil['categoryName']).'"';
                                                    echo '<li '.$fn.'><a href="#">'.$categoryName.' <span>('.$categoryFil['comAdCount'].')</span></a></li>';
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
                                        <div class="row " id="adApprovals_list" style="padding-right: 1%; padding-left: 3%;">
                                            <?php echo $adApprovals_list; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-xs-3 alpha-tab-menu tabs-right">
                                    <div class="list-group">
                                        <?php
                                        foreach ($alphas as $key => $val) {
                                            //$active = ($key == 0)? 'active' : '';
                                            $active = '';
                                            $onClick = 'onclick="advertiseApprovalFilter(\''.$val.'\',\'yes\', this)"';
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

    <div class="modal fade" id="adDoc_app_modal" role="dialog" data-keyboard="false" data-backdrop="static" style="z-index: 1500;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="close_verifyAppMod();">&times;</button>
                    <h4 class="modal-title" id="memAdDoc_title"> <?php echo $this->lang->line('communityngo_memDoc_approval_verify'); ?><!--Advertisement Document Approval Verification--> </h4>
                </div>
                <?php echo form_open('', 'role="form" id="adApproval_mas_form"'); ?>

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
                        <input type="hidden" id="advertiseId" name="advertiseId">
                        <input type="hidden" id="phoneAd_no" name="phoneAd_no">
                        <input type="hidden" id="expire_days" name="expire_days">
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
                    <h3 class="modal-title">Advertisement Notifications</h3>
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
        var regionsLength = '<?php  echo $adType_length; ?>';
        var designation_length = '<?php  echo $addCategory_length; ?>';
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

        adApprovals_list_scroll();

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
                fetchPage('system/communityNgo/ngo_mo_advertisementApproval_mngr','','Advertisement Approval')
            });


            var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

            $('.dateAdpic').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            }).on('dp.change', function (ev) {
            });


            $('#adApproval_mas_form').bootstrapValidator({
                live: 'enabled',
                message: 'This value is not valid.',
                excluded: [':disabled'],
                fields: {
                    adApproval_remarks: {validators: {notEmpty: {message: 'Approval Remark is required.'}}},
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
                    url: "<?php echo site_url('CommunityNgo/save_addApproval_del'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            $('#adDoc_app_modal').modal('hide');
                            $('#adApproval_mas_form')[0].reset();
                            $('#adApproval_mas_form').bootstrapValidator('resetForm', true);
                            var alpha = $('.list-group-item.active').attr('data-value');
                            advertiseApprovalFilter(alpha);
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

        function select_adFilter(filterType, obj){
            isSearchedWithTextBox = null;
            $('.'+filterType).removeClass('active-list');
            $('.'+filterType+'-remove-filter').remove();
            var removeSpan = '<span class="remove-filter '+filterType+'-remove-filter" onclick="removeFilterItem(this, \''+filterType+'\')"><i class="fa fa-times"></i></span>';
            $(obj).addClass('active-list');
            $('.'+filterType+'.active-list').after(removeSpan);

            window.localStorage.setItem('emp-master-'+filterType, $(obj).attr('data-val'));

            adFilter_data();

        }

        function removeFilterItem(obj, filterType){
            $(obj).parent().find('li').removeClass('active-list');
            $(obj).remove();
            window.localStorage.setItem('emp-master-'+filterType, '');

            adFilter_data();
        }

        function adFilter_data(){
            per_page = 0;

            var alpha = $('.list-group-item.active').attr('data-value');
            advertiseApprovalFilter(alpha);

            var adTypeAr = $('.segment-list.active-list').attr('data-text');
            var categoryApr = $('.designation-list.active-list').attr('data-text');
            var apprvlStatus = $('.status-list.active-list').attr('data-text');
            var str = '';

            if(adTypeAr != undefined){
                str += '<li ><?php echo $this->lang->line('communityngo_advertisement_type');?> > '+adTypeAr +' &nbsp;&nbsp;&nbsp;&nbsp;</li>';<!--Types-->
            }

            var separatorStr = '<li class="divider-vertical-menu"><div class="docs-toolbar-small-separator goog-toolbar-separator goog-inline-block"';
            separatorStr += 'id="slideLayoutSeparator" aria-disabled="true" role="separator" style="user-select: none;">&nbsp;</div></li>';
            if(categoryApr != undefined){
                var separator = (str != '')? separatorStr : '';
                var separator2 = (str != '')? '&nbsp;&nbsp;&nbsp;&nbsp;' : '';
                str += separator+'<li >'+separator2+' Gender > '+categoryApr +'</li>';
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
            advertiseApprovalFilter(alpha);
        }

        function approveMem_search(obj){
            isSearchedWithTextBox = 1;
            var keyword = $.trim($(obj).val());
            if(keyword != lastKeyWordSearch){
                lastKeyWordSearch = keyword;
                per_page = 0;
                var alpha = $('.list-group-item.active').attr('data-value');
                advertiseApprovalFilter(alpha);
            }

            window.localStorage.setItem('emp-master-searchKeyword', keyword);
            window.localStorage.setItem('emp-master-pagination', 0);
        }

        function advertiseApprovalFilter(letter, isFromAlphas=null, obj=null){

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
                var adTypeAr = $('.segment-list.active-list').attr('data-val');
                var categoryApr = $('.designation-list.active-list').attr('data-val');
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
                dataPost.push({'name': 'adTypeAr', 'value':adTypeAr});
                dataPost.push({'name': 'categoryApr', 'value':categoryApr});
                dataPost.push({'name': 'apprvlStatus', 'value':apprvlStatus});
                dataPost.push({'name': 'data_pagination', 'value':data_pagination});

                searchRequest = $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: dataPost,
                    url: '<?php echo site_url("CommunityNgo/addApprovalsFilter"); ?>/'+uriSegment,
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        $('#adApprovals_list').html(data['adApprovals_list']);
                        $('#pagination-ul').html(data['pagination']);
                        $('#filterDisplay').html(data['filterDisplay']);
                        $('#empTotalCount').html('('+data['comAdCount']+')');
                        per_page = data['per_page'];
                        setTimeout(function(){ adApprovals_list_scroll(); },300);
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

        function adApprovals_list_scroll(){
            /*$('#adApprovals_list').slimScroll({
             scrollTo: '0px',
             height : '715px',
             size : '3px',
             alwaysVisible: true,
             barClass : 'filter-slim-scroll-bar-large',
             wrapperClass : 'filter-slim-scroll-wrapper-adApprovals_list',
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
            $('#adTypeAr').multiselect2('deselectAll', false);
            $('#adTypeAr').multiselect2('updateButtonText');
            window.localStorage.removeItem("apprvlStatus");
            window.localStorage.removeItem("adTypeAr");
            fetchEmployees();
        }

        function openAdVerify_docs(advertiseId,adMemName,adType_id,sub_category_name,phoneAd_no,expire_days){
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {advertiseId:advertiseId, adMemName:adMemName,adType_id:adType_id,sub_category_name:sub_category_name},
                url: '<?php echo site_url("CommunityNgo/addDoc_app_details"); ?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#phoneAd_no').val(phoneAd_no);
                    $('#advertiseId').val(advertiseId);
                    $('#expire_days').val(expire_days);

                    $('#memAdDoc_title').html(adMemName +' -'+ '<?php echo $this->lang->line('communityngo_advertisement_approval'); ?>'+' ('+ sub_category_name +')');
                    $('#adDoc_app_modal').modal('show');
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

        function open_ApproveAddViewer(advertiseId)
        {
            $.ajax({
                type: "POST",
                url: "CommunityNgo/addApprovalBody",
                data: {'advertiseId':advertiseId},
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

        function openMemAdd_notifiyModal(adMemID){
            $('#approval_mem_notifications').modal('show');
        }

    </script>

<?php

if($isFiltered == 1){
    $s_alphaSearch = $filterPost['alphaSearch'];
    $s_searchKeyword = $filterPost['searchKeyword'];
    $ap_categoryApr = $filterPost['categoryApr'];
    $s_adTypeAr = $filterPost['adTypeAr'];
    $s_status = $filterPost['apprvlStatus'];


    if( !empty($s_searchKeyword) ){
        echo "<script> $('#searchKey').val(\"".$s_searchKeyword."\"); </script>";
    }

    if( !empty($ap_categoryApr) ){
        echo "<script> $('.designation-list[data-val=\"".$ap_categoryApr."\"]').click(); </script>";
    }

    if( !empty($s_adTypeAr) ){
        echo "<script> $('.segment-list[data-val=\"".$s_adTypeAr."\"]').click(); </script>";
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
                advertiseApprovalFilter('".$s_alphaSearch."', 'no');
            }, 400);
         </script>";

}

?>


    <div class="modal videoModal" data-modal-window id="modal_adVideo">
        <iframe id="youtubeIframe" src="" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <button data-modal-close>Close</button>
    </div>


    <!--Audio player model-->
    <div class="example-modal">
        <div class="modal fade" id="modal_adAudio">
            <form role="form" id="adAudio_com_form" class="form-group">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="close_adAudio_com();"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('communityngo_upld_audio_player');?><!--Audio Player--> - &nbsp;&nbsp;<span id="audioSubject"></span></h4>
                    </div>
                    <div class="modal-body">
                        <iframe id="taudio" width="100%" height="200" src="" frameborder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="close_adAudio_com();"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                    </div>
                </div>
            </div>
            </form>
            </div>
        </div>

    <script>

        function openAdVideo_mod(uploadAdd_url){

            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {uploadAdd_url: uploadAdd_url},
                url: "<?php echo site_url('CommunityNgo/load_uploadAddVideo'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {

                    $('#youtubeIframe').attr('src', data);

                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });

        }

        /*! Modals v10.1.2 | (c) 2017 Chris Ferdinandi | MIT License | http://github.com/cferdinandi/modals */
        !(function(e,t){"function"==typeof define&&define.amd?define([],t(e)):"object"==typeof exports?module.exports=t(e):e.modals=t(e)})("undefined"!=typeof global?global:this.window||this.global,(function(e){"use strict";var t,o,n,l={},c="querySelector"in document&&"addEventListener"in e&&"classList"in document.createElement("_"),r="closed",d={selectorToggle:"[data-modal]",selectorWindow:"[data-modal-window]",selectorClose:"[data-modal-close]",modalActiveClass:"active",modalBGClass:"modal-bg",preventBGScroll:!0,preventBGScrollHtml:!0,preventBGScrollBody:!0,backspaceClose:!0,stopVideo:!0,callbackOpen:function(){},callbackClose:function(){}},a=function(){var e={},t=!1,o=0,n=arguments.length;"[object Boolean]"===Object.prototype.toString.call(arguments[0])&&(t=arguments[0],o++);for(;o<n;o++){var l=arguments[o];!(function(o){for(var n in o)Object.prototype.hasOwnProperty.call(o,n)&&(t&&"[object Object]"===Object.prototype.toString.call(o[n])?e[n]=a(!0,e[n],o[n]):e[n]=o[n])})(l)}return e},s=function(e,t){for(Element.prototype.matches||(Element.prototype.matches=Element.prototype.matchesSelector||Element.prototype.mozMatchesSelector||Element.prototype.msMatchesSelector||Element.prototype.oMatchesSelector||Element.prototype.webkitMatchesSelector||function(e){for(var t=(this.document||this.ownerDocument).querySelectorAll(e),o=t.length;--o>=0&&t.item(o)!==this;);return o>-1});e&&e!==document;e=e.parentNode)if(e.matches(t))return e;return null},i=function(e,t){if(t.stopVideo&&e.classList.contains(t.modalActiveClass)){var o=e.querySelector("iframe"),n=e.querySelector("video");if(o){var l=o.src;o.src=l}n&&n.pause()}},u=function(){var e=document.createElement("div");e.style.visibility="hidden",e.style.width="100px",e.style.msOverflowStyle="scrollbar",document.body.appendChild(e);var t=e.offsetWidth;e.style.overflow="scroll";var o=document.createElement("div");o.style.width="100%",e.appendChild(o);var n=o.offsetWidth;return e.parentNode.removeChild(e),t-n},m=function(){if(!document.querySelector("[data-modal-bg]")){var e=document.createElement("div");e.setAttribute("data-modal-bg",!0),e.classList.add(n.modalBGClass),document.body.appendChild(e)}},p=function(){var e=document.querySelector("[data-modal-bg]");e&&document.body.removeChild(e)};l.closeModal=function(e){var t=a(n||d,e||{}),l=document.querySelector(t.selectorWindow+"."+t.modalActiveClass);l&&(i(l,t),l.classList.remove(t.modalActiveClass),p(),r="closed",t.preventBGScroll&&(document.documentElement.style.overflowY="",document.body.style.overflowY="",document.body.style.paddingRight=""),t.callbackClose(o,l),o&&(o.focus(),o=null))},l.openModal=function(e,c,s){var i=a(n||d,s||{}),u=document.querySelector(c);"open"===r&&l.closeModal(i),e&&(o=e),u.classList.add(i.modalActiveClass),m(),r="open",u.setAttribute("tabindex","-1"),u.focus(),i.preventBGScroll&&(i.preventBGScrollHtml&&(document.documentElement.style.overflowY="hidden"),i.preventBGScrollBody&&(document.body.style.overflowY="hidden"),document.body.style.paddingRight=t+"px"),i.callbackOpen(e,u)};var v=function(e,t,o){if(o)return e.removeEventListener("touchstart",a,!1),e.removeEventListener("touchend",s,!1),void e.removeEventListener("click",i,!1);if(t&&"function"==typeof t){var n,l,c,r,d,a=function(e){n=!0,l=e.changedTouches[0].pageX,c=e.changedTouches[0].pageY},s=function(e){r=e.changedTouches[0].pageX-l,d=e.changedTouches[0].pageY-c,Math.abs(r)>=7||Math.abs(d)>=10||t(e)},i=function(e){if(n)return void(n=!1);t(e)};e.addEventListener("touchstart",a,!1),e.addEventListener("touchend",s,!1),e.addEventListener("click",i,!1)}},f=function(e){var t=e.target,o=s(t,n.selectorToggle),c=s(t,n.selectorClose),d=s(t,n.selectorWindow),a=e.keyCode;if(a&&"open"===r)(27===a||n.backspaceClose&&(8===a||46===a))&&l.closeModal();else if(t){if(d&&!c)return;!o||a&&13!==a?"open"===r&&(e.preventDefault(),l.closeModal()):(e.preventDefault(),l.openModal(o,o.getAttribute("data-modal"),n))}};return l.destroy=function(){n&&(v(document,null,!0),document.removeEventListener("keydown",f,!1),document.documentElement.style.overflowY="",document.body.style.overflowY="",document.body.style.paddingRight="",t=null,o=null,n=null)},l.init=function(e){c&&(l.destroy(),n=a(d,e||{}),t=u(),v(document,f),document.addEventListener("keydown",f,!1))},l}));

        /**
         * Autoplay a YouTube, Vimeo, or HTML5 video
         * @param  {Node} modal  The modal to search inside
         */
        var autoplayVideo = function (modal) {

            // Look for a YouTube, Vimeo, or HTML5 video in the modal
            var video = modal.querySelector('iframe[src*="www.youtube.com"], iframe[src*="player.vimeo.com"], video');

            // Bail if the modal doesn't have a video
            if (!video) return;

            // If an HTML5 video, play it
            if (video.tagName.toLowerCase() === 'video') {
                video.play();
                return;
            }

            // Add autoplay to video src
            // video.src: the current video `src` attribute
            // (video.src.indexOf('?') < 0 ? '?' : '&'): if the video.src already has query string parameters, add an "&". Otherwise, add a "?".
            // 'autoplay=1': add the autoplay parameter
            video.src = video.src + (video.src.indexOf('?') < 0 ? '?' : '&') + 'autoplay=1';

        };

        /**
         * Stop a YouTube, Vimeo, or HTML5 video
         * @param  {Node} modal  The modal to search inside
         */
        var stopVideo = function (modal) {

            // Look for a YouTube, Vimeo, or HTML5 video in the modal
            var video = modal.querySelector('iframe[src*="www.youtube.com"], iframe[src*="player.vimeo.com"], video');

            // Bail if the modal doesn't have a video
            if (!video) return;

            // If an HTML5 video, pause it
            if (video.tagName.toLowerCase() === 'video') {
                video.pause();
                return;
            }

            // Remove autoplay from video src
            video.src = video.src.replace('&autoplay=1', '').replace('?autoplay=1', '');

        };

        modals.init({
            callbackOpen: function ( toggle, modal ) {
                autoplayVideo(modal);
            },
            callbackClose: function ( toggle, modal ) {
                stopVideo(modal);
            }
        });
    </script>

    <script>


        function openAdAudio_modal(uploadAdd_url,upload_title)
        {

            $("iframe#taudio").attr('src',uploadAdd_url);

            document.getElementById('audioSubject').innerHTML = upload_title;

            $('#modal_adAudio').modal('show');



        }

        function close_adAudio_com() {

            $('#adAudio_com_form').bootstrapValidator('resetForm', true);
            $('#adAudio_com_form')[0].reset();
            $("iframe#taudio").attr('src','');

            $('#modal_adAudio').modal({backdrop: "static"});

        }

        function open_pageUrlInNewTab(url) {
            var win = window.open(url, '_blank');
            win.focus();
        }
    </script>
<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 12/11/2018
 * Time: 11:30 AM
 */
