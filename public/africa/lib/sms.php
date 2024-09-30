<?php
Require '../vendor/autoload.php';
use App\Http\vendor\africastalking\africastalking\src\AfricasTalking;


class sms{

    var $connObj,
        $userName = 'easymaog', // username here e.g easymaog //
        $senderId = 'EASYMAOG', // sender ID here //
        $apiKey   = 'atsk_cef9faaa47b5e5451867634e42b4b27d105d95e3830906f3b618b6d0e0ca233dee1efdef'; //api key here //

    function __construct()
    {
        $service       = new AfricasTalking($this->userName,$this->apiKey);
        $this->connObj = $service->sms();
    }

    function sendSms($to=[],$message){
        try{

            return   $this->connObj->send([
                'to'      => $to,
                'message' => $message,
                'from'    => $this->senderId
            ]);

        }catch (Exception $e){
            return $e;
        }
    }
}

// sample message
//$sms = new sms();
//$send = $sms->sendSms([265882230137],'Hello bwana sakala');
//print_r($send);

