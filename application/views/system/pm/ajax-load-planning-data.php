<style>

    #cTable tbody tr.highlight td {
        background-color: #FFEE58;
    }


</style>
<div style="padding-bottom: 27px;">

</div>
<table id="cTable" class="table " style="width: 100%">
    <thead>
    <tr>
        <!-- <th>Account</th>-->

        <th style="">Task</th>

        <th style="">Note</th>
        <th style="">Start Date</th>
        <th style="">End Date</th>
        <th>Color</th>
        <th style="width: 30px">completed %</th>
        <th style="">Assigned Employee</th>
        <th style="">Sort Order</th>

        <th style="width: 60px"></th>
    </tr>
    </thead>
    <tbody>
    <?php

      if ($header) {

        foreach ($header as $value) {

          if ($value['masterID'] == 0) {

            ?>


              <tr class="header">
                  <td colspan=""><a href="#"><i class="fa fa-minus-square"
                                                aria-hidden="true"></i></a>

                      <a href="#" data-type="text"
                         data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                         data-pk="<?php echo $value['projectPlannningID'] ?>"
                         data-name="description"
                         data-title="Task" class="xeditable "
                         data-value="<?php echo $value['description'] ?>">
                        <?php echo $value['description'] ?>
                      </a>
                  </td>

                  <td>
                      <a href="#" data-type="text"
                         data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                         data-pk="<?php echo $value['projectPlannningID'] ?>"
                         data-name="note"
                         data-title="Note" class="xeditable "
                         data-value="<?php echo $value['note'] ?>">
                        <?php echo $value['note'] ?>
                      </a>
                  </td>
                  <td style="text-align: center"><?php echo $value['startDate'] ?></td>
                  <td style="text-align: center"><?php echo $value['endDate'] ?></td>
                  <td style="text-align: center"><a href="#" data-type="select"
                                                    data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                                                    data-pk="<?php echo $value['projectPlannningID'] ?>"
                                                    data-name="bgColor"
                                                    data-title="Task" class="status"
                                                    data-value="<?php echo $value['bgColor'] ?>">
                      <?php //echo $subvalue['bgColor'] ?>
                      </a></td>
                  <td style="text-align: center">

                   <!--   <a href="#" data-type="number"
                         data-url="<?php /*echo site_url('Boq/update_project_planning') */?>"
                         data-pk="<?php /*echo $value['projectPlannningID'] */?>"
                         data-name="percentage"
                         data-title="Percentage" class="xeditable "
                         data-value="<?php /*echo $value['percentage'] */?>">
                        <?php /*echo $value['percentage'] */?>
                      </a>-->
                  </td>
                  <td><?php echo $value['ename2'] ?></td>
                  <td>
                      <select name="sortOrder" id="inlinesortOrder" onchange="changeSortOrder('m',this.value,<?php echo $value['projectPlannningID'] ?>)">
                        <?php if (!empty($sortOrder)) {
                          foreach ($sortOrder as $s) {
                            $select = '';
                            if ($value['sortOrder'] == $s['sortOrder']) {
                              $select = 'selected';
                            }
                            ?>
                              <option <?php echo $select ?>
                                      value="<?php echo $s['sortOrder'] ?>"><?php echo $s['sortOrder'] ?></option>
                            <?php
                          }
                        } ?>
                      </select>
                  </td>
                  <td class="pull-right" colspan=""><a
                              onclick="addplanningSub(<?php echo $value['projectPlannningID'] ?>,'<?php echo $value['description'] ?>')">
                          <i class="fa fa-plus"
                             aria-hidden="true"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a
                              onclick="deleteplanningTask(<?php echo $value['projectPlannningID'] ?>)"><span
                                  style="color:#ff3f3a" class="glyphicon glyphicon-trash "></span></a></td>


              </tr>


            <?php
          }

          foreach ($header as $subvalue) {


            if ($value['projectPlannningID'] == $subvalue['masterID']) {

              ?>
                <tr class="subheader">

                    <td style="padding-left: 35px"><a href="#" data-type="text"
                                                      data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                                                      data-pk="<?php echo $subvalue['projectPlannningID'] ?>"
                                                      data-name="description"
                                                      data-title="Task" class="xeditable "
                                                      data-value="<?php echo $subvalue['description'] ?>">
                        <?php echo $subvalue['description'] ?>
                        </a></td>
                    <td><a href="#" data-type="text"
                           data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                           data-pk="<?php echo $subvalue['projectPlannningID'] ?>"
                           data-name="description"
                           data-title="Task" class="xeditable "
                           data-value="<?php echo $subvalue['note'] ?>">
                        <?php echo $subvalue['note'] ?>
                        </a></td>
                    <td style="text-align: center"><?php echo $subvalue['startDate'] ?></td>
                    <td style="text-align: center"><?php echo $subvalue['endDate'] ?></td>
                    <td style="text-align: center"><a href="#" data-type="select"
                                                      data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                                                      data-pk="<?php echo $subvalue['projectPlannningID'] ?>"
                                                      data-name="bgColor"
                                                      data-title="Task" class="status"
                                                      data-value="<?php echo $subvalue['bgColor'] ?>">
                        <?php echo $subvalue['bgColor'] ?>
                        </a></td>
                    <td style="text-align: center"><a href="#" data-type="number"
                                                      data-url="<?php echo site_url('Boq/update_project_planning') ?>"
                                                      data-pk="<?php echo $subvalue['projectPlannningID'] ?>"
                                                      data-name="percentage"
                                                      data-title="Percentage" class="xeditable "
                                                      data-value="<?php echo $subvalue['percentage'] ?>">
                        <?php echo $subvalue['percentage'] ?>
                        </a></td>
                    <td><?php echo $subvalue['ename2'] ?></td>
                    <td>
                        <select name="sortOrder" id="inlinesortOrder" onchange="changeSortOrder('s',this.value,<?php echo $subvalue['masterID'] ?>,<?php echo $subvalue['projectPlannningID'] ?>)">

                          <?php
                            $CI         = get_instance();
                            $sortOrder2 = $CI->db->query("select sortOrder from srp_erp_projectplanning where masterID={$subvalue['masterID']}")->result_array();
                            if (!empty($sortOrder2)) {
                              foreach ($sortOrder2 as $so) {
                                $select = '';
                                if ($subvalue['sortOrder'] == $so['sortOrder']) {
                                  $select = 'selected';
                                }
                                ?>
                                  <option <?php echo $select ?>
                                          value="<?php echo $so['sortOrder'] ?>"><?php echo $so['sortOrder'] ?></option>
                                <?php
                              }
                            } ?>
                        </select>
                    </td>
                    <td colspan=""><a onclick="deleteplanningTask(<?php echo $subvalue['projectPlannningID'] ?>)"><span
                                    style="color:#ff3f3a" class="glyphicon pull-right glyphicon-trash "></span></a></td>
                </tr>
              <?php
            }
          }


        }

      }
    ?>
    </tbody>
