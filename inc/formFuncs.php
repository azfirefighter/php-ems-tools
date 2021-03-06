<?php
/*
 +--------------------------------------------------------------------------------------------------------+
 | PHP EMS Tools      http://www.php-ems-tools.com                                                        |
 +--------------------------------------------------------------------------------------------------------+
 | Time-stamp: "2011-02-08 10:47:16 jantman"                                                              |
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
 | $LastChangedRevision:: 74                                                                            $ |
 | $HeadURL:: http://svn.jasonantman.com/newcall/inc/formFuncs.php                                      $ |
 +--------------------------------------------------------------------------------------------------------+
*/

/**
 * Generic PCR form generation functions.
 *
 * @package MPAC-NewCall
 */

$consciousness_opts = array("Alert" => "Alert", "Verbal" => "Verbal", "Painful" => "Painful", "Unresponsive" => "Unresponsive");
$pupils_opts_L = array('NONE' => 'Left', 'Equal' => 'Equal', 'Dilated' => 'Dilated', 'Constricted' => 'Constricted', 'Unresponsive' => 'Unresponsive', 'Responsive/Unequal' => 'Responsive/Unequal');
$pupils_opts_R = array('NONE' => 'Right', 'Equal' => 'Equal', 'Dilated' => 'Dilated', 'Constricted' => 'Constricted', 'Unresponsive' => 'Unresponsive', 'Responsive/Unequal' => 'Responsive/Unequal');
$skinMoisture_opts = array("" => "", "Dry" => "Dry", "Moist" => "Moist");
$skinTemp_opts = array("" => "", "Warm" => "Warm", "Cool" => "Cool");
$skinColor_opts = array("" => "", "Red" => "Red", "Pale" => "Pale", "Blue" => "Blue", "Pink" => "Pink", "Jaundice" => "Jaundice");

$state_abbrevs = array("NJ" => "NJ", "AL" => "AL", "AK" => "AK", "AZ" => "AZ", "AR" => "AR", "CA" => "CA", "CO" => "CO", "CT" => "CT", "DE" => "DE", "DC" => "DC", "FL" => "FL", "GA" => "GA", "HI" => "HI", "ID" => "ID", "IL" => "IL", "IN" => "IN", "IA" => "IA", "KS" => "KS", "KY" => "KY", "LA" => "LA", "ME" => "ME", "MD" => "MD", "MA" => "MA", "MI" => "MI", "MN" => "MN", "MS" => "MS", "MO" => "MO", "MT" => "MT", "NE" => "NE", "NV" => "NV", "NH" => "NH", "NM" => "NM", "NY" => "NY", "NC" => "NC", "ND" => "ND", "OH" => "OH", "OK" => "OK", "OR" => "OR", "PA" => "PA", "PR" => "PR", "RI" => "RI", "SC" => "SC", "SD" => "SD", "TN" => "TN", "TX" => "TX", "UT" => "UT", "VT" => "VT", "VA" => "VA", "WA" => "WA", "WV" => "WV", "WI" => "WI", "WY" => "WY");

function makeVitalsRow($rowNum, $vals)
{
    global $consciousness_opts, $pupils_opts_L, $pupils_opts_R, $skinMoisture_opts, $skinTemp_opts, $skinColor_opts;

    //<tr><th>Time</th><th>BP</th><th>Pulse</th><th>Respirations</th><th>Lung Sounds</th><th>Consciousness</th><th>Pupils</th><th>Skin</th><th>SpO2</th></tr>
    $str = '<tr>'."\n";
    $str .= '<td>'.ja_text('Vitals_'.$rowNum.'_time', array("size" => 5, "maxlength" => 5), $vals).'</td>';
    $str .= '<td>'.ja_text('Vitals_'.$rowNum.'_bp', array("size" => 7, "maxlength" => 9), $vals).'</td>';
    $str .= '<td>'.ja_text('Vitals_'.$rowNum.'_pulse', array("size" => 3, "maxlength" => 6), $vals).'</td>';
    $str .= '<td>'.ja_text('Vitals_'.$rowNum.'_resp', array("size" => 10, "maxlength" => 10), $vals).'</td>';
    $str .= '<td>'.ja_text('Vitals_'.$rowNum.'_lungSounds', array("size" => 10, "maxlength" => 10), $vals).'</td>';

    $str .= '<td>';
    $str .= ja_select('Vitals_'.$rowNum.'_consciousness', array(), $consciousness_opts, $vals);
    $str .= '</td>';

    $str .= '<td>';
    $str .= ja_select('Vitals_'.$rowNum.'_pupilL', array("onChange" => "update_pupils(".$rowNum.", 0)"), $pupils_opts_L, $vals);
    $str .= ja_select('Vitals_'.$rowNum.'_pupilR', array("onChange" => "update_pupils(".$rowNum.", 1)"), $pupils_opts_R, $vals);
    $str .= '</td>';

    $str .= '<td>';
    $str .= ja_select('Vitals_'.$rowNum.'_skinMoisture', array(), $skinMoisture_opts, $vals);
    $str .= ja_select('Vitals_'.$rowNum.'_skinTemp', array(), $skinTemp_opts, $vals);
    $str .= ja_select('Vitals_'.$rowNum.'_skinColor', array(), $skinColor_opts, $vals);
    $str .= '</td>';

    $str .= '<td>'.ja_text('Vitals_'.$rowNum.'_spo2', array("size" => 3, "maxlength" => 3), $vals).'&#37;</td>';
    $str .= "\n";
    $str .= '</td>'."\n";
    return $str;
}


