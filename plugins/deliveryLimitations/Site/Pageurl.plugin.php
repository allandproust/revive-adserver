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

require_once MAX_PATH . '/plugins/deliveryLimitations/DeliveryLimitations.php';
require_once MAX_PATH . '/lib/max/Plugin/Translation.php';

/**
 * A Site delivery limitation plugin, for filtering delivery of ads on the
 * basis of the URL of the page the ad is on.
 *
 * Valid comparison operators:
 * ==, !=, =~, !~, =x, !x
 *
 * @package    OpenadsPlugin
 * @subpackage DeliveryLimitations
 * @author     Andrew Hill <andrew@m3.net>
 * @author     Chris Nutting <chris@m3.net>
 */
class Plugins_DeliveryLimitations_Site_Pageurl extends Plugins_DeliveryLimitations
{
    var $defaultComparison = '=~';

    function Plugins_DeliveryLimitations_Site_Pageurl()
    {
        $this->Plugins_DeliveryLimitations();

        $aConf = $GLOBALS['_MAX']['CONF'];
        if ($aConf['database']['type'] == 'mysql') {
            $this->columnName = 'CONCAT(IF(https=1, \'https://\', \'http://\'), domain, page, IF(query<>\'\', \'?\', \'\'),query)';
        } else {
            $this->columnName = 'IF(https=1, \'https://\', \'http://\') || domain || page || IF(query<>\'\', \'?\', \'\') || query';
        }
    }

    /**
     * Return name of plugin
     *
     * @return string
     */
    function getName()
    {
        return MAX_Plugin_Translation::translate('Page URL', $this->module, $this->package);
    }


    function getUpgradeFromEarly($op, $sData)
    {
        return OA_limitationsGetUpgradeForContains($op, $sData);
    }

}

?>