</table>

<script>

    function changeSortOrder(type,value,id,masterID){
        $.ajax({
            async: false,
            type: 'post',
            dataType: 'json',
            data: {'type': type,value:value,id:id,masterID:masterID},
            url: "<?php echo site_url('Boq/change_projectplanningSortOrder'); ?>",
            beforeSend: function () {
                HoldOn.open({
                    theme: "sk-bounce", message: "<h4> Please wait until page load! </h4>",
                });
            },
            success: function (data) {

                loadTaskData($('#headerID').val());
                getchart();
                HoldOn.close();
                refreshNotifications(true);

            }, error: function () {

                HoldOn.close();
                alert('An Error Occurred! Please Try Again.');
                refreshNotifications(true);
            }
        });
    }

    $('.tags').editable({
        inputclass: 'input-large',
        select2: {
            tags: ['html', 'javascript', 'css', 'ajax'],
            tokenSeparators: [",", " "]
        }
    });

    $('.status').editable({

        source: [
            {value: "ggroupblack", text: 'Black'},
            {value: "gtaskblue", text: 'Blue'},
            {value: "gtaskred", text: 'Red'},
            {value: "gtaskpurple", text: 'Purple'},
            {value: "gtaskgreen", text: 'Green'},
            {value: "gtaskpink", text: 'Pink'}

        ]
    });
    $('.xeditable').editable();
    $('#cTable').on('click', 'tr', function (e) {
        $('#cTable').find('tr.highlight').removeClass('highlight');
        $(this).addClass('highlight');
    });


    function highlightSearch(searchtext) {
        $('#cTable tr').each(function () {
            $(this).removeClass('highlight');
        });
        if (searchtext !== '') {
            $('#cTable tr').each(function () {
                if ($(this).find('td').text().toLowerCase().indexOf(searchtext.toLowerCase()) == -1) {

                    $(this).removeClass('highlight');
                }
                else {
                    $(this).addClass('highlight');
                }
            });
        }
    }

    function deleteplanningTask(projectPlannningID) {

        swal({
                title: "Are you sure?",
                text: "Your will not be able to recover this data",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!"
            },
            function () {
                $.ajax({
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'projectPlannningID': projectPlannningID},
                    url: "<?php echo site_url('Boq/deleteplanning'); ?>",
                    beforeSend: function () {
                        HoldOn.open({
                            theme: "sk-bounce", message: "<h4> Please wait until page load! </h4>",
                        });
                    },
                    success: function (data) {

                        loadTaskData($('#headerID').val());
                        getchart();
                        HoldOn.close();
                        refreshNotifications(true);

                    }, error: function () {

                        HoldOn.close();
                        alert('An Error Occurred! Please Try Again.');
                        refreshNotifications(true);
                    }
                });
            });


    }


</script>