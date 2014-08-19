<?php
require 'includes/dbconfig.php';
require 'apptokens.php';
require 'tmhOAuth-master/tmhOAuth.php';

$con=mysql_connect($host,$user,$password);
mysql_select_db($dbname,$con);
$cursore="1421349339805306812";
$breaker=0;
do
{
$connection = new tmhOAuth(array('consumer_key'=>$consumer_key,'consumer_secret'=>$consumer_secret,'user_token'=>$user_token,'user_secret'=>$user_secret));


$code=$connection->request('GET',$connection->url('1.1/followers/ids'),array('screen_name'=>'twitterapi','cursor'=>$cursore));
$res=json_decode($connection->response['response'],true);

if($code==200)
{


    for($i=0;$i<count($res['ids']);$i++)
    {


            $result=mysql_query("INSERT INTO twitterids_master (twitterids,createdon) values (".$res['ids'][$i].",NOW())") or die(mysql_error());


    }

    $breaker=$breaker+1;
    echo 'Done '.$breaker.' Times';
    $cursore=$res['next_cursor'];
    echo 'Next Cursor: '.$res['next_cursor'].' <br>';
}
else
{
    print 'Error:$code';
    break;
}
    sleep(10);
}while($res['next_cursor']!="" || $breaker<15)

?>