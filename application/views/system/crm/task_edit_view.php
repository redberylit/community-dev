<?php echo head_page($_POST['page_name'], false);
$this->load->helper('crm_helper');
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/build.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>"/>
<div id="filter-panel" class="collapse filter-panel"></div>
<div id="taskMaster_editView"></div>
<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<script type="text/javascript">
    $(document).ready(function () {
        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            taskID = p_id;
            getTaskManagement_editView(taskID);
        }

        pageRedirection = '<?php if((isset($_POST['data_arr'])) && !empty($_POST['data_arr'])){ echo $_POST['data_arr']; } ?>';

        masterID = '<?php if((isset($_POST['policy_id'])) && !empty($_POST['policy_id'])){ echo $_POST['policy_id']; } ?>';

        if (pageRedirection == 'Dashboard') {
            $('.headerclose').click(function () {
                if(masterID=='CRMTSK'){
                    fetchPage('system/crm/reports_management','','CRMTSK');
                }else{
                    fetchPage('system/crm/dashboard', '', 'Dashboard');
                }

            });
        } else if (pageRedirection == 'ProjectsTask') {
            $('.headerclose').click(function () {
                    fetchPage('system/crm/project_edit_view', masterID, 'View Project');
            });
        }else if (pageRedirection == 'contactTask')
        {
            $('.headerclose').click(function () {
                fetchPage('system/crm/contact_edit_view', masterID,'View Contact');
            });
        }
            else if(pageRedirection == 'opportunityTaks')
        {
            $('.headerclose').click(function () {
                fetchPage('system/crm/opportunities_edit_view', masterID, 'View Opportunity');
            });
        }

        else if (pageRedirection == 'opportunitie') {
            $('.headerclose').click(function () {
                fetchPage('system/crm/opportunities_edit_view', masterID, 'View Opportunity');
            });
        }
        else if (pageRedirection == 'Lead') {
            $('.headerclose').click(function () {
                if(masterID=='CRMTSK'){
                    fetchPage('system/crm/reports_management','','CRMTSK');
                }else{
                    fetchPage('system/crm/lead_edit_view', masterID, 'View Lead');
                }
            });
        }
       /* else if (pageRedirection == 'LeadTask') {
            $('.headerclose').click(function () {
                fetchPage('system/crm/lead_edit_view', masterID, 'View Lead');
            });
        }*/
        else if (pageRedirection == 'organization') {
            $('.headerclose').click(function () {
                if(masterID=='CRMTSK'){
                    fetchPage('system/crm/reports_management','','CRMTSK');
                }else{
                    fetchPage('system/crm/organization_edit_view', masterID, 'View Organization');
                }
            });
        }
        else {
            $('.headerclose').click(function () {
                if(masterID=='CRMTSK'){
                    fetchPage('system/crm/reports_management','','CRMTSK');
                }else{
                    fetchPage('system/crm/task_management', '', 'Tasks');
                }
            });
        }

    });

    function getTaskManagement_editView(taskID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {taskID: taskID},
            url: "<?php echo site_url('crm/load_taskManagement_editView'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#taskMaster_editView').html(data);

                if(masterID=='CRM'){
                    $('.projecteditbtn').removeClass('hidden');
                }else{
                    $('.projecteditbtn').addClass('hidden');
                }
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function task_edit_view_close() {

        fetchPage('system/crm/task_management', '', 'Tasks');

    }
</script>