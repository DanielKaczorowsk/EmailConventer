<?php
namespace EmailTemplate;

	class Template
	{
		private $query,$content,$foreach;
		public function reset():void
		{
			$this->query = new \stdClass();
		}
		public function file(string $template):	template
		{
			$this->reset();
			$this->query->file = file_get_contents($template);
			return $this;
		}
		public function title(string $title):	template
		{
			$this->query->title = $title;
			return $this;
		}
		public function nav(string $nav):	template
		{
			$this->query->nav = $nav;
			return $this;
		}
		public function contents(string $contents):	template
		{
			$this->query->contents = $contents;
			$this->foreach = $contents;
			return $this;
		}
		public function data(array $data):	template
		{
			$this->query->data = $data;
			return $this;
		}
		public function render()
		{
			 if(isset($this->query->title))
			 {
				 $this->content['title'] = $this->query->title;
			 }
			 if(isset($this->query->nav))
			 {
				if(file_exists($this->query->nav))
				{
				 $this->query->nav = file_get_contents($this->query->nav);
				 $this->content['nav'] = $this->query->nav;
				}
				else
				{
				 $this->content['nav'] = $this->query->nav;
				}
			 }
			 if(isset($this->query->contents))
			 {
				if(file_exists($this->query->contents))
				{
					 if(isset($this->query->data))
					 {
					   $this->query->contents = file_get_contents($this->query->contents);
					   $len = count($this->query->data);
					   $i = -1;
					   foreach($this->query->data as $keys => $datas)
					   {
						foreach($datas as $key1 => $data)
						{
							$i++;
							$this->query->contents = $this->foreach;
							$this->query->contents = file_get_contents($this->query->contents);
							preg_match('/@startforeach name='.$keys.'(.+)@endforeach/s',$this->query->contents,$foreach);
							$this->query->contents = $foreach[1];
							if($key1 === 0)
							{
								foreach($data as $key2 => $value) 
								{
									$this->query->contents = preg_replace('/{foreach-'. $key2 . '}/s', $value, $this->query->contents);
								}
							}
							else if($key1 === $i)
							{
								foreach($data as $key2 => $value) 
								{
									$this->query->contents = preg_replace('/{foreach-'. $key2 . '}/s', $value, $this->query->contents);
								}
							}
							$this->file[$key1] = $this->query->contents;
						}
					   }
						$this->query->contents = $this->foreach;
						$this->query->contents = file_get_contents($this->query->contents);
						$this->query->contents = preg_replace('/@startforeach(.+)@endforeach/s',implode(' ',$this->file),$this->query->contents);
						$this->content['contents'] = $this->query->contents;
					 }
					 else
					 {
						$this->query->contents = file_get_contents($this->query->contents);
						$this->content['contents'] = $this->query->contents;
					 } 
				}
				else
				{
					$this->query->contents = file_get_contents($this->query->contents);
				 	$this->content['contents'] = $this->query->contents;
				} 
			 }
			 
			 foreach($this->content as $key => $value) 
			 {
                $this->query->file = preg_replace('/{' . $key . '}/', $value, $this->query->file);
             }
			 return $this->query->file;
		}
	}
?>
