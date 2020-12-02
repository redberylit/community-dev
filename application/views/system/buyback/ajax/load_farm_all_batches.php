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
    .stars
    {
        display: inline-block;color: #F0F0F0;text-shadow: 0 0 1px #666666;font-size:30px;
    }  .highlights,
       .selectedstars {color:#F4B30A;text-shadow: 0 0 1px #F48F0A;}
    .truncate {
        max-width: 80px;
        overflow: hidden;
        display: inline-block;
        text-overflow: ellipsis;
        white-space: nowrap;
        color:#333 ;
    }

    .truncate:hover {
        position: absolute;
        max-width: none;
        z-index: 100;
        overflow: visible;
        top: -6px;
        padding: 5px 20px 5px 0;
    }
    .wrappingcls{
        height: 1.2em;
        position: relative;
        display: inline-block;
        margin-top: 5px;
    }


</style>
<?php
if (!empty($batch)) { ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr class="task-cat-upcoming">
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">#</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Batch Code</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Start Date</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Closing Date</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;">Description</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center"">Grading</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Status</td>
                <td class="headrowtitle" style="border-bottom: solid 1px #f76f01;text-align: center">Action</td>
            </tr>
            <?php
            $x = 1;
            foreach ($batch as $val) {
                ?>
                <tr>
                    <td class="mailbox-star" width="5%" style="vertical-align: middle;"><?php echo $x; ?></td>
                    <td class="mailbox-star" width="10%" style="vertical-align: middle;"><?php echo $val['batchCode'] ?></td>
                    <td class="mailbox-star" width="10%" style="vertical-align: middle;"><?php echo $val['batchStartDate'] ?></td>
                    <td class="mailbox-star" width="12%" style="vertical-align: middle;"><?php echo $val['batchClosingDate'] ?></td>
                    <td class="mailbox-star" width="10%" style="vertical-align: middle;">
                         <span class="wrappingcls">
                        <p class="truncate">
                        <?php echo $val['description'] ?>
                        </p>
                         </span>
                    </td>
                    <td class="mailbox-star" width="20%" style="vertical-align: middle;">

                        <input type="hidden" name="rating" id="rating" value="<?php echo $val["grade"]; ?>" />
                        <ul onMouseOut="resetRating();">
                            <?php
                            for($i=1;$i<=6;$i++) {
                                $selected = "";
                                if($val['batchconfirm']==1 && $val['batchapprovedYN']==1 )
                                {
                                    if(!empty($val["grade"]) && $i<=$val["grade"]) {
                                        $selected = "selectedstars";
                                    }
                                }
                                ?>
                                <li class='stars starsgrading <?php echo $selected; ?>'>★</li>
                            <?php }  ?>
                            <ul>

                    </td>
                    <td class="mailbox-name" width="15%" style="text-align: center; vertical-align: middle">
                        <?php if ($val['isclosed'] == 1) { ?>
                            <span class="label" style="background-color: #F44336; color: #FFFFFF; font-size: 11px;">Closed</span>
                            <?php
                        } else { ?>
                            <span class="label" style="background-color: #8bc34a; color: #FFFFFF; font-size: 11px;">Active</span>
                            <?php
                        }
                        ?>
                    </td>
                    <td class="mailbox-attachment" width="10%" style="vertical-align: middle;">
                        <span class="pull-right"><a href="#" onclick="generateProductionReport(<?php echo $val['batchMasterID'] ?>)"><i class="fa fa-file-text-o" aria-hidden="true" title="Production Statement" rel="tooltip" style="font-size: 14px"></i></a>&nbsp; | &nbsp;<a href="#" onclick="feedScheduleReport_view(<?php echo $val['batchMasterID'] ?>)"><i class="fa fa-bar-chart" aria-hidden="true" title="Feed Shedule" rel="tooltip" style="font-size: 14px"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="edit_farmBatch(<?php echo $val['batchMasterID'] ?>)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
<?php /*echo '<pre>'; print_r($val); echo '</pre>';*/?>
                            <?php if($val['dispatchbatch']==''){?>
                            |&nbsp;&nbsp;<a onclick="delete_farmBatch(<?php echo $val['batchMasterID'] ?>);"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>
                        <?php }?>
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
    <div class="search-no-results">THERE ARE NO BATCHES TO DISPLAY.</div>
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