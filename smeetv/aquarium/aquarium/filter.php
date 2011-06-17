<?php
///////////////////////////////////////////////////////////////////////////////
// PACKAGE: Aquarium -- PHP profanity filtering script                       //
// PURPOSE: This PHP script filters a string for curses and inapropriate     //
//          language.                                                        //
// STATS:   The filter is reasonably fast, it can filter about 220 words per //
//          second (of course depending on your hardware).                   //
// USAGE:   In any other PHP file, add the following line                    //
//             "require_once('/aquarium/filter.php');"                       //
//          statement. then call the function "filter($text);" on any        //
//          string.                                                          //
// SOURCE:  Written by Tom Reitz (treitz@reitzinternet.com). This software   //
//          is distributed free under the GPL. You may use, modify, edit and //
//          copy this software as much as  you want, but note that it is     //
//          provided as-is with no warranty or guarantee.                    //
// AUTHOR:  Tom Reitz. I'm a computer science student, I code in PHP in my   //
//          free time. Visit my webpage at www.cs.wisc.edu/~tomas/           //
//          See www.reitzinternet.com/opensource/ for more free PHP scripts! //
//                                                                           //
// USER SETTINGS: (tweak these if you want)                                  //
////////////////////////////                                                 //
$sens = 0.85;             // Filter sensitivity. A number between 0 and 1.   //
                          // 0.85 seems to work well.                        //
                          //                                                 //
$symb = "*";              // The character to use when filtering a bad word. //
                          //                                                 //
                          // The following are flags for what kinds of bad   //
                          // words to filter. When in doubt, set to true.    //
$medical_words   = true;  // <-- Medical terms for sexual drugs and diseases //
$porn_words      = true;  // <-- Pornographic words                          //
$bathroom_words  = true;  // <-- Words for bathroom activities               //
$fourlettr_words = true;  // <-- Four-letter words                           //
$curse_words     = true;  // <-- Curse words                                 //
$throwup_words   = true;  // <-- Words for the act of throwing up            //
$samesex_words   = true;  // <-- Words for non-heterosexualality             //
$other_words     = true;  // <-- A few other objectionable words             //
                          //                                                 //
////////////////////////////                                                 //
// OTHER NOTES: Thanks for using this software!                              //
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
// FUNCTION: filter()
// Parameter: a string of text to be filtered.
// Return Value: an array:
//     Element 0 is the parameter string with bad words filtered.
//     Element 1 is the length of time the filtering took (in seconds).
//     Element 2 is the integer number of words in the parameter string.
//     Element 3 is the integer number of bad words that were filtered.
///////////////////////////////////////////////////////////////////////////////
function filter($text) {
	$time_start = microtime(true);

	global $sens,$symb,$medical_words,$porn_words,$bathroom_words,
               $fourlettr_words,$curse_words,$throwup_words,$samesex_words,
               $other_words;
	
	//$words = file("blacklist_unencr.txt");
	//for($i=0;$i<count($words);$i++) echo base64_encode(strip_bare($words[$i]))."<br>";

	// Read in and decrypt the blacklisted words, and remove extra line breaks, etc.
	$words_tmp = file("/var/www/smeetv.com/smeetv/aquarium/aquarium/blacklist.txt");
	for($i=0;$i<count($words_tmp);$i++) $words_tmp[$i]=strip_bare(strtolower(base64_decode($words_tmp[$i])));

	// Parse the file
	$i=0;
	$comment_count = 0;
	while($i<count($words_tmp)) {
		if($words_tmp[$i][0]=='#') {
			$comment_count++;
			$i++;
			continue;
		}
		if($comment_count==1) $medical[] = $words_tmp[$i];
		if($comment_count==2) $porn[] = $words_tmp[$i];
		if($comment_count==3) $bathroom[] = $words_tmp[$i];
		if($comment_count==4) $fourlettr[] = $words_tmp[$i];
		if($comment_count==5) $curse[] = $words_tmp[$i];
		if($comment_count==6) $throwup[] = $words_tmp[$i];
		if($comment_count==7) $samesex[] = $words_tmp[$i];
		if($comment_count==8) $other[] = $words_tmp[$i];
		$i++;
	}

	$words = array();
	if($medical != NULL && $medical_words) $words = array_merge($words,$medical);
	if($porn != NULL && $porn_words) $words = array_merge($words,$porn);
	if($bathroom != NULL && $bathroom_words) $words = array_merge($words,$bathroom);
	if($fourlettr != NULL && $fourlettr_words) $words = array_merge($words,$fourlettr);
	if($curse != NULL && $curse_words) $words = array_merge($words,$curse);
	if($throwup != NULL && $throwup_words) $words = array_merge($words,$throwup);
	if($samesex != NULL && $samesex_words) $words = array_merge($words,$samesex);
	if($other != NULL && $other_words) $words = array_merge($words,$other);

	// Load the whitelisted words file, and remove extra line breaks, etc.
	$except = file("/var/www/smeetv.com/smeetv/aquarium/aquarium/whitelist.txt");
	for($i=0;$i<count($except);$i++) $except[$i] = strip_bare(strtolower($except[$i]));
	// Script up to this point takes under 0.05 seconds.

	// Split the text into individual words
	$text = stripslashes($text);
	$tok = parse_string($text."\n");

	$bad_words_counter=0;
	// For every word in the text to be filtered...
	for($j=0;$j<count($tok);$j++) {
		$skip = false;
		// Check if its a whitelisted word...
		if(in_array($tok[$j],$except) || in_array(strip_end($tok[$j]),$except) || strlen($tok[$j]>100)) $skip = true;
		// If not, check if its similar to a blacklisted word...
		if($skip) continue;
		$blank_out = false;
		if(in_array($tok[$j],$words)==true) $blank_out = true;
		for($i=0;$i<count($words);$i++) {
			$similar = get_similarity($words[$i],strip_end($tok[$j]));
			if($similar >= $sens) {
				$blank_out = true;
				break;
			}
		}
		if($blank_out) {
			// If we get here, we're dealing with a bad word.
			// Filter it!
			$replace=str_repeat($symb,strlen($tok[$j]));
			$text = str_ireplace_once($tok[$j],$replace,$text);
			$bad_words_counter++;
		}
	}
	$time_finish = microtime(true);
	$time = $time_finish - $time_start;

	return array($text,$time,count($tok),$bad_words_counter);
}




