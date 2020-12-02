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
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">System Code</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Farmer</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Field Officer</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Document Date</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Batch Code</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center;">Confirmation</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center;">Action</td>
            </tr>
            <?php
            $x = 1;
            foreach ($batch as $val) {
                ?>
                <tr>
                    <td class="mailbox-star" width="5%"><?php echo $x; ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['fvrSystemCode'] ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['farmerName'] ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['fieldOfficerName'] ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['documentDate'] ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['batchCode'] ?></td>
                    <td class="mailbox-name" style="text-align: center" width="10%">
                        <?php if ($val['fvrConfirmedYN'] == 1) { ?>
                            <span class="label"
                                  style="background-color: #8bc34a; color: #FFFFFF; font-size: 11px;">Confirmed</span>
                        <?php } else { ?>
                            <span class="label"
                                  style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;">Not Confirmed</span>
                        <?php } ?>
                    </td>
                    <td class="mailbox-name" width="10%">
                        <span class="pull-right">
                           <?php if ($val['fvrConfirmedYN'] != 1) { ?>
                            <a onclick='attachment_modal(<?php echo $val['farmerVisitID'] ?>,"Dispatch Note","BBDPN",<?php echo $val['fvrConfirmedYN'] ?>)'><span
                                    title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a href="#"
                               onclick="fetchPage('system/buyback/create_new_farm_visit_report','<?php echo $val['farmerVisitID'] ?>','Edit Farm Visit Report','BUYBACK')"><span
                                    title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                               <a target="_blank"
                                  href="<?php echo site_url('buyback/load_farmVisitReport_confirmation/') . '/' . $val['farmerVisitID'] ?>"><span
                                       title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>
                               &nbsp;&nbsp;|&nbsp;&nbsp;<a
                                onclick="delete_farmVisitReport(<?php echo $val['farmerVisitID'] ?>);"><span
                                    title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"
                                    style="color:rgb(209, 91, 71);"></span></a>
                        </span>
                        <?php
                        } else {
                            if ($val['fvrCreatedUserID'] == trim(current_userID()) && $val['fvrConfirmedYN'] == 1 && $val['isclosed']!=1) {
                                ?>
                                <a onclick="referback_farmVisitReport(<?php echo $val['farmerVisitID'] ?>);"><span
                                        title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat"
                                        style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                <?php
                            }
                            ?>
                            <a onclick='attachment_modal(<?php echo $val['farmerVisitID'] ?>,"Dispatch Note","BBDPN",<?php echo $val['fvrConfirmedYN'] ?>)'><span
                                    title="Attachment" rel="tooltip"
                                    class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a target="_blank"
                               onclick="documentPageView_modal('BBFVR','<?php echo $val['farmerVisitID'] ?>')"><span
                                    title="" rel="tooltip" class="glyphicon glyphicon-eye-open" data-original-title="View"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            <a target="_blank"
                               href="<?php echo site_url('buyback/load_farmVisitReport_confirmation/') . '/' . $val['farmerVisitID'] ?>"><span
                                    title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>
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
        </table>
    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO BATCHES TO DISPLAY.</div>
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