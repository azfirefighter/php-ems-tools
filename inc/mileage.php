<?php
/*
 +--------------------------------------------------------------------------------------------------------+
 | PHP EMS Tools      http://www.php-ems-tools.com                                                        |
 +--------------------------------------------------------------------------------------------------------+
 | Time-stamp: "2010-08-24 22:36:30 jantman"                                                              |
 +--------------------------------------------------------------------------------------------------------+
 | Copyright (c) 2009, 2010 Jason Antman. All rights reserved.                                            |
 |                                                                                                        |
 | This program is free software; you can redistribute it and/or modify                                   |
 | it under the terms of the GNU General Public License as published by                                   |
 | the Free Software Foundation; either version 3 of the License, or                                      |
 | (at your option) any later version.                                                                    |
 |                                                                                                        |
 | This program is distributed in the hope that it will be useful,                                        |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of                                         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                                          |
 | GNU General Public License for more details.                                                           |
 |                                                                                                        |
 | You should have received a copy of the GNU General Public License                                      |
 | along with this program; if not, write to:                                                             |
 |                                                                                                        |
 | Free Software Foundation, Inc.                                                                         |
 | 59 Temple Place - Suite 330                                                                            |
 | Boston, MA 02111-1307, USA.                                                                            |
 +--------------------------------------------------------------------------------------------------------+
 |Please use the above URL for bug reports and feature/support requests.                                  |
 +--------------------------------------------------------------------------------------------------------+
 | Authors: Jason Antman <jason@jasonantman.com>                                                          |
 +--------------------------------------------------------------------------------------------------------+
 | $LastChangedRevision:: 67                                                                            $ |
 | $HeadURL:: http://svn.jasonantman.com/newcall/inc/mileage.php                                        $ |
 +--------------------------------------------------------------------------------------------------------+
*/

/**
 * Functions related to unit mileage
 *
 * @package MPAC-NewCall-PHP
 */

function checkMileage($unit, $miles)
{
    $query = "SELECT end_mileage FROM calls_units WHERE unit='".mysql_real_escape_string(trim($unit))."' AND is_deprecated=0 ORDER BY end_mileage DESC LIMIT 1;";
    $result = mysql_query($query) or die("ERROR: Error in query: $query ERROR: ".mysql_error());

    if(mysql_num_rows($result) < 1)
    {
	// no previous entries for this rig.
	return "";
    }

    $row = mysql_fetch_assoc($result);
    $lastMiles = (int)$row['end_mileage'];

    if($miles < $lastMiles){ return "TOOLOW";}

    if($miles > ($lastMiles + 30)){ return "TOOHIGH";}

    return "";
}

?>