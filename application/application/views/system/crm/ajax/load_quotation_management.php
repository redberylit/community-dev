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
    .actionicon{
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
    .headrowtitle{
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
    .numberColoring{
        font-size: 13px;
        font-weight: 600;
        color: saddlebrown;
    }
</style>
<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
if (!empty($header)) {
    //print_r($header);
    ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat noselect" style="background: white;">
                <td class="task-cat-upcoming" colspan="10">
                    <div class="task-cat-upcoming-label">Quotations</div><!--Latest Quotations-->
                    <div class="taskcount"><?php echo sizeof($header) ?></div>
                </td>
            </tr>
            <tr>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"></td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_code');?></td><!--Code-->
                <?php
                if($page == "master"){ ?>
                    <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('crm_opportunity');?></td><!--Opportunity-->
                <?php } ?>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('crm_organization');?> </td><!--Organization-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_document_date');?></td><!--Document Date-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('crm_expire_date');?> </td><!--Expire Date-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_currency');?></td><!--Currency-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_value');?></td><!--Value-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;"><?php echo $this->lang->line('common_status');?></td><!--Status-->
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;text-align: center"><?php echo $this->lang->line('common_action');?></td><!--Action-->
            </tr>
            <?php
            $x = 1;
            foreach ($header as $val) {
                ?>
                <tr>
                    <td class="mailbox-name"><a href="#" class="numberColoring"><?php echo $x; ?></a></td>
                    <td class="mailbox-name"><a href="#"
                                                onclick="#" ><?php
                            echo $val['quotationCode'];
                            ?></a>
                    </td>
                    <?php if($page == "master"){ ?>
                    <td class="mailbox-name"><a href="#"><?php echo $val['opportunityName']; ?></a></td>
                    <?php } ?>
                    <td class="mailbox-name"><a href="#"><?php echo $val['fullname']; ?></a></td>
                    <td class="mailbox-name"><a href="#" ><?php echo $val['quotationDate'];  ?></a></td>
                    <td class="mailbox-name"><a href="#" ><?php echo $val['quotationExpDate'];  ?></a></td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['CurrencyCode']; ?></a></td>
                    <td class="mailbox-name" style="text-align: right"><a href="#"><?php
                            $detailValue = $this->db->query("SELECT sum(requestedQty * unittransactionAmount) AS totalValue FROM srp_erp_crm_quotationdetails WHERE contractAutoID={$val['quotationAutoID']}")->row_array();
                            if(!empty($detailValue)){
                                    echo number_format($detailValue['totalValue'], 2);
                            }else {
                                echo number_format(0, 2);
                            }
                             ?>
                        </a></td>
                    <td class="mailbox-name" style="text-align: center">
                        <?php if ($val['confirmedYN'] == 1) { ?>
                            <span class="label"
                                  style="background-color: #8bc34a; color: #FFFFFF; font-size: 11px;"><?php echo $this->lang->line('common_confirmed');?> </span><!--Confirmed-->
                        <?php } else {?>
                            <span class="label"
                                  style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;"><?php echo $this->lang->line('common_not_confirmed');?> </span><!--Not Confirmed-->
                        <?php } ?>
                    </td>
                    <td class="mailbox-attachment"><span class="pull-right">
                            <?php
                            if($val['confirmedYN'] == 1){ ?>
                                <a target="_blank" onclick="view_quotation_printModel(<?php echo $val['quotationAutoID'] ?>)"><span title="" rel="tooltip" class="glyphicon glyphicon-eye-open" data-original-title="View"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('crm/quotation_print_view/'). '/' .$val['quotationAutoID'] ?>"><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                <div class="actionicon"><span class="glyphicon glyphicon-ok" style="color:rgb(255, 255, 255);" title="completed"></span</div>
                                <?php
                            }else{

                           /* if($page != "master"){ */?>
                                <a href="#" onclick="fetchPage('system/crm/create_new_quotation','<?php echo $val['quotationAutoID'] ?>','Edit Quotation', 4, <?php echo $val['opportunityID'] ?>)"><span
                                        title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <?php /*}
                            */?>
                            <a target="_blank" onclick="view_quotation_printModel(<?php echo $val['quotationAutoID'] ?>)"><span title="" rel="tooltip" class="glyphicon glyphicon-eye-open" data-original-title="View"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="<?php echo site_url('crm/quotation_print_view/'). '/' .$val['quotationAutoID'] ?>"><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a
                                onclick="delete_crm_quotation(<?php echo $val['quotationAutoID'] ?>);"><span title="Delete"
                                                                                                   rel="tooltip"
                                                                                                   class="glyphicon glyphicon-trash"
                                                                                                   style="color:rgb(209, 91, 71);"></span></a></span>
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
    <div class="search-no-results"><?php echo $this->lang->line('crm_there_are_no_quotation_to_display');?> .</div><!--THERE ARE NO QUOTATION TO DISPLAY-->
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