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
if (!empty($detail)) { ?>
    <div class="table-responsive mailbox-messages" id="advancerecid">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat-upcoming">
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">#</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Description</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Amount</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Action</td>
            </tr>
            <?php
            $x = 1;
            $total = 0;
            foreach ($detail as $val) {
                ?>
                <tr>
                    <td class="mailbox-star" width="1%"> <?php echo $x; ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['description'] ?></td>
                    <td class="mailbox-star" width="10%"><?php echo number_format($val['transactionAmount'], 2) ?></td>
                    <td class="mailbox-attachment taskaction_td" width="5%"><span>
                            <a onclick="edit_paymentVoucher_advance(<?php echo $val['voucherDetailID']?>)"><span title="Edit" rel="tooltip"
                                                                             class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|  <a
                                onclick="delete_iou_voucher(<?php echo $val['voucherDetailID']?>)"><span title="Delete" rel="tooltip"
                                                                                                         class="glyphicon glyphicon-trash"
                                                                                                         style="color:rgb(209, 91, 71);"></span></a></span>
                    </td>
                </tr>
                <?php
                $x++;
                $total += $val['transactionAmount'];
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td class="text-right "  colspan="2">
                    Total
                </td>
                <td class="text">
                    <?php echo number_format($total, 2) ?>
                </td >
                <td class="text-right">&nbsp;</td>
            </tr>
            </tfoot>
        </table><!-- /.table -->
    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results" id="advancerecid">NO RECORDS FOUND.</div>
    <?php
}
?>
