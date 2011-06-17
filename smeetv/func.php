<?

function connect2db() {
    mysql_pconnect("localhost","root","");
    mysql_select_db("smeetv");
}

function validate_username($v_username) {
   connect2db();
   $query="select id from accounts where username = '$v_username'";
   $go=mysql_query($query);
   $num=mysql_num_rows($go);
   if(mysql_num_rows($go)) {
       return FALSE;
}


   return eregi('[^a-z0-9_]', $v_username) ? FALSE : TRUE;
}

function validate_email($v_email) {
   connect2db();
   $query="select id from accounts where email='$v_email'";
   $go=mysql_query($query);
   $num=mysql_num_rows($go);
   if(mysql_num_rows($go)) {
       return FALSE;
   }
   if(filter_var($v_email, FILTER_VALIDATE_EMAIL)){
       return TRUE;
   }else{
       return FALSE;
   }
}






function displayTwit($id,$content,$link,$date,$timestamp,$squares=0,$link_override=0) {

    $twusr=explode("/",$link);
    $output='';


    $output.='
		<article id="'.alphaID($id).'" class="twit lo'.$link_override.'">
			<div class="t">';
    if($link_override==1) $output.='<a href="http://smeetv.com/img/'.alphaID($id).'">';
    if($link_override==0) $output.=f1init_makeClickableLinks($content);
    else $output.=$content;
    if($link_override==1) $output.='</a>';
    $output.='
				<footer>';
    if($link_override==1) $output.='<a href="http://smeetv.com/img/'.alphaID($id).'">';
    if($link_override==0) $output.='<a href="'.$link.'">posted by '.$twusr[3].'</a>';
    else $output.='posted by '.$twusr[3];
    $output.=',
					discovered <time id="'.$id.'" datetime="'. date('Y-m-d, H:i', $timestamp).'">'.
                                        //ceil(f1init_ago($timestamp)/60)
                                        nicetime($timestamp)
                                        .'</time>';
    if($link_override==0) $output.=', <a onclick="return confirm(\'Are you sure you want to flag this photo? It will remove it from your TV and mark it as unsafe for others.\')" href="/img/report/'.alphaID($id).'">report this image</a>';
    if($link_override==1) $output.='</a>';
    $output.='
				</footer>';
    $output.='
                                <span class="slant"></span>
			</div>
                        ';
    if($squares==1){
    $output.='
			<aside>
				<section id="mainimg" class="squares mainimg" >
                                   <article id="'.alphaID($id).'" rel="'.$link.'" class="hide">'.$content.'</article>
				</section>
				<section class="squares share">
					<div style="clear:both">
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_twitter"></a>
<!--<a class="addthis_button_facebook"></a>-->
<a class="addthis_button_email"></a>

<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4dbc63166fcdf6f9"></script>
<!-- AddThis Button END -->
<iframe src="https://www.facebook.com/plugins/like.php?&href=http://smeetv.com'.$_SERVER['REQUEST_URI'].'"
        scrolling="no" frameborder="0"
        style="border:none; width:450px; height:80px"></iframe>

					</div>
					<script type="text/javascript"><!--
					google_ad_client = "ca-pub-1221828368307550";
					/* smeetv_injected_slide */
					google_ad_slot = "2446448564";
					google_ad_width = 336;
					google_ad_height = 280;
					//-->
					</script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>		
				</section>
				<br class="clear">
				<section class="squares ymlw">
					<h3>You may also like:</h3>';

preg_match_all('/(\w+)/',$content,$matches);
preg_match_all('/#(\w+)/',$content,$matches_hash);

if(count($matches_hash[0])>0){ /*hashes found*/
    $words_found=count($matches[0]);
    $force_size = count($matches[0]);
}else{ /*hashtags not found*/
    $words_found=count($matches[0]);
    usort($matches[0],'sortByLength');
    $force_size = ceil(count($matches[0]) / 3);
}


/*

    $output.='
					<p id="yml1" class="yml"><iframe src="/etc/suggest/img/'.getWordSuggestion($matches[0][rand(0,$force_size)]).'"></iframe></p>
					<p id="yml2" class="yml"><iframe src="/etc/suggest/img/'.getWordSuggestion($matches[0][rand(0,$force_size)]).'"></iframe></p>
					<p id="yml3" class="yml"><iframe src="/etc/suggest/img/'.getWordSuggestion($matches[0][rand(0,$force_size)]).'"></iframe></p>
					<p id="yml4" class="yml"><iframe src="/etc/suggest/img/'.getWordSuggestion($matches[0][rand(0,$force_size)]).'"></iframe></p>
					<p id="yml5" class="yml"><iframe src="/etc/suggest/img/'.getWordSuggestion($matches[0][rand(0,$force_size)]).'"></iframe></p>
				</section>
				<section class="squares">		
				</section>
			</aside>
                        ';
*/
    }
    $output.='
		</article>
    ';


    return $output;
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

/** 
 * xml2array() will convert the given XML text to an array in the XML structure. 
 * Link: http://www.bin-co.com/php/scripts/xml2array/ 
 * Arguments : $contents - The XML text 
 *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
 *                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
 * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure. 
 * Examples: $array =  xml2array(file_get_contents('feed.xml')); 
 *              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute')); 
 */ 
function xml2array($contents, $get_attributes=1, $priority = 'tag') { 
    if(!$contents) return array(); 

    if(!function_exists('xml_parser_create')) { 
        //print "'xml_parser_create()' function not found!"; 
        return array(); 
    } 

    //Get the XML parser of PHP - PHP must have this module for the parser to work 
    $parser = xml_parser_create(''); 
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss 
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
    xml_parse_into_struct($parser, trim($contents), $xml_values); 
    xml_parser_free($parser); 

    if(!$xml_values) return;//Hmm... 

    //Initializations 
    $xml_array = array(); 
    $parents = array(); 
    $opened_tags = array(); 
    $arr = array(); 

    $current = &$xml_array; //Refference 

    //Go through the tags. 
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array 
    foreach($xml_values as $data) { 
        unset($attributes,$value);//Remove existing values, or there will be trouble 

        //This command will extract these variables into the foreach scope 
        // tag(string), type(string), level(int), attributes(array). 
        extract($data);//We could use the array by itself, but this cooler. 

        $result = array(); 
        $attributes_data = array(); 
         
        if(isset($value)) { 
            if($priority == 'tag') $result = $value; 
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode 
        } 

        //Set the attributes too. 
        if(isset($attributes) and $get_attributes) { 
            foreach($attributes as $attr => $val) { 
                if($priority == 'tag') $attributes_data[$attr] = $val; 
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
            } 
        } 

        //See tag status and do the needed. 
        if($type == "open") {//The starting of the tag '<tag>' 
            $parent[$level-1] = &$current; 
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
                $current[$tag] = $result; 
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data; 
                $repeated_tag_index[$tag.'_'.$level] = 1; 

                $current = &$current[$tag]; 

            } else { //There was another element with the same tag name 

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array 
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
                    $repeated_tag_index[$tag.'_'.$level]++; 
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2; 
                     
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well 
                        $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
                        unset($current[$tag.'_attr']); 
                    } 

                } 
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
                $current = &$current[$tag][$last_item_index]; 
            } 

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
            //See if the key is already taken. 
            if(!isset($current[$tag])) { //New Key 
                $current[$tag] = $result; 
                $repeated_tag_index[$tag.'_'.$level] = 1; 
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data; 

            } else { //If taken, put all things inside a list(array) 
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array... 

                    // ...push the new element into that array. 
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
                     
                    if($priority == 'tag' and $get_attributes and $attributes_data) { 
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
                    } 
                    $repeated_tag_index[$tag.'_'.$level]++; 

                } else { //If it is not an array... 
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1; 
                    if($priority == 'tag' and $get_attributes) { 
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                             
                            $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
                            unset($current[$tag.'_attr']); 
                        } 
                         
                        if($attributes_data) { 
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
                        } 
                    } 
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken 
                } 
            } 

        } elseif($type == 'close') { //End of tag '</tag>' 
            $current = &$parent[$level-1]; 
        } 
    } 
     
    return($xml_array); 
}  



