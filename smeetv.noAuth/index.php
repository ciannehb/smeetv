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

        function fetch(account,search) {
            logger('fetching items');
            $('#content').hide();
            var account = '<?=$_GET['account']?>';
            var numtwits = '<?=$_GET['numtwits']?>';
            if(!account)var account='kdstonehill';
            $('.twuid').attr('value',account);
            if(!numtwits)var numtwits=100;
            $('.twitstofetch').attr('value',numtwits);
            if(!search)var search=' ';
            if(search==='all')var search=' ';
            $('#transport,#content').empty();
            $.jTwitter(account, numtwits, function(data){
                //$('#content').empty();

                // go through each item
                var itcount=0;
                $.each(data, function(i, post){

                    // search for selected hash tag
                    if(post.text.search(search)>0) {

                        itcount=itcount+1

                        // TODO extract this into function
                        // here we're searching for images

                        /* pixelpipe handler */
                        if(post.text.search('http://pi.p') > 0) {
                            logger('found pixelpipe item');
                            var tmpPosStart = post.text.search('http://pi.pe'),
                                tmpPosEnd = post.text.indexOf(' ',tmpPosStart),
                                noxpath = 'div#content li.object div.file img';

                            if(tmpPosEnd===-1) {
                                var shorturl = post.text.slice(tmpPosStart);
                            } else {
                                var shorturl = post.text.slice(tmpPosStart,tmpPosEnd);
                            }


                            $('<span />').appendTo('#content').load('xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){
                                if(response.search('HTTP request failed')>0) {
                                    logger('erorr: failed to fetch image');
                                } else {
                                    logger('injecting foreign image into tv');
                                }
                                //$(this).append('<span class="description">'+post.text+'</span>');
                                $(this).find('img').attr('alt',post.text);
                            });

                            //$('#content').prepend('<div>image found: <strong><a href="'+ shorturl +'">'+ shorturl +'</a></strong></div>');


                        /* twitpic handler */
                        } else if(post.text.search('http://twitpic.com') > 0) {



                            logger('found twitpic item');
                            var tmpPosStart = post.text.search('http://twitpic.c'),
                                tmpPosEnd = post.text.indexOf(' ',tmpPosStart),
                                noxpath = '#content #view-photo-main #photo img#photo-display';

                            if(tmpPosEnd===-1) {
                                var shorturl = post.text.slice(tmpPosStart);
                            } else {
                                var shorturl = post.text.slice(tmpPosStart,tmpPosEnd);
                            }

                            $('<span />').appendTo('#content').load('xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){
                                if(response.search('HTTP request failed')>0) {
                                    logger('erorr: failed to fetch image');
                                } else {
                                    logger('injecting foreign image into tv');
                                }

                                $(this).append('<span class="description">'+post.text+'</span>');
                                $(this).find('img').attr('alt',post.text);
                            });

                            //$('#content').prepend('<div>image found: <strong><a href="' + shorturl + '">'+ shorturl +'</a></strong></div>');

                        /* imageshack/yfrog handler */
                        } else if(post.text.search('http://yfrog.com/') > 0) {
                            logger('found imageshack/yfrog item');
                            var tmpPosStart = post.text.search('http://yfrog'),
                                tmpPosEnd = post.text.indexOf(' ',tmpPosStart),
                                noxpath = '#main_image';

                            if(tmpPosEnd===-1) {
                                var shorturl = post.text.slice(tmpPosStart);
                            } else {
                                var shorturl = post.text.slice(tmpPosStart,tmpPosEnd);
                            }




                            $('<span />').appendTo('#content').load('xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){
                                if(response.search('HTTP request failed')>0) {
                                    logger('erorr: failed to fetch image');
                                } else {
                                    logger('injecting foreign image into tv');
                                }
                                $(this).append('<span class="description">'+post.text+'</span>');
                                $(this).find('img').attr('alt',post.text);
                            });

                            //$('#content').prepend('<div>image found: <strong><a href="' + shorturl + '">'+ shorturl +'</a></strong></div>');



                        /* flickr handler */
                        } else if(post.text.search('http://www.flickr.com') > 0) {
                            logger('found flickr item');
                            var tmpPosStart = post.text.search('http://www.flick'),
                                tmpPosEnd = post.text.indexOf(' ',tmpPosStart),
                                noxpath = '#Photo .photoImgDiv img';

                            if(tmpPosEnd===-1) {
                                var shorturl = post.text.slice(tmpPosStart);
                            } else {
                                var shorturl = post.text.slice(tmpPosStart,tmpPosEnd);
                            }


                            $('<span />').appendTo('#content').load('xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){
                                if(response.search('HTTP request failed')>0) {
                                    logger('erorr: failed to fetch image');
                                } else {
                                    logger('injecting foreign image into tv');
                                }
                                $(this).append('<span class="description">'+post.text+'</span>');
                                $(this).find('img').attr('alt',post.text);
                            });

                            //$('#content').prepend('<div>image found: <strong><a href="' + shorturl + '">'+ shorturl +'</a></strong></div>');


                        /* imgur handler */
                        } else if(post.text.search('http://imgur.com') > 0) {
                            logger('found imgur item');
                            var tmpPosStart = post.text.search('http://imgur.com'),
                                tmpPosEnd = post.text.indexOf(' ',tmpPosStart),
                                noxpath = '#large-image img';

                            if(tmpPosEnd===-1) {
                                var shorturl = post.text.slice(tmpPosStart);
                            } else {
                                var shorturl = post.text.slice(tmpPosStart,tmpPosEnd);
                            }




                            $('<span />').appendTo('#content').load('xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){
                                if(response.search('HTTP request failed')>0) {
                                    logger('erorr: failed to fetch image');
                                } else {
                                    logger('injecting foreign image into tv');
                                }
                                $(this).append('<span class="description">'+post.text+'</span>');
                                $(this).find('img').attr('alt',post.text);
                            });

                            //$('#content').prepend('<div>image found: <strong><a href="' + shorturl + '">'+ shorturl +'</a></strong></div>');
                        }
                    }
                });
                logger('found and parsed ' + itcount + ' pictures');
                $('#content .loading').hide();
            });
        }

        function kickStopEvent(){
            logger('firing up tv image iterator');
            slider();
        }

        function slider(){
            logger('initialising slider');
            $('#content').cycle({
                fx: 'fade',
                speed: 3000,
                next:   '#next2', 
                prev:   '#prev2' 
            });
        }

	$(document).ready(function(){


            $('.hash option').click(function(e){
                logger('chosing another search criteria');
                fetch('',$(this).attr('name'));
                e.preventDefault();
            });

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
                       kickStopEvent();
                   }
           });

           fetch();

        });
    </script>

    <style>

        body {
            background:url("bg.jpg") no-repeat scroll 0 0  #000000;
        }

        * {
        }
        #tv {
            height:352px;
            margin:104px 0 0 185px;
            padding:0;
            width:526px;
        }
        .hide {
            display:none;
        }
        #ad {
            background:none repeat scroll 0 0 #CCCCCC;
            color:#ECECEC;
            font-size:6.5em;
            font-weight:bold;
            height:70px;
            letter-spacing:-0.07em;
            line-height:0.9em;
            margin:0;
            overflow:hidden;
            padding:0;
            width:100%;
        }
        #content {
            height:226px !important;
            width:524px !important;
            overflow:auto;
            overflow:hidden;
        }

        #content .description {
            position:absolute;
            margin-top:-3em;
            padding:.25em;
            background:#fff;
            opacity:.75;
            width:100%;
        }

        #content span {
            display:block;
            text-align:center;
            width:100%;
        }

        #content a {
            text-decoration:none;
        }

        #content img {
            height:226px;
            border:none;
        }

        #controller {
            color:#FFFFFF;
            font-size:0.8em;
            margin-left:178px;
            text-align:right;
            width:535px;
        }
        #controller a,
        #controller a:visited {
            color:#fff;
            text-decoration:none;
        }
        #controller .twuid,
        #controller .twitstofetch,
        #controller option,
        #controller select {
            background:#000;
            color:#fff;
            width:40px;
        }

        #controller span {
            border-right:1px solid #ccc;
            margin-right:10px;
            padding-right:10px;
        }

        #controller span:last-child {
            border-right:none !important;
            margin-right:none;
        }

        .busy {
            position:absolute;
            font-size:.75em;
            color:#fff;
            top:1em;
            left:1em;
        }
        #transport .tvslides img {
            width:80px;
        }
        #transport .tvslides img:hover {
            width:auto;
            position:absolute;
        }
        #log {
            position:absolute;
            top:.25em;
            right:.25em;
            width:340px;
            height:140px;
            border:1px solid;
            border-color:#666666 #222 #222 #666666;
            padding:.15em;
            font-size:.7em;
            color:#fff;
            font-family:monospace;
            -moz-border-radius:4px;
            -webkit-border-radius:4px;
            overflow:auto;
        }
        #log p {
            line-height:.8em;
            margin:0;
            padding:0;
        }
        .fadein { position:relative; height:332px; width:500px; }
        .fadein img { position:absolute; left:0; top:0; }
        .slideshow { height: 232px; width: 232px; margin: auto }
        .slideshow img { padding: 15px; border: 1px solid #ccc; background-color: #eee; }
    </style>


    <div class="busy"><img src="ajax-loader.gif"></div>
    <div id="tv">
        <div id="ad">advertisement</div>
        <div id="content"></div>
    </div>
    <div id="controller"> 
        <span><a href="#play">play</a></span>
        <span>choose hashtag: <select class="hash">
            <option selected></option>
            <option name="#kencats">#kencats</option>
            <option name="#kendogs">#kendogs</option>
            <option name="#">#</option>
            <option name="all">*</option>
        </select><span>
        <span>tw uid: <input class="twuid" type="text" value="" disabled="disabled" style="opacity:.25"></span>
        <span># of twits to fetch <input class="twitstofetch" type="text" disabled="disabled" style="opacity:.25"><a href="./?numtwits=10&account=<?=$_GET['account']?>">10</a>, <a href="./?numtwits=100&account=<?=$_GET['account']?>">100</a>, <a href="./?numtwits=1000&account=<?=$_GET['account']?>">1000</a></span>

<br>

slides control: <a href="#prev" id="prev2">prev</a> <a href="#next" id="next2">next</a>

<br>



        suggested twiter accounts with pictures: <a href="./?numtwits=<?=$_GET['numtwits']?>&account=karunchandhok">@karunchandhok</a>, <a href="./?numtwits=<?=$_GET['numtwits']?>&account=rubarrichello">@rubarrichello</a>, <a href="./?numtwits=<?=$_GET['numtwits']?>&account=H_Kovalainen">@H_Kovalainen</a>
    </div>
    <div id="transport">transport</div>
     <div id="log"></div>
    </body>
</html>
