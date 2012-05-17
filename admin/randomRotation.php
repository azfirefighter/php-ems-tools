<?php
// +----------------------------------------------------------------------+
// | PHP EMS Tools      http://www.php-ems-tools.com                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006, 2007 Jason Antman.                               |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 3 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// |Please use the above URL for bug reports and feature/support requests.|
// +----------------------------------------------------------------------+
// | Authors: Jason Antman <jason@jasonantman.com>                        |
// +----------------------------------------------------------------------+
//      $Id: roster.php,v 1.5 2007/09/20 00:00:40 jantman Exp $

require_once('../config/config.php'); // main configuration
require_once('../config/rosterConfig.php'); // roster configuration
require_once('../config/scheduleConfig.php'); // roster configuration

//CONNECT TO THE DB
$connection = mysql_connect() or die ("I'm sorry, but I was unable to connect! (MySQL error: unable to connect).".$errorMsg);
mysql_select_db($dbName) or die ("I'm sorry, but I was unable to select the database!".$errorMsg);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php echo '<title>'.$shortName." - Random Rotation</title>"; ?>
<link rel="stylesheet" href="../css/saturdaySchedule.css" type="text/css">
</head>

<body>

<?php

echo '<h1>'.$shortName." - Random Rotation of Members</h1>\n";

echo '<h3>Senior Members</h3>';
echo '<table class="roster">'."\n";


$query = "SELECT EMTid,FirstName,LastName FROM roster WHERE status='Senior';";
$result = mysql_query($query) or die ("I'm sorry, but there was an error in your SQL query: ".$query."<br><br>" . mysql_error().'<br><br>'.$errorMsg);

$members = array();
$order = array();
while($row = mysql_fetch_assoc($result))
{
    $members[$row['EMTid']] = $row['FirstName'].' '.$row['LastName'];
    $order[] = $row['EMTid'];
}

shuffle($order);

for($i = 0; $i < count($order); $i+=2)
{
    echo '<tr>';
    $id = $order[$i];
    echo '<td>'.$id.' - '.$members[$id].'</td>';
    if(isset($order[$i+1]))
    {
	$id = $order[$i+1];
	echo '<td>'.$id.' - '.$members[$id].'</td>';
    }
    else
    {
	echo '<td>&nbsp;</td>';
    }
    echo '</tr>';
}
echo '</table>';

$query = "SELECT EMTid,FirstName,LastName FROM roster WHERE status='Probie';";
$result = mysql_query($query) or die ("I'm sorry, but there was an error in your SQL query: ".$query."<br><br>" . mysql_error().'<br><br>'.$errorMsg);

echo '<h3>Probies</h3>';
echo '<table class="roster">'."\n";
while($row = mysql_fetch_assoc($result))
{
    echo '<tr><td>'.$row['EMTid'].' - '.$row['FirstName'].' '.$row['LastName'].'</td></tr>';
}
echo '</table>';

?>


</body>

</html>
