<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('company_policy_active')) {
    function company_policy_active($isActive, $AutoID)
    {
        $checked = '';
        if ($isActive) {
            $checked = 'checked';
        }

        $status = '<span style="text-align: center;">';
        $status .= '<input ' . $checked . ' onclick="companyPolicyActive(' . $AutoID . ',this)" type="checkbox" id="" name="isActive_' . $AutoID . '">';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('get_policy')) {
    function get_policy($fieldType, $companyPolicyMasterID, $companyValue, $documentID, $isCompanyLevel,$code)
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $element = '';
        switch ($fieldType) {
            case 'select':
                if ($isCompanyLevel) {
                    $values = $CI->db->query("SELECT * FROM srp_erp_companypolicymaster_value WHERE companypolicymasterID='{$companyPolicyMasterID}' AND companyID='{$companyID}'")->result_array();
                } else {
                    $values = $CI->db->query("SELECT * FROM srp_erp_companypolicymaster_value WHERE companypolicymasterID='{$companyPolicyMasterID}'")->result_array();
                }

                $element.=' <div class="input-group">';



                $element .= '<select name="' . $companyPolicyMasterID . '" onchange="ChangePolicy(this)" id="' . $companyPolicyMasterID . '" class="form-control" data-type="' . $documentID . '">';
                /*      $element .= '<option></option>';*/
                foreach ($values as $value) {
                    $selected = $companyValue == $value['systemValue'] ? 'selected' : '';
                    $element .= "<option {$selected} value='{$value['systemValue']}'>{$value['value']}</option>";
                }
                $element .= ' </select>';

                if($code=='PC' && $companyValue==1){
                    $element .= '';
                    $element .='<div class="input-group-addon" style="border: none;"><button type="button" class="btn btn-primary btn-xs pull-right" onclick="addpasswordpolicy()"><i class="fa fa-cog"></i></button></div>';
                    $element .=' </div>';
                }

                break;
            case 'text':
                $element .= "<input name='{$companyPolicyMasterID}' value='{$companyValue}' onchange=\"ChangePolicy(this)\" id='{$companyPolicyMasterID}' class='form-control' data-type='{$documentID}'>";
                break;
            case 'checkbox':

                break;
            case 'radio':

                break;
        }

        return $element;
    }
}