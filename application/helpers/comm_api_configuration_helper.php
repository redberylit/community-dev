<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//host register
if (!function_exists('com_registerWithApi')) {
    function com_registerWithApi($community_register_url,$first_name,$last_name,$email,$mobile,$password,$password_confirmation)
    {

        $url = $community_register_url;
        $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'mobile' => $mobile,
                'password' => $password,
                'password_confirmation' => $password_confirmation
            );

            $headers = array (
                'Content-Type: application/json',
                'Accept: application/json'
            );
        

        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

        $result = curl_exec ( $ch );
        curl_close ( $ch );

        return true;

    }
}

//host own group
if (!function_exists('com_ownGroupWithApi')) {
    function com_ownGroupWithApi($community_owngroup_url,$mahallahTitle,$mahallahDescription,$bearer_token)
    {

        try {
        $url = $community_owngroup_url;
        $data = array(
                "id" => 0,
                "group_title" => $mahallahTitle,
                "description" => $mahallahDescription,
                "group_user" => [],
                "groupUser" => [],
                "Authorization: Bearer " . $_SESSION['bearer_token']
                );

            $headers = array (
                'Content-Type: application/json',
                'Accept: application/json'
            );
        

        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

        $result = curl_exec ( $ch );
        curl_close ( $ch );

        return true;
        }
        catch (Exception $e) {
            $exp = $e->getMessage();
            echo json_encode(array('error' => 0, 'message' => 'An Error has occurred,<br>' . $exp . '.<br/> please contact your system support team'));
        }
    }
}




/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 06/03/2021
 * Time: 08:57 PM
 */