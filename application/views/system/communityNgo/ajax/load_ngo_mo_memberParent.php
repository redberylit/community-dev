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
<div id="memParentDiv">
    <?php
    foreach ($memberParent as $key => $memPrt) {
    ?>
        <?php echo form_open('', 'role="form" id="CommunityParent_Form"'); ?>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2><?php echo $this->lang->line('communityngo_com_member_father_Profile'); ?>
                        <!--FATHER DETAILS HEADER-->
                    </h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_father'); ?>
                            <!--Member--></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <select onchange="get_comMemFatherDel();" id="cfComMasID" class="form-control select2" name="cfComMasID">
                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                            <?php

                            if (!empty($memMale_drop)) {
                                foreach ($memMale_drop as $val) {

                                    if ($memPrt['Com_MasterID'] != $val['Com_MasterID']) {
                                        if ($memPrt['cfComMasID'] == $val['Com_MasterID']) {
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
                        <select id="fatherIsAlive" class="form-control select2" name="fatherIsAlive" onchange="get_ftrAliveStatus();">
                            <option value=""></option>
                            <?php $aliveFg = '';
                            $notAliveFg = '';
                            if ($memPrt['fatherIsAlive'] == '1') {
                                $aliveFg = 'selected="selected"';
                            } else if ($memPrt['fatherIsAlive'] == '0') {
                                $notAliveFg = 'selected="selected"';
                            } ?>
                            <option value="1" <?php echo $aliveFg; ?>>Yes</option>
                            <option value="0" <?php echo $notAliveFg; ?>>No</option>
                        </select>
                    </div>
                    <div id="ftrDateOfDeathDiv" style="display: none;">
                        <div class="form-group col-sm-2">
                            <label class="title">
                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                <!--DOD --></label>
                        </div>
                        <div class="form-group col-sm-4">
                            <div class="input-group datepic">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="cfDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cfDateOfDeath']; ?>" id="cfDateOfDeath" class="form-control">
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
                        <input type="text" name="cfFullName" id="cfFullName" class="form-control" value="<?php echo $memPrt['cfFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityngo_dob'); ?>
                            <!--Date of Birth--></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="cFatherDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cFatherDOB']; ?>" id="cFatherDOB" class="form-control">
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
                        <select id="cfBornCountry" class="form-control select2" name="cfBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_fatherBornArea();">
                            <option value=""></option>
                            <?php
                            if (!empty($country_drop)) {
                                foreach ($country_drop as $val) {
                                    if ($memPrt['cfBornCountry'] == $val['countryID']) {
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
                        <select name="cfBornArea" class="form-control select2" id="cfBornArea">
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
                        <input type="text" name="cfBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $memPrt['cfBC_No']; ?>" id="cfBC_No" class="form-control">
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                            <!--reg Date --></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="cfBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cfBCDate']; ?>" id="cfBCDate" class="form-control">
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
                        <select id="cfOccupationId" class="form-control select2" name="cfOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                            <option value=""></option>
                            <?php
                            if (!empty($memParentJob)) {
                                foreach ($memParentJob as $valCJob) {
                                    if ($memPrt['cfOccupationId'] == $valCJob['specializationID']) {
                            ?>
                                        <option value="<?php echo $valCJob['specializationID']; ?>" selected="selected"><?php echo $valCJob['description']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $valCJob['specializationID']; ?>"><?php echo $valCJob['description']; ?></option>
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
                        <input type="text" name="cfNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $memPrt['cfNIC_No']; ?>" id="cfNIC_No" class="form-control">
                    </div>

                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                            <!--DC NO--><label>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" name="cfDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $memPrt['cfDC_No']; ?>" id="cfDC_No" class="form-control">
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                            <!--reg Date --></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="cfDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cfDCDate']; ?>" id="cfDCDate" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <header class="head-title">
                    <h2><?php echo $this->lang->line('communityngo_com_member_mother_Profile'); ?>
                        <!--Mother DETAILS HEADER-->
                    </h2>
                </header>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title"><?php echo $this->lang->line('communityngo_com_member_mother'); ?>
                            <!--Member--></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <select onchange="get_comMemMoFatherDel();" id="cmComMasID" class="form-control select2" name="cmComMasID">
                            <option data-currency="" value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                            <?php
                            if (!empty($memFemale_drop)) {
                                foreach ($memFemale_drop as $val) {

                                    if ($memPrt['Com_MasterID'] != $val['Com_MasterID']) {
                                        if ($memPrt['cmComMasID'] == $val['Com_MasterID']) {
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
                        <select id="motherIsAlive" class="form-control select2" name="motherIsAlive" onchange="get_mtrAliveStatus();">
                            <option value=""></option>
                            <?php $aliveMg = '';
                            $notAliveMg = '';
                            if ($memPrt['motherIsAlive'] == '1') {
                                $aliveMg = 'selected="selected"';
                            } else if ($memPrt['motherIsAlive'] == '0') {
                                $notAliveMg = 'selected="selected"';
                            } ?>
                            <option value="1" <?php echo $aliveMg; ?>>Yes</option>
                            <option value="0" <?php echo $notAliveMg; ?>>No</option>
                        </select>
                    </div>
                    <div id="mtrDateOfDeathDiv" style="display: none;">
                        <div class="form-group col-sm-2">
                            <label class="title">
                                <?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>
                                <!--DOD --></label>
                        </div>
                        <div class="form-group col-sm-4">
                            <div class="input-group datepic">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="cmDateOfDeath" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cmDateOfDeath']; ?>" id="cmDateOfDeath" class="form-control">
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
                        <input type="text" name="cmFullName" id="cmFullName" class="form-control" value="<?php echo $memPrt['cmFullName']; ?>" placeholder="<?php echo $this->lang->line('communityngo_name'); ?>">
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityngo_dob'); ?>
                            <!--Date of Birth--></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="cMotherDOB" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cMotherDOB']; ?>" id="cMotherDOB" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_born_country'); ?>
                            <!--Born Country--></label></div>
                    <div class="form-group col-sm-4">
                        <select id="cmBornCountry" class="form-control select2" name="cmBornCountry" data-placeholder="<?php echo $this->lang->line('communityNgo_born_country'); ?>" onchange="get_motherBornArea();">
                            <option value=""></option>
                            <?php
                            if (!empty($country_drop)) {
                                foreach ($country_drop as $val) {
                                    if ($memPrt['cmBornCountry'] == $val['countryID']) {
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
                        <select name="cmBornArea" class="form-control select2" id="cmBornArea">
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
                        <input type="text" name="cmBC_No" placeholder="<?php echo $this->lang->line('communityNgo_bc_no'); ?>" value="<?php echo $memPrt['cmBC_No']; ?>" id="cmBC_No" class="form-control">
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                            <!--reg Date --></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="cmBCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cmBCDate']; ?>" id="cmBCDate" class="form-control">
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
                        <select id="cmOccupationId" class="form-control select2" name="cmOccupationId" data-placeholder="<?php echo $this->lang->line('communityngo_Job'); ?>">
                            <option value=""></option>
                            <?php
                            if (!empty($memParentJob)) {
                                foreach ($memParentJob as $valMJob) {
                                    if ($memPrt['cmOccupationId'] == $valMJob['specializationID']) {
                            ?>
                                        <option value="<?php echo $valMJob['specializationID']; ?>" selected="selected"><?php echo $valMJob['description']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $valMJob['specializationID']; ?>"><?php echo $valMJob['description']; ?></option>
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
                        <input type="text" name="cmNIC_No" maxlength="12" placeholder="<?php echo $this->lang->line('communityngo_nic'); ?>" value="<?php echo $memPrt['cmNIC_No']; ?>" id="cmNIC_No" class="form-control">
                    </div>

                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_dc_no'); ?>
                            <!--DC NO--><label>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" name="cmDC_No" placeholder="<?php echo $this->lang->line('communityNgo_dc_no'); ?>" value="<?php echo $memPrt['cmDC_No']; ?>" id="cmDC_No" class="form-control">
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="title">
                            <?php echo $this->lang->line('communityNgo_regDate'); ?>
                            <!--reg Date --></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group datepic">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="cmDCDate" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" value="<?php echo $memPrt['cmDCDate']; ?>" id="cmDCDate" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    <?php
    }
    ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

        $('.datepic').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        }).on('dp.change', function(ev) {});

        get_ftrAliveStatus();
        get_fatherBornArea();
        get_mtrAliveStatus();
        get_motherBornArea();
        get_comMemFatherDel();
        get_comMemMoFatherDel();

        $('.select2').select2();

    });

    function fetch_memPrtDetails() {
        if (Com_MasterID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {
                    'Com_MasterID': Com_MasterID
                },
                url: '<?php echo site_url("CommunityNgo/load_memParentDetails_view"); ?>',
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    stopLoad();
                    $('#memParentDiv').html(data);
                    get_ftrAliveStatus();
                    get_fatherBornArea();
                    get_mtrAliveStatus();
                    get_motherBornArea();

                },
                error: function() {
                    myAlert('e', 'An Error Occurred! Please Try Again.');
                    stopLoad();
                }
            });
        }
    }

    function get_ftrAliveStatus() {

        var fatherIsAlive = document.getElementById('fatherIsAlive').value;

        if (fatherIsAlive == '0') {
            document.getElementById('ftrDateOfDeathDiv').style.display = 'block';
            $('#cfDC_No').prop('disabled', false);
            $('#cfDCDate').prop('disabled', false);
        } else {
            document.getElementById('ftrDateOfDeathDiv').style.display = 'none';
            $('#cfDC_No').prop('disabled', true);
            $('#cfDCDate').prop('disabled', true);
        }

    }

    function get_fatherBornArea() {

        var bornConutryId = document.getElementById('cfBornCountry').value;
        var areaFor = 'memFtr';
        var bldComMasID = document.getElementById('cfComMasID').value;

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
                $('#cfBornArea').html(data);
                $('#CommunityParent_Form').data('bootstrapValidator').resetField($('#cfBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_mtrAliveStatus() {

        var motherIsAlive = document.getElementById('motherIsAlive').value;

        if (motherIsAlive == '0') {
            document.getElementById('mtrDateOfDeathDiv').style.display = 'block';
            $('#cmDC_No').prop('disabled', false);
            $('#cmDCDate').prop('disabled', false);
        } else {
            document.getElementById('mtrDateOfDeathDiv').style.display = 'none';
            $('#cmDC_No').prop('disabled', true);
            $('#cmDCDate').prop('disabled', true);
        }

    }

    function get_motherBornArea() {

        var bornConutryId = document.getElementById('cmBornCountry').value;
        var areaFor = 'memMtr';
        var bldComMasID = document.getElementById('cmComMasID').value;

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
                $('#cmBornArea').html(data);
                $('#CommunityParent_Form').data('bootstrapValidator').resetField($('#cmBornArea'));
                stopLoad();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function get_comMemFatherDel() {
        var cfComMasID = document.getElementById('cfComMasID').value;

        if (cfComMasID == "" || cfComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cfComMasID
                },
                success: function(datum) {
                    $('#cfFullName').val(datum.name).prop("disabled", true);
                    $('#cFatherDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cfBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cfBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cfBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cfBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cfOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cfNIC_No').val(datum.CNIC_No).prop("disabled", true);

                }
            });
        }
    }

    function get_comMemMoFatherDel() {
        var cmComMasID = document.getElementById('cmComMasID').value;

        if (cmComMasID == "" || cmComMasID == null) {} else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "CommunityNgo/get_comMemBloodRelative",
                data: {
                    famComMasterID: cmComMasID
                },
                success: function(datum) {
                    $('#cmFullName').val(datum.name).prop("disabled", true);
                    $('#cMotherDOB').val(datum.CDOB).prop("disabled", true);
                    $('#cmBornCountry').val(datum.CountryOfOrigin).change().prop("disabled", true);
                    $('#cmBornArea').val(datum.CPlaceOfBirth).change().prop("disabled", true);
                    $('#cmBC_No').val(datum.CBC_No).prop("disabled", true);
                    $('#cmBCDate').val(datum.CBC_Date).prop("disabled", true);
                    $('#cmOccupationId').val(datum.comMemJobId).change().prop("disabled", true);
                    $('#cmNIC_No').val(datum.CNIC_No).prop("disabled", true);
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
 * Time: 05:04 PM
 */
