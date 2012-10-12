<?php

	class optInstruction
	{
		protected $tpl;
		protected $compiler;
		protected $output;
		public $inMaster = false;

		public function __construct(optCompiler $compiler)
		{
			$this -> compiler = $compiler;
			$this -> tpl = $compiler -> tpl;
		} // end __construct();

		public function setOutput(&$output)
		{
			$this -> output = &$output;
		} // end setOutput();

		public function configure()
		{
			return array();
		} // end configure();
		
		public function defaultTreeProcess($block)
		{
			if(!$block instanceof optBlock)
			{
				return false;
			}
			if($block -> hasChildNodes())
			{
				foreach($block as $node)
				{
					$this -> nodeProcess($node);
				}
			}
		} // end defaultTreeProcess();
		
		public function nodeProcess(ioptNode $node)
		{
			switch($node -> getType())
			{
				case OPT_ROOT:
					$this -> defaultTreeProcess($node -> getFirstBlock());
					break;
				case OPT_TEXT:
					$this -> compiler -> out($node->__toString(), true);
					break;
				case OPT_EXPRESSION:
					$result = $this -> compiler -> compileExpression($node->getFirstBlock()->getAttributes(), 1);
					if($result[1] == 1)
					{
						// we have an assignment, so we must build different code
						$this -> compiler -> out($result[0].';');
					}
					else
					{
						$this -> compiler -> out('echo '.$result[0].';');
					}
					break;
				case OPT_INSTRUCTION:
					$this -> instructionProcess($node);
					break;
				case OPT_COMPONENT:
					$this -> compiler -> processors['component'] -> instructionNodeProcess($node);
					break;
				case OPT_ATTRIBUTE:
					$this -> compiler -> mapper[$node -> getName()] -> processAttribute($node->getFirstBlock());
					break;
			}
		} // end nodeProcess();

		final public function findSnippets($block, &$snippetName, $group = false)
		{
			$tag = $block -> getElementByTagName('use');

			if(is_null($tag))
			{
				return NULL;
			}

			$fb = $tag -> getFirstBlock();

			$params = array(
				'snippet' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
			);
			$this -> compiler -> parametrize('use', $fb->getAttributes(), $params);
			$snippetName = $params['snippet'];

			if(!$group)
			{
				if(isset($this->compiler->genericBuffer['bind'][$params['snippet']]))
				{
					return $this->compiler->genericBuffer['bind'][$params['snippet']];
				}
			}
			else
			{
				if(isset($this->compiler->genericBuffer['bindGroup'][$params['snippet']]))
				{
					return $this->compiler->genericBuffer['bindGroup'][$params['snippet']];
				}
			}
			return NULL;
		} // end findSnippets();
		
		public function instructionProcess(ioptNode $node)
		{
			if($node -> getType() == OPT_INSTRUCTION)
			{
				// is there any processor for this instruction?
				if(!isset($this -> compiler -> mapper[$node -> getName()]))
				{
					return 0;
				}
				
				// pass the execution to the instruction processor

				if(!$this->compiler->master || $this->compiler->processors[$node->getName()]->inMaster)
				{
					$this -> compiler -> mapper[$node -> getName()] -> instructionNodeProcess($node);
				}
				return 1;
			}	
			return 0;
		} // end instructionProcess();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			
		} // end instructionNodeProcess();
		
		public function processAttribute(optBlock $block)
		{
		
		} // end processAttribute();
		
		public function processOpt($namespace)
		{
			return 'true';
		} // end processOpt();
	}
	
	class optSection extends optInstruction
	{
		private $sections = array(0 => array());
		public $sectionList = array();
		public $nesting = 0;

		public function configure()
		{
			return array(
				// processor name
				0 => 'section',
				// instructions
				'section' => OPT_MASTER,
				'sectionelse' => OPT_ALT,
				'/section' => OPT_ENDER,
				'tree' => OPT_MASTER,
				'treeelse' => OPT_ALT,
				'/tree' => OPT_ENDER,
				'show' => OPT_MASTER,
				'showelse' => OPT_ALT,
				'/show' => OPT_ENDER,
				'cycle' => OPT_COMMAND,
				'sectionfirst' => OPT_ATTRIBUTE,
				'sectionlast' => OPT_ATTRIBUTE,
				'sectioncycle' => OPT_ATTRIBUTE
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{	
				switch($block -> getName())
				{
					case 'show':
							$this -> showBegin($block -> getAttributes());
							$this -> defaultTreeProcess($block);
							break;
					case 'showelse':
							$this -> showElse();
							$this -> defaultTreeProcess($block);
							break;
					case '/show':
							$this -> showEnd();
							break;
					case 'section':
							$this -> sectionBegin($block -> getAttributes(), $block);
							$this -> defaultTreeProcess($block);
							break;
					case 'sectionelse':
							$this -> sectionElse();
							$this -> defaultTreeProcess($block);
							break;
					case '/section':
							$this -> sectionEnd();
							break;
					case 'tree':
							$this -> treeBegin($block -> getAttributes(), $block);
							$this -> defaultTreeProcess($block);
							break;
					case 'treeelse':
							$this -> treeElse();
							$this -> defaultTreeProcess($block);
							break;
					case '/tree':
							$this -> treeEnd();
							break;
					case 'cycle':
							$this -> cycle($block->getAttributes());

				}
			}
		} // end process();
		
		public function showBegin($paramStr)
		{
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
				'order' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_STRING, NULL),
				'state' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
				'datasource' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
				'separator' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
			);
			
			$this -> compiler -> parametrize('show', $paramStr, $params);
			$this -> showAction($params['name'], $params['order'], $params['state'], $params['datasource'], $params['separator'], true);
		} // end showBegin();
		
		private function showAction($name, $order, $state, $datasource, $separator, $show)
		{
			$link = '';
			if($this -> tpl -> sectionDynamic == OPT_SECTION_COMPILE)
			{
				if(isset($this -> tpl -> data[$name]) && $this -> tpl -> data[$name] instanceof optDynamicData)
				{
					// This is a dynamic section
					$output = ' if(is_object($this->data[\''.$name.'\'])){ $__'.$name.'_data = $this->data[\''.$name.'\']->call(); }else{ $__'.$name.'_data = array(); }';
					$syntax = $this -> getLink($name, $datasource, $link);
					$link = '$__'.$name.'_data';
				}
				else
				{					
					$syntax = $this -> getLink($name, $datasource, $link); 
					$output = '';
				}
			}
			else
			{
				$syntax = $this -> getLink($name, $datasource, $link);
				$output = 'if(is_object('.$link.')){ $__'.$name.'_data = '.$link.'->call(); }else{ $__'.$name.'_data = '.$link.'; }';
				$link = '$__'.$name.'_data';
			}
			if(is_null($state))
			{
				$output .= ' if(is_array('.$link.') && ($__'.$name.'_cnt = sizeof('.$link.')) > 0){ ';
			}
			else
			{
				if($this -> tpl -> statePriority == OPT_PRIORITY_NORMAL)
				{
					$output .= ' if('.$state.' && is_array('.$link.') && ($__'.$name.'_cnt = sizeof('.$link.')) > 0){ ';
				}
				else
				{
					$output .= ' if('.$state.'){ if(is_array('.$link.') && ($__'.$name.'_cnt = sizeof('.$link.')) > 0){ ';
				}
			}

			$this -> sections[$this -> nesting] = array(
				'name' => $name,
				'order' => $order,
				'state' => $state,
				'link' => $link,
				'show' => $show,
				'separator' => $separator,
				'else' => false
			);
			$this -> compiler -> out($output);
		} // end showAction();
		
		public function showElse()
		{
			if($this->sections[$this->nesting]['show'] == true)
			{
				$this -> compiler -> out(' } else { ');	
			}		
		} // end showElse();
		
		public function showEnd()
		{
			if($this->sections[$this->nesting]['show'] == true)
			{
				if(!is_null($this->sections[$this->nesting]['state']) && $this -> compiler -> tpl -> statePriority == OPT_PRIORITY_HIGH)
				{
					$this -> compiler -> out(' } } ');	
				}
				else
				{
					$this -> compiler -> out(' } ');	
				}
			}
			unset($this -> sections[$this -> nesting]);
		} // end showEnd();
		
		public function sectionBegin($paramStr, $block)
		{
			if(@$this->sections[$this->nesting]['show'] != true)
			{
				$params = array(
					'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
					'order' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_STRING, NULL),
					'state' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
					'datasource' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
					'separator' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
				);
				$this -> compiler -> parametrize('section', $paramStr, $params);
				
				$separatorNode = $block -> getElementByTagName('separator');
				if(is_object($separatorNode))
				{
					$params['separator'] = $separatorNode;
				}
				
				$this -> showAction($params['name'], $params['order'], $params['state'], $params['datasource'], $params['separator'], false);			
			}
			else
			{
				$separatorNode = $block -> getElementByTagName('separator');
				if(is_object($separatorNode))
				{
					$this->sections[$this->nesting]['separator'] = $separatorNode;
				}
			}
			
			// Process the section
			$this -> sectionList[$this -> nesting] = $name = $this->sections[$this->nesting]['name'];

			if($this->sections[$this->nesting]['order'] == 'reversed')
			{
				$this -> compiler -> out(' for($__'.$name.'_id = $__'.$name.'_cnt - 1; $__'.$name.'_id >= 0; $__'.$name.'_id--){ $__'.$name.'_val = &'.$this->sections[$this->nesting]['link'].'[$__'.$name.'_id]; ');		
			}			
			else
			{
				$this -> compiler -> out(' foreach('.$this->sections[$this->nesting]['link'].' as $__'.$name.'_id => &$__'.$name.'_val){ ');
			}
			$this -> nesting++;
		} // end sectionBegin();

		public function sectionElse()
		{
			if($this->sections[$this->nesting-1]['show'] == false)
			{
				$this->sections[$this->nesting-1]['else'] = true;
				$this -> compiler -> out(' } } else { ');		
			}
		} // end sectionElse();

		public function sectionEnd()
		{
			$this -> nesting--;
			
			if(!is_null($this->sections[$this->nesting]['separator']))
			{
				if(is_object($this->sections[$this->nesting]['separator']))
				{
					$this -> compiler -> out(' if(!'.$this->processOpt(array(0 => 'opt', 'section', $this->sections[$this->nesting]['name'], 'last')).'){ ');
					$this -> defaultTreeProcess($this->sections[$this->nesting]['separator']->getFirstBlock());
					$this -> compiler -> out(' } ');
				}
				else
				{
					$this -> compiler -> out(' if(!'.$this->processOpt(array(0 => 'opt', 'section', $this->sections[$this->nesting]['name'], 'last')).'){ echo '.$this->sections[$this->nesting]['separator'].'; } ');
				}
			}
			
			if($this->sections[$this->nesting]['show'] == true)
			{				
				$this -> compiler -> out(' } ');			
			}
			else
			{
				if($this->sections[$this->nesting]['else'] == true)
				{
					$this -> compiler -> out(' } ');
				}
				else
				{
					$this -> compiler -> out(' } } ');
				}
				unset($this -> sectionList[$this -> nesting]);
				unset($this -> sections[$this -> nesting]);
			}
		} // end sectionEnd();

		public function treeBegin($paramStr, $block)
		{
			if(@$this->sections[$this->nesting]['show'] != true)
			{
				$params = array(
					'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
					'order' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_STRING, NULL),
					'state' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
					'datasource' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
				);
				$this -> compiler -> parametrize('section', $paramStr, $params);
				$this -> showAction($params['name'], $params['order'], $params['state'], $params['datasource'], NULL, false);			
			}
			// Find the code snippets
			$snippetName = '';
			$snippet = $this -> findSnippets($block, $snippetName, true);
			if(is_null($snippet))
			{
				$snippet = array();
				foreach($block as $testnode)
				{
					if($testnode -> getType() == OPT_UNKNOWN)
					{
						switch($testnode -> getName())
						{
							case 'opening':
								$snippet['opening'] = $testnode->getFirstBlock();
								break;
							case 'closing':
								$snippet['closing'] = $testnode->getFirstBlock();
								break;
							case 'leaf':
								$snippet['leaf'] = $testnode->getFirstBlock();
								break;
						}
					}
				}
			}
			else
			{
				$this -> compiler -> addConverterItem($snippetName, $this->sections[$this->nesting]['name']);
			}
			if(!isset($snippet['opening']) || !isset($snippet['closing']) || !isset($snippet['leaf']))
			{
				$this -> tpl -> error(E_USER_WARNING, 'One of the snippets for the "tree" instruction
					has not been defined.', OPT_W_SNIPPETS_NOT_DEF);
			}



			$this -> sectionList[$this -> nesting] = $name = $this->sections[$this->nesting]['name'];
			$code = '';
			if($this->sections[$this->nesting]['order'] == 'reversed')
			{
				$code = ' for($__'.$name.'_id = $__'.$name.'_cnt - 1; $__'.$name.'_id >= 0; $__'.$name.'_id--){ $__'.$name.'_val = &'.$this->sections[$this->nesting]['link'].'[$__'.$name.'_id]; ';		
			}			
			else
			{
				$code = ' for($__'.$name.'_id = 0; $__'.$name.'_id < $__'.$name.'_cnt; $__'.$name.'_id++){ $__'.$name.'_val = &'.$this->sections[$this->nesting]['link'].'[$__'.$name.'_id]; ';
			}

			$code .= '$a = $__'.$name.'_val[\'depth\'];
$b = @'.$this->sections[$this->nesting]['link'].'[$__'.$name.'_id+1][\'depth\'];
if($a == $b){ $state = 0; }
if($a < $b){ $state = 1; }
if($a > $b){ $state = 2; $toclose = $a - $b; }
switch($state){
case 0:
';
			$this -> compiler -> out($code);
			$this -> defaultTreeProcess($snippet['leaf']);
			$this -> compiler -> out(' break; case 1: ');
			$this -> defaultTreeProcess($snippet['opening']);
			$this -> compiler -> out(' break; case 2: ');
			$this -> defaultTreeProcess($snippet['leaf']);
			$this -> compiler -> out(' for($__'.$name.'_i = 0; $__'.$name.'_i < $toclose; $__'.$name.'_i++){ ');
			$this -> defaultTreeProcess($snippet['closing']);
			$this -> compiler -> out(' } break; } }');
		
			$this -> nesting++;
			$this -> compiler -> removeConverterItem($snippetName);
		} // end treeBegin();

		public function treeElse()
		{
			if($this->sections[$this->nesting-1]['show'] == false)
			{
				$this->sections[$this->nesting-1]['else'] = true;
				$this -> compiler -> out(' } else { ');		
			}
		} // end sectionElse();

		public function treeEnd()
		{
			$this -> nesting--;
			if($this->sections[$this->nesting]['show'] == false)
			{
				$this -> compiler -> out(' } ');
				unset($this -> sectionList[$this -> nesting]);
				unset($this -> sections[$this -> nesting]);
			}
		} // end treeEnd();

		private function cycle($paramStr)
		{
			$params = array(
				'type' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
				'__UNKNOWN__' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION)
			);
			$vars = $this -> compiler -> parametrize('section cycle', $paramStr, $params);
			if(sizeof($vars) > 1)
			{
				$code = '$__'.$this->sections[$this->nesting-1]['name'].'_cycle = array(0 => ';
				foreach($vars as $var)
				{
					$code .= $var.', ';
				}
				$code .= '); $__'.$this->sections[$this->nesting-1]['name'].'_cc = '.sizeof($vars).'; ';
				$this -> compiler -> out($code);
				$this -> sections[$this -> nesting-1]['cycle'] = $params['type'];
			}
			else
			{
				$this -> tpl -> error(E_USER_WARNING, 'The cycle should have at least two values.', OPT_W_SHORT_CYCLE);
			}
		} // end cycle();

		private function getLink($name, $datasource, &$link)
		{
			if($this -> compiler -> tpl -> sectionStructure == OPT_SECTION_MULTI)
			{
				if(is_null($datasource))
				{
					$syntax = OPT_SECTION_MULTI;
					if($this -> nesting == 0)
					{
						$link = '$this -> data[\''.$name.'\']'; 
					}
					else
					{
						$link = '$this -> data[\''.$name.'\']';
						foreach($this -> sections as $item)
						{
							$link .= '[$__'.$item['name'].'_id]';
						}
					}
				}
				else
				{
					$syntax = OPT_SECTION_SINGLE;
					$link = $datasource;
				}
			}
			else
			{
				if(is_null($datasource))
				{
					$syntax = OPT_SECTION_SINGLE;
					if($this -> nesting == 0)
					{
						$link = '$this -> data[\''.$name.'\']'; 
					}
					else
					{
						$link = '$__'.$this->sections[$this->nesting-1]['name'].'_val[\''.$name.'\']';
					}
				}
				else
				{
					$syntax = OPT_SECTION_MULTI;
					$link = $datasource;
					if($this -> nesting > 0)
					{
						foreach($this -> sections as $item)
						{
							$link .= '[$__'.$item['name'].'_id]';
						}
					}
				}
			}
			return $syntax;
		} // end getLink();

		public function processOpt($namespace)
		{
			switch($namespace[3])
			{
				case 'count':
					return '$__'.$namespace[2].'_cnt';
				case 'id':
					return '$__'.$namespace[2].'_id';
				case 'size':
					return 'sizeof($__'.$namespace[2].'_val)';
				case 'first':
					foreach($this -> sections as $id => &$void)
					{
						if($void['name'] == $namespace[2]);
						$sid = $id;
					}
					if($this -> sections[$sid]['order']=='reversed')
					{
						return '($__'.$namespace[2].'_id == $__'.$namespace[2].'_cnt - 1)';
					}
					return '($__'.$namespace[2].'_id == 0)';					
				case 'last':
					foreach($this -> sections as $id => &$void)
					{
						if($void['name'] == $namespace[2]);
						$sid = $id;
					}
					if($this -> sections[$sid]['order']=='reversed')
					{
						return '($__'.$namespace[2].'_id == 0)';
					}					
					return '($__'.$namespace[2].'_id == $__'.$namespace[2].'_cnt - 1)';
				case 'far':
					foreach($this -> sections as $id => &$void)
					{
						if($void['name'] == $namespace[2]);
						$sid = $id;
					}
					return '(($__'.$namespace[2].'_id == $__'.$namespace[2].'_cnt - 1) || ($__'.$namespace[2].'_id == 0))';
				default:
					$this -> tpl -> error(E_USER_ERROR, 'Unknown OPT section command: '.$namespace[3], 105);
			}
		} // end processOpt();

		public function processAttribute(optBlock $block)
		{
			switch($block -> getName())
			{
				case 'sectionfirst':
					foreach($this -> sections as $id => &$void)
					{
						if($void['name'] == $block->getAttributes());
						$sid = $id;
					}
					if($this -> sections[$sid]['order']=='reversed')
					{
						$this->compiler->out(' if($__'.$block->getAttributes().'_id == $__'.$block->getAttributes().'_cnt - 1){ echo \'class="first"\'; } ');
						break;
					}
					$this->compiler->out('if($__'.$block->getAttributes().'_id == 0){ echo \'class="first"\'; } ');
					break;
				case 'sectionlast':
					foreach($this -> sections as $id => &$void)
					{
						if($void['name'] == $block->getAttributes());
						$sid = $id;
					}
					if($this -> sections[$sid]['order']=='reversed')
					{
						$this->compiler->out('if($__'.$block->getAttributes().'_id == 0){ echo \'class="last"\'; } ');

						break;
					}
					$this->compiler->out(' if($__'.$block->getAttributes().'_id == $__'.$block->getAttributes().'_cnt - 1){ echo \'class="last"\'; } ');
					break;
				case 'sectioncycle':
					$sid = 0;
					foreach($this -> sections as $id => &$void)
					{
						if(!isset($void['name']))
						{
							continue;
						}
						if($void['name'] == $block->getAttributes())
						{
							$sid = $id;
						}
					}
					if(isset($this->sections[$sid]['cycle']))
					{
						$this -> compiler -> out(' echo '.$this->sections[$sid]['cycle'].'.\'="\'.($__'.$this->sections[$sid]['name'].'_cycle[$__'.$this->sections[$sid]['name'].'_id % $__'.$this->sections[$sid]['name'].'_cc]).\'"\'; ');
					}
					break;			
			}
		} // end processAttribute();
	}

	class optPagesystem extends optInstruction
	{
		public function configure()
		{
			return array(
				0 => 'pagesystem',
				'pagesystem' => OPT_MASTER,
				'/pagesystem' => OPT_ENDER
				);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'pagesystem':
							$this -> psBegin($block);
							$this -> defaultTreeProcess($block);
							break;
					case '/pagesystem':
							$this -> psEnd();
							break;
				}
			}	
		} // end process();

		private function psBegin($block)
		{
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
				'npDisplay' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, '1'),
				'flDisplay' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, '1')
			);
			$snippetName = '';
			$snippet = $this -> findSnippets($block, $snippetName, true);
			if(is_null($snippet))
			{
				$snippet = array();
				foreach($block as $testnode)
				{
					if($testnode -> getType() == OPT_UNKNOWN)
					{
						switch($testnode -> getName())
						{
							case 'page':
							case 'active':
							case 'separator':
							case 'first':
							case 'last':
							case 'prev':
							case 'next':
								$snippet[$testnode->getName()] = $testnode->getFirstBlock();
								break;
						}
					}
				}
			}

			$this -> compiler -> parametrize('pagesystem', $block -> getAttributes(), $params);
			$link = '$this->data[\''.$params['name'].'\']';
			$code = 'if('.$link.' instanceof ioptPagesystem){';

			$stdLink = '$this->vars[\'url\'] = $_p'.$params['name'].'[\'l\']; $this->vars[\'title\'] = $_p'.$params['name'].'[\'p\']; ';

			if(isset($snippet['first']))
			{
				$code .= ' if('.$params['flDisplay'].'){ if($_p'.$params['name'].' = '.$link.'->firstPage()){ '.$stdLink.' ';
				$this -> compiler -> out($code);
				$this -> defaultTreeProcess($snippet['first']);
				$code = ' } } ';
			}
			if(isset($snippet['prev']))
			{
				$code .= ' if('.$params['npDisplay'].'){ if($_p'.$params['name'].' = '.$link.'->prevPage()){ '.$stdLink.' ';
				$this -> compiler -> out($code);
				$this -> defaultTreeProcess($snippet['prev']);
				$code = ' } } ';
			}

			$code .= 'while($_p'.$params['name'].' = '.$link.'->getPage()){ '.$stdLink.' switch($_p'.$params['name'].'[\'t\']){ case 0: ';

			$this -> compiler -> out($code);
			$this -> defaultTreeProcess($snippet['page']);

			$this -> compiler -> out(' break; case 1: ');
			$this -> defaultTreeProcess($snippet['active']);

			
			$this -> compiler -> out(' break; case 2: ');
			$this -> defaultTreeProcess($snippet['separator']);

			$code = ' } } ';

			if(isset($snippet['next']))
			{
				$code .= ' if('.$params['npDisplay'].'){ if($_p'.$params['name'].' = '.$link.'->nextPage()){ '.$stdLink.' ';
				$this -> compiler -> out($code);
				$this -> defaultTreeProcess($snippet['next']);
				$code = ' } } ';
			}
			if(isset($snippet['last']))
			{
				$code .= ' if('.$params['flDisplay'].'){ if($_p'.$params['name'].' = '.$link.'->lastPage()){ '.$stdLink.' ';
				$this -> compiler -> out($code);
				$this -> defaultTreeProcess($snippet['last']);
				$code = ' } } ';
			}

			$code .= ' } ';
			$this -> compiler -> out($code);
		} // end psBegin();

		private function psEnd()
		{

		} // end psEnd();

	} // end optPagesystem;

	class optInclude extends optInstruction
	{
		public function configure()
		{
			return array(
				// processor name
				0 => 'include',
				// instructions
				'include' => OPT_COMMAND
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			$block = $node -> getFirstBlock();
			$params = array(
				'file' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
				'default' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
				'assign' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_ID, NULL),
				'__UNKNOWN__' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
			);
			$variables = $this -> compiler -> parametrize('include', $block -> getAttributes(), $params);

			$code = '';
			foreach($variables as $name => $variable)
			{
				$code .= ' $this -> vars[\''.$name.'\'] = '.$variable.'; ';		
			}
			if($params['assign'] != NULL)
			{
				$code .= ' ob_start(); ';
			}
			if($params['default'] != NULL)
			{
				$code .= ' if(!$this -> doInclude('.$params['file'].', true)){ $this -> doInclude('.$params['default'].'); } ';
			}
			else
			{
				$code .= '$this -> doInclude('.$params['file'].'); ';
			}
			if($params['assign'] != NULL)
			{
				$code .= '$this->vars[\''.$params['assign'].'\'] = ob_get_clean(); ';
			}
			$this -> compiler -> out($code);
		} // end instructionNodeProcess();
	}

	class optPlace extends optInstruction
	{
		public function configure()
		{
			return array(
				// processor name
				0 => 'place',
				// instructions
				'place' => OPT_COMMAND
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			$block = $node -> getFirstBlock();
			$params = array(
				'file' => array(OPT_PARAM_REQUIRED, OPT_PARAM_STRING),
				'assign' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_ID, NULL),
			);
			$this -> compiler -> parametrize('place', $block->getAttributes(), $params);
	
			$file = '';
			if($params['assign'] != NULL)
			{	
				$this -> compiler -> out(' ob_start(); ');
			}
			$this -> compiler -> out($this -> compiler -> tpl -> getTemplate($params['file']), true);
			if($params['assign'] != NULL)
			{
				$this -> compiler -> out(' $this -> vars[\''.$params['assign'].'\'] .= ob_end_flush(); ');
			}
		} // end instructionNodeProcess();
	}
	
	class optVar extends optInstruction
	{
		public function configure()
		{
			return array(
				// processor name
				0 => 'var',
				// instructions
				'var' => OPT_COMMAND
			);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			$block = $node -> getFirstBlock();
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
				'value' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION)
			);
			$this -> compiler  -> parametrize('var', $block -> getAttributes(), $params);
	
			$this -> compiler -> out('$this -> vars[\''.$params['name'].'\'] = '.$params['value'].'; ');
		} // end instructionNodeProcess();
	}
	
	class optDefault extends optInstruction
	{
		public function configure()
		{
			return array(
				// processor name
				0 => 'default',
				// instructions
				'default' => OPT_COMMAND
			);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			$block = $node -> getFirstBlock();
			$params = array(
				'test' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
				'alt' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
			);
			$this -> compiler -> parametrize('default', $block -> getAttributes(), $params);
			
			$this -> compiler -> out(' echo (!empty('.$params['test'].') ? '.$params['test'].' : '.$params['alt'].'); ');
		} // end process();
	}

	class optIf extends optInstruction
	{
		private $nesting = 0;
	
		public function configure()
		{
			return array(
				// processor name
				0 => 'if',
				// instructions
				'if' => OPT_MASTER,
				'elseif' => OPT_ALT,
				'else' => OPT_ALT,
				'/if' => OPT_ENDER
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'if':
							$this -> ifBegin($block -> getAttributes());
							$this -> defaultTreeProcess($block);
							break;
					case 'elseif':
							$this -> ifElseif($block -> getAttributes());
							$this -> defaultTreeProcess($block);
							break;
					case 'else':
							$this -> ifElse($block -> getAttributes());
							$this -> defaultTreeProcess($block);
							break;
						
					case '/if':
							$this -> ifEnd();
							break;
				}			
			}		
		} // end process();
		
		private function ifBegin($group)
		{	 	
			if($this -> compiler -> tpl->xmlsyntaxMode == 1 || $this -> compiler -> tpl -> strictSyntax == 1)
			{
				$params = array(
					'test' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION)
				);
				$this -> compiler -> parametrize('if', $group, $params);
				$this -> compiler -> out(' if('.$params['test'].'){ ');
			}
			else
			{
				$this -> compiler -> out('; if('.$this -> compiler -> compileExpression($group[4]).'){ ');
			}
		} // end ifBegin();
		
		private function ifElseif($group)
		{
			if($this -> compiler->tpl->xmlsyntaxMode == 1 || $this -> compiler->tpl->strictSyntax == 1)
			{
				$params = array(
					'test' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION)
				);
				$this -> compiler -> parametrize('elseif', $group, $params);
				$this -> compiler -> out('; }elseif('.$params['test'].'){ ');
			}
			else
			{
				$this -> compiler -> out(' }elseif('.$this -> compiler -> compileExpression($group[4]).'){ ');
			}
		} // end ifElseif();
		
		private function ifElse($group)
		{
	 		$this -> compiler -> out(' }else{ ');
		} // end ifElse();
		
		private function ifEnd()
		{
	 		$this -> compiler -> out(' } ');
		} // end ifEnd();
	}
	
	class optCapture extends optInstruction
	{
		private $nesting = 0;
		private $oldMaster = 0;
		private $names = array();
	
		public function configure()
		{
			return array(
				// processor name
				0 => 'capture',
				// instructions
				'capture' => OPT_MASTER,
				'/capture' => OPT_ENDER
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'capture':
							$this -> captureBegin($block -> getAttributes());
							$this -> defaultTreeProcess($block);
							break;
					case '/capture':
							$this -> captureEnd();
							break;
				}
			}
		} // end process();
		
		private function captureBegin($group)
		{
			$params = array(
				'to' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID)
			);
			$this -> compiler -> parametrize('capture', $group, $params);
			$this -> names[$this->nesting] = $params['to'];
			$this -> compiler -> out(' ob_start(); ');
			$this -> nesting++;
		} // end captureBegin();
		
		private function captureEnd()
		{
			$this -> nesting--;
			$this -> compiler -> out(' $this -> capture[\''.$this->names[$this->nesting].'\'] = ob_get_clean(); ');
		} // end captureEnd();

		public function processOpt($namespace)
		{
			return '$this -> capture[\''.$namespace[2].'\']';
		} // end processOpt();
	}
	
	class optFor extends optInstruction
	{
		protected $nesting = 0;
		
		public function configure()
		{
			return array(
				// processor name
				0 => 'for',
				// instructions
				'for' => OPT_MASTER,
				'/for' => OPT_ENDER
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'for':
							$this -> forBegin($block);
							$this -> defaultTreeProcess($block);
							break;
					case '/for':
							$this -> forEnd();
							break;
				}
			}
		} // end process();
		
		private function forBegin($block)
		{
			$params = array(
				'begin' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ASSIGN_EXPR),
				'end' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ASSIGN_EXPR),
				'iterate' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ASSIGN_EXPR),
				'separator' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
			);
			$this -> compiler -> parametrize('for', $block->getAttributes(), $params);
			$separatorNode = $block -> getElementByTagName('separator');
			$start = false;
			if(is_object($separatorNode))
			{
				$start = true;
			}
			elseif(!is_null($params['separator']))
			{
				$start = true;
			}
			
	 		$this -> compiler -> out(' '.($start ? '$__for_'.$this->nesting.'=0; ' : '').' for('.$params['begin'].'; '.$params['end'].'; '.$params['iterate'].'){ ');
			if(is_object($separatorNode))
			{
				$this -> compiler -> out(' if($__for_'.$this->nesting.' != 0){ ');
				$this -> defaultTreeProcess($separatorNode->getFirstBlock());
				$this -> compiler -> out(' } $__for_'.$this->nesting.' = 1;');
			}
			elseif(!is_null($params['separator']))
			{
				$this -> compiler -> out(' if($__for_'.$this->nesting.' != 0){ echo '.$params['separator'].'; } $__for_'.$this->nesting.' = 1;');
			}
			$this -> nesting++;
		} // end forBegin();
		
		private function forEnd()
		{
	 		$this -> compiler -> out(' } ');
	 		$this -> nesting--;
		} // end forEnd();
	}
	
	class optForeach extends optInstruction
	{
		private $nesting = 0;
	
		public function configure()
		{
			return array(
				// processor name
				0 => 'foreach',
				// instructions
				'foreach' => OPT_MASTER,
				'foreachelse' => OPT_ALT,
				'/foreach' => OPT_ENDER
			);
			$this -> nesting = 0;
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'foreach':
							$this -> foreachBegin($block);
							$this -> defaultTreeProcess($block);
							break;
					case 'foreachelse':
							$this -> foreachElse();
							$this -> defaultTreeProcess($block);
							break;
					case '/foreach':
							$this -> foreachEnd();
							break;				
				}			
			}		
		} // end process();

		private function foreachBegin($block)
		{
			$params = array(
				'table' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
				'index' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
				'value' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_ID, NULL),
				'separator' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
			);
			$this -> compiler -> parametrize('foreach', $block -> getAttributes(), $params);
			$separatorNode = $block -> getElementByTagName('separator');
			$start = false;
			if(is_object($separatorNode))
			{
				$start = true;
			}
			elseif(!is_null($params['separator']))
			{
				$start = true;
			}

			if($params['value'] == NULL)
			{
				$this -> compiler -> out(' if(sizeof('.$params['table'].') > 0){ '.($start ? '$__foreach_'.$this->nesting.'=0; ' : '').' foreach('.$params['table'].' as $__f_'.$this -> nesting.'_val){ $this -> vars[\''.$params['index'].'\'] = &$__f_'.$this -> nesting.'_val; ');
			}
			else
			{
				$this -> compiler -> out(' if(sizeof('.$params['table'].') > 0){ '.($start ? '$__foreach_'.$this->nesting.'=0; ' : '').' foreach('.$params['table'].' as $__f_'.$this -> nesting.'_id => $__f_'.$this -> nesting.'_val){ $this -> vars[\''.$params['index'].'\'] = $__f_'.$this -> nesting.'_id; $this -> vars[\''.$params['value'].'\'] = &$__f_'.$this -> nesting.'_val; ');
			}
			if(is_object($separatorNode))
			{
				$this -> compiler -> out(' if($__foreach_'.$this->nesting.' != 0){ ');
				$this -> defaultTreeProcess($separatorNode->getFirstBlock());
				$this -> compiler -> out(' } $__foreach_'.$this->nesting.' = 1;');
			}
			elseif(!is_null($params['separator']))
			{
				$this -> compiler -> out(' if($__foreach_'.$this->nesting.' != 0){ echo '.$params['separator'].'; } $__foreach_'.$this->nesting.' = 1;');
			}
			$this -> nesting++;
		} // end foreachBegin();

		private function foreachElse()
		{
		 	$this -> compiler -> out(' } }else{ { ');		
		} // end foreachElse();

		private function foreachEnd()
		{
 			$this -> compiler -> out(' } } ');
 			$this -> nesting--;
		} // end foreachEnd();
	}

	class optDynamic extends optInstruction
	{
		public $active = 0;
	
		public function configure()
		{
			return array(
				// processor name
				0 => 'dynamic',
				// instructions
				'dynamic' => OPT_MASTER,
				'/dynamic' => OPT_ENDER
			);
		} // end configure();
		
		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'dynamic':
							$this -> dynamicBegin($block->getAttributes());
							$this -> defaultTreeProcess($block);
							break;
					case '/dynamic':
							$this -> dynamicEnd();
							break;
				}
			}
		} // end process();
		
		private function dynamicBegin($group)
		{
			if($this -> compiler -> tpl -> getStatus() == false)
			{
				return '';
			}
		
			if($this -> active == 0)
			{
				$this -> active = 1;
				$this -> compiler -> dynamic(true);
			}
			else
			{
				$this -> compiler -> tpl -> error(E_USER_WARNING, 'Dynamic section already opened.', OPT_W_DYNAMIC_OPENED);
			}
		} // end dynamicBegin();
		
		private function dynamicEnd()
		{
			if($this -> compiler -> tpl -> getStatus() == false)
			{
				return '';
			}
	
			if($this -> active == 1)
			{
				$this -> active = 0;
				$this -> compiler -> dynamic(false);
			}
			else
			{
				$this -> compiler -> tpl -> error(E_USER_WARNING, 'Dynamic section already closed.', OPT_W_DYNAMIC_CLOSED);
			}
		} // end dynamicEnd();
	}
	
	class optBind extends optInstruction
	{
		public $inMaster = true;
		public $buffer;
	
		public function configure()
		{
			$this -> compiler -> genericBuffer['bind'] = array();
			return array(
				// processor name
				0 => 'bind',
				// instructions
				'bind' => OPT_MASTER,
				'/bind' => OPT_ENDER
			);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'bind':
							$this -> buffer[$this->getName($block->getAttributes())] = $block;
							break;
				}
			}
		} // end instructionNodeProcess();
		
		public function getName($attributes)
		{
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID)
			);
			$this -> compiler -> parametrize('bind', $attributes, $params);
			return $params['name'];
		} // end getName();
	} // end optBind;
	
	class optInsert extends optInstruction
	{
		public function configure()
		{
			return array(
				// processor name
				0 => 'insert',
				// instructions
				'insert' => OPT_COMMAND
			);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			$block = $node -> getFirstBlock();
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID)
			);
			$this -> compiler -> parametrize('insert', $block -> getAttributes(), $params);
			
			if(isset($this -> compiler -> processors['bind'] -> buffer[$params['name']]))
			{
				$this -> defaultTreeProcess($this -> compiler -> processors['bind'] -> buffer[$params['name']]);
			}
			else
			{
				$this -> compiler -> tpl -> error(E_USER_ERROR, 'Unknown bind identifier: "'.$params['name'].'".', OPT_E_BIND_NOT_FOUND);
			}
		} // end instructionNodeProcess();
	} // end optInsert;

	class optBindEvent extends optInstruction
	{
		public $inMaster = true;
	
		public function configure()
		{
			$this -> compiler -> genericBuffer['bindEvent'] = array();
			return array(
				// processor name
				0 => 'bindEvent',
				// instructions
				'bindEvent' => OPT_MASTER,
				'/bindEvent' => OPT_ENDER
			);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'bindEvent':
						$params = array(
							'id' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
							'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
							'message' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
							'position' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_ID, 0)
						);
						$this -> compiler -> parametrize('bindEvent', $block -> getAttributes(), $params);
						$this -> compiler -> genericBuffer['bindEvent'][$params['id']] = array(
							'name' => $params['name'],
							'message' => $params['message'],
							'position' => $params['position'],
							'tree' => $node						
						);
						break;
				}
			}
		} // end instructionNodeProcess();
	} // end optBindEvent;
	
	class optBindGroup extends optInstruction
	{
		public $inMaster = true;
	
		public function configure()
		{
			$this -> compiler -> genericBuffer['bindGroup'] = array();
			return array(
				// processor name
				0 => 'bindGroup',
				// instructions
				'bindGroup' => OPT_MASTER,
				'/bindGroup' => OPT_ENDER
			);
		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block -> getName())
				{
					case 'bindGroup':
							$this -> doBind($this->getName($block->getAttributes()), $block);
							break;
				}
			}
		} // end instructionNodeProcess();

		public function doBind($name, $block)
		{
			$this -> compiler -> genericBuffer['bindGroup'][$name] = array();
			foreach($block as $node)
			{
				if($node -> getType() == OPT_INSTRUCTION || $node -> getType() == OPT_UNKNOWN)
				{
					$this -> compiler -> genericBuffer['bindGroup'][$name][$node->getName()] = $node->getFirstBlock();		
				}
			}
		} // end doBind();

		public function getName($attributes)
		{
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID)
			);
			$this -> compiler -> parametrize('bindGroup', $attributes, $params);
			return $params['name'];
		} // end getName();
	} // end optBindGroup;
	
	# COMPONENTS
	class optComponent extends optInstruction
	{
		public function configure()
		{

		} // end configure();

		public function instructionNodeProcess(ioptNode $node)
		{
			static $cid;
			if($cid == NULL)
			{
				$cid = 0;
			}

			// we always use the first block in this case
			$block = $node -> getFirstBlock();
			
			$condBegin = 0;
			$componentLink = '';

			// do we have an undefined component?
			if($block -> getName() == 'component')
			{				
				$params = array(
					'id' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
					'datasource' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
					'__UNKNOWN__' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
				);
				$args = $this -> compiler -> parametrize($node -> getName(), $block->getAttributes(), $params);
				$code =' if('.$params['id'].' instanceof ioptComponent){ ';
				$componentLink = $params['id'];
				$condBegin = 1;
			}
			else
			{
				$params = array(
					'datasource' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL),
					'__UNKNOWN__' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, NULL)
				);
				$args = $this -> compiler -> parametrize($block -> getName(), $block->getAttributes(), $params);
				if(isset($args['name']))
				{
					$code = ' $__component_'.$cid.' = new '.$block -> getName().'('.$args['name'].'); ';
				}
				else
				{
					$code = ' $__component_'.$cid.' = new '.$block -> getName().'(); ';
				}
				if($params['datasource'] != NULL)
				{
						$code .= ' $__component_'.$cid.' -> setDatasource('.$params['datasource'].'); ';
				}
				$componentLink = '$__component_'.$cid;	
			}
			$code .= $componentLink.' -> setOptInstance($this); ';

			foreach($args as $name => $value)
			{
				$code .= $componentLink.' -> set(\''.$name.'\', '.$value.'); ';
			}

			// let's see, what do we have inside the block
			
			// event table
			$events = array(0 => array(), array(), array());
			
			foreach($block as $node)
			{
				switch($node -> getName())
				{
					case 'param':
						// parameters
						$params = array(
							'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
							'value' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION)
						);
						$this -> compiler -> parametrize('component parameter', $node -> getFirstBlock()->getAttributes(), $params);
						$code .= $componentLink.' -> set(\''.$params['name'].'\', '.$params['value'].'); ';
						break;
					case 'listItem':
						// list items
						$params = array(
							'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
							'value' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
							'selected' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_EXPRESSION, 0)
						);
						$this -> compiler -> parametrize('component list element', $node -> getFirstBlock()->getAttributes(), $params);
						$code .= $componentLink.' -> push(\''.$params['name'].'\', '.$params['value'].', '.$params['selected'].'); ';
						break;
					case 'load':
						$params = array('event' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID));
						$this -> compiler -> parametrize('event loader', $node->getFirstBlock()->getAttributes(), $params);
						if(isset($this -> compiler -> genericBuffer['bindEvent'][$params['event']]))
						{
							$info = $this -> compiler -> genericBuffer['bindEvent'][$params['event']];
							switch($info['position'])
							{
								case 'up':
									$events[0][$info['name']] = array(0 => $info['tree'], $info['message']);
									break;
								case 'mid':
									$events[1][$info['name']] = array(0 => $info['tree'], $info['message']);
									break;
								case 'down':
								default:
									$events[2][$info['name']] = array(0 => $info['tree'], $info['message']);
									break;
							}
						}
						break;
					default:
						if($node -> getType() == OPT_UNKNOWN)
						{
							// events
							$params = array(
								'message' => array(OPT_PARAM_REQUIRED, OPT_PARAM_ID),
								'position' => array(OPT_PARAM_OPTIONAL, OPT_PARAM_ID, 0)
							);
							$this -> compiler -> parametrize('component event', $node->getFirstBlock()->getAttributes(), $params);
							switch($params['position'])
							{
								case 'up':
									$events[0][$node -> getName()] = array(0 => $node, $params['message']);
									break;
								case 'mid':
									$events[1][$node -> getName()] = array(0 => $node, $params['message']);
									break;
								case 'down':
								default:
									$events[2][$node -> getName()] = array(0 => $node, $params['message']);
									break;
							}
						}
				}
			}
			$this -> compiler -> out($code);
			// ok, now we put the events in the correct order
			foreach($events[0] as $name => $nodeData)
			{
				$this -> compileEvent($name, $componentLink, $nodeData);
			}
			$this -> compiler -> out(' '.$componentLink.' -> begin(); ');
			foreach($events[1] as $name => $nodeData)
			{
				$this -> compileEvent($name, $componentLink, $nodeData);
			}
			$this -> compiler -> out(' '.$componentLink.' -> end(); ');
			foreach($events[2] as $name => $nodeData)
			{
				$this -> compileEvent($name, $componentLink, $nodeData);
			}

			// terminate the processing
			if($condBegin == 1)
			{
				$this -> compiler -> out(' } ');		
			}
		} // end instructionNodeProcess();
		
		private function compileEvent($name, $componentId, $eventNode)
		{
			$this -> compiler -> out(' if('.$componentId.' -> '.$name.'(\''.$eventNode[1].'\')) { ');
			foreach($eventNode[0] as $block)
			{
				$this -> defaultTreeProcess($block);
			}
			$this -> compiler -> out(' } ');
		} // end compileEvent();

	}
	# /COMPONENTS
?>
