<?

function connect2db() {
    mysql_pconnect("localhost","root","");
    mysql_select_db("smeetv");
}

function validate_username($v_username) { 
   return eregi('[^a-z0-9_]', $v_username) ? FALSE : TRUE; 
}

function isUserLoggedIn() {
    if($_SESSION['authenticated']==1 && isset($_SESSION['username'])){
        return 1;
    } else {
        return 0;
    }
}

function logOut() {
if(!isset($_SESSION['authenticated']))
    return false;
unset($_SESSION['authenticated']);
unset($_SESSION['username']);
unset($_SESSION['id']);
unset($_SESSION['idhash']);
session_destroy();
return true;
}

function advancedClean($level,$string){
                if($level==3){  // POWER CLEAN
                        $string=trim($string);
                        $string=strip_tags($string);
                        $string=htmlspecialchars($string);
                        $string=addslashes($string);
        }elseif($level==2){   // MEDIUM CLEAN

                }elseif($level==1){   // BASIC CLEAN
                        $string==trim($string);
                        $string=addslashes($string);
                }

                return $string;
}


function f1init_ago($timestamp){
   $text = time() - $timestamp;
   return $text;
}


function f1init_makeClickableLinks($text)
{

        $text = html_entity_decode($text);
        $text = " ".$text;
        $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a type="convertedurl" href="\\1">\\1</a>', $text);
        $text = eregi_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a type="convertedurl" href="\\1">\\1</a>', $text);
        $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
        '\\1<a type="convertedurl" href="http://\\2">\\2</a>', $text);
        $text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})',
        '<a type="convertedemail" href="mailto:\\1">\\1</a>', $text);
        $text = eregi_replace(' #([0-9a-z][0-9a-z-]+)',
        '<a type="hashtag" href="http://twitter.com/#search?q=\\1"> <em>#</em>\\1</a>', $text);
        $text = eregi_replace('@([0-9a-z][0-9a-z-]+)',
        '<a type="twitteruser" href="http://twitter.com/\\1"><em>@</em>\\1</a>', $text);

        return $text;
}






?>
