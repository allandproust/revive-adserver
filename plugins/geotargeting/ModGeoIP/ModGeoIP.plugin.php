<?php

/*
+---------------------------------------------------------------------------+
| OpenX v${RELEASE_MAJOR_MINOR}                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 BuraBuraLimited                                   |
| For contact details, see: http://www.openx.org/                           |
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

require_once MAX_PATH . '/plugins/geotargeting/Geotargeting.php';
require_once(MAX_PATH . '/plugins/geotargeting/ModGeoIP/ModGeoIP.delivery.php');

/**
 * Class to get GeoTargeting information from the MaxMind LLC database file,
 * after the lookup has been performed via the C/mod_apache interface.
 *
 * @static
 * @package    OpenadsPlugin
 * @subpackage Geotargeting
 * @author     Andrew Hill <andrew@m3.net>
 * @author     Radek Maciaszek <radek@m3.net>
 */
class Plugins_Geotargeting_ModGeoIP_ModGeoIP extends Plugins_Geotargeting
{

    /**
     * A method to return information about the class.
     *
     * @return string A string describing the class.
     */
    function getModuleInfo()
    {
        return 'MaxMind mod_apache GeoIP';
    }

    /**
     * The method calls to the delivery half of the plugin to get the
     * geo information
     *
     * @return array An array that will contain the results of the
     *               GeoTargeting lookup.
     */
    function getInfo()
    {
        return OA_Geo_ModGeoIP_getInfo();
    }
}

?>