/*
    function populateUrls(url){
        var currentstate = $("#"+thisid).attr('data'); // currentstate is `undefined` when there is no data, means initial iteration
        if(currentstate === undefined){
            console.log('initial iteration'); // here we just popular urls
            for(i = 0; i < url.length; i++){ 
                var newattr = ((newattr === undefined)?(""):(newattr + " ")) + url[i];
            }
        }else{
            console.log('repeated iteration'); // here we attempt to delegate tasks to dig/reload url's with proper non shorturls etc
          
        }
        $("#"+thisid).attr("data", newattr);
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



function imagify(a,id){
    var token = imagify_detect_pic(a),
        shorturl = imagify_get_shorturl(a,token),
        noxpath = imagify_get_noxpath(shorturl);
	if(token===undefined){
	    return false;
	}
	// we know URL no need to do ajax
	if(noxpath===null) {
	    var getcontent=$('#'+id+' .thumbnail').html();
	    $('#'+id+' .thumbnail').html('<img src="'+shorturl+'" alt="'+getcontent+'">');
	    $('#mainimg > article').removeClass('hide');
	    return;
	}
    $('#'+id+ ' .thumbnail').load('/etc/util/xdom?' + shorturl + ' ' + noxpath,function(response,status,xhr){
        if(noxpath===false) { // added this for handling t.co links
            if(response.indexOf('location.replace')>-1) {
                var newpath=response;
                newpath=newpath.slice(response.indexOf('location.replace')+18,response.indexOf('")')); // filter our url
                newpath=newpath.replace(/\\/g,''); // find and replace all escaping backslashes
                var crawled_img_path="NEED TO LOAD TO DOM AGAIN NOW THAT WE RESOLVED URL";
            } else {
                var crawled_img_path=$(response).find(imagify_crawlurl(response)).attr('src');
            }
            if(crawled_img_path===undefined) {
                return false;
            }
            var cite = crawled_img_path.replace('http://',' ');
            var cite = cite.slice(0,cite.indexOf('/'));
            $(this).append('<cite>hosted on: ' + cite + '</cite>');
            if(!crawled_img_path) { /// most likely it's just direct link to the image
                crawled_img_path=shorturl;
            }
            $(this).append('<img />');
            $(this).find('img').attr({'src':crawled_img_path,'alt':a});
            $('.picctrl').show();
        } else {
            var cite = shorturl.replace('http://',' ');
            var cite = cite.slice(0,cite.indexOf('/'));
            $(this).append('<cite>hosted on: ' + cite + '</cite>');
            var timg=$(this).find('img');
            $(timg).attr('alt',a);
            if(!$(this).find('img').attr('src')){
                if($('.imgloadfailure').length==0){
                    $('body').append('<div class="notification error imgloadfailure" rel="1"><span class="ui-icon exclamation">&nbsp;</span>Failed to load <span class="alt opt">image ' + id + '</span>. <a href="" class="destroy_notification"><span class="ui-icon close_small ">&nbsp;</span></a></div>');
                }else{
                        var numfailed=parseInt($('.imgloadfailure').attr('rel'))+1;
                        if(numfailed===2){
                            $('.imgloadfailure span.alt.opt').html('<span class="counter"></span> images');
                        }
                        $('.imgloadfailure').attr('rel',numfailed);
                        $('.imgloadfailure span.alt.opt .counter').html(numfailed);
                }
                $(this).append('<img src="http://smeetv.com/i/blankimg.png" />');
            } else {
                $('.picctrl').show();
                if($(this).find('img').attr('src').search('http://') < 0) { // added this for handling relative urls done this for img.ly initially
                    var old_src = $(this).find('img').attr('src');
                    $(timg).attr('src',token+''+old_src);
                }
            }
        }
    });
    $('#mainimg > article').removeClass('hide');
}

function imagify_get_noxpath(shorturl){
	if(shorturl.search('twitpic') > 0){
	        var noxpath = '#media > img';
	}

	if(shorturl.search('cinemagr') > 0){
	        var noxpath = 'row > img';
	}

	if(shorturl.search('gifpal.com') > 0){
	        var noxpath = '.gifHolder img.gifImage';
	}
	if(shorturl.search('photobzz.com') > 0){
	        var noxpath = '.fancy img';
	}

	if(shorturl.search('yfrog') > 0){
	        var noxpath = '#main_image';
	}

	if(shorturl.search('tweetphoto') > 0){
	        var noxpath = '#medium_photo';
	}

	if(shorturl.search('dailybooth') > 0){
	        var noxpath = '.inline-media-image > img';
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

	if(shorturl.search('pbckt.com') > 0){
	        var noxpath = '#fullSizedImage';
	}

	if(shorturl.search('photobucket.com') > 0){
	        var noxpath = '#fullSizedImage';
	}

	if(shorturl.search('lockerz.com') > 0){
	        var noxpath = '#photo';
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
	        var noxpath=false;
	}

	if(shorturl.search('\ t.co') > 0){
	        var noxpath=false;
	}

	if(shorturl.search('bit.ly') > 0){
	        var noxpath=false;
	}

	if(shorturl.search('picplz.com') > 0){
	        var noxpath = '#mainImage';
	}

	if(shorturl.search('instagr.am') > 0){
	        var noxpath = '.stage .stage-inner img.photo';
	}

	if(shorturl.search('photozou.jp') > 0){
	        var noxpath = '#indivi_media > a > img';
	}

	if(shorturl.search('deviantart.com') > 0){
	        var noxpath = '#zoomed-in';
	}

	if(shorturl.search('iimmgg.com') > 0){
	        var noxpath = '#laimagen';
	}

	if(shorturl.search('tinypic.com') > 0){
	        var noxpath = '#imgElement';
	}

	if(shorturl.search('i.min.us') > 0){
	        var noxpath = null;
	}

    return noxpath;
}

function imagify_get_shorturl(a,token) {

    var prepend='';

    if(a.search('http://')===-1 && !token == 'undefined'){
        token=token.replace('http://','');
        prepend='http://';
    }

    if(token.search('http://')===-1) {
        prepend='http://';
    }

    var tmpPosStart = a.search(token),
        tmpPosEnd = a.indexOf(' ',tmpPosStart);
    if(tmpPosEnd===-1) {
        var shorturl = a.slice(tmpPosStart);
    } else {
        var shorturl = a.slice(tmpPosStart,tmpPosEnd);
    }
    return prepend+shorturl;
}

function imagify_detect_pic(a) {

	if(a.search('/twitpic') > 0){
	        var token = 'http://twitpic.c';
	}
	else if(a.search('/cinemagr') > 0){
	        var token = 'http://cinemagr.a';
	}
	else if(a.search('/instagr') > 0){
	        var token = 'http://instagr.a';
	}
	else if(a.search('/yfrog') > 0){
	        var token = 'http://yfrog.c';
	}
	else if(a.search('/tweetphoto') > 0){
	        var token = 'http://tweetphoto.c';
	}
	else if(a.search('/twitgoo') > 0){
	        var token = 'http://twitgoo.c';
	}

	else if(a.search('/dailybooth') > 0){
	        var token = 'http://dailybooth.c';
	}
	else if(a.search('/gifpal') > 0){
	        var token = 'http://gifpal.c';
	}
	else if(a.search('/photobzz.c') > 0){
	        var token = 'http://photobzz.c';
	}
	else if(a.search('/picktor') > 0){
	        var token = 'http://picktor.c';
	}
	else if(a.search('flic.kr') > 0){
	        var token = 'http://flic.kr';
	}
	else if(a.search('/plixi.com') > 0){
	        var token = 'http://plixi.com';
	}

	else if(a.search('/lockerz.com') > 0){
	    var token = 'http://lockerz.com';
	}
	else if(a.search('/movapic.com') > 0){
	        var token = 'http://movapic.com';
	}
	else if(a.search('img.ly') > 0){
	        var token = 'http://img.ly';
	}
	else if(a.search('upic.me') > 0){
	        var token = 'http://upic.me';
	}
	else if(a.search('fotki.yandex.ru') > 0){
	        var token = 'http://fotki.yandex.ru';
	}
	else if(a.search('/t.co') > 0){
	        var token = 'http://t.co';
	}
	else if(a.search('\ t.co') > 0){
	        var token = 't.co';
	}

	else if(a.search('bit.ly') > 0){
	        var token = 'http://bit.ly';
	}
	else if(a.search('picplz.com') > 0){
	        var token = 'http://picplz.com';
	}
	else if(a.search('instagr.am') > 0){
	        var token = 'http://instagr.am';
	}
	else if(a.search('pbckt.com') > 0){
	        var token = 'http://pbckt.com';
	}
	else if(a.search('photobucket.com') > 0){
	        var token = 'http://photobucket.com';
	}

	else if(a.search('photozou.jp') > 0){
	        var token = 'http://photozou.jp';
	}

	else if(a.search('deviantart.com') > 0){
	        var token = 'http://deviantart.com';
	}

	else if(a.search('iimmgg.com') > 0){
	        var token = 'http://iimmgg.com';
	}

	else if(a.search('min.us') > 0){
	        var token = 'http://i.min.us';
	}

	else if(a.search('tinypic.com') > 0){
	        var token = 'http://tinypic.com';
	}

    return token;

}
*/