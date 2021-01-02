
<div class="modal fade" id="com_members_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="comMembers_title"><?php echo $this->lang->line('communityngo_members'); ?></h4>
            </div>
            <form method="post" class="form-horizontal" id="com_members_form" name="com_members_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-1">
                                <ul class="zx-nav zx-nav-tabs zx-tabs-left zx-vertical-text">
                                    <li id="TabComMembers_view" class="active"><a href="#comMembersHome-m" data-toggle="tab"><?php echo $this->lang->line('common_view');?><!--View--></a></li>
                                    <li id="TabComMembersAttachment"><a href="#comMembers-m" data-toggle="tab"><?php echo $this->lang->line('common_attachment');?><!--Attachment--></a></li>
                                </ul>
                            </div>
                            <div class="col-sm-11" style="padding-left: 0px;margin-left: -2%;">
                                <div class="zx-tab-content">
                                    <div class="zx-tab-pane active" id="comMembersHome-m">
                                        <div id="load_comMembers_div" class="col-md-12"></div>
                                    </div>
                                    <div class="zx-tab-pane" id="comMembers-m">
                                        <div id="loadPageComMemberAttachment" class="col-md-8">
                                            <div class="table-responsive">
                                                <span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span>&nbsp; <strong><?php echo $this->lang->line('common_attachments');?><!--Attachments--></strong>
                                                <br><br>
                                                <table class="table table-striped table-condensed table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo $this->lang->line('common_file_name');?><!--File Name--></th>
                                                        <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
                                                        <th><?php echo $this->lang->line('common_type');?><!--Type--></th>
                                                        <th><?php echo $this->lang->line('common_action');?><!--Action--></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="mem_attachment_modal_body" class="no-padding">
                                                    <tr class="danger">
                                                        <td colspan="5" class="text-center"><?php echo $this->lang->line('common_no_attachment_found');?><!--No Attachment Found--></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="com_families_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="comFamilies_title"><?php echo $this->lang->line('CommunityNgo_com_families'); ?></h4>
            </div>
            <form method="post" class="form-horizontal" id="com_families_form" name="com_families_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-1">
                                <ul class="zx-nav zx-nav-tabs zx-tabs-left zx-vertical-text">
                                    <li id="TabcomFamilies_view" class="active"><a href="#comFamiliesHome-m" data-toggle="tab"><?php echo $this->lang->line('common_view');?><!--View--></a></li>
                                    <li id="TabcomFamiliesAttachment"><a href="#comFamilies-m" data-toggle="tab"><?php echo $this->lang->line('common_attachment');?><!--Attachment--></a></li>
                                </ul>
                            </div>
                            <div class="col-sm-11" style="padding-left: 0px;margin-left: -2%;">
                                <div class="zx-tab-content">
                                    <div class="zx-tab-pane active" id="comFamiliesHome-m">
                                        <div id="load_comFamilies_div" class="col-md-12"></div>
                                    </div>
                                    <div class="zx-tab-pane" id="comFamilies-m">
                                        <div id="loadPageComFamilyAttachment" class="col-md-8">
                                            <div class="table-responsive">
                                                <span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span>&nbsp; <strong><?php echo $this->lang->line('common_attachments');?><!--Attachments--></strong>
                                                <br><br>
                                                <table class="table table-striped table-condensed table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo $this->lang->line('common_file_name');?><!--File Name--></th>
                                                        <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
                                                        <th><?php echo $this->lang->line('common_type');?><!--Type--></th>
                                                        <th><?php echo $this->lang->line('common_action');?><!--Action--></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="fam_attachment_modal_body" class="no-padding">
                                                    <tr class="danger">
                                                        <td colspan="5" class="text-center"><?php echo $this->lang->line('common_no_attachment_found');?><!--No Attachment Found--></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="com_committees_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="comCommittees_title"><?php echo $this->lang->line('communityngo_Committees'); ?></h4>
            </div>
            <form method="post" class="form-horizontal" id="com_committees_form" name="com_committees_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-1">
                                <ul class="zx-nav zx-nav-tabs zx-tabs-left zx-vertical-text">
                                    <li id="TabcomCommittees_view" class="active"><a href="#comCommitteesHome-m" data-toggle="tab"><?php echo $this->lang->line('common_view');?><!--View--></a></li>
                                    <li id="TabcomCommitteesAttachment"><a href="#comCommittees-m" data-toggle="tab"><?php echo $this->lang->line('common_attachment');?><!--Attachment--></a></li>
                                </ul>
                            </div>
                            <div class="col-sm-11" style="padding-left: 0px;margin-left: -2%;">
                                <div class="zx-tab-content">
                                    <div class="zx-tab-pane active" id="comCommitteesHome-m">
                                        <div id="load_comCommittees_div" class="col-md-12"></div>
                                    </div>
                                    <div class="zx-tab-pane" id="comCommittees-m">
                                        <div id="loadPageComCommitteeAttachment" class="col-md-8">
                                            <div class="table-responsive">
                                                <span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span>&nbsp; <strong><?php echo $this->lang->line('common_attachments');?><!--Attachments--></strong>
                                                <br><br>
                                                <table class="table table-striped table-condensed table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo $this->lang->line('common_file_name');?><!--File Name--></th>
                                                        <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
                                                        <th><?php echo $this->lang->line('common_type');?><!--Type--></th>
                                                        <th><?php echo $this->lang->line('common_action');?><!--Action--></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="committee_attachment_modal_body" class="no-padding">
                                                    <tr class="danger">
                                                        <td colspan="5" class="text-center"><?php echo $this->lang->line('common_no_attachment_found');?><!--No Attachment Found--></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="com_FamMembrs_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="comFamMembers_title"><?php echo $this->lang->line('CommunityNgo_com_FamMembrs'); ?></h4>
            </div>
            <form method="post" class="form-horizontal" id="com_FamMembrs_form" name="com_FamMembrs_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-1">
                                <ul class="zx-nav zx-nav-tabs zx-tabs-left zx-vertical-text">
                                    <li id="TabcomFamMembers_view" class="active"><a href="#comFamMembersHome-m" data-toggle="tab"><?php echo $this->lang->line('common_view');?><!--View--></a></li>
                                    <li id="TabcomFamMembersAttachment"><a href="#comFamMembers-m" data-toggle="tab"><?php echo $this->lang->line('common_attachment');?><!--Attachment--></a></li>
                                </ul>
                            </div>
                            <div class="col-sm-11" style="padding-left: 0px;margin-left: -2%;">
                                <div class="zx-tab-content">
                                    <div class="zx-tab-pane active" id="comFamMembersHome-m">
                                        <div id="load_comFamMembers_div" class="col-md-12"></div>
                                    </div>
                                    <div class="zx-tab-pane" id="comFamMembers-m">
                                        <div id="loadPageComFamilyAttachment" class="col-md-8">
                                            <div class="table-responsive">
                                                <span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span>&nbsp; <strong><?php echo $this->lang->line('common_attachments');?><!--Attachments--></strong>
                                                <br><br>
                                                <table class="table table-striped table-condensed table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo $this->lang->line('common_file_name');?><!--File Name--></th>
                                                        <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
                                                        <th><?php echo $this->lang->line('common_type');?><!--Type--></th>
                                                        <th><?php echo $this->lang->line('common_action');?><!--Action--></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="famMem_attachment_modal_body" class="no-padding">
                                                    <tr class="danger">
                                                        <td colspan="5" class="text-center"><?php echo $this->lang->line('common_no_attachment_found');?><!--No Attachment Found--></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function popup_comMemberModel(areaMemId,gsDivitnId) {

        $("#comMembers-m").removeClass("active");
        $("#comMembersHome-m").addClass("active");
        $("#TabComMembersAttachment").removeClass("active");
        $("#TabComMembers_view").addClass("active");
        $('#load_comMembers_div').html('');
        var titleForMembers = '<?php echo $this->lang->line('communityngo_members'); ?>';
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {areaMemId:areaMemId,gsDivitnId:gsDivitnId},
            url: "<?php echo site_url('CommunityNgoDashboard/load_comMembers_del'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {

                $('#com_members_form')[0].reset();
                $('#com_members_form').bootstrapValidator('resetForm', true);

                $('#load_comMembers_div').html(data);
                $('#comMembers_title').html(titleForMembers);
                $('#com_members_modal').modal('show');

                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });

    }

    function popup_comFamiliesModel(areaMemId,gsDivitnId) {

        $("#comFamilies-m").removeClass("active");
        $("#comFamiliesHome-m").addClass("active");
        $("#TabcomFamiliesAttachment").removeClass("active");
        $("#TabcomFamilies_view").addClass("active");
        $('#load_comFamilies_div').html('');
        var titleForfamilies = '<?php echo $this->lang->line('CommunityNgo_com_families'); ?>';
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {areaMemId:areaMemId,gsDivitnId:gsDivitnId},
            url: "<?php echo site_url('CommunityNgoDashboard/load_comFamilies_del'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {

                $('#com_families_form')[0].reset();
                $('#com_families_form').bootstrapValidator('resetForm', true);

                $('#load_comFamilies_div').html(data);
                $('#comFamilies_title').html(titleForfamilies);
                $('#com_families_modal').modal('show');

                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });

    }

    function popup_comCommitteesModel(areaMemId,gsDivitnId) {

        $("#comCommittees-m").removeClass("active");
        $("#comCommitteesHome-m").addClass("active");
        $("#TabcomCommitteesAttachment").removeClass("active");
        $("#TabcomCommittees_view").addClass("active");
        $('#load_comCommittees_div').html('');
        var titleForcommittees = '<?php echo $this->lang->line('communityngo_Committees'); ?>';
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {areaMemId:areaMemId,gsDivitnId:gsDivitnId},
            url: "<?php echo site_url('CommunityNgoDashboard/load_comCommittees_del'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {

                $('#com_committees_form')[0].reset();
                $('#com_committees_form').bootstrapValidator('resetForm', true);

                $('#load_comCommittees_div').html(data);
                $('#comCommittees_title').html(titleForcommittees);
                $('#com_committees_modal').modal('show');

                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });

    }

    function popup_comFamilyModal(FamMasterID) {

        $("#comFamMembers-m").removeClass("active");
        $("#comFamMembersHome-m").addClass("active");
        $("#TabcomFamMembersAttachment").removeClass("active");
        $("#TabcomFamMembers_view").addClass("active");
        $('#load_comFamMembers_div').html('');
        var titleForFamMembrs = '<?php echo $this->lang->line('CommunityNgo_com_FamMembrs'); ?>';
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {FamMasterID:FamMasterID},
            url: "<?php echo site_url('CommunityNgoDashboard/load_comFamMembers_del'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {

                $('#com_FamMembrs_form')[0].reset();
                $('#com_FamMembrs_form').bootstrapValidator('resetForm', true);

                $('#load_comFamMembers_div').html(data);
                $('#comFamMembers_title').html(titleForFamMembrs);
                $('#com_FamMembrs_modal').modal('show');

                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
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
 * Date: 1/21/2019
 * Time: 9:28 AM
 */