<style type="text/css">
    .saveInputs{ height: 25px; font-size: 11px }
    #country-master-tb td{  padding: 4px 10px; }
</style>

<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_others_master', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('hrms_others_master_country_master');
echo head_page($title  , false);

?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-7 pull-right">
        <button type="button" class="btn btn-primary btn-sm pull-right" onclick="openCountry_modal()" ><i class="fa fa-plus-square"></i>&nbsp; <?php echo $this->lang->line('common_add');?><!--Add--> </button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="load_country" class="<?php echo table_class(); ?> hover">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="width: auto"><?php echo $this->lang->line('common_code');?><!--Code--></th>
            <th style="width: auto"><?php echo $this->lang->line('common_Country');?><!--Country--></th>
            <th style="width: 30px"></th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>


<div class="modal fade" id="country_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('hrms_others_master_add_country');?><!--Add Country--></h4>
            </div>

            <div class="modal-body" id="countryDiv"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="save_country()"><?php echo $this->lang->line('common_save');?><!--Save--></button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var is_loaded = $('#is-loaded-country-master-tb');
    var country_master_tb = $('#country-master-tb');
    var oTable;

    $(document).ready(function() {
        $('.headerclose').click(function(){
            fetchPage('system/hrm/country_master','Test','HRMS');
        });
        load_country();
    });

    function load_country(){
        $('#load_country').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('Employee/fetch_country'); ?>",
            "aaSorting": [[0, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var tmp_i   = oSettings._iDisplayStart;
                var iLen    = oSettings.aiDisplay.length;

                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);


                    /*if( parseInt(oSettings.aoData[x]._aData['payrollMasterID']) == selectedRowID ){
                        var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                        $(thisRow).addClass('dataTable_selectedTr');
                    }*/

                    x++;
                }

            },
            "aoColumns": [
                {"mData": "countryID"},
                {"mData": "countryShortCode"},
                {"mData": "CountryDes"},
                {"mData": "edit"}
            ],
            "columnDefs": [{"searchable": false, "targets": [0,3]}],
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
    }

    function load_masterCountry(){
        oTable = country_master_tb.DataTable({
            "scrollY": "150px",
            "scrollCollapse": true,
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bPaginate": false,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('Employee/fetch_allCountry'); ?>",
            "aaSorting": [[2, 'asc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                if (oSettings.bSorted || oSettings.bFiltered) {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html('<div align="right">'+ (i + 1) +'</divi>');
                    }
                }
                $(".dataTables_empty").text('<?php echo $this->lang->line('common_no_data_available_in_table'); ?>')
                $(".previous a").text('<?php echo $this->lang->line('common_previous'); ?>')
                $(".next  a").text('<?php echo $this->lang->line('common_next'); ?>')
            },
            "aoColumns": [
                {"mData": "countryID"},
                {"mData": "countryShortCode"},
                {"mData": "CountryDes"},
                {"mData": "edit"}
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

        if( is_loaded.val() == 0 ){
            is_loaded.val(1);
            setTimeout(function(){
                oTable.ajax.reload();
            }, 200);

        }
    }

    /*function openCountry_modal(){
        $('#country_modal').modal({backdrop: "static"});
        load_masterCountry();
    }*/
    function openCountry_modal(){
        $('#country_modal').modal({backdrop: "static"});
        var countryDiv = $('#countryDiv');

        $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                url: '<?php echo site_url('Employee/fetch_allCountry'); ?>',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    setTimeout(function(){
                        stopLoad();
                        countryDiv.html(data);
                    }, 300);

                }, error: function () {
                    myAlert('e', '<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                }
            });
    }

    function deleteCountry(id, description){
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
                    url :"<?php echo site_url('Employee/deleteCountry'); ?>",
                    type : 'post',
                    dataType : 'json',
                    data : {'hidden-id':id},
                    beforeSend: function () {
                        startLoad();
                    },
                    success : function(data){
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if( data[0] == 's'){ load_country() }
                    },error : function(){
                        stopLoad();
                        myAlert('e', 'error');
                    }
                });
            }
        );
    }
</script>



<?php
/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 2016-11-03
 * Time: 5:27 PM
 */