<?php


class CommentPageNav
{
	protected $_tpl;
	protected $ec;

	protected $current_page;
	protected $count;
	protected $limit;
	protected $item_id;
	protected $buttons_count;
	protected $filename;

	public function __construct($ec, $_pdo, $_tpl)
	{
		$this->_tpl = $_tpl;
		$this->_pdo = $_pdo;
		$this->ec = $ec;
	}

	public function create($item_id, $current_page, $limit, $buttons_count, $filename)
	{
		$i = 0;
		foreach(func_get_args() as $arg)
		{
			if ($i === 1)
			{
				continue;
			}

			if ($i === 4)
			{
				break;
			}

			if (!is_numeric($arg) || !$arg)
			{
				throw new systemException('Paramter metody CommentPageNav::create() nie jest numeryczny lub jest równy 0!');
			}

			$i++;
		}

		$this->count = $this->_pdo->getSelectCount('SELECT Count(`id`) FROM [comments] WHERE `content_type` = :filename AND `content_id` = :item_id', array(
			array(':filename', $filename, PDO::PARAM_STR),
			array(':item_id', intval($item_id), PDO::PARAM_INT)
		));

		$this->limit = intval($limit);
		$this->buttons_count = intval($buttons_count);
		$this->filename = $filename;
		$this->current_page = $current_page;
		$this->item_id = intval($item_id);

		$this->parse();
	}

	public function parse()
	{
		if ($this->count)
		{
			$this->ec->paging->setPagesCount($this->count, $this->current_page, $this->ec->comment->getLimit());

			$data = $this->ec->pageNav->create($this->_tpl, $this->buttons_count);

			$this->ec->pageNav->get($data, 'page_nav');
		}

		$this->_tpl->assign('comments', $this->ec->comment->get($this->filename, $this->item_id, $this->current_page, $this->limit));
	}
}