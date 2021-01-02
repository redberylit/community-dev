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

    .actionicon {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #89e68d;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 2px 5px 2px 5px;
        line-height: 14px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .headrowtitle {
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
        background-color: white;
        border-top: 1px solid #ffffff;
    }
</style>
<?php
if (!empty($batch)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat-upcoming">
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">#</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Farmer</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Batch Code</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Document Date</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">No of Birds</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Confirmation</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Action</td>
            </tr>
            <?php
            $x = 1;
            foreach ($batch as $val) {
                ?>
                <tr>
                    <td class="mailbox-star" width="5%"><?php echo $x; ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['farmerName']; ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['batchCode']; ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['documentDate']; ?></td>
                    <td class="mailbox-name" width="10%" style="text-align: center">
                        <?php
                        $companyID = $this->common_data['company_data']['company_id'];
                            $birdsCount = $this->db->query("SELECT SUM(noOfBirds) as TotalBirds FROM srp_erp_buyback_mortalitydetails WHERE companyID = {$companyID} AND mortalityAutoID = {$val['mortalityAutoID']}")->row_array();
                            if(!empty($birdsCount)){
                                echo $birdsCount['TotalBirds'];
                            }else {
                                echo 0;
                            }
                            ?>
                        </td>
                    <td class="mailbox-name" width="10%" style="text-align: center">
                        <?php if ($val['confirmedYN'] == 0) { ?>
                            <span class="label" style="background-color: #F44336; color: #FFFFFF; font-size: 11px;">Not Confirmed</span>
                            <?php
                        } else { ?>
                            <span class="label" style="background-color: #8bc34a; color: #FFFFFF; font-size: 11px;">Confirmed</span>
                            <?php
                        }
                        ?>
                    </td>
                    <td class="mailbox-name" width="10%"><span class="pull-right">
                                 <?php if($val['confirmedYN']!=1) {
                                 ?>
                            <a href="#" onclick="fetchPage('system/buyback/create_new_mortality','<?php echo $val['mortalityAutoID'] ?>','Edit Mortality','BUYBACK')"><span
                                    title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                               <a target="_blank" href="<?php echo site_url('buyback/load_mortality_confirmation/') . '/' . $val['mortalityAutoID'] ?>"  ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>
                               &nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="delete_mortality(<?php echo $val['mortalityAutoID'] ?>);"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a></span>
                    <?php
                    }else{
                        if ($val['createdUserID'] == trim(current_userID()) && $val['confirmedYN'] == 1 && $val['isclosed'] !=1) {
                            ?>
                            <a onclick="referback_mortality(<?php echo $val['mortalityAutoID'] ?>);"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <?php
                        }
                        ?>
                        <a target="_blank" onclick="documentPageView_modal('BBM','<?php echo $val['mortalityAutoID']?>')"><span title="" rel="tooltip" class="glyphicon glyphicon-eye-open" data-original-title="View"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                        <a target="_blank" href="<?php echo site_url('buyback/load_mortality_confirmation/') . '/' . $val['mortalityAutoID'] ?>"  ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>
                        <?php
                    }
                    ?>
                    </td>
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
    <div class="search-no-results">THERE ARE NO MORTALITIES TO DISPLAY.</div>
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

    });
</script>