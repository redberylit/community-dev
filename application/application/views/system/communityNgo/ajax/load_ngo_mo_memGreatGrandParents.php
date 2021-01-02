<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$this->load->helper('community_ngo_helper');

$memMale_drop = fetch_parentMale_drop();
$memFemale_drop = fetch_parentFemale_drop();
$country_drop = load_country();
$memParentJob = load_memParentJob();
$date_format_policy = date_format_policy();
?>

<style>
    #grandParents-tab .nav-tabs-custom>.nav-tabs>li.active {
        border-top: 0px !important;
    }
</style>

<div id="memGrandParentDiv">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" id="grandParents-tab" style="border: 1px solid rgba(150, 145, 145, 0.21); margin-bottom: 5px;">
            <li class="grand_parents_li active" data-value="ftrSideGrtGrandTab">
                <a href="#ftrSideGrtGrandTab" data-toggle="tab" id="grtFtrGrandTab" onclick="get_grtFtrParent();" aria-expanded="true"> <i class="fa fa-male"></i> <?php echo $this->lang->line('communityngo_fatherSideGrt_grandparent'); ?></a>
            </li>
            <li class="grand_parents_li" data-value="mtrSideGrtGrandTab">
                <a href="#mtrSideGrtGrandTab" data-toggle="tab" id="grtMtrGrandTab" onclick="get_grtMtrParent();" aria-expanded="true"> <i class="fa fa-female"></i> <?php echo $this->lang->line('communityngo_motherSideGrt_grandparent'); ?></a>
                <!--Occupation-->
            </li>
        </ul>

        <div class="tab-content" style="padding-top: 0px;">
            <div class="tab-pane disabled grtFtrGrand active" id="ftrSideGrtGrandTab">
                <?php echo form_open('', 'role="form" id="memGrtGrandPrtFtr_Form"'); ?>
                <div class="" style="" id="">
                    <?php
                    foreach ($mem_grtGrandParents as $key => $gFtr) {
                    ?>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>
                                        <?php echo $this->lang->line('communityngo_com_member_ggFather_Profile'); ?>
                                        <!--g FATHER DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_ggfather'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_grt_memFgrdFatherDel();" id="cf_grt_GdFComMasID" class="form-control select2" name="cf_grt_GdFComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memMale_drop)) {
                                                foreach ($memMale_drop as $val) {

                                                    if ($gFtr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($gFtr['cf_grt_GdFComMasID'] == $val['Com_MasterID']) {
                                            ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>" selected="selected"><?php echo $val['CName_with_initials'] ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>"><?php echo $val['CName_with_initials'] ?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_is_alive'); ?>
                                            <!--is alive--></label>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <select id="cf_grt_GrandFIsAlive" class="form-control select2" name="cf_grt_GrandFIsAlive" onchange="get_grtFtrGPAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveFpg = '';
                                            $notAliveFpg = '';
                                            if ($gFtr['cf_grt_GrandFIsAlive'] == '1') {
                                                $aliveFpg = 'selected="selected"';
                                            } else if ($gFtr['cf_grt_GrandFIsAlive'] == '0') {
                                                $notAliveFpg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveFpg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveFpg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="ftrGrtGPDODDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cf_grt_GrandFDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandFDateOfDeath']; ?>" id="cf_grt_GrandFDateOfDeath" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_name'); ?>
                                            <!--full name--></label>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandFFullName" id="cf_grt_GrandFFullName" class="form-control" value="<?php echo $gFtr['cf_grt_GrandFFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_grt_GrandFDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandFDOB']; ?>" id="cf_grt_GrandFDOB" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                                            <!--Born Country--></label>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cf_grt_GrandFBornCountry" class="form-control select2" name="cf_grt_GrandFBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_grtFtrGPBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($gFtr['cf_grt_GrandFBornCountry'] == $val['countryID']) {
                                            ?>
                                                        <option value="<?php echo $val['countryID'] ?>" selected="selected"><?php echo $val['CountryDes'] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $val['countryID'] ?>"><?php echo $val['CountryDes'] ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityNgo_born_area'); ?>
                                            <!--Born Area--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select name="cf_grt_GrandFBornArea" class="form-control select2" id="cf_grt_GrandFBornArea">
                                            <option value="" selected="selected">Select An Area</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityNgo_bc_no'); ?>
                                            <!--bc NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandFBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $gFtr['cf_grt_GrandFBC_No']; ?>" id="cf_grt_GrandFBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_grt_GrandFBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandFBCDate']; ?>" id="cf_grt_GrandFBCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">

                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_Job'); ?>
                                            <!--FATHER OCCUPATION--></label>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cf_grt_GrandFOccupationId" class="form-control select2" name="cf_grt_GrandFOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valfJob) {
                                                    if ($gFtr['cf_grt_GrandFOccupationId'] == $valfJob['specializationID']) {
                                            ?>
                                                        <option value="<?php echo $valfJob['specializationID']; ?>" selected="selected"><?php echo $valfJob['description']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $valfJob['specializationID']; ?>"><?php echo $valfJob['description']; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_nic'); ?>
                                            <!--NIC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandFNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $gFtr['cf_grt_GrandFNIC_No']; ?>" id="cf_grt_GrandFNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandFDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $gFtr['cf_grt_GrandFDC_No']; ?>" id="cf_grt_GrandFDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_grt_GrandFDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandFDCDate']; ?>" id="cf_grt_GrandFDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2><?php echo $this->lang->line('communityngo_com_member_ggMother_Profile'); ?>
                                        <!--g Mother DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_ggmother'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_grt_memFgrdMotherDel();" id="cf_grt_GdMComMasID" class="form-control select2" name="cf_grt_GdMComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memFemale_drop)) {
                                                foreach ($memFemale_drop as $val) {

                                                    if ($gFtr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($gFtr['cf_grt_GdMComMasID'] == $val['Com_MasterID']) {
                                            ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>" selected="selected"><?php echo $val['CName_with_initials'] ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>"><?php echo $val['CName_with_initials'] ?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_is_alive'); ?>
                                            <!--is alive--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cf_grt_GrandMIsAlive" class="form-control select2" name="cf_grt_GrandMIsAlive" onchange="get_grtFtrGMAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveFg = '';
                                            $notAliveFg = '';
                                            if ($gFtr['cf_grt_GrandMIsAlive'] == '1') {
                                                $aliveFg = 'selected="selected"';
                                            } else if ($gFtr['cf_grt_GrandMIsAlive'] == '0') {
                                                $notAliveFg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveFg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveFg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="ftrGrtGmDODDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cf_grt_GrandMDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandMDateOfDeath']; ?>" id="cf_grt_GrandMDateOfDeath" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_name'); ?>
                                            <!--full name--></label>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandMFullName" id="cf_grt_GrandMFullName" class="form-control" value="<?php echo $gFtr['cf_grt_GrandMFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_grt_GrandMDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandMDOB']; ?>" id="cf_grt_GrandMDOB" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                                            <!--Born Country--></label></div>
                                    <div class="form-group col-sm-4">
                                        <select id="cf_grt_GrandMBornCountry" class="form-control select2" name="cf_grt_GrandMBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_grtFtrGMBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($gFtr['cf_grt_GrandMBornCountry'] == $val['countryID']) {
                                            ?>
                                                        <option value="<?php echo $val['countryID']; ?>" selected="selected"><?php echo $val['CountryDes']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $val['countryID']; ?>"><?php echo $val['CountryDes']; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityNgo_born_area'); ?>
                                            <!--Born Area--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select name="cf_grt_GrandMBornArea" class="form-control select2" id="cf_grt_GrandMBornArea">
                                            <option value="" selected="selected">Select An Area</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_bc_no'); ?>
                                            <!--bc NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandMBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $gFtr['cf_grt_GrandMBC_No']; ?>" id="cf_grt_GrandMBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_grt_GrandMBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandMBCDate']; ?>" id="cf_grt_GrandMBCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">

                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_Job'); ?>
                                            <!--MOTHER OCCUPATION--></label>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cf_grt_GrandMOccupationId" class="form-control select2" name="cf_grt_GrandMOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valmJob) {
                                                    if ($gFtr['cf_grt_GrandMOccupationId'] == $valmJob['specializationID']) {
                                            ?>
                                                        <option value="<?php echo $valmJob['specializationID']; ?>" selected="selected"><?php echo $valmJob['description']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $valmJob['specializationID']; ?>"><?php echo $valmJob['description']; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_nic'); ?>
                                            <!--NIC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandMNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $gFtr['cf_grt_GrandMNIC_No']; ?>" id="cf_grt_GrandMNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_grt_GrandMDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $gFtr['cf_grt_GrandMDC_No']; ?>" id="cf_grt_GrandMDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_grt_GrandMDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gFtr['cf_grt_GrandMDCDate']; ?>" id="cf_grt_GrandMDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                </form>
            </div>
            <div class="tab-pane disabled grtMtrGrand" id="mtrSideGrtGrandTab">
                <?php echo form_open('', 'role="form" id="memGrtGrandPrtMtr_Form"'); ?>
                <div class="tab-pane" id="" style="">
                    <?php
                    foreach ($mem_grtGrandParents as $key => $gMtr) {
                    ?>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>
                                        <?php echo $this->lang->line('communityngo_com_member_ggFather_Profile'); ?>
                                        <!--g FATHER DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_ggfather'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_grt_memMgrdFatherDel();" id="cm_grt_GdFComMasID" class="form-control select2" name="cm_grt_GdFComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memMale_drop)) {
                                                foreach ($memMale_drop as $val) {

                                                    if ($gMtr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($gMtr['cm_grt_GdFComMasID'] == $val['Com_MasterID']) {
                                            ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>" selected="selected"><?php echo $val['CName_with_initials'] ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>"><?php echo $val['CName_with_initials'] ?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_is_alive'); ?>
                                            <!--is alive--></label>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <select id="cm_grt_GrandFIsAlive" class="form-control select2" name="cm_grt_GrandFIsAlive" onchange="get_grtMtrGPAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveMpg = '';
                                            $notAliveMpg = '';
                                            if ($gMtr['cm_grt_GrandFIsAlive'] == '1') {
                                                $aliveMpg = 'selected="selected"';
                                            } else if ($gMtr['cm_grt_GrandFIsAlive'] == '0') {
                                                $notAliveMpg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveMpg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveMpg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="mtrGrtGpDODDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cm_grt_GrandFDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandFDateOfDeath']; ?>" id="cm_grt_GrandFDateOfDeath" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_name'); ?>
                                            <!--full name--></label>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandFFullName" id="cm_grt_GrandFFullName" class="form-control" value="<?php echo $gMtr['cm_grt_GrandFFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_grt_GrandFDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandFDOB']; ?>" id="cm_grt_GrandFDOB" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                                            <!--Born Country--></label>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cm_grt_GrandFBornCountry" class="form-control select2" name="cm_grt_GrandFBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_grtMtrGPBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($gMtr['cm_grt_GrandFBornCountry'] == $val['countryID']) {
                                            ?>
                                                        <option value="<?php echo $val['countryID'] ?>" selected="selected"><?php echo $val['CountryDes'] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $val['countryID'] ?>"><?php echo $val['CountryDes'] ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityNgo_born_area'); ?>
                                            <!--Born Area--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select name="cm_grt_GrandFBornArea" class="form-control select2" id="cm_grt_GrandFBornArea">
                                            <option value="" selected="selected">Select An Area</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityNgo_bc_no'); ?>
                                            <!--bc NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandFBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $gMtr['cm_grt_GrandFBC_No']; ?>" id="cm_grt_GrandFBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_grt_GrandFBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandFBCDate']; ?>" id="cm_grt_GrandFBCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">

                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_Job'); ?>
                                            <!--FATHER OCCUPATION--></label>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cm_grt_GrandFOccupationId" class="form-control select2" name="cm_grt_GrandFOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valfJob) {
                                                    if ($gMtr['cm_grt_GrandFOccupationId'] == $valfJob['specializationID']) {
                                            ?>
                                                        <option value="<?php echo $valfJob['specializationID']; ?>" selected="selected"><?php echo $valfJob['description']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $valfJob['specializationID']; ?>"><?php echo $valfJob['description']; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_nic'); ?>
                                            <!--NIC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandFNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $gMtr['cm_grt_GrandFNIC_No']; ?>" id="cm_grt_GrandFNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandFDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $gMtr['cm_grt_GrandFDC_No']; ?>" id="cm_grt_GrandFDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_grt_GrandFDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandFDCDate']; ?>" id="cm_grt_GrandFDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2><?php echo $this->lang->line('communityngo_com_member_ggMother_Profile'); ?>
                                        <!--g Mother DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_ggmother'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_grt_memMgrdMotherDel();" id="cm_grt_GdMComMasID" class="form-control select2" name="cm_grt_GdMComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memFemale_drop)) {
                                                foreach ($memFemale_drop as $val) {

                                                    if ($gMtr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($gMtr['cm_grt_GdMComMasID'] == $val['Com_MasterID']) {
                                            ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>" selected="selected"><?php echo $val['CName_with_initials'] ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $val['Com_MasterID'] ?>"><?php echo $val['CName_with_initials'] ?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_is_alive'); ?>
                                            <!--is alive--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cm_grt_GrandMIsAlive" class="form-control select2" name="cm_grt_GrandMIsAlive" onchange="get_grtMtrGMAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveMmg = '';
                                            $notAliveMmg = '';
                                            if ($gMtr['cm_grt_GrandMIsAlive'] == '1') {
                                                $aliveMmg = 'selected="selected"';
                                            } else if ($gMtr['cm_grt_GrandMIsAlive'] == '0') {
                                                $notAliveMmg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveMmg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveMmg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="mtrGrtGmDODDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cm_grt_GrandMDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandMDateOfDeath']; ?>" id="cm_grt_GrandMDateOfDeath" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_name'); ?>
                                            <!--full name--></label>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandMFullName" id="cm_grt_GrandMFullName" class="form-control" value="<?php echo $gMtr['cm_grt_GrandMFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_grt_GrandMDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandMDOB']; ?>" id="cm_grt_GrandMDOB" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                                            <!--Born Country--></label></div>
                                    <div class="form-group col-sm-4">
                                        <select id="cm_grt_GrandMBornCountry" class="form-control select2" name="cm_grt_GrandMBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_grtMtrGMBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($gMtr['cm_grt_GrandMBornCountry'] == $val['countryID']) {
                                            ?>
                                                        <option value="<?php echo $val['countryID']; ?>" selected="selected"><?php echo $val['CountryDes']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $val['countryID']; ?>"><?php echo $val['CountryDes']; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityNgo_born_area'); ?>
                                            <!--Born Area--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select name="cm_grt_GrandMBornArea" class="form-control select2" id="cm_grt_GrandMBornArea">
                                            <option value="" selected="selected">Select An Area</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_bc_no'); ?>
                                            <!--bc NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandMBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $gMtr['cm_grt_GrandMBC_No']; ?>" id="cm_grt_GrandMBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_grt_GrandMBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandMBCDate']; ?>" id="cm_grt_GrandMBCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">

                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_Job'); ?>
                                            <!--MOTHER OCCUPATION--></label>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select id="cm_grt_GrandMOccupationId" class="form-control select2" name="cm_grt_GrandMOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valmJob) {
                                                    if ($gMtr['cm_grt_GrandMOccupationId'] == $valmJob['specializationID']) {
                                            ?>
                                                        <option value="<?php echo $valmJob['specializationID']; ?>" selected="selected"><?php echo $valmJob['description']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $valmJob['specializationID']; ?>"><?php echo $valmJob['description']; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_nic'); ?>
                                            <!--NIC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandMNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $gMtr['cm_grt_GrandMNIC_No']; ?>" id="cm_grt_GrandMNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_grt_GrandMDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $gMtr['cm_grt_GrandMDC_No']; ?>" id="cm_grt_GrandMDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_grt_GrandMDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $gMtr['cm_grt_GrandMDCDate']; ?>" id="cm_grt_GrandMDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function(ev) {});

        $('.select2').select2();

        get_grtFtrGPAliveStatus();
        get_grtFtrGPBornArea();
        get_grtFtrGMAliveStatus();
        get_grtFtrGMBornArea();
        get_grtMtrGPAliveStatus();
        get_grtMtrGPBornArea();
        get_grtMtrGMAliveStatus();
        get_grtMtrGMBornArea();
        get_grtFtrParent();
        get_grt_memFgrdFatherDel();
        get_grt_memFgrdMotherDel();
        get_grt_memMgrdFatherDel();
        get_grt_memMgrdMotherDel();
    });

    function get_grtFtrParent() {
        document.getElementById('saveGMP_btn').style.display = 'none';
        document.getElementById('saveGFP_btn').style.display = 'block';
    }

    function get_grtMtrParent() {
        document.getElementById('saveGFP_btn').style.display = 'none';
        document.getElementById('saveGMP_btn').style.display = 'block';
    }

    function fetch_grtGPrtDetails(tab) {

        if (Com_MasterID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {
                    'Com_MasterID': Com_MasterID
                },
                url: '<?php echo site_url("CommunityNgo/load_grt_GrandParentDetails_view"); ?>',
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    stopLoad();
                    //  $('#memGrandParentDiv').html(data);

                    if (tab == 'mtrSideGrtGrandTab') {
                        $('#grtMtrGrandTab').tab('show');
                        $('[href=#mtrSideGrtGrandTab]').addClass('active');
                        $('.grtMtrGrand').addClass('active');
                        get_grtMtrGPAliveStatus();
                        get_grtMtrGPBornArea();
                        get_grtMtrGMAliveStatus();
                        get_grtMtrGMBornArea();
                    } else {
                        $('#grtFtrGrandTab').tab('show');
                        $('[href=#ftrSideGrtGrandTab]').addClass('active');
                        $('.grtFtrGrand').addClass('active');
                        get_grtFtrGPAliveStatus();
                        get_grtFtrGPBornArea();
                        get_grtFtrGMAliveStatus();
                        get_grtFtrGMBornArea();
                    }

                },
                error: function() {
                    myAlert('e', 'An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }
    }

    function get_grtFtrGPAliveStatus() {

        var cf_grt_GrandFIsAlive = document.getElementById('cf_grt_GrandFIsAlive').value;

        if (cf_grt_GrandFIsAlive == '0') {
            document.getElementById('ftrGrtGPDODDiv').style.display = 'block';
            $('#cf_grt_GrandFDC_No').prop('disabled', false);
            $('#cf_grt_GrandFDCDate').prop('disabled', false);
        } else {
            document.getElementById('ftrGrtGPDODDiv').style.display = 'none';
            $('#cf_grt_GrandFDC_No').prop('disabled', true);
            $('#cf_grt_GrandFDCDate').prop('disabled', true);

        }

    }

    function get_grtFtrGPBornArea() {

        var bornConutryId = document.getElementById('cf_grt_GrandFBornCountry').value;
        var areaFor = 'gftrGftr';
        var bldComMasID = document.getElementById('cf_grt_GdFComMasID').value;

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                bornConutryId: bornConutryId,
                'Com_MasterID': Com_MasterID,
                'areaFor': areaFor,
                'bldComMasID': bldComMasID
            },
            url: "<?php echo site_url('CommunityNgo/fetch_country_based_Area_Dropdown'); ?>",

            success: function(data) {
                $('#cf_grt_GrandFBornArea').html(data);
                $('#memGrtGrandPrtFtr_Form').data('bootstrapValidator').resetField($('#cf_grt_GrandFBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_grtFtrGMAliveStatus() {

        var cf_grt_GrandMIsAlive = document.getElementById('cf_grt_GrandMIsAlive').value;

        if (cf_grt_GrandMIsAlive == '0') {
            document.getElementById('ftrGrtGmDODDiv').style.display = 'block';
            $('#cf_grt_GrandMDC_No').prop('disabled', false);
            $('#cf_grt_GrandMDCDate').prop('disabled', false);
        } else {
            document.getElementById('ftrGrtGmDODDiv').style.display = 'none';
            $('#cf_grt_GrandMDC_No').prop('disabled', true);
            $('#cf_grt_GrandMDCDate').prop('disabled', true);
        }

    }

    function get_grtFtrGMBornArea() {

        var bornConutryId = document.getElementById('cf_grt_GrandMBornCountry').value;
        var areaFor = 'gftrGmtr';
        var bldComMasID = document.getElementById('cf_grt_GdMComMasID').value;

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                bornConutryId: bornConutryId,
                'Com_MasterID': Com_MasterID,
                'areaFor': areaFor,
                'bldComMasID': bldComMasID
            },
            url: "<?php echo site_url('CommunityNgo/fetch_country_based_Area_Dropdown'); ?>",

            success: function(data) {
                $('#cf_grt_GrandMBornArea').html(data);
                $('#memGrtGrandPrtFtr_Form').data('bootstrapValidator').resetField($('#cf_grt_GrandMBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_grtMtrGPAliveStatus() {

        var cm_grt_GrandFIsAlive = document.getElementById('cm_grt_GrandFIsAlive').value;

        if (cm_grt_GrandFIsAlive == '0') {
            document.getElementById('mtrGrtGpDODDiv').style.display = 'block';
            $('#cm_grt_GrandFDC_No').prop('disabled', false);
            $('#cm_grt_GrandFDCDate').prop('disabled', false);
        } else {
            document.getElementById('mtrGrtGpDODDiv').style.display = 'none';
            $('#cm_grt_GrandFDC_No').prop('disabled', true);
            $('#cm_grt_GrandFDCDate').prop('disabled', true);
        }

    }

    function get_grtMtrGPBornArea() {

        var bornConutryId = document.getElementById('cm_grt_GrandFBornCountry').value;
        var areaFor = 'gmtrGftr';
        var bldComMasID = document.getElementById('cm_grt_GdFComMasID').value;

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                bornConutryId: bornConutryId,
                'Com_MasterID': Com_MasterID,
                'areaFor': areaFor,
                'bldComMasID': bldComMasID
            },
            url: "<?php echo site_url('CommunityNgo/fetch_country_based_Area_Dropdown'); ?>",

            success: function(data) {
                $('#cm_grt_GrandFBornArea').html(data);
                $('#memGrtGrandPrtMtr_Form').data('bootstrapValidator').resetField($('#cm_grt_GrandFBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_grtMtrGMAliveStatus() {

        var cm_grt_GrandMIsAlive = document.getElementById('cm_grt_GrandMIsAlive').value;

        if (cm_grt_GrandMIsAlive == '0') {
            document.getElementById('mtrGrtGmDODDiv').style.display = 'block';
            $('#cm_grt_GrandMDC_No').prop('disabled', false);
            $('#cm_grt_GrandMDCDate').prop('disabled', false);
        } else {
            document.getElementById('mtrGrtGmDODDiv').style.display = 'none';
            $('#cm_grt_GrandMDC_No').prop('disabled', true);
            $('#cm_grt_GrandMDCDate').prop('disabled', true);
        }

    }

    function get_grtMtrGMBornArea() {

        var bornConutryId = document.getElementById('cm_grt_GrandMBornCountry').value;
        var areaFor = 'gmtrGmtr';
        var bldComMasID = document.getElementById('cm_grt_GdMComMasID').value;

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                bornConutryId: bornConutryId,
                'Com_MasterID': Com_MasterID,
                'areaFor': areaFor,
                'bldComMasID': bldComMasID
            },
            url: "<?php echo site_url('CommunityNgo/fetch_country_based_Area_Dropdown'); ?>",

            success: function(data) {
                $('#cm_grt_GrandMBornArea').html(data);
                $('#memGrtGrandPrtMtr_Form').data('bootstrapValidator').resetField($('#cm_grt_GrandMBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_grt_memFgrdFatherDel() {
        var cf_grt_GdFComMasID = document.getElementById('cf_grt_GdFComMasID').value;

        if (cf_grt_GdFComMasID == "" || cf_grt_GdFComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cf_grt_GdFComMasID
                },
                success: function(datum) {
                    $('#cf_grt_GrandFFullName').val(datum.name).prop("disabled", true);
                    $('#cf_grt_GrandFDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cf_grt_GrandFBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cf_grt_GrandFBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cf_grt_GrandFBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cf_grt_GrandFBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cf_grt_GrandFOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cf_grt_GrandFNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }

    function get_grt_memFgrdMotherDel() {
        var cf_grt_GdMComMasID = document.getElementById('cf_grt_GdMComMasID').value;

        if (cf_grt_GdMComMasID == "" || cf_grt_GdMComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cf_grt_GdMComMasID
                },
                success: function(datum) {
                    $('#cf_grt_GrandMFullName').val(datum.name).prop("disabled", true);
                    $('#cf_grt_GrandMDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cf_grt_GrandMBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cf_grt_GrandMBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cf_grt_GrandMBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cf_grt_GrandMBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cf_grt_GrandMOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cf_grt_GrandMNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }

    function get_grt_memMgrdFatherDel() {
        var cm_grt_GdFComMasID = document.getElementById('cm_grt_GdFComMasID').value;

        if (cm_grt_GdFComMasID == "" || cm_grt_GdFComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cm_grt_GdFComMasID
                },
                success: function(datum) {
                    $('#cm_grt_GrandFFullName').val(datum.name).prop("disabled", true);
                    $('#cm_grt_GrandFDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cm_grt_GrandFBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cm_grt_GrandFBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cm_grt_GrandFBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cm_grt_GrandFBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cm_grt_GrandFOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cm_grt_GrandFNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }

    function get_grt_memMgrdMotherDel() {
        var cm_grt_GdMComMasID = document.getElementById('cm_grt_GdMComMasID').value;

        if (cm_grt_GdMComMasID == "" || cm_grt_GdMComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cm_grt_GdMComMasID
                },
                success: function(datum) {
                    $('#cm_grt_GrandMFullName').val(datum.name).prop("disabled", true);
                    $('#cm_grt_GrandMDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cm_grt_GrandMBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cm_grt_GrandMBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cm_grt_GrandMBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cm_grt_GrandMBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cm_grt_GrandMOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cm_grt_GrandMNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }
</script>


<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 07/24/2020
 * Time: 12:32 PM
 */
