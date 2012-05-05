<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));

    if(strpos($arr[count($arr)-1],"?")) { /* addme widget leaves some garbage at the end of query string eg. http://fs.f1vlad.com/smeetv/smeetv/tv/img/2628?sms_ss=twitter&at_xt=4dc6c8054cd5144b,0 */
        $transport=explode("?",$arr[count($arr)-1]);
        $transport=$transport[0];
    } else {
        $transport=$arr[count($arr)-1];
    }

    //$id=advancedClean(3,$arr[count($arr)-1]);
    $id=$transport;


    if(strlen($id)==0) {
        header("HTTP/1.0 404 Not Found");
        return;
    }

    $smeetvdb=connect2db();
    $query="select id,content,timestamp,link,date from twits_dump where id=".alphaID($id,true);
    $go=mysql_query($query);

    if(mysql_num_rows($go)==0){
        $query="select id,content,timestamp,link,date from twits_dump_1 where id=".alphaID($id,true);
        $go=mysql_query($query);
    }
    
    if(mysql_num_rows($go)==0){
        $query="select id,content,timestamp,link,date from twits_dump_2 where id=".alphaID($id,true);
        $go=mysql_query($query);
    }
    

    $get=mysql_fetch_array($go);

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');

    $title=$get['content'];

    //drawHeader(trim(substr($get['content'],stripos($get['content']," "),75)),$u);




if($u==true) {

$userinfo='';
$userinfo.='<section class="um"><nav role="usermenu"><a class="username" href="/u/'.$_SESSION["username"].'"><span class="ui-icon"></span> '.$_SESSION["username"].'</a></nav>';
$userinfo.='<nav role="usersecondarymenu">';
if($unverified) {
$userinfo.='
    <span title="Please verify your email to unlock these features." class="strike">(<abbr>anonpub</abbr>: <a href="#">channel</a>, <a href="#">rss</a>)</span>
';
}else{
$userinfo.='
<ul>
    <li><abbr title="anonpub: anonymous public channel and rss feed is your images stream, you can share publicly, it does not display your userid or username.">anonpub</abbr>:</li>
        <ul>
            <li><a href="/chan/'.$_SESSION['idhash'].'">channel</a></li>
            <li><a href="/rss/'.$_SESSION['idhash'].'">rss</a></li>
        </ul>
';
}
$userinfo.='<li><a class="logout" href="/smeetv/logout.php">logout</a></li>';
$userinfo.= '</ul></nav><section>';



}

$is_flagged=is_flagged($id);

    drawHeader(trim($title),$u,0,'','Picture &sect;'.$id,$userinfo);
    echo "<section id='content' class='grid_24'><section class='wrap'>";
    if($is_flagged==1) 
        echo '<div class="notification error ontop"><span class="ui-icon exclamation">&nbsp;</span>Beware, this photograph was flagged by our users. For your safety, we have delayed displaying it by 30 seconds.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>';


?>




