<?php

	/*	-- ESRI-ArcGIS integration --

															  )[            ....   
														   -$wj[        _swmQQWC   
															-4Qm    ._wmQWWWW!'    
															 -QWL_swmQQWBVY"~.____ 
															 _dQQWTY+vsawwwgmmQWV! 
											1isas,       _mgmQQQQQmmQWWQQWVY!"-    
										   .s,. -?ha     -9WDWU?9Qz~- -- -         
										   -""?Ya,."h,   <!`_mT!2-?5a,             
										   -Swa. Yg.-Q,  ~ ^`  /`   "$a.           
		 aac  <aa, aa/                aac  _a,-4c ]k +m               "1           
		.QWk  ]VV( QQf   .      .     QQk  )YT`-C.-? -Y  .                         
		.QWk       WQmymmgc  <wgmggc. QQk       wgz  = gygmgwagmmgc                
		.QWk  jQQ[ WQQQQQQW;jWQQ  QQL QQk  ]WQ[ dQk  ) QF~"WWW(~)QQ[               
		.QWk  jQQ[ QQQ  QQQ(mWQ9VVVVT QQk  ]WQ[ mQk  = Q;  jWW  :QQ[               
		 WWm,,jQQ[ QQQQQWQW']WWa,_aa. $Qm,,]WQ[ dQm,sj Q(  jQW  :QW[               
		 -TTT(]YT' TTTYUH?^  ~TTB8T!` -TYT[)YT( -?9WTT T'  ]TY  -TY(               
						 
							  www.libelium.com


		Libelium Comunicaciones Distribuidas SL

		Autor: Joaquín Ruiz

		http://88.1.207.168:11111/meshlium/rest/services/Libelium/FeatureServer
	*/

	class REST {
		
		public $_allow = array();
		public $_content_type = "text/plain;charset=utf8";
		public $_request = array();
		
		private $_method = "";		
		private $_code = 200;
		
		public function __construct(){
			$this->inputs();
		}
		
		public function get_referer(){
			return $_SERVER['HTTP_REFERER'];
		}
		public function response($data,$status){
			$this->_code = ($status)?$status:200;
			$this->set_headers();
			print_r($data);
			exit;
		}
		public function responseImg($data,$status){
			$this->_code = ($status)?$status:200;
			$this->set_headersImg();
			echo $data;
			exit;
		}

		private function get_status_message(){
			$status = array(
						100 => 'Continue',  
						101 => 'Switching Protocols',  
						200 => 'OK',
						201 => 'Created',  
						202 => 'Accepted',  
						203 => 'Non-Authoritative Information',  
						204 => 'No Content',  
						205 => 'Reset Content',  
						206 => 'Partial Content',  
						300 => 'Multiple Choices',  
						301 => 'Moved Permanently',  
						302 => 'Found',  
						303 => 'See Other',  
						304 => 'Not Modified',  
						305 => 'Use Proxy',  
						306 => '(Unused)',  
						307 => 'Temporary Redirect',  
						400 => 'Bad Request',  
						401 => 'Unauthorized',  
						402 => 'Payment Required',  
						403 => 'Forbidden',  
						404 => 'Not Found',  
						405 => 'Method Not Allowed',  
						406 => 'Not Acceptable',  
						407 => 'Proxy Authentication Required',  
						408 => 'Request Timeout',  
						409 => 'Conflict',  
						410 => 'Gone',  
						411 => 'Length Required',  
						412 => 'Precondition Failed',  
						413 => 'Request Entity Too Large',  
						414 => 'Request-URI Too Long',  
						415 => 'Unsupported Media Type',  
						416 => 'Requested Range Not Satisfiable',  
						417 => 'Expectation Failed',  
						500 => 'Internal Server Error',  
						501 => 'Not Implemented',  
						502 => 'Bad Gateway',  
						503 => 'Service Unavailable',  
						504 => 'Gateway Timeout',  
						505 => 'HTTP Version Not Supported');
			return ($status[$this->_code])?$status[$this->_code]:$status[500];
		}

		public function get_request_method(){
			return $_SERVER['REQUEST_METHOD'];
		}

		private function inputs(){
			switch($this->get_request_method()){
				case "POST":
					$this->_request = $this->cleanInputs($_POST);
					break;
				case "GET":
				case "DELETE":
					$this->_request = $this->cleanInputs($_GET);
					break;
				case "PUT":
					parse_str(file_get_contents("php://input"),$this->_request);
					$this->_request = $this->cleanInputs($this->_request);
					break;
				default:
					$this->response('',406);
					break;
			}
		}

		private function cleanInputs($data){
			$clean_input = array();
			if(is_array($data)){
				foreach($data as $k => $v){
					$clean_input[$k] = $this->cleanInputs($v);
				}
			}else{
				if(get_magic_quotes_gpc()){
					$data = trim(stripslashes($data));
				}
				$data = strip_tags($data);
				$clean_input = trim($data);
			}
			return $clean_input;
		}

		protected function set_headers(){
			header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
			header("Content-Type:".$this->_content_type);
		}
		private function set_headersImg(){
			header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
			header("Content-Type: image/png");
		}
	}	
?>