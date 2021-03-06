<?php
$companyCode = $this->common_data['company_data']['company_code'];
$showAddBtn = true;
function url_exists($url)
{
    $url = str_replace("http://", "", $url);
    if (strstr($url, "/")) {
        $url = explode("/", $url, 2);
        $url[1] = "/" . $url[1];
    } else {
        $url = array($url, "/");
    }

    $fh = fsockopen($url[0], 80);
    if ($fh) {
        fputs($fh, "GET " . $url[1] . " HTTP/1.1\nHost:" . $url[0] . "\n\n");
        if (fread($fh, 22) == "HTTP/1.1 404 Not Found") {
            return FALSE;
        } else {
            return TRUE;
        }

    } else {
        return FALSE;
    }
}

if ($type == 'edit') {
    $showAddBtn = true;
} else {
    $showAddBtn = false;
}
?>
<style>
    .select2-container {
        min-width: 150px !important;
    }

    .select2-dropdown--below {
    / / select2-container min-width: 150 px !important;
        z-index: 2000 !important;
    }
</style>
<?php
if (!empty($benificiaryArray)) {

    foreach ($benificiaryArray as $familyInfo) {
        ?>
        <div class="container-fluid familyMasterContainer"
             id="specificfamilydetail<?php echo $familyInfo['empfamilydetailsID'] ?>">

            <div class="row">
    <span style="margin: 5px;"
          class="pull-right btn-group"> <?php if ($showAddBtn == true) { ?>
            <button id="edit" title="Delete" rel="tooltip"
                    class="btn btn-xs btn-danger"
                    onclick="delete_comBeneficiary_familyDel(<?php echo $familyInfo["empfamilydetailsID"]; ?>)">
                <i class="fa fa-trash"></i>
            </button>
        <?php } ?> </span>

                <div class="col-md-7">
                    <div class="familyContainer">
                        <h5 style="color:#002100">
                            <?php if ($showAddBtn == true) { ?>
                                <a href="#" data-type="select2"
                                   data-url="<?php echo site_url('CommunityNgo/update_comBeneficiary_familyDel') ?>"
                                   data-pk="<?php echo $familyInfo['empfamilydetailsID'] ?>"
                                   data-name="relationship"
                                   data-title="Relationship Status"
                                   class="relationshipDrop"
                                   data-placement="right"
                                   data-value="<?php echo isset($familyInfo['relationship']) ? $familyInfo['relationship'] : ''; ?>">
                                    <?php
                                    echo isset($familyInfo['relationshipDesc']) ? $familyInfo['relationshipDesc'] : '';
                                    ?>
                                </a>
                            <?php } else {
                                echo isset($familyInfo['relationshipDesc']) ? $familyInfo['relationshipDesc'] : '';
                            } ?>

                        </h5>
                        <table class="table table-condensed">
                            <!--border="0" cellpadding="10" cellspacing="0" width="100%"-->
                            <!---->
                            <tr>
                                <td>Name :</td>
                                <td>
                                    <?php if ($showAddBtn == true) { ?>
                                        <a href="#" data-type="text"
                                           data-placement="bottom"
                                           data-url="<?php echo site_url('CommunityNgo/update_comBeneficiary_familyDel') ?>"
                                           data-pk="<?php echo $familyInfo['empfamilydetailsID'] ?>"
                                           data-name="name"
                                           data-title="Name"
                                           class="xeditable"
                                           data-value="<?php echo isset($familyInfo['name']) ? $familyInfo['name'] : ''; ?>">
                                            <?php echo $familyInfo['name'] ?>
                                        </a>
                                    <?php } else {
                                        echo $familyInfo['name'];
                                    } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Nationality :</td>
                                <td>
                                    <?php
                                    $filename = '/community/images/flags/' . trim($familyInfo['countryName']) . '.png';
                                    if (!empty($familyInfo['countryName'])) {
                                        if (url_exists($filename)) {
                                            echo '<img src="' . $filename . '" />';
                                        }
                                    }
                                    if ($showAddBtn == true) { ?>
                                        <a href="#" data-type="select2"
                                           data-url="<?php echo site_url('CommunityNgo/update_comBeneficiary_familyDel') ?>"
                                           data-pk="<?php echo $familyInfo['empfamilydetailsID'] ?>"
                                           data-name="nationality"
                                           data-title="Nationality"
                                           class="countryDrop"
                                           data-value="<?php echo $familyInfo['nationality']; ?>">
                                            <?php echo $familyInfo['countryName']; ?>
                                        </a>
                                    <?php } else {
                                        echo $familyInfo['countryName'];
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date of Birth :</td>
                                <td>
                                    <?php if ($showAddBtn == true) { ?>
                                        <a href="#" data-type="combodate"
                                           data-url="<?php echo site_url('CommunityNgo/update_comBeneficiary_familyDel') ?>"
                                           data-pk="<?php echo $familyInfo['empfamilydetailsID'] ?>"
                                           data-name="DOB"
                                           data-title="Date of Birth"
                                           class="xeditableDate"
                                           data-value="<?php if (!empty($familyInfo['DOB']) && $familyInfo['DOB'] != '0000-00-00 00:00:00') {

                                               echo format_date($familyInfo['DOB']);
                                           } ?>">
                                            <?php
                                            if (isset($familyInfo['DOB'])) {
                                                if (!empty($familyInfo['DOB']) && $familyInfo['DOB'] != '0000-00-00 00:00:00') {
                                                    echo format_date_dob($familyInfo['DOB']);
                                                }
                                            }
                                            ?>
                                        </a>
                                    <?php } else {
                                        if (isset($familyInfo['DOB'])) {
                                            if (!empty($familyInfo['DOB']) && $familyInfo['DOB'] != '0000-00-00 00:00:00') {
                                                echo format_date_dob($familyInfo['DOB']);
                                            }
                                        }
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Gender :</td>
                                <td>
                                    <?php if ($showAddBtn == true) { ?>
                                        <a href="#" data-type="select2"
                                           data-url="<?php echo site_url('CommunityNgo/update_comBeneficiary_familyDel') ?>"
                                           data-pk="<?php echo $familyInfo['empfamilydetailsID'] ?>"
                                           data-name="gender"
                                           data-title="Gender"
                                           class="genderDrop"
                                           data-value="<?php echo isset($familyInfo['gender']) ? $familyInfo['gender'] : ''; ?>">
                                            <?php
                                            echo isset($familyInfo['genderDesc']) ? $familyInfo['genderDesc'] : '';
                                            ?>
                                        </a>
                                    <?php } else {
                                        echo isset($familyInfo['genderDesc']) ? $familyInfo['genderDesc'] : '';
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>NIC No. :</td>
                                <td>
                                    <?php if ($showAddBtn == true) { ?>
                                        <a href="#" data-type="text"
                                           data-url="<?php echo site_url('CommunityNgo/update_comBeneficiary_familyDel') ?>"
                                           data-pk="<?php echo $familyInfo['empfamilydetailsID'] ?>"
                                           data-name="idNO"
                                           data-title="ID No.  "
                                           class="xeditable"
                                           data-value="<?php echo isset($familyInfo['idNO']) ? $familyInfo['idNO'] : ''; ?>">
                                            <?php
                                            echo isset($familyInfo['idNO']) ? $familyInfo['idNO'] : '';
                                            ?>
                                        </a>
                                    <?php } else {
                                        echo isset($familyInfo['idNO']) ? $familyInfo['idNO'] : '';
                                    } ?>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="familyContainer">
                        <div class="show-image" style="text-align: center;">
                            <?php
                            $communityimage = get_all_community_images($familyInfo['image'],'Community/'.$companyCode.'/MemberImages/','communityNoImg');
                           ?>
                                <img
                                    src="<?php echo $communityimage; ?>"
                                    alt="Image" style="width: 200px; height: 145px;">


                            <button
                                onclick="modaluploadimages(<?php echo $familyInfo['empfamilydetailsID'] ?>,<?php echo $familyInfo['Com_MasterID'] ?>)"
                                class="update btn btn-warning btn-xs"
                                type="button" value="Update"><i
                                    class="fa fa-upload"></i></button>

                        </div>

                        </table>

                    </div>
                </div>
            </div>
        </div>
        <hr>
    <?php }
} else {

    ?>
    <div id="familydetails" style="">
        <div class="alert alert-danger" role="alert">
            <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
            <span class="sr-only">Not Found:</span>
            No Family Details Found!
        </div>
    </div>
    <?php exit;
}
?>


<script>
    $(document).ready(function () {
        //$('.select2').select2();

        $('.xeditable').editable();

        $('.xeditableDate').editable({
            format: 'YYYY-MM-DD',
            viewformat: 'DD.MM.YYYY',
            template: 'D / MMMM / YYYY',
            combodate: {
                minYear: <?php echo format_date_getYear() - 80 ?>,
                maxYear: <?php echo format_date_getYear() + 10 ?>,
                minuteStep: 1
            }
        });

        $('.genderDrop').editable({
            source: [
                <?php
                $result = load_gender_drop();
                if (!empty($result)) {
                    $i = 1;
                    $count = count($result);
                    foreach ($result as $val) {
                        echo "{id: '" . $val['genderID'] . "', text: '" . trim($val['name']) . "'} ";
                        if ($count != $i) {
                            echo ',';
                        }
                        $i++;
                    }
                }
                ?>
            ]
        });


        $('.countryDrop').editable({
            source: [
                <?php
                $result = load_nationality_drop();
                if (!empty($result)) {
                    $i = 1;
                    $count = count($result);
                    foreach ($result as $val) {
                        $string = str_replace(' ', '-', $val['Nationality']); // Replaces all spaces with hyphens.
                        $finalOutput = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

                        echo "{id: '" . $val['NId'] . "', text: '" . $finalOutput . "'} ";
                        if ($count != $i) {
                            echo ',';
                        }
                        $i++;
                    }
                }
                ?>
            ]
        });


        $('.relationshipDrop').editable({
            source: [
                <?php
                $getResult = get_hrms_relationship();
                if (!empty($getResult)) {
                    $i = 1;
                    $count = count($getResult);
                    foreach ($getResult as $val) {
                        echo "{id: '" . $val['relationshipID'] . "', text: '" . trim($val['relationship']) . "'} ";
                        if ($count != $i) {
                            echo ',';
                        }
                        $i++;
                    }
                }
                ?>
            ]


        });

    });

</script>

