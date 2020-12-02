<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('srm', $primaryLanguage);
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
</style>
<?php
if (!empty($output)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">#</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('srm_inquiry_code');?><!--Inquiry Code--></td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_customer_name');?><!--Customer Name--></td>
                <!--<td class="headrowtitle" style="border-top: 1px solid #ffffff;">Order Code</td>-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_currency');?><!--Currency--></td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;text-align: center"><?php echo $this->lang->line('common_status');?><!--Status--></td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_action');?><!--Action--></td>
            </tr>
            <?php
            $x = 1;
            foreach ($output as $val) {
                ?>
                <tr>
                    <td class="mailbox-name"><a href="#"><?php echo $x ?></a></td>
                    <td class="mailbox-name">
                        <a class="link-person noselect" href="#"  onclick="fetchPage('system/srm/customer-order/order_inquiry_edit_view','<?php echo $val['inquiryID'] ?>','View Order Inquiry','SRM')"><?php echo $val['orderCode'] ?></a>
                    </td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['customerName'] ?></a></td>
                    <!--<td class="mailbox-name"><a href="#"><?php /*echo $val['customerOrderCode'] */?></a></td>-->
                    <td class="mailbox-name"><a href="#"><?php echo $val['CurrencyCode'] ?></a></td>
                    <td class="mailbox-name" style="text-align: center">
                        <?php if ($val['inquiryConfirm'] == 1) { ?>
                            <span class="label"
                                  style="background-color: #8bc34a; color: #FFFFFF; font-size: 11px;"><?php echo $this->lang->line('common_confirmed');?><!--Confirmed--></span>
                        <?php } else {?>
                            <span class="label"
                                  style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;"><?php echo $this->lang->line('common_not_confirmed');?><!--Not Confirmed--></span>
                        <?php } ?>
                    </td>
                    <td class="mailbox-attachment">
                        <span class="pull-right">
                             <?php if ($val['inquiryConfirm'] != 1) { ?>
                            <a href="#"
                               onclick="fetchPage('system/srm/customer-order/create_new_order_inquiry','<?php echo $val['inquiryID'] ?>','<?php echo $this->lang->line('srm_edit_order_inquiry');?>','SRM')"><span
                                    title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;<!--Edit Order Inquiry-->
                             <?php } ?>
                            <a onclick="generated_supplier_RFQ_View(<?php echo $val['inquiryID'] ?>);"><i class="fa fa-envelope" aria-hidden="true" title="Email" style="font-size: 14px"></i></a>&nbsp&nbsp|&nbsp&nbsp
                            <a onclick="delete_customer_inquiry_master(<?php echo $val['inquiryID'] ?>);"><span
                                    title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"
                                    style="color:rgb(209, 91, 71);"></span></a>
                        </span>
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
    <div class="search-no-results"><?php echo $this->lang->line('srm_there_are_no_customer_order_inquiry');?>.</div><!--THERE ARE NO CUSTOMER ORDER INQUIRY TO DISPLAY-->
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