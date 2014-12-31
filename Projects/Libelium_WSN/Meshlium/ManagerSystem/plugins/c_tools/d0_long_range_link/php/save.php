<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function save($data, $ifaz)
{

    global $section;
    global $plugin;
    global $base_plugin;

    if($ifaz=='ath0')
    {
        $writepath=$base_plugin.'data/networkingOptions_wifi0';
        exec("sudo remountrw");
        $fp=fopen($writepath,'w');

        if($data['input_method']=='Auto')
        {
            fwrite($fp,"#!/bin/bash\n\n");
            fwrite($fp,"#Ath0 Mode AUTO\n");
            fwrite($fp,"#Ath0 distance provided by user: ".$data['distance_value']."\n");

            exec("sudo sysctl dev.wifi0.acktimeout | cut -d' ' -f3", $auxValue);
            $acktimeout = $auxValue['0'];
            $newACK = ($acktimeout+(($data['distance_value']/333000)*100000*2));
            unset($auxValue);
            exec("sudo sysctl dev.wifi0.ctstimeout | cut -d' ' -f3", $auxValue);
            $ctstimeout = $auxValue['0'];
            
            fwrite($fp,'sysctl -w dev.wifi0.acktimeout='.intval($newACK)."\n");
            fwrite($fp,'sysctl -w dev.wifi0.ctstimeout='.$ctstimeout."\n");
            fwrite($fp,'sysctl -w dev.wifi0.slottime='.intval($newACK)."\n\n");
        }
        else
        {
            fwrite($fp,"#!/bin/bash\n\n");
            fwrite($fp,"#Ath0 Mode MANUAL\n");
            fwrite($fp,"#Ath0 manual values provided by user.\n");

            fwrite($fp,'sysctl -w dev.wifi0.acktimeout='.$data['acktimeout']."\n");
            fwrite($fp,'sysctl -w dev.wifi0.ctstimeout='.$data['ctstimeout']."\n");
            fwrite($fp,'sysctl -w dev.wifi0.slottime='.$data['slottime']."\n\n");
        }
        
        fclose($fp);
    }

    if($ifaz=='ath1')
    {
        $writepath=$base_plugin.'data/networkingOptions_wifi1';
        exec("sudo remountrw");
        $fp=fopen($writepath,'w');

        if($data['input_method']=='Auto')
        {
            fwrite($fp,"#Ath1 Mode AUTO\n");
            fwrite($fp,"#Ath1 distance provided by user: ".$data['distance_value']."\n");

            exec("sudo sysctl dev.wifi1.acktimeout | cut -d' ' -f3", $auxValue);
            $acktimeout = $auxValue['0'];
            $newACK = ($acktimeout+(($data['distance_value']/333000)*100000*2));
            unset($auxValue);
            exec("sudo sysctl dev.wifi1.ctstimeout | cut -d' ' -f3", $auxValue);
            $ctstimeout = $auxValue['0'];

            fwrite($fp,'sysctl -w dev.wifi1.acktimeout='.intval($newACK)."\n");
            fwrite($fp,'sysctl -w dev.wifi1.ctstimeout='.$ctstimeout."\n");
            fwrite($fp,'sysctl -w dev.wifi1.slottime='.intval($newACK)."\n\n");
        }
        else
        {
            fwrite($fp,"#Ath1 Mode MANUAL\n");
            fwrite($fp,"#Ath1 manual values provided by user.\n");

            fwrite($fp,'sysctl -w dev.wifi1.acktimeout='.$data['acktimeout']."\n");
            fwrite($fp,'sysctl -w dev.wifi1.ctstimeout='.$data['ctstimeout']."\n");
            fwrite($fp,'sysctl -w dev.wifi1.slottime='.$data['slottime']."\n\n");
        }

        fclose($fp);
    }

    
    exec("sudo remountro");
}

function saveDefaultsValues($ifaz)
{
    global $base_plugin;
    if($ifaz=='ath0')
    {
        $writepath=$base_plugin.'data/networkingOptions_wifi0';
        exec("sudo remountrw");
        $fp=fopen($writepath,'w');
            fwrite($fp,"#!/bin/bash\n\n");
        fclose($fp);
    }
    else
    {
        exec('sudo echo "" > '.$base_plugin.'data/networkingOptions_wifi1');
    }

}

function makeDaemon()
{
    global $base_plugin;

    exec("sudo remountrw");

    exec("sudo cat ".$base_plugin.'data/networkingOptions_wifi0 > '.$base_plugin.'data/networkingOptions.sh');
    exec("sudo cat ".$base_plugin.'data/networkingOptions_wifi1 >> '.$base_plugin.'data/networkingOptions.sh');
    exec('sudo cp '.$base_plugin.'data/networkingOptions.sh /etc/init.d/networkingOptions.sh');

    exec("sudo remountro");
}
?>
