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
function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $currentYear = exec ("date +%Y");
    $currentMounth = exec ("date +%m");
    $currentMounthB = exec ("date +%B");
    $currentDay = exec ("date +%d");
    $currentHour = exec ("date +%H");
    $currentMin = exec ("date +%M");

    
    $date=exec("date");
	$list='

            <div class="title2"><b style="color:#E35C50;">Time synchronization</b></div>
            <div class="plugin_content">
            <div class="title2" id="current_date">'.$date.'</div><br>
                <label>Date and hour for meshlium</label>
            <form id="set_date_meshlium" class="ss">



<select id="s_year" name="s_year" style="" onChange="yearLogic()" >
    <option name="year"    value="year" style="font-weight: bold;margin-bottom: 10px; background: #454545; color: #dedede; padding: 2px;" >Year</option>
    <option name="'.$currentYear.'"    value="'.$currentYear.'" style="font-weight: bold;margin-bottom: 10px; padding: 2px;">Current: '.$currentYear.'</option>
    <option name="2010"    value="2010">2010</option>
    <option name="2011"   value="2011">2011</option>
    <option name="2012"      value="2012" class="bisiesto">2012</option>
    <option name="2013"      value="2013">2013</option>
    <option name="2014"        value="2014">2014</option>
    <option name="2015"       value="2015">2015</option>
    <option name="2016"       value="2016" class="bisiesto">2016</option>
    <option name="2017"     value="2017">2017</option>
    <option name="2018"  value="2018">2018</option>
    <option name="2019"    value="2019">2019</option>
    <option name="2020"   value="2020" class="bisiesto">2020</option>
    <option name="2021"    value="2021">2021</option>
    <option name="2022"   value="2022">2022</option>
    <option name="2023"      value="2023">2023</option>
    <option name="2024"      value="2024" class="bisiesto">2024</option>
    <option name="2025"        value="2025">2025</option>
    <option name="2026"       value="2026">2026</option>
    <option name="2027"       value="2027">2027</option>
    <option name="2028"     value="2028" class="bisiesto">2028</option>
    <option name="2029"  value="2029">2029</option>
    <option name="2030"    value="2030">2030</option>
    <option name="2031"   value="2031">2031</option>
    <option name="2032"   value="2032" class="bisiesto">2032</option>
    <option name="2033"      value="2033">2033</option>
    <option name="2034"      value="2034">2034</option>
    <option name="2035"        value="2035">2035</option>
    <option name="2036"       value="2036" class="bisiesto">2036</option>
    <option name="2037"       value="2037">2037</option>
    <option name="2038"     value="2038">2038</option>
    <option name="2039"  value="2039">2039</option>
    <option name="2040"    value="2040" class="bisiesto">2040</option>
</select>
<select id="s_mounth" name="s_mounth" style="" onChange="mounthLogic()" disabled class="disabled" >
    <option name="mounth"    value="mounth" style="font-weight: bold;margin-bottom: 10px; background: #454545; color: #dedede; padding: 2px;" >Month</option>
    <option name="'.$currentMounth.'"    value="'.$currentMounth.'" style="font-weight: bold;margin-bottom: 10px; padding: 2px;">Current: '.$currentMounthB.'</option>
    <option name="january"    value="01">January</option>
    <option name="february"   value="02" class="corto">February</option>
    <option name="march"      value="03">March</option>
    <option name="april"      value="04" class="corto">April</option>
    <option name="may"        value="05">May</option>
    <option name="june"       value="06" class="corto">June</option>
    <option name="july"       value="07">July</option>
    <option name="agoust"     value="08">Augoust</option>
    <option name="september"  value="09" class="corto">September</option>
    <option name="october"    value="10">October</option>
    <option name="november"   value="11" class="corto">November</option>
    <option name="december"   value="12">December</option>
</select>
<select id="s_day" name="s_day" style="" disabled class="disabled" onChange="dayLogic()" >
    <option name="day"    value="day" style="font-weight: bold;margin-bottom: 10px; background: #454545; color: #dedede; padding: 2px;" >Day</option>
    <option name="'.$currentDay.'"    value="'.$currentDay.'" style="font-weight: bold;margin-bottom: 10px; padding: 2px;">Current: '.$currentDay.'</option>
    <option name="01" value="01">01</option>
    <option name="02" value="02">02</option>
    <option name="03" value="03">03</option>
    <option name="04" value="04">04</option>
    <option name="05" value="05">05</option>
    <option name="06" value="06">06</option>
    <option name="07" value="07">07</option>
    <option name="08" value="08">08</option>
    <option name="09" value="09">09</option>
    <option name="10" value="10">10</option>
    <option name="11" value="11">11</option>
    <option name="12" value="12">12</option>
    <option name="13" value="13">13</option>
    <option name="14" value="14">14</option>
    <option name="15" value="15">15</option>
    <option name="16" value="16">16</option>
    <option name="17" value="17">17</option>
    <option name="18" value="18">18</option>
    <option name="19" value="19">19</option>
    <option name="20" value="20">20</option>
    <option name="21" value="21">21</option>
    <option name="22" value="22">22</option>
    <option name="23" value="23">23</option>
    <option name="24" value="24">24</option>
    <option name="25" value="25">25</option>
    <option name="26" value="26">26</option>
    <option name="27" value="27">27</option>
    <option name="28" value="28">28</option>
    <option name="29" value="29" class="febrero bisiesto">29</option>
    <option name="30" value="30" class="febrero">30</option>
    <option name="31" value="31" class="febrero largo">31</option>
