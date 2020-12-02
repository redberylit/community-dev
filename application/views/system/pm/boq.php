<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('project_management', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('promana_common_project');
echo head_page($title, false);

/*
echo head_page('Project',false); */?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">

    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-primary pull-right"
                onclick="fetchPage('system/pm/erp_boq_estimation_add_new','','Project')" ><i
                    class="fa fa-plus"></i> <?php echo $this->lang->line('common_create_new');?><!--Create New-->
        </button>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="table_boq" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style=""><?php echo $this->lang->line('promana_common_id');?><!--ID--></th>
            <th style=""><?php echo $this->lang->line('promana_pm_project_code');?><!--Project Code--></th>
            <th style=""><?php echo $this->lang->line('common_customer_name');?><!--Customer Name--></th>

            <th style="width: 55px;"><?php echo $this->lang->line('common_document_date');?><!--Document Date--></th>
            <th style="width: 55px;"><?php echo $this->lang->line('common_start_date');?><!--Start Date--></th>
            <th style="width: 55px;"><?php echo $this->lang->line('common_end_date');?><!--End Date--></th>
            <th style=""><?php echo $this->lang->line('common_comment');?><!--Comment--></th>
            <th style="width: 30px;"><?php echo $this->lang->line('common_confirmed');?><!--Confirmed--></th>
            <th style="width: 30px"><?php echo $this->lang->line('common_approved');?><!--Approved--></th>
            <th style="width: 30px"></th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>
<script type="text/javascript">

    var Otable;
    $(document).ready(function() {
        $('.headerclose').click(function(){
            fetchPage('system/pm/boq','','Project');
        });

    });

    window.Otable=  $('#table_boq').DataTable({
        "language": {
            "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
        },
        "bProcessing": true,
        "bServerSide": true,
        "bDestroy": true,
        "StateSave": true,
        "sAjaxSource": "<?php echo site_url('Boq/fetch_Boq_headertable'); ?>",
        "aaSorting": [[1, 'desc']],
        "fnInitComplete": function () {

        },
        "fnDrawCallback": function (oSettings) {
            $("[rel=tooltip]").tooltip();
            var
                tmp_i = oSettings._iDisplayStart;
            var
                iLen = oSettings.aiDisplay.length;
            var
                x = 0;
            for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                x++;
            }

        },
        "aoColumns": [
            {"mData": "headerID"},
            {"mData": "projectCode"},
            {"mData": "customerName"},

            {"mData": "createdDateTime"},
            {"mData": "projectDateFrom"},
            {"mData": "projectDateTo"},
            {"mData": "comment"},
            {"mData": "confirmedYN"},
            {"mData": "approvedYN"},
            {"mData": "action"}
        ],
        "fnServerData": function (sSource, aoData, fnCallback) {
            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': sSource,
                'data': aoData,
                'success': fnCallback
            });
        }
    });

    function deleteBoqHeader(headerID){
        if (headerID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*Your want to delete this record*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('promana_common_yes_delete_it');?>",/*Yes, delete it!*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: false,
                        type: 'post',
                        dataType: 'json',
                        data: {'headerID':headerID},
                        url: "<?php echo site_url('Boq/deleteBoqHeader'); ?>",
                        beforeSend: function () {
                            HoldOn.open({
                                theme: "sk-bounce", message: "<h4> <?php echo $this->lang->line('promana_common_please_wait_untill_page_load');?><!--Please wait until page load!--> </h4>",
                            });
                        },
                        success: function (data) {
                            HoldOn.close();
                           myAlert(data[0],data[1]);
                            Otable.ajax.reload();
                        }, error: function () {
                            HoldOn.close();
                            alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                            refreshNotifications(true);
                        }
                    });
                });
        };
    }





</script>