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




if(strpos($_SESSION['idhash'],'nverified-')==1){
    $unverified=TRUE;
}

?>
<nav role="usermenu">
<a id="rss1231" href="/u/<?=$_SESSION['username']?>"><?=$_SESSION['username']?></a>
<?if($unverified) {?>
    <span title="Please verify your email to unlock these features." class="strike">(<abbr>anonpub</abbr>: <a href="#">channel</a>, <a href="#">rss</a>)</span>
<?}else{?>
    (<abbr title="anonpub: anonymous public channel and rss feed is your images stream, you can share publicly, it does not display your userid or username.">anonpub</abbr>: <a href="/chan/<?=$_SESSION['idhash']?>">channel</a>, <a href="/rss/<?=$_SESSION['idhash']?>">rss</a>)
<?}?>
<span id="fni"></span>

</nav>
<?
    $query="select aid,id,content,timestamp,link,date from twits where uid={$_SESSION['id']} order by id desc limit 0,20";




    $go=mysql_query($query);
    $barr="";
    for($i=0;$i<mysql_num_rows($go);$i++){
        $get=mysql_fetch_array($go);
        $orig_date=strtotime($get['date']);
        $barr.="<article id=\"{$get['aid']}\" rel=\"".$get['link']."\">
                <aside>
                    discovered ".nicetime($get['timestamp']).",
                    posted ".nicetime($orig_date)."
                </aside>

                ".$get['content']."
            </article>";
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
        select smeetv_img_num,smeetv_channels,smeetv_hashtags,smeetv_speed,smeetv_text,ad,smeetv_size,email,username
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

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


var success=0;

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
                                    $('body').append('<div class="flagged notification confirm"><span class="ui-icon check">&nbsp;</span>Image flagged, it will be immediately removed from your stream and flagged in shared stream, our editors will review it.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                                }
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to flag image, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to flag image, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                        }
                });
        }












        function createCookie(name,value,days) {
	    if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
	    }
	    else var expires = "";
	    document.cookie = name+"="+value+expires+"; path=/";
            }

        function readCookie(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }    
	    return null;
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

                                $('body').append('<div class="notification confirm"><span class="ui-icon check">&nbsp;</span>TV size saved.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save TV size, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save TV size, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                                //$('#messages').notify({status: 'error', message: 'A system error occurred'});
                        }
                });
        }

        function logger(str) {
           var ts = Math.round(new Date().getTime() / 1000);
           $('#log').prepend('<p>'+ts+': '+str+'<p>');
           var f1v=true;
        }

        function kickStopEvent(){
            // logger('firing up tv image iterator');
           // slider();
        }

        function slider(){
            logger('initialising slider');
            $('#content').cycle({
                fx: 'fade',
timeout: <?=$get['smeetv_speed']?>,
                speed: 300,
                pause: 1,
                next:   '#next2, #altnext', 
                prev:   '#prev2' 
            });


        }

        function check_num_twits_fetched(){
                $.ajax({
                        type: 'GET',
                        url: '/status/ajax',
                        dataType: 'html',
                        success: function(response, textStatus) {
                            $('#fni').html(response);
                        },
                });
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
                                $('body').addClass('newstuffsfound').append('<div class="notification success new-pics-found"><span class="ui-icon check">&nbsp;</span>New pictures found. <a id="thisrefresh" href="">Refresh the page</a> to see changes.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            //alert('not success');
                        }
                });
        }

var repeater = function(func, times, interval,success) {
    window.setTimeout( function(times) {
    return function() {
      if (--times > 0) window.setTimeout(arguments.callee, interval);
      func(times);
    }
  }(times), interval);
}


repeater(function(left){
    if(left>0 && $('body').hasClass('newstuffsfound')==false) {
       //check_new_twits('<?=$most_recent_hash?>','<?=$_SESSION['id']?>');
    }
    setTimeout(function(){check_num_twits_fetched()},10000);
}, 100000, 60000, success);






	$(document).ready(function(){

<?if($unverified) {?>

            $('body').append('<div class="ontop notification confirm"><span class="ui-icon check">&nbsp;</span>To make sure these introductory messages do not bother you anymore, please confirm your email by clicking a link we sent you when you registered this account.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');

            $('body').append('<div class="ontop notification confirm"><span class="ui-icon check">&nbsp;</span>Be patient, our robot is tirelessly looking for pictures, he\'ll notify you here as he discovers the new ones. Click on the icon on the bottom right of each photo for additional information.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');

            $('body').append('<div class="ontop notification confirm"><span class="ui-icon check">&nbsp;</span>Hi there newbie newb, :),  glad you registered with us! Please specify keywords (hashtags, phrases, or twitter usernames) on the bottom panel and wait.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
<? } ?>






$('#fni').load('/status/ajax');


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

                                $('body').append('<div class="notification confirm"><span class="ui-icon check">&nbsp;</span>Settings saved. <a id="thisrefresh" href="">Refresh the page</a> to see changes.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>')
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>')
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>')
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
                                $('body').append('<div class="notification confirm"><span class="ui-icon check">&nbsp;</span>Settings saved. <a id="thisrefresh" href="">Refresh the page</a> to see changes. You will be notified here of new picture as they\'re found by our robot.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to save settings, please retry.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
                                //$('#messages').notify({status: 'error', message: 'A system error occurred'});
                        }



                });



                e.preventDefault();
        });




