<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--[if gte IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <![endif]-->
        <title>smeetv</title>
        <script src="jquery.js"></script>
        <script src="jquery.jtwitter.js"></script>
        <script type="text/javascript" src="jquery.cycle.all.2.72.js"></script>
    </head>
    <body>


    <script>
        var smeetv_src = 'http://twitter.com/statuses/public_timeline.rss';

        function logger(str) {
           var ts = Math.round(new Date().getTime() / 1000);
           $('#log').prepend('<p>'+ts+': '+str+'<p>');
        }

        function save(d){

var myaA=d.split("\,");
var myaC=myaA.length;

alert(myaC);


        }

        function getUsersList () {
            var gul = $.ajax({
                url: "/smeetv/mysql_select.php",
                data: "query=select id from accounts order by id desc",
                success: function(data){
                    //$('<span id="tmptr" />').html(data).appendTo('#tv');
                    save(data);
                },
                dataType: 'text',
                type: 'GET'
            });
            return gul;
        }


        function fetch(account,search) {
            logger('fetching items');
            var account='kdstonehill';
            var numtwits=100;
            var search=' ';
            $.jTwitter(account, numtwits, function(data){
                var itcount=0;
                $.each(data, function(i, post){
                    if(post.text.search(search)>0) {
                        itcount=itcount+1
                        if(post.text.search('http://twitpic.com') > 0) {  // searching for twitpics
                            logger('found twitpic item');
                            $('<span>'+post.text+'</span>').appendTo('#content');
                        }
                    }
                });
                logger('found and parsed ' + itcount + ' pictures');
            });
        }





	$(document).ready(function(){

           // busy spinner
           $('.busy')
               .hide()
               .ajaxStart(function() {
                   if(!window.location.toString().match('upload')){
                        $(this).show(); 
                        $('#content').hide(function(){
                            logger('hide tv content while images load, avoid ui mess');
                        });
                   }
           })
               .ajaxStop(function() {
                   if (!this.dont_stop_after_ajax){
                       $(this).hide();
                       $('#content').show(function(){
                           logger('reveal tv now that everything\'s ready');
                       });
                   }
           });

           //fetch();
           var usrs = getUsersList();

           //alert(usrs);











           

        });
    </script>


    <div class="busy"><img src="ajax-loader.gif"></div>
    <div id="tv">
        <div id="content"></div>
    </div>
    <div style="display:none" id="log"></div>
    </body>
</html>
