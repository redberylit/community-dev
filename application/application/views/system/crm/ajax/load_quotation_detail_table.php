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
    tfoot {
        border-top: 1px double #f76f01;
        border-bottom: 1px double #f76f01;
    }
</style>
<?php
if (!empty($header)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat-upcoming">
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">#</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Product</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">UOM</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">QTY</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Expected Delivery Date</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Price</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Total Price</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;"></td>
            </tr>
            <?php
            $x = 1;
            $total = 0;
            $lineTotal = 0;
            $grandTotal = 0;
            foreach ($header as $val) {
                ?>
                <tr>
                    <td class="mailbox-star" width="5%"><?php echo $x; ?></td>
                    <td class="mailbox-star" width="20%"><?php echo $val['productName'] ?></td>
                    <td class="mailbox-star" width="10%"><?php echo $val['unitOfMeasure'] ?></td>
                    <td class="mailbox-star" width="%"><?php echo $val['requestedQty'] ?></td>
                    <td class="mailbox-star" width="%"><?php echo $val['expectedDeliveryDate'] ?></td>
                    <td class="mailbox-star" style="text-align: right"><?php echo number_format($val['unittransactionAmount'], 2) ?></td>
                    <td class="mailbox-star" style="text-align: right"><?php
                        $lineTotal = $val['requestedQty'] * $val['unittransactionAmount'];
                        echo number_format($lineTotal, 2)
                        ?></td>
                    <td class="mailbox-attachment taskaction_td"><span class="pull-right"><a
                                onclick="delete_order_detail(<?php echo $val['contractDetailsAutoID'] ?>);"><span
                                    title="Delete" rel="tooltip"
                                    class="glyphicon glyphicon-trash"
                                    style="color:rgb(209, 91, 71);"></span></a></span>
                    </td>
                </tr>
                <?php
                $total += $val['unittransactionAmount'];
                $grandTotal += $lineTotal;
                $x++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="mailbox-star">Total</td>
                <td class="mailbox-star" style="text-align: right"><?php echo number_format($total, 2) ?></td>
                <td class="mailbox-star" style="text-align: right"><?php echo number_format($grandTotal, 2) ?></td>
                <td class="mailbox-star"></td>
            </tr>
            </tfoot>
        </table><!-- /.table -->
    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO PRODUCTS TO DISPLAY.</div>
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