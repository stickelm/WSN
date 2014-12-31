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
 *  Version 2.0
 *  Author: Alvaro Gonzalez
 */

function make_interface()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    

    $list.='<div class="title2">OTA FTP</div>';
    $list.='<div id="plugin_content">
              <form id="uploadform" method="post" enctype="multipart/form-data" action="/ManagerSystem/plugins/b_SensorData/f0_otaftp/php/upload_file.php">
              <label" id="su_name" >Select option:</b></label>
              <br><br>
              <div style="margin-left:20px">
              <input type="checkbox" name="checkbox1" id="no_file_check" /> <span>NO_FILE</span><br><br>
              <input type="checkbox" name="checkbox2" id="file_check"  /> <input name="file" id="file" size="27" type="file" /><br><br>  
              <input type="checkbox" name="checkbox3" id="file_exist_check" /> <span>Existing file</span><br><br> 
              ';

            exec("ls -l /mnt/user/ota/ | awk '{print $9}'",$list_binary);
            exec("ls -la /mnt/user/ota/ | awk '{print $5}'",$list_size);
            exec("ls -l /mnt/user/ota/ | wc -l", $n_files);
    $list.='
            <div id="list_abinary"style="width:317px; padding-left:100px; display:none;" >
              <div style="padding: 15px; overflow: auto; height: 111px; background-color: white; border: 1px solid #AAAAAA;">
                <ul style="padding-left:10px;">';
            for ($i = 1; $i < $n_files[0]; $i++) {
              if (($list_binary[$i] != 'UPGRADE.TXT') && ($list_binary[$i] != 'UPGRADE.TXT.new')){

    $list.='      <li style="cursor: pointer; text-decoration: underline" class="binary_file" id="size_'.$list_size[$i].'">'.$list_binary[$i].'</li>';
              };
            };
                
     $list.='   </ul>
              </div>
              <div ><span>Path binary files: <b>/mnt/user/ota </b></span></div>
              <br><br> 
              </div>               
              <span id="version" style="display:none">Version: <input style="width: 50px;"type="text" name="version"></span>
            </div>
            <br>
            <input type="submit" name="action" value="Generate UPGRADE.TXT" />           
           
          </form>
           <div style="padding-left: 28%; width:317px;">
              <div style="padding-top:20px;"><span><b>UPGRADE.TXT</b></span></div>    
            ';
            
      


    exec("more /mnt/user/ota/UPGRADE.TXT | awk '/FILE:/{print $1}' | awk '{ print substr($0,6,20)}'",$name_up);
    exec("more /mnt/user/ota/UPGRADE.TXT | awk '/PATH:/{print $1}' | awk '{ print substr($0,6,20)}'",$path_up);
    exec("more /mnt/user/ota/UPGRADE.TXT | awk '/SIZE:/{print $1}' | awk '{ print substr($0,6,20)}'",$size_up);
    exec("more /mnt/user/ota/UPGRADE.TXT | awk '/VERSION:/{print $1}' | awk '{ print substr($0,9,20)}'",$version_up);
    $list.='     
                  
              <div id="show_file" style="border: 1px solid #AAAAAA; width:285px; margin:auto; padding:15px">
                <span><font face="Courier New" size="2">FILE:<b><span id="binary_file_name">'.$name_up[0].'</span></b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">PATH:<b>'.$path_up[0].'</b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">SIZE:<b><span id="binary_file_size">'.$size_up[0].'</span></b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">VERSION:<b>'.$version_up[0].'</b></font></span>
              </div>
             
            </div>
            <br><br>
            <div id="a"></div> 
             <span id="status" style="display:none">Uploading...</span>
              <iframe id="target_iframe" name="target_iframe" src="" style="width:0;height:0;border:0px"></iframe>
            <script type="text/javascript">
              $(".binary_file").click(function() { 
                $("#binary_file_name").html($(this).html()); 
                $("#binary_file_size").html($(this).attr("id").match(/[\d]+$/)[0]); 

                submit_data="do=change&name="+$(this).html()+"&size="+$(this).attr("id").match(/[\d]+$/)[0];
                $.ajax({
                  type: "POST",
                  url: "/ManagerSystem/plugins/b_SensorData/f0_otaftp/php/check.php",
                  data: submit_data,
                  success: function(data){ 
                    $(\'#show_file\').html(data);

                  }
              
                });
              });
            </script>

            </div>';

    return $list;
}


?>
