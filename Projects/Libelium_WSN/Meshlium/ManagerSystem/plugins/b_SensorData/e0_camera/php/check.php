<?
// Desactivar toda notificación de error
  error_reporting(0);
  
  function generate_background_percent($percent){
    if ($percent < '12')
        $background = '#08E308 ';

    if (('12' <= $percent)  && ($percent < '25'))
        $background = '#B0FF04 ';

    if (('25' <= $percent)  && ($percent < '37'))
        $background = '#DEFF00  ';

    if (('37' <= $percent)  && ($percent < '50'))
        $background = '#FBEB00  ';

    if (('50' <= $percent)  && ($percent < '62'))
        $background = '#FFC600 ';

    if (('62' <= $percent)  && ($percent < '84'))
        $background = '#FF9C00 ';

    if (('84' <= $percent)  && ($percent < '96'))
        $background = '#FF5200  ';

    if ($percent >= '96')
        $background = '#FF0000 ';
    return $background;
  }

  function getPercentBar($color, $value)
  {
    $_ocupado = explode('%', $value);
    return "
    <div class='percent_bar' style='position: relative;-moz-border-radius: 4px; '>
    <div class='percent_progres small_font' style='-moz-border-radius: 4px; border: 1px solid #343434; border-top: 0px; border-left: 0px; position: absolute; z-index:9; background: ".$color.";  width:".$_ocupado['0'].";'>
    </div>
    <div style='color:#343434; z-index: 15;position:absolute;margin-left: 5px;margin-top: 2px;'>".round($_ocupado['0'], 2)." %</div>
    <div class='glass_bar' style='-moz-border-radius: 4px; left:".$_ocupado['0']."px; position: absolute;margin-left:-".$_ocupado['0']."; z-index:10;width: 100%;' ></div>
    </div> ";
  }

  if ($_POST['do'] == 'fotos'){
    exec("find /mnt/user/camera/ -type f | grep 'jpg' | wc -l",$n_fotos);
    $html.='<b>'.$n_fotos[0].'</b>';
    echo $html;
  }

  if ($_POST['do'] == 'usage'){
    exec("df -hTP | grep '/dev/hda3' | awk '{print $1}'",$disk_usage_1);
    exec("df -hTP | grep '/dev/hda3' | awk '{print $2}'",$disk_usage_2);
    exec("df -hTP | grep '/dev/hda3' | awk '{print $3}'",$disk_usage_3);
    exec("df -hTP | grep '/dev/hda3' | awk '{print $4}'",$disk_usage_4);
    exec("df -hTP | grep '/dev/hda3' | awk '{print $5}'",$disk_usage_5);
    exec("df -hTP | grep '/dev/hda3' | awk '{print $6}'",$disk_usage_6);
    exec("df -hTP | grep '/dev/hda3' | awk '{print $7}'",$disk_usage_7);
    
    $numero = ereg_replace("[%]","",$disk_usage_6[0]);
    $background_color = generate_background_percent($numero);
    $html.='
    <thead>
      <tr>
        <td></td>
        <td style="padding-right: 10px;"><b>Size</b></td>
        <td style="padding-right: 10px;"><b>Used</b></td>
        <td style="padding-right: 10px;"><b>Avail</b></td>
        <td style="padding-right: 10px;"><b>Used%</b></td>
      </tr>
    </thead>
    <tbody>
    <tr>
      <td><span style="padding-right:15px;font-style:italic;">Path: '.$disk_usage_7[0].'/camera/</span></td>
      <td>'.$disk_usage_3[0]."</td>
      <td>".$disk_usage_4[0]."</td>
      <td>".$disk_usage_5[0]."</td>      
      <td>".getPercentBar($background_color, $disk_usage_6[0])."</td>
    </tr>
    </tbody>";

    echo $html;
  }
   

  if ($_POST['do'] == 'videos'){
    exec("find /mnt/user/camera/ -type f | grep 'mp4' | wc -l",$n_videos);
    
    $html.='<b>'.$n_videos[0].'</b>';
    echo $html;
  }
  
  if ($_POST['do'] == 'generate_image_video'){
    // $ffmpeg = '/usr/bin/ffmpeg/ffmpeg'; //to load the extension ffmpeg

    // $video = '/mnt/user/camera/Waspmote_02_11-111-1989.mp4'; //path to the video

    // $image = '/mnt/user/camera/thunmbnail'; //path to store the thumbnail

    // $interval = 5;
    // $size = '640x480';
    // exec("$ffmpeg -itsoffset -105 -i $video -vcodec mjpeg -vframes 1 -an -f rawvideo -s $size $image");
  }
 
  if ($_POST['do'] == 'foto_files'){
    exec("ls -lt /mnt/user/camera/ | awk '{print $9}'",$foto_files);
    echo json_encode($foto_files);
  }

   if ($_POST['do'] == 'video_files'){
     exec("ls -lt /mnt/user/camera/ | awk '{print $9}'",$video_files);
     echo json_encode($video_files);
   }
  

  
?>