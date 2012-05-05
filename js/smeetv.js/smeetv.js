function crawl(e) {

}

function look(e) {

}


function imagify(a,id){


    var token = imagify_detect_pic(a),
        shorturl = imagify_get_shorturl(a,token),
        noxpath = imagify_get_noxpath(shorturl);

    // if image is not imagify'able, do not proceed
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

function imagify_crawlurl(e){
    if(e.search('id="media') > 0 && e.search('twitpic') > 0){
        var noxpath = '#media > img';
    } else if(e.search('main_image') > 0 && e.search('yfrog') > 0) {
        var noxpath = '#main_image';
    } else if(e.search('wrap') > 0 && e.search('instagr') > 0) {
        var noxpath = '.stage .stage-inner img.photo';
    }
    else if(e.search('.container') > 0 && e.search('cinemagr.am') > 0) {
        var noxpath = '.row > img';
    }
    else if(e.search('.gifHolder') > 0 && e.search('gifpal.com') > 0) {
        var noxpath = '.gifHolder img.gifImage';
    }
    else if(e.search('.fancy') > 0 && e.search('http://photobzz.com') > 0) {
        var noxpath = '.fancy img';
    }
    else if(e.search('medium_photo') > 0) {  // tweet photo
        var noxpath = '#medium_photo';
    } else if(e.search('fullsize') > 0 && e.search('twitgoo') > 0) {
        var noxpath = '#fullsize';
    } else if(e.search('picktwitPhotoContainer') > 0) {  // picktor
        var noxpath = '#picktwitPhotoContainer > a > img';
    } else if(e.search('photo-div') > 0 && e.search('flickr') > 0) {
        var noxpath = '#photo .photo-div > img';
    } else if(e.search('picdetail') > 0 && e.search('movapic') > 0) {
        var noxpath = '.picdetail img.image';
    } else if(e.search('the-image') > 0 && e.search('img.ly') > 0) {
        var noxpath = '#the-image';
    } else if(e.search('image') > 0 && e.search('upic.me') > 0) {
        var noxpath = '#image';
    } else if(e.search('foto') > 0 && e.search('fotki.yandex.ru') > 0) {
        var noxpath = '#foto img';
    } else if(e.search('photo') > 0 && e.search('lockerz.com') > 0) {
        var noxpath = '#photo';
    } else if(e.search('photo') > 0 && e.search('plixi') > 0) { // plixi
	var noxpath = '#main > section > article > figure > a > img';
    } else if(e.search('#mainImage') > 0 && e.search('picplz.com') > 0) {
        var noxpath = '#main > section > article > figure > a > img';
    } else if(e.search('pbckt.com') > 0) {
        var noxpath = '#fullSizedImage';
    } else if(e.search('photobucket.com') > 0) {
        var noxpath = '#fullSizedImage';
    } else if(e.search('.inline-media-image') > 0 && e.search('dailybooth.com') > 0) {
        var noxpath = '#main > section > article > figure > a > img';
    } else if(e.search('photozou.jp') > 0) {
        var noxpath = '#indivi_media > a > img';
    } else if(e.search('deviantart.com') > 0) {
        var noxpath = '#zoomed-in > img';
    } else if(e.search('iimmgg.com') > 0) {
        var noxpath = '#laimagen';
    } else if(e.search('min.us') > 0) {
        var noxpath = 'img';
    } else if(e.search('tinypic.com') > 0) {
        var noxpath = '#imgElement';
    }
    return noxpath;
}

function imagify_get_noxpath(shorturl){
	if(shorturl.search('twitpic') > 0){
	        var noxpath = '.container .row > img';
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




/**
 **  Javascript AlphabeticID class (based on a script by Kevin van Zonneveld <kevin@vanzonneveld.net>)
 **
 **  Author: Even Simon <even.simon@gmail.com>
 **
 **  Description: Translates a numeric identifier into a short string and backwords.
 **
 **  Usage:
 **    var str = AlphabeticID.encode(9007199254740989); // str = 'fE2XnNGpF'
 **    var id = AlphabeticID.decode('fE2XnNGpF'); // id = 9007199254740989;
 ***/
var AlphabeticID = {
  index:'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
 
/**
 **  <a href="http://twitter.com/function">@function</a> AlphabeticID.encode
 **  <a href="http://twitter.com/description">@description</a> Encode a number into short string
 **  <a href="http://twitter.com/param">@param</a> integer
 **  <a href="http://twitter.com/return">@return</a> string
 ***/
  encode:function(_number){
    if('undefined' == typeof _number){
      return null;
    }
    else if('number' != typeof(_number)){
      throw new Error('Wrong parameter type');
    }
    var ret = '';
 
    for(var i=Math.floor(Math.log(parseInt(_number))/Math.log(AlphabeticID.index.length));i>=0;i--){
      ret = ret + AlphabeticID.index.substr((Math.floor(parseInt(_number) / AlphabeticID.bcpow(AlphabeticID.index.length, i)) % AlphabeticID.index.length),1);
    }
 
    return ret.reverse();
  },
 
  /**
 **  <a href="http://twitter.com/function">@function</a> AlphabeticID.decode
 **  <a href="http://twitter.com/description">@description</a> Decode a short string and return number
 **  <a href="http://twitter.com/param">@param</a> string
 **  <a href="http://twitter.com/return">@return</a> integer
 ***/
  decode:function(_string){
    if('undefined' == typeof _string){
      return null;
    }
    else if('string' != typeof _string){
      throw new Error('Wrong parameter type');
    }
 
    var str = _string.reverse();
    var ret = 0;
 
    for(var i=0;i<=(str.length - 1);i++){
      ret = ret + AlphabeticID.index.indexOf(str.substr(i,1)) * (AlphabeticID.bcpow(AlphabeticID.index.length, (str.length - 1) - i));
    }
 
    return ret;
  },
 
  /**
 **  <a href="http://twitter.com/function">@function</a> AlphabeticID.bcpow
 **  <a href="http://twitter.com/description">@description</a> Raise _a to the power _b
 **  <a href="http://twitter.com/param">@param</a> float _a
 **  <a href="http://twitter.com/param">@param</a> integer _b
 **  <a href="http://twitter.com/return">@return</a> string
 ***/
  bcpow:function(_a, _b){
    return Math.floor(Math.pow(parseFloat(_a), parseInt(_b)));
  }
};
 
/**
 **  <a href="http://twitter.com/function">@function</a> String.reverse
 **  <a href="http://twitter.com/description">@description</a> Reverse a string
 **  <a href="http://twitter.com/return">@return</a> string
 ***/
String.prototype.reverse = function(){
  return this.split('').reverse().join('');
};



