<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('crm_contacts');
echo head_page($title, false);

/*echo head_page('Contacts', false);*/
$supplier_arr = all_supplier_drop(false);
$date_format_policy = date_format_policy();
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
        border: 1px solid #89aedc99;
    }

    ul, ol {
        padding: 0;
        margin: 0 0 10px 25px;
    }

    .alpha-box li a {
        text-decoration: none;
        color: #555;
        padding: 4px 8px 4px 8px;
        border-bottom: 1px solid #89aedc99;
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
        padding: 1px 5px 0 6px;
        line-height: 14px;
        margin-left: 8px;
        margin-top: 9px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }
</style>
<div id="filter-panel" class="collapse filter-panel">
</div>
<div class="row">
    <div class="col-md-5">
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right"
                onclick="fetchPage('system/crm/create_contact',null,'<?php echo $this->lang->line('crm_add_new_contact');?>','CRM');"><i
                class="fa fa-plus"></i> <?php echo $this->lang->line('crm_new_contact');?>
        </button><!--Add New Contact--><!--New Contact-->
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
                    <span data-id="57698933" class="noselect follow unfollowing" title="Following" onclick="updatefavouritescontacts()"></span>
                </div>
                <div class="col-sm-4" style="margin-left: -4%;">
                    <div class="box-tools">
                        <div class="has-feedback">
                            <input name="searchTask" type="text" class="form-control input-sm"
                                   placeholder="Search Contacts"
                                   id="searchTask" >
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
                <div class="col-sm-11">
                    <div id="ContactMaster_view"></div>
                </div>
                <div class="col-sm-1">
                    <ul class="alpha-box">
                        <li><a href="#" class="contactsorting selected" id="sorting_1" onclick="load_contact_filter('#',1)">#</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_2" onclick="load_contact_filter('A',2)">A</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_3" onclick="load_contact_filter('B',3)">B</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_4" onclick="load_contact_filter('C',4)">C</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_5" onclick="load_contact_filter('D',5)">D</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_6" onclick="load_contact_filter('E',6)">E</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_7" onclick="load_contact_filter('F',7)">F</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_8" onclick="load_contact_filter('G',8)">G</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_9" onclick="load_contact_filter('H',9)">H</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_10" onclick="load_contact_filter('I',10)">I</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_11" onclick="load_contact_filter('J',11)">J</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_12" onclick="load_contact_filter('K',12)">K</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_13" onclick="load_contact_filter('L',13)">L</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_14" onclick="load_contact_filter('M',14)">M</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_15" onclick="load_contact_filter('N',15)">N</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_16" onclick="load_contact_filter('O',16)">O</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_17" onclick="load_contact_filter('P',17)">P</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_18" onclick="load_contact_filter('Q',18)">Q</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_19" onclick="load_contact_filter('R',19)">R</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_20" onclick="load_contact_filter('S',20)">S</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_21" onclick="load_contact_filter('T',21)">T</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_22" onclick="load_contact_filter('U',22)">U</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_23" onclick="load_contact_filter('V',23)">V</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_24" onclick="load_contact_filter('W',24)">W</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_25" onclick="load_contact_filter('X',25)">X</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_26" onclick="load_contact_filter('Y',26)">Y</a></li>
                        <li><a href="#" class="contactsorting" id="sorting_27" onclick="load_contact_filter('Z',27)">Z</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/crm/contact_management', '', 'Contact');
        });
        load_contact_filter('#', 1);
        //getContactManagement_tableView();

    });

    $('#searchTask').bind('input', function(){
        startMasterSearch();
    });

    function getContactManagement_tableView(filtervalue) {
        var searchTask = $('#searchTask').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'searchTask': searchTask,'filtervalue':filtervalue},
            url: "<?php echo site_url('crm/load_contactManagement_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#ContactMaster_view').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function delete_contact(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this record!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'contactID': id},
                    url: "<?php echo site_url('Crm/delete_contact_master'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        refreshNotifications(true);
                        stopLoad();
                        getContactManagement_tableView();

                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getContactManagement_tableView();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('.contactsorting').removeClass('selected');
        $('#searchTask').val('');
        $('#sorting_1').addClass('selected');
        getContactManagement_tableView();
    }

    function load_contact_filter(value, id){
        $('.contactsorting').removeClass('selected');
        $('#sorting_'+ id).addClass('selected');
        if(value != '#'){
            $('#search_cancel').removeClass('hide');
        }
        getContactManagement_tableView(value)
    }

    function updatefavouritescontacts(){

    }

</script>