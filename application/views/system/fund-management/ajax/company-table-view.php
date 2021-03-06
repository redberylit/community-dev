<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('fn_management', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

<style>
    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .label {
        display: inline;
        padding: .2em .8em .3em;
    }

    .contact-box .align-left {
        float: left;
        margin: 0 7px 0 0;
        padding: 2px;
        border: 1px solid #ccc;
    }

    img {
        vertical-align: middle;
        border: 0;
        -ms-interpolation-mode: bicubic;
    }

    .numberOrder {

    }

    .head-row-title {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0 25px;
        font-weight: bold;
        text-align: left;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
        background-color: #fafafa;
        border-top: 1px solid #ffffff;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .numberColoring {
        font-size: 13px;
        font-weight: 600;
        color: saddlebrown;
    }

    .k-grid-header th.k-header, .k-filter-row th {
        overflow: hidden;
        border-style: solid;
        border-width: 0 0 1px 0;
        padding: .5em .6em .4em .6em;
        font-weight: bold;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: left;
        border-right: dotted 1px #ddd;
        position: relative;
    }

    .sup_table {
        border-bottom-width: 1px;
        border-style: solid;
        border-left-width: 0;
        border-right-width: 0;
        white-space: nowrap;
        border-color: #ddd;
    }

    .person-circle {
        background-color: #d7d9da;
        border-radius: 50% !important;
        color: rgba(255, 255, 255, .87);
        font-size: 14px;
        font-weight: bold;
        height: 28px;
        line-height: 25px;
        text-align: center;
        width: 28px;
        position: relative;
    }

    .label-circle-danger{
        background-color: #c14031 !important;
        border-radius: 50%;
        font-size: 11px;
    }

    .label-circle-success{
        background-color: #00a65a !important;
        border-radius: 50%;
        font-size: 11px;
    }

    .label-circle-warning{
        background-color: #f39c12 !important;
        border-radius: 50%;
        font-size: 11px;
    }

    .label-circle-danger:hover,.label-circle-warning:hover{
        cursor: pointer;
    }
</style>


<?php
if (empty($comData)) {
    echo '<div class="search-no-results">'. $this->lang->line('fn_man_there_are_no_company').'</div>';
    die();
}
?>

    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);"></td>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);"></td>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);">
                    <?php echo $this->lang->line('common_name'); ?><!--Name-->
                </td>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);">
                    <?php echo $this->lang->line('common_address'); ?><!--Address-->
                </td>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);">
                    <?php echo $this->lang->line('fn_man_phone_no'); ?><!--Phone No-->
                </td>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);">
                    <?php echo $this->lang->line('common_status').' '.$this->lang->line('common_document'); ?>
                </td>
                <td class="head-row-title" style="border: solid 1px rgba(158, 158, 158, 0.23);">
                    <?php echo $this->lang->line('common_action'); ?><!--Action-->
                </td>
            </tr>
            <?php
            $x = 1;
            foreach ($comData as $val) {
                $masterID = $val['id'];
                ?>
                <tr>
                    <td class="mailbox-name sup_table"><a href="#" class="numberColoring"><?php echo $x; ?></a></td>
                    <td align="center" class="mailbox-name sup_table">
                        <a class="avatar" href="#" onclick="edit_company_data('<?php echo $masterID ?>')">
                            <img class="person-circle align-left" style="border-radius: 50%; cursor: pointer;" src="<?php echo base_url("images/company-avatar.jpg") ?>">
                        </a>
                    </td>
                    <td class="mailbox-name sup_table">
                        <div class="contact-box">
                            <div class="link-box">
                                <strong class="contacttitle">
                                    <a class="link-person noselect" href="#" onclick="edit_company_data('<?php echo $masterID ?>')">
                                        <?php echo $val['company_name'] ?>
                                    </a>
                                    <br><?php echo $val['email_id'] ?>
                                </strong>
                            </div>
                        </div>
                    </td>
                    <td class="mailbox-name sup_table">
                        <?php  echo $val['address']; ?> <br/>
                        <div class="pull-left"> Postal code: <?php  echo $val['postal_code']; ?> </div>
                        <div class="pull-right" style="padding-right: 10px"> Country : <?php  echo $val['CountryDes']; ?>  </div>
                    </td>
                    <td class="mailbox-name sup_table"><?php echo $val['tel_no']; ?></td>
                    <td class="mailbox-attachment sup_table">
                        <?php
                        $data = get_attachment_status('FMC', $masterID);
                        echo $data;
                        ?>
                    </td>
                    <td class="mailbox-attachment sup_table">
                        <span class="pull-right">
                            <a href="#" onclick="edit_company_data('<?php echo $masterID ?>')">
                                <span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span>
                            </a>&nbsp;&nbsp;
                            <!--<a onclick="delete_contact(<?php /*echo $masterID */?>)">
                                <span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span>
                            </a>-->
                        </span>
                    </td>
                </tr>
                <?php
                $x++;
            }
            ?>
            </tbody>
        </table>
    </div>

<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 7/10/2018
 * Time: 12:55 PM
 */