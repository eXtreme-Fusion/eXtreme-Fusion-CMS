<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/

class detectRobots
{
	protected $_robots = array();
	protected $_cache = array();
	protected $_user_agent = '';
	
	public function __construct()
	{
		$this->_robots = array(
			'name' => FALSE
		);
		
		$this->_user_agent = $_SERVER['HTTP_USER_AGENT'];
	}
	
	public function getRobots($var = FALSE, $version = FALSE)
	{
		if ($var)
		{
			return $this->detectNewRobots($var, $version);
		} 
		
		return $this->detectNewRobots($this->_user_agent, $version);
	}
	
	private function detectNewRobots($var, $version)
	{
		if(preg_match('/Googlebot\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Googlebot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/Googlebot\-Image\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Googlebot-Image'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/Gigabot\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Gigabot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/^W3C_Validator\/([0-9a-z\+\-\.]+)$/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'W3C_Validator'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/W3C_CSS_Validator_[a-z]+\/([0-9a-z\+\-\.]+)$/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'W3C CSS Validator'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/msnbot(-media|)\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'MSNBot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/psbot\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Psbot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/IRLbot\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'IRL crawler'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/Seekbot\/([0-9a-z\+\-\.]+).*/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Seekport Robot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/^(cfetch|voyager)\/([0-9a-z\+\-\.]+)$/s', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Voyager'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/BecomeBot\/([0-9a-z\+\-\.]+).*/si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'BecomeBot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/SnapBot\/([0-9a-z\+\-\.]+).*/si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'SnapBot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/Yeti\/([0-9a-z\+\-\.]+).*/si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Yeti'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/Yandex\/([0-9a-z\+\-\.]+).*/si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Yandex Crawler'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/spbot\/([0-9a-z\+\-\.]+); \+http:\/\/www\.seoprofiler\.com\//si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'SEOprofiler.com bot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/DotBot\/([0-9a-z\+\-\.]+); http:\/\/www\.dotnetdotcom\.org\//si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'DotBot'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/Twiceler-([0-9\.]+) http:\/\/www\.cuil[l]?\.com/si', $var, $this->_cache))
		{
			$this->_robots['name'] = 'Twiceler'.($version ? ' '.($this->_cache[1] ? $this->_cache[1] : '') : '');
		}
		elseif(preg_match('/^ia_archiver$/s', $var))
		{
			$this->_robots['name'] = 'Alexa';
		}
		elseif(preg_match('/Slurp.*inktomi/s', $var))
		{
			$this->_robots['name'] = 'Inktomi Slurp';
		}
		elseif(preg_match('/Yahoo!.*Slurp/s', $var))
		{
			$this->_robots['name'] = 'Yahoo! Slurp';
		}
		elseif(preg_match('/Ask Jeeves\/Teoma/s', $var))
		{
			$this->_robots['name'] = 'Ask.com';
		}
		elseif(preg_match('/^MSRBOT /s', $var))
		{
			$this->_robots['name'] = 'MSRBot';
		}
		
		return $this->_robots['name'];
	}
}
?>
