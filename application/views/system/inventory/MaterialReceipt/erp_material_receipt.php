<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('inventory', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
/*$title = $this->lang->line('transaction_add_new_material_receipt_note');
echo head_page($title, false);*/

echo head_page($_POST['page_name'], false);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$customer_arr = all_employee_drop();
$location_arr = all_delivery_location_drop();
$location_arr_default = default_delivery_location_drop();
$segment_arr = fetch_segment();
$financeyear_arr = all_financeyear_drop(true);
$unitOfMeasure_arr = array('' => 'Select UOM');
$projectExist = project_is_exist();
$financeyearperiodYN = getPolicyValues('FPC', 'All');
?>
<link rel="stylesheet" href="<?php echo base_url('plugins/css/autocomplete-suggestions.css'); ?>"/>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1"
       data-toggle="tab"><?php echo $this->lang->line('transaction_goods_received_voucher_step_one'); ?>
        - <?php echo $this->lang->line('transaction_mrn_header'); ?></a><!--Step 1--><!--MRN Header-->
    <a class="btn btn-default btn-wizard" href="#step2" onclick="fetch_material_receipt_detail()"
       data-toggle="tab"><?php echo $this->lang->line('transaction_goods_received_voucher_step_two'); ?>
        - <?php echo $this->lang->line('transaction_mrn_detail'); ?></a><!--Step 2--><!--MRN Detail-->
    <a class="btn btn-default btn-wizard" href="#step3" onclick="load_conformation()"
       data-toggle="tab"><?php echo $this->lang->line('transaction_goods_received_voucher_step_three'); ?>
        - <?php echo $this->lang->line('transaction_mrn_confirmation'); ?> </a><!--Step 3--><!--MRN Confirmation-->
</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="material_receipt_form"'); ?>
        <div class="row">
            <div class="form-group col-sm-4">
                <label
                    for="receiptType"><?php echo $this->lang->line('common_type'); ?><?php required_mark(); ?></label>
                <!--Type-->
                <?php echo form_dropdown('receiptType', array('' => $this->lang->line('common_select_type')/*'Select Type'*/, 'Direct Issue' => $this->lang->line('transaction_direct'), 'Material Request' => $this->lang->line('transaction_material_request') /*'Direct'*/), 'Direct Issue', 'class="form-control select2" id="receiptType" required'); ?>
            </div>
            <div class="form-group col-sm-4 requestedSegmentDiv">
                <label
                    for="segment"><?php echo $this->lang->line('transaction_primary_segment'); ?><?php required_mark(); ?></label>
                <!--Primary Segment-->
                <?php echo form_dropdown('segment', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="segment" required'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('transaction_common_referenc_no'); ?> </label>
                <!--Reference No-->
                <input type="text" class="form-control " id="issueRefNo" name="issueRefNo">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label
                    for="SalesPersonName"><?php echo $this->lang->line('transaction_common_received_by'); ?><?php required_mark(); ?></label>
                <!--Received By-->
                <div class="input-group">
                    <input type="text" class="form-control" id="employeeName" name="employeeName" required>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" title="Clear" rel="tooltip"
                                onclick="clearEmployee()"
                                style="height: 29px; padding: 2px 10px;"><i class="fa fa-repeat"></i></button>
                        <button class="btn btn-default" type="button" title="Add Employee" rel="tooltip"
                                onclick="link_employee_model()"
                                style="height: 29px; padding: 2px 10px;"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
            </div>
            <!-- <div class="form-group col-sm-4">
                <label for="employeeID">Requested By <?php /*required_mark(); */ ?></label>
                <?php /*echo form_dropdown('employeeID', $customer_arr, '', 'class="form-control select2" id="employeeID" required'); */ ?>
            </div>-->
            <div class="form-group col-sm-4">
                <label
                    for=""><?php echo $this->lang->line('transaction_date_received'); ?><?php required_mark(); ?></label>
                <!--Date Received-->

                <div class="input-group datepic">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="receivedDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                           value="<?php echo $current_date; ?>" id="receivedDate"
                           class="form-control" required>
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label
                    for=""><?php echo $this->lang->line('transaction_common_warehouse_location'); ?><?php required_mark(); ?></label>
                <!--Warehouse Location-->
                <?php echo form_dropdown('location', $location_arr, $location_arr_default, 'class="form-control select2" id="location" required'); ?>
            </div>
        </div>
        <div class="row">
            <?php
            if($financeyearperiodYN==1){
            ?>
            <div class="form-group col-sm-4">
                <label
                    for="financeyear"><?php echo $this->lang->line('transaction_common_financial_year'); ?><?php required_mark(); ?></label>
                <!--Financial Year-->
                <?php echo form_dropdown('financeyear', $financeyear_arr, $this->common_data['company_data']['companyFinanceYearID'], 'class="form-control" id="financeyear" required onchange="fetch_finance_year_period(this.value)"'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label
                    for="financeyear"><?php echo $this->lang->line('transaction_common_financial_period'); ?><?php required_mark(); //?></label>
                <!--Financial Period-->
                <?php echo form_dropdown('financeyear_period', array('' => 'Select Financial Period'), '', 'class="form-control" id="financeyear_period" required'); ?>
            </div>
                <?php
            }
            ?>
            <div class="form-group col-sm-4">
                <label
                    for="itemType"><?php echo $this->lang->line('transaction_item_type'); ?><?php required_mark(); ?></label>
                <!--Item Type-->
                <?php echo form_dropdown('itemType', array('' => $this->lang->line('common_select_type')/*'Select Type'*/, 'Inventory' => $this->lang->line('transaction_inventory')/*'Inventory'*/, 'Non Inventory' => $this->lang->line('transaction_non_inventory')/*'Non Inventory'*/), 'Inventory', 'class="form-control select2" id="itemType" required'); ?>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <label for=""><?php echo $this->lang->line('transaction_common_narration'); ?> </label><!--Narration-->
                <textarea class="form-control" rows="3" id="narration" name="narration"></textarea>
            </div>
        </div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-primary"
                    type="submit"><?php echo $this->lang->line('common_save_and_next'); ?>  </button><!--Save & Next-->
        </div>
        </form>
    </div>
    <div id="step2" class="tab-pane">
        <div class="row">
            <div class="col-md-8"><h4>&nbsp;&nbsp;&nbsp;<i
                        class="fa fa-hand-o-right"></i> <?php echo $this->lang->line('transaction_common_add_item_detail'); ?>
                </h4></div><!--Add Item Detail-->
            <div class="col-md-4">
                <button type="button" onclick="item_detail_modal()" class="btn btn-primary pull-right"><i
                        class="fa fa-plus"></i> <?php echo $this->lang->line('common_add_item'); ?>
                </button><!--Add Item-->
            </div>
        </div>
        <table class="<?php echo table_class(); ?>">
            <thead>
            <tr>
                <th style="min-width: 5%">#</th>
                <th style="min-width: 10%" class="mrBaseDiveTable ">MI Code</th>
                <th style="min-width: 10%"><?php echo $this->lang->line('transaction_common_item_code'); ?> </th>
                <!--Item Code-->
                <th style="min-width: 30%"><?php echo $this->lang->line('transaction_common_item_description'); ?> </th>
                <!--Item Description-->
                <th style="min-width: 10%"><?php echo $this->lang->line('common_uom'); ?></th><!--UOM-->
                <!-- <th style="min-width: 10%">Requested</th> -->
                <th style="min-width: 10%"><?php echo $this->lang->line('common_qty'); ?> </th><!--QTY-->
                <th style="min-width: 10%"><?php echo $this->lang->line('common_unit_cost'); ?> </th><!--Unit Cost-->
                <th style="min-width: 15%"><?php echo $this->lang->line('common_value'); ?>
                    (<?php echo $this->common_data['company_data']['company_default_currency']; ?>)
                </th><!--Value-->
                <th style="min-width: 10%">&nbsp;</th>
            </tr>
            </thead>
            <tbody id="item_table_body">
            <tr class="danger">
                <td colspan="8" class="text-center"><b><?php echo $this->lang->line('common_no_records_found'); ?> </b>
                </td><!--No Records Found-->
            </tr>
            </tbody>
            <tfoot id="table_tfoot">

            </tfoot>
        </table>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev"><?php echo $this->lang->line('common_previous'); ?> </button>
            <!--Previous-->
        </div>
    </div>
    <div id="step3" class="tab-pane">
        <div id="conform_body"></div>
        <hr>
        <div id="conform_body_attachement">
            <h4 class="modal-title"
                id="Material_issue_attachment_label"><?php echo $this->lang->line('transaction_goods_received_voucher_modal_title'); ?> </h4>
            <!--Modal title-->
            <br>

            <div class="table-responsive" style="width: 60%">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo $this->lang->line('common_file_name'); ?> </th><!--File Name-->
                        <th><?php echo $this->lang->line('common_description'); ?> </th><!--Description-->
                        <th><?php echo $this->lang->line('common_type'); ?> </th><!--Type-->
                        <th><?php echo $this->lang->line('common_action'); ?> </th><!--Action-->
                    </tr>
                    </thead>
                    <tbody id="Material_issue_attachment" class="no-padding">
                    <tr class="danger">
                        <td colspan="5"
                            class="text-center"><?php echo $this->lang->line('common_no_attachment_found'); ?> </td>
                        <!--No Attachment Found-->
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev"><?php echo $this->lang->line('common_previous'); ?> </button>
            <!--Previous-->
            <button class="btn btn-primary "
                    onclick="save_draft()"><?php echo $this->lang->line('common_save_as_draft'); ?> </button>
            <!--Save as Draft-->
            <button class="btn btn-success submitWizard"
                    onclick="confirmation()"><?php echo $this->lang->line('common_confirm'); ?> </button><!--Confirm-->
        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="item_detail_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title"><?php echo $this->lang->line('transaction_common_add_item_detail'); ?> </h5>
                <!--Add Item Detail-->
            </div>
            <div class="modal-body">
                <form role="form" id="item_detail_form" class="form-horizontal">
                    <table class="table table-bordered table-condensed no-color" id="MaterialIssue_detail_add_table">
                        <thead>
                        <tr>
                            <th style="width: 250px;"><?php echo $this->lang->line('transaction_common_item_code'); ?><?php required_mark(); ?></th>
                            <!--Item Code-->
                            <th style="width: 150px;"><?php echo $this->lang->line('transaction_common_uom'); ?><?php required_mark(); ?></th>
                            <!--UOM-->
                            <th style="width:100px;"><?php echo $this->lang->line('transaction_current_stock'); ?><?php required_mark(); ?></th>
                            <!--Current Stock-->
                            <th style="width: 150px;"><?php echo $this->lang->line('common_segment'); ?><?php required_mark(); ?></th>
                            <!--Segment-->
                            <?php if ($projectExist == 1) { ?>
                                <th><?php echo $this->lang->line('common_project'); ?><?php required_mark(); ?></th><!--Project-->
                            <?php } ?>
                            <th style="width: 150px;"><?php echo $this->lang->line('transaction_received_qty'); ?><?php required_mark(); ?></th>
                            <!--Received Qty-->
                            <th style="width: 150px;"><?php echo $this->lang->line('common_unit_cost'); ?><?php required_mark(); ?></th>
                            <!--Unit Cost-->
                            <th style="width: 200px;"><?php echo $this->lang->line('common_comment'); ?> </th>
                            <!--Comment-->
                            <th style="width: 40px;">
                                <button type="button" class="btn btn-primary btn-xs"
                                        onclick="add_more_material_issue()"><i
                                        class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" onkeyup="clearitemAutoID(event,this)"
                                       class="form-control search f_search"
                                       name="search[]"
                                       placeholder="<?php echo $this->lang->line('common_item_id'); ?>,<?php echo $this->lang->line('transaction_common_item_description'); ?>..."
                                       id="f_search_1"><!--Item ID--><!--Item Description-->
                                <input type="hidden" class="form-control itemAutoID" name="itemAutoID[]">
                                <input type="hidden" class="form-control currentStock" name="currentStock">
                            </td>
                            <td>
                                <?php echo form_dropdown('UnitOfMeasureID[]', $unitOfMeasure_arr, '', 'class="form-control umoDropdown"  required'); ?>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="currentWareHouseStockQty[]"
                                           class="form-control currentWareHouseStock" required readonly>
                                </div>
                            </td>
                            <td>
                                <?php echo form_dropdown('a_segment[]', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2 segment" required onchange="load_segmentBase_projectID_item(this)"'); ?>
                            </td>
                            <?php if ($projectExist == 1) { ?>
                                <td>
                                    <div class="div_projectID_income">
                                        <select name="projectID" class="form-control select2">
                                            <option
                                                value=""><?php echo $this->lang->line('common_select_project'); ?> </option>
                                            <!--Select Project-->
                                        </select>
                                    </div>
                                </td>
                            <?php } ?>
                            <td style="width: 100px">
                                <input type="text" name="quantityRequested[]" onfocus="this.select();"
                                       onkeyup="validatetb_row(this)"
                                       class="form-control number quantityRequested" required>
                            </td>
                            <td style="width: 100px">
                                <input type="text" name="unitCost[]" onfocus="this.select();"
                                       class="form-control number unitCost" required>
                            </td>
                            <td>
                                <textarea class="form-control" rows="1" name="comment[]"
                                          placeholder="<?php echo $this->lang->line('transaction_common_item_comment'); ?>..."></textarea>
                                <!--Item Comment-->
                            </td>
                            <td class="remove-td" style="vertical-align: middle;text-align: center"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?php echo $this->lang->line('common_Close'); ?> </button><!--Close-->
                <button class="btn btn-primary" type="button"
                        onclick="saveMaterialReceipt_addItem()"><?php echo $this->lang->line('common_save_change'); ?>
                </button><!--Save changes-->
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" role="dialog" id="item_detail_modal_edit" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('transaction_common_edit_item_detail'); ?> </h4>
                <!--Edit Item Detail-->
            </div>
            <div class="modal-body">
                <form role="form" id="edit_item_detail_form" class="form-horizontal">
                    <table class="table table-bordered table-condensed no-color" id="MaterialIssue_detail_edit_table">
                        <thead>
                        <tr>
                            <th style="width: 250px;"><?php echo $this->lang->line('transaction_common_item_code'); ?><?php required_mark(); ?></th>
                            <!--Item Code-->
                            <th style="width: 150px;" ><?php echo $this->lang->line('common_uom'); ?><?php required_mark(); ?></th>
                            <!--UOM-->
                            <th style="width:100px;"><?php echo $this->lang->line('transaction_current_stock'); ?><?php required_mark(); ?></th>
                            <!--Current Stock-->
                            <th style="width: 150px;" class="mitypecoloumn"><?php echo $this->lang->line('common_segment'); ?><?php required_mark(); ?></th>
                            <!--Segment-->
                            <?php if ($projectExist == 1) { ?>
                                <th><?php echo $this->lang->line('common_project'); ?><?php required_mark(); ?></th><!--Project-->
                            <?php } ?>
                            <th style="width: 150px;"><?php echo $this->lang->line('transaction_received_qty'); ?><?php required_mark(); ?></th>
                            <!--Received Qty-->
                            <th style="width: 150px;" class="mitypecoloumn"><?php echo $this->lang->line('common_unit_cost'); ?><?php required_mark(); ?></th>
                            <!--Unit Cost-->
                            <th style="width: 200px;"><?php echo $this->lang->line('common_comment'); ?> </th>
                            <!--Comment-->
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" onkeyup="clearitemAutoIDEdit(event,this)" class="form-control"
                                       id="search"
                                       name="search"
                                       placeholder="<?php echo $this->lang->line('common_item_id'); ?>, <?php echo $this->lang->line('common_item_description'); ?>...">
                                <!--Item ID--><!--Item Description-->
                                <input type="hidden" class="form-control" id="itemAutoID_edit" name="itemAutoID">
                                <input type="hidden" class="form-control" id="currentStock_edit" name="currentStock">
                                <input type="hidden" class="form-control" id="mrMasterID_edit_frt" name="mrMasterID">
                                <input type="hidden" class="form-control" id="mrnDetailID_edit" name="mrnDetailID">
                            </td>
                            <td>
                                <?php echo form_dropdown('unitOfMeasureID', $unitOfMeasure_arr, '', 'class="form-control" id="UnitOfMeasureID_edit"  required'); ?>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="currentWareHouseStockQty"
                                           class="form-control" id="currentWareHouseStockQty_edit" required readonly>
                                </div>
                            </td>
                            <td class="mitypecoloumn">
                                <?php echo form_dropdown('a_segment', $segment_arr, $this->common_data['company_data']['default_segment'], 'class="form-control select2" id="segment_edit" onchange="load_segmentBase_projectID_itemEdit(this)"'); ?>
                            </td>
                            <?php if ($projectExist == 1) { ?>
                                <td>
                                    <div id="edit_div_projectID_item">
                                        <select name="projectID" id="projectID" class="form-control select2">
                                            <option
                                                value=""><?php echo $this->lang->line('common_select_project'); ?> </option>
                                            <!--Select Project-->
                                        </select>
                                    </div>
                                </td>
                            <?php } ?>
                            <td style="width: 100px">
                                <input type="text" name="quantityRequested" onfocus="this.select();"
                                       class="form-control number" onkeyup="chkbalanceqty()" id="quantityRequested_edit" required>
                            </td>
                            <td style="width: 100px" class="mitypecoloumn">
                                <input type="text" name="unitCost" onfocus="this.select();"
                                       class="form-control number" id="unitCost_edit" required>
                            </td>
                            <td>
                                <textarea class="form-control" rows="1" name="comment"
                                          placeholder="<?php echo $this->lang->line('transaction_common_item_comment'); ?>..."
                                          id="comment_edit"></textarea><!--Item Comment-->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?php echo $this->lang->line('common_Close'); ?> </button><!--Close-->
                <button class="btn btn-primary" type="button"
                        onclick="updateMaterialIssue_addItem()"><?php echo $this->lang->line('common_update_changes'); ?>
                </button><!--Update changes-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-lg" id="emp_model" role="dialog"
     aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"
                    id="exampleModalLabel"><?php echo $this->lang->line('transaction_link_employee'); ?> </h4>
                <!--Link Employee-->
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3"
                               class="col-sm-3 control-label"><?php echo $this->lang->line('common_employee'); ?> </label>
                        <!--Employee-->
                        <div class="col-sm-7">
                            <?php
                            echo form_dropdown('employee_id', $customer_arr, '', 'class="form-control select2" id="employee_id" required'); ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?php echo $this->lang->line('common_Close'); ?> </button><!--Close-->
                <button type="button" class="btn btn-primary"
                        onclick="fetch_employee_detail()"><?php echo $this->lang->line('common_add_employee'); ?> </button>
                <!--Add employee-->
            </div>
        </div>
    </div>
</div>
<div aria-hidden="true" role="dialog" id="stockadjustmentSwitch" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title"><?php echo $this->lang->line('transaction_gl_account'); ?> </h5>
                <!--GL Account-->
            </div>
            <div class="modal-body" id="divglAccount">
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default"
                        type="button"><?php echo $this->lang->line('common_Close'); ?> </button><!--Close-->
                <button class="btn btn-primary" type="button"
                        onclick="materialAccountUpdate(1)"><?php echo $this->lang->line('transaction_apply_to_all'); ?>
                </button><!--Apply to All-->
                <button class="btn btn-primary" type="button"
                        onclick="materialAccountUpdate(0)"><?php echo $this->lang->line('common_save_change'); ?>
                </button><!--Save changes-->

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mr_base_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Material Request Base</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="box box-widget widget-user-2">
                            <div class="widget-user-header bg-yellow">
                                <h5>Material Issue</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked" id="mrcode">


                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <table class="table table-bordered table-striped table-condesed ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th class="text-left">Description</th>
                                <th>UOM</th>
                                <th>Requested Qty</th>
                                <th>Material Issued Qty</th>
                                <th>Received Qty</th>
                                <th>Balance Qty</th>
                                <!--<th>Current Qty</th>-->
                                <th>Qty</th>
                            </tr>
                            </thead>
                            <tbody id="table_body_mr_note_detail">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="mrbasebtn" class="btn btn-primary" onclick="save_mr_note_base_items()">Save
                    changes
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/** sub item master modal created by Shafry */
$this->load->view('system/grv/sub-views/inc-sub-item-master');
?>
<script type="text/javascript">
    var search_id = 1;
    var type;
    var mrnAutoID;
    var mrnDetailID;
    var EIdNo;
    var ECode;
    var projectID;
    var materialReceiptNoteType;
    var Primary_Segment;
    $(document).ready(function () {
        $("[rel=tooltip]").tooltip();
        $('.headerclose').click(function () {
            fetchPage('system/inventory/MaterialReceipt/material_receipt_note', mrnAutoID, 'Material Receipt Note');
        });
        $('.select2').select2();
        type = 'Inventory';
        EIdNo = null;
        projectID = null;
        mrnAutoID = null;
        mrnDetailID = null;
        Inputmask().mask(document.querySelectorAll("input"));
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {
            $('#material_receipt_form').bootstrapValidator('revalidateField', 'receivedDate');
        });

        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        CurrencyID = <?php echo json_encode($this->common_data['company_data']['company_default_currencyID']); ?>;
        currency_validation_modal(CurrencyID, 'MRN', '', '');
        if (p_id) {
            mrnAutoID = p_id;
            load_material_receipt_header();
            $("#a_link").attr("href", "<?php echo site_url('MaterialReceiptNote/load_material_receipt_conformation'); ?>/" + mrnAutoID);
            $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_material_receipt'); ?>/" + mrnAutoID + '/MRN');
            $('.btn-wizard').removeClass('disabled');
        } else {
            $('.btn-wizard').addClass('disabled');
            initializeitemTypeahead(type);
            initializeitemTypeahead_edit(type);
        }

        FinanceYearID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinanceYearID'])); ?>;
        DateFrom = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateFrom'])); ?>;
        DateTo = <?php echo json_encode(trim($this->common_data['company_data']['FYPeriodDateTo'])); ?>;
        periodID = <?php echo json_encode(trim($this->common_data['company_data']['companyFinancePeriodID'])); ?>;
        fetch_finance_year_period(FinanceYearID, periodID);

        number_validation();
        $('#material_receipt_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                receiptType: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_type_is_required');?>.'}}}, /*Type is required*/
                //segment: {validators: {notEmpty: {message: '<?php //echo $this->lang->line('common_segment_is_required');?>.'}}}, /*Segment is required*/
                /*employeeID: {validators: {notEmpty: {message: 'Employee is required.'}}},*/
                employeeName: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_employee_is_required');?>.'}}}, /*Employee is required*/
                receivedDate: {validators: {notEmpty: {message: '<?php echo $this->lang->line('transaction_received_date_is_required');?>.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            $("#receiptType").prop("disabled", false);
            $("#location").prop("disabled", false);
            $("#itemType").prop("disabled", false);
            $("#segment").prop("disabled", false);
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'employeeID', 'value': EIdNo});
            data.push({'name': 'mrnAutoID', 'value': mrnAutoID});
            data.push({'name': 'requested', 'value': $('#employee_id option:selected').text()});
            data.push({'name': 'location_dec', 'value': $('#location option:selected').text()});
            data.push({'name': 'companyFinanceYear', 'value': $('#financeyear option:selected').text()});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('MaterialReceiptNote/save_material_receipt_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    refreshNotifications(true);
                    if (data['status']) {
                        $('.btn-wizard').removeClass('disabled');
                        mrnAutoID = data['last_id'];
                        materialReceiptNoteType = data['receiptType'];
                        Primary_Segment = $("#segment").val();
                        $("#a_link").attr("href", "<?php echo site_url('MaterialReceiptNote/load_material_issue_conformation'); ?>/" + mrnAutoID);
                        $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_material_issue'); ?>/" + mrnAutoID + '/MI');
                        if (materialReceiptNoteType == 'Material Request') {
                            $("#segment").prop("disabled", true);
                        }else{
                            $("#segment").prop("disabled", false);
                        }
                        fetch_material_receipt_detail();
                        $('[href=#step2]').tab('show');
                    }
                    stopLoad();
                    type = $('#itemType').val();
                    /*$('#search').typeahead('destroy');
                     initializeitemTypeahead(type);*/
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                    /*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('a[data-toggle="tab"]').removeClass('btn-primary');
            $('a[data-toggle="tab"]').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        });

        $('.next').click(function () {
            var nextId = $(this).parents('.tab-pane').next().attr("id");
            $('[href=#' + nextId + ']').tab('show');
        });

        $('.prev').click(function () {
            var prevId = $(this).parents('.tab-pane').prev().attr("id");
            $('[href=#' + prevId + ']').tab('show');
        });
    });

    function fetch_finance_year_period(companyFinanceYearID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'companyFinanceYearID': companyFinanceYearID},
            url: "<?php echo site_url('Dashboard/fetch_finance_year_period'); ?>",
            success: function (data) {
                $('#financeyear_period').empty();
                var mySelect = $('#financeyear_period');
                mySelect.append($('<option></option>').val('').html('<?php echo $this->lang->line('transaction_select_financial_period');?>'));
                /*Select  Financial Period*/
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['companyFinancePeriodID']).html(text['dateFrom'] + ' - ' + text['dateTo']));
                    });
                    if (select_value) {
                        $("#financeyear_period").val(select_value);
                    }
                    ;
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }

    function clearitemAutoID(e, ths) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9 || keyCode == 13) {
            //e.preventDefault();
        } else {
            $(ths).closest('tr').find('.itemAutoID').val('');
        }

    }

    function clearitemAutoIDEdit(e, ths) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9 || keyCode == 13) {
            //e.preventDefault();
        } else {
            $(ths).closest('tr').find('#itemAutoID_edit').val('');
        }

    }

    function initializeitemTypeahead(type, id) {
        $('#f_search_' + id).autocomplete({
            serviceUrl: '<?php echo site_url();?>/MaterialReceiptNote/fetch_inv_item_stock_adjustment/?&t=' + type,
            onSelect: function (suggestion) {

                var cont = true;
                $('.itemAutoID').each(function () {
                    if (this.value) {
                        if (this.value == suggestion.itemAutoID) {
                            $('#f_search_' + id).val('');
                            $('#f_search_' + id).closest('tr').find('.itemAutoID').val('');
                            $('#f_search_' + id).closest('tr').find('.currentWareHouseStock').val('');
                            $('#f_search_' + id).closest('tr').find('.quantityRequested').val('');
                            $('#f_search_' + id).closest('tr').find('.unitCost').val('');
                            myAlert('w', 'Selected item is already selected');
                            cont = false;
                        }
                    }
                });


                if (cont) {//$(this).closest('tr').find('.itemAutoID').val(suggestion.itemAutoID);
                    setTimeout(function () {
                        $('#f_search_' + id).closest('tr').find('.itemAutoID').val(suggestion.itemAutoID);
                    }, 200);
                    $(this).closest('tr').find('.currentStock').val(suggestion.currentStock);
                    $(this).closest('tr').find('.d_uom').text(suggestion.defaultUnitOfMeasure);
                    $(this).closest('tr').find('.unitCost').val(suggestion.companyLocalWacAmount);
                    $(this).closest('tr').find('.quantityRequested').focus();
                    $(this).closest('tr').css("background-color", 'white');
                    fetch_related_uom_id(suggestion.defaultUnitOfMeasureID, suggestion.defaultUnitOfMeasureID, this);
                    fetch_warehouse_item(suggestion.itemAutoID, this);
                }
            }
        });
        $('#f_search_' + id).off('focus.autocomplete');

    }

    function initializeitemTypeahead_edit(type) {
        $('#search').autocomplete({
            serviceUrl: '<?php echo site_url();?>/MaterialReceiptNote/fetch_inv_item_stock_adjustment/?&t=' + type,
            onSelect: function (suggestion) {
                setTimeout(function () {
                    $('#quantityRequested_edit').val();
                    $('#itemAutoID_edit').val(suggestion.itemAutoID);
                    $('#currentStock_edit').val(suggestion.currentStock);
                    $('#d_uom_edit').val(suggestion.defaultUnitOfMeasure);
                    $(this).closest('tr').find('#quantityRequested_edit').focus();
                }, 200);
                fetch_related_uom_id_edit(suggestion.defaultUnitOfMeasureID, suggestion.defaultUnitOfMeasureID);
                fetch_warehouse_item_edit(suggestion.itemAutoID);
            }
        });


    }

    function fetch_warehouse_item(itemAutoID, element) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'mrnAutoID': mrnAutoID, 'itemAutoID': itemAutoID},
            url: "<?php echo site_url('MaterialReceiptNote/fetch_warehouse_item'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data['status']) {
                    $(element).closest('tr').find('.currentWareHouseStock').val(data['currentStock']);
                } else {
                    $(element).closest('tr').find('.currentWareHouseStock').val(0);
                    //$(element).closest('tr').find('.itemAutoID').val('');
                }
                refreshNotifications(true);
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function fetch_warehouse_item_edit(itemAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'mrnAutoID': mrnAutoID, 'itemAutoID': itemAutoID},
            url: "<?php echo site_url('MaterialReceiptNote/fetch_warehouse_item'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data['status']) {
                    $('#currentWareHouseStockQty_edit').val(data['currentStock']);
                } else {
                    $('#search').typeahead('val', '');
                    $('#itemAutoID_edit').val('');
                }
                refreshNotifications(true);
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function load_material_receipt_header() {
        if (mrnAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'mrnAutoID': mrnAutoID},
                url: "<?php echo site_url('MaterialReceiptNote/load_material_receipt_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        $('#receiptType').val(data['receiptType']).change();
                        materialReceiptNoteType = data['receiptType'];
                        $('#receivedDate').val(data['receivedDate']);
                        if(data['receiptType']=='Material Request'){

                        }else{
                            $('#segment').val(data['segmentID'] + '|' + data['segmentCode']).change();
                            Primary_Segment = data['segmentID'] + '|' + data['segmentCode'];
                        }

                        $('#narration').val(data['comment']);
                        $('#location').val(data['wareHouseAutoID']).change();
                        $('#contactPersonName').val(data['contactPersonName']);
                        $('#contactPersonNumber').val(data['contactPersonNumber']);
                        if (data["employeeID"] > 0) {
                            $('#employeeName').prop('readonly', true);
                            $('#employeeName').val(data['employeeCode'] + ' | ' + data['employeeName']);
                        } else {
                            $('#employeeName').val(data['employeeName']);
                        }
                        $('#employee_id').val(data['employeeID']).change();
                        EIdNo = data['employeeID'];
                        ECode = data['employeeCode'];
                        $('#financeyear').val(data['companyFinanceYearID']);
                        $('#issueRefNo').val(data['RefNo']);
                        fetch_finance_year_period(data['companyFinanceYearID'], data['companyFinancePeriodID']);
                        fetch_material_receipt_detail();
                        $('#itemType').val(data['itemType']).change();
                        type = data['itemType'];
                        $('#search').typeahead('destroy');
                        initializeitemTypeahead(type);
                        $('[href=#step2]').tab('show');
                        $('a[data-toggle="tab"]').removeClass('btn-primary');
                        $('a[data-toggle="tab"]').addClass('btn-default');
                        $('[href=#step2]').removeClass('btn-default');
                        $('[href=#step2]').addClass('btn-primary');
                    }
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                    /*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }

    function fetch_related_uom_id(masterUnitID, select_value, element) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'masterUnitID': masterUnitID},
            url: "<?php echo site_url('dashboard/fetch_related_uom_id'); ?>",
            success: function (data) {
                $(element).closest('tr').find('.umoDropdown').empty();

                var mySelect = $(element).parent().closest('tr').find('.umoDropdown');

                mySelect.append($('<option></option>').val('').html('Select  UOM'));
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['UnitID']).html(text['UnitShortCode'] + ' | ' + text['UnitDes']));
                    });
                    if (select_value) {
                        $(element).closest('tr').find('.umoDropdown').val(select_value);
                    }
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }


    function fetch_related_uom_id_edit(masterUnitID, select_value) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'masterUnitID': masterUnitID},
            url: "<?php echo site_url('dashboard/fetch_related_uom_id'); ?>",
            success: function (data) {
                $('#UnitOfMeasureID_edit').empty();
                var mySelect = $('#UnitOfMeasureID_edit');
                mySelect.append($('<option></option>').val('').html('Select UOM'));
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['UnitID']).html(text['UnitShortCode'] + ' | ' + text['UnitDes']));
                    });
                    if (select_value) {
                        $('#UnitOfMeasureID_edit').val(select_value);
                    }
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }

    function fetch_material_receipt_detail() {
        var receiptType = $('#receiptType').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'mrnAutoID': mrnAutoID},
            url: "<?php echo site_url('MaterialReceiptNote/fetch_material_receipt_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                var total = 0;
                $('#item_table_body').empty();
                x = 1;
                if (jQuery.isEmptyObject(data['detail'])) {
                    $('#item_table_body').append('<tr class="danger"><td colspan="9" class="text-center"><b><?php echo $this->lang->line('common_no_records_found');?></b></td></tr>');
                    <!--No Records Found-->
                    $("#receiptType").prop("disabled", false);
                    $("#location").prop("disabled", false);
                    $("#itemType").prop("disabled", false);
                } else {
                    $("#receiptType").prop("disabled", true);
                    $("#location").prop("disabled", true);
                    $("#itemType").prop("disabled", true);
                    var currency_decimal = <?php echo json_encode($this->common_data['company_data']['company_default_decimal']); ?>;
                    $.each(data['detail'], function (key, value) {
                        /*console.log(value);*/
                        if (value['isSubitemExist'] == 1) {
                            var colour = 'color: #dad835 !important';
                            colour = '';
                            string = '<tr><td>' + x + '</td><td class="mrBaseDiveTable">' + value['itemIssueCode'] + '</td><td>' + value['itemSystemCode'] + '</td><td>' + value['itemDescription'] + '</td><td class="text-center">' + value['unitOfMeasure'] + '</td><td class="text-right">' + value['qtyReceived'] + '</td><td class="text-right">' + parseFloat(value['unitCost']).formatMoney(currency_decimal, '.', ',') + '</td><td class="text-right">' + parseFloat(value['totalValue']).formatMoney(currency_decimal, '.', ',') + '</td><td class="text-right"><a style="' + colour + '" onclick="load_itemMasterSub_modal(' + value['mrnDetailID'] + ',\'MRN\')"><i class="fa fa-list"></i></a> &nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="edit_item(' + value['mrnDetailID'] + ',this);"><span class="glyphicon glyphicon-pencil"></span></a> &nbsp;&nbsp; |&nbsp;&nbsp; <a onclick="delete_item(' + value['mrnDetailID'] + ');"><span style="color:rgb(209, 91, 71);" class="glyphicon glyphicon-trash"></span></a></td></tr>';
                        } else {
                            string = '<tr><td>' + x + '</td><td class="mrBaseDiveTable">' + value['itemIssueCode'] + '</td><td>' + value['itemSystemCode'] + '</td><td>' + value['itemDescription'] + '</td><td class="text-center">' + value['unitOfMeasure'] + '</td><td class="text-right">' + value['qtyReceived'] + '</td><td class="text-right">' + parseFloat(value['unitCost']).formatMoney(currency_decimal, '.', ',') + '</td><td class="text-right">' + parseFloat(value['totalValue']).formatMoney(currency_decimal, '.', ',') + '</td><td class="text-right"> <a onclick="edit_item(' + value['mrnDetailID'] + ',this);"><span class="glyphicon glyphicon-pencil"></span></a> &nbsp;&nbsp; |&nbsp;&nbsp; <a onclick="delete_item(' + value['mrnDetailID'] + ');"><span style="color:rgb(209, 91, 71);" class="glyphicon glyphicon-trash"></span></a></td></tr>';
                        }
                        $('#item_table_body').append(string);
                        total += parseFloat(value['totalValue']);
                        if (materialReceiptNoteType == 'Material Request') {
                            $("#segment").prop("disabled", true);
                        }else {
                            $("#segment").prop("disabled", false);
                        }

                            x++;
                    });
                }
                if(receiptType == 'Material Request'){
                    $('.mrBaseDiveTable').removeClass('hide');
                    $('#table_tfoot').html('<tr> <td class="text-right" colspan="7"><?php echo $this->lang->line('common_total');?></td><td class="text-right total">' + total.formatMoney(currency_decimal, '.', ',') + '</td><td></td></tr>');
                }else{
                    $('#table_tfoot').html('<tr> <td class="text-right" colspan="6"><?php echo $this->lang->line('common_total');?> </td><td class="text-right total">' + total.formatMoney(currency_decimal, '.', ',') + '</td><td></td></tr>');
                    $('.mrBaseDiveTable').addClass('hide');
                }
                <!--Total-->
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function materialAccountUpdate(all) {
        var $form = $('#stock_adjustment_gl_form');
        var data = $form.serializeArray();
        data.push({name: "applyAll", value: all});
        data.push({name: "masterID", value: mrnAutoID});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('MaterialReceiptNote/materialAccountUpdate'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                myAlert(data[0], data[1]);
                fetch_material_receipt_detail();
                $('#stockadjustmentSwitch').modal('hide');
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });

    }

    function edit_glaccount(mrnDetailID, PLGLAutoID, BLGLAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {PLGLAutoID: PLGLAutoID, BLGLAutoID: BLGLAutoID},
            url: "<?php echo site_url('MaterialReceiptNote/stockAdjustment_load_gldropdown'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#divglAccount').html(data);
                $('#detailID').val(mrnDetailID);
                $('#stockadjustmentSwitch').modal('show');

                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function item_detail_modal() {
        if (mrnAutoID) {
            if (materialReceiptNoteType == 'Material Request') {
                load_MR_codes();
                $("#mr_base_modal").modal({backdrop: "static"});
            } else {
                $('.search').typeahead('destroy');
                $('#item_detail_form')[0].reset();
                $('#MaterialIssue_detail_add_table tbody tr').not(':first').remove();
                $('.search').typeahead('val', '');
                $('.itemAutoID').val('');
                $('.segment').val(Primary_Segment).change();
                initializeitemTypeahead(type, 1);
                $('#a_segment').val($('#segment').val());
                $('.f_search').closest('tr').css("background-color", 'white');
                $('.quantityRequested').closest('tr').css("background-color", 'white');
                $("#item_detail_modal").modal({backdrop: "static"});
            }
        }
    }

    function load_conformation() {
        if (mrnAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'mrnAutoID': mrnAutoID, 'html': true},
                url: "<?php echo site_url('MaterialReceiptNote/load_material_receipt_conformation'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#conform_body').html(data);
                    $("#a_link").attr("href", "<?php echo site_url('MaterialReceiptNote/load_material_receipt_conformation'); ?>/" + mrnAutoID);
                    $("#de_link").attr("href", "<?php echo site_url('Double_entry/fetch_double_material_receipt'); ?>/" + mrnAutoID + '/MRN');
                    attachment_modal_MaterialIssue(mrnAutoID, "<?php echo $this->lang->line('transaction_material_receipt_note');?>", "MRN");
                    /*Material Receipt Note*/
                    stopLoad();
                }, error: function () {
                    stopLoad();
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                    /*An Error Occurred! Please Try Again*/
                }
            });
        }
    }

    function confirmation() {
        if (mrnAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_confirm_this_document');?>", /*You want to confirm this document!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_confirm');?>", /*Confirm*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'mrnAutoID': mrnAutoID},
                        url: "<?php echo site_url('MaterialReceiptNote/material_item_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data['error'] == 1) {
                                myAlert('e', data['message']);
                            }else if(data['error']==2) {
                                myAlert('w',data['message']);
                            } else {
                                myAlert('s', data['message']);
                                fetchPage('system/inventory/MaterialReceipt/material_receipt_note', mrnAutoID, 'Material Receipt Note');
                            }
                            /*if (data['status']) {


                             } else {
                             myAlert('w', data['data'], 1000);
                             }*/

                        }, error: function (xhr, textStatus, errorThrown) {
                            myAlert('e', 'Error (' + textStatus + ') : ' + xhr.responseText);
                        }
                    });
                });
        }
    }

    function save_draft() {
        if (mrnAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_save_this_document');?>", /*You want to save this document!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_save_as_draft');?>", /*Save as Draft*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    fetchPage('system/inventory/MaterialReceipt/material_receipt_note', mrnAutoID, 'Material Receipt Note');
                });
        }
    }

    function delete_item(id) {
        if (mrnAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>", /*You want to delete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>", /*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'mrnDetailID': id},
                        url: "<?php echo site_url('MaterialReceiptNote/delete_material_item'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            fetch_material_receipt_detail();
                            stopLoad();
                            refreshNotifications(true);
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function edit_item(id,element) {
        if (mrnAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_edit_this_record');?>", /*You want to edit this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_edit');?>", /*Edit*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'mrnDetailID': id},
                        url: "<?php echo site_url('MaterialReceiptNote/load_material_receipt_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            mrnDetailID = data['mrnDetailID'];
                            projectID = data['projectID'];
                            $('#search').typeahead('destroy');
                            $('#search').val(data['itemDescription'] + " (" + data['itemSystemCode'] + ")");
                            $('#itemAutoID_edit').val(data.itemAutoID);
                            $('#currentStock_edit').val(data.currentStock);
                            $('#mrMasterID_edit_frt').val(data.mrMasterID);
                            $('#mrnDetailID_edit').val(id);
                            $('#currentWareHouseStockQty_edit').val(data.currentWareHouseStock);
                            $('#d_uom_edit').text(data.defaultUOM);
                            fetch_related_uom_id_edit(data['defaultUOMID'], data['unitOfMeasureID']);
                            $('#quantityRequested_edit').val(data['qtyReceived']);
                            $('#unitCost_edit').val(data['unitCost']);
                            $('#segment_edit').val(data['segmentID'] + '|' + data['segmentCode']).change();
                            $('#comment_edit').val(data['comments']);
                            initializeitemTypeahead_edit(type);
                            if (materialReceiptNoteType == 'Material Request') {
                                $('#search').attr('disabled',true);
                                $('#comment_edit').attr('readonly',true);
                                $('.mitypecoloumn').addClass('hidden');
                                $("#UnitOfMeasureID_edit").prop("disabled", true);
                            }else{
                                $('#search').attr('disabled',false);
                                $('#comment_edit').attr('readonly',false);
                                $('.mitypecoloumn').removeClass('hidden');
                                $("#UnitOfMeasureID_edit").prop("disabled", false);
                            }

                            $("#item_detail_modal_edit").modal({backdrop: "static"});
                            stopLoad();
                        }, error: function () {
                            stopLoad();
                            swal("Cancelled", "Try Again ", "error");
                        }
                    });
                });
        }
    }

    function attachment_modal_MaterialIssue(documentSystemCode, document_name, documentID) {
        if (documentSystemCode) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("Attachment/fetch_attachments"); ?>',
                dataType: 'json',
                data: {'documentSystemCode': documentSystemCode, 'documentID': documentID,'confirmedYN': 0},
                success: function (data) {
                    $('#Material_issue_attachment_label').html('<span aria-hidden="true" class="glyphicon glyphicon-hand-right color"></span> &nbsp;' + document_name + "<?php echo $this->lang->line('common_attachments');?>");
                    <!--Attachments-->
                    $('#Material_issue_attachment').empty();
                    $('#Material_issue_attachment').append('' +data+ '');

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#ajax_nav_container').html(xhr.responseText);
                }
            });
        }
    }

    function delete_Material_issue_attachement(mrnAutoID, DocumentSystemCode, myFileName) {
        if (mrnAutoID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>", /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete_this_attachment_file');?>", /*You want to delete this attachment file!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>", /*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'attachmentID': mrnAutoID, 'myFileName': myFileName},
                        url: "<?php echo site_url('inventory/delete_material_Issue_attachement'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {

                            stopLoad();
                            if (data == true) {
                                myAlert('s', 'Deleted Successfully');
                                attachment_modal_MaterialIssue(DocumentSystemCode, "Material issue", "MI");
                            } else {
                                myAlert('e', 'Deletion Failed');
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function add_more_material_issue() {
        search_id += 1;
        $('select.select2').select2('destroy');
        var appendData = $('#MaterialIssue_detail_add_table tbody tr:first').clone();
        appendData.find('.f_search').attr('id', 'f_search_' + search_id);
        appendData.find('.f_search').attr('onkeyup', 'clearitemAutoID(event,this)');
        appendData.find('input').val('');
        appendData.find('textarea').val('');

        appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
        $('#MaterialIssue_detail_add_table').append(appendData);
        var lenght = $('#MaterialIssue_detail_add_table tbody tr').length - 1;
        $('#f_search_' + search_id).closest('tr').css("background-color", 'white');
        $('#f_search_' + search_id).closest('tr').find('.segment').val(Primary_Segment).change();
        $(".select2").select2();
        initializeitemTypeahead(type, search_id);
        number_validation();
    }

    $(document).on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });

    function saveMaterialReceipt_addItem() {
        var $form = $('#item_detail_form');
        var data = $form.serializeArray();
        data.push({'name': 'mrnAutoID', 'value': mrnAutoID});
        data.push({'name': 'mrnDetailID', 'value': mrnDetailID});
        $('select[name="UnitOfMeasureID[]"] option:selected').each(function () {
            data.push({'name': 'uom[]', 'value': $(this).text()})
        });
        $('.itemAutoID').each(function () {
            if (this.value == '') {
                $(this).closest('tr').css("background-color", '#ffb2b2');
            }
        });
        $('.quantityRequested').each(function () {
            if (this.value == '' || this.value == 0) {
                $(this).closest('tr').css("background-color", '#ffb2b2');
            }
        });
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('MaterialReceiptNote/save_material_detail_multiple'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    mrnDetailID = null;
                    $('#item_detail_form')[0].reset();
                    setTimeout(function () {
                        fetch_material_receipt_detail(4);
                        $('#item_detail_modal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }, 300);
                }
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function updateMaterialIssue_addItem() {
        $("#UnitOfMeasureID_edit").prop("disabled", false);
        $('#search').attr('disabled',false);
        var $form = $('#edit_item_detail_form');
        var data = $form.serializeArray();
        data.push({'name': 'mrnAutoID', 'value': mrnAutoID});
        data.push({'name': 'mrnDetailID', 'value': mrnDetailID});
        data.push({'name': 'materialReceiptNoteType', 'value': materialReceiptNoteType});
        data.push({'name': 'uom', 'value': $('#UnitOfMeasureID_edit option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('MaterialReceiptNote/save_material_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    mrnDetailID = null;
                    $('#item_detail_form')[0].reset();
                    setTimeout(function () {
                        fetch_material_receipt_detail(4);
                        $('#item_detail_modal_edit').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }, 300);
                }else{
                    $("#UnitOfMeasureID_edit").prop("disabled", true);
                }
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');
                /*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function clearEmployee() {
        $('#employee_id').val('').change();
        $('#employeeName').val('').trigger('input');
        $('#employeeName').prop('readonly', false);
        EIdNo = null;
    }

    function link_employee_model() {
        /*$('#employee_id').val('').change();*/
        $('#emp_model').modal('show');
    }

    function fetch_employee_detail() {
        var employee_id = $('#employee_id').val();
        if (employee_id == '') {
            //swal("", "Select An Employee", "error");
            myAlert('e', '<?php echo $this->lang->line('transaction_select_an_employee');?>');
            /*Select An Employee*/
        } else {
            EIdNo = employee_id;
            var empName = $("#employee_id option:selected").text();
            /*  var empNameSplit = empName.split('|');*/
            $('#employeeName').val($.trim(empName)).trigger('input');
            $('#employeeName').prop('readonly', true);
            $('#emp_model').modal('hide');
        }
    }

    function load_segmentBase_projectID_item(segment) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Procurement/load_project_segmentBase_multiple"); ?>',
            dataType: 'html',
            data: {segment: segment.value},
            async: true,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $(segment).closest('tr').find('.div_projectID_income').html(data);
                $('.select2').select2();
                stopLoad();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                stopLoad();
            }
        });
    }

    function load_segmentBase_projectID_itemEdit(segment) {
        var type = 'item';
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Procurement/load_project_segmentBase"); ?>',
            dataType: 'html',
            data: {segment: segment.value, type: type},
            async: true,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#edit_div_projectID_item').html(data);
                $('.select2').select2();
                if (projectID) {
                    $("#projectID_item").val(projectID).change()
                }
                stopLoad();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                stopLoad();
            }
        });
    }

    function load_MR_codes() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'mrnAutoID': mrnAutoID},
            url: "<?php echo site_url('MaterialReceiptNote/fetch_material_issue_code'); ?>",
            success: function (data) {
                $('#mrcode').empty();
                $('#table_body_mr_note_detail').empty();
                var mySelect = $('#mrcode');
                if (!jQuery.isEmptyObject(data)) {
                    $.each(data, function (key, value) {
                       var balanceQTY = value['mrQty']-value['qtyReceived'];
                        /*if(balanceQTY>0){*/
                            mySelect.append('<li title="MR Date :- ' + value['issueDate'] + ' Requested By:- ' + value['employeeName'] + '"  rel="tooltip"><a onclick="fetch_mr_detail_table(' + value['itemIssueAutoID'] + ')">' + value['itemIssueCode'] + ' <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span></a></li>');
                            $("[rel=tooltip]").tooltip();
                        /*}*/
                    });
                } else {
                    mySelect.append('<li><a>No Records found</a></li>');
                }
            }, error: function () {
                swal("Cancelled", "Your " + value + " file is safe :)", "error");
            }
        });
    }

    function fetch_mr_detail_table(itemIssueAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'itemIssueAutoID': itemIssueAutoID},
            url: "<?php echo site_url('MaterialReceiptNote/fetch_material_issue_detail_table'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#table_body_mr_note_detail').empty();
                x = 1;
                if (jQuery.isEmptyObject(data['detail'])) {
                    $('#table_body_mr_note_detail').append('<tr class="danger"><td colspan="7" class="text-center"><b>No Records Found</b></td></tr>');
                } else {
                    tot_amount = 0;
                    var issuedQTY = 0;
                    var balanceQTY;
                    var issuedQTYs;
                    $.each(data['detail'], function (key, value) {
                        if (value['qtyReceived']) {
                            issuedQTY = value['qtyReceived'];
                        } else {
                            issuedQTY = 0;
                        }
                        balanceQTY = value['qtyIssued'] - issuedQTY;
                        issuedQTYs = '<input type="text" class="number" size="10" id="qtyIssued_' + value['itemIssueDetailID'] + '" value="0" onkeyup="check_qty_available(' + value['itemIssueDetailID'] + ',' + balanceQTY + ',' + value['stock'] + ')" > <input class="checkbox" id="check_' + value['itemIssueDetailID'] + '" type="hidden" value="' + value['itemIssueDetailID'] + '">';
                        $('#table_body_mr_note_detail').append('<tr><td>' + x + '</td><td>' + value['itemSystemCode'] + '</td><td>' + value['itemDescription'] + '</td><td class="text-center">' + value['unitOfMeasure'] + '</td><td class="text-right">' + (value['qtyRequested'] ) + '</td><td class="text-right">' + (value['qtyIssued'] ) + '</td><td class="text-right">' + issuedQTY + '</td><td>' + balanceQTY + '</td><td class="text-right">' + issuedQTYs + '</td></tr>');
                        /*<td class="text-right">' + (value['stock'] ) + '</td>*/
                        x++;
                    });

                }
                number_validation();
                stopLoad();
                refreshNotifications(true);
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function save_mr_note_base_items() {
        var qty = [];
        var itemIssueDetailID = [];
        $('#table_body_mr_note_detail input:hidden').each(function () {
            itemIssueDetailID.push($(this).val());
            qty.push($('#qtyIssued_' + $(this).val()).val());
        });
        if (!jQuery.isEmptyObject(itemIssueDetailID)) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'itemIssueDetailID': itemIssueDetailID, 'qty': qty, 'mrnAutoID': mrnAutoID},
                url: "<?php echo site_url('MaterialReceiptNote/save_material_issue_note_base_items'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    refreshNotifications(true);
                    if (data['status']) {
                        $('#mr_base_modal').modal('hide');
                        setTimeout(function () {
                            fetch_material_receipt_detail(4);
                        }, 300);
                    } else {
                        myAlert('w', data['data'], 1000);
                    }

                }, error: function () {
                    $('#mr_base_modal').modal('hide');
                    stopLoad();
                    swal("Cancelled", "Try Again ", "error");
                }
            });
        }
    }

    function check_qty_available(mrDetailID, balanceQTY, currentStock) {
        var issuedqty = $('#qtyIssued_' + mrDetailID).val();
         if (issuedqty > balanceQTY) {
            myAlert('w', 'Qty cannot be greater than Balance Qty');
            $('#qtyIssued_' + mrDetailID).val(0);
            $('#mrbasebtn').attr('disabled', true);
        } else {
            $('#mrbasebtn').attr('disabled', false);
        }
    }
    function validatetb_row(det) {
        if (det.value > 0) {
            $(det).closest('tr').css("background-color", 'white');
        }
    }

    $('#receiptType').on('change', function() {
        if(this.value == 'Material Request'){
            $('.requestedSegmentDiv').addClass('hide');
        }else{
            $('.requestedSegmentDiv').removeClass('hide');
            $("#segment").prop("disabled", false);
        }
    })

    function chkbalanceqty(){
        if (materialReceiptNoteType == 'Material Request') {
            var issuedqty = $('#quantityRequested_edit').val();
            var itemID = $('#itemAutoID_edit').val();
            var mrnDetailID_edit = $('#mrnDetailID_edit').val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'issuedqty': issuedqty, 'itemAutoID': itemID, 'mrnDetailID': mrnDetailID_edit},
                url: "<?php echo site_url('MaterialReceiptNote/chk_balance_qty'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();

                    if(data[0]=='w'){
                        myAlert(data[0],data[1])
                        $('#quantityRequested_edit').val('')
                    }

                }, error: function () {
                    stopLoad();
                    swal("Cancelled", "Try Again ", "error");
                }
            });
        }
    }

</script>