///////////////////////////////////////////////////////////////////////////////
// FUNCTION: parse_string()
// This function takes a string of any length and returns an array
// of the words in the string. A word is defined as a group of
// characters that is seperated from other groups of characters by
// a space character (' ').
///////////////////////////////////////////////////////////////////////////////

function parse_string($string) {
	$tokens = array();
	$string2 = str_replace(array("\r\n", "\r", "\n"), " ", $string);
	$curr_elmt = 0;
	$word = "";
	for($i=0;$i<strlen($string2);$i++) {
		if($string2[$i]==' ' && $word!="") {
			$tokens[$curr_elmt] = $word;
			$word = "";
			$curr_elmt++;
		}
		else $word .= $string2[$i];
	}
	for($i=0;$i<count($tokens);$i++) $tokens[$i] = strip_bare(strtolower($tokens[$i]));
	return $tokens;
}




///////////////////////////////////////////////////////////////////////////////
// FUNCTION: get_similarity()
// This function is used to determine the similarity between two
// words (passed as parameters). Two factors are considered in
// this similarity check:
// (1) The words are tested for similarity of sound (pronounciation),
//     using PHP's metaphone function.
// (2) The words are tested for similarity of spelling, using PHP's
//     levenshtein function.
// Each similarity test returns a number between 0 and 1 (for the
// sound-alike test, it is exactly either 0 or 1), and the average
// of the two results is returned (also a number between 0 and 1).
///////////////////////////////////////////////////////////////////////////////

function get_similarity($word_a, $word_b) {
	$meta1 = metaphone($word_a);
	$meta2 = metaphone($word_b);
	$sounds_similar = 0.0;
	if($meta1==$meta2) $sounds_similar = 1.0;
	$looks_similar = 1-(levenshtein($word_a,$word_b)/max(strlen($word_a),strlen($word_b)));
	return (0.5*$looks_similar + 0.5*$sounds_similar);
}




///////////////////////////////////////////////////////////////////////////////
// FUNCTION: str_ireplace_once()
// Same as PHP's str_ireplace, but only replaces the first occurance of the
// search string, not all occurances.
///////////////////////////////////////////////////////////////////////////////

function str_ireplace_once($search, $replace, $subject) {
	$first_char = strpos($subject, $search);
	$front_chunk = substr($subject,0,$first_char);
	$back_chunk = substr($subject,($first_char+strlen($search)),(strlen($subject)-1));
	return $front_chunk.$replace.$back_chunk;
}




///////////////////////////////////////////////////////////////////////////////
// FUNCTION: strip_bare()
// This function strips all whitespace, punctuation, tags, and line breaks
// out of a string.
///////////////////////////////////////////////////////////////////////////////

function strip_bare($string) {
	// Strip line breaks
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	// strip punctuation
	$string = str_replace(array("\"", "'", "/", "_", ",", ".", ";", ":", "!", "?", "-", "&", ",$"), "", $string);
	// Strip whitespace
	$string = str_replace(array("\t", " "), "", $string);
	// strip tags
	$string = strip_tags($string);
	return $string;
}




///////////////////////////////////////////////////////////////////////////////
// FUNCTION: strip_end()
// This function strips all endings off of a word.
///////////////////////////////////////////////////////////////////////////////

function strip_end($string) {
	// Strip endings
	if(substr($string,-2)=="'s") $string = substr($string,0,-2);
	if(substr($string,-1)=="s" && get_similarity($string,"ass")<=0.5) $string = substr($string,0,-1);
	if(substr($string,-2)=="er") $string = substr($string,0,-2);
	if(substr($string,-2)=="ed") $string = substr($string,0,-2);
	if(substr($string,-3)=="ing") $string = substr($string,0,-3);
	if(substr($string,-3)=="ion") $string = substr($string,0,-3);
	if(substr($string,-1)=="y") $string = substr($string,0,-1);
	return $string;
}
?>