/*
<a id="dim_bg" href="" class="btn">dim bg</a>

*/


        $('#dim_bg').live('click',function(e){
            var coo = createCookie('dim','1',365);
            $('head #light').attr({'href':'/c/dim.css','id':'dim'});
            $('#dim_bg').attr({'id':'undim_bg','class':'btn active'});
            e.preventDefault();
        });

        $('#undim_bg').live('click',function(e){
            var coo = createCookie('dim','0',10);
            $('head #dim').attr({'href':'/c/light.css','id':'light'});
            $('#undim_bg').attr({'id':'dim_bg','class':'btn'});
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
        $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>You already have "'+kwd+'" in your list.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');

    } else {
        $('body').append('<div class="notification confirm suggestrefresh"><span class="ui-icon check">&nbsp;</span>"'+kwd+'" keyword was added to your preference. <a id="trigger_remote_control_save" href="">Click here to save your settings</a>.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>');
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
    $(this).closest('.notification').filter(':first').remove();
    e.preventDefault();
});

$('#topnav .gototrigger').click(function(e){
    $('#controller a.'+$(this).attr('rel')).trigger('click');
    e.preventDefault();
});


var disable_keyevents = false;
$('textarea,input')
    .focus(function() { disable_keyevents = true })
    .blur(function() { disable_keyevents = true; });



$(document).keypress(function(e) {
    if (e.which == 32 && disable_keyevents!=true) {
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
            $('body').append('<div class="notification pause"><span class="ui-icon pause">&nbsp;</span>Presentation paused.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>')
            if($('.notification.play').length) {
                $('.notification.play').remove();
            }
        } else {
            c.cycle('resume',true).removeClass('play').addClass('pause');
            $('#minicontroller #pp').removeClass('play').addClass('pause').attr('href','#pause').attr('title','Pause').html('pause');
            $('body').append('<div class="notification play"><span class="ui-icon play">&nbsp;</span>Presentation resumed.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>')
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
                       $(this).show(); 
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
var str = AlphabeticID.encode(parseInt(findid));
gotosrc('/img/'+str);

e.preventDefault();
});

$('#report_image').live('click',function(e){
if(confirm('Are you sure you want to flag this photo? It will remove it from your TV and mark it as unsafe for others.')==true){
    var findid = $('#content article:visible').attr('id');
    //gotosrc('/smeetv/smeetv/tv/img/'+findid+'/report');
    $('#content').find('article#'+findid+' img').attr('src','reported');
    flag_image(findid);
}
e.preventDefault();
});


function exif_lookup(id){
    /*alert('trying to get exif info for' + id);*/
}


           function gotosrc(en){
               window.open(en);
               return false;
           }

           // imagify
           $('#content > article').each(function(){
               var content=$(this).html(),
                   thisid=$(this).attr('id');

               imagify(content,thisid);


    setTimeout(function(){
        exif_lookup(thisid);
    },10000);

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
	<div id="content" class="pause <?if(!$get['smeetv_text']==1){echo "notext";}?>"><?=$barr?></div>
    </div>
    <aside id="minicontroller" class="">
         <span class="fleft">
             <a title="Previous" href="#prev" class="prev2 ui-icon" id="prev2">previous</a>
             <a title="Pause" href="#pause" class="pause ui-icon" id="pp">pause</a>
             <a title="Next" href="#next" id="next2" class="next2 ui-icon">next</a>
         </span>
         <span class="fright">
             <a title="Report image" id="report_image" class="ui-icon report-image"  href="">!</a>
             <a title="Open in new window" id="original_source_link" class="ui-icon original-source"  href="">&#8734;</a>
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
    <div class="fleft"><strong>Remote Control</strong><a id="dim_bg" href="" class="btn">dim lights</a></div>
    <form method="post" id="remotecontrol432" action="/etc/save/">
    <input type="hidden" name="section" id="removecontrolcb" value="remotecontrol">

    <div class="fleft smeetv_hashtags">
    <abbr title="Enter hashtags, phrases or twitter usernames separated by comma to start fetching pictures from twitter.">Keywords</abbr>
    <div style="float:left;"><textarea id="smeetv_hashtags_2343675" name="smeetv_hashtags"><?=$get['smeetv_hashtags']?></textarea><input type="hidden" id="smeetv_hashtags_prev_2343675" name="smeetv_hashtags_prev" value="<?=$get['smeetv_hashtags']?>"></div>
    </div>
    <div class="fleft">
    <abbr title="How fast do you want these pictures and videos to flip?">Speed</abbr>
    <label class="s <?if($get['smeetv_speed']=='60000'){?>selected<?}?>">slow<input type="radio" name="smeetv_speed" value="60000" <?if($get['smeetv_speed']=='60000'){?>checked<?}?>></label>
    <label class="s <?if($get['smeetv_speed']=='20000'){?>selected<?}?>"> medium<input type="radio" name="smeetv_speed" value="20000" <?if($get['smeetv_speed']=='20000'){?>checked<?}?>></label>
    <label class="s <?if($get['smeetv_speed']=='5000'){?>selected<?}?>">fast<input type="radio" name="smeetv_speed" value="5000" <?if($get['smeetv_speed']=='5000'){?>checked<?}?>></label>
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
    <div class="fleft"><label><input type="text" value="<?=$get['username']?>" disabled="disabled"></label><label><input type="text" value="<?=$get['email']?>" disabled="disabled"></label></div>

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

<style>#ft {position:fixed;right:1em;}</style>
<?require_once('smeetv/footer.php');?>
