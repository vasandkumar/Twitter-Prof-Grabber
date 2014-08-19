<?php

require 'includes/dbconfig.php';
require 'apptokens.php';
require 'tmhOAuth-master/tmhOAuth.php';

$con=mysql_connect($host,$user,$password);
mysql_select_db($dbname,$con);


for($is=0;$is<10;$is++)
{
    $userid="";
$resultss=mysql_query("SELECT twitterids FROM twitterids_master WHERE datagrabbed='no' ORDER BY  `id` ASC LIMIT 0,100")or die(mysql_error());

while($row=mysql_fetch_array($resultss))
{
    if($userid!="" || $userid!=null)
    {
        $userid.=",";
    }
    $userid.=$row['twitterids'];
}


$connection = new tmhOAuth(array('consumer_key'=>$consumer_key,'consumer_secret'=>$consumer_secret,'user_token'=>$user_token,'user_secret'=>$user_secret));

$code=$connection->request('GET',$connection->url('1.1/users/lookup'),array('user_id'=>$userid));
$ress=json_decode($connection->response['response'],true);

if($code==200)
{

    for($i=0;$i<count($ress);$i++)
    {
       $res=$ress[$i];


        $sql="INSERT INTO profiles_master (twtid,screenname,name,profile_background_image_url,lang,default_profile_image,
                        profile_link_color,friends_count,followers_count,favourites_count,created_at,utc_offset,profile_use_background_image,
                        profile_text_color,id_str,url,protected,notifications,profile_sidebar_border_color,verified,default_profile,profile_image_url,
                        profile_image_url_https,contributors_enabled,time_zone,profile_background_tile,location,profile_sidebar_fill_color,geo_enabled,
                        listed_count,profile_background_image_url_https,is_translator,profile_background_color,description,createdon,updatedon,
                        statuses_count,idtw) VALUES
                        (".$res['id'].",'".mysql_real_escape_string($res['screen_name'])."','".mysql_real_escape_string($res['name'])."','".mysql_real_escape_string($res['profile_background_image_url'])."','".mysql_real_escape_string($res['lang'])."','".mysql_real_escape_string($res['default_profile_image'])."',
                        '".mysql_real_escape_string($res['profile_link_color'])."',".$res['friends_count'].",".$res['followers_count'].",".$res['favourites_count'].",'".mysql_real_escape_string($res['created_at'])."','".$res['utc_offset']."','".$res['profile_use_background_image']."',
                        '".mysql_real_escape_string($res['profile_text_color'])."',".$res['id_str'].",'".mysql_real_escape_string($res['url'])."','".mysql_real_escape_string($res['protected'])."','".mysql_real_escape_string($res['notifications'])."','".mysql_real_escape_string($res['profile_sidebar_border_color'])."','".mysql_real_escape_string($res['verified'])."','".mysql_real_escape_string($res['default_profile'])."','".mysql_real_escape_string($res['profile_image_url'])."',
                        '".mysql_real_escape_string($res['profile_image_url_https'])."','".mysql_real_escape_string($res['contributors_enabled'])."','".mysql_real_escape_string($res['time_zone'])."','".mysql_real_escape_string($res['profile_background_tile'])."','".mysql_real_escape_string($res['location'])."','".mysql_real_escape_string($res['profile_sidebar_fill_color'])."','".mysql_real_escape_string($res['geo_enabled'])."',
                        ".mysql_real_escape_string($res['listed_count']).",'".mysql_real_escape_string($res['profile_background_image_url_https'])."','".mysql_real_escape_string($res['is_translator'])."','".mysql_real_escape_string($res['profile_background_color'])."','".mysql_real_escape_string($res['description'])."',NOW(),NOW(),".$res['statuses_count'].",".$res['id'].")";

        $result=mysql_query($sql) or die(mysql_error());
    $result2=mysql_query("UPDATE twitterids_master SET datagrabbed='yes' WHERE twitterids=".$res['id']) or die(mysql_error());
        echo $res['name'].' is done<br>';
    }
}else
{
    print 'Error:'.$code;
    die();
}
}
echo "ok sir";
mysql_close();

?>