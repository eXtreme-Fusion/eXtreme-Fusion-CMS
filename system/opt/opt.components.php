<?php

	function generateTagElementList($list) {
		$code = '';
		foreach($list as $name => $value) {
			$code .= ' '.$name.'="'.htmlspecialchars($value).'"';			
		}
		return $code;
	}

	class selectComponent implements ioptComponent {
		protected $_list = array();
		protected $message = NULL;
		protected $tagParameters = array();
		protected $tpl;
		public function __construct($name = '') {
			$this -> _list = array();
			$this -> message = NULL;
			$this -> tagParameters = array();		
		}
		public function setOptInstance(optClass $tpl) {
			$this -> tpl = $tpl;
		}
		public function set($name, $value) {
			switch($name) {
				case 'message':
					$this -> message = $value;
					break;
				case 'selected':
					foreach($this -> _list as $i => &$item) {
						if($item['value'] == $value) {
							$item['selected'] = true;
						}				
					}
					break;
				default:
					$this -> tagParameters[$name] = $value;		
			}
		}
		public function push($value, $desc, $selected = false) {
			$this -> _list[] = array(
				'value' => $value,
				'desc' => $desc,
				'selected' => $selected		
			);
		}
		public function setDatasource(&$source) {
			$this -> _list = $source;		
		}
		public function begin() {	
			$code = '<select'.generateTagElementList($this->tagParameters).'>';
			$selected = 0;
			foreach($this -> _list as $item) {
				if($item['selected'] == 1 && $selected == 0) {
					$code .= '<option value="'.$item['value'].'" selected="selected">'.$item['desc'].'</option>';
					$selected = 1;
				} else {
					$code .= '<option value="'.$item['value'].'">'.$item['desc'].'</option>';
				}		
			}
			$code .= '</select>';
			_e($code);
		}
		public function onmessage($pass_to) {
			if($this -> message == NULL) {
				return 0;
			}
			$this -> tpl -> vars[$pass_to] = $this -> message;
			return 1;		
		}
		public function end() {
			_e('');		
		}
	}

	class textInputComponent implements ioptComponent {
		protected $message = NULL;
		protected $tagParameters = array();
		protected $tpl;
		public function __construct($name = '') {
			$this -> message = NULL;		
		}
		public function setOptInstance(optClass $tpl) {
			$this -> tpl = $tpl;
		}
		public function set($name, $value) {
			switch($name) {
				case 'message':
					$this -> message = $value;
					break;
				default:
					$this -> tagParameters[$name] = $value;		
			}
		}		
		public function push($value, $desc, $selected = false) {
			$this -> set($value, $desc);
		}
		public function setDatasource(&$source) {
			if(is_array($source)) {
				if(isset($source['name'])) {
					$this -> tagParameters['name'] = $source['name'];
				}
				if(isset($source['value'])) {
					$this -> tagParameters['value'] = $source['value'];
				}
				if(isset($source['message'])) {
					$this -> message = $source['message'];
				}
			}
		}
		public function begin() {
			_e('<input type="text"'.generateTagElementList($this->tagParameters).' />');
		}
		public function onmessage($pass_to) {
			if($this -> message == NULL) {
				return 0;
			}
			$this -> tpl -> vars[$pass_to] = $this -> message;
			return 1;
		}
		public function end() {
			_e('');		
		}
	}
	class textLabelComponent extends textinputComponent implements ioptComponent {
		public function begin() {
			$code = '<input type="hidden"'.generateTagElementList($this->tagParameters).' />';
			if($this -> tagParameters['value'] != NULL) {
				_e($code.'<span class="label">'.htmlspecialchars($this -> tagParameters['value']).'</span>');
			}
			_e($code);
		}
	}
	class formActionsComponent implements ioptComponent {
		private $buttons;
		protected $tpl;
		public function __construct($name = '') {
			$this -> buttons = array();
		}
		public function setOptInstance(optClass $tpl) {
			$this -> tpl = $tpl;
		}
		public function set($name, $value) {
			$this -> push($name, $value);
		}
		public function push($name, $value, $type = 'submit') {
			$this -> buttons[] = array(
				'name' => $name,
				'value' => $value,
				'type' => $type
			);		
		}
		public function setDatasource(&$source) {
			$this -> buttons = $source;
		}
		public function begin() {
			$code = '';
			foreach($this -> buttons as $button) {
				$code .= '<input type="'.$button['type'].'"'.($button['name'] != NULL ? ' name="'.$button['name'].'"' : '').' value="'.$button['value'].'"/>';			
			}
			_e($code);
		}
		public function end() {
			_e('');		
		}
	}
?>
