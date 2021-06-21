<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function check(){
        $username = "Masumetc555";
        $hash = "7799f1a053fd94524811d08fa9d9c43f";
        $numbers = '01317310220';
        // $numbers = '01780481585';


        //*121*5479#
        $message = "Tmi amr friend how tai mona kora ";




        $params = array('u'=>$username, 'h'=>$hash, 'op'=>'pv', 'to'=>$numbers, 'msg'=>$message);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://alphasms.biz/index.php?app=ws");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close ($ch);

        return 'okay';
    }
}
