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
            <tbody>
            <?php
            $x = 1;
            foreach ($header as $val) { ?>
                <tr>
                    <td class="mailbox-name"><div class="contact-box">
                            <?php if($val['contactImage'] != ''){ ?>
                                <img class="align-left" src="<?php echo base_url('uploads/crm/profileimage/'.$val['contactImage']); ?>" width="40" height="40">
                                <?php
                            } else { ?>
                                <img class="align-left" src="<?php echo base_url("images/crm/icon-list-contact.png") ?>" alt="" width="40" height="40">
                            <?php } ?>
                            <div class="link-box"><strong class="contacttitle"><a class="link-person noselect" href="#"  onclick="fetchPage('system/crm/contact_edit_view','<?php echo $val['contactID'] ?>','View Contact','CRM',<?php echo $masterID ?>)"><?php echo $val['firstName']." ".$val['lastName'] ?></a><br><?php echo $val['email'] ?></a></strong></div></div>
                    </td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['organization']; ?></a></td>
                    <td class="mailbox-name"><a href="#"><?php echo $val['phoneMobile']; ?></a></td>
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
    <div class="search-no-results">THERE ARE NO CONTACTS TO DISPLAY.</div>
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