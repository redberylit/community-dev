<?php
echo head_page($_POST['page_name'], false);
$this->load->helper('buyback_helper');
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$segment_arr = fetch_segment();
$supplier_arr = all_supplier_drop();
$farms_arr = load_all_farms();
$currency_arr = all_currency_new_drop();//array('' => 'Select Currency');
$location_arr = all_delivery_location_drop();
$location_arr_default = default_delivery_location_drop();
$financeyear_arr = all_financeyear_drop(true);
$batch_arr = array('' => 'Select Batch');

?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>"/>
<div id="filter-panel" class="collapse filter-panel"></div>
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #060606
    }

    span.input-req-inner {
        width: 20px;
        height: 40px;
        position: absolute;
        overflow: hidden;
        display: block;
        right: 4px;
        top: -15px;
        -webkit-transform: rotate(135deg);
        -ms-transform: rotate(135deg);
        transform: rotate(135deg);
        z-index: 100;
    }

    span.input-req-inner:before {
        font-size: 20px;
        content: "*";
        top: 15px;
        right: 1px;
        color: #fff;
        position: absolute;
        z-index: 2;
        cursor: default;
    }

    span.input-req-inner:after {
        content: '';
        width: 35px;
        height: 35px;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
        background: #f45640;
        position: absolute;
        top: 7px;
        right: -29px;
    }

    .search_cancel {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;
        vertical-align: middle;
        padding: 3px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .lableheader {
        text-align: right;
        color: rgb(121, 121, 121);
        font-weight: 500;
    }

    .quickmanagement {
        text-align: left;
        color: rgb(121, 121, 121);
        font-weight: 500;
        padding-left: 40px;
    }

    .stepwizard-step p {
        margin-top: 10px;
    }

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;

    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

</style>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - Farm Visit Report Header</a>
    <a class="btn btn-default btn-wizard" href="#step2" onclick="load_confirmation();" data-toggle="tab">Step 2 -
        Farm Visit Report Confirmation</a>
</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="buyback_farmer_visit_frm"'); ?>
        <input type="hidden" name="farmerVisitID" id="edit_farmerVisitID">

        <div class="row">
            <div class="col-sm-12 animated zoomIn">
                <header class="head-title">
                    <h2>FARM DETAIL</h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Farm Name</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                        <?php echo form_dropdown('farmID', $farms_arr, '', 'class="form-control select2" id="farmID" onchange="fetch_farmBatch_fieldOfficerReport(this.value),fetchFarmerAddress(this.value)" required'); ?>
                            <span class="input-req-inner"></span></span>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Batch</label>
                    </div>
                    <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                        <div id="div_loadBatch">
                            <?php echo form_dropdown('batchMasterID', $batch_arr, '', 'class="form-control" id="batchMasterID"'); ?>
                        </div>
                    <span class="input-req-inner"></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Farm Address</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <textarea name="farmerAddress" id="farmerAddress" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">No Of Birds</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" name="noofbirds" id="noofbirds" class="form-control" readonly
                               placeholder="No Of Birds">
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Document Date</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <span class="input-req" title="Required Field">
                         <div class="input-group datepic">
                             <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                             <input type="text" name="documentDate"
                                    data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                    value="<?php echo $current_date; ?>" id="documentDate" class="form-control">
                         </div>
                            <span class="input-req-inner"></span></span>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Farm Type</label>
                    </div>
                    <div class="form-group col-sm-4">
                      <span class="input-req"
                            title="Required Field"><?php echo form_dropdown('farmType', array('' => 'Select Type', '1' => 'All-in - All-out', '2' => 'Multi-age'), '1', 'class="form-control " id="farmType" required'); ?>
                          <span
                              class="input-req-inner"></span></span>

                        <span class="input-req-inner"></span></span>

                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Input Date / Hatch Date</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="hatchDate"
                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                   value="<?php echo $current_date; ?>" id="hatchDate" class="form-control">
                        </div>

                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">Breed</label>
                    </div>
                    <div class="form-group col-sm-4">
                      <span class="input-req"
                            title="Required Field">
                     <input type="text" name="breed" id="breed" class="form-control"
                            placeholder="Breed">
                          <span
                              class="input-req-inner"></span></span>

                        <span class="input-req-inner"></span></span>

                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Feed</label>
                    </div>
                    <div class="form-group col-sm-4">
                         <span class="input-req" title="Required Field">
                     <input type="text" name="feed" id="feed" class="form-control" placeholder="Feed">
                          <span class="input-req-inner"></span></span>
                        <span class="input-req-inner"></span></span>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">No Of Visit</label>
                    </div>
                    <div class="form-group col-sm-4">
                      <span class="input-req" title="Required Field">
                          <div id="VisitNo">
                              <input type="text" name="noofvisit" id="noofvisit" class="form-control" placeholder="Select Visit" readonly>
                          </div>
                          <?php // echo form_dropdown('noofvisit', array('' => 'Select No Of Visits', '1' => '1st Visit', '2' => '2nd Visit','3' => '3rd Visit','4' => '4th Visit','5'=> '5th Visit','6'=>'6th Visit'), '', 'class="form-control select2" id="noofvisit" required'); ?>
                          <span
                                  class="input-req-inner"></span></span>

                        <span class="input-req-inner"></span></span>

                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>Details Of The Farm Conditions / Management And Instructions Given To The Farmer</h2>
                </header>
                <div class="row">
                    <div class="form-group col-sm-10" style="margin-top: 5px;">
                        <textarea class="form-control" rows="5" name="detailFarmDescription" id="narration"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>Quick Management Appraisal Score</h2>
                </header>
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">
            <div class="form-group col-sm-2">
                <label class="title"> <span class="label label-success">A</span>&nbsp;Very
                    Satisfactory</label>
            </div>

            <div class="form-group col-sm-2">
                <label class="title"> <span class="label label-warning">B</span>&nbsp;Satisfactory</label>
            </div>

            <div class="form-group col-sm-2">
                <label class="title"> <span class="label label-danger">C</span>&nbsp;Un Satisfactory</label>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;No Of
                    Feeders</label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="numberOfFeedersA" type="radio" data-caption="" class="columnSelected"
                                   name="numberOfFeeders" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="numberOfFeedersB" type="radio" data-caption="" class="columnSelected"
                                   name="numberOfFeeders" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="numberOfFeedersC" type="radio" data-caption="" class="columnSelected"
                                   name="numberOfFeeders" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Litter Quality
                </label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="litterQualityA" type="radio" data-caption="" class="columnSelected"
                                   name="litterQuality" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="litterQualityB" type="radio" data-caption="" class="columnSelected"
                                   name="litterQuality" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="litterQualityC" type="radio" data-caption="" class="columnSelected"
                                   name="litterQuality" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;No Of
                    Drinkers</label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="numberOfDrinkersA" type="radio" data-caption="" class="columnSelected"
                                   name="numberOfDrinkers" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="numberOfDrinkersB" type="radio" data-caption="" class="columnSelected"
                                   name="numberOfDrinkers" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="numberOfDrinkersC" type="radio" data-caption="" class="columnSelected"
                                   name="numberOfDrinkers" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Smell Of
                    Amonia</label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="smellOfAmoniaA" type="radio" data-caption="" class="columnSelected"
                                   name="smellOfAmonia" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="smellOfAmoniaB" type="radio" data-caption="" class="columnSelected"
                                   name="smellOfAmonia" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="smellOfAmoniaC" type="radio" data-caption="" class="columnSelected"
                                   name="smellOfAmonia" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Feeder
                    Height</label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="feederHeightA" type="radio" data-caption="" class="columnSelected"
                                   name="feederHeight" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="feederHeightB" type="radio" data-caption="" class="columnSelected"
                                   name="feederHeight" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="feederHeightC" type="radio" data-caption="" class="columnSelected"
                                   name="feederHeight" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Ventilation
                </label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="ventilationA" type="radio" data-caption="" class="columnSelected"
                                   name="ventilation" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="ventilationB" type="radio" data-caption="" class="columnSelected"
                                   name="ventilation" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="ventilationC" type="radio" data-caption="" class="columnSelected"
                                   name="ventilation" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Drinker Height
                </label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="drinkerHeightA" type="radio" data-caption="" class="columnSelected"
                                   name="drinkerHeight" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="drinkerHeightB" type="radio" data-caption="" class="columnSelected"
                                   name="drinkerHeight" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="drinkerHeightC" type="radio" data-caption="" class="columnSelected"
                                   name="drinkerHeight" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Biosecurity</label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="biosecurityA" type="radio" data-caption="" class="columnSelected"
                                   name="biosecurity" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="biosecurityB" type="radio" data-caption="" class="columnSelected"
                                   name="biosecurity" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="biosecurityC" type="radio" data-caption="" class="columnSelected"
                                   name="biosecurity" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Density</label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="densityA" type="radio" data-caption="" class="columnSelected" name="density"
                                   value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="densityB" type="radio" data-caption="" class="columnSelected" name="density"
                                   value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="densityC" type="radio" data-caption="" class="columnSelected" name="density"
                                   value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="quickmanagement"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;Record Keeping
                </label>
            </div>
            <div class="form-group col-sm-4">
                <div class="skin-section extraColumns">
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsgreen">
                            <input id="recordKeepingA" type="radio" data-caption="" class="columnSelected"
                                   name="recordKeeping" value="1">
                            <label for="checkbox">&nbsp;&nbsp;A</label></div>
                    </label>
                    <label class="radio-inline">
                        <div class="skin-section extraColumnsyellow">
                            <input id="recordKeepingB" type="radio" data-caption="" class="columnSelected"
                                   name="recordKeeping" value="2">
                            <label for="checkbox">&nbsp;&nbsp;B</label></div>
                    </label>

                    <label class="radio-inline">
                        <div class="skin-section extraColumnsred">
                            <input id="recordKeepingC" type="radio" data-caption="" class="columnSelected"
                                   name="recordKeeping" value="3">
                            <label for="checkbox">&nbsp;&nbsp;C</label></div>
                    </label>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="text-right m-t-xs">
                <div class="form-group col-sm-12" style="margin-top: 10px;">
                    <button class="btn btn-primary" type="submit" id="save_btn">Save</button>
                </div>
            </div>
        </div>
        </form>
        <div class="row hide" id="farmVisitReport_detail_div">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2>TECHNICAL DATA DETAIL</h2>
                </header>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary pull-right"
                                onclick="farmVisitReport_detail_modal()">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-12">
                        <div id="farmVisitReport_detail"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="step2" class="tab-pane">
        <div id="confirm_body"></div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-default prev">Previous</button>
            <button class="btn btn-primary " onclick="save_draft()">Save as Draft</button>
            <button class="btn btn-success submitWizard" onclick="confirmation()">Confirm</button>
        </div>
    </div>
</div>

<div aria-hidden="true" role="dialog" id="technical_data_add_modal" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Technical Data</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="technical_data_add_form" class="form-horizontal">
                    <input type="hidden" name="farmerVisitID" id="farmerVisitID_edit_itemAdd">
                    <table class="table table-bordered table-condensed no-color" id="technical_data_add_table">
                        <thead>
                        <tr>
                            <th rowspan="2">Age (Days)<?php required_mark(); ?></th>
                            <th rowspan="2">No Of Birds</th>
                            <th colspan="2">Mortality<?php required_mark(); ?></th>
                            <th rowspan="2">Total Feed (Kg)<?php required_mark(); ?></th>
                            <th rowspan="2">Av.Feed Per Bird</th>
                            <th rowspan="2">Av.Body Weight</th>
                            <th rowspan="2">FCR</th>
                            <th rowspan="2">Remarks<?php required_mark(); ?></th>
                          <!--  <th rowspan="2">
                                <button type="button" class="btn btn-primary btn-xs" onclick="add_more()"><i
                                        class="fa fa-plus"></i></button>
                            </th>-->
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Percent</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="age[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="numberOfBirds[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="mortalityNumber[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="mortalityPercent[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="totalFeed[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="avgFeedperBird[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="avgBodyWeight[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="fcr[]" class="form-control number">
                            </td>
                            <td>
                                <input type="text" name="remarks[]" class="form-control">
                            </td>
                          <!--  <td class="remove-td" style="vertical-align: middle;text-align: center"></td>-->
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="save_farmerVisitReport_detail()">Save
                </button>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js'); ?>"></script>
<script>

    var farmerVisitID;
    var farmerVisitDetailID;
    $(document).ready(function () {

        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function (ev) {
        });
        $('.select2').select2();
        Inputmask().mask(document.querySelectorAll("input"));

        $('.headerclose').click(function () {
            fetchPage('system/buyback/report/field_officer_report.php', '', 'Field Officer')
        });

        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            farmerVisitID = p_id;
            load_farmVisitReport_header();
            load_confirmation();
            $('.btn-wizard').removeClass('disabled');
        } else {
            $("#narration").wysihtml5();
            $('.btn-wizard').addClass('disabled');
            $('.addTableView').addClass('hide');
        }
        /* Add colours To Radio Button(Green,Yellow,Red)*/
        $('.extraColumnsgreen input').iCheck({
            checkboxClass: 'icheckbox_square_relative-green',
            radioClass: 'iradio_square_relative-green',
            increaseArea: '20%'
        });

        $('.extraColumnsyellow input').iCheck({
            checkboxClass: 'icheckbox_square_relative-yellow',
            radioClass: 'iradio_square_relative-yellow',
            increaseArea: '20%'
        });

        $('.extraColumnsred input').iCheck({
            checkboxClass: 'icheckbox_square_relative-red',
            radioClass: 'iradio_square_relative-red',
            increaseArea: '20%'
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

        $('#buyback_farmer_visit_frm').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                farmID: {validators: {notEmpty: {message: 'Farm Name is required.'}}},
                //batchMasterID: {validators: {notEmpty: {message: 'Batch is required.'}}},
                documentDate: {validators: {notEmpty: {message: 'Document Date is required.'}}},
                farmType: {validators: {notEmpty: {message: 'Farm Type is required.'}}},
                breed: {validators: {notEmpty: {message: 'Breed Type is required.'}}},
                feed: {validators: {notEmpty: {message: 'Feed is required.'}}},
             //   noofvisit: {validators: {notEmpty: {message: 'Number of visit required.'}}},
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
                url: "<?php echo site_url('Buyback/save_farmVisit_report_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        farmerVisitID = data[2];
                        $('#farmerVisitID_edit_itemAdd').val(farmerVisitID);
                        $('#edit_farmerVisitID').val(farmerVisitID);
                        $('#farmVisitReport_detail_div').removeClass('hide');
                        get_farmerVisitReport_tableView(farmerVisitID);
                        $('.btn-wizard').removeClass('disabled');
                        $('#save_btn').html('Update');
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

    function farmVisitReport_detail_modal() {
        $('#technical_data_add_form')[0].reset();
        $('#technical_data_add_table tbody tr').not(':first').remove();
        $("#technical_data_add_modal").modal({backdrop: "static"});
    }

    function add_more() {
        var appendData = $('#technical_data_add_table tbody tr:first').clone();
        appendData.find('.umoDropdown').empty();
        appendData.find('.remove-td').html('<span class="glyphicon glyphicon-trash remove-tr" style="color:rgb(209, 91, 71);"></span>');
        $('#technical_data_add_table').append(appendData);
        var lenght = $('#technical_data_add_table tbody tr').length - 1;
    }

    $(document).on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });

    function fetch_farmVisitNo(batchID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'batchID': batchID},
            url: "<?php echo site_url('Buyback/fetch_farmVisitNo_visitReport'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#VisitNo').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function fetch_farmBatch_fieldOfficerReport(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/fetch_farm_BatchesDropdown_visitReport'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#div_loadBatch').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function load_farmVisitReport_header() {
        if (farmerVisitID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'farmerVisitID': farmerVisitID},
                url: "<?php echo site_url('Buyback/load_farmVisitReport_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        farmerVisitID = data['farmerVisitID'];
                        $('#edit_farmerVisitID').val(farmerVisitID);
                        $('#farmerVisitID_edit_itemAdd').val(farmerVisitID);
                        $('#documentDate').val(data['documentDate']);
                        $('#hatchDate').val(data['hatchDate']);
                        $('#farmerAddress').val(data['farmerAddress']);
                        $('#noofbirds').val(data['numberOfBirds']);
                        $('#breed').val(data['breed']);
                        $('#feed').val(data['feed']);
                        $('#farmType').val(data['farmType']);
                        $("#narration").wysihtml5();
                        $('#narration').val(data['detailFarmDescription']);
                        $('#farmID').val(data['farmID']).change();
                        $('#noofvisit').val(data['numberOfVisit']).change();
                        batchMasterID = data['batchMasterID'];

                        setTimeout(function () {
                            $('#batchMasterID').val(batchMasterID);
                        }, 500);
                        get_farmerVisitReport_tableView(farmerVisitID);
                        if (data['numberOfFeeders'] == 1) {
                            $('#numberOfFeedersA').iCheck('check');
                        } else if (data['numberOfFeeders'] == 2) {
                            $('#numberOfFeedersB').iCheck('check');
                        } else if (data['numberOfFeeders'] == 3) {
                            $('#numberOfFeedersC').iCheck('check');
                        }
                        if (data['numberOfDrinkers'] == 1) {
                            $('#numberOfDrinkersA').iCheck('check');
                        } else if (data['numberOfDrinkers'] == 2) {
                            $('#numberOfDrinkersB').iCheck('check');
                        } else if (data['numberOfDrinkers'] == 3) {
                            $('#numberOfDrinkersC').iCheck('check');
                        }
                        if (data['feederHeight'] == 1) {
                            $('#feederHeightA').iCheck('check');
                        } else if (data['feederHeight'] == 2) {
                            $('#feederHeightB').iCheck('check');
                        } else if (data['feederHeight'] == 3) {
                            $('#feederHeightC').iCheck('check');
                        }
                        if (data['drinkerHeight'] == 1) {
                            $('#drinkerHeightA').iCheck('check');
                        } else if (data['drinkerHeight'] == 2) {
                            $('#drinkerHeightB').iCheck('check');
                        } else if (data['drinkerHeight'] == 3) {
                            $('#drinkerHeightC').iCheck('check');
                        }
                        if (data['density'] == 1) {
                            $('#densityA').iCheck('check');
                        } else if (data['density'] == 2) {
                            $('#densityB').iCheck('check');
                        } else if (data['density'] == 3) {
                            $('#densityC').iCheck('check');
                        }
                        if (data['litterQuality'] == 1) {
                            $('#litterQualityA').iCheck('check');
                        } else if (data['litterQuality'] == 2) {
                            $('#litterQualityB').iCheck('check');
                        } else if (data['litterQuality'] == 3) {
                            $('#litterQualityC').iCheck('check');
                        }
                        if (data['smellOfAmonia'] == 1) {
                            $('#smellOfAmoniaA').iCheck('check');
                        } else if (data['smellOfAmonia'] == 2) {
                            $('#smellOfAmoniaB').iCheck('check');
                        } else if (data['smellOfAmonia'] == 3) {
                            $('#smellOfAmoniaC').iCheck('check');
                        }
                        if (data['ventilation'] == 1) {
                            $('#ventilationA').iCheck('check');
                        } else if (data['ventilation'] == 2) {
                            $('#ventilationB').iCheck('check');
                        } else if (data['ventilation'] == 3) {
                            $('#ventilationC').iCheck('check');
                        }
                        if (data['biosecurity'] == 1) {
                            $('#biosecurityA').iCheck('check');
                        } else if (data['biosecurity'] == 2) {
                            $('#biosecurityB').iCheck('check');
                        } else if (data['biosecurity'] == 3) {
                            $('#biosecurityC').iCheck('check');
                        }
                        if (data['recordKeeping'] == 1) {
                            $('#recordKeepingA').iCheck('check');
                        } else if (data['recordKeeping'] == 2) {
                            $('#recordKeepingB').iCheck('check');
                        } else if (data['recordKeeping'] == 3) {
                            $('#recordKeepingC').iCheck('check');
                        }
                        $('#farmVisitReport_detail_div').removeClass('hide');
                        $('[href=#step2]').tab('show');
                        $('a[data-toggle="tab"]').removeClass('btn-primary');
                        $('a[data-toggle="tab"]').addClass('btn-default');
                        $('[href=#step2]').removeClass('btn-default');
                        $('[href=#step2]').addClass('btn-primary');


                    }
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }

    function load_confirmation() {
        if (farmerVisitID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {'farmerVisitID': farmerVisitID, 'html': true},
                url: "<?php echo site_url('Buyback/load_farmVisitReport_confirmation'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#confirm_body').html(data);
                    refreshNotifications(true);
                }, error: function () {
                    stopLoad();
                    alert('An Error Occurred! Please Try Again.');
                    refreshNotifications(true);
                }
            });
        }
    }

    function save_draft() {
        if (farmerVisitID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to save this document!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Save as Draft"
                },
                function () {
                    fetchPage('system/buyback/report/field_officer_report', farmerVisitID, 'Farm Visit Report');
                });
        }
    }

    function fetchFarmerAddress(farmID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {farmID: farmID},
            url: "<?php echo site_url('Buyback/load_farm_header'); ?>",
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $("#farmerAddress").val(data['address']);
                }
            }
        });
    }

    function batchWiseBirds(batchID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {batchID: batchID},
            url: "<?php echo site_url('Buyback/fetch_goodReciptNote_batch_chicks_farmvisit'); ?>",
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $("#noofbirds").val(data);
                }
            }
        });
    }

    function get_farmerVisitReport_tableView(farmerVisitID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {farmerVisitID: farmerVisitID},
            url: "<?php echo site_url('Buyback/load_farmVisitReport_detail_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#farmVisitReport_detail').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function save_farmerVisitReport_detail() {
        var data = $("#technical_data_add_form").serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('Buyback/save_farmVisitReport_detail'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    farmerVisitDetailID = null;
                    $('#technical_data_add_modal').modal('hide');
                    setTimeout(function () {
                        get_farmerVisitReport_tableView(farmerVisitID);
                    }, 300);
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function delete_farmVisitReport_detail(id) {
        if (farmerVisitID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to delete this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'farmerVisitDetailID': id},
                        url: "<?php echo site_url('Buyback/delete_farmVisitReport_detail'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            myAlert('s', 'Record Deleted Successfully');
                            get_farmerVisitReport_tableView(farmerVisitID);
                            refreshNotifications(true);
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function confirmation() {
        if (farmerVisitID) {
            swal({
                    title: "Are you sure?",
                    text: "You want confirm this document!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Confirm"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'farmerVisitID': farmerVisitID},
                        url: "<?php echo site_url('buyback/farmVisitReport_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            myAlert(data[0], data[1]);
                            stopLoad();
                            if (data[0] == 's') {
                                fetchPage('system/buyback/report/field_officer_report', '', 'Farm Visit Report');
                            }
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
    }

    function batchWiseMasterChange(batchMasterID){
        batchWiseBirds(batchMasterID);
        get_first_dispatchDate(batchMasterID);
    }

    function get_first_dispatchDate(batchMasterID){
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {batchMasterID: batchMasterID},
            url: "<?php echo site_url('Buyback/load_buyback_first_dispatchNote_for_fvr'); ?>",
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $("#hatchDate").val(data['documentDate']);
                }
            }
        });
    }


</script>
