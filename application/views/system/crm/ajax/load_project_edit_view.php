<style>
    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .entity-detail .ralign, .property-table .ralign {
        text-align: right;
        color: gray;
        padding: 3px 10px 4px 0;
        width: 150px;
        max-width: 200px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .title {
        color: #aaa;
        padding: 4px 10px 0 0;
        font-size: 13px;
    }

    .tddata {
        color: #333;
        padding: 4px 10px 0 0;
        font-size: 13px;
    }

    .nav-tabs > li > a {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        position: relative;
        padding: 0 25px;
        float: left;
        display: block;
        /*color: rgb(44, 83, 158);*/
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
    }

    .nav-tabs > li > a:hover {
        background: rgb(230, 231, 234);
        font-size: 12px;
        line-height: 30px;
        height: 30px;
        position: relative;
        padding: 0 25px;
        float: left;
        display: block;
        /*color: rgb(44, 83, 158);*/
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        border-radius: 3px 3px 0 0;
        border-color: transparent;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: #c0392b;
        cursor: default;
        background-color: #fff;
        font-weight: bold;
        border-bottom: 3px solid #f15727;
    }

    .arrow-steps .step.current {
        color: #fff !important;
        background-color: #657e5f !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/pipeline.css'); ?>">
<?php
if (!empty($header)) {

?>
<div class="row">
    <div class="col-md-5">
        &nbsp;
    </div>
    <div class="col-md-4 text-center">

    </div>
    <div class="col-md-3 text-right">
        <?php
        if($header['clsd']!=1){
            ?>
            <button type="button"  class="btn btn-primary pull-right projecteditbtn"
                    onclick="check_edit_approval()">
                <span title="" rel="tooltip" class="glyphicon glyphicon-pencil" data-original-title="Edit"></span>
                Edit
            </button>
        <?php
        }else{
            ?>
            <button type="button"  class="btn btn-primary pull-right projecteditbtn"
                    onclick="closed_project_warnig()">
                <span title="" rel="tooltip" class="glyphicon glyphicon-pencil" data-original-title="Edit"></span>
                Edit
            </button>
        <?php
        }
        ?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-5">
        &nbsp;
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
    </div>
</div>
<ul class="nav nav-tabs" id="main-tabs">
    <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-television"></i>About</a></li>
    <li><a href="#emails" data-toggle="tab"><i class="fa fa-television"></i>Emails </a></li>
    <li><a href="#notes" onclick="project_notes()" data-toggle="tab"><i class="fa fa-television"></i>Notes </a>
    </li>
    <li><a href="#files" onclick="project_attachments()" data-toggle="tab"><i class="fa fa-television"></i>Files
        </a></li>
    <li><a href="#tasks" onclick="project_tasks()" data-toggle="tab"><i class="fa fa-television"></i>Tasks </a></li>
<!--    <li><a href="#salesTarget" onclick="project_salesTarget()" data-toggle="tab"><i class="fa fa-television"></i>Sales
            Target </a></li>-->
</ul>
<input type="hidden" id="editprojectID" value="<?php echo $header['projectID'] ?>">
<div class="tab-content">
    <div class="tab-pane active" id="about">
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>PROJECT DETAILS</h2>
                </header>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <table class="property-table">
                    <tbody>
                    <tr>
                        <td class="ralign"><span class="title">Customer</span></td>
                        <td><?php if (!empty($header['organizationName'])) { ?>
                                <strong>
                                    <div class="link-box"><strong class="contacttitle"><a class="link-person noselect"
                                                                                          href="#"
                                                                                          onclick="fetchPage('system/crm/organization_edit_view','<?php echo $header['relatedDocumentMasterID'] ?>','View Organization','<?php echo $header['projectID'] ?>','Project')"><?php echo $header['organizationName'] ?></a></strong>
                                    </div>
                                </strong>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Project Name</span></td>
                        <td><span class="tddata">
                                <?php echo $header['projectName'] ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Status</span></td>
                        <td><span class="label"
                                  style="background-color: <?php echo $header['statusBackgroundColor'] ?>; color:<?php echo $header['statusTextColor'] ?>; font-size: 11px;"><?php echo $header['statusDescription'] ?></span>
                            <?php
                            if ($header['closeStatus'] == 0) { ?>
                                <a class="nopjax" href="#" onclick="change_status()">&nbsp; &nbsp; Change</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <!--                    <tr>
                        <td class="ralign"><span class="title">Reason</span></td>
                        <td><span class="tddata"><?php /*echo $header['reason']; */ ?></span>
                        </td>
                    </tr>-->
                    <tr>
                        <td class="ralign"><span class="title">Category</span></td>
                        <td><span class="tddata"><?php echo $header['categoryDescription']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Value</span></td>
                        <td><span
                                class="tddata"><?php echo $header['CurrencyCode'] . " " . number_format($header['transactionAmount'], 2) ?></span>
                        </td>
                    </tr>
                    <!--                    <tr>
                        <td class="ralign"><span class="title">Probability Of Winning</span></td>
                        <td><span class="tddata"><?php /*echo $header['probabilityofwinning']; */ ?> %</span>
                        </td>
                    </tr>-->
                    <tr>
                        <td class="ralign"><span class="title">Project Start Date</span></td>
                        <td><span class="tddata"><?php echo $header['projectStartDate']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Project End Date</span></td>
                        <td><span class="tddata"><?php echo $header['projectEndDate']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">User Responsible</span></td>
                        <td><span class="tddata"><?php echo $header['responsiblePerson']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Converted from Opportunity</span></td>
                        <td><?php if (!empty($header['opportunityID'])) { ?>
                                <strong>
                                    <div class="link-box"><strong class="contacttitle"><a class="link-person noselect"
                                                                                          href="#"
                                                                                          onclick="fetchPage('system/crm/opportunities_edit_view','<?php echo $header['opportunityID'] ?>','View Opportunity','<?php echo $header['projectID'] ?>','Project')"><?php echo $header['projectName'] ?></a></strong>
                                    </div>
                                </strong>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Description</span></td>
                        <td><span class="tddata"><?php echo $header['opportunityDescription']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Is Closed</span></td>
                        <td><span class="tddata"><?php if($header['clsd']==1){echo "Yes";}else{echo "No";} ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ralign"><span class="title">Closed Date</span></td>
                        <td><span class="tddata"><?php echo $header['clsdDate'] ?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
           <!-- <div class="col-sm-3">
                <div class="fileinput-new thumbnail">
                    <img src="<?php /*echo base_url('images/item/no-image.png'); */?>" id="changeImg"
                         style="width: 200px; height: 145px;">
                </div>
            </div>-->
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>PIPELINE</h2>
                </header>
                <div class="row">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="col-sm-10">
                        <ul class="nav nav-tabs" id="pipelineTabs">
                            <div class="arrow-steps clearfix">
                                <?php
                                if (!empty($header['pipelineID'])) {
                                    $pipeline = $this->db->query("SELECT * FROM srp_erp_crm_pipelinedetails WHERE pipeLineID={$header['pipelineID']}")->result_array();
                                    if (!empty($pipeline)) {
                                        $count = count($pipeline);
                                        $percentage = 100 / $count;

                                        foreach ($pipeline as $pipe) {
                                            $active = 'not-current';
                                            $fontcolor = 'color: #666 !important;';
                                            if ($pipe['pipeLineDetailID'] == $header['pipelineStageID']) {
                                                $active = "current";
                                                $fontcolor = 'color: #fff !important;';
                                            } ?>

                                            <div class="step <?php echo $active ?>" style="margin-top:3px !important; ">
                                                <li><a href="#stageID_<?php echo $pipe['pipeLineDetailID'] ?>"
                                                       data-toggle="tab"
                                                       onclick="checkCurrentTab(<?php echo $header['projectID'] ?>,<?php echo $pipe['pipeLineDetailID'] ?>)"
                                                    <span
                                                        style="font-size: 14px !important;text-align: center !important;cursor: default !important; <?php echo $fontcolor; ?>"><?php echo $pipe['stageName'] ?></span>
                                                    </a>
                                                </li>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </ul>
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <?php
                    if (!empty($pipeline)) {
                        foreach ($pipeline as $pipe) {
                            $active = 'not-current';
                            if ($pipe['pipeLineDetailID'] == $header['pipelineStageID']) {
                                $active = "current";
                            } ?>
                            <div class="tab-pane tapPipeLine" id="stageID_<?php echo $pipe['pipeLineDetailID'] ?>">
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            style="font-weight: 500;font-size: 16px;color: slategrey;"><?php echo $pipe['stageName'] ?></div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" class="btn btn-primary pull-right"
                                                onclick="fetchPage('system/crm/create_new_task','','Create Task',9,[<?php echo $header['projectID'] ?>,<?php echo $pipe['pipeLineDetailID'] ?>]);">
                                            <i class="fa fa-plus"></i> Task
                                        </button>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-sm-1">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="piplineview"
                                             id="taskMaster_view_<?php echo $pipe['pipeLineDetailID'] ?>"></div>
                                    </div>
                                    <div class="col-sm-1">
                                        &nbsp;
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>RELATED TO</h2>
                </header>
                <div class="row">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="col-sm-10">
                        <div class="form-group col-sm-3">
                            <span class="input-req" title="Required Field">
                            <?php echo form_dropdown('relatedTo[]', array('' => 'Select Type', '6' => 'Contact', '8' => 'Organizations'), '', 'class="form-control relatedTo" id="relatedTo_1" onchange="relatedChange(this)"'); ?>
                                 </span>
                        </div>

                        <div class="form-group col-sm-3" style="padding-left: 0px;">
                             <span class="input-req" title="Required Field">
                            <input type="text" class="form-control f_search" name="related_search[]" id="f_search_1"
                                   placeholder="Contact, Organization..."
                                </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>PERMISSIONS</h2>
                </header>
                <div class="row">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Visibility </label><!--Visibility-->
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="iradio_square-blue">
                            <div class="skin-section extraColumns"><input id="isPermissionEveryone" type="radio"
                                                                          data-caption="" class="columnSelected"
                                                                          name="userPermission"
                                                                          value="1"><label for="checkbox">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-2" style="margin-left: -6%;">
                        <label style="font-weight: 400">Everyone </label><!--Everyone-->
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"></label>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="iradio_square-blue">
                            <div class="skin-section extraColumns"><input name="userPermission" id="isPermissionCreator"
                                                                          type="radio" data-caption=""
                                                                          class="columnSelected" value="2"><label
                                    for="checkbox">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-2" style="margin-left: -6%;">
                        <label style="font-weight: 400"> Only For Me</label><!--Only For Me-->
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"></label>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="iradio_square-blue">
                            <div class="skin-section extraColumns"><input name="userPermission" id="isPermissionGroup"
                                                                          type="radio"
                                                                          data-caption="" class="columnSelected"
                                                                          onclick="leadPermission(3)"
                                                                          value="3"><label for="checkbox">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-2" style="margin-left: -6%;">
                        <label style="font-weight: 400"> Select a Group </label><!--Select a Group-->
                    </div>
                </div>
                <div class="row hide" id="show_groupPermission">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"></label>
                    </div>
                    <div class="form-group col-sm-4" style="margin-left: 2%;">
                        <?php echo form_dropdown('groupID', $groupmaster_arr, '', 'class="form-control" id="groupID"'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"></label>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="iradio_square-blue">
                            <div class="skin-section extraColumns"><input name="userPermission"
                                                                          id="isPermissionMultiple"
                                                                          type="radio"
                                                                          data-caption="" class="columnSelected"
                                                                          onclick="leadPermission(4)"
                                                                          value="4"><label for="checkbox">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-2" style="margin-left: -6%;">
                        <label style="font-weight: 400"> Select Multiple People</label><!--Select Multiple People-->
                    </div>
                </div>
                <div class="row hide" id="show_multiplePermission">
                    <div class="form-group col-sm-1">
                        &nbsp
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title"></label>
                    </div>
                    <div class="form-group col-sm-4" style="margin-left: 2%;">
                        <?php echo form_dropdown('employees[]', $employees_multiple_arr, '', 'class="form-control select2" id="employeesID"  multiple="" style="z-index: 0;"'); ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>RECORD DETAILS</h2>
                </header>
            </div>
        </div>
        <table class="property-table">
            <tbody>
            <tr>
                <td class="ralign"><span class="title">Created Date</span></td>
                <td><span class="tddata"><?php echo $header['createdDate'] ?></span></td>
            </tr>
            <tr>
                <td class="ralign"><span class="title">Last Updated</span></td>
                <td><span class="tddata"><?php echo $header['modifydate'] ?></span></td>
            </tr>
            <tr>
                <td class="ralign"><span class="title">Project Created By</span></td>
                <td><span class="tddata"><?php echo $header['createdUserName'] ?></span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="emails">
        <br>

        <div class="row">
            <div class="col-sm-12">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">Project Emails</div>
                    </div>
                    <div class="post-area">
                        <article class="post">
                            <header class="infoarea">
                                <strong class="attachemnt_title">
                                <span
                                    style="text-align: center;font-size: 15px;font-weight: 800;">Email Not Configured </span>
                                </strong>
                            </header>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="notes">
        <br>

        <div class="row" id="show_add_notes_button">
            <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Project Notes </h4></div>
            <div class="col-md-4">
                <button type="button" onclick="show_add_note()" class="btn btn-primary pull-right projecteditbtn"><i
                        class="fa fa-plus"></i> Add Note
                </button>
            </div>
        </div>
        <br>
        <?php echo form_open('', 'role="form" id="frm_opportunity_add_notes"'); ?>
        <input type="hidden" name="projectID" value="<?php echo $header['projectID']; ?>">
        <input type="hidden" name="paath" value="project">

        <div id="show_add_notes" class="hide">
            <div class="row">
                <div class="form-group col-sm-8">
                                <span class="input-req" title="Required Field"><textarea class="form-control" rows="5"
                                                                                         name="description"
                                                                                         id="description"></textarea><span
                                        class="input-req-inner" style="top: 25px;"></span></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <button class="btn btn-primary" type="submit">Add</button>
                    <button class="btn btn-danger" type="button" onclick="close_add_note()">Close</button>
                </div>
                <div class="form-group col-sm-6" style="margin-top: 10px;">
                    &nbsp
                </div>
            </div>
        </div>
        </form>
        <div id="show_all_notes"></div>
    </div>
    <div class="tab-pane" id="files">
        <br>

        <div class="row" id="show_add_files_button">
            <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Project Files </h4></div>
            <div class="col-md-4">

                <button type="button" onclick="show_add_file()" class="btn btn-primary pull-right projecteditbtn"><i
                        class="fa fa-plus"></i> Add Files
                </button>
            </div>
        </div>
        <div class="row hide" id="add_attachemnt_show">
            <?php echo form_open_multipart('', 'id="opportunity_attachment_uplode_form" class="form-inline"'); ?>
            <div class="col-sm-10" style="margin-left: 3%">
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" id="opportunityattachmentDescription"
                               name="attachmentDescription" placeholder="Description..." style="width: 240%;">
                        <input type="hidden" class="form-control" id="documentID" name="documentID" value="9">
                        <input type="hidden" class="form-control" id="campaign_document_name" name="document_name"
                               value="Project">
                        <input type="hidden" class="form-control" id="opportunity_documentAutoID"
                               name="documentAutoID"
                               value="<?php echo $header['projectID']; ?>">
                    </div>
                </div>
                <div class="col-sm-8" style="margin-top: -8px;">
                    <div class="form-group">
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput"
                             style="margin-top: 8px;">
                            <div class="form-control" data-trigger="fileinput"><i
                                    class="glyphicon glyphicon-file color fileinput-exists"></i> <span
                                    class="fileinput-filename"></span></div>
                                  <span class="input-group-addon btn btn-default btn-file"><span
                                          class="fileinput-new"><span class="glyphicon glyphicon-plus"
                                                                      aria-hidden="true"></span></span><span
                                          class="fileinput-exists"><span class="glyphicon glyphicon-repeat"
                                                                         aria-hidden="true"></span></span><input
                                          type="file" name="document_file" id="document_file"></span>
                            <a class="input-group-addon btn btn-default fileinput-exists" id="remove_id"
                               data-dismiss="fileinput"><span class="glyphicon glyphicon-remove"
                                                              aria-hidden="true"></span></a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-default" onclick="document_uplode()"><span
                            class="glyphicon glyphicon-floppy-open color" aria-hidden="true"></span></button>
                    </form>
                </div>
            </div>

        </div>
        <br>

        <div id="show_all_attachments"></div>
    </div>
    <div class="tab-pane" id="tasks">
        <br>

        <div class="row">
            <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Project Tasks </h4></div>
            <div class="col-md-4">
                <button type="button"
                        onclick="fetchPage('system/crm/create_new_task','','Create Task',9, <?php echo $header['projectID']; ?>);"
                        class="btn btn-primary pull-right projecteditbtn"><i
                        class="fa fa-plus"></i> Add Task
                </button>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-12">
                <div id="show_all_tasks"></div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="salesTarget">
        <br>

        <div class="row">
            <div class="col-sm-12">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">Project Sales Target</div>
                    </div>
                    <div class="post-area">
                        <article class="post">
                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="show_all_salesTarget"></div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php
    }
    ?>
    <script type="text/javascript">
        var Otable;
        $(document).ready(function () {
            $('.extraColumns input').iCheck({
                checkboxClass: 'icheckbox_square_relative-blue',
                radioClass: 'iradio_square_relative-blue',
                increaseArea: '20%'
            });
            load_project_header();
            $("#description").wysihtml5();

            $('#frm_opportunity_add_notes').bootstrapValidator({
                live: 'enabled',
                message: 'This value is not valid.',
                excluded: [':disabled'],
                fields: {
                    //campaign_name: {validators: {notEmpty: {message: 'Campaign Name is required.'}}},
                },
            }).on('success.form.bv', function (e) {
                e.preventDefault();
                var $form = $(e.target);
                var bv = $form.data('bootstrapValidator');
                var data = $form.serializeArray();
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('CrmLead/add_project_notes'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1], data[2]);
                        if (data[0] == 's') {
                            close_add_note();
                            project_notes();
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

            $('#frm_opportunity_add_product').bootstrapValidator({
                live: 'enabled',
                message: 'This value is not valid.',
                excluded: [':disabled'],
                fields: {
                    productID: {validators: {notEmpty: {message: 'Product Name is required.'}}},
                    description: {validators: {notEmpty: {message: 'Description is required.'}}},
                    transactionCurrencyID: {validators: {notEmpty: {message: 'Transaction Currency is required.'}}},
                    price: {validators: {notEmpty: {message: 'Price is required.'}}}
                },
            }).on('success.form.bv', function (e) {
                e.preventDefault();
                var $form = $(e.target);
                var bv = $form.data('bootstrapValidator');
                var data = $form.serializeArray();
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('CrmLead/add_opportunity_product'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1], data[2]);
                        if (data[0] == 's') {
                            close_add_product();
                            opportunity_products();
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

        function project_notes() {
            var projectID = $('#editprojectID').val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {projectID: projectID},
                url: "<?php echo site_url('CrmLead/load_project_all_notes'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#show_all_notes').html(data);
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }
        function show_add_note() {
            $('#show_all_notes').addClass('hide');
            $('#show_add_notes_button').addClass('hide');
            $('#show_add_notes').removeClass('hide');
            $('#frm_opportunity_add_notes')[0].reset();
            $('#frm_opportunity_add_notes').bootstrapValidator('resetForm', true);
        }

        function close_add_note() {
            $('#show_add_notes').addClass('hide');
            $('#show_all_notes').removeClass('hide');
            $('#show_add_notes_button').removeClass('hide');
        }

        function document_uplode() {
            var formData = new FormData($("#opportunity_attachment_uplode_form")[0]);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "<?php echo site_url('crm/attachement_upload'); ?>",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data['type'], data['message'], 1000);
                    if (data['status']) {
                        $('#add_attachemnt_show').addClass('hide');
                        $('#remove_id').click();
                        $('#opportunityattachmentDescription').val('');
                        project_attachments();
                    }
                },
                error: function (data) {
                    stopLoad();
                    swal("Cancelled", "No File Selected :)", "error");
                }
            });
            return false;
        }

        function project_attachments() {
            var projectID = $('#editprojectID').val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {projectID: projectID},
                url: "<?php echo site_url('CrmLead/load_project_all_attachments'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#show_all_attachments').html(data);
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function show_add_file() {
            $('#add_attachemnt_show').removeClass('hide');
        }

        function delete_crm_attachment(id, fileName) {
            swal({
                    title: "Are you sure?",
                    text: "You want to Delete!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes!"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'attachmentID': id, 'myFileName': fileName},
                        url: "<?php echo site_url('crm/delete_crm_attachment'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data == true) {
                                myAlert('s', 'Deleted Successfully');
                                project_attachments();
                            } else {
                                myAlert('e', 'Deletion Failed');
                            }
                        },
                        error: function () {
                            stopLoad();
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });

        }

        function project_tasks() {
            var projectID = $('#editprojectID').val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {projectID: projectID},
                url: "<?php echo site_url('CrmLead/load_project_all_tasks'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#show_all_tasks').html(data);
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function project_salesTarget() {
            var projectID = $('#editprojectID').val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {projectID: projectID},
                url: "<?php echo site_url('crm/load_project_all_salesTarget'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#show_all_salesTarget').html(data);
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        $('#changeImg').click(function () {
            $('#itemImage').click();
        });

        function loadImage(obj) {
            if (obj.files && obj.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#changeImg').attr('src', e.target.result);
                };
                reader.readAsDataURL(obj.files[0]);
                profileImageUploadLead();
            }
        }

        function profileImageUploadLead() {
            var imgageVal = new FormData();
            imgageVal.append('projectID', $('#editprojectID').val());

            var files = $("#itemImage")[0].files[0];
            imgageVal.append('files', files);
            // var formData = new FormData($("#opportunity_profile_image_uplode_form")[0]);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                data: imgageVal,
                contentType: false,
                cache: false,
                processData: false,
                url: "<?php echo site_url('CrmLead/opportunity_image_upload'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {

                    }
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }

        function change_status() {
            $('#statusID').val('');
            $('#reason').val('');
            $('#statusModal').modal({backdrop: "static"});
        }

        function checkCurrentTab(opporunityID, pipeLineDetailID) {
            $('.tapPipeLine').removeClass('active');
            $('#stageID_' + pipeLineDetailID).addClass('active');
            getTaskManagement_tableView(opporunityID, pipeLineDetailID)
        }

        function getTaskManagement_tableView(opporunityID, pipeLineDetailID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {opporunityID: opporunityID, pipeLineDetailID: pipeLineDetailID,type:1},
                url: "<?php echo site_url('crm/load_taskManagement_project_view'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#taskMaster_view_' + pipeLineDetailID).html(data);
                    $(".taskHeading_tr").hide();
                    $(".taskaction_td").hide();
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function delete_note(notesID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to Delete!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes!"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {notesID: notesID},
                        url: "<?php echo site_url('crm/delete_master_notes_allDocuments'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data == true) {
                                myAlert('s', 'Note Deleted Successfully');
                                project_notes();
                            } else {
                                myAlert('e', 'Deletion Failed');
                            }
                        },
                        error: function () {
                            stopLoad();
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });

        }

        function load_project_header() {
            var projectID= <?php echo $header['projectID'] ?>;
            if (projectID) {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'projectID': projectID},
                    url: "<?php echo site_url('CrmLead/load_project_header'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        if (!jQuery.isEmptyObject(data['detail'])) {
                            var id = 1;
                            $.each(data['detail'], function (key, value) {
                                $('#relatedTo_' + id).val(value.relatedDocumentID);
                                $('#relatedAutoID_' + id).val(value.relatedDocumentMasterID);
                                $('#f_search_' + id).val(value.searchValue);
                                $('#linkedFromOrigin_' + id).val(value.originFrom);
                                if (value.originFrom == 1) {
                                    $("#relatedTo_" + id).prop("disabled", "disabled");
                                    $("#f_search_" + id).prop("disabled", "disabled");
                                    $("#linkmorerelation").addClass("hide");
                                } else {
                                    $("#linkmorerelation").removeClass("hide");
                                }
                                $("#relatedTo_" + id).prop("disabled", "disabled");
                                $("#f_search_" + id).prop("disabled", "disabled");
                                id++;
                            });
                        }
                        if (!jQuery.isEmptyObject(data['permission'])) {
                            var selectedItems = [];
                            $.each(data['permission'], function (key, value) {
                                if (value.permissionID == 1) {
                                    $('#isPermissionEveryone').iCheck('check');
                                    //$('#isPermissionEveryone').iCheck('disable');
                                    $('#isPermissionCreator').iCheck('disable');
                                    $('#isPermissionGroup').iCheck('disable');
                                    $('#isPermissionMultiple').iCheck('disable');
                                } else if (value.permissionID == 2) {
                                    $('#isPermissionCreator').iCheck('check');
                                    $('#isPermissionEveryone').iCheck('disable');
                                    //$('#isPermissionCreator').iCheck('disable');
                                    $('#isPermissionGroup').iCheck('disable');
                                    $('#isPermissionMultiple').iCheck('disable');
                                } else if (value.permissionID == 3) {
                                    $('#isPermissionGroup').iCheck('check');
                                    $('#isPermissionEveryone').iCheck('disable');
                                    $('#isPermissionCreator').iCheck('disable');
                                    //$('#isPermissionGroup').iCheck('disable');
                                    $('#isPermissionMultiple').iCheck('disable');
                                } else if (value.permissionID == 4) {
                                    $('#isPermissionMultiple').iCheck('check');
                                    $('#isPermissionEveryone').iCheck('disable');
                                    $('#isPermissionCreator').iCheck('disable');
                                    $('#isPermissionGroup').iCheck('disable');
                                    //$('#isPermissionMultiple').iCheck('disable');
                                }
                            });
                        }
                        stopLoad();
                        refreshNotifications(true);
                    }, error: function () {
                        alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                        stopLoad();
                        refreshNotifications(true);
                    }
                });
            }
        }

        function closed_project_warnig(){
            myAlert('w','You canot Edit this project. This Project has been closed')
        }
        function check_edit_approval(){
            if(<?php echo $this->common_data['current_userID']?>==<?php if(!empty($superadmn)){ echo $superadmn['isadmin'];}else{echo 000;}  ?>){
                fetchPage('system/crm/create_project',<?php echo $header['projectID'] ?>,'Edit Project','CRM');
            }else if(<?php echo $this->common_data['current_userID']?>==<?php echo $header['crtduser'] ?>){
                fetchPage('system/crm/create_project',<?php echo $header['projectID'] ?>,'Edit Project','CRM');
            }else if(<?php echo $this->common_data['current_userID']?>==<?php echo $header['responsibleEmpID'] ?>){
                fetchPage('system/crm/create_project',<?php echo $header['projectID'] ?>,'Edit Project','CRM');
            }else if(<?php echo $this->common_data['current_userID']?>==<?php if(!empty($isAdmin)){ echo $isAdmin['isadmin'];}else{echo 000;}  ?>){
                fetchPage('system/crm/create_project',<?php echo $header['projectID'] ?>,'Edit Project','CRM');
            }else{
                myAlert('w','You do not have permission to edit this project')
            }
        }

    </script>


