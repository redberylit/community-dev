<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('treasury', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('treasury_tr_lm_loan_management');
echo head_page($title, false);

/*echo head_page('Loan Management',false); */?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-5">
        <table class="<?php echo table_class(); ?>">
            <tr>
                <td><span class="label label-info">&nbsp;</span> <?php echo $this->lang->line('treasury_common_initiated');?><!--Initiated-->
                </td>
                <td><span class="label label-success">&nbsp;</span> <?php echo $this->lang->line('common_approved');?><!--Approved-->
                </td>
                <td><span class="label label-danger">&nbsp;</span> <?php echo $this->lang->line('common_closed');?><!--Closed-->
                </td>

            </tr>
        </table>
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right" onclick="fetchPage('system/bank_rec/erp_loan_mgt_new','','Add Journal Entry','Journal Entry');"><i class="fa fa-plus"></i> <?php echo $this->lang->line('common_create_new');?><!--Create New--> </button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="journal_entry_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="min-width: 12%"><?php echo $this->lang->line('common_code');?><!--Code--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('common_from');?> <!--From--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('common_to');?> <!--To--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('common_bank');?><!--Bank--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('common_narration');?><!--Narration--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('treasury_tr_lm_int');?><!--Int-->.%</th>
            <th style="min-width: 15%"><?php echo $this->lang->line('treasury_tr_lm_cur');?><!--Cur-->.</th>
            <th style="min-width: 15%"><?php echo $this->lang->line('treasury_tr_lm_facility_limit');?><!--Facility Limit--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('treasury_tr_lm_utilized');?><!--Utilized--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('treasury_tr_lm_facility_balance');?><!--Facility Balance--></th>
            <th style="min-width: 15%"><?php echo $this->lang->line('treasury_tr_lm_setltlement');?><!--Settlement--></th>
         <!--   <th style="min-width: 15%">Due Balance</th>-->
            <th style="min-width: 5%"><?php echo $this->lang->line('common_status');?><!--Staus--></th>
            <th style="width: 25%"><?php echo $this->lang->line('common_action');?><!--Action--></th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.headerclose').click(function(){
            fetchPage('system/bank_rec/erp_loan_management','','Loan Management');
        });
      journal_entry_table();
    });

    function journal_entry_table(){
        var Otable = $('#journal_entry_table').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Bank_rec/bankfacilityloan'); ?>",
            "aaSorting": [[1, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "facilityCode"},
                {"mData": "facilityCode"},
                {"mData": "facilityDateFrom"},
                {"mData": "facilityDateTo"},
                {"mData": "bank"},
                {"mData": "narration"},
                {"mData": "rateOfInterest"},
                {"mData": "CurrencyShortCode"},
                {"mData": "amount"},
                {"mData": "utilized"},
                {"mData": "settlement"},
                {"mData": "balance"},  {"mData": "status"},
              /*  {"mData": "action"},*/
                {"mData": "edit"}
            ],
            //"columnDefs": [{"targets": [2], "orderable": false}],
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

    function delete_loan(id){
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this record!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async : true,
                    type : 'post',
                    dataType : 'json',
                    data : {'bankFacilityID':id},
                    url :"<?php echo site_url('Bank_rec/delete_bankloan'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success : function(data){
                        refreshNotifications(true);
                        journal_entry_table();
                        stopLoad();
                    },error : function(){
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

</script>