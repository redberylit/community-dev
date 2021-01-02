<?php
if (!empty($beneMem)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat-upcoming">
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">#</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">NAME</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">PHONE NO</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">COUNTRY</td>


            </tr>
            <?php
            $x = 1;

            foreach ($beneMem as $val) {


               // $data['beneMem'] = $this->db->query("SELECT *,CountryDes,DATE_FORMAT(bfm.createdDateTime,'" . $convertFormat . "') AS createdDate,DATE_FORMAT(bfm.modifiedDateTime,'" . $convertFormat . "') AS modifydate,DATE_FORMAT(bfm.registeredDate,'" . $convertFormat . "') AS registeredDate,DATE_FORMAT(bfm.dateOfBirth,'" . $convertFormat . "') AS dateOfBirth,bfm.createdUserName as contactCreadtedUser,bfm.email as contactEmail,bfm.phonePrimary as contactPhonePrimary,bfm.phoneSecondary as contactPhoneSecondary,project.projectName as projectName,benType.description as benTypeDescription,smpro.Description as provinceName,smdis.Description as districtName,smdiv.Description as divisionName,smsubdiv.Description as subDivisionName,bfm.projectID FROM srp_erp_ngo_beneficiaryfamilydetails bfmMem INNER JOIN srp_erp_ngo_beneficiarymaster bfm ON bfm.benificiaryID = bfmMem.beneficiaryID LEFT JOIN srp_erp_countrymaster ON srp_erp_countrymaster.countryID = bfm.countryID LEFT JOIN srp_erp_ngo_projects project ON project.ngoProjectID = bfm.projectID LEFT JOIN srp_erp_ngo_benificiarytypes benType ON benType.beneficiaryTypeID = bfm.benificiaryType LEFT JOIN srp_erp_statemaster smpro ON smpro.stateID = bfm.province LEFT JOIN srp_erp_statemaster smdis ON smdis.stateID = bfm.district LEFT JOIN srp_erp_statemaster smdiv ON smdiv.stateID = bfm.division LEFT JOIN srp_erp_statemaster smsubdiv ON smsubdiv.stateID = bfm.subDivision WHERE bfm.companyID = '" . $companyID . "' AND projectID = '" . $projectID . "' AND bfm.confirmedYN = '" . $conform . "'")->result_array();

                ?>
                <tr>
                    <td class="mailbox-star" width="5%"><?php echo $x; ?></td>
                    <td class="mailbox-star" width=""><?php echo $val['nameWithInitials'] ; ?></td>
                    <td class="mailbox-star" width=""><?php echo $val['contactPhonePrimary']; ?></td>
                    <td class="mailbox-star" width=""><?php echo $val['CountryDes']; ?></td>
                 </tr>
                <?php


                $x++;
            }
            ?>
            </tbody>

        </table><!-- /.table -->
    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO RECORDS TO DISPLAY.</div>
    <?php
}
?>

<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 7/6/2018
 * Time: 11:28 AM
 */