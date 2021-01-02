<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
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

    .task-cat-upcoming-label {
        display: inline;
        float: left;
        color: #f76f01;
        font-weight: bold;
        margin-top: 5px;
        font-size: 15px;
    }

    .taskcount {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #eee;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 1px 3px 0 3px;
        line-height: 14px;
        margin-left: 8px;
        margin-top: 9px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
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

    .numberColoring {
        font-size: 13px;
        font-weight: 600;
        color: saddlebrown;
    }
</style>
<?php
if (!empty($header)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat noselect" style="background: white;">
                <td class="task-cat-upcoming" colspan="8">
                    <div class="task-cat-upcoming-label">Total Farms</div>
                    <div class="taskcount"><?php echo sizeof($header) ?></div>
                </td>
            </tr>
            <tr>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">#</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">Name</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">Area</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">Sub Area</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">Farm Type</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">Contact No</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;">outstanding</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;text-align: center;">Status</td>
                <td class="headrowtitle" style="border-top: 1px solid #F76F01;text-align: center;">Action</td>
            </tr>
            <?php
            $x = 1;
            foreach ($header as $val) { ?>
                <tr>
                    <td class="mailbox-name"><a href="#" class="numberColoring"><?php echo $x; ?></a></td>
                    <td class="mailbox-name">
                        <div class="contact-box">
                            <!--<img class="align-left" src="<?php /*echo base_url("images/buyback/farm.jpg") */?>"
                                 alt="" width="40" height="40">-->
                            <div class="link-box"><strong class="contacttitle">

                                    <a class="link-person noselect" href="#"
                                       onclick="fetchPage('system/buyback/farm_edit_view','<?php echo $val['farmID'] ?>','View Farm','BUYBACK')"><?php echo $val['farmerName'] ?>
                                        <?php // if ($val['farmImage'] != '') { ?>
                                        <!--   <img class="person-circle align-left" style="width: 40px; height: 40px; cursor: pointer; border-radius: 40px" src="<?php echo base_url('uploads/buyback/farmMaster/'.$val['farmImage']); ?>"> -->
                                            <?php
                                     //   } else { ?>
                                           <!-- <img class="person-circle align-left" style="width: 40px; height: 40px; cursor: pointer;" src="<?php echo base_url("images/buyback/farm.jpg") ?>"> -->
                                            <div class="person-circle align-left" style="width: 40px; height: 40px; background-color: <?php echo $color = getColor()?>; cursor: pointer; border-radius: 40px"><span style="font-size: 25px; color: white; vertical-align: middle;"><center><?php $str = $val['farmerName']; echo $str[0];?></center></span></div>
                                        <?php // } ?>
                                    </a>
                                    <br><?php echo $val['farmSystemCode']; ?>
                                </strong></div>
                        </div>
                    </td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['farmerLocation']; ?></a></td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['farmersubarea']; ?></a></td>
                    <td class="mailbox-name"><a href="#"><?php if ($val['farmType'] == 1) {
                                echo "Third Party";
                            } else {
                                echo "Own";
                            } ?></a></td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['phoneMobile']; ?></a></td>
                    <td class="mailbox-name" style="text-align: right">
                        <a href="#">
                        <?php
                        $totalFarmerpay = 0;
                        $batchOutstanding = $this->db->query("SELECT COALESCE(SUM(batchPayableAmount),0) as oustanding FROM `srp_erp_buyback_batch` WHERE farmID = {$val['farmID']}")->row_array();

                        $batchTotalPaid = $this->db->query("SELECT COALESCE (SUM(pvd.wagesAmount), 0) AS wagesAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN (SELECT pvMasterAutoID, type, SUM(transactionAmount) AS wagesAmount FROM
		srp_erp_buyback_paymentvoucherdetail GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$val['farmID']} AND PVtype = 3 AND approvedYN = 1")->row_array();
                        $farmdetailexist = $this->db->query("select farmid from srp_erp_buyback_dispatchnote where farmid = {$val['farmID']}")->row_array();
                        $farmdetailexistpv = $this->db->query("select farmid from srp_erp_buyback_paymentvouchermaster where farmid = {$val['farmID']}")->row_array();

                        $totalFarmerpay = $batchOutstanding['oustanding'] - $batchTotalPaid['wagesAmount'];

                        echo number_format($totalFarmerpay, 0)." (".$val['CurrencyCode'].")"
                        ?>
                        </a>
                    </td>
                    <td class="mailbox-name" style="text-align: center">
                        <?php if ($val['isActive'] == 1) { ?>
                            <span class="label"
                                  style="background-color: #8bc34a; color: #FFFFFF; font-size: 11px;">Active</span>
                        <?php } else { ?>
                            <span class="label"
                                  style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;">Not Active</span>
                        <?php } ?>
                    </td>
                    <td class="mailbox-attachment"><span class="pull-right">
                            <a href="#"
                               onclick="fetchPage('system/buyback/create_farm','<?php echo $val['farmID'] ?>','Edit Farm','BUYBACK')"><span
                                    title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;

                    <?php if($farmdetailexist =='' && $farmdetailexistpv == ''){?>

                        <a
                                onclick="delete_farm_buyback(<?php echo $val['farmID'] ?>);"><span title="Delete" rel="tooltip"
                                                                                           class="glyphicon glyphicon-trash"
                                                                                           style="color:rgb(209, 91, 71);"></span></a></span>
                   <?php }?>

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
    <div class="search-no-results">THERE ARE NO FARMS TO DISPLAY.</div>
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