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
        background-color: #337ab7;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 2px 5px 2px 5px;
        line-height: 14px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #fffbfb;
    }
</style>
<?php
if (!empty($header)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr>
                <td class="headrowtitle" style="border-bottom: 1px solid #d21d1d;">#</td>
                <td class="headrowtitle" style="border-bottom: 1px solid #d21d1d;"><?php echo $this->lang->line('common_code');?><!--Code--></td>
                <td class="headrowtitle" style="border-bottom: 1px solid #d21d1d;"><?php echo $this->lang->line('common_supplier_name');?><!--Supplier Name--></td>
                <td class="headrowtitle" style="border-bottom: 1px solid #d21d1d; text-align: center"><?php echo $this->lang->line('common_action');?><!--Action--></td>
            </tr>
            <?php
            $x = 1;
            foreach ($header as $val) {
                ?>
                <tr>
                    <td class="mailbox-name">
                        <?php echo $x; ?>
                    </td>
                    <td class="mailbox-star"><?php echo $val['supplierSystemCode']; ?></td>
                    <td class="mailbox-star"><?php echo $val['supplierName']; ?></td>
                    <td class="mailbox-star">
                        <?php
                        if ($val['isRfqEmailed'] == 1) { ?>
                            <span class="pull-right"><div class="actionicon"><a target="_blank" onclick="view_rfq_printModel(<?php echo $val['inquiryMasterID']; ?>,<?php echo $val['supplierID']; ?>)"><span title="" rel="tooltip" class="glyphicon glyphicon-eye-open" data-original-title="View" style="color: white;"></span></a></div>&nbsp&nbsp|&nbsp&nbsp<div class="actionicon"><span class="glyphicon glyphicon-ok" style="color:rgb(255, 255, 255);" title="completed"></span></div></span>
                            <?php
                        } else { ?>
                            <span class="pull-right"><div class="actionicon"><a target="_blank" onclick="view_rfq_printModel(<?php echo $val['inquiryMasterID']; ?>,<?php echo $val['supplierID']; ?>)"><span title="" rel="tooltip" class="glyphicon glyphicon-eye-open" data-original-title="View" style="color: white;"></span></a></div> | <a href="#"
                                    onclick="send_rfq_supplier(<?php echo $val['inquiryMasterID']; ?>,<?php echo $val['supplierID']; ?>)">
                                    <div class="actionicon"><?php echo $this->lang->line('srm_send_rfq');?><!--Send RFQ--></div></a></span>
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
    <div class="search-no-results"><?php echo $this->lang->line('srm_there_are_no_customer_order_items');?><!--THERE ARE NO CUSTOMER ORDER ITEMS TO DISPLAY-->.</div>
    <?php
}
?>