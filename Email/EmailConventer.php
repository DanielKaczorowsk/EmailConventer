<?php
	namespace Email;
	use EmailTemplate\Template as Template;
	include "../"."SPL_autoload_register.php";
		class EmailConventer
		{
			private $query,$option;
			public function reset():	void
			{
				$this->query = new \stdClass();
			}
			public function host(array $host)
			{
				$this->reset();
				$this->query->host = $host;
				return $this;
			}
			public function title(string $title)
			{
				$this->query->title = $title;
				return $this;
			}
			public function from(string $from)
			{
				$this->query->from = $from;
				return $this;
			}
			public function To(array $to)
			{
				$this->query->To = $to;
				return $this;
			}
			public function MessageId(string $id)
			{
				$this->query->MessageId = $id;
				return $this;
			}
			public function wordwrap(string $msg,float $count)
			{
				$this->query->wordwrap = wordwrap($msg,$count);
				return $this;
			}
			public function header(array $headers)
			{
				$this->query->header = $headers;
				return $this;
			}
			public function template(string $template)
			{
				$this->query->template = $template;
				return $this;
			}
			public function contentTemplate(string $content)
			{
				$this->query->contentTemplate = $content;
				return $this;
			}
			public function AddCC(string $cc)
			{
				$this->query->AddCC = $cc;
				return $this;
			}
			public function AddBCC(string $bcc)
			{
				$this->query->AddBCC = $bcc;
				return $this;
			}
			public function user(array $user)
			{
				$this->query->user = $user;
				return $this;
			}
			public function TSL($tsl)
			{
				$this->query->TSL = $tsl;
				return $this;
			}
			public function send()
			{
				/*ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				error_reporting(E_ALL);*/ //error report
				if(isset($this->query->host['url']))
				{
					$ch = curl_init();
					ini_set('SMTP',$this->query->host['url']); //url smtp
					$this->option[CURLOPT_URL] = 'smtp://'.$this->query->host['url'];
					$this->option[CURLOPT_FOLLOWLOCATION] = false;
					if(isset($this->query->host['port']))
					{
						ini_set('smtp_port',$this->query->host['port']); //port
						$this->option[CURLOPT_PORT] = $this->query->host['port'];
					}
					if(isset($this->query->from))
					{
						$this->option[CURLOPT_MAIL_FROM] = $this->query->from;
						$this->query->headers = array('FROM: '.$this->query->from);
					}
					if(isset($this->query->To))
					{
						$this->option[CURLOPT_MAIL_RCPT] = $this->query->To;
						array_push($this->query->headers,'To: '.implode(',',$this->query->To));
					}
					if(isset($this->query->user['name'])&&$this->query->user['password'])
					{
						ini_set('username',$this->query->user['name']);
						ini_set('password',$this->query->user['password']);
						$this->option[CURLOPT_USERPWD] = implode(':',$this->query->user);
					}
					if(isset($this->query->AddCC))
					{
						array_push($this->query->headers,'CC:'.$this->query->AddCC);
					}
					if(isset($this->query->AddBCC))
					{
						array_push($this->query->headers,'BCC:'.$this->query->AddBCC);
					}
					if(isset($this->query->headers))
					{
						$bounary = uniqid();
						if(isset($this->query->title))
						{
							array_push($this->query->headers,'Subject:'.$this->query->title);
						}
						array_push($this->query->headers,'Date:'.date('r'));
						if(isset($this->query->MessageId))
						{
							array_push($this->query->headers,'Message-ID:'.$this->query->MessageId);
						}						array_push($this->query->headers,"Content-type: multipart/alternative; bounary=".$bounary);
					}
					if(isset($this->query->TSL))
					{
						$this->option[CURLOPT_USE_SSL] = CURLUSESSL_ALL;
						$this->option[CURLOPT_SSL_VERIFYPEER] =	true;
						$this->option[CURLOPT_SSL_VERIFYHOST] = 2;
						$this->option[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_3;

					}
					if(isset($this->query->template))
					{
				$template = new Template;
				$this->query->template = $template
  							 ->file($this->query->template)
							 ->title('Przyk³adowy Email')
							 ->contents($this->query->contentTemplate)
						 	 ->render();
						$array_data[$bounary."\r\n"."Content-Type:text/html;charset=utf-8"."\r\n"."Content-Transfer-Encoding: 8bit"]=$this->query->template;
						//$this->option[CURLOPT_ENCODING] = 'utf-8';
					}
					else if(isset($this->query->wordwrap))
					{
						$array_data[$bounary."\r\n"."Content-Type:text/plain;charset=utf-8"."\r\n"."Content-Transfer-Encoding: 8bit"]= $this->query->wordwrap;
					}
					if(isset($this->query->template) || isset($this->query->wordwrap))
					{
						$this->option[CURLOPT_POST] = true;
                                                $this->option[CURLOPT_RETURNTRANSFER]=true;
                                                $this->option[CURLOPT_CUSTOMREQUEST] = 'POST';
                                                $this->option[CURLOPT_POSTFIELDS] = $array_data;
                                                $this->option[CURLOPT_UPLOAD] = true;
                                                $this->option[CURLOPT_HTTPHEADER] = $this->query->headers;
					}
					curl_setopt_array($ch,$this->option);
					$response = curl_exec($ch);
					$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
					$error_no = curl_errno($ch);
					$error = curl_error($ch);
					if ($error_no != 0) 
					{
						echo 'Problem opening connection.  CURL Error: ' . $error_no;
					}
					return 'Wyslano';
				}
			}
		}
		$email = new EmailConventer;
		$email = $email->host(['url'=>'smtp.gmail.com','port'=>587])
		->from('')
		->To([''])
		->title('')
		->MessageId('<>')
		->User(['name'=>'','password'=>''])
		->template(__DIR__.'/../'.'EmailTemplate/test.html')
		->contentTemplate(__DIR__.'/../'.'EmailTemplate/email.html')
		->TSL(true)
		->send();
?>

