<?php echo head_page('SYSTEM SETTINGS', false);
$supplier_arr = all_supplier_drop(false);
$date_format_policy = date_format_policy();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">

<style>
    #list-main .left-sidenav > .active > a {
        position: relative;
        z-index: 2;
        border-right: 0 !important;
    }

    #list-main .nav-list > .active > a, .nav-list > .active > a:hover {
        padding-left: 12px;
        font-weight: normal;
        color: #dd4b39;
        text-shadow: none;
        background-color: #dcdcdc;
        border-left: 3px solid #dd4b39;
    }

    #list-main .nav-list > .active > a, .nav-list > .active > a:hover, .nav-list > .active > a:focus {
        color: #dd4b39;

        background-color: rgba(239, 239, 239, 0.75);
    }

    #list-main .left-sidenav > li > a {
        display: block;
        width: 176px \9;
        margin: 0;
        padding: 4px 7px 4px 15px;
    !important;
        padding: 6px;
        font-size: 13px;

    }

    #list-main .nav-list > li > a {

        color: #222;
    }

    #list-main .nav-list > li > a, .nav-list .nav-header {

        text-shadow: 0 1px 0 rgba(255, 255, 255, .5);
    }

    #list-main .nav > li > a {
        display: block;
    }

    #list-main a, a:hover, a:active, a:focus {
        outline: 0;
    }

    #list-main .left-sidenav > .active {
        border-right: none;
        background-color: #f5f5f5;
    }

    #list-main.left-sidenav li {
        border-bottom: 1px solid #e5e5e5;
    }

    #list-main .left-sidenav li {
        border-bottom: 1px solid #e5e5e5;
    }

    #list-main li {
        line-height: 20px;
    }

    #list-main .nav-list {
        padding-right: 0px;
        padding-left: 0px;
    }

    #list-main a {
        text-decoration: none;
    }

    #list-main .left-sidenav .icon-chevron-right {
        float: right;
        margin-top: 2px;
        margin-left: -6px;
        opacity: .25;
        padding-right: 4px;

    }

    .flex {
        display: flex;
    }

    #list-main .sidebar-left {
        float: left;
    }

    article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
        display: block;
    }

    #list-main .left-sidenav {
        width: 200px;
        padding: 0;
        background-color: #fff;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        border: 1px solid #e5e5e5;
    }

    #list-main.nav-list {
        padding-right: 15px;
        padding-left: 15px;
        margin-bottom: 0;
    }

    #list-main .nav {
        margin-bottom: 20px;
        margin-left: 0;
        list-style: none;
    }

    #list-main ul, ol {
        padding: 0;
        margin: 0 0 10px 25px;
    }

    #list-main .left-sidenav li {
        border-bottom: 1px solid #e5e5e5;
    }

    form {
        margin: 0 0 20px;
    }

    fieldset {
        padding: 0;
        margin: 0;
        border: 0;
    }

    section {
        padding-top: 0;
    }

    article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
        display: block;
    }

    .past-posts .posts-holder {
        padding: 0 0 10px 4px;
        margin-right: 10px;
    }

    .past-info {
        background: #fff;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        padding: 0 0 8px 10px;
        margin-left: 2px;
    }

    .title-icon {
        margin-right: 8px;
        vertical-align: text-bottom;
    }

    article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
        display: block;
    }

    .system-settings-item {
        margin-top: 20px;
    }

    .fa-chevron-right {
        color: rgba(149, 149, 149, 0.75);
        margin-top: 4px;
    }

    .system-settings-item {
        margin-top: 20px;
    }

    .system-settings-item img {
        vertical-align: middle;
        padding-right: 5px;
        margin: 2px;
    }

    .system-settings-item a {
        padding: 10px;
        text-decoration: none;
        font-weight: bold;
    }

    .past-info #toolbar, .past-info .toolbar {
        background: #f8f8f8;
        font-size: 13px;
        font-weight: bold;
        color: #000;
        border-radius: 3px 3px 0 0;
        -webkit-border-radius: 3px 3px 0 0;
        border: #dcdcdc solid 1px;
        padding: 5px 15px 12px 10px;
        line-height: 2;
        height: 29px;
    }

    .system-settings-item .fa {
        text-decoration: none;
        color: black;
        font-size: 16px;
        padding-right: 5px;
    }

    .system-settings-item .fa {
        text-decoration: none;
        color: black;
        font-size: 16px;
        padding-right: 5px;
    }

    .width100p {
        width: 100%;
    }

    .user-table {
        width: 100%;
    }

    .bottom10 {
        margin-bottom: 10px !important;
    }

    .btn-toolbar {
        margin-top: -2px;
    }

    table {
        max-width: 100%;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
    }

</style>
<link rel="stylesheet" type="text/css"
      href="<?php echo base_url('plugins/bootstrapcolorpicker/dist/css/bootstrap-colorpicker.css'); ?>">
<script src="<?php echo base_url('plugins/bootstrapcolorpicker/dist/js/bootstrap-colorpicker.js'); ?>"></script>
<div id="filter-panel" class="collapse filter-panel">
</div>
<div class="row">
    <div class="col-md-5">
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <!-- <button type="button" class="btn btn-primary pull-right"
                 onclick="fetchPage('system/crm/create_contact',null,'Add New Contact','CRM');"><i
                 class="fa fa-plus"></i> New Contact
         </button>-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="list-main" class="top15 ">
            <aside class="sidebar-left col-md-3 " style="width: 21%;">
                <ul id="list" class="nav nav-list left-sidenav">
                    <li class="documentStatus"><a onclick="configuration_page('documentStatus')" href="#"> <i
                                    class="fa fa-chevron-right pull-right"></i>Document Status</a></li>
                </ul>
            </aside>

            <div id="load_configuration_view" class="col-md-9" style="width: 79%;">
                <form action="#" class="form-box">
                    <fieldset>
                        <section class="past-posts">
                            <div class="posts-holder">
                                <div class="past-info">
                                    <div id="toolbar">
                                        <div class="toolbar-title"><i class="fa fa-cog" aria-hidden="true"></i> System
                                            Settings
                                        </div>
                                    </div>
                                    <div class="post-area">
                                        <article class="page-content">
                                            <div class="system-settings">

                                            </div>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    configuration_page('documentStatus');
    function configuration_page(sys, masterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {sys: sys, masterID: masterID},
            url: "<?php echo site_url('MFQ_SystemSettings/settings_users'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#load_configuration_view').html(data);
                $('#list-main li').removeClass('active');
                $('.' + sys).addClass('active');
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

</script>