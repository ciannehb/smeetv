

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

