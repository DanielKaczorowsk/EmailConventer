# EmailConventer
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
 	This is settings on gmail.
# Template
	  $template = new Template;
	  $this->query->template = $template
	 						 ->file($this->query->template) -> primary html
							 ->title('Przykładowy Email')
	             ->data($data) -> use database @startforeach @endforeach
							 ->contents($this->query->contentTemplate) -> content html
						 	 ->render();
 EmailConventer use primary settings template.
