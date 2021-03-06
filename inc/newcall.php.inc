<?php
/*
 +--------------------------------------------------------------------------------------------------------+
 | PHP EMS Tools      http://www.php-ems-tools.com                                                        |
 +--------------------------------------------------------------------------------------------------------+
 | Time-stamp: "2010-11-30 11:44:08 jantman"                                                              |
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
 | $LastChangedRevision:: 71                                                                            $ |
 | $HeadURL:: http://svn.jasonantman.com/newcall/inc/newcall.php.inc                                    $ |
 +--------------------------------------------------------------------------------------------------------+
*/

/**
 * General, main, overall include file. Global functions and DB setup.
 *
 * @package MPAC-NewCall-PHP
 */

// DB Stuff
$dbName = "pcr";
$conn = mysql_connect() or die("Error connecting to MySQL.");
mysql_select_db($dbName) or die("Error selecting database: '".$dbName."'.");

require_once('/srv/www/htdocs/newcall/version.php');

function findAgeFromDOB($str)
{
    $dob = strtotime($str);
    $diff = time() - $dob;
    return floor($diff / 31449600);
}

function makeAddress($num, $st, $apt, $city = "", $state = "")
{
    $s = $num." ".$st;
    if(trim($apt) != "")
    {
	$s .= " #".$apt;
    }
    if($city != "" && $state != ""){ $s .= ", ".$city.", ".$state;}
    return $s;
}

function makeIntsctAddress($street, $intsct_street, $city, $state)
{
    $s = "Intsct. $street and $intsct_street";
    if($city != "" && $state != ""){ $s .= ", ".$city.", ".$state;}
    return $s;
}

function makeCallLocAddress($id)
{
    $query = "SELECT * FROM calls_locations WHERE call_loc_id=$id AND is_deprecated=0;";
    $result = mysql_query($query) or db_error($query, mysql_error());
    if(mysql_num_rows($result) < 1){ return "";}
    $row = mysql_fetch_assoc($result);
    
    if(isset($row['Intsct_Street']) && trim($row['Intsct_Street']) != "")
    {
	// intersection
	return makeIntsctAddress($row['Street'], $row['Intsct_Street'], $row['City'], $row['State']);
    }
    else
    {
	// not intersection
	return makeAddress($row['StreetNumber'], $row['Street'], $row['AptNumber'], $row['City'], $row['State']);
    }
}

function db_error($query, $error)
{
    error_log("Error in query: $query\n ERROR: $error");
    die("ERROR in query: $query Error: $error");
}

function log_edit($table, $type, $deprecates_id = 0, $deprecated_by_id = 0)
{
    // $type is {"new" | "update"}
    $query = "INSERT INTO calls_edits SET ";
    $query .= "ts=".time();
    $query .= ",table_name='".mysql_real_escape_string($table)."'";
    $query .= ",edit_type='".mysql_real_escape_string($type)."'";
    if($deprecates_id != 0){ $query .= ",deprecates_id=".((int)$deprecates_id);}
    if($deprecated_by_id != 0){ $query .= ",deprecated_by_id=".((int)$deprecated_by_id);}
    if(isset($_SERVER["REMOTE_ADDR"])){ $query .= ",remote_ip='".mysql_real_escape_string(trim($_SERVER["REMOTE_ADDR"]))."'";}
    if(isset($_SERVER["PHP_AUTH_USER"])){ $query .= ",auth_username='".mysql_real_escape_string(trim($_SERVER["PHP_AUTH_USER"]))."'";}
    if(isset($_SERVER["AUTH_TYPE"])){ $query .= ",auth_method='".mysql_real_escape_string(trim($_SERVER["AUTH_TYPE"]))."'";}
    $query .= ";";
    $result = trans_safe_query($query) or db_error($query, mysql_error());
}

function trans_start()
{
    $query = "SET autocommit=0;";
    $result = mysql_query($query) or db_error($query, mysql_error(), false);
    $query = "START TRANSACTION;";
    $result = mysql_query($query) or db_error($query, mysql_error(), false);
}

function trans_safe_query($query)
{
    $result = mysql_query($query);
    if(! $result)
    {
	$query2 = "ROLLBACK;";
	$error = mysql_error();
	$result = mysql_query($query2);
	error_log("trans_safe_query: Error in query: $query\n ERROR: $error");
	return false;
    }
    return $result;
}

function trans_commit()
{
    $query = "COMMIT;";
    $result = mysql_query($query);
    if(! $result)
    {
	$query2 = "ROLLBACK;";
	$error = mysql_error();
	$result = mysql_query($query2);
	db_error($query, $error, false);
    }
}

function trans_rollback()
{
    $query = "ROLLBACK;";
    $result = mysql_query($query);
    if(! $result)
    {
	db_error($query, mysql_error(), false);
    }
}

function var_dump_string($v)
{
    $s = "";
    ob_start();
    var_dump($v);
    $s = ob_get_clean();
    return $s;
}

?>