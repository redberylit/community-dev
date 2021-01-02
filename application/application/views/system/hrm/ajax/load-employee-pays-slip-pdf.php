<div class="row">
    <div class="col-md-12 bg-border" style="">
        <?php
        $payrollMasterID = $this->input->post('payrollMasterID');
        $empID = $this->input->post('empID');
        $isNonPayroll = $this->input->post('isNonPayroll');

        if($payrollMasterID != null AND $empID != null){
             /*echo '<a href="'.site_url('template_paySheet/pay_slip').'/'.$payrollMasterID.'/'.$empID .'/'.$isNonPayroll.'/'.current_userCode().'"> click </a>';
             die();*/
            $uri = '/'.$payrollMasterID.'/'.$empID.'/'.$isNonPayroll.'/'.current_userCode();
        ?>
            <div>
                <object
                    data="<?php echo site_url('Template_paysheet/pay_slip').''.$uri; ?>"
                    type="application/pdf" width="100%" height="900">
                    alt : <a
                        href="<<?php echo site_url('Template_paysheet/pay_slip').''.$uri; ?>"
                        target="_blank" style="" class="">
                    <i style="font-size:20px;background-color: #CF000A;color: white" class="fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF
                    </a>
                </object>
            </div>
        <?php
        }
        else {
            ?>
            <div>
                <p> No Records Founds. </p>
            </div>
            <?php
        } ?>
    </div>
</div>