<?php
	// parameter flags
	define('OPT_PARAM_REQUIRED', 0);
	define('OPT_PARAM_OPTIONAL', 1);
	
	// parameter styles
	define('OPT_STYLE_BOTH', 0);
	define('OPT_STYLE_OPT', 1);
	define('OPT_STYLE_XML', 2);
	// parameter types
	define('OPT_PARAM_ID', 2);
	define('OPT_PARAM_EXPRESSION', 3);
	define('OPT_PARAM_ASSIGN_EXPR', 4);
	define('OPT_PARAM_STRING', 5);
	define('OPT_PARAM_NUMBER', 6);
	define('OPT_PARAM_VARIABLE', 7);

	define('OPT_ROOT', 0);
	define('OPT_TEXT', 1);
	define('OPT_INSTRUCTION', 2);
	define('OPT_EXPRESSION', 3);
	define('OPT_COMPONENT', 4);
	define('OPT_UNKNOWN', 5);

	define('OPT_MASTER', 0);
	define('OPT_ALT', 1);
	define('OPT_ENDER', 2);
	define('OPT_COMMAND', 3);
	define('OPT_ATTRIBUTE', 6);
	
	define('OPCODE_NULL', -1);
	define('OPCODE_STRING', 0);
	define('OPCODE_NUMBER', 1);
	define('OPCODE_LANGUAGE', 2);
	define('OPCODE_VARIABLE', 3);
	define('OPCODE_CONFIG', 4);
	define('OPCODE_PARENTHESIS', 5);
	define('OPCODE_FUNCTION', 6);
	define('OPCODE_METHOD', 7);
	define('OPCODE_OPERATOR', 8);
	define('OPCODE_OBJECT_CALL', 9);
	define('OPCODE_IDENTIFIER', 10);
	define('OPCODE_SEPARATOR', 11);
	define('OPCODE_ASSIGN', 12);
	define('OPCODE_APPLY', 13);
	define('OPCODE_EXPRESSION', 14);
	define('OPCODE_BRACKET', 15);
	
	interface ioptNode
	{
		public function __construct($name, $type, $parent);
		public function getName();
		public function getType();
		public function getBlockCount();	
	}
	
	class optNode implements ioptNode, IteratorAggregate
	{
		private $name;
		private $type;
		private $blocks = array();
		private $parent;
		
		private $storedBlock;
		
		public function __construct($name, $type, $parent)
		{
			$this -> name = $name;
			$this -> type = $type;
			$this -> parent = $parent;
		} // end __construct();

		public function addItem($item, $tpl = NULL)
		{
			if(is_null($tpl))
			{
				$this -> blocks[] = $item;			
			}
			else
			{
				if(strpos($item -> getName(), $this -> name) !== false)
				{
					$this -> blocks[] = $item;
				}
				else
				{
					$tpl -> error(E_USER_ERROR, 'Unexpected enclosing statement: "'.$item->getName().'".', OPT_E_ENCLOSING_STATEMENT);
				}
			}
		} // end addBlock();
		
		public function getName()
		{
			return $this -> name;
		} // end getName();
	
		public function getType()
		{
			return $this -> type;
		} // end getType();
		
		public function getParent()
		{
			return $this -> parent;
		} // end getParent();

		public function getBlockCount()
		{
			return count($this -> blocks);
		} // end getBlockCount();
		
		public function getFirstBlock()
		{
			return $this -> blocks[0];
		} // end getFirstBlock();
		
		public function storeBlock(optBlock $block)
		{
			$this -> storedBlock = $block;
		} // end storeBlock();
		
		public function restoreBlock()
		{
			return $this -> storedBlock;
		} // end restoreBlock();
		
		public function getIterator()
		{
			return new ArrayIterator($this -> blocks);		
		} // end getIterator();

		public function __toString()
		{
			return $this -> type.':'.$this -> name;
		} // end __toString();
	}
	
	class optTextNode implements ioptNode
	{
		private $name;
		private $type;
		private $text;
		private $parent;
		
		public function __construct($name, $type, $parent)
		{
			$this -> name = $name;
			$this -> type = $type;
			$this -> parent = $parent;
			$this -> text = '';
		} // end __construct();
		
		public function addItem($item)
		{
			$this -> text .= $item;
		} // end addBlock();
		
		public function getName()
		{
			return $this -> name;
		} // end getName();
	
		public function getType()
		{
			return $this -> type;
		} // end getType();
		
		public function getParent()
		{
			return $this -> parent;
		} // end getParent();

		public function getBlockCount()
		{
			return 0;
		} // end getBlockCount();

		public function storeBlock(optBlock $block)
		{
			$this -> error(E_USER_ERROR, 'Unexpected "'.$this->getType().'"!', OPT_E_UNKNOWN);
		} // end storeBlock();
		
		public function restoreBlock()
		{
			$this -> error(E_USER_ERROR, 'Unexpected "'.$this->getType().'"!', OPT_E_UNKNOWN);
		} // end restoreBlock();

		public function __toString()
		{
			return str_replace(array(
					'&lb;',
					'&rb;'
				),
				array(
					'{',
					'}'
				), $this -> text
			);
		} // end __toString();
	}
	
	class optBlock implements IteratorAggregate
	{
		private $name;
		private $attributes;
		private $type;
		private $nodes = array();
		
		public function __construct($name, $attributes = NULL, $type = OPT_COMMAND)
		{
			$this -> name = $name;
			$this -> attributes = $attributes;
			$this -> type = $type;
		} // end __construct();
		
		public function addNode(ioptNode $node)
		{
			$this -> nodes[] = $node;		
		} // end addBlock();
		
		public function getName()
		{
			return $this -> name;
		} // end getName();
	
		public function hasAttributes()
		{
			return isset($this -> attributes[3]);
		} // end hasAttributes();
		
		public function getAttributes()
		{
			return $this -> attributes;
		} // end getAttributes();

		public function getType()
		{
			return $this -> type;
		} // end getAttributes();

		public function hasChildNodes()
		{
			return count($this -> nodes) > 0;
		} // end hasChildNodes();
		
		public function getElementByTagName($tagName)
		{
			foreach($this -> nodes as $node)
			{
				if($node -> getName() == $tagName)
				{
					return $node;
				}
			}
			return NULL;
		} // end getElementByTagName();
		
		public function getIterator()
		{
			return new ArrayIterator($this -> nodes);		
		} // end getIterator();

		public function __toString()
		{
			return $this -> name;
		} // end __toString();
	}

	// Instruction tree classes
	require_once(OPT_DIR.'opt.instructions.php');

	// Main compiler
	final class optCompiler
	{
		public $tpl;
		public $processors;
		public $mapper;
		public $translator;
		
		public $dynamic;		
		private $dynamicEnabled = false;
		private $di = 0;
		private $dynamicSeg = array(0 => '');
		private $output;
		public $master = false;
		private $converter;
		
		// EXPRESSION REGEX		
		private $rDoubleQuoteString = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';
		private $rReversedQuoteString = '`[^`\\\\]*(?:\\\\.[^`\\\\]*)*`';
		private $rSingleQuoteString = '\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'';
		private $rHexadecimalNumber = '\-?0[xX][0-9a-fA-F]+';
		private $rDecimalNumber = '[0-9]+\.?[0-9]*';
		private $rLanguageBlock = '\$[a-zA-Z0-9\_]+@[a-zA-Z0-9\_]+';
		private $rVariableBlock = '(\$|@)[a-zA-Z0-9\_\.]+';
		private $rOperators = '\-\>|!==|===|==|!=|\=\>|<>|<<|>>|<=|>=|\&\&|\|\||\(|\)|,|\!|\^|=|\&|\~|<|>|\||\%|\+\+|\-\-|\+|\-|\*|\/|\[|\]|\.|\:\:|';
		private $rIdentifier = '[a-zA-Z0-9\_]+';
		private $rConfiguration = '\#[a-zA-Z0-9\_]+';

		// Administrative methods
		public function __construct($tpl)
		{
			// Init the compiler
			if($tpl instanceof optCompiler)
			{
				$this -> tpl = $tpl -> tpl;
			}
			else
			{
				// let's say it's an instance of optClass or optApi
				$this -> tpl = $tpl;
			}

			// Load compiler files
			foreach($this -> tpl -> instructionFiles as $file)
			{
				require_once($file);
			}
			$this -> processors['generic'] = new optInstruction($this);
			# COMPONENTS
			$this -> processors['component'] = new optComponent($this);
			# /COMPONENTS
			// Translate the instructions
			$this -> translate($this -> tpl -> control);
		} // end __construct();

		public function translate($classList)
		{
			foreach($classList as $class)
			{
				$instruction = new $class($this);
				$data = $instruction -> configure();
				$this -> processors[$data[0]] = $instruction;
				
				foreach($data as $name => $type)
				{
					if(is_string($name))
					{
						$this -> translator[$name] = $type;
						$this -> mapper[$name] = $instruction;
					}
				}
			}
		} // end translate();

		// General compiler
		public function parse($filename, $code, $master = false)
		{
			static $regex;
			static $blockRegex;

			$this -> master = false;
			$this -> dynamic = false;
			$this -> dynamicEnabled = false;
			$this -> dynamicSeg = array(0 => '');
			$this -> di = 0;

			$pre = 'pre';
			if($master)
			{
				$this -> master = true;
				$pre = 'preMaster';
			}

			foreach($this -> tpl -> filters[$pre] as $name)
			{
				// @ used because of stupid notice
				// "Object of class optClass to string conversion".
				// Whatever it means, I couldn't recognize, why PHP does such things.
				$code = @$name($this -> tpl, $code);
			}

			$code = str_replace(array(
				'<'.'?'
			),array(
				'<?php echo \'<?\'; ?'.'>'
			), $code);

			if($regex == NULL || $this -> tpl -> nschange)
			{
				$nslist = implode('|', $this -> tpl -> namespaces);
				$blockRegex = '\{literal\}|\{\/literal\}|\{php\}|\{\/php\}';
				if($this -> tpl -> xmlsyntaxMode == 1)
				{
					$regex = '';
					$this -> tpl -> delimiters[] = '\<(\/?)('.$nslist.')\:(.*?)(\/?)\>';
					$this -> tpl -> delimiters[] = '\<()('.$nslist.')\:(.*?)(\/)\>';
					$this -> tpl -> delimiters[] = '('.$nslist.')\:put\=\"(.*?[^\\\\])\"';
					
					$blockRegex = '\<opt\:literal\>|\<\/opt\:literal\>|\<opt\:php\>|\<\/opt\:php\>|'.$blockRegex;
				}
				$regex = str_replace('$$NS$$', $nslist, implode('|', $this -> tpl -> delimiters));
			}
			$this -> output = '';			
			// initialize the tree
			$root = $current = new optNode(NULL, OPT_ROOT, NULL);
			$rootBlock = $currentBlock = new optBlock(NULL);
			$root -> addItem($rootBlock);
			$textAssign = 0;
			$commented = 0;
			$literal = false;
			$php = false;

			$textBlocks = preg_split('/('.$blockRegex.')/si', $code, 0, PREG_SPLIT_DELIM_CAPTURE);
			foreach($textBlocks as $bid => $bval)
			{				
				if(preg_match('/'.$blockRegex.'/si', $bval))
				{
					// Escape the OPT namespace, if in XML Syntax mode
					if($this -> tpl -> xmlsyntaxMode == 1)
					{
						$bval = str_replace('opt:', '', $bval);
					}
					// Check, what we have here
					if(strpos($bval, '/literal') !== false || $bval == ']]>')
					{
						$literal = false;
					}
					elseif(strpos($bval, 'literal') !== false || strpos($bval, 'CDATA') !== false)
					{
						if($textAssign == 0)
						{
							$text = new optTextNode(NULL, OPT_TEXT, $current);
							$currentBlock -> addNode($text);
						}
						$textAssign = 1;
						$literal = true;
					}
					elseif(strpos($bval, '/php') !== false)
					{
						$php = false;
						$text -> addItem('?'.'>');
					}
					elseif(strpos($bval, 'php') !== false)
					{
						$php = true;
						if($textAssign == 0)
						{
							$text = new optTextNode(NULL, OPT_TEXT, $current);
							$currentBlock -> addNode($text);
						}
						$textAssign = 1;
						$text -> addItem('<'.'?php');
					}
				}
				elseif($literal == true || $php == true)
				{
					// Static literal/php text
					$text -> addItem($bval);
				}
				elseif($literal == false && $php == false)
				{
					// tokenizer
					preg_match_all('#({\*.+?\*\}|'.$regex.')#si', $bval, $result, PREG_PATTERN_ORDER);
					$resolution = sizeof($result);
					$offset = 0;
					foreach($result[0] as $i => &$item)
					{
						// Copy static text			
						$id = strpos($bval, $item, $offset);
						$staticText = substr($bval, $offset, $id - $offset);

						if($textAssign == 0)
						{
							$text = new optTextNode(NULL, OPT_TEXT, $current);
							$currentBlock -> addNode($text);
						}
						$text -> addItem($staticText);
						$textAssign = 1;
						// Move to the end of the current token
						$offset = $id + strlen($item);
						// Process the token
						if(preg_match('/{\*.+?\*\}/s', trim($item)))
						{
							continue;
						}
						// an attribute
						if($result[8][$i] == ':')
						{
							// namespace checking
							$fakeAttribute = false;	

							if($result[7][$i] == 'opt')
							{
								$attribute = $result[9][$i];					
							}
							elseif(in_array($result[7][$i], $this->tpl->namespaces))
							{
								$attribute = $result[7][$i].':'.$result[9][$i];
							}
							else
							{
								$fakeAttribute = true;
							}
							if(!$fakeAttribute)
							{
								if(isset($this -> translator[$attribute]))
								{
									if($this -> translator[$attribute] == OPT_ATTRIBUTE)
									{
										$node = new optNode($attribute, OPT_ATTRIBUTE, $current);
										$node -> addItem(new optBlock($attribute, $result[10][$i]));
										$currentBlock -> addNode($node);
										$textAssign = 0;
									}
									else
									{
										$fakeAttribute = true;
									}
								}
								else
								{
									$fakeAttribute = true;
								}							
							}
							
							if($fakeAttribute)
							{
								// text item
								if($textAssign == 0)
								{
									$text = new optTextNode(NULL, OPT_TEXT, $current);
									$currentBlock -> addNode($text);
								}
								$text -> addItem($item);
								$textAssign = 1;
							}
							continue;
						}
						
						// a command
						$textAssign = 0;							
						$sortMatches = array(0 => NULL, 1 => NULL, 2 => NULL, 3 => NULL);
						$foundCommand = false;
						for($id = 1; $id < $resolution; $id++)
						{
							$val = trim($result[$id][$i]);
							if($val != '')
							{
								if($val[strlen($val)-1] == ':')
								{
									continue;
								}
								if($val == '/')
								{
									if(!$foundCommand)
									{
										$sortMatches[0] = '/';
									}
									else
									{
										$sortMatches[2] = '/';
									}
								}
								elseif($id != 1)
								{
									// Namespace support
									if(!is_null($sortMatches[1]))
									{
										if($sortMatches[1] == 'opt')
										{
											$sortMatches[1] = $val;
										}
										else
										{
											$sortMatches[3] = $sortMatches[1];
											$sortMatches[1] .= ':'.$val;
										}
									}
									else
									{
										$sortMatches[3] = 'opt';
										$sortMatches[1] = $val;
									}
									$foundCommand = true;
								}
							}
						}
						$sortMatches[1] = $this -> parseEntities($sortMatches[1]);
						if(preg_match('/^(([a-zA-Z0-9\_\:]+)([= \t]{1}(.*))?)$/s', $sortMatches[1], $found))
						{
							// we have an instruction
							$realname = $found[2];
							if($sortMatches[0] == '/')
							{					
								$found[2] = '/'.$found[2];
							}
							$found[6] = $item;
							// general instructions
							if(isset($this -> translator[$found[2]]))
							{
								switch($this -> translator[$found[2]])
								{
									case OPT_COMMAND:
										$node = new optNode($found[2], OPT_INSTRUCTION, $current);
										$node -> addItem(new optBlock($found[2], $found, OPT_COMMAND));
										$currentBlock -> addNode($node);
										break;
									case OPT_MASTER:
										$current -> storeBlock($currentBlock);
										$current = new optNode($found[2], OPT_INSTRUCTION, $current);
										$currentBlock -> addNode($current);
										$currentBlock = new optBlock($found[2], $found, OPT_MASTER);
										$current -> addItem($currentBlock, $this -> tpl);
										break;
									case OPT_ALT:
										$currentBlock = new optBlock($found[2], $found, OPT_ALT);
										$current -> addItem($currentBlock);
										break;
									case OPT_ENDER:
										$currentBlock = new optBlock($found[2], $found, OPT_ENDER);
										$current -> addItem($currentBlock, $this -> tpl);
										$current = $current -> getParent();
										if(!is_object($current))
										{
											$this -> tpl -> error(E_USER_ERROR, 'Unexpected enclosing statement: "'.$found[2].'".', OPT_E_ENCLOSING_STATEMENT);
										}
										$currentBlock = $current -> restoreBlock();
										break;	
								}
							}
							# COMPONENTS
							// components, and other shit
							elseif($realname == 'component' || isset($this -> tpl -> components[$realname]))
							{
								if($sortMatches[0] == '/')
								{
									$currentBlock = new optBlock($found[2], $found);
									$current -> addItem($currentBlock, $this -> tpl);
									$current = $current -> getParent();
									if(!is_object($current))
									{
										$this -> tpl -> error(E_USER_ERROR, 'Unexpected enclosing statement: "'.$found[2].'".', OPT_E_ENCLOSING_STATEMENT);
									}
									$currentBlock = $current -> restoreBlock();
								}
								else
								{
									$current -> storeBlock($currentBlock);
									$current = new optNode($realname, OPT_COMPONENT, $current);
									$currentBlock -> addNode($current);
									$currentBlock = new optBlock($realname, $found);
									$current -> addItem($currentBlock, $this -> tpl);
								}
							}
							# /COMPONENTS
							else
							{
								// here comes the undefined command. The instruction programmer may do with them whatever he wants
								// the compiler is going to recognize, what sort of command is it. But first - a small check. The
								// command must belong to the "opt" namespace. Otherwise it will be simply displayed.
								if($sortMatches[3] == 'opt' || in_array($sortMatches[3], $this->tpl->namespaces))
								{
									$ending = substr($found[2], strlen($found[2]) - 4, 4);
									if($sortMatches[0] == '/')
									{
										// ending command, like in XML: /command
										$currentBlock = new optBlock($found[2], $found, OPT_ENDER);
										$current -> addItem($currentBlock, $this -> tpl);
										$current = $current -> getParent();
										if(!($current instanceof ioptNode))
										{
											$this -> tpl -> error(E_USER_ERROR, 'Unexpected enclosing statement: "'.$found[2].'"!', OPT_E_ENCLOSING_STATEMENT);
										}
										$currentBlock = $current -> restoreBlock();
									}
									elseif($sortMatches[2] == '/')
									{
										// standalone command, like XML: command/ 
										$node = new optNode($found[2], OPT_UNKNOWN, $current);
										$node -> addItem(new optBlock($found[2], $found, OPT_COMMAND), $this -> tpl);
										$currentBlock -> addNode($node);
									}
									elseif($ending == 'else')
									{
										// alternative command, doesn't exist in XML: commandelse
										$currentBlock = new optBlock($found[2], $found, OPT_ALT);
										$current -> addItem($currentBlock, $this -> tpl);
									}
									else
									{
										// beginning command: command
										$current -> storeBlock($currentBlock);
										$current = new optNode($realname, OPT_UNKNOWN, $current);
										$currentBlock -> addNode($current);
										$currentBlock = new optBlock($realname, $found, OPT_MASTER);
										$current -> addItem($currentBlock, $this -> tpl);
									}
								}
								else
								{
									// Display the undefined command that doesn't belong to the "opt" namespace
									if($textAssign == 0)
									{
										$text = new optTextNode(NULL, OPT_TEXT, $current);
										$currentBlock -> addNode($text);
									}
									$text -> addItem($item);
									$textAssign = 1;
								}
							}
						}
						else
						{
							// we have an expression
							$node = new optNode(NULL, OPT_EXPRESSION, $current);
							$node -> addItem(new optBlock(NULL, $sortMatches[1]));
							$currentBlock -> addNode($node);
						}
					}
					// No tokens left, copy just the rest of the $bval
					if($textAssign == 0)
					{
						$text = new optTextNode(NULL, OPT_TEXT, $current);
						$currentBlock -> addNode($text);
					}
					$text -> addItem(substr($bval, $offset, strlen($bval) - $offset));
					$textAssign = 1;
				}
			}
			
			if($current -> getType() != OPT_ROOT)
			{
				$this -> tpl -> error(E_USER_ERROR, 'Unexpected enclosing statement: "NULL"!', OPT_E_ENCLOSING_STATEMENT);
			}
			
			// execute the tree
			$this -> processors['generic'] -> nodeProcess($root);
			$this -> output = str_replace('?><'.'?php', '', $this -> output);
			if(!$master)
			{
				// apply postfilters
				foreach($this -> tpl -> filters['post'] as $name)
				{
					$this -> output = $name($this -> tpl, $this -> output);
				}
				
				if(!is_null($filename))
				{
					if(!is_writeable($this -> tpl -> compile))
					{
						$this -> tpl -> error(E_USER_ERROR, $this->tpl->compile.' is not a writeable directory.', OPT_E_WRITEABLE);
					}
					file_put_contents($filename, $this -> output);
					if($this -> dynamicEnabled)
					{
						file_put_contents($filename.'.dyn', serialize($this -> dynamicSeg));			
					}
				}
				return $this -> output;
			}
			return true;
		} // end parse();

		public function debugBlockProcess(optBlock $block)
		{
			if($block -> hasChildNodes())
			{
				foreach($block as $node)
				{
					$this -> debugNodeProcess($node);
				}
			}
		} // end debugBlockProcess();
		
		public function debugNodeProcess(ioptNode $node)
		{
			_e('<ul>');
			switch($node -> getType())
			{
				case OPT_ROOT:
					_e('<li>Root');
					$this -> debugBlockProcess($node -> getFirstBlock());
					_e('</li>');
					break;
				case OPT_TEXT:
					_e('<li>Text</li>');
					break;
				case OPT_EXPRESSION:
					_e('<li>Expression: '.$node->getFirstBlock()->getAttributes().'</li>');
					break;
				case OPT_ATTRIBUTE:
					_e('<li>Attribute: '.$node->getName().'('.$node->getFirstBlock()->getAttributes().')</li>');
					break;
				case OPT_INSTRUCTION:
					_e('<li>Instruction: '.$node->getName());
					foreach($node as $block)
					{
						$this -> debugBlockProcess($block);
					}					
					_e('</li>');
					break;
				case OPT_COMPONENT:
					_e('<li>Component: '.$node->getName());
					foreach($node as $block)
					{
						$this -> debugBlockProcess($block);
					}					
					_e('</li>');
					break;
				case OPT_UNKNOWN:
					_e('<li>Unknown: '.$node->getName());
					foreach($node as $block)
					{
						$this -> debugBlockProcess($block);
					}					
					_e('</li>');
					break;
			}
			_e('</ul>');
		} // end debugNodeProcess();

		public function compileExpression($expr, $allowAssignment=0)
		{
			preg_match_all('/(?:'.
	       			$this->rDoubleQuoteString.'|'.
	       			$this->rSingleQuoteString.'|'.
	       			$this->rReversedQuoteString.'|'.
					$this->rHexadecimalNumber.'|'.
					$this->rDecimalNumber.'|'.
					$this->rLanguageBlock.'|'.
					$this->rVariableBlock.'|'.
					$this->rOperators.'|'.
					$this->rIdentifier.'|'.
					$this->rConfiguration.')/x', $expr, $match);
			
			$tokens = &$match[0];

			$wordOperators = array(
				'eq' => '==',
				'ne' => '!=',
				'neq' => '!=',
				'lt' => '<',
				'le' => '<=',
				'lte' => '<=',
				'gt' => '>',
				'ge' => '>=',
				'gte' => '>=',
				'and' => '&&',
				'or' => '||',
				'xor' => 'xor',
				'not' => '!'
			);
			
			$wordNumericOperators = array(
				'mod' => '%',
				'div' => '/',
				'add' => '+',
				'sub' => '-',
				'mul' => '*'
			);
			
			$state = array(
				// square parenthesis counters
				'parenthesis' => 0,
				// previous token type
				'prev' => OPCODE_NULL,
				'prevToken' => '',
				'apply' => 0,	
				// assignment control
				'first' => 1,
				'assigned' => 0
			);
			// parenthesis stack
			$phs = array();
			$pi = 0;
			// parenthesis stack
			$bhs = array();
			$bi = 0;

			foreach($tokens as $i => &$token)
			{
				$storedToken = $token;
				if($token == ' ')
				{
					if($state['prevToken'] == ' ')
					{
						unset($tokens[$i]);
					}
					continue;
				}
				if($token == '')
				{
					unset($tokens[$i]);
					continue;
				}
				$token = trim($token);
				switch($token)
				{
					case '!':
						if($state['prev'] == OPCODE_OBJECT_CALL)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case '!==':
					case '==':
					case '===':
					case '>':
					case '<':
					case '!=':
					case '<>':
					case '<<':
					case '>>':
					case '<=':
					case '>=':
					case '=>':
					case '&&':
					case '||':
						if($state['prev'] == OPCODE_OPERATOR || $state['prev'] == OPCODE_OBJECT_CALL || $state['prev'] == OPCODE_NULL)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case '::':
						if($state['prev'] == OPCODE_OPERATOR || $state['prev'] == OPCODE_OBJECT_CALL || $state['prev'] == OPCODE_NULL)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						$token = '.';
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;			
					case '|':
					case '^':
					case '&':
					case '~':
					case '+':
					case '*':
					case '/':
					case '%':
					case 'xor':
						if($state['prev'] == OPCODE_OPERATOR || $state['prev'] == OPCODE_NULL || $state['prev'] == OPCODE_OBJECT_CALL || $state['prev'] == OPCODE_STRING)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case '++':
					case '--':
						if($state['prev'] != OPCODE_VARIABLE)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);						
						}
						break;
					case '-':
						// signed values support, less restrictions
						if($state['prev'] == OPCODE_OBJECT_CALL || $state['prev'] == OPCODE_STRING)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case ',':
						$pi--;
						if(@($phs[$pi] != OPCODE_METHOD && $phs[$pi] != OPCODE_FUNCTION && $phs[$pi] != OPCODE_APPLY))
						{
							$this -> expressionError('OPCODE_SEPARATOR', $token, $expr);
						}
						$pi++;
						$state['prev'] = OPCODE_SEPARATOR;
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case 'true':
					case 'false':
					case 'null':
						if($state['prev'] == OPCODE_OPERATOR || $state['prev'] == OPCODE_ASSIGN || $state['prev'] == OPCODE_PARENTHESIS || $state['prev'] == OPCODE_BRACKET || $state['prev'] == OPCODE_NULL || $state['prev'] == OPCODE_SEPARATOR)
						{
							$state['prev'] = OPCODE_NUMBER;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						else
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						break;
					case 'eq':
					case 'ne':
					case 'neq':
					case 'lt':
					case 'le':
					case 'lte':
					case 'gt':
					case 'ge':
					case 'gte':
					case 'and':
					case 'or':
						if($state['prev'] == OPCODE_OBJECT_CALL && $token != 'and' && $token != 'or')
						{
							$state['prev'] = OPCODE_VARIABLE;
							break;
						}
						if($state['prev'] == OPCODE_OPERATOR || $state['prev'] == OPCODE_PARENTHESIS || $state['prev'] == OPCODE_OBJECT_CALL || $state['prev'] == OPCODE_NULL)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						$token = $wordOperators[$token];
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case 'not':
						// parenthesis control
						if($state['prev'] == OPCODE_OBJECT_CALL)
						{
							$this -> expressionError('OPCODE_OPERATOR', $token, $expr);
						}
						$state['prev'] = OPCODE_OPERATOR;
						$token = $wordOperators[$token];
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case 'mod':
					case 'div':					
					case 'add':						
					case 'sub':	
					case 'mul':
						if($state['prev'] == OPCODE_OBJECT_CALL)
						{
							// this is a part of an object call...
							$state['prev'] = OPCODE_VARIABLE;
						}
						elseif($state['prev'] == OPCODE_NUMBER || $state['prev'] == OPCODE_VARIABLE || $state['prev'] == OPCODE_PARENTHESIS || $state['prev'] == OPCODE_METHOD || $state['prev'] == OPCODE_FUNCTION)
						{
							$token = $wordNumericOperators[$token];
							$state['prev'] = OPCODE_OPERATOR;
						}
						else
						{
							$token = $this -> compileString($token);
							$state['prev'] = OPCODE_STRING;
						}
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case '(':
						// store the previous state in order to know, what we open it for.
						if($state['prev'] == OPCODE_NUMBER || $state['prev'] == OPCODE_STRING || $state['prev'] == OPCODE_LANGUAGE || $state['prev'] == OPCODE_CONFIG || $state['prev'] == OPCODE_VARIABLE)
						{
							$this -> expressionError('OPCODE_PARENTHESIS', $token, $expr);
						}

						if($state['prev'] == OPCODE_FUNCTION || $state['prev'] == OPCODE_APPLY)
						{
							// this token has been already added, skip
							$token = '';
							$phs[$pi] = $state['prev'];
						}
						elseif($state['prev'] == OPCODE_METHOD)
						{
							$phs[$pi] = OPCODE_METHOD;
						}
						else
						{
							$phs[$pi] = OPCODE_PARENTHESIS;
						}
						$state['prev'] = OPCODE_PARENTHESIS;
						$pi++;
						if($bi == 0)
						{
							$state['first'] = 0;
						}
						break;
					case ')':
						$pi--;
						if($pi < 0)
						{
							$this -> expressionError('OPCODE_PARENTHESIS', $token, $expr);
						}
						$state['prev'] = $phs[$pi];
						break;
					case '[':
						
						// store the previous state in order to know, what we open it for.
						if($state['prev'] != OPCODE_VARIABLE && $state['prev'] != OPCODE_BRACKET)
						{
							$this -> expressionError('OPCODE_BRACKET', $token, $expr);
						}
						$bhs[$bi] = OPCODE_VARIABLE;
						$state['prev'] = OPCODE_BRACKET;
						$bi++;
						break;
					case ']':
						$bi--;
						if($bi < 0)
						{
							$this -> expressionError('OPCODE_BRACKET', $token, $expr);
						}
						$state['prev'] = $bhs[$bi];
						break;
					case '->':
						if($state['prev'] == OPCODE_VARIABLE || $state['prev'] == OPCODE_METHOD || $state['prev'] == OPCODE_FUNCTION)
						{
							$state['prev'] = OPCODE_OBJECT_CALL;
							break;
						}
						$this -> expressionError('OPCODE_OBJECT_CALL', $token, $expr);		
						break;
					case '=':
					case 'is':
						if($allowAssignment == 1)
						{
							if($bi == 0 && $state['first'] == 1)
							{
								$token = '=';
								$state['prev'] = OPCODE_ASSIGN;
								$state['assigned'] = 1;
							}
							else
							{
								$this -> expressionError('OPCODE_ASSIGN', $token, $expr);
							}
							break;				
						}
					default:
						if(preg_match('/^'.$this->rLanguageBlock.'$/', $token))
						{
							$token = $this -> compileLanguageBlock($token, $state['prev'], @($phs[$pi-1]));
							$state['prev'] = OPCODE_LANGUAGE;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif(preg_match('/^'.$this->rVariableBlock.'$/', $token))
						{
							$token = $this -> compileBlock($token);
							$state['prev'] = OPCODE_VARIABLE;
							$state['first'] == $state['first'] && 1;
						}
						elseif(preg_match('/^'.$this->rConfiguration.'$/', $token))
						{
							$token = $this -> compileConfiguration($token);
							$state['prev'] = OPCODE_VARIABLE;
							$state['first'] == $state['first'] && 1;
						}
						elseif(preg_match('/^'.$this->rDecimalNumber.'$/', $token))
						{
							$state['prev'] = OPCODE_NUMBER;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif(preg_match('/^'.$this->rHexadecimalNumber.'$/', $token))
						{
							$state['prev'] = OPCODE_NUMBER;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif(preg_match('/^'.$this->rDoubleQuoteString.'$/', $token))
						{
							$token = $this -> compileString($token);
							$state['prev'] = OPCODE_STRING;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif(preg_match('/^'.$this->rSingleQuoteString.'$/', $token))
						{
							$token = $this -> compileString($token);
							$state['prev'] = OPCODE_STRING;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif(preg_match('/^'.$this->rReversedQuoteString.'$/', $token))
						{
							$token = $this -> compileString($token);
							$state['prev'] = OPCODE_STRING;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif($tokens[$i+1] == '(')
						{
							if($state['prev'] == OPCODE_OBJECT_CALL)
							{
								$state['prev'] = OPCODE_METHOD;
							}
							elseif($state['prev'] == OPCODE_FUNCTION || $state['prev'] == OPCODE_METHOD || $state['prev'] == OPCODE_OPERATOR ||
								$state['prev'] == OPCODE_PARENTHESIS || $state['prev'] == OPCODE_NULL || $state['prev'] == OPCODE_ASSIGN ||
								$state['prev'] == OPCODE_SEPARATOR)
							{
								if($token == 'apply')
								{
									$state['prev'] = OPCODE_APPLY;
								}
								else
								{
									$state['prev'] = OPCODE_FUNCTION;
								}
								$token = $this -> compileFunction($token, $tokens[$i+2]);								
							}
							else
							{
								$this -> expressionError('OPCODE_FUNCTION', $token, $expr);
							}
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif($state['prev'] == OPCODE_NULL || $state['prev'] == OPCODE_OPERATOR || $state['prev'] == OPCODE_PARENTHESIS || $state['prev'] == OPCODE_BRACKET || $state['prev'] == OPCODE_ASSIGN)
						{
							$token = $this -> compileString($token);
							$state['prev'] = OPCODE_STRING;
							if($bi == 0)
							{
								$state['first'] = 0;
							}
						}
						elseif($state['prev'] == OPCODE_OBJECT_CALL)
						{
							$state['prev'] = OPCODE_VARIABLE;
						}
						else
						{
							$this -> expressionError('OPCODE_UNKNOWN', $token, $expr);
						}
				}
				$state['prevToken'] = $storedToken;
			}
			if($pi > 0)
			{
				$this -> expressionError('OPCODE_PARENTHESIS', $token, $expr);
			}
			
			if($bi > 0)
			{
				$this -> expressionError('OPCODE_BRACKET', $token, $expr);
			}
			if($allowAssignment == 0)
			{
				return implode('', $tokens);
			}
			return array(implode('', $tokens), $state['assigned']);
		} // end compileExpression();

		private function compileBlock($name)
		{
			$value = substr($name, 1, strlen($name) - 1);
			$result = '';
			
			if(strpos($value, '.') !== FALSE)
			{
				$ns = explode('.', $value);
			}
			else
			{
				$ns = array(0 => $value);
			}
			
			if($name{0} == '@')
			{
				$result = '$this->vars';
			}
			else
			{
				// $opt match
				if($ns[0] == 'opt')
				{
					return $this -> compileOpt($ns);
				}
				// section match
				if(isset($this -> processors['section']))
				{
					$cnt = sizeof($ns);
					if($cnt >= 2)
					{
						$ns[$cnt-2] = $this -> getConverterItem($ns[$cnt-2]);
						if(in_array($ns[0], $this -> processors['section'] -> sectionList))
						{
							return '$__'.$ns[$cnt-2].'_val[\''.$ns[$cnt-1].'\']';
						}
					}
					else
					{
						if(in_array($ns[0], $this -> processors['section'] -> sectionList))
						{
							return '$__'.$ns[0].'_val';
						}
					}
				}
				$result = '$this->data';
			}
			
			foreach($ns as $item)
			{
				if(ctype_digit($item))
				{
					$result .= '['.$item.']';
				}
				else
				{
					$result .= '[\''.$item.'\']';
				}
			}
			return $result;
		} // end compileBlock();
		
		private function compileLanguageBlock($block, $state, $heap)
		{
			$ns = explode('@', ltrim($block, '$'));
			if($this -> tpl -> showWarnings == 1)
			{
				if($this -> tpl -> i18nType == 0 && !isset($this -> tpl -> i18n[$ns[0]][$ns[1]]))
				{
					$this -> tpl -> error(E_USER_WARNING, 'The language block "$'.$ns[0].'@'.$ns[1].'" does not exist.', OPT_W_LANG_NOT_FOUND);
				}
			}
			if($state == OPCODE_PARENTHESIS && $heap == OPCODE_APPLY)
			{
				return '\''.$ns[0].'\',\''.$ns[1].'\'';
			}
			else
			{
				if($this -> tpl -> i18nType == 1)
				{
					return '$this->i18n->put(\''.$ns[0].'\', \''.$ns[1].'\')';									
				}
				else
				{
					return '$this->i18n[\''.$ns[0].'\'][\''.$ns[1].'\']';
				}
			}
		} // end compileLanguageBlock();

		private function compileConfiguration($block)
		{
			if($this -> tpl -> useConfigBlocks)
			{
				$name = ltrim($block, '#');
				if(in_array($name, $this -> tpl -> configDirectives))
				{
					return '$this -> '.ltrim($block, '#');
				}
				else
				{
					$this -> tpl -> error(E_USER_ERROR, 'Unknown configuration block: '.$name, OPT_E_UNKNOWN_CONFIG);
				}
			}
			$this -> tpl -> error(E_USER_ERROR, 'The access to the configuration blocks is restricted.', OPT_E_ACCESS_RESTRICTED);
		} // end compileConfiguration();

		private function compileString($str)
		{
			switch($str[0])
			{
				case '\'':
					return $str;
				case '"':
					return '"'.str_replace('"', '\\"', stripslashes(substr($str, 1, strlen($str) - 2))).'"';
				case '`':
					return '\''.str_replace('\'', '\\\'', stripslashes(substr($str, 1, strlen($str) - 2))).'\'';
					
				default:
					return '\''.$str.'\'';
			}
		} // end compileString();
		
		private function compileFunction($function, $nextToken)
		{
			if($function == 'apply')
			{
				if($this -> tpl -> i18nType == 1)
				{
					return '$this->i18n->apply(';
				}
				else
				{
					return 'optPredefApply($this,';
				}
			}
			elseif(isset($this -> tpl -> functions[$function]))
			{
				return 'opt'.$this -> tpl -> functions[$function].'($this'.($nextToken != ')' ? ',' : '');	
			}
			elseif(isset($this -> tpl -> phpFunctions[$function]))
			{
				return $this -> tpl -> phpFunctions[$function].'(';
			}
			$this -> tpl -> error(E_USER_ERROR, 'Odwołanie do nieistniejącej funkcji "'.$function.'"', OPT_E_FUNCTION_NOT_FOUND);
		} // end compileString();

		private function compileOpt($namespace)
		{
			switch($namespace[1])
			{
				case 'get':
				case 'post':
				case 'cookie':
				case 'session':
				case 'server':
				case 'env':
				case 'request':
					if($this -> tpl -> useEnvironment)
					{
						return '$_'.strtoupper($namespace[1]).'[\''.$namespace[2].'\']';
					}
					$this -> tpl -> error(E_USER_ERROR, 'The access to the environment variables is restricted.', OPT_E_ACCESS_RESTRICTED);
					break;
				case 'now':
					return 'time()';
				case 'const':
					if(defined($namespace[2]))
					{
						return $namespace[2];
					}
					else
					{
						$this -> tpl -> error(E_USER_ERROR, 'Unknown constant: "'.$namespace[2].'"', OPT_E_CONSTANT_NOT_FOUND);
					}
				case 'version':
					return 'OPT_VERSION';
				default:
					if(isset($this -> processors[$namespace[1]]))
					{
						return $this -> processors[$namespace[1]] -> processOpt($namespace);
					}
					$this -> tpl -> error(E_USER_ERROR, 'Unknown OPT command: "'.$namespace[1].'"', OPT_E_COMMAND_NOT_FOUND);	
			}
		} // end compileOpt();
		
		private function expressionError($tokenType, $token, $expression)
		{
			$this -> tpl -> error(E_USER_ERROR, 'Unexpected token: '.$tokenType.' ('.$token.') in expression '.$expression, OPT_E_EXPRESSION);
		} // end expressionError();
		
		private function parseEntities($code)
		{
			if($this -> tpl -> xmlsyntaxMode == 1 || $this -> tpl -> entities == 1)
			{
				return str_replace(array(
					'&amp;', '&quot;', '&lt;', '&gt;', '&lb;', '&rb;', '&apos;'				
					),array(
					'&', '"', '<', '>', '{', '}', '\''				
					),
					$code);
			}
			return $code;
		} // end parseEntities();
		
		/*
		 * INSTRUCTION WRITING TOOLS
		 */

		public function out($code, $static = false)
		{
			if($this -> master == false)
			{
				// The normal compilation mode - both static and dynamic output enabled
				if($static)
				{
					$this -> output .= $code;
					if($this -> dynamic)
					{
						$this -> dynamicSeg[$this->di] .= $code;	
					}
					return;
				}
				$this -> output .= '<'.'?php '.$code.' ?'.'>';
				if($this -> dynamic)
				{
					$this -> dynamicSeg[$this->di] .= '<'.'?php '.$code.' ?'.'>';
				}
			}
			else
			{
				// Master page - only dynamic output available
				if(!$static)
				{
					$this -> output .= ' '.$code.' ';
					if($this -> dynamic)
					{
						$this -> dynamicSeg[$this->di] .= ' '.$code.' ';
					}
				}
			}
		} // end out();

		public function dynamic($state)
		{
			if($state == true)
			{
				$this -> out(' if($this->startCache){ $dynamic = true; $this -> outputBuffer[] = ob_get_contents(); } ');
			}		
		
			$this -> dynamic = $state;
			$this -> dynamicEnabled = true;
			if($this -> dynamic == false)
			{
				
				$this -> di++; // dynamic segment iterator
				$this -> dynamicSeg[$this->di] = '';
				
				$this -> out(' if($this->startCache){ ob_start(); } ');
			}
		} // end dynamic();

		public function getConverterItem($item)
		{
			if(!isset($this -> converter[$item]))
			{
				return $item;
			}
			return $this -> converter[$item];
		} // end getConverterItem();

		public function addConverterItem($item, $dest)
		{
			$this -> converter[$item] = $dest;
		} // end addConverterItem();

		public function removeConverterItem($item)
		{
			unset($this -> converter[$item]);
		} // end removeConverterItem();

		public function parametrize($instruction, $matches, &$config, $style = OPT_STYLE_BOTH)
		{
			if(!isset($matches[4]))
			{
				$matches[4] = '';
				$matches[3] = '=';
			}
			$matches[3] = trim($matches[3]);
			$unknown = array();
			
			if(count($config) == 0)
			{
				// no parameters passed. Now the script wonders, why someone has called this method.
				$config = array();
				return $unknown;
			}

			if($matches[3]{0} == '=')
			{
				if($style == OPT_STYLE_XML)
				{
					$this -> parametrizeError($instruction, 7, 'OPT_STYLE_OPT');
				}
			
				// use non-named parameter parsing
				$params = array();
				if($matches[3] != '=')
				{
					// split the param string into parameters
					preg_match_all('/(?:'.
						$this -> rDoubleQuoteString.'|'.
						$this -> rSingleQuoteString.'|'.
						$this -> rReversedQuoteString.'|;|[^"\'`;]*)/mx', $matches[4], $found);
					$params = array(0 => '');
					$i = 0;
					foreach($found[0] as $item)
					{
						if($item == ';')
						{
							$i++;
							$params[$i] = '';
						}
						else
						{
							$params[$i] .= trim($item);
						}
					}
				}
				// All parameters are optional
				$first = reset($config);
				if(count($params) == 0 && $first[0] == OPT_PARAM_OPTIONAL)
				{
					foreach($config as $name => $par)
					{
						$config[$name] = $par[2];
					}
					return $unknown;
				}

				$pi = 0;
				$optional = 0;
				// process everything
				foreach($config as $name => $par)
				{
					if($par[0] == OPT_PARAM_OPTIONAL)
					{
						$optional = 1;
					}
					
					if($name == '__UNKNOWN__')
					{
						$iterator = 0;
						while(isset($params[$pi]))
						{
							$unknown[$iterator] = $this -> paramTest($instruction, $pi, $par[1], $params[$pi]);
							$iterator++;
							$pi++;
						}
					}
					else
					{
						if(!isset($params[$pi]))
						{
							// parameter not set
							if($optional == 1)
							{
								// pass the default value
								$config[$name] = $par[2];				
							}
							else
							{
								$this -> parametrizeError($instruction, 1, $pi);
							}		
						}
						else
						{
							if(trim($params[$pi]) == '!x')
							{
								if($optional == 0)
								{
									$this -> parametrizeError($instruction, 4, $pi);						
								}
								// force the default value
								$config[$name] = $par[2];
								$pi++;
								continue;
							}
							$config[$name] = $this -> paramTest($instruction, $pi, $par[1], $params[$pi]);
						}
						$pi++;
					}
				}
				// End of parsing
			}
			else
			{
				if($style == OPT_STYLE_OPT)
				{
					$this -> parametrizeError($instruction, 7, 'OPT_STYLE_XML');
				}
				// use named parameters
				preg_match_all('#([a-zA-Z0-9\_]+)\s*\=\s*"((.*?)[^\\\\])"#ms', $matches[4], $found);

				// Parse all matches
				foreach($found[1] as $id => $name)
				{
					$found[2][$id] = str_replace(array(
						'\\"',
						'\\\\"'
					),
					array(
						'"',
						'\\"'
						), $found[2][$id]
					);
				
					if(isset($config[$name]))
					{
						$config[$name] = $this -> paramTest($instruction, $name, $config[$name][1], $found[2][$id]);
					}
					elseif(isset($config['__UNKNOWN__']) && $name != '__UNKNOWN__')
					{
						$unknown[$name] = $this -> paramTest($instruction, $name, $config['__UNKNOWN__'][1], $found[2][$id]);					
					}
					elseif($name == '__UNKNOWN__')
					{
						$this -> parametrizeError($instruction, 6, $name);
					}
					else
					{
						$config[$name] = $found[2][$id];
					}
				}
				// Optional parameters parsing
				foreach($config as $name => &$value)
				{
					if($name == '__UNKNOWN__')
					{
						continue;
					}	
					if(is_array($value))
					{
						if($value[0] == OPT_PARAM_OPTIONAL)
						{
							$value = $value[2];
						}
						else
						{
							$this -> parametrizeError($instruction, 1, $name);
						}
					}	
				}
			}
			return $unknown;
		} // end parametrize();

		private function paramTest($instrName, $name, $type, $value)
		{
			// Type checking		
			switch($type)
			{
				case OPT_PARAM_ID:
					if(preg_match('/[a-zA-Z\_]?[a-zA-Z0-9\_]+/', $value))
					{
						return $value;
					}
					$this -> parametrizeError($instrName, 3, $name);
					break;
				case OPT_PARAM_EXPRESSION:
					return $this -> compileExpression($value);
				case OPT_PARAM_ASSIGN_EXPR:
					$ret = $this -> compileExpression($value, true);
					return $ret[0];
				case OPT_PARAM_STRING:
					return $value;
				case OPT_PARAM_NUMBER:
					if(preg_match('/(0[xX][0-9a-fA-F]+)|([0-9]+(\.[0-9]+)?)/', $value))
					{
						return $value;
					}
					$this -> parametrizeError($instrName, 3, $name);
					break;
				case OPT_PARAM_VARIABLE:
					if(preg_match('/\@([a-zA-Z0-9\_]+)/', $value, $got))
					{
						return '$this -> vars[\''.$got[1].'\']';
					}
					else
					{
						$this -> parametrizeError($instrName, 3, $name);
					}
					break;
				default:
					// Skip it
					return '';
			}
		} // end paramTest();

		public function parametrizeError($name, $code, $number = NULL)
		{
			switch($code)
			{
				case 1: 
					$this -> tpl -> error(E_USER_ERROR, 'Required parameter "'.$number.'" not specified in "'.$name.'" instruction.', OPT_E_REQUIRED_NOT_FOUND);
				case 3:
					$this -> tpl -> error(E_USER_ERROR, 'Invalid parameter #'.$number.' in "'.$name.'" instruction.', OPT_E_INVALID_PARAMETER);
				case 4:
					$this -> tpl -> error(E_USER_ERROR, 'Cannot use !x marker for a required parameter in "'.$name.'" instruction.', OPT_E_DEFAULT_MARKER);
				case 6:
					$this -> tpl -> error(E_USER_ERROR, '__UNKNOWN__ is a reserved parameter name in "'.$name.'" instruction.', OPT_E_UNKNOWN_PARAM);
				case 7:
					$this -> tpl -> error(E_USER_ERROR, 'The "'.$name.'" instruction requires '.$number.' style parameters.', OPT_E_PARAM_STYLE);			
			}
		} // end parametrizeError();
	}
?>
