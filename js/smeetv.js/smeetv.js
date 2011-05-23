

function imagify(a,id){
               var token = imagify_detect_pic(a),
                   shorturl = imagify_get_shorturl(a,token),
                   noxpath = imagify_get_noxpath(shorturl);




                   $('#'+id).load('/etc/util/xdom.php?geturl=' + shorturl + ' ' + noxpath,function(response,status,xhr){
                       $(this).append('<span class="description"><a href="'+shorturl+'">'+a+'</a></span>');

                       if(noxpath===false) { // added this for handling t.co links
                           crawled_img_path=$(response).find(imagify_crawlurl(response)).attr('src');
                           $(this).append('<img alt="'+a+'" src="'+crawled_img_path+'">');
                       } else {
                           var timg=$(this).find('img');
                           $(timg).attr('alt',a);
                           if(!$(this).find('img').attr('src')){
                               $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Failed to load image ' + id + '. <a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                           } else {
                               if($(this).find('img').attr('src').search('http://') < 0) { // added this for handling relative urls done this for img.ly initially
                                   var old_src = $(this).find('img').attr('src');
                                   $(timg).attr('src',token+''+old_src);
                               }
                           }
                       }
                   });
}

function imagify_crawlurl(e){
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



function imagify_get_noxpath(shorturl){


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
                       var noxpath=false;
               }

               if(shorturl.search('bit.ly') > 0){
                       var noxpath=false;
               }

               if(shorturl.search('picplz.com') > 0){
                       var noxpath = '#mainImage';
               }

               if(shorturl.search('instagr.am') > 0){
                       var noxpath = '#wrap img.photo';
               }
    return noxpath;
}

function imagify_get_shorturl(a,token) {



               var tmpPosStart = a.search(token),
                   tmpPosEnd = a.indexOf(' ',tmpPosStart);


               if(tmpPosEnd===-1) {
                   var shorturl = a.slice(tmpPosStart);
               } else {
                   var shorturl = a.slice(tmpPosStart,tmpPosEnd);
               }
    return shorturl;
}

function imagify_detect_pic(a) {
               if(a.search('twitpic') > 0){
                       var token = 'http://twitpic.c';
               }
               else if(a.search('yfrog') > 0){
                       var token = 'http://yfrog.c';
               }
               else if(a.search('tweetphoto') > 0){
                       var token = 'http://tweetphoto.c';
               }
               else if(a.search('twitgoo') > 0){
                       var token = 'http://twitgoo.c';
               }
               else if(a.search('picktor') > 0){
                       var token = 'http://picktor.c';
               }
               else if(a.search('flic.kr') > 0){
                       var token = 'http://flic.kr';
               }
               else if(a.search('twitvid.com') > 0){
                       var token = 'http://twitvid.com';
               }
               else if(a.search('plixi.com') > 0){
                       var token = 'http://plixi.com';
               }

               else if(a.search('lockerz.com') > 0){
                   var token = 'http://lockerz.com';
               }
               else if(a.search('movapic.com') > 0){
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
               else if(a.search('t.co') > 0){
                       var token = 'http://t.co';
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

    return token;

}

