<?php
require 'includes/dbconfig.php';
require 'apptokens.php';
require 'tmhOAuth-master/tmhOAuth.php';

$con=mysql_connect($host,$user,$password);
mysql_select_db($dbname,$con);

$cursoe='1427923064557607571';
if(isset($_POST['next_cursor'])){$cursoe=$_POST['next_cursor'];}

echo $cursoe."<br>";
$connection = new tmhOAuth(array('consumer_key'=>$consumer_key,'consumer_secret'=>$consumer_secret,'user_token'=>$user_token,'user_secret'=>$user_secret));


$code=$connection->request('GET',$connection->url('1.1/followers/ids'),array('screen_name'=>'twitterapi','cursor'=>$cursoe));
$res=json_decode($connection->response['response'],true);

if($code==200)
{
    echo '<form method="post" action=""><input type="text" name="next_cursor"/ value="'.$res['next_cursor'].'"/><input type="submit" name="gfsub"/></form> <br>';
    for($i=0;$i<count($res['ids']);$i++)
    {

            $result=mysql_query("INSERT INTO twitterids_master (twitterids,createdon,datagrabbed) values (".$res['ids'][$i].",NOW(),'no')") or die(mysql_error());
        echo 'ID: '.$res['ids'][$i].' is done.<br>';

    }


}
else
{
    print 'Error:'.$code;
}
mysql_close($con);


?>