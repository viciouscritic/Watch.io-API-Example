<?php

error_reporting(0);

$cachetime = 2 * 60 * 60;  //2 hours measured in seconds
$cachedir = "cache";
$cachefile = $cachedir ."/watchiocache.php";			
if (file_exists($cachefile) && filesize($cachefile) > 0 && (time() - $cachetime < filemtime($cachefile))) 
{
	$html = file_get_contents($cachefile);
	exit;
}
						
$baseurl = "http://watch.io/api/query.php?";
$credit = "http://watch.io";

$params = array(
	'show' => 'mister ed',
	'exact' => 'true',
	'max' => '5',
	'domain' => 'example.com',
	'format' => 'json'
	);

$query = http_build_query($params);
$url = $baseurl .$query;

$result = file_get_contents($url);
$show = json_decode($result, TRUE);

$html = "";

if(!empty($show) && $show['result'] == 'success')
{
	foreach($show['episodes'] as $e)
	{
		$html .= '<a href="' .htmlentities($e['watchurl']) .'"><img src="' .htmlentities($e['thumb']) .'"></a>';
		$html .= '<a href="' .htmlentities($e['watchurl']) .'">' .htmlentities($e['showname']) .' : ' .htmlentities($e['title']) .'</a>';
		$html .= '<p>' .sprintf("S%02sE%02s", $e['season'], $e['episode']) .'</p>';
		$html .= '<p>' .$e['synopsis'] .'</p>';
	}

	if(!empty($html))
	{
		$html .= '<br>';
		$html .='<p>Powered by: <a href="' .htmlentities($credit) .'">Watch.io</a></p>';
		
		if(file_exists($cachedir))	//make sure the cache directory exists
		{
			$fp = fopen($cachefile, 'w');
			fwrite($fp, $html);
			fclose($fp);
		}
	}
}

echo $html;


/*

Copyright (c) 2011, Lance Ward
To the extent possible under law, Lance has waived all copyright and related or neighboring rights to Watch.io API Example. This work is published from: United States.

http://creativecommons.org/publicdomain/zero/1.0/

*/	
?>