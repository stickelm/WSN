<?
	if ($_POST['check'] == 'frame'){
		exec("tail -n 100 /mnt/lib/cfg/parser/frame.log",$frames);
    for ($i = 1; $i <= 100; $i++) {
      $list.='<span style="margin:5px;font-size: 10px">'.$frames[$i].'</span><br>';
      };
		echo $list;
	}


	if ($_POST['check'] == 'sensor'){
		exec("tail -n 100 /mnt/lib/cfg/parser/sensor.log",$sensor);
    for ($i = 1; $i <= 100; $i++) {
      $list.='<span style="margin:5px;font-size: 10px">'.$sensor[$i].'</span><br>';
      };
		echo $list;
	}
?>