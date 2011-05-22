<?
    include('smeetv/func.php');
    session_start();
    if(isUserLoggedIn()==0){
        header('Location:/smeetv/login.php');
    }

    if(isUserLoggedIn()==1){
        $u=true;
    }

    require_once('smeetv/header.php');
    drawHeader('remote control',$u,'1');
    connect2db();
?>
<nav role="usermenu">
<a id="rss1231" href="/u/<?=$_SESSION['username']?>"><?=$_SESSION['username']?></a> (<abbr title="anonpub: anonymous public channel and rss feed is your images stream, you can share publicly, it does not display your userid or username.">anonpub</abbr>: <a href="/chan/<?=$_SESSION['idhash']?>">channel</a>, <a href="/rss/<?=$_SESSION['idhash']?>">rss</a>)
</nav>
<?
    $query="select id,content,timestamp,link from twits where uid={$_SESSION['id']} order by id desc limit 0,20";



    $barr.="
<script type=\"text/javascript\"><!--
google_ad_client = \"ca-pub-1221828368307550\";
/* smeetv_injected_slide */
google_ad_slot = \"2446448564\";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type=\"text/javascript\"
src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
</script>
    ";

    $go=mysql_query($query);
    $barr="";
    for($i=0;$i<mysql_num_rows($go);$i++){
        $get=mysql_fetch_array($go);
        $barr.="<article id=\"{$get['id']}\" rel=\"".$get['link']."\">".$get['content']."</article>";
        if($i==0) {
            $most_recent_hash=md5($get['id']."".$get['timestamp']);
        }
    }


    //$barr.="<span id=\"0\" rel=\"https://twitter.com/#!/f1vlad/status/61176896204980224\"><strong>Sponsored tweet: </strong>Found this in the car, miami beach, wanna go back! http://twitpic.com/4nqd20</span>";

    $barr_inject="
<script type=\"text/javascript\"><!--
google_ad_client = \"ca-pub-1221828368307550\";
/* smeetv_injected_slide */
google_ad_slot = \"2446448564\";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type=\"text/javascript\"
src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
</script>
    ";

    $query="
        select smeetv_img_num,smeetv_channels,smeetv_hashtags,smeetv_speed,smeetv_text,ad,smeetv_size
        from accounts where id='".$_SESSION['id']." limit 0,1'
    ";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);


    if(!$get['smeetv_img_num']) {
        $get['smeetv_img_num']=50;
    }


    if(!$get['smeetv_speed']) {
        $get['smeetv_speed']=1000;
    }

    if($get['smeetv_size']) {
        $saved_size = explode(",",$get['smeetv_size']);
    }




    /* advertising */
/*
    $hash_split=split(',',$get['smeetv_hashtags']);
    foreach ($hash_split as &$h_s) {
        $q2="select * from ads where keyword like '%$h_s%'";
        $g2=mysql_query($q2);
        if(mysql_num_rows($g2)>0) {
            $ad=mysql_fetch_array($g2);
            $context_ad=1;
            break;
        }
    }
*/
     $show_ads = $get['ad'];


?>
<script src="/js/jquery.js"></script>

