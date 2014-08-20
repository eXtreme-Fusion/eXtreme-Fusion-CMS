<?php defined('EF5_SYSTEM') || exit;

class Move_Controller extends Forum_Controller {

	public function index()
	{
		$category = $this
			->model('move')
			->findByID($this->params[0]);

		if ( ! $category)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}
		
		if ($category['url'] == NULL)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 403));
		}
		
		$_visit = $this->pdo->exec('UPDATE [board_categories] SET `visits`=`visits`+1 WHERE `id` = :id', array(':id', $this->params[0], PDO::PARAM_INT));
		
		$this->ec->header->set('	<meta http-equiv="refresh" content="5; url='.$category['url'].'" />');

		return $this->view('move', array(
			'category' => $category
		));
	}

}