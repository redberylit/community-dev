<?php

$this->load->helper('community_ngo_helper');
$companies_drop = load_allComCompanies();

$companyID = $this->common_data['company_data']['company_id'];
?>
<script src="<?php echo base_url('plugins/daterangepicker/daterangepicker.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/daterangepicker/daterangepicker-bs3.css'); ?>">

<input type="hidden" id="publicityID" name="publicityID" value="1">

<div class="no_padding col-md-12" style="padding: 2px;">
    <div class="col-md-12 col-sm-12 col-xs-12" style="padding:10px;">

        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">

            <div class="" role="group" aria-label="...">
                <form id="form_ofBloodDonors" name="form_ofBloodDonors">
                    <div class="row" style="margin-top: 10px;">
                        <div class="form-group col-sm-5">
                            <div class="box-tools">
                                <div class="has-feedback">
                                    <div class="input-group">
                                        <input name="searchTask" type="text" class="form-control input-sm" placeholder="Search..." id="searchTask" title="Search by NIC or Contact NO or Contact Address or Name">
                                        <span class="input-group-addon" data-toggle="tooltip" title="Search" onclick="start_publicSearch();"><i class="fa fa-search" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 hide" id="search_cancel">
                            <span class="tipped-top"><a id="cancelSearch" href="#" onclick="clear_publicSearch()"><img src="<?php echo base_url("images/community/cancel-search.gif") ?>"></a></span>
                        </div>
                        <div class="form-group col-sm-2">
                        </div>
                        <div class="form-group col-sm-3">
                            <label style="font-size: 11px;text-decoration-style: solid;font-weight:normal;float:right;">(Note : Search by NIC or Contact NO or Contact Address or Name)</label>

                        </div>
                    </div>
                </form>
            </div>
            <div class="well" id="searchPublicDiv" style="height: 100%;background-color: transparent;">

            </div>

        </div>
    </div>

</div>

<script type="text/javascript">
    function start_publicSearch() {
        $('#search_cancel').removeClass('hide');
        get_search_publicDel();
    }

    function clear_publicSearch() {
        $('#search_cancel').addClass('hide');
        $('#searchTask').val('');
        get_search_publicDel();
    }

    function get_search_publicDel() {

        var searchTask = $('#searchTask').val();

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                'searchTask': searchTask
            },
            url: "<?php echo site_url('CommunityJammiyaDashboard/load_people_searchPersonDel'); ?>",
            beforeSend: function() {
                startLoad();
            },
            success: function(data) {

                $('#searchPublicDiv').html(data);
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });

    }
</script>

<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 5/6/2019
 * Time: 12:10 PM
 */
