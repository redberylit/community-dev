<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('project_management', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('promana_pm_project_master');
echo head_page($title, false);
/*
echo head_page('Project Master', FALSE);*/
  $currency_arr =all_currency_drop(TRUE,'ID');
  $service_line_arr = fetch_segment(TRUE);
  $date_format_policy = date_format_policy();
?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">

    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-primary pull-right"
                onclick="modalproject()"><i
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
            <th style=""><?php echo $this->lang->line('promana_common_project_name');?><!--ProjectName--></th>
            <th style=""><?php echo $this->lang->line('common_description');?><!--Description--></th>
            <th style="width: 55px;"><?php echo $this->lang->line('common_currency');?><!--Currency--></th>
            <th style="width: 55px;"><?php echo $this->lang->line('common_segment');?><!--Segment--></th>
            <th style=""><?php echo $this->lang->line('common_start_date');?><!--Start Date--></th>
            <th style=""><?php echo $this->lang->line('common_end_date');?><!--End Date--></th>

            <th style="width: 50px"></th>
        </tr>
        </thead>
    </table>
</div>

<div style="" aria-hidden="true" role="dialog"  id="modalproject" class="modal fade">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h5 id="title" class="modal-title"><?php echo $this->lang->line('promana_pm_project_master');?><!--Project Master--></h5>
            </div>
            <form id="submitform" class="form-horizontal" role="form">
            <div class="modal-body">
                <div class="row" style="margin:5px">





                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="textinput"><?php echo $this->lang->line('promana_common_project_name');?><!--Project Name--></label>
                                <div class="col-sm-4">
                                    <input type="text" id="projectName" name="projectName" class="form-control">
                                </div>

                                <label class="col-sm-2 control-label" for="textinput"><?php echo $this->lang->line('common_segment');?><!--Segment--></label>
                                <div class="col-sm-4">
                                  <?php echo form_dropdown('segementID', $service_line_arr, '', 'class="form-control searchbox" id="segementID" required'); ?>
                                </div>
                            </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="textinput"><?php echo $this->lang->line('common_start_date');?><!--Start Date--></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>

                                    <input type="text" name="projectStartDate" value="" id="projectStartDate"
                                           class="form-control dateFields"
                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'">
                                </div>
                            </div>

                            <label class="col-sm-2 control-label" for="textinput"><?php echo $this->lang->line('common_end_date');?><!--End Date--></label>
                            <div class="col-sm-4">

                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>

                                    <input type="text" name="projectEndDate" value="" id="projectEndDate"
                                           class="form-control dateFields"
                                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="textinput"><?php echo $this->lang->line('common_currency');?><!--Currency--></label>
                            <div class="col-sm-4">
                              <?php echo form_dropdown('projectCurrencyID', $currency_arr, '', ' class="form-control searchbox" id="projectCurrencyID" required'); ?>
                            </div>

                            <label class="col-sm-2 control-label" for="textinput"><?php echo $this->lang->line('common_description');?><!--Description--></label>
                            <div class="col-sm-4">
                                <textarea  id="description" name="description" class="form-control"></textarea>
                            </div>
                        </div>








                </div>

            </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('common_save');?><!--Save--></button>
                    <button data-dismiss="modal" class="btn btn-default" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>

                </div>
            </form>
            </div>

        </div>

    </div>

  <?php echo footer_page('Right foot', 'Left foot', FALSE); ?>
    <script type="text/javascript">
        var masterID = null;
        $(document).ready(function () {
            $('.headerclose').click(function () {
                fetchPage('system/pm/project', '', 'Project');
            });
            $(".searchbox").select2();
            Inputmask().mask(document.querySelectorAll("input"));
            loadtable();
        });

        function modalproject() {
             masterID = null;
            $('#modalproject').modal('show');
            $("#projectCurrencyID").prop('disabled', false);
            $("#projectName").prop('disabled', false);
        }
function loadtable(){


        window.Otable = $('#table_boq').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('Boq/fetch_Boq_projectTable'); ?>",
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
                {"mData": "projectID"},
                {"mData": "projectName"},
                {"mData": "description"},
                {"mData": "CurrencyCode"},
                {"mData": "segment"},
                {"mData": "projectStartDate"},
                {"mData": "projectEndDate"},

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
}

        $('#submitform').bootstrapValidator({
            live: 'enabled',
            message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
            /* feedbackIcons: {
             valid: 'glyphicon glyphicon-ok',
             invalid: 'glyphicon glyphicon-remove',
             validating: 'glyphicon glyphicon-refresh'
             },*/
            excluded: [':disabled'],
            fields: {


                projectName: {validators: {notEmpty: {message: '<?php echo $this->lang->line('promana_pm_project_name_is_required');?>.'}}},/*Project Name is required*/
                segementID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_segment_is_required');?>.'}}},/*Segement is required*/
                projectStartDate: {validators: {notEmpty: {message: '<?php echo $this->lang->line('promana_pm_start_date_is_required');?>.'}}},/*StartDate is required*/
                projectCurrencyID: {validators: {notEmpty: {message: '<?php echo $this->lang->line('promana_pm_end_date_is_required');?>.'}}}/*End Date is required*/

            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'projectID', 'value': masterID});

            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('Boq/save_project'); ?>",
                    beforeSend: function () {
                        HoldOn.open({
                            theme: "sk-bounce", message: "<h4> Please wait until page load! </h4>",
                        });
                    },
                    success: function (data) {
                        $('#modalproject').modal('hide');
                        $form.bootstrapValidator('resetForm', true);
                            myAlert(data[0],data[1]);
                        loadtable();
                        HoldOn.close();

                    }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    HoldOn.close();
                    refreshNotifications(true);
                }
                });
        });


        function delete_project(projectID) {
            if (projectID) {
                swal({
                        title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                        text: "<?php echo $this->lang->line('promana_common_you_will_not_be_able');?>",/*Your will not be able to recover this data*/
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
                            data: {'projectID': projectID},
                            url: "<?php echo site_url('Boq/delete_project'); ?>",
                            beforeSend: function () {
                                HoldOn.open({
                                    theme: "sk-bounce", message: "<h4> Please wait until page load! </h4>",
                                });
                            },
                            success: function (data) {

                                myAlert(data[0],data[1]);


                                loadtable();
                                HoldOn.close();
                                refreshNotifications(true);

                            }, error: function () {

                                HoldOn.close();
                                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                                refreshNotifications(true);
                            }
                        });
                    });
            };
        }

       function  edit_project(projectID){
           $.ajax({
               async: false,
               type: 'post',
               dataType: 'json',
               data: {'projectID': projectID},
               url: "<?php echo site_url('Boq/get_project_data'); ?>",
               beforeSend: function () {
                   HoldOn.open({
                       theme: "sk-bounce", message: "<h4> Please wait until page load! </h4>",
                   });
               },
               success: function (data) {

                    $('#modalproject').modal('show');
                   $('#projectCurrencyID').val(data['projectCurrencyID']).change();

                    $('#projectEndDate').val(data['projectEndDate']);


                    masterID = data['projectID'];

                    $('#projectName').val(data['projectName']);

                    $('#projectStartDate').val(data['projectStartDate']);

                   $('#projectType').val(data['projectType']); $('#description').val(data['description']);

                    $('#segementID').val(data['segmentID']).change();

                   $("#projectCurrencyID").prop('disabled', true);
                   $("#projectName").prop('disabled', true);


                   HoldOn.close();
                   refreshNotifications(true);

               }, error: function () {

                   HoldOn.close();
                   alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                   refreshNotifications(true);
               }
           });
        }


    </script>