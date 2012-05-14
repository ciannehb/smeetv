function gogo(thisid){
    (function myLoop (i) {
       console.log(i);
       setTimeout(function () {
	  $('article#'+thisid).find('img.pending').addClass('test'+i);
	  UnknownFunction(thisid);
	  if (--i) myLoop(i);      //  decrement i and call myLoop again if i > 0
       }, 2000)
    })(5);                        //  pass the number of iterations as an argument
}

function kickDig(id){ // Change i here to specify amount of attempts to make to crawl
    for(i = 0; i < 1; i++){
	setTimeout(function(){
	    var p = processUrls(id);
	},350);
    }
}

function extractUrls2(content){
    console.log(content);
    var pattern = /(https?:\/\/[^\s]+)/g,out = [],ii=0; // older pattern, YET TO check with multiple urls in one twit
    var forbidden_url_chars = /([^A-Za-z0-9\:\/\.\#\-\_\?\@\=])/g;
    content = content.replace("http://"," http://"); // deal with pushed content to touch http and incorrecting injecting into containers
    content=content.split(" ");
    var bld = "";
    for(i = 0; i < content.length; i++){
	if(pattern.test(content[i])===true){
	    ii++;
	    //alert(content[i].replace(/([^A-Za-z0-9\:\/\.\#\-\_\?\@\=])/g,"x")); ////! !!
	    content[i]=content[i].replace('&quot;','');
	    content[i]=content[i].replace(forbidden_url_chars,""); // <-- this should be handled better in pattern above
	    bld = bld + "<img src='"+content[i]+"' rel='"+content[i]+"' class='pending'>";
	}
    }
    return bld; // return array of urls
}
    
function constructImagePath(data,dompath) { // get image full absolute path
    var imgpath=$(data).find(dompath).attr('src');
    return imgpath;
}

function updateElStorage(rel,src,rmel) {
    //console.log('rel is ' + rel + ', and new src is ' + src);
    $('article.twit').find("img[rel='" + rel + "']").attr('src',src);
    if(rmel===1){ // element imagified, remove it from queue
	$('article.twit').find("img[rel='" + rel + "']").removeClass('pending');
    }
}

function updateCurrentDataStorage(id,newContent) {
    var current_state = $('#'+id).attr('data');
    $('#'+id).attr('data',current_state + " " + newContent )
}

function UnknownFunction(thisid){ // Go through each URL trying to convert it until process is the end, that is absolute URL to image.
    $('article#'+thisid+'.twit img.pending').each(function(){

        // Sometimes even short urls will translate directly into displayable image
	// in img src="", in this case we don't need to run through gogo iterator
	// !!!!!!! This logic may need to adjusted once we add default image for failed to load imgs/background etc.

        if($(this).height()>50){ // broken link src="" is 20px on macs chrome, giving it some extra pixels just in case.
	    $(this).removeClass('pending');
	}

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


function imagify_crawlurl(e){
    if(e.search('media-overlay') > 0 && e.search('twitpic') > 0){
        var noxpath = '#media > img';
    } else if(e.search('main_image') > 0 && e.search('yfrog') > 0) {
        var noxpath = '#main_image';
    } else if(e.search('wrap') > 0 && e.search('instagr') > 0) {
        var noxpath = '.stage .stage-inner img.photo';
    } else if(e.search('panel') > 0 && e.search('imgur') > 0) {
        var noxpath = '.image > img';
    } else if(e.search('gifContainer') > 0 && e.search('gifboom') > 0) {
        var noxpath = '#gif';
    } else if(e.search('.container') > 0 && e.search('cinemagr.am') > 0) {
        var noxpath = '.row > img';
    } else if(e.search('PinImageHolder') > 0 && e.search('pinterest.com') > 0) {
        var noxpath = '#pinCloseupImage';
    } else if(e.search('.gifHolder') > 0 && e.search('gifpal.com') > 0) {
        var noxpath = '.gifHolder img.gifImage';
    } else if(e.search('.fancy') > 0 && e.search('http://photobzz.com') > 0) {
        var noxpath = '.fancy img';
    } else if(e.search('medium_photo') > 0) {  // tweet photo
        var noxpath = '#medium_photo';
    } else if(e.search('twimage') > 0 && e.search('twitgoo') > 0) {
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
    } else if(e.search('imgclean.com') > 0 && e.search('imgclear-entry') > 0) {
        var noxpath = 'attachment-original';
    } else if(e.search('deviantart.com') > 0 && e.search('zoomed-in') > 0 ) {
        var noxpath = '#zoomed-in > img';
    } else if(e.search('iimmgg.com') > 0) {
        var noxpath = '#laimagen';
    } else if(e.search('min.us') > 0) {
        var noxpath = 'img';
    } else if(e.search('quickmeme.com') > 0 && e.search('meme_title')) {
        var noxpath = 'img';
    } else if(e.search('tinypic.com') > 0) {
        var noxpath = '#imgElement';
    }
    return noxpath;
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



