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
require_once(MAX_PATH . '/lib/max/Delivery/cache.php');
require_once(MAX_PATH . '/lib/max/Delivery/javascript.php');
require_once MAX_PATH . '/lib/max/Delivery/tracker.php';

//Register any script specific input variables
MAX_commonRegisterGlobalsArray(array('trackerid', 'inherit'));
if (empty($trackerid)) $trackerid = 0;

// Determine the user ID
$userid = MAX_cookieGetUniqueViewerID(false);
$conversionsid = NULL;
$variables_script = '';

header("Content-type: application/x-javascript");

// Log the tracker impression
$logVars = false;
if ($conf['logging']['trackerImpressions']) {
    $conversionInfo = MAX_Delivery_log_logTrackerImpression($userid, $trackerid);
    // Generate code required to send variable values to the {$conf['file']['conversionvars']} script
    if (isset($inherit)) {
        $variablesScript = MAX_trackerbuildJSVariablesScript($trackerid, $conversionInfo, $inherit);
    } else {
        $variablesScript = MAX_trackerbuildJSVariablesScript($trackerid, $conversionInfo);
    }
    $logVars = true;
}

MAX_cookieFlush();
// Write the code for seding the variable values
if ($logVars) {
    echo "$variablesScript";
}

?>