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
 *  Author: Octavio Benedí  Joaquin Ruiz
 */
$html='

<script type="text/javascript">

function populateDays(kind) {
    if (kind==0)
    {
	$("#ip_address").val("ipv6.google.com");
    }
    else
    {
	$("#ip_address").val("");
    }
}
</script>



<div class="title2">Ping</div>
       <div class="plugin_content">
        <form name="ping_test" id="ping_test" onsubmit="return false;" >
            <table><tbody><tr><td>
                <label>Select interface</label>
            </td><td>
                <select id="interface" name="interface"; onchange="populateDays(this.selectedIndex-1)">
                    <option value="eth0">Ethernet (IPv4)</option>
		    <option value="eth06">Ethernet (IPv6)</option>
                    <option value="ath0">Wifi AP</option>
                    <option value="ath1">Wifi Mesh</option>
                    <option value="ppp0">3G/GPRS</option>
                </select>
            </td></tr><tr><td>
                <label>Destination Host</label>
            </td><td>
                <input type="text" id="ip_address" name="ip_address" class="ms_host ms_mandatory" />
            </td><td>
                <input type="button" class="bsave" value="Do test" onclick="complex_ajax_call(\'ping_test\',\'ping_test_output\',\''.$section.'\',\''.$plugin.'\')" />
            </td></tr>
            <tr>
                <td></td>
                <td colspan="2">
                </td>
            </tr>
            </tbody></table>
        </form>
        <div id="ping_test_output"></div>
        </div>';
?>