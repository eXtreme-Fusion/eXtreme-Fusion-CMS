<?php

/**
 * Klsa bazowa dla kontrolerów podstron
 * korzystających ze standardowej tablicy parametrów $_GET.
 */
abstract class Admin_Controller
{
	protected $_oRequest;

	protected $_aParams;
	protected $_sAction;

	public function __construct(Request $oRequest)
	{
		$this->_oRequest = $oRequest;

		$this->_setParams();
		$this->_runAction();
	}

	public function getParam($key, $default = null)
	{
		if (isset($this->_aParams[$key]))
		{
			return $this->_aParams[$key];
		}

		return $default;
	}

	protected function _setParams()
	{
		$this->_aParams = $this->_oRequest->get();
		$this->_sAction = array_shift($this->_aParams).'Action';

		if (is_numeric($this->_sAction[0]))
		{
			throw new LogicException('Action name can not begin with numeric character.');
		}

		if (empty($this->_sAction))
		{
			$this->_sAction = 'index';
		}
	}

	protected function _runAction()
	{
		if (method_exists($this, $this->_sAction))
		{
			$sAction = $this->_sAction;

			$this->$sAction();
		}
	}
}
