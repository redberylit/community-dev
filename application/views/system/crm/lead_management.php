<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('crm_leads');
echo head_page($title, false);
/*echo head_page('Leads', false);*/
$this->load->helper('crm_helper');
$isgroupadmin = crm_isGroupAdmin();
$admin = crm_isSuperAdmin();
$cuurentuser = current_userID();
$this->load->helper('crm_helper');
$date_format_policy = date_format_policy();
$status_arr_filter = lead_status();
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
                onclick="fetchPage('system/crm/create_lead',null,'<?php echo $this->lang->line('crm_add_new_lead');?>','CRM');"><i
                class="fa fa-plus"></i> <?php echo $this->lang->line('crm_new_lead');?>
        </button><!--Add New Lead--><!--New Lead-->
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
                <div class="col-sm-4">
                    <div class="box-tools">
                        <div class="has-feedback">
                            <input name="searchTask" type="text" class="form-control input-sm"
                                   placeholder="<?php echo $this->lang->line('crm_search_leads');?>"
                                   id="searchTask" onkeypress="startMasterSearch()"><!--Search Leads-->
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php echo form_dropdown('statusID', $status_arr_filter, '', 'class="form-control" id="filter_statusID"  onchange="startMasterSearch()"'); ?>
                </div>
                <div class="col-sm-1 hide" id="search_cancel">
                    <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clearSearchFilter()"><img
                                src="<?php echo base_url("images/crm/cancel-search.gif") ?>"></a></span>
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-sm-11">
                    <div id="LeadMaster_view"></div>
                </div>
                <div class="col-sm-1">
                    <ul class="alpha-box">
                        <li><a href="#" class="leadsorting" id="sorting_1" onclick="load_lead_filter('#',1)">#</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_2" onclick="load_lead_filter('A',2)">A</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_3" onclick="load_lead_filter('B',3)">B</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_4" onclick="load_lead_filter('C',4)">C</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_5" onclick="load_lead_filter('D',5)">D</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_6" onclick="load_lead_filter('E',6)">E</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_7" onclick="load_lead_filter('F',7)">F</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_8" onclick="load_lead_filter('G',8)">G</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_9" onclick="load_lead_filter('H',9)">H</a></li>
                        <li><a href="#" class="leadsorting" id="sorting_10" onclick="load_lead_filter('I',10)">I</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_11" onclick="load_lead_filter('J',11)">J</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_12" onclick="load_lead_filter('K',12)">K</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_13" onclick="load_lead_filter('L',13)">L</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_14" onclick="load_lead_filter('M',14)">M</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_15" onclick="load_lead_filter('N',15)">N</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_16" onclick="load_lead_filter('O',16)">O</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_17" onclick="load_lead_filter('P',17)">P</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_18" onclick="load_lead_filter('Q',18)">Q</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_19" onclick="load_lead_filter('R',19)">R</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_20" onclick="load_lead_filter('S',20)">S</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_21" onclick="load_lead_filter('T',21)">T</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_22" onclick="load_lead_filter('U',22)">U</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_23" onclick="load_lead_filter('V',23)">V</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_24" onclick="load_lead_filter('W',24)">W</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_25" onclick="load_lead_filter('X',25)">X</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_26" onclick="load_lead_filter('Y',26)">Y</a>
                        </li>
                        <li><a href="#" class="leadsorting" id="sorting_27" onclick="load_lead_filter('Z',27)">Z</a>
                        </li>
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
            fetchPage('system/crm/lead_management', '', 'Lead');
        });
        load_lead_filter('#', 1);
        //getLeadManagement_tableView();

    });

    $('#searchTask').bind('input', function(){
        startMasterSearch();
    });

    function getLeadManagement_tableView(filtervalue) {
        var searchTask = $('#searchTask').val();
        var status = $('#filter_statusID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'searchTask': searchTask, 'filtervalue': filtervalue,status:status},
            url: "<?php echo site_url('CrmLead/load_leadManagement_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#LeadMaster_view').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function delete_lead(id) {
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
                    data: {'leadID': id},
                    url: "<?php echo site_url('CrmLead/delete_lead_master'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0],data[1]);
                        if(data[0]=='s')
                        {
                            getLeadManagement_tableView();
                        }

                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function startMasterSearch() {
        $('#search_cancel').removeClass('hide');
        getLeadManagement_tableView();
    }

    function clearSearchFilter() {
        $('#search_cancel').addClass('hide');
        $('.leadsorting').removeClass('selected');
        $('#filter_statusID').val('');
        $('#searchTask').val('');
        $('#sorting_1').addClass('selected');
        getLeadManagement_tableView();
    }

    function load_lead_filter(value, id) {
        $('.leadsorting').removeClass('selected');
        $('#sorting_' + id).addClass('selected');
        $('#search_cancel').removeClass('hide');
        getLeadManagement_tableView(value)
    }

  function edit_lead(leadID,createdUserIDtask,responsiblePersonEmpID)
    {
        if((createdUserIDtask == '<?php echo $cuurentuser ?>') || ('<?php echo $admin['isSuperAdmin']?>' == 1) || ('<?php echo $isgroupadmin['adminYN']?>' == 1) || (responsiblePersonEmpID ==  '<?php echo $cuurentuser ?>'))
        {
            fetchPage('system/crm/create_lead',leadID,'<?php echo $this->lang->line('crm_edit_lead');?>','CRM')
        }else
        {
            myAlert('w','You do not have the permission to edit');
        }


    }

</script>