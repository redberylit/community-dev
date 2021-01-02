<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('operationngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

?>


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

    .task-cat-upcoming {
        border-bottom: solid 1px #f76f01;
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

    .numberColoring {
        font-size: 13px;
        font-weight: 600;
        color: saddlebrown;
    }

    .deleted {
        text-decoration: line-through;

    }

    . deleted div {
        text-decoration: line-through;

    }
</style>
<?php
if (!empty($master)) {

    ?>
    <br>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat noselect" style="background: white;">
                <td class="task-cat-upcoming" colspan="10">
                    <div class="task-cat-upcoming-label">Latest Vouchers</div>
                    <div class="taskcount"><?php echo sizeof($master) ?></div>
                </td>
            </tr>
            <tr>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"></td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Code</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Name</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Narration</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Amount</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Expense</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Balance</td>

                <td class="headrowtitle" style="border-top: 1px solid #ffffff; text-align: center;">Status</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">IOU Status</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff; text-align: center;">Action</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"></td>

            </tr>
            <?php
            $x = 1;
            foreach ($master as $val) {
                ?>
                <?php if ($val['isDeleted'] == 1) {
                    $delete = 'deleted deleted div';
                } else {
                    $delete = '';
                } ?>
                <tr>
                    <td class="mailbox-name <?php echo $delete ?>"><a href="#"
                                                                      class="numberColoring"> <?php echo $x; ?></a></td>
                    <td class="mailbox-name <?php echo $delete ?>"><a href="#"><?php echo $val['iouCode']; ?></a></td>
                    <td class="mailbox-name <?php echo $delete ?>"><a href="#"><?php echo $val['empNameiou']; ?></a>
                    </td>
                    <td class="mailbox-name <?php echo $delete ?>"><a
                                href="#"><?php echo ucwords(trim_value($val['narration'], 8)); ?></a></td>
                    <td class="mailbox-name <?php echo $delete ?>"><a
                                href="#"><?php echo $val['CurrencyCode'] . ' ' . number_format($val['transactionamount'], $val['transactionCurrencyDecimalPlaces']) ?></a>
                    </td>
                    <td class="mailbox-name <?php echo $delete ?>"><a
                                href="#"><?php echo $val['CurrencyCode'] . ' ' . number_format($val['bookingamount'], $val['transactionCurrencyDecimalPlaces']) ?></a>
                    </td>
                    <td class="mailbox-name <?php echo $delete ?>"><a
                                href="#"><?php echo $val['CurrencyCode'] . ' ' . number_format($val['expamt'], $val['transactionCurrencyDecimalPlaces']) ?></a>
                    </td>
                    <td class="mailbox-name <?php echo $delete ?>">
                        <?php if ($val['confirmedYN'] != 1 && $val['confirmedYN'] != 2) {
                            ?>
                            <span class="label"
                                  style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;">Draft</span>
                            <?php
                        } else if ($val['confirmedYN'] == 1 && $val['approvedYN'] != 1) {
                            ?>

                            <a style="cursor: pointer"
                               onclick="fetch_all_approval_users_modal('IOU','<?php echo $val['voucherAutoID'] ?>')"><span
                                        class="label"
                                        style="background-color:#ff661d; color: #FFFFFF; font-size: 11px;">Confirmed <i
                                            class="fa fa-external-link" aria-hidden="true"></i></span></a>
                            <?php
                        } elseif ($val['confirmedYN'] == 2 && $val['approvedYN'] != 1) {
                            ?>

                            <a style="cursor: pointer"
                               onclick="fetch_approval_reject_user_modal('IOU','<?php echo $val['voucherAutoID'] ?>')"> <span
                                        class="label"
                                        style="background-color:#ff784f; color: #FFFFFF; font-size: 11px;">Referred Back <i
                                            class="fa fa-external-link" aria-hidden="true"></i></span></a>
                            <?php
                        } else if ($val['confirmedYN'] == 1 && $val['approvedYN'] == 1){
                            ?>
                            <a style="cursor: pointer"
                               onclick="fetch_approval_user_modal('IOU','<?php echo $val['voucherAutoID'] ?>')"><span
                                        class="label"
                                        style="background-color:#8bc34a; color: #FFFFFF; font-size: 11px;">Approved <i
                                            class="fa fa-external-link" aria-hidden="true"></i></span></a>
                            <?php

                        } ?>
                    </td>
                    <td class="mailbox-name <?php echo $delete ?>">
                        <?php if($val['closedYN'] != 1){?>
                            <?php if($val['bookingamount']==0){?>
                                <span class="label" style="background-color:rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;">Open</span>
                                <?php }?>
                            <?php if($val['bookingamount'] < $val['transactionamount'] && $val['bookingamount']!=0){?>
                                <span class="label" style="background-color:#f39c12; color: #FFFFFF; font-size: 11px;">Partially Matched</span>
                            <?php }?>

                            <?php if($val['bookingamount'] >= $val['transactionamount'] && $val['bookingamount']!=0){?>
                                <span class="label" style="background-color:#8bc34a; color: #FFFFFF; font-size: 11px;">Fully Matched</span>
                            <?php }?>
                        <?php } else {?>
                            <a style="cursor: pointer"
                               onclick="closedvoucherdetails(<?php echo $val['voucherAutoID'] ?>)"> <span
                                        class="label"
                                        style="background-color:#00c0ef; color: #FFFFFF; font-size: 11px;">Closed <i
                                            class="fa fa-external-link" aria-hidden="true"></i></span></a>
                             <?php }?>
                        </td>

                    <td class="mailbox-attachment" style="text-align: right"><span>


                             <?php if ($val['approvedYN'] == 1 ) {
                                 ?>
                                 <a href="#"
                                    onclick="documentPageView_modal('PV',<?php echo $val['paymentVoucherAutoID'] ?>)"><span
                                             title="View Payment Voucher" rel="tooltip"
                                             class="glyphicon glyphicon-list-alt"></span></a> |

                                 <a href="#"
                                    onclick="fetchPage('system/iou/iou_voucher_view','<?php echo $val['voucherAutoID'] ?>','View IOU Voucher Details')"><span
                                             title="View IOU Voucher Details" rel="tooltip"
                                             class="glyphicon glyphicon-pencil"></span></a> |


                             <?php } ?>






                            <?php if ($val['closedYN'] != 1) { ?>
                                <?php if ($val['expamt'] > 0) { ?>
                                    <a href="#"
                                       onclick="generatevoucher(<?php echo $val['voucherAutoID'] ?>,<?php echo $val['expamt'] ?>,'Receipt Voucher','1')"><span
                                                title="Generate Receipt Voucher" rel="tooltip"
                                                class="glyphicon glyphicon-remove"></span></a> |
                                <?php } else if ($val['expamt'] != 0) { ?>
                                    <a href="#"
                                       onclick="generatevoucher(<?php echo $val['voucherAutoID'] ?>,<?php echo $val['expamt'] ?>,'Payment Voucher','2')"><span
                                                title="Generate Payment Voucher" rel="tooltip"
                                                class="glyphicon glyphicon-remove"></span></a> |
                                <?php } else if ($val['approvedYN'] == 1) { ?>
                                    <a href="#" onclick="closeiouvoucher(<?php echo $val['voucherAutoID'] ?>)"><span
                                                title="Close Voucher" rel="tooltip"
                                                class="glyphicon glyphicon-remove"></span></a> |
                                <?php } ?>
                            <?php } ?>


                            <?php if ($val['confirmedYN'] != 1 && $val['isDeleted'] != 1) {
                                ?>
                                <a href="#"
                                   onclick="fetchPage('system/iou/create_iou_voucher','<?php echo $val['voucherAutoID'] ?>','Edit IOU Voucher')"><span
                                            title="Edit" rel="tooltip"
                                            class="glyphicon glyphicon-pencil"></span></a> |
                            <?php } ?>
                            <a target="_blank"
                               onclick="documentPageView_modal('IOU','<?php echo $val['voucherAutoID'] ?>')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-eye-open"
                                        data-original-title="View"></span></a> |
                          <a target="_blank"
                             href="<?php echo site_url('iou/load_iou_voucher_confirmation/') . '/' . $val['voucherAutoID'] ?>"><span
                                      title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a></span>&nbsp;
                        <?php if ($val['confirmedYN'] != 1 && $val['isDeleted'] != 1) {
                            ?> | <a onclick="delete_iou_voucher(<?php echo $val['voucherAutoID'] ?>);"><span
                                        title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"
                                        style="color:rgb(209, 91, 71);"></span></a>
                        <?php } ?>
                        <?php if ($val['isDeleted'] == 1) {
                            ?>
                            |&nbsp;&nbsp;  <a onclick="reopen_iou_voucher(<?php echo $val['voucherAutoID'] ?>);"><span
                                        title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat"
                                        style="color:rgb(209, 91, 71);"></span></a>
                        <?php } ?>
                        <?php
                        if ($val['approvedYN'] == 0 and $val['confirmedYN'] == 1 && $val['isDeleted'] == 0) { ?>
                            |&nbsp;&nbsp;<a onclick="referback_iouvoucher(<?php echo $val['voucherAutoID'] ?>);"><span
                                        title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat"
                                        style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;
                        <?php } ?>
                    </td>
                </tr>
                <?php
                $x++;
            } ?>
            </tbody>
        </table>
    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO IOU VOUCHERS TO DISPLAY.</div>
    <?php
}

?>

<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $("[rel=tooltip]").tooltip();
        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

    });
</script>