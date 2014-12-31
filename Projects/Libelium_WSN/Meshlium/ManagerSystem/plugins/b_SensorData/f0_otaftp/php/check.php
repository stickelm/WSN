<?
	include_once "interface_capturer.php";

	if ($_POST['do']  == 'refres'){

		exec("more /mnt/user/ota/UPGRADE.TXT | awk '/FILE:/{print $1}' | awk '{ print substr($0,6,20)}'",$name_up);
    	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/PATH:/{print $1}' | awk '{ print substr($0,6,20)}'",$path_up);
    	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/SIZE:/{print $1}' | awk '{ print substr($0,6,20)}'",$size_up);
    	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/VERSION:/{print $1}' | awk '{ print substr($0,9,20)}'",$version_up);
		$list.='<span><font face="Courier New" size="2">FILE:<b><span id="binary_file_name">'.$name_up[0].'</span></b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">PATH:<b>'.$path_up[0].'</b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">SIZE:<b><span id="binary_file_size">'.$size_up[0].'</span></b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">VERSION:<b>'.$version_up[0].'</b></font></span>';
		echo $list;
	};

	if ($_POST['do'] == 'no_file'){
		$instruccion_1 = "echo 'FILE:NO_FILE' >> /mnt/user/ota/UPGRADE.TXT.new";
		$instruccion_2 = "echo 'PATH:/' >> /mnt/user/ota/UPGRADE.TXT.new";
		$instruccion_3 = "echo 'SIZE:0' >> /mnt/user/ota/UPGRADE.TXT.new";
		$instruccion_4 = "echo 'VERSION:0' >> /mnt/user/ota/UPGRADE.TXT.new";
		exec($instruccion_1);
		exec($instruccion_2);
		exec($instruccion_3);
		exec($instruccion_4);
		exec("mv /mnt/user/ota/UPGRADE.TXT.new /mnt/user/ota/UPGRADE.TXT");

	

	}
	if ($_POST['do'] == 'change'){

		$name = $_POST['name'];
		//$size = $_POST['size'];
		$instruccion_1 = "echo 'FILE:".$name."' >> /mnt/user/ota/UPGRADE.TXT.new";
		exec($instruccion_1);

		exec("awk 'NR==2' /mnt/user/ota/UPGRADE.TXT | awk '/PATH:/{print $1}' | awk '{ print substr($0,6,20)}'",$linea_2);
		$instruccion_2 = "echo 'PATH:".$linea_2[0]."' >> /mnt/user/ota/UPGRADE.TXT.new";
		exec($instruccion_2);	

		exec("ls -la /mnt/user/ota/".$name." | awk '{print $5}'",$size);
		$instruccion_3 = "echo 'SIZE:".$size[0] ."' >> /mnt/user/ota/UPGRADE.TXT.new";
		exec($instruccion_3);
		exec("awk 'NR==4' /mnt/user/ota/UPGRADE.TXT | awk '/VERSION:/{print $1}' | awk '{ print substr($0,9,20)}'",$linea_4);

		$instruccion_4 = "echo 'VERSION:".$linea_4[0] ."' >> /mnt/user/ota/UPGRADE.TXT.new";
		exec($instruccion_4);
		exec("mv /mnt/user/ota/UPGRADE.TXT.new /mnt/user/ota/UPGRADE.TXT");

		exec("more /mnt/user/ota/UPGRADE.TXT | awk '/FILE:/{print $1}' | awk '{ print substr($0,6,20)}'",$name_up);
    	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/PATH:/{print $1}' | awk '{ print substr($0,6,20)}'",$path_up);
    	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/SIZE:/{print $1}' | awk '{ print substr($0,6,20)}'",$size_up);
    	exec("more /mnt/user/ota/UPGRADE.TXT | awk '/VERSION:/{print $1}' | awk '{ print substr($0,9,20)}'",$version_up);
		$list.=' <span><font face="Courier New" size="2">FILE:<b><span id="binary_file_name">'.$name_up[0].'</span></b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">PATH:<b>'.$path_up[0].'</b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">SIZE:<b><span id="binary_file_size">'.$size_up[0].'</span></b></font></span>
                <br><br>
                <span><font face="Courier New" size="2">VERSION:<b>'.$version_up[0].'</b></font></span>';
		echo $list;
	}
?>
