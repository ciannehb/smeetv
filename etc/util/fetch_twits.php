<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');

    $smeetvdb=connect2db();

echo "<h2>Fetching twits for all users</h2>";

    $fetch_interval = 1800; # 30 minutes

    connect2db();
    $query="select id,username,force_update from accounts where idhash!='NULL'";
    $go=mysql_query($query);
    $num=mysql_num_rows($go);

    for($i=0;$i<$num;$i++){
        $tbopts=time()-1800;  # 30 look back 30 minutes only
        $get=mysql_fetch_array($go);
        $curusrid=$get['id'];
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
            $do_force=1;
        } else {
            $do_force=0;
            if($fetch_interval<time()-$subget['timestamp']){
                # TODO — it looks at fetch_interval and $subget['timestamp'];
                # it isn't optimal, we should introduce last fetch date, so it doesn't always think that 
                # it needs to fetch. It could be just that last fetched item is old and no more twitpics found
                # doesn't necessary represent last fetch attempt... anyway, need to add last fetch try timestamp
                $fetch=true;
            }elseif(($fetch_interval/4) < (time()-$subget['timestamp'])){ // less expensive query
                $fetch=true;
                $query_quick=true;
                $tbopts=time()-1800/4;  # 30 look back 30 minutes only
            }else{
                $fetch=false;
            }
        }






        if($get['force_update']==1 || $do_force==1){
            $fetch=true;
            $do_force=1;
            $unforce=mysql_query("update accounts set force_update='0' where id='{$get[id]}'");
        } else {
            $do_force=0;
        }

//return;

/*

full text searching, need this in my.cnf
    [mysqld]
    ft_min_word_len=3

http://dev.mysql.com/doc//refman/5.0/en/fulltext-fine-tuning.html
http://dev.mysql.com/doc//refman/5.0/en/set-option.html
http://devzone.zend.com/article/1304
http://dev.mysql.com/doc/refman/5.0/en/fulltext-search.html


SELECT * FROM twits_dump          WHERE MATCH(content) AGAINST ('new hampshire');


*/





        if($fetch==true){

            $q1=mysql_query("select smeetv_hashtags from accounts where id='{$get['id']}' ");
            $g1=mysql_fetch_array($q1);
            $htgs=split(',',$g1[0]);

            $q2="select * from twits_dump where (";

// SELECT * FROM twits_dump          WHERE (MATCH(content) AGAINST ('you shared') OR MATCH(content) AGAINST ('xxx'))  and timestamp limit 0,10;
            $io=0;
            foreach( $htgs as $value){
                $io++;
                //$q2="select * from twits_dump where content like '%$hashtags_prepend".trim($value)."%' and timestamp > '$tbopts' ";
                //$q2="select * from twits_dump where content like '%$hashtags_prepend".trim($value)."%' and timestamp > '$tbopts' and flagged = '0' ";
                //$q2.=" OR content like '%".trim($value)."%' ";
               
                $q2.=" MATCH(content) AGAINST('LOWER(".strtolower($value).")')  ";
                if($io!=count($htgs)) $q2.=" OR ";

            }



            if($do_force!=1){
                $q2_opt="  AND timestamp > '$tbopts'  ";
            }
            $q2.=") $q2_opt order by id desc limit ";
            if(!$query_quick==true) $q2.=" 0,30 ";
            else $q2.="0,4";
            echo $q2."<br>";
            $g2=mysql_query($q2);
            for($ii=0;$ii<mysql_num_rows($g2);$ii++) {
            //for($ii=0;$ii<30;$ii++) {
                $get2=mysql_fetch_array($g2);
                //$insert_query="INSERT INTO twits (content,link,date,timestamp,uid) VALUES ('{$get2['content']}','{$get2['link']}','{$get2['date']}','".time()."','".$curusrid."')";
                $insert_query="INSERT INTO twits (aid,content,link,date,timestamp,uid) VALUES ('{$get2['id']}','{$get2['content']}','{$get2['link']}','{$get2['date']}','".time()."','".$curusrid."')";
                $go2=mysql_query($insert_query);
                echo $insert_query."<br>";


            }

        }

    }

//echo $_SERVER['PHP_SELF']." pushed\n";


?>

<?disconnectFromDb($smeetvdb);?>

































