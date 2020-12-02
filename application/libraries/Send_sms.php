<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Send_sms{

    Public function send($mobileno,$msgbody){
        $url = 'https://app.notify.lk/api/v1/send/?user_id=10193&api_key=iy8UDI9nAweN6RfslO94&sender_id=NotifyDEMO&to='.$mobileno.'&message='.$msgbody;

        $headers = array (
           'Content-Type: application/json'
        );

        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

}
