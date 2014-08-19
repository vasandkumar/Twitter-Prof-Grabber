<?php
    require 'includes/dbconfig.php';
    require 'apptokens.php';
    require 'tmhOAuth-master/tmhOAuth.php';


    $connection = new tmhOAuth(array('consumer_key'=>$consumer_key,'consumer_secret'=>$consumer_secret,'user_token'=>$user_token,'user_secret'=>$user_secret));


    $code=$connection->request('GET',$connection->url('1.1/application/rate_limit_status'),array('resources'=>'users'));
    $res=json_decode($connection->response['response'],true);

if($code==200)
{
    print_r($res);
}
else{
    print 'Error:$code';
}