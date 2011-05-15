<?
require_once('header.php');
require_once('func.php');


echo "<h2>Fetching twits for all users</h2>";

    $fetch_interval = 1800; # 30 minutes
    $tbopts=time()-1800;  # 30 look back 30 minutes only

    connect2db();
    $query="select id,username,force_update,smeetv_hashtags_force from accounts";
    $go=mysql_query($query);
    $num=mysql_num_rows($go);
    for($i=0;$i<$num;$i++){

        $get=mysql_fetch_array($go);
        if($get['smeetv_hashtags_force']==1) {
            $hashtags_prepend="#";
        }
        echo "<hr>";
        echo "<h1>working on account of user <pre>{$get['username']}(<a href=\"#\">{$get['id']}</a>)</pre></h1>";

        /* get timestamp of last updated twit, to see how recent it is */
        $subquery="select timestamp from twits where uid='{$get['id']}' order by id desc limit 0,1";
        $subgo=mysql_query($subquery);
        $subget=mysql_fetch_array($subgo);

        /* find out how old last twit is and decide whether to fetch additional */

        if(!$subget['timestamp']){
            echo "No time stamp found or initial/first index (new user)";
            $fetch=true;
        } else {
            if($fetch_interval<time()-$subget['timestamp']){
                # TODO — it looks at fetch_interval and $subget['timestamp'];
                # it isn't optimal, we should introduce last fetch date, so it doesn't always think that 
                # it needs to fetch. It could be just that last fetched item is old and no more twitpics found
                # doesn't necessary represent last fetch attempt... anyway, need to add last fetch try timestamp
                $fetch=true;
            }else{
                $fetch=false;
            }
        }




        if($get['force_update']==1){
            $fetch=true;
            $unforce=mysql_query("update accounts set force_update='0' where id='{$get[id]}'");
        }elseif($get['force_update']==2){
            $tbopts=0;
            $fetch=true;
            $unforce=mysql_query("update accounts set force_update='0' where id='{$get[id]}'");
        }


        if($fetch==true){

            $q1=mysql_query("select smeetv_hashtags from accounts where id='{$get['id']}' ");
            $g1=mysql_fetch_array($q1);
            $htgs=split(',',$g1[0]);
            foreach( $htgs as $value){
                //$q2="select * from twits_dump where content like '%$hashtags_prepend".trim($value)."%' and timestamp > '$tbopts' ";
                $q2="select * from twits_dump where content like '%$hashtags_prepend".trim($value)."%' and timestamp > '$tbopts' and flagged = '0' ";
                echo $q2."<br>";
                $g2=mysql_query($q2);
                if($res=mysql_num_rows($g2)>0){
                    for($ii=0;$ii<$res;$ii++){
                        $g22=mysql_fetch_array($g2);
                        # TODO — here we can encode twitter's content or URL and store it as hash
                        # this can be used to quickly glance if twits is not duplicate
                        $g3="insert into twits 
                             (content,link,date,timestamp,uid)
                             values
                             ('{$g22['content']}','{$g22['link']}','{$g22['date']}','".time()."','{$get['id']}');
                             ";
                        //echo $g3;
                        $g3=mysql_query($g3);

                    }
                }
            }
            

        }

    }



?>

<div id="log"></div>

<?require_once('footer.php');?>
