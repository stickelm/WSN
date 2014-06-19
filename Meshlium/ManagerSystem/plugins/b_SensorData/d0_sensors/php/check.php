<?
	include_once "interface_capturer.php";

	if ($_POST['check'] == 'frame'){
		exec("tail /mnt/lib/cfg/parser/frame.log",$frames);
    for ($i = 1; $i <= 75; $i++) {
      $list.='<span style="margin:5px;font-size: 10px">'.$frames[$i].'</span><br>';
      };
		echo $list;
	}


	if ($_POST['check'] == 'sensor'){
		exec("tail /mnt/lib/cfg/parser/sensor.log",$sensor);
    for ($i = 1; $i <= 75; $i++) {
      $list.='<span style="margin:5px;font-size: 10px">'.$sensor[$i].'</span><br>';
      };
		echo $list;
	}

	if (isset($_POST['id']) && isset($_POST['fields']) && isset($_POST['type'])){
		$id=$_POST['id'];
		$fields=$_POST['fields'];
		$type=$_POST['type'];

		$user_sensors_xml=$_SERVER['DOCUMENT_ROOT'].'/ManagerSystem/plugins/b_SensorData/d0_sensors/data/user_sensors.xml';
		$standard_sensors_xml=$_SERVER['DOCUMENT_ROOT'].'/ManagerSystem/plugins/b_SensorData/d0_sensors/data/sensors.xml';
		if($id == ""){
			echo '<span style="color:#ff4444;margin:5px;font-size:15px;font-weigth:bold;">ERROR: ASCII ID must be defined.</span>';
			die;
		}
		if($fields == ""){
			echo '<span style="color:#ff4444;margin:5px;font-size:15px;font-weigth:bold;">ERROR: Number of fields must be defined.</span>';
			die;
		}else if((int)$fields != $fields || (int)$fields < 0 || !is_numeric($fields)){
			echo '<span style="color:#ff4444;margin:5px;font-size:15px;font-weigth:bold;">ERROR: Fields must be a positive number.</span>';
			die;
		}else{
			$error=0;
		}

		$standard_sensors = simplexml_load_file($standard_sensors_xml);
	    foreach ($standard_sensors as $sensor):
	        $ascii_id=$sensor->string;
	    	if($ascii_id == $id){
	    		$error=1;
	    	}
	    endforeach;

	    if (!$error){
		    $asigned_id=200;
		    $my_sensors = simplexml_load_file($user_sensors_xml);
		    foreach ($my_sensors as $sensor):
		        $ascii_id=$sensor->string;
		    	if($ascii_id == $id){
		    		$error=1;
		    	}
		    	$asigned_id++;
		    endforeach;
		}

	    if(!$error){
	    	$xml = simplexml_load_file($user_sensors_xml);
			$sensor = $xml->addChild('sensor');
			$sensor->addChild('id', $asigned_id);
			$sensor->addChild('string', $id);
			$sensor->addChild('fields', $fields);
			$sensor->addChild('type', $type);

			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($xml->asXML());
			$dom->save($user_sensors_xml);

			echo '<span style="color:#343434;margin:5px;font-size:15px;font-weigth:bold;">Sensor added.</span>';

	    }else{
	    	echo '<span style="color:#ff4444;margin:5px;font-size:15px;font-weigth:bold;">ERROR: ASCII ID already defined. Enter a diferent one.</span>';
	    }

	}

	if(isset($_POST['deleteid'])){

		$user_sensors_xml=$_SERVER['DOCUMENT_ROOT'].'/ManagerSystem/plugins/b_SensorData/d0_sensors/data/user_sensors.xml';

		$delete_id=str_replace ("delete_" , "" , $_POST['deleteid']);

		$doc = new DOMDocument;
		$doc->load($user_sensors_xml);

		$thedocument = $doc->documentElement;

		//list of sensors
		$list = $thedocument->getElementsByTagName('sensor');

		$nodeToRemove = null;
		foreach ($list as $domElement){

			foreach($domElement->childNodes as $child) {
				
				if($child->nodeName == 'id'){
					$value= $child->nodeValue;
					if($value == $delete_id){
						$nodeToRemove = $domElement;
					}
				}
    		}
		}

		//Now remove it.
		if ($nodeToRemove != null){
			$thedocument->removeChild($nodeToRemove);
			$doc->save($user_sensors_xml);
			echo '<span style="color:#343434;margin:5px;font-size:15px;font-weigth:bold;">Sensor deleted.</span>';
		}
	}

	if(isset($_POST['refresh'])){
		echo make_sensor_xml_content();
	}
?>