function getWordSuggestion($var){
// get contents of a file into a string
$filename = "http://google.com/complete/search?output=toolbar&q=$var";

$handle = fopen($filename, "rb");$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);


$x=xml2array($contents);
$offset=count($x[toplevel][CompleteSuggestion]);
$kwd=$x[toplevel][CompleteSuggestion][rand(0,$offset-1)][suggestion_attr][data];
return $kwd;
}


function sortByLength($a,$b){
  /* usage: usort($matches[0],'sortByLength')*/
  if($a == $b) return 0;
  return (strlen($a) > strlen($b) ? -1 : 1);
}



/** http://kevin.vanzonneveld.net/techblog/article/create_short_ids_with_php_like_youtube_or_tinyurl/
 * Translates a number to a short alhanumeric version
 *
 * Translated any number up to 9007199254740992
 * to a shorter version in letters e.g.:
 * 9007199254740989 --> PpQXn7COf
 *
 * specifiying the second argument true, it will
 * translate back e.g.:
 * PpQXn7COf --> 9007199254740989
 *
 * this function is based on any2dec && dec2any by
 * fragmer[at]mail[dot]ru
 * see: http://nl3.php.net/manual/en/function.base-convert.php#52450
 *
 * If you want the alphaID to be at least 3 letter long, use the
 * $pad_up = 3 argument
 *
 * In most cases this is better than totally random ID generators
 * because this can easily avoid duplicate ID's.
 * For example if you correlate the alpha ID to an auto incrementing ID
 * in your database, you're done.
 *
 * The reverse is done because it makes it slightly more cryptic,
 * but it also makes it easier to spread lots of IDs in different
 * directories on your filesystem. Example:
 * $part1 = substr($alpha_id,0,1);
 * $part2 = substr($alpha_id,1,1);
 * $part3 = substr($alpha_id,2,strlen($alpha_id));
 * $destindir = "/".$part1."/".$part2."/".$part3;
 * // by reversing, directories are more evenly spread out. The
 * // first 26 directories already occupy 26 main levels
 *
 * more info on limitation:
 * - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
 *
 * if you really need this for bigger numbers you probably have to look
 * at things like: http://theserverpages.com/php/manual/en/ref.bc.php
 * or: http://theserverpages.com/php/manual/en/ref.gmp.php
 * but I haven't really dugg into this. If you have more info on those
 * matters feel free to leave a comment.
 *
 * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author  Simon Franz
 * @author  Deadfish
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link    http://kevin.vanzonneveld.net/
 *
 * @param mixed   $in    String or long input to translate
 * @param boolean $to_num  Reverses translation when true
 * @param mixed   $pad_up  Number or boolean padds the result up to a specified length
 * @param string  $passKey Supplying a password makes it harder to calculate the original ID
 *
 * @return mixed string or long
 */