<script src="/js/jquery.ui.core.js"></script>
<script src="/js/jquery.ui.widget.js"></script>
<script src="/js/jquery.ui.mouse.js"></script>
<script src="/js/jquery.ui.draggable.js"></script>
<script src="/js/jquery.ui.resizable.js"></script>
<script src="/js/jquery.ui.tabs.js"></script>
<script src="/js/smeetv.js/smeetv.js"></script>

    <script src="/js/jquery.jtwitter.js"></script>
    <script type="text/javascript" src="/js/jquery.cycle.all.2.72.js"></script>

    <script>

        function flag_image(id) {
                $.ajax({
                        type: 'POST',
                        url: './img/report/'+id,
                        data: { 'op':'ajax', 'id':id },
                        dataType: 'html',
                        success: function(response, textStatus) {
                            var result = $(response + ' meta[name=ajr]' ).attr('content');
                            if(result=="1"){
                                if($('.flagged.notification').length==0) {
                                    $('body').append('<div class="flagged notification confirm"><span class="ui-icon check">&nbsp;</span>Image flagged, it will be immediately removed from your stream and flagged in shared stream, our editors will review it.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                                }
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to flag image, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to flag image, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                        }
                });
        }


        function save_resized_window(h,w) {
            var smeetv_size = h + ',' + w;
                $.ajax({
                        type: 'POST',
                        //url: '../settings.php',
                        url: '/etc/save/',
                        data: {'smeetv_size': smeetv_size,
                               'section': 'save_resize_r',
                               'op': 'ajax'},
                        dataType: 'html',
                        success: function(response, textStatus) {
                            var result = $(response + ' meta[name=ajr]' ).attr('content');
                            if(result=="1"){

                                $('body').append('<div class="notification confirm"><span class="ui-icon check">&nbsp;</span>TV size saved.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save TV size, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save TV size, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                                //$('#messages').notify({status: 'error', message: 'A system error occurred'});
                        }
                });
        }

        function logger(str) {
           var ts = Math.round(new Date().getTime() / 1000);
           $('#log').prepend('<p>'+ts+': '+str+'<p>');
        }

        function kickStopEvent(){
            // logger('firing up tv image iterator');
           // slider();
        }

        function slider(){
            logger('initialising slider');
            $('#content').cycle({
                fx: 'fade',
//timeout: 10000,
timeout: <?=$get['smeetv_speed']?>,
                speed: 300,
                pause: 1,
                next:   '#next2, #altnext', 
                prev:   '#prev2' 
            });


//$('#content').append('<article id="00">ad</article>');


        }






        function check_new_twits(h,i){
                if(!h){
                    h='null';
                }
                $.ajax({
                        type: 'POST',
                        url: './etc/u/',
                        data: {'h': h,'i': i},
                        dataType: 'html',
                        success: function(response, textStatus) {
                            if(response=="1" && $('.notification.success.new-pics-found').length<1){
                                $('body').append('<div class="notification success new-pics-found"><span class="ui-icon check">&nbsp;</span>New pictures found. <a id="thisrefresh" href="">Refresh the page</a> to see changes.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');



                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            //alert('not success');
                        }
                });
        }










var repeater = function(func, times, interval) {
    window.setTimeout( function(times) {
    return function() {
      if (--times > 0) window.setTimeout(arguments.callee, interval);
      func(times);
    }
  }(times), interval);
}


repeater(function(left){
    check_new_twits('<?=$most_recent_hash?>','<?=$_SESSION['id']?>');
/*
    if(left == 0) {
        alert("I'm done");
    }
*/
}, 100000, 60000);






	$(document).ready(function(){







$('#featuredhashtags_content').load('/etc/featuredhashtags/');

$('#inj').live('click',function(e){

//$('#00').appendTo('#content');

e.preventDefault();
});


        $('#check_new_twits').live('click',function(){
            check_new_twits('<?=$most_recent_hash?>','<?=$_SESSION['id']?>');
            return false;
        });




        $('#settings34s5').submit(function(e){
                var $form = $(this),
                    message,
                    peakin,
                    icontype = 'blank',
                    notificationtype;
                $.ajax({
                        type: 'POST',
                        url: $form.attr('action'),
                        url: '/etc/save/',
                        data: {'password': $('#passwordcb', $form).val(),
                               'ad': $('#adcb', $form).attr('checked'),
                               'section': $('#sectioncb', $form).val(),
                               'op':'ajax'},
                        dataType: 'html',
                        success: function(response, textStatus) {
                            var result = $(response + ' meta[name=ajr]' ).attr('content');
                            if(result=="1"){

                                $('body').append('<div class="notification confirm"><span class="ui-icon check">&nbsp;</span>Settings saved. <a id="thisrefresh" href="">Refresh the page</a> to see changes.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
                                //$('#messages').notify({status: 'error', message: 'A system error occurred'});
                        }
                });
                e.preventDefault();
        });








        $('#remotecontrol432').submit(function(e){
/*xxx*/
                var $form = $(this),
                    message,
                    peakin,
                    icontype = 'blank',
                    notificationtype;
                $.ajax({
                        type: 'POST',
                        url: $form.attr('action'),

                        data: {'smeetv_hashtags': $('textarea[name=smeetv_hashtags]',$form).val(),
                               'smeetv_hashtags_prev': $('input[name=smeetv_hashtags_prev]',$form).val(),
                               'smeetv_speed': $('input[name=smeetv_speed]:checked',$form).val(),
                               'smeetv_text': $('input[name=smeetv_text]:checked',$form).val(),
                               'smeetv_img_num': $('input[name=smeetv_img_num]:checked',$form).val(),
                               'section': $('#removecontrolcb', $form).val(),
                               'op':'ajax'},
                        dataType: 'html',


                        success: function(response, textStatus) {
                            var result = $(response + ' meta[name=ajr]' ).attr('content');
                            if(result=="1"){

                                $('body').append('<div class="notification confirm"><span class="ui-icon check">&nbsp;</span>Settings saved. <a id="thisrefresh" href="">Refresh the page</a> to see changes.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
                                //$('#messages').notify({status: 'error', message: 'A system error occurred'});
                        }



                });



                e.preventDefault();
        });


















        $('#thisrefresh').live('click',function(){
                location.reload();
        });


        $('#thishide').live('click',function(e){
                $(this).parent().hide();
                e.preventDefault();
        });

        $('#featuredhashtags').live('click',function(e){
                $('#featuredhashtags_content').toggle();
                e.preventDefault();
        });




$('#featuredhashtags_content > span > a').live('click',function(e){
    var kwd=$(this).attr('title'),
        kwd_inputarea = $('#smeetv_hashtags_2343675'),
        new_val = kwd_inputarea.val() + ', ' + kwd;

    if(kwd_inputarea.val().search(kwd)!=-1){
        $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>You already have "'+kwd+'" in your list.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');

    } else {
        $('body').append('<div class="notification confirm suggestrefresh"><span class="ui-icon check">&nbsp;</span>"'+kwd+'" keyword was added to your preference. <a id="trigger_remote_control_save" href="">Click here to save your settings</a>.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
        kwd_inputarea.val(new_val);
        $(".gotoremotecontrol").trigger("click");
    }
        e.preventDefault();
});

$('#trigger_remote_control_save').live('click',function(e){
    $('#remotecontrol432').submit();
    e.preventDefault();
    return false;
});


$('#controller .attn').live('mouseover',function(e){
    var hitem=$(this).attr('rel');
    var p = $('.'+hitem).filter(":first").offset();
    $('#helparrow').animate({'opacity':'1', 'left' : p.left , 'top' : p.top},110);
    setTimeout(function(e2){
        //$('#helparrow').animate({opacity:'0'}, 750,function(){});


    },3000);
});

$('#controller .attn').live('mouseout',function(e){
        $('#helparrow').animate({opacity:'0','left':'0'}, 50,function(){});
});

$('.destroy_notification').live('click',function(e){
        //$(this).closest('.notification').filter(':first').animate({'top':'-100px','width':'5%'},750);
$(this).closest('.notification').filter(':first').remove();
        e.preventDefault();
});

$('#topnav .gototrigger').click(function(e){
    $('#controller a.'+$(this).attr('rel')).trigger('click');
    e.preventDefault();
});






$(document).keypress(function(e) {
    if (e.which == 32) {
    // toggle play/pause here
    togglePlayPause();
    e.preventDefault(e);
    }
});

$('#pp').bind('click',function(e){
    togglePlayPause();
    e.preventDefault();
});





function togglePlayPause(){
        var c = $('#content');
        if (c.hasClass('pause')) {
            c.cycle('pause').removeClass('pause').addClass('play');
            $('#minicontroller #pp').removeClass('pause').addClass('play').attr('href','#play').attr('title','Play').html('play');
            $('body').append('<div class="notification pause"><span class="ui-icon pause">&nbsp;</span>Presentation paused.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
            if($('.notification.play').length) {
                $('.notification.play').remove();
            }
        } else {
            c.cycle('resume',true).removeClass('play').addClass('pause');
            $('#minicontroller #pp').removeClass('play').addClass('pause').attr('href','#pause').attr('title','Pause').html('pause');
            $('body').append('<div class="notification play"><span class="ui-icon play">&nbsp;</span>Presentation resumed.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>')
            if($('.notification.pause').length) {
                $('.notification.pause').remove();
            }
        }
}





	//all hover and click logic for buttons
	$(".ui-icon:not(.ui-state-disabled)")
	.hover(function(){
	    $(this).addClass("ui-state-hover");
	    },
	    function(){ 
		$(this).removeClass("ui-state-hover"); 
	    })
	.mousedown(function(){
	    $(this).parents('.fg-buttonset-single:first').find(".ui-icon.ui-state-active").removeClass("ui-state-active");
	    if( $(this).is('.ui-state-active.fg-button-toggleable, .fg-buttonset-multi .ui-state-active') ){
		$(this).removeClass("ui-state-active");
	    } else {
		$(this).addClass("ui-state-active");
	    }
	})
	.mouseup(function(){
	    if(!$(this).parent().is('.fg-buttonset-single ,  .fg-buttonset-multi ') || !$(this).is('.fg-button-toggleable')){
		$(this).removeClass("ui-state-active"); 
	    }
	});



                $( "#controller, #wraptv" ).draggable({handle:'.draggable-handle'});
		$( "#resizable, #controller" ).resizable();
                $( "#controller" ).tabs();


            var doresize;
            $('#resizable').resize(function() {
                $('.busy').show();
                clearTimeout(doresize);
                doresize = setTimeout(function(){
                    save_resized_window($('#resizable').height(),$('#resizable').width());
                    $('.busy').hide();
                },3000);
            });



            $('.hash option').click(function(e){
                //logger('chosing another search criteria');
                fetch('',$(this).attr('name'));
                e.preventDefault();
            });

           // busy spinner
           $('.busy')
               .hide()
               .ajaxStart(function() {
                   if(!window.location.toString().match('upload')){
                        $(this).show(); 
/*
                        $('#content').hide(function(){
                            logger('hide tv content while images load, avoid ui mess');
                        });
*/
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

           //fetch();



$('#original_source_link').live('click',function(e){
var findid = $('#content article:visible').attr('id');
gotosrc('/img/'+findid);
e.preventDefault();
});

$('#report_image').live('click',function(e){
var findid = $('#content article:visible').attr('id');
//gotosrc('/smeetv/smeetv/tv/img/'+findid+'/report');
$('#content').find('article#'+findid+' img').attr('src','reported');
flag_image(findid);
e.preventDefault();
});


function crawlurl(e){
    if(e.search('id="photo-display') > 0 && e.search('twitpic') > 0){
        var noxpath = '#content #view-photo-main #photo img#photo-display';
    } else if(e.search('main_image') > 0 && e.search('yfrog') > 0) {
        var noxpath = '#main_image';
    } else if(e.search('medium_photo') > 0) {  // tweet photo
        var noxpath = '#medium_photo';
    } else if(e.search('fullsize') > 0 && e.search('twitgoo') > 0) {
        var noxpath = '#fullsize';
    } else if(e.search('picktwitPhotoContainer') > 0) {  // picktor
        var noxpath = '#picktwitPhotoContainer > a > img';
    } else if(e.search('photo-div') > 0 && e.search('flickr') > 0) {
        var noxpath = '#photo .photo-div > img';
    } else if(e.search('photo') > 0 && e.search('plixi') > 0) { // plixi
        var noxpath = '#photo';
    } else if(e.search('picdetail') > 0 && e.search('movapic') > 0) {
        var noxpath = '.picdetail img.image';
    } else if(e.search('the-image') > 0 && e.search('img.ly') > 0) {
        var noxpath = '#the-image';
    } else if(e.search('image') > 0 && e.search('upic.me') > 0) {
        var noxpath = '#image';
    } else if(e.search('foto') > 0 && e.search('fotki.yandex.ru') > 0) {
        var noxpath = '#foto img';
    } else if(e.search('') > 0 && e.search('lockerz.com') > 0) {
        var noxpath = '#mainImage';
    } else if(e.search('#mainImage') > 0 && e.search('picplz.com') > 0) {
        var noxpath = '#main > section > article > figure > a > img';
    }
   return noxpath;
}



           function gotosrc(en){
               window.open(en);
               return false;
           }




           function imagify(a,id){
               var token = imagify_detect_pic(a),
                   shorturl = imagify_get_shorturl(a,token);


               if(shorturl.search('twitpic') > 0){
                       var noxpath = '#content #view-photo-main #photo img#photo-display';
               }

               if(shorturl.search('yfrog') > 0){
                       var noxpath = '#main_image';
               }

               if(shorturl.search('tweetphoto') > 0){
                       var noxpath = '#medium_photo';
               }

               if(shorturl.search('twitgoo') > 0){
                       var noxpath = '#fullsize';
               }

               if(shorturl.search('picktor') > 0){
                       var noxpath = '#picktwitPhotoContainer > a > img';
               }

               if(shorturl.search('flic.kr') > 0){
                       var noxpath = '#photo .photo-div > img';
               }

               if(shorturl.search('plixi.com') > 0){
                       var noxpath = '#photo';
               }

               if(shorturl.search('lockerz.com') > 0){
                       var noxpath = '#main section article figure a img';
               }


               if(shorturl.search('movapic.com') > 0){
                       var noxpath = '.picdetail img.image';
               }

               if(shorturl.search('img.ly') > 0){
                       var noxpath = '#the-image';
               }

               if(shorturl.search('upic.me') > 0){
                       var noxpath = '#image';
               }

               if(shorturl.search('fotki.yandex.ru') > 0){
                       var noxpath = '#foto img';
               }

               if(shorturl.search('t.co') > 0){
                       var mark_to_crawl=true;
               }

               if(shorturl.search('bit.ly') > 0){
                       var mark_to_crawl=true;
               }

               if(shorturl.search('picplz.com') > 0){
                       var noxpath = '#mainImage';
               }

               if(shorturl.search('instagr.am') > 0){
                       var noxpath = '#wrap img.photo';
               }




$('#'+id).load('/etc/util/xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){



    $(this).append('<span class="description"><a href="'+shorturl+'">'+a+'</a></span>');

    if(mark_to_crawl===true) { // added this for handling t.co links
       crawled_img_path=$(response).find(crawlurl(response)).attr('src');
       $(this).append('<img alt="'+a+'" src="'+crawled_img_path+'">');
       mark_to_crawl=false;
    } else {

       var timg=$(this).find('img');

       $(timg).attr('alt',a);

       if(!$(this).find('img').attr('src')){
          //alert('image not found' + id + 'TODO: handle this error better' );
          $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to load image ' + id + '. <a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
       } else {
           if($(this).find('img').attr('src').search('http://') < 0) { // added this for handling relative urls done this for img.ly initially
               var old_src = $(this).find('img').attr('src');
               $(timg).attr('src',token+''+old_src);
           }
       }

    }
});



if(shorturl.search('twitpic') > 0){
    noxpath = '#content #view-photo-main #photo img#photo-display';
}

if(shorturl.search('yfrog') > 0){
    noxpath = '#main_image';
}



/*
$('#'+id).html('<img src="">');
$('#'+id+' img').attr('src','x');
*/

           }


           // imagify
           $('#content > article').each(function(){


               var content=$(this).html();

               imagify(content,$(this).attr('id'));

           });



           slider();

        });


    </script>



    <div class="busy"><img src="/i/loading.gif"></div>
    <div id="helparrow"><img src="/i/arrow.png"></div>
    <div id="featuredhashtags_content" style="display:none">
<?/*
$qh="select * from keywords order by counter desc";
$qh=mysql_query($qh);
for($i=0;$i<mysql_num_rows($qh);$i++){
    $gh=mysql_fetch_array($qh);
    echo "
         <span><a href=\"\" style=\"font-size:".($gh['counter']+10)."px\" title=\"{$gh['keyword']}\" id=\"{$gh['keyword']}\">{$gh['keyword']}</a></span>
    ";
}
*/?>
    </div>


<section class="wraptv" id="wraptv">
  <div id="resizable" class="tv_frame" style="width:<?=$saved_size[1]?>px;height:<?=$saved_size[0]?>px;">
  <div class="yakor">&nbsp;</div>
  <div class="dragginggniggard"><div class="draggable-handle ui-icon">&nbsp;</div></div>
    <div id="tv" class="">
        <div id="altnext" title="Click to go to next image" class="gradientt"> </div>
	<div id="content" class="hide pause <?if(!$get['smeetv_text']==1){echo "notext";}?>"><?=$barr?></div>
    </div>
    <aside id="minicontroller" class="">
         <span class="fleft">
             <a title="Previous" href="#prev" class="prev2 ui-icon" id="prev2">previous</a>
             <a title="Pause" href="#pause" class="pause ui-icon" id="pp">pause</a>
             <a title="Next" href="#next" id="next2" class="next2 ui-icon">next</a>
         </span>
         <span class="fright">
             <a title="Report image" id="report_image" class="ui-icon report-image"  href="">!</a>
             <a title="Original source" id="original_source_link" class="ui-icon original-source"  href="">&#8734;</a>
         </span>
    </aside>
  </div>
</section>




<div id="controller" class="default draggable-handle ">
        <ul class="littlenav">
                <li><a href="#tabs-1" class="gotoremotecontrol" title="Remote control">tv</a></li>
                <li><a href="#tabs-2" class="gotosettings" title="Settings">&#9679;</a></li>
                <li><a href="#tabs-3" class="gotohelp" title="Help">?</a></li>
        </ul>

 <div class="dragginggniggard"><div class="draggable-handle ui-icon">&nbsp;</div></div>

    <div id="tabs-1">
    <div class="fleft"><strong>Remote Control</strong></div>
    <form method="post" id="remotecontrol432" action="/etc/save/">
    <input type="hidden" name="section" id="removecontrolcb" value="remotecontrol">

    <div class="fleft smeetv_hashtags">
    <abbr title="Enter hashtags, plan words or twitter usernames separated by comma to start fetching pictures from twitter.">Tags</abbr>
    <div style="float:left;"><textarea id="smeetv_hashtags_2343675" name="smeetv_hashtags"><?=$get['smeetv_hashtags']?></textarea><input type="hidden" id="smeetv_hashtags_prev_2343675" name="smeetv_hashtags_prev" value="<?=$get['smeetv_hashtags']?>"></div>
    </div>
    <div class="fleft">
    <abbr title="How fast do you want these pictures and videos to flip?">Speed</abbr>
    <label class="s <?if($get['smeetv_speed']=='50000'){?>selected<?}?>">slow<input type="radio" name="smeetv_speed" value="10000" <?if($get['smeetv_speed']=='10000'){?>checked<?}?>></label>
    <label class="s <?if($get['smeetv_speed']=='10000'){?>selected<?}?>"> medium<input type="radio" name="smeetv_speed" value="5000" <?if($get['smeetv_speed']=='5000'){?>checked<?}?>></label>
    <label class="s <?if($get['smeetv_speed']=='5000'){?>selected<?}?>">fast<input type="radio" name="smeetv_speed" value="1000" <?if($get['smeetv_speed']=='1000'){?>checked<?}?>></label>
    </div>
    <div class="fleft">
    <abbr title="Show descriptions for these pictures and videos?">Descriptions</abbr>
    <label class="s 
    <?if($get['smeetv_text']=='1'){?>selected<?}?>">on<input type="radio" name="smeetv_text" value="1" <?if($get['smeetv_text']=='1'){?>checked<?}?>></label> <label class="s 
    <?if($get['smeetv_text']=='0'){?>selected<?}?>">off<input type="radio" name="smeetv_text" value="0" <?if($get['smeetv_text']=='0'){?>checked<?}?>></label>
    </div>
    <div class="fleft">
    <abbr class="disabled" title="Narrow down to these GPS coordinates (currently disabled)">Narrow down to:</abbr>
    <label class="s"><input type="text" name="gps" disabled="disabled" value="00.000, 00.000"></label>
    </div>
    <div class="fright">
    <input type="submit" value="&crarr;">
    </div>
    </form>
    </div>

    <div id="tabs-2">
    <div class="fleft"><strong>Settings</strong></div>






    <form id="settings34s5" method="post" action="../settings.php"><input type="hidden" name="section" id="sectioncb" value="settings">
    <div class="fleft"><label><input type="text" value="username" disabled="disabled"></label><label><input type="text" value="email@email.com" disabled="disabled"></label></div>

    <div class="fleft"><label class="static text" for="passwordcb">Password:</label> <input id="passwordcb" type="password" name="password" value="" ></div>

    <div class="fleft"><label class="static text" for="adcb" title="Leave check mark in place if you do not mind seeing contextual ads.">Ads?</label>
        <input type="checkbox" name="ad" value="1" id="adcb" <?if($get['ad']==1) echo " checked "; ?>>
    </div>

    <div class="fright">
    <input type="submit" value="&crarr;">
    </div>
    </form>




    </div>

    <div id="tabs-3">
    <div class=""><strong>Help</strong> <div style="width:1%" class="fleft text"><span class="line">
    Use <span class="attn" rel="prev2">&#8592;</span> and <span rel="next2" class="attn">&#8594;</span> to navigate back and forth, <span class="btn" style="font-size:.75em;float:none;line-height:1em;margin-right:0">SPACEBAR</span> to pause/resume the presentation, <span class="attn" rel="yakor">resize</span> and <span rel="draggable-handle" class="attn">move</span> the tv around. Check out <span class="attn" rel="gotosettings">Settings</span>.
    </div></div>
    </div>

</div>









<?


if($show_ads=='1') {
?>


<div id="ad">


<script type="text/javascript"><!--
google_ad_client = "ca-pub-1221828368307550";
/* smeetv prototype */
google_ad_slot = "2418217707";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

</div>
<?
}
?>

<div style="display:none;">
<span id="00">
<?=$barr_inject?>
</span>
</div>

    <div id="transport"><!--transport--></div>
     <div id="log"></div>


<?require_once('smeetv/footer.php');?>