// SHOULD BE DEPRECATED
function genStateDiv()
{
    global $state_abbrevs;
    global $values;
    $str = '<label for="AddressState">State: </label>'."\n";
    $state_abbrevs["Non-US"] = "Non-US";
    $str .= ja_select("AddressState", array("value" => "NJ", "onChange" => "update_state()"), $state_abbrevs, $values);

    return $str;
}

function genCityDiv($state, $values, $suffix = "")
{
    if($suffix == "_CL"){ $prefix = "cl_";} else { $prefix = "pt_";}
    if($state == "NJ")
    {
	$str = '<label for="'.$prefix.'AddressCity">City: </label>';

	$foo = array();
	$foo['Midland Park'] = "Midland Park";
	$query = "SELECT DISTINCT oC_City FROM opt_Cities WHERE oC_State='NJ' AND oC_City != 'Midland Park' ORDER BY oC_City;";
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result))
	{
	    $foo[$row['oC_City']] = $row['oC_City'];
	}
	$foo['--Other'] = "--Other";
	$str .= ja_select($prefix."AddressCity", array("onChange" => "update_city$suffix()"), $foo, $values);

	$str .= ' <span id="'.$prefix.'city_other_span" style="display: none;"><input type="text" name="'.$prefix.'city_other" id="'.$prefix.'city_other" size="20" /></span>';
    }
    else
    {
	$str = '<label for="'.$prefix.'city_other">City: </label>';
	$str .= ja_text($prefix."city_other", array("size" => 30, "maxlength" => 30), $values);
    }
    return $str;
}

function processForm($values)
{
    echo "Form Processed. Or, it should have been. But this is just TODO."; // TODO
    die();
}

function gen_MAdiv($vals)
{
    $query = "SELECT oC_id,oC_state,oC_City FROM opt_Cities WHERE oC_is_MA=1;";
    $result = mysql_query($query) or die("Error in Query: ".$query."<br />ERROR: ".mysql_error()."<br />");
    $opts = array();
    while($row = mysql_fetch_assoc($result))
    {
	$opts[$row['oC_City']] = $row['oC_City'];
    }
    $opts["--Other"] = "--Other";
    $foo = array();
    $foo['onChange'] = "update_MA_town()";
    if(! isset($vals['MAcheck']) || $vals['MAcheck'] != "on")
    {
	$foo['style'] = "display: none;";
    }
    $str = ja_select("MA_town", $foo, $opts, $vals);
    $str .= '<span id="MA_town_other_span" ';
    if(isset($vals['MAcheck']) && $vals['MAcheck'] != "--Other")
    {
	$str .= 'style="display: none;"';
    }
    $str .= '>'.ja_text("MA_town_other", null, $vals).'</span>';
    return $str;
}

function genCrewTable($numRows, $values)
{
    $s = "";
    for($i = 0; $i < $numRows; $i++)
    {
	$s .= '<tr id="crew_tr_'.$i.'">';
	$s .= '<td>'.ja_text("crew_id_".$i, array("size" => 4, "maxlength" => 4, "onblur" => "javascript:update_crew_member(".$i.")"), $values).'</td>';
	$s .= '<td>'.ja_radio("crew_driver_scene", "crew_driver_scene_".$i, $i, $values).'</td>';
	$s .= '<td>'.ja_radio("crew_driver_hosp", "crew_driver_hosp_".$i, $i, $values).'</td>';
	$s .= '<td>'.ja_radio("crew_driver_bldg", "crew_driver_bldg_".$i, $i, $values).'</td>';
	$s .= '<td>'.ja_check("crew_onscene".$i, array(), $values).'</td>';
	if(isset($values["crew_genDuty_".$i]))
	{
	    $s .= '<td>'.ja_text("crew_genDuty_".$i, array("size" => 4, "readonly" => "readonly"), $values).'</td>';
	}
	else
	{
	    $s .= '<td>'.ja_text("crew_genDuty_".$i, array("size" => 4, "readonly" => "readonly", "value" => "Gen"), $values).'</td>';
	}
	$s .= "</tr>\n";
    }
    return $s;
}

function getTransToOptions()
{
    $a = array();
    $query = "SELECT * FROM opt_TransportTo ORDER BY ott_order ASC;";
    $result = mysql_query($query) or die("Error in Query: ".$query."<br />ERROR: ".mysql_error()."<br />");
    while($row = mysql_fetch_assoc($result))
    {
	$a[$row['ott_name']] = $row['ott_name'];
    }
    return $a;
}

function getMPstreets()
{
    $a = array();
    $query = "SELECT street FROM opt_streets WHERE state_abbrev='NJ' AND city='Midland Park' ORDER BY street ASC;";
    $result = mysql_query($query) or die("Error in Query: ".$query."<br />ERROR: ".mysql_error()."<br />");
    while($row = mysql_fetch_assoc($result))
    {
	$a[$row['street']] = $row['street'];
    }
    return $a;
}

?>