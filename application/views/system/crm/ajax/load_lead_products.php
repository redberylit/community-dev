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
</style>
<?php
if (!empty($header)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <thead>
            <th>#</th>
            <th style="text-align: left">Product Name</th>
            <th style="text-align: left">Product Description</th>
            <th style="text-align: right">Transaction Amount</th>
            <th style="text-align: right">Reporting Amount</th>
            <th style="text-align: right">Local Amount</th>
            <th style="text-align: center">Action</th>
            </thead>
            <tbody>
            <?php
            $x = 1;
            $total = 0;
            $totallocal=0;
            $totalcurr=0;
            $reportingCurrency = '';
            foreach ($header as $val) {
                $reportingCurrency = $val['reportingcurrency'];
                $reportingPrice = ($val['price'] / $val['companyReportingCurrencyExchangeRate']);

                $transactioncurrency = $val['transactioncurrency'];
                $transactionprice = ($val['price'] / $val['transactionExchangeRate']);

                $localcurrency =  $val['currencycodelocal'];
                $localcurrencyprice = ($val['price'] / $val['companyLocalCurrencyExchangeRate']);

                ?>
                <tr>
                    <td class="mailbox-name"><a href="#"><?php echo $x; ?></a></td>
                    <td class="mailbox-name" style="text-align: left;width: 20%;"><a href="#"><?php echo $val['productName']; ?></a></td>
                    <td class="mailbox-name" style="width: 14%;"><a href="#"><?php echo $val['productDescription']; ?></a></td>

                    <td class="mailbox-name" style="text-align: right;width: 15%"><a href="#"><?php echo $transactioncurrency .':'. number_format($transactionprice, $val['transactionCurrencyDecimalPlaces']) ?></a></td>


                    <td class="mailbox-name" style="text-align: right;width: 15%"><a href="#"><?php echo $reportingCurrency .':'. number_format($reportingPrice, $val['companyReportingCurrencyDecimalPlaces']) ?></a></td>

                    <td class="mailbox-name" style="text-align: right;width: 15%"><a href="#"><?php echo $localcurrency .':'. number_format($localcurrencyprice, $val['companyLocalCurrencyDecimalPlaces']) ?></a></td>
                   <!-- <td class="mailbox-name" style="text-align: right">
                        <a href="#"><?php /*echo  number_format($reportingPrice, $val['companyReportingCurrencyDecimalPlaces']) */?></a>
                    </td>-->
                    <td class="mailbox-attachment taskaction_td"><span class="pull-right">
                            <a onclick="edit_lead_product(<?php echo $val['leadProductID'] ?>);"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-pencil" style="color:blue;"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="delete_lead_product(<?php echo $val['leadProductID'] ?>);"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a></span>
                    </td>
                </tr>
                <?php
                $x++;
                $total += $reportingPrice;
                $totallocal += $localcurrencyprice;
                $totalcurr += $transactionprice;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">Total</td>

                <td style="text-align: right"></td>
                <td style="text-align: right"><?php echo $reportingCurrency .' ('. format_number($total,$val['companyReportingCurrencyDecimalPlaces']).')' ?></td>
                <td style="text-align: right"><?php echo $localcurrency .' ('. format_number($totallocal,$val['companyLocalCurrencyDecimalPlaces']).')' ?></td>
                <td>&nbsp;</td>
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