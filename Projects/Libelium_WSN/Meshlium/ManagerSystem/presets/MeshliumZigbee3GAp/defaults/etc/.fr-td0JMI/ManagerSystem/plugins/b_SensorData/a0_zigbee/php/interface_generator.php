<?php
/*
 *  Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Version 0.1
 *  Author: Octavio Benedí  
 */
function make_select($name,$options,$selected_option="",$onclick_js="")
{
    if ($onclick_js!='')
    {
        $select='<select name="'.$name.'" id="'.$name.'" onclick="'.$onclick_js.'">';
    }
    else
    {
        $select='<select name="'.$name.'" id="'.$name.'" >';
    }
    
    foreach($options as $value=>$option)
    {
        if($value==$selected_option)
        {
            $selected='selected="yes"';
        }
        else
        {
            $selected='';
        }
        $select.='<option value="'.$value.'" '.$selected.'>'.$option.'</option>';
    }
    $select.="</select>";

    return $select;
}


function make_interface($xbee = "802")
{
    $xbee = exec("cat /mnt/lib/cfg/zigbee");
    include_once $xbee.'_interface.php';
    $function =  '_'.$xbee.'_interface';
    return $function();
}
?>