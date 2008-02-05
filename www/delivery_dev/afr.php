<?php

/*
+---------------------------------------------------------------------------+
| OpenX v${RELEASE_MAJOR_MINOR}                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 BuraBuraLimited                                   |
| For contact details, see: http://www.openx.org/                           |
|                                                                           |
| Copyright (c) 2000-2003 the phpAdsNew developers                          |
| For contact details, see: http://www.phpadsnew.com/                       |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

// Require the initialisation file
require_once '../../init-delivery.php';

// Required files
require_once MAX_PATH . '/lib/max/Delivery/adSelect.php';
require_once MAX_PATH . '/lib/max/Delivery/flash.php';

// No Caching
MAX_commonSetNoCacheHeaders();

//Register any script specific input variables
MAX_commonRegisterGlobalsArray(array('refresh', 'resize', 'rewrite', 'n'));

// Initialise any afr.php specific variables
if (!isset($rewrite))   $rewrite = 1;
if (!isset($refresh))   $refresh = 0;
if (!isset($resize))    $resize = 0;

// Get the banner
$banner = MAX_adSelect($what, $clientid, $target, $source, $withtext, $context, true, $ct0, $loc, $referer);

// Send cookie if needed
if (!empty($banner['html']) && !empty($n)) {
    // Send bannerid headers
    $cookie = array();
    $cookie[$conf['var']['adId']] = $banner['bannerid'];
    // Send zoneid headers
    if ($zoneid != 0) {
        $cookie[$conf['var']['zoneId']] = $zoneid;
    }
    // Send source headers
    if (!empty($source)) {
        $cookie[$conf['var']['channel']] = $source;
    }
    // Added code to update the destination URL stored in the cookie to hold the correct random value (Bug # 88)
    global $cookie_random;
    $cookie[$conf['var']['dest']] = str_replace('{random}', $cookie_random, $row['url']);
    // Set the cookie
    MAX_cookieSet($conf['var']['vars'] . "[$n]", serialize($cookie));
}

MAX_cookieFlush();

// Rewrite targets in HTML code to make sure they are
// local to the parent and not local to the iframe
if (isset($rewrite) && $rewrite == 1) {
	$banner['html'] = preg_replace('#target\s*=\s*([\'"])_parent\1#i', "target='_top'", $banner['html']);
	$banner['html'] = preg_replace('#target\s*=\s*([\'"])_self\1#i', "target='_parent'", $banner['html']);
}

// Build HTML
$outputHtml = "<html>\n";
$outputHtml .= "<head>\n";
$outputHtml .= "<title>".(!empty($banner['alt']) ? $banner['alt'] : 'Advertisement')."</title>\n";

// Include the FlashObject script if required
if (isset($banner['contenttype']) && $banner['contenttype'] == 'swf') {
    $outputHtml .= MAX_flashGetFlashObjectExternal();
}

// Add refresh meta tag if $refresh is set and numeric
if (isset($refresh) && is_numeric($refresh) && $refresh > 0) {
    $dest = MAX_commonGetDeliveryUrl($conf['file']['frame']).'?'.$_SERVER['QUERY_STRING'];
    parse_str($_SERVER['QUERY_STRING'], $qs);
    $dest .= (!array_key_exists('loc', $qs)) ? "&loc=" . urlencode($loc) : '';
	$outputHtml .= "<meta http-equiv='refresh' content='".$refresh.";url=".htmlspecialchars($dest)."'>\n";
}

if (isset($resize) && $resize == 1) {
	$outputHtml .= "<script type='text/javascript'>\n";
	$outputHtml .= "<!--// <![CDATA[ \n";
	$outputHtml .= "\tfunction MAX_adjustframe(frame) {\n";
	$outputHtml .= "\t\tif (document.all) {\n";
    $outputHtml .= "\t\t\tparent.document.all[frame.name].width = ".$banner['width'].";\n";
    $outputHtml .= "\t\t\tparent.document.all[frame.name].height = ".$banner['height'].";\n";
  	$outputHtml .= "\t\t}\n";
  	$outputHtml .= "\t\telse if (document.getElementById) {\n";
    $outputHtml .= "\t\t\tparent.document.getElementById(frame.name).width = ".$banner['width'].";\n";
    $outputHtml .= "\t\t\tparent.document.getElementById(frame.name).height = ".$banner['height'].";\n";
  	$outputHtml .= "\t\t}\n";
	$outputHtml .= "\t}\n";
	$outputHtml .= "// ]]> -->\n";
	$outputHtml .= "</script>\n";
}

$outputHtml .= "</head>\n";

if (isset($resize) && $resize == 1) {
	$outputHtml .= "<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' style='background-color:transparent; width: 100%; text-align: center;' onload=\"MAX_adjustframe(window);\">\n";
} else {
	$outputHtml .= "<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' style='background-color:transparent; width: 100%; text-align: center;'>\n";
}

$outputHtml .= $banner['html'];
$outputHtml .= "\n</body>\n";

$outputHtml .= "</html>\n";

echo $outputHtml;

?>