</select>
<select id="s_hour" name="s_hour" style="" disabled class="disabled"  onChange="hourLogic()">
    <option name="hour"    value="hour" style="font-weight: bold;margin-bottom: 10px; background: #454545; color: #dedede; padding: 2px;" >Hour</option>
    <option name="'.$currentHour.'"    value="'.$currentHour.'" style="font-weight: bold;margin-bottom: 10px; padding: 2px;">Current: '.$currentHour.'</option>
    <option name="00" value="00">00</option>
    <option name="01" value="01">01</option>
    <option name="02" value="02">02</option>
    <option name="03" value="03">03</option>
    <option name="04" value="04">04</option>
    <option name="05" value="05">05</option>
    <option name="06" value="06">06</option>
    <option name="07" value="07">07</option>
    <option name="08" value="08">08</option>
    <option name="09" value="09">09</option>
    <option name="10" value="10">10</option>
    <option name="11" value="11">11</option>
    <option name="12" value="12">12</option>
    <option name="13" value="13">13</option>
    <option name="14" value="14">14</option>
    <option name="15" value="15">15</option>
    <option name="16" value="16">16</option>
    <option name="17" value="17">17</option>
    <option name="18" value="18">18</option>
    <option name="19" value="19">19</option>
    <option name="20" value="20">20</option>
    <option name="21" value="21">21</option>
    <option name="22" value="22">22</option>
    <option name="23" value="23">23</option>
</select>
<select id="s_minute" name="s_minute" style="" disabled class="disabled">
    <option name="minute"    value="minute" style="font-weight: bold;margin-bottom: 10px; background: #454545; color: #dedede; padding: 2px;" >Minute</option>
    <option name="'.$currentMin.'"    value="'.$currentMin.'" style="font-weight: bold;margin-bottom: 10px; padding: 2px;">Current: '.$currentMin.'</option>
    <option name="00" value="00">00</option>
    <option name="01" value="01">01</option>
    <option name="02" value="02">02</option>
    <option name="03" value="03">03</option>
    <option name="04" value="04">04</option>
    <option name="05" value="05">05</option>
    <option name="06" value="06">06</option>
    <option name="07" value="07">07</option>
    <option name="08" value="08">08</option>
    <option name="09" value="09">09</option>
    <option name="10" value="10">10</option>
    <option name="11" value="11">11</option>
    <option name="12" value="12">12</option>
    <option name="13" value="13">13</option>
    <option name="14" value="14">14</option>
    <option name="15" value="15">15</option>
    <option name="16" value="16">16</option>
    <option name="17" value="17">17</option>
    <option name="18" value="18">18</option>
    <option name="19" value="19">19</option>
    <option name="20" value="20">20</option>
    <option name="21" value="21">21</option>
    <option name="22" value="22">22</option>
    <option name="23" value="23">23</option>
    <option name="24" value="24">24</option>
    <option name="25" value="25">25</option>
    <option name="26" value="26">26</option>
    <option name="27" value="27">27</option>
    <option name="28" value="28">28</option>
    <option name="29" value="29">29</option>
    <option name="30" value="30">30</option>
    <option name="31" value="31">31</option>
    <option name="32" value="32">32</option>
    <option name="33" value="33">33</option>
    <option name="34" value="34">34</option>
    <option name="35" value="35">35</option>
    <option name="36" value="36">36</option>
    <option name="37" value="37">37</option>
    <option name="38" value="38">38</option>
    <option name="39" value="39">39</option>
    <option name="40" value="40">40</option>
    <option name="41" value="41">41</option>
    <option name="42" value="42">42</option>
    <option name="43" value="43">43</option>
    <option name="44" value="44">44</option>
    <option name="45" value="45">45</option>
    <option name="46" value="46">46</option>
    <option name="47" value="47">47</option>
    <option name="48" value="48">48</option>
    <option name="49" value="49">49</option>
    <option name="50" value="50">50</option>
    <option name="51" value="51">51</option>
    <option name="52" value="52">52</option>
    <option name="53" value="53">53</option>
    <option name="54" value="54">54</option>
    <option name="55" value="55">55</option>
    <option name="56" value="56">56</option>
    <option name="57" value="57">57</option>
    <option name="58" value="58">58</option>
    <option name="59" value="59">59</option>
</select>
<input type="button" value="Ok" onclick="complex_ajax_call(\'set_date_meshlium\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')"/>

            </form>


   ';
    return $list;
}
?>