<?php

class Module_Polls
{
	protected $_pdo;
	
	protected $data;
	protected $cached = FALSE;
	
	protected $cache_active = array(), $cache_inactive = array();
	protected $cache_voted_IDs;
	
	public function __construct($_pdo, $_user)
	{
		$this->_pdo = $_pdo;
		$this->_user = $_user;
	}
	
	public function setLocalData()
	{
		$this->data = $this->_pdo->getData('SELECT * FROM [polls] ORDER BY `id` DESC');
	}
	
	protected function explodeData()
	{
		foreach($this->data as $row)
		{
			if ($row['date_end'] === '0')
			{
				$this->cache_active[] = $row;
			}
			else
			{
				$this->cache_inactive[] = $row;
			}
		}
		
		$this->cached = TRUE;
	}
	
	// ID ankiet, w których g³osowa³ zalogowany u¿ytkownik
	public function getVotedIDs()
	{
		if (!$this->cache_voted_IDs)
		{
			$this->cache_voted_IDs = $this->_pdo->getIDs($this->_pdo->getData('SELECT `poll_id` FROM [polls_vote] WHERE `user_id` = '.isNum($this->_user->get('id'))), 'poll_id', FALSE);
		}
		
		return $this->cache_voted_IDs;
	}
	
	public function getActive()
	{
		if (!$this->cached)
		{
			$this->explodeData();
		}
		
		return $this->cache_active;
	}
	
	public function getInactive()
	{
		if (!$this->cached)
		{
			$this->explodeData();
		}
		
		return $this->cache_inactive;
	}
	
	
	protected function getNotVotedExploded($data)
	{
		$voted = $this->getVotedIDs();
		
		$ret = array();
		foreach($data as $row)
		{
			if (!in_array($row['id'], $voted))
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function getInactiveNotVoted()
	{
		return $this->getNotVotedExploded($this->getInactive());
	}
	
	public function getActiveNotVoted()
	{
		return $this->getNotVotedExploded($this->getActive());
	}
	
	public function getActiveVoted()
	{
		$ret = array();
		foreach($this->getVoted() as $row)
		{
			if ($row['date_end'] === '0')
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function getInactiveVoted()
	{
		$ret = array();
		foreach($this->getVoted() as $row)
		{
			if ($row['date_end'] !== '0')
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function getVoted()
	{
		$voted = $this->getVotedIDs();
		
		$ret = array();
		foreach($this->data as $row)
		{
			if (in_array($row['id'], $voted))
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function getNotVoted()
	{
		$voted = $this->getVotedIDs();
		
		$ret = array();
		foreach($this->data as $row)
		{
			if (!in_array($row, $voted))
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function getVotedActive()
	{
		$ret = array();
		foreach($this->getVoted() as $row)
		{
			if ($row['date_end'] === '0')
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function getVotedInactive()
	{
		$ret = array();
		foreach($this->getVoted() as $row)
		{
			if ($row['date_end'] !== '0')
			{
				$ret[] = $row;
			}
		}
		
		return $ret;
	}
	
	public function parseResponse(array $data)
	{
		foreach($data as $key => $row)
		{
			$data[$key]['response'] = unserialize($row['response']);
		}
		
		return $data;
	}
	
	public function getVotingData($source)
	{
		$data = $this->_pdo->getData('SELECT `poll_id`, `response` FROM [polls_vote] WHERE `poll_id` IN ('.$this->_pdo->getIDsQuery($source, 'id', FALSE).')');
	
		$ret = array();
		foreach($data as $row)
		{
			$ret[$row['poll_id']][] = $row;
		}
		
		return $ret;
	}
	
	public function getResponses($poll_id, $data)
	{
		$resp = array();
		if (isset($data[$poll_id]))
		{
			foreach($data[$poll_id] as $d)
			{
				if (isset($resp[$d['response']]))
				{
					$resp[$d['response']] += 1;
				}
				else
				{
					$resp[$d['response']] = 1;
				}
			}
			
			
		}
		
		return $resp;
	}
	
	public function parseResponses($data, $votes)
	{
		$resp = $this->getResponses($data['id'], $votes);
		
		$ret = array();
		foreach($data['response'] as $key => $val)
		{
			if (!isset($resp[$key]))
			{
				$resp[$key] = 0;
			}
			
			$ret[$key] = array(
				'name' => $val,
				'value' => $key,
				'count' => $resp[$key],
				'percent' => round($resp[$key]/count($votes[$data['id']])*100)
			);
		}

		return $ret;
	}
}