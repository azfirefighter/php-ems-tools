<?php
/*
 +--------------------------------------------------------------------------------------------------------+
 | PHP EMS Tools      http://www.php-ems-tools.com                                                        |
 +--------------------------------------------------------------------------------------------------------+
 | Time-stamp: "2010-08-24 22:36:24 jantman"                                                              |
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
 | $HeadURL:: http://svn.jasonantman.com/newcall/inc/nav.php                                            $ |
 +--------------------------------------------------------------------------------------------------------+
*/

/**
 * Functions to output navigation controls for view/edit.
 *
 * @package MPAC-NewCall-PHP
 */

function genNavControls($RunNumber, $edit = false)
{
    global $vals;
    if($edit){ $action = "Edit";} else { $action = "View";}

    $s = '<div class="callNav">'."\n";

    $query = "SELECT RunNumber FROM calls WHERE RunNumber < $RunNumber ORDER BY RunNumber DESC LIMIT 1;";
    $result = mysql_query($query) or die("ERROR: Error in query: $query ERROR: ".mysql_error());
    if(mysql_num_rows($result) > 0)
    {
	$row = mysql_fetch_assoc($result);
	$lastRun = $row['RunNumber'];
    }

    $query = "SELECT RunNumber FROM calls WHERE RunNumber > $RunNumber ORDER BY RunNumber ASC LIMIT 1;";
    $result = mysql_query($query) or die("ERROR: Error in query: $query ERROR: ".mysql_error());
    if(mysql_num_rows($result) > 0)
    {
	$row = mysql_fetch_assoc($result);
	$nextRun = $row['RunNumber'];
    }

    echo '<table style="width: 100%;">'."\n";
    echo '<tr>';
    if(isset($lastRun))
    {
	echo '<td style="text-align: left; width: 20%;"><a href="newcall.php?RunNumber='.$lastRun;
	if($edit){ echo "&action=edit";}
	echo '">&larr; '.formatRunNum($lastRun).'</a></td>';
    }
    else
    {
	echo '<td style="width: 20%;">&nbsp;</td>';
    }
    // view by number
    echo '<td>'.'<form name="goToCall" method="GET" action="newcall.php"><label for="RunNumber"><strong>'.$action.' Call:</strong> <input type="text" name="RunNumber" id="RunNumber" size="10" /><input type="submit" value="'.$action.' Call" /></form>'.'</td>';
    // print run
    echo '<td style=width: 20%; text-align: center;"><a href="printCall.php?runNum='.$RunNumber.'">Print this Call</a></td>';
    echo '<td style=width: 20%; text-align: center;"><a href="index.php">Calls Home</a></td>';
    if(isset($nextRun))
    {
	echo '<td style="text-align: right; width: 20%;"><a href="newcall.php?RunNumber='.$nextRun;
	if($edit){ echo "&action=edit";}
	echo '">'.formatRunNum($nextRun).' &rarr;</a></td>';
    }
    else
    {
	echo '<td style="width: 20%;">&nbsp;</td>';
    }
    echo '</tr>'."\n";
    echo '</table> <!-- end callNavTable -->'."\n";

    $s .= '</div> <!-- close callNav div -->'."\n";
    return $s;
}

?>