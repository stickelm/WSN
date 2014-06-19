<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function loadData($ifaz)
{
    global $base_plugin;

    if(trim($ifaz) == 'ath0')
        exec("cat ".$base_plugin."data/networkingOptions_wifi0", $netOpts);
    else
        exec("cat ".$base_plugin."data/networkingOptions_wifi1", $netOpts);

    foreach ($netOpts as $numLine => $line)
    {
        if(strstr($line, " Mode") != false)
        {
            $returnData[$ifaz]['mode'] = explode(" Mode ", $line);
            $returnData[$ifaz]['mode'] = ucfirst(strtolower($returnData[$ifaz]['mode'][1]));
        }

        if(strstr($line, "provided by user: ") != false)
        {
            $returnData[$ifaz]['distance'] = explode("provided by user: ", $line);
            $returnData[$ifaz]['distance'] = $returnData[$ifaz]['distance'][1];
        }

        if(strstr($line, "acktimeout") != false)
        {
            $returnData[$ifaz]['acktimeout'] = explode("=", $line);
            $returnData[$ifaz]['acktimeout'] = $returnData[$ifaz]['acktimeout'][1];
        }

        if(strstr($line, "ctstimeout") != false)
        {
            $returnData[$ifaz]['ctstimeout'] = explode("=", $line);
            $returnData[$ifaz]['ctstimeout'] = $returnData[$ifaz]['ctstimeout'][1];
        }

        if(strstr($line, "slottime") != false)
        {
            $returnData[$ifaz]['slottime'] = explode("=", $line);
            $returnData[$ifaz]['slottime'] = $returnData[$ifaz]['slottime'][1];
        }

    }

    return $returnData;
}
?>
