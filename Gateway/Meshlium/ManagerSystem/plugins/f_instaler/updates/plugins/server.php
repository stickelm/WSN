<?php
if ($_POST['type']=="installPlugin")
{
   exec ("sudo remountrw");
   $file = explode('.', $_POST['libplg']);

   exec("sudo cp /var/www/ManagerSystem/upload/* /tmp/.");
   exec("sudo mv /tmp/".$_POST['libplg']." /tmp/".$file[0].".tgz");

   exec("sudo chown www-data:www-data /tmp/".$file[0].".tgz");
   exec("cd /tmp && tar zxvf ".$file[0].".tgz");

   $archivo_XML = file_get_contents ("/tmp/plugin_".$file[0]."/xml.xml");
   $xml = simplexml_load_string($archivo_XML);

   exec ("cp -r /tmp/plugin_".$file[0]."/".$xml->plugin->name." /var/www/ManagerSystem/plugins/".$xml->plugin->section);

   echo "New plugin instaled on ".$xml->plugin->section." - ".$xml->plugin->name;
   exec("rm -rf /var/www/ManagerSystem/upload/*");
   exec("remountro");
}


if ($_POST['type']=="installSection")
{
   exec ("sudo remountrw");
   $urlSeccionada = explode("/", $_POST[link]);
   $nameFile = $urlSeccionada[sizeof($urlSeccionada)-1];
   $file = explode('.', $nameFile);

   exec("sudo cp /var/www/ManagerSystem/upload/* /tmp/.");
   exec("sudo mv /tmp/".$nameFile." /tmp/".$file[0].".tgz");

   exec("sudo chown www-data:www-data /tmp/".$file[0].".tgz");
   exec("cd /tmp && tar zxvf ".$file[0].".tgz");

   $archivo_XML = file_get_contents ("/tmp/section_".$file[0]."/xml.xml");
   $xml = simplexml_load_string($archivo_XML);

   exec ("cp -r /tmp/section_".$file[0]."/".$xml->plugin->section." /var/www/ManagerSystem/plugins/");
   echo "/tmp/section_".$file[0]."/".$xml->plugin->section;

   echo "New section instaled";
   exec("rm -rf /var/www/ManagerSystem/upload/*");
   exec("remountro");
}


