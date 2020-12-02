
<!--Translation added by Naseek-->
<?php





$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_leave_management', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$this->lang->load('calendar', $primaryLanguage);
$title = $this->lang->line('hrms_leave_management_leave_group');
echo head_page($title, false);






?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">


    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-primary pull-right" onclick="fetchPage('system/hrm/new_leave_group','','Add Leave group','Leave Group');"><i class="fa fa-plus"></i> <?php echo $this->lang->line('hrms_leave_management_create_new');?><!--Create New --></button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="leave_Group_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="min-width: 25%"><?php echo $this->lang->line('common_description');?><!--Description--></th>

            <!--<th style="min-width: 15%">Default</th>-->


            <th style="width: 5%">&nbsp;</th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.headerclose').click(function(){
            fetchPage('system/hrm/leave_group','','HRMS')
        });
        leave_Group_table();
    });

    function leave_Group_table(){
        var Otable = $('#leave_Group_table').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Employee/leave_group_master'); ?>",
            "aaSorting": [[0, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                if (oSettings.bSorted || oSettings.bFiltered) {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                    }
                }

            },
            "aoColumns": [
                {"mData": "leaveGroupID"},
                {"mData": "description"},

                /*{"mData": "isDefault"},*/
                {"mData": "action"}

            ],
            //"columnDefs": [{"targets": [2], "orderable": false}],
            "columnDefs": [{"searchable": false, "targets": [0]}],
            "fnServerData": function (sSource, aoData, fnCallback) {
                //aoData.push({ "name": "filter","value": $(".pr_Filter:checked").val()});
                //aoData.push({ "name": "subcategory","value": $("#subcategory").val()});
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function deleteLeave(id){
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",
                text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55 ",
                confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async : true,
                    type : 'post',
                    dataType : 'json',
                    data : {'leaveGroupID':id},
                    url :"<?php echo site_url('Employee/delete_leaveGroup'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success : function(data){
                        refreshNotifications(true);
                        myAlert('s','Successfully deleted');
                        leave_Group_table();

                        stopLoad();
                    },error : function(){
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }


</script>