<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('srm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('srm_order_review');
echo head_page($title, false);


/*echo head_page('Order Review', false);*/
$this->load->helper('srm_helper');
$order_inquiry_arr = all_order_inquiries();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/bootstrap/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('plugins/css/autocomplete-suggestions.css'); ?>"/>
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #060606
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

    .posts-holder {
        padding: 0 0 10px 4px;
        margin-right: 10px;
    }

    #toolbar, .past-info .toolbar {
        background: #f8f8f8;
        font-size: 13px;
        font-weight: bold;
        color: #000;
        border-radius: 3px 3px 0 0;
        -webkit-border-radius: 3px 3px 0 0;
        border: #dcdcdc solid 1px;
        padding: 5px 15px 12px 10px;
        height: 20px;
    }

    .past-info {
        background: #fff;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        padding: 0 0 8px 10px;
        margin-left: 2px;
    }

    .title {
        float: left;
        width: 170px;
        text-align: right;
        font-size: 13px;
        color: #7b7676;
        padding: 4px 10px 0 0;
    }

    .search_cancel {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;
        vertical-align: middle;
        padding: 3px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
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
        padding: 1px 5px 0 6px;
        line-height: 14px;
        margin-left: 8px;
        margin-top: 9px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .custome {
        width: 60%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
    }

    .customestyle {
        width: 60%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
        margin-left: -46%
    }

    .customestyle2 {
        width: 80%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
        margin-left: -94%
    }

    .customestyle3 {
        width: 80%;
        background-color: #f2f2f2;
        font-size: 14px;
        font-weight: 500;
        margin-left: -94%
    }

    #search_cancel img {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;

        padding: 4px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .textClose {
        text-decoration: line-through;
        font-weight: 500;
        text-decoration-color: #3c8dbc;
    }


</style>
<div class="row">
    <div class="col-md-12 animated zoomIn">
        <header class="head-title">
            <h2><?php echo $this->lang->line('srm_order_review_header');?><!-- ORDER REVIEW HEADER--> </h2>
        </header>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="form-group col-sm-2">
        <label class="title"> <?php echo $this->lang->line('srm_inquiry_id');?><!--Inquiry ID--> </label>
    </div>
    <div class="form-group col-sm-4">
        <?php echo form_dropdown('inquiryID', $order_inquiry_arr, '', 'class="form-control select2" id="inquiryID" onchange="load_customerInquiry_header()"');
        ?>
    </div>
    <div class="form-group col-sm-2">
        <label class="title"> <?php echo $this->lang->line('common_narration');?><!--Narration--> </label>
    </div>
    <div class="form-group col-sm-4">
        <input type="text" name="narration" id="narration" class="form-control">
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="form-group col-sm-2">
        <label class="title"> <?php echo $this->lang->line('common_customer_name');?><!--Customer Name--></label>
    </div>
    <div class="form-group col-sm-4">
        <input type="text" name="customerName" id="customerName" class="form-control">
    </div>
    <div class="form-group col-sm-2">
        <label class="title"> <?php echo $this->lang->line('srm_reference_number');?><!--Referance Number--> </label>
    </div>
    <div class="form-group col-sm-4">
        <input type="text" name="referanceNumber" id="referanceNumber" class="form-control">
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="form-group col-sm-12">
        <div class="text-right m-t-xs">
            <button class="btn btn-primary" type="button" onclick="orderReview_analyse()"><?php echo $this->lang->line('srm_analyse');?><!--Analyse--></button>
        </div>
    </div>
</div>
<br>
<div id="inquiryDetailView"></div>



<script type="text/javascript">

    var supplierReviewSync = [];
    $(document).ready(function () {
        $('.carousel').carousel({
            interval: false
        })
    });

    function load_customerInquiry_header() {
        var inquiryID = $('#inquiryID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {inquiryID: inquiryID},
            url: "<?php echo site_url('srm_master/load_inquiry_reviewHeader'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#narration').val(data['narration']);
                    $('#customerName').val(data['CustomerName']);
                    $('#referanceNumber').val(data['referenceNumber']);
                }
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function orderReview_analyse(){
        view_supplierAssignModel();
    }

    function view_supplierAssignModel() {
        var inquiryID = $('#inquiryID').val();
        $('#inquiryDetailView').html('');
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {inquiryID: inquiryID},
            url: "<?php echo site_url('srm_master/order_review_detail_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#inquiryDetailView').html(data);
                stopLoad();
            }, error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function orderItem_selected_check(item) {
        console.log(item);
        var value = $(item).val();
        if ($(item).is(':checked')) {
            var inArray = $.inArray(value, supplierReviewSync);
            if (inArray == -1) {
                supplierReviewSync.push(value);
            }
        }
        else {
            var i = supplierReviewSync.indexOf(value);
            if (i != -1) {
                supplierReviewSync.splice(i, 1);
            }
        }
    }

    function generate_review_supplier() {
        var inquiryID = $('#inquiryID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'supplierReviewSync': supplierReviewSync,inquiryID: inquiryID},
            url: "<?php echo site_url('srm_master/generate_order_review_supplier'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    view_supplierAssignModel();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

</script>
