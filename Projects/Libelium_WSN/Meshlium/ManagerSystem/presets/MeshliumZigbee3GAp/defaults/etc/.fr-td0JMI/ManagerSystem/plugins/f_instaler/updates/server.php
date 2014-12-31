<?php
if ($_POST['type']=="installUpdate")
{
   exec ("sudo remountrw");
   $file = explode('.', $_POST['libupd']);

   exec("sudo cp /var/www/ManagerSystem/upload/* /tmp/.");
   exec("sudo mv /tmp/".$_POST['libupd']." /tmp/".$file[0].".tgz");

   exec("sudo chown www-data:www-data /tmp/".$file[0].".tgz");
   exec("cd /tmp && tar zxvf ".$file[0].".tgz");

   exec("sudo chmod 777 /tmp/".$file[0]."/update.sh");
   exec("sudo /tmp/".$file[0]."/update.sh", $output);

   exec('sudo echo "'.$file[0].'" >> /mnt/lib/.info/updatesInstaled');
   //echo 'sudo echo "'.$file[0].'" >> /mnt/lib/.info/updatesInstaled';
   echo "System updated";
   exec("rm -rf /var/www/ManagerSystem/upload/*");
   exec("remountro");

   //echo print_r($output, true);
/*
   $archivo_XML = file_get_contents ("/tmp/plugin_".$file[0]."/xml.xml");
   $xml = simplexml_load_string($archivo_XML);

   exec ("cp -r /tmp/plugin_".$file[0]."/".$xml->plugin->name." /var/www/ManagerSystem/plugins/".$xml->plugin->section);

   echo "New plugin instaled on ".$xml->plugin->section." - ".$xml->plugin->name;
*/
}

