<?php

  $file_id='file';
  $status='';
  $check_1 = $_POST["checkbox1"];
  $check_2 = $_POST["checkbox2"];
  $check_3 = $_POST["checkbox3"];
  $version = $_POST["version"];

  if ($check_1 == 'on'){
  	$status="<div id=\"state\" class=\"connected\" style=\"width:292px; padding:10px;margin-left:28%\">File generated successfully</div>";
  }
  if ($check_2 == 'on'){
  	$filename=$_FILES[$file_id]['name'];
	  $tmpfile=$_FILES[$file_id]['tmp_name'];
	  $tmpsize=$_FILES[$file_id]['size'];
	  if(!$_FILES[$file_id]['name']) {
    	echo returnStatus("<font color=\'red\'>no file specified</font>");
    	return;
  	}
 
	  /*copy file over to tmp directory */
		if(move_uploaded_file($tmpfile, "/mnt/user/ota/".$filename)){
			if(empty($version)){
			$status="<div id=\"state\" class=\"disconnected\" style=\"width:292px; padding:10px;margin-left:28%\">Empty Version</div>";
		}else{
		  if ($version < 256){
				$status="<div id=\"state\" class=\"connected\" style=\"width:292px; padding:10px;margin-left:28%\">File generated successfully</div>";
				$instruccion_1 = "echo 'FILE:".$filename."' >> /mnt/user/ota/UPGRADE.TXT.new";
				$instruccion_2 = "echo 'PATH:/' >> /mnt/user/ota/UPGRADE.TXT.new";
				$instruccion_3 = "echo 'SIZE:".$tmpsize."' >> /mnt/user/ota/UPGRADE.TXT.new";
				$instruccion_4 = "echo 'VERSION:".$version."' >> /mnt/user/ota/UPGRADE.TXT.new";
				exec($instruccion_1);
				exec($instruccion_2);
				exec($instruccion_3);
				exec($instruccion_4);
				exec("mv /mnt/user/ota/UPGRADE.TXT.new /mnt/user/ota/UPGRADE.TXT");
			}else{
				$status="<div id=\"state\" class=\"disconnected\" style=\"width:292px; padding:10px;margin-left:28%\">Version overload</div>";		
			}
			 }
		}else{
		  $status='<font color=\'red\'>Failed</font>';
		}
	}

	if ($check_3 == 'on'){
		if(empty($version)){
			$status="<div id=\"state\" class=\"disconnected\" style=\"width:292px; padding:10px;margin-left:28%\">Empty Version</div>";
		}else{
			if ($version < 256){
				exec("more /mnt/user/ota/UPGRADE.TXT | awk '/FILE:/{print $1}' | awk '{ print substr($0,6,20)}'",$name_up);
				exec("more /mnt/user/ota/UPGRADE.TXT | awk '/SIZE:/{print $1}' | awk '{ print substr($0,6,20)}'",$size_up);
				$instruccion_1 = "echo 'FILE:".$name_up[0]."' >> /mnt/user/ota/UPGRADE.TXT.new";
				$instruccion_2 = "echo 'PATH:/' >> /mnt/user/ota/UPGRADE.TXT.new";
				$instruccion_3 = "echo 'SIZE:".$size_up[0]."' >> /mnt/user/ota/UPGRADE.TXT.new";
				$instruccion_4 = "echo 'VERSION:".$version."' >> /mnt/user/ota/UPGRADE.TXT.new";
				exec($instruccion_1);
				exec($instruccion_2);
				exec($instruccion_3);
				exec($instruccion_4);
				exec("mv /mnt/user/ota/UPGRADE.TXT.new /mnt/user/ota/UPGRADE.TXT");
				$status="<div id=\"state\" class=\"connected\" style=\"width:292px; padding:10px;margin-left:28%\">File generated successfully</div>";
				
			}else{
				$status="<div id=\"state\" class=\"disconnected\" style=\"width:292px; padding:10px;margin-left:28%\">Version overload</div>";		
			}
		}
  	
  }

  echo returnStatus($status);

function returnStatus($status){
	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/FILE:/{print $1}' | awk '{ print substr($0,6,20)}'",$name_up);
  exec("more /mnt/user/ota/UPGRADE.TXT | awk '/PATH:/{print $1}' | awk '{ print substr($0,6,20)}'",$path_up);
  exec("more /mnt/user/ota/UPGRADE.TXT | awk '/SIZE:/{print $1}' | awk '{ print substr($0,6,20)}'",$size_up);
  exec("more /mnt/user/ota/UPGRADE.TXT | awk '/VERSION:/{print $1}' | awk '{ print substr($0,9,20)}'",$version_up);
	return "<script type='text/javascript'>
			function init(){
				if(top.uploadComplete) top.uploadComplete('".$status."');}
			window.onload=init;
			
			
		</script>";

}

?>
