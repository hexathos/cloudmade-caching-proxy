cloudmade-caching-proxy
=======================

This file is a caching proxy script for fetching cloudmade tiles, it is also
enhancing the cloudmade call to respect the German Data Protection Act.

It will use your servers ip address and not the ip address of your visitors
for accessing cloudmade.com

Place the file in a subfolder of your folder (e.g. /wp-content) and create
a folder called "tiles".

Edit the file, pasting your style id, and your cloudmade api key.

If you are using leafletjs, change the tile layer to
<yoururl>/wp-content/tileproxy.php?s={s}&z={z}&x={x}&y={y}

This script is not supported. Use it at your own risk.