if ($_POST['type']=="checkForUpdates")
{
   exec ("sudo remountrw");
   exec("rm /tmp/updates.xm*");
   exec ("cd /tmp && wget http://www.libelium.com/downloads/managersystem/updates/updates.xml");

   if(file_exists("/tmp/updates.xml"))
   {
      $archivo_XML = file_get_contents ("/tmp/updates.xml");
      $xml = simplexml_load_string($archivo_XML);
      echo "<br><b>Current version: ".$manager_system_version."</b><br><br>";
      //echo "<pre>".print_r($xml, true)."</pre>";
      foreach ($xml->update as $update)
      {
         if($update->version > $manager_system_version)
         {
            if (($update->from == "any") && ($update->version > $manager_system_version))
            {
               exec("cat /mnt/lib/.info/updatesInstaled", $instaled);
               if (!in_array($update->file, $instaled))
               {
                  echo "<div style='-moz-border-radius: 5px;background: white; border: 1px solid #898989; padding: 1%; width: 97%; margin: 1%;'>";
                     echo "<b>".$update->name."</b><br><br>";
                     echo "<button onclick='downloadUpdate(\"".$section."\",\"".$plugin."\",\"".$update->file."\",\"".$update->link."\")'>Download</button>
                           <span class='installSMS' id='loading_".$update->file."' style='display:none;'>loading...</span>
                           <span class='installSMS' id='notinstall_".$update->file."' style='display:none;color:#DF4C44;font-weight:bold;'>Cannot load file</span>
                           <button class='installSMS' onclick='installUpdate(\"".$section."\",\"".$plugin."\",\"".$update->file.".libupd\")' id='install_".$update->file."' style='display:none;'>Install</button>";
                     echo "<div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                        exec("rm /tmp/".$update->file.".log*");
                        exec ("cd /tmp && wget http://www.libelium.com/downloads/managersystem/updates/".$update->file.".log");
                        unset($description);
                        exec ("cat /tmp/".$update->file.".log", $description);
                        foreach ($description as $line)
                        {
                           echo $line."<br>";
                        }
                     echo "</div>";
                  echo "</div>";
               }
            }
            elseif ($update->from < $manager_system_version)
            {
               echo "<div style='-moz-border-radius: 5px;background: white; border: 1px solid #898989; padding: 1%; width: 97%; margin: 1%;'>";
                  echo "<b>".$update->name."</b><br><br>";
                  echo "<button onclick='downloadUpdate(\"".$section."\",\"".$plugin."\",\"".$update->file."\",\"".$update->link."\")'>Download</button>
                        <span class='installSMS' id='loading_".$update->file."' style='display:none;'>loading...</span>
                        <span class='installSMS' id='notinstall_".$update->file."' style='display:none;color:#DF4C44;font-weight:bold;'>Cannot load file</span>
                        <button class='installSMS' onclick='installUpdate(\"".$section."\",\"".$plugin."\",\"".$update->file.".libupd\")' id='install_".$update->file."' style='display:none;'>Install</button>";
                  echo "<div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                     exec("rm /tmp/".$update->file.".log*");
                     exec ("cd /tmp && wget http://www.libelium.com/downloads/managersystem/updates/".$update->file.".log");
                     unset($description);
                     exec ("cat /tmp/".$update->file.".log", $description);
                     foreach ($description as $line)
                     {
                        echo $line."<br>";
                     }
                  echo "</div>";
               echo "</div>";
            }
            elseif ($update->from == $manager_system_version)
            {
               echo "<div style='-moz-border-radius: 5px;background: white; border: 1px solid #898989; padding: 1%; width: 97%; margin: 1%;'>";
                  echo "<b>".$update->name."</b><br><br>";
                  echo "<button onclick='downloadUpdate(\"".$section."\",\"".$plugin."\",\"".$update->file."\",\"".$update->link."\")'>Download</button>
                        <span class='installSMS' id='loading_".$update->file."' style='display:none;'>loading...</span>
                        <span class='installSMS' id='notinstall_".$update->file."' style='display:none;color:#DF4C44;font-weight:bold;'>Cannot load file</span>
                        <button class='installSMS' onclick='installUpdate(\"".$section."\",\"".$plugin."\",\"".$update->file.".libupd\")' id='install_".$update->file."' style='display:none;'>Install</button>";
                  echo "<div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                     exec("rm /tmp/".$update->file.".log*");
                     exec ("cd /tmp && wget http://www.libelium.com/downloads/managersystem/updates/".$update->file.".log");
                     unset($description);
                     exec ("cat /tmp/".$update->file.".log", $description);
                     foreach ($description as $line)
                     {
                        echo $line."<br>";
                     }
                  echo "</div>";
               echo "</div>";
            }
            elseif ($update->from > $manager_system_version)
            {
               echo "<div style=' color: #454545 !important;-moz-border-radius: 5px;background: white; border: 1px solid #898989; padding: 1%; width: 97%; margin: 1%;'>";
                  echo "<b>".$update->name.": </b> <br><br><button style='opacity: 0.5;'>Download</button> Previus updates are needed.<br>";
                  echo "<div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                     exec("rm /tmp/".$update->file.".log*");
                     exec ("cd /tmp && wget http://www.libelium.com/downloads/managersystem/updates/".$update->file.".log");
                     unset($description);
                     exec ("cat /tmp/".$update->file.".log", $description);
                     foreach ($description as $line)
                     {
                        echo $line."<br>";
                     }
                  echo "</div>";
               echo "</div>";
            }
         }
      }
   }
   else
      echo "Nothing new";
   exec("remountro");
}

if ($_POST['type']=="downloadUpdate")
{
   exec ("sudo remountrw");
   exec("sudo rm /var/www/ManagerSystem/upload/*");
   exec ("cd /var/www/ManagerSystem/upload && wget ".$_POST['link']);
   $file = explode('/', $_POST['link']);
   $uploadhtml.= "File: " . $file[sizeof($file)-1]."<br />";
   exec('echo "'.$uploadhtml.'" > /var/www/ManagerSystem/upload/log');
   echo exec('ls /var/www/ManagerSystem/upload/ | grep libupd | wc -l');
   exec("remountro");
}
?>