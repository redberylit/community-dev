<?php echo head_page($_POST['page_name'], false);
$this->load->helper('crm_helper');
$status_arr = all_opportunities_status();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/pipeline.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">

<div id="filter-panel" class="collapse filter-panel"></div>
<div id="opportunityMaster_editView"></div>

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Opportunity State</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('', 'role="form" id="editopportunitystate_form"'); ?>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">Status</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <?php echo form_dropdown('statusID', $status_arr, '', 'class="form-control" id="statusID"  required'); ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">Reason</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <input type="text" name="reason" id="reason" class="form-control" placeholder="Reason">
                        <input type="hidden" class="form-control" name="opportunityID"
                               id="opportunityID">
                    </div>
                </div>
            </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="opportunity_state()">Update</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            opportunityID = p_id;
            getOpportunityManagement_editView(opportunityID);
        }
        pageRedirection = '<?php if((isset($_POST['data_arr'])) && !empty($_POST['data_arr'])){ echo $_POST['data_arr']; } ?>';
        masterID = '<?php if((isset($_POST['policy_id'])) && !empty($_POST['policy_id'])){ echo $_POST['policy_id']; } ?>';

        if (pageRedirection == 'Project') {
            $('.headerclose').click(function () {
                fetchPage('system/crm/project_edit_view', masterID, 'View Project');
            });
        } else if (pageRedirection == 'Contact') {
            $('.headerclose').click(function () {
                fetchPage('system/crm/contact_edit_view', masterID, 'View Contact');
            });
        } else {
            $('.headerclose').click(function () {
                fetchPage('system/crm/opportunities_management', '', 'Opportunities');
            });
        }

        /*        if (masterID != '') {
         $('.headerclose').click(function () {
         fetchPage('system/crm/organization_edit_view', masterID, 'View Organizations');
         });
         } */

    });

    function opportunity_state() {
        var statusID = $('#statusID').val();
        var reason = $('#reason').val();
        var opportunityID = p_id;
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {statusID: statusID, 'reason': reason, opportunityID: opportunityID},
            url: "<?php echo site_url('CrmLead/opportunity_update_status'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    $('#statusModal').modal('hide');
                    fetchPage('system/crm/opportunities_edit_view', p_id, 'View Opportunity', 'CRM');
                } else {
                    $('.btn-wizard').removeClass('disabled');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function getOpportunityManagement_editView(opportunityID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {opportunityID: opportunityID},
            url: "<?php echo site_url('CrmLead/load_opportunityManagement_editView'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#opportunityMaster_editView').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function opportunity_edit_view_close() {

        fetchPage('system/crm/opportunity_management', '', 'Opportunities');

    }

</script>