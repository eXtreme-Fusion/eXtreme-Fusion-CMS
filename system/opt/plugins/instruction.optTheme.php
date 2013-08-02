<?php
if ( ! function_exists('optTheme'))
{
	class optTheme extends optInstruction {

		public function configure()
		{
			return array(
				0        => 'theme',
				'panel'  => OPT_MASTER,
				'/panel' => OPT_ENDER
			);
		}

		public function instructionNodeProcess(ioptNode $node)
		{
			foreach($node as $block)
			{
				switch($block->getName())
				{
					case 'panel':
						$this->themeBegin($block->getAttributes());
						$this->defaultTreeProcess($block);
					break;
					case '/panel':
						$this->themeEnd();
					break;
				}
			}
		}

		protected function themeBegin($attributes)
		{
			$params = array(
				'name' => array(OPT_PARAM_REQUIRED, OPT_PARAM_EXPRESSION),
			);

			$this->compiler->parametrize('panel', $attributes, $params);

			$this->compiler->out('$this->middlePanel('.$params['name'].');');
		}

		protected function themeEnd()
		{
			$this->compiler->out('$this->middlePanel();');
		}

	}
}