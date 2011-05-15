<?php

ini_set ('user_agent', $_SERVER['HTTP_USER_AGENT']);

$context = array(
    'http'=>array('max_redirects' => 10)
);

$context = stream_context_create($context);



function exifToNumber($value, $format) {
	$spos = strpos($value, '/');
	if ($spos === false) {
		return sprintf($format, $value);
	} else {
		list($base,$divider) = split("/", $value, 2);
		if ($divider == 0) 
			return sprintf($format, 0);
		else
			return sprintf($format, ($base / $divider));
	}
}

function exifToCoordinate($reference, $coordinate) {
	if ($reference == 'S' || $reference == 'W')
		$prefix = '-';
	else
		$prefix = '';
		
	return $prefix . sprintf('%.6F', exifToNumber($coordinate[0], '%.6F') +
		(((exifToNumber($coordinate[1], '%.6F') * 60) +	
		(exifToNumber($coordinate[2], '%.6F'))) / 3600));
}

function getCoordinates($filename){
	if (extension_loaded('exif')) {
		$exif = exif_read_data($_GET['filename'], 'EXIF');
		
		if (isset($exif['GPSLatitudeRef']) && isset($exif['GPSLatitude']) && 
			isset($exif['GPSLongitudeRef']) && isset($exif['GPSLongitude'])) {
			return array (
				exifToCoordinate($exif['GPSLatitudeRef'], $exif['GPSLatitude']), 
				exifToCoordinate($exif['GPSLongitudeRef'], $exif['GPSLongitude'])
			);
		}
	}
}

function coordinate2DMS($coordinate, $pos, $neg) {
	$sign = $coordinate >= 0 ? $pos : $neg;
	
	$coordinate = abs($coordinate);
	$degree = intval($coordinate);
	$coordinate = ($coordinate - $degree) * 60;
	$minute = intval($coordinate);
	$second = ($coordinate - $minute) * 60;
	
	return sprintf("%s %d&#xB0; %02d&#x2032; %05.2f&#x2033;", $sign, $degree, $minute, $second);
}









//$exif = exif_read_data($_GET['filename'],'EXIF');
/*
var_dump($exif['GPSLatitude']);
var_dump($exif['GPSLatitudeRef']);
var_dump($exif['GPSLongitude']);
var_dump($exif['GPSLongitudeRef']);
*/



if ($c = getCoordinates($_GET['$filename'])) {
    $latitude = $c[0];
    $longitude = $c[1];

    // use the data in fun ways...
}

echo 'http://www.google.com/maps?z=16&ll='.$latitude.','.$longitude.'&t=k';



?>
