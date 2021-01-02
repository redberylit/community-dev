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
            <li class="grand_parents_li active" data-value="ftrSideGrandTab">
                <a href="#ftrSideGrandTab" data-toggle="tab" id="ftrGrandTab" onclick="get_ftrParent();" aria-expanded="true"> <i class="fa fa-male"></i> <?php echo $this->lang->line('communityngo_fatherSide_grandparent'); ?></a>
            </li>
            <li class="grand_parents_li" data-value="mtrSideGrandTab">
                <a href="#mtrSideGrandTab" data-toggle="tab" id="mtrGrandTab" onclick="get_mtrParent();" aria-expanded="true"> <i class="fa fa-female"></i> <?php echo $this->lang->line('communityngo_motherSide_grandparent'); ?></a>
                <!--Occupation-->
            </li>
        </ul>

        <div class="tab-content" style="padding-top: 0px;">
            <div class="tab-pane disabled ftrGrand active" id="ftrSideGrandTab">
                <?php echo form_open('', 'role="form" id="memGrandPrtFtr_Form"'); ?>
                <div class="" style="" id="">
                    <?php
                    $fgp = 1;
                    foreach ($memGrandParents as $key => $ftr) {
                    ?>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>
                                        <?php echo $this->lang->line('communityngo_com_member_gFather_Profile'); ?>
                                        <!--g FATHER DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_gfather'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_memFgrdFatherDel();" id="cf_GdFComMasID" class="form-control select2" name="cf_GdFComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memMale_drop)) {
                                                foreach ($memMale_drop as $val) {

                                                    if ($ftr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($ftr['cf_GdFComMasID'] == $val['Com_MasterID']) {
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
                                        <select id="cf_GrandFIsAlive" class="form-control select2" name="cf_GrandFIsAlive" onchange="get_ftrGPAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveFpg = '';
                                            $notAliveFpg = '';
                                            if ($ftr['cf_GrandFIsAlive'] == '1') {
                                                $aliveFpg = 'selected="selected"';
                                            } else if ($ftr['cf_GrandFIsAlive'] == '0') {
                                                $notAliveFpg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveFpg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveFpg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="ftrGPDateOfDeathDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cf_GrandFDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandFDateOfDeath']; ?>" id="cf_GrandFDateOfDeath" class="form-control">
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
                                        <input type="text" name="cf_GrandFFullName" id="cf_GrandFFullName" class="form-control" value="<?php echo $ftr['cf_GrandFFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_GrandFDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandFDOB']; ?>" id="cf_GrandFDOB" class="form-control">
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
                                        <select id="cf_GrandFBornCountry" class="form-control select2" name="cf_GrandFBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_ftrGPBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($ftr['cf_GrandFBornCountry'] == $val['countryID']) {
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
                                        <select name="cf_GrandFBornArea" class="form-control select2" id="cf_GrandFBornArea">
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
                                        <input type="text" name="cf_GrandFBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $ftr['cf_GrandFBC_No']; ?>" id="cf_GrandFBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_GrandFBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandFBCDate']; ?>" id="cf_GrandFBCDate" class="form-control">
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
                                        <select id="cf_GrandFOccupationId" class="form-control select2" name="cf_GrandFOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valfJob) {
                                                    if ($ftr['cf_GrandFOccupationId'] == $valfJob['specializationID']) {
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
                                        <input type="text" name="cf_GrandFNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $ftr['cf_GrandFNIC_No']; ?>" id="cf_GrandFNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_GrandFDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $ftr['cf_GrandFDC_No']; ?>" id="cf_GrandFDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_GrandFDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandFDCDate']; ?>" id="cf_GrandFDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2><?php echo $this->lang->line('communityngo_com_member_gMother_Profile'); ?>
                                        <!--g Mother DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_gmother'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_memFgrdMotherDel();" id="cf_GdMComMasID" class="form-control select2" name="cf_GdMComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memFemale_drop)) {
                                                foreach ($memFemale_drop as $val) {

                                                    if ($ftr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($ftr['cf_GdMComMasID'] == $val['Com_MasterID']) {
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
                                        <select id="cf_GrandMIsAlive" class="form-control select2" name="cf_GrandMIsAlive" onchange="get_ftrGMAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveFg = '';
                                            $notAliveFg = '';
                                            if ($ftr['cf_GrandMIsAlive'] == '1') {
                                                $aliveFg = 'selected="selected"';
                                            } else if ($ftr['cf_GrandMIsAlive'] == '0') {
                                                $notAliveFg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveFg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveFg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="ftrGMDateOfDeathDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cf_GrandMDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandMDateOfDeath']; ?>" id="cf_GrandMDateOfDeath" class="form-control">
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
                                        <input type="text" name="cf_GrandMFullName" id="cf_GrandMFullName" class="form-control" value="<?php echo $ftr['cf_GrandMFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_GrandMDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandMDOB']; ?>" id="cf_GrandMDOB" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                                            <!--Born Country--></label></div>
                                    <div class="form-group col-sm-4">
                                        <select id="cf_GrandMBornCountry" class="form-control select2" name="cf_GrandMBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_ftrGMBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($ftr['cf_GrandMBornCountry'] == $val['countryID']) {
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
                                        <select name="cf_GrandMBornArea" class="form-control select2" id="cf_GrandMBornArea">
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
                                        <input type="text" name="cf_GrandMBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $ftr['cf_GrandMBC_No']; ?>" id="cf_GrandMBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_GrandMBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandMBCDate']; ?>" id="cf_GrandMBCDate" class="form-control">
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
                                        <select id="cf_GrandMOccupationId" class="form-control select2" name="cf_GrandMOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valmJob) {
                                                    if ($ftr['cf_GrandMOccupationId'] == $valmJob['specializationID']) {
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
                                        <input type="text" name="cf_GrandMNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $ftr['cf_GrandMNIC_No']; ?>" id="cf_GrandMNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cf_GrandMDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $ftr['cf_GrandMDC_No']; ?>" id="cf_GrandMDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cf_GrandMDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $ftr['cf_GrandMDCDate']; ?>" id="cf_GrandMDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $fgp++;
                    }
                    ?>
                </div>
                </form>
            </div>
            <div class="tab-pane disabled mtrGrand" id="mtrSideGrandTab">
                <?php echo form_open('', 'role="form" id="memGrandPrtMtr_Form"'); ?>
                <div class="tab-pane" id="" style="">
                    <?php
                    $fgm = 1;
                    foreach ($memGrandParents as $key => $mtr) {
                    ?>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2>
                                        <?php echo $this->lang->line('communityngo_com_member_gFather_Profile'); ?>
                                        <!--g FATHER DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_gfather'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_memMgrdFatherDel();" id="cm_GdFComMasID" class="form-control select2" name="cm_GdFComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memMale_drop)) {
                                                foreach ($memMale_drop as $val) {

                                                    if ($mtr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($mtr['cm_GdFComMasID'] == $val['Com_MasterID']) {
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
                                        <select id="cm_GrandFIsAlive" class="form-control select2" name="cm_GrandFIsAlive" onchange="get_mtrGPAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveMpg = '';
                                            $notAliveMpg = '';
                                            if ($mtr['cm_GrandFIsAlive'] == '1') {
                                                $aliveMpg = 'selected="selected"';
                                            } else if ($mtr['cm_GrandFIsAlive'] == '0') {
                                                $notAliveMpg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveMpg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveMpg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="mtrGPDateOfDeathDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cm_GrandFDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandFDateOfDeath']; ?>" id="cm_GrandFDateOfDeath" class="form-control">
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
                                        <input type="text" name="cm_GrandFFullName" id="cm_GrandFFullName" class="form-control" value="<?php echo $mtr['cm_GrandFFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_GrandFDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandFDOB']; ?>" id="cm_GrandFDOB" class="form-control">
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
                                        <select id="cm_GrandFBornCountry" class="form-control select2" name="cm_GrandFBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_mtrGPBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($mtr['cm_GrandFBornCountry'] == $val['countryID']) {
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
                                        <select name="cm_GrandFBornArea" class="form-control select2" id="cm_GrandFBornArea">
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
                                        <input type="text" name="cm_GrandFBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $mtr['cm_GrandFBC_No']; ?>" id="cm_GrandFBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_GrandFBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandFBCDate']; ?>" id="cm_GrandFBCDate" class="form-control">
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
                                        <select id="cm_GrandFOccupationId" class="form-control select2" name="cm_GrandFOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valfJob) {
                                                    if ($mtr['cm_GrandFOccupationId'] == $valfJob['specializationID']) {
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
                                        <input type="text" name="cm_GrandFNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $mtr['cm_GrandFNIC_No']; ?>" id="cm_GrandFNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_GrandFDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $mtr['cm_GrandFDC_No']; ?>" id="cm_GrandFDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_GrandFDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandFDCDate']; ?>" id="cm_GrandFDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 animated zoomIn">
                                <header class="head-title">
                                    <h2><?php echo $this->lang->line('communityngo_com_member_gMother_Profile'); ?>
                                        <!--g Mother DETAILS HEADER-->
                                    </h2>
                                </header>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_gmother'); ?>
                                            <!--Member--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <select onchange="get_memMgrdMotherDel();" id="cm_GdMComMasID" class="form-control select2" name="cm_GdMComMasID">
                                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                                            <?php
                                            if (!empty($memFemale_drop)) {
                                                foreach ($memFemale_drop as $val) {

                                                    if ($mtr['Com_MasterID'] != $val['Com_MasterID']) {
                                                        if ($mtr['cm_GdMComMasID'] == $val['Com_MasterID']) {
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
                                        <select id="cm_GrandMIsAlive" class="form-control select2" name="cm_GrandMIsAlive" onchange="get_mtrGMAliveStatus();">
                                            <option value=""></option>
                                            <?php $aliveMmg = '';
                                            $notAliveMmg = '';
                                            if ($mtr['cm_GrandMIsAlive'] == '1') {
                                                $aliveMmg = 'selected="selected"';
                                            } else if ($mtr['cm_GrandMIsAlive'] == '0') {
                                                $notAliveMmg = 'selected="selected"';
                                            } ?>
                                            <option value="1" <?php echo $aliveMmg; ?>>Yes</option>
                                            <option value="0" <?php echo $notAliveMmg; ?>>No</option>
                                        </select>
                                    </div>
                                    <div id="mtrGMDateOfDeathDiv" style="display: none;">
                                        <div class="form-group col-sm-2">
                                            <label class="title">
                                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                                <!--DOD --></label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group datepic">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" name="cm_GrandMDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandMDateOfDeath']; ?>" id="cm_GrandMDateOfDeath" class="form-control">
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
                                        <input type="text" name="cm_GrandMFullName" id="cm_GrandMFullName" class="form-control" value="<?php echo $mtr['cm_GrandMFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityngo_dob'); ?>
                                            <!--Date of Birth--></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_GrandMDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandMDOB']; ?>" id="cm_GrandMDOB" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                                            <!--Born Country--></label></div>
                                    <div class="form-group col-sm-4">
                                        <select id="cm_GrandMBornCountry" class="form-control select2" name="cm_GrandMBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_mtrGMBornArea();">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($country_drop)) {
                                                foreach ($country_drop as $val) {
                                                    if ($mtr['cm_GrandMBornCountry'] == $val['countryID']) {
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
                                        <select name="cm_GrandMBornArea" class="form-control select2" id="cm_GrandMBornArea">
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
                                        <input type="text" name="cm_GrandMBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $mtr['cm_GrandMBC_No']; ?>" id="cm_GrandMBC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_GrandMBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandMBCDate']; ?>" id="cm_GrandMBCDate" class="form-control">
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
                                        <select id="cm_GrandMOccupationId" class="form-control select2" name="cm_GrandMOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($memParentJob)) {
                                                foreach ($memParentJob as $valmJob) {
                                                    if ($mtr['cm_GrandMOccupationId'] == $valmJob['specializationID']) {
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
                                        <input type="text" name="cm_GrandMNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $mtr['cm_GrandMNIC_No']; ?>" id="cm_GrandMNIC_No" class="form-control">
                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                                            <!--DC NO--><label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input type="text" name="cm_GrandMDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $mtr['cm_GrandMDC_No']; ?>" id="cm_GrandMDC_No" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="title">
                                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                                            <!--reg Date --></label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group datepic">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="cm_GrandMDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $mtr['cm_GrandMDCDate']; ?>" id="cm_GrandMDCDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $fgm++;
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

        get_ftrGPAliveStatus();
        get_ftrGPBornArea();
        get_ftrGMAliveStatus();
        get_ftrGMBornArea();
        get_mtrGPAliveStatus();
        get_mtrGPBornArea();
        get_mtrGMAliveStatus();
        get_mtrGMBornArea();
        get_ftrParent();
        get_memFgrdFatherDel();
        get_memFgrdMotherDel();
        get_memMgrdFatherDel();
        get_memMgrdMotherDel();
    });

    function get_ftrParent() {
        document.getElementById('saveMP_btn').style.display = 'none';
        document.getElementById('saveFP_btn').style.display = 'block';
    }

    function get_mtrParent() {
        document.getElementById('saveFP_btn').style.display = 'none';
        document.getElementById('saveMP_btn').style.display = 'block';
    }

    function fetch_gPrtDetails(tab) {

        if (Com_MasterID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {
                    'Com_MasterID': Com_MasterID
                },
                url: '<?php echo site_url("CommunityNgo/load_grandParentDetails_view"); ?>',
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    stopLoad();
                    //  $('#memGrandParentDiv').html(data);

                    if (tab == 'mtrSideGrandTab') {
                        $('#mtrGrandTab').tab('show');
                        $('[href=#mtrSideGrandTab]').addClass('active');
                        $('.mtrGrand').addClass('active');
                        get_mtrGPAliveStatus();
                        get_mtrGPBornArea();
                        get_mtrGMAliveStatus();
                        get_mtrGMBornArea();
                    } else {
                        $('#ftrGrandTab').tab('show');
                        $('[href=#ftrSideGrandTab]').addClass('active');
                        $('.ftrGrand').addClass('active');
                        get_ftrGPAliveStatus();
                        get_ftrGPBornArea();
                        get_ftrGMAliveStatus();
                        get_ftrGMBornArea();
                    }

                },
                error: function() {
                    myAlert('e', 'An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }
    }

    function get_ftrGPAliveStatus() {

        var cf_GrandFIsAlive = document.getElementById('cf_GrandFIsAlive').value;

        if (cf_GrandFIsAlive == '0') {
            document.getElementById('ftrGPDateOfDeathDiv').style.display = 'block';
            $('#cf_GrandFDC_No').prop('disabled', false);
            $('#cf_GrandFDCDate').prop('disabled', false);
        } else {
            document.getElementById('ftrGPDateOfDeathDiv').style.display = 'none';
            $('#cf_GrandFDC_No').prop('disabled', true);
            $('#cf_GrandFDCDate').prop('disabled', true);

        }

    }

    function get_ftrGPBornArea() {

        var bornConutryId = document.getElementById('cf_GrandFBornCountry').value;
        var areaFor = 'ftrGftr';
        var bldComMasID = document.getElementById('cf_GdFComMasID').value;

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
                $('#cf_GrandFBornArea').html(data);
                $('#memGrandPrtFtr_Form').data('bootstrapValidator').resetField($('#cf_GrandFBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_ftrGMAliveStatus() {

        var cf_GrandMIsAlive = document.getElementById('cf_GrandMIsAlive').value;

        if (cf_GrandMIsAlive == '0') {
            document.getElementById('ftrGMDateOfDeathDiv').style.display = 'block';
            $('#cf_GrandMDC_No').prop('disabled', false);
            $('#cf_GrandMDCDate').prop('disabled', false);
        } else {
            document.getElementById('ftrGMDateOfDeathDiv').style.display = 'none';
            $('#cf_GrandMDC_No').prop('disabled', true);
            $('#cf_GrandMDCDate').prop('disabled', true);
        }

    }

    function get_ftrGMBornArea() {

        var bornConutryId = document.getElementById('cf_GrandMBornCountry').value;
        var areaFor = 'ftrGmtr';
        var bldComMasID = document.getElementById('cf_GdMComMasID').value;

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
                $('#cf_GrandMBornArea').html(data);
                $('#memGrandPrtFtr_Form').data('bootstrapValidator').resetField($('#cf_GrandMBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_mtrGPAliveStatus() {

        var cm_GrandFIsAlive = document.getElementById('cm_GrandFIsAlive').value;

        if (cm_GrandFIsAlive == '0') {
            document.getElementById('mtrGPDateOfDeathDiv').style.display = 'block';
            $('#cm_GrandFDC_No').prop('disabled', false);
            $('#cm_GrandFDCDate').prop('disabled', false);
        } else {
            document.getElementById('mtrGPDateOfDeathDiv').style.display = 'none';
            $('#cm_GrandFDC_No').prop('disabled', true);
            $('#cm_GrandFDCDate').prop('disabled', true);
        }

    }

    function get_mtrGPBornArea() {

        var bornConutryId = document.getElementById('cm_GrandFBornCountry').value;
        var areaFor = 'mtrGftr';
        var bldComMasID = document.getElementById('cm_GdFComMasID').value;

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
                $('#cm_GrandFBornArea').html(data);
                $('#memGrandPrtMtr_Form').data('bootstrapValidator').resetField($('#cm_GrandFBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_mtrGMAliveStatus() {

        var cm_GrandMIsAlive = document.getElementById('cm_GrandMIsAlive').value;

        if (cm_GrandMIsAlive == '0') {
            document.getElementById('mtrGMDateOfDeathDiv').style.display = 'block';
            $('#cm_GrandMDC_No').prop('disabled', false);
            $('#cm_GrandMDCDate').prop('disabled', false);
        } else {
            document.getElementById('mtrGMDateOfDeathDiv').style.display = 'none';
            $('#cm_GrandMDC_No').prop('disabled', true);
            $('#cm_GrandMDCDate').prop('disabled', true);
        }

    }

    function get_mtrGMBornArea() {

        var bornConutryId = document.getElementById('cm_GrandMBornCountry').value;
        var areaFor = 'mtrGmtr';
        var bldComMasID = document.getElementById('cm_GdMComMasID').value;

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
                $('#cm_GrandMBornArea').html(data);
                $('#memGrandPrtMtr_Form').data('bootstrapValidator').resetField($('#cm_GrandMBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_memFgrdFatherDel() {
        var cf_GdFComMasID = document.getElementById('cf_GdFComMasID').value;

        if (cf_GdFComMasID == "" || cf_GdFComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cf_GdFComMasID
                },
                success: function(datum) {
                    $('#cf_GrandFFullName').val(datum.name).prop("disabled", true);
                    $('#cf_GrandFDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cf_GrandFBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cf_GrandFBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cf_GrandFBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cf_GrandFBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cf_GrandFOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cf_GrandFNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }

    function get_memFgrdMotherDel() {
        var cf_GdMComMasID = document.getElementById('cf_GdMComMasID').value;

        if (cf_GdMComMasID == "" || cf_GdMComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cf_GdMComMasID
                },
                success: function(datum) {
                    $('#cf_GrandMFullName').val(datum.name).prop("disabled", true);
                    $('#cf_GrandMDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cf_GrandMBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cf_GrandMBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cf_GrandMBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cf_GrandMBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cf_GrandMOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cf_GrandMNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }

    function get_memMgrdFatherDel() {
        var cm_GdFComMasID = document.getElementById('cm_GdFComMasID').value;

        if (cm_GdFComMasID == "" || cm_GdFComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cm_GdFComMasID
                },
                success: function(datum) {
                    $('#cm_GrandFFullName').val(datum.name).prop("disabled", true);
                    $('#cm_GrandFDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cm_GrandFBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cm_GrandFBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cm_GrandFBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cm_GrandFBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cm_GrandFOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cm_GrandFNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }

    function get_memMgrdMotherDel() {
        var cm_GdMComMasID = document.getElementById('cm_GdMComMasID').value;

        if (cm_GdMComMasID == "" || cm_GdMComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cm_GdMComMasID
                },
                success: function(datum) {
                    $('#cm_GrandMFullName').val(datum.name).prop("disabled", true);
                    $('#cm_GrandMDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cm_GrandMBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cm_GrandMBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cm_GrandMBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cm_GrandMBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cm_GrandMOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cm_GrandMNIC_No').val(datum.CNIC_No).prop("disabled", true);
                }
            });
        }
    }
</script>


<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 07/22/2020
 * Time: 12:15 PM
 */