<script src="//smeetv.com/js/jquery-1.7.2.js"></script>
<script src="//smeetv.com/js/smeetv.js/smeetv.js"></script>
<script>
$(document).ready(function(){
    $('.destroy_notification').live('click',function(e){
        $(this).closest('.notification').filter(':first').remove();
        e.preventDefault();
    });

    $('article.twit').each(function(){
        var content = "<?php echo $title ?>",
            thisid=$(this).attr('id');


    // Sample content FOR TESTING
    // var content="testing http://t.co/doSdVNJT  http://t.co/rDZRqakz various urls http://twitpic.com/9ds0du yeah";
    //var content="d Ñ Photobzz http://photobzz.com/photo/2478";
    var content = "<?php echo $title ?>";

    // Process twit and set up blank image tags to be processed further
    var setupImgTags = extractUrls2(content); // extracting urls and adding their path into new <img src="...">
    $("#"+thisid).append(setupImgTags);
    
    function gogo(){
        (function myLoop (i) {
           console.log(i);
           setTimeout(function () {
              $('article.twit').find('img.pending').addClass('test'+i);
              UnknownFunction();
              if (--i) myLoop(i);      //  decrement i and call myLoop again if i > 0
           }, 1000)
        })(5);                        //  pass the number of iterations as an argument
    }

    // push iterator
    gogo();
    
    function updateElStorage(rel,src,rmel) {
        //console.log('rel is ' + rel + ', and new src is ' + src);
        $('article.twit').find("img[rel='" + rel + "']").attr('src',src);
        if(rmel===1){ // element imagified, remove it from queue
            $('article.twit').find("img[rel='" + rel + "']").removeClass('pending');
        }
    }
    
    function UnknownFunction(){ // Go through each URL trying to convert it until process is the end, that is absolute URL to image.
        $('article.twit img.pending').each(function(){
            var thisel = $(this),
                sel = $('img[rel="'+thisel.attr('rel')+'"]').attr('src');
            $.get("/etc/util/xdom?"+sel, function(data) {
                var dompath = imagify_crawlurl(data);
                if(dompath) {      // found qualifying image hosting
                    var ci=constructImagePath(data,dompath);
                    if(ci && ci.indexOf("http://") === -1){
                        var thiselsrc = thisel.attr('src'),
                            tprefix = thiselsrc.slice(0,thiselsrc.indexOf('//')+2),
                            tsliced = thiselsrc.replace(tprefix,""),
                            thost = tsliced.slice(0,tsliced.indexOf("/"));
                        ci = tprefix + thost + ci;
                    }
                    updateElStorage(thisel.attr('rel'),ci,1);
                } else {       // image hosting unknown, qualify URL's of image hosting
                                // attempting to find "http-equiv="refresh"" that's how twitter redirects from short http://t.co/* urls 
                    var searchres = data.indexOf(";URL="); // <---- proprietary, need better logic, assumes t.co short url
                    if(searchres > -1) {//found URL
                        var newpath=data;
                        newpath=newpath.slice(data.indexOf('location.replace')+18,data.indexOf('")')); // filter our url
                        newpath=newpath.replace(/\\/g,''); // find and replace all escaping backslashes
                        updateElStorage(thisel.attr('rel'),newpath);
                    }
                }
            });
        });
    }
    

    function kickDig(id){ // Change i here to specify amount of attempts to make to crawl
        for(i = 0; i < 1; i++){
            setTimeout(function(){
                var p = processUrls(id);
            },350);
        }
    }
    
    function constructImagePath(data,dompath) { // get image full absolute path
        var imgpath=$(data).find(dompath).attr('src');
        return imgpath;
    }

    function updateCurrentDataStorage(id,newContent) {
        var current_state = $('#'+id).attr('data');
        $('#'+id).attr('data',current_state + " " + newContent )
    }
    
    function processUrls(id){ // Go through each URL trying to convert it until process is the end, that is absolute URL to image.
        var content = $("#"+id).attr("data");
        content = content.split(" ");
        updateCurrentDataStorage(id,"|");
        for(i = 0; i < content.length; i++){
            console.log("attempt to process url: " + content[i]);
            $.get("/etc/util/xdom?"+content[i], function(data) {
                var dompath = imagify_crawlurl(data);
                if(dompath) {      // found qualifying image hosting
                    $(data).find(dompath).attr('src')
                    var ci=constructImagePath(data,dompath);
                    updateCurrentDataStorage(id,ci);
                } else {       // image hosting unknown, qualify URL's of image hosting
                                // attempting to find "http-equiv="refresh"" that's how twitter redirects from short http://t.co/* urls 
                    var searchres = data.indexOf(";URL=");
                    if(searchres > -1) {//found URL
                        var newpath=data;
                        newpath=newpath.slice(data.indexOf('location.replace')+18,data.indexOf('")')); // filter our url
                        newpath=newpath.replace(/\\/g,''); // find and replace all escaping backslashes
                        updateCurrentDataStorage(id,"x"+newpath);
                    }
                }
            });
        }
    }

    function doCallback(data) {
        console.log(data);
    }

    function populateUrls(url){
        var currentstate = $("#"+thisid).attr('data'); // currentstate is `undefined` when there is no data, means initial iteration
        if(currentstate === undefined){
            console.log('initial iteration'); // here we just popular urls
            for(i = 0; i < url.length; i++){ 
                var newattr = ((newattr === undefined)?(""):(newattr + " ")) + url[i];
            }
        }else{
            console.log('repeated iteration'); // here we attempt to delegate tasks to dig/reload url's with proper non shorturls etc
            /*
             
             TODO
             
            */
        }
        $("#"+thisid).attr("data", newattr);
    }
/*
    function extractUrls(content){
        var pattern = /(https?:\/\/[^\s]+)/g,out = [],ii=0; // url regexp
        content=content.split(" ");
        for(i = 0; i < content.length; i++){
            if(pattern.test(content[i])===true){
                console.log(content[i]);
                out[ii]=content[i];
                ii++;
            }
        }
        return out; // return array of urls
    }
*/

function removeSpecialChars(str)
{
str = str.replace(/[^a-zA-Z 0-9]+/g,"");
return str
}


    function extractUrls2(content){
        var pattern = /(https?:\/\/[^\s]+)/g,out = [],ii=0; // older pattern, YET TO check with multiple urls in one twit
        //var pattern = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/; // newer pattern
        var forbidden_url_chars = /([^A-Za-z0-9\:\/\.\#\-\_\?\@\=])/g;
        //alert(content[i].replace(/([^A-Za-z0-9\:\/\.\#\-\_\?\@\=])/g,"x")); ////! !!
        console.log(content);
        content=content.split(" ");
//alert(removeSpecialChars(content));
        var bld = "";
        for(i = 0; i < content.length; i++){
            if(pattern.test(content[i])===true){
                ii++;
                //alert(content[i].replace(/([^A-Za-z0-9\:\/\.\#\-\_\?\@\=])/g,"x")); ////! !!
                content[i]=content[i].replace(forbidden_url_chars,""); // <-- this should be handled better in pattern above
                bld = bld + "<img src='"+content[i]+"' rel='"+content[i]+"' class='pending'>";
            }
        }
        return bld; // return array of urls
    }




/////////  URLs now included in data="". Now need to kick of the process of going through them and imagifying.


/*
        function extractUris(content) { // extract urls from twit
            var rlregex = /(https?:\/\/[^\s]+)/g,rar = [],ii=0;
            content=content.split(" ");
            for(i = 0; i < content.length; i++){
                if(rlregex.test(content[i])===true){
                    rar[ii]=content[i];
                    ii++;
                }
            }
            return rar; // return array of urls
        }

        function spiderUris(uris) { // inspect each url, if shorturl, dig to get to the end
            for(i = 0; i < uris.length; i++){
                    var rrr = digdeep(uris[i]);
            }
        }

        function digdeep(uri) {

            if(!imagify_get_noxpath(uri)===false) {
                doSomethingWithData(uri,0);
            } else {
                $.get("/etc/util/xdom?"+uri, doSomethingWithData);
            }

            function doSomethingWithData(data,preprocess) {
                if(preprocess===0) { // we know the NOXPATH found, so this is direct hosting url, no need to dig
                    var thisprocess = data;
                }else{
                    var a = data.indexOf("URL=")+4,
                        b = data.indexOf('">'),
                        thisprocess = data.slice(a,b);
                }

                var currentattr = $("#"+thisid).attr("data");
                if(!currentattr===false){ // if data attr already exists, do not overwrite it, append it.
                    var newattr = currentattr + " " + thisprocess;
                    $("#"+thisid).attr("data", newattr);
                } else {
                    $("#"+thisid).attr("data", thisprocess);
                }
            }




        }

        var altcontent="testing http://t.co/doSdVNJT  http://t.co/rDZRqakz various urls http://twitpic.com/doSdVNJT yeah";
        //var altcontent='[Womens] "All-Star TTC" (Green) Sweatshirt on Sale Now at TruType Clothing Online Store (http://t.co/1zc0cXdq) ... http://t.co/rDZRqakz';
        var a2=extractUris(altcontent);
        spiderUris(a2);
*/








        // if $is_flagged==1 -- delay imagification
    });
});
</script>



<?
echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],1,0);
?>

</section>
</section>

<?disconnectFromDb($smeetvdb);?>
<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>