function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
{
  $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  if ($passKey !== null) {
    // Although this function's purpose is to just make the
    // ID short - and not so much secure,
    // with this patch by Simon Franz (http://blog.snaky.org/)
    // you can optionally supply a password to make it harder
    // to calculate the corresponding numeric ID
 
    for ($n = 0; $n<strlen($index); $n++) {
      $i[] = substr( $index,$n ,1);
    }
 
    $passhash = hash('sha256',$passKey);
    $passhash = (strlen($passhash) < strlen($index))
      ? hash('sha512',$passKey)
      : $passhash;
 
    for ($n=0; $n < strlen($index); $n++) {
      $p[] =  substr($passhash, $n ,1);
    }
 
    array_multisort($p,  SORT_DESC, $i);
    $index = implode($i);
  }
 
  $base  = strlen($index);
 
  if ($to_num) {
    // Digital number  <<--  alphabet letter code
    $in  = strrev($in);
    $out = 0;
    $len = strlen($in) - 1;
    for ($t = 0; $t <= $len; $t++) {
      $bcpow = bcpow($base, $len - $t);
      $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
    }
 
    if (is_numeric($pad_up)) {
      $pad_up--;
      if ($pad_up > 0) {
        $out -= pow($base, $pad_up);
      }
    }
    $out = sprintf('%F', $out);
    $out = substr($out, 0, strpos($out, '.'));
  } else {
    // Digital number  -->>  alphabet letter code
    if (is_numeric($pad_up)) {
      $pad_up--;
      if ($pad_up > 0) {
        $in += pow($base, $pad_up);
      }
    }
 
    $out = "";
    for ($t = floor(log($in, $base)); $t >= 0; $t--) {
      $bcp = bcpow($base, $t);
      $a   = floor($in / $bcp) % $base;
      $out = $out . substr($index, $a, 1);
      $in  = $in - ($a * $bcp);
    }
    $out = strrev($out); // reverse
  }
 
  return $out;
}




function nicetime($date)
{
    if(empty($date)) {
        return "No date provided";
    }
    
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date         = strtotime($date);
    $unix_date         = $date;
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;
        $tense         = "ago";
        
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
}











?>
