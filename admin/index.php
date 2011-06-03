<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }


    if($_SESSION['idhash']!="d820d0aa0b02f932465b1e84b98afbdd673bbdb7"){
        header("Location:/");
        return false;
    }


    connect2db();


if($_GET['op']=="ban" && $_GET['id']){
$query="update accounts set idhash='NULL' where id='{$_GET['id']}'";
mysql_query($query);
}

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');

    drawHeader('Admin',$u);


?>
    <h2>Simple admin</h2>

<h3>Latest logged in users</h3>
<table border="1" id="users" class="draggable sortable">
    <thead>
        <tr>
            <th>id</th>
            <th>username</th>
            <th>last login</th>
            <th>ip</th>
            <th>actions</th>
        </tr>
<?
$query="select id,username,email,last_ip,last_time,idhash from accounts order by last_time desc limit 0,20";
$go=mysql_query($query);
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    echo "<tr class='{$get['idhash']}'>";
    echo "
<td>{$get['id']}</td>
<td><a href=\"mailto:{$get['email']}\">{$get['username']}</a></td>
<td>".f1init_ago($get['last_time'])." seconds ago</td>
<td><a href=\"http://whois.sc/{$get['last_ip']}\" target=\"_new\">{$get['last_ip']}</a></td>
<td><a href=\"{$_SERVER['PHP_SELF']}?op=ban&id={$get['id']}\" class=\"btn\">ban</a></td>
    ";
    echo "</tr>";
}
?>
    </thead>
</table>



<h3>Flagged images</h3>
<table id="images" class="draggable sortable" border="1">
<thead>
<th>id</th>
<th>content</th>
<th>actions</th>
</thead>
<?
$query="select id,aid,content,link from twits_dump where flagged='1' order by id desc limit 0,3";
$go=mysql_query($query);
for($i=0;$i<mysql_num_rows($go);$i++){
$get=mysql_fetch_array($go);
echo "

<tr>
<td>{$get['id']}</td>
<td><a href=\"{$get['link']}\">{$get['content']}</a></td>
<td><a href=\"/admin/image/delete/{$get['id']}\" class=\"btn\">delete</a> <a href=\"/admin/image/unflag/{$get['id']}\" class=\"btn\">unflag</a></td>
</tr>

";
}
?>
</table>

























<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>
