<?php
/**
 * Cloudmade-Caching-Proxy
 *
 * A small script that respects German Privacy Laws. Just get the tiles over this
 * script, and cloudmade will never get your visitors ip address or location.
 *
 * Using this script with cloudmade requires a cloudmade account / api key,
 * and a cloudmade style id.
 *
 * @category OSM_Proxy
 * @package  Cloudmade-Caching-Proxy
 * @author   Rainer Bendig <mrbendig@mrbendig.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License 2.0
 * @version  1.0
 * @link     https://github.com/mrbendig/cloudmade-caching-proxy/blob/master/tileproxy.php
 */

$cloudmadeApikKy = "";
$cloudmadeStyle = "";

// Caching cloudmade tiles may conflict with using terms.
$maxCachingTime=60*60*24*2;

$cloudmadeTileUrl = "http://%s.tile.cloudmade.com/%s/%s/256/%s/%s/%s.png";
$cacheFilePath = __DIR__."/tiles/%s_%s_%s_%s.png";

$s = $_GET["s"];
$x = (is_numeric($_GET["x"]) ? $_GET["x"] : 0);
$y = (is_numeric($_GET["y"]) ? $_GET["y"] : 0);
$z = (is_numeric($_GET["z"]) ? $_GET["z"] : 0);

// build the caching path
$fullCacheFilePath = sprintf($cacheFilePath, $s, $z, $x, $y);

/////////////////////////////////////////////////////////////////////////
// check if file does not exist, or if it's aged if so fetch a new one //
/////////////////////////////////////////////////////////////////////////
if (!file_exists($fullCacheFilePath) || (time()-filemtime($fullCacheFilePath) >= $maxCachingTime) ) {

    /////////////////////////////////////////////////////////////////////////
    // enhance timeouts, for the case if cloudmade is responding too slow. //
    /////////////////////////////////////////////////////////////////////////
    set_time_limit(0);

    $curlOptions = array(
        CURLOPT_FILE => fopen($fullCacheFilePath, 'w+'),
        CURLOPT_URL => sprintf($cloudmadeTileUrl, $s, $cloudmadeApiKey, $cloumadeStyle, $z, $x, $y)
    );

    ///////////////////////////////
    //Download and save the tile //
    ///////////////////////////////
    $curlHandler = curl_init();
    curl_setopt_array($curlHandler, $curlOptions);
    curl_exec($curlHandler);
    curl_close($curlHandler);
}

///////////////////////
// display the image //
///////////////////////
$createdImage = imagecreatefrompng($fullCacheFilePath);
header('Content-Type: image/png');
imagepng($createdImage);
imagedestroy($createdImage);