if ($_POST['type']=="checkForPlugins")
{
   exec ("sudo remountrw");
   exec("rm /tmp/plugins.xm*");
   exec ("cd /tmp && wget http://www.libelium.com/downloads/managersystem/plugins/plugins.xml");

   $total_new_versions = 0;
   $total_new_plugins = 0;
   $total_new_sections = 0;

   if(file_exists("/tmp/plugins.xml"))
   {
      $archivo_XML = file_get_contents ("/tmp/plugins.xml");
      $xml = simplexml_load_string($archivo_XML);
      foreach ($xml->section as $_section)
      {
         if (file_exists("/var/www/ManagerSystem/plugins/".$_section->name))
         {
            foreach ($_section->plugins->plugin as $_plugins)
            {
                if($_plugins->require <= $manager_system_version)
                {
                     if (file_exists("/var/www/ManagerSystem/plugins/".$_section->name."/".$_plugins->name))
                     {
                           $realVersion = exec("cat /var/www/ManagerSystem/plugins/".$_section->name."/".$_plugins->name."/version");
                           if ($realVersion != $_plugins->version)
                           {
                              $newVersions .= "
                              <div style='background: white; margin: 10px; padding: 5px; border: 1px solid #898989;-moz-border-radius: 5px;'>
                                 <b>".str_replace(":", " → ", $_plugins->metaname)."</b> (Up ".$realVersion." to ".$_plugins->version.")<br><br>
                                 <button onclick='downloadUpdate(\"".$section."\",\"".$plugin."\",\"".$_plugins->name."\",\"".$_plugins->link."\")'>Download</button>
                                 <span class='installSMS' id='loading_".$_plugins->name."' style='display:none;'>loading...</span>
                                 <span class='installSMS' id='notinstall_".$_plugins->name."' style='display:none;color:#DF4C44;font-weight:bold;'>Cannot load file</span>
                                 <button class='installSMS' onclick='installPlugin(\"".$section."\",\"".$plugin."\",\"".$_plugins->nameLibplg."\")' id='install_".$_plugins->name."' style='display:none;'>Install</button>
                                 <br>
                                    <div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                                          $newVersions .=  "<b>".$_plugins->description."</b><hr>";
                                          $newVersions .=  $_plugins->changes;
                                       $newVersions .=  "
                                    </div>
                              </div>";
                              $total_new_versions++;
                           }
                     }
                     else
                     {
                        $newPluings .= "
                              <div style='background: white; margin: 10px; padding: 5px; border: 1px solid #898989;-moz-border-radius: 5px;'>
                                 <b>".str_replace(":", " → ", $_plugins->metaname)."</b> (Up ".$realVersion." to ".$_plugins->version.") <br><br>
                                 <button onclick='downloadUpdate(\"".$section."\",\"".$plugin."\",\"".$_plugins->name."\",\"".$_plugins->link."\")'>Download</button>
                                 <span class='installSMS' id='loading_".$_plugins->name."' style='display:none;'>loading...</span>
                                 <span class='installSMS' id='notinstall_".$_plugins->name."' style='display:none;color:#DF4C44;font-weight:bold;'>Cannot load file</span>
                                 <button class='installSMS' onclick='installPlugin(\"".$section."\",\"".$plugin."\",\"".$_plugins->nameLibplg."\")' id='install_".$_plugins->name."' style='display:none;'>Install</button>
                                 <br>
                                    <div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                                          $newPluings .=  "<b>".$_plugins->description."</b><hr>";
                                          $newPluings .=  $_plugins->changes;
                                       $newPluings .=  "
                                    </div>
                              </div>";
                           $total_new_plugins++;
                     }
                }
            }
         }
         else
         {
                $newSections .= "
                  <div style='background: white; margin: 10px; padding: 5px; border: 1px solid #898989;-moz-border-radius: 5px;'>
                     <b>New section avaliable → ".$_section->metaname." </b><br><br>
                     <button onclick='downloadUpdate(\"".$section."\",\"".$plugin."\",\"".$_section->name."\",\"".$_section->link."\")'>Download</button>
                     <span class='installSMS' id='loading_".$_section->name."' style='display:none;'>loading...</span>
                     <span class='installSMS' id='notinstall_".$_plugins->name."' style='display:none;color:#DF4C44;font-weight:bold;'>Cannot load file</span>
                     <button class='installSMS' onclick='installSection(\"".$section."\",\"".$plugin."\",\"".$_section->link."\")'  id='install_".$_section->name."' style='display:none;'>Install</button>
                     <br>
                        <div style='background: #dedede; border: 1px solid #898989; padding: 1%; width: 50%; margin: 1% 1% 0 0;'>";
                              $newSections .=  "<b>It has the following plugins</b><hr>";
                              foreach ($_section->plugins->plugin as $_newsectionplugins)
                              {
                                 $newSections .=  $_newsectionplugins->name."<br>";
                              }
                           $newSections .=  "
                        </div>
                  </div>";
                $total_new_sections++;
         }
      }
         echo "<div style='width: 97%; padding: 4px 1%; background: #676767; -moz-border-radius: 5px; border: 1px solid #898989; margin: 8px 0; '>
                  <div style='width: 33%; float: left; text-align: center;'><button id='b_newSections' onclick='$(\"#b_newVersions\").removeClass(\"selected\");$(\"#b_newPluings\").removeClass(\"selected\");$(this).addClass(\"selected\");$(\"#showAvaliables\").html($(\"#newSections\").html())' style='width:98%;'>New Sections (".$total_new_sections.")</button></div>
                  <div style='width: 34%; float: left; text-align: center;'><button id='b_newPluings' onclick='$(\"#b_newVersions\").removeClass(\"selected\");$(\"#b_newSections\").removeClass(\"selected\");$(this).addClass(\"selected\");$(\"#showAvaliables\").html($(\"#newPluings\").html())' style='width:98%;'>New Plugins (".$total_new_plugins.")</button></div>
                  <div style='width: 33%; float: left; text-align: center;'><button id='b_newVersions' onclick='$(\"#b_newPluings\").removeClass(\"selected\");$(\"#b_newSections\").removeClass(\"selected\");$(this).addClass(\"selected\");$(\"#showAvaliables\").html($(\"#newVersions\").html())' style='width:98%;'>Update Plugins (".$total_new_versions.")</button></div>
                  <div style='clear: both;'></div>
               </div>
               <div id='showAvaliables'></div>";

         echo "<div id='newSections' style='display: none;'>".$newSections."</div>";
         echo "<div id='newPluings'  style='display: none;'>".$newPluings." </div>";
         echo "<div id='newVersions' style='display: none;'>".$newVersions."</div>";

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
   //echo "cd /var/www/ManagerSystem/upload && wget ".$_POST['link'];
   
   $file = explode('/', $_POST['link']);

   $uploadhtml.= "File: " . $file[sizeof($file)-1]."<br />";
   exec('echo "'.$uploadhtml.'" > /var/www/ManagerSystem/upload/log');
   echo exec('ls /var/www/ManagerSystem/upload/ | grep libplg | wc -l');
   exec("remountro");
}

?>
