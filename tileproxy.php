<?php
/*  cloudmade caching proxy / tileproxy.php
    Copyright (C) 2014, Rainer Bendig (@mrbendig)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

$cloudmadeapikey = "";
$cloudmadestyle = "";
$maxage=60*60*24*2;

$url = "http://%s.tile.cloudmade.com/%s/%s/256/%s/%s/%s.png";
$path = __DIR__."/tiles/%s_%s_%s_%s.png";

$s = $_GET["s"];
$x = $_GET["x"];
$y = $_GET["y"];
$z = $_GET["z"];


$fullpath = sprintf($path,$s,$z,$x,$y);

// check if file does not exist, or if it's aged if so fetch a new one
if(!file_exists($fullpath) || (time()-filemtime($fullpath) >= $maxage))
{
	set_time_limit(0);

	$options = array(
	  CURLOPT_FILE    => fopen ($fullpath, 'w+'),
	  CURLOPT_URL     => sprintf($url,$s,$cloudmadeapikey,$cloudmadestyle,$z,$x,$y)
	);

	$ch = curl_init();
	curl_setopt_array($ch, $options);
	curl_exec($ch);
}

// display the image
$im = imagecreatefrompng($fullpath);
